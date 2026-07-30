<?php
/**
 * Template Name: About
 *
 * Bespoke bio/about page — light hero, sticky-label bio section, a plain
 * stat row, a bordered "By the Numbers" stat grid, and a dark contact
 * band. Fully template-driven (no the_content()), same as
 * template-buy-the-rsp.php — hand-authored copy, not editor content.
 *
 * Design explicitly drops the "Invisible Thread" timeline block that
 * appears (unused) in the design file's own data — do not add it back.
 *
 * Shares the dark page-frame + light-hero pattern with the Buy the RSP
 * page, but uses its own rsp-about-* CSS classes rather than reusing
 * Buy's, matching how home.php duplicated rather than reused the article
 * template's related-post-card CSS (see CLAUDE.md/session precedent).
 */

get_header();

$stats = array(
	array( 'value' => '2006', 'label' => 'First RSP published — every April 1 since' ),
	array( 'value' => '164', 'label' => 'Skill prospects profiled in the 2026 edition' ),
	array( 'value' => '1,267', 'label' => 'Pages of film-based evaluation' ),
	array( 'value' => '20+', 'label' => 'Years in the fantasy football industry' ),
);

$output_stats = array(
	array( 'value' => '900+', 'label' => 'Film breakdowns' ),
	array( 'value' => '2,000+', 'label' => 'Articles published' ),
	array( 'value' => '100+', 'label' => 'Podcast episodes' ),
	array( 'value' => 'April 1', 'label' => 'New RSP every year' ),
);

$links = array(
	array( 'label' => 'RSP Website', 'href' => 'https://mattwaldmanrsp.com' ),
	array( 'label' => 'Buy the RSP', 'href' => 'https://mattwaldman.com' ),
	array( 'label' => 'X / Twitter', 'href' => 'https://x.com/mattwaldman' ),
	array( 'label' => 'Bluesky', 'href' => 'https://bsky.app/profile/mattwaldman.bsky.social' ),
);
?>

<div class="rsp-about-hero">
	<div class="rsp-about-hero__inner">
		<div class="rsp-about-hero__kicker-row">
			<div class="rsp-about-hero__kicker-rule"></div>
			<span class="rsp-about-hero__kicker">About the RSP</span>
		</div>
		<h1 class="rsp-about-hero__title">Matt Waldman</h1>
		<p class="rsp-about-hero__summary">Football writer and analyst. Creator of the most comprehensive evaluation of NFL rookie prospects at the skill positions in the game.</p>
	</div>
</div>

<div class="rsp-about-bio">
	<div class="rsp-about-bio__grid">
		<div class="rsp-about-bio__label">
			<span>The Analyst</span>
			<div class="rsp-about-bio__label-rule"></div>
		</div>
		<div class="rsp-about-bio__copy">
			<p class="rsp-about-bio__lead">His annual publication, <span class="rsp-about-bio__accent">The Rookie Scouting Portfolio</span>, is the most comprehensive evaluation of NFL Rookie Prospects at the skill positions&nbsp;— QB, RB, WR, and TE&nbsp;— available anywhere.</p>
			<p>Matt's work is used by Dan Hatman's Scouting Academy as part of its curriculum, and his clientele includes scouts, media, and fantasy players alike. The RSP was featured in <em>The New York Times</em> throughout the month of April in the years leading to the 2011–2013 NFL Drafts.</p>
			<p>Matt is a Senior Staff Writer at Footballguys.com, where he pens The Weekly Gut Check and The Top 10. His inventive and risk-friendly fantasy analysis is also featured annually in fantasy sports publications. He began the Football Outsiders column "Futures," which he penned for three years before leaving to pursue player evaluation full time.</p>
		</div>
	</div>
</div>

<div class="rsp-about-stats">
	<div class="rsp-about-stats__grid">
		<?php foreach ( $stats as $stat ) : ?>
			<div class="rsp-about-stats__cell">
				<span class="rsp-about-stats__value"><?php echo esc_html( $stat['value'] ); ?></span>
				<span class="rsp-about-stats__label"><?php echo esc_html( $stat['label'] ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<div class="rsp-about-output">
	<div class="rsp-about-output__heading-row">
		<span class="rsp-about-output__heading">By the Numbers</span>
		<div class="rsp-about-output__rule"></div>
	</div>
	<div class="rsp-about-output__grid">
		<?php foreach ( $output_stats as $stat ) : ?>
			<div class="rsp-about-output__cell">
				<span class="rsp-about-output__value"><?php echo esc_html( $stat['value'] ); ?></span>
				<span class="rsp-about-output__label"><?php echo esc_html( $stat['label'] ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</div>

<div class="rsp-about-contact">
	<div class="rsp-about-contact__bg" aria-hidden="true"></div>
	<div class="rsp-about-contact__gradient" aria-hidden="true"></div>
	<div class="rsp-about-contact__inner">
		<span class="rsp-about-contact__eyebrow">Get in Touch</span>
		<h2 class="rsp-about-contact__heading">A seat in the film room</h2>
		<p class="rsp-about-contact__subcopy">Questions about the publication, media requests, or scouting inquiries&nbsp;— reach out directly and Matt will get back to you.</p>
		<a href="mailto:mattwaldmanrsp@gmail.com" class="rsp-about-contact__email">mattwaldmanrsp@gmail.com</a>
		<div class="rsp-about-contact__links">
			<?php foreach ( $links as $link ) : ?>
				<a href="<?php echo esc_url( $link['href'] ); ?>" class="rsp-about-contact__link"><?php echo esc_html( $link['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
