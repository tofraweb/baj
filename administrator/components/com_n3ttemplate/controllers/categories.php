<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class n3tTemplateControllerCategories extends n3tTemplateController {
	
	function __construct($config=array()) {
		parent::__construct($config);
    if (!n3tTemplateHelperACL::authorizeAdmin()) {
      JError::raiseError( 403, JText::_('COM_N3TTEMPLATE_NOT_AUTHORIZED') );  
    }		
		$this->_setUrl('index.php?option=com_n3ttemplate&view=categories');
		$this->_setEditUrl('index.php?option=com_n3ttemplate&view=categories&task=edit&cid[]=');
		$this->_setModelName('category');
		$this->_setViewName('category');				
	}
}
?>