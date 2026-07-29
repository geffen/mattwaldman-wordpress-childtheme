<?php
	get_header();
?>

<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="layout-fixed">
				<?php
					if ( have_posts() ) :
						while ( have_posts() ) : the_post();
							?>
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<header class="entry-header">
										<h1 class="entry-title" <?php editor_hide_post_title(); ?>><?php the_title(); ?></h1>
										<div class="entry-meta">
											<span class="entry-date">
												<time class="entry-date" datetime="2012-02-13T04:34:10+00:00"><?php echo get_the_date(); ?></time>
											</span>
											
											<span class="comment-link">
												<?php
													comments_popup_link( __( '0 Comments', 'editor' ), __( '1 Comment', 'editor' ), __( '% Comments', 'editor' ) );
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
											
											<?php
												edit_post_link( __( 'Edit', 'editor' ), '<span class="edit-link">', '</span>' );
											?>
										</div>
									</header>
									<?php
										if ( has_post_thumbnail() )
										{
											?>
												<div class="featured-image">
													<?php
														the_post_thumbnail( 'pixelwars_theme_image_size_2', array( 'alt' => the_title_attribute( 'echo=0' ), 'title' => "" ) );
													?>
												</div>
											<?php
										}
									?>
									<div class="entry-content">
										<?php
											the_content();
										?>
										<?php
											wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'editor' ), 'after' => '</div>' ) );
										?>
										<?php
											if ( get_the_tags() != "" )
											{
												?>
													<div class="post-tags tagcloud">
														<?php
															the_tags( "", ' ', "" );
														?>
													</div>
												<?php
											}
										?>
										<?php
											get_template_part( 'part', 'share_links' );
										?>
										<?php
											get_template_part( 'part', 'single_navigation' );
										?>
										<?php
											$about_the_author_module = get_option( 'about_the_author_module', 'Yes' );
											
											if ( $about_the_author_module != 'No' )
											{
												get_template_part( 'part', 'about_author' );
											}
										?>
										<?php
											$pixelwars__related_posts = get_option( 'pixelwars__related_posts', 'Yes' );
											
											if ( $pixelwars__related_posts == 'Yes' )
											{
												get_template_part( 'part', 'related_posts' );
											}
										?>
									</div>
								</article>
								<?php
									comments_template( "", true );
								?>
							<?php
						endwhile;
					endif;
					wp_reset_query();
				?>
			</div>
		</div>
	</div>
</div>

<?php
	get_footer();
?>