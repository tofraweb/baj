<?php
/**
 * @package    AjaxContact
 * @author     Douglas Machado {@link http://idealextensions.com}
 * @author     Created on 22-Mar-2011
 * @license    GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

		$config = &JFactory::getConfig();
		jimport('joomla.environment.browser');
		$browser	= new JBrowser();
		
		// load plugin parameters
		$captchaPlugin = JPluginHelper::getPlugin( 'system', 'icaptcha' );
		if(!isset($captchaPlugin->params)){
			$captchaPlugin = new stdClass();
			$captchaPlugin->params	= '';
		}
		$captchaPluginParams	= new JRegistry();
		$captchaPluginParams->loadString($captchaPlugin->params);
		
        $formName	=	'ajax_contact'.$suffix;
		$user = & JFactory::getUser();
		$moduleURI	= $params->get('fix-site_domain', JURI::root()). 'modules/mod_ajaxcontact/';
		$useFX		= $params->get('clear_form_onsubmit-useFx',0);
		$clearForm	= $params->get('clear_form_onsubmit',10000);
		$log		= 'log'.$suffix;
		$customRedirect = trim( $params->get( 'custom_redirect','') );
		if($customRedirect){
			if( substr( $customRedirect, 0, 9) == 'index.php' ){
				$customRedirect = JRoute::_($customRedirect);
			}elseif( substr( $customRedirect, 0, 4) != 'http' ){
			 	$customRedirect = '';
			}
		}
		$lang 		=& JFactory::getLanguage();
		
		$updateCaptchaAfterload ='';
		if( $params->get( 'enable_captcha', 2) > 0 AND ($captchaPluginParams->get('captcha_systems') == 'secureimage' OR $captchaPluginParams->get('captcha_systems') == 'securImage2')){
			if(!defined('AFM_CAPTCHA_PLUGIN_CALLED')){define('AFM_CAPTCHA_PLUGIN_CALLED',1);}else{$updateCaptchaAfterload	= 'updateCaptcha();';}
		}
		
		
		
		?>
		
		<div class="ac_email<?php echo $moduleclass_sfx; ?> ideal-ajax-module" id="ajaxcontact">
			<?php if($params->get('formpretext')): ?>
				<div id="ac-pretext"><?php echo $params->get('formpretext'); ?></div>
			<?php endif;?>
			<form method="get" 
				name="<?php echo $formName; ?>" 
				action="<?php echo JURI::root(); ?>modules/mod_ajaxcontact/ajax.php" 
				id="<?php echo $formName; ?>" 
				class="form-validate" >
			<?php 
			$script	= "";
			for($i=1; $i<=20; $i++){

				//echo '<pre>'; print_r($params); echo '</pre>'; exit; 
				$type	= $params->get('cf'.$i,0);
				
				if($type){
					$value	= $params->get('cf'.$i.'-value');
					$label	= $params->get('cf'.$i.'-label');
					$name	= $i;
					$fieldType	= "fieldType_".$type;
					$cfparam= array('required'=>	$params->get('cf'.$i.'-required',0), 
									'type'=>		$type,
									'suffix'=>		$suffix,
									'autopopulate'=>$params->get('autopopulate'),
									'labelType'=>	$params->get('labelType','label')
									);
					$cf	= new $fieldType($value,$label,$name,$cfparam);
					echo $cf->getFieldHTML();
					$script .= $cf->getValidationScript();
				}
			}
			/**
			 * replaced $moduleURI for modules/mod_ajaxcontact/ to make sure it can be used with multiple domains
			 * submitUrl:	 			'".$moduleURI."ajax.php?module_id=".$module->id."',
			 * @since 1.5.8.2
			 */
			$script = "
			var ajaxContact_".$suffix." = new ajaxModule({
			suffix:	 				'".$suffix."',
			submitUrl:	 			'".$moduleURI."ajax.php?module_id=".$module->id."',
			frm:					'".$formName."',
			useFX:		 			".$useFX.",
			useAjax:	 			".$params->get( 'fix-useajax',1).",
			hideCharCount:			".(($params->get('textfield',1) >= 1 AND $params->get('textfield-maxlen',0) > 1 ) ? '0' : '1').",
			redirectURL:			'".$customRedirect."',
			clearform:				".$params->get('clear_form_onsubmit',99999999999999).",
			log:	 				'".$log."',
			textField:				'text".$suffix."',
			msgDisplayMethod:		'".$params->get('fix-message-display-method','inline')."',
			logFX:					0,
			googleConversionEnabled:".$params->get('google_conversion',0).",
			lang: {
			invalid:			'".JText::_('MOD_AJAXCONTACT_INVALID_FIELDS')."',
			reachMaxCharacters:	'".JText::_('MOD_AJAXCONTACT_LIMIT_OF_CHARACTERS_REACHED')."'
			}
			});
			{$script}
			".$updateCaptchaAfterload."
			document.".$formName.".screen_resolution.value=screen.width +'x'+ screen.height;
			document.".$formName.".javascript_enabled.value='1';
			
			";
			$script	= "
			/* <![CDATA[ */
			window.addEvent('domready', function(){
			{$script}
			".(($params->get('textfield',1) AND $params->get('labelType') == 'field') ? "\n\t new OverText(document.id('text".$suffix."')); \n" : ''  )."
			});
			/* ]]> */";
			
			$doc =& JFactory::getDocument(); 
			if(!defined('AFM_CALLED')){
			
				modAjaxContactHelper::loadJavascript();
			
				if($config->getValue('config.debug')){
					$doc->addScript( 	JURI::root(). 'modules/mod_ajaxcontact/js/ajaxformmodule-uncompressed.js' );
					//$doc->addScript( 	JURI::root(). 'modules/mod_ajaxcontact/js/validate-uncompressed.js' );
				}else{
					$doc->addScript( 	JURI::root(). 'modules/mod_ajaxcontact/js/ajaxformmodule.js' );
					//$doc->addScript( 	JURI::root(). 'modules/mod_ajaxcontact/js/validate.js' );
				}
				define('AFM_CALLED',1);
			}
			$doc->addScriptDeclaration($script);
			$doc->addStyleSheet( JURI::root(). 'modules/mod_ajaxcontact/css/ajaxcontact.css' );
			//JHTML::_('behavior.formvalidation');
			
			?>
				<?php if($params->get('textfield',1) > 0): ?>
					<div class="ac-textarea-container">	
					<?php if($params->get('labelType','label') == 'label'): ?>
						<label for="text<?php echo $suffix; ?>" class="ac-field-label requiredField">
							<?php echo $params->get('textfield-label',JText::_( 'MOD_AJAXCONTACT_MESSAGE_FIELD_LABEL' ));?>
						</label>
						
					<?php endif; ?>
						<textarea 
							cols="20" 
							rows="4" 
							name="text" 
							id="text<?php  echo $suffix; ?>"
							class="inputbox required"
							title="<?php echo JText::_( 'MOD_AJAXCONTACT_MESSAGE_FIELD_LABEL' ); ?>"
						></textarea>
					
							<?php
							// Limit Characters Box
							if($params->get('textfield',1) >= 1 AND $params->get('textfield-maxlen',0) > 1 ){ 
								if($browser->getBrowser() != 'msie' OR ($params->get('fix-hide_char_count_from_ie',0) == 0 AND $browser->getBrowser() == 'msie' )){
								?>
									<div id="limit-character-container"><span class="ac_limit_chars" id="limit_chars<?php  echo $suffix; ?>"><?php echo $params->get('textfield-maxlen') ?></span>
										<?php echo JText::_('MOD_AJAXCONTACT_CHARACTERS_LEFT'); ?>
									</div>
						<?php	}
							}	?>
						</div>
				<?php else: ?>
					<input type="hidden" name="text" value="<?php echo JText::_('MOD_AJAXCONTACT_CUSTOM_MESSAGE'); ?>" />
				<?php endif; ?>
										
					<?php  
									
						if( ($params->get( 'enable_captcha', 2) > 0  AND !$user->get('id') ) 
							OR $params->get( 'enable_captcha', 2) == 2)
						{
							$dispatcher	=& JDispatcher::getInstance();
							
							$captchaPluginParams->set('suffix',		$suffix);
							$captchaPluginParams->set('returnType',	'boolean');
							$results = $dispatcher->trigger('onAfterDisplayForm', array('params'=>$captchaPluginParams,'returnType'=>'html'));
							if(isset($results[0])){
								echo $results[0];
							} 
							echo '<br />';
						}
						
						
					?>
					
					<?php if ($params->get( 'show_email_copy',1 ) == 1) : ?>
						<div id="ac-email-copy-container">
							<input type="checkbox" name="ajax_contact_email_copy" id="ajax_contact_email_copy" value="1" class="ac-checkbox"  />
							<label for="ajax_contact_email_copy">
								<?php echo JText::_( 'MOD_AJAXCONTACT_EMAIL_A_COPY' ); ?>
							</label>
						</div>
					<?php elseif($params->get( 'show_email_copy',1 ) == 2): 
						// Always send copy message
					?>
						<input type="hidden" name="ajax_contact_email_copy" id="ajax_contact_email_copy" value="1"  />
					<?php endif; ?>
					
					<div class="message" id="log<?php echo $suffix; ?>" 
						<?php echo ($params->get('fix-message-display-method','inline') == 'alert' ? ' style="display:none" ': ''); ?>></div>
					
					<div class="af-button-container readon">
						<input type="submit" 
							name="submit" 
							id="ac-submit" 	
							value="<?php echo JText::_('MOD_AJAXCONTACT_SEND_EMAIL'); ?>" class="button validate" />
					</div>
					<input type="hidden" 
							name="maxlen<?php echo $suffix; ?>" 	
							id="maxlen<?php echo $suffix; ?>"	
							value="<?php echo $params->get('textfield-maxlen');?>" />

					
					<input type="hidden" name="pretext"			value="<?php echo $params->get('pretext');?>" />
					<input type="hidden" name="randomsuffix"	value="<?php echo $suffix;?>" />
					<input type="hidden" name="task"			value="sendEmail" />
					<input type="hidden" name="screen_resolution"	value="" />
					<input type="hidden" name="javascript_enabled"	value="" />
					<input type="hidden" name="module_id" 		value="<?php echo $module->id; ?>" />
					<?php if($params->get('custom_redirect') AND $params->get('fix-useajax', 1) == 0 ): ?>
						<input type="hidden" name="customRedirect"	value="<?php echo $customRedirect; ?>" />
					<?php endif; ?>
					<input type="hidden" name="lang"	value="<?php echo $lang->getTag(); ?>" />
					<input type="hidden" name="lastURL" value="<?php echo JURI::current(); ?>" />
					
					<?php echo JHTML::_( 'form.token' ); ?>
			</form>
			
		</div>
	<?php 