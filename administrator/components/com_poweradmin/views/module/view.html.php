<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.html.php 13973 2012-07-13 09:32:56Z hiepnv $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class PoweradminViewModule extends JView
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$JSNMedia = JSNFactory::getMedia();
		$JSNMedia->addStyleSheet(JSN_POWERADMIN_STYLE_URI. 'styles.css');		
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JS_URI. 'jquery.hotkeys.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JS_URI. 'jstorage.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JS_URI. 'jquery.topzindex.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.submenu.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.mousecheck.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.functions.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JS_URI. 'jquery.tinyscrollbar.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.assignpages.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JS_URI. 'jstree/jquery.jstree.js');
		$JSNMedia->addScriptDeclaration("var baseUrl = '".JURI::root()."';");

		//require helper
		JSNFactory::localimport('libraries.joomlashine.page.assignpages');
		$viewHelper = JSNAssignpages::getInstance();

		$menuTypes = $viewHelper->menuTypeDropDownList();
        $this->assign('menutypes', $menuTypes);

        $moduleid = JRequest::getVar('id', 0);
        $menuitems = $viewHelper->renderMenu($moduleid);        
        $this->assign('menuitems', $menuitems);
        
        JSNFactory::localimport('libraries.joomlashine.modules');
        $assignType = JSNModules::checkAssign($moduleid);        
        $this->assign('assignType', $assignType);

		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');	

		$language = JFactory::getLanguage();
		$language->load('com_modules');

		parent::display($tpl);
	}
}