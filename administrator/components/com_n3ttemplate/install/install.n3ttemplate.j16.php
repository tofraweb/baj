<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted access');

class com_n3ttemplateInstallerScript
{
  private $helper = null;

	private function createHelper($path)
	{
    if (!$this->helper) {
	    require_once($path.DS.'install'.DS.'install.helper.php');
		  $this->helper = new n3tTemplateInstallHelper();
    }
	}
	
  function install($parent) {
    $this->createHelper($parent->getParent()->getPath('extension_administrator'));
    $this->helper->install($parent);
  }

  function update($parent) {
    $this->createHelper($parent->getParent()->getPath('extension_administrator'));
    $this->helper->update($parent);
  }

  function uninstall($parent) {
    $this->createHelper($parent->getParent()->getPath('extension_administrator'));
    $this->helper->uninstall($parent);
  }

  function postflight($type, $parent) {
    $this->helper->postflight($type, $parent);
  }
}
?>