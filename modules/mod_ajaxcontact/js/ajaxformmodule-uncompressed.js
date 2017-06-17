/**
 * Compatible with Form Validator built-in Joomla
 */
if(typeof(jQuery) != "undefined" && $===jQuery){
	jQuery.noConflict();
}
	var ajaxModule = new Class({
	
		 setOptions: function(options){
			this.options = {
				suffix:	 		'',
				submitUrl:	 	'',
				useFX:		 	0,
				useAjax:	 	1,
				hideCharCount:	0,
				logFX:			0,
				redirectURL:	'',
				clearform:		9999999999,
				log:	 		'log',
				frm:	 		'',
				textField:		'text',
				msgDisplayMethod: 		'inline',
				googleConversionEnabled:	0,
				lang: {
					invalid:			'There are some invalid fields, please check fields in red',
					reachMaxCharacters:	'You have reached the maximum limit of characters allowed'
					}
		};
		Object.extend(this.options, options || {});
  },
 
		
  	initialize: function(options){
		this.setOptions(options);
	  	if(this.options.useFX){
	  		this.options.logFX	= new Fx.Slide(this.options.log); 
	  		this.options.logFX.toggle();
	  		
	  	}
	  	if(this.options.useAjax){
	  		this.attachEventToForm(options);
	  	}
	  	if(this.options.hideCharCount != 1){
	  		this.attachEventToTextfield(this.options);
	  	}
  		
	},
	
	setLogRes:function(txt, cls,slideIn){
		if(this.options.msgDisplayMethod == 'alert'){
			if(txt.length>1) alert(txt);
		}else{
			var obj	= $(this.options.log);
			obj.removeClass('error');
			obj.removeClass('success');
			obj.removeClass('ajax-loading');
			obj.addClass(cls);
			obj.set('html',txt);
			if(slideIn == true && this.options.useFX){
				this.options.logFX.slideIn();
			}
		}
	},
	attachEventToForm:function(options){
		var obj	= this;
		
		
		$(this.options.frm).addEvent('submit', function(e) {
			new Event(e).stop();
			
			var frm	= document.id(obj.options.frm);
			var formValidator	= new Form.Validator.Inline(frm); 
			//console.log(formValidator);
			if(formValidator.validate()){
				obj.setLogRes('','ajax-loading',true);
			}else{
				obj.setLogRes(obj.options.lang.invalid, 'error', true);
				(function(){obj.setLogRes('','ac-none', true);}).delay(7000);
				return false;
			}
			
			//build the request 
			// We are not really sending JSON. Could not get it to work with MooTools 1.2 for some reason, so we are just getting JSON back
			var jSonRequest = new Request.JSON({url:options.submitUrl+'&'+$(obj.options.frm).toQueryString(),onComplete: function(response){
				obj.setLogRes(response.msg,response.action,true);
					//did it return as good, or bad?
					if(response.action == 'success'){
						if(obj.options.googleConversionEnabled == 1){
							obj.googleConversion(options.submitUrl);
						}
						// Google Analytics Support 
						if(typeof(pageTracker) != 'undefined'){
							pageTracker._trackPageview('/ajaxcontactform_submitted');
						}
						// infinity page tracking Support
						if(typeof(__NAS) != 'undefined'){
							__NAS.page('/ajaxcontactform_submitted');
						}
						if(obj.options.redirectURL != ''){
							window.location=obj.options.redirectURL;
						}else{
							(function(){ if(!window.ie){$(obj.options.frm).reset();}}).delay(obj.options.clearform);
							if(obj.options.useFX){
								obj.options.logFX.hide();
								obj.options.logFX.slideIn();
								(function(){obj.options.logFX.slideOut();}).delay(obj.options.clearform);
						  	}
						}
					}
					//reload captcha
					if(typeof(Recaptcha) != "undefined"){
						Recaptcha.reload();
					}else if(typeof(updateCaptcha) != "undefined"){
						updateCaptcha();
					}
				},
				data: obj.toJsonString($(obj.options.frm))
			}).send();					
		});
	}, 
	
	attachEventToTextfield:	function(options){
		var maxlen = parseInt($('maxlen'+options.suffix).value);
		var obj 	= this;
		
		$(this.options.textField).addEvent('keyup', function(e) {
			new Event(e).stop();
			var fieldLength = this.value.length;
			
			if(fieldLength >= maxlen && maxlen > 0){
				obj.setLogRes(options.lang.reachMaxCharacters,'error',true);
				this.value = this.value.substring(0, maxlen);
			}else if(fieldLength <= (maxlen -1)){
				if(options.useFX){
					options.logFX.slideOut();
			  	}
			}
			$('limit_chars'+options.suffix).set('text', (maxlen - fieldLength) );
		});
	},
	
	toJsonString:	function(frm){
		var jsonString = [];
		frm.getElements('input, textarea, select').each(function(el){
			var name = el.name;
			var value = el.get('value');
			if (value === false || !name || el.disabled) return;
			var qs = function(val){
				//if is an array
				if(name.indexOf('[') > 0 && name.indexOf(']') > 0){
					var fields = document.getElementsByName(name);
					var fieldValue	= '';
					name= name.replace('[','');
					name= name.replace(']','');
					for(var i=0; i<fields.length; i++){
						if(fields[i].checked == true){
							if(fieldValue.length < 1){
								fieldValue = fields[i].value;
							}else{
								fieldValue = fieldValue+', '+fields[i].value;
							}
						}
					}
					value	= fieldValue;
				}
				//value = encodeURIComponent(value);
				jsonString.push(JSON.encode(name) + ':' + JSON.encode( encodeURIComponent(value) ));
			};
			if ($type(value) == 'array') value.each(qs);
			else qs(value);
		});
		return ('{' + (jsonString.join(',')) + '}');
	},
	googleConversion: function(url) {
		var iframe = document.createElement('iframe');
		iframe.style.width = '0px';
		iframe.style.height = '0px';
		document.body.appendChild(iframe);
		iframe.src = url+'&task=googleconversion';
	}
});
ajaxModule.implement(new Options);

	