        <footer id="colophon" class="site-footer" role="contentinfo">
			<div class="layout-medium">
				<?php
					if ( is_active_sidebar( 'pixelwars_footer_social_icons' ) )
					{
						?>
							<div class="footer-social">
								<?php
									dynamic_sidebar( 'pixelwars_footer_social_icons' );
								?>
							</div>
						<?php
					}
				?>
				
				
				<?php
					$footer_widget_locations = get_option( 'footer_widget_locations', 'No' );
					
					if ( $footer_widget_locations == 'Yes' )
					{
						?>
							<div class="footer-sidebar widget-area" role="complementary">
								<?php
									if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'pixelwars_footer_1' ) ) :
									endif;
								?>
								
								
								<?php
									if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'pixelwars_footer_2' ) ) :
									endif;
								?>
								
								
								<?php
									if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'pixelwars_footer_3' ) ) :
									endif;
								?>
								
								
								<?php
									if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( 'pixelwars_footer_4' ) ) :
									endif;
								?>
							</div>
						<?php
					}
				?>
			</div>
			
			
			<div class="site-info">
				<div class="layout-medium">
					<?php
						dynamic_sidebar( 'pixelwars_footer_5' );
					?>
				</div>
			</div>
		</footer>
	</div>
    
	
	<?php
		wp_footer();
	?>
</body>
</html>