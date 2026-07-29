<?php
	if ( is_category() )
	{
		?>
			<header class="entry-header">
				<h1 class="entry-title">
					<i><?php echo __( 'Category', 'editor' ); ?></i> <span class="cat-title"><?php echo single_cat_title(); ?></span>
				</h1>
			</header>
		<?php
	}
	elseif ( is_tag() )
	{
		?>
			<header class="entry-header">
				<h1 class="entry-title">
					<i><?php echo __( 'Posts tagged', 'editor' ); ?></i> <span class="cat-title"><?php echo single_tag_title(); ?></span>
				</h1>
			</header>
		<?php
	}
	elseif ( is_author() )
	{
		?>
			<header class="entry-header">
				<h1 class="entry-title">
					<i><?php echo __( 'Posts by', 'editor' ); ?></i> <span class="cat-title"><?php the_author(); ?></span>
				</h1>
			</header>
		<?php
	}
	elseif ( is_search() )
	{
		?>
			<header class="entry-header">
				<h1 class="entry-title">
					<i><?php echo __( 'Searched For', 'editor' ); ?></i> <span class="cat-title"><?php the_search_query(); ?></span>
				</h1>
			</header>
		<?php
	}
	elseif ( is_date() )
	{
		?>
			<header class="entry-header">
				<h1 class="entry-title">
					<i><?php echo __( 'Date Archives', 'editor' ); ?></i> <span class="cat-title"><?php
																									if ( is_day() )
																									{
																										printf( get_the_date() );
																									}
																									elseif ( is_month() )
																									{
																										printf( get_the_date( _x( 'F Y', 'monthly archives date format', 'editor' ) ) );
																									}
																									elseif ( is_year() )
																									{
																										printf( get_the_date( _x( 'Y', 'yearly archives date format', 'editor' ) ) );
																									}
																									else
																									{
																										_e( 'Archives', 'editor' );
																									}
																								?></span>
				</h1>
			</header>
		<?php
	}
	elseif ( is_post_type_archive() )
	{
		?>
			<header class="entry-header">
				<h1 class="entry-title">
					<i><?php echo __( 'Archives', 'editor' ); ?></i> <span class="cat-title"><?php echo post_type_archive_title(); ?></span>
				</h1>
			</header>
		<?php
	}
	elseif ( is_archive() )
	{
		?>
			<header class="entry-header">
				<h1 class="entry-title">
					<i><?php echo __( 'Archives', 'editor' ); ?></i> <span class="cat-title"><?php echo single_post_title(); ?></span>
				</h1>
			</header>
		<?php
	}
	else
	{
		if ( ! is_front_page() )
		{
			?>
				<header class="entry-header">
					<h1 class="entry-title"><?php single_post_title(); ?></h1>
				</header>
			<?php
		}
	}
?>