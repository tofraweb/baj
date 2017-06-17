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

class n3tTemplateModelCategory extends JModelForm {
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

  public function getTable($name = 'Category', $prefix = 'n3tTemplateTable', $options = array()) 
  {
		return JTable::getInstance($name, $prefix, $options);
	}
    
	public function getForm($data = array(), $loadData = true)
	{
		jimport('joomla.form.form');

		JForm::addFormPath(JPATH_ADMINISTRATOR. DS .'components'. DS .'com_n3ttemplate'. DS .'params');

		$form = $this->loadForm('com_n3ttemplate.category', 'category', array('control' => 'params', 'load_data' => $loadData), false, '/model' );

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	public function getPlugins()
	{
		jimport('joomla.form.form');
		$lang = &JFactory::getLanguage();
		
    $plugins = n3tTemplateHelperPlugin::loadPlugins();    
    foreach ($plugins as &$plugin) {
		  JForm::addFormPath(JPATH_PLUGINS. DS .'n3ttemplate'. DS . $plugin->plugin);             
      $lang->load('plg_n3ttemplate_'.$plugin->plugin,JPATH_ADMINISTRATOR);		

		  $plugin->form = $this->loadForm('com_n3ttemplate.category.'.$plugin->plugin, $plugin->plugin, array('control' => $plugin->plugin.'_params', 'load_data' => true), false, '/extension/config' );

		  if (empty($plugin->form)) $plugin->form = false;
    }
    
		return $plugins;
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

    if (isset($data['use_plugin_params']) && $data['use_plugin_params'] && $data['plugin']) {
      $data['plugin_params'] = $data[$data['plugin'].'_params']['params'];
    } else {
      $data['plugin_params'] = '';
    }
    
    if ($copy && $data['title']) {
  		$query = 'SELECT id FROM #__n3ttemplate_categories WHERE parent_id='.$data['parent_id'].' AND title='.$this->_db->Quote($data['title']); 
	   	$this->_db->setQuery($query);
		  while (intval($this->_db->loadResult())) { 
        if (preg_match('#\((\d+)\)$#', $data['title'], $m)) {
  				$data['title'] = preg_replace('#\(\d+\)$#', '('.($m[1] + 1).')', $data['title']);
  			} else {
  				$data['title'] .= ' (2)';
  			}
    		$query = 'SELECT id FROM #__n3ttemplate_categories WHERE parent_id='.$data['parent_id'].' AND title='.$this->_db->Quote($data['title']);
	    	$this->_db->setQuery($query);
      }      
    }      

		if (!$row->bind($data)) {
			$this->setError($row->getError());
			return false;
		}

		if (!$row->id) {			
			$row->ordering = $row->getNextOrder( 'published >= 0 AND parent_id='.$row->parent_id );
		}

		if (!$row->check()) {
			$this->setError($row->getError());
			return false;
		}

		if (!$row->store()) {
			$this->setError($row->getError());
			return false;
		}
		
		if ($row->plugin) {
		  $this->_db->setQuery('SELECT count(id) from #__n3ttemplate_categories WHERE parent_id='.$row->id);
		  $count = $this->_db->loadResult();
		  if ($count) JError::raiseWarning(1, JText::_('COM_N3TTEMPLATE_PLUGIN_HAS_CATEGORIES'));
		  $this->_db->setQuery('SELECT count(id) FROM #__n3ttemplate_temapltes WHERE category_id='.$row->id);
		  $count = $this->_db->loadResult();
		  if ($count) JError::raiseWarning(1, JText::_('COM_N3TTEMPLATE_PLUGIN_HAS_TEMPLATES'));
		}
		return $row->id;
	}

	function copy($cid = array()) {
	  foreach ($cid as $id) {
	    $row = $this->getTable();
	    $row->load($id);
      if ($row->title) {
    		$query = 'SELECT id FROM #__n3ttemplate_categories WHERE parent_id='.$row->parent_id.' AND title='.$this->_db->Quote($row->title); 
  	   	$this->_db->setQuery($query);
  		  while (intval($this->_db->loadResult())) { 
          if (preg_match('#\((\d+)\)$#', $row->title, $m)) {
    				$row->title = preg_replace('#\(\d+\)$#', '('.($m[1] + 1).')', $row->title);
    			} else {
    				$row->title .= ' (2)';
    			}
      		$query = 'SELECT id FROM #__n3ttemplate_categories WHERE parent_id='.$row->parent_id.' AND title='.$this->_db->Quote($row->title);
  	    	$this->_db->setQuery($query);
        }                  
      } 			    
      $row->id = 0;
      $row->ordering = $row->getNextOrder( 'published >= 0 AND parent_id='.$row->parent_id );
      
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
	    
	    if ($data['parent_id'] > -1 
      && $data['movecopy'] == 'move' 
      && $row->parent_id != $data['parent_id']) {
        $parent_id = $data['parent_id'];
        while ($parent_id) {
          if ($parent_id == $row->id) {
    	   		$this->setError(JText::_('COM_N3TTEMPLATE_CATEGORY_RECURSION'));
      			return false;            
          } 
          $query = 'SELECT parent_id FROM #__n3ttemplate_categories WHERE id='.intval($parent_id);
          $this->_db->setQuery($query);
          $parent_id = $this->_db->loadResult();
        }
      }

	    if ($data['parent_id'] > -1 
      && ($data['movecopy'] == 'copy' 
      || $row->parent_id != $data['parent_id'])) {
        if ($row->title) {
      		$query = 'SELECT id FROM #__n3ttemplate_categories WHERE parent_id='.intval($data['parent_id']).' AND title='.$this->_db->Quote($row->title); 
    	   	$this->_db->setQuery($query);
    		  while (intval($this->_db->loadResult())) { 
            if (preg_match('#\((\d+)\)$#', $row->title, $m)) {
      				$row->title = preg_replace('#\(\d+\)$#', '('.($m[1] + 1).')', $row->title);
      			} else {
      				$row->title .= ' (2)';
      			}
        		$query = 'SELECT id FROM #__n3ttemplate_categories WHERE parent_id='.intval($data['parent_id']).' AND title='.$this->_db->Quote($row->title);
    	    	$this->_db->setQuery($query);
          }                  
        } 			    
        $row->parent_id = $data['parent_id'];
        $row->ordering = $row->getNextOrder( 'published >= 0 AND parent_id='.$row->parent_id );
        if ($data['movecopy'] == 'copy')
          $row->id = 0;                      
      }
      if ($data['access'] != '')
        $row->access = $data['access'];
        
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
		$query = 'UPDATE #__n3ttemplate_categories SET published=-2, parent_id=0, ordering=9999, checked_out=0, checked_out_time='.$this->_db->Quote($this->_db->getNullDate())
			. ' WHERE id IN ( '.$cids.' )';
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$query = 'UPDATE #__n3ttemplate_categories SET parent_id=0 WHERE parent_id IN ( '.$cids.' )';
		$this->_db->setQuery( $query );
		if(!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		$query = 'UPDATE #__n3ttemplate_templates SET category_id=0 WHERE category_id IN ( '.$cids.' )';
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
		$query = 'UPDATE #__n3ttemplate_categories SET published=0'
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
		$query = 'DELETE FROM #__n3ttemplate_categories'
			. ' WHERE id IN ( '.$cids.' )';
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

		$query = 'UPDATE #__n3ttemplate_categories'
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

		if (!$row->move( $direction, ' published >= 0 AND parent_id = '.$row->parent_id )) {
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
  			$query = 'SELECT c.* FROM #__n3ttemplate_categories AS c' .
  					' WHERE c.id = '.(int) $this->_id;
  			$this->_db->setQuery($query);
  			$this->_data = $this->_db->loadObject();
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
			$data->parent_id = 0;
			$data->title = null;
			$data->plugin = null;
			$data->note = null;
			$data->params = null;
			$data->plugin_params = null;
			$data->published = 1;
      $data->access = 0;
			$data->checked_out = 0;
			$data->checked_out_time= 0;
			$data->ordering = 0;
			$this->_data = $data;
			return (boolean) $this->_data;
		}
		return true;
	}
}