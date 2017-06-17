<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.version');

class n3tTemplateHelperButton extends JObject
{

	public static function getButtonAccess() {
	  $db =& JFactory::getDBO();  
    if (JVersion::isCompatible('1.6.0')) {
      $db->setQuery('SELECT access FROM #__extensions WHERE type="plugin" AND element="n3ttemplate" AND folder="editors-xtd"');
    } else {
      $db->setQuery('SELECT access FROM #__plugins WHERE element="n3ttemplate" AND folder="editors-xtd"');
    }	 
    $access = $db->loadResult();
    return $access ? $access : 0; 
	}
	
	public static function setButtonAccess($access) {
	  $db =& JFactory::getDBO();  	  
    if (JVersion::isCompatible('1.6.0')) {
      $db->setQuery('UPDATE #__extensions SET access='.$access.' WHERE type="plugin" AND element="n3ttemplate" AND folder="editors-xtd"');
    } else {
      $db->setQuery('UPDATE #__plugins SET access='.$access.' WHERE element="n3ttemplate" AND folder="editors-xtd"');
    }	 
    $db->query();     
  }

}
?>