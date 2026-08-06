<?php
/**
 * Template Name: Buy the RSP
 *
 * Bespoke sales page — hero, pricing packages, "what's inside" stat band,
 * testimonials, CTA strip. Fully template-driven (no the_content()); this
 * is a hand-authored marketing page, not post-driven content, so package
 * pricing/copy lives here rather than in the editor.
 *
 * Uses a dark page-frame background instead of the site's default light
 * one — see the .rsp-page-dark-frame body class (applied via
 * editor_child_body_classes() in functions.php) and its #page override
 * in style.css. Scoped to this template only.
 *
 * All purchase CTAs link out to https://mattwaldman.com for checkout,
 * matching the pattern already used in the article template's pull-quote
 * and sidebar.
 */

get_header();

$packages = array(
	array(
		'name'     => 'RSP Draft Package',
		'price'    => '$21.95',
		'badge'    => 'Most Popular',
		'featured' => true,
		'desc'     => 'The full 2026 publication — every rookie skill-position evaluation, released April 1 with a post-draft May update.',
		'features' => array(
			'164 skill prospects graded',
			'100+ narrative scouting reports',
			'1,267 pages of film analysis',
			'April release + May post-draft update',
		),
		'cta' => 'Buy the RSP',
	),
	array(
		'name'     => 'Dynasty Rankings & Projections',
		'price'    => '$24.95',
		'badge'    => '',
		'featured' => false,
		'desc'     => 'The Draft Package paired with dynasty rankings and statistical projections for incoming rookies.',
		'features' => array(
			'Everything in the Draft Package',
			'Dynasty rookie rankings',
			'Statistical projections',
			'Best value for dynasty leagues',
		),
		'cta' => 'Add to Cart',
	),
	array(
		'name'     => 'Past Editions',
		'price'    => '$9.95',
		'badge'    => '',
		'featured' => false,
		'desc'     => 'Any prior edition back to the very first RSP in 2006 — a film-based record of two decades of draft classes.',
		'features' => array(
			'Every edition, 2006–2025',
			'Same in-depth evaluations',
			'Instant download',
			'Study a class in hindsight',
		),
		'cta' => 'Browse Archive',
	),
);

$inside_stats = array(
	array( 'value' => '164', 'label' => 'Skill prospects graded' ),
	array( 'value' => '100+', 'label' => 'Narrative scouting reports' ),
	array( 'value' => '1,267', 'label' => 'Pages of evaluation' ),
	array( 'value' => 'QB·RB·WR·TE', 'label' => 'Positions covered' ),
);

$testimonials = array(
	array(
		'quote' => 'The RSP is the gold standard for rookie evaluation. Nobody watches more tape or explains the why behind a grade better than Matt.',
		'name'  => 'Matt Harmon',
		'role'  => 'Yahoo! Sports · Reception Perception',
	),
	array(
		'quote' => 'If you care about how prospects actually play — not just where they get drafted — the Rookie Scouting Portfolio is essential reading every spring.',
		'name'  => 'Doug Farrar',
		'role'  => 'Author & NFL Analyst',
	),
	array(
		'quote' => "Matt’s process-driven approach is exactly what young evaluators should be studying. It’s part of our curriculum for a reason.",
		'name'  => 'Dan Hatman',
		'role'  => 'The Scouting Academy',
	),
);
?>

<div class="rsp-buy-hero">
	<div class="rsp-buy-hero__inner">
		<div class="rsp-buy-hero__kicker-row">
			<div class="rsp-buy-hero__kicker-rule"></div>
			<span class="rsp-buy-hero__kicker">The 2026 Rookie Scouting Portfolio</span>
		</div>
		<h1 class="rsp-buy-hero__title">Buy the RSP</h1>
		<p class="rsp-buy-hero__summary">The most comprehensive film-based evaluation of NFL rookie skill-position prospects available anywhere. Now in its 21st year — download the moment it publishes on April 1.</p>
	</div>
</div>

<div class="rsp-buy-packages">
	<div class="rsp-buy-packages__grid">
		<?php foreach ( $packages as $package ) : ?>
			<div class="rsp-buy-package<?php echo $package['featured'] ? ' rsp-buy-package--featured' : ''; ?>">
				<?php if ( $package['badge'] ) : ?>
					<span class="rsp-buy-package__badge"><?php echo esc_html( $package['badge'] ); ?></span>
				<?php endif; ?>
				<span class="rsp-buy-package__name"><?php echo esc_html( $package['name'] ); ?></span>
				<span class="rsp-buy-package__price"><?php echo esc_html( $package['price'] ); ?></span>
				<p class="rsp-buy-package__desc"><?php echo esc_html( $package['desc'] ); ?></p>
				<div class="rsp-buy-package__features">
					<?php foreach ( $package['features'] as $feature ) : ?>
						<div class="rsp-buy-package__feature">
							<span class="rsp-buy-package__check" aria-hidden="true">&check;</span>
							<span><?php echo esc_html( $feature ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<a href="https://mattwaldman.com" class="rsp-buy-package__cta<?php echo $package['featured'] ? ' rsp-buy-package__cta--featured' : ''; ?>"><?php echo esc_html( $package['cta'] ); ?></a>
			</div>
		<?php endforeach; ?>
	</div>
	<p class="rsp-buy-packages__note">Secure checkout via Stripe or PayPal. Instant access to your download area after purchase.</p>
</div>

<div class="rsp-buy-inside">
	<div class="rsp-buy-inside__inner">
		<h2 class="rsp-buy-inside__heading">What's inside the 2026 RSP</h2>

		<div class="rsp-buy-inside__stats">
			<?php foreach ( $inside_stats as $stat ) : ?>
				<div class="rsp-buy-inside__stat">
					<span class="rsp-buy-inside__stat-value"><?php echo esc_html( $stat['value'] ); ?></span>
					<span class="rsp-buy-inside__stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="rsp-buy-inside__bio">
			<div class="rsp-buy-inside__bio-label">
				<span>Every Edition</span>
				<div class="rsp-buy-inside__bio-rule"></div>
			</div>
			<div class="rsp-buy-inside__bio-copy">
				<p class="rsp-buy-inside__bio-lead">In-depth, narrative scouting reports on the top quarterbacks, running backs, wide receivers, and tight ends — each graded through a consistent, film-based process.</p>
				<p>Every purchase includes the initial April 1 release plus a post-draft update in May, so your rankings reflect landing spots and depth-chart fit. Past editions back to 2006 are available for a fraction of the price.</p>
				<p>A portion of every sale is donated to Darkness to Light, a nonprofit dedicated to preventing child sexual abuse.</p>
			</div>
		</div>
	</div>
</div>

<div class="rsp-buy-testimonials">
	<div class="rsp-buy-testimonials__heading-row">
		<span class="rsp-buy-testimonials__heading">In Their Words</span>
		<div class="rsp-buy-testimonials__rule"></div>
	</div>
	<div class="rsp-buy-testimonials__grid">
		<?php foreach ( $testimonials as $testimonial ) : ?>
			<div class="rsp-buy-testimonial">
				<span class="rsp-buy-testimonial__mark" aria-hidden="true">&ldquo;</span>
				<p class="rsp-buy-testimonial__quote"><?php echo esc_html( $testimonial['quote'] ); ?></p>
				<div class="rsp-buy-testimonial__attribution">
					<span class="rsp-buy-testimonial__name"><?php echo esc_html( $testimonial['name'] ); ?></span>
					<span class="rsp-buy-testimonial__role"><?php echo esc_html( $testimonial['role'] ); ?></span>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<div class="rsp-buy-cta">
	<div class="rsp-buy-cta__bg" aria-hidden="true"></div>
	<div class="rsp-buy-cta__gradient" aria-hidden="true"></div>
	<div class="rsp-buy-cta__inner">
		<span class="rsp-buy-cta__eyebrow">Published April 1</span>
		<h2 class="rsp-buy-cta__heading">Get a seat in the film room</h2>
		<p class="rsp-buy-cta__subcopy">Join the scouts, media, and fantasy players who rely on the RSP every draft season.</p>
		<a href="https://mattwaldman.com" class="rsp-buy-cta__button">Buy the 2026 RSP</a>
	</div>
</div>

<?php get_footer(); ?>
