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
		register_setting( 'easy-faqs-settings-group', 'faqs_image' );
		register_setting( 'easy-faqs-settings-group', 'faqs_style' );
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_custom_css' );
		
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
				#mc_embed_signup .button {
					clear:both;
					background-color: #FFA500;
					border: 0 none;
					border-radius:4px;
					box-shadow: 0 1px 0 rgba(255, 255, 255, 0.38) inset;
					color: #FFFFFF;
					cursor: pointer;
					display: inline-block;
					font-size:15px;
					font-weight: bold;
					height: auto;
					margin: 0 5px 10px 0;
					padding: 10px 10px 10px;
    				text-align: center;
					text-decoration: none;
					vertical-align: middle;
					white-space: nowrap;
					width: auto;
				}
				#mc_embed_signup .button:hover {background-color:green;}
				#mc_embed_signup .small-meta {font-size: 11px;}
				#mc_embed_signup .nowrap {white-space:nowrap;}     
				#mc_embed_signup .clear {clear:none; display:inline;}

				#mc_embed_signup label {
					display:block; 
					font-family: georgia;
					font-size: 30px;
					font-weight:bold;
					margin-bottom: 0px;					
					padding-bottom:0px; 				
				}
				#mc_embed_signup input.email {display:block; padding:8px 0; margin:0 4% 10px 0; text-indent:5px; width:95%; min-width:130px;}
				#mc_embed_signup input.button {display:block; width:35%; margin:15px 0 30px; min-width:90px;}

				#mc_embed_signup div#mce-responses {float:left; top:-1.4em; padding:0em .5em 0em .5em; overflow:hidden; width:90%;margin: 0 5%; clear: both;}
				#mc_embed_signup div.response {margin:1em 0; padding:1em .5em .5em 0; font-weight:bold; float:left; top:-1.5em; z-index:1; width:80%;}
				#mc_embed_signup #mce-error-response {display:none;}
				#mc_embed_signup #mce-success-response {color:#529214; display:none;}
				#mc_embed_signup label.error {display:block; float:none; width:auto; margin-left:1.05em; text-align:left; padding:.5em 0;}
				#mc_embed_signup {
					background: none repeat scroll 0 0 #FDF5E6;
					border: 1px solid #008000;
					clear: left;
					color: #008000;
					font: 14px Helvetica,Arial,sans-serif;
					margin: 20px 0 30px;
					max-width: 650px;
					padding: 20px 30px;
				}
				#mc_embed_signup form{padding: 10px}
				#mc_embed_signup input.button{    
					background-color: #689C23;
					border: 1px solid #4D7C0A;
					color: #FFFFFF;
				}
				#mc_embed_signup .new_subs {
					font-style:italic;
					margin-bottom: 0;
				}
				/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
				   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
			</style>
			<div id="mc_embed_signup">
			<form action="http://illuminatikarate.us2.list-manage2.com/subscribe/post?u=403e206455845b3b4bd0c08dc&amp;id=4ec6d49e6b" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
				<label for="mce-EMAIL">Subscribe To Our Mailing List Now</label>
				<p>We'll send you occasional newsletters with WordPress tips, tricks, and special offers. We do not spam.</p>
				<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Your Email" required>
				<div class="clear"><input type="submit" value="Subscribe Now &raquo;" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
				<p class="new_subs">PS: New subscribers will receive a special discount code good for any version of <a href="http://goldplugins.com/our-plugins/easy-faqs-details/">Easy FAQs Pro</a>!</p>
								
			</form>
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
					<th scope="row"><label for="faqs_image">Show FAQ Image</label></th>
					<td><input type="checkbox" name="faqs_image" id="faqs_image" value="1" <?php if(get_option('faqs_image')){ ?> checked="CHECKED" <?php } ?>/>
					<p class="description">If checked, the Image will be shown next to the FAQ.</p>
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