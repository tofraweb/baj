<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: edit_assignment.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

echo JHtml::_('sliders.panel', JText::_('COM_MODULES_MENU_ASSIGNMENT'), 'assignment-options'); ?>
<fieldset class="panelform">			
	<div id="jsn-edit-module" class="jsn-assignment-form">
		<div class="jsn-heading-panel clearafter">
			<!-- Assingment dropdown list -->
			<div class="asignment-dropdown-list">
				<label for="assignment-dropdown-list"><?php echo JText::_('JSN_ASSIGNPAGE_ASSIGNTO');?></label>
				<select name="assignment" id="assignment-dropdown-list">
					<option value="0" <?php if ($this->assignType === 0) { echo 'selected="selected"'; }?>><?php echo JText::_('JSN_ASSINGMENT_NOPAGES');?></option>
					<option value="1" <?php if ($this->assignType === 1) { echo 'selected="selected"'; }?>><?php echo JText::_('JSN_ASSINGMENT_ALLPAGES');?></option>
					<option value="2" <?php if ($this->assignType === 2 || $this->assignType === -2) { echo 'selected="selected"'; }?>><?php echo JText::_('JSN_ASSIGNMENT_EXCEPT_SELECTED');?></option>
					<option value="3" <?php if ($this->assignType === 3) { echo 'selected="selected"'; }?>><?php echo JText::_('JSN_ASSINGMENT_ONSELECTPAGES');?></option>
				</select>
			</div>
			<!-- Menu items publishing -->
			<span id="jsn-toggle-publish-module" class="jsn-toggle-button"></span>
		</div>
		<!-- Menu item listing -->
		<div class="jsn-menu-selector-container">
			<div class="jsn-menu-selector-container_inner menus-assignment">
				<!-- Menu type dropdown list -->
				<?php echo $this->menutypes;?>
				<?php echo $this->menuitems;?>
			</div>
		</div>
	</div>
</fieldset>