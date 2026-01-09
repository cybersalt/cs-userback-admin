<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Cs_UserbackAdmin
 *
 * @author      Cybersalt Consulting Ltd.
 * @copyright   Copyright (C) 2025-2026 Cybersalt Consulting Ltd. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 * @link        https://github.com/cybersalt/cs-userback-admin
 *
 * Injects the Userback feedback widget into Joomla Administrator and/or Frontend.
 * Supports user group restrictions and backend session detection for frontend display.
 *
 * Credits: Original development by Gurnoor Deol
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\User\User;

final class PlgSystemCs_userbackadmin extends CMSPlugin
{
    public function onBeforeCompileHead(): void
    {
        try {
            $app = Factory::getApplication();
            $isAdmin = $app->isClient('administrator');
            $isSite = $app->isClient('site');

            // Only process admin or site clients
            if (!$isAdmin && !$isSite) {
                return;
            }

            // Get document
            $doc = $app->getDocument();

            // Only inject if it's an HTML document
            if ($doc === null || $doc->getType() !== 'html') {
                return;
            }

            // Get Userback token
            $token = (string) $this->params->get('access_token', '');
            if ($token === '') {
                return;
            }

            // Get current user
            $user = $app->getIdentity();

            // Check backend display
            if ($isAdmin) {
                if (!$this->shouldShowInBackend($user)) {
                    return;
                }
            }

            // Check frontend display
            if ($isSite) {
                if (!$this->shouldShowOnFrontend($user)) {
                    return;
                }
            }

            // Add Userback script
            $script = <<<JS
                window.Userback = window.Userback || {};
                Userback.access_token = '{$token}';
                (function(d) {
                    var s = d.createElement('script'); s.async = true;
                    s.src = 'https://static.userback.io/widget/v1.js';
                    (d.head || d.body).appendChild(s);
                })(document);
            JS;

            $doc->addScriptDeclaration($script);

        } catch (\Throwable $e) {
            // Fail silently to prevent crash
        }
    }

    /**
     * Check if widget should display in backend
     */
    private function shouldShowInBackend(?User $user): bool
    {
        // Check if backend is enabled
        if (!(int) $this->params->get('enable_backend', 1)) {
            return false;
        }

        // If no user, don't show in backend
        if ($user === null || $user->guest) {
            return false;
        }

        // Check user group restrictions
        $allowedGroups = $this->params->get('backend_usergroups', []);

        // Handle both array and string formats from Joomla params
        if (is_string($allowedGroups)) {
            $allowedGroups = $allowedGroups !== '' ? explode(',', $allowedGroups) : [];
        }
        if (!is_array($allowedGroups)) {
            $allowedGroups = [];
        }
        // Filter out empty values
        $allowedGroups = array_filter($allowedGroups, function($v) { return $v !== '' && $v !== null; });

        if (!empty($allowedGroups)) {
            return $this->userInGroups($user, $allowedGroups);
        }

        // No group restrictions, show to all backend users
        return true;
    }

    /**
     * Check if widget should display on frontend
     */
    private function shouldShowOnFrontend(?User $user): bool
    {
        // Check if frontend is enabled
        $enableFrontend = $this->params->get('enable_frontend', 0);
        if (!(int) $enableFrontend) {
            return false;
        }

        $allowedGroups = $this->params->get('frontend_usergroups', []);

        // Handle both array and string formats from Joomla params
        if (is_string($allowedGroups)) {
            $allowedGroups = $allowedGroups !== '' ? explode(',', $allowedGroups) : [];
        }
        if (!is_array($allowedGroups)) {
            $allowedGroups = [];
        }
        // Filter out empty values
        $allowedGroups = array_filter($allowedGroups, function($v) { return $v !== '' && $v !== null; });

        $hasGroupRestrictions = !empty($allowedGroups);

        // Check for active backend session (user logged into admin)
        $backendUser = $this->getBackendSessionUser();
        $hasBackendSession = ($backendUser !== null && !$backendUser->guest);

        // Check frontend login status
        $hasFrontendLogin = ($user !== null && !$user->guest);

        // If user has backend session, check their groups
        if ($hasBackendSession) {
            if (!$hasGroupRestrictions) {
                return true; // No restrictions, backend user can see it
            }
            if ($this->userInGroups($backendUser, $allowedGroups)) {
                return true; // Backend user is in allowed group
            }
        }

        // If user is logged in on frontend, check their groups
        if ($hasFrontendLogin) {
            if (!$hasGroupRestrictions) {
                return true; // No restrictions, frontend user can see it
            }
            if ($this->userInGroups($user, $allowedGroups)) {
                return true; // Frontend user is in allowed group
            }
        }

        // If we have either session but didn't pass group checks, deny
        if ($hasBackendSession || $hasFrontendLogin) {
            return !$hasGroupRestrictions; // Only show if no group restrictions
        }

        // Guest (no backend or frontend session) - check guest setting
        return (bool) $this->params->get('show_guests', 0);
    }

    /**
     * Get user from backend session if one exists for the current browser
     * Joomla stores frontend and backend sessions separately in #__session table
     * client_id = 0 for frontend, client_id = 1 for administrator
     *
     * Strategy: Look for admin session cookie, read its session data, extract user ID
     */
    private function getBackendSessionUser(): ?User
    {
        try {
            // Method 1: Try to find an admin session cookie and look it up
            $adminUserId = $this->findAdminSessionUserId();

            if ($adminUserId && (int) $adminUserId > 0) {
                return Factory::getContainer()->get(\Joomla\CMS\User\UserFactoryInterface::class)->loadUserById((int) $adminUserId);
            }
        } catch (\Throwable $e) {
            // Fail silently
        }

        return null;
    }

    /**
     * Find user ID from any active administrator session for this browser
     */
    private function findAdminSessionUserId(): ?int
    {
        $db = Factory::getContainer()->get(\Joomla\Database\DatabaseInterface::class);

        // Collect potential session IDs from cookies
        // Include original values, MD5 hashes, and SHA-256 hashes
        $potentialSessionIds = [];
        foreach ($_COOKIE as $cookieName => $cookieValue) {
            if (!is_string($cookieValue)) {
                continue;
            }

            // Session IDs are typically 26-48 character alphanumeric/hex strings
            if (preg_match('/^[a-f0-9]{20,}$/i', $cookieValue) || preg_match('/^[a-zA-Z0-9,-]{20,}$/i', $cookieValue)) {
                $potentialSessionIds[] = $cookieValue;
                $potentialSessionIds[] = md5($cookieValue);
                $potentialSessionIds[] = hash('sha256', $cookieValue);
            }
        }

        // Also add the current PHP session ID if available
        if (session_status() === PHP_SESSION_ACTIVE) {
            $phpSessionId = session_id();
            if ($phpSessionId) {
                $potentialSessionIds[] = $phpSessionId;
                $potentialSessionIds[] = md5($phpSessionId);
                $potentialSessionIds[] = hash('sha256', $phpSessionId);
            }
        }

        if (!empty($potentialSessionIds)) {
            // Remove duplicates
            $potentialSessionIds = array_unique($potentialSessionIds);

            // Try direct session_id match first
            $query = $db->getQuery(true)
                ->select($db->quoteName('userid'))
                ->from($db->quoteName('#__session'))
                ->whereIn($db->quoteName('session_id'), $potentialSessionIds, \Joomla\Database\ParameterType::STRING)
                ->where($db->quoteName('client_id') . ' = 1')
                ->where($db->quoteName('userid') . ' > 0')
                ->where($db->quoteName('guest') . ' = 0');

            $db->setQuery($query);
            $userId = $db->loadResult();

            if ($userId) {
                return (int) $userId;
            }
        }

        // Method 2: Check if current frontend user has an active admin session
        // This works when the same user is logged into both frontend and backend
        $app = Factory::getApplication();
        $currentUser = $app->getIdentity();

        if ($currentUser && !$currentUser->guest && (int) $currentUser->id > 0) {
            $query = $db->getQuery(true)
                ->select('COUNT(*)')
                ->from($db->quoteName('#__session'))
                ->where($db->quoteName('userid') . ' = :userId')
                ->where($db->quoteName('client_id') . ' = 1')
                ->where($db->quoteName('guest') . ' = 0')
                ->bind(':userId', $currentUser->id, \Joomla\Database\ParameterType::INTEGER);

            $db->setQuery($query);
            $hasAdminSession = (int) $db->loadResult() > 0;

            if ($hasAdminSession) {
                return (int) $currentUser->id;
            }
        }

        return null;
    }

    /**
     * Check if user belongs to any of the specified groups
     */
    private function userInGroups(User $user, array $allowedGroups): bool
    {
        $userGroups = $user->getAuthorisedGroups();
        return !empty(array_intersect($userGroups, $allowedGroups));
    }

    /**
     * Prevent enabling the plugin if no access token is set
     */
    public function onExtensionBeforeSave($context, $table, $isNew, $data): bool
    {
        // Only apply to this plugin
        if ($context !== 'com_plugins.plugin' || $table->element !== 'cs_userbackadmin' || $table->folder !== 'system') {
            return true;
        }

        // Check if enabling
        $enabled = (int) ($data['enabled'] ?? $table->enabled ?? 0);

        // Get the access token being saved
        $params = $data['params'] ?? [];
        $token = $params['access_token'] ?? '';

        if ($enabled === 1 && trim($token) === '') {
            $app = Factory::getApplication();
            $app->enqueueMessage('Please enter a valid Userback access token before enabling the plugin.', 'error');
            return false; // block save
        }

        return true;
    }
}
