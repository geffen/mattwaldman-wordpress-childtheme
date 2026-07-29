<?php
	if ( is_search() )
	{
		?>
			<article class="hentry page page-404">
				<header class="entry-header">
					<h1 class="entry-title"><?php echo __( 'NOTHING FOUND', 'editor' ); ?></h1>
				</header>
				
				<div class="entry-content">
					<div class="http-alert">
						<h1>
							<i class="pw-icon-paper-plane"></i>
						</h1>
					</div>
					
					<p><?php echo __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'editor' ); ?></p>
					
					<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="text" id="search-big" name="s" placeholder="<?php echo __( 'enter keyword', 'editor' ); ?>">
					</form>
				</div>
			</article>
		<?php
	}
?>