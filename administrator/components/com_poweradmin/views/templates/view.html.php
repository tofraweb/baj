<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.html.php 14023 2012-07-14 09:19:47Z thangbh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class PoweradminViewTemplates extends JView
{
	
	public function display($tpl = null)
	{	
		$JSNMedia = JSNFactory::getMedia();		
		$JSNMedia->addStyleSheet(JSN_POWERADMIN_STYLE_URI. 'styles.css');

		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI."jsn.mousecheck.js");
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI."jsn.submenu.js");
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI."jsn.manage-styles.js");
		
		$JSNMedia->addStyleDeclaration("
		.template-item {
			background: url(".JSN_POWERADMIN_IMAGES_URI."loader.gif) no-repeat center center;
		}
		.loading {
			background: url(".JSN_POWERADMIN_IMAGES_URI."indicator.gif) no-repeat center right;
		}
		");
		
		$model = $this->getModel('templates');
		$rows  = $model->getTemplates();
		
		//assign to view
		$this->assign('templates', $rows);
		return parent::display();
	}
	
}