<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class n3tTemplateModelCategories extends n3tTemplateModelList {

	function __construct() {
	  $this->_setModelName('categories');
	  $this->_setDefaultOrdering('c.ordering'); 
    parent::__construct();
		$app=JFactory::getApplication();
		$option=JRequest::getCmd('option');		
	}
	
	protected function _setQuery() {
	  $where = array();
	  $default_order = $this->_search ? '0' : '1';
		$query='SELECT SQL_CALC_FOUND_ROWS c.*, c.parent_id as parent, c.title as name, c.title as treename, ';
    $query.=$default_order.' as order_up, '.$default_order.' as order_down, ';
    if (JVersion::isCompatible('1.6.0')) { 
      $query.='g.title AS groupname, ';
      $query.='e.enabled AS plugin_published, ';
      $query.='e.name AS plugin_title, ';
      $query.='e.access AS plugin_access, ';
      $query.='eg.title AS plugin_groupname ';
    } else {
      $query.='g.name AS groupname, ';
      $query.='p.published AS plugin_published, ';
      $query.='p.name AS plugin_title, ';
      $query.='p.access AS plugin_access, ';
      $query.='pg.name AS plugin_groupname ';
    }    
    $query.='FROM #__n3ttemplate_categories AS c'."\n";
    if (JVersion::isCompatible('1.6.0')) {
      $query.="LEFT JOIN #__viewlevels AS g ON g.id = c.access ";
      $query.='LEFT JOIN #__extensions AS e ON e.type="plugin" AND e.folder="n3ttemplate" AND e.element = c.plugin ';
      $query.="LEFT JOIN #__viewlevels AS eg ON eg.id = e.access ";
    } else {
      $query.="LEFT JOIN #__groups AS g ON g.id = c.access ";
      $query.='LEFT JOIN #__plugins AS p ON p.folder="n3ttemplate" AND p.element = c.plugin ';
      $query.="LEFT JOIN #__groups AS pg ON pg.id = p.access ";
    }
    
		if ($this->_search) {
      $subwhere = array();		  
			$subwhere[] = 'LOWER( c.title ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $this->_search, true ).'%', false ); 		
			$subwhere[] = 'LOWER( c.note ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $this->_search, true ).'%', false ); 		
			$where[] = "(".implode(" OR ", $subwhere).")";		  		
    }
    switch ($this->_filter_state) {
      case 1:
      case 0:
      case -2:
        $where[] = 'c.published='.$this->_filter_state;
      break;
      default:
        $where[] = 'c.published>-2';
      break;      
    }       
    if (count($where)) $query.=" WHERE ".implode("\nAND ", $where)." \n"; 
		$query.="ORDER BY ".$this->_order." ".$this->_order_Dir.", c.parent_id, c.ordering \n";
		return $query;
	}
	
	function &getData() {
	  if (!$this->_data) {
  		$query = $this->_setQuery();
  		$this->_db->setQuery( $query );
  		$rows = $this->_db->loadObjectList();  		
  		
  		if ($this->_search) {
  		  $list = $rows;
  		} else {
        $children = array();
    		foreach ($rows as $v )
    		{
    			$pt = $v->parent;
    			$list = @$children[$pt] ? $children[$pt] : array();
    			array_push( $list, $v );
    			$children[$pt] = $list;
    		}    		
    		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children );
    		$this->_total = count( $list );
    		$parents = array();
    		foreach($list as $index => &$list_item) {
    		  if (!isset($parents[$list_item->parent])) $parents[$list_item->parent] = array('first' => $index, 'last' => $index);
    		  else $parents[$list_item->parent]['last'] = $index;  		  
        }
        foreach($parents as $parent) {
          $list[$parent['first']]->order_up = 0;
          $list[$parent['last']]->order_down = 0;
        }     
      } 
      
  		jimport('joomla.html.pagination');
  		$this->_pagination = new JPagination( $this->_total, $this->_limitstart, $this->_limit );
  
  		// slice out elements based on limits
		  $this->_data=array_slice( $list, $this->_pagination->limitstart, $this->_pagination->limit );
      $lang = &JFactory::getLanguage();            
      foreach($this->_data as & $data) {
        if ($data->plugin) {   
          $lang->load('plg_n3ttemplate_'.$data->plugin,JPATH_ADMINISTRATOR);       
          $data->plugin_title = JText::_($data->plugin_title);
          $data->plugin_title = preg_replace('/^n3ttemplate\s*-?\s*/i', '', $data->plugin_title);
        }           
      }
    }
    return $this->_data;     		    
	}
	
	protected function _modifyLists() {
	  $this->_lists['batch-parent-id'] = n3tTemplateHelperHTML::categoryTree(-1,'batch[parent_id]',null,1,false,JText::_('COM_N3TTEMPLATE_BATCH_KEEP_CURRENT'));
	  $this->_lists['batch-movecopy'] = n3tTemplateHelperHTML::batchMoveCopy();
	  $this->_lists['batch-access'] = n3tTemplateHelperHTML::accesslevel('','batch[access]',JText::_('COM_N3TTEMPLATE_BATCH_KEEP_CURRENT'));
  }	
}
?>