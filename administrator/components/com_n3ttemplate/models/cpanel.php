<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.model');
jimport('joomla.version');

class n3tTemplateModelCpanel extends JModel
{

	public function __construct()
	{
		parent::__construct();
	}

	private function _getIcon($icon, $label, $view = null, $task = null, $params = null, $option = null )
	{
		return array(
			'icon'	=> $icon,
			'text'	=> JText::_($label),
			'option'	=> $option,
			'view'	=> $view,
			'task'	=> $task,
			'params'	=> $params
		);
	}

	public function getIcons()
	{
	  $return = array();
    
    if (n3tTemplateHelperACL::authorizeAdmin()) {        
      $return['CATEGORIES'][] = $this->_getIcon('categories','COM_N3TTEMPLATE_CATEGORIES','categories');
      $return['CATEGORIES'][] = $this->_getIcon('new-category','COM_N3TTEMPLATE_NEW_CATEGORY','categories', 'edit');
        
      $return['TEMPLATES'][] = $this->_getIcon('templates','COM_N3TTEMPLATE_TEMPLATES','templates');
      $return['TEMPLATES'][] = $this->_getIcon('new-template','COM_N3TTEMPLATE_NEW_TEMPLATE','templates', 'edit');
      $return['TEMPLATES'][] = $this->_getIcon('autotemplates','COM_N3TTEMPLATE_AUTOTEMPLATES','autotemplates');
    }
    if (n3tTemplateHelperACL::authorizeConfig()) {
      $return['ADMINISTRATION'][] = $this->_getIcon('configuration','COM_N3TTEMPLATE_CONFIGURATION','config');
      if (JVersion::isCompatible('1.6.0'))
        $return['ADMINISTRATION'][] = $this->_getIcon('plugins','COM_N3TTEMPLATE_PLUGINS','plugins',null,'filter_folder=n3ttemplate','com_plugins');
      else
        $return['ADMINISTRATION'][] = $this->_getIcon('plugins','COM_N3TTEMPLATE_PLUGINS','plugins',null,'filter_type=n3ttemplate','com_plugins');
      if (n3tTemplateHelperUpdate::checkVersion())
        $return['ADMINISTRATION'][] = $this->_getIcon('update','COM_N3TTEMPLATE_UPDATE_FOUND','update');
    }
    return $return;
	}
}