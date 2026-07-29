<?php

	function editor_ocdi_import_files()
	{
		return array(
			array(
				'import_file_name'         => esc_html__('Demo Content', 'editor'),
				'local_import_file'        => trailingslashit(get_template_directory()) . 'admin/demo-data/content.xml',
				'local_import_widget_file' => trailingslashit(get_template_directory()) . 'admin/demo-data/widgets.wie'
			)
		);
	}
	
	add_filter('pt-ocdi/import_files', 'editor_ocdi_import_files');
	
	
	function editor_ocdi_time_for_one_ajax_call()
	{
		return 10;
	}
	
	add_action('pt-ocdi/time_for_one_ajax_call', 'editor_ocdi_time_for_one_ajax_call');
	
	
	add_filter('pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false');

?>