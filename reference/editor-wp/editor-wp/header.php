<?php
	$fixed_header = get_option( 'fixed_header', 'Yes' );
	
	if ( $fixed_header == 'No' )
	{
		$fixed_header = "";
	}
	else
	{
		$fixed_header = 'is-menu-fixed';
	}
?>
<!doctype html>

<html <?php language_attributes(); ?> class="<?php echo esc_attr( $fixed_header ); ?>">

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php
		$mobile_zoom = get_option( 'mobile_zoom', 'Yes' );
		
		if ( $mobile_zoom == 'No' )
		{
			?>
				<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
			<?php
		}
		else
		{
			?>
				<meta name="viewport" content="width=device-width, initial-scale=1">
			<?php
		}
	?>
	<?php
		wp_head();
	?>
</head>

<body <?php body_class(); ?>>
    <div id="page" class="hfeed site">
        <header id="masthead" class="site-header" role="banner">
			<nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
				
				<a class="menu-toggle"><span class="lines"></span></a>
				
				<div class="nav-menu">
					<?php
						wp_nav_menu(
							array(
								'theme_location' => 'pixelwars_theme_menu_location_1',
								'menu'           => 'pixelwars_theme_menu_location_1',
								'menu_id'        => 'nav',
								'menu_class'     => 'menu-custom',
								'container'      => false,
								'depth'          => 0
							)
						);
					?>
				</div>
				<?php
					$nav_menu_search = get_option( 'nav_menu_search', 'No' );
					
					if ( $nav_menu_search != 'No' )
					{
						?>
							<a class="search-toggle toggle-link"></a>
							
							<div class="search-container">
								<div class="search-box">
									<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
										<label>
											<?php echo __( 'Search for', 'editor' ); ?>
											
											<input type="search" id="search-field" name="s" placeholder="<?php echo __( 'type and hit enter', 'editor' ); ?>">
										</label>
										
										<input type="submit" class="search-submit" value="<?php echo __( 'Search', 'editor' ); ?>">
									</form>
								</div>
							</div>
						<?php
					}
				?>
				
				<div class="social-container">
					<?php
						if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'pixelwars_header_social_icons' ) ) :
						endif;
					?>
				</div>
			</nav>
			
			<h1 class="site-title">
				<?php
					$logo_type = get_option( 'logo_type', 'Text Logo' );
					
					if ( $logo_type == 'Image Logo' )
					{
						$logo_image = get_option( 'logo_image', "" );
						
						?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<img alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url( $logo_image ); ?>">
							</a>
						<?php
					}
					else
					{
						$text_logo_out    = get_bloginfo( 'name' );
						$select_text_logo = get_option( 'select_text_logo', 'WordPress Site Title' );
						
						if ( $select_text_logo == 'Theme Site Title' )
						{
							$text_logo_out = stripcslashes( get_option( 'theme_site_title', "" ) );
						}
						
						?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html($text_logo_out); ?></a>
						<?php
					}
				?>
			</h1>
			
			<?php
				$select_tagline = get_option( 'select_tagline', 'WordPress Tagline' );
				
				if ( $select_tagline == 'WordPress Tagline' )
				{
					$wordpress_tagline = get_bloginfo( 'description' );
					
					if ( $wordpress_tagline != "" )
					{
						?>
							<p class="site-description"><?php echo esc_html($wordpress_tagline); ?></p>
						<?php
					}
				}
				else
				{
					$theme_tagline = stripcslashes( get_option( 'theme_tagline', "" ) );
					
					if ( $theme_tagline != "" )
					{
						?>
							<p class="site-description"><?php echo esc_html($theme_tagline); ?></p>
						<?php
					}
				}
			?>
        </header>