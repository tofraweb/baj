<!-- Footer -->
<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: footer.php 14111 2012-07-17 07:38:10Z thangbh $
-------------------------------------------------------------------------*/

?>
<script>
(function ($) {
	$.get(
			'index.php?option=com_poweradmin&task=rawmode.compareVersions'
		).success(function (response) {			
			if(response){
				$('#jsn_update_now').show();
			}
		});
})(JoomlaShine.jQuery);	
</script>
<div id="jsn-footer" class="jsn-page-footer jsn-bootstrap">
	<ul class="jsn-footer-menu">
		<li class="first">
			<a href="http://www.joomlashine.com/joomla-extensions/jsn-poweradmin-docs.zip" target="_blank"><?php echo JText::_('JSN_POWERADMIN_DOCUMENT') ?></a>
		</li>
		<li>
			<a href="http://www.joomlashine.com/contact-us/get-support.html" target="_blank"><?php echo JText::_('JSN_POWERADMIN_SUPPORT') ?></a>
		</li>
		<li>
			<a href="http://www.joomlashine.com/joomla-extensions/jsn-poweradmin-on-jed.html" target="_blank"><?php echo JText::_('JSN_POWERADMIN_VOTE_ON_JED') ?></a>
		</li>
		<li class="jsn-iconbar">
			<strong><?php echo JText::_('JSN_POWERADMIN_FOLLOW_US') ?></strong>
			<a href="http://www.facebook.com/joomlashine" target="_blank" class="jsn-icon16 icon-social icon-facebook" title="<?php echo htmlspecialchars(JText::_('JSN_POWERADMIN_FIND_US_ON_FACEBOOK')); ?>"></a><a href="http://twitter.com/joomlashine" target="_blank" class="jsn-icon16 icon-social icon-twitter" title="<?php echo htmlspecialchars(JText::_('JSN_POWERADMIN_FOLLOW_US_ON_TWITTER')); ?>"></a>
		</li>
	</ul>
	
	<ul class="jsn-footer-menu">
		<li class="first">
			<a href="http://www.joomlashine.com/joomla-extensions/jsn-poweradmin.html" target="_blank">JSN PowerAdmin v<?php echo PowerAdminHelper::getVersion() ?></a>
			<?php echo JText::_('JSN_POWERADMIN_COPYRIGHT_BY') ?>
			<a href="http://www.joomlashine.com" target="_blank">JoomlaShine.com</a>
		</li>
		<li style="display:none;" id="jsn_update_now">
			<span class="jsn-text-attention"><?php echo JText::_('JSN_POWERADMIN_UPDATE_AVAILABLE') ?></span>
			<a href="index.php?option=com_poweradmin&view=update" class="label label-important"><?php echo JText::_('JSN_POWERADMIN_UPDATE_NOW') ?></a>
		</li>
		<li>
			<a href="http://www.joomlashine.com/joomla-templates.html" target="_blank">Joomla templates</a>
		</li>
		<li>
			<a href="http://www.joomlashine.com/joomla-extensions.html" target="_blank">Joomla extensions</a>
		</li>
		
	</ul>
</div>