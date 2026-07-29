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
										<?php
											wp_link_pages(
												array(
													'before' => '<div class="page-links clearfix">' . __('Pages:', 'editor'),
													'after'  => '</div>'
												)
											);
										?>
									</div>
								</article>
								<?php
									$page_comments = get_option( 'page_comments', 'Yes' );
									
									if ( $page_comments != 'No' )
									{
										comments_template( "", true );
									}
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