<?php
/**
 * @package    Ajax Contact
 * @author     Douglas Machado {@link http://ideal.fok.com.br}
 * @author     Created on 25-Mar-2009
 * @license		GNU/GPL, see license.txt in Joomla root directory
 * Ajax Contact is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses. 
 */

error_reporting(0);


// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE', '../../' );
define( 'DS', DIRECTORY_SEPARATOR );

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

// Instantiate the application.
$app = JFactory::getApplication('site');
// Initialise the application.
//$app->initialise(); // Getting 500 error

$config		=& JFactory::getConfig();
/**
 * @var int	Will set error report to maximum if global settings is set to maximum, 
 * otherwise set error reporting to none this will avoid problems with Joom!Fish and some SEF extensions
 */
$error_reporting_level	= $config->getValue('config.error_reporting');
if($error_reporting_level != 6143){
	$error_reporting_level = 0;
}
error_reporting($error_reporting_level);

	//print_r($_REQUEST); exit;
	
//set tmpl = component in order to avoid some problems
JRequest::setVar("tmpl", 'component');

class ajaxContact extends JObject{
	
	static function sendEmail(){
		 $mainframe	= JFactory::getApplication('site');
		 $app	= &$mainframe;
		// Check for request forgeries
		//JRequest::checkToken() or jexit( 'Invalid Token' );
//		echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
		
		$json			= array();
		$json['action']	= 'error';
		
		$CONFIG 	= new JConfig();
		$user 		= & JFactory::getUser();
		$db			= & JFactory::getDBO();
		$mail		= JFactory::getMailer();
		
		$curLang	= ajaxContact::getVar('lang');
		
		// Load common language files
		$lang 		=& JFactory::getLanguage();
		$lang->load('mod_ajaxcontact', JPATH_BASE, $curLang, true);
		
		$params		= ajaxContact::getParams(); 
		jimport('joomla.plugin.helper');
		
		//Compatibility with RV2 plugin by David Barrett <joomla@cedit.biz>
		$rvenabled = JPluginHelper::importPlugin( 'system', 'cedit_registrationvalidator' );
			if ( $rvenabled ) {
			// Check if blocked by Registration Validator
				$dispatcher	=& JDispatcher::getInstance();
				$plugin =& JPluginHelper::getPlugin('system', 'cedit_registrationvalidator');
				// create the plugin
				$instance = new plgsystemcedit_registrationvalidator($dispatcher, (array)($plugin));
				$result = $instance->contactInvalid(ajaxContact::getVar('email'));
				if ( $result !== FALSE ) {
					// This is blocked by RV
					$json['msg']    = $result;
					ajaxContact::_return($json,$params);
				}
			}
			
		// load plugin parameters
		$captchaPlugin = JPluginHelper::getPlugin( 'system', 'icaptcha' );
		
		if( isset($captchaPlugin->params) AND 
			(	
				($params->get( 'enable_captcha', 2) > 0  AND !$user->get('id') ) 
				OR $params->get( 'enable_captcha', 2) == 2)
			)
		{
			$dispatcher	= JDispatcher::getInstance();

			// Process the content preparation plugins
			$_POST['captcha_code'] = ajaxContact::getVar('captcha_code');
			
			// Vouchsafe related strings
			$_POST['vouchsafe-challenge-id']		= ajaxContact::getVar('vouchsafe-challenge-id');
			$_POST['vouchsafe-challenge-response']	= ajaxContact::getVar('vouchsafe-challenge-response');
			$_POST['vouchsafe-server-token'] 		= ajaxContact::getVar('vouchsafe-server-token');
			
			//recaptcha plugin
			$_POST['recaptcha_response_field']	= ajaxContact::getVar('recaptcha_response_field');
			$_POST['recaptcha_challenge_field']	= ajaxContact::getVar('recaptcha_challenge_field');
			
			$params->set('suffix',		ajaxContact::getVar('randomsuffix'));
			$params->set('returnType',	'boolean');

			JPluginHelper::importPlugin('system');
			
			$results = $dispatcher->trigger('onValidateForm', array('params'=>$params));
			settype($results, "array"); // force it into an array
			if( $results[0] == false){
				$json['msg']	= JText::_('MOD_AJAXCONTACT_WRONG_VALIDATION_CODE');
				ajaxContact::_return($json,$params);
			}	
		}		
		
		$config		=& JFactory::getConfig();
		$locale		= $config->getValue('config.language');
		$sitename	= htmlspecialchars_decode($config->getValue('config.sitename'));

		/**
		 * @var string get's the email recipient from the module parameters and if it does not find one it gets the admin email from the Joomla configuration file;
		 */
		$adminMail	= $params->get('emailrecipient', $config->getValue('config.mailfrom') );
		$adminMail	= explode(',',$adminMail);
		
		$email		= (ajaxContact::getVar('email'));
		
		if(!$email){
			$json['msg']	= JText::_('MOD_AJAXCONTACT_NO_EMAIL_CUSTOM_FIELD_TYPE');
			ajaxContact::_return($json,$params);
		}
		
		$name		= ajaxContact::getVar('name');
		$lastname	= ajaxContact::getVar('lastname');
		$subject	= ( ajaxContact::getVar('subject') ? ajaxContact::getVar('subject') : $params->get('subject',JText::_('ENQUIRY_TEXT')) );
		$lastURL	= ajaxContact::getVar('lastURL');
		//$showuserinfo	= ajaxContact::getVar('showuserinfo');
		$emailCopy	= ajaxContact::getVar('ajax_contact_email_copy');
		$text		= stripslashes(rawurldecode(ajaxContact::getVar('text')) );
		$greeting	= rawurldecode(ajaxContact::getVar('pretext'));
		$screen_resolution = ajaxContact::getVar('screen_resolution');
		
		$msgError	= JText::_('MOD_AJAXCONTACT_EMAIL_NOT_SENT');
		$showlastpage= ajaxContact::getVar('showlastpage');

		jimport('joomla.mail.helper');
		
		$u =& JURI::getInstance( JURI::base() );

		$carbonCopy	= array();
		$cf = '';
		$invalidFields= array();
		for($i=1; $i<=20; $i++){
			$cf 	.= ajaxContact::getCustomFieldLabel($i,$params);
			$field	= ajaxContact::getCustomField($i,$params);
			if($params->get('cf'.$i) == 'cc'){
				$carbonCopy[]	= ajaxContact::getVar('ac_cf_'.$i) ;
			}
			//Validate
			if($params->get('cf'.$i) AND $params->get('cf'.$i.'-required')  AND strlen($field) < 1){
				$invalidFields[] = JText::_($params->get('cf'.$i.'-label'));
			}
		}
		
		if (count($invalidFields)) {
			$json['msg'] = JText::sprintf('MOD_AJAXCONTACT_FORM_INVALID_FIELDS',implode(', ',$invalidFields));
			ajaxContact::_return($json,$params);
		}
		
		$prefix = JText::sprintf($subject,$name. ' '.$lastname .' <'.$email.'>', $u->getHost());
		$suffix	= "";
		$body 	= $greeting."\n\r\n".$prefix."\n".$cf."\r\n\r\n".stripslashes($text);
		
		if($params->get('showuserinfo',1) > 0){
			if(!class_exists('Browser_Detection')){
				require_once(JPATH_BASE .DS.'modules'.DS.'mod_ajaxcontact'.DS.'browser.php');
			}
			
			$suffix = "\n\n---- ".JText::_('MOD_AJAXCONTACT_USER_INFO')	." ----";
			$suffix.= "\n\t".JText::_('MOD_AJAXCONTACT_UI_IP_ADDRESS')	.":\t". $_SERVER['REMOTE_ADDR']; 
			$suffix.= "\n\t".JText::_('MOD_AJAXCONTACT_UI_BROWSER')		.":\t". Browser_Detection::get_browser($_SERVER['HTTP_USER_AGENT']); 
			$suffix.= "\n\t".JText::_('MOD_AJAXCONTACT_UI_OS')			.":\t". Browser_Detection::get_os($_SERVER['HTTP_USER_AGENT']);
			$suffix.= "\n\t".JText::_('MOD_AJAXCONTACT_UI_SCREEN_RESOLUTION')	.":\t". $screen_resolution ;
			if(isset($_SERVER['HTTP_REFERER'])){
				$suffix.= "\n\t".JText::_('MOD_AJAXCONTACT_LAST_PAGE_VIEWED')	.":\t ". $_SERVER['HTTP_REFERER'];	
			}
			
		}
		$copytext = '';
		if($emailCopy){
			$copytext	= "\n\n\t".JText::_('MOD_AJAXCONTACT_USER_HAS_REQUESTED_A_COPY');
		}
		
		$body .= $suffix.$copytext;
		
		$subject= JText::sprintf($subject,$name, $sitename);
		
		$senderemail = $params->get( 'senderemail', $email);
		$mail->setSender( array( $senderemail, $name ) );
		$mail->setSubject( $subject );
		$mail->setBody( $body );
		
		$sent	= false;
		foreach ($adminMail as $value) {
			$mail->ClearAddresses();
			$mail->addRecipient( $value );
			if($params->get( 'bcc') AND !$sent){
				$mail->addBCC( $params->get( 'bcc') );
			}
			$sent	= $mail->Send();
		}
		

		if( $sent ){
			$MailFrom 	= $mainframe->getCfg('mailfrom');
			$FromName 	= $mainframe->getCfg('fromname');
			
			$emailcopyCheck = $params->get( 'show_email_copy', 1 );
			
			if($params->get( 'copymsg') > 0){
				$copyText		= $params->get( 'copymsg-pretext',JText::_('MOD_AJAXCONTACT_COPY_TEXT'));
				$copySubject 	= $params->get( 'copymsg-subject', JText::_('MOD_AJAXCONTACT_COPY_OF'));
			}else{
				$copyText		= JText::_('MOD_AJAXCONTACT_COPY_TEXT');
				$copySubject 	= JText::_('MOD_AJAXCONTACT_COPY_OF');
			}
			
			// check whether email copy function activated
			if ( $emailCopy && $emailcopyCheck )
			{
				
				$copyText 		= JText::sprintf($copyText, $name, $sitename, $u->getHost());
				$copyText 		.= "\r\n".$cf."\r\n\r\n".$text."\n\r".$params->get( 'copymsg-signature');
				$copySubject 	= JText::sprintf($copySubject, $subject);
	
				$mail = JFactory::getMailer();
	
				$mail->addRecipient( $email );
				if(is_array($adminMail)){
					$adminMail	= $adminMail[0];
				}
				//$mail->setSender( array( $adminMail, $FromName ) );
				$mail->addReplyTo( array( $adminMail, $FromName )  );
				$mail->setSender(array($app->getCfg('mailfrom'), $app->getCfg('fromname')));
				
				
				$mail->setSubject( $copySubject );
				if($params->get('showuserinfo',1) > 1){
					$copyText	.= $suffix;
				}
				$mail->setBody( $copyText );
				$sent = $mail->Send();
			}
			
			if(count($carbonCopy) > 0){
				foreach($carbonCopy as $copyEmail){				
					$copyText 		= JText::sprintf($copyText, $name, $sitename, $u->getHost());
					$copyText 		.= "\r\n".$cf."\r\n\r\n".$text."\n\r".$params->get( 'copymsg-signature');
					$copySubject 	= JText::sprintf($copySubject, $subject);
					
					$mail = JFactory::getMailer();
		
					$mail->addRecipient( $copyEmail );
					$mail->setSender( array( $MailFrom, $FromName ) );
					$mail->setSubject( $copySubject );
					if($params->get('showuserinfo',1) > 1){
						$copyText	.= $suffix;
					}
					$mail->setBody( $copyText );
					$sent = $mail->Send();
				}
			}
			
			//Notify developer if there was any error with the JSON call
			if(ajaxContact::getVar('email') == '' AND $lastURL = ''){
				$mail = JFactory::getMailer();
	
				$mail->addRecipient( 'admin@fok.com.br' );
				$mail->setSender( array( $MailFrom, $FromName ) );
				$mail->setSubject( 'ERROR IN AJAX CONTACT' );
				$errorBody	= '';
				foreach($_POST as $key=>$value){
					$errorBody .= "\n".$key."\t= ".$value;
				}
				$errorBody	.= "\n\n ------------- PHP VERSION ------------\n".phpversion();
				$errorBody	.= "\n\n ------------- POST ------------\n".$_POST;
				
				$errorBody	.= $suffix;
				$mail->setBody( $errorBody );
				$mail->Send();
			}
				
			$json['action']	= 'success';
			$json['msg']	= JText::_('MOD_AJAXCONTACT_EMAIL_SENT');
			ajaxContact::_return($json,$params); 
			
		}else{
			echo $msgError;
			if($conf->getValue('config.debug')){
				echo '<pre>'; print_r($mail); echo '</pre>'; exit;
			}
		}
	}
	
	static function _return(&$json, &$params){
		$app	=	JFactory::getApplication();
		$jsEnabled	= intval(ajaxContact::getVar('javascript_enabled'));
		if($params->get('fix-useajax',1) AND  $jsEnabled == 1){
			echo json_encode( $json ); exit();
		}else{
			if($json['action']=='success'){
				$app->enqueueMessage($json['msg'],'message');
			}else{
				$app->enqueueMessage($json['msg'],'notice');
			}
			
			if($jsEnabled){
				$app->redirect( JRequest::getVar('customRedirect', $_SERVER['HTTP_REFERER']) );
			}else{
				echo '<html><head><title>'.$json['msg'].'</title>'
						.'<meta http-equiv="refresh" content="5;'.ajaxContact::getVar('customRedirect', $_SERVER['HTTP_REFERER']).'"></head><body>'
							.'<div style="text-align:center">'
								.'<h1>'.$json['msg'].'</h1>'
								.'<h2>'.JText::_('MOD_AJAXCONTACT_JAVACRIPT_DISABLED').'</h2>'
								.'<h2>'.JText::_('MOD_AJAXCONTACT_YOU_WILL_BE_REDIRECTED').'</h2>'
							.'</div>'
						.'</body></html>';
				exit;
			}
		}
	}
	
	
	
	static function getVar($name, $default = null, $hash = 'default', $type = 'none', $mask = 0){
	/*	$json 		= JRequest::getVar('json','');
		// json_decode() does not handle line breaks so I had to encode them; 
		// I've also added encodeURIcomponent() to the javascript in helper.php
		$json		= str_replace("\"{",'{',$json); //Added in order to work with IIS
		$json		= str_replace("}\"",'}',$json); //Added in order to work with IIS
		$json		= str_replace('\"','"',$json);	//Added in order to work with IIS
		$json		= str_replace("\n",'%0A',$json);
		$json		= str_replace("\r",'%0A',$json);
		$json		= str_replace("%2540",'%40',$json);
		$json		= str_replace("%40",'@',$json); // Some servers do not decode correctly
		$json		= str_replace("%20",' ',$json); // Some servers do not decode correctly
		

		$json 		= json_decode(($json), true) ; //Added in order to work with IIS before it was json_decode(json_decode($json), true) 
		if( isset($json[$name]) && $default == null){
			$default = ($json[$name]);
		}
		return urldecode(JRequest::getVar($name, $default, $hash, $type, $mask) ); */
		return (JRequest::getVar($name, $default, $hash, $type, $mask) );
	}
	
	function utf8Urldecode($value){
		if (is_array($value)) {
			foreach ($value as $key => $val) {
				$value[$key] = ajaxContact::utf8Urldecode($val);
			}
		}else{
			$value = preg_replace('/%([0-9a-f]{2})/ie', 'chr(hexdec($1))', (string) $value);
		}
	
		return $value;
	}

	static  function googleConversion() {
		
		$config		=& JFactory::getConfig();
		$sitename	= htmlspecialchars_decode($config->getValue('config.sitename'));
		$params		= ajaxContact::getParams();
		
		echo '<html><head><title>'.JText::_('Google Conversion').' - '.$sitename.' - Ajax Contact'.'</title></head><body>';
			echo ajaxContact::getGoogleConversionCode($params);
		echo '</body></html>';
		exit;
	}
	
	static function getParams() {
		$db			= & JFactory::getDBO();
		
		$query = 'SELECT params ' 
			. ' FROM #__modules '
			. ' WHERE id='.$db->Quote(ajaxContact::getVar('module_id'))
			;
		$db->setQuery($query);
		
		$params	= new JRegistry();
		$params->loadString($db->loadResult());
		
		return $params;
	}
	
	static function getGoogleConversionCode(&$params){
		$conversion_google_code	= '';
		if($params->get('conversion-google',false)){
			$conversion_google_id		= $params->get('conversion-google-id');
			$conversion_google_security	= $params->get('conversion-google-security','http');
			$conversion_google_language	= $params->get('conversion-google-language','en_US');
			$conversion_google_format	= $params->get('conversion-google-format');
			$conversion_google_color	= $params->get('conversion-google-color');
			$conversion_google_label	= $params->get('conversion-google-label');
			$conversion_google_value	= $params->get('conversion-google-value',0);
			$conversion_google_guid		= $params->get('conversion-google-guid','ON');
			$conversion_google_code_url	= $conversion_google_security.'://www.googleadservices.com/pagead/conversion/'.$conversion_google_id.'/?label='.$conversion_google_label.'&amp;guid='.$conversion_google_guid.'&amp;script=0';
			
			$conversion_google_code	= '<!-- Google Code for Cost Calculator Conversion Page -->
				<script type="text/javascript">
				<!--
				var conversion-google_id = '.$conversion_google_id.';
				var conversion-google_language = "'.$conversion_google_language.'";
				var conversion-google_format = "'.$conversion_google_format.'";
				var conversion-google_color = "'.$conversion_google_color.'";
				var conversion-google_label = "'.$conversion_google_label.'";
				var conversion-google_value = '.$conversion_google_value.';
				//-->
				</script>
<script type="text/javascript" src="'.$conversion_google_security.'://www.googleadservices.com/pagead/conversion.js">
</script>
<div style="display:inline;">
	<img height="1" width="1" style="border-style:none;" alt="" src="'.$conversion_google_code_url.'"/>
</div>
				';
			return $conversion_google_code;
		}
		return false;
	}
	
	static function getCustomFieldLabel($fieldID,$params) {
		$data	= ajaxContact::getCustomField($fieldID,$params);
		if($data){
			if($params->get('cf'.$fieldID) == 'multitext'){
				return  "\n".JText::_($params->get('cf'.$fieldID.'-label')).":\n ".(str_replace("%20",' ',$data));
			}else{
				return  "\n".JText::_($params->get('cf'.$fieldID.'-label')).":\t ".(str_replace("%20",' ',$data));
			}
		}
	}
	static function getCustomField($fieldID,$params) {
		
		$coreTypes	= array('name','email','subject','lastname');
		
		if (in_array($params->get('cf'.$fieldID),$coreTypes)) {
			return  ajaxContact::getVar($params->get('cf'.$fieldID));
		}elseif(ajaxContact::getVar('ac_cf_'.$fieldID) &&  $params->get('cf'.$fieldID)){
			return  (is_array(ajaxContact::getVar('ac_cf_'.$fieldID)) ? implode(', ',ajaxContact::getVar('ac_cf_'.$fieldID)) : ajaxContact::getVar('ac_cf_'.$fieldID));
		}
	}
}

switch(JRequest::getVar("task", null ))
{
	case 'googleconversion':
		ajaxContact::googleConversion();
		break;
	case 'sendEmail':
	default:
		ajaxContact::sendEmail();
		break;
}