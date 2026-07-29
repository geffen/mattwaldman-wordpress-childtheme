<?php
	get_header();
?>

<div id="main" class="site-main">
	<?php
		get_template_part('part', 'main_slider');
	?>
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="layout-fixed">
				<?php
					get_template_part('part', 'archive_title');
				?>
				<div class="blog-regular">
					<?php
						if (have_posts()) :
							while (have_posts()) : the_post();
								?>
									<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
										<header class="entry-header">
											<h1 class="entry-title" <?php editor_hide_post_title(); ?>>
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h1>
											<div class="entry-meta">
												<span class="entry-date">
													<time class="entry-date" datetime="2012-02-13T04:34:10+00:00"><?php echo get_the_date(); ?></time>
												</span>
												
												<span class="comment-link">
													<?php
														comments_popup_link(
															__('0 Comments', 'editor'),
															__('1 Comment', 'editor'),
															__('% Comments', 'editor')
														);
													?>
												</span>
												
												<?php
													editor_reading_time();
												?>
												
												<?php
													if (get_the_category())
													{
														?>
															<span class="cat-links">
																<?php
																	the_category(', ');
																?>
															</span>
														<?php
													}
												?>
												
												<?php
													edit_post_link(
														__('Edit', 'editor'),
														'<span class="edit-link">',
														'</span>'
													);
												?>
											</div>
										</header>
										
										<?php
											if (has_post_thumbnail())
											{
												?>
													<div class="featured-image">
														<a href="<?php the_permalink(); ?>">
															<?php
																the_post_thumbnail('pixelwars_theme_image_size_2');
															?>
														</a>
													</div>
												<?php
											}
										?>
										
										<div class="entry-content">
											<?php
												if (has_excerpt())
												{
													the_excerpt();
													
													echo '<span class="more"><a class="more-link" href="'. get_permalink() . '">' . __('Continue reading <span class="meta-nav">&#8594;</span>', 'editor') . '</a></span>';
												}
												else
												{
													$theme_excerpt = get_option('theme_excerpt', 'No');
													
													if ($theme_excerpt == 'Yes')
													{
														the_excerpt();
													}
													elseif ($theme_excerpt == 'standard')
													{
														$format = get_post_format();
														
														if ($format == false)
														{
															the_excerpt();
														}
														else
														{
															the_content(__('Continue reading <span class="meta-nav">&#8594;</span>', 'editor'));
														}
													}
													else
													{
														the_content(__('Continue reading <span class="meta-nav">&#8594;</span>', 'editor'));
													}
												}
											?>
											
											<?php
												wp_link_pages(
													array(
														'before' => '<div class="page-links">' . __('Pages:', 'editor'),
														'after'  => '</div>'
													)
												);
											?>
										</div>
									</article>
								<?php
							endwhile;
						else :
						
							get_template_part('part', 'content_none');
						
						endif;
						wp_reset_query();
					?>
					
					<?php
						get_template_part('part', 'pagination');
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	get_footer();
?>