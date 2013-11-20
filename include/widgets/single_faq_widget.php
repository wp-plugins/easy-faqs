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
along with Easy FAQs.  If not, see <http://www.gnu.org/licenses/>.

Shout out to http://www.makeuseof.com/tag/how-to-create-wordpress-widgets/ for the help
*/

class singleFAQWidget extends WP_Widget
{
	function singleFAQWidget(){
		$widget_ops = array('classname' => 'singleFAQWidget', 'description' => 'Displays a random FAQ.' );
		$this->WP_Widget('singleFAQWidget', 'Easy Single FAQ', $widget_ops);
	}

	function form($instance){
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'faqid' => null ) );
		$title = $instance['title'];
		$faqid = $instance['faqid'];
		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>">Widget Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
			<?php
				$faqs = get_posts('post_type=faq');
			?>
				<label for="<?php echo $this->get_field_id('faqid'); ?>">FAQ to Display</label>
				<select id="<?php echo $this->get_field_id('faqid'); ?>" name="<?php echo $this->get_field_name('faqid'); ?>">
				<?php if($faqs) : foreach ( $faqs as $faq  ) : ?>
					<option value="<?php echo $faq->ID; ?>"  <?php if($faqid == $faq->ID): ?> selected="SELECTED" <?php endif; ?>><?php echo $faq->post_title; ?></option>
				<?php endforeach; endif;?>
				 </select>
		<?php
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['faqid'] = $new_instance['faqid'];
		return $instance;
	}

	function widget($args, $instance){
		global $easy_faqs;
		
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$faqid = empty($instance['faqid']) ? null : $instance['faqid'];

		if (!empty($title)){
			echo $before_title . $title . $after_title;;
		}
		
		echo $easy_faqs->outputSingleFAQ(array('faqs_link' => get_option('faqs_link'), 'faqid' => $faqid));

		echo $after_widget;
	} 
}
?>