<?php
	get_header();
?>


<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="layout-medium">
				<?php
					if ( have_posts() ) :
						while ( have_posts() ) : the_post();
							?>
								<header class="entry-header">
									<h1 class="entry-title"><?php the_title(); ?></h1> 
									
									<?php
										$pixelwars__gallery_page_slug = get_option( 'pixelwars__gallery_page_slug', "" );
										
										if ( $pixelwars__gallery_page_slug != "" )
										{
											?>
												<ul class="header-links">
													<li>
														<a href="<?php echo esc_url( home_url('/') . $pixelwars__gallery_page_slug . '/' ); ?>"><i class="pw-icon-level-up"></i> <?php echo __( 'Back to Gallery', 'editor' ); ?></a>
													</li>
												</ul>
											<?php
										}
									?>
								</header>
								
								
								<div id="rg-gallery" class="rg-gallery">
									<?php
										the_content();
									?>
									
									<?php
										wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'editor' ), 'after' => '</div>' ) );
									?>
									
									<div class="rg-image-wrapper">
										<div class="rg-image-nav">
											<a href="#" class="rg-image-nav-prev"><?php echo __( 'Previous Image', 'editor' ); ?></a>
											
											<a href="#" class="rg-image-nav-next"><?php echo __( 'Next Image', 'editor' ); ?></a>
										</div>
										
										<div class="rg-image"></div>
										
										<div class="rg-loading"></div>
										
										<div class="rg-caption-wrapper">
											<div class="rg-caption">
												<p></p>
											</div>
										</div>
									</div>
								</div>
							<?php
						endwhile;
					endif;
				?>
			</div>
		</div>
	</div>
</div>


<?php
	get_footer();
?>