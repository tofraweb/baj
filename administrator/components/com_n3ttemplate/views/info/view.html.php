<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class n3tTemplateViewInfo extends JView
{
	function display()
	{
	  $this->loadHelper('html');
    
    JToolBarHelper::help('index',true);
	   
		n3tTemplateHelperHTML::toolbarTitle(JText::_('COM_N3TTEMPLATE_CONTROL_PANEL'));
		n3tTemplateHelperHTML::assets();
		n3tTemplateHelperHTML::subtoolbar();
					 
		parent::display();
	}
	
	function releaseNotes() {
    $lang =& JFactory::getLanguage();
    if (JFile::exists(JPATH_COMPONENT_ADMINISTRATOR.DS.'install'.DS.'notes'.DS.$lang->getTag().'.php'))
      require(JPATH_COMPONENT_ADMINISTRATOR.DS.'install'.DS.'notes'.DS.$lang->getTag().'.php');
    elseif (JFile::exists(JPATH_COMPONENT_ADMINISTRATOR.DS.'install'.DS.'notes'.DS.'en-GB.php'))
      require(JPATH_COMPONENT_ADMINISTRATOR.DS.'install'.DS.'notes'.DS.'en-GB.php');  	  
	}
}