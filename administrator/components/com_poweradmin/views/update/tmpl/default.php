<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: default.php 13473 2012-06-22 12:29:18Z hiennh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$hasUpdate = version_compare(PowerAdminHelper::getVersion(), PowerAdminHelper::getLatestVersion(), '<');
?>
<div class="jsn-updater-container">
	<a id="jsn-updater-link-cancel" class="jsn-updater-link-cancel" href="index.php?option=com_poweradmin"><?php echo JText::_('JSN_POWERADMIN_BUTTON_CANCEL'); ?></a>
	<h3 class="jsn-element-heading"><?php echo JText::sprintf('JSN_POWERADMIN_UPDATE_HEADING', 'JSN PowerAdmin') ?></h3>
	
	<?php if (!$hasUpdate):?>
		<p>No update found for your current product</p>
	<?php else: ?>
		<p>This process will update JSN PowerAdmin to the latest version: <b><?php echo $this->version ?></b></p>
		
		<div class="jsn-updater-processing-box">
			<ul>
				<li id="jsn-updater-download" class="jsn-updater-download">
					Download installation package.
					<img class="jsn-updater-loading" src="components/com_poweradmin/assets/images/ajax-loader-circle.gif">
					<span class="jsn-updater-success"></span>
					<span class="jsn-updater-failure"></span>
					
					<div id="jsn-updater-download-message" class="jsn-updater-process-message"></div>
				</li>
				
				<li id="jsn-updater-install" class="jsn-updater-install">
					Install package.
					<img class="jsn-updater-loading" src="components/com_poweradmin/assets/images/ajax-loader-circle.gif">
					<span class="jsn-updater-success"></span>
					<span class="jsn-updater-failure"></span>
					
					<div id="jsn-updater-install-message" class="jsn-updater-process-message"></div>
				</li>
			</ul>
			
			<div id="jsn-updater-successfully">
				<span class="jsn-installation-success-label">JSN PowerAdmin is successfully updated</span>
				Please <strong>clear your browser's cache</strong> after clicking button "Finish".
			</div>
		</div>
		
		<div id="jsn-updater-buttons" class="jsn-button-container">
			<button id="jsn-updater-button-update" class="link-button">Update</button>
			<button id="jsn-updater-button-cancel" class="link-button">Cancel</button>
			<button id="jsn-updater-button-finish" class="link-button">Finish</button>
		</div>
	<?php endif ?>
</div>

