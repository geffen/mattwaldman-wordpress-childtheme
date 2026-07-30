<?php
/**
 * Default page template — light hero title + editable content body.
 * Replaces the parent's page.php, which still rendered the old Pixelwars
 * layout (same reason archive.php exists).
 *
 * This is the fallback for any page WITHOUT a specific template assigned
 * (Ranking & Projections, Player Evaluation, and anything Matt adds
 * later). Pages with their own designs use template-*.php instead.
 *
 * Hero follows the same spec as the About / Buy the RSP / Member Login
 * heroes so a plain page still looks like part of the site.
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<div class="rsp-page-hero">
		<div class="rsp-page-hero__inner">
			<div class="rsp-page-hero__kicker-row">
				<div class="rsp-page-hero__kicker-rule"></div>
				<span class="rsp-page-hero__kicker"><?php bloginfo( 'name' ); ?></span>
			</div>
			<h1 class="rsp-page-hero__title"><?php the_title(); ?></h1>
		</div>
	</div>

	<div class="rsp-page-body">
		<div class="rsp-page-body__inner">
			<?php
				the_content();

				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'editor-child' ),
					'after'  => '</div>',
				) );
			?>
		</div>
	</div>

	<?php
endwhile;

get_footer();
