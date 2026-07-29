<?php

	function editor_child_scripts()
	{
		wp_enqueue_style('editor-parent-style', get_template_directory_uri(). '/style.css');

		wp_enqueue_style('rsp-google-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Cinzel:wght@600;700&display=swap', array(), null);

		wp_enqueue_script('rsp-nav', get_stylesheet_directory_uri() . '/js/nav.js', array(), '1.0.0', true);
	}

	add_action('wp_enqueue_scripts', 'editor_child_scripts');


	function editor_child_font_preconnect()
	{
		echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
	}

	add_action('wp_head', 'editor_child_font_preconnect', 1);


/* Custom Functions */