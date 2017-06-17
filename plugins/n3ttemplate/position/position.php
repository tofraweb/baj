<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class plgN3tTemplatePosition extends JPlugin
{

	function plgN3tTemplatePosition(& $subject, $config)
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
            	  
      $db->setQuery('SELECT DISTINCT position FROM #__modules WHERE client_id=0'.$published.' ORDER BY position'.$limit);
      
      $positions = $db->loadObjectList();
	    foreach ($positions as $position) { 
	      $item = new stdClass();
        $item->title=$position->position; 
        $item->url='position='.$position->position;
        $items[] = $item;
      }	              
      return true; 
	  }	  
	  return false;
	}
	
	function onN3tTemplateTemplate( $plugin, & $template, $params ) {
	  if ($plugin == $this->_name) {
	    if (!is_object($params)) $params = $this->params;
	    switch ($params->def('output', 'link')) {
	      case 'loadposition':
	        $template = '{loadposition '.JRequest::getCmd('position','').'}';
	        break;
	      case 'modulesanywhere':
	        $template = '{modulepos '.JRequest::getCmd('position','').'}';
	        break;
	      case 'custom':
	        $template = $params->def('custom_output', '');
      		$search = array(
	         '%POSITION%'
          );    	
      		$replace = array(
      		  JRequest::getCmd('position','')
      		);                      
      		$template = str_replace($search, $replace, $template);            
          break; 
	    }	    
      return true; 
	  }	  
	  return false;
	}

}