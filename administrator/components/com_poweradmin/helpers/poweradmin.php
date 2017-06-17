<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: poweradmin.php 13370 2012-06-18 04:06:36Z binhpt $
-------------------------------------------------------------------------*/
 
// No direct access
defined('_JEXEC') or die;

class PowerAdminHelper
{
	private static $_cachedManifest = null;
	private static $_installedComponents = null;

	function getAssetsPath()
	{
		return JURI::root().'administrator/components/com_poweradmin/assets/';
	}
	
	/**
	 * Retrieve current version of PowerAdmin from manifest file
	 * @return string version
	 */
	public static function getVersion ()
	{
		return self::getCachedManifest()->version;
	}
	
	/**
	 * Retrieve latest version of PowerAdmin from JoomlaShine website.
	 * This method use to check for update
	 * @return string version
	 */
	public static function getLatestVersion ()
	{
		JSNFactory::localimport('libraries.joomlashine.request.httprequest');
		
		$request = new JSNHttpRequest(JSN_POWERADMIN_CHECK_VERSION_URL);
		$result  = $request->DownloadToString();
		$version = null;
		
		if ($result !== false) {
			$versionInfo = json_decode($result);
			$version = @$versionInfo->version;
		}
		
		return $version;
	}
	
	/**
	 * Retrieve cached manifest information from database
	 * @return object
	 */
	public static function getCachedManifest ($extension = 'com_poweradmin')
	{
		if (self::$_cachedManifest === null) {
			$dbo = JFactory::getDbo();
			$dbo->setQuery(
				sprintf(
					'SELECT manifest_cache FROM #__extensions WHERE element=%s LIMIT 1',
					$dbo->quote($extension)
				)
			);

			self::$_cachedManifest = json_decode($dbo->loadResult());
		}
		
		return self::$_cachedManifest;
	}

	/**
	* Return array of search coverage
	*/
	public static function getSearchCoverages()
	{
		$coverages = array(
			'articles',
			'categories',
			'components',
			'modules',
			'plugins',
			'menus',
			'adminmenus',
			'templates',
			'users'
		);

		$installedComponents = self::getInstalledComponents();
		if (in_array('com_k2', $installedComponents)) {
			$coverages[] = 'k2';
		}

		return $coverages;
	}

	/**
	 * Retrieve list installed components
	 * @return mixed
	 */
	public static function getInstalledComponents ()
	{
		if (self::$_installedComponents == null) {
			$dbo = JFactory::getDBO();
			$dbo->setQuery("SELECT element FROM #__extensions WHERE type='component'");

			self::$_installedComponents = $dbo->loadResultArray();
		}

		return self::$_installedComponents;
	}
	
	/**
	 * Get jsnadminbar plugin params
	 */
	public static function getJSNAdminBarParams($key='')
	{
		$jsnadminbarPlg = JPluginHelper::getPlugin('system','jsnpoweradmin');
		$registry = new JRegistry;
		$registry->loadJSON($jsnadminbarPlg->params);
		$params = $registry->toArray();
		if ($key && isset($params[$key])) return $params[$key];
			else return $params;
	}
	
	/**
	 * Genarate url with suffix is current
	 * version of jsn poweradmin 
	 */
	public static function makeUrlWithSuffix($fileUrl)
	{
		$currentVersion	= '';
		if($fileUrl){
			$currentVersion		= self::getVersion();
			$fileUrl	.= '?v=' . $currentVersion;				
		}
		return $fileUrl;
	}
}