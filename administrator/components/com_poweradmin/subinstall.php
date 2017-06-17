<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: subinstall.php 14374 2012-07-25 05:16:04Z hiepnv $
-------------------------------------------------------------------------*/

class com_poweradminInstallerScript
{
	/**
	 * @var JXMLElement
	 */
	private $_manifest = null;
	
	/**
	 * Contains all extensions is declared in subinstall section
	 * @var array
	 */
	private $_relatedExtensions = array();
	
	/**
	 * Implement preflight hook.
	 * This step will be verify permission for install/update process
	 * 
	 * @param string $mode
	 * @return boolean
	 */
	public function preflight ($mode, $parent)
	{
		$app 				= JFactory::getApplication();
		$installer			= $parent->getParent();
		
		$this->_manifest = $installer->getManifest();
		$this->_parseRelatedExtensions($installer);
		
		$canInstallExtension 		= true;
		$canInstallSiteLanguage  	= is_writable(JPATH_SITE.DS.'language');
		$canInstallAdminLanguage 	= is_writable(JPATH_ADMINISTRATOR.DS.'language');
		
		if ($canInstallSiteLanguage === false) {
			$app->enqueueMessage(sprintf('Cannot install language file at "%s"', JPATH_SITE.DS.'language'), 'error');
		}

		foreach (glob(JPATH_SITE.DS.'language/*', GLOB_ONLYDIR) as $dir) {
			if (!is_writable($dir)) {
				$canInstallSiteLanguage = false;
				$app->enqueueMessage(sprintf('Cannot install language file at "%s"', $dir), 'error');
			}
		}
		
		if ($canInstallAdminLanguage === false) {
			$app->enqueueMessage(sprintf('Cannot install language file at "%s"', JPATH_ADMINISTRATOR.DS.'language'), 'error');
		}

		foreach (glob(JPATH_ADMINISTRATOR.DS.'language/*', GLOB_ONLYDIR) as $dir) {
			if (!is_writable($dir)) {
				$canInstallAdminLanguage = false;
				$app->enqueueMessage(sprintf('Cannot install language file at "%s"', $dir), 'error');
			}
		}

		// Checking folder permissions for related extensions
		foreach ($this->_relatedExtensions as $extension) 
		{
			if ($extension->remove == true) {
				$this->_removeExtension($extension);
				continue;
			}

			switch ($extension->type) {
				case 'plugin':
					$path = JPATH_ROOT.DS.'plugins'.DS.$extension->folder;
					if (!is_dir($path) || !is_writable($path)) {
						$canInstallExtension = false;
						$app->enqueueMessage(sprintf('Cannot install %s "%s" because "%s" is readonly', $extension->type, $extension->name, $path), 'error');
					}
				break;

				case 'component':
					$sitePath = JPATH_SITE.DS.'components';
					$adminPath = JPATH_ADMINISTRATOR.DS.'components';

					if (!is_dir($sitePath) || !is_writable($sitePath)) {
						$canInstallExtension = false;
						$app->enqueueMessage(sprintf('Cannot install %s "%s" because "%s" is readonly', $extension->type, $extension->name, $sitePath), 'error');
					}

					if (!is_dir($adminPath) || !is_writable($adminPath)) {
						$canInstallExtension = false;
						$app->enqueueMessage(sprintf('Cannot install %s "%s" because "%s" is readonly', $extension->type, $extension->name, $adminPath), 'error');
					}
				break;

				case 'module':
					$path = ($extension->client == 'site') ? JPATH_SITE.DS : JPATH_ADMINISTRATOR.DS;
					$path.= 'modules';

					if (!is_dir($path) || !is_writable($path)) {
						$canInstallExtension = false;
						$app->enqueueMessage(sprintf('Cannot install %s "%s" because "%s" is readonly', $extension->type, $extension->name, $path), 'error');
					}
				break;
			}
		}
		
		return $canInstallExtension && $canInstallSiteLanguage && $canInstallAdminLanguage;
	}

	/**
	 * Implement postflight hook
	 * @return void
	 */
	public function postflight ($type, $parent)
	{
		$installer	= $parent->getParent();
		$app 		= JFactory::getApplication();
		
		$this->_manifest = $installer->getManifest();
		$this->_parseRelatedExtensions($installer);

		foreach ($this->_relatedExtensions as $extension) {
			if ($extension->remove == true) {
				continue;
			}

			$subInstaller = new JInstaller();
			if (!$subInstaller->install($extension->source)) {
				$app->enqueueMessage(sprintf('Error installing %s "%s"', $extension->type, $extension->name), 'error');
				continue;
			}

			$this->_updateExtensionSettings($extension);
			$app->enqueueMessage(sprintf('Install %s "%s" was successfull', $extension->type, $extension->name));
		}
		
		//load Advancemodules plugin after
		$isAdvmInstalled = count($this->_getExtension('advancedmodules', 'plugin'));
		if($isAdvmInstalled){
			$dbo = JFactory::getDBO();			
			$dbo->setQuery("UPDATE #__extensions SET ordering=1 WHERE element='advancedmodules' AND type='plugin'");
			@$dbo->query();			
		}
	}
	
	/**
	 * Implement uninstall hook
	 * @return boolean
	 */
	public function uninstall ($parent)
	{
		$installer = $parent->getParent();
		
		$this->_manifest = $installer->getManifest();
		$this->_parseRelatedExtensions($installer);
		$this->_disableAllRelatedExtensions();

		foreach ($this->_relatedExtensions as $extension) {
			$this->_removeExtension($extension);
		}
	}
	
	/**
	 * Retrieve related extensions from manifest file
	 * @return array
	 */
	private function _parseRelatedExtensions ($installer)
	{
		if (isset($this->_relatedExtensions) && is_array($this->_relatedExtensions) && !empty($this->_relatedExtensions)) {
			return;
		}

		$this->_relatedExtensions = array();

		if (isset($this->_manifest->subinstall) && $this->_manifest->subinstall instanceOf JXMLElement)
		{
			// Loop on each node to retrieve extension information
			foreach ($this->_manifest->subinstall->children() as $node)
			{
				// Verify tag name
				if ($node->name() !== 'extension') {
					continue;
				}
				
				// Retrieve information from attributes
				$attributes = $node->attributes();
				$name 		= (isset($attributes->name)) ? (string)$attributes->name : '';
				$type 		= (isset($attributes->type)) ? (string)$attributes->type : '';
				$folder 	= (isset($attributes->folder)) ? (string)$attributes->folder : '';
				$publish 	= (isset($attributes->publish) && ((string)$attributes->publish == 'true' || (string)$attributes->publish == 'yes'));
				$lock 		= (isset($attributes->lock) && ((string)$attributes->lock == 'true' || (string)$attributes->lock == 'yes'));
				$remove		= (isset($attributes->remove) && ((string)$attributes->remove == 'true' || (string)$attributes->remove == 'yes'));
				$client		= (isset($attributes->client)) ? (string)$attributes->client : 'site';
				$position	= (isset($attributes->position)) ? (string)$attributes->position : '';
				$ordering	= (isset($attributes->ordering)) ? (string)$attributes->ordering : '1';
				$title 		= (isset($attributes->title)) ? (string)$attributes->title : $name;
				
				if (empty($name) || empty($type) || !in_array($type, array('plugin', 'module', 'component'))) {
					continue;
				}
				
				if ($type == 'plugin' && empty($folder)) {
					continue;
				}
				
				$extension = new StdClass();
				$extension->type 	= $type;
				$extension->name 	= $name;
				$extension->folder 	= $folder;
				$extension->publish = $publish;
				$extension->lock 	= $lock;
				$extension->remove 	= $remove;
				$extension->client  = $client;
				$extension->source  = $installer->getPath('source') . DS . $attributes->dir;

				if ($type == 'module') {
					$extension->position = $position;
					$extension->ordering = $ordering;
					$extension->title = $title;
				}
				
				$this->_relatedExtensions[] = $extension;
			}
		}
	}

	private function _updateExtensionSettings ($extension)
	{
		$table = JTable::getInstance('Extension');
		$table->load(array('element' => $extension->name));
		$table->enabled = ($extension->publish == true) ? 1 : 0;
		$table->protected = ($extension->lock == true) ? 1 : 0;
		$table->client_id = ($extension->client == 'site') ? 0 : 1;
		$table->store();

		if ($extension->type == 'module') {
			$module = JTable::getInstance('module');
			$module->load(array('module' => $extension->name));

			$module->title = $extension->title;
			$module->ordering = $extension->ordering;
			$module->published = ($extension->publish == true) ? 1 : 0;
			$module->position = $extension->position;

			$module->store();

			if (is_numeric($module->id) && $module->id > 0) {
				$dbo = JFactory::getDBO();
				$dbo->setQuery("INSERT INTO #__modules_menu (moduleid, menuid) VALUES ({$module->id}, 0)");
				$dbo->query();
			}
		}

		return $this;
	}

	private function _disableAllRelatedExtensions ()
	{
		$dbo = JFactory::getDBO();

		foreach ($this->_relatedExtensions as $extension) {
			$dbo->setQuery("UPDATE #__extensions SET enabled=0 WHERE element='{$extension->name}'");
			$dbo->query();
		}

		return $this;
	}

	private function _disableExtension ($extension)
	{
		$dbo = JFactory::getDBO();
		$dbo->setQuery("UPDATE #__extensions SET enabled=0 WHERE element='{$extension->name}'");
		$dbo->query();
	}

	private function _unlockExtension ($extension)
	{
		$dbo = JFactory::getDBO();
		$dbo->setQuery("UPDATE #__extensions SET protected=0 WHERE element='{$extension->name}'");
		$dbo->query();
	}

	private function _removeExtension ($extension)
	{
		$app = JFactory::getApplication();

		$dbo = JFactory::getDBO();
		$dbo->setQuery("SELECT * FROM #__extensions WHERE element='{$extension->name}'");
		$extensions = $dbo->loadObjectList();

		foreach ($extensions as $ext) {
			$installer = new JInstaller();

			$this->_disableExtension($extension);
			$this->_unlockExtension($extension);

			if ($ext->extension_id > 0) {
				if ($installer->uninstall($extension->type, $ext->extension_id)) {
					$app->enqueueMessage(sprintf('%s "%s" has been uninstalled', ucfirst($extension->type), $extension->name));
				}
				else {
					$app->enqueueMessage(sprintf('Cannot uninstall %s "%s"', $extension->type, $extension->name . ' ' . $ext->extension_id));
				}
			}
		}
	}
	
	private function _getExtension($name, $type = null)
	{
		$dbo 	= JFactory::getDBO();
		$query 	= "SELECT * FROM #__extensions WHERE element='{$name}'" ;
		if($type) {
			$query .= " AND type='{$type}'";
		}		
		$dbo->setQuery($query);
		return $dbo->loadObjectList();
	}
}









