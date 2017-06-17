<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: templates.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

class PoweradminControllerTemplates extends JControllerForm
{
	
	function makeDefault()
	{
		$id = JRequest::getVar('id', 0);
		if (!is_numeric($id) || $id <= 0)
			jexit();
			
		$this->getModel('templates')->setDefaultTemplate($id);
		jexit();
	}
	
	function duplicate()
	{
		$id = JRequest::getVar('id', array(), 'GET', 'array');
		
		JSNFactory::import('components.com_templates.models.style');
		JSNFactory::import('components.com_templates.tables.style');
		$model	= $this->getModel('Style','TemplatesModel',array('ignore_request'=>true));
		$model->duplicate($id);
		$duplicatedStyle = $this->getModel('templates')->getLatestStyle();
		$duplicatedStyle->thumbnail = (is_file(JPATH_SITE.DS.'templates'.DS.$duplicatedStyle->template.DS.'template_thumbnail.png')) ? JURI::root().'templates/'.$duplicatedStyle->template.'/template_thumbnail.png' : '';

		echo json_encode($duplicatedStyle);
		jexit();
	}
	
	function delete()
	{
		$id = JRequest::getVar('id', array(), 'GET', 'array');
		
		JSNFactory::import('components.com_templates.models.style');
		JSNFactory::import('components.com_templates.tables.style');
		
		JFactory::getLanguage()->load('com_templates');
		
		$model	= $this->getModel('Style','TemplatesModel',array('ignore_request'=>true));
		$isDeleted = $model->delete($id);
		
		$response = array(
			'isDeleted' => $isDeleted,
			'message'   => $model->getError()
		);
		
		echo json_encode($response);
		jexit();
	}	
	
	function uninstall()
	{
		$id = JRequest::getVar('id', 0);
		if (!is_numeric($id) || $id <= 0)
			return;
		
		$dbo = JFactory::getDBO();
		$dbo->setQuery("SELECT e.extension_id FROM #__extensions e INNER JOIN #__template_styles s ON e.element=s.template WHERE e.type='template' AND s.id={$id} LIMIT 1");
		$extensionId = $dbo->loadResult();
		
		if ($extensionId > 0) {
			JFactory::getLanguage()->load('com_installer');
			JSNFactory::import('components.com_installer.models.manage');
			
			$model	= $this->getModel('manage','InstallerModel',array('ignore_request'=>true));
			$result = $model->remove(array($extensionId));
			
			$app = JFactory::getApplication();
			echo json_encode($app->getMessageQueue());
		}
		
		jexit();
	}
}
?>