<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.model');
jimport('joomla.html.parameter');

class n3tTemplateModelButton extends JModel
{  
 
	public function __construct()
	{
		parent::__construct();
	}

  function getTemplate() {
    $user = & JFactory::getUser();
    if (JVersion::isCompatible('1.6.0')) 			
			$access = 'access IN ('.implode(',', $user->getAuthorisedViewLevels()).') ';    
    else 
      $access = 'access<='.$user->get('aid', 0).' ';
          
    $category_id = JRequest::getInt('category',0);
    if ($category_id) {        
		  $this->_db->setQuery( 'SELECT id, plugin, plugin_params '.
        'FROM #__n3ttemplate_categories '.
        'WHERE id='.$category_id.' AND published=1 AND '.$access );
      $category = $this->_db->loadObject();      
      if ($category && $category->plugin) {
        if ($category->plugin)
          return n3tTemplateHelperPlugin::loadTemplate($category->plugin, $category->plugin_params);
          
    		$this->_db->setQuery( 'SELECT template '.
          'FROM #__n3ttemplate_templates '.
          'WHERE id='.JRequest::getInt('id',0).' AND published=1 AND '.$access );
        return $this->_db->loadResult();        
      }   
    } else {
  		$this->_db->setQuery( 'SELECT template '.
        'FROM #__n3ttemplate_templates '.
        'WHERE id='.JRequest::getInt('id',0).' AND published=1 AND '.$access );
      return $this->_db->loadResult();        
    }    
    return '';  
  }
  
  function getAutoTemplate() {
    $user = & JFactory::getUser();
    if (JVersion::isCompatible('1.6.0')) 			
			$access = 't.display_access IN ('.implode(',', $user->getAuthorisedViewLevels()).') ';    
    else 
      $access = 't.display_access<='.$user->get('aid', 0).' ';

		$this->_db->setQuery( 'SELECT t.template '.
      'FROM #__n3ttemplate_autotemplates at '.
      'LEFT JOIN #__n3ttemplate_templates t ON t.id=at.template_id '.
      'WHERE at.position="editor" AND at.category_id='.JRequest::getInt('id',0).' AND t.published=1 AND '.$access );
    return $this->_db->loadResult();        
  }
  
  function _loadXml($id = 0, $plugin = null, $plugin_params = null, $indent = "\t") {
		if ($plugin) {		  		  
		  $items = n3tTemplateHelperPlugin::loadItems($plugin, $plugin_params);
      foreach ($items as &$item) {
        $item->id = $id;
        if (!isset($item->title)) $item->title = '';
        $item->plugin = $plugin;
        $item->plugin_params = $plugin_params;
        if (!isset($item->category)) $item->category = false;
        $item->params = null;
        if (!isset($item->load_expanded)) $item->load_expanded = false;
        if (!isset($item->url)) $item->url = ''; 
        if (!isset($item->description)) $item->description = '';
        $item->url = 'category='.$id.($item->url ? '&amp;'.$item->url : '');
        $item->native = 0;                       
      }       
		} else {
      $user = & JFactory::getUser();
      if (JVersion::isCompatible('1.6.0')) 			
  			$access = 'access IN ('.implode(',', $user->getAuthorisedViewLevels()).') ';    
      else 
        $access = 'access<='.$user->get('aid', 0).' ';    
		
  		$this->_db->setQuery( 'SELECT id, null as category_id, title, plugin, plugin_params, 1 AS category, params, note AS description, 1 AS native '.
        'FROM #__n3ttemplate_categories '.
        'WHERE parent_id='.$id.' AND published=1 AND '.$access.
        'ORDER BY ordering ASC' );
  		$items = $this->_db->loadObjectList();

  		$this->_db->setQuery( 'SELECT id, category_id, title, null as plugin, null as plugin_params, 0 AS category, params, note AS description, 1 AS native '.
        'FROM #__n3ttemplate_templates '.
        'WHERE category_id='.$id.' AND published=1 AND '.$access.
        'ORDER BY ordering ASC' );
      $items = array_merge($items, $this->_db->loadObjectList());
    }      
    
    $return = '';    
		foreach ($items as &$item) {
      if (!$plugin) {  		  
		    if ($item->category) {
	        $params = new JParameter( $item->params, JPATH_COMPONENT.DS.'params'.DS.'category.xml' );  
  	      $item->load_expanded = $params->get('load_expanded', 0);
          $item->url = 'category='.$item->id;
        } else { 
	        //$params = new JParameter( $item->params, JPATH_COMPONENT.DS.'params'.DS.'template.xml' );
	        $item->url = 'id='.$item->id;
	      }	      
	    }
  		$return.= "\n".$indent.'<node';
  		$return.= ' text="'.htmlspecialchars($item->title,ENT_COMPAT, 'UTF-8').'"';
  		if ($item->category) {
  		  $return.= ' icon="_closed" openicon="_open"';
  		  if ($item->load_expanded) {  
          $return.= ' open="true"'.($item->description ? ' title="'.htmlspecialchars($item->description,ENT_COMPAT, 'UTF-8').'"' : '').'>';
          $return.= $this->_loadXml($item->id,$item->plugin,$item->plugin_params,$indent."\t");
          $return.= "\n".$indent.'</node>';
        } else {          
          $return.= ' load="'. JURI::base().'index.php?option=com_n3ttemplate&amp;view=button&amp;format=xml&amp;'.$item->url.'"';
          $return.= ($item->description ? ' title="'.htmlspecialchars($item->description,ENT_COMPAT, 'UTF-8').'"' : '').' />';
        }
      } else {
        $return.= ' icon="_doc" template="'.$item->url.'"';
        if ($item->native) $return.= ' templateid="'.$item->id.'"';
        $return.= ($item->description ? ' title="'.htmlspecialchars($item->description,ENT_COMPAT, 'UTF-8').'"' : '').' />';
      }
    }            
    return $return;				  
  }

  function getXml() {
    $category_id = JRequest::getInt('category',0);
    $return = '<?xml version="1.0" encoding="UTF-8"?'.'>'."\n";
    $return.= '<nodes>';
    if ($category_id) {
      $user = & JFactory::getUser();    
      if (JVersion::isCompatible('1.6.0')) 			
  			$access = 'access IN ('.implode(',', $user->getAuthorisedViewLevels()).') ';    
      else 
        $access = 'access<='.$user->get('aid', 0).' ';    
          
  		$this->_db->setQuery( 'SELECT id, plugin, plugin_params '.
        'FROM #__n3ttemplate_categories '.
        'WHERE id='.$category_id.' AND published=1 AND '.$access );        
      $category = $this->_db->loadObject();      
      if ($category) {      
        $return.=$this->_loadXml($category->id, $category->plugin, $category->plugin_params)."\n";
      }
    } else { 
      $return.=$this->_loadXml()."\n";
    }
    $return.= '</nodes>';
    return $return;
  }

}