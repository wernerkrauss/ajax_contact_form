<?php

class MyContactFormExtension extends DataExtension {
	
		static $emailTo = false;
		
		public static function setEmailTo($newEmail) {
			self::$emailTo = $newEmail;
		}
		
		public static function getEmailTo() {
			return (self::$emailTo) ? self::$emailTo : Email::getAdminEmail();
		}
	
		public function ContactForm() {
			
		  return AjaxContactForm::create("ContactForm",$this->owner->ContactFormData("To"),$this->owner->ContactFormData("Subject"))
		            ->addFields(
		                TextField::create("Name",_t("AjaxContactForm.NAME","Your name"))
							->setAttribute('placeholder', _t("AjaxContactForm.NAME","Your name"))
							->addExtraClass("no-label")
		                	->setAttribute('minlength', 3)
		                	->setAttribute('required', true),
		                EmailField::create("Email", _t("AjaxContactForm.EMAIL","Your email"))
							->setAttribute('placeholder', _t("AjaxContactForm.EMAIL","Your email"))
							->addExtraClass("no-label")
		                	->setAttribute('required', true),
		                TextareaField::create("Question",_t("AjaxContactForm.QUESTION","Your message"))
							->setAttribute('placeholder', _t("AjaxContactForm.QUESTION","Your message"))
							->addExtraClass("no-label")
		                	->setAttribute('minlength', 20)
		                	->setAttribute('required', true)
		            )
					//->updateValidation("Name", array ('email' => true))
		            // You can add fields as strings, too.
		           // ->addField("Otázka//Textarea")
		            ->setSuccessMessage($this->owner->ContactFormData("SuccessMessage"))
		            ->setOnBeforeSend(function($data, $form) {
		                  // Do stuff here. Return false to refuse the form.
		            })
		            //->setEmailTemplate("MyCustomTemplate")
		            //->addOmittedField("SomeField")
		            ->setIntroText($this->owner->ContactFormData("IntroText"))
		            ->addSpamProtector(
		                SimpleQuestionSpamProtector::create()
		                  ->addQuestion(_t("ContactForm.ANTISPAM_Q1","Five plus 4 is"),_t("ContactForm.ANTISPAM_A1","9"))
		                  ->addQuestion(_t("ContactForm.ANTISPAM_Q2","Eight minus 2 is"),_t("ContactForm.ANTISPAM_A2","6"))
		            )
		            ->render();
	}

	public function ContactFormPage() {
		return ContactFormPage::get()->limit(1)->First();
	}
	
	public function ContactFormData($data) {
		$cfp = $this->owner->ContactFormPage();
		$defaults = array(
			"To" => self::getEmailTo(),
			"Subject" => _t("AjaxContactForm.CONTACTFORMSUBJECT","New contact form"),
			"IntroText" => _t("ContactForm.INTROTEXT","Someone submitted a form. Here's the data."),
			"SuccessMessage" => _t("AjaxContactForm.SUCCESSMESSAGE","Contact form submitted succesfully!")
		);
		return ($cfp && isset($cfp->$data) && !empty($cfp->$data)) ? $cfp->$data : $defaults[$data];
	}

	
	
}