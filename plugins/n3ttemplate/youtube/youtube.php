<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );


class plgN3tTemplateYoutube extends JPlugin
{
  private $_entries = null;  
  private $_entry = false;
  private $_tag = '';
  
	function plgN3tTemplateYoutube(& $subject, $config)
	{
		parent::__construct($subject, $config);
    $this->loadLanguage('', JPATH_ADMINISTRATOR); 		
	}

	protected function _startElement($parser, $name, $attrs = Array()) {	  
		switch($name) {
			case 'ENTRY':
			  $this->_entry = new stdClass();
			  $this->_entry->id = '';
			  $this->_entry->title = '';
			  $this->_entry->description = '';
			  $this->_entry->content = array('1' => '', '5' => '', '6' => '');
			  $this->_entry->player = '';
			  $this->_entry->thumbnails = array('hqdefault' => false, 'default' => false, 'poster' => false, 'start' => false, 'middle' => false, 'end' => false);
				break;
			case 'MEDIA:CONTENT':
        if (is_object($this->_entry)) { 			  
          $this->_entry->content[$attrs['YT:FORMAT']] = $attrs['URL'];
        }
				break;
      case 'MEDIA:PLAYER':
        if (is_object($this->_entry))        
          $this->_entry->player = $attrs['URL'];
				break;
      case 'MEDIA:THUMBNAIL':
        if (is_object($this->_entry))           
          $this->_entry->thumbnails[$attrs['YT:NAME']] = array('url' => $attrs['URL'], 'height' => $attrs['HEIGHT'], 'width' => $attrs['WIDTH']);
				break;
		}
		$this->_tag = $name;
	}

	protected function _endElement($parser, $name)
	{
		switch($name) {
			case 'ENTRY':
			  if (is_object($this->_entry) && $this->_entry->id) {
	        if (!$this->_entry->player) $this->_entry->player = 'http://www.youtube.com/watch?v='.$this->_entry->id;			    
          $this->_entries[] = $this->_entry;
        }
        $this->_entry = false;  
				break;
		}
	}

	protected function _characterData($parser, $data)
	{
		switch($this->_tag) {
			case 'YT:VIDEOID':
			  if (is_object($this->_entry)) {
		      $this->_entry->id = $data;
			  }
				break;
			case 'TITLE':
			  if (is_object($this->_entry))  
			    $this->_entry->title .= $data;			    
				break;
			case 'MEDIA:TITLE':
			  if (is_object($this->_entry) && !$this->_entry->title)  
			    $this->_entry->title = $data;
				break;
			case 'MEDIA:DESCRIPTION':
			  if (is_object($this->_entry))  
			    $this->_entry->description .= $data;
				break;
		}
	}
	
  function _youtubeFeed($url) {
    if (!($fp = @fopen($url, "r"))) return false;
    $this->_entries = array();
    $this->_entry = false;
    $this->_tag = '';
    $xml_parser = xml_parser_create('');
		xml_set_object($xml_parser, $this);
		xml_set_element_handler($xml_parser, '_startElement', '_endElement');
		xml_set_character_data_handler($xml_parser, '_characterData');
		while ($data = fread($fp, 8192)) {
			if (!xml_parse($xml_parser, $data, feof($fp))) {
			  xml_parser_free($xml_parser);
			  return false; 
			}
		}
		xml_parser_free($xml_parser);
    return $this->_entries;         
  }
  
	function onN3tTemplateItems( $plugin, & $items, $params ) {
	  if ($plugin == $this->_name) {      	      
	    if (!is_object($params)) $params = $this->params;
	    $source = $params->def('source', 'standard_feed'); 
	    $url = '';	    
	    switch ($source) {
	      case 'standard_feed':
	        $feed_mode = $params->def('standard_feed_mode', 'most_viewed');
	        $region = $params->def('standard_feed_region', '');           
	        $category = $params->def('standard_feed_category', '');
	        $url = 'https://gdata.youtube.com/feeds/api/standardfeeds/';
	        if ($region) $url .= $region.'/';
          $url .= $feed_mode;
          if ($category) $url .= '_'.$category;              
          break;
        case 'user_uploads':
        case 'user_favorites':
          $user = $params->def('user', '');
          if ($source == 'user_uploads') 
            $url = 'https://gdata.youtube.com/feeds/api/users/'.trim($user).'/uploads';
          else if ($source = 'user_favorites')    
            $url = 'https://gdata.youtube.com/feeds/api/users/'.trim($user).'/favorites';
          break; 
        case 'playlist':
          $playlist = $params->def('playlist', '');
          $url = 'https://gdata.youtube.com/feeds/api/playlists/'.trim($playlist);
          break;
	    }
	    
      if ($url) { 
        $url .= '?v=2';                
        $max_results = $params->def('max_results', '');
        $max_results = (int)$max_results;
        if ($max_results) $url .= '&max-results='.$max_results;
        $format = $params->def('format', '');
        if ($format) $url .= '&format='.$format;
        $videos =& $this->_youtubeFeed($url);    	             
        if ($videos) {  
    	    foreach ($videos as $video) { 
    	      $item = new stdClass();
            $item->title=$video->title; 
            $item->url='videoid='.$video->id;
            $item->description=$video->description;
            $items[] = $item;
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
	    $videos =& $this->_youtubeFeed('http://gdata.youtube.com/feeds/api/videos/'.JRequest::getCmd('videoid','').'?v=2');
      $template = '';       
	    if ($videos && count($videos)==1) {
        $video = $videos[0]; 
	      if ($video && $video->id) {
    	    switch ($params->def('output', 'link')) {
    	      case 'link':
   	          $template = '<a href="'.$video->player.'" title="'.$video->title.'">'.$video->title.'</a>';
    	        break;	
    	      case 'bigpreview':
    	        if ($video->thumbnails['hqdefault'])
    	          $template = '<a href="'.$video->player.'" title="'.$video->title.'"><img src="'.$video->thumbnails['hqdefault']['url'].'" alt="'.$video->title.'" width="'.$video->thumbnails['hqdefault']['width'].'" height="'.$video->thumbnails['hqdefault']['height'].'"/></a>';
    	        break;	
    	      case 'smallpreview':
    	        if (isset($video->thumbnails['default']))
    	          $template = '<a href="'.$video->player.'" title="'.$video->title.'"><img src="'.$video->thumbnails['default']['url'].'" alt="'.$video->title.'" width="'.$video->thumbnails['default']['width'].'" height="'.$video->thumbnails['default']['height'].'"/></a>';
    	        break;	
    	      case 'jw_allvideo':
    	        $template = '{youtube}'.$video->id.'{/youtube}'; 
    	        break;	        
    	      case 'custom':
    	        $template = $params->def('custom_output', '');
          		$search = array(
  		         '%ID%',
  		         '%TITLE%',
  		         '%DESCRIPTION%',
  		         '%PLAYER%',
  		         '%CONTENT_1%','%CONTENT_5%','%CONTENT_6%',
  		         '%THUMBNAIL_HQDEFAULT%','%THUMBNAIL_HQDEFAULT_WIDTH%','%THUMBNAIL_HQDEFAULT_HEIGHT%',
               '%THUMBNAIL_DEFAULT%','%THUMBNAIL_DEFAULT_WIDTH%','%THUMBNAIL_DEFAULT_HEIGHT%',
               '%THUMBNAIL_POSTER%','%THUMBNAIL_POSTER_WIDTH%','%THUMBNAIL_POSTER_HEIGHT%',
               '%THUMBNAIL_START%','%THUMBNAIL_START_WIDTH%','%THUMBNAIL_START_HEIGHT%',
               '%THUMBNAIL_MIDDLE%','%THUMBNAIL_MIDDLE_WIDTH%','%THUMBNAIL_MIDDLE_HEIGHT%',
               '%THUMBNAIL_END%','%THUMBNAIL_END_WIDTH%','%THUMBNAIL_END_HEIGHT%'
		          );
                  	
          		$replace = array(
          		  $video->id,
          		  $video->title,
          		  $video->description,
          		  $video->player,
          		  $video->content['1'],$video->content['5'],$video->content['6'],
          		  $video->thumbnails['hqdefault']['url'],$video->thumbnails['hqdefault']['width'],$video->thumbnails['hqdefault']['height'],
          		  $video->thumbnails['default']['url'],$video->thumbnails['default']['width'],$video->thumbnails['default']['height'],
          		  $video->thumbnails['poster']['url'],$video->thumbnails['poster']['width'],$video->thumbnails['poster']['height'],
          		  $video->thumbnails['start']['url'],$video->thumbnails['start']['width'],$video->thumbnails['start']['height'],
          		  $video->thumbnails['middle']['url'],$video->thumbnails['middle']['width'],$video->thumbnails['middle']['height'],
          		  $video->thumbnails['end']['url'],$video->thumbnails['end']['width'],$video->thumbnails['end']['height']
          		);                      
          		$template = str_replace($search, $replace, $template); 
    	        break;	                          
    	    }
        }
      }
      return true; 
	  }	  
	  return false;
	}

}