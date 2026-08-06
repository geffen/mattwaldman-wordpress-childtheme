<?php
/**
 * Template Name: Term Index
 *
 * A page that fronts a whole taxonomy: renders whatever intro copy is
 * written in the editor, then a card grid of that taxonomy's terms, each
 * linking to its own archive (which archive.php already renders in the
 * designed card grid).
 *
 * Exists because WordPress has no root archive for a taxonomy — /position/
 * and /draft-class/ both 404, so "by Position" and "by Draft Class" in the
 * nav had nowhere to point. This gives them a real destination and a place
 * for Matt to write intro copy.
 *
 * Used by /player-evaluation/by-position/ and
 * /player-evaluation/by-draft-class/.
 *
 * The taxonomy is matched to the page automatically: first by an optional
 * "rsp_taxonomy" custom field, otherwise by the page's own slug via the
 * $slug_map below. Same convention as template-category-listing.php.
 *
 * Terms are listed with hide_empty => false on purpose. Every rsp_position
 * and rsp_draft_class term currently has 0 posts (nothing has been tagged
 * yet), and a page that renders empty would read as broken rather than as
 * "not populated yet" — so empty terms show, clearly marked.
 */

get_header();

/** Page slug => taxonomy name. Extend when a third taxonomy gets a page. */
$slug_map = array(
	'by-position'     => 'rsp_position',
	'by-draft-class'  => 'rsp_draft_class',
	'position'        => 'rsp_position',
	'draft-class'     => 'rsp_draft_class',
);

/** Per-taxonomy presentation. Falls back to the taxonomy labels. */
$tax_meta = array(
	'rsp_position'    => array(
		'kicker' => 'Player Evaluation',
		'noun'   => 'position',
		'blurb'  => 'Every prospect graded through the same film-based checklist, sorted by the position they play.',
	),
	'rsp_draft_class' => array(
		'kicker' => 'Player Evaluation',
		'noun'   => 'draft class',
		'blurb'  => 'Study a full class the way Matt does — every evaluation from a single draft year in one place.',
	),
);

while ( have_posts() ) :
	the_post();

	$page_slug = get_post_field( 'post_name', get_the_ID() );
	$override  = trim( (string) get_post_meta( get_the_ID(), 'rsp_taxonomy', true ) );

	$taxonomy = $override ? $override : ( isset( $slug_map[ $page_slug ] ) ? $slug_map[ $page_slug ] : '' );

	if ( $taxonomy && ! taxonomy_exists( $taxonomy ) )
	{
		$taxonomy = '';
	}

	$meta = ( $taxonomy && isset( $tax_meta[ $taxonomy ] ) ) ? $tax_meta[ $taxonomy ] : array(
		'kicker' => 'Browse',
		'noun'   => 'section',
		'blurb'  => '',
	);

	$terms = $taxonomy
		? get_terms( array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
		) )
		: array();

	if ( is_wp_error( $terms ) )
	{
		$terms = array();
	}

	$has_intro = trim( wp_strip_all_tags( get_the_content() ) ) !== '';
	?>

	<div class="rsp-page-hero">
		<div class="rsp-page-hero__inner">
			<div class="rsp-page-hero__kicker-row">
				<div class="rsp-page-hero__kicker-rule"></div>
				<span class="rsp-page-hero__kicker"><?php echo esc_html( $meta['kicker'] ); ?></span>
			</div>
			<h1 class="rsp-page-hero__title"><?php the_title(); ?></h1>
			<?php if ( ! $has_intro && $meta['blurb'] ) : ?>
				<p class="rsp-page-hero__summary"><?php echo esc_html( $meta['blurb'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<div class="rsp-index">

		<?php if ( $has_intro ) : ?>
			<div class="rsp-index__intro">
				<?php the_content(); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! $taxonomy ) : ?>

			<p class="rsp-index__empty">
				This page isn&rsquo;t linked to a taxonomy yet. Give it the slug
				<code>by-position</code> or <code>by-draft-class</code>, or set an
				<code>rsp_taxonomy</code> custom field to the taxonomy name
				(<code>rsp_position</code> / <code>rsp_draft_class</code>).
			</p>

		<?php elseif ( ! $terms ) : ?>

			<p class="rsp-index__empty">No <?php echo esc_html( $meta['noun'] ); ?> terms exist yet.</p>

		<?php else : ?>

			<div class="rsp-terms__grid">
				<?php foreach ( $terms as $term ) : ?>
					<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="rsp-term-card<?php echo $term->count ? '' : ' is-empty'; ?>">
						<span class="rsp-term-card__name"><?php echo esc_html( $term->name ); ?></span>

						<span class="rsp-term-card__count">
							<?php
								echo $term->count
									? esc_html( sprintf(
										_n( '%s evaluation', '%s evaluations', $term->count, 'editor-child' ),
										number_format_i18n( $term->count )
									) )
									: esc_html__( 'Nothing tagged yet', 'editor-child' );
							?>
						</span>

						<?php if ( $term->description ) : ?>
							<span class="rsp-term-card__desc"><?php echo esc_html( $term->description ); ?></span>
						<?php endif; ?>

						<span class="rsp-term-card__cta">View <?php echo esc_html( $term->name ); ?> &rarr;</span>
					</a>
				<?php endforeach; ?>
			</div>

		<?php endif; ?>

	</div>

	<?php
endwhile;

get_footer();
