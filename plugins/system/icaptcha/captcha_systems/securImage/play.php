<?php

/**
 * Project:     Securimage: A PHP class for creating and managing form CAPTCHA images<br />
 * File:        securimage_play.php<br />
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or any later version.<br /><br />
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.<br /><br />
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA<br /><br />
 *
 * Any modifications to the library should be indicated clearly in the source code
 * to inform users that the changes are not a part of the original software.<br /><br />
 *
 * If you found this script useful, please take a quick moment to rate it.<br />
 * http://www.hotscripts.com/rate/49400.html  Thanks.
 *
 * @link http://www.phpcaptcha.org Securimage PHP CAPTCHA
 * @link http://www.phpcaptcha.org/latest.zip Download Latest Version
 * @link http://www.phpcaptcha.org/Securimage_Docs/ Online Documentation
 * @copyright 2011 Drew Phillips
 * @author Drew Phillips <drew@drew-phillips.com>
 * @version 3.0 (October 2011)
 * @package Securimage
 *
 */
define( '_JEXEC', 1 );

define('JPATH_BASE', '../../../../../' );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'SECURIMAGE_PATH', dirname(__FILE__) );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );


$app	= &JFactory::getApplication('site');
// Instantiate the application.
$app = JFactory::getApplication('site');

$config		=& JFactory::getConfig();
/**
 * @var int	Will set error report to maximum if global settings is set to maximum, otherwise set error reporting to none this will avoid problems with Joom!Fish and some SEF extensions
 */
$error_reporting_level	= $config->getValue('config.error_reporting');
if($error_reporting_level != 6143){
	$error_reporting_level = 0;
}
error_reporting($error_reporting_level);

// Initialise the application.
//$app->initialise();

$session   = &JFactory::getSession();

$db			= & JFactory::getDBO();

$query = 'SELECT params ' 
			. ' FROM #__extensions '
			. ' WHERE element 	='.$db->Quote('icaptcha')
				.' AND type		='.$db->Quote('plugin')
				.' AND folder	='.$db->Quote('system')
			;
$db->setQuery($query);

$params 	= $db->loadResult();
$registry 	= new JRegistry();
$registry->loadString($params);
$params		= $registry;
//echo '<pre>'; print_r($params); exit;
		
include 'securimage.php';

$img = new securimage();

$lang   = &JFactory::getLanguage();


// To use an alternate language, uncomment the following and download the files from phpcaptcha.org
// $img->audio_path = $img->securimage_path . '/audio/es/';

// If you have more than one captcha on a page, one must use a custom namespace
// $img->namespace = 'form2';

if(is_readable(SECURIMAGE_PATH.DS.'audio'.DS.$lang->getTag().DS.'A.wav')){
	$img->audio_path	= SECURIMAGE_PATH.DS.'audio'.DS.$lang->getTag().DS;
}

$img->outputAudioFile();
