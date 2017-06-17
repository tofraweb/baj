<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.html.php 13597 2012-06-27 10:46:26Z binhpt $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class PowerAdminViewUpdate extends JView
{
	public function display ($tpl = null)
	{
		$this->version = PowerAdminHelper::getLatestVersion();
		$this->_addAssets();
		$this->_addToolbar();
		
		parent::display($tpl);
	}
	
	private function _addAssets ()
	{
		$document = JFactory::getDocument();
		$document->addStylesheet(PowerAdminHelper::makeUrlWithSuffix('components/com_poweradmin/assets/css/poweradmin.css'));
		$document->addStylesheet(PowerAdminHelper::makeUrlWithSuffix('components/com_poweradmin/assets/css/update.css'));
		
		$document->addScript(PowerAdminHelper::makeUrlWithSuffix('components/com_poweradmin/assets/js/jquery/jquery.min.js'));
		$document->addScript(PowerAdminHelper::makeUrlWithSuffix('components/com_poweradmin/assets/js/joomlashine/update.js'));
	}

	private function _addToolbar ()
	{
		JToolBarHelper::title(JText::_('JSN_POWERADMIN_UPDATE_TITLE'), 'poweradmin-update');
	}
}