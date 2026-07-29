<?php
/**
 * Dark minimal footer — copyright + tagline, matching the Article Template
 * design's footer. Full replacement of the parent's widget-based footer.php
 * (same rationale as header.php — see CLAUDE.md).
 */
?>
		<footer id="colophon" class="rsp-footer" role="contentinfo">
			<div class="rsp-footer__inner">
				<span class="rsp-footer__copy">&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> Matt Waldman's Rookie Scouting Portfolio. All rights reserved.</span>
				<span class="rsp-footer__tagline">Film-based analysis since 2006</span>
			</div>
		</footer>
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>
