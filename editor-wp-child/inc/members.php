<?php
/**
 * Members-only access layer.
 * =========================================================================
 *
 * WHY THIS FILE EXISTS (read before changing the access check)
 *
 * Matt sells the RSP through **aMember Pro**, which is live at
 * https://www.mattwaldman.com/amember/ . aMember is NOT a WordPress
 * plugin — it's a standalone PHP application, and its official WordPress
 * integration requires aMember to sit in a subdirectory of the SAME
 * server/filesystem as WordPress so the bridge can require
 * `amember/library/Am/Lite.php` and share a session cookie.
 *
 * That isn't the case here. As of this writing:
 *
 *   mattwaldman.com      35.209.31.200   <- aMember Pro lives here
 *   mattwaldmanrsp.com   192.0.78.x      <- the WP site (WordPress.com)
 *   notmattwaldman.com   74.208.236.15   <- our IONOS test site
 *
 * Three different hosts. So the aMember bridge cannot work as-is, and
 * aMember's REST module also appears to be switched off (its /api/
 * endpoints 404). Whether real single sign-on becomes possible is a
 * hosting decision that lives outside this repo.
 *
 * So: every access decision in the theme goes through ONE function,
 * editor_child_user_has_access(), which is backed today by ordinary
 * WordPress roles/capabilities and ends in a filter. When aMember does
 * land on the same host, hooking that one filter switches the whole site
 * over without touching a single template:
 *
 *   add_filter( 'editor_child_user_has_access', function ( $has, $area ) {
 *       require_once '/path/to/amember/library/Am/Lite.php';
 *       $lite = Am_Lite::getInstance();
 *       return $lite->isLoggedIn() && $lite->haveSubscriptions( $product_ids_for( $area ) );
 *   }, 10, 2 );
 *
 * Do not scatter capability checks through templates — call the function.
 */

if ( ! defined( 'ABSPATH' ) )
{
	exit;
}


/**
 * The registry of members-only areas. Everything else — templates, the
 * login page, the seeders — reads this rather than hardcoding slugs, so
 * adding a third area is a one-place change.
 *
 * page_slug     the WordPress page that fronts the area
 * category      the post category holding that area's gated posts
 * capability    the WP capability that unlocks it (one per area, so a
 *               future aMember product maps 1:1 onto a capability)
 *
 * Labels and summaries are stored as PLAIN TEXT — "Ranking &
 * Projections", not "Ranking &amp; Projections". Escape at output with
 * esc_html(). Storing pre-encoded entities here means every consumer has
 * to remember to decode, and the one that forgets prints "&amp;" on the
 * page or writes it into a term name (see gotcha 8 in CLAUDE.md for the
 * nav-menu version of the same trap).
 */
function editor_child_member_areas()
{
	$areas = array(
		'draft-guide' => array(
			'label'      => 'RSP Draft Guide',
			'page_slug'  => 'rsp-draft-guide',
			'category'   => 'rsp-draft-guide',
			'capability' => 'rsp_access_draft_guide',
			'kicker'     => 'Members',
			'summary'    => 'The full Rookie Scouting Portfolio draft publication — every scouting report, ranking, and cross-check from this year’s class.',
		),
		'rankings'    => array(
			'label'      => 'Ranking & Projections',
			'page_slug'  => 'ranking-and-projections',
			'category'   => 'rsp-rankings',
			'capability' => 'rsp_access_rankings',
			'kicker'     => 'Members',
			'summary'    => 'Dynasty rookie rankings and two-year statistical projections, updated through the post-draft cycle.',
		),
	);

	return apply_filters( 'editor_child_member_areas', $areas );
}


/**
 * Look up a single area by its key. Returns false for an unknown key so
 * callers can fail closed.
 */
function editor_child_get_member_area( $area_key )
{
	$areas = editor_child_member_areas();

	return isset( $areas[ $area_key ] ) ? $areas[ $area_key ] : false;
}


/**
 * Resolve which members-only area a page represents.
 *
 * Matched first by an optional "rsp_member_area" custom field (so a page
 * can be renamed without breaking), otherwise by the page's own slug —
 * the same convention template-category-listing.php uses to find its
 * category. Returns false when the page isn't a member area at all.
 */
function editor_child_detect_member_area( $post_id = null )
{
	$post_id = $post_id ? $post_id : get_the_ID();

	if ( ! $post_id )
	{
		return false;
	}

	$areas    = editor_child_member_areas();
	$override = trim( (string) get_post_meta( $post_id, 'rsp_member_area', true ) );

	if ( $override && isset( $areas[ $override ] ) )
	{
		return $override;
	}

	$page_slug = get_post_field( 'post_name', $post_id );

	foreach ( $areas as $key => $area )
	{
		if ( $area['page_slug'] === $page_slug )
		{
			return $key;
		}
	}

	return false;
}


/**
 * THE access check. Everything gates on this.
 *
 * Fails closed: an unknown area key, or a logged-out visitor, gets false.
 * Administrators always pass so Matt can proof the member view without a
 * second account.
 *
 * @param string $area_key Key from editor_child_member_areas().
 * @param int    $user_id  Defaults to the current user.
 * @return bool
 */
function editor_child_user_has_access( $area_key, $user_id = 0 )
{
	$user_id = $user_id ? (int) $user_id : get_current_user_id();
	$area    = editor_child_get_member_area( $area_key );
	$has     = false;

	if ( $area && $user_id )
	{
		/*
		 * Administrators (and anyone who can edit others' posts, i.e.
		 * editors) see member content so the page can be proofed.
		 */
		if ( user_can( $user_id, 'manage_options' ) || user_can( $user_id, 'edit_others_posts' ) )
		{
			$has = true;
		}
		elseif ( user_can( $user_id, $area['capability'] ) )
		{
			$has = true;
		}
	}

	/**
	 * The swap point for a real membership backend (see the file header).
	 *
	 * @param bool   $has      Whether access is granted.
	 * @param string $area_key Area being checked.
	 * @param int    $user_id  User being checked (0 when logged out).
	 */
	return (bool) apply_filters( 'editor_child_user_has_access', $has, $area_key, $user_id );
}


/**
 * Every area key the given user can reach. Used by the login page to
 * show a signed-in member where to go next.
 */
function editor_child_accessible_member_areas( $user_id = 0 )
{
	$accessible = array();

	foreach ( editor_child_member_areas() as $key => $area )
	{
		if ( editor_child_user_has_access( $key, $user_id ) )
		{
			$accessible[ $key ] = $area;
		}
	}

	return $accessible;
}


/**
 * One-time registration of the "RSP Member" role plus the per-area
 * capabilities, mirrored onto administrator so admins hold them
 * explicitly rather than only via the manage_options shortcut above.
 *
 * Option-gated like the other seeders in functions.php: add_role() is a
 * no-op once the role exists, but the option also stops us re-granting
 * capabilities that someone may have deliberately removed.
 *
 * Bump the option name if the capability list ever changes.
 */
function editor_child_register_member_role()
{
	if ( get_option( 'editor_child_member_role_v1' ) )
	{
		return;
	}

	$caps = array();

	foreach ( editor_child_member_areas() as $area )
	{
		$caps[ $area['capability'] ] = true;
	}

	add_role( 'rsp_member', 'RSP Member', array_merge( array( 'read' => true ), $caps ) );

	$admin = get_role( 'administrator' );

	if ( $admin )
	{
		foreach ( array_keys( $caps ) as $cap )
		{
			$admin->add_cap( $cap );
		}
	}

	update_option( 'editor_child_member_role_v1', 1 );
}

add_action( 'init', 'editor_child_register_member_role', 8 );


/**
 * Resolves what the header's gold CTA should be for the current user.
 *
 * Returns array( 'show' => bool, 'label' => string, 'url' => string ).
 *
 * Default behaviour SWAPS rather than hides: a member who already owns
 * everything gets "My Account" pointing at their first area, instead of
 * being sold something they've bought. Swapping keeps the header's
 * footprint identical — the breakpoint ladder in style.css is measured
 * against logo + nav + CTA widths (see gotcha 10 in CLAUDE.md), so an
 * empty slot is a layout change, whereas a shorter label is not.
 *
 * A member who owns only SOME areas keeps the Buy CTA — there's still
 * something to sell them.
 *
 * Staff (anyone who can edit posts) always see the real Buy CTA. They
 * pass every access check via the proofing bypass in
 * editor_child_user_has_access(), so without this Matt would never see
 * his own primary CTA while browsing the site.
 *
 * To hide the CTA outright instead of swapping it:
 *
 *   add_filter( 'editor_child_buy_cta', function ( $cta, $owns_all ) {
 *       if ( $owns_all ) { $cta['show'] = false; }
 *       return $cta;
 *   }, 10, 2 );
 */
function editor_child_buy_cta()
{
	$areas      = editor_child_member_areas();
	$accessible = editor_child_accessible_member_areas();
	$owns_all   = $areas && count( $accessible ) === count( $areas ) && ! current_user_can( 'edit_posts' );

	$cta = array(
		'show'  => true,
		'label' => __( 'Buy the RSP', 'editor-child' ),
		'url'   => editor_child_get_buy_rsp_url(),
	);

	if ( $owns_all )
	{
		$first = reset( $accessible );
		$page  = $first ? get_page_by_path( $first['page_slug'] ) : false;

		$cta['label'] = __( 'My Account', 'editor-child' );
		$cta['url']   = $page ? get_permalink( $page ) : editor_child_get_member_login_url();
	}

	/**
	 * @param array $cta       show / label / url.
	 * @param bool  $owns_all  Whether this user already owns every area.
	 */
	return apply_filters( 'editor_child_buy_cta', $cta, $owns_all );
}


/**
 * Hide the WordPress admin bar from anyone who can't actually edit
 * content.
 *
 * Members are customers, not staff. The admin bar advertises the WP
 * backend, breaks the header design by pushing the sticky nav down 32px,
 * and offers them nothing but a profile link. aMember's own integration
 * guidance says the same: members should never be looking at WordPress
 * admin chrome.
 *
 * Keyed on edit_posts rather than on the member capabilities, so it also
 * covers plain Subscribers and any future customer-ish role — and so
 * Matt, admins, editors, and contributors keep the bar.
 */
function editor_child_hide_admin_bar( $show )
{
	return current_user_can( 'edit_posts' ) ? $show : false;
}

add_filter( 'show_admin_bar', 'editor_child_hide_admin_bar' );


/**
 * Base URL of Matt's aMember install. Filterable so it can be pointed
 * elsewhere (or at a same-host install) without editing the theme.
 */
function editor_child_amember_base_url()
{
	return apply_filters( 'editor_child_amember_base_url', 'https://www.mattwaldman.com/amember' );
}


/** Where a non-member goes to actually buy access. */
function editor_child_amember_signup_url()
{
	return editor_child_amember_base_url() . '/signup';
}


/**
 * The Member Login page URL, optionally carrying a redirect_to so a
 * locked area can bounce a visitor to the login form and back again.
 *
 * $redirect is passed through as a plain query arg; it is validated
 * against the site's own host on the receiving end (see
 * template-member-login.php), not here — never trust it at read time.
 */
function editor_child_member_login_url( $redirect = '' )
{
	$url = editor_child_get_member_login_url();

	if ( $redirect )
	{
		$url = add_query_arg( 'redirect_to', urlencode( $redirect ), $url );
	}

	return $url;
}


/**
 * Parse the "rsp_downloads" custom field into a list of download rows.
 *
 * Deliberately a plain post-meta text field rather than a plugin or a
 * custom post type — Matt edits it in the wp-admin Custom Fields box,
 * one download per line:
 *
 *     RSP 2026 Publication|https://www.mattwaldman.com/amember/member|April release
 *     Post-Draft Update|https://www.mattwaldman.com/amember/member
 *
 * Fields are Label|URL|Note, note optional. Blank lines and rows without
 * a URL are skipped, so a half-finished field degrades quietly instead
 * of printing broken links. Same spirit as the rsp_duration /
 * rsp_episode fields template-resources.php reads.
 */
function editor_child_get_member_downloads( $post_id = null )
{
	$post_id = $post_id ? $post_id : get_the_ID();
	$raw     = $post_id ? (string) get_post_meta( $post_id, 'rsp_downloads', true ) : '';
	$rows    = array();

	if ( '' === trim( $raw ) )
	{
		return $rows;
	}

	foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line )
	{
		$line = trim( $line );

		if ( '' === $line )
		{
			continue;
		}

		$parts = array_map( 'trim', explode( '|', $line ) );
		$label = isset( $parts[0] ) ? $parts[0] : '';
		$url   = isset( $parts[1] ) ? $parts[1] : '';
		$note  = isset( $parts[2] ) ? $parts[2] : '';

		if ( '' === $label || '' === $url )
		{
			continue;
		}

		$rows[] = array(
			'label' => $label,
			'url'   => $url,
			'note'  => $note,
		);
	}

	return $rows;
}


/**
 * The gated posts for an area, newest first.
 *
 * 'ignore_sticky_posts' is mandatory here: a secondary WP_Query with no
 * page-type query vars is treated as is_home internally, and WordPress
 * then splices sticky posts in ON TOP of posts_per_page. The test site
 * has sticky posts, so without this the count comes back wrong.
 */
function editor_child_member_area_query( $area_key, $per_page = 12, $paged = 1 )
{
	$area     = editor_child_get_member_area( $area_key );
	$category = $area ? get_category_by_slug( $area['category'] ) : false;

	if ( ! $category || is_wp_error( $category ) )
	{
		return new WP_Query( array( 'post__in' => array( 0 ) ) );
	}

	return new WP_Query( array(
		'cat'                     => $category->term_id,
		'posts_per_page'          => $per_page,
		'paged'                   => max( 1, (int) $paged ),
		'ignore_sticky_posts'     => true,
		/*
		 * Opt this query out of editor_child_hide_gated_posts() — it is
		 * the one query that is SUPPOSED to return gated posts, and it
		 * only ever runs after an access check has already passed (or,
		 * for the locked-state teaser, when only titles are rendered).
		 */
		'editor_child_bypass_gate' => true,
	) );
}


/* -------------------------------------------------------------------------
 * Protecting the gated POSTS themselves.
 *
 * Listing gated posts only on the member-area page isn't protection —
 * each post still has its own public permalink, and would otherwise turn
 * up in archives, search, feeds and the REST API. The three guards below
 * close those routes for anyone without access to the owning area.
 * ---------------------------------------------------------------------- */


/**
 * Map of gated category term ID => area key, built once per request.
 */
function editor_child_gated_category_map()
{
	static $map = null;

	if ( null !== $map )
	{
		return $map;
	}

	$map = array();

	foreach ( editor_child_member_areas() as $key => $area )
	{
		$category = get_category_by_slug( $area['category'] );

		if ( $category && ! is_wp_error( $category ) )
		{
			$map[ (int) $category->term_id ] = $key;
		}
	}

	return $map;
}


/**
 * The gated category IDs the given user may NOT see.
 */
function editor_child_forbidden_category_ids( $user_id = 0 )
{
	$forbidden = array();

	foreach ( editor_child_gated_category_map() as $term_id => $area_key )
	{
		if ( ! editor_child_user_has_access( $area_key, $user_id ) )
		{
			$forbidden[] = $term_id;
		}
	}

	return $forbidden;
}


/**
 * Which area (if any) a post belongs to, by its categories.
 */
function editor_child_post_member_area( $post_id )
{
	$map = editor_child_gated_category_map();

	if ( ! $map )
	{
		return false;
	}

	$term_ids = wp_get_post_categories( $post_id );

	foreach ( $term_ids as $term_id )
	{
		if ( isset( $map[ (int) $term_id ] ) )
		{
			return $map[ (int) $term_id ];
		}
	}

	return false;
}


/**
 * Keep gated posts out of every front-end listing the visitor isn't
 * entitled to see: the main query (home, archives, search), the theme's
 * own secondary queries (the front page's latest grid, the Resources
 * sections, related posts), feeds, and the REST API.
 *
 * Applies to ALL front-end queries by default rather than main queries
 * only — a gated title showing up in the homepage grid is exactly the
 * leak this is meant to prevent. The single query that must see gated
 * posts opts out with 'editor_child_bypass_gate'.
 */
function editor_child_hide_gated_posts( $query )
{
	if ( is_admin() && ! ( defined( 'REST_REQUEST' ) && REST_REQUEST ) )
	{
		return;
	}

	if ( $query->get( 'editor_child_bypass_gate' ) )
	{
		return;
	}

	/*
	 * Only 'post' carries the category taxonomy. Skipping everything
	 * else keeps a pointless taxonomy JOIN off nav-menu, page and
	 * attachment queries, which run on every request.
	 */
	$post_type = $query->get( 'post_type' );

	if ( $post_type && 'any' !== $post_type )
	{
		$types = (array) $post_type;

		if ( ! in_array( 'post', $types, true ) )
		{
			return;
		}
	}

	$forbidden = editor_child_forbidden_category_ids();

	if ( ! $forbidden )
	{
		return;
	}

	/*
	 * Leave singular queries alone. Excluding a category from the query
	 * that IS the gated post would just 404 it; the template_redirect
	 * guard below handles that case properly, sending the visitor to the
	 * area's locked page instead of a dead end.
	 */
	if ( $query->is_singular() )
	{
		return;
	}

	$existing = $query->get( 'category__not_in' );
	$existing = is_array( $existing ) ? $existing : ( $existing ? array( $existing ) : array() );

	$query->set( 'category__not_in', array_unique( array_merge( $existing, $forbidden ) ) );
}

add_action( 'pre_get_posts', 'editor_child_hide_gated_posts' );


/**
 * Block direct access to a gated post's permalink, and to the gated
 * category archive, for visitors without entitlement.
 *
 * Sends them to the area's own page, which renders the locked state
 * (pitch + sign-in CTA) — better than a bare 404, and the ?locked flag
 * lets that page explain why they were bounced.
 *
 * A redirect rather than a rendered lock screen is the safer default:
 * there's no chance of the post body reaching the response at all.
 */
function editor_child_guard_gated_singles()
{
	if ( is_admin() )
	{
		return;
	}

	$area_key = false;

	if ( is_singular( 'post' ) )
	{
		$area_key = editor_child_post_member_area( get_queried_object_id() );
	}
	elseif ( is_category() )
	{
		$map      = editor_child_gated_category_map();
		$term_id  = (int) get_queried_object_id();
		$area_key = isset( $map[ $term_id ] ) ? $map[ $term_id ] : false;
	}

	if ( ! $area_key || editor_child_user_has_access( $area_key ) )
	{
		return;
	}

	$area = editor_child_get_member_area( $area_key );
	$page = get_page_by_path( $area['page_slug'] );

	$target = $page
		? add_query_arg( 'locked', '1', get_permalink( $page ) )
		: editor_child_member_login_url( home_url( '/' ) );

	wp_safe_redirect( $target, 302 );
	exit;
}

add_action( 'template_redirect', 'editor_child_guard_gated_singles' );


/**
 * Third route: a single REST item (/wp-json/wp/v2/posts/<id>) is fetched
 * with get_post(), not WP_Query, so the pre_get_posts filter above never
 * sees it. Strip the body out of the response for gated posts the
 * requester isn't entitled to.
 *
 * Title and date stay — they're already public in the locked page's
 * teaser list — but content, excerpt and any rendered body do not.
 */
function editor_child_guard_gated_rest( $response, $post )
{
	$area_key = editor_child_post_member_area( $post->ID );

	if ( ! $area_key || editor_child_user_has_access( $area_key ) )
	{
		return $response;
	}

	$data = $response->get_data();

	foreach ( array( 'content', 'excerpt' ) as $field )
	{
		if ( isset( $data[ $field ] ) )
		{
			$data[ $field ] = array(
				'rendered'  => '',
				'protected' => true,
			);
		}
	}

	$response->set_data( $data );

	return $response;
}

add_filter( 'rest_prepare_post', 'editor_child_guard_gated_rest', 10, 2 );
