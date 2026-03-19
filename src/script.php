<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Cs_UserbackAdmin
 *
 * @copyright   Copyright (C) 2025-2026 Cybersalt Consulting Ltd. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;

class PlgSystemCs_userbackadminInstallerScript
{
    public function postflight($type, $parent): void
    {
        $db = Factory::getContainer()->get(\Joomla\Database\DatabaseInterface::class);

        if ($type === 'install') {
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__extensions'))
                ->set($db->quoteName('enabled') . ' = 1')
                ->where($db->quoteName('element') . ' = ' . $db->quote('cs_userbackadmin'))
                ->where($db->quoteName('folder') . ' = ' . $db->quote('system'))
                ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
            $db->setQuery($query);
            $db->execute();
        }

        $this->showPostInstallMessage($type, $db);
    }

    protected function showPostInstallMessage(string $type, $db): void
    {
        $action = $type === 'update' ? 'updated' : 'installed';

        // Look up the plugin's extension_id for the settings link
        $query = $db->getQuery(true)
            ->select($db->quoteName('extension_id'))
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('element') . ' = ' . $db->quote('cs_userbackadmin'))
            ->where($db->quoteName('folder') . ' = ' . $db->quote('system'))
            ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
        $db->setQuery($query);
        $extensionId = $db->loadResult();

        $url = 'index.php?option=com_plugins&view=plugin&layout=edit&extension_id=' . (int) $extensionId;

        echo '<div class="card mb-3" style="margin: 20px 0;">'
            . '<div class="card-body">'
            . '<h3 class="card-title">Cybersalt Userback Admin</h3>'
            . '<p class="card-text">The plugin has been successfully ' . $action . '.</p>'
            . '<a href="' . $url . '" class="btn btn-primary text-white">'
            . '<span class="icon-wrench" aria-hidden="true"></span> Open Plugin Settings'
            . '</a>'
            . '</div></div>';
    }
}
