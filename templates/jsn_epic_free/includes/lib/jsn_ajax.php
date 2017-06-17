<?php
/*------------------------------------------------------------------------
# JSN Template Framework
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
# @license - GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
# @version $Id: jsn_ajax.php 13522 2012-06-25 08:50:17Z ngocpm $
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

class JSNAjax
{
	var $_template_folder_path  = '';
	var $_template_folder_name 	= '';
	var $_obj_utils				= null;
	var $_template_edition 		= '';
	var $_template_version 		= '';
	var $_template_name 		= '';
	var $_template_copyright	= '';
	var $_template_author		= '';
	var $_template_author_url	= '';
	var $_product_info_url		= '';

	function JSNAjax()
	{
		$this->_setPhysicalTmplInfo();
		require_once($this->_template_folder_path.DS.'includes'.DS.'lib'.DS.'jsn_utils.php');
		require_once($this->_template_folder_path.DS.'includes'.DS.'lib'.DS.'jsn_sampledata_helper.php');
		$this->_setUtilsInstance();
		$this->_setTmplInfo();
	}

	/**
	 *
	 * Initialize instance of JSNUtils class
	 */
	function _setUtilsInstance()
	{
		$this->_obj_utils = JSNUtils::getInstance();
	}

	/**
	 * Initialize Physical template information variable
	 *
	 */
	function _setPhysicalTmplInfo()
	{
		$template_name 					= explode(DS, str_replace(array('\includes\lib', '/includes/lib'), '', dirname(__FILE__)));
		$template_name 					= $template_name [count( $template_name ) - 1];
		$path_base 						= str_replace(DS."templates".DS.$template_name.DS.'includes'.DS.'lib', "", dirname(__FILE__));
		$this->_template_folder_name    = $template_name;
		$this->_template_folder_path 	= $path_base . DS . 'templates' .  DS . $template_name;
	}

	/**
	 * Initialize template information variable
	 *
	 */
	function _setTmplInfo()
	{
		$result 				 	= $this->_obj_utils->getTemplateDetails();
		$manifest_cache				= $this->_obj_utils->getTemplateManifestCache();
		$manifest_cache				= json_decode($manifest_cache);
		$this->_template_edition 	= $result->edition;
		$this->_template_version 	= $manifest_cache->version;
		$this->_template_name 		= $result->name;
		$this->_template_copyright 	= $result->copyright;
		$this->_template_author 	= $result->author;
		$this->_template_author_url = $result->authorUrl;
		$template_name	  			= JString::strtolower($this->_template_name);
		$exploded_template_name 	= explode('_', $template_name);
		$template_name				= @$exploded_template_name[0].'-'.@$exploded_template_name[1];
		$this->_product_info_url	= JSN_TEMPLATE_INFO_URL;
	}

	/**
	 * Check cache folder writable or not
	 *
	 */
	function checkCacheFolder()
	{
		$cache_folder   = JRequest::getVar('cache_folder');
		$isDir 			= is_dir($cache_folder);
		$isWritable 	= is_writable($cache_folder);
		echo json_encode(array('isDir' => $isDir, 'isWritable' => $isWritable));
	}

	function checkFolderPermission($for = 'sampledata')
	{
		if ($for == 'sampledata')
		{
			$sdHelperInstance = new JSNSampleDataHelper();
			$failedList = $sdHelperInstance->checkFolderPermission();

			if (count($failedList) > 0)
			{
				echo json_encode(array('permission' => false, 'folders' => $failedList));
			}
			else
			{
				echo json_encode(array('permission' => true));
			}
		}

		exit();
	}

	/**
	 * Check template's latest version from JoomlaShine server
	 */
	function checkVersion()
	{
		$session = JFactory::getSession();
		$templateVersionSesId = md5('template_version_' . strtolower($this->_template_name));

		/* Template identified_name will be something like: tpl_epic */
		$exploded_template_name	= explode('_', JString::strtolower($this->_template_name));
		$identified_name		= 'tpl_'.$exploded_template_name[1];

		$latestVersion = $this->_obj_utils->getLatestProductVersion($identified_name, 'template');

		if($latestVersion === false)
		{
			echo json_encode(array('connection' => false, 'version' => ''));
		}
		else
		{
			echo json_encode(array('connection' => true, 'version' => $latestVersion));
			$session->set($templateVersionSesId, $latestVersion, 'jsntemplatesession');
		}
	}

	/**
	 * Check Files Integrity
	 */
	function checkFilesIntegrity()
	{
		require_once($this->_template_folder_path.DS.'includes'.DS.'lib'.DS.'jsn_checksum_integrity_comparison.php');
		$checksum 	= new JSNChecksumIntegrityComparison();
		$result 	= $checksum->compareIntegrity();

		if (is_array($result) && count($result) && (isset($result['added']) || isset($result['deleted']) || isset($result['modified'])))
		{
			if (count(@$result['added']) || count(@$result['deleted']) || count(@$result['modified']))
			{
				// Some files have been modified , added, or deleted
				echo json_encode(array('integrity' => 1));
			}
			else
			{
				// No files modification found
				echo json_encode(array('integrity' => 0));
			}
		}
		else
		{
			// The checksum file is missing or empty
			echo json_encode(array('integrity' => 2));
		}
	}

	function downloadSampleDataPackage()
	{
		$obj_sampledata_helper = new JSNSampleDataHelper();
		$obj_sampledata_helper->setSampleDataURL();
		$foldername 	= 'tmp';
		$folderpath 	= JPATH_ROOT.DS.$foldername;
		$link 			= '';
		$template_style_id	= JRequest::getInt('template_style_id', 0, 'GET');

		$session          = JFactory::getSession();
		$login_identifier = md5('state_login_'.strtolower($this->_template_name));
		$sdExtSesId       = md5('exts_info_'.strtolower($this->_template_name));
		$sdFileSesId      = md5('sample_data_file_'.strtolower($this->_template_name));
		$sdExtFaiedSesId  = md5('exts_failed_install_'.strtolower($this->_template_name));
		$state_login      = $session->get($login_identifier, false, 'jsntemplatesession');
		$failedExts       = $session->get($sdExtFaiedSesId, array(), 'jsntemplatesession');

		if(!$state_login) jexit('Invalid Token');

		if (is_writable($folderpath))
		{

			$joomla_version		= $this->_obj_utils->getJoomlaVersion();
			$downloadURL		= JSN_SAMPLE_DATA_FILE_URL;

			$objJSNDownloadPackage = new JSNDownloadPackage($downloadURL);
			$result = $objJSNDownloadPackage->download();

			if ($result)
			{
				$result = (string) $result;

				$releaseTool = JRequest::getVar('release_tool', 0);

				if ($releaseTool == 1)
				{
					echo json_encode(array('download' => true, 'sampleDataFile'=> (string) $result, 'connection' => true, 'exts' => ''));
					return;
				}
				else
				{
					/* Read the xml file for the list of to-be-installed exts */
					$extsToInstall = array();

					if (JFile::exists(JPATH_ROOT.DS.'tmp'.DS.$result))
					{
						/* Array with full info. of available exts */
						$extInfo      = array();

						/* Array with brief info of will-be-installed exts */
						$extBriefInfo = '';
						$extList = '';

						$xmlReaderInstance = new JSNReadXMLFile();
						$sdHelperInstance  = new JSNSampleDataHelper();

						$unpack = $sdHelperInstance->unpackPackage($result);
						if ($unpack)
						{
							$installExts = $xmlReaderInstance->getSampleDataFileContent($unpack, $this->_template_name, true);

							if ($installExts)
							{
								foreach ($installExts as $ext)
								{
									$extInfo[$ext->name] = $ext;

									/* Form an array of exts for user to choose */
									if ($ext->show === true)
									{
										/* Check if the extension would be installed */
										$checkResult = $sdHelperInstance->determineExtInstallation($ext);

										if ($checkResult->needInstall === true)
										{
											$extList[$ext->name]['desc'] = $ext->description;
											$extList[$ext->name]['message'] = $checkResult->message;
										}
										else
										{
											if ($checkResult->finalMessage != '')
											{
												$failedExts[$ext->name]['message'] = $checkResult->finalMessage;
											}
										}
									}
								}
							}

							$sdHelperInstance->deleteSampleDataFolder($unpack);
						}

						$session->set($sdExtSesId, $extInfo, 'jsntemplatesession');
						$session->set($sdExtFaiedSesId, $failedExts, 'jsntemplatesession');
						$session->set($sdFileSesId, $result, 'jsntemplatesession');

						echo json_encode(array('download' => true, 'sampleDataFile'=> (string) $result, 'connection' => true, 'exts' => $extList));
						return;
					}
					else
					{
						echo json_encode(array('download' => false, 'sampleDataFile'=> '', 'message' => JText::_('JSN_SAMPLE_DATA_PACKAGE_FILE_NOT_FOUND'), 'connection' => true));
						return;
					}
				}
			}
			else
			{
				echo json_encode(array('download' => false, 'message'=> JText::_('JSN_SAMPLE_DATA_DOWNLOAD_FAILED'), 'redirect_link'=>$link, 'connection'=>false));
				return;
			}
		}
		else
		{
			echo json_encode(array('download' => false, 'message' => JText::_('JSN_SAMPLE_DATA_TEMP_FOLDER_UNWRITABLE'), 'redirect_link'=>$link, 'connection'=>true));
			return;
		}
		return;
	}

	function selectExtensions()
	{
		$session           = JFactory::getSession();
		$sdExtSesId        = md5('exts_info_'.strtolower($this->_template_name));
		$sdExtInstallSesId = md5('exts_to_install_'.strtolower($this->_template_name));

		$extInfoArray = $session->get($sdExtSesId, array(), 'jsntemplatesession');
		$extInfoKeys = array_keys($extInfoArray);

		$installExts     = array();
		$flatInstallExts = array();
		$notSelected = array();

		$exts = JRequest::getVar('exts', array());
		$totalExt = count($exts);
		if ($totalExt > 0)
		{
			for ($i = 0; $i < $totalExt; $i++)
			{
				$isLastExt = false;
				$ext = $exts[$i];
				if (array_key_exists($ext, $extInfoArray))
				{
					$installExts[$ext]['desc'] = $extInfoArray[$ext]->description;
					$flatInstallExts[$ext]['childOf'] = '';
					if ($i == ($totalExt-1))
					{
						$isLastExt = true;
					}
					$flatInstallExts[$ext]['isLastExt'] = $isLastExt;

					if (isset($extInfoArray[$ext]->extDep))
					{
						$extDeps = array();
						foreach ($extInfoArray[$ext]->extDep as $extDep)
						{
							if (array_key_exists($extDep, $extInfoArray))
							{
								$extDeps[$extDep]['desc'] = $extInfoArray[$extDep]->description;
								$flatInstallExts[$extDep]['childOf'] = $ext;
								$flatInstallExts[$extDep]['isLastExt'] = false;
							}
						}
						$installExts[$ext]['deps'] = $extDeps;
					}
				}
			}
		}

		$sessionExtFailedId = md5('exts_failed_install_'.strtolower($this->_template_name));
		$failedExts         = $session->get($sessionExtFailedId, array(), 'jsntemplatesession');

		/* Not-selected exts will be treated as failed ones */
		$notSelected = array_diff($extInfoKeys, $exts);
		foreach ($notSelected as $ext)
		{
			$mes = '';
			if ($extInfoArray[$ext]->hasData === true)
			{
				 $mes = JText::sprintf('JSN_SAMPLE_DATA_WARNING_EXT_PRO_EXIST', $extInfoArray[$ext]->description);
			}
			$failedExts[$ext]['message'] = $mes;
		}

		$session->set($sdExtInstallSesId, $flatInstallExts, 'jsntemplatesession');
		$session->set($sessionExtFailedId, $failedExts, 'jsntemplatesession');

		$sdFileSesId = md5('sample_data_file_'.strtolower($this->_template_name));
		$sdFileName = $session->get($sdFileSesId, '', 'jsntemplatesession');

		$resultArray = array(
				'result'         => true,
				'exts'           => $installExts,
				'sampleDataFile' => $sdFileName
			);

		if (count($flatInstallExts) > 0)
		{
			$flatKeys = array_keys($flatInstallExts);
			$resultArray['firstExt']  = $flatKeys[0];
			$resultArray['childOf']   = $flatInstallExts[$flatKeys[0]]['childOf'];
			$resultArray['isLastExt'] = $flatInstallExts[$flatKeys[0]]['isLastExt'];
		}

		echo json_encode($resultArray);
		exit();
	}

	/**
	 * This function is for installing extensions for sample data
	 * This function DOES NOT actually install the extension, but acts as a
	 * "proxy" to receive request from AJAX, then using HTTP Socket to send an
	 * internal request to install the extension.
	 * It is important to go this way because direct AJAX request to install
	 * the extension might get interrupted because some extensions will perform
	 * a 303 redirection after standard Joomla JInstaller process.
	 */
	function requestInstallExtension()
	{
		$session            = JFactory::getSession();
		$sdExtSesId         = md5('exts_info_'.strtolower($this->_template_name));
		$sdExtInstallSesId  = md5('exts_to_install_'.strtolower($this->_template_name));
		$sessionExtFailedId = md5('exts_failed_install_'.strtolower($this->_template_name));
		$sdFileSesId        = md5('sample_data_file_'.strtolower($this->_template_name));

		$extInfoArray    = $session->get($sdExtSesId, array(), 'jsntemplatesession');
		$flatInstallExts = $session->get($sdExtInstallSesId, array(), 'jsntemplatesession');

		$sdHelperInstance = new JSNSampleDataHelper();

		/* Get the submitted valirables */
		$extName = JRequest::getVar('ext_name');

		if (array_key_exists($extName, $extInfoArray))
		{
			$extInfo        = $extInfoArray[$extName];
			$installResult  = true;
			$toContinue     = true;
			$mes            = '';
			$failedExts     = $session->get($sessionExtFailedId, array(), 'jsntemplatesession');
			$sampleDataFile = '';

			/* Download latest version */
			require_once($this->_template_folder_path.DS.'includes'.DS.'lib'.DS.'jsn_downloadtemplatepackage.php');
			$joomlaVersion = $this->_obj_utils->getJoomlaVersion(true);
			if ($extInfo->downloadUrl)
			{
				$link = $extInfo->downloadUrl;
			}
			else
			{
				$link = JSN_TEMPLATE_AUTOUPDATE_URL
					. '&identified_name=' . urlencode($extInfo->identifiedName)
					. '&joomla_version=' . $joomlaVersion
					. '&edition=free&upgrade=yes';
			}
			$tmpName = $extInfo->name . '-j' . $joomlaVersion . '.zip';

			$packageDownloaderInstance = new JSNDownloadTemplatePackage($link, $tmpName);
			$downloadResult = $packageDownloaderInstance->download();

			if ($downloadResult !== false && stripos($downloadResult, 'error0') === false)
			{
				/* If download success, send HTTP Socket request to install */

				$url = JURI::root().'index.php?template='.strtolower($this->_template_name)
					. '&tmpl=jsn_runajax&task=installExtension'
					. '&package=' . urlencode($downloadResult);

				// The last argument NOFOLLOW = true means don't follow redirection
				$httpRequestInstance = new JSNHTTPSocket($url, null, null, 'get', true);
				$output              = json_decode($httpRequestInstance->socketDownload());

	            /* Assuming empty string (as redirection) returned means success */
	            if (isset($output->result) && $output->result === false)
	            {
					$installResult = false;
					if (isset($output->message) && $output->message != '')
					{
						$mes = $output->message;
					}
					else
					{
						$mes = JText::_('JSN_SAMPLE_DATA_INSTALL_FAILED');
					}

					/* Only show warning for the extension which actually has sample data */
					if ($extInfo->hasData === true)
					{
						$failedExts[$extInfo->name]['message'] = JText::sprintf('JSN_SAMPLE_DATA_WARNING_EXT_INSTALL_FAILED', $extInfo->description);
					}
	            }
	            else
	            {
	            	$installResult = true;

	            	/**
	            	 * As ImageShow might not have completed the installation
	            	 * itself, so Joomla cannot remove the installation file.
	            	 * We need to delete installer package using an existing
	            	 * function of SampleData Helper.
	            	 */
	            	$sdHelperInstance->deleteSampleDataFile($downloadResult);

	            	/* Enable plugins as were not enabled by JInstaller */
	            	$sdHelperInstance->enableInstalledPlugin($extInfo->name, $extInfo->type);
	            }
			}

        	/* Change to the next extension if available */
        	if (array_key_exists($extName, $flatInstallExts))
        	{
        		unset($flatInstallExts[$extName]);
        		if (count($flatInstallExts) > 0)
        		{
					$arrayKeys = array_keys($flatInstallExts);
					$nextExt   = $arrayKeys[0];
					$childOf   = $flatInstallExts[$nextExt]['childOf'];
					$isLastExt = $flatInstallExts[$nextExt]['isLastExt'];
        		}
        		else
        		{
					$nextExt = '';
					$childOf = '';
					$isLastExt = false;
        		}

        		$sampleDataFile = $session->get($sdFileSesId, '', 'jsntemplatesession');

        		$session->set($sdExtInstallSesId, $flatInstallExts, 'jsntemplatesession');
	        	$session->set($sessionExtFailedId, $failedExts, 'jsntemplatesession');

				echo json_encode(array(
						'installExt'     => $installResult,
						'extName'        => $extName,
						'message'        => $mes,
						'nextExt'        => $nextExt,
						'childOf'        => $childOf,
						'isLastExt'      => $isLastExt,
						'tocontinue'     => $toContinue,
						'sampleDataFile' => $sampleDataFile
						)
					);
        	}
		}
		exit();
	}

	function installExtension()
	{
		$sdHelperInstance = new JSNSampleDataHelper();

		$packageName = JRequest::getVar('package', '', 'GET');
		$packagePath = JPATH_ROOT.DS.'tmp'.DS.$packageName;

		if (JFile::exists($packagePath))
		{
			jimport('joomla.installer.helper');
			$installer = JInstaller::getInstance();

			$tmpExtPackage = JPATH_ROOT.DS.'tmp'.DS.$packageName;

			$unpack = JInstallerHelper::unpack($packagePath);
			$installResult = $installer->install($unpack['dir']);
			JInstallerHelper::cleanupInstall($packagePath, $unpack['dir']);

			echo json_encode(array('result' => $installResult));
		}
		else
		{
			$mes = JText::sprintf('JSN_SAMPLE_DATA_EXT_PACKAGE_NOT_FOUND', $packageName);
			echo json_encode(array('result' => false, 'message' => $mes));
		}
		exit();
	}

	function installSampleData()
	{
		$file_name		= JRequest::getVar('file_name');
		$foldertmp		= JPATH_ROOT.DS.'tmp';
		$folderbackup	= $this->_template_folder_path.DS.'backups';
		$link 			= '';
		$errors			= array();
		$isNotExisted	= array();

		$obj_read_xml_file 	= new JSNReadXMLFile();

		$session 			= JFactory::getSession();
		$login_identifier 	= md5('state_login_'.strtolower($this->_template_name));
		$sessionExtFailedId = md5('exts_failed_install_'.strtolower($this->_template_name));
		//$identifier 		= md5('state_installation_'.strtolower($this->_template_name));
		$state_login		= $session->get($login_identifier, false, 'jsntemplatesession');
		$failedExts    		= $session->get($sessionExtFailedId, array(), 'jsntemplatesession');

		if(!$state_login) jexit('Invalid Token');

		$obj_sample_data_helper			= new JSNSampleDataHelper();
		$array_non_basic_module			= $obj_sample_data_helper->getNonBasicModule();
		$array_non_basic_admin_module	= $obj_sample_data_helper->getNonBasicAdminModule();
		$array_3rd_extension_menu		= $obj_sample_data_helper->getThirdExtensionMenus();
		$domain							= $obj_sample_data_helper->getDomain();

		if (!is_writable($foldertmp))
		{
			$obj_sample_data_helper->deleteSampleDataFile($file_name);
			echo json_encode(array('download' => false, 'message' => JText::_('JSN_SAMPLE_DATA_TEMP_FOLDER_UNWRITABLE'), 'redirect_link'=>$link));
			return;
		}
		$path = $foldertmp.DS.$file_name;
		if (!JFile::exists($path))
		{
			echo json_encode(array('install' => false, 'message' => JText::_('JSN_SAMPLE_DATA_PACKAGE_FILE_NOT_FOUND'), 'redirect_link'=>$link, 'manual'=>true));
			return;
		}
		$unpackage = $obj_sample_data_helper->unpackPackage($file_name);

		if ($unpackage)
		{
			$sample_xml_data = $obj_read_xml_file->getSampleDataFileContent($unpackage, $this->_template_name );
			$installed_data = $sample_xml_data['installed_data'];

			if ($installed_data && is_array($installed_data))
			{
				if (trim($sample_xml_data['version']) != trim($this->_template_version))
				{
					$obj_sample_data_helper->deleteSampleDataFile($file_name);
					$obj_sample_data_helper->deleteSampleDataFolder($unpackage);
					echo json_encode(array('install' => false, 'message' => JText::_('JSN_SAMPLE_DATA_OUTDATED_PRODUCT'), 'redirect_link'=>$link));
					return;
				}
				else
				{
					foreach ($failedExts as $key => $value) {
						unset($installed_data[$key]);
						if ($value['message'] != '')
						{
							$errors[] = $value['message'];
						}
						$isNotExisted[] = $key;
					}

					$obj_backup	= JSNBackup::getInstance();
					if (is_writable($folderbackup))
					{
						$backup_file_name = $obj_backup->executeBackup($this->_template_folder_path.DS.'backups',$domain, $installed_data);
					}
					else
					{
						$backup_file_name = '';
					}
					$obj_sample_data_helper->deleteNonBasicAdminModule();
					$obj_sample_data_helper->installSampleData($installed_data);
					if (count($isNotExisted))
					{
						$obj_sample_data_helper->deleteRecordAssetsTableByName($isNotExisted);
					}

					$obj_sample_data_helper->runQueryNonBasicModule($array_non_basic_module);
					$obj_sample_data_helper->runQueryNonBasicModule($array_non_basic_admin_module, true);
					$obj_sample_data_helper->restoreThirdExtensionMenus($array_3rd_extension_menu);
					$obj_sample_data_helper->rebuildMenu();
					$obj_sample_data_helper->copyContentFromFilesFolder($unpackage);
					$obj_sample_data_helper->deleteSampleDataFolder($unpackage);
					$obj_sample_data_helper->setDefaultTemplate(strtolower($this->_template_name));
					$obj_sample_data_helper->deleteSampleDataFile($file_name);

					//$session->set($identifier, true, 'jsntemplatesession');
					$session->set($login_identifier, false, 'jsntemplatesession');
					echo json_encode(array('install' => true, 'message'=>'', 'redirect_link'=>$link, 'warnings'=>$errors, 'backup_file_name'=>$backup_file_name));

					$session->clear($sessionExtFailedId, 'jsntemplatesession');
					return;
				}
			}
			else
			{
				$obj_sample_data_helper->deleteSampleDataFile($file_name);
				echo json_encode(array('install' => false, 'message' => JText::_('JSN_SAMPLE_DATA_INVALID'), 'redirect_link'=>$link, 'manual'=>true));
				return;
			}
		}
		else
		{
			$obj_sample_data_helper->deleteSampleDataFile($file_name);
			echo json_encode(array('install' => false, 'message' => JText::_('JSN_SAMPLE_DATA_UNABLE_EXTRACT_PACKAGE'), 'redirect_link'=>$link));
			exit();
		}

		return;
	}

	function backupModifiedFile()
	{
		$session 			= JFactory::getSession();
		$login_identifier 	= md5('state_update_login_'.strtolower($this->_template_name));
		$state_login		= $session->get($login_identifier, false, 'jsntemplatesession');

		if(!$state_login) jexit('Invalid Token');

		$obj_updater_helper	= new JSNUpdaterHelper();
		$backup_result 		= $obj_updater_helper->backupModifiedFile();

		if ($backup_result)
		{
			echo json_encode(array('backup' => true, 'backup_file_name'=>(is_string($backup_result))?$backup_result:''));
			exit();
		}
		else
		{
			$obj_updater_helper->destroySession();
			echo json_encode(array('backup' => false, 'backup_file_name'=>''));
			exit();
		}
	}

	function manualUpdateTemplate()
	{
		jimport('joomla.utilities.xmlelement');
		$session 					= JFactory::getSession();
		$login_identifier 			= md5('state_update_login_'.strtolower($this->_template_name));
		$state_login				= $session->get($login_identifier, false, 'jsntemplatesession');
		$modified_file_identifier 	= md5('state_modified_file_'.strtolower($this->_template_name));
		$modified_files				= $session->get($modified_file_identifier, array(), 'jsntemplatesession');

		if(!$state_login) jexit('Invalid Token');

		$obj_updater_helper	= new JSNUpdaterHelper();
		$extract_dir 		= JRequest::getCmd('extract_dir');
		$backup_file 		= JRequest::getCmd('backup_file');

		$extract_dir_path 	= JPATH_ROOT.DS.'tmp'.DS.$extract_dir;
		$files				= $obj_updater_helper->compareChecksumFile($extract_dir_path);

		$installer 			= JSNInstaller::getInstance();
		$strXML				= '';
		$tmpArray			= array();
		$deleted_files		= array();
		$new_manifest 		= $obj_updater_helper->findManifest($extract_dir_path);
		$tmp_new_version  	= $new_manifest->version;
		$new_version		= $tmp_new_version->data();
		$old_version		= $this->_template_version;
		$compare_version	= $this->_obj_utils->compareVersion($new_version, $old_version);

		if ($compare_version == 0)
		{
			$tmpArray = array_merge($tmpArray, $modified_files);
		}
		else
		{
			if (isset($files['added']))
			{
				$tmpArray = array_merge($tmpArray, $files['added']);
			}

			if (isset($files['modified']))
			{
				$tmpArray = array_merge($tmpArray, $files['modified']);
			}

			if (isset($files['deleted']))
			{
				$deleted_files	= $files['deleted'];
			}
		}
		$tmpArray  = array_merge($tmpArray, array('template.checksum'));
		if (count($tmpArray))
		{
			$strXML = '<?xml version="1.0" encoding="UTF-8" ?><extension><files>';
			foreach ($tmpArray as $value)
			{
				$strXML .= '<filename>'.$value.'</filename>';
			}
			foreach ($files['modified'] as $value)
			{
				$strXML .= '<filename>'.$value.'</filename>';
			}
			$strXML .= '</files></extension>';
		}

		if (!empty($strXML))
		{
			$new_tmp_xml = new JXMLElement($strXML);
		}
		else
		{
			$new_tmp_xml = null;
		}
		if (!$installer->install($extract_dir_path, $new_tmp_xml, $deleted_files))
		{
			echo json_encode(array('update' => false, 'message' => JText::_('JSN_UPDATE_MANUAL_INSTALL_FAILED'),'backup_file_name'=>'', 'from_version'=>$old_version, 'to_version'=>$new_version));
		}
		else
		{
			echo json_encode(array('update' => true, 'backup_file_name'=>$backup_file, 'from_version'=>$old_version, 'to_version'=>$new_version));
		}
		$obj_updater_helper->destroySession();
		if (is_dir($extract_dir_path))
		{
			JFolder::delete($extract_dir_path);
		}
		exit();
	}

	function downloadTemplatePackage()
	{
		$session 					= JFactory::getSession();
		$post						= JRequest::get('post');
		$login_identifier 			= md5('state_update_login_'.strtolower($this->_template_name));
		$state_login				= $session->get($login_identifier, false, 'jsntemplatesession');
		$link						= '';
		if(!$state_login) jexit('Invalid Token');

		if ($this->_template_edition != '' && $this->_template_edition != 'free')
		{
			$edition = $this->_template_edition;
		}
		else
		{
			$edition = 'free';
		}

		$tmp_path 	= JPATH_ROOT.DS.'tmp';
		if (is_writable($tmp_path))
		{
			$post['customer_password'] 	= JRequest::getString('customer_password', '', 'post', JREQUEST_ALLOWRAW);
			$obj_updater_helper	= new JSNAutoUpdaterHelper();
			$result = $obj_updater_helper->downloadTemplatePackage($post);
			if ($result)
			{
				$errorCode = strtolower((string) $result);
				switch ($errorCode)
				{
					case 'err00':
						$message = JText::_('JSN_SAMPLE_DATA_LIGHTCART_RETURN_ERR00');
						break;
					case 'err01':
						$message = JText::_('JSN_SAMPLE_DATA_LIGHTCART_RETURN_ERR01');
						break;
					case 'err02':
						$message = JText::_('JSN_SAMPLE_DATA_LIGHTCART_RETURN_ERR02');
						break;
					case 'err03':
						$message = JText::_('JSN_SAMPLE_DATA_LIGHTCART_RETURN_ERR03');
						break;
					default:
						$message = '';
						break;
				}
				if ($message != '')
				{
					$obj_updater_helper->destroySession();
					echo json_encode(array('download'=>false, 'file_name'=> '', 'connection'=>true, 'message'=>$message, 'manual'=>false));
				}
				else
				{
					echo json_encode(array('download'=>true, 'file_name'=> (string) $result, 'connection'=>true, 'message'=>'', 'manual'=>false));
				}
				return;
			}
			else
			{
				if ($edition == 'free')
				{
					$templateNameParts = explode('_', strtolower($this->_template_name));
					$link = 'http://www.joomlashine.com/joomla-templates/'.$templateNameParts[0].'-'.$templateNameParts[1].'-download.html';
				}
				else
				{
					$link = 'http://www.joomlashine.com/customer-area.html';
				}
				$obj_updater_helper->destroySession();
				echo json_encode(array('download'=>false, 'file_name'=> '', 'message' => JText::sprintf('JSN_UPDATE_DOWNLOAD_FAILED', $link), 'connection'=>false, 'manual'=>true));
				return;
			}
		}
		else
		{
			$obj_updater_helper->destroySession();
			echo json_encode(array('download'=>false, 'file_name'=> '', 'message' => JText::_('JSN_UPDATE_TEMP_FOLDER_UNWRITABLE'), 'connection'=>false, 'manual'=>false));
			return;
		}
	}

	function autoUpdateTemplate()
	{
		jimport('joomla.utilities.xmlelement');
		$session 					= JFactory::getSession();
		$login_identifier 			= md5('state_update_login_'.strtolower($this->_template_name));
		$state_login				= $session->get($login_identifier, false, 'jsntemplatesession');
		$modified_file_identifier 	= md5('state_modified_file_'.strtolower($this->_template_name));
		$modified_files				= $session->get($modified_file_identifier, array(), 'jsntemplatesession');
		$tmp_path					= JPATH_ROOT.DS.'tmp';
		if(!$state_login) jexit('Invalid Token');
		if ($this->_template_edition != '' && $this->_template_edition != 'free')
		{
			$edition = $this->_template_edition;
		}
		else
		{
			$edition = 'free';
		}

		if ($edition == 'free')
		{
			$templateNameParts = explode('_', strtolower($this->_template_name));
			$link = 'http://www.joomlashine.com/joomla-templates/'.$templateNameParts[0].'-'.$templateNameParts[1].'-download.html';
			$msg_manual_download = JText::sprintf('JSN_UPDATE_DOWNLOAD_FREE', $link);
		}
		else
		{
			$link = 'http://www.joomlashine.com/customer-area.html';
			$msg_manual_download = '<span class="jsn-red-message">Unable to download file</span>. Please try to <a href="http://www.joomlashine.com/customer-area.html" target="_blank" class="link-action">download file from Customer Area</a>, then select it:';
		}

		$package_name 				= JRequest::getCmd('package_name');
		$backup_file 				= JRequest::getCmd('backup_file');
		$package_path				= $tmp_path.DS.$package_name;
		$obj_updater_helper			= new JSNUpdaterHelper();
		$obj_auto_updater_helper	= new JSNAutoUpdaterHelper();
		$unpack 					= $obj_auto_updater_helper->unpack($package_path);
		if ($unpack)
		{
			$files			= $obj_updater_helper->compareChecksumFile($unpack['dir']);
			$installer 		= JSNInstaller::getInstance();
			$strXML				= '';
			$tmpArray			= array();
			$deleted_files		= array();
			$new_manifest 		= $obj_updater_helper->findManifest($unpack['dir']);
			$tmp_new_version  	= $new_manifest->version;
			$new_version		= $tmp_new_version->data();
			$old_version		= $this->_template_version;
			$compare_version	= $this->_obj_utils->compareVersion($new_version, $old_version);

			if ($compare_version == 0)
			{
				$tmpArray = array_merge($tmpArray, $modified_files);
			}
			else
			{
				if (isset($files['added']))
				{
					$tmpArray = array_merge($tmpArray, $files['added']);
				}

				if (isset($files['modified']))
				{
					$tmpArray = array_merge($tmpArray, $files['modified']);
				}

				if (isset($files['deleted']))
				{
					$deleted_files	= $files['deleted'];
				}
			}
			$tmpArray  = array_merge($tmpArray, array('template.checksum'));
			if (count($tmpArray))
			{
				$strXML = '<?xml version="1.0" encoding="UTF-8" ?><extension><files>';
				foreach ($tmpArray as $value)
				{
					$strXML .= '<filename>'.$value.'</filename>';
				}
				foreach ($files['modified'] as $value)
				{
					$strXML .= '<filename>'.$value.'</filename>';
				}
				$strXML .= '</files></extension>';
			}

			if (!empty($strXML))
			{
				$new_tmp_xml = new JXMLElement($strXML);
			}
			else
			{
				$new_tmp_xml = null;
			}

			if (!$installer->install($unpack['dir'], $new_tmp_xml, $deleted_files))
			{
				echo json_encode(array('update' => false, 'backup_file_name'=>'', 'message' => JText::sprintf('JSN_UPDATE_INSTALL_FAILED', $link), 'manual'=>true));
			}
			else
			{
				echo json_encode(array('update' => true, 'backup_file_name'=>$backup_file, 'message'=>'', 'manual'=>false));
			}
		}
		else
		{
			echo json_encode(array('update'=>false, 'backup_file_name'=>'', 'message' => JText::sprintf('JSN_UPDATE_UNPACK_FAILED', $link), 'manual'=>true));
		}
		$obj_updater_helper->destroySession();
		$obj_updater_helper->deleteInstallationPackage($unpack['packagefile'], $unpack['dir']);
		return;
	}
}