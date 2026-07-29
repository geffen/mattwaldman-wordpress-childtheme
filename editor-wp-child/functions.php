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


	/**
	 * Core wp_nav_menu() doesn't add a "menu-item-has-children" class to
	 * parent items by itself — add it for the RSP menus so the desktop
	 * dropdown caret and the mobile accordion toggle (js/nav.js) have
	 * something to hook into.
	 */
	function editor_child_menu_has_children_class( $items, $args )
	{
		if ( empty( $args->menu_class ) || ! in_array( $args->menu_class, array( 'rsp-menu', 'rsp-mobile-menu__list' ), true ) )
		{
			return $items;
		}

		$parent_ids = array_filter( wp_list_pluck( $items, 'menu_item_parent' ) );

		foreach ( $items as $item )
		{
			if ( in_array( (string) $item->ID, $parent_ids, true ) )
			{
				$item->classes[] = 'menu-item-has-children';
			}
		}

		return $items;
	}

	add_filter('wp_nav_menu_objects', 'editor_child_menu_has_children_class', 10, 2);


/* Custom Functions */