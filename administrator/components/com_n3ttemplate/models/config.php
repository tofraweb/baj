<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');
jimport('joomla.application.component.helper');
jimport('joomla.version');
if (JVersion::isCompatible('1.6.0'))
  jimport('joomla.application.component.modelform');

if (!class_exists('JModelForm')) {
  class JModelForm extends JModel {}
}

class n3tTemplateModelConfig extends JModelForm {

	function __construct()
	{
		parent::__construct();
	}

	function &getParams()
	{
		static $instance;

		if ($instance == null)
		{
      if (JVersion::isCompatible('1.6.0'))
        $table =& JTable::getInstance('extension');
      else
  		  $table =& JTable::getInstance('component');
      $component = &JComponentHelper::getComponent(JRequest::getCmd( 'option' ));
	   	$table->load( $component->id );

   	  $path	= JPATH_ADMINISTRATOR.DS.'components'.DS.$component->option.DS.'config.xml';
			$instance = new JParameter( $table->params, $path );
			$instance->setValue('button_acl',n3tTemplateHelperButton::getButtonAccess());
		}
		return $instance;
	} 
	
	function getComponent()
	{
		return JComponentHelper::getComponent('com_n3ttemplate');
	}

	public function getForm($data = array(), $loadData = true)
	{
		jimport('joomla.form.form');

		JForm::addFormPath(JPATH_ADMINISTRATOR. DS .'components'. DS .'com_n3ttemplate');

		$form = $this->loadForm('com_config.component', 'config', array('control' => 'jform', 'load_data' => $loadData), false, '/config' );

		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	
	function store($data)
	{
    if (JVersion::isCompatible('1.6.0')) {
      $data['params'] = $data['jform'];
      unset($data['jform']);
      $table =& JTable::getInstance('extension');
  		if (isset($data['params']) && isset($data['params']['rules'])) {
  			jimport('joomla.access.rules');
  			$rules	= new JRules($data['params']['rules']);
  			$asset	= JTable::getInstance('asset');

  			if (!$asset->loadByName('com_n3ttemplate')) {
  				$root	= JTable::getInstance('asset');
  				$root->loadByName('root.1');
  				$asset->name = 'com_n3ttemplate';
  				$asset->title = 'com_n3ttemplate';
  				$asset->setLocation($root->id,'last-child');
  			}
  			$asset->rules = (string) $rules;

  			if (!$asset->check() || !$asset->store()) {
  				$this->setError($asset->getError());
  				return false;
  			}
  			
  			unset($data['params']['rules']);
  		}
    } else {
		  $table =& JTable::getInstance('component');
		}
    
    $component = &JComponentHelper::getComponent(JRequest::getCmd( 'option' ));
 		n3tTemplateHelperButton::setButtonAccess((int)$data['params']['button_acl']);		
    
		$table->load( $component->id );
		$table->bind($data);

		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}    
		
		if (JVersion::isCompatible('1.6.0')) {
		  $cache = JFactory::getCache('_system');
		  $cache->clean();
		}
		
		return true;
	}

}