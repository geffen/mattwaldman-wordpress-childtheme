<?php

	/**
	 * Members-only access layer (RSP Draft Guide, Ranking & Projections).
	 * Kept in its own file rather than inline here — this file is already
	 * long, and the access check is the one piece of the theme that gets
	 * swapped out when aMember can finally be integrated properly. Read
	 * that file's header before changing how access is decided.
	 */
	require_once get_stylesheet_directory() . '/inc/members.php';


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
	 * TEST-SITE-ONLY content importer. Pulls recent posts from the live
	 * RSP site's public REST API and recreates them locally so the
	 * Podcasts / Film Room pages and the Resources page render against
	 * realistic content instead of empty categories — which is what
	 * production will do on its own, since those categories are already
	 * populated there.
	 *
	 * Three guards, because this writes posts:
	 *  - hard-gated to the notmattwaldman.com test host, so it can never
	 *    run on production (where it would duplicate the site's own posts
	 *    back into itself);
	 *  - one-time, via the editor_child_content_imported_v2 option;
	 *  - runs on admin requests only, so the remote HTTP calls and image
	 *    downloads can never block a front-end page load.
	 *
	 * Featured images are sideloaded into the media library. The same
	 * artwork is reused across many RSP posts, so downloaded URLs are
	 * cached per run and the attachment is shared rather than fetched
	 * dozens of times.
	 *
	 * Imported posts are marked with a _rsp_imported_from meta value so
	 * they can be found and bulk-deleted later. DELETE this function
	 * before production.
	 */
	function editor_child_import_demo_content()
	{
		if ( ! is_admin() || wp_doing_ajax() )
		{
			return;
		}

		if ( get_option( 'editor_child_content_imported_v2' ) )
		{
			return;
		}

		if ( false === strpos( home_url(), 'notmattwaldman.com' ) )
		{
			return;
		}

		// media_sideload_image() and its dependencies aren't loaded on
		// every admin request.
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Remote image URL => local attachment ID, so shared artwork is
		// only downloaded once per run.
		$image_cache = array();

		$attach_image = function ( $image_url, $post_id ) use ( &$image_cache ) {
			if ( ! $image_url || has_post_thumbnail( $post_id ) )
			{
				return;
			}

			if ( isset( $image_cache[ $image_url ] ) )
			{
				set_post_thumbnail( $post_id, $image_cache[ $image_url ] );
				return;
			}

			$attachment_id = media_sideload_image( $image_url, $post_id, null, 'id' );

			if ( is_wp_error( $attachment_id ) )
			{
				return;
			}

			$image_cache[ $image_url ] = $attachment_id;
			set_post_thumbnail( $post_id, $attachment_id );
		};

		// Remote category ID => local category slug.
		$map = array(
			2060    => 'podcasts',
			1466246 => 'film-room',
		);

		foreach ( $map as $remote_cat => $local_slug )
		{
			$local = get_category_by_slug( $local_slug );

			if ( ! $local )
			{
				continue;
			}

			// _embed rather than _fields: it returns the featured image
			// URL in the same request, avoiding one extra HTTP call per
			// post (which would risk a timeout across 40 posts).
			$response = wp_remote_get(
				add_query_arg(
					array(
						'categories' => $remote_cat,
						'per_page'   => 20,
						'orderby'    => 'date',
						'order'      => 'desc',
						'_embed'     => 'wp:featuredmedia',
					),
					'https://mattwaldmanrsp.com/wp-json/wp/v2/posts'
				),
				array( 'timeout' => 30 )
			);

			if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) )
			{
				continue;
			}

			$posts = json_decode( wp_remote_retrieve_body( $response ), true );

			if ( ! is_array( $posts ) )
			{
				continue;
			}

			foreach ( $posts as $remote_post )
			{
				$title = isset( $remote_post['title']['rendered'] ) ? $remote_post['title']['rendered'] : '';

				if ( ! $title )
				{
					continue;
				}

				$source_link = isset( $remote_post['link'] ) ? esc_url_raw( $remote_post['link'] ) : '';
				$image_url   = isset( $remote_post['_embedded']['wp:featuredmedia'][0]['source_url'] )
					? esc_url_raw( $remote_post['_embedded']['wp:featuredmedia'][0]['source_url'] )
					: '';

				// Dedupe on the source URL rather than the title:
				// get_page_by_title() is deprecated (and this install is on
				// WP 7.x), and the source URL is a stabler key anyway.
				// An earlier run imported these without images, so an
				// existing post still gets its thumbnail backfilled here
				// rather than being skipped outright.
				if ( $source_link )
				{
					$already = get_posts( array(
						'post_type'           => 'post',
						'post_status'         => 'any',
						'posts_per_page'      => 1,
						'fields'              => 'ids',
						'ignore_sticky_posts' => true,
						'no_found_rows'       => true,
						'meta_key'            => '_rsp_imported_from',
						'meta_value'          => $source_link,
					) );

					if ( ! empty( $already ) )
					{
						wp_set_post_categories( $already[0], array( (int) $local->term_id ), true );
						$attach_image( $image_url, $already[0] );
						continue;
					}
				}

				$new_id = wp_insert_post( array(
					'post_title'    => wp_strip_all_tags( $title ),
					'post_content'  => isset( $remote_post['content']['rendered'] ) ? $remote_post['content']['rendered'] : '',
					'post_excerpt'  => isset( $remote_post['excerpt']['rendered'] ) ? wp_strip_all_tags( $remote_post['excerpt']['rendered'] ) : '',
					'post_date'     => isset( $remote_post['date'] ) ? $remote_post['date'] : current_time( 'mysql' ),
					'post_status'   => 'publish',
					'post_type'     => 'post',
					'post_category' => array( (int) $local->term_id ),
				) );

				if ( $new_id && ! is_wp_error( $new_id ) )
				{
					update_post_meta( $new_id, '_rsp_imported_from', $source_link );
					$attach_image( $image_url, $new_id );
				}
			}
		}

		update_option( 'editor_child_content_imported_v2', 1 );
	}

	add_action('admin_init', 'editor_child_import_demo_content');


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
	 * One-time setup of the two members-only areas defined in
	 * inc/members.php: their gated post categories, the /rsp-draft-guide/
	 * page, and the Member Area template assignment on both pages.
	 *
	 * Option-gated (like every other seeder here) so it can never
	 * re-fight a manual change in wp-admin — assigning _wp_page_template
	 * on every init would silently revert a template switch made in the
	 * page editor, which is exactly the trap
	 * editor_child_sync_primary_menu_urls() fell into.
	 *
	 * /ranking-and-projections/ already exists from the placeholder
	 * seeder above; its copy is only rewritten if it still holds that
	 * seeded placeholder text, so Matt's own edits survive.
	 *
	 * Bump the option name to re-run after changing this function.
	 */
	function editor_child_seed_member_areas()
	{
		if ( get_option( 'editor_child_member_areas_seeded_v1' ) )
		{
			return;
		}

		$pitch = array(
			'draft-guide' => "<p>The Rookie Scouting Portfolio is a film-based evaluation of every notable rookie skill-position prospect &mdash; quarterbacks, running backs, wide receivers, and tight ends &mdash; graded through the same checklist Matt has used since 2006.</p>\n\n<p>Members get the full publication the day it drops on April 1, plus the post-draft update in May, and every in-season addition to this area.</p>",
			'rankings'    => "<p>Dynasty rookie rankings and two-year statistical projections for every notable skill-position prospect, built on the same film-based process as the Rookie Scouting Portfolio.</p>\n\n<p>Members get each ranking refresh through the post-draft cycle, with the reasoning behind every move.</p>",
		);

		foreach ( editor_child_member_areas() as $key => $area )
		{
			/* The category that holds this area's gated posts. */
			if ( ! term_exists( $area['category'], 'category' ) )
			{
				wp_insert_term(
					$area['label'],
					'category',
					array( 'slug' => $area['category'] )
				);
			}

			$page    = get_page_by_path( $area['page_slug'] );
			$content = isset( $pitch[ $key ] ) ? $pitch[ $key ] : '';

			if ( ! $page )
			{
				$page_id = wp_insert_post( array(
					'post_title'   => $area['label'],
					'post_name'    => $area['page_slug'],
					'post_content' => $content,
					'post_status'  => 'publish',
					'post_type'    => 'page',
				) );

				if ( is_wp_error( $page_id ) || ! $page_id )
				{
					continue;
				}
			}
			else
			{
				$page_id = $page->ID;

				/*
				 * Only overwrite copy that's still the untouched
				 * placeholder from editor_child_seed_placeholder_nav_pages().
				 */
				if ( $content && false !== strpos( $page->post_content, 'This page is a placeholder' ) )
				{
					wp_update_post( array(
						'ID'           => $page_id,
						'post_content' => $content,
					) );
				}
			}

			update_post_meta( $page_id, '_wp_page_template', 'template-member-area.php' );
		}

		update_option( 'editor_child_member_areas_seeded_v1', 1 );
	}

	add_action('init', 'editor_child_seed_member_areas', 10);


	/**
	 * One-time creation of the two taxonomy landing pages that hang off
	 * Player Evaluation: /player-evaluation/by-position/ and
	 * /player-evaluation/by-draft-class/.
	 *
	 * They exist because WordPress builds no root archive for a custom
	 * taxonomy — /position/ and /draft-class/ both 404 — so those nav
	 * items had nowhere to point. Both use template-term-index.php,
	 * which lists the taxonomy's terms and links through to the per-term
	 * archives that archive.php already renders.
	 *
	 * Created as children of /player-evaluation/ (post_parent), so the
	 * URLs nest and breadcrumbs read correctly. Skipped entirely if that
	 * parent page is missing rather than creating them at the root.
	 *
	 * Option-gated like the other seeders — it must never re-fight a
	 * manual edit in wp-admin. Bump the option name to re-run.
	 */
	function editor_child_seed_taxonomy_landing_pages()
	{
		if ( get_option( 'editor_child_taxonomy_pages_seeded_v1' ) )
		{
			return;
		}

		$parent = get_page_by_path( 'player-evaluation' );

		if ( ! $parent )
		{
			return;
		}

		$pages = array(
			'by-position'    => array(
				'title'   => 'by Position',
				'content' => "<p>Quarterbacks, running backs, wide receivers, and tight ends are each graded against a position-specific checklist — the same one behind every edition of the Rookie Scouting Portfolio since 2006.</p>\n\n<p>Pick a position to see every evaluation filed under it.</p>",
			),
			'by-draft-class' => array(
				'title'   => 'by Draft Class',
				'content' => "<p>Every prospect Matt studies is filed by the year he entered the league, so a full class can be read the way it was scouted — and re-read years later against what actually happened.</p>\n\n<p>Pick a class to see its evaluations.</p>",
			),
		);

		foreach ( $pages as $slug => $page )
		{
			$existing = get_page_by_path( 'player-evaluation/' . $slug );

			if ( $existing )
			{
				$page_id = $existing->ID;
			}
			else
			{
				$page_id = wp_insert_post( array(
					'post_title'   => $page['title'],
					'post_name'    => $slug,
					'post_content' => $page['content'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_parent'  => $parent->ID,
				) );

				if ( is_wp_error( $page_id ) || ! $page_id )
				{
					continue;
				}
			}

			update_post_meta( $page_id, '_wp_page_template', 'template-term-index.php' );
		}

		update_option( 'editor_child_taxonomy_pages_seeded_v1', 1 );
	}

	add_action('init', 'editor_child_seed_taxonomy_landing_pages', 11);


	/**
	 * Resolves the Buy the RSP page URL for the header's own CTA button,
	 * which isn't part of wp_nav_menu() so it doesn't get touched by
	 * editor_child_sync_primary_menu_urls().
	 *
	 * Falls back to aMember's signup page rather than '#'. This used to
	 * return '#', and when the local /buy-the-rsp/ page was renamed in
	 * wp-admin the site's most important CTA silently became a dead link
	 * in the header of EVERY page — with nothing in the markup to show
	 * anything was wrong. A CTA that reaches the real store is always
	 * better than one that goes nowhere.
	 */
	function editor_child_get_buy_rsp_url()
	{
		$page = get_page_by_path( 'buy-the-rsp' );

		return $page ? get_permalink( $page ) : editor_child_amember_signup_url();
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
	 * Resolves the Member Login page URL for the quickbar link, which
	 * (like the CTA and tagline) isn't part of wp_nav_menu(). Falls back
	 * to WordPress's own login screen if no page has been created yet, so
	 * the link is never a dead '#'.
	 */
	function editor_child_get_member_login_url()
	{
		$page = get_page_by_path( 'member-login' );

		return $page ? get_permalink( $page ) : wp_login_url();
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
		$dark_templates = array(
			'template-buy-the-rsp.php',
			'template-about.php',
			'template-member-login.php',
			'template-member-area.php',
		);

		if ( is_front_page() || is_page_template( $dark_templates ) )
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

	/*
	 * DISABLED — the menu is now managed by hand in Appearance > Menus.
	 *
	 * This hook ran on every request and rewrote menu item URLs by
	 * matching their titles, which would silently revert any edit made in
	 * wp-admin to an item named Film Room / Articles / Podcasts / RSP
	 * Draft Guide / About Us / Ranking & Projections / Player Evaluation.
	 * The CMS is the source of truth for the menu now, so this stays off.
	 *
	 * The function itself is kept for reference only. Don't re-enable it
	 * without first confirming nobody is maintaining the menu by hand.
	 */
	// add_action('init', 'editor_child_sync_primary_menu_urls', 20);


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