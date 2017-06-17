<?php
/**
 * @version		1.6.0
 * @package		com_contactenhanced
 * @copyright	Copyright (C) 2006 - 2010 Ideal Custom Software Development
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

require_once (dirname(__FILE__).'/switch.php');
/**
 * Form Field class for the Joomla Framework.
 *
 * @package		com_contactenhanced
* @since		1.6
 */
class JFormFieldCustomfieldtype extends JFormFieldSwitch
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Customfieldtype';

	
	
	/**
	 * Method to get the field options.
	 *
	 * @return	array	The field option objects.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();
		$groupedlist= array();
		
		$options[]	= JHTML::_( 'select.option', 0, 			JText::_( 'MOD_AJAXCONTACT_DO_NOT_SHOW' ) );
		$options[]	= JHTML::_( 'select.optgroup', JText::_('One per Form') );
		
		//$options[]	= JHTML::_( 'select.optgroup', JText::_('One per Category') );
		$options[]	= JHTML::_( 'select.option', "name", 		JText::_( 'Name' ) );
		$options[]	= JHTML::_( 'select.option', "lastname", 	JText::_( 'Last name' ) );
		$options[]	= JHTML::_( 'select.option', "email", 		JText::_( 'Email' ) );
		$options[]	= JHTML::_( 'select.option', "subject", 	JText::_( 'Subject' ) );
		$options[]	= JHTML::_( 'select.option', "cc", 			JText::_( 'Carbon copy' ) );
		
		$options[]	= JHTML::_( 'select.optgroup', JText::_('General') );
		
		$options[]	= JHTML::_( 'select.option', "checkbox", 	JText::_( 'Checkbox' ) );
		$options[]	= JHTML::_( 'select.option', "date", 		JText::_( 'Date' ) );
		$options[]	= JHTML::_( 'select.option', "radiobutton", JText::_( 'Radiobox' ) );
		$options[]	= JHTML::_( 'select.option', "selectlist", 	JText::_( 'Select List' ) );
		$options[]	= JHTML::_( 'select.option', "text", 		JText::_( 'Text' ) );
		$options[]	= JHTML::_( 'select.option', "multitext", 	JText::_( 'Textarea (multiple text)' ) );
		$options[]	= JHTML::_( 'select.option', "textemail", 	JText::_( 'Text with email validation' ) );
		
		
		
		return $options;
	}
}
