<?php
	if ( wp_attachment_is_image() )
	{
		?>
			<nav class="nav-single row">
				<div class="nav-previous col-sm-6">
					<?php
						previous_image_link( false, '<span class="meta-nav">&#8592;</span>' . __( 'PREVIOUS IMAGE', 'editor' ) );
					?>
				</div>
				
				<div class="nav-next col-sm-6">
					<?php
						next_image_link( false, __( 'NEXT IMAGE', 'editor' ) . '<span class="meta-nav">&#8594;</span>' );
					?>
				</div>
			</nav>
		<?php
	}
	elseif ( is_singular( 'portfolio' ) )
	{
		?>
			<nav class="row nav-single">
				<div class="col-sm-6 nav-previous">
					<?php
						next_post_link( '<h4>' . __( 'PREVIOUS PROJECT', 'editor' ) . '</h4>' . '%link', '<span class="meta-nav">&#8592;</span> %title' );
					?>
				</div>
				
				<div class="col-sm-6 nav-next">
					<?php
						previous_post_link( '<h4>' . __( 'NEXT PROJECT', 'editor' ) . '</h4>' . '%link', '%title <span class="meta-nav">&#8594;</span>' );
					?>
				</div>
			</nav>
		<?php
	}
	else
	{
		?>
			<nav class="nav-single row">
				<div class="nav-previous col-sm-6">
					<?php
						previous_post_link( '<h4>' . __( 'PREVIOUS POST', 'editor' ) . '</h4>' . '%link', '<span class="meta-nav">&#8592;</span>' . ' ' . '%title' );
					?>
				</div>
				
				<div class="nav-next col-sm-6">
					<?php
						next_post_link( '<h4>' . __( 'NEXT POST', 'editor' ) . '</h4>' . '%link', '%title' . ' ' . '<span class="meta-nav">&#8594;</span>' );
					?>
				</div>
			</nav>
		<?php
	}
?>