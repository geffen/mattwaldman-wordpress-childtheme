<?php

	function pixelwars_main_slider()
	{
		$slides = get_option('editor_main_slider_slides', 'sticky');
		
		if ($slides != 'latest')
		{
			$slides = get_option('sticky_posts');
		}
		else
		{
			$slides = "";
		}
		
		$slides_count = get_option('editor_main_slider_latest_posts_count', '5');
		
		$args = array(  'post_type'      => 'post',
						'post__in'       => $slides,
						'posts_per_page' => $slides_count );
		
		$query = new WP_Query( $args );
		
		if ( $query->have_posts() )
		{
			$items = get_option( 'pixelwars_homepage_owl_carousel_items', '3' );
			$loop = get_option( 'pixelwars_homepage_owl_carousel_loop', 'true' );
			$center = get_option( 'pixelwars_homepage_owl_carousel_center', 'false' );
			$mouse_drag = get_option( 'pixelwars_homepage_owl_carousel_mouse_drag', 'true' );
			$nav_links = get_option( 'pixelwars_homepage_owl_carousel_nav_links', 'true' );
			$nav_dots = get_option( 'pixelwars_homepage_owl_carousel_nav_dots', 'false' );
			$autoplay = get_option( 'pixelwars_homepage_owl_carousel_autoplay', 'true' );
			$autoplay_speed = get_option( 'pixelwars_homepage_owl_carousel_autoplay_speed', '600' );
			$autoplay_timeout = get_option( 'pixelwars_homepage_owl_carousel_autoplay_timeout', '2000' );
			
			?>
				<div class="post-slider owl-carousel" data-items="<?php echo esc_attr( $items ); ?>" data-loop="<?php echo esc_attr( $loop ); ?>" data-center="<?php echo esc_attr( $center ); ?>" data-mouse-drag="<?php echo esc_attr( $mouse_drag ); ?>" data-nav="<?php echo esc_attr( $nav_links ); ?>" data-dots="<?php echo esc_attr( $nav_dots ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>" data-autoplay-timeout="<?php echo esc_attr( $autoplay_timeout ); ?>" data-nav-prev-text="<?php esc_attr_e('Prev', 'editor'); ?>" data-nav-next-text="<?php esc_attr_e('Next', 'editor'); ?>">
					<?php
						while ( $query->have_posts() )
						{
							$query->the_post();
							
							
							$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'pixelwars_theme_image_size_1' );
							
							$featured_image_url = $featured_image[0];
							
							?>
								<div class="post-thumbnail" style="background-image: url( <?php echo esc_url( $featured_image_url ); ?> );">
									<header class="entry-header">
										<div class="entry-meta">
											<span class="cat-links">
												<?php
													the_category( ' , ' );
												?>
											</span>	
										</div>
										
										<h1 class="entry-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h1>
									</header>
								</div>
							<?php
						}
					?>
				</div>
			<?php
		}
		
		wp_reset_postdata();
	}
	
	
	// ===============================================================
	
	
	if ( is_home() )
	{
		if ( isset( $_GET['main_slider'] ) )
		{
			if ( $_GET['main_slider'] == 'yes' )
			{
				pixelwars_main_slider();
			}
		}
		else
		{
			$main_slider = get_option( 'main_slider', 'No' );
			
			if ( ( $main_slider == 'Yes' ) || ( $main_slider == 'Yes2' ) )
			{
				pixelwars_main_slider();
			}
		}
	}

?>