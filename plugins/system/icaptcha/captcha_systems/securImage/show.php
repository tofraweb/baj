<?php
error_reporting(0);

// Set flag that this is a parent file
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


// Change some settings

$img->image_width		= (int) $params->get('captcha_systems-securImage-width', 	150);
$img->image_height		= (int) $params->get('captcha_systems-securImage-height', 	70);
$img->perturbation		= (float) $params->get('captcha_systems-securImage-perturbation',	0.7); // 1.0 = high distortion, higher numbers = more distortion
if($params->get('captcha_systems-securImage-length') == 'random'){
	$img->code_length 	= rand(3, 5);
}else{
	$img->code_length 	= $params->get('captcha_systems-securImage-length',	4);
}
if($params->get('captcha_systems-securImage-ttf') ){
	if(is_readable(SECURIMAGE_PATH.DS.'ttf'.DS.$params->get('captcha_systems-securImage-ttf',	'AHGBold.ttf')) ){
		$img->ttf_file 			= SECURIMAGE_PATH.DS.'ttf'.DS.$params->get('captcha_systems-securImage-ttf',	'AHGBold.ttf');
	}
}
$img->image_bg_color	= new Securimage_Color("#".$params->get('captcha_systems-securImage-bg_color',		'FFFFFF'));
$img->text_color		= new Securimage_Color("#".$params->get('captcha_systems-securImage-text_color', 	'3D3D3D'));
$img->line_color		= new Securimage_Color("#".$params->get('captcha_systems-securImage-line_color', 	'3D3D3D'));
$img->signature_color	= new Securimage_Color("#".$params->get('captcha_systems-securImage-signature_color', 	'FFFFFF'));
$img->image_signature	= $params->get('captcha_systems-securImage-image_signature', 	'');
$img->num_lines			= (int) $params->get('captcha_systems-securImage-number_lines', 	8);
$img->charset			= $params->get('captcha_systems-securImage-charset', 	'ABCDEFGHKLMNPRSTUVWYZabcdefghkmnprstuvwyz23456789');

$bgimg	= '';
if($params->get('captcha_systems-securImage-background') != '-1' ){
	if(is_readable(SECURIMAGE_PATH.DS.'backgrounds'.DS.$params->get('captcha_systems-securImage-background',	'letters-x.jpg'))){
		$bgimg 			= SECURIMAGE_PATH.DS.'backgrounds'.DS.$params->get('captcha_systems-securImage-background',	'letters-x.jpg');
	}
}

$lang   = &JFactory::getLanguage();


if(is_readable(SECURIMAGE_PATH.DS.'audio'.DS.$lang->getTag().DS.'a.wav')){
	$img->audio_path	= SECURIMAGE_PATH.DS.'audio'.DS.$lang->getTag().DS;
}
	



// You can customize the image by making changes below, some examples are included - remove the "//" to uncomment

//$img->captcha_type    = Securimage::SI_CAPTCHA_MATHEMATIC; // show a simple math problem instead of text
//$img->noise_level     = 5;
//$img->noise_color     = $img->text_color;
//$img->use_transparent_text = true;
//$img->text_transparency_percentage = 30; // 100 = completely transparent
//$img->use_wordlist = true; 

// see securimage.php for more options that can be set



$img->show($bgimg);  // outputs the image and content headers to the browser
// alternate use: 
// $img->show('/path/to/background_image.jpg');
