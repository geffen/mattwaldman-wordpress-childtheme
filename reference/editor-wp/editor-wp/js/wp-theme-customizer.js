(function($)
{


// ====================================================================================================================
// ====================================================================================================================


	wp.customize( 'blogname', function( value )
	{
		value.bind( function( to )
		{
			$( 'h1.site-title a' ).html( to );
		});
	});
	
	
	wp.customize( 'blogdescription', function( value )
	{
		value.bind( function( to )
		{
			$( 'header  p.site-description' ).html( to );
		});
	});


// ====================================================================================================================
// ====================================================================================================================


	wp.customize( 'setting_link_color', function( value )
	{
		value.bind( function( to )
		{
			var styleCss = '<style type="text/css">' + 
								
								'a { color: ' + to + '; }' +
								
								'.owl-theme .owl-nav div { background: ' + to + '; }' +
								
							'</style>';
			
			
			$( 'body' ).append( styleCss );
		});
	});
	
	
	wp.customize( 'setting_link_hover_color', function( value )
	{
		value.bind( function( to )
		{
			var styleCss = '<style type="text/css">' + 
								
								'a:hover, .nav-menu ul li a:hover, .nav-menu ul li:hover > a, .nav-menu ul li a.selected { color: ' + to + '; }' +
								
								'.owl-theme .owl-nav div:hover { background: ' + to + '; }' +
								
							'</style>';
			
			
			$( 'body' ).append( styleCss );
		});
	});


// ====================================================================================================================
// ====================================================================================================================


 	wp.customize( 'setting_content_font', function( value )
	{
		value.bind( function( to )
		{
			$( 'body' ).append( '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' + to + '">' );
			
			
			var styleCss = '<style type="text/css">' + 
								
								'body, input, textarea, select, button { font-family: "' + to + '", serif; }' +
								
							'</style>';
			
			
			$( 'body' ).append( styleCss );
		});
	});
	
	
 	wp.customize( 'setting_heading_font', function( value )
	{
		value.bind( function( to )
		{
			$( 'body' ).append( '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' + to + '">' );
			
			
			var styleCss = '<style type="text/css">' + 
								
								'h1, h2, h3, h4, h5, h6, .entry-meta, .entry-header, .navigation, .post-pagination, tr th, dl dt, .header-links, input[type=submit], input[type=button], button, a.button, .button, label, .comment .reply, .comment-meta, .yarpp-thumbnail-title, .tab-titles, .skill-unit .bar .progress span, .owl-theme .owl-nav [class*="owl-"], .widget_categories ul li.cat-item a { font-family: "' + to + '", sans-serif; }' +
								
							'</style>';
			
			
			$( 'body' ).append( styleCss );
		});
	});
	
	
 	wp.customize( 'setting_menu_font', function( value )
	{
		value.bind( function( to )
		{
			$( 'body' ).append( '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' + to + '">' );
			
			
			var styleCss = '<style type="text/css">' + 
								
								'.nav-menu { font-family: "' + to + '", sans-serif; }' +
								
							'</style>';
			
			
			$( 'body' ).append( styleCss );
		});
	});
	
	
 	wp.customize( 'setting_text_logo_font', function( value )
	{
		value.bind( function( to )
		{
			$( 'body' ).append( '<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=' + to + '">' );
			
			
			var styleCss = '<style type="text/css">' + 
								
								'.site-header .site-title { font-family: "' + to + '", serif; }' +
								
							'</style>';
			
			
			$( 'body' ).append( styleCss );
		});
	});


// ====================================================================================================================
// ====================================================================================================================


 	wp.customize( 'setting_content_width', function( value )
	{
		value.bind( function( to )
		{
			var styleCss = '<style type="text/css">' + 
								
								'.layout-fixed { max-width: ' + to + '; }' +
								
							'</style>';
			
			
			$( 'body' ).append( styleCss );
		});
	});


// ====================================================================================================================
// ====================================================================================================================


 	wp.customize( 'pixelwars__setting_custom_css', function( value )
	{
		value.bind( function( to )
		{
			var styleCss = '<style type="text/css">' + to + '</style>';
			
			
			$( 'body' ).append( styleCss );
		});
	});


// ====================================================================================================================
// ====================================================================================================================


})(jQuery);