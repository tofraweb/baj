<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.html.php 13948 2012-07-12 11:56:19Z thangbh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class PowerAdminViewAbout extends JView
{
	public function display ($tpl = null)
	{
		$this->addToolbar();
		$this->addAssets();
		
		parent::display($tpl);
	}
	
	private function addToolbar ()
	{
		JToolBarHelper::title(JText::_('JSN_POWERADMIN_ABOUT_TITLE'), 'poweradmin-about');
	}
	
	private function addAssets ()
	{
		$JSNMedia = JSNFactory::getMedia();		
		$JSNMedia->addScript('components/com_poweradmin/assets/js/joomlashine/about.js');

		$JSNMedia->addScriptDeclaration('JSNLang.add("JSN_POWERADMIN_JOOMLA_TEMPLATES", "'.JText::_('JSN_POWERADMIN_JOOMLA_TEMPLATES').'");');
		$JSNMedia->addScriptDeclaration('var baseUrl = "' . JUri::root() . '";');
	}
}