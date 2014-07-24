<?php
/*
This file is part of Easy FAQs.

Easy FAQs is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Easy FAQs is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with The Easy FAQs.  If not, see <http://www.gnu.org/licenses/>.
*/

class easyFAQOptions
{
	function __construct(){
		//may be running in non WP mode (for example from a notification)
		if(function_exists('add_action')){
			//add a menu item
			add_action('admin_menu', array($this, 'add_admin_menu_item'));		
		}
	}
	
	function add_admin_menu_item(){
		$title = "Easy FAQ Settings";
		$page_title = "Easy FAQs Settings";
		
		//create new top-level menu
		add_menu_page($page_title, $title, 'administrator', __FILE__, array($this, 'settings_page'));

		//call register settings function
		add_action( 'admin_init', array($this, 'register_settings'));	
	}


	function register_settings(){
		//register our settings
		register_setting( 'easy-faqs-settings-group', 'faqs_link' );
		register_setting( 'easy-faqs-settings-group', 'faqs_read_more_text' );
		register_setting( 'easy-faqs-settings-group', 'faqs_image' );
		register_setting( 'easy-faqs-settings-group', 'faqs_style' );
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_custom_css' );	
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_use_captcha' );	
		
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_registered_name' );
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_registered_url' );
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_registered_key' );
	}

	function settings_page(){
		$title = "Easy FAQs Settings";
		$message = "Easy FAQs Settings Updated.";
	?>
	<div class="wrap">
		<h2><?php echo $title; ?></h2>
		
		<?php if(!isValidFAQKey()): ?>			
			<!-- Begin MailChimp Signup Form -->
			<style type="text/css">
				/* MailChimp Form Embed Code - Slim - 08/17/2011 */
				#mc_embed_signup form {display:block; position:relative; text-align:left; padding:10px 0 10px 3%}
				#mc_embed_signup h2 {font-weight:bold; padding:0; margin:15px 0; font-size:1.4em;}
				#mc_embed_signup input {border:1px solid #999; -webkit-appearance:none;}
				#mc_embed_signup input[type=checkbox]{-webkit-appearance:checkbox;}
				#mc_embed_signup input[type=radio]{-webkit-appearance:radio;}
				#mc_embed_signup input:focus {border-color:#333;}
				#mc_embed_signup .button {clear:both; background-color: #aaa; border: 0 none; border-radius:4px; color: #FFFFFF; cursor: pointer; display: inline-block; font-size:15px; font-weight: bold; height: 32px; line-height: 32px; margin: 0 5px 10px 0; padding:0; text-align: center; text-decoration: none; vertical-align: top; white-space: nowrap; width: auto;}
				#mc_embed_signup .button:hover {background-color:#777;}
				#mc_embed_signup .small-meta {font-size: 11px;}
				#mc_embed_signup .nowrap {white-space:nowrap;}     
				#mc_embed_signup .clear {clear:none; display:inline;}

				#mc_embed_signup h3 { color: #008000; display:block; font-size:19px; padding-bottom:10px; font-weight:bold; margin: 0 0 10px;}
				#mc_embed_signup .explain {
					color: #808080;
					width: 600px;
				}
				#mc_embed_signup label {
					color: #000000;
					display: block;
					font-size: 15px;
					font-weight: bold;
					padding-bottom: 10px;
				}
				#mc_embed_signup input.email {display:block; padding:8px 0; margin:0 4% 10px 0; text-indent:5px; width:58%; min-width:130px;}

				#mc_embed_signup div#mce-responses {float:left; top:-1.4em; padding:0em .5em 0em .5em; overflow:hidden; width:90%;margin: 0 5%; clear: both;}
				#mc_embed_signup div.response {margin:1em 0; padding:1em .5em .5em 0; font-weight:bold; float:left; top:-1.5em; z-index:1; width:80%;}
				#mc_embed_signup #mce-error-response {display:none;}
				#mc_embed_signup #mce-success-response {color:#529214; display:none;}
				#mc_embed_signup label.error {display:block; float:none; width:auto; margin-left:1.05em; text-align:left; padding:.5em 0;}		
				#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }
					#mc_embed_signup{    
							background-color: white;
							border: 1px solid #DCDCDC;
							clear: left;
							color: #008000;
							font: 14px Helvetica,Arial,sans-serif;
							margin-top: 10px;
							margin-bottom: 0px;
							max-width: 800px;
							padding: 5px 12px 0px;
				}
				#mc_embed_signup form{padding: 10px}

				#mc_embed_signup .special-offer {
					color: #808080;
					margin: 0;
					padding: 0 0 3px;
					text-transform: uppercase;
				}
				#mc_embed_signup .button {
				  background: #5dd934;
				  background-image: -webkit-linear-gradient(top, #5dd934, #549e18);
				  background-image: -moz-linear-gradient(top, #5dd934, #549e18);
				  background-image: -ms-linear-gradient(top, #5dd934, #549e18);
				  background-image: -o-linear-gradient(top, #5dd934, #549e18);
				  background-image: linear-gradient(to bottom, #5dd934, #549e18);
				  -webkit-border-radius: 5;
				  -moz-border-radius: 5;
				  border-radius: 5px;
				  font-family: Arial;
				  color: #ffffff;
				  font-size: 20px;
				  padding: 10px 20px 10px 20px;
				  line-height: 1.5;
				  height: auto;
				  margin-top: 7px;
				  text-decoration: none;
				}

				#mc_embed_signup .button:hover {
				  background: #65e831;
				  background-image: -webkit-linear-gradient(top, #65e831, #5dd934);
				  background-image: -moz-linear-gradient(top, #65e831, #5dd934);
				  background-image: -ms-linear-gradient(top, #65e831, #5dd934);
				  background-image: -o-linear-gradient(top, #65e831, #5dd934);
				  background-image: linear-gradient(to bottom, #65e831, #5dd934);
				  text-decoration: none;
				}
				#signup_wrapper {
					max-width: 800px;
					margin-bottom: 20px;
				}
				#signup_wrapper .u_to_p
				{
					font-size: 10px;
					margin: 0;
					padding: 2px 0 0 3px;				
				]
			</style>
			<div id="signup_wrapper">
				<div id="mc_embed_signup">
					<form action="http://illuminatikarate.us2.list-manage2.com/subscribe/post?u=403e206455845b3b4bd0c08dc&amp;id=4ec6d49e6b" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
						<p class="special-offer">Special Offer:</p>
						<h3>Sign-up for our mailing list now, and we'll give you a discount on Easy FAQs Pro!</h3>
						<label for="mce-EMAIL">Your Email:</label>
						<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
						<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
						<div style="position: absolute; left: -5000px;"><input type="text" name="b_403e206455845b3b4bd0c08dc_6ad78db648" tabindex="-1" value=""></div>
						<div class="clear"><input type="submit" value="Subscribe Now" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
						<p class="explain"><strong>What To Expect:</strong> You'll receive you around one email from us each month, jam-packed with special offers and tips for getting the most out of WordPress. Of course, you can unsubscribe at any time.</p>
					</form>
				</div>
				<p class="u_to_p"><a href="http://goldplugins.com/our-plugins/easy-faqs-details/">Upgrade to Easy FAQs Pro now</a> to remove banners like this one.</p>
			</div>
			<!--End mc_embed_signup-->
		<?php endif; ?>
		
		<?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') : ?>
		<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
		<?php endif; ?>	
		
		<form method="post" action="options.php">
			<?php settings_fields( 'easy-faqs-settings-group' ); ?>			
			
			<h3>Basic Options</h3>
			
			<p>Use the below options to control various bits of output.</p>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="faqs_style">FAQs Style</a></th>
					<td>
						<select name="faqs_style" id="faqs_style">	
							<option value="default_style" <?php if(get_option('faqs_style') == "default_style"): echo 'selected="SELECTED"'; endif; ?>>Default Style</option>
							<option value="no_style" <?php if(get_option('faqs_style') == "no_style"): echo 'selected="SELECTED"'; endif; ?>>No Style</option>
						</select>
						<p class="description">Select which style you want to use.  If 'No Style' is selected, only your Theme's CSS, and any Custom CSS you've added, will be used.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_faqs_custom_css">Custom CSS</a></th>
					<td><textarea name="easy_faqs_custom_css" id="easy_faqs_custom_css" style="width: 250px; height: 250px;"><?php echo get_option('easy_faqs_custom_css'); ?></textarea>
					<p class="description">Input any Custom CSS you want to use here.  The plugin will work without you placing anything here - this is useful in case you need to edit any styles for it to work with your theme, though.</p></td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="faqs_link">FAQs Read More Link</label></th>
					<td><input type="text" name="faqs_link" id="faqs_link" value="<?php echo get_option('faqs_link'); ?>"  style="width: 250px" />
					<p class="description">This is the URL of the 'Read More' Link.  If not set, no Read More Link is output.  If set, Read More Link will be output next to faq that will go to this page.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="faqs_read_more_text">FAQs Read More Text</label></th>
					<td><input type="text" name="faqs_read_more_text" id="faqs_read_more_text" value="<?php echo get_option('faqs_read_more_text', 'Read More'); ?>"  style="width: 250px" />
					<p class="description">This is the Text of the 'Read More' Link.  Default text is "Read More."  This is only displayed if a URL is set in the above field, FAQs Read More Link.</p>
					</td>
				</tr>
			</table>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="faqs_image">Show FAQ Image</label></th>
					<td><input type="checkbox" name="faqs_image" id="faqs_image" value="1" <?php if(get_option('faqs_image')){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, the Image will be shown next to the FAQ.</p>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_faqs_use_captcha">Enable Captcha on Submission Form</label></th>
					<td><input type="checkbox" name="easy_faqs_use_captcha" id="easy_faqs_use_captcha" <?php if(!isValidFAQKey()): ?>disabled="disabled"<?php endif; ?> value="1" <?php if(get_option('easy_faqs_use_captcha')){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, and a compatible plugin is installed (such as <a href="https://wordpress.org/plugins/really-simple-captcha/" target="_blank">Really Simple Captcha</a>) then we will output a Captcha on the Submission Form.  This is useful if you are having SPAM problems.</p>
					<?php if(!class_exists('ReallySimpleCaptcha')): ?><p class="alert"><strong>ALERT: Really Simple Captcha is NOT active.  Captcha feature will not function.</strong></p><?php endif; ?>
					</td>
				</tr>
			</table>
						
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="easy_faq_submit_notification_address">Submission Success Notification E-Mail Address</label></th>
					<td><input type="text" name="easy_faq_submit_notification_address" id="easy_faq_submit_notification_address" <?php if(!isValidFAQKey()): ?>disabled="disabled"<?php endif; ?> value="<?php echo get_option('easy_faq_submit_notification_address'); ?>"  style="width: 250px" />
					<p class="description">If set, we will attempt to send an e-mail notification to this address upon a succesfull submission.  If not set, submission notifications will be sent to the site's Admin E-mail address.</p>
					</td>
				</tr>
			</table>
			
			<?php include('registration_options.php'); ?>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div>
	<?php } // end settings_page function
	
} // end class
?>