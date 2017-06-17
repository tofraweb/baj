<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted Access');

?>
<div id="cpanel">
  <div class="cpanelModulesWrapper">
  <?php echo n3tTemplateHelperHTML::cpanelModules(); ?>
  </div>
	<div class="cpanelIconsWrapper">
	  <?php echo n3tTemplateHelperHTML::cpanelIcons($this->icons); ?>
	</div>
</div>

<div class="clr"></div>