<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: changeposition.php 13785 2012-07-05 03:50:44Z hiepnv $
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class PoweradminControllerChangeposition extends JControllerForm
{
	/**
	 * 
	 * Save setting position to the database
	 */
	public function setPosition(){
		$app = JFactory::getApplication();
		$moduleid = $app->getUserState( 'com_poweradmin.changeposition.moduleid', JRequest::getVar('moduleid', array(), 'get', 'array') );
		$position = JRequest::getVar('position', '');
		$model = $this->getModel('changeposition');
		for($i = 0; $i < count($moduleid); $i++){
			$model->setPosition($moduleid[$i], $position);
		}
		jexit();
	}
	/**
	 * 
	 * Redirect to position listing page
	 */
	public function selectPosition()
	{
		if(!JSNFactory::_cURLCheckFunctions()){
			$msg 	=	 JText::_('JSN_POWERADMIN_ERROR_CURL_NOT_ENABLED');
			$this->setRedirect('index.php?option=com_poweradmin&view=error&tmpl=component', $msg, 'error');
			$this->redirect();
		}
	
		global $templateAuthor, $notSupportedTemplateAuthors;		
		if(in_array($templateAuthor,$notSupportedTemplateAuthors)){
			$msg 	=	 JText::_('JSN_POWERADMIN_ERROR_TEMPLATE_NOT_SUPPORTED');
			$this->setRedirect('index.php?option=com_poweradmin&view=error&tmpl=component',$msg);			
		}else{		
			$app = JFactory::getApplication();			
			$moduleid = JRequest::getVar('moduleid', array(), 'get', 'array');
				
			$redirect_mode = JRequest::getVar('redirect_mode', 'visualmode');
			
			if ($redirect_mode == 'visualmode'){
				$appendRedirect = 'format=raw&layout=visualmode';
			}else{
				$appendRedirect = 'tmpl=component';
			}
			
			$moduleid = explode(',', $moduleid[0]);
			$app->setUserState( 'com_poweradmin.changeposition.moduleid', $moduleid );
			$this->setRedirect('index.php?option=com_poweradmin&view=changeposition&'.$appendRedirect);
			$this->redirect();			
		}
		

	}	
	
}
?>