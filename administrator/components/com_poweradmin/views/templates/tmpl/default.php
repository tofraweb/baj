<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: default.php 14023 2012-07-14 09:19:47Z thangbh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div id="manage-styles">
	<h3><a href="#"><?php echo JText::_('JSN_POWERADMIN_INSTALLED_TEMPLATES') ?></a></h3>
	<div id="installed-templates">
		<div id="jsn-page-container" class="jsn-template-select-panel">
			<?php foreach ($this->templates as $template): ?>
				<?php $class = ($template->home == 1) ? 'template-item default' : 'template-item' ?>
				<div class="<?php echo $class;?>" id="jTemplate-<?php echo $template->id;?>" sid="<?php echo $template->id;?>" tid="<?php echo $template->tid;?>">
					<?php if (file_exists(JSN_POWERADMIN_TEMPLATE_PATH.DS.$template->template.DS.'template_thumbnail.png')): ?>
						<a class="template-item-thumb" href="javascript:;" >
							<span class="thumbnail">
								<img src="<?php echo JURI::root().'templates/'.$template->template.'/template_thumbnail.png'; ?>" alt="<?php echo $template->title;?>" align="center"/>
							</span>
							<span><?php echo $template->title;?></span>	
						</a>
					<?php else: ?>
						<a class="template-item-thumb template-thumbnail-blank" href="index.php?option=com_templates&task=style.edit&id=<?php echo $template->id ?>">
							<span class="blank-message"><?php echo JText::_('JSN_POWERADMIN_BLANK_THUMBNAIL') ?></span>
							<span><?php echo $template->title;?></span>	
						</a>
					<?php endif ?>				
				</div>
			<?php endforeach ?>
		</div>
	</div>
	<h3><a href="#"><?php echo JText::_('JSN_POWERADMIN_GET_MORE_TEMPLATES') ?></a></h3>
	<div id="get-more-templates">
		<div id="slideshow">
			<iframe src="http://www.joomlashine.com/free-joomla-templates-promo.html" scrolling="no" frameborder="0" width="640" height="510"></iframe>
		</div>
	</div>
</div>