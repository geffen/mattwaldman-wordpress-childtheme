<?php

	function editor_child_scripts()
	{
		wp_enqueue_style('editor-parent-style', get_template_directory_uri(). '/style.css');
	}
	
	add_action('wp_enqueue_scripts', 'editor_child_scripts');


/* Custom Functions */