<div id="secondary" class="widget-area sidebar" role="complementary">
	<?php
		if ( is_page() )
		{
			$sidebar = get_option( $post->ID . 'my_sidebar', 'pixelwars_page_sidebar' );
			
			dynamic_sidebar( $sidebar );
		}
		elseif ( is_singular( 'post' ) )
		{
			if ( is_active_sidebar( 'pixelwars_post_sidebar' ) )
			{
				dynamic_sidebar( 'pixelwars_post_sidebar' );
			}
			else
			{
				dynamic_sidebar( 'pixelwars_blog_sidebar' );
			}
		}
		else
		{
			dynamic_sidebar( 'pixelwars_blog_sidebar' );
		}
	?>
</div>