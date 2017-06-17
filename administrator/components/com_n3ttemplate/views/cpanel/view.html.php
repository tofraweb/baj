<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class n3tTemplateViewCpanel extends JView
{
	function display()
	{
	  $this->loadHelper('html');
    
		n3tTemplateHelperHTML::toolbarTitle(JText::_('COM_N3TTEMPLATE_CONTROL_PANEL'));
		n3tTemplateHelperHTML::assets();
		n3tTemplateHelperHTML::subtoolbar();
		JToolBarHelper::divider();
		JToolBarHelper::help('cpanel',true);
					 
		$this->assign('icons', $this->get('icons')); 

		parent::display();
	}
}