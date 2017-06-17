<?php	
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: poweradmin.php 13073 2012-06-06 08:40:46Z binhpt $
-------------------------------------------------------------------------*/
 
// No direct access
defined('_JEXEC') or die ('Restricted access');

// Disable strict standards message
error_reporting(E_ALL & ~E_STRICT);

$user = JFactory::getUser();
$view = JRequest::getCmd('view', 'config');

// Access check.
if ((!$user->authorise('core.manage', 'com_poweradmin')) ||
	($view == 'rawmode' && !$user->authorise('core.sitemanager', 'com_poweradmin')) ||
	($view == 'config' && !$user->authorise('core.admin', 'com_poweradmin')) ||
	($view == 'search' && !$user->authorise('core.sitesearch', 'com_poweradmin'))) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Adding submenus
if ($user->authorise('core.sitemanager', 'com_poweradmin')) {
	JSubMenuHelper::addEntry(JText::_('JSN_POWERADMIN_MENU_RAWMODE_TEXT'), 'index.php?option=com_poweradmin&view=rawmode', $view == 'rawmode');
}

if ($user->authorise('core.sitesearch', 'com_poweradmin')) {
	JSubMenuHelper::addEntry(JText::_('JSN_POWERADMIN_MENU_SITESEARCH_TEXT'), 'index.php?option=com_poweradmin&task=search.query', $view == 'search');
}

if ($user->authorise('core.admin', 'com_poweradmin')) {
	JSubMenuHelper::addEntry(JText::_('JSN_POWERADMIN_MENU_CONFIGURATION_TEXT'), 'index.php?option=com_poweradmin&view=config', $view == 'config');
}

JSubMenuHelper::addEntry(JText::_('JSN_POWERADMIN_MENU_ABOUT_TEXT'), 'index.php?option=com_poweradmin&view=about', $view == 'about');

// Include dependancies
jimport('joomla.application.component.controller');
$controller	= JController::getInstance('Poweradmin');
if (!class_exists('JSNFactory')){
	JError::raiseWarning(NOTICE, JText::_("JSN_WARNING_ENABLE_PLUGIN"));
	$controller->setRedirect('index.php?option=com_plugins');
	$controller->redirect();
}

JSNFactory::localimport('defines');
JSNFactory::localimport('helpers.poweradmin');
//JSNFactory::localimport('libraries.JoomlaShine.jsnhtml');
JSNFactory::localimport('helpers.html.jsntoolbar');

JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

$JSNMedia = JSNFactory::getMedia();
$JSNMedia->addStyleSheet( JSN_POWERADMIN_STYLE_URI. 'poweradmin.css');
//get template author
$jsntemplate 	= JSNFactory::getTemplate();		
global $templateAuthor;
$templateAuthor = $jsntemplate->getAuthor();

$controller->execute(JRequest::getCmd('task'));
$controller->redirect();

?>