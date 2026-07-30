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


	/**
	 * The host sends no cache headers at all on front-end HTML — no
	 * Cache-Control, ETag, or Expires — so browsers fall back to
	 * heuristic freshness and can serve a stale page for hours after a
	 * deploy or a new post. Send explicit revalidation headers so the
	 * browser always checks with the server.
	 *
	 * Enqueued assets are already handled separately by filemtime()
	 * versioning; this covers the HTML documents themselves.
	 *
	 * Skips admin, feeds, and REST so their own caching rules stand.
	 */
	function editor_child_revalidate_html()
	{
		if ( is_admin() || is_feed() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) )
		{
			return;
		}

		if ( headers_sent() )
		{
			return;
		}

		header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
	}

	add_action('send_headers', 'editor_child_revalidate_html');


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
	 * TEST-SITE-ONLY demo content: adds the "Articles" category to the 8
	 * most recent posts so the Resources > Articles nav link has a real
	 * grid to show (that category is newly created and empty, and Matt's
	 * actual re-categorisation hasn't happened yet).
	 *
	 * Two guards, because this writes to post data:
	 *  - hard-gated to the notmattwaldman.com test host, so it can never
	 *    fire on production even if this theme is deployed there;
	 *  - one-time, via the editor_child_demo_articles_seeded option.
	 *
	 * Appends the category (does not replace existing ones), so it's
	 * trivially reversible. DELETE this function once real content is
	 * categorised — it exists only to make the nav browsable during dev.
	 */
	function editor_child_seed_demo_articles_category()
	{
		if ( get_option( 'editor_child_demo_articles_seeded' ) )
		{
			return;
		}

		if ( false === strpos( home_url(), 'notmattwaldman.com' ) )
		{
			return;
		}

		$articles = get_category_by_slug( 'articles' );

		if ( ! $articles )
		{
			return;
		}

		$post_ids = get_posts( array(
			'posts_per_page'      => 8,
			'ignore_sticky_posts' => true,
			'fields'              => 'ids',
		) );

		foreach ( $post_ids as $post_id )
		{
			wp_set_post_categories( $post_id, array( (int) $articles->term_id ), true );
		}

		update_option( 'editor_child_demo_articles_seeded', 1 );
	}

	add_action('init', 'editor_child_seed_demo_articles_category', 10);


	/**
	 * One-time seed of rsp_draft_class terms. Deliberately not done in the
	 * original term seeder (draft classes are an open-ended list), but the
	 * "by Draft Class" nav submenu needs terms to point at, so seed the
	 * recent classes. Add future years in wp-admin under Posts > Draft
	 * Class — nothing here needs changing.
	 */
	function editor_child_seed_draft_class_terms()
	{
		if ( get_option( 'editor_child_draft_classes_seeded' ) )
		{
			return;
		}

		foreach ( array( '2024', '2025', '2026' ) as $year )
		{
			if ( ! term_exists( $year, 'rsp_draft_class' ) )
			{
				wp_insert_term( $year, 'rsp_draft_class' );
			}
		}

		update_option( 'editor_child_draft_classes_seeded', 1 );
	}

	add_action('init', 'editor_child_seed_draft_class_terms', 8);


	/**
	 * One-time build of the third nav level: turns "by Position" and
	 * "by Draft Class" into dropdown parents whose children are the
	 * actual taxonomy terms (QB/RB/WR/TE and the draft-class years).
	 * Each child points at its term archive, which archive.php renders.
	 *
	 * Separate from editor_child_seed_primary_menu() because that one has
	 * already run on the live site — its option gate means it will never
	 * fire again, so new structure needs its own one-time seeder.
	 *
	 * Draft classes are ordered newest-first; positions keep QB/RB/WR/TE
	 * order rather than alphabetical.
	 */
	function editor_child_seed_nav_submenus()
	{
		if ( get_option( 'editor_child_nav_submenus_seeded' ) )
		{
			return;
		}

		$menu = wp_get_nav_menu_object( 'RSP Primary Navigation' );

		if ( ! $menu )
		{
			return;
		}

		$items = wp_get_nav_menu_items( $menu->term_id );

		if ( ! $items )
		{
			return;
		}

		$parent_ids   = array();
		$existing     = array();

		foreach ( $items as $item )
		{
			// Decode entities before matching — see the note in
			// editor_child_sync_primary_menu_urls(); titles containing "&"
			// come back as "&amp;" and never match a raw comparison.
			$title = html_entity_decode( $item->title, ENT_QUOTES, 'UTF-8' );

			if ( in_array( $title, array( 'by Position', 'by Draft Class' ), true ) )
			{
				$parent_ids[ $title ] = $item->ID;
			}

			$existing[ $item->menu_item_parent . '|' . $title ] = true;
		}

		$groups = array(
			'by Position'    => array(
				'taxonomy' => 'rsp_position',
				'order'    => array( 'QB', 'RB', 'WR', 'TE' ),
			),
			'by Draft Class' => array(
				'taxonomy' => 'rsp_draft_class',
				'order'    => array(),
			),
		);

		foreach ( $groups as $parent_title => $group )
		{
			if ( empty( $parent_ids[ $parent_title ] ) )
			{
				continue;
			}

			$parent_id = $parent_ids[ $parent_title ];

			$terms = get_terms( array(
				'taxonomy'   => $group['taxonomy'],
				'hide_empty' => false,
			) );

			if ( is_wp_error( $terms ) || empty( $terms ) )
			{
				continue;
			}

			// Explicit order where the design implies one (positions),
			// otherwise newest-first (draft class years).
			if ( ! empty( $group['order'] ) )
			{
				$ordered = array();

				foreach ( $group['order'] as $name )
				{
					foreach ( $terms as $term )
					{
						if ( $term->name === $name )
						{
							$ordered[] = $term;
						}
					}
				}

				$terms = $ordered ? $ordered : $terms;
			}
			else
			{
				usort( $terms, function ( $a, $b ) {
					return strcmp( $b->name, $a->name );
				} );
			}

			foreach ( $terms as $term )
			{
				if ( isset( $existing[ $parent_id . '|' . $term->name ] ) )
				{
					continue;
				}

				$term_link = get_term_link( $term );

				if ( is_wp_error( $term_link ) )
				{
					continue;
				}

				wp_update_nav_menu_item( $menu->term_id, 0, array(
					'menu-item-title'     => $term->name,
					'menu-item-url'       => $term_link,
					'menu-item-parent-id' => $parent_id,
					'menu-item-status'    => 'publish',
				) );
			}
		}

		update_option( 'editor_child_nav_submenus_seeded', 1 );
	}

	add_action('init', 'editor_child_seed_nav_submenus', 15);


	/**
	 * One-time creation of the "Buy the RSP" page, assigned the
	 * template-buy-the-rsp.php template, so the header CTA and the
	 * "RSP Draft Guide" nav item have a real destination to sync to
	 * (see editor_child_sync_primary_menu_urls()) without a manual
	 * wp-admin step.
	 */
	function editor_child_seed_buy_rsp_page()
	{
		if ( get_option( 'editor_child_buy_rsp_page_seeded' ) )
		{
			return;
		}

		$existing = get_page_by_path( 'buy-the-rsp' );

		if ( ! $existing )
		{
			$page_id = wp_insert_post( array(
				'post_title'  => 'Buy the RSP',
				'post_name'   => 'buy-the-rsp',
				'post_status' => 'publish',
				'post_type'   => 'page',
			) );

			if ( $page_id && ! is_wp_error( $page_id ) )
			{
				update_post_meta( $page_id, '_wp_page_template', 'template-buy-the-rsp.php' );
			}
		}

		update_option( 'editor_child_buy_rsp_page_seeded', 1 );
	}

	add_action('init', 'editor_child_seed_buy_rsp_page', 9);


	/**
	 * One-time wiring of the "About" page to template-about.php. The test
	 * site already has a real "About" page at /about/ (genuine bio copy,
	 * not placeholder content) — assign the template to that existing
	 * page rather than creating a duplicate. Only creates a new page if
	 * /about/ doesn't exist at all.
	 */
	function editor_child_seed_about_page()
	{
		if ( get_option( 'editor_child_about_page_seeded' ) )
		{
			return;
		}

		$existing = get_page_by_path( 'about' );

		if ( $existing )
		{
			update_post_meta( $existing->ID, '_wp_page_template', 'template-about.php' );
		}
		else
		{
			$page_id = wp_insert_post( array(
				'post_title'  => 'About',
				'post_name'   => 'about',
				'post_status' => 'publish',
				'post_type'   => 'page',
			) );

			if ( $page_id && ! is_wp_error( $page_id ) )
			{
				update_post_meta( $page_id, '_wp_page_template', 'template-about.php' );
			}
		}

		update_option( 'editor_child_about_page_seeded', 1 );
	}

	add_action('init', 'editor_child_seed_about_page', 9);


	/**
	 * One-time creation of the two nav destinations that have no design
	 * file yet: "Ranking & Projections" and "Player Evaluation". Both use
	 * the default page.php template and are seeded with real, editable
	 * post_content rather than a bespoke template — the copy is
	 * placeholder, so Matt can rewrite it in the editor without a code
	 * change, and no invented visual language gets baked into a template.
	 *
	 * Replace this content (or drop in a proper template) once designs
	 * for these two screens exist.
	 */
	function editor_child_seed_placeholder_nav_pages()
	{
		if ( get_option( 'editor_child_placeholder_pages_seeded' ) )
		{
			return;
		}

		$pages = array(
			'ranking-and-projections' => array(
				'title'   => 'Ranking & Projections',
				'content' => "<p>Dynasty rookie rankings and two-year statistical projections for every notable skill-position prospect, built on the same film-based process as the Rookie Scouting Portfolio.</p>\n\n<p>The Dynasty Rankings &amp; Projections package is available alongside the RSP Draft Package — see <a href=\"https://mattwaldman.com\">mattwaldman.com</a> for current pricing and release dates.</p>\n\n<p><em>This page is a placeholder — content and layout still to be designed.</em></p>",
			),
			'player-evaluation'       => array(
				'title'   => 'Player Evaluation',
				'content' => "<p>Every prospect and NFL player Matt studies is graded through one consistent, film-based framework — the same process behind the Rookie Scouting Portfolio since 2006.</p>\n\n<p>Browse the evaluations by position, by draft class, or head straight to the film.</p>\n\n<p><em>This page is a placeholder — content and layout still to be designed.</em></p>",
			),
		);

		foreach ( $pages as $slug => $page )
		{
			if ( get_page_by_path( $slug ) )
			{
				continue;
			}

			wp_insert_post( array(
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_content' => $page['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
			) );
		}

		update_option( 'editor_child_placeholder_pages_seeded', 1 );
	}

	add_action('init', 'editor_child_seed_placeholder_nav_pages', 9);


	/**
	 * Resolves the Buy the RSP page URL for the header's own CTA button,
	 * which isn't part of wp_nav_menu() so it doesn't get touched by
	 * editor_child_sync_primary_menu_urls(). Falls back to '#' if the
	 * page hasn't been seeded/published yet.
	 */
	function editor_child_get_buy_rsp_url()
	{
		$page = get_page_by_path( 'buy-the-rsp' );

		return $page ? get_permalink( $page ) : '#';
	}


	/**
	 * Resolves the About page URL for the header's quickbar tagline link,
	 * which (like the CTA above) isn't part of wp_nav_menu().
	 */
	function editor_child_get_about_url()
	{
		$page = get_page_by_path( 'about' );

		return $page ? get_permalink( $page ) : '#';
	}


	/**
	 * Resolves where the front page's "Explore the Film Room" / "View all"
	 * links should point. The design sends both to the Resources page,
	 * which isn't built yet — until it is, fall back to the Film Room
	 * category archive so the links still go somewhere real.
	 */
	function editor_child_get_resources_url()
	{
		$page = get_page_by_path( 'resources' );

		if ( $page )
		{
			return get_permalink( $page );
		}

		$film_room = get_category_by_slug( 'film-room' );

		return $film_room ? get_category_link( $film_room ) : home_url( '/' );
	}


	/**
	 * Dark page-frame override for the designs that use a dark page
	 * column instead of the site's default near-white one: the front
	 * page, Buy the RSP, and About. Scoped via body class rather than
	 * changing the shared #page background.
	 *
	 * is_front_page() rather than is_page_template() for the front page —
	 * front-page.php isn't a selectable page template.
	 */
	function editor_child_body_classes( $classes )
	{
		if ( is_front_page() || is_page_template( array( 'template-buy-the-rsp.php', 'template-about.php' ) ) )
		{
			$classes[] = 'rsp-page-dark-frame';
		}

		return $classes;
	}

	add_filter('body_class', 'editor_child_body_classes');


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
	 * destination is resolvable for them.
	 *
	 * Deliberately NOT in the map: "by Position" and "by Draft Class",
	 * which are pure dropdown parents for their taxonomy-term children
	 * (see editor_child_seed_nav_submenus()), and "Resources", which
	 * stays put until the Resources page template is built.
	 */
	function editor_child_sync_primary_menu_urls()
	{
		$menu = wp_get_nav_menu_object( 'RSP Primary Navigation' );

		if ( ! $menu )
		{
			return;
		}

		$film_room     = get_category_by_slug( 'film-room' );
		$articles      = get_category_by_slug( 'articles' );
		$podcasts      = get_category_by_slug( 'podcasts' );
		$buy_rsp_page  = get_page_by_path( 'buy-the-rsp' );
		$about_page    = get_page_by_path( 'about' );
		$ranking_page  = get_page_by_path( 'ranking-and-projections' );
		$player_page   = get_page_by_path( 'player-evaluation' );

		$url_map = array_filter( array(
			'Film Room'             => $film_room ? get_category_link( $film_room ) : '',
			'Articles'              => $articles ? get_category_link( $articles ) : '',
			'Podcasts'              => $podcasts ? get_category_link( $podcasts ) : '',
			'RSP Draft Guide'       => $buy_rsp_page ? get_permalink( $buy_rsp_page ) : '',
			'About Us'              => $about_page ? get_permalink( $about_page ) : '',
			'Ranking & Projections' => $ranking_page ? get_permalink( $ranking_page ) : '',
			'Player Evaluation'     => $player_page ? get_permalink( $player_page ) : '',
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
			// Menu item titles come back HTML-encoded, so "Ranking &
			// Projections" arrives as "Ranking &amp; Projections" and a
			// raw-string comparison silently never matches. Decode before
			// looking the title up.
			$title = html_entity_decode( $item->title, ENT_QUOTES, 'UTF-8' );

			if ( ! isset( $url_map[ $title ] ) || $item->url === $url_map[ $title ] )
			{
				continue;
			}

			wp_update_nav_menu_item( $menu->term_id, $item->ID, array(
				'menu-item-title'     => $item->title,
				'menu-item-url'       => $url_map[ $title ],
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