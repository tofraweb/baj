<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: imageshow.php 13999 2012-07-14 03:41:28Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
class plgSystemImageShow extends JPlugin{
	var $_user  = null;
	var $_application = null;
	function plgSystemImageShow(& $subject, $config){
		$this->_user = JFactory::getUser();
		$this->_application = JFactory::getApplication();
		parent::__construct($subject, $config);
		$this->JSNDisablePHPErrMsg();
	}

	function JSNDisablePHPErrMsg()
	{
		$application = JFactory::getApplication();
		$appName 	= $application->getName();
		$option 	= JRequest::getVar('option');
		if($appName == 'administrator' && $option == 'com_imageshow')
		{
			if (function_exists('error_reporting'))
			{
				error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
			}
		}
	}

	function onAfterRoute()
    {
       	$application = JFactory::getApplication();

		$option 		= JRequest::getVar('option');
		$controller 	= JRequest::getVar('controller');
		$task 			= JRequest::getVar('task');

		if($application->getName() == 'administrator')
		{
			if($option == 'com_imageshow' && $controller == 'flex' && !empty($task))
			{
				include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_factory.php');
				$user = JFactory::getUser();
				if(empty($user->id))
				{
					$language = JFactory::getLanguage();
					$language->load('com_imageshow', JPATH_BASE, null, true);
					$objJSNFlex = JSNISFactory::getObj('classes.jsn_is_flex');
					echo $objJSNFlex->bindObject(false, JText::_('SHOWLIST_FLEX_LOGIN_FLEX'));
					jexit();
				}
			}
		}
    }

	function onAfterDispatch()
    {
		$doc 			= JFactory::getDocument();
		$objFO 			= JFactory::getApplication();
		$doc->addStyleSheet(JURI::root(true).'/components/com_imageshow/assets/css/style.css');
		if($objFO->getName() == 'site')
		{
			JHTML::_('behavior.mootools');
			$doc->addScript(JUri::root(true).'/components/com_imageshow/assets/js/swfobject.js');
			$doc->addScript(JUri::root(true).'/components/com_imageshow/assets/js/jsn_is_extultils.js');
			$doc->addScript(JUri::root(true).'/components/com_imageshow/assets/js/jsn_is_imageshow.js');
		}
	}

	function onAfterRender()
	{
		$document = JFactory::getDocument();
		if ($document instanceOf JDocumentHTML)
		{
			$template = $document->template;
			$content  = JResponse::getBody();
			if ($this->_application->isAdmin() && $this->_user->id > 0)
			{
				preg_match('/<body([^>]+)>/is', $content, $matches);
				$pos = strpos(@$matches[0], 'jsn-master');
				if (!$pos)
				{
					if(preg_match('/<body([^>]*)class\s*=\s*"([^"]+)"([^>]*)>/is', $content))
		    		{
						$content = preg_replace('/<body([^>]*)class\s*=\s*"([^"]+)"([^>]*)>/is', '<body\\1 class="jsn-master tmpl-'.$template.' \\2" \\3>', $content);
					}
					else
					{
						$content = preg_replace('/<body([^>]+)>/is', '<body\\1 class="jsn-master tmpl-'.$template.'">', $content);
					}
				}
				JResponse::setBody($content);
			}
		}
	}
}
?>