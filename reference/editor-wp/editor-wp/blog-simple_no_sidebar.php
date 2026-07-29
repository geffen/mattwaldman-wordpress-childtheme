<?php
	get_header();
?>


<div id="main" class="site-main">
	<?php
		get_template_part( 'part', 'main_slider' );
	?>
	
	
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="layout-fixed">
				<?php
					get_template_part( 'part', 'archive_title' );
				?>
				
				
				<div class="blog-simple">
					<ul>
						<?php
							if ( have_posts() ) :
								while ( have_posts() ) : the_post();
								
									?>
										<li>
											<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
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
														
														<?php
															if ( get_the_category() )
															{
																?>
																	<span class="cat-links">
																		<?php
																			the_category( ', ' );
																		?>
																	</span>
																<?php
															}
														?>
													</div>
												</header>
												
												<p>
													<?php
														pixelwars_theme_excerpt_max_charlength( 55 );
													?>
												</p>
											</article>
										</li>
									<?php
								
								endwhile;
							else :
							
								get_template_part( 'part', 'content_none' );
							
							endif;
							wp_reset_query();
						?>
					</ul>
					
					
					<?php
						get_template_part( 'part', 'pagination' );
					?>
				</div>
			</div>
		</div>
	</div>
</div>


<?php
	get_footer();
?>