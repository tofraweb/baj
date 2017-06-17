<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class n3tTemplateModelTemplates extends n3tTemplateModelList {
	
	private $_category_id=null;	
  	
	function __construct() {
	  $this->_setModelName('templates');
	  $this->_setDefaultOrdering('t.ordering'); 
    parent::__construct();
		$app=JFactory::getApplication();
		$option=JRequest::getCmd('option');
		
		$this->_category_id=$app->getUserStateFromRequest($option.'.category_id','category_id',-1,'int');
	}
	
	protected function _setQuery() {
	  $where = array();
		$query="SELECT SQL_CALC_FOUND_ROWS t.*";
    if (JVersion::isCompatible('1.6.0')) { 
      $query.=', g.title AS groupname ';
      $query.=', dg.title AS display_groupname ';
    } else {
      $query.=', g.name AS groupname ';
      $query.=', dg.name AS display_groupname ';
    }    		
    $query.="FROM #__n3ttemplate_templates AS t \n";
		$query.="LEFT JOIN #__n3ttemplate_categories AS c ON c.id=t.category_id \n";
    if (JVersion::isCompatible('1.6.0')) {
      $query.="LEFT JOIN #__viewlevels AS g ON g.id = t.access ";
      $query.="LEFT JOIN #__viewlevels AS dg ON dg.id = t.display_access ";
    } else {
      $query.="LEFT JOIN #__groups AS g ON g.id = t.access ";
      $query.="LEFT JOIN #__groups AS dg ON dg.id = t.display_access ";
    }		
		if ($this->_category_id > -1) {
	   	$where[] = "t.category_id=".$this->_category_id;
		}
		if ($this->_search) {
      $subwhere = array();		  
			$subwhere[] = 'LOWER( t.title ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $this->_search, true ).'%', false );
			$subwhere[] = 'LOWER( t.note ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $this->_search, true ).'%', false );
			$subwhere[] = 'LOWER( t.template ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $this->_search, true ).'%', false );
			$where[] = "(".implode(" OR ", $subwhere).")";		  
    }
    switch ($this->_filter_state) {
      case 1:
      case 0:
      case -2:
        $where[] = 't.published='.$this->_filter_state;
      break;
      default:
        $where[] = 't.published>-2';
      break;      
    }
    if (count($where)) $query.=" WHERE ".implode("\nAND ", $where)." \n"; 
    $order = $this->_order." ".$this->_order_Dir;
    if ($this->_order == 't.ordering' || $this->_order == 't.published') $order = "t.category_id asc, ".$order;
    else $order .= ", t.ordering asc";
    $order .= ", t.id asc";   
		$query.="ORDER BY ".$order." \n";
		return $query;
	}
	
	protected function _modifyData() {
	  if ($this->_data) {
  		$query="SELECT id, title, parent_id FROM #__n3ttemplate_categories \n";
  		$this->_db->setQuery($query);
  		$categories = $this->_db->loadObjectList('id'); 
  	
  	  foreach ($this->_data as & $data) {
  	    if ($data->category_id) {
  	      $data->category_title = $categories[$data->category_id]->title;
  	      $parent_id = $categories[$data->category_id]->parent_id;
  	      while($parent_id) {
  	        $data->category_title = $categories[$parent_id]->title . ' - '.$data->category_title;
  	        $parent_id = $categories[$parent_id]->parent_id;
          }
  	    } else {
  	      $data->category_title = '';
        }
      }
      unset($categories);
    }
  }

	protected function _modifyLists() {
	  $this->_lists['filter_category'] = $this->_category_id;
	  $this->_lists['category'] = n3tTemplateHelperHTML::categoryTree( $this->_category_id, 'category_id', null, 1, true, JText::_('COM_N3TTEMPLATE_SELECT_CATEGORY') );
	  $this->_lists['batch-category-id'] = n3tTemplateHelperHTML::categoryTree(-1,'batch[category_id]',null,1,false,JText::_('COM_N3TTEMPLATE_BATCH_KEEP_CURRENT'));
	  $this->_lists['batch-movecopy'] = n3tTemplateHelperHTML::batchMoveCopy();
	  $this->_lists['batch-access'] = n3tTemplateHelperHTML::accesslevel('','batch[access]',JText::_('COM_N3TTEMPLATE_BATCH_KEEP_CURRENT'));
	  $this->_lists['batch-display-access'] = n3tTemplateHelperHTML::accesslevel('','batch[display_access]',JText::_('COM_N3TTEMPLATE_BATCH_KEEP_CURRENT'));
  }
	
}
?>