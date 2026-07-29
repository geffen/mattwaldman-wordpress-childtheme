<?php
	get_header();
?>

<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<header class="entry-header">
				<div class="layout-fixed">
					<h1 class="entry-title">
						<?php
							single_cat_title();
						?>
					</h1>
					
					<ul id="filters" class="filters">
						<?php
							$all_departments = get_categories(array('type'     => 'portfolio',
																	'taxonomy' => 'department' ));
							
							$parent_department_slug = get_query_var('term');
							$parent_department_id = "";
							
							foreach ( $all_departments as $one_department )
							{
								if ( $one_department->slug == $parent_department_slug )
								{
									$parent_department_id = $one_department->term_id;
								}
							}
							
							$pf_terms = get_categories(array('type'     => 'portfolio',
															 'taxonomy' => 'department',
															 'parent'   => $parent_department_id ));
							
							if (count($pf_terms) >= 1)
							{
								?>
									<li class="current">
										<a data-filter="*" href="#">
											<?php
												_e('All', 'editor');
											?>
										</a>
									</li>
								<?php
							}
							
							foreach ($pf_terms as $pf_term)
							{
								?>
									<li>
										<a data-filter=".<?php echo esc_attr($pf_term->slug); ?>" href="<?php echo esc_url(get_category_link($pf_term->term_id)); ?>">
											<?php
												echo esc_html($pf_term->name);
											?>
										</a>
									</li>
								<?php
							}
						?>
					</ul>
				</div>
			</header>
			
			<div class="layout-medium">
				<div class="media-grid-wrap">
					<?php
						$layout 	= get_option('portfolio_layout', 'masonry');
						$item_width = get_option('portfolio_columns', '360');
					?>
					<div class="portfolio media-grid masonry" data-layout="<?php echo esc_attr($layout); ?>" data-item-width="<?php echo esc_attr($item_width); ?>">
						<?php
							$loop_portfolio = new WP_Query(array('post_type'      => 'portfolio',
																 'department'     => $parent_department_slug,
																 'posts_per_page' => -1 ));
							
							if ( $loop_portfolio->have_posts() ) :
								while ( $loop_portfolio->have_posts() ) : $loop_portfolio->the_post();
								
									if (has_post_thumbnail())
									{
										?>
											<div id="post-<?php the_ID(); ?>" <?php post_class(editor_portfolio_page__post_class()); ?>>
												<?php
													$portfolio_item_type = get_option(get_the_ID() . 'pf_type', 'Standard');
													$portfolio_item_type_icon = "";
													
													if ($portfolio_item_type == 'Lightbox Gallery') { $portfolio_item_type_icon = 'image'; }
													elseif ($portfolio_item_type == 'Lightbox Audio') { $portfolio_item_type_icon = 'audio'; }
													elseif ($portfolio_item_type == 'Lightbox Video') { $portfolio_item_type_icon = 'video'; }
													elseif ($portfolio_item_type == 'Direct URL') { $portfolio_item_type_icon = 'url'; }
												?>
												
												<div class="media-box <?php echo esc_attr($portfolio_item_type_icon); ?>">
													<?php
														the_post_thumbnail('pixelwars_theme_image_size_3');
													?>
													
													<div class="mask"></div>
													
													<?php
														if ($portfolio_item_type == 'Lightbox Gallery')
														{
															the_content();
														}
														elseif ($portfolio_item_type == 'Direct URL')
														{
															$direct_url = stripcslashes(get_option(get_the_ID() . 'pf_direct_url'));
															$new_tab = get_option(get_the_ID() . 'pf_link_new_tab', true);
															?>
																<a <?php if ($new_tab != false) { echo 'target="_blank"'; } ?> href="<?php echo esc_url($direct_url); ?>"></a>
															<?php
														}
														elseif (($portfolio_item_type == 'Lightbox Audio') || ($portfolio_item_type == 'Lightbox Video'))
														{
															$direct_url = stripcslashes(get_option($post->ID . 'pf_direct_url'));
															?>
																<a class="lightbox mfp-iframe" title="<?php the_title_attribute(); ?>" href="<?php echo esc_url($direct_url); ?>"></a>
															<?php
														}
														else
														{
															?>
																<a href="<?php the_permalink(); ?>"></a>
															<?php
														}
													?>
												</div>
												
												<div class="media-cell-desc">
													<h3><?php the_title(); ?></h3>
													
													<?php
														if (has_excerpt())
														{
															?>
																<p class="category">
																	<?php
																		echo get_the_excerpt();
																	?>
																</p>
															<?php
														}
													?>
												</div>
											</div>
										<?php
									}
								
								endwhile;
							endif;
							wp_reset_postdata();
						?>
					</div>
				</div>
			</div>
			
			<?php
				$category_description = category_description();
				
				if ($category_description != "")
				{
					?>
						<div class="layout-fixed">
							<div class="entry-content">
								<?php
									echo esc_html($category_description);
								?>
							</div>
						</div>
					<?php
				}
			?>
		</div>
	</div>
</div>

<?php
	get_footer();
?>