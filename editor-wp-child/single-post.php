<?php
/**
 * Article Template — film-breakdown / long-form post layout.
 * Full replacement of the parent's single-post.php -> post-sidebar.php
 * chain (same rationale as header.php — design is structurally unrelated
 * to the parent's markup, see CLAUDE.md).
 *
 * Two shortcodes (registered in functions.php) let Matt drop design
 * elements inline in the post body wherever he wants them:
 *   [rsp_video id="YOUTUBE_ID"]  — 16:9 click-to-play video block
 *   [rsp_promo]                  — the RSP purchase-pitch pull-quote block
 *
 * No author box and no comments — the design omits both (all content is
 * by Matt).
 */

get_header();

while ( have_posts() ) :
	the_post();

	$categories    = get_the_category();
	$word_count    = str_word_count( wp_strip_all_tags( get_the_content() ) );
	$read_minutes  = max( 1, (int) round( $word_count / 200 ) );
	$tags          = get_the_tags();
	$prev_post     = get_previous_post();
	$next_post     = get_next_post();
	$permalink     = get_permalink();
	$share_title   = get_the_title();

	$related_query = false;
	if ( ! empty( $categories ) ) {
		$related_query = new WP_Query( array(
			'category__in'        => wp_list_pluck( $categories, 'term_id' ),
			'post__not_in'        => array( get_the_ID() ),
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		) );
	}
	?>

	<div class="rsp-article-layout">

		<article <?php post_class( 'rsp-article' ); ?>>

			<?php if ( ! empty( $categories ) ) : ?>
				<div class="rsp-article__kicker">
					<?php foreach ( array_slice( $categories, 0, 2 ) as $i => $cat ) : ?>
						<?php if ( $i > 0 ) : ?><span class="rsp-article__kicker-sep">&bull;</span><?php endif; ?>
						<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" class="rsp-article__kicker-link"><?php echo esc_html( $cat->name ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<h1 class="rsp-article__title"><?php the_title(); ?></h1>

			<div class="rsp-article__byline">
				<span class="rsp-article__meta"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>&nbsp;&middot;&nbsp;<?php echo esc_html( $read_minutes ); ?> min read</span>
				<div class="rsp-article__byline-spacer"></div>
				<div class="rsp-article__share">
					<span class="rsp-article__share-label">Share</span>
					<a class="rsp-article__share-icon" href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( $permalink ); ?>&amp;text=<?php echo rawurlencode( $share_title ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on X', 'editor-child' ); ?>">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 1.2h3.7l-8 9.1L24 22.8h-7.4l-5.8-7.5-6.6 7.5H.5l8.5-9.7L0 1.2h7.6l5.2 6.9 6.1-6.9zm-1.3 19.4h2L6.5 3.3H4.3l13.3 17.3z"/></svg>
					</a>
					<a class="rsp-article__share-icon" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( $permalink ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on Facebook', 'editor-child' ); ?>">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0 0 22 12z"/></svg>
					</a>
					<a class="rsp-article__share-icon" href="mailto:?subject=<?php echo rawurlencode( $share_title ); ?>&amp;body=<?php echo rawurlencode( $permalink ); ?>" aria-label="<?php esc_attr_e( 'Share by email', 'editor-child' ); ?>">
						<svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M2 4h20v16H2V4zm2 2.4V6h16v.4l-8 5.6-8-5.6zM4 8.8V18h16V8.8l-7.4 5.2a1 1 0 0 1-1.2 0L4 8.8z"/></svg>
					</a>
				</div>
			</div>

			<?php if ( has_post_thumbnail() ) :
				$caption = wp_get_attachment_caption( get_post_thumbnail_id() );
				?>
				<figure class="rsp-article__figure">
					<?php the_post_thumbnail( 'large', array( 'class' => 'rsp-article__image' ) ); ?>
					<?php if ( $caption ) : ?>
						<figcaption class="rsp-article__caption"><?php echo esc_html( $caption ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endif; ?>

			<div class="rsp-article__body">
				<?php
					the_content();

					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'editor-child' ),
						'after'  => '</div>',
					) );
				?>
			</div>

			<?php if ( $tags ) : ?>
				<div class="rsp-article__tags">
					<?php foreach ( $tags as $tag ) : ?>
						<a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>" class="rsp-article__tag">#<?php echo esc_html( $tag->name ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $prev_post || $next_post ) : ?>
				<div class="rsp-article__adjacent">
					<?php if ( $prev_post ) : ?>
						<a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="rsp-article__adjacent-card rsp-article__adjacent-card--prev">
							<span class="rsp-article__adjacent-label">&larr; Previous</span>
							<span class="rsp-article__adjacent-title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
						</a>
					<?php endif; ?>
					<?php if ( $next_post ) : ?>
						<a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="rsp-article__adjacent-card rsp-article__adjacent-card--next">
							<span class="rsp-article__adjacent-label">Next &rarr;</span>
							<span class="rsp-article__adjacent-title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $related_query && $related_query->have_posts() ) : ?>
				<div class="rsp-article__related">
					<h3 class="rsp-article__related-heading">You May Also Like</h3>
					<div class="rsp-article__related-grid">
						<?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
							<a href="<?php the_permalink(); ?>" class="rsp-related-card">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium', array( 'class' => 'rsp-related-card__image' ) ); ?>
								<?php else : ?>
									<div class="rsp-related-card__image rsp-related-card__image--placeholder"></div>
								<?php endif; ?>
								<div class="rsp-related-card__meta">
									<?php $related_cats = get_the_category(); ?>
									<?php if ( ! empty( $related_cats ) ) : ?>
										<span class="rsp-related-card__cat"><?php echo esc_html( $related_cats[0]->name ); ?></span>
									<?php endif; ?>
									<span class="rsp-related-card__title"><?php the_title(); ?></span>
								</div>
							</a>
						<?php endwhile; ?>
					</div>
				</div>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>

		</article>

		<aside class="rsp-sidebar">

			<div class="rsp-sidebar__section">
				<div class="rsp-sidebar__heading">
					<span class="rsp-sidebar__heading-text">About RSP</span>
					<div class="rsp-sidebar__heading-rule"></div>
				</div>
				<img class="rsp-sidebar__about-image" src="<?php echo esc_url( get_stylesheet_directory_uri() . '/images/Front-AllText.png' ); ?>" alt="Rookie Scouting Portfolio cover">
				<p class="rsp-sidebar__about-text">Film-based scouting of every notable QB, RB, WR, and TE in the draft class — published every April 1 since 2006.</p>
				<a href="https://mattwaldman.com" class="rsp-sidebar__about-link">Buy the RSP &rarr;</a>
			</div>

			<div class="rsp-sidebar__section">
				<div class="rsp-sidebar__heading">
					<span class="rsp-sidebar__heading-text">Follow Matt</span>
					<div class="rsp-sidebar__heading-rule"></div>
				</div>
				<div class="rsp-sidebar__follow">
					<a href="#" class="rsp-sidebar__follow-link">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8zM9.6 15.6V8.4l6.2 3.6-6.2 3.6z"/></svg>
						YouTube
					</a>
					<a href="#" class="rsp-sidebar__follow-link">
						<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 1.2h3.7l-8 9.1L24 22.8h-7.4l-5.8-7.5-6.6 7.5H.5l8.5-9.7L0 1.2h7.6l5.2 6.9 6.1-6.9zm-1.3 19.4h2L6.5 3.3H4.3l13.3 17.3z"/></svg>
						X / Twitter
					</a>
					<a href="#" class="rsp-sidebar__follow-link">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 3h-2.7v11.6a2.7 2.7 0 1 1-1.9-2.6v-2.8a5.7 5.7 0 1 0 4.6 5.6V8.2a7 7 0 0 0 3.5 1v-3c-1.9-.5-3.2-2.2-3.5-4.2z"/></svg>
						TikTok
					</a>
					<a href="#" class="rsp-sidebar__follow-link">
						<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 10.8C10.9 8.6 7.9 4.6 5.1 2.6 2.4.7 1.4 1 .7 1.3 0 1.7 0 2.7 0 3.4c0 .6.4 5.3.6 6.1.8 2.6 3.5 3.5 6.1 3.2-3.8.6-7.2 2-2.8 6.9 4.9 5 6.7-1.1 7.6-4.2.9 3.1 2 9 7.5 4.2 4.1-4.2.7-6.3-3.1-6.9 2.6.3 5.3-.6 6.1-3.2.2-.8.6-5.5.6-6.1 0-.7 0-1.7-.7-2.1-.7-.3-1.7-.6-4.4 1.3-2.8 2-5.8 6-6.9 8.2z"/></svg>
						Bluesky
					</a>
				</div>
			</div>

			<?php
				$sidebar_categories = get_categories( array(
					'hide_empty' => true,
					'orderby'    => 'count',
					'order'      => 'DESC',
				) );
			?>
			<?php if ( $sidebar_categories ) : ?>
				<div class="rsp-sidebar__section">
					<div class="rsp-sidebar__heading">
						<span class="rsp-sidebar__heading-text">Categories</span>
						<div class="rsp-sidebar__heading-rule"></div>
					</div>
					<div class="rsp-sidebar__categories">
						<?php foreach ( $sidebar_categories as $sidebar_cat ) : ?>
							<a href="<?php echo esc_url( get_category_link( $sidebar_cat ) ); ?>" class="rsp-sidebar__cat-row">
								<span><?php echo esc_html( $sidebar_cat->name ); ?></span>
								<span class="rsp-sidebar__cat-count"><?php echo esc_html( $sidebar_cat->count ); ?></span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="rsp-sidebar__promo">
				<div class="rsp-sidebar__promo-bg" aria-hidden="true"></div>
				<div class="rsp-sidebar__promo-content">
					<span class="rsp-sidebar__promo-eyebrow">RSP Film Room</span>
					<span class="rsp-sidebar__promo-heading">Watch the tape with Matt</span>
					<p class="rsp-sidebar__promo-text">New film breakdowns weekly on YouTube — QBs, RBs, WRs, and TEs, play by play.</p>
					<a href="#" class="rsp-sidebar__promo-cta">Subscribe on YouTube</a>
				</div>
			</div>

		</aside>

	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
