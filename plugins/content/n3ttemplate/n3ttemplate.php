<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );
jimport('joomla.version');

class plgContentN3tTemplate extends JPlugin
{

	function plgContentN3tTemplate(& $subject, $config)
	{
		parent::__construct($subject, $config); 		
	}
	
	// Joomla 1.5
	function onPrepareContent( &$article, &$params, $limitstart )
	{ 
    if (isset($article->catid))
  	  $this->_proccessAuto($article);
	  $this->_proccess($article);
	}
  
  // Joomla 1.6	
  public function onContentPrepare($context, &$article, &$params, $page = 0) 	
	{ 
	  if ($context == 'com_content.article') {
      if (isset($article->catid))
        $this->_proccessAuto($article);
    }
    $this->_proccess($article);
	}
	
  public function onContentBeforeDisplay($context, &$article, &$params, $page=0) 
  {    
    if ($context == 'com_content.article') {
      // bug 24340 workaround
      if (!isset($article->text)) {
      if (isset($article->catid))
        $this->_proccessAuto($article);
      }
    }
  }

	private function _proccess(&$article) 
  {      
    if (strpos($article->text, '{n3ttemplate') === false) return true;       
    $regex		= '/{n3ttemplate\s+(.*?)}/i';
    preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
		if ($matches){
			foreach ($matches as $match) {
				$id = trim($match[1]);
				$output = $this->_load($id);
				$article->text = preg_replace("|$match[0]|", addcslashes($output, '\\'), $article->text, 1);
			}
		}
  }           		
	
	private function _proccessAuto(&$article) 
  {        	
    $category_id = $article;
    if (isset($article->catid) && $article->catid) {
      $user = & JFactory::getUser();
      if (JVersion::isCompatible('1.6.0')) 			
    		$access = 't.display_access IN ('.implode(',', $user->getAuthorisedViewLevels()).') ';    
      else 
        $access = 't.display_access<='.$user->get('aid', 0).' ';
        
  		$db =& JFactory::getDBO();
  		$db->setQuery( 'SELECT t.id, at.position '.
        'FROM #__n3ttemplate_autotemplates AS at, #__n3ttemplate_templates AS t '.      
        'WHERE t.id=at.template_id AND at.category_id='.$article->catid.' AND t.published=1 AND '.$access );
      $autotemplates = $db->loadObjectList();
      if (JVersion::isCompatible('1.6.0'))
        $is_introtext = !isset($article->text);
      else 
        $is_introtext = $article->text == $article->introtext;       
      if ($autotemplates) {
        foreach($autotemplates as $autotemplate) {
          switch ($autotemplate->position) {
            case "top":
              if (!$is_introtext) {
                if (isset($article->text))
                  $article->text = $this->_load($autotemplate->id).$article->text;
              }                            
              break;
            case "bottom":
              if (!$is_introtext) {
                if (isset($article->text))
                  $article->text.= $this->_load($autotemplate->id);
              }
              break;
            case "introtop":
              if ($is_introtext) {
                if (isset($article->text))
                  $article->text = $this->_load($autotemplate->id).$article->text;
                elseif (isset($article->introtext))
                  $article->introtext = $this->_load($autotemplate->id).$article->introtext;
              }
              break;
            case "introbottom":
              if ($is_introtext) {
                if (isset($article->text))
                  $article->text.= $this->_load($autotemplate->id);
                elseif (isset($article->introtext))
                  $article->introtext.= $this->_load($autotemplate->id);
              }
              break;
          }
        }
      }      		
    }
	}
			
	private function _load($id)
	{
	  $id = (int)$id;
	  if ($id) {
      $user = & JFactory::getUser();
      if (JVersion::isCompatible('1.6.0')) 			
  			$access = 'display_access IN ('.implode(',', $user->getAuthorisedViewLevels()).') ';    
      else 
        $access = 'display_access<='.$user->get('aid', 0).' ';
        
	    $db =& JFactory::getDBO();
  		$db->setQuery( 'SELECT template '.
        'FROM #__n3ttemplate_templates '.
        'WHERE id='.$id.' AND published=1 AND '.$access );
      return $db->loadResult();        		    
	  }
	  return "";
  }	
}