<h3>Pro Registration</h3>			
<?php if(isValidFAQKey()): ?>	
<p class="easy_faq_registered">Your plugin is succesfully registered and activated!</p>
<?php else: ?>
<p>Fill out the fields below, if you have purchased the pro version of the plugin, to activate additional features such as Front-End FAQ Submission.</p>
<p class="easy_faq_not_registered">Your plugin is not succesfully registered and activated. <a href="http://goldplugins.com/our-plugins/easy-faqs-details/" target="_blank">Click here</a> to upgrade today!</p>
<?php endif; ?>	
<style type="text/css">
.easy_faq_registered {
    background-color: #90EE90;
    font-weight: bold;
    padding: 20px;
    width: 860px;
}
.easy_faq_not_registered {
	background-color: #FF8C00;
    font-weight: bold;
    padding: 20px;
    width: 860px;
}
</style>	
<?php if(!isValidMSFAQKey()): ?>
<table class="form-table">
	<tr valign="top">
		<th scope="row"><label for="easy_faqs_registered_name">Email Address</label></th>
		<td><input type="text" name="easy_faqs_registered_name" id="easy_faqs_registered_name" value="<?php echo get_option('easy_faqs_registered_name'); ?>"  style="width: 250px" />
		<p class="description">This is the e-mail address that you used when you registered the plugin.</p>
		</td>
	</tr>
</table>
	
<table class="form-table">
	<tr valign="top">
		<th scope="row"><label for="easy_faqs_registered_url">Website Address</label></th>
		<td><input type="text" name="easy_faqs_registered_url" id="easy_faqs_registered_url" value="<?php echo get_option('easy_faqs_registered_url'); ?>"  style="width: 250px" />
		<p class="description">This is the Website Address that you used when you registered the plugin.</p>
		</td>
	</tr>
</table>
	
<table class="form-table">
	<tr valign="top">
		<th scope="row"><label for="easy_faqs_registered_key">API Key</label></th>
		<td><input type="text" name="easy_faqs_registered_key" id="easy_faqs_registered_key" value="<?php echo get_option('easy_faqs_registered_key'); ?>"  style="width: 250px" />
		<p class="description">This is the API Key that you received after registering the plugin.</p>
		</td>
	</tr>
</table>
<?php endif; ?>