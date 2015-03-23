<?php
/*
Plugin Name: Easy FAQs
Plugin URI: http://goldplugins.com/our-plugins/easy-faqs-details/
Description: Easy FAQs - Provides custom post type, shortcodes, widgets, and other functionality for Frequently Asked Questions (FAQs).
Author: Gold Plugins
Version: 1.9.1
Author URI: http://goldplugins.com

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
along with Easy FAQs .  If not, see <http://www.gnu.org/licenses/>.
*/

global $easy_faqs_footer_css_output;

include('include/lib/lib.php');
include('include/lib/str_highlight.php');
include('include/lib/database_setup.php');
include('include/lib/easy_faqs_search_faqs.class.php');
include('include/lib/BikeShed/bikeshed.php');

class easyFAQs
{
	var $category_sort_order = array();
	var $SearchFAQs = false;
	
	function __construct(){
		
		// load subsclasses
		$this->SearchFAQs = new EasyFAQs_SearchFAQs($this);
		
		//create shortcodes
		add_shortcode('single_faq', array($this, 'outputSingleFAQ'));
		add_shortcode('faqs', array($this, 'outputFAQs'));
		add_shortcode('faqs-by-category', array($this, 'outputFAQsByCategory'));
		add_shortcode('faqs_by_category', array($this, 'outputFAQsByCategory')); // i've heard it both ways
		add_shortcode('submit_faq', array($this, 'submitFAQForm'));
		
		// register search_faqs shortcode and recent searches dashboard widget for pro users only
		if (isValidFAQKey()) {
			add_shortcode('search_faqs', array($this->SearchFAQs, 'outputSearchForm'));
			add_action( 'wp_dashboard_setup', array($this->SearchFAQs, 'add_dashboard_widget') );		
		}

		//add JS
		add_action( 'wp_enqueue_scripts', array($this, 'easy_faqs_setup_js' ));

		//add CSS
		add_action( 'wp_enqueue_scripts', array($this, 'easy_faqs_setup_css' ));

		//add Custom CSS
		add_action( 'wp_head', array($this, 'easy_faqs_setup_custom_css'));

		//register sidebar widgets
		add_action( 'widgets_init', array($this, 'easy_faqs_register_widgets' ));

		//do stuff
		add_action( 'after_setup_theme', array($this, 'easy_faqs_setup_faqs' ));

		//add example shortcode to list of faqs
		add_filter('manage_faq_posts_columns', array($this, 'easy_faqs_column_head'), 10);  
		add_action('manage_faq_posts_custom_column', array($this, 'easy_faqs_columns_content'), 10, 2); 
		
		//add example shortcode to faq categories list
		add_filter('manage_edit-easy-faq-category_columns', array($this, 'easy_faqs_cat_column_head'), 10);  
		add_action('manage_easy-faq-category_custom_column', array($this, 'easy_faqs_cat_columns_content'), 10, 3); 
		
		// admin init
		add_action('admin_init', array($this, 'admin_init'));
		
		// add Google web fonts if needed
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_webfonts'));
		
		//add our custom links for Settings and Support to various places on the Plugins page
		$plugin = plugin_basename(__FILE__);
		add_filter( "plugin_action_links_{$plugin}", array($this, 'add_settings_link_to_plugin_action_links') );
		add_filter( 'plugin_row_meta', array($this, 'add_custom_links_to_plugin_description'), 10, 2 );	

		//flush rewrite rules - only do this once!
		//we do this to prevent 404s when viewing individual FAQs
		register_activation_hook( __FILE__, array($this, 'easy_faqs_rewrite_flush'));
	}

	//only do this once
	function easy_faqs_rewrite_flush() {		
		$this->easy_faqs_setup_faqs();
		
		flush_rewrite_rules();
	}
	
	function admin_init()
	{
		wp_register_style( 'easy_faqs_admin_stylesheet', plugins_url('include/css/admin_style.css', __FILE__) );
		wp_enqueue_style( 'easy_faqs_admin_stylesheet' );
		
		wp_enqueue_script(
			'gp-shortcode-generator',
			plugins_url('include/js/gp-shortcode-generator.js', __FILE__),
			array( 'jquery' ),
			false,
			true
		);		
		wp_enqueue_script(
			'gp-admin',
			plugins_url('include/js/gp-admin.js', __FILE__),
			array( 'jquery' ),
			false,
			true
		);
		
	}

	//add an inline link to the settings page, before the "deactivate" link
	function add_settings_link_to_plugin_action_links($links) { 
	  $settings_link = '<a href="admin.php?page=easy-faqs-settings">Settings</a>';
	  array_unshift($links, $settings_link); 
	  return $links; 
	}

	//add inlines link to pur plugin listing on the Plugins page, in the description area
	function add_custom_links_to_plugin_description($links, $file) { 
	
		/** Get the plugin file name for reference */
		$plugin_file = plugin_basename( __FILE__ );
	 
		/** Check if $plugin_file matches the passed $file name */
		if ( $file == $plugin_file )
		{		
			$new_links['settings_link'] = '<a href="admin.php?page=easy-faqs-settings">Settings</a>';
			$new_links['support_link'] = '<a href="http://goldplugins.com/contact/?utm-source=plugin_menu&utm_campaign=support&utm_banner=easy-faqs" target="_blank">Get Support</a>';
			
			if(!isValidFAQKey()){
				$new_links['upgrade_to_pro'] = '<a href="http://goldplugins.com/our-plugins/easy-faqs-details/upgrade-to-easy-faqs-pro/?utm_source=plugin_menu&utm_campaign=up
				grade" target="_blank">Upgrade to Pro</a>';
			}
			
			$links = array_merge( $links, $new_links);
		}
		return $links; 
	}

	//setup JS
	function easy_faqs_setup_js() {
		if(isValidFAQKey()){
			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script(
				'easy-faqs',
				plugins_url('include/js/easy-faqs-init.js', __FILE__),
				array( 'jquery' )
			);
		}
	}

	//add FAQ CSS to header
	function easy_faqs_setup_css() {
		wp_register_style( 'easy_faqs_style', plugins_url('include/css/style.css', __FILE__) );
		
		switch(get_option('faqs_style')){
			case 'no_style':
				break;
			case 'default_style':
			default:
				wp_enqueue_style( 'easy_faqs_style' );
				break;
		}
	}	

	function easy_faqs_send_notification_email(){
		//get e-mail address from post meta field
		$email_address = get_option('easy_faqs_submit_notification_address', get_bloginfo('admin_email'));
	 
		$subject = "New Easy FAQ Submission on " . get_bloginfo('name');
		$body = "You have received a new submission with Easy FAQs on your site, " . get_bloginfo('name') . ".  Login and see what they had to say!";
	 
		//use this to set the From address of the e-mail
		$headers = 'From: ' . get_bloginfo('name') . ' <'.get_bloginfo('admin_email').'>' . "\r\n";
	 
		if(wp_mail($email_address, $subject, $body, $headers)){
			//mail sent!
		} else {
			//failure!
		}
	}
		
	function easy_faqs_check_captcha() {
		$captcha = new ReallySimpleCaptcha();
		// This variable holds the CAPTCHA image prefix, which corresponds to the correct answer
		$captcha_prefix = $_POST['captcha_prefix'];
		// This variable holds the CAPTCHA response, entered by the user
		$captcha_code = $_POST['captcha_code'];
		// This variable will hold the result of the CAPTCHA validation. Set to 'false' until CAPTCHA validation passes
		$captcha_correct = false;
		// Validate the CAPTCHA response
		$captcha_check = $captcha->check( $captcha_prefix, $captcha_code );
		// Set to 'true' if validation passes, and 'false' if validation fails
		$captcha_correct = $captcha_check;
		// clean up the tmp directory
		$captcha->remove($captcha_prefix);
		$captcha->cleanup();
		
		return $captcha_correct;
	}	
		
	function easy_faqs_outputCaptcha(){
		// Instantiate the ReallySimpleCaptcha class, which will handle all of the heavy lifting
		$captcha = new ReallySimpleCaptcha();
		 
		// ReallySimpleCaptcha class option defaults.
		// Changing these values will hav no impact. For now, these are here merely for reference.
		// If you want to configure these options, see "Set Really Simple CAPTCHA Options", below
		$captcha_defaults = array(
			'chars' => 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789',
			'char_length' => '4',
			'img_size' => array( '72', '24' ),
			'fg' => array( '0', '0', '0' ),
			'bg' => array( '255', '255', '255' ),
			'font_size' => '16',
			'font_char_width' => '15',
			'img_type' => 'png',
			'base' => array( '6', '18'),
		);
		 
		/**************************************
		* All configurable options are below  *
		***************************************/
		 
		//Set Really Simple CAPTCHA Options
		$captcha->chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		$captcha->char_length = '4';
		$captcha->img_size = array( '100', '50' );
		$captcha->fg = array( '0', '0', '0' );
		$captcha->bg = array( '255', '255', '255' );
		$captcha->font_size = '16';
		$captcha->font_char_width = '15';
		$captcha->img_type = 'png';
		$captcha->base = array( '6', '18' );
		 
		/********************************************************************
		* Nothing else to edit.  No configurable options below this point.  *
		*********************************************************************/
		 
		// Generate random word and image prefix
		$captcha_word = $captcha->generate_random_word();
		$captcha_prefix = mt_rand();
		// Generate CAPTCHA image
		$captcha_image_name = $captcha->generate_image($captcha_prefix, $captcha_word);
		// Define values for CAPTCHA fields
		$captcha_image_url =  get_bloginfo('wpurl') . '/wp-content/plugins/really-simple-captcha/tmp/';
		$captcha_image_src = $captcha_image_url . $captcha_image_name;
		$captcha_image_width = $captcha->img_size[0];
		$captcha_image_height = $captcha->img_size[1];
		$captcha_field_size = $captcha->char_length;
		// Output the CAPTCHA fields
		?>
		<div class="easy_faqs_field_wrap">
			<img src="<?php echo $captcha_image_src; ?>"
			 alt="captcha"
			 width="<?php echo $captcha_image_width; ?>"
			 height="<?php echo $captcha_image_height; ?>" /><br/>
			<label for="captcha_code"><?php echo get_option('easy_faqs_captcha_field_label','Captcha'); ?></label><br/>
			<input id="captcha_code" name="captcha_code"
			 size="<?php echo $captcha_field_size; ?>" type="text" />
			<p class="easy_faqs_description"><?php echo get_option('easy_faqs_captcha_field_description','Enter the value in the image above into this field.'); ?></p>
			<input id="captcha_prefix" name="captcha_prefix" type="hidden"
			 value="<?php echo $captcha_prefix; ?>" />
		</div>
		<?php
	}

	//submit faq shortcode
	function submitFAQForm($atts){   
			ob_start();
			
			// process form submissions
			$inserted = false;
       
			if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] )) {
				//only process submissions from logged in users
				if(isValidFAQKey()){  
					$do_not_insert = false;
					
					if (isset ($_POST['the-title']) && strlen($_POST['the-title']) > 0) {
							$title =  "Question from: " . $_POST['the-title'];
					} else {
							echo '<p class="easy_faqs_error">Please enter your name.</p>';
							$do_not_insert = true;
					}	
				   
					if (isset ($_POST['the-body']) && strlen($_POST['the-body']) > 0) {
							$body = $_POST['the-body'];
					} else {
							echo '<p class="easy_faqs_error">Please enter a question.</p>';
							$do_not_insert = true;
					}		
				
					if(class_exists('ReallySimpleCaptcha') && get_option('easy_faqs_use_captcha',0)){ 
						$correct = $this->easy_faqs_check_captcha(); 
						if(!$correct){
							echo '<p class="easy_faqs_error">Captcha did not match.</p>';
							$do_not_insert = true;
						}
					}
				   
					if(!$do_not_insert){
						$post = array(
								'post_title'    => $title,
								'post_content'  => $body,
								'post_category' => array(1),  // custom taxonomies too, needs to be an array
								'post_status'   => 'pending',
								'post_type'     => 'faq'
						);
					   
						$new_id = wp_insert_post($post);
					   
						$inserted = true;
   
						// do the wp_insert_post action to insert it
						do_action('wp_insert_post', 'wp_insert_post');                 
					}
				} else {
					echo "You must have a valid key to perform this action.";
				}
			}       
		   
			$content = '';
		   
			if(isValidFAQKey()){     
			   
				if($inserted){
					echo '<p class="easy_faqs_submission_success_message">' . get_option('easy_faqs_submit_success_message','Thank You For Your Submission!') . '</p>';
					$this->easy_faqs_send_notification_email();
				} else { ?>
				<!-- New Post Form -->
				<div id="postbox">
					<form id="new_post" name="new_post" method="post">
						<div class="easy_faqs_field_wrap">
							<label for="the-title">Your Name</label><br />
							<input type="text" id="the-title" tabindex="1" name="the-title" />
							<p class="easy_faqs_description">Please let us know your name.</p>
						</div>
						<div class="easy_faqs_field_wrap">
							<label for="the-body">Question</label><br />
							<textarea id="the-body" tabindex="2" name="the-body" cols="50" rows="6"></textarea>
							<p class="easy_faqs_description">This is the question that you are asking.</p>
						</div>
		
						<?php if(class_exists('ReallySimpleCaptcha') && get_option('easy_faqs_use_captcha',0)){ $this->easy_faqs_outputCaptcha(); } ?>
						
						<div class="easy_faqs_field_wrap"><input type="submit" value="Submit Your Question" tabindex="3" id="submit" name="submit" /></div>
						<input type="hidden" name="action" value="post" />
						<?php wp_nonce_field( 'new-post' ); ?>
					</form>
				</div>
				<!--// New Post Form -->
				<?php }
			   
				$content = ob_get_contents();
				ob_end_clean(); 
			}
		   
			return $content;
	}
	
	//add Custom CSS
	function easy_faqs_setup_custom_css() {
		//use this to track if css has been output
		global $easy_faqs_footer_css_output;
		
		if($easy_faqs_footer_css_output){
			return;
		} else {
			echo '<style type="text/css" media="screen">' . get_option('easy_faqs_custom_css') . "</style>";
			$easy_faqs_footer_css_output = true;
		}
	}

	
	function word_trim($string, $count, $ellipsis = FALSE)	{
		$words = explode(' ', $string);
		if (count($words) > $count)
		{
			array_splice($words, $count);
			$string = implode(' ', $words);
			// trim of punctionation
			$string = rtrim($string, ',;.');	

			// add ellipsis if needed
			if (is_string($ellipsis)) {
				$string .= $ellipsis;
			} elseif ($ellipsis) {
				$string .= '&hellip;';
			}			
		}
		return $string;
	}

	// converts a DateTime string (e.g., a MySQL timestamp) into a friendly time string, e.g. "10 minutes ago"	
	// source: http://stackoverflow.com/a/18602474
	function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
		
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
		
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
		
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
	
	//setup custom post type for faqs
	function easy_faqs_setup_faqs(){
		//include custom post type code
		include('include/lib/ik-custom-post-type.php');
		//include options code
		include('include/easy_faq_options.php');	
		$easy_faqs_options = new easyFAQOptions($this);
				
		//setup post type for faqs
		$postType = array('name' => 'FAQ', 'plural' =>'faqs', 'slug' => 'faq' );
		$fields = array(); 
		$myCustomType = new ikFAQsCustomPostType($postType, $fields);
		register_taxonomy( 'easy-faq-category', 'faq', array( 'hierarchical' => true, 'label' => __('FAQ Category'), 'rewrite' => array('slug' => 'faq-category', 'with_front' => true) ) ); 
		
		//load list of current posts that have featured images	
		$supportedTypes = get_theme_support( 'post-thumbnails' );
		
		//none set, add them just to our type
		if( $supportedTypes === false ){
			add_theme_support( 'post-thumbnails', array( 'faq' ) );       
			//for the faq thumb images    
		}
		//specifics set, add our to the array
		elseif( is_array( $supportedTypes ) ){
			$supportedTypes[0][] = 'faq';
			add_theme_support( 'post-thumbnails', $supportedTypes[0] );
			//for the faq thumb images
		}
		//if neither of the above hit, the theme in general supports them for everything.  that includes us!
		
		add_image_size( 'easy_faqs_thumb', 50, 50, true );
	}
	 
	//this is the heading of the new column we're adding to the faq posts list
	function easy_faqs_column_head($defaults) {  
		$defaults = array_slice($defaults, 0, 2, true) +
		array("single_shortcode" => "Shortcode") +
		array_slice($defaults, 2, count($defaults)-2, true);
		return $defaults;  
	}  

	//this content is displayed in the faq post list
	function easy_faqs_columns_content($column_name, $post_ID) {  
		if ($column_name == 'single_shortcode') {  
			echo "<code>[single_faq id={$post_ID}]</code>";
		}  
	} 

	//this is the heading of the new column we're adding to the faq category list
	function easy_faqs_cat_column_head($defaults) {  
		$defaults = array_slice($defaults, 0, 2, true) +
		array("single_shortcode" => "Shortcode") +
		array_slice($defaults, 2, count($defaults)-2, true);
		return $defaults;  
	}  

	//this content is displayed in the faq category list
	function easy_faqs_cat_columns_content($value, $column_name, $tax_id) {  

		$category = get_term_by('id', $tax_id, 'easy-faq-category');
		
		return "<code>[faqs category='{$category->slug}']</code>"; 
	} 

	//return an array of random numbers within a given range
	//credit: http://stackoverflow.com/questions/5612656/generating-unique-random-numbers-within-a-range-php
	function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
		$numbers = range($min, $max);
		shuffle($numbers);
		return array_slice($numbers, 0, $quantity);
	}

	//output specific faq
	function outputSingleFAQ($atts)
	{		
		// go ahead and extract category and ID because we need them to generate the loop
		extract( shortcode_atts( array(
			'id' => NULL,
			'category' => '',
		), $atts ) );
		$loop = new WP_Query(array( 'post_type' => 'faq','p' => $id, 'easy-faq-category' => $category));
		$faqs_list_html = $this->displayFAQsFromQuery($loop, $atts);		
		return $faqs_list_html;
	}
	
	// Generic function to display the results of a WP_Query ($loop)
	function displayFAQsFromQuery($loop, $atts = array())
	{
		// load default shortcode attributes into an array
		// and merge with anything specified
		extract( shortcode_atts( array(
			'read_more_link' => get_option('faqs_link'),
			'id' => NULL,
			'category' => '',
			'show_thumbs' => get_option('faqs_image'),
			'style' => '',			
			'quicklinks' => false,
			'read_more_link_text' =>  get_option('faqs_read_more_text', 'Read More'),
			'highlight_word' => ''
		), $atts ) );
		
		// start building the HTML now
		$output = '';
		
		// start with a wrapper div (with accordion classes, if requested & allowed)
		if( $style == "accordion" && isValidFAQKey() ) {
			$output .= '<div class="easy-faqs-wrapper easy-faqs-accordion">';
		} else if( $style == "accordion-collapsed" && isValidFAQKey() ){
			//output the accordion, with everything collapsed
			$output .= '<div class="easy-faqs-wrapper easy-faqs-accordion-collapsed">';
		} else {
			$output .='<div class="easy-faqs-wrapper">';
		}
		
		//output QuickLinks, if available and pro
		if($quicklinks && isValidFAQKey()){
			ob_start();
			$this->outputQuickLinks($atts);
			$output .= ob_get_contents();
			ob_end_clean();
		} 
		
		// build a single FAQ HTML block for each item, adding it to $output
		while( $loop->have_posts() )
		{
			// load up the current post data with this FAQ's information
			// this lets us use get_the_content, get_the_title, etc
			$loop->the_post();
			
			// load content for this FAQ
			$postid = get_the_ID();
			$faq['content'] = get_post_meta($postid, '_ikcf_short_content', true); 		
			
			// if nothing is set for the short content, use the long content instead
			if(strlen($faq['content']) < 2){
				$faq['content'] = get_the_content($postid); 
			}
			
			// add an image, if requested
			if ($show_thumbs) {
				$faq['image'] = get_the_post_thumbnail($postid, 'easy_faqs_thumb');				
				$image_html = $faq['image'];
			} else {
				$image_html = '';				
			}

			// generate the question and answer HTML
			$question_html = $this->build_the_question($postid);
			$answer_html = $this->build_the_answer($faq, $read_more_link, $read_more_link_text);
			
			// highlight the query in the question & answer
			if (strlen(trim($highlight_word)) > 0) {
				$highlight_tag = '<span class="search_highlight">\1</span>';				
				$highlight_tag = apply_filters('easy_faqs_search_highlight_tag', $highlight_tag);
				$question_html = gp_str_highlight($question_html, $highlight_word, null, $highlight_tag);
				$answer_html = gp_str_highlight($answer_html, $highlight_word, null, $highlight_tag);
			}
			
			// put it all together into the single FAQ template
			$faq_template = '<div class="easy-faq" id="easy-faq-%d">%s %s %s</div>';
			$faq_html = sprintf($faq_template, $postid, $image_html, $question_html, $answer_html);

			// add the completed FAQ to the output we are building
			$output .= $faq_html;
		} //endwhile;	
		
		// close the wrapper div
		$output .= '</div>';
		
		// reset the page's query to how it was when we got here
		wp_reset_query();
		
		return $output;
	}
	
	function build_the_question($postid)
	{
		$h3 = '<h3 class="easy-faq-title" style="%s">%s</h3>';
		$style_str = $this->build_typography_css('easy_faqs_question_');
		$output = sprintf($h3, $style_str, get_the_title($postid));
		return apply_filters( 'easy_faqs_question', $output);
	}
	
	function build_the_answer($faq, $read_more_link = '', $read_more_link_text = '')
	{
		$template = '<div class="easy-faq-body" style="%s">%s %s</div>';
		$content_str = apply_filters('the_content', $faq['content']);
		$style_str = $this->build_typography_css('easy_faqs_answer_');
		
		// add the read more link (if the user's options say to do so)
		if(!empty($read_more_link)) {
			// build the read more link to be inserted
			$link_template = '<a class="easy-faq-read-more-link" style="%s" href="%s">%s</a>';
			$link_style_str = $this->build_typography_css('easy_faqs_read_more_link_');
			$link_str = sprintf($link_template, $link_style_str, $read_more_link, $read_more_link_text);
		} else {
			// do not output a read more link
			$link_str = '';
		}
		$link_str = apply_filters( 'easy_faqs_read_more_link', $link_str);		
		
		// return the formatted answer text
		$output =  sprintf($template, $style_str, $content_str, $link_str);
		return apply_filters( 'easy_faqs_answer', $output);		
	}
	
	//passed the atts for the shortcode of faqs this is displayed above
	//loads faq data into a loop object
	//loops through that object and outputs quicklinks for those FAQs
	function outputQuickLinks($atts, $by_category = false){		
		//load shortcode attributes into an array
		extract( shortcode_atts( array(
			'count' => -1,
			'category' => '',
			'category_id' => '',
			'orderby' => 'date',//'none','ID','author','title','name','date','modified','parent','rand','menu_order'
			'order' => 'ASC',//'DESC'
			'colcount' => false
		), $atts ) );
		
		
		if($by_category){
			//load list of FAQ categories
			$categories = array();
			$args = array();
			
			/* If a custom category order was specified, apply it now */
			if ( !empty($category_id) ) {
				// we may have many categorys, delimited by commas, so explode 
				// the ID string into an array and then trim any whitespace
				$cats = explode(',', $category_id);
				$trimmed_cats = array_map('trim', $cats);
				$args['include'] = $trimmed_cats;
				
				// get only the categories which were specified
				$categories = get_terms('easy-faq-category', $args);		
				
				// resort the category's by the custom order
				$this->category_sort_order = $trimmed_cats;
				usort( $categories, array($this, "order_faqs_by_category_id") );
			} else {
				// no custom ordering specified, so proceed normally
				$categories = get_terms('easy-faq-category', $args);		
			}

			$quick_links_title = "<h3 class='quick-links' id='quick-links-top'>Quick Links</h3>";
			echo apply_filters( 'easy_faqs_quick_links_title', $quick_links_title);
			
			//loop through categories, outputting a heading for the category and the list of faqs in that category
			foreach($categories as $category)
			{
				//output title of category as a heading
				$category_name = apply_filters( 'easy_faqs_category_name', $category->name);
				$category_heading = sprintf('<h4 class="easy-testimonial-category-heading">%s</h4>', $category_name);
				echo apply_filters( 'easy_faqs_quick_links_category_heading', $category_heading);

				//load faqs into an array
				$loop = new WP_Query(array( 'post_type' => 'faq','posts_per_page' => $count, 'orderby' => $orderby, 'order' => $order, 'easy-faq-category' => $category->slug));
			
				$i = 0;
				$r = $loop->post_count;
				
				if(!$colcount){
					$divCount = intval($r/5);
					//if there are trailing testimonials, make sure we take into account the final div
					if($r%5!=0){
						$divCount ++;
					}		
				} else {
					$divCount = intval($colcount);
				}
				
				//trying CSS3 instead...
				echo "<div class='faq-questions'>";
				echo "<ol style=\"-webkit-column-count: {$divCount}; -moz-column-count: {$divCount}; column-count: {$divCount};\">";
				
				while($loop->have_posts()) : $loop->the_post();

					$postid = get_the_ID();
					
					$list_item = '<li class="faq_scroll" id="'.$postid.'"><a href="#easy-faq-' . $postid . '">' . get_the_title($postid) . '</a></li>';
					echo apply_filters( 'easy_faqs_quick_links_list_item', $list_item);

					$i ++;
					
				endwhile;
				
				
				echo "</ol>";
				echo "</div>";
			} 
		} else {
			//load faqs into an array
			$loop = new WP_Query(array( 'post_type' => 'faq','posts_per_page' => $count, 'orderby' => $orderby, 'order' => $order, 'easy-faq-category' => $category));
		
			$i = 0;
			$r = $loop->post_count;
			
			if(!$colcount){
				$divCount = intval($r/5);
				//if there are trailing testimonials, make sure we take into account the final div
				if($r%5!=0){
					$divCount ++;
				}		
			} else {
				$divCount = intval($colcount);
			}
			
			//trying CSS3 instead...
			$quick_links_title = "<h3 class='quick-links' id='quick-links-top'>Quick Links</h3>";
			echo apply_filters( 'easy_faqs_quick_links_title', $quick_links_title);			
			echo "<div class='faq-questions'>";
			echo "<ol style=\"-webkit-column-count: {$divCount}; -moz-column-count: {$divCount}; column-count: {$divCount};\">";
			
			while($loop->have_posts()) : $loop->the_post();

				$postid = get_the_ID();
				
				echo '<li class="faq_scroll" id="'.$postid.'"><a href="#easy-faq-' . $postid . '">' . get_the_title($postid) . '</a></li>';

				$i ++;
				
			endwhile;
			
			
			echo "</ol>";
			echo "</div>";
		}
	}

	//output all faqs
	function outputFAQs($atts)
	{
		// go ahead and extract category and ID because we need them to generate the loop
		extract( shortcode_atts( array(
			'count' => -1,
			'category' => '',
			'orderby' => 'date',//'none','ID','author','title','name','date','modified','parent','rand','menu_order'
			'order' => 'ASC'//'DESC'
		), $atts ) );
		
		$args = array( 
			'post_type' => 'faq',
			'posts_per_page' => $count,
			'orderby' => $orderby,
			'order' => $order,
			'easy-faq-category' => $category,
		);
		
		$loop = new WP_Query($args);

		$faqs_list_html = $this->displayFAQsFromQuery($loop, $atts);
		return $faqs_list_html;
	}
	
	//output all faqs grouped by category
	function outputFAQsByCategory($atts){ 
		
		//load shortcode attributes into an array
		extract( shortcode_atts( array(
			'category_id' => '',
			'category_ids' => '',
			'read_more_link' => get_option('faqs_link'),
			'count' => -1,
			//'category' => '',
			'show_thumbs' => get_option('faqs_image'),
			'read_more_link_text' =>  get_option('faqs_read_more_text', 'Read More'),
			'style' => '',
			'quicklinks' => false,
			'orderby' => 'date',//'none','ID','author','title','name','date','modified','parent','rand','menu_order'
			'order' => 'ASC'//'DESC'
		), $atts ) );
				
		if(!is_numeric($count)){
			$count = -1;
		}
		
		// handle possible pluralization of category_id(s)
		if ( empty($category_id) && !empty($category_ids) ) {
			// note: $atts gets passed through to several other functions later,
			// so we need to update it too
			$category_id = $category_ids;
			$atts['category_id'] = $category_ids; 
		}

		ob_start();
		
		//load list of FAQ categories
		$categories = array();
		$args = array();
		
		/* If a custom category order was specified, apply it now */
		if ( !empty($category_id) ) {
			// we may have many categorys, delimited by commas, so explode 
			// the ID string into an array and then trim any whitespace
			$cats = explode(',', $category_id);
			$trimmed_cats = array_map('trim', $cats);
			$args['include'] = $trimmed_cats;
			
			// get only the categories which were specified
			$categories = get_terms('easy-faq-category', $args);		
			
			// resort the category's by the custom order
			$this->category_sort_order = $trimmed_cats;
			usort( $categories, array($this, "order_faqs_by_category_id") );
		} else {
			// no custom ordering specified, so proceed normally
			$categories = get_terms('easy-faq-category', $args);		
		}

		//output QuickLinks, if available and pro
		if($quicklinks && isValidFAQKey()){
			$this->outputQuickLinks($atts, true);
		} 
		
		// starting here, we force quicklinks to false so that we don't 
		// output another set of quicklinks for every category
		$atts['quicklinks'] = false;

		//loop through categories, outputting a heading for the category and the list of faqs in that category
		foreach($categories as $category)
		{	
			//output title of category as a heading
			$category_name = apply_filters( 'easy_faqs_category_name', $category->name);
			$category_heading = sprintf('<h2 class="easy-faqs-category-heading">%s</h2>', $category_name);
			echo apply_filters( 'easy_faqs_category_heading', $category_heading);
		
			//load faqs into an array and then output them as a list
			$loop = new WP_Query(array( 'post_type' => 'faq','posts_per_page' => $count, 'orderby' => $orderby, 'order' => $order, 'easy-faq-category' => $category->slug));
			echo $this->displayFAQsFromQuery($loop, $atts);
			
		}//endforeach categories
		
		$content = ob_get_contents();
		ob_end_clean();	
		
		wp_reset_query();
		
		return $content;
	}
	
	function order_faqs_by_category_id($cat_1, $cat_2)
	{
		// first find their term's positions in our order array		
		// this is the key on which we will actually sort
		$c1_pos = array_search( $cat_1->term_id, $this->category_sort_order );
		$c2_pos = array_search( $cat_2->term_id, $this->category_sort_order );
		
		// now, handle cases where one of the keys wasn't found
		// in this case, whichever one was found "wins"		
		if ($c1_pos === FALSE && $c2_pos === FALSE) {
			return 0;
		}
		else if ($c1_pos >= 0 && $c2_pos == FALSE) {
			return 1;
		}
		else if ($c1_pos === FALSE && $c2_pos >= 0) {
			return -1;
		}		
				
		// both keys found; return the one which is first in our custom order
		if ($c1_pos === $c2_pos) {
			// this should only happen if a category id was duplicated
			return 0;
		}
		else if ($c1_pos > $c2_pos) {
			// first term appears first
			return 1;
		} else if ($c1_pos < $c2_pos) {
			// second term appears first
			return -1;
		}
		
	}
	
/*
	 * Builds a CSS string corresponding to the values of a typography setting
	 *
	 * @param	$prefix		The prefix for the settings. We'll append font_name,
	 *						font_size, etc to this prefix to get the actual keys
	 *
	 * @returns	string		The completed CSS string, with the values inlined
	 */
	function build_typography_css($prefix)
	{
		$css_rule_template = ' %s: %s;';
		$output = '';
		
		/* 
		 * Font Family
		 */
		 
		$option_val = get_option($prefix . 'font_family', '');
		if (!empty($option_val)) {
			// strip off 'google:' prefix if needed
			$option_val = str_replace('google:', '', $option_val);

		
			// wrap font family name in quotes
			$option_val = '\'' . $option_val . '\'';
			$output .= sprintf($css_rule_template, 'font-family', $option_val);
		}
		
		/* 
		 * Font Size
		 */
		$option_val = get_option($prefix . 'font_size', '');
		if (!empty($option_val)) {
			// append 'px' if needed
			if ( is_numeric($option_val) ) {
				$option_val .= 'px';
			}
			$output .= sprintf($css_rule_template, 'font-size', $option_val);
		}		
		
		/* 
		 * Font Color
		 */
		$option_val = get_option($prefix . 'font_color', '');
		if (!empty($option_val)) {
			$output .= sprintf($css_rule_template, 'color', $option_val);
		}

		/* 
		 * Font Style - add font-style and font-weight rules
		 * NOTE: in this special case, we are adding 2 rules!
		 */
		$option_val = get_option($prefix . 'font_style', '');

		// Convert the value to 2 CSS rules, font-style and font-weight
		// NOTE: we lowercase the value before comparison, for simplification
		switch(strtolower($option_val))
		{
			case 'regular':
				// not bold not italic
				$output .= sprintf($css_rule_template, 'font-style', 'normal');
				$output .= sprintf($css_rule_template, 'font-weight', 'normal');
			break;
		
			case 'bold':
				// bold, but not italic
				$output .= sprintf($css_rule_template, 'font-style', 'normal');
				$output .= sprintf($css_rule_template, 'font-weight', 'bold');
			break;

			case 'italic':
				// italic, but not bold
				$output .= sprintf($css_rule_template, 'font-style', 'italic');
				$output .= sprintf($css_rule_template, 'font-weight', 'normal');
			break;
		
			case 'bold italic':
				// bold and italic
				$output .= sprintf($css_rule_template, 'font-style', 'italic');
				$output .= sprintf($css_rule_template, 'font-weight', 'bold');
			break;
			
			default:
				// empty string or other invalid value, ignore and move on
			break;			
		}			

		// return the completed CSS string
		return trim($output);		
	}
	
	// Enqueue any needed Google Web Fonts
	function enqueue_webfonts()
	{
		$font_list = $this->list_required_google_fonts();
		$font_list_encoded = array_map('urlencode', $this->list_required_google_fonts());
		$font_str = implode('|', $font_list_encoded);
		
		//don't register this unless a font is set to register
		if(strlen($font_str)>2){
			wp_register_style( 'easy_faqs_webfonts', 'http://fonts.googleapis.com/css?family=' . $font_str);
			wp_enqueue_style( 'easy_faqs_webfonts' );
		}
	}

	function list_required_google_fonts()
	{
		// check each typography setting for google fonts, and build a list
		$option_keys = array(
			'easy_faqs_question_font_family',
			'easy_faqs_answer_font_family',
			'easy_faqs_read_more_link_font_family',
		);
		$fonts = array();
		foreach ($option_keys as $option_key) {
			$option_value = get_option($option_key);
			if (strpos($option_value, 'google:') !== FALSE) {
				$option_value = str_replace('google:', '', $option_value);
				
				//only add the font to the array if it was in fact a google font
				$fonts[$option_value] = $option_value;				
			}
		}
		return $fonts;
	}	


	//register any widgets here
	function easy_faqs_register_widgets() {
		include('include/widgets/single_faq_widget.php');

		register_widget( 'singleFAQWidget' );
	}
}//end easyFAQs

if (!isset($easy_faqs)){
	$easy_faqs = new easyFAQs();
}