<?php
/*
Template Name: Archives
*/
?>

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
									</header>
									<div class="entry-content">
										<?php
											the_content();
										?>
										<div class="post-list archives-list">
											<h3><?php echo __( 'Last 20 Posts', 'editor' ); ?></h3>
											<ul>
												<?php
													$loop_custom = new WP_Query(
														array(
															'post_type'      => 'post',
															'posts_per_page' => 20
														)
													);
													
													if ( $loop_custom->have_posts() ) :
														while ( $loop_custom->have_posts() ) : $loop_custom->the_post();
															?>
																<li>
																	<article>
																		<h3 class="entry-title">
																			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
																		</h3>
																		<div class="entry-meta">
																			<?php
																				editor_reading_time();
																			?>
																		</div>
																	</article>
																</li>
															<?php
														endwhile;
													endif;
													wp_reset_query();
												?>
											</ul>
										</div>
										
										<div class="archives-list archives-tag tagcloud archives-by-month">
											<h3><?php echo __( 'Archives by Month', 'editor' ); ?></h3>
											<ul>
												<?php
													$args = array(
														'format' => 'custom', 
														'before' => '<li>',
														'after'  => '</li>'
													);
													
													wp_get_archives( $args );
												?>
											</ul>
										</div>
										
										<div class="archives-list archives-tag tagcloud archives-by-category">
											<h3><?php echo __( 'Archives by Category', 'editor' ); ?></h3>
											<ul>
												<?php
													$categories = get_categories();
													
													foreach ( $categories as $category )
													{
														echo '<li><a href="' . get_category_link( $category->term_id ) . '">' . $category->name . '</a></li> ';
													}
												?>
											</ul>
										</div>
										
										<div class="archives-list archives-tag tagcloud archives-by-tag">
											<h3><?php echo __( 'Archives by Tag', 'editor' ); ?></h3>
											<ul>
												<?php
													$tags = get_tags();
													
													foreach ( $tags as $tag )
													{
														echo '<li><a href="' . get_tag_link( $tag->term_id ) . '">' . $tag->name . '</a></li> ';
													}
												?>
											</ul>
										</div>
										
										<div class="archives-list archives-tag tagcloud archives-by-format">
											<h3><?php echo __( 'Archives by Format', 'editor' ); ?></h3>
											<ul>
												<?php
													$post_formats = get_theme_support( 'post-formats' );
													
													foreach ( $post_formats[0] as $post_format )
													{
														$format_link = get_post_format_link( $post_format );
														
														echo '<li><a href="' . $format_link . '">' . $post_format . '</a></li> ';
													}
												?>
											</ul>
									   </div>
									</div>
								</article>
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