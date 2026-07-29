<?php
/*
Template Name: Books
*/
?>

<?php
	get_header();
?>

<?php
	update_option('pixelwars__books_page', get_the_ID());
?>

<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="layout-medium">
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
				</header>
				<div class="bookshelf">
					<?php
						the_post();
						the_content();
					?>
					<?php
						function editor_book_buy_url_new_tab()
						{
							$book_buy_url_new_tab = get_option(get_the_ID() . 'book_buy_url_new_tab', true);
							
							if ($book_buy_url_new_tab)
							{
								$book_buy_url_new_tab = 'target="_blank"';
							}
							
							return $book_buy_url_new_tab;
						}
					?>
					<?php
						$loop_custom = new WP_Query(
							array(
								'post_type'      => 'book',
								'posts_per_page' => -1
							)
						);
						
						if ($loop_custom->have_posts()) :
							while ($loop_custom->have_posts()) : $loop_custom->the_post();
								?>
									<figure>
										<div class="perspective">
											<div class="book">
												<div class="cover">
													<div class="spine">
														<?php
															$book_side_image = stripcslashes(get_option(get_the_ID() . 'book_side_image', ""));
														?>
														<img alt="<?php the_title_attribute(); ?>" src="<?php echo esc_url($book_side_image); ?>">
													</div>
													<div class="front">
														<?php
															$book_cover_image = stripcslashes(get_option(get_the_ID() . 'book_cover_image', ""));
														?>
														<img alt="<?php the_title_attribute(); ?>" src="<?php echo esc_url($book_cover_image); ?>">
													</div>
												</div>
											</div>
										</div>
										<div class="buttons">
											<a class="open-details" href="#"><?php echo __('Details', 'editor'); ?></a>
											<?php
												$book_buy_url = stripcslashes(get_option(get_the_ID() . 'book_buy_url', ""));
												
												if ($book_buy_url != "")
												{
													?>
														<a <?php echo editor_book_buy_url_new_tab(); ?> href="<?php echo esc_url($book_buy_url); ?>"><?php echo __('Buy', 'editor'); ?></a>
													<?php
												}
											?>
										</div>
										<figcaption>
											<?php
												$book_author_name = "";
												$taxonomy_book    = 'book_author';
												$terms_list       = get_the_terms(get_the_ID(), $taxonomy_book);
												
												if (! empty($terms_list))
												{
													$out = array();
													
													foreach ($terms_list as $term_list)
													{
														$out[] = $term_list->name;
													}
													
													$book_author_name = join(', ', $out);
												}
											?>
											<h2><?php the_title(); ?> <span><?php echo esc_attr($book_author_name); ?></span></h2>
										</figcaption>
										<div class="details">
											<?php
												the_content();
											?>
											<span class="close-details">X</span>
										</div>
									</figure>
								<?php
							endwhile;
						endif;
						wp_reset_query();
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	get_footer();
?>