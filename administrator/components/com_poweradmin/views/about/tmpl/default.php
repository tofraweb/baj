<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: default.php 14028 2012-07-14 11:28:33Z thangbh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$latestVersion = PowerAdminHelper::getLatestVersion();
$currentVersion = PoweradminHelper::getVersion();
$hasUpdate = version_compare($currentVersion, $latestVersion, '<');
?>
<div id="jsn-product-about" class="jsn-page-about">
	<div class="jsn-bootstrap">
		<div class="jsn-product-about">
			<div class="jsn-product-intro">
				<div class="jsn-product-thumbnail">
					<?php echo JHTML::_('image.administrator', 'components/com_poweradmin/assets/images/product-thumbnail.png',''); ?>
				</div>
				<div class="jsn-product-details">
					<h2 class="jsn-section-header">
						JSN PowerAdmin
					</h2>
					<dl>
						<dt><?php echo JText::_('JSN_POWERADMIN_VERSION'); ?>:</dt>
						<dd>
							<strong class="jsn-current-version"><?php echo $currentVersion ?></strong>&nbsp;-&nbsp;
							<span id="jsn-check-version-result">
								<?php if ($latestVersion === null): ?>
								<span class="label label-warning"><?php echo JText::_('JSN_POWERADMIN_UPDATE_CHECK_FAILED') ?></span>
								<?php elseif ($hasUpdate): ?>
								<span class="label label-warning"><?php echo JText::_('JSN_POWERADMIN_UPDATE_AVAILABLE') ?><a href="index.php?option=com_poweradmin&amp;view=update" class="jsn-action-link"><?php echo JText::_('JSN_POWERADMIN_UPDATE_NOW') ?></a></span>
								<?php else: ?>
								<span class="label label-success"><?php echo JText::_('JSN_POWERADMIN_UPDATE_NOT_AVAILABLE') ?></span>
								<?php endif ?>
							</span>
						</dd>
						<dt><?php echo JText::_('JSN_POWERADMIN_AUTHOR'); ?>:</dt>
						<dd>
							<a href="http://www.joomlashine.com">JoomlaShine.com</a>
						</dd>
						<dt><?php echo JText::_('JSN_POWERADMIN_COPYRIGHT'); ?>:</dt>
						<dd>Copyright &copy; 2008 - 2012 - JoomlaShine.com</dd>
					</dl>
				</div>
				<div class="clearbreak"></div>
			</div>
			<div class="jsn-product-cta jsn-bgpattern pattern-sidebar">
				<div class="pull-left">
					<ul class="jsn-list-horizontal">
						<li>
							<a class="btn" target="_blank" href="http://www.joomlashine.com/joomla-extensions/jsn-poweradmin-on-jed.html">
								<i class="icon-comment"></i>&nbsp;<?php echo JText::_('JSN_POWERADMIN_REVIEW_ON_JED') ?>
							</a>
						</li>
						<li>
							<a class="btn" target="_blank" id="see-other-products" href="index.php?option=com_poweradmin&view=about&layout=otherproducts&tmpl=component">
								<i class="icon-briefcase"></i>&nbsp;<?php echo JText::_('JSN_POWERADMIN_SEE_OTHER_PRODUCTS') ?>
							</a>
						</li>
					</ul>
				</div>
				<div class="pull-right">
					<ul class="jsn-list-horizontal">
						<li>
							<a href="http://www.facebook.com/joomlashine" title="<?php echo htmlspecialchars(JText::_('JSN_POWERADMIN_CONNECT_ON_FACEBOOK')); ?>" target="_blank">
								<span class="jsn-icon24 icon-social icon-facebook"></span>
							</a>
						</li>
						<li>
							<a href="http://www.twitter.com/joomlashine" title="<?php echo htmlspecialchars(JText::_('JSN_POWERADMIN_FOLLOW_ON_TWITTER')); ?>" target="_blank">
								<span class="jsn-icon24 icon-social icon-twitter"></span>
							</a>
						</li>
						<li>
							<a href="http://www.youtube.com/joomlashine" title="<?php echo htmlspecialchars(JText::_('JSN_POWERADMIN_WATCH_US_ON_YOUTUBE')); ?>" target="_blank">
								<span class="jsn-icon24 icon-social icon-youtube"></span>
							</a>
						</li>
					</ul>
				</div>
				<div class="clearbreak"></div>
			</div>
		</div>
		<div class="jsn-product-support">
			<div>
				<h3 class="jsn-section-header"><?php echo JText::_('JSN_POWERADMIN_HELP_AND_SUPPORT') ?></h3>
				<p><?php echo JText::_('JSN_POWERADMIN_NEED_HELP') ?></p>
				<ul>
					<li><?php echo JText::sprintf('JSN_POWERADMIN_READ_DOCUMENTATION', 'http://www.joomlashine.com/joomla-extensions/jsn-poweradmin-docs.zip') ?></li>
					<li><?php echo JText::sprintf('JSN_POWERADMIN_ASK_SUPPORT', 'http://www.joomlashine.com/forum.html') ?></li>
					<li><?php echo JText::sprintf('JSN_POWERADMIN_SUBMIT_TICKET', 'http://www.joomlashine.com/dedicated-support.html') ?></li>
				</ul>
			</div>
			<div>
				<h3 class="jsn-section-header"><?php echo JText::_('JSN_POWERADMIN_FEEDBACK') ?></h3>
				<p><?php echo JText::_('JSN_POWERADMIN_WE_HEAR_YOU') ?></p>
				<ul>
					<li><?php echo JText::sprintf('JSN_POWERADMIN_SUBMIT_BUG', 'http://www.joomlashine.com/contact-us/product-feedback.html') ?></li>
					<li><?php echo JText::sprintf('JSN_POWERADMIN_GIVE_TESTIMONIAL', 'http://www.joomlashine.com/contact-us/product-feedback.html') ?></li>
					<li><?php echo JText::sprintf('JSN_POWERADMIN_REVIEW_PRODUCT_ON_JED', 'http://www.joomlashine.com/joomla-extensions/jsn-poweradmin-on-jed.html') ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>