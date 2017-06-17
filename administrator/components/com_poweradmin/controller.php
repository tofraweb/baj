<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: controller.php 12779 2012-05-18 02:55:18Z binhpt $
-------------------------------------------------------------------------*/
 
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class PoweradminController extends JController
{
	function display($cachable = false, $params = null)
	{
		JRequest::setVar('view',JRequest::getCmd('view','config'));
		parent::display($cachable);
	}
	
	function checkUpdate ()
	{
		$currentVersion = PoweradminHelper::getVersion();
		$latestVersion = PoweradminHelper::getLatestVersion();
		
		$response = array();
		
		if ($latestVersion == null) {
			$response['status'] = 400;
			$response['message'] = 'Failed to contact to versioning server';
		}
		else {
			$hasUpdate = version_compare($currentVersion, $latestVersion, '<');
			$hasUpdateMessage = 'New version is available. <a href="index.php?option=com_poweradmin&view=update" target="_blank" class="jsn-action-link">Update now</a>';
			$latestVersionMessage = 'You have the latest version.';
			
			$response['status'] = $hasUpdate ? 200 : 204;
			$response['message'] = $hasUpdate ? $hasUpdateMessage : $latestVersionMessage;
		}
		
		echo json_encode($response);
		jexit();
	}
	
	function update ()
	{
		echo 'Update page';
		jexit();
	}

	function removeExtension()
	{
		$user	= JFactory::getUser();
		$component = JRequest::getCmd('component');
		
		$coreComponents = array(
			'com_content', 'com_admin', 'com_config', 'com_checkin', 
			'com_cache', 'com_login', 'com_users', 'com_menus', 
			'com_categories', 'com_media',
			'com_messages', 'com_redirect', 
			'com_search'
		);

		if ($user->get('id') && preg_match('/^com_/i', $component) && !in_array($component, $coreComponents))
		{
			$dbo = JFactory::getDBO();
			$dbo->setQuery("SELECT extension_id FROM #__extensions WHERE element LIKE '{$component}' AND type LIKE 'component' LIMIT 1");
			$componentId = $dbo->loadResult();
			
			if (empty($componentId) || !is_numeric($componentId)) {
				$this->setRedirect('index.php');
				return;
			}
			
			JFactory::getLanguage()->load('com_installer');
			JSNFactory::import('components.com_installer.models.manage');
			
			$model	= $this->getModel('manage','InstallerModel',array('ignore_request'=>true));
			$result = $model->remove(array($componentId));
			$this->setRedirect('index.php?option=com_installer&view=manage');
			
			return;
		}
		
		$this->setRedirect('index.php');
	}
}
