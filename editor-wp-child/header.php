<?php
/**
 * Header 1C — quickbar + main bar + dropdown nav + mobile slide-in menu.
 * Full replacement of the parent header.php (design is structurally
 * unrelated to the parent's markup — see CLAUDE.md).
 *
 * Nav items (RSP Draft Guide, Player Evaluation, Resources, etc.) come from
 * the existing 'pixelwars_theme_menu_location_1' menu location so Matt can
 * edit them in Appearance > Menus. Give "Player Evaluation" / "Resources"
 * child items in the menu builder to get their dropdowns.
 *
 * TODO: swap the '#' placeholder (Member Login) for a real page URL once
 * that page exists. The tagline and Buy the RSP CTA below already
 * resolve via editor_child_get_about_url() / editor_child_get_buy_rsp_url().
 */

$is_member_login = is_page( 'member-login' );
$buy_rsp_url      = editor_child_get_buy_rsp_url();
$about_url        = editor_child_get_about_url();
?>
<!doctype html>

<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">

		<header id="masthead" class="rsp-header" role="banner">
			<div class="rsp-header__texture" aria-hidden="true"></div>

			<div class="rsp-quickbar">
				<div class="rsp-quickbar__inner">
					<a href="<?php echo esc_url( $about_url ); ?>" class="rsp-quickbar__tagline">Pleasantly Shocking Readers since 2006</a>

					<div class="rsp-quickbar__right">
						<a href="#" class="rsp-quickbar__login<?php echo $is_member_login ? ' is-active' : ''; ?>">Member Login</a>

						<div class="rsp-quickbar__social">
							<a href="#" aria-label="YouTube"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8zM9.6 15.6V8.4l6.2 3.6-6.2 3.6z"/></svg></a>
							<a href="#" aria-label="X"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 1.2h3.7l-8 9.1L24 22.8h-7.4l-5.8-7.5-6.6 7.5H.5l8.5-9.7L0 1.2h7.6l5.2 6.9 6.1-6.9zm-1.3 19.4h2L6.5 3.3H4.3l13.3 17.3z"/></svg></a>
							<a href="#" aria-label="Bluesky"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 10.8C10.9 8.6 7.9 4.6 5.1 2.6 2.4.7 1.4 1 .7 1.3 0 1.7 0 2.7 0 3.4c0 .6.4 5.3.6 6.1.8 2.6 3.5 3.5 6.1 3.2-3.8.6-7.2 2-2.8 6.9 4.9 5 6.7-1.1 7.6-4.2.9 3.1 2 9 7.5 4.2 4.1-4.2.7-6.3-3.1-6.9 2.6.3 5.3-.6 6.1-3.2.2-.8.6-5.5.6-6.1 0-.7 0-1.7-.7-2.1-.7-.3-1.7-.6-4.4 1.3-2.8 2-5.8 6-6.9 8.2z"/></svg></a>
						</div>
					</div>
				</div>
			</div>

			<div class="rsp-mainbar">
				<a class="rsp-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/LogoTxt.png' ); ?>" width="261" height="76" alt="<?php bloginfo( 'name' ); ?>">
				</a>

				<nav id="rsp-primary-nav" class="rsp-nav" aria-label="<?php esc_attr_e( 'Primary', 'editor-child' ); ?>">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'pixelwars_theme_menu_location_1',
							'menu_id'        => 'rsp-menu',
							'menu_class'     => 'rsp-menu',
							'container'      => false,
							'depth'          => 3,
							'fallback_cb'    => false,
						) );
					?>
				</nav>

				<a href="<?php echo esc_url( $buy_rsp_url ); ?>" class="rsp-cta" data-sheen>Buy the RSP</a>

				<button type="button" class="rsp-hamburger" aria-expanded="false" aria-controls="rsp-mobile-menu" aria-label="<?php esc_attr_e( 'Toggle menu', 'editor-child' ); ?>">
					<span></span><span></span><span></span>
				</button>
			</div>
		</header>

		<div class="rsp-mobile-overlay" hidden></div>
		<div id="rsp-mobile-menu" class="rsp-mobile-menu" hidden>
			<?php
				wp_nav_menu( array(
					'theme_location' => 'pixelwars_theme_menu_location_1',
					'menu_id'        => 'rsp-mobile-menu-list',
					'menu_class'     => 'rsp-mobile-menu__list',
					'container'      => false,
					'depth'          => 3,
					'fallback_cb'    => false,
				) );
			?>
			<a href="<?php echo esc_url( $buy_rsp_url ); ?>" class="rsp-cta rsp-cta--mobile" data-sheen>Buy the RSP</a>
		</div>
