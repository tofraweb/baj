<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class plgN3tTemplateModule extends JPlugin
{

	function plgN3tTemplateModule(& $subject, $config)
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
      $published = $params->def('only_published', 0) ? ' AND published=1' : '';
            	  
      $db->setQuery('SELECT DISTINCT id, title FROM #__modules WHERE client_id=0'.$published.' ORDER BY position'.$limit);
      
      $modules = $db->loadObjectList();
	    foreach ($modules as $module) { 
	      $item = new stdClass();
        $item->title=$module->title; 
        $item->url='module='.$module->id;
        $items[] = $item;
      }	              
      return true; 
	  }	  
	  return false;
	}
		
	function onN3tTemplateTemplate( $plugin, & $template, $params ) {
	  if ($plugin == $this->_name) {
	    if (!is_object($params)) $params = $this->params;
	    $db =& JFactory::getDBO();
	    $db->setQuery('SELECT id, title, module FROM #__modules WHERE id='.JRequest::getInt('module',0));
	    $module = $db->loadObject();
	    if ($module) {
	      $module->name = substr($module->module, 4);
  	    switch ($params->def('output', 'link')) {
  	      case 'loadmodule':  	         
  	        $template = '{loadmodule '.$module->name.','.$module->title.'}';
  	        break;
  	      case 'modulesanywhere':  	         
  	        $template = '{module '.$module->title.'}';
  	        break;
  	      case 'custom':
  	        $template = $params->def('custom_output', '');
        		$search = array(
  	         '%MODULE%',
  	         '%ID%',
  	         '%TITLE%',
  	         '%NAME%'
            );    	
        		$replace = array(
        		  $module->module,
        		  $module->id,
        		  $module->title,
        		  $module->name
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