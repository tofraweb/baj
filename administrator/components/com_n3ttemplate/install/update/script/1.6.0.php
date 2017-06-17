<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted Access');

JFolder::delete($this->status->trg.DS.'assets');
JFile::delete($this->status->trg.DS.'admin.n3ttemplate.php');
JFile::delete($this->status->trg.DS.'manifest.xml');
JFile::delete($this->status->trg.DS.'install'.DS.'install.n3ttemplate.php');
JFile::delete($this->status->trg.DS.'install'.DS.'uninstall.n3ttemplate.php');
?>