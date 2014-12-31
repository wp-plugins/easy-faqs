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
	var $textdomain = '';
	var $shed = false;

	function __construct(){
		//may be running in non WP mode (for example from a notification)
		if(function_exists('add_action')){
			//add a menu item
			add_action('admin_menu', array($this, 'add_admin_menu_item'));		
		}
		
		// create the BikeShed object now, so that BikeShed can add its hooks
		$this->shed = new Easy_FAQs_GoldPlugins_BikeShed();
	}
	
	function add_admin_menu_item(){
		$title = "Easy FAQ Settings";
		$page_title = "Easy FAQs Settings";
		$top_level_slug = "easy-faqs-settings";
		
		//create new top-level menu
		add_menu_page($page_title, $title, 'administrator', $top_level_slug , array($this, 'basic_settings_page'));
		add_submenu_page($top_level_slug , 'Basic Options', 'Basic Options', 'administrator', $top_level_slug, array($this, 'basic_settings_page'));
		add_submenu_page($top_level_slug , 'Shortcode Generator', 'Shortcode Generator', 'administrator', 'easy-faqs-shortcode-generator', array($this, 'shortcode_generator_page'));
		add_submenu_page($top_level_slug , 'Help & Instructions', 'Help & Instructions', 'administrator', 'easy-faqs-help', array($this, 'help_settings_page'));

		//call register settings function
		add_action( 'admin_init', array($this, 'register_settings'));	
	}
	
	//function to produce tabs on admin screen
	function admin_tabs( $current = 'homepage' ) {
	
		$tabs = array( 'easy-faqs-settings' => __('Basic Options', $this->textdomain),
					   'easy-faqs-shortcode-generator' => __('Shortcode Generator', $this->textdomain),
					   'easy-faqs-help' => __('Help & Instructions', $this->textdomain)
					 );
		echo '<div id="icon-themes" class="icon32"><br></div>';
		echo '<h2 class="nav-tab-wrapper">';
			foreach( $tabs as $tab => $name ){
				$class = ( $tab == $current ) ? ' nav-tab-active' : '';
				echo "<a class='nav-tab$class' href='?page=$tab'>$name</a>";
			}
		echo '</h2>';
	}

	function register_settings(){
		//register our settings
		register_setting( 'easy-faqs-settings-group', 'faqs_link' );
		register_setting( 'easy-faqs-settings-group', 'faqs_read_more_text' );
		register_setting( 'easy-faqs-settings-group', 'faqs_image' );
		register_setting( 'easy-faqs-settings-group', 'faqs_style' );
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_custom_css' );	
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_use_captcha' );	
		
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_question_font_size' );	
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_question_font_color' );	
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_question_font_style' );	
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_question_font_family' );	
		
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_answer_font_size' );	
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_answer_font_color' );	
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_answer_font_style' );	
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_answer_font_family' );	
		
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_read_more_link_font_size' );	
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_read_more_link_font_color' );	
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_read_more_link_font_style' );	
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_read_more_link_font_family' );	
		
		
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_registered_name' );
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_registered_url' );
		register_setting( 'easy-faqs-settings-group', 'easy_faqs_registered_key' );
	}

	function settings_page_top(){
		$title = "Easy FAQs Settings";
		$message = "Easy FAQs Settings Updated.";
		
		global $pagenow;
	?>
	<div class="wrap easy_faqs_wrapper gold_plugins_settings">
		<h2><?php echo $title; ?></h2>
		<?php if(!isValidFAQKey()): ?>			
			<!-- Begin MailChimp Signup Form -->
			<style type="text/css">
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
						<p class="explain"><strong>What To Expect:</strong> <br/> As soon as you've confirmed your subscription, you'll receive a coupon code for a big discount on Easy FAQs Pro. After that, you'll receive about one email from us each month, jam-packed with special offers and tips for getting the most out of WordPress. Of course, you can unsubscribe at any time.</p>
					</form>
				</div>
				<p class="u_to_p"><a href="http://goldplugins.com/our-plugins/easy-faqs-details/">Upgrade to Easy FAQs Pro now</a> to remove banners like this one.</p>
			</div>
			<!--End mc_embed_signup-->
		<?php endif; ?>
		
		<?php if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') : ?>
		<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
		<?php endif;
		
		$this->get_and_output_current_tab($pagenow);	
	}
	
	function get_and_output_current_tab($pagenow){
		$tab = $_GET['page'];
		
		$this->admin_tabs($tab); 
				
		return $tab;
	}
	
	function basic_settings_page(){	
		$this->settings_page_top(); ?>	
		
		<form method="post" action="options.php">		
			<?php settings_fields( 'easy-faqs-settings-group' ); ?>			
			
			<h3>Basic Options</h3>
			
			<p>Use the below options to control various bits of output.</p>
			
			<table class="form-table">
				<?php
					// FAQs Theme (select)
					$themes = array(
						'default_style' => 'Default Theme',
						'no_style' => 'No Theme',
					);
					$desc = 'Select which style you want to use.  If \'No Style\' is selected, only your Theme\'s CSS, and any Custom CSS you\'ve added, will be used.';
					$this->shed->select( array('name' => 'faqs_style', 'options' => $themes, 'label' =>'FAQs Style', 'value' => get_option('faqs_style'), 'description' => $desc) );

					// Question Font (typography)
					$values = array(
						'font_size' => get_option('easy_faqs_question_font_size'),
						'font_family' => get_option('easy_faqs_question_font_family'),
						'font_style' => get_option('easy_faqs_question_font_style'),
						'font_color' => get_option('easy_faqs_question_font_color'),
					);
					$this->shed->typography( array('name' => 'easy_faqs_question_*', 'label' =>'Question Font', 'description' => 'Choose a font size, family, style, and color.', 'google_fonts' => true, 'default_color' => '#878787', 'values' => $values) );

					// Answer Font (typography)
					$values = array(
						'font_size' => get_option('easy_faqs_answer_font_size'),
						'font_family' => get_option('easy_faqs_answer_font_family'),
						'font_style' => get_option('easy_faqs_answer_font_style'),
						'font_color' => get_option('easy_faqs_answer_font_color'),
					);
					$this->shed->typography( array('name' => 'easy_faqs_answer_*', 'label' =>'Answer Font', 'description' => 'Choose a font size, family, style, and color.', 'google_fonts' => true, 'default_color' => '#878787', 'values' => $values) );

					// View All Link Font (typography)
					$values = array(
						'font_size' => get_option('easy_faqs_read_more_link_font_size'),
						'font_family' => get_option('easy_faqs_read_more_link_font_family'),
						'font_style' => get_option('easy_faqs_read_more_link_font_style'),
						'font_color' => get_option('easy_faqs_read_more_link_font_color'),
					);
					$this->shed->typography( array('name' => 'easy_faqs_read_more_link_*', 'label' =>'View All Link Font', 'description' => 'Choose a font size, family, style, and color.', 'google_fonts' => true, 'default_color' => '#878787', 'values' => $values) );
					
				
					// Custom CSS (textarea)
					$this->shed->textarea( array('name' => 'easy_faqs_custom_css', 'label' =>'Custom CSS', 'value' => get_option('easy_faqs_custom_css'), 'description' => 'Input any Custom CSS you want to use here.  The plugin will work without you placing anything here - this is useful in case you need to edit any styles for it to work with your theme, though.') );
					
					// FAQS - View All Link (text)
					$this->shed->text( array('name' => 'faqs_link', 'label' =>'FAQs View All Link', 'value' => get_option('faqs_link'), 'description' => 'This is the URL of the \'View All\' Link.  If not set, no View All Link is output.  If set, View All Link will be output next to FAQ that will go to this page.') );

					// FAQS - View All Text (text)
					$this->shed->text( array('name' => 'faqs_read_more_text', 'label' =>'FAQs View All Text', 'value' => get_option('faqs_read_more_text'), 'description' => 'This is the Text of the \'View All\' Link.  Default text is "View All."  This is only displayed if a URL is set in the above field, FAQs View All Link.') );
					
					// Hide Images in Feed (checkbox)
					$checked = (get_option('faqs_image') == '1');
					$this->shed->checkbox( array('name' => 'faqs_image', 'label' =>'Show FAQ Images', 'value' => 1, 'checked' => $checked, 'description' => 'If checked, the Featured Image for each FAQ will be displayed.', 'inline_label' => 'Show FAQ Images') );
				?>
			</table>

			<h3>Submission Form Settings</h3>
			<?php if(!isValidFAQKey()):?>
			<p class="easy_faq_not_registered"><strong>These settings require Easy FAQs Pro.</strong>&nbsp;&nbsp;&nbsp;<a class="button" target="blank" href="http://goldplugins.com/our-plugins/easy-faqs-details/">Upgrade Now</a></p>
			<?php endif;?>
			
			<table class="form-table">
			<?php
					// Submission Form CAPTCHA (checkbox)
					$desc = 'If checked, and a compatible plugin is installed (such as <a href="https://wordpress.org/plugins/really-simple-captcha/" target="_blank">Really Simple Captcha</a>) then we will output a Captcha on the Submission Form.  This is useful if you are having SPAM problems.';
					$disabled =  !isValidFAQKey();
					if(!class_exists('ReallySimpleCaptcha')) {
						$desc .= '</p><p class="alert"><strong>ALERT: Really Simple Captcha is NOT active.  Captcha feature will not function.</strong>';
					}
					$checked = (get_option('easy_faqs_use_captcha') == '1');
					$this->shed->checkbox( array('name' => 'easy_faqs_use_captcha', 'label' =>'Enable Captcha on Submission Form', 'value' => 1, 'checked' => $checked, 'description' => $desc, 'inline_label' => 'Show a CAPTCHA on form submissions to prevent spam', 'disabled' => $disabled) );
			
					// Email Address for Submissions (text)
					$this->shed->text( array('name' => 'easy_faq_submit_notification_address', 'label' =>'Submission Success Notification E-Mail Address', 'value' => get_option('easy_faq_submit_notification_address'), 'description' => 'If set, we will attempt to send an e-mail notification to this address upon a succesfull submission.  If not set, submission notifications will be sent to the website administrator\'s e-mail address.', 'disabled' => $disabled) );
			?>

			</table>	
			<?php include('registration_options.php'); ?>				
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
		</div>						
		<?php
	}
	
	function help_settings_page(){
		$this->settings_page_top();
		include('pages/help.html');					
		?></div><?php			
	}	
		
	function shortcode_generator_page() {
		$this->settings_page_top();
		$categories = get_terms( 'easy-faq-category', 'orderby=title&hide_empty=0' );
		?>
		<div id="gold_plugins_shortcode_generator">
			<h3>Shortcode Generator</h3>			
			<p>Select the options you'd like, and then click the "Build My Shortcode!" button. You'll get a shortcode that you can copy and paste into any post or page.</p>
			
			<form id="easy_faqs_shortcode_generator">
				<table class="form-table">
					<tbody>										
						<tr>
							<th scope="row">
								<div class="sc_gen_control_group">
									<label for="sc_gen_count">Count</label>
								</div>
							</th>
							<td>
								<input type="text" class="valid_int" id="sc_gen_count" value="10" />
								<p class="description">How many FAQs would you like to show? If you have more than this number, we'll show a Read More link.</p>
								<p class="description tip"><strong>Tip:</strong> Leave this blank to show all FAQs (unlimited)</p>
							</td>
						</tr>
												
						<tr>
							<th scope="row">
								<div class="sc_gen_control_group">
									<label for="sc_gen_read_more_url">View All URL</label>
								</div>
							</th>
							<td>
								<input type="text" id="sc_gen_read_more_url" value="" />
								<p class="description">The URL of your FAQs page. If you have more FAQs than are currently displayed, we'll show this link.</p>
								<p class="description"><strong>Tip:</strong> leave this blank to hide the View All link entirely.</p>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<div class="sc_gen_control_group">
									<label for="sc_gen_read_more_text">View All Link Text</label>
								</div>
							</th>
							<td>
								<input type="text" id="sc_gen_read_more_text" value="View All FAQs" />
								<p class="description">The anchor text for the 'View All' link. If you have more FAQs than are currently displayed, we'll show this link.</p>
							</td>
						</tr>

						<tr>
							<th scope="row">
								<div class="sc_gen_control_group">
									<label for="sc_gen_order_by">Order By</label>
								</div>
							</th>
							<td>
								<div class="inline-select-wrapper">
									<select id="sc_gen_order_by">
										<option value="rand">Random</option>
										<option value="id">ID</option>
										<option value="author">Author</option>
										<option value="title" selected="selected">Title</option>
										<option value="name">Name</option>
										<option value="date">Date</option>
										<option value="modified">Last Modified</option>
										<option value="parent">Parent ID</option>								
									</select>
								</div>
								<div class="inline-select-wrapper">
									<select id="sc_gen_order_dir">
										<option value="asc">Ascending (ASC)</option>
										<option value="desc">Descending (DESC)</option>
									</select>
								</div>
								<p class="description">How should we order your FAQs?</p>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								<div class="sc_gen_control_group">
									<label for="sc_gen_category">Filter By Category</label>
								</div>
							</th>
							<td>
								<select id="sc_gen_category">
									<option value="all">All Categories</option>
									<?php foreach($categories as $cat):?>
									<option value="<?=$cat->slug?>"><?=htmlentities($cat->name)?></option>
									<?php endforeach; ?>
								</select>
								<p class="description"><a href="<?php echo admin_url('edit-tags.php?taxonomy=easy-faq-category&post_type=faq'); ?>">Manage Categories</a></p>
							</td>
						</tr>
						
						<tr>
							<th scope="row">
								Featured Images
							</th>
							<td>							
								<div class="sc_gen_control_group">
									<label for="sc_gen_show_thumbs">
										<input type="checkbox" class="checkbox" id="sc_gen_show_thumbs" value="yes" />
										Show Featured Images with each FAQ?
									</label>
								</div>
							</td>
						</tr>
						
						<?php if (!isValidFAQKey()):?>
						<tr>
							<td colspan="2">
								<div class="upgrade_notice">
									<p class="easy_faq_not_registered"><strong>These settings require Easy FAQs Pro.</strong>&nbsp;&nbsp;&nbsp;<a class="button" target="blank" href="http://goldplugins.com/our-plugins/easy-faqs-details/?utm_source=shortcode_generator&utm_campaign=pro_upgrade">Upgrade Now</a></p>
								</div>
							</td>
						</tr>
						<?php endif; ?>
						
						<?php if (!isValidFAQKey()):?>
						<tr class="disabled">
						<?php else: ?>
						<tr>
						<?php endif; ?>
							<th scope="row">
								Quick Links
							</th>
							<td>
								<div class="sc_gen_control_group">
									<label for="sc_gen_quick_links">
										<input type="checkbox" class="checkbox" id="sc_gen_quick_links" value="yes" />
										Include a Quick Links section?
									</label>
								</div>
							</td>
						</tr>
						
						<?php if (!isValidFAQKey()):?>
						<tr class="disabled">
						<?php else: ?>
						<tr>
						<?php endif; ?>
							<th scope="row">
								<label for="sc_gen_quick_links_cols">Quick Links Columns</label>
							</th>
							<td>
								<input type="text" class="valid_int" id="sc_gen_quick_links_cols" value="2" />
								<p class="description">How many columns should your Quick Links section have?</p>
							</td>
						</tr>
						
						
						<?php if (!isValidFAQKey()):?>
						<tr class="disabled">
						<?php else: ?>
						<tr>
						<?php endif; ?>
							<th scope="row">
								Accordion Style
							</th>
							<td>
								<div class="sc_gen_control_group sc_gen_control_group_radio">
									<label title="Hide Ratings">
										<input type="radio" value="normal" id="sc_gen_style_normal" name="sc_gen_style" checked="checked">
										<span>Normal Style</span>
									</label>
									<label title="Show Rating Before Testimonial">
										<input type="radio" value="accordion" id="sc_gen_style_accordion_first_open" name="sc_gen_style">
										<span>Accordion Style - First FAQ Visible</span>
									</label>
									<label title="Show Rating After Testimonial">
										<input type="radio" value="accordion-collapsed" id="sc_gen_style_accordion_closed" name="sc_gen_style">
										<span>Accordion Style - All FAQs Start Collapsed</span>
									</label>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				
				<?php if (!isValidFAQKey()):?>
				<input type="hidden" name="is_pro" id="is_pro" value="0" />
				<?php else: ?>
				<input type="hidden" name="is_pro" id="is_pro" value="1" />
				<?php endif; ?>
				
				
				<p class="submit">
					<button id="sc_generate" class="button button-primary" type="button">Build My Shortcode!</button>
				</p>
				
				<div id="sc_gen_output_wrapper">
					<label for="sc_gen_output">Here is your Shortcode!</label>
					<p class="description">Copy and paste this shortcode into any page or post to display your FAQs!</p>
					<textarea id="sc_gen_output" rows="4" cols="80"></textarea>
				</div>
				
			</form>
		</div><!-- end #gold_plugins_shortcode_generator -->
		</div><!--end settings_page-->
		<?php 
	}	
	
	
} // end class
?>