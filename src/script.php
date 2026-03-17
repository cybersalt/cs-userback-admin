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
        if ($type === 'install') {
            $db = Factory::getContainer()->get(\Joomla\Database\DatabaseInterface::class);
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__extensions'))
                ->set($db->quoteName('enabled') . ' = 1')
                ->where($db->quoteName('element') . ' = ' . $db->quote('cs_userbackadmin'))
                ->where($db->quoteName('folder') . ' = ' . $db->quote('system'))
                ->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
            $db->setQuery($query);
            $db->execute();
        }
    }
}
