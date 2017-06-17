<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: language.php 13870 2012-07-10 09:01:26Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JSNISLanguageHelper
{
	public static function getSupportedLanguage ($area = 'site')
	{
		if ($area == 'site')
		{
			$path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_imageshow'.DS.'languages/site';
		}
		else
		{
			$path = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_imageshow'.DS.'languages/admin';
		}
		$files = glob("{$path}/*.ini");
		$supportedLanguages = array();
		if (count($files))
		{
			foreach ($files as $file) {
				$name = basename($file);
				if (preg_match('/^([a-z]+)\-([A-Z]+)\./', $name, $matches)) {
					$code = $matches[1].'-'.$matches[2];
					if (!in_array($code, $supportedLanguages)) {
						$supportedLanguages[] = $code;
					}
				}
			}
		}
		return $supportedLanguages;
	}
}




