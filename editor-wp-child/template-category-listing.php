<?php
/**
 * Template Name: Category Listing
 *
 * A page that fronts a post category: renders whatever intro copy is
 * written in the editor, then lists that category's posts in the shared
 * card grid with pagination.
 *
 * Used by /film-room/, /articles/, and /podcasts/ — pages that exist so
 * the nav can point at clean URLs (and so Matt can add intro copy)
 * rather than at bare /category/... archives.
 *
 * The category is matched to the page automatically: first by an
 * optional "rsp_category" custom field (slug or ID), otherwise by the
 * page's own slug. So /film-room/ finds the "film-room" category with no
 * configuration. Set the custom field only if a page's slug and its
 * category slug ever need to differ.
 *
 * Paginates via the `paged` query var, so /film-room/page/2/ works.
 */

get_header();

while ( have_posts() ) :
	the_post();

	$page_slug = get_post_field( 'post_name', get_the_ID() );
	$override  = get_post_meta( get_the_ID(), 'rsp_category', true );
	$category  = false;

	if ( $override )
	{
		$category = is_numeric( $override )
			? get_category( (int) $override )
			: get_category_by_slug( $override );
	}

	if ( ! $category || is_wp_error( $category ) )
	{
		$category = get_category_by_slug( $page_slug );
	}

	$has_intro = trim( wp_strip_all_tags( get_the_content() ) ) !== '';
	?>

	<div class="rsp-page-hero">
		<div class="rsp-page-hero__inner">
			<div class="rsp-page-hero__kicker-row">
				<div class="rsp-page-hero__kicker-rule"></div>
				<span class="rsp-page-hero__kicker">Resources</span>
			</div>
			<h1 class="rsp-page-hero__title"><?php the_title(); ?></h1>
		</div>
	</div>

	<div class="rsp-index">

		<?php if ( $has_intro ) : ?>
			<div class="rsp-index__intro">
				<?php the_content(); ?>
			</div>
		<?php endif; ?>

		<?php
			if ( ! $category )
			{
				?>
				<p class="rsp-index__empty">No category matches this page yet. Create a category with the slug <code><?php echo esc_html( $page_slug ); ?></code>, or set an <code>rsp_category</code> custom field on this page.</p>
				<?php
			}
			else
			{
				$paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );

				$listing = new WP_Query( array(
					'cat'                 => $category->term_id,
					'posts_per_page'      => get_option( 'posts_per_page' ),
					'paged'               => $paged,
					'ignore_sticky_posts' => true,
				) );

				if ( $listing->have_posts() )
				{
					?>
					<div class="rsp-index__grid">
						<?php while ( $listing->have_posts() ) : $listing->the_post(); ?>
							<a href="<?php the_permalink(); ?>" class="rsp-post-card">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large', array( 'class' => 'rsp-post-card__image' ) ); ?>
								<?php else : ?>
									<div class="rsp-post-card__image rsp-post-card__image--placeholder"></div>
								<?php endif; ?>
								<div class="rsp-post-card__meta">
									<?php $card_cats = get_the_category(); ?>
									<?php if ( ! empty( $card_cats ) ) : ?>
										<span class="rsp-post-card__cat"><?php echo esc_html( $card_cats[0]->name ); ?></span>
									<?php endif; ?>
									<span class="rsp-post-card__title"><?php the_title(); ?></span>
								</div>
							</a>
						<?php endwhile; ?>
					</div>

					<?php
						// 'plain' + a .nav-links wrapper so this reuses the
						// same pagination CSS as home.php/archive.php. With
						// type 'list' the wrapping <ul> would itself match
						// .page-numbers and pick up the pill styling.
						$links = paginate_links( array(
							'total'     => $listing->max_num_pages,
							'current'   => $paged,
							'mid_size'  => 1,
							'prev_text' => '&larr; Previous',
							'next_text' => 'Next &rarr;',
							'type'      => 'plain',
						) );

						if ( $links )
						{
							echo '<div class="nav-links">' . $links . '</div>';
						}
					?>
					<?php
				}
				else
				{
					?>
					<p class="rsp-index__empty">Nothing published in <?php echo esc_html( $category->name ); ?> yet &mdash; check back soon.</p>
					<?php
				}

				wp_reset_postdata();
			}
		?>

	</div>

	<?php
endwhile;

get_footer();
