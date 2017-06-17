<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted Access');

jimport('joomla.version');

?>
<div id="update">
<?php if ($this->versionInfo->updateFound && $this->versionInfo->fullLoaded) { ?>
	<fieldset class="adminform">
		<legend><?php echo JText::_( 'COM_N3TTEMPLATE_UPDATE_DETAILS' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="200" align="right" class="key">
				<?php echo JText::_( 'COM_N3TTEMPLATE_UPDATE_CURRENT_VERSION' ); ?>:
			</td>
			<td>
				<?php echo $this->versionInfo->currentVersion; ?>
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<?php echo JText::_( 'COM_N3TTEMPLATE_UPDATE_VERSION' ); ?>:
			</td>
			<td>
				<?php echo $this->versionInfo->updateVersion; ?>
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<?php echo JText::_( 'COM_N3TTEMPLATE_UPDATE_INFO' ); ?>:
			</td>
			<td>
				<a href="<?php echo $this->versionInfo->updateInfoUrl; ?>" target="_blank"><?php echo $this->versionInfo->updateInfoTitle; ?></a>
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				<?php echo JText::_( 'COM_N3TTEMPLATE_UPDATE_DOWNLOAD' ); ?>:
			</td>
			<td>
				<a href="<?php echo $this->versionInfo->updateDownloadUrl; ?>"><?php echo $this->versionInfo->updateDownloadUrl; ?></a>
			</td>
		</tr>
		<tr>
			<td width="200" align="right" class="key">
				&nbsp;
			</td>
			<td>
        <form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_installer&view=install');?>" method="post" name="adminForm" id="adminForm">
     			<input type="submit" class="button" value="<?php echo JText::_('COM_N3TTEMPLATE_UPDATE_INSTALL'); ?>" />
      		<input type="hidden" name="install_url" value="<?php echo $this->versionInfo->updateDownloadUrl; ?>" />
      		<input type="hidden" name="type" value="" />
      		<input type="hidden" name="installtype" value="url" />
      		<?php if (JVersion::isCompatible('1.6.0')) { ?>      		
      		<input type="hidden" name="task" value="install.install" />
      		<?php } else { ?>
      		<input type="hidden" name="task" value="doInstall" />
      		<?php } ?>
      		<?php echo JHtml::_('form.token'); ?>
        </form>	  
			</td>
		</tr>
	  </table>
	</fieldset>
<?php } elseif (!$this->versionInfo->loaded || $this->versionInfo->updateFound && !$this->versionInfo->fullLoaded) { ?>
<p><?php echo JText::_( 'COM_N3TTEMPLATE_UPDATE_ERROR' ); ?></p>	  
<?php } else { ?>
<p><?php echo JText::_( 'COM_N3TTEMPLATE_UPDATE_NOT_FOUND' ); ?></p>
<?php } ?>
</div>

<div class="clr"></div>