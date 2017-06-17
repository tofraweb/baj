<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class plgN3tTemplateFile extends JPlugin
{

	function plgN3tTemplateFile(& $subject, $config)
	{
		parent::__construct($subject, $config);
    $this->loadLanguage('', JPATH_ADMINISTRATOR); 		
	}

	function onN3tTemplateItems( $plugin, & $items, $params ) {
	  if ($plugin == $this->_name) {
	    if (!is_object($params)) $params = $this->params;
  	  $db =& JFactory::getDBO();
        
      $limit=(int)$params->def('max_results', '');      
      $max_depth=$params->def('max_depth', '');
      $filter=$params->def('file_types', 'gif,jpg,png');
      if ($filter) {
        $filter=explode(',',$filter);
        $filter=implode('|',$filter);
        $filter='\.('.$filter.')$';
      } 
            
      $current_path = JRequest::getVar('path','');      
      $base_path = JPath::clean(JPATH_ROOT.'/'.$params->def('base_path', 'images').'/');            
      $path = JPath::clean($base_path.$current_path);
      if (strpos($path, $base_path) !== 0) {
        $path = $base_path;
        $current_path = ''; 
      }
            
      if ($current_path) {
        $current_depth = max(count(explode('/',$current_path)),0);
        $current_path.= '/';
      } else
        $current_depth = 0; 
                 
      $folders_count = 0;
      if ($max_depth == '' || $current_depth < $max_depth) {
        $folders = JFolder::folders($path);      
  	    if ($folders) { 
  	      if ($limit) $folders = array_slice($folders, 0, $limit);
          $folders_count = count($folders);           
          foreach ($folders as $folder) { 
    	      $item = new stdClass();
            $item->title=$folder; 
            $item->url='path='.$current_path.$folder;
            $item->category=true;
            $items[] = $item;
          }
        }
      }	              
            
      $files = JFolder::files($path, $filter);      
	    if ($files) { 
	      if ($limit) $files = array_slice($files, 0, $limit - $folders_count);
        foreach ($files as $file) { 
  	      $item = new stdClass();
          $item->title=$file; 
          $item->url='path='.$current_path.$file;
          $items[] = $item;
        }
      }	              
      return true; 
	  }	  
	  return false;
	}
	
	function onN3tTemplateTemplate( $plugin, & $template, $params ) {
	  if ($plugin == $this->_name) {
	    if (!is_object($params)) $params = $this->params;

      $current_path = JRequest::getVar('path','');      
      $base_path = JPath::clean(JPATH_ROOT.'/'.$params->def('base_path', 'images').'/');            
      $path = JPath::clean($base_path.$current_path);
      if (strpos($path, $base_path) !== 0) {
        $path = $base_path;
        $current_path = ''; 
      }
      
	    $base_path = JPath::clean($params->def('base_path', 'images'));
      $pathinfo = pathinfo($current_path);
      if ($pathinfo['dirname'] == '.') $pathinfo['dirname'] = ''; 
      if ($pathinfo['dirname']) $pathinfo['dirname'] .= '/'; 	    
	    
	    switch ($params->def('output', 'link')) {
	      case 'link':
	        $template = '<a href="'.JURI::root().$base_path.$current_path.'">'.$pathinfo['basename'].'</a>';
	        break;
	      case 'allvideos':
	        $template = '{'.$pathinfo['extension'].'}'.$pathinfo['dirname'].$pathinfo['filename'].'{/'.$pathinfo['extension'].'}';
	        break;
	      case 'custom':
	        $template = $params->def('custom_output', '');
      		$search = array(
	         '%BASENAME%',
	         '%FILENAME%',
	         '%EXTENSION%',
	         '%PATH%',
	         '%FILEPATH%',
	         '%BASEPATH%'
          );    	
      		$replace = array(
      		  $pathinfo['basename'],
      		  $pathinfo['filename'],
      		  $pathinfo['extension'],
      		  $pathinfo['dirname'],
      		  $current_path,
      		  $base_path
      		);                      
      		$template = str_replace($search, $replace, $template);            
          break; 
	    }	    
      return true; 
	  }	  
	  return false;
	}

}