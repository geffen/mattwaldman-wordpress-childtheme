<?php
/**
 * Template Name: Member Login
 *
 * Light hero + two-column body (form card left, "How access works" steps
 * right), from `Member Login.dc.html`. Collapses to one column below
 * 860px. Uses the dark page column, so it's registered in
 * editor_child_body_classes().
 *
 * The design prototype's form is static ("no auth wired in the
 * prototype"), but a sign-in box that doesn't sign anyone in is worse
 * than none — so this posts to WordPress's own wp-login.php with the
 * standard log / pwd / rememberme / redirect_to fields. No credential
 * handling of our own: WordPress does the authenticating.
 *
 * Failed logins are rendered by wp-login.php itself rather than back on
 * this page; that's core behaviour and fine unless Matt wants inline
 * errors later.
 *
 * Accepts a ?redirect_to= so a locked members area can send a visitor
 * here and get them back afterwards. That value arrives from the URL and
 * is therefore untrusted — it is run through wp_validate_redirect()
 * against the site's own host below, so it can never be used to bounce
 * someone off-site after login.
 */

get_header();

/*
 * Where to send the visitor once they're signed in. Defaults to this
 * page. wp_validate_redirect() falls back to the default for anything
 * pointing at another host.
 */
$default_redirect = get_permalink( get_queried_object_id() );
$requested        = isset( $_GET['redirect_to'] ) ? wp_unslash( $_GET['redirect_to'] ) : '';
$redirect_to      = $requested
	? wp_validate_redirect( $requested, $default_redirect )
	: $default_redirect;

/*
 * If they arrived from a members area, name it — "Sign in to reach the
 * RSP Draft Guide" beats a bare login box. Matched by comparing the
 * validated redirect against each area's page URL.
 */
$origin_area = false;

foreach ( editor_child_member_areas() as $area )
{
	$area_page = get_page_by_path( $area['page_slug'] );

	if ( $area_page && false !== strpos( $redirect_to, (string) get_permalink( $area_page ) ) )
	{
		$origin_area = $area;
		break;
	}
}

$steps = array(
	array(
		'n'     => '1',
		'title' => 'Buy the RSP',
		'body'  => 'Choose your package and check out securely through Stripe or PayPal. Your account is created at purchase.',
	),
	array(
		'n'     => '2',
		'title' => 'Sign in here',
		'body'  => 'Use the username and password tied to your purchase to access your personal download area.',
	),
	array(
		'n'     => '3',
		'title' => 'Download anytime',
		'body'  => 'Grab the April release, the May post-draft update, and any past editions you own — as often as you like.',
	),
);

while ( have_posts() ) :
	the_post();
	?>

	<div class="rsp-login-hero">
		<div class="rsp-login-hero__inner">
			<div class="rsp-login-hero__kicker-row">
				<div class="rsp-login-hero__kicker-rule"></div>
				<span class="rsp-login-hero__kicker">Members</span>
			</div>
			<h1 class="rsp-login-hero__title"><?php the_title(); ?></h1>
			<p class="rsp-login-hero__summary">
				<?php if ( $origin_area && ! is_user_logged_in() ) : ?>
					Sign in to reach <strong><?php echo esc_html( $origin_area['label'] ); ?></strong> and every edition of the RSP you own.
				<?php else : ?>
					Sign in to reach your download area and every edition of the RSP you own.
				<?php endif; ?>
			</p>
		</div>
	</div>

	<div class="rsp-login">
		<div class="rsp-login__grid">

			<div class="rsp-login__card">
				<?php if ( is_user_logged_in() ) : ?>
					<?php
						$current_user = wp_get_current_user();
						$all_areas    = editor_child_member_areas();
						$unlocked     = editor_child_accessible_member_areas();
						$locked       = array_diff_key( $all_areas, $unlocked );
					?>
					<p class="rsp-login__signed-in">You're signed in as <strong><?php echo esc_html( $current_user->display_name ); ?></strong>.</p>

					<?php
						/*
						 * Send a signed-in member to the areas they actually
						 * bought, not to wp-admin's profile screen — members
						 * have no business in the dashboard.
						 */
					?>
					<?php if ( $unlocked ) : ?>
						<div class="rsp-login__areas">
							<?php foreach ( $unlocked as $area ) : ?>
								<?php $area_page = get_page_by_path( $area['page_slug'] ); ?>
								<?php if ( $area_page ) : ?>
									<a class="rsp-login__area" href="<?php echo esc_url( get_permalink( $area_page ) ); ?>">
										<span class="rsp-login__area-label"><?php echo esc_html( $area['label'] ); ?></span>
										<span class="rsp-login__area-go" aria-hidden="true">&rarr;</span>
									</a>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<p class="rsp-login__notice">
							Your account doesn&rsquo;t include a members area yet. If you&rsquo;ve just purchased,
							email <a href="mailto:mattwaldmanrsp@gmail.com">mattwaldmanrsp@gmail.com</a> and Matt will get you set up.
						</p>
					<?php endif; ?>

					<?php if ( $locked ) : ?>
						<p class="rsp-login__locked-note">
							Not included with your account:
							<?php
								$names = array();

								foreach ( $locked as $area )
								{
									$names[] = esc_html( $area['label'] );
								}

								echo implode( ', ', $names );
							?>
							&mdash; <a href="<?php echo esc_url( editor_child_get_buy_rsp_url() ); ?>">see what&rsquo;s available</a>.
						</p>
					<?php endif; ?>

					<div class="rsp-login__foot">
						<span><a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>">Sign out</a></span>
					</div>
				<?php else : ?>
					<form class="rsp-login__form" method="post" action="<?php echo esc_url( wp_login_url() ); ?>">
						<div class="rsp-login__field">
							<label class="rsp-login__label" for="rsp-user">Username or Email</label>
							<input class="rsp-login__input" type="text" name="log" id="rsp-user" placeholder="you@example.com" autocomplete="username" required>
						</div>

						<div class="rsp-login__field">
							<div class="rsp-login__label-row">
								<label class="rsp-login__label" for="rsp-pass">Password</label>
								<a class="rsp-login__forgot" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">Forgot?</a>
							</div>
							<input class="rsp-login__input" type="password" name="pwd" id="rsp-pass" placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" autocomplete="current-password" required>
						</div>

						<label class="rsp-login__remember">
							<input type="checkbox" name="rememberme" value="forever">
							Keep me signed in
						</label>

						<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect_to ); ?>">

						<button type="submit" class="rsp-login__submit">Sign In</button>
					</form>

					<div class="rsp-login__foot">
						<span>New to the RSP? <a href="<?php echo esc_url( editor_child_amember_signup_url() ); ?>">Buy the RSP</a> to create your account.</span>
					</div>
				<?php endif; ?>
			</div>

			<div class="rsp-login__side">
				<div class="rsp-login__side-head">
					<span class="rsp-login__side-label">How access works</span>
					<div class="rsp-login__side-rule"></div>
				</div>

				<div class="rsp-login__steps">
					<?php foreach ( $steps as $step ) : ?>
						<div class="rsp-login__step">
							<span class="rsp-login__step-n"><?php echo esc_html( $step['n'] ); ?></span>
							<div class="rsp-login__step-body">
								<span class="rsp-login__step-title"><?php echo esc_html( $step['title'] ); ?></span>
								<span class="rsp-login__step-text"><?php echo esc_html( $step['body'] ); ?></span>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="rsp-login__support">
					<p>Trouble signing in or didn't receive your download? Email <a href="mailto:mattwaldmanrsp@gmail.com">mattwaldmanrsp@gmail.com</a> and Matt will get you sorted.</p>
				</div>
			</div>

		</div>
	</div>

	<?php
endwhile;

get_footer();
