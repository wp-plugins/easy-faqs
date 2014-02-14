<?php
/*
Plugin Name: Easy FAQs
Plugin URI: http://goldplugins.com/our-plugins/easy-faqs-details/
Description: Easy FAQs - Provides custom post type, shortcodes, widgets, and other functionality for Frequently Asked Questions (FAQs).
Author: Illuminati Karate
Version: 1.2.1
Author URI: http://illuminatikarate.com

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

class easyFAQs
{
	function __construct(){		
		//create shortcodes
		add_shortcode('single_faq', array($this, 'outputSingleFAQ'));
		add_shortcode('faqs', array($this, 'outputFAQs'));
		add_shortcode('submit_faq', array($this, 'submitFAQForm'));

		//add JS
		add_action( 'wp_enqueue_scripts', array($this, 'easy_faqs_setup_js' ));

		//add CSS
		add_action( 'wp_head', array($this, 'easy_faqs_setup_css' ));

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

	//submit faq shortcode
	function submitFAQForm($atts){ 
			// process form submissions
			$inserted = false;
		   
			if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] )) {
				//only process submissions from logged in users
				if(isValidFAQKey()){  
						if (isset ($_POST['the-title'])) {
								$title =  $_POST['the-title'];
						} else {
								echo 'Please enter a title';
						}
					   
						if (isset ($_POST['the-body'])) {
								$body = $_POST['the-body'];
						} else {
								echo 'Please enter the content';
						}
					   
						$tags = $_POST['the-post_tags'];
					   
						$post = array(
								'post_title'    => $title,
								'post_content'  => $body,
								'post_category' => array(1),  // custom taxonomies too, needs to be an array
								'tags_input'    => $tags,
								'post_status'   => 'pending',
								'post_type'     => 'faq'
						);
					   
						wp_insert_post($post);
					   
						$inserted = true;
	   
						// do the wp_insert_post action to insert it
						do_action('wp_insert_post', 'wp_insert_post');                 
				} else {
						echo "You must have a valid key to perform this action.";
				}
			}       
		   
			$content = '';
		   
			if(isValidFAQKey()){       
				ob_start();
			   
				if($inserted){
						echo "Thank you for your submission!";
				} else { ?>
				<!-- New Post Form -->
				<div id="postbox">
						<form id="new_post" name="new_post" method="post" action="/blog/easy-faqs-test/">
								<div class="easy_faqs_field_wrap">
									<label for="the-title">Question</label><br />
									<textarea id="the-title" tabindex="3" name="the-body" cols="50" rows="6"></textarea>
									<p class="easy_faqs_description">This is the question that you are asking.</p>
								</div>
								<div class="easy_faqs_field_wrap">
									<label for="the-body">Additional Info</label><br />
									<textarea id="the-body" tabindex="3" name="the-body" cols="50" rows="6"></textarea>
									<p class="easy_faqs_description">Any additional info you want relayed along with your question.</p>
								</div>
								<div class="easy_faqs_field_wrap"><input type="submit" value="Submit FAQ" tabindex="6" id="submit" name="submit" /></div>
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

	//setup custom post type for faqs
	function easy_faqs_setup_faqs(){
		//include custom post type code
		include('include/lib/ik-custom-post-type.php');
		//include options code
		include('include/easy_faq_options.php');	
		$easy_faqs_options = new easyFAQOptions();
				
		//setup post type for faqs
		$postType = array('name' => 'FAQ', 'plural' =>'faqs', 'slug' => 'faq' );
		$fields = array(); 
		$myCustomType = new ikFAQsCustomPostType($postType, $fields);
		register_taxonomy( 'easy-faq-category', 'faq', array( 'hierarchical' => true, 'label' => __('FAQ Category'), 'rewrite' => array('slug' => 'faq', 'with_front' => false) ) ); 
		
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
	function outputSingleFAQ($atts){ 
		
		//load shortcode attributes into an array
		extract( shortcode_atts( array(
			'faqs_link' => get_option('faqs_link'),
			'faqid' => NULL,
			'category' => ''
		), $atts ) );
		
		$show_thumbs = get_option('faqs_image');
		
		ob_start();
		
		$i = 0;
		
		echo '<div class="easy-faqs-wrapper">';
		
		//load faqs into an array
		$loop = new WP_Query(array( 'post_type' => 'faq','p' => $faqid, 'easy-faq-category' => $category));
		while($loop->have_posts()) : $loop->the_post();
			$postid = get_the_ID();
			$faq['content'] = get_post_meta($postid, '_ikcf_short_content', true); 		

			//if nothing is set for the short content, use the long content
			if(strlen($faq['content']) < 2){
				$faq['content'] = get_the_content($postid); 
			}
			
			if ($show_thumbs) {
				$faq['image'] = get_the_post_thumbnail($postid, 'easy_faqs_thumb');
			}
		
			?><div class="easy-faq" id="easy-faq-<?php echo $postid; ?>">		
				<?php if ($show_thumbs) {
					echo $faq['image'];
				} ?>
				
				<?php echo '<h3 class="easy-faq-title">' . get_the_title($postid) . '</h3>'; ?>
					
				<p class="faq_body">
					<?php echo apply_filters('the_content', $faq['content']);?>
				</p>	

			</div><?php 	
				
		endwhile;	
		wp_reset_query();
		
		echo '</div>';
		
		$content = ob_get_contents();
		ob_end_clean();	
		
		return $content;
	}

	//output all faqs
	function outputFAQs($atts){ 
		
		//load shortcode attributes into an array
		extract( shortcode_atts( array(
			'faqs_link' => get_option('faqs_link'),
			'count' => -1,
			'category' => '',
			'style' => '',
			'orderby' => 'date',//'none','ID','author','title','name','date','modified','parent','rand','menu_order'
			'order' => 'ASC'//'DESC'
		), $atts ) );
		
		$show_thumbs = get_option('faqs_image');
				
		if(!is_numeric($count)){
			$count = -1;
		}
		
		ob_start();
		
		if($style == "accordion" && isValidFAQKey()){
			echo '<div class="easy-faqs-wrapper easy-faqs-accordion">';
		} else {
			echo '<div class="easy-faqs-wrapper">';
		}

		$i = 0;
		
		//load faqs into an array
		$loop = new WP_Query(array( 'post_type' => 'faq','posts_per_page' => $count, 'orderby' => $orderby, 'order' => $order, 'easy-faq-category' => $category));
		while($loop->have_posts()) : $loop->the_post();
			$postid = get_the_ID();
			$faq['content'] = get_post_meta($postid, '_ikcf_short_content', true); 		

			//if nothing is set for the short content, use the long content
			if(strlen($faq['content']) < 2){
				$faq['content'] = get_the_content($postid); 
			}
			
			if ($show_thumbs) {
				$faq['image'] = get_the_post_thumbnail($postid, 'easy_faqs_thumb');
			}
		
			if($i < $count || $count == -1){
		
				?><div class="easy-faq" id="easy-faq-<?php echo $postid; ?>">		
					<?php if ($show_thumbs) {
						echo $faq['image'];
					} ?>	
					
					<?php echo '<h3 class="easy-faq-title">' . get_the_title($postid) . '</h3>'; ?>	
				
					<div class="easy-faq-body">
						<?php echo apply_filters('the_content', $faq['content']);?>
					</div>	
					
				</div><?php 	
				
				$i ++;
			}
		endwhile;	
		wp_reset_query();

		echo '</div>'; //<!--.easy-faqs-wrapper-->
		
		$content = ob_get_contents();
		ob_end_clean();	
		
		return $content;
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
?>