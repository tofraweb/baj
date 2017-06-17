<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');

JRequest::setVar('view', JRequest::getCmd('view','cpanel')); 

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'controller.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'model.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'view.php');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'acl.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'button.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'content.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'plugin.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'update.php');

$controller=JRequest::getCmd('view','cpanel');
$path = JPATH_COMPONENT_ADMINISTRATOR.DS.'controllers'.DS.$controller.'.php';
if(JFile::exists($path))
{
	require_once($path);
}
else 
{
	JError::raiseError('500',JText::_('COM_N3TTEMPLATE_UNKNOWN_CONROLLER').' '.$controller);
}

$controllerName='n3tTemplateController'.ucfirst($controller);

$class=new $controllerName();
$class->execute(JRequest::getCmd('task'));
$class->redirect();
?>