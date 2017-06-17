<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class n3tTemplateModelAutotemplates extends n3tTemplateModelList {
	
	private $_category_id=null;	
	private $_position=null;
  	
	function __construct() {
	  $this->_setModelName('autotemplates');
	  $this->_setDefaultOrdering('t.title'); 
    parent::__construct();
		$app=JFactory::getApplication();
		$option=JRequest::getCmd('option');
		
		$this->_category_id=$app->getUserStateFromRequest($option.'.category_id','category_id',-1,'int');
		$this->_position=$app->getUserStateFromRequest($option.'.position','position','','word');
	}
	
	protected function _setQuery() {
	  $where = array();
		$query="SELECT SQL_CALC_FOUND_ROWS at.id, at.template_id, at.category_id AS content_category_id, at.position, t.title AS title, t.category_id, t.checked_out as template_checked_out, t.note, 0 AS checked_out ";
		$query.="FROM #__n3ttemplate_autotemplates AS at \n";
    $query.="LEFT JOIN #__n3ttemplate_templates AS t ON t.id=at.template_id \n";
		$query.="LEFT JOIN #__n3ttemplate_categories AS c ON c.id=t.category_id \n";
    $query.="LEFT JOIN #__categories AS cc ON cc.id = at.category_id \n";
		if ($this->_category_id > -1) {
	   	$where[] = "t.category_id=".$this->_category_id;
		}
		if ($this->_position != '') {
	   	$where[] = "at.position=".$this->_db->Quote($this->_position);
		}
		if ($this->_search) {
      $subwhere = array();		  
			$subwhere[] = 'LOWER( t.title ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $this->_search, true ).'%', false );
			$subwhere[] = 'LOWER( t.note ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $this->_search, true ).'%', false );
			$subwhere[] = 'LOWER( t.template ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $this->_search, true ).'%', false );
			$subwhere[] = 'LOWER( cc.title ) LIKE '.$this->_db->Quote( '%'.$this->_db->getEscaped( $this->_search, true ).'%', false );
			$where[] = "(".implode(" OR ", $subwhere).")";		  
    }
    $where[] = 't.published>-2';
    if (count($where)) $query.=" WHERE ".implode("\nAND ", $where)." \n"; 
    $order = $this->_order." ".$this->_order_Dir;
    $order .= ", t.ordering asc";
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
        
        $data->position_title = JText::_('COM_N3TTEMPLATE_ARTICLE_POSITION_'.strtoupper($data->position));
      }
      
      if (JVersion::isCompatible('1.6.0')) {
  		  $query="SELECT id, title, parent_id FROM #__categories WHERE id > 1 \n";
    		$this->_db->setQuery($query);
    		$categories = $this->_db->loadObjectList('id'); 
    	
    	  foreach ($this->_data as & $data) {
    	    if ($data->content_category_id && isset($categories[$data->content_category_id])) {
    	      $data->content_category_title = $categories[$data->content_category_id]->title;
    	      $parent_id = $categories[$data->content_category_id]->parent_id;
    	      while($parent_id) {
    	        if (isset($categories[$parent_id])) { 
    	          $data->content_category_title = $categories[$parent_id]->title . ' - '.$data->content_category_title;
    	          $parent_id = $categories[$parent_id]->parent_id;
    	        } else 
    	          break;
            }
    	    } else {
    	      $data->category_title = '';
          }
        }  		  
  		} else { 
  		  $query ="SELECT c.id, c.title, s.title AS section_title FROM #__categories AS c \n";
  		  $query.="LEFT JOIN #__sections AS s ON c.section=s.id \n";
    		$this->_db->setQuery($query);
    		$categories = $this->_db->loadObjectList('id'); 
    	  foreach ($this->_data as & $data) {
    	    if ($data->content_category_id && isset($categories[$data->content_category_id])) {
    	      $data->content_category_title = $categories[$data->content_category_id]->section_title . ' - ' . $categories[$data->content_category_id]->title;
    	    } else {
    	      $data->category_title = '';
          }
        }
  		}
      unset($categories);
    }
  }

	protected function _modifyLists() {
	  $this->_lists['filter_category'] = $this->_category_id;
	  $this->_lists['filter_position'] = $this->_position;
	  $this->_lists['category'] = n3tTemplateHelperHTML::categoryTree( $this->_category_id, 'category_id', null, 1, true, JText::_('COM_N3TTEMPLATE_SELECT_CATEGORY') );
	  $this->_lists['position'] = n3tTemplateHelperHTML::filterPosition( $this->_position );
  }
	
	function delete($cid = array())
	{
		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			$query = 'DELETE FROM #__n3ttemplate_autotemplates'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		return true;
	}	
}
?>