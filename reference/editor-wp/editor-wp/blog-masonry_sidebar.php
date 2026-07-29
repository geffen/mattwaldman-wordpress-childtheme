<?php
	get_header();
?>


<div id="main" class="site-main">
	<?php
		get_template_part( 'part', 'main_slider' );
	?>
	
	
	<div class="layout-medium">
		<div id="primary" class="content-area with-sidebar">
			<div id="content" class="site-content" role="main">
				<?php
					get_template_part( 'part', 'archive_title' );
				?>
				
				
				<?php
					$blog_masonry_layout = get_option( 'blog_masonry_layout', 'masonry' );
					
					$blog_masonry_item_width = get_option( 'blog_masonry_item_width', '340' );
				?>
				
				<div class="masonry blog-masonry" data-layout="<?php echo esc_attr( $blog_masonry_layout ); ?>" data-item-width="<?php echo esc_attr( $blog_masonry_item_width ); ?>">
					<?php
						if ( have_posts() ) :
							while ( have_posts() ) : the_post();
							
								?>
									<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
										<?php
											if ( has_post_thumbnail() )
											{
												?>
													<div class="featured-image">
														<a href="<?php the_permalink(); ?>">
															<?php
																the_post_thumbnail( 'pixelwars_theme_image_size_3', array( 'alt' => the_title_attribute( 'echo=0' ), 'title' => "" ) );
															?>
														</a>
													</div>
												<?php
											}
										?>
										
										
										<header class="entry-header">
											<h1 class="entry-title">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h1>
											
											
											<div class="entry-meta">
												<span class="entry-date">
													<time class="entry-date" datetime="2012-02-13T04:34:10+00:00"><?php echo get_the_date(); ?></time>
												</span> 
												
												<span class="comment-link">
													<?php
														comments_popup_link( __( '0 Comment', 'editor' ), __( '1 Comment', 'editor' ), __( '% Comments', 'editor' ) );
													?>
												</span>
												
												<?php
													editor_reading_time();
												?>
												
												<span class="cat-links">
													<?php
														the_category( ', ' );
													?>
												</span>
											</div>
										</header>
										
										
										<div class="entry-content">
											<p>
												<?php
													pixelwars_theme_excerpt_max_charlength( 150 );
												?>
											</p>
										</div>
									</article>
								<?php
							
							endwhile;
						
						else :
						
							get_template_part( 'part', 'content_none' );
						
						endif;
						wp_reset_query();
					?>
				</div>
				
				
				<?php
					get_template_part( 'part', 'pagination' );
				?>
			</div>
		</div>
		
		
		<?php
			get_sidebar();
		?>
	</div>
</div>


<?php
	get_footer();
?>