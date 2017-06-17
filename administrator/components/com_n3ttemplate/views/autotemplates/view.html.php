<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class n3tTemplateViewAutotemplates extends JView {
	function display($tpl=null) {
	  
    $this->loadHelper('html');
	       
		n3tTemplateHelperHTML::toolbarTitle(JText::_('COM_N3TTEMPLATE_AUTOTEMPLATES'));
		n3tTemplateHelperHTML::assets();
		n3tTemplateHelperHTML::subtoolbar();
		
		$data=&$this->get('data');
		$lists=&$this->get('lists');	
		
 		if(count($data)) {
    	JToolBarHelper::custom('delete','delete.png','delete_f2.png', 'Delete', true); 		
   		JToolBarHelper::divider();
 		}
    JToolBarHelper::help('autotemplates',true);
		
		$this->assignRef('data',$data);
		$this->assignRef('pagination',$this->get('pagination'));
		$this->assignRef('lists',$lists);
		$this->assignRef('user',JFactory::getUser());
        				
		parent::display($tpl);
	}	
}
?>