<?php
/**
 * Archive listing — category, tag, taxonomy, date, and author archives.
 * Replaces the parent's archive/category chain, which was still rendering
 * the old Pixelwars layout on every nav dropdown destination (Film Room,
 * Articles, Podcasts, and the rsp_position / rsp_draft_class taxonomies).
 *
 * Reuses the same designed card grid + pagination as home.php rather than
 * inventing a second listing style, so every listing surface on the site
 * matches. WordPress uses this file for tag.php/category.php/taxonomy.php
 * too unless a more specific template exists, so one file covers them all.
 *
 * Note the empty state is a real possibility here (the Film Room /
 * Articles / Podcasts categories are newly created and may have no posts
 * yet), so it gets a proper message rather than a blank column.
 */

get_header();
?>

<div class="rsp-index">

	<header class="rsp-archive-header">
		<h1 class="rsp-archive-header__title"><?php echo esc_html( wp_strip_all_tags( get_the_archive_title() ) ); ?></h1>
		<div class="rsp-archive-header__rule"></div>
		<?php
			$archive_description = get_the_archive_description();

			if ( $archive_description ) :
		?>
			<div class="rsp-archive-header__desc"><?php echo wp_kses_post( $archive_description ); ?></div>
		<?php endif; ?>
	</header>

	<?php if ( have_posts() ) : ?>

		<div class="rsp-index__grid">
			<?php while ( have_posts() ) : the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="rsp-post-card">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium_large', array( 'class' => 'rsp-post-card__image' ) ); ?>
					<?php else : ?>
						<div class="rsp-post-card__image rsp-post-card__image--placeholder"></div>
					<?php endif; ?>
					<div class="rsp-post-card__meta">
						<?php $archive_cats = get_the_category(); ?>
						<?php if ( ! empty( $archive_cats ) ) : ?>
							<span class="rsp-post-card__cat"><?php echo esc_html( $archive_cats[0]->name ); ?></span>
						<?php endif; ?>
						<span class="rsp-post-card__title"><?php the_title(); ?></span>
					</div>
				</a>
			<?php endwhile; ?>
		</div>

		<?php
			the_posts_pagination( array(
				'mid_size'  => 1,
				'prev_text' => '&larr; Previous',
				'next_text' => 'Next &rarr;',
			) );
		?>

	<?php else : ?>

		<p class="rsp-index__empty">No posts here yet. <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to the home page</a>.</p>

	<?php endif; ?>

</div>

<?php get_footer(); ?>
