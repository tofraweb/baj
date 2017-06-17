<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class n3tTemplateTableCategory extends JTable
{
	var $id = null;
	var $parent_id = null;
  var $title = null; 
  var $plugin = null; 
  var $note = null; 
  var $params = null;
  var $plugin_params = null; 
	var $published = null;
	var $access = null;
	var $checked_out = 0;
	var $checked_out_time = 0;
	var $ordering = null;	

	function __construct(& $db) {
		parent::__construct('#__n3ttemplate_categories', 'id', $db);
	}

	function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] ))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		if (key_exists( 'plugin_params', $array ) && is_array( $array['plugin_params'] ))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['plugin_params']);
			$array['plugin_params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}

	function check()
	{
		$filter = new JFilterInput(array(), array(), 0, 0);
		$this->title = $filter->clean($this->title);

		if (trim($this->title) == '') {
			$this->setError(JText::_('COM_N3TTEMPLATE_SAVE_MISSING_TITLE'));
			return false;
		}

		$query = 'SELECT id FROM #__n3ttemplate_categories WHERE parent_id='.$this->parent_id.' AND title='.$this->_db->Quote($this->title);
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());		
		if ($xid && $xid != intval($this->id)) {
			$this->setError(JText::_('COM_N3TTEMPLATE_SAVE_DUPLICATE_TITLE'));
			return false;
		}

		return true;
	}
}
