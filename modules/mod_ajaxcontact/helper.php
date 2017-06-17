<?php
/**
 * @package    AjaxContact
 * @author     Douglas Machado {@link http://idealextensions.com}
 * @author     Created on 22-Mar-2011
 * @license    GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
require_once( dirname(__FILE__).DS."customFields.class.php" );
require_once( dirname(__FILE__).DS."browser.php" );
JHTML::_('behavior.mootools');


abstract class modAjaxContactHelper
{
	public static function loadJavascript() {
		$doc 	=& JFactory::getDocument();
		$lang	= &JFactory::getLanguage();
//echo $lang->getTag(); exit; 
		$script	= '';
		
		$mooToolsLanguage	= array(
								"ar-AA"	=>	"ar",
								"ca-CA"	=>	"ca-CA",
								"ca-ES"	=>	"ca-CA",
								"de-DE"	=>	"de-DE",
								"de-DE"	=>	"de-CH",
								"zh-CN"	=>	"zh-CHS",
								"zh-TW"	=>	"zh-CHT",
								"cs-CZ"	=>	"cs-CZ",
								"da-DK"	=>	"da-DK",
								"nl-NL"	=>	"nl-NL",
								"nl-BE"	=>	"nl-NL",
								"en-US"	=>	"en-US",
								"en-UK"	=>	"en-UK",
								"en-AU"	=>	"en-UK",
								"he-IL"	=>	"he-IL",
								"et-EE"	=>	"et-EE",
								"fi-FI"	=>	"fi-FI",
								"fr-FR"	=>	"fr-FR",
								"de-CH"	=>	"de-CH",
								"de-DE"	=>	"de-DE",
								"hu-HU"	=>	"hu-HU",
								"it-IT"	=>	"it-IT",
								"ja-JP"	=>	"ja-JP",
								"no-NO"	=>	"no-NO",
								"fa-IR"	=>	"fa",
								"pl-PL"	=>	"pl-PL",
								"pt-BR"	=>	"pt-BR",
								"pt-PT"	=>	"pt-PT",
								"ru-RU"	=>	"ru-RU",
								"si-SI"	=>	"si-SI",
								"es-AR"	=>	"es-AR",
								"es-ES"	=>	"es-ES",
								"sv-SE"	=>	"sv-SE",
								"tr-TR"	=>	"tr-TR",
								"uk-UA"	=>	"uk-UA"
							);
		if(array_key_exists($lang->getTag(),$mooToolsLanguage)){
			$script	.= "Locale.use('".$mooToolsLanguage[$lang->getTag()]."');";
		}else{
			jimport('joomla.filesystem.file');
			if(JFile::exists(JPATH_ROOT.DS.'modules'.DS.'mod_ajaxcontact'.DS.'js'.DS.'mootools-languages'.DS.'locale.'.$lang->getTag().'.js')){
				$doc->addScript(JURI::root().'modules/mod_ajaxcontact/js/mootools-languages/locale.'.$lang->getTag().'.js');
				$script	.= "Locale.use('".$lang->getTag()."');";
			}
		}
		
		$doc->addScriptDeclaration( $script );
	}
}


