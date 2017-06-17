<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class n3tTemplateControllerButton extends JController {

	function __construct($config=array()) {
		parent::__construct($config);
    if (!n3tTemplateHelperACL::authorize()) {
      JError::raiseError( 403, JText::_('COM_N3TTEMPLATE_NOT_AUTHORIZED') );  
    }		
	}
	
	function display() {
  	parent::display();
	}
	
	function preview() {
  	$app =& JFactory::getApplication();
  	$db 			=& JFactory::getDBO(); 

		$query = 'SELECT template' .
				' FROM #__templates_menu' .
				' WHERE client_id = 0' .
				' AND menuid = 0';
		$db->setQuery($query);
		$template = $db->loadResult();
		
  	$document =& JFactory::getDocument();
		$document->setTitle(JText::_('COM_N3TTEMPLATE_TEMPLATE_PREVIEW'));
		$document->addStyleSheet(JURI::root() . 'templates/'.$template.'/css/editor.css');
		$document->setBase(JURI::root()); 

	  $model = $this->getModel('button');
	  echo $model->getTemplate();
	}

	function template() {
  	$app =& JFactory::getApplication();

	  $model = $this->getModel('button');
	  echo $model->getTemplate();
	
	  JResponse::setHeader('Content-Type', 'text/html; charset=utf-8');
    JResponse::sendHeaders();    
	  $app->close();
	}

	function autotemplate() {
  	$app =& JFactory::getApplication();

	  $model = $this->getModel('button');
	  echo $model->getAutoTemplate();
	  
	  JResponse::setHeader('Content-Type', 'text/html; charset=utf-8');
    JResponse::sendHeaders();	  
	  $app->close();
	}
	
}
?>