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


	/**
	 * One-time seed of the RSP Header 1C nav structure, so it doesn't have
	 * to be built by hand in Appearance > Menus. Runs once (gated by the
	 * editor_child_rsp_menu_seeded option), creates a "RSP Primary
	 * Navigation" menu with the Header 1C item structure (placeholder '#'
	 * links until the real pages exist), and assigns it to
	 * pixelwars_theme_menu_location_1 — replacing whatever menu is
	 * currently assigned there.
	 */
	function editor_child_seed_primary_menu()
	{
		if ( get_option( 'editor_child_rsp_menu_seeded' ) )
		{
			return;
		}

		$menu_name = 'RSP Primary Navigation';
		$menu_id   = wp_create_nav_menu( $menu_name );

		if ( is_wp_error( $menu_id ) )
		{
			$existing = wp_get_nav_menu_object( $menu_name );

			if ( ! $existing )
			{
				return;
			}

			$menu_id = $existing->term_id;
		}

		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'  => 'RSP Draft Guide',
			'menu-item-url'    => '#',
			'menu-item-status' => 'publish',
		) );

		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'  => 'Ranking & Projections',
			'menu-item-url'    => '#',
			'menu-item-status' => 'publish',
		) );

		$player_eval_id = wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'  => 'Player Evaluation',
			'menu-item-url'    => '#',
			'menu-item-status' => 'publish',
		) );

		foreach ( array( 'by Position', 'by Draft Class', 'Film Room' ) as $label )
		{
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => $label,
				'menu-item-url'       => '#',
				'menu-item-parent-id' => $player_eval_id,
				'menu-item-status'    => 'publish',
			) );
		}

		$resources_id = wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'  => 'Resources',
			'menu-item-url'    => '#',
			'menu-item-status' => 'publish',
		) );

		foreach ( array( 'Film Room', 'Articles', 'Podcasts' ) as $label )
		{
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'     => $label,
				'menu-item-url'       => '#',
				'menu-item-parent-id' => $resources_id,
				'menu-item-status'    => 'publish',
			) );
		}

		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'  => 'About Us',
			'menu-item-url'    => '#',
			'menu-item-status' => 'publish',
		) );

		$locations = get_theme_mod( 'nav_menu_locations' );
		$locations = is_array( $locations ) ? $locations : array();
		$locations['pixelwars_theme_menu_location_1'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );

		update_option( 'editor_child_rsp_menu_seeded', 1 );
	}

	add_action('init', 'editor_child_seed_primary_menu');


/* Custom Functions */