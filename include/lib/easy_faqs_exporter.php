<?php
class easyFAQsExporter
{	
	public function output_form(){
		?>
		<form method="POST" action="">			
			<label>CSV Filename</label>
			<div class="bikeshed bikeshed_text">
				<div class="text_wrapper">
					<input type="text" class="" value="faqs-export.csv" id="csv_filename" name="csv_filename">
				</div>
				<p class="description">This is the desired filename of the export.  This will default to "faqs-export.csv".</p>
			</div>
				
			<p class="submit">
				<input type="submit" class="button" value="Export FAQs" />
			</p>
		</form>
		<?php
	}
	
	public function process_export($filename = "faqs-export.csv"){
		ob_start();
					
		//load faqs
		$args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'category'         => '',
			'category_name'    => '',
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'faq',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => true 				
		);
		
		$faqs = get_posts($args);
		
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Description: File Transfer');
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename={$filename}");
		header("Expires: 0");
		header("Pragma: public");
		
		$fh = @fopen( 'php://output', 'w' );
		
		$headerDisplayed = false;
			
		foreach($faqs as $faq){
			if ( !$headerDisplayed ) {
				// Use the keys from $data as the titles
				fputcsv($fh, array('Question','Answer'));
				$headerDisplayed = true;
			}
			
			fputcsv($fh, array($faq->post_title, $faq->post_content));
		}
		
		// Close the file
		fclose($fh);
		
		ob_end_flush();
	}
	
	//displays interface to allow user to download a CSV export of their FAQs
	public function csv_exporter(){
		$filename = empty($_POST['csv_filename']) ? false : $_POST['csv_filename'];
		
		//form not yet submitted, present user with form
		if(!$filename){		
			$this->output_form();
		} else { //form has been submitted, generate export!
			?>
				<p>Your download should begin momentarily...</p>
				<?php $this->output_form(); ?>
				<iframe style="width:100%;height:100%" src="<?php echo plugins_url( 'easy_faqs_exporter_view.php?filename='.$filename, __FILE__ ); ?>" />
			<?php
		}
	}
}