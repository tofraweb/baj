<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class n3tTemplateControllerConfig extends JController {
	private $_url=null;
	
	function __construct($config=array()) {
		parent::__construct($config);
    if (!n3tTemplateHelperACL::authorizeConfig()) {
      JError::raiseError( 403, JText::_('COM_N3TTEMPLATE_NOT_AUTHORIZED') );  
    }		
		$this->_url='index.php?option=com_n3ttemplate';
		$this->_editurl='index.php?option=com_n3ttemplate&view=config';
	}
	
	function display() {
		JRequest::setVar('layout','form');
		JRequest::setVar('hidemainmenu',1); 	
  	parent::display();
	}
	
	function _save($close) {
		JRequest::checkToken() or jexit('Invalid token');
		
		$post	= JRequest::get('post');

		$model=$this->getModel('config');
		if ($close)
		  $url = $this->_url;
		else
		  $url = $this->_editurl;
		if(!$model->store($post)) 
			$this->setRedirect($url,$model->getError(),'error');
		else 
			$this->setRedirect($url,JText::_('COM_N3TTEMPLATE_SAVED_CONFIGURATION'));
	}

	function save() {
    $this->_save(true);	 
	}
	
	function apply() {
	  $this->_save(false);
	}
	
	function cancel() {
		$this->setRedirect($this->_url);
	}
	
}
?>