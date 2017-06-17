<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class plgN3tTemplatePhocamaps extends JPlugin
{

	function plgN3tTemplatePhocamaps(& $subject, $config)
	{
		parent::__construct($subject, $config);
    $this->loadLanguage('', JPATH_ADMINISTRATOR); 		
	}

	function onN3tTemplateItems( $plugin, & $items, $params ) {
	  if ($plugin == $this->_name) {
	    if (!is_object($params)) $params = $this->params;
  	  $db =& JFactory::getDBO();
        
      $limit=(int)$params->def('max_results', '');
      $limit = $limit ? $limit=' LIMIT 0,'.$limit : '';
      $published = $params->def('only_published', 0) ? ' WHERE published=1' : '';
      switch($params->def('ordering', 'idasc')) {
        case 'idasc':
        default:
          $ordering = 'id ASC';
          break;
        case 'iddesc':
          $ordering = 'id DESC';
          break;           
        case 'titleasc':
          $ordering = 'title ASC, id ASC';
          break;           
        case 'titledesc':
          $ordering = 'title DESC, id DESC';
          break;           
      }      
            	  
      $db->setQuery('SELECT DISTINCT id, title FROM #__phocamaps_map'.$published.' ORDER BY '.$ordering.$limit);
      
      $maps = $db->loadObjectList();
      
      if ($maps) { 
  	    foreach ($maps as $map) { 
  	      $item = new stdClass();
          $item->title=$map->title; 
          $item->url='id='.$map->id;
          $items[] = $item;
        }	              
        return true;
      } 
	  }	  
	  return false;
	}
	
	function onN3tTemplateTemplate( $plugin, & $template, $params ) {
	  if ($plugin == $this->_name) {
	    if (!is_object($params)) $params = $this->params;
	    $db =& JFactory::getDBO();
	    $db->setQuery('SELECT id, title, alias, description FROM #__phocamaps_map WHERE id='.JRequest::getInt('id'));
	    $map = $db->loadObject();
	    if ($map) {
  	    switch ($params->def('output', 'link')) {
  	      case 'map':
  	        $template = '{phocamaps view=map|id='.$map->id.'}';
  	        break;
  	      case 'link':
  	        $template = '{phocamaps view=link|id='.$map->id.'|text='.$map->title.'}';
  	        break;
  	      case 'custom':
  	        $template = $params->def('custom_output', '');
        		$search = array(
  	         '%ID%',
  	         '%ALIAS%',
  	         '%TITLE%',
  	         '%DESCRIPTION%'
            );    	
        		$replace = array(
        		  $map->id,
        		  $map->alias,
        		  $map->title,
        		  $map->description
        		);                      
        		$template = str_replace($search, $replace, $template);            
            break; 
  	    }	    
        return true;
      } 
	  }	  
	  return false;
	}

}