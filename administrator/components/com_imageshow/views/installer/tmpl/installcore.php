<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: installcore.php 14433 2012-07-27 02:31:11Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_('INSTALLER_INSTALLER'));
global $mainframe;
$session = JFactory::getSession();
$fileBackUpForMigrate = $session->get('jsn_is_backup_for_migrate', null, 'jsnimageshow');
$preVersion 		  = (float) str_replace('.', '', $session->get('preversion', null, 'jsnimageshow'));
$downloadBackup 	  = '';
$restoreFailureText   = JText::_('INSTALLER_RESTORE_DATA_FAILURE', true);

if ($fileBackUpForMigrate && $preVersion && $preVersion < 400)
{
	$downloadLink = 'index.php?option=com_imageshow&controller=installer&task=downloadBackupFile&file_name='.$fileBackUpForMigrate;
	$downloadBackup = JText::sprintf('INSTALLER_DOWNLOAD_BACKUP_FILE', $downloadLink);
}
$objJConfig = new JConfig();
$JConfigArrays = JArrayHelper::fromObject($objJConfig);
$FTPEnable     = $JConfigArrays['ftp_enable'];
?>
<div class="jsn-page-install">
	<div class="jsn-page-content jsn-rounded-large jsn-box-shadow-large jsn-bootstrap">
	<h1><?php echo JText::sprintf('Install JSN %s', $this->infoXmlDetail['realName']. ' ' . $this->infoXmlDetail['edition']); ?></h1>
	<p><?php echo 'There are several stages involved in the process. Please be patient.'; ?></p>
	<div class="jsn-installation-finish jsn-bootstrap">
	<?php
	$errors = $session->get('jsn_install_error');

		echo '<ul><li>Install core elements.';

		if(count($errors))
		{
			echo '<span class="jsn-installation-failure-icon"></span>';
			$countError = count($errors);

			if ($countError > 1) {
				echo '<ul class="jsn-installation-failure">';
			}
			echo '<p class="jsn-installation-failure">Following folder(s) must have Writable permission during installation process:</p>';
			foreach ($errors as $value)
			{
				if ($countError > 1) {
					echo '<li>';
				} else {
					echo '<p class="jsn-installation-failure">';
				}

				if ($value == 'plg') {
					echo '/plugins';
				}

				if ($value == 'plgcontent') {
					echo '/plugins/content';
				}

				if ($value == 'plgsystem') {
					echo '/plugins/system';
				}

				if ($value == 'module') {
					echo '/modules';
				}

				if ($value == 'lgcheckfo') {
					echo '/language (including all subfolders)';
				}

				if ($value == 'lgcheck') {
					echo '/administrator/language (including all subfolders)';
				}

				if($value == 'plgeditor_xtd'){
					echo '/plugins/editor-xtd';
				}

				if ($countError > 1) {
					echo '</li>';
				} else {
					echo '</p>';
				}
			}
			echo '<p class="jsn-installation-failure">Please set Writable permission to appropriate folder(s) and reinstall the extension.</p>';
			echo '<div class="form-actions"><a class="btn btn-primary" href="index.php?option=com_installer">Reinstall</a></div>';
			echo '<p class="jsn-installation-failure"><strong>Note:</strong><br/>After installation process, you are recommended to set all folders permission back to "Unwritable".</p>';
			if ($countError > 1) {
				echo '</ul>';
			}
			echo '</li>';
		}
		else
		{
			echo '&nbsp;<span class="jsn-icon16 icon-ok"></span></li>';
			echo '</li>';
			echo '<li id="jsn-install-imagesources">'.JText::_('INSTALLER_IMAGESHOW_DEFAULT_SOURCE_INSTALLATION_SUCCESS').'
								<div class="jsn-install-processing" id="jsn-install-process-source">
									<img src="components/com_imageshow/assets/images/ajax-loader-circle.gif"/>
									&nbsp;<span class="jsn-icon16 icon-ok"></span>
									<p class="jsn-install-process-text"></p>
								</div>
				</li>';
			echo '<li id="jsn-install-themes">'.JText::_('INSTALLER_IMAGESHOW_DEFAULT_THEME_INSTALLATION_SUCCESS').'
										<div class="jsn-install-processing" id="jsn-install-process-theme">
											<img src="components/com_imageshow/assets/images/ajax-loader-circle.gif"/>
											&nbsp;<span class="jsn-icon16 icon-ok"></span>
											<p class="jsn-install-process-text"></p>
										</div>
				</li>
				<li id="jsn-install-migrate-data">'.JText::_('INSTALLER_IMAGESHOW_DEFAULT_MIGRATE_DATA').'
										<div class="jsn-install-processing" id="jsn-install-process-migrate">
											<img src="components/com_imageshow/assets/images/ajax-loader-circle.gif"/>
											&nbsp;<span class="jsn-icon16 icon-ok"></span>
											<p class="jsn-install-process-text"></p>
										</div>
				</li>
				</ul>';
		}
		echo '</div>';
		if (!count($errors)) {
			echo '<div id="jsn-installation-successfull-message" class="jsn-installation-successfull-message">'.JText::sprintf('INSTALLER_INSTALLATION_CONGRATULATION',  $this->infoXmlDetail['realName']. ' ' . $this->infoXmlDetail['edition']).'</div>';
		}

		if ($downloadBackup) {
			echo '<div id="jsn-install-download-backup-file">'.$downloadBackup.'</div>';
		}

		echo '<div id="jsn-installation-buttons" class="jsn-bootstrap form-actions">';
		echo '<button id="jsn-installation-button-finish" class="btn btn-primary" type="submit" onclick="redirectToImageShowPage();">';
		echo  JText::_('INSTALLER_FINISH');
		echo '</button>';
		echo '<button id="jsn-installation-button-close" class="btn btn-primary" type="submit" onclick="redirectToInstallPage();">';
		echo  JText::_('INSTALLER_CLOSE');
		echo '</button>';
?>
	</div>
	<?php
		echo '<script type="text/javascript">
				function redirectToImageShowPage()
			 	{
			 		window.location.href = "index.php?option=com_imageshow&controller=installer&task=finish";
			 	}

			 	function redirectToInstallPage()
			 	{
			 		window.location.href = "index.php?option=com_installer";
			 	}
			 	</script>';

		if (!count($errors))
		{
			echo '<script> window.addEvent("domready", function(){
					var options = {};
						options.processText = "'.JText::_('INSTALLER_PROCESS_TEXT', true).'";
						options.waitText = "'.JText::_('INSTALLER_WAIT_TEXT', true).'";
						options.parentSourceID = "jsn-install-process-source";
						options.textTag = "p";
						options.manualRequiredSourcesInstallText = "'.JText::_('MANUAL_INSTALL_REQUIRED_IMAGE_SOURCES_INSTALL', true).'";
						options.manualRequiredThemesInstallText = "'.JText::_('MANUAL_INSTALL_REQUIRED_THEMES_INSTALL', true).'";
						options.manualRequiredDefaultInstallText = "'.JText::_('MANUAL_INSTALL_REQUIRED_DEFAULT_INSTALL', true).'";
						options.manualInstallText = "'.JText::_('MANUAL_INSTALL', true).'";
						options.manualImageSourceText = "'.JText::_('MANUAL_IMAGE_SOURCES', true).'";
						options.manualThemeText = "'.JText::_('MANUAL_THEME', true).'";
						options.manualDownloadText = "'.JText::_('MANUAL_DOWNLOAD', true).'";
						options.manualPackageText = "'.JText::_('MANUAL_PACKAGE', true).'";
						options.manualThenSelectItText = "'.JText::_('MANUAL_THEN_SELECT_IT', true).'";
						options.manualInstallButton = "'.JText::_('MANUAL_INSTALL_BUTTON', true).'";
						options.dowloadInstallationPackageText = "'.JText::_('MANUAL_DOWNLOAD_INSTALLATION_PACKAGE', true).'";
						options.selectDownloadPackageText = "'.JText::_('MANUAL_SELECT_DOWNLOADED_PACKAGE', true).'";
						options.downloadLink = "'.$this->escape(JSN_IMAGESHOW_AUTOUPDATE_URL).'";
						options.redirectLink = "index.php?option=com_imageshow&controller=installer&task=installcore";
						options.language = "'.$this->adminLang.'";

					options.parentThemeID = "jsn-install-process-theme";
					options.restoreDatabase = '.(($downloadBackup) ? "true" : "false") .';
					options.backupFile = '.(($downloadBackup) ? "'$fileBackUpForMigrate'" : "''") .';
					options.restoreDataFailureText = "'.$restoreFailureText.'";
					options.messageSource			= "'.JText::_('INSTALLER_PROCESS_MISSING_SOURCE', true).'";
					options.messageTheme			= "'.JText::_('INSTALLER_PROCESS_MISSING_THEME', true).'";
					options.ftp						= "'.$FTPEnable.'";
					JSNISInstallDefault.getListInstall(options);
				});</script>';
		}
	?>
	</div>
</div>
