<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');

JRequest::setVar('view', 'button'); 
$lang = &JFactory::getLanguage();
$lang->load('com_n3ttemplate',JPATH_ADMINISTRATOR);

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'model.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'view.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'acl.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'button.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'plugin.php');

$controller=JRequest::getCmd('view','button');
$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.$controller.'.php';
if(JFile::exists($path))
{
	require_once($path);
}
else 
{
	JError::raiseError('500',JText::_('Unknown controller').' '.$controller);
}

$controllerName='n3tTemplateController'.ucfirst($controller);

$controller=new $controllerName();
$controller->addViewPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views');
$controller->addModelPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'models');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
?>