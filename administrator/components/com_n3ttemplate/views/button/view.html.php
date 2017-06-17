<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class n3tTemplateViewButton extends n3tTemplateView
{
	function display()
	{
	  $this->loadHelper('html');
	  n3tTemplateHelperHTML::assetsPopup();
	  
	  $params = JComponentHelper::getParams( 'com_n3ttemplate' ); 	  
	  $this->assignRef('params',$params);
     				
		parent::display();
	}
}