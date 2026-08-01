<?php
/**
 * Template Name: Member Area
 *
 * The members-only areas — RSP Draft Guide and Ranking & Projections —
 * rendered as ONE template with two states at the SAME URL:
 *
 *   locked   what a logged-out / non-member visitor sees: the pitch, a
 *            "what's inside" grid, and sign-in / buy CTAs
 *   unlocked what an entitled member sees: their download area and the
 *            gated post listing
 *
 * Same URL for both on purpose. Separate /…-members/ URLs would mean
 * duplicate content, links that break the moment someone's access
 * changes, and two places for the gate to be got wrong.
 *
 * The locked state is a PHP branch, not CSS — no member content is
 * emitted into the response at all when locked. Keep it that way.
 *
 * Which area a page is comes from editor_child_detect_member_area():
 * the page's slug, or an "rsp_member_area" custom field to override it.
 * Access is decided solely by editor_child_user_has_access() — see the
 * header of inc/members.php for why that indirection exists (aMember
 * lives on a different server, so the real check gets filtered in later).
 *
 * Editor-managed content on the page:
 *   post_content   the public pitch shown in the locked state
 *   rsp_downloads  the member download list, "Label|URL|Note" per line
 *
 * Uses the dark page column (registered in editor_child_body_classes()).
 */

get_header();

while ( have_posts() ) :
	the_post();

	$area_key = editor_child_detect_member_area( get_the_ID() );
	$area     = $area_key ? editor_child_get_member_area( $area_key ) : false;
	$has_access = $area_key ? editor_child_user_has_access( $area_key ) : false;

	/*
	 * Set when the visitor was bounced here from a gated post or
	 * category by editor_child_guard_gated_singles().
	 */
	$was_bounced = isset( $_GET['locked'] );
	?>

	<div class="rsp-member-hero">
		<div class="rsp-member-hero__inner">
			<div class="rsp-member-hero__kicker-row">
				<div class="rsp-member-hero__kicker-rule"></div>
				<span class="rsp-member-hero__kicker"><?php echo esc_html( $area ? $area['kicker'] : 'Members' ); ?></span>
				<?php if ( $area ) : ?>
					<span class="rsp-member-hero__badge<?php echo $has_access ? ' is-unlocked' : ''; ?>">
						<?php echo $has_access ? 'Unlocked' : 'Members only'; ?>
					</span>
				<?php endif; ?>
			</div>

			<h1 class="rsp-member-hero__title"><?php the_title(); ?></h1>

			<?php if ( $area ) : ?>
				<p class="rsp-member-hero__summary"><?php echo esc_html( $area['summary'] ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( ! $area ) : ?>

		<?php /* Misconfigured page — say so plainly rather than failing open. */ ?>
		<div class="rsp-member">
			<p class="rsp-member__empty">
				This page isn&rsquo;t linked to a members area yet. Give it the slug
				<code>rsp-draft-guide</code> or <code>ranking-and-projections</code>, or set an
				<code>rsp_member_area</code> custom field to <code>draft-guide</code> or <code>rankings</code>.
			</p>
		</div>

	<?php elseif ( $has_access ) : ?>

		<?php // ---------------- UNLOCKED: the member view ---------------- ?>
		<div class="rsp-member">

			<?php $current_user = wp_get_current_user(); ?>
			<div class="rsp-member__welcome">
				<div class="rsp-member__welcome-text">
					<span class="rsp-member__welcome-label">Signed in</span>
					<span class="rsp-member__welcome-name"><?php echo esc_html( $current_user->display_name ); ?></span>
				</div>
				<a class="rsp-member__signout" href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>">Sign out</a>
			</div>

			<?php $downloads = editor_child_get_member_downloads( get_the_ID() ); ?>

			<section class="rsp-member__section">
				<div class="rsp-member__section-head">
					<h2 class="rsp-member__section-title">Your downloads</h2>
					<div class="rsp-member__section-rule"></div>
				</div>

				<?php if ( $downloads ) : ?>
					<ul class="rsp-member__downloads">
						<?php foreach ( $downloads as $download ) : ?>
							<li class="rsp-member__download">
								<a class="rsp-member__download-link" href="<?php echo esc_url( $download['url'] ); ?>">
									<span class="rsp-member__download-icon" aria-hidden="true">&darr;</span>
									<span class="rsp-member__download-body">
										<span class="rsp-member__download-label"><?php echo esc_html( $download['label'] ); ?></span>
										<?php if ( $download['note'] ) : ?>
											<span class="rsp-member__download-note"><?php echo esc_html( $download['note'] ); ?></span>
										<?php endif; ?>
									</span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else : ?>
					<p class="rsp-member__empty">
						No downloads have been posted here yet. Your editions are always available in
						<a href="<?php echo esc_url( editor_child_amember_base_url() ); ?>">your account at mattwaldman.com</a>.
					</p>
				<?php endif; ?>
			</section>

			<?php
				$paged   = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
				$listing = editor_child_member_area_query( $area_key, get_option( 'posts_per_page' ), $paged );
			?>

			<?php if ( $listing->have_posts() ) : ?>
				<section class="rsp-member__section">
					<div class="rsp-member__section-head">
						<h2 class="rsp-member__section-title">Members&rsquo; updates</h2>
						<div class="rsp-member__section-rule"></div>
					</div>

					<div class="rsp-index__grid">
						<?php while ( $listing->have_posts() ) : $listing->the_post(); ?>
							<a href="<?php the_permalink(); ?>" class="rsp-post-card">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'medium_large', array( 'class' => 'rsp-post-card__image' ) ); ?>
								<?php else : ?>
									<div class="rsp-post-card__image rsp-post-card__image--placeholder"></div>
								<?php endif; ?>
								<div class="rsp-post-card__meta">
									<span class="rsp-post-card__cat"><?php echo esc_html( get_the_date() ); ?></span>
									<span class="rsp-post-card__title"><?php the_title(); ?></span>
								</div>
							</a>
						<?php endwhile; ?>
					</div>

					<?php
						// 'plain' + a .nav-links wrapper, matching the pagination
						// pattern in template-category-listing.php.
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
				</section>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		</div>

	<?php else : ?>

		<?php // ---------------- LOCKED: the public view ---------------- ?>
		<div class="rsp-member">

			<?php if ( $was_bounced ) : ?>
				<div class="rsp-member__notice">
					That page is part of <strong><?php echo esc_html( $area['label'] ); ?></strong>, which is available to members only.
				</div>
			<?php endif; ?>

			<?php if ( trim( wp_strip_all_tags( get_the_content() ) ) !== '' ) : ?>
				<div class="rsp-member__intro">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

			<div class="rsp-gate">
				<div class="rsp-gate__icon" aria-hidden="true">&#128274;</div>
				<h2 class="rsp-gate__title">This area is for RSP members</h2>
				<p class="rsp-gate__text">
					<?php echo esc_html( $area['label'] ); ?> is included with your RSP purchase.
					Already bought it? Sign in below. If not, the RSP is available now.
				</p>

				<div class="rsp-gate__actions">
					<a class="rsp-gate__cta" href="<?php echo esc_url( editor_child_member_login_url( get_permalink() ) ); ?>">Member Login</a>
					<a class="rsp-gate__cta rsp-gate__cta--ghost" href="<?php echo esc_url( editor_child_get_buy_rsp_url() ); ?>">Buy the RSP</a>
				</div>

				<p class="rsp-gate__note">
					Accounts are created at purchase through
					<a href="<?php echo esc_url( editor_child_amember_signup_url() ); ?>">mattwaldman.com</a>.
				</p>
			</div>

			<?php
				/*
				 * Teaser: titles and dates ONLY. Deliberately does not touch
				 * the_content()/the_excerpt() — nothing from the post body is
				 * allowed into a locked response.
				 */
				$teaser = editor_child_member_area_query( $area_key, 5, 1 );
			?>

			<?php if ( $teaser->have_posts() ) : ?>
				<section class="rsp-member__section">
					<div class="rsp-member__section-head">
						<h2 class="rsp-member__section-title">Inside right now</h2>
						<div class="rsp-member__section-rule"></div>
					</div>

					<ul class="rsp-gate__teaser">
						<?php while ( $teaser->have_posts() ) : $teaser->the_post(); ?>
							<li class="rsp-gate__teaser-item">
								<span class="rsp-gate__teaser-lock" aria-hidden="true">&#128274;</span>
								<span class="rsp-gate__teaser-title"><?php the_title(); ?></span>
								<span class="rsp-gate__teaser-date"><?php echo esc_html( get_the_date() ); ?></span>
							</li>
						<?php endwhile; ?>
					</ul>
				</section>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		</div>

	<?php endif; ?>

	<?php
endwhile;

get_footer();
