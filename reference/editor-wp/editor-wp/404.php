<?php
	get_header();
?>


<div id="main" class="site-main">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="layout-medium">
				<article class="hentry page page-404">
					<header class="entry-header">
						<h1 class="entry-title"><?php echo __( 'Page Not Found', 'editor' ); ?></h1>
					</header>
					
					<div class="entry-content">
						<div class="http-alert">
							<h1>
								<i class="pw-icon-paper-plane"></i>
							</h1>
						</div>
						
						<p><?php echo __( 'You can search for what you are looking.', 'editor' ); ?></p>
						
						<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<input type="text" id="search-big" name="s" placeholder="<?php echo __( 'enter keyword', 'editor' ); ?>">
						</form>
					</div>
				</article>
			 </div>
		</div>
	</div>
</div>


<?php
	get_footer();
?>