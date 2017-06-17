<?php
/**
 * @version   $Id: template-save.php 2413 2012-08-16 04:31:03Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('JPATH_BASE') or die();

/** @var $gantry Gantry */
		global $gantry;

$action = JRequest::getString('action');
gantry_import('core.gantryjson');


switch ($action) {
	case 'save':
	case 'apply':
		echo gantryAjaxSaveTemplate();
		break;
	default:
		echo "error";
}

function gantryAjaxSaveTemplate()
{
	// Check for request forgeries
	JRequest::checkToken() or jexit('Invalid Token');

	JModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_gantry/models');
	$model = JModel::getInstance("Template", 'GantryModel');
	$data  = JRequest::getVar('jform', array(), 'post', 'array');
	if (!$model->save($data)) {
		return 'error';
	}

	// Clear the front end gantry cache after each call
	$cache = GantryCache::getInstance(false);
	$cache->clearGroupCache();

	$task = JRequest::getCmd('task');
	if ($task == 'apply') {
		return JText::_('Template settings have been successfully applied.');
	} else {
		return JText::_('Template settings have been successfully saved.');
	}


}
