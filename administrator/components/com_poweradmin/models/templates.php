<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: templates.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.modeladmin');

class PoweradminModelTemplates extends JModelAdmin
{
	public function getForm($data = array(), $loadData = true){}	
    
	/**
	 * 
	 * Get all templates was installed
	 */
	public function getTemplates()
	{
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("s.*, e.extension_id AS tid");
        $query->from("#__template_styles AS s");
        $query->join('LEFT', "#__extensions AS e ON e.element = s.template ");
        $query->where("s.client_id=0");
        $query->order("s.template ASC");
        $db->setQuery($query);
		
		return $db->loadObjectList();
	}
	
	/**
	 * 
	 * Get template by id
	 * @param Number $id
	 */
	public function setDefaultTemplate($id)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update("#__template_styles");
		$query->set("home = 0");
		$query->where("client_id=0");
		$db->setQuery($query);
		if ($db->query()){
			$query->clear();
			$query->update("#__template_styles");
			$query->set("home = 1");
			$query->where("id=".$db->quote($id));
			$db->setQuery($query);
			$db->query();
		}
	}
	
	/**
	 * 
	 * Get latest Style
	 */
	public function getLatestStyle()
	{
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select("s.id, s.template, s.title, s.home, e.extension_id AS tid");
        $query->from("#__template_styles AS s");
        $query->join('LEFT', "#__extensions AS e ON e.element = s.template ");
        $query->where("s.client_id=0");
        $query->order("s.id DESC");
		$query->limit("1");
        $db->setQuery($query);
		
		return $db->loadObject();
	}
}
?>