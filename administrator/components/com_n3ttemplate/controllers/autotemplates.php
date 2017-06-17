<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class n3tTemplateControllerAutotemplates extends n3tTemplateController {

	function __construct($config=array()) {
		parent::__construct($config);
    if (!n3tTemplateHelperACL::authorizeAdmin()) {
      JError::raiseError( 403, JText::_('COM_N3TTEMPLATE_NOT_AUTHORIZED') );  
    }		
		$this->_setUrl('index.php?option=com_n3ttemplate&view=autotemplates');
		$this->_setModelName('autotemplates');
	}
	
	function orderup()
	{
	}

	function orderdown()
	{
	}
  	
	function saveorder()
	{
	} 
	
	function publish()
	{
	}

	function unpublish()
	{
	}
 	
	function edit() {		
	}
		
	function save() {
	}
	
	function cancel() {
	}
	
	function remove() {
	}
	
	function restore() {
	}	
}
?>