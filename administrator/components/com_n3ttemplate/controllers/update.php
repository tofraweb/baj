<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.controller');

class n3tTemplateControllerUpdate extends JController
{

	function display() {
	  $app =& JFactory::getApplication();
    $app->setUserState( "com_n3ttemplate.update.found", false);
    $app->setUserState( "com_n3ttemplate.update.check", false);

  	parent::display();
	}
	
}
?>