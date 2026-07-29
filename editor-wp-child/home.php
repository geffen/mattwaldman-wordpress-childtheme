<?php
/**
 * Homepage — post listing ("Your homepage displays: Your latest posts").
 * Full replacement of the parent's index.php -> blog-regular*.php chain
 * (same rationale as header.php/single-post.php — see CLAUDE.md).
 *
 * No design file exists for this view in the design project yet. Card
 * style intentionally reuses the article template's related-post-card
 * visual language (image, category kicker, title) rather than the
 * parent's old Bootstrap blog-regular markup, so the site reads as one
 * system until a dedicated homepage design shows up.
 */

get_header();
?>

<div class="rsp-index">

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
						<?php $index_cats = get_the_category(); ?>
						<?php if ( ! empty( $index_cats ) ) : ?>
							<span class="rsp-post-card__cat"><?php echo esc_html( $index_cats[0]->name ); ?></span>
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

		<p class="rsp-index__empty"><?php esc_html_e( 'Nothing to see here yet.', 'editor-child' ); ?></p>

	<?php endif; ?>

</div>

<?php get_footer(); ?>
