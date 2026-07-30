<?php
/**
 * Front page — built from `Home Page.dc.html` in the design project.
 * Section order: hero, featured breakdown, three portals, "What is the
 * RSP?", latest from the film room, CTA strip.
 *
 * WordPress uses front-page.php for the site's front page regardless of
 * whether Settings > Reading is set to "latest posts" or a static page,
 * so this takes over from home.php. home.php is kept as the blog-index
 * template — it kicks back in if a separate "Posts page" is ever set.
 *
 * NOTE on the "Latest from the Film Room" band: the prototype sets no
 * background there (so it inherited the dark page column) but styles its
 * titles/excerpts in dark-on-light colors, which would be unreadable.
 * Built as a light band per the design README, matching the portals band
 * above it.
 *
 * Featured + latest pull real posts. The prototype's film stills are
 * placeholders — posts without a featured image fall back to a flat
 * placeholder block rather than a broken image.
 */

get_header();

$buy_url       = editor_child_get_buy_rsp_url();
$resources_url = editor_child_get_resources_url();

$film_room_cat = get_category_by_slug( 'film-room' );
$articles_cat  = get_category_by_slug( 'articles' );
$podcasts_cat  = get_category_by_slug( 'podcasts' );

/*
 * Featured breakdown: a sticky post if Matt has set one, otherwise the
 * most recent post.
 */
$sticky        = get_option( 'sticky_posts' );
$featured_args = array(
	'posts_per_page'      => 1,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);

if ( ! empty( $sticky ) )
{
	$featured_args['post__in'] = $sticky;
}

$featured = new WP_Query( $featured_args );

if ( ! $featured->have_posts() && ! empty( $sticky ) )
{
	unset( $featured_args['post__in'] );
	$featured = new WP_Query( $featured_args );
}

$featured_id = $featured->have_posts() ? $featured->posts[0]->ID : 0;

/*
 * Latest from the film room: newest Film Room posts, excluding whatever
 * is already featured above. That category is new, so fall back to
 * latest posts site-wide while it's still empty.
 *
 * ignore_sticky_posts is required, not optional: a secondary WP_Query
 * with no page-type query vars is flagged is_home internally, and
 * WordPress then splices sticky posts into the results *on top of*
 * posts_per_page — this grid returned 7 items (4 stickies + 3 posts)
 * instead of 3 before this was added.
 */
$latest_args = array(
	'posts_per_page'      => 3,
	'post__not_in'        => $featured_id ? array( $featured_id ) : array(),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);

if ( $film_room_cat )
{
	$latest_args['cat'] = $film_room_cat->term_id;
}

$latest = new WP_Query( $latest_args );

if ( ! $latest->have_posts() && $film_room_cat )
{
	unset( $latest_args['cat'] );
	$latest = new WP_Query( $latest_args );
}

$portals = array(
	array(
		'title' => 'Film Room',
		'desc'  => 'Frame-by-frame breakdowns of the prospects and players who reward a closer look.',
		'cta'   => 'Watch &amp; read',
		'url'   => $film_room_cat ? get_category_link( $film_room_cat ) : $resources_url,
	),
	array(
		'title' => 'Articles',
		'desc'  => 'Long-form scouting, process pieces, and the thinking behind every RSP grade.',
		'cta'   => 'Start reading',
		'url'   => $articles_cat ? get_category_link( $articles_cat ) : $resources_url,
	),
	array(
		'title' => 'Podcasts',
		'desc'  => 'Conversations on evaluation, the draft, and the players Matt is studying now.',
		'cta'   => 'Listen in',
		'url'   => $podcasts_cat ? get_category_link( $podcasts_cat ) : $resources_url,
	),
);

$output_stats = array(
	array( 'value' => '900+', 'label' => 'Film breakdowns' ),
	array( 'value' => '2,000+', 'label' => 'Articles published' ),
	array( 'value' => '100+', 'label' => 'Podcast episodes' ),
	array( 'value' => 'April 1', 'label' => 'New RSP every year' ),
);
?>

<div class="rsp-home-hero">
	<div class="rsp-home-hero__bg" aria-hidden="true"></div>
	<div class="rsp-home-hero__gradient" aria-hidden="true"></div>
	<div class="rsp-home-hero__inner">
		<div class="rsp-home-hero__content">
			<div class="rsp-home-hero__kicker-row">
				<div class="rsp-home-hero__kicker-rule"></div>
				<span class="rsp-home-hero__kicker">Film-based scouting since 2006</span>
			</div>
			<h1 class="rsp-home-hero__title">A seat in Matt Waldman's film room</h1>
			<p class="rsp-home-hero__summary">Narrative, process-driven evaluation of every notable QB, RB, WR, and TE in the NFL draft class — the analysis, not just the answers. New Rookie Scouting Portfolio every April 1.</p>
			<div class="rsp-home-hero__actions">
				<a href="<?php echo esc_url( $buy_url ); ?>" class="rsp-home-hero__cta" data-sheen>Buy the 2026 RSP</a>
				<a href="<?php echo esc_url( $resources_url ); ?>" class="rsp-home-hero__cta-alt">Explore the Film Room</a>
			</div>
		</div>
	</div>
</div>

<?php if ( $featured->have_posts() ) : ?>
	<div class="rsp-home-featured">
		<div class="rsp-home-featured__inner">
			<div class="rsp-home-section-label rsp-home-section-label--dark">
				<span>Featured Breakdown</span>
				<div class="rsp-home-section-label__rule"></div>
			</div>
			<?php while ( $featured->have_posts() ) : $featured->the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="rsp-home-featured__card">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'class' => 'rsp-home-featured__image' ) ); ?>
					<?php else : ?>
						<div class="rsp-home-featured__image rsp-home-featured__image--placeholder"></div>
					<?php endif; ?>
					<div class="rsp-home-featured__body">
						<?php $featured_cats = get_the_category(); ?>
						<span class="rsp-home-featured__kicker"><?php echo esc_html( ! empty( $featured_cats ) ? $featured_cats[0]->name : 'RSP Film Room' ); ?></span>
						<span class="rsp-home-featured__title"><?php the_title(); ?></span>
						<span class="rsp-home-featured__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 32 ) ); ?></span>
						<span class="rsp-home-featured__more">Read the breakdown &rarr;</span>
					</div>
				</a>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
	</div>
<?php endif; ?>

<div class="rsp-home-portals">
	<div class="rsp-home-portals__inner">
		<div class="rsp-home-portals__grid">
			<?php foreach ( $portals as $portal ) : ?>
				<a href="<?php echo esc_url( $portal['url'] ); ?>" class="rsp-home-portal">
					<div class="rsp-home-portal__bar"></div>
					<div class="rsp-home-portal__body">
						<span class="rsp-home-portal__title"><?php echo esc_html( $portal['title'] ); ?></span>
						<span class="rsp-home-portal__desc"><?php echo esc_html( $portal['desc'] ); ?></span>
						<span class="rsp-home-portal__cta"><?php echo wp_kses_post( $portal['cta'] ); ?> &rarr;</span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<div class="rsp-home-pitch">
	<div class="rsp-home-pitch__inner">
		<div class="rsp-home-pitch__copy">
			<span class="rsp-home-pitch__kicker">What is the RSP?</span>
			<h2 class="rsp-home-pitch__heading">Two decades of tape, one consistent process</h2>
			<p class="rsp-home-pitch__text">The Rookie Scouting Portfolio grades every notable skill-position prospect through the same film-based framework Matt built in 2005 — profiles that go far deeper than jargon and scout-speak, with rankings, tiers, and post-draft fit. A portion of every sale is donated to Darkness to Light.</p>
			<a href="<?php echo esc_url( $buy_url ); ?>" class="rsp-home-pitch__cta">See what's inside</a>
		</div>
		<div class="rsp-home-pitch__stats">
			<?php foreach ( $output_stats as $stat ) : ?>
				<div class="rsp-home-pitch__stat">
					<span class="rsp-home-pitch__stat-value"><?php echo esc_html( $stat['value'] ); ?></span>
					<span class="rsp-home-pitch__stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<?php if ( $latest->have_posts() ) : ?>
	<div class="rsp-home-latest">
		<div class="rsp-home-latest__inner">
			<div class="rsp-home-section-label">
				<span>Latest from the Film Room</span>
				<div class="rsp-home-section-label__rule"></div>
				<a href="<?php echo esc_url( $resources_url ); ?>" class="rsp-home-latest__viewall">View all &rarr;</a>
			</div>
			<div class="rsp-home-latest__grid">
				<?php while ( $latest->have_posts() ) : $latest->the_post(); ?>
					<a href="<?php the_permalink(); ?>" class="rsp-home-latest-card">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium_large', array( 'class' => 'rsp-home-latest-card__image' ) ); ?>
						<?php else : ?>
							<div class="rsp-home-latest-card__image rsp-home-latest-card__image--placeholder"></div>
						<?php endif; ?>
						<?php $latest_cats = get_the_category(); ?>
						<?php if ( ! empty( $latest_cats ) ) : ?>
							<span class="rsp-home-latest-card__kicker"><?php echo esc_html( $latest_cats[0]->name ); ?></span>
						<?php endif; ?>
						<span class="rsp-home-latest-card__title"><?php the_title(); ?></span>
						<span class="rsp-home-latest-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></span>
					</a>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<div class="rsp-home-cta">
	<div class="rsp-home-cta__bg" aria-hidden="true"></div>
	<div class="rsp-home-cta__gradient" aria-hidden="true"></div>
	<div class="rsp-home-cta__inner">
		<span class="rsp-home-cta__eyebrow">Published April 1</span>
		<h2 class="rsp-home-cta__heading">Get your seat for the 2026 class</h2>
		<p class="rsp-home-cta__subcopy">Join the scouts, media, and fantasy players who rely on the RSP every draft season.</p>
		<a href="<?php echo esc_url( $buy_url ); ?>" class="rsp-home-cta__button" data-sheen>Buy the 2026 RSP</a>
	</div>
</div>

<?php get_footer(); ?>
