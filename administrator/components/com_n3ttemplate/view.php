<?php
/**
 * @package n3tTemplate
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2010 - 2012 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die('Restricted Access');

jimport('joomla.application.component.view');

class n3tTemplateView extends JView
{
	public function __construct()
	{
		parent::__construct();
		$this->addHelperPath(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers');
		$this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'views'.DS.$this->getName().DS.'tmpl');
	}
}