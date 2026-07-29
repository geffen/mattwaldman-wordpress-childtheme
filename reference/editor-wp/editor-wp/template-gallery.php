<?php
/*
Template Name: Gallery
*/
?>

<?php
	get_header();
?>

<?php
	$pixelwars__gallery_page_slug = get_post_field( 'post_name', get_the_ID() );
	update_option( 'pixelwars__gallery_page_slug', $pixelwars__gallery_page_slug );
?>

<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="layout-medium">
				<header class="entry-header">
					<h1 class="entry-title" <?php editor_hide_post_title(); ?>><?php single_post_title(); ?></h1>
				</header>
				<div class="media-grid-wrap">
					<?php
						$gallery_layout  = get_option( 'gallery_layout', 'masonry' );
						$gallery_columns = get_option( 'gallery_columns', '420' );
					?>
					<div class="r-gallery masonry media-grid" data-layout="<?php echo esc_attr( $gallery_layout ); ?>" data-item-width="<?php echo esc_attr( $gallery_columns ); ?>">
						<?php
							$args = array(
								'post_type'      => 'gallery',
								'posts_per_page' => -1
							);
							
							$loop = new WP_Query( $args );
							
							if ( $loop->have_posts() ) :
								while ( $loop->have_posts() ) : $loop->the_post();
									?>
										<div class="media-cell hentry">
											<div class="media-box">
												<?php
													the_post_thumbnail( 'pixelwars_theme_image_size_6' );
												?>
												<div class="mask">
													<div class="media-cell-desc">
														<h3><?php the_title(); ?></h3>
														
														<h4><?php echo get_the_date(); ?></h4>
													</div>
												</div>
												<a href="<?php the_permalink(); ?>"></a>
											</div>
										</div>
									<?php
								endwhile;
							endif;
							wp_reset_postdata();
						?>
					</div>
				</div>
			</div>
			
			<?php
				while (have_posts()) : the_post();
				
					$page_content = get_the_content();
					
					if ($page_content != "")
					{
						?>
							<div class="layout-fixed">
								<div class="entry-content">
									<?php
										the_content();
									?>
								</div>
							</div>
						<?php
					}
				
				endwhile;
			?>
		</div>
	</div>
</div>

<?php
	get_footer();
?>