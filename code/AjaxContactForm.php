<?php

class AjaxContactForm extends ContactForm {
	
	/**
	 * Gets the JavaScript to use with jQuery validation
	 *
	 * @return string
	 */
	protected function getValidationJS() {
		$js = "
		(function(\$) {
		\$(function() {
			\$('#{$this->form->FormName()}').validate({
				rules: {";
						foreach($this->validation as $name => $params) {
							$js .= "\n\t\t\t\t\t".$name.": {\n";
							foreach($params as $index => $key) {
								if($index == "message") continue;
								if(is_bool($key)) {
									$key = $key ? "true" : "false";
								}
								elseif(is_string($key)) {
									$key = "\"{$key}\"";
								}
								$js .= "\t\t\t\t\t\t{$index}: {$key},\n";
							}
							$js .= "\t\t\t\t\t},\n";
						}

						$js .= "
				},
				messages: {";
						foreach($this->validation as $name => $params) {
							if(!isset($params['message'])) {
								$params['message'] = sprintf(_t('ContactForm.FIELDISREQUIRED','"%s" is required'),$name);
							}
							$js .= "\n\t\t\t\t\t".$name.": \"" . addslashes($params['message'])."\",\n";				
						}
						$js .= "
				},
				 submitHandler: function(form, event) {
				   //form.submit();
				   var sbutton = $(form).find('.Actions input');
				   var currentValue = sbutton.prop('value');
				   sbutton.addClass('secondary').prop('value', 'Odosielam...');
				   	event.preventDefault();
					$.post($(form).attr('action'), $(form).serialize(), function(data){
						if (data == 'false') {
							$(form).prepend(('<div class=\"alert-box alert\">Vyplnili ste správne antispam otázku?<a href=\'\' class=\'close\'>&times;</a></div>'));
							sbutton.removeClass('secondary').prop('value', currentValue);
						} else {
							$(form).html(('<div class=\"alert-box success\">' + data + '</div>'));
							setTimeout(function(){\$('.reveal-modal').trigger('reveal:close');},5000);
						}
					});
				 }
			});
		})
		})(jQuery);";

		return $js;		
	}




	/**
	 * Renders the {@link Form} object that is managed by the ContactForm.
	 * Includes dependencies and sets up the spam
	 *
	 * @return Form
	 */
	public function render() {
		if(ContactFormSpamProtector::ip_is_locked() && Director::isLive()) {
			return Controller::curr()->httpError(400);
		}
		foreach($this->spamProtection as $spam) {
			$spam->initialize($this);
		}	
		if(!empty($this->validation)) {
			if(!self::$jquery_included) {
				Requirements::javascript(THIRDPARTY_DIR."/jquery/jquery.js");
			}
			Requirements::javascript("contact_form/javascript/validation.js");			
			Requirements::customScript($this->getValidationJS());

		}
		if($data = Session::get("FormData.{$this->form->FormName()}")) {
			$this->form->loadDataFrom($data);
		}				
		
		if (Director::is_ajax()) {
			return $this->form;
		} else {
			return $this->form->renderWith(array("ContactForm"));
		}

	}
	
}
