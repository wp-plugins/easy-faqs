<?php
	//boot up WP so we can access some needed things
	include('../../../../../wp-load.php');
	
	$exporter = new easyFAQsExporter();
				
	$filename = empty($_GET['filename']) ? 'faqs-export.csv' : $_GET['filename'];
	
	$exporter->process_export($filename);