<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class n3tTemplateModelList extends JModel {
	protected $_limitstart=null;
	protected $_limit=null;
	protected $_order=null;
	protected $_order_Dir=null;
	
	protected $_data=null;
  protected $_total=0;	
	protected $_pagination=null;
	protected $_lists=null;
	
	protected $_search=null;
	protected $_filter_state=null;
	
	private $_modelName = '';
	private $_defaultOrdering = '';
	
	function __construct() {
		parent::__construct();
		$app=JFactory::getApplication();
		$option=JRequest::getCmd('option');
		
		$this->_limitstart=$app->getUserStateFromRequest($option.'.'.$this->_modelName.'.limitstart','limitstart',0,'int');
		$this->_limit=$app->getUserStateFromRequest($option.'.'.$this->_modelName.'.limit','limit',$app->getCfg('list_limit'),'int');
		$this->_order=$app->getUserStateFromRequest($option.'.'.$this->_modelName.'.filter_order','filter_order',$this->_defaultOrdering,'string');
		$this->_order_Dir=$app->getUserStateFromRequest($option.'.'.$this->_modelName.'.filter_order_Dir','filter_order_Dir','','string');
		
		$this->_search = $app->getUserStateFromRequest($option.'.'.$this->_modelName.'.search','search','','string');
		if (strpos($this->_search, '"') !== false) {
			$this->_search = str_replace(array('=', '<'), '', $this->_search);
		}
		$this->_search = JString::strtolower($this->_search);		
		$this->_filter_state=$app->getUserStateFromRequest($option.'.'.$this->_modelName.'.filter_state','filter_state',2,'int');		
	}
	
	protected function _setModelName($modelName) {
	  $this->_modelName = $modelName;
  }

	protected function _setDefaultOrdering($defaultOrdering) {
	  $this->_defaultOrdering = $defaultOrdering;
  }
   
	protected function _setQuery() {
	  return '';
	}
	
	protected function _modifyData() {
  }
  	
	function &getData() {
	  if (!$this->_data) {
	    $this->_data = $this->_getList($this->_setQuery(),$this->_limitstart,$this->_limit);
	    $this->_db->setQuery('SELECT FOUND_ROWS()');
	    $this->_total=$this->_db->loadResult();
      $this->_modifyData();	    
    } 
		return $this->_data;
	}
	
	function &getPagination() {
	  if (!$this->_pagination) { 
		  jimport('joomla.html.pagination');
		  $this->_pagination = new JPagination($this->_total,$this->_limitstart,$this->_limit);
		}
		return $this->_pagination; 
	}
	
	protected function _modifyLists() {
  }
  	
	function &getLists() {
	  if (!$this->_lists) {
	    $this->_lists = array();
	    
  		$this->_lists["limitstart"]=$this->_limitstart;
  		$this->_lists["limit"]=$this->_limit;
  		$this->_lists["order"]=$this->_order;  		
  		$this->_lists["order_Dir"]=$this->_order_Dir;
  		$this->_lists["ordering"]=$this->_order == $this->_defaultOrdering && !$this->_search;
  		
  		$this->_lists["search"]=$this->_search;
  		$this->_lists["filter_state"]=$this->_filter_state;
  		$this->_lists["state"]=n3tTemplateHelperHTML::filterState($this->_filter_state);
  		$this->_modifyLists();
		}
		return $this->_lists;
	}
}
?>