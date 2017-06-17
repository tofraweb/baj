<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.version');
jimport('joomla.plugin.helper');

class n3tTemplateHelperPlugin extends JObject
{
  
	public static function loadPlugins() {
		static $plugins;
		if (!isset ($plugins)) {
  	  $db =& JFactory::getDBO();  
      if (JVersion::isCompatible('1.6.0')) {
        $db->setQuery('SELECT element AS plugin, params, enabled AS published, name FROM #__extensions WHERE type="plugin" AND folder="n3ttemplate"');
      } else {
        $db->setQuery('SELECT element AS plugin, params, published, name FROM #__plugins WHERE folder="n3ttemplate"');
      }	 
      $plugins = $db->loadObjectList('plugin');
      $lang = &JFactory::getLanguage();			
      foreach ($plugins as & $plugin) {
        $lang->load('plg_n3ttemplate_'.$plugin->plugin,JPATH_ADMINISTRATOR);
        $plugin->name = JText::_($plugin->name);
        $plugin->name = preg_replace('/^n3ttemplate\s*-?\s*/i', '', $plugin->name);
      }
		}
		return $plugins;  	    
	}
  
	public static function loadItems($plugin, $params) {
	  $items = array();
	  JPluginHelper::importPlugin('n3ttemplate');
	  if ($params) $params = new JParameter( $params );
    $dispatcher =& JDispatcher::getInstance();        
    $dispatcher->trigger('onN3tTemplateItems', array ($plugin, & $items, $params));
    return $items;    	    
	}

	public static function loadTemplate($plugin, $params) {    
	  $template = '';
	  JPluginHelper::importPlugin('n3ttemplate');
    $dispatcher =& JDispatcher::getInstance();    
    if ($params) $params = new JParameter( $params );
    $dispatcher->trigger('onN3tTemplateTemplate', array ($plugin, & $template, $params));
    return $template; 	   
	}
	
}
?>