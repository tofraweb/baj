<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class plgN3tTemplateAcepolls extends JPlugin
{

	function plgN3tTemplateAcepolls(& $subject, $config)
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
            	  
      $db->setQuery('SELECT DISTINCT id, title FROM #__acepolls_polls'.$published.' ORDER BY '.$ordering.$limit);
      
      $polls = $db->loadObjectList();
      
      if ($polls) { 
  	    foreach ($polls as $poll) { 
  	      $item = new stdClass();
          $item->title=$poll->title; 
          $item->url='id='.$poll->id;
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
	    $db->setQuery('SELECT id, title, alias FROM #__acepolls_polls WHERE id='.JRequest::getInt('id'));
	    $poll = $db->loadObject();
	    if ($poll) {
  	    switch ($params->def('output', 'plugin')) {
  	      case 'plugin':
  	        $template = '{acepolls '.$poll->id.'}';
  	        break;
  	      case 'custom':
  	        $template = $params->def('custom_output', '');
        		$search = array(
  	         '%ID%',
  	         '%ALIAS%',
  	         '%TITLE%'
            );    	
        		$replace = array(
        		  $poll->id,
        		  $poll->alias,
        		  $poll->title
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