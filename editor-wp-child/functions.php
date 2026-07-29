<?php

	function editor_child_scripts()
	{
		wp_enqueue_style('editor-parent-style', get_template_directory_uri(). '/style.css');

		wp_enqueue_style('rsp-google-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Cinzel:wght@600;700&family=Lora:ital,wght@0,400;0,500;1,400&display=swap', array(), null);

		wp_enqueue_script('rsp-nav', get_stylesheet_directory_uri() . '/js/nav.js', array(), filemtime( get_stylesheet_directory() . '/js/nav.js' ), true);
	}

	add_action('wp_enqueue_scripts', 'editor_child_scripts');


	/**
	 * The parent theme enqueues the child's style.css itself (handle
	 * "theme-style", functions.php ~line 60) with $ver = null, so it never
	 * gets a cache-busting query string — any CDN/browser cache keyed on
	 * that bare URL can serve a stale copy after every future CSS change.
	 * Re-register the same handle after the parent's enqueue runs, with a
	 * version tied to the file's actual mtime so it self-busts on every
	 * upload.
	 */
	function editor_child_fix_style_cache_busting()
	{
		wp_dequeue_style( 'theme-style' );
		wp_deregister_style( 'theme-style' );
		wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
	}

	add_action('wp_enqueue_scripts', 'editor_child_fix_style_cache_busting', 20);


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
	 * Content taxonomies backing the Player Evaluation nav dropdown.
	 * "by Position" / "by Draft Class" don't have landing-page templates
	 * yet (that's separate work), but registering these now lets posts
	 * start getting tagged ahead of that.
	 */
	function editor_child_register_taxonomies()
	{
		register_taxonomy( 'rsp_position', 'post', array(
			'label'        => 'Position',
			'hierarchical' => false,
			'public'       => true,
			'show_ui'      => true,
			'show_in_menu' => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'position' ),
		) );

		register_taxonomy( 'rsp_draft_class', 'post', array(
			'label'        => 'Draft Class',
			'hierarchical' => false,
			'public'       => true,
			'show_ui'      => true,
			'show_in_menu' => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'draft-class' ),
		) );
	}

	add_action('init', 'editor_child_register_taxonomies', 5);


	/**
	 * New taxonomies need their rewrite rules flushed once before their
	 * URLs resolve instead of 404ing — normally done by hand via
	 * Settings > Permalinks (just visiting and saving that screen
	 * flushes them), doing it here so that manual step isn't required.
	 */
	function editor_child_flush_rewrites_for_new_taxonomies()
	{
		if ( get_option( 'editor_child_rsp_rewrites_flushed' ) )
		{
			return;
		}

		flush_rewrite_rules();
		update_option( 'editor_child_rsp_rewrites_flushed', 1 );
	}

	add_action('init', 'editor_child_flush_rewrites_for_new_taxonomies', 30);


	/**
	 * One-time seed of starting terms: QB/RB/WR/TE for the new Position
	 * taxonomy (RSP's scope per the design spec), and the three Resources
	 * categories (Film Room / Articles / Podcasts) the Resources +
	 * Player Evaluation nav dropdowns point at. Draft Class terms aren't
	 * pre-seeded — that's an open-ended list, added as posts get tagged.
	 */
	function editor_child_seed_content_taxonomy_terms()
	{
		if ( get_option( 'editor_child_rsp_terms_seeded' ) )
		{
			return;
		}

		foreach ( array( 'QB', 'RB', 'WR', 'TE' ) as $position )
		{
			if ( ! term_exists( $position, 'rsp_position' ) )
			{
				wp_insert_term( $position, 'rsp_position' );
			}
		}

		foreach ( array( 'Film Room', 'Articles', 'Podcasts' ) as $cat_name )
		{
			if ( ! term_exists( $cat_name, 'category' ) )
			{
				wp_insert_term( $cat_name, 'category' );
			}
		}

		update_option( 'editor_child_rsp_terms_seeded', 1 );
	}

	add_action('init', 'editor_child_seed_content_taxonomy_terms', 8);


	/**
	 * One-time seed of the RSP Header 1C nav structure, so it doesn't have
	 * to be built by hand in Appearance > Menus. Runs once (gated by the
	 * editor_child_rsp_menu_seeded option), creates a "RSP Primary
	 * Navigation" menu with the Header 1C item structure (placeholder '#'
	 * links until the real pages exist), and assigns it to
	 * pixelwars_theme_menu_location_1 — replacing whatever menu is
	 * currently assigned there. editor_child_sync_primary_menu_urls()
	 * below fills in real URLs as real destinations come online, so this
	 * only ever needs to run the one time.
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


	/**
	 * Keeps known nav item URLs pointed at real destinations as those
	 * destinations come online, instead of leaving them at the seed
	 * function's '#' placeholders forever. Runs every request but is
	 * cheap (a handful of term lookups + one menu-items query) and only
	 * writes when a URL actually needs to change.
	 *
	 * Matches items by exact title, so anything Matt adds, renames, or
	 * reorders by hand in Appearance > Menus is left alone — only the
	 * titles in $url_map below ever get touched, and only once a real
	 * destination is resolvable for them. Titles not yet resolvable
	 * (RSP Draft Guide, Ranking & Projections, Player Evaluation's "by
	 * Position"/"by Draft Class", About Us) stay '#' until their pages
	 * exist — add them to $url_map here once they do.
	 */
	function editor_child_sync_primary_menu_urls()
	{
		$menu = wp_get_nav_menu_object( 'RSP Primary Navigation' );

		if ( ! $menu )
		{
			return;
		}

		$film_room = get_category_by_slug( 'film-room' );
		$articles  = get_category_by_slug( 'articles' );
		$podcasts  = get_category_by_slug( 'podcasts' );

		$url_map = array_filter( array(
			'Film Room' => $film_room ? get_category_link( $film_room ) : '',
			'Articles'  => $articles ? get_category_link( $articles ) : '',
			'Podcasts'  => $podcasts ? get_category_link( $podcasts ) : '',
		) );

		if ( empty( $url_map ) )
		{
			return;
		}

		$items = wp_get_nav_menu_items( $menu->term_id );

		if ( ! $items )
		{
			return;
		}

		foreach ( $items as $item )
		{
			if ( ! isset( $url_map[ $item->title ] ) || $item->url === $url_map[ $item->title ] )
			{
				continue;
			}

			wp_update_nav_menu_item( $menu->term_id, $item->ID, array(
				'menu-item-title'     => $item->title,
				'menu-item-url'       => $url_map[ $item->title ],
				'menu-item-parent-id' => $item->menu_item_parent,
				'menu-item-status'    => 'publish',
			) );
		}
	}

	add_action('init', 'editor_child_sync_primary_menu_urls', 20);


	/**
	 * Article Template shortcodes — let Matt drop these design elements
	 * inline in the post body wherever he wants, instead of them being
	 * fixed to one spot in the template:
	 *   [rsp_video id="YOUTUBE_ID"] — 16:9 click-to-play video block
	 *   [rsp_promo]                  — RSP purchase-pitch pull-quote block
	 */
	function editor_child_video_shortcode( $atts )
	{
		$atts = shortcode_atts( array( 'id' => '' ), $atts, 'rsp_video' );

		if ( empty( $atts['id'] ) )
		{
			return '';
		}

		$video_id = sanitize_text_field( $atts['id'] );

		ob_start();
		?>
		<div class="rsp-article__video" data-video-id="<?php echo esc_attr( $video_id ); ?>">
			<img class="rsp-article__video-poster" src="https://img.youtube.com/vi/<?php echo esc_attr( $video_id ); ?>/maxresdefault.jpg" alt="" loading="lazy">
			<button type="button" class="rsp-article__video-play" aria-label="<?php esc_attr_e( 'Play video', 'editor-child' ); ?>">
				<span class="rsp-article__video-play-icon"></span>
			</button>
		</div>
		<?php
		return ob_get_clean();
	}

	add_shortcode('rsp_video', 'editor_child_video_shortcode');


	function editor_child_promo_shortcode()
	{
		ob_start();
		?>
		<div class="rsp-article__pullquote">
			<p class="rsp-article__pullquote-lead">And of course, if you want to know about the rookies from this draft class, you'll find the most in-depth analysis of offensive skill players available (QB, RB, WR, and TE) with the <a href="https://mattwaldman.com">Rookie Scouting Portfolio</a> for $21.95.</p>
			<p class="rsp-article__pullquote-sub">Matt's <a href="https://mattwaldman.com">RSP Dynasty Rankings and Two-Year Projections Package</a> is available for $24.95. Best yet, proceeds from sales are set aside for a year-end donation to Darkness to Light to combat the sexual abuse of children.</p>
		</div>
		<?php
		return ob_get_clean();
	}

	add_shortcode('rsp_promo', 'editor_child_promo_shortcode');


/* Custom Functions */