<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: poweradmin.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include dependancies
jimport('joomla.application.component.controller');

//include defines
JSNFactory::localimport('defines');
//load front-end helper 
JSNFactory::localimport('helpers.poweradmin', 'site');

// Execute the task.
$controller	= JController::getInstance('poweradmin');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
