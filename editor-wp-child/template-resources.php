<?php
/**
 * Template Name: Resources
 *
 * Resources landing page, built from `Resources Page.dc.html`: dark hero,
 * three category portal cards, then a section each for Film Room
 * (video-style cards), Articles (row list), and Podcasts (dark cards).
 *
 * All three sections pull real posts from the matching category. The
 * page column stays light (unlike Buy/About) — the hero is a dark band,
 * but everything below it is dark-on-light.
 *
 * Two optional post meta fields drive detail the design shows but WP has
 * no native field for. Both degrade silently when absent:
 *   rsp_duration — "18:24" / "58 min", shown on film + podcast cards
 *   rsp_episode  — podcast episode number
 *
 * Portal counts come from the real category counts and hide themselves
 * when a category is still empty, rather than printing "0 breakdowns".
 */

get_header();

$film_room_cat = get_category_by_slug( 'film-room' );
$articles_cat  = get_category_by_slug( 'articles' );
$podcasts_cat  = get_category_by_slug( 'podcasts' );

$film_room_page = get_page_by_path( 'film-room' );
$articles_page  = get_page_by_path( 'articles' );
$podcasts_page  = get_page_by_path( 'podcasts' );

/** Prefer the landing page for a section, fall back to its category archive. */
$section_url = function ( $page, $category ) {
	if ( $page )
	{
		return get_permalink( $page );
	}

	return $category ? get_category_link( $category ) : home_url( '/' );
};

$film_room_url = $section_url( $film_room_page, $film_room_cat );
$articles_url  = $section_url( $articles_page, $articles_cat );
$podcasts_url  = $section_url( $podcasts_page, $podcasts_cat );

/** Latest posts in a category, or an empty query when it doesn't exist yet. */
$section_query = function ( $category, $count ) {
	if ( ! $category )
	{
		return new WP_Query( array( 'post__in' => array( 0 ) ) );
	}

	return new WP_Query( array(
		'cat'                 => $category->term_id,
		'posts_per_page'      => $count,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	) );
};

$film_query     = $section_query( $film_room_cat, 3 );
$articles_query = $section_query( $articles_cat, 4 );
$podcast_query  = $section_query( $podcasts_cat, 3 );

$portals = array(
	array(
		'icon'  => '&#9654;',
		'title' => 'Film Room',
		'count' => $film_room_cat && $film_room_cat->count ? sprintf( _n( '%s breakdown', '%s breakdowns', $film_room_cat->count, 'editor-child' ), number_format_i18n( $film_room_cat->count ) ) : '',
		'desc'  => 'Play-by-play video analysis of prospects and NFL players, narrated frame by frame.',
		'cta'   => 'Watch on YouTube',
		'url'   => $film_room_url,
	),
	array(
		'icon'  => '&#9998;',
		'title' => 'Articles &amp; Analysis',
		'count' => $articles_cat && $articles_cat->count ? sprintf( _n( '%s post', '%s posts', $articles_cat->count, 'editor-child' ), number_format_i18n( $articles_cat->count ) ) : '',
		'desc'  => 'The NFL Lens, scouting reports, and The Gut Check — written study of what the tape shows.',
		'cta'   => 'Browse articles',
		'url'   => $articles_url,
	),
	array(
		'icon'  => '&#127897;',
		'title' => 'Podcasts',
		'count' => $podcasts_cat && $podcasts_cat->count ? sprintf( _n( '%s episode', '%s episodes', $podcasts_cat->count, 'editor-child' ), number_format_i18n( $podcasts_cat->count ) ) : '',
		'desc'  => 'Going Deep with Matt Waldman and guests — draft classes, dynasty strategy, and film talk.',
		'cta'   => 'Listen now',
		'url'   => $podcasts_url,
	),
);
?>

<div class="rsp-res-hero">
	<div class="rsp-res-hero__bg" aria-hidden="true"></div>
	<div class="rsp-res-hero__inner">
		<div class="rsp-res-hero__kicker-row">
			<div class="rsp-res-hero__kicker-rule"></div>
			<span class="rsp-res-hero__kicker">Everything the RSP Makes</span>
		</div>
		<h1 class="rsp-res-hero__title"><?php the_title(); ?></h1>
		<p class="rsp-res-hero__summary">Film breakdowns, written analysis, and long-form podcasts — every prospect and NFL player studied through the same film-based lens Matt Waldman has used since 2006. Start with the latest below, or dive into a full archive.</p>
	</div>
</div>

<div class="rsp-res-portals">
	<div class="rsp-res-portals__grid">
		<?php foreach ( $portals as $portal ) : ?>
			<a href="<?php echo esc_url( $portal['url'] ); ?>" class="rsp-res-portal">
				<span class="rsp-res-portal__icon" aria-hidden="true"><?php echo wp_kses_post( $portal['icon'] ); ?></span>
				<span class="rsp-res-portal__head">
					<span class="rsp-res-portal__title"><?php echo wp_kses_post( $portal['title'] ); ?></span>
					<?php if ( $portal['count'] ) : ?>
						<span class="rsp-res-portal__count"><?php echo esc_html( $portal['count'] ); ?></span>
					<?php endif; ?>
				</span>
				<span class="rsp-res-portal__desc"><?php echo esc_html( $portal['desc'] ); ?></span>
				<span class="rsp-res-portal__cta"><?php echo esc_html( $portal['cta'] ); ?> &rarr;</span>
			</a>
		<?php endforeach; ?>
	</div>
</div>

<div class="rsp-res-section">
	<div class="rsp-res-section__head">
		<div class="rsp-res-section__heading">
			<span class="rsp-res-section__label">Watch</span>
			<h2 class="rsp-res-section__title">RSP Film Room</h2>
		</div>
		<a href="<?php echo esc_url( $film_room_url ); ?>" class="rsp-res-section__all">All film breakdowns &rarr;</a>
	</div>

	<?php if ( $film_query->have_posts() ) : ?>
		<div class="rsp-res-cards">
			<?php while ( $film_query->have_posts() ) : $film_query->the_post(); ?>
				<?php $duration = get_post_meta( get_the_ID(), 'rsp_duration', true ); ?>
				<a href="<?php the_permalink(); ?>" class="rsp-res-film">
					<div class="rsp-res-film__thumb">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium_large', array( 'class' => 'rsp-res-film__image' ) ); ?>
						<?php endif; ?>
						<span class="rsp-res-film__play" aria-hidden="true"><span></span></span>
						<?php if ( $duration ) : ?>
							<span class="rsp-res-film__dur"><?php echo esc_html( $duration ); ?></span>
						<?php endif; ?>
					</div>
					<div class="rsp-res-film__meta">
						<?php $film_cats = get_the_category(); ?>
						<?php if ( ! empty( $film_cats ) ) : ?>
							<span class="rsp-res-film__kicker"><?php echo esc_html( $film_cats[0]->name ); ?></span>
						<?php endif; ?>
						<span class="rsp-res-film__title"><?php the_title(); ?></span>
						<span class="rsp-res-film__date"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
					</div>
				</a>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	<?php else : ?>
		<p class="rsp-res-empty">No film breakdowns published yet.</p>
	<?php endif; ?>
</div>

<div class="rsp-res-section">
	<div class="rsp-res-section__head">
		<div class="rsp-res-section__heading">
			<span class="rsp-res-section__label">Read</span>
			<h2 class="rsp-res-section__title">Articles &amp; Analysis</h2>
		</div>
		<a href="<?php echo esc_url( $articles_url ); ?>" class="rsp-res-section__all">All articles &rarr;</a>
	</div>

	<?php if ( $articles_query->have_posts() ) : ?>
		<div class="rsp-res-rows">
			<?php while ( $articles_query->have_posts() ) : $articles_query->the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="rsp-res-row">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'medium', array( 'class' => 'rsp-res-row__image' ) ); ?>
					<?php else : ?>
						<div class="rsp-res-row__image rsp-res-row__image--placeholder"></div>
					<?php endif; ?>
					<div class="rsp-res-row__body">
						<?php $row_cats = get_the_category(); ?>
						<?php if ( ! empty( $row_cats ) ) : ?>
							<span class="rsp-res-row__kicker"><?php echo esc_html( $row_cats[0]->name ); ?></span>
						<?php endif; ?>
						<span class="rsp-res-row__title"><?php the_title(); ?></span>
						<p class="rsp-res-row__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
						<span class="rsp-res-row__date"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
					</div>
				</a>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
			<div class="rsp-res-rows__end"></div>
		</div>
	<?php else : ?>
		<p class="rsp-res-empty">No articles published yet.</p>
	<?php endif; ?>
</div>

<div class="rsp-res-section rsp-res-section--last">
	<div class="rsp-res-section__head">
		<div class="rsp-res-section__heading">
			<span class="rsp-res-section__label">Listen</span>
			<h2 class="rsp-res-section__title">The Going Deep Podcast</h2>
		</div>
		<a href="<?php echo esc_url( $podcasts_url ); ?>" class="rsp-res-section__all">All episodes &rarr;</a>
	</div>

	<?php if ( $podcast_query->have_posts() ) : ?>
		<div class="rsp-res-cards">
			<?php while ( $podcast_query->have_posts() ) : $podcast_query->the_post(); ?>
				<?php
					$duration = get_post_meta( get_the_ID(), 'rsp_duration', true );
					$episode  = get_post_meta( get_the_ID(), 'rsp_episode', true );
				?>
				<a href="<?php the_permalink(); ?>" class="rsp-res-pod">
					<div class="rsp-res-pod__top">
						<span class="rsp-res-pod__icon" aria-hidden="true">&#127897;</span>
						<?php if ( $duration ) : ?>
							<span class="rsp-res-pod__dur"><?php echo esc_html( $duration ); ?></span>
						<?php endif; ?>
					</div>
					<div class="rsp-res-pod__body">
						<?php if ( $episode ) : ?>
							<span class="rsp-res-pod__ep">Episode <?php echo esc_html( $episode ); ?></span>
						<?php endif; ?>
						<span class="rsp-res-pod__title"><?php the_title(); ?></span>
						<span class="rsp-res-pod__date"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
					</div>
					<div class="rsp-res-pod__play">
						<span class="rsp-res-pod__play-icon" aria-hidden="true"><span></span></span>
						<span class="rsp-res-pod__play-label">Play episode</span>
					</div>
				</a>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	<?php else : ?>
		<p class="rsp-res-empty">No episodes published yet.</p>
	<?php endif; ?>
</div>

<?php get_footer(); ?>
