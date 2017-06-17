<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.application.component.model');
jimport('joomla.version');
if (JVersion::isCompatible('1.6.0'))
  jimport('joomla.application.component.modelform');

if (!class_exists('JModelForm')) {
  class JModelForm extends JModel {}
}

class n3tTemplateModelTemplate extends JModelForm {
	private $_id = null;
	private $_data = null;

	function __construct()
	{
		parent::__construct();
		$app=JFactory::getApplication();
		$option=JRequest::getCmd('option');
		   
		$array = JRequest::getVar('cid', array(0), '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if (!$this->_loadData())
		  $this->_initData();

		return $this->_data;
	}
	
  public function getTable($name = 'Template', $prefix = 'n3tTemplateTable', $options = array()) 
  {
		return JTable::getInstance($name, $prefix, $options);
	}	

	public function getForm($data = array(), $loadData = true)
	{
		jimport('joomla.form.form');

		JForm::addFormPath(JPATH_ADMINISTRATOR. DS .'components'. DS .'com_n3ttemplate'. DS .'params');

		$form = $this->loadForm('com_n3ttemplate.template', 'template', array('control' => 'jform', 'load_data' => $loadData), false, '/model' );

		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	function isCheckedOut( $uid=0 )
	{
		if ($this->_loadData())
		{
			if ($uid) {
				return ($this->_data->checked_out && $this->_data->checked_out != $uid);
			} else {
				return $this->_data->checked_out;
			}
		}
	}

	function checkin()
	{
		if ($this->_id)
		{
			$row = & $this->getTable();
			if(!$row->checkin($this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}

	function checkout($uid = null)
	{
		if ($this->_id)
		{
			if (is_null($uid)) {
				$user	=& JFactory::getUser();
				$uid	= $user->get('id');
			}
			$row = & $this->getTable();
			if(!$row->checkout($uid, $this->_id)) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			return true;
		}
		return false;
	}

	function store($data, $copy)
	{
		$row =& $this->getTable();

    if (JVersion::isCompatible('1.6.0')) {
      if (isset($data['jform'])) {
        $data['params'] = $data['jform'];      
        unset($data['jform']);
      } else
        $data['params'] = null;      
    }

    if ($copy)
      $autotemplates = array();
    else        
      $autotemplates = $data['autotemplates'];    
    unset($data['autotemplates']);

    if ($copy && $data['title']) {
  		$query = 'SELECT id FROM #__n3ttemplate_templates WHERE category_id='.$data['category_id'].' AND title='.$this->_db->Quote($data['title']);
	   	$this->_db->setQuery($query);
		  while (intval($this->_db->loadResult())) { 
        if (preg_match('#\((\d+)\)$#', $data['title'], $m)) {
  				$data['title'] = preg_replace('#\(\d+\)$#', '('.($m[1] + 1).')', $data['title']);
  			} else {
  				$data['title'] .= ' (2)';
  			}
    		$query = 'SELECT id FROM #__n3ttemplate_templates WHERE category_id='.$data['category_id'].' AND title='.$this->_db->Quote($data['title']);
	    	$this->_db->setQuery($query);
      }      
    }  
    
		if (!$row->bind($data)) {
			$this->setError($row->getError());
			return false;
		}

		if (!$row->id) {			
			$row->ordering = $row->getNextOrder( 'published >= 0 AND category_id='.$row->category_id );
		}

		if (!$row->check()) {
			$this->setError($row->getError());
			return false;
		}

		if (!$row->store()) {
			$this->setError($row->getError());
			return false;
		}
    
    foreach(n3tTemplateHelperContent::getArticlePositions() as $position) {
      if (isset($autotemplates[$position])) 
        $categories = $autotemplates[$position];
      else 
        $categories = array(); 
             
      JArrayHelper::toInteger($categories);        
		  $query = 'SELECT at.category_id FROM #__n3ttemplate_autotemplates AS at' .
				' WHERE at.template_id = '.$row->id.' AND position='.$this->_db->Quote($position);
      $this->_db->setQuery($query);
  		if (JVersion::isCompatible('1.7.0'))
  		  $current = $this->_db->loadColumn();
  		else  			
  		  $current = $this->_db->loadResultArray();      			
        
      $insert = array_diff($categories,$current);    
      $delete = array_diff($current,$categories);
      if (count($delete) || count($categories)) {
        $where=array();
        if (count($delete)) $where[] = 'category_id IN ('.implode(',',$delete).')';
        if (count($categories)) $where[] = 'category_id IN ('.implode(',',$categories).') AND template_id<>'.$row->id;
        $query = 'DELETE FROM #__n3ttemplate_autotemplates WHERE '.
          'position='.$this->_db->Quote($position).
          ' AND ('.implode(' OR ',$where).')';
        $this->_db->setQuery($query);
    		if(!$this->_db->query()) {
    			$this->setError($this->_db->getErrorMsg());
    			return false;
    		}
  		}    		
		  if (count($insert)) {
  		  $query = 'INSERT IGNORE INTO #__n3ttemplate_autotemplates (template_id, category_id, position) VALUES ';
  		  foreach ($insert as & $i) $i = '('.$row->id.','.$i.','.$this->_db->Quote($position).')'; 
  		  $query .= implode(',', $insert);
        $this->_db->setQuery($query);
    		if(!$this->_db->query()) {
    			$this->setError($this->_db->getErrorMsg());
    			return false;
    		}
      }
    }
		return $row->id;
	}

	function copy($cid = array()) {
		foreach ($cid as $id) {
      $row = $this->getTable();
      $row->load($id);
      
      if ($row->title) {
    		$query = 'SELECT id FROM #__n3ttemplate_templates WHERE category_id='.$row->category_id.' AND title='.$this->_db->Quote($row->title);
  	   	$this->_db->setQuery($query);
  		  while (intval($this->_db->loadResult())) { 
          if (preg_match('#\((\d+)\)$#', $row->title, $m)) {
    				$row->title = preg_replace('#\(\d+\)$#', '('.($m[1] + 1).')', $row->title);
    			} else {
    				$row->title .= ' (2)';
    			}
      		$query = 'SELECT id FROM #__n3ttemplate_templates WHERE category_id='.$row->category_id.' AND title='.$this->_db->Quote($row->title);
  	    	$this->_db->setQuery($query);
        }      
      } 			    
      $row->id = 0;
      $row->ordering = $row->getNextOrder( 'published >= 0 AND category_id='.$row->category_id );
      
  		if (!$row->check()) {
  			$this->setError($row->getError());
  			return false;
  		}
      
  		if (!$row->store()) {
  			$this->setError($row->getError());
  			return false;
  		}
  	}
    
    return true;          
	}

	function batch($cid, $data) {
	  foreach ($cid as $id) {	
      $row = $this->getTable();
      $row->load($id);
      
      if ($data['category_id'] > -1
      && ($data['movecopy'] == 'copy' 
      || $row->category_id != $data['catogory_id'])) {
        if ($row->title) {
      		$query = 'SELECT id FROM #__n3ttemplate_templates WHERE category_id='.intval($data['category_id']).' AND title='.$this->_db->Quote($row->title);
    	   	$this->_db->setQuery($query);
    		  while (intval($this->_db->loadResult())) { 
            if (preg_match('#\((\d+)\)$#', $row->title, $m)) {
      				$row->title = preg_replace('#\(\d+\)$#', '('.($m[1] + 1).')', $row->title);
      			} else {
      				$row->title .= ' (2)';
      			}
        		$query = 'SELECT id FROM #__n3ttemplate_templates WHERE category_id='.intval($data['category_id']).' AND title='.$this->_db->Quote($row->title);
    	    	$this->_db->setQuery($query);
          }      
        } 			    
        $row->category_id = $data['category_id'];
        $row->ordering = $row->getNextOrder( 'published >= 0 AND category_id='.$row->category_id );
        if ($data['movecopy'] == 'copy')
          $row->id = 0;
      }
      if ($data['access'] != '')
        $row->access = $data['access'];
      if ($data['display_access'] != '')
        $row->display_access = $data['display_access'];
      
  		if (!$row->check()) {
  			$this->setError($row->getError());
  			return false;
  		}
      
  		if (!$row->store()) {
  			$this->setError($row->getError());
  			return false;
  		}
    }
    
    return true;          
	}

	function remove($cid = array())
	{
		$cids = implode( ',', $cid );
		$query = 'UPDATE #__n3ttemplate_templates SET published=-2, category_id=0, ordering=9999, checked_out=0, checked_out_time='.$this->_db->Quote($this->_db->getNullDate())
			. ' WHERE id IN ( '.$cids.' )';
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	function restore($cid = array())
	{
		$cids = implode( ',', $cid );
		$query = 'UPDATE #__n3ttemplate_templates SET published=0'
			. ' WHERE id IN ( '.$cids.' )';
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	function delete($cid = array())
	{
		$cids = implode( ',', $cid );
		$query = 'DELETE FROM #__n3ttemplate_templates'
			. ' WHERE id IN ( '.$cids.' )';
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$query = 'DELETE FROM #__n3ttemplate_autotemplates'
			. ' WHERE template_id IN ( '.$cids.' )';
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	function publish($cid = array(), $publish = 1)
	{
		$user 	=& JFactory::getUser();

		$cids = implode( ',', $cid );

		$query = 'UPDATE #__n3ttemplate_templates'
			. ' SET published = '.(int) $publish
			. ' WHERE id IN ( '.$cids.' )'
			. ' AND ( checked_out = 0 OR ( checked_out = '.(int) $user->get('id').' ) )'
		;
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	function move($direction)
	{
		$row =& $this->getTable();
		if (!$row->load($this->_id)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->move( $direction, ' published >= 0 AND category_id = '.$row->category_id )) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return true;
	}

	function saveorder($cid = array(), $order)
	{
		$row =& $this->getTable();

		for( $i=0; $i < count($cid); $i++ )
		{
			$row->load( (int) $cid[$i] );

			if ($row->ordering != $order[$i])
			{
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}

		return true;
	}

	function _loadData()
	{
		if (empty($this->_data))
		{
		  if ((int) $this->_id) {
  			$query = 'SELECT t.* FROM #__n3ttemplate_templates AS t' .
  					' WHERE t.id = '.(int) $this->_id;
  			$this->_db->setQuery($query);
  			$this->_data = $this->_db->loadObject();
  			$this->_data->autotemplates = array();
  			
  			foreach(n3tTemplateHelperContent::getArticlePositions() as $position) {
  			  $query = 'SELECT at.category_id FROM #__n3ttemplate_autotemplates AS at' .
  					' WHERE at.position="'.$position.'" AND at.template_id = '.(int) $this->_id;
  			  $this->_db->setQuery($query);  			
  			  if (JVersion::isCompatible('1.7.0'))
  			    $this->_data->autotemplates[$position] = $this->_db->loadColumn();
  			  else  			
  			    $this->_data->autotemplates[$position] = $this->_db->loadResultArray();
        }
            			
			  return (boolean) $this->_data;
			} 
			return false;
		}
		return true;
	}

	function _initData()
	{
		if (empty($this->_data))
		{
			$data = new stdClass();
			$data->id = 0;
			$data->category_id = 0;
			$data->title = null;
			$data->note = null;
			$data->template = null;
			$data->params = null;
			$data->published = 1;
			$data->access = 0;
			$data->display_access = 0;
			$data->checked_out = 0;
			$data->checked_out_time= 0;
			$data->ordering = 0;
			$data->autotemplates = array();
			foreach(n3tTemplateHelperContent::getArticlePositions() as $position) {
			  $data->autotemplates[$position] = array();
			}
			$this->_data = $data;
			return (boolean) $this->_data;
		}
		return true;
	}	
  
}
