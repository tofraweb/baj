<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.model');

class n3tTemplateModelUpdate extends JModel
{

	public function __construct()
	{
		parent::__construct();
	}

	public function getVersionInfo()
	{	 
    return n3tTemplateHelperUpdate::versionInfo();
	}
}