<form role="search" id="searchform" class="searchform" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<label class="screen-reader-text" for="s"><?php echo __( 'Search for:', 'editor' ); ?></label>
		
		<input type="text" id="s" name="s" required="required" placeholder="<?php echo __( 'type and hit enter ...', 'editor' ); ?>">
		
		<input type="submit" id="searchsubmit" style="display: none;" value="<?php echo __( 'Search', 'editor' ); ?>">
	</div>
</form>