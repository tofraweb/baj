<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: package.auth.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
include_once(dirname(__FILE__).DS.'package.php');

class JSNDownloadPackageAuth extends JSNDownloadPackage
{
	var $_tmpPackageName 	= '';
	var $_downloadURL		= '';
	var $_tmpFolder			= '';
	var $_tmpPackagePath	= '';
	var $_msgError 			= '';
	var $_getFields			= '';

	function __construct($url)
	{
		$this->_tmpFolder 	= JPATH_ROOT.DS.'tmp'.DS;
		$this->_downloadURL = $url;

		jimport('joomla.filesystem.archive');
		$this->_jarchiveZip = JArchive::getAdapter('zip');
	}

	function setOptions($options = null)
	{
		if ($options)
		{
			$this->_identifyName 	= $options->identifyName;
			$this->_edition		 	= $options->edition;
			$this->_joomlaVersion 	= $options->joomlaVersion;
			$this->_tmpPackageName	= 'jsn_tmp_package_'.$options->identifyName.'.zip';
			$this->_tmpPackagePath 	= $this->_tmpFolder.$this->_tmpPackageName;
			$this->_getFields 		.= '&identified_name='.urlencode($this->_identifyName).'&edition='.urlencode($this->_edition).'&joomla_version='.urlencode($this->_joomlaVersion);

			if (isset($options->username) && $options->username != '') {
				$this->_getFields .= '&username='.urlencode($options->username);
			}

			if (isset($options->password) && $options->password != '') {
				$this->_getFields .= '&password='.urlencode($options->password);
			}
			
			if (isset($options->upgrade)) {
				$this->_getFields .= '&upgrade='.$options->upgrade;
			}
		}
	}

	function download($options)
	{
		$this->setOptions($options);

		if (function_exists('fsockopen')) {
			return $this->fsocketopenDownload();
		} else if ($this->_cURLCheckFunctions()) {
			return $this->cURLdownload();
		} elseif ($this->_fOPENCheck()) {
			return $this->fOPENdownload();
		}

		return false;
	}

	function cURLdownload()
	{
		@set_time_limit(ini_get('max_execution_time'));

		$fp = fopen($this->_tmpPackagePath, "w");
		$cp = curl_init($this->_downloadURL.$this->_getFields);

		curl_setopt($cp, CURLOPT_FILE, $fp);
		curl_setopt($cp, CURLOPT_HEADER, 0);

		$result = curl_exec($cp);
		$requestInfo = array();

		if (!curl_errno($cp)) {
			$requestInfo = curl_getinfo($cp);
		}

		curl_close($cp);
		fclose($fp);

		if ($result)
		{
			if (is_array($requestInfo) && isset($requestInfo['size_download']))
			{
				if ($requestInfo['size_download'] != @filesize($this->_tmpPackagePath)) {
					$this->_msgError = JText::_('JSN_DOWNLOAD_FILE_CORRUPT');
					return false;
				}
			}

			$binaryData = $this->readFileZipToBinaryData($this->_tmpPackagePath);

			if (!$this->_jarchiveZip->checkZipData($binaryData))
			{
				if (JFile::exists($this->_tmpPackagePath)) {
					JFile::delete($this->_tmpPackagePath);
				}

				$this->_msgError = $binaryData;

				return false;
			}

			return basename($this->_tmpPackagePath);
		}
		else
		{
			if (JFile::exists($this->_tmpPackagePath))
			{
				JFile::delete($this->_tmpPackagePath);
			}
			return false;
		}

		return false;
	}

	function fOPENdownload()
	{
		$handle = @fopen($this->_downloadURL.$this->_getFields, 'r');

		if (!$handle) {
			return false;
		}

		$contents = null;

		while (!feof($handle))
		{
			$contents .= fread($handle, 8192);
			if ($contents === false) {
				return false;
			}
		}

		if (!$this->_jarchiveZip->checkZipData($contents))
		{
			if (JFile::exists($this->_tmpPackagePath)) {
				JFile::delete($this->_tmpPackagePath);
			}

			$this->_msgError = $contents;

			return false;
		}

		JFile::write($this->_tmpPackagePath, $contents);
		@set_time_limit(ini_get('max_execution_time'));
		fclose($handle);

		return basename($this->_tmpPackagePath);
	}

	function fsocketopenDownload()
	{
		require_once dirname(dirname(__FILE__)).DS.'request/httprequest.php';
		
		$objJSNHTTP = new JSNHTTPRequest($this->_downloadURL.$this->_getFields);//JSNISFactory::getObj('classes.jsn_is_httprequest', null, $this->_downloadURL.$this->_getFields);
		$result = $objJSNHTTP->DownloadToString();

		if (!$this->_jarchiveZip->checkZipData($result))
		{
			$this->_msgError = $result;
			return false;
		}

		JFile::write($this->_tmpPackagePath, $result);
		@set_time_limit(ini_get('max_execution_time'));

		$requestHeader = $objJSNHTTP->getRequestHeader();

		if (is_array($requestHeader) && isset($requestHeader['content-length']))
		{
			if ($requestHeader['content-length'] != @filesize($this->_tmpPackagePath)) {
				$this->_msgError = JText::_('JSN_DOWNLOAD_FILE_CORRUPT');
				return false;
			}
		}

		return basename($this->_tmpPackagePath);
	}
}