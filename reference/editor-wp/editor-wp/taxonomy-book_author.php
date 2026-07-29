<?php
	$pixelwars__books_page = get_option( 'pixelwars__books_page', "" );
	
	if ( $pixelwars__books_page != "" )
	{
		wp_redirect( esc_url( home_url( '/' ) . '?page_id=' . $pixelwars__books_page ) ); exit;
	}
	else
	{
		wp_redirect( esc_url( home_url( '/' ) ) ); exit;
	}
?>