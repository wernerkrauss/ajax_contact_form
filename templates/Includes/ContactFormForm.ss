		<div id="revealContactForm" class="reveal-modal medium" style="display: none;">
			<h2>
				<% _t("AjaxContactForm.CONTACTFORM","Contact form") %> 
				<% if ContactFormPage %>
					<a href="#" data-reveal-id="revealContacts" class="secondary button tiny right">
						<% _t("AjaxContactForm.SHOWCONTACTS","Show all contacts") %> &#187;
					</a>
				<% end_if %>
			</h2>
			$ContactForm
		</div>
		<% if ContactFormPage %>
			<div id="revealContacts" class="reveal-modal small" data-animation="none" style="display: none;">
				<h2>
					<% _t("AjaxContactForm.CONTACTS","Contacts") %> 
					<a href="#" data-reveal-id="revealContactForm" class="button tiny right">
						<% _t("AjaxContactForm.SHOWFORM","Show contact form") %> &#187;
					</a>
				</h2>
				$ContactFormPage.Content
			</div>
		<% end_if %>