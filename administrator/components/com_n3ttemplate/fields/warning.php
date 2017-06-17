<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('JPATH_BASE') or die();

jimport('joomla.form.formfield');

class JFormFieldWarning extends JFormField
{
	protected $type = 'Warning';

	protected function getInput()
	{		
		$doc=& JFactory::getDocument();		
	  $doc->addStyleSheet(JURI::root(true).'/media/com_n3ttemplate/n3ttemplate.css');
	
		$table = $this->element['table'];		
		$warning = $this->element['warning'];
    
    if ($table && $warning) {
      $db =& JFactory::getDBO(); 
		  $tables = $db->getTableList();
		  $table = str_replace( '#__', $db->getPrefix(), $table);
		  if (array_search($table, $tables) === false)
		    return '<span class="elementWarning">'.JText::_($warning).'</span>';    
    }
      
		return '';
	}
}