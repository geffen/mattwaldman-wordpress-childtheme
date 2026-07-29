<?php
	get_header();
?>

<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="layout-medium">
				<?php
					if (have_posts()) :
						while (have_posts()) : the_post();
							?>
								<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
									<header class="entry-header">
										<h1 class="entry-title"><?php the_title(); ?></h1>
										
										<?php
											$portfolio_page_slug = get_option( 'portfolio_page_slug', "" );
											
											if ( $portfolio_page_slug != "" )
											{
												?>
													<ul class="header-links">
														<li>
															<a href="<?php echo esc_url( home_url( '/' ) . $portfolio_page_slug ); ?>">
																<i class="pw-icon-level-up"></i> <?php echo __( 'Back to Portfolio', 'editor' ); ?>
															</a>
														</li>
													</ul>
												<?php
											}
										?>
									</header>
									
									<div class="entry-content">
										<?php
											$pf_type = get_option( $post->ID . 'pf_type', 'Standard' );
											
											if ( $pf_type == 'Lightbox Audio' )
											{
												$pf_direct_url = stripcslashes( get_option( $post->ID . 'pf_direct_url' ) );
												
												?>
													<iframe src="<?php echo esc_url( $pf_direct_url ); ?>" width="100%" height="166" frameborder="no" scrolling="no"></iframe>
												<?php
												
												if ( has_excerpt() )
												{
													?>
														<p>
															<?php
																echo get_the_excerpt();
															?>
														</p>
													<?php
												}
											}
											elseif ( $pf_type == 'Lightbox Video' )
											{
												$pf_direct_url = stripcslashes( get_option( $post->ID . 'pf_direct_url' ) );
												
												?>
													<iframe src="<?php echo esc_url( $pf_direct_url ); ?>" width="800" height="450" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
												<?php
												
												if ( has_excerpt() )
												{
													?>
														<p>
															<?php
																echo get_the_excerpt();
															?>
														</p>
													<?php
												}
											}
											elseif ( $pf_type == 'Direct URL' )
											{
												$pf_direct_url = stripcslashes( get_option( $post->ID . 'pf_direct_url', "" ) );
												
												if ( $pf_direct_url != "" )
												{
													$new_tab = get_option( $post->ID . 'pf_link_new_tab', true );
													
													?>
														<p>
															<a class="button" <?php if ( $new_tab != false ) { echo 'target="_blank"'; } ?> href="<?php echo esc_url( $pf_direct_url ); ?>"><?php echo __( 'Launch Project', 'editor' ); ?></a>
														</p>
													<?php
												}
												
												if ( has_excerpt() )
												{
													?>
														<p>
															<?php
																echo get_the_excerpt();
															?>
														</p>
													<?php
												}
												
												if ( has_post_thumbnail() )
												{
													?>
														<p>
															<?php
																the_post_thumbnail( 'full', array( 'alt' => the_title_attribute( 'echo=0' ), 'title' => "" ) );
															?>
														</p>
													<?php
												}
											}
											elseif ( $pf_type == 'Lightbox Gallery' )
											{
												if ( has_excerpt() )
												{
													?>
														<p>
															<?php
																echo get_the_excerpt();
															?>
														</p>
													<?php
												}
												
												if ( has_post_thumbnail() )
												{
													?>
														<p>
															<?php
																the_post_thumbnail( 'full', array( 'alt' => the_title_attribute( 'echo=0' ), 'title' => "" ) );
															?>
														</p>
													<?php
												}
											}
										?>
										
										<?php
											the_content();
										?>
										
										<?php
											wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'editor' ), 'after' => '</div>' ) );
										?>
									</div>
									
									<?php
										get_template_part( 'part', 'single_navigation' );
									?>
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