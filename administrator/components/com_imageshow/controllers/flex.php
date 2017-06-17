<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: flex.php 13757 2012-07-04 03:26:48Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.controller' );

class ImageShowControllerFlex extends JController {

	var $_objFlex = null;

	function __construct($config = array())
	{
		$this->_objFlex = JSNISFactory::getObj('classes.jsn_is_flex');
		parent::__construct($config);
	}

	function getScriptCheckThumb() {
		$task 		= JRequest::getVar('task');
		echo $this->_objFlex->$task();
		jexit();
	}

	function checkThumb() {
		$task 		= JRequest::getVar('task');
		echo $this->_objFlex->$task();
		jexit();
	}
}
