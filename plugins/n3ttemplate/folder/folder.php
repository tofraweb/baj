<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class plgN3tTemplateFolder extends JPlugin
{

	function plgN3tTemplateFolder(& $subject, $config)
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
      $show_empty=(int)$params->def('show_empty', '');

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
                 
      if ($max_depth == '' || $current_depth < $max_depth) {
        $folders = JFolder::folders($path);      
  	    if ($folders) { 
  	      if ($limit) $folders = array_slice($folders, 0, $limit);                     
          foreach ($folders as $folder) {
            $subfolders = JFolder::folders($path.'/'.$folder);
            $subfiles = JFolder::files($path.'/'.$folder,'.',false,false,array('.svn', 'CVS','index.html'));
            if ($subfolders) $subfolders_count = count($subfolders);
            else $subfolders_count = 0;
            if ($subfiles) $subfiles_count = count($subfiles);
            else $subfiles_count = 0;
            if ($show_empty || $subfolders_count + $subfiles_count > 0) { 
      	      $item = new stdClass();
              $item->title=$folder; 
              $item->url='path='.$current_path.$folder;            
              $item->category=($current_depth+1 < $max_depth) || $subfolders_count > 0;            
              $items[] = $item;
            }
          }
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
	    
	    switch ($params->def('output', 'link')) {
	      case 'simapleimagegallery':
	        $template = '{gallery}'.$current_path.'{/gallery}';
	        break;
	      case 'ppgallery':  
	        $template = '{ppgallery}'.$current_path.'{/ppgallery}';
	        break;
	      case 'cssgallery':  
	        $template = '{becssg}'.$current_path.'{/becssg}';
	        break;
	      case 'mp3browser':  
	        $template = '{music}/'.$base_path.'/'.$current_path.'{/music}';
	        break;
	      case 'custom':
	        $template = $params->def('custom_output', '');
      		$search = array(
	         '%PATH%',
	         '%BASEPATH%'
          );    	
      		$replace = array(
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