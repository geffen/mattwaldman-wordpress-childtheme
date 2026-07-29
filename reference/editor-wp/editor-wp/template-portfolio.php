<?php
/*
Template Name: Portfolio
*/
?>

<?php
	get_header();
?>

<?php
	update_option('portfolio_page_slug', get_post_field('post_name', get_the_ID()));
?>

<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<header class="entry-header">
				<div class="layout-fixed">
					<h1 class="entry-title" <?php editor_hide_post_title(); ?>><?php single_post_title(); ?></h1>
					
					<ul id="filters" class="filters">
						<?php
							$categories = get_categories(
								array(
									'type'     => 'portfolio',
									'taxonomy' => 'department',
									'parent'   => 0
								)
							);
							
							if (count($categories) >= 1)
							{
								?>
									<li class="current"><a data-filter="*" href="#"><?php _e('All', 'editor'); ?></a></li>
								<?php
							}
							
							foreach ($categories as $category)
							{
								?>
									<li>
										<a data-filter=".<?php echo esc_attr($category->slug); ?>" href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
											<?php
												echo esc_html($category->name);
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
							$query = new WP_Query(
								array(
									'post_type'      => 'portfolio',
									'posts_per_page' => -1
								)
							);
							
							if ($query->have_posts()) :
								while ($query->have_posts()) : $query->the_post();
								
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
															$direct_url = stripcslashes(get_option(get_the_ID() . 'pf_direct_url'));
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