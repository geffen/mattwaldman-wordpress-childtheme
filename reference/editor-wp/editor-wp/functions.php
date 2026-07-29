<?php

	function pixelwars_theme_enqueue_login()
	{
		wp_enqueue_script( 'jquery' );
	}
	
	add_action( 'login_enqueue_scripts', 'pixelwars_theme_enqueue_login' );
	
	
	function pixelwars_theme_enqueue_admin()
	{
		wp_enqueue_style( 'pixelwars-admin', get_template_directory_uri() . '/admin/admin.css' );
		wp_enqueue_style( 'thickbox' );
		
		
		wp_enqueue_script( 'thickbox' );
		
		if ( isset( $_GET['page'] ) )
		{
			if ( $_GET['page'] != 'codestyling-localization/codestyling-localization.php' )
			{
				wp_enqueue_script( 'media-upload' );
			}
		}
	}
	
	add_action( 'admin_enqueue_scripts', 'pixelwars_theme_enqueue_admin' );
	
	
	function pixelwars_theme_enqueue()
	{
		$extra_char_set = false;
		global $pixelwars_subset;
		$pixelwars_subset = '&subset=';
		
		if ( get_option( 'char_set_latin', false ) ) { $pixelwars_subset .= 'latin,'; $extra_char_set = true; }
		if ( get_option( 'char_set_latin_ext', false ) ) { $pixelwars_subset .= 'latin-ext,'; $extra_char_set = true; }
		if ( get_option( 'char_set_cyrillic', false ) ) { $pixelwars_subset .= 'cyrillic,'; $extra_char_set = true; }
		if ( get_option( 'char_set_cyrillic_ext', false ) ) { $pixelwars_subset .= 'cyrillic-ext,'; $extra_char_set = true; }
		if ( get_option( 'char_set_greek', false ) ) { $pixelwars_subset .= 'greek,'; $extra_char_set = true; }
		if ( get_option( 'char_set_greek_ext', false ) ) { $pixelwars_subset .= 'greek-ext,'; $extra_char_set = true; }
		if ( get_option( 'char_set_vietnamese', false ) ) { $pixelwars_subset .= 'vietnamese,'; $extra_char_set = true; }
		if ( $extra_char_set == false ) { $pixelwars_subset = ""; } else { $pixelwars_subset = substr( $pixelwars_subset, 0, -1 ); }
		
		wp_enqueue_style( 'my-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,800,700,600,300' . $pixelwars_subset, null, null );
		wp_enqueue_style( 'droid-serif', '//fonts.googleapis.com/css?family=Droid+Serif:400,700,700italic,400italic' . $pixelwars_subset, null, null );
		wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', null, null );
		wp_enqueue_style( 'fontello', get_template_directory_uri() . '/css/fonts/fontello/css/fontello.css', null, null );
		wp_enqueue_style( 'uniform', get_template_directory_uri() . '/js/jquery.uniform/uniform.default.css', null, null );
		wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup/magnific-popup.css', null, null );
		wp_enqueue_style( 'fluidbox', get_template_directory_uri() . '/js/jquery.fluidbox/fluidbox.css', null, null );
		wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/js/owl-carousel/owl.carousel.css', null, null );
		wp_enqueue_style( 'selection-sharer', get_template_directory_uri() . '/js/selection-sharer/selection-sharer.css', null, null );
		wp_enqueue_style( 'elastislide', get_template_directory_uri() . '/js/responsive-image-gallery/elastislide.css', null, null );
		wp_enqueue_style( 'main', get_template_directory_uri() . '/css/main.css', null, null );
		wp_enqueue_style( '768', get_template_directory_uri() . '/css/768.css', null, null );
		wp_enqueue_style( '992', get_template_directory_uri() . '/css/992.css', null, null );
		wp_enqueue_style( 'wp-fix', get_template_directory_uri() . '/css/wp-fix.css', null, null );
		wp_enqueue_style( 'theme-style', get_stylesheet_uri(), null, null );
		
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		{
			wp_enqueue_script( 'comment-reply' );
		}
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.min.js', null, null );
		wp_enqueue_script( 'fastclick', get_template_directory_uri() . '/js/fastclick.js', null, null, true );
		wp_enqueue_script( 'fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', null, null, true );
		wp_enqueue_script( 'validate', get_template_directory_uri() . '/js/jquery.validate.min.js', null, null, true );
		wp_enqueue_script( 'uniform', get_template_directory_uri() . '/js/jquery.uniform/jquery.uniform.min.js', null, null, true );
		wp_enqueue_script( 'imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', null, null, true );
		wp_enqueue_script( 'isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', null, null, true );
		wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/js/jquery.magnific-popup/jquery.magnific-popup.min.js', null, null, true );
		wp_enqueue_script( 'fluidbox', get_template_directory_uri() . '/js/jquery.fluidbox/jquery.fluidbox.min.js', null, null, true );
		wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/js/owl-carousel/owl.carousel.min.js', null, null, true );
		wp_enqueue_script( 'readingTime', get_template_directory_uri() . '/js/readingTime.js', null, null, true );
		wp_enqueue_script( 'selection-sharer', get_template_directory_uri() . '/js/selection-sharer/selection-sharer.js', null, null, true );
		wp_enqueue_script( 'r-gallery', get_template_directory_uri() . '/js/responsive-image-gallery/r-gallery.js', null, null, true );
		wp_enqueue_script( 'socialstream', get_template_directory_uri() . '/js/socialstream.jquery.js', null, null, true );
		wp_enqueue_script( 'main', get_template_directory_uri() . '/js/main.js', null, null, true );
		wp_enqueue_script( 'wp-fix', get_template_directory_uri() . '/js/wp-fix.js', null, null, true );
	}
	
	
	function editor_after_setup_theme()
	{
		load_theme_textdomain('editor', get_template_directory() . '/languages');
		register_nav_menus(array('pixelwars_theme_menu_location_1' => esc_html__('Theme Navigation Menu', 'editor')));
		
		add_theme_support('title-tag');
		add_theme_support('automatic-feed-links');
		add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
		add_theme_support('post-formats', array('image', 'gallery', 'audio', 'video', 'quote', 'link', 'chat', 'status', 'aside'));
		add_theme_support('post-thumbnails');
		add_editor_style('custom-editor-style.css');
		
		add_action('wp_enqueue_scripts', 'pixelwars_theme_enqueue');
		
		remove_theme_support('widgets-block-editor');
	}
	
	add_action('after_setup_theme', 'editor_after_setup_theme');


/* ============================================================================================================================================= */


	function pixelwars_exclude_sticky_posts_from_main_query( $query )
	{
		if ( ( $query->is_main_query() ) && ( is_home() ) && ( ! is_admin() ) )
		{
			$sticky_posts = get_option( 'sticky_posts' );
			$query->set( 'post__not_in', $sticky_posts );
		}
	}
	
	
	$pixelwars_main_slider = get_option( 'main_slider', 'No' );
	
	if ( $pixelwars_main_slider == 'Yes' )
	{
		add_action( 'pre_get_posts', 'pixelwars_exclude_sticky_posts_from_main_query' );
	}


/* ============================================================================================================================================= */


	function pixelwars_custom_login_logo_url( $url )
	{
		return esc_url( home_url( '/' ) );
	}
	
	function pixelwars_custom_login_logo_title()
	{
		return get_bloginfo( 'name' );
	}
	
	function pixelwars_theme_login_logo()
	{
		$logo_login_hide = get_option( 'logo_login_hide', false );
		$logo_login = get_option( 'logo_login', "" );
		
		if ( $logo_login_hide )
		{
			echo '<style type="text/css"> h1 { display: none; } </style>';
		}
		else
		{
			if ( $logo_login != "" )
			{
				add_filter( 'login_headerurl', 'pixelwars_custom_login_logo_url' );
				add_filter( 'login_headertitle', 'pixelwars_custom_login_logo_title' );
				
				echo '<style type="text/css">
						h1 a
						{
							background-image: url( "' . $logo_login . '" ) !important;
						}
					</style>';
			}
		}
	}
	
	add_action( 'login_head', 'pixelwars_theme_login_logo' );


/* ============================================================================================================================================= */


	if ( ! isset( $content_width ) )
	{
		$content_width = 740;
	}
	
	
	add_image_size( 'pixelwars_theme_image_size_1', 768 ); // main slider
	add_image_size( 'pixelwars_theme_image_size_2', 1000 ); // blog-regular, post-single
	add_image_size( 'pixelwars_theme_image_size_3', 500 ); // blog-masonry, portfolio-page, department-page
	add_image_size( 'pixelwars_theme_image_size_4', 768, 512, true ); // related posts
	add_image_size( 'pixelwars_theme_image_size_5', 1400 ); // single-gallery
	add_image_size( 'pixelwars_theme_image_size_6', 500, 334, true ); // gallery-page


/* ============================================================================================================================================= */


	function pixelwars_theme_new_post_column_add( $columns )
	{
		return array_merge( $columns, array( 'pixelwars_post_feat_img' => __( 'Featured Image', 'editor' ) ) );
	}
	
	add_filter( 'manage_posts_columns' , 'pixelwars_theme_new_post_column_add' );
	
	
	function pixelwars_theme_new_post_column_show( $column, $post_id )
	{
		if ( $column == 'pixelwars_post_feat_img' )
		{
			if ( has_post_thumbnail() )
			{
				the_post_thumbnail( 'thumbnail' );
			}
		}
	}
	
	add_action( 'manage_posts_custom_column' , 'pixelwars_theme_new_post_column_show', 10, 2 );


/* ============================================================================================================================================= */


	if ( ! function_exists( 'pixelwars_theme_comments' ) ) :
	
		/*
			Template for comments and pingbacks.
			
			To override this walker in a child theme without modifying the comments template
			simply create your own pixelwars_theme_comments(), and that function will be used instead.
			
			Used as a callback by wp_list_comments() for displaying the comments.
		*/
		
		function pixelwars_theme_comments( $comment, $args, $depth )
		{
			$GLOBALS['comment'] = $comment;
			
			
			switch ( $comment->comment_type ) :
			
				case 'pingback' :
				
				
				case 'trackback' :
				
					// Display trackbacks differently than normal comments.
					?>
						<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
							<p>
								<?php
									_e( 'Pingback:', 'editor' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'editor' ), '<span class="edit-link">', '</span>' );
								?>
							</p>
					<?php
				
				break;
				
				
				default :
				
					// Proceed with normal comments.
					global $post;
					
					?>
						<li id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
							<article id="comment-<?php comment_ID(); ?>" class="comment">
								<header class="comment-meta comment-author vcard">
									<?php
										echo get_avatar( $comment, 128 );
										
										
										printf( '<cite class="fn">%1$s %2$s</cite>',
												
												get_comment_author_link(),
												
												// If current post author is also comment author, make it known visually.
												( $comment->user_id === $post->post_author ) ? '<span></span>' : "" );
										
										
										printf( '<span class="comment-date">%3$s</span>',
												
												esc_url( get_comment_link( $comment->comment_ID ) ),
												
												get_comment_time( 'c' ),
												
												/* translators: 1: date, 2: time */
												sprintf( __( '%1$s at %2$s', 'editor' ), get_comment_date(), get_comment_time() ) );
									?>
								</header>
								
								
								<section class="comment-content comment">
									<?php
										if ( '0' == $comment->comment_approved )
										{
											?>
												<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'editor' ); ?></p>
											<?php
										}
									?>
									
									<?php
										comment_text();
									?>
									
									<?php
										edit_comment_link(  __( 'Edit', 'editor' ),
															'<p class="edit-link">',
															'</p>' );
									?>
								</section>
								
								
								<div class="reply">
									<?php
										comment_reply_link( array_merge( $args, array(  'reply_text' => __( 'Reply', 'editor' ),
																						'after'      => ' <span>&darr;</span>',
																						'depth'      => $depth,
																						'max_depth'  => $args['max_depth'] ) ) );
									?>
								</div>
							</article>
					<?php
				
				break;
			
			endswitch;
		}
	
	endif;


/* ============================================================================================================================================= */


	function pixelwars_theme_password_form()
	{
		global $post;
		
		$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
		
		$o = '<form class="password-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post"><p>' . __( "This content is password protected. To view it please enter the password below:", 'editor' ) . '</p><label for="' . $label . '">' . __( "Password:", 'editor' ) . ' </label><input type="password" id="' . $label . '" name="post_password" class="post-password" size="20" maxlength="20" /><input type="submit" name="Submit" class="btn" value="' . esc_attr__( "Submit", 'editor' ) . '" /></form>';
		
		return $o;
	}
	
	add_filter( 'the_password_form', 'pixelwars_theme_password_form' );
	
	
	function pixelwars_theme_excerpt_password_form( $excerpt )
	{
		if ( post_password_required() )
		{
			$excerpt = get_the_password_form();
		}
		
		return $excerpt;
	}
	
	add_filter( 'the_excerpt', 'pixelwars_theme_excerpt_password_form' );
	
	
	function pixelwars_theme_excerpt_more( $more )
	{
		return '... <span class="more"><a class="more-link" href="'. get_permalink( get_the_ID() ) . '">' . __( 'Continue reading <span class="meta-nav">&#8594;</span>', 'editor' ) . '</a></span>';
	}
	
	add_filter( 'excerpt_more', 'pixelwars_theme_excerpt_more' );
	
	
	function pixelwars_theme_excerpt_max_charlength( $charlength )
	{
		$excerpt = get_the_excerpt();
		$charlength++;
		
		if ( mb_strlen( $excerpt ) > $charlength )
		{
			$subex = mb_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			
			if ( $excut < 0 )
			{
				echo mb_substr( $subex, 0, $excut );
			}
			else
			{
				echo $subex;
			}
			
			echo '...';
		}
		else
		{
			echo $excerpt;
		}
	}


/* ============================================================================================================================================= */


	function pixelwars_theme_custom_box_show_post_title_visibility( $post )
	{
		?>
			<div class="admin-inside-box">
				<?php
					wp_nonce_field( 'pixelwars_theme_custom_box_show_post_title_visibility', 'pixelwars_theme_custom_box_nonce_post_title_visibility' );
				?>
				
				<p>
					<?php
						$hide_post_title = get_option( $post->ID . 'hide_post_title', false );
						
						if ( $hide_post_title )
						{
							$hide_post_title_out = 'checked="checked"';
						}
						else
						{
							$hide_post_title_out = "";
						}
					?>
					<label for="hide_post_title"><input type="checkbox" id="hide_post_title" name="hide_post_title" <?php echo $hide_post_title_out; ?>> Hide title</label>
				</p>
			</div>
		<?php
	}
	
	
	function pixelwars_theme_custom_box_add_post_title_visibility()
	{
		add_meta_box( 'pixelwars_theme_custom_box_post_title_visibility_post', __( 'Title Visibility', 'editor' ), 'pixelwars_theme_custom_box_show_post_title_visibility', 'post', 'side', 'high' );
		
		add_meta_box( 'pixelwars_theme_custom_box_post_title_visibility_page', __( 'Title Visibility', 'editor' ), 'pixelwars_theme_custom_box_show_post_title_visibility', 'page', 'side', 'high' );
	}
	
	add_action( 'add_meta_boxes', 'pixelwars_theme_custom_box_add_post_title_visibility' );
	
	
	function pixelwars_theme_custom_box_save_post_title_visibility( $post_id )
	{
		if ( ! isset( $_POST['pixelwars_theme_custom_box_nonce_post_title_visibility'] ) )
		{
			return $post_id;
		}
		
		
		$nonce = $_POST['pixelwars_theme_custom_box_nonce_post_title_visibility'];
		
		if ( ! wp_verify_nonce( $nonce, 'pixelwars_theme_custom_box_show_post_title_visibility' ) )
        {
			return $post_id;
		}
		
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        {
			return $post_id;
		}
		
		
		if ( 'page' == $_POST['post_type'] )
		{
			if ( ! current_user_can( 'edit_page', $post_id ) )
			{
				return $post_id;
			}
		}
		else
		{
			if ( ! current_user_can( 'edit_post', $post_id ) )
			{
				return $post_id;
			}
		}
		
		
		update_option( $post_id . 'hide_post_title', $_POST['hide_post_title'] );
	}
	
	add_action( 'save_post', 'pixelwars_theme_custom_box_save_post_title_visibility' );
	
	
	function editor_hide_post_title()
	{
		$hide_post_title = get_option(get_the_ID() . 'hide_post_title', false);
		
		if ($hide_post_title)
		{
			echo 'style="display: none;"';
		}
	}


/* ============================================================================================================================================= */


	register_sidebar( array('name'          => __( 'Blog Sidebar', 'editor' ),
							'id'            => 'pixelwars_blog_sidebar',
							'description'   => __( 'Add one or more widget.', 'editor' ),
							'before_widget' => '<aside id="%1$s" class="widget %2$s">',
							'after_widget'  => '</aside>',
							'before_title'  => '<h3 class="widget-title">',
							'after_title'   => '</h3>' ) );
	
	
	register_sidebar( array('name'          => __( 'Post Sidebar', 'editor' ),
							'id'            => 'pixelwars_post_sidebar',
							'description'   => __( 'Add one or more widget.', 'editor' ),
							'before_widget' => '<aside id="%1$s" class="widget %2$s">',
							'after_widget'  => '</aside>',
							'before_title'  => '<h3 class="widget-title">',
							'after_title'   => '</h3>' ) );
	
	
	register_sidebar( array('name'          => __( 'Page Sidebar', 'editor' ),
							'id'            => 'pixelwars_page_sidebar',
							'description'   => __( 'Add one or more widget.', 'editor' ),
							'before_widget' => '<aside id="%1$s" class="widget %2$s">',
							'after_widget'  => '</aside>',
							'before_title'  => '<h3 class="widget-title">',
							'after_title'   => '</h3>' ) );
	
	
	register_sidebar( array('name'          => __( 'Header Social Icons', 'editor' ),
							'id'            => 'pixelwars_header_social_icons',
							'description'   => __( 'Use social icon shortcodes with the "Custom HTML" widget.', 'editor' ),
							'before_widget' => "",
							'after_widget'  => "",
							'before_title'  => '<span style="display: none;">',
							'after_title'   => '</span>' ) );
	
	
	register_sidebar( array('name'          => __( 'Footer Social Icons', 'editor' ),
							'id'            => 'pixelwars_footer_social_icons',
							'description'   => 'Use social media shortcodes with the Text widget in this widget location to add icons to your footer.',
							'before_widget' => "",
							'after_widget'  => "",
							'before_title'  => '<span style="display: none;">',
							'after_title'   => '</span>' ) );
	
	
	register_sidebar( array('name'          => __( 'Author Social Icons', 'editor' ),
							'id'            => 'pixelwars_author_social_icons',
							'description'   => 'Use social media shortcodes with the Text widget in this widget location to add icons under the author info.',
							'before_widget' => "",
							'after_widget'  => "",
							'before_title'  => '<span style="display: none;">',
							'after_title'   => '</span>' ) );
	
	
	register_sidebar( array('name'          => __( 'Footer 1', 'editor' ),
							'id'            => 'pixelwars_footer_1',
							'description'   => __( 'Add one or more widget.', 'editor' ),
							'before_widget' => '<aside id="%1$s" class="widget %2$s">',
							'after_widget'  => '</aside>',
							'before_title'  => '<h3 class="widget-title">',
							'after_title'   => '</h3>' ) );
	
	
	register_sidebar( array('name'          => __( 'Footer 2', 'editor' ),
							'id'            => 'pixelwars_footer_2',
							'description'   => __( 'Add one or more widget.', 'editor' ),
							'before_widget' => '<aside id="%1$s" class="widget %2$s">',
							'after_widget'  => '</aside>',
							'before_title'  => '<h3 class="widget-title">',
							'after_title'   => '</h3>' ) );
	
	
	register_sidebar( array('name'          => __( 'Footer 3', 'editor' ),
							'id'            => 'pixelwars_footer_3',
							'description'   => __( 'Add one or more widget.', 'editor' ),
							'before_widget' => '<aside id="%1$s" class="widget %2$s">',
							'after_widget'  => '</aside>',
							'before_title'  => '<h3 class="widget-title">',
							'after_title'   => '</h3>' ) );
	
	
	register_sidebar( array('name'          => __( 'Footer 4', 'editor' ),
							'id'            => 'pixelwars_footer_4',
							'description'   => __( 'Add one or more widget.', 'editor' ),
							'before_widget' => '<aside id="%1$s" class="widget %2$s">',
							'after_widget'  => '</aside>',
							'before_title'  => '<h3 class="widget-title">',
							'after_title'   => '</h3>' ) );
	
	
	register_sidebar( array('name'          => __( 'Footer Copyright Text', 'editor' ),
							'id'            => 'pixelwars_footer_5',
							'description'   => __( 'Use Text widget here.', 'editor' ),
							'before_widget' => '<div id="%1$s" class="%2$s">',
							'after_widget'  => '</div>',
							'before_title'  => '<span style="display: none;">',
							'after_title'   => '</span>' ) );
	
	
	$sidebars_with_commas = get_option( 'sidebars_with_commas' );
	
	if ( $sidebars_with_commas != "" )
	{
		$sidebars = preg_split("/[\s]*[,][\s]*/", $sidebars_with_commas);
		
		foreach ( $sidebars as $sidebar_name )
		{
			register_sidebar( array('name'          => $sidebar_name,
									'id'            => $sidebar_name,
									'before_widget' => '<aside id="%1$s" class="widget %2$s">',
									'after_widget'  => '</aside>',
									'before_title'  => '<h3 class="widget-title">',
									'after_title'   => '</h3>' ) );
		}
	}


/* ============================================================================================================================================= */


	function pixelwars_theme_custom_box_show_sidebar( $post )
	{
		?>
			<div class="admin-inside-box">
				<?php
					wp_nonce_field( 'pixelwars_theme_custom_box_show_sidebar', 'pixelwars_theme_custom_box_nonce_sidebar' );
				?>
				
				
				<p>
					<?php
						$my_sidebar = get_option( $post->ID . 'my_sidebar', 'pixelwars_page_sidebar' );
					?>
					
					<select id="my_sidebar" name="my_sidebar">
						<option <?php if ( $my_sidebar == 'pixelwars_page_sidebar' ) { echo 'selected="selected"'; } ?> value="pixelwars_page_sidebar"><?php echo __( 'Page Sidebar', 'editor' ); ?></option>
						
						<option <?php if ( $my_sidebar == 'pixelwars_blog_sidebar' ) { echo 'selected="selected"'; } ?> value="pixelwars_blog_sidebar"><?php echo __( 'Blog Sidebar', 'editor' ); ?></option>
						
						<option <?php if ( $my_sidebar == 'pixelwars_post_sidebar' ) { echo 'selected="selected"'; } ?> value="pixelwars_post_sidebar"><?php echo __( 'Post Sidebar', 'editor' ); ?></option>
						
						<?php
							$sidebars_with_commas = get_option( 'sidebars_with_commas' );
							
							if ( $sidebars_with_commas != "" )
							{
								$sidebars = preg_split( "/[\s]*[,][\s]*/", $sidebars_with_commas );

								foreach ( $sidebars as $sidebar_name )
								{
									$selected = "";
									
									if ( $my_sidebar == $sidebar_name )
									{
										$selected = 'selected="selected"';
									}
									
									echo '<option ' . $selected . ' value="' . $sidebar_name . '">' . $sidebar_name . '</option>';
								}
							}
						?>
					</select>
				</p>
				
				<p class="howto">
					<?php echo __( 'Select: Page with Sidebar template.', 'editor' ); ?><br><?php echo __( 'Create: Theme Options > Sidebar.', 'editor' ); ?>
				</p>
			</div>
		<?php
	}
	
	function pixelwars_theme_custom_box_add_sidebar()
	{
		add_meta_box( 'pixelwars_theme_custom_box_sidebar', __( 'Sidebar', 'editor' ), 'pixelwars_theme_custom_box_show_sidebar', 'page', 'side', 'low' );
	}
	
	add_action( 'add_meta_boxes', 'pixelwars_theme_custom_box_add_sidebar' );
	
	
	function pixelwars_theme_custom_box_save_sidebar( $post_id )
	{
		if ( ! isset( $_POST['pixelwars_theme_custom_box_nonce_sidebar'] ) )
		{
			return $post_id;
		}
		
		$nonce = $_POST['pixelwars_theme_custom_box_nonce_sidebar'];
		
		if ( ! wp_verify_nonce( $nonce, 'pixelwars_theme_custom_box_show_sidebar' ) )
        {
			return $post_id;
		}
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        {
			return $post_id;
		}
		
		if ( 'page' == $_POST['post_type'] )
		{
			if ( ! current_user_can( 'edit_page', $post_id ) )
			{
				return $post_id;
			}
		}
		else
		{
			if ( ! current_user_can( 'edit_post', $post_id ) )
			{
				return $post_id;
			}
		}
		
		update_option( $post_id . 'my_sidebar', $_POST['my_sidebar'] );
	}
	
	add_action( 'save_post', 'pixelwars_theme_custom_box_save_sidebar' );


/* ============================================================================================================================================= */


	class pixelwars_Flickr_Widget extends WP_Widget
	{
		public function __construct()
		{
			parent::__construct(
				'pixelwars_flickr_widget',
				__('- Flickr', 'editor'),
				array('description' => __('Flickr widget.', 'editor'))
			);
		}
		
		public function form($instance)
		{
			if ( isset( $instance[ 'title' ] ) ) { $title = $instance[ 'title' ]; } else { $title = ""; }
			if ( isset( $instance[ 'user' ] ) ) { $user = $instance[ 'user' ]; } else { $user = ""; }
			if ( isset( $instance[ 'number_of_items' ] ) ) { $number_of_items = $instance[ 'number_of_items' ]; } else { $number_of_items = '8'; }
			
			?>
				<p>
					<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo __( 'Title:', 'editor' ); ?></label>
					<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr( $title ); ?>">
				</p>
				<p>
					<label for="<?php echo esc_attr($this->get_field_id('user')); ?>"><?php echo __( 'User:', 'editor' ); ?></label>
					<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('user')); ?>" name="<?php echo esc_attr($this->get_field_name('user')); ?>" value="<?php echo esc_attr( $user ); ?>">
				</p>
				<p>
					<label for="<?php echo esc_attr($this->get_field_id('number_of_items')); ?>"><?php echo __( 'Number of items to show:', 'editor' ); ?></label>
					<input type="text" id="<?php echo esc_attr($this->get_field_id('number_of_items')); ?>" name="<?php echo esc_attr($this->get_field_name('number_of_items')); ?>" size="3" value="<?php echo esc_attr( $number_of_items ); ?>">
				</p>
			<?php
		}
		
		public function update( $new_instance, $old_instance )
		{
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['user'] = strip_tags( $new_instance['user'] );
			$instance['number_of_items'] = strip_tags( $new_instance['number_of_items'] );
			
			return $instance;
		}
		
		public function widget( $args, $instance )
		{
			extract( $args );
			$title = apply_filters( 'widget_title', $instance['title'] );
			$user = apply_filters( 'widget_user', $instance['user'] );
			$number_of_items = apply_filters( 'widget_number_of_items', $instance['number_of_items'] );
			
			echo $before_widget;
			
				if ( ! empty( $title ) )
				{
					echo $before_title . $title . $after_title;
				}
				
				?>
					<div class="flickr-badges flickr-badges-s">
						<script src="http://www.flickr.com/badge_code_v2.gne?size=s&amp;count=<?php echo esc_attr( $number_of_items ); ?>&amp;display=random&amp;layout=x&amp;source=user&amp;user=<?php echo esc_attr( $user ); ?>"></script>
					</div>
				<?php
			
			echo $after_widget;
		}
	}
	
	add_action('widgets_init', function() { register_widget('pixelwars_Flickr_Widget'); });


/* ============================================================================================================================================= */


	class pixelwars_Social_Feed_Widget extends WP_Widget
	{
		public function __construct()
		{
			parent::__construct('pixelwars_social_feed_widget',
								__( '- Social Feed', 'editor' ),
								array( 'description' => __( 'Social feed widget.', 'editor' ) ) );
		}
		
		public function form( $instance )
		{
			if ( isset( $instance[ 'title' ] ) ) { $title = $instance[ 'title' ]; } else { $title = ""; }
			if ( isset( $instance[ 'network' ] ) ) { $network = $instance[ 'network' ]; } else { $network = ""; }
			if ( isset( $instance[ 'user' ] ) ) { $user = $instance[ 'user' ]; } else { $user = ""; }
			if ( isset( $instance[ 'number_of_items' ] ) ) { $number_of_items = $instance[ 'number_of_items' ]; } else { $number_of_items = '6'; }
			
			?>
				<p>
					<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo __( 'Title:', 'editor' ); ?></label>
					
					<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr( $title ); ?>">
				</p>
				
				<p>
					<label for="<?php echo esc_attr($this->get_field_id('network')); ?>"><?php echo __( 'Network:', 'editor' ); ?></label>
					<select class="widefat" id="<?php echo esc_attr($this->get_field_id('network')); ?>" name="<?php echo esc_attr($this->get_field_name('network')); ?>">
						<option></option>
						<option <?php if ( $network == 'pinterest' ) { echo 'selected="selected"'; } ?> value="pinterest">Pinterest</option>
						<option <?php if ( $network == 'picasa' ) { echo 'selected="selected"'; } ?> value="picasa">Picasa</option>
					</select>
				</p>
				
				<p>
					<label for="<?php echo esc_attr($this->get_field_id('user')); ?>"><?php echo __( 'User:', 'editor' ); ?></label>
					<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('user')); ?>" name="<?php echo esc_attr($this->get_field_name('user')); ?>" value="<?php echo esc_attr( $user ); ?>">
				</p>
				
				<p>
					<label for="<?php echo esc_attr($this->get_field_id('number_of_items')); ?>"><?php echo __('Number of items to show:', 'editor'); ?></label>
					
					<input type="number" min="1" max="50" step="1" id="<?php echo esc_attr($this->get_field_id('number_of_items')); ?>" name="<?php echo esc_attr($this->get_field_name('number_of_items')); ?>" value="<?php echo esc_attr($number_of_items); ?>">
				</p>
			<?php
		}
		
		public function update( $new_instance, $old_instance )
		{
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['network'] = strip_tags( $new_instance['network'] );
			$instance['user'] = strip_tags( $new_instance['user'] );
			$instance['number_of_items'] = strip_tags( $new_instance['number_of_items'] );
			
			return $instance;
		}
		
		public function widget( $args, $instance )
		{
			extract( $args );
			$title = apply_filters( 'widget_title', $instance['title'] );
			$network = apply_filters( 'widget_network', $instance['network'] );
			$user = apply_filters( 'widget_user', $instance['user'] );
			$number_of_items = apply_filters( 'widget_number_of_items', $instance['number_of_items'] );
			
			echo $before_widget;
			
				if ( ! empty( $title ) )
				{
					echo $before_title . $title . $after_title;
				}
				
				?>
					<div class="social-feed" data-social-network="<?php echo esc_attr( $network ); ?>" data-username="<?php echo esc_attr( $user ); ?>" data-limit="<?php echo esc_attr( $number_of_items ); ?>"></div>
				<?php
			
			echo $after_widget;
		}
	}
	
	add_action('widgets_init', function() { register_widget('pixelwars_Social_Feed_Widget'); });


/* ============================================================================================================================================= */


	function pixelwars__create_post_type__portfolio()
	{
		$labels = array('name'               => __( 'Portfolio', 'editor' ),
						'singular_name'      => __( 'Portfolio Item', 'editor' ),
						'add_new'            => __( 'Add New', 'editor' ),
						'add_new_item'       => __( 'Add New', 'editor' ),
						'edit_item'          => __( 'Edit', 'editor' ),
						'new_item'           => __( 'New', 'editor' ),
						'all_items'          => __( 'All', 'editor' ),
						'view_item'          => __( 'View', 'editor' ),
						'search_items'       => __( 'Search', 'editor' ),
						'not_found'          => __( 'No Items found', 'editor' ),
						'not_found_in_trash' => __( 'No Items found in Trash', 'editor' ),
						'parent_item_colon'  => '',
						'menu_name'          => 'Portfolio' );
		
		$args = array(  'labels' => $labels,
						'public' => true,
						'exclude_from_search' => false,
						'publicly_queryable'  => true,
						'show_ui'             => true,
						'query_var'           => true,
						'show_in_nav_menus'   => true,
						'capability_type'     => 'post',
						'hierarchical'        => false,
						'menu_position'       => 5,
						'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
						'rewrite'             => array( 'slug' => 'portfolio', 'with_front' => false ) );
		
		register_post_type( 'portfolio' , $args );
	}
	
	add_action( 'init', 'pixelwars__create_post_type__portfolio' );
	
	
	function pixelwars__updated_messages__portfolio( $messages )
	{
		global $post, $post_ID;
		
		$messages['portfolio'] = array( 0 => "", // Unused. Messages start at index 1.
										
										1 => sprintf( __( '<strong>Updated.</strong> <a target="_blank" href="%s">View</a>', 'editor' ), esc_url( get_permalink( $post_ID ) ) ),
										
										2 => __( 'Custom field updated.', 'editor' ),
										
										3 => __( 'Custom field deleted.', 'editor' ),
										
										4 => __( 'Updated.', 'editor' ),
										
										// translators: %s: date and time of the revision
										5 => isset( $_GET['revision'] ) ? sprintf( __( 'Restored to revision from %s', 'editor' ), wp_post_revision_title( ( int ) $_GET['revision'], false ) ) : false,
										
										6 => sprintf( __( '<strong>Published.</strong> <a target="_blank" href="%s">View</a>', 'editor' ), esc_url( get_permalink( $post_ID) ) ),
										
										7 => __( 'Saved.', 'editor' ),
										
										8 => sprintf( __( 'Submitted. <a target="_blank" href="%s">Preview</a>', 'editor' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
										
										9 => sprintf( __( 'Scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview</a>', 'editor' ),
										
										// translators: Publish box date format, see http://php.net/date
										date_i18n( __( 'M j, Y @ G:i', 'editor' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID) ) ),
										
										10 => sprintf( __( '<strong>Item draft updated.</strong> <a target="_blank" href="%s">Preview</a>', 'editor' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ) );
		
		
		return $messages;
	}
	
	add_filter( 'post_updated_messages', 'pixelwars__updated_messages__portfolio' );
	
	
	function pixelwars__portfolio_columns( $pf_columns )
	{
		$pf_columns = array('cb'                => '<input type="checkbox">',
							'title'             => __( 'Title', 'editor' ),
							'pf_featured_image' => __( 'Featured Image', 'editor' ),
							'departments'       => __( 'Departments', 'editor' ),
							'date'              => __( 'Date', 'editor' ) );
		
		
		return $pf_columns;
	}
	
	add_filter( 'manage_edit-portfolio_columns', 'pixelwars__portfolio_columns' );
	
	
	function pixelwars__custom_columns__portfolio( $pf_column )
	{
		global $post, $post_ID;
		
		
		switch ( $pf_column )
		{
			case 'pf_featured_image':
			
				if ( has_post_thumbnail() )
				{
					the_post_thumbnail( 'thumbnail' );
				}
			
			break;
			
			
			case 'departments':
			
				$taxonomy = 'department';
				
				$terms_list = get_the_terms( $post_ID, $taxonomy );
				
				if ( ! empty( $terms_list ) )
				{
					$out = array();
					
					foreach ( $terms_list as $term_list )
					{
						$out[] = '<a href="edit.php?post_type=portfolio&department=' . $term_list->slug . '">' . $term_list->name . ' </a>';
					}
					
					echo join( ', ', $out );
				}
			
			break;
		}
	}
	
	add_action( 'manage_posts_custom_column',  'pixelwars__custom_columns__portfolio' );
	
	
	function pixelwars__taxonomy__portfolio()
	{
		$labels_cat = array('name'              => __( 'Departments', 'editor' ),
							'singular_name'     => __( 'Department', 'editor' ),
							'search_items'      => __( 'Search', 'editor' ),
							'all_items'         => __( 'All', 'editor' ),
							'parent_item'       => __( 'Parent', 'editor' ),
							'parent_item_colon' => __( 'Parent:', 'editor' ),
							'edit_item'         => __( 'Edit', 'editor' ),
							'update_item'       => __( 'Update', 'editor' ),
							'add_new_item'      => __( 'Add New', 'editor' ),
							'new_item_name'     => __( 'New Name', 'editor' ),
							'menu_name'         => __( 'Departments', 'editor' ) );
		
		
		register_taxonomy(  'department',
							array( 'portfolio' ),
							array(  'hierarchical' => true,
									'labels'       => $labels_cat,
									'show_ui'      => true,
									'public'       => true,
									'query_var'    => true,
									'rewrite'      => array( 'slug' => 'department' ) ) );
	}
	
	add_action( 'init', 'pixelwars__taxonomy__portfolio' );
	
	
	function pixelwars_taxonomy_filter_portfolio()
	{
		global $typenow;
		
		
		if ( $typenow == 'portfolio' )
		{
			$filters = array( 'department' );
			
			
			foreach ( $filters as $tax_slug )
			{
				$tax_obj = get_taxonomy( $tax_slug );
				
				$tax_name = $tax_obj->labels->name;
				
				$terms = get_terms( $tax_slug );
				
				
				echo '<select name="' . $tax_slug . '" id="' . $tax_slug . '" class="postform">';
				
					echo '<option value="">' . __( 'All', 'editor' ) . ' ' . $tax_name . '</option>';
					
					foreach ( $terms as $term )
					{
						echo '<option value=' . $term->slug, @$_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count . ')</option>';
					}
				
				echo '</select>';
			}
		}
	}
	
	add_action( 'restrict_manage_posts', 'pixelwars_taxonomy_filter_portfolio' );
	
	
	function pixelwars_theme_custom_box_show_portfolio( $post )
	{
		?>
			<?php
				wp_nonce_field( 'pixelwars_theme_custom_box_show_portfolio', 'pixelwars_theme_custom_box_nonce_portfolio' );
			?>
			
			
			<p>
				<?php
					$pf_type = get_option( $post->ID . 'pf_type', 'Standard' );
				?>
				
				<label><input type="radio" name="pf_type" <?php if ( $pf_type == 'Standard' ) { echo 'checked="checked"'; } ?> value="Standard"> <?php echo __( 'Standard', 'editor' ); ?></label>
				
				<br>
				
				<label><input type="radio" name="pf_type" <?php if ( $pf_type == 'Lightbox Gallery' ) { echo 'checked="checked"'; } ?> value="Lightbox Gallery"> <?php echo __( 'Lightbox Gallery', 'editor' ); ?></label>
				
				<br>
				
				<label><input type="radio" name="pf_type" <?php if ( $pf_type == 'Lightbox Audio' ) { echo 'checked="checked"'; } ?> value="Lightbox Audio"> <?php echo __( 'Lightbox Audio', 'editor' ); ?></label>
				
				<br>
				
				<label><input type="radio" name="pf_type" <?php if ( $pf_type == 'Lightbox Video' ) { echo 'checked="checked"'; } ?> value="Lightbox Video"> <?php echo __( 'Lightbox Video', 'editor' ); ?></label>
				
				<br>
				
				<label><input type="radio" name="pf_type" <?php if ( $pf_type == 'Direct URL' ) { echo 'checked="checked"'; } ?> value="Direct URL"> <?php echo __( 'Direct URL', 'editor' ); ?></label>
			</p>
			
			<hr>
			
			<p>
				<?php
					$pf_direct_url = stripcslashes( get_option( $post->ID . 'pf_direct_url' ) );
					
					$pf_link_new_tab = get_option( $post->ID . 'pf_link_new_tab', true );
				?>
				
				<label for="pf_direct_url">URL</label>
				
				<input type="text" id="pf_direct_url" name="pf_direct_url" class="widefat code2" value="<?php echo esc_url( $pf_direct_url ); ?>">
				
				<label><input type="checkbox" name="pf_link_new_tab" <?php if ( $pf_link_new_tab != false ) { echo 'checked="checked"'; } ?>> <?php echo __( 'Open link in new tab', 'editor' ); ?></label>
			</p>
		<?php
	}
	
	function pixelwars_theme_custom_box_add_portfolio()
	{
		add_meta_box( 'pixelwars_theme_custom_box_portfolio', __( 'Type', 'editor' ), 'pixelwars_theme_custom_box_show_portfolio', 'portfolio', 'side', 'low' );
	}
	
	add_action( 'add_meta_boxes', 'pixelwars_theme_custom_box_add_portfolio' );
	
	
	function pixelwars_theme_custom_box_save_portfolio( $post_id )
	{
		if ( ! isset( $_POST['pixelwars_theme_custom_box_nonce_portfolio'] ) )
		{
			return $post_id;
		}
		
		
		$nonce = $_POST['pixelwars_theme_custom_box_nonce_portfolio'];
		
		if ( ! wp_verify_nonce( $nonce, 'pixelwars_theme_custom_box_show_portfolio' ) )
        {
			return $post_id;
		}
		
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        {
			return $post_id;
		}
		
		
		if ( 'page' == $_POST['post_type'] )
		{
			if ( ! current_user_can( 'edit_page', $post_id ) )
			{
				return $post_id;
			}
		}
		else
		{
			if ( ! current_user_can( 'edit_post', $post_id ) )
			{
				return $post_id;
			}
		}
		
		
		update_option( $post_id . 'pf_type', $_POST['pf_type'] );
		update_option( $post_id . 'pf_direct_url', $_POST['pf_direct_url'] );
		update_option( $post_id . 'pf_link_new_tab', $_POST['pf_link_new_tab'] );
	}
	
	add_action( 'save_post', 'pixelwars_theme_custom_box_save_portfolio' );


/* ============================================================================================================================================= */


	function pixelwars__create_post_type__gallery()
	{
		$args = array(  'label'         => __( 'Gallery', 'editor' ),
						'public'        => true,
						'menu_position' => 5,
						'supports'      => array( 'title', 'editor', 'thumbnail' ) );
		
		
		register_post_type( 'gallery' , $args );
	}
	
	add_action( 'init', 'pixelwars__create_post_type__gallery' );
	
	
	function pixelwars__gallery_columns( $columns )
	{
		$columns = array(   'cb'                                 => '<input type="checkbox">',
							'title'                              => __( 'Title', 'editor' ),
							'pixelwars__featured_image__gallery' => __( 'Featured Image', 'editor' ),
							'date'                               => __( 'Date', 'editor' ) );
		
		
		return $columns;
	}
	
	add_filter( 'manage_edit-gallery_columns', 'pixelwars__gallery_columns' );
	
	
	function pixelwars__custom_columns__gallery( $column )
	{
		switch ( $column )
		{
			case 'pixelwars__featured_image__gallery':
			
				the_post_thumbnail( 'thumbnail' );
			
			break;
		}
	}
	
	add_action( 'manage_posts_custom_column',  'pixelwars__custom_columns__gallery' );


/* ============================================================================================================================================= */


	function pixelwars_create_post_type_book()
	{
		$labels = array('name'               => __( 'Books', 'editor' ),
						'singular_name'      => __( 'Book', 'editor' ),
						'add_new'            => __( 'Add New', 'editor' ),
						'add_new_item'       => __( 'Add New', 'editor' ),
						'edit_item'          => __( 'Edit', 'editor' ),
						'new_item'           => __( 'New', 'editor' ),
						'all_items'          => __( 'All', 'editor' ),
						'view_item'          => __( 'View', 'editor' ),
						'search_items'       => __( 'Search', 'editor' ),
						'not_found'          => __( 'No Items found', 'editor' ),
						'not_found_in_trash' => __( 'No Items found in Trash', 'editor' ),
						'parent_item_colon'  => '',
						'menu_name'          => 'Books' );
		
		
		$args = array(  'labels'              => $labels,
						'public'              => true,
						'exclude_from_search' => false,
						'publicly_queryable'  => true,
						'show_ui'             => true,
						'query_var'           => true,
						'show_in_nav_menus'   => true,
						'capability_type'     => 'post',
						'hierarchical'        => false,
						'menu_position'       => 5,
						'supports'            => array( 'title', 'editor', ),
						'rewrite'             => array( 'slug' => 'book', 'with_front' => false ) );
		
		
		register_post_type( 'book' , $args );
	}
	
	add_action( 'init', 'pixelwars_create_post_type_book' );
	
	
	function pixelwars_updated_messages_book( $messages )
	{
		global $post, $post_ID;
		
		$messages['book'] = array(  0 => "", // Unused. Messages start at index 1.
		
									1 => sprintf( __( '<strong>Updated.</strong> <a target="_blank" href="%s">View</a>', 'editor' ), esc_url( get_permalink( $post_ID) ) ),
									
									2 => __( 'Custom field updated.', 'editor' ),
									
									3 => __( 'Custom field deleted.', 'editor' ),
									
									4 => __( 'Updated.', 'editor' ),
									
									// translators: %s: date and time of the revision
									5 => isset( $_GET['revision'] ) ? sprintf( __( 'Restored to revision from %s', 'editor' ), wp_post_revision_title( ( int ) $_GET['revision'], false ) ) :false,
									
									6 => sprintf( __( '<strong>Published.</strong> <a target="_blank" href="%s">View</a>', 'editor' ), esc_url( get_permalink( $post_ID) ) ),
									
									7 => __( 'Saved.', 'editor' ),
									
									8 => sprintf( __( 'Submitted. <a target="_blank" href="%s">Preview</a>', 'editor' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
									
									9 => sprintf( __( 'Scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview</a>', 'editor' ),
									
									// translators: Publish box date format, see http://php.net/date
									date_i18n( __( 'M j, Y @ G:i', 'editor' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID) ) ),
									
									10 => sprintf( __( '<strong>Item draft updated.</strong> <a target="_blank" href="%s">Preview</a>', 'editor' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ) );
		
		
		return $messages;
	}
	
	add_filter( 'post_updated_messages', 'pixelwars_updated_messages_book' );
	
	
	function pixelwars_book_columns( $book_columns )
	{
		$book_columns = array(  'cb'          => '<input type="checkbox">',
								'title'       => __( 'Title', 'editor' ),
								'book_image'  => __( 'Book Image', 'editor' ),
								'book_author' => __( 'Book Author', 'editor' ),
								'date'        => __( 'Date', 'editor' ) );
		
		
		return $book_columns;
	}
	
	add_filter( 'manage_edit-book_columns', 'pixelwars_book_columns' );
	
	
	function pixelwars_custom_columns_book( $book_column )
	{
		global $post, $post_ID;
		
		switch ( $book_column )
		{
			case 'book_image':
			
				$book_cover_image = stripcslashes( get_option( $post->ID . 'book_cover_image', "" ) );
				
				if ( $book_cover_image != "" )
				{
					?>
						<img style="max-height: 150px;" alt="<?php the_title_attribute(); ?>" src="<?php echo esc_url( $book_cover_image ); ?>">
					<?php
				}
			
			break;
			
			case 'book_author':
			
				$taxonomy = 'book_author';
				
				$terms_list = get_the_terms( $post_ID, $taxonomy );
				
				if ( ! empty( $terms_list ) )
				{
					$out = array();
					
					foreach ( $terms_list as $term_list )
					{
						$out[] = '<a href="edit.php?post_type=book&book_author=' . $term_list->slug . '">' . $term_list->name . ' </a>';
					}
					
					echo join( ', ', $out );
				}
			
			break;
		}
	}
	
	add_action( 'manage_posts_custom_column',  'pixelwars_custom_columns_book' );
	
	
	function pixelwars_taxonomy_book()
	{
		$labels_cat = array('name'              => __( 'Book Authors', 'editor' ),
							'singular_name'     => __( 'Book Author', 'editor' ),
							'search_items'      => __( 'Search', 'editor' ),
							'all_items'         => __( 'All', 'editor' ),
							'parent_item'       => __( 'Parent', 'editor' ),
							'parent_item_colon' => __( 'Parent:', 'editor' ),
							'edit_item'         => __( 'Edit', 'editor' ),
							'update_item'       => __( 'Update', 'editor' ),
							'add_new_item'      => __( 'Add New', 'editor' ),
							'new_item_name'     => __( 'New Name', 'editor' ),
							'menu_name'         => __( 'Book Authors', 'editor' ) );
		
		
		register_taxonomy(  'book_author',
							array( 'book' ),
							array(  'hierarchical' => true,
									'labels'       => $labels_cat,
									'show_ui'      => true,
									'public'       => true,
									'query_var'    => true,
									'rewrite'      => array( 'slug' => 'book_author' ) ) );
	}
	
	add_action( 'init', 'pixelwars_taxonomy_book' );
	
	
	function pixelwars_taxonomy_filter_book()
	{
		global $typenow;
		
		if ( $typenow == 'book' )
		{
			$filters = array( 'book_author' );
			
			foreach ( $filters as $tax_slug )
			{
				$tax_obj = get_taxonomy( $tax_slug );
				
				$tax_name = $tax_obj->labels->name;
				
				$terms = get_terms( $tax_slug );
			
				echo '<select id="' . esc_attr( $tax_slug ) .'" name="' . esc_attr( $tax_slug ) . '" class="postform">';
				
					echo '<option value="">' . __( 'All', 'editor' ) . ' ' .$tax_name .'</option>';
					
					foreach ( $terms as $term )
					{
						echo '<option value=' . $term->slug, @$_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name . ' (' . $term->count . ')</option>';
					}
				
				echo '</select>';
			}
		}
	}
	
	add_action( 'restrict_manage_posts', 'pixelwars_taxonomy_filter_book' );
	
	
	function pixelwars_theme_custom_box_show_book( $post )
	{
		?>
			<?php
				wp_nonce_field( 'pixelwars_theme_custom_box_show_book', 'pixelwars_theme_custom_box_nonce_book' );
			?>
			
			
			<p>
				<label for="book_cover_image"><?php echo __( 'Cover Image', 'editor' ); ?></label>
				
				<?php
					$book_cover_image = stripcslashes( get_option( $post->ID . 'book_cover_image', "" ) );
				?>
				
				<input type="text" id="book_cover_image" name="book_cover_image" class="widefat code2 upload" value="<?php echo esc_url( $book_cover_image ); ?>">
				
				<input type="button" class="button upload-button" style="margin-top: 10px;" value="<?php echo __( 'Browse', 'editor' ); ?>">
				
				<br>
				
				<img style="margin-top: 10px; max-height: 150px;" src="<?php echo esc_url( $book_cover_image ); ?>">
			</p>
			
			
			<hr>
			
			
			<p>
				<label for="book_side_image"><?php echo __( 'Side Image', 'editor' ); ?></label>
				
				<?php
					$book_side_image = stripcslashes( get_option( $post->ID . 'book_side_image', "" ) );
				?>
				
				<input type="text" id="book_side_image" name="book_side_image" class="widefat code2 upload" value="<?php echo esc_url( $book_side_image ); ?>">
				
				<input type="button" class="button upload-button" style="margin-top: 10px;" value="<?php echo __( 'Browse', 'editor' ); ?>">
				
				<br>
				
				<img style="margin-top: 10px; max-height: 150px;" src="<?php echo esc_url( $book_side_image ); ?>">
			</p>
			
			
			<hr>
			
			
			<p>
				<label for="book_buy_url"><?php echo __( 'Buy URL', 'editor' ); ?></label>
				<?php
					$book_buy_url = stripcslashes( get_option( $post->ID . 'book_buy_url', "" ) );
					
					$book_buy_url_new_tab = get_option( $post->ID . 'book_buy_url_new_tab', true );
				?>
				<input type="text" id="book_buy_url" name="book_buy_url" class="widefat code2" value="<?php echo esc_url( $book_buy_url ); ?>">
				
				<label><input type="checkbox" id="book_buy_url_new_tab" name="book_buy_url_new_tab" <?php if ( $book_buy_url_new_tab ) { echo 'checked="checked"'; } ?>> <?php echo __( 'Open link in new tab', 'editor' ); ?></label>
			</p>
			
			
			<script>
				jQuery(document).ready(function($)
				{
					// Image Upload
					var uploadID = "";
					
					$(document).on('click', '.upload-button', function()
					{
						window.send_to_editor = function( html )
						{
							imgurl = $( 'img', html ).attr( 'src' );
							
							uploadID.val( imgurl );
							
							uploadID.trigger( 'change' );
							
							tb_remove();
						}
						
						uploadID = $(this).prev( 'input' );
						
						formfield = $( '.upload' ).attr( 'name' );
						
						tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');
						
						return false;
					});
				});
			</script>
		<?php
	}
	
	function pixelwars_theme_custom_box_add_book()
	{
		add_meta_box( 'pixelwars_theme_custom_box_book', __( 'Details', 'editor' ), 'pixelwars_theme_custom_box_show_book', 'book', 'side', 'low' );
	}
	
	add_action( 'add_meta_boxes', 'pixelwars_theme_custom_box_add_book' );
	
	
	function pixelwars_theme_custom_box_save_book( $post_id )
	{
		if ( ! isset( $_POST['pixelwars_theme_custom_box_nonce_book'] ) )
		{
			return $post_id;
		}
		
		
		$nonce = $_POST['pixelwars_theme_custom_box_nonce_book'];
		
		if ( ! wp_verify_nonce( $nonce, 'pixelwars_theme_custom_box_show_book' ) )
        {
			return $post_id;
		}
		
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        {
			return $post_id;
		}
		
		
		if ( 'page' == $_POST['post_type'] )
		{
			if ( ! current_user_can( 'edit_page', $post_id ) )
			{
				return $post_id;
			}
		}
		else
		{
			if ( ! current_user_can( 'edit_post', $post_id ) )
			{
				return $post_id;
			}
		}
		
		
		update_option( $post_id . 'book_buy_url', $_POST['book_buy_url'] );
		update_option( $post_id . 'book_buy_url_new_tab', $_POST['book_buy_url_new_tab'] );
		update_option( $post_id . 'book_cover_image', $_POST['book_cover_image'] );
		update_option( $post_id . 'book_side_image', $_POST['book_side_image'] );
	}
	
	add_action( 'save_post', 'pixelwars_theme_custom_box_save_book' );


/* ============================================================================================================================================= */


	/*
		This function filters the post content when viewing a post with the "chat" post format.  It formats the 
		content with structured HTML markup to make it easy for theme developers to style chat posts. The 
		advantage of this solution is that it allows for more than two speakers (like most solutions). You can 
		have 100s of speakers in your chat post, each with their own, unique classes for styling.
		
		@author David Chandra
		@link http://www.turtlepod.org
		@author Justin Tadlock
		@link http://justintadlock.com
		@copyright Copyright (c) 2012
		@license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
		@link http://justintadlock.com/archives/2012/08/21/post-formats-chat
		
		@global array $_post_format_chat_ids An array of IDs for the chat rows based on the author.
		@param string $content The content of the post.
		@return string $chat_output The formatted content of the post.
	*/
	
	
	function pixelwars_theme_post_format_chat_content( $content )
	{
		global $_post_format_chat_ids;
		
		
		/* If this is not a 'chat' post, return the content. */
		if ( !has_post_format( 'chat' ) )
		{
			return $content;
		}
		
		
		/* Set the global variable of speaker IDs to a new, empty array for this chat. */
		$_post_format_chat_ids = array();
		
		/* Allow the separator (separator for speaker/text) to be filtered. */
		$separator = apply_filters( 'my_post_format_chat_separator', ':' );
		
		/* Open the chat transcript div and give it a unique ID based on the post ID. */
		$chat_output = "\n\t\t\t" . '<div id="chat-transcript-' . esc_attr( get_the_ID() ) . '" class="chat-transcript">';
		
		/* Split the content to get individual chat rows. */
		$chat_rows = preg_split( "/(\r?\n)+|(<br\s*\/?>\s*)+/", $content );
		
		
		/* Loop through each row and format the output. */
		foreach ( $chat_rows as $chat_row )
		{
			/* If a speaker is found, create a new chat row with speaker and text. */
			if ( strpos( $chat_row, $separator ) )
			{
				/* Split the chat row into author/text. */
				$chat_row_split = explode( $separator, trim( $chat_row ), 2 );
				
				
				/* Get the chat author and strip tags. */
				$chat_author = strip_tags( trim( $chat_row_split[0] ) );
				
				
				/* Get the chat text. */
				$chat_text = trim( $chat_row_split[1] );
				
				
				/* Get the chat row ID (based on chat author) to give a specific class to each row for styling. */
				$speaker_id = pixelwars_theme_post_format_chat_row_id( $chat_author );
				
				
				/* Open the chat row. */
				$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';
				
				
				/* Add the chat row author. */
				$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-author ' . sanitize_html_class( strtolower( "chat-author-{$chat_author}" ) ) . ' vcard"><cite class="fn">' . apply_filters( 'my_post_format_chat_author', $chat_author, $speaker_id ) . '</cite>' . $separator . '</div>';
				
				
				/* Add the chat row text. */
				$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text"><p>' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'my_post_format_chat_text', $chat_text, $chat_author, $speaker_id ) ) . '</p></div>';
				
				
				/* Close the chat row. */
				$chat_output .= "\n\t\t\t\t" . '</div><!-- .chat-row -->';
			}
			/*
				If no author is found, assume this is a separate paragraph of text that belongs to the
				previous speaker and label it as such, but let's still create a new row.
			*/
			else
			{
				/* Make sure we have text. */
				if ( !empty( $chat_row ) )
				{
					/* Open the chat row. */
					$chat_output .= "\n\t\t\t\t" . '<div class="chat-row ' . sanitize_html_class( "chat-speaker-{$speaker_id}" ) . '">';
					
					
					/* Don't add a chat row author.  The label for the previous row should suffice. */
					
					
					/* Add the chat row text. */
					$chat_output .= "\n\t\t\t\t\t" . '<div class="chat-text"><p>' . str_replace( array( "\r", "\n", "\t" ), '', apply_filters( 'my_post_format_chat_text', $chat_row, $chat_author, $speaker_id ) ) . '</p></div>';
					
					
					/* Close the chat row. */
					$chat_output .= "\n\t\t\t</div><!-- .chat-row -->";
				}
			}
		}
		
		
		/* Close the chat transcript div. */
		$chat_output .= "\n\t\t\t</div><!-- .chat-transcript -->\n";
		
		
		/* Return the chat content and apply filters for developers. */
		return apply_filters( 'my_post_format_chat_content', $chat_output );
	}
	
	
	/*
		This function returns an ID based on the provided chat author name. It keeps these IDs in a global 
		array and makes sure we have a unique set of IDs.  The purpose of this function is to provide an "ID"
		that will be used in an HTML class for individual chat rows so they can be styled. So, speaker "John" 
		will always have the same class each time he speaks. And, speaker "Mary" will have a different class 
		from "John" but will have the same class each time she speaks.
		
		@author David Chandra
		@link http://www.turtlepod.org
		@author Justin Tadlock
		@link http://justintadlock.com
		@copyright Copyright (c) 2012
		@license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
		@link http://justintadlock.com/archives/2012/08/21/post-formats-chat
		
		@global array $_post_format_chat_ids An array of IDs for the chat rows based on the author.
		@param string $chat_author Author of the current chat row.
		@return int The ID for the chat row based on the author.
	*/
	
	
	function pixelwars_theme_post_format_chat_row_id( $chat_author )
	{
		global $_post_format_chat_ids;
		
		
		/* Let's sanitize the chat author to avoid craziness and differences like "John" and "john". */
		$chat_author = strtolower( strip_tags( $chat_author ) );
		
		
		/* Add the chat author to the array. */
		$_post_format_chat_ids[] = $chat_author;
		
		
		/* Make sure the array only holds unique values. */
		$_post_format_chat_ids = array_unique( $_post_format_chat_ids );
		
		
		/* Return the array key for the chat author and add "1" to avoid an ID of "0". */
		return absint( array_search( $chat_author, $_post_format_chat_ids ) ) + 1;
	}
	
	
	/* Filter the content of chat posts. */
	add_filter( 'the_content', 'pixelwars_theme_post_format_chat_content' );


/* ============================================================================================================================================= */


	add_filter( 'the_excerpt', 'do_shortcode' );
	
	add_filter( 'widget_text', 'do_shortcode' );


/* ============================================================================================================================================= */


	function row( $atts, $content = "" )
	{
		$row = '<div class="row">' . do_shortcode( $content ) . '</div>';
		
		
		return $row;
	}
	
	add_shortcode( 'row', 'row' );


/* ============================================================================================================================================= */


	function column( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'width'    => "",
										'width_xs' => "",
										'width_md' => "",
										'width_lg' => "" ), $atts ) );
		
		
		if ( $width != "" )
		{
			$width = 'col-sm-' . $width;
		}
		
		
		if ( $width_xs != "" )
		{
			$width_xs = 'col-xs-' . $width_xs;
		}
		
		
		if ( $width_md != "" )
		{
			$width_md = 'col-md-' . $width_md;
		}
		
		
		if ( $width_lg != "" )
		{
			$width_lg = 'col-lg-' . $width_lg;
		}
		
		
		$column = '<div class="' . $width . ' ' . $width_xs . ' ' . $width_md . ' ' . $width_lg . '">' . do_shortcode( $content ) . '</div>';
		
		
		return $column;
	}
	
	add_shortcode( 'column', 'column' );


/* ============================================================================================================================================= */


	function alert( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'type' => "" ), $atts ) );
		
		
		$alert = '<div class="alert ' . $type . '">' . do_shortcode( $content ) . '</div>';
		
		
		return $alert;
	}
	
	add_shortcode( 'alert', 'alert' );


/* ============================================================================================================================================= */


	function button( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'text'   => "",
										'url'    => "",
										'target' => "",
										'color'  => "",
										'size'   => "",
										'icon'   => "" ), $atts ) );
		
		
		if ( $icon != "" )
		{
			$icon = '<i class="pw-icon-' . $icon . '"></i>';
		}
		
		
		$button = '<a target="' . $target . '" href="' . $url . '" class="button ' . $color . ' ' . $size . '">' . $icon . $text . '</a>';
		
		
		return $button;
	}
	
	add_shortcode( 'button', 'button' );


/* ============================================================================================================================================= */


	function social_icon_wrap( $atts, $content = "" )
	{
		$social_icon_wrap = '<ul class="social">' . do_shortcode( $content ) . '</ul>';
		
		
		return $social_icon_wrap;
	}
	
	add_shortcode( 'social_icon_wrap', 'social_icon_wrap' );


/* ============================================================================================================================================= */


	function social_icon( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'type'     => "",
										'same_tab' => "",
										'url'      => "" ), $atts ) );
		
		
		if ( $same_tab == 'yes' )
		{
			$output = '<li><a class="' . $type . '" href="' . $url . '"></a></li>';
		}
		else
		{
			$output = '<li><a target="_blank" class="' . $type . '" href="' . $url . '"></a></li>';
		}
		
		
		return $output;
	}
	
	add_shortcode( 'social_icon', 'social_icon' );


/* ============================================================================================================================================= */


	function toggle_wrap( $atts, $content = "" )
	{
		$toggle_wrap = '<div class="toggle-group">' . do_shortcode( $content ) . '</div>';
		
		
		return $toggle_wrap;
	}
	
	add_shortcode( 'toggle_wrap', 'toggle_wrap' );


/* ============================================================================================================================================= */


	function toggle( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'title' => "" ), $atts ) );
		
		
		$toggle = '<div class="toggle"><h4>' . $title . '</h4><div class="toggle-content">' . do_shortcode( $content ) . '</div></div>';
		
		
		return $toggle;
	}
	
	add_shortcode( 'toggle', 'toggle' );


/* ============================================================================================================================================= */


	function accordion_wrap( $atts, $content = "" )
	{
		$accordion_wrap = '<div class="toggle-group accordion">' . do_shortcode( $content ) . '</div>';
		
		
		return $accordion_wrap;
	}
	
	add_shortcode( 'accordion_wrap', 'accordion_wrap' );


/* ============================================================================================================================================= */


	function accordion( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'title' => "" ), $atts ) );
		
		
		$accordion = '<div class="toggle"><h4>' . $title . '</h4><div class="toggle-content">' . do_shortcode( $content ) . '</div></div>';
		
		
		return $accordion;
	}
	
	add_shortcode( 'accordion', 'accordion' );


/* ============================================================================================================================================= */


	function tab_wrap( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'titles' => "",
										'active' => "" ), $atts ) );
		
		
		$titles_with_commas = $titles;
		$titles_with_markup = "";
		
		if ( $titles_with_commas != "" )
		{
			$titles_array = preg_split("/[\s]*[,][\s]*/", $titles_with_commas);
			
			foreach ( $titles_array as $title_name )
			{
				if ( $active == $title_name )
				{
					$titles_with_markup .= '<li><a class="active">' . $title_name . '</a></li>';
				}
				else
				{
					$titles_with_markup .= '<li><a>' . $title_name . '</a></li>';
				}
			}
		}
		
		
		$tab_wrap = '<div class="tabs"><ul class="tab-titles">' . $titles_with_markup . '</ul><div class="tab-content">' . do_shortcode( $content ) . '</div></div>';
		
		
		return $tab_wrap;
	}
	
	add_shortcode( 'tab_wrap', 'tab_wrap' );


/* ============================================================================================================================================= */


	function tab( $atts, $content = "" )
	{
		$tab = '<div>' . do_shortcode( $content ) . '</div>';
		
		
		return $tab;
	}
	
	add_shortcode( 'tab', 'tab' );


/* ============================================================================================================================================= */


	function project_action( $atts, $content = "" )
	{
		$project_action = '<div class="project-action">' . do_shortcode( $content ) . '</div>';
		
		
		return $project_action;
	}
	
	add_shortcode( 'project_action', 'project_action' );


/* ============================================================================================================================================= */


	function call_to_action( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'title' => "",
										'text'  => "" ), $atts ) );
		
		
		$call_to_action = '<div class="cta"><div class="row"><div class="col-sm-8"><h3>' . $title . '</h3><p>' . $text . '</p></div><div class="col-sm-4"><div class="cta-button">' . do_shortcode( $content ) . '</div></div></div></div>';
		
		
		return $call_to_action;
	}
	
	add_shortcode( 'call_to_action', 'call_to_action' );


/* ============================================================================================================================================= */


	function contact_form( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'to'      => "",
										'subject' => "",
										'captcha' => "" ), $atts ) );
		
		
		if ( $to != "" )
		{
			update_option( 'contact_form_to', $to );
		}
		else
		{
			$admin_email = get_bloginfo( 'admin_email' );
			
			update_option( 'contact_form_to', $admin_email );
		}
		
		
		if ( $captcha == "yes" )
		{
			$random1 = rand( 1, 5 );
			$random2 = rand( 1, 5 );
			
			$sum_random = $random1 + $random2;
			
			$captcha_out = '<p>';
			$captcha_out .= '<input type="hidden" id="captcha" name="captcha" value="yes">';
			$captcha_out .= '<label for="sum_user">' . $random1 . ' + ' . $random2 . ' = ?</label>';
			$captcha_out .= '<input type="text" id="sum_user" name="sum_user" class="required" placeholder="' . __( 'What is the sum?', 'editor' ) . '">';
			$captcha_out .= '<input type="hidden" id="sum_random" name="sum_random" value="' . $sum_random . '">';
			$captcha_out .= '</p>';
		}
		else
		{
			$captcha_out = '<p style="padding: 0px; margin: 0px;"><input type="hidden" id="captcha" name="captcha" value="no"></p>';
		}
		
		
		// Get the site domain and get rid of www.
		$site_url = strtolower( $_SERVER['SERVER_NAME'] );
		
		if ( substr( $site_url, 0, 4 ) == 'www.' )
		{
			$site_url = substr( $site_url, 4 );
		}
		
		$sender_domain = 'server@' . $site_url;
		
		
		$contact_form = '<div class="contact-form"><form id="contact-form" class="validate-form" method="post" action="' . get_template_directory_uri() . '/send-mail.php">';
		
		$contact_form .= '<input type="hidden" id="sender_domain" name="sender_domain" value="' . $sender_domain . '">';
		$contact_form .= '<input type="hidden" id="subject" name="subject" value="' . $subject . '">';
		
		$contact_form .= '<p><label for="name">' . __( 'NAME', 'editor' ) . '</label><input type="text" id="name" name="name" class="required"></p>';
		$contact_form .= '<p><label for="email">' . __( 'EMAIL', 'editor' ) . '</label><input type="text" id="email" name="email" class="required email"></p>';
		$contact_form .= '<p><label for="message">' . __( 'MESSAGE', 'editor' ) . '</label><textarea id="message" name="message" class="required"></textarea></p>';
		$contact_form .= $captcha_out;
		$contact_form .= '<p><button class="submit button"><span class="submit-label">' . __( 'Submit', 'editor' ) . '</span><span class="submit-status"></span></button></p>';
		$contact_form .= '</form></div>';
		
		
		return $contact_form;
	}
	
	add_shortcode( 'contact_form', 'contact_form' );


/* ============================================================================================================================================= */


	function section_title( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'text' => "",
										'align' => "" ), $atts ) );
		
		
		$section_title = '<h2 class="section-title ' . $align . '">' . $text . '</h2>';
		
		
		return $section_title;
	}
	
	add_shortcode( 'section_title', 'section_title' );


/* ============================================================================================================================================= */


	function latest_from_the_blog($atts, $content = "")
	{
		extract(shortcode_atts(array('items' => ""), $atts));
		
		if ($items == "")
		{
			$items = 10;
		}
		
		$post_list = "";
		$args = array('posts_per_page' => $items);
		$posts = get_posts($args);
		
		if ($posts)
		{
			$reading_time_markup = "";
			$reading_time = get_option('editor_reading_time', 'Yes');
			
			if ($reading_time != 'No')
			{
				$reading_time_markup = '<span class="read-time"><span class="eta"></span> ' . esc_html__('editor', 'editor') . '</span>';
			}
			
			foreach ($posts as $post)
			{
				setup_postdata($post);
				
				$post_list .= '<li>';
				$post_list .= '<article>';
				$post_list .= '<h3 class="entry-title">';
				$post_list .= '<a href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a>';
				$post_list .= '</h3>';
				$post_list .= '<div class="entry-meta">';
				$post_list .= $reading_time_markup;
				$post_list .= '</div>';
				$post_list .= '</article>';
				$post_list .= '</li>';
			}
			
			wp_reset_postdata();
		}
		
		$output = '<div class="post-list"><ul>' . $post_list . '</ul></div>';
		
		return $output;
	}
	
	add_shortcode('latest_from_the_blog', 'latest_from_the_blog');


/* ============================================================================================================================================= */


	function fun_fact( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'icon' => "",
										'text' => "" ), $atts ) );
		
		
		$fun_fact = '<div class="fun-fact"><i class="pw-icon-' . $icon . '"></i><h4>' . $text . '</h4></div>';
		
		
		return $fun_fact;
	}
	
	add_shortcode( 'fun_fact', 'fun_fact' );


/* ============================================================================================================================================= */


	function service( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'icon'  => "",
										'title' => "",
										'text'  => "" ), $atts ) );
		
		
		$service = '<div class="service"><i class="pw-icon-' . $icon . '"></i><h4>' . $title . '</h4><p>' . $text . '</p></div>';
		
		
		return $service;
	}
	
	add_shortcode( 'service', 'service' );


/* ============================================================================================================================================= */


	function launch_button( $atts, $content = "" )
	{
		$launch_button = '<p class="launch-wrap">' . do_shortcode( $content ) . '</p>';
		
		
		return $launch_button;
	}
	
	add_shortcode( 'launch_button', 'launch_button' );


/* ============================================================================================================================================= */


	function intro( $atts, $content = "" )
	{
		$intro = '<div class="intro cf"><h2>' . do_shortcode( $content ) . '</h2></div>';
		
		
		return $intro;
	}
	
	add_shortcode( 'intro', 'intro' );


/* ============================================================================================================================================= */


	function rotate_words( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'titles' => "" ), $atts ) );
		
		
		$titles_with_commas = $titles;
		$titles_with_markup = "";
		
		if ( $titles_with_commas != "" )
		{
			$titles_array = preg_split("/[\s]*[,][\s]*/", $titles_with_commas);
			
			foreach ( $titles_array as $title_name )
			{
				$titles_with_markup .= '<span>' . $title_name . '</span>';
			}
		}
		
		
		$rotate_words = '<span class="rotate-words">' . $titles_with_markup . '</span>';
		
		
		return $rotate_words;
	}
	
	add_shortcode( 'rotate_words', 'rotate_words' );


/* ============================================================================================================================================= */


	function skill_wrap( $atts, $content = "" )
	{
		$skill_wrap = '<div class="skillset">' . do_shortcode( $content ) . '</div>';
		
		
		return $skill_wrap;
	}
	
	add_shortcode( 'skill_wrap', 'skill_wrap' );


/* ============================================================================================================================================= */


	function skill( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'title' => "",
										'percent' => "" ), $atts ) );
		
		
		$skill = '<div class="skill-unit"><h4>' . $title . '</h4><div class="bar" data-percent="' . $percent . '"><div class="progress"></div></div></div>';
		
		
		return $skill;
	}
	
	add_shortcode( 'skill', 'skill' );


/* ============================================================================================================================================= */


	function testimonial_wrap( $atts, $content = "" )
	{
		$testimonial_wrap = '<div class="testo-group">' . do_shortcode( $content ) . '</div>';
		
		
		return $testimonial_wrap;
	}
	
	add_shortcode( 'testimonial_wrap', 'testimonial_wrap' );


/* ============================================================================================================================================= */


	function testimonial( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'image' => "",
										'title' => "",
										'sub_title' => "" ), $atts ) );
		
		
		$testimonial = '<div class="testo"><img alt="' . $title . '" src="' . $image . '"><h4>' . $title . '<span>' . $sub_title . '</span></h4><p>' . do_shortcode($content) . '</p></div>';
		
		
		return $testimonial;
	}
	
	add_shortcode( 'testimonial', 'testimonial' );


/* ============================================================================================================================================= */


	function timeline( $atts, $content = "" )
	{
		$timeline = '<div class="timeline">' . do_shortcode( $content ) . '</div>';
		
		
		return $timeline;
	}
	
	add_shortcode( 'timeline', 'timeline' );


/* ============================================================================================================================================= */


	function event_group_title( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'icon' => "",
										'text' => "" ), $atts ) );
		
		
		$event_group_title = '<div class="event"><h2>' . $text . '</h2><i class="pw-icon-' . $icon . '"></i></div>';
		
		
		return $event_group_title;
	}
	
	add_shortcode( 'event_group_title', 'event_group_title' );


/* ============================================================================================================================================= */


	function event( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'current' => "",
										'date' => "",
										'title' => "",
										'sub_title' => "" ), $atts ) );
		
		
		$event = '<div class="event ' . $current . '"><span class="date">' . $date . '</span><h4>' . $title . '</h4><h5>' . $sub_title . '</h5><p>' . do_shortcode( $content ) . '</p></div>';
		
		
		return $event;
	}
	
	add_shortcode( 'event', 'event' );


/* ============================================================================================================================================= */


	function slider($atts, $content = "")
	{
		extract(shortcode_atts(array('items'      => '1',
									 'loop'       => 'true',
									 'center'     => 'false',
									 'mouse_drag' => 'true',
									 'nav'        => 'true',
									 'dots'       => 'true',
									 'autoplay'   => 'false',
									 'speed'      => '600',
									 'timeout'    => '2000'), $atts));
		
		$output = '<div class="owl-carousel owl-loading" data-items="' . $items . '" data-loop="' . $loop . '" data-center="' . $center . '" data-mouse-drag="' . $mouse_drag . '" data-nav="' . $nav . '" data-dots="' . $dots . '" data-autoplay="' . $autoplay . '" data-autoplay-speed="' . $speed . '" data-autoplay-timeout="' . $timeout . '" data-nav-prev-text="' . esc_attr__('Prev', 'editor') . '" data-nav-next-text="' . esc_attr__('Next', 'editor') . '">' . do_shortcode($content) . '</div>';
		
		return $output;
	}
	
	add_shortcode('slider', 'slider');


/* ============================================================================================================================================= */


	function slide($atts, $content = "")
	{
		extract(
			shortcode_atts(
				array(
					'title' => "",
					'image' => ""
				),
				$atts
			)
		);
		
		$title_out = "";
		
		if ($title != "")
		{
			$title_out = '<p class="owl-title">' . $title . '</p>';
		}
		
		$slide = '<div><img alt="' . $title . '" src="' . $image . '">' . $title_out . '</div>';
		
		return $slide;
	}
	
	add_shortcode('slide', 'slide');


/* ============================================================================================================================================= */


	function quote( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'name'  => "",
										'align' => "" ), $atts ) );
		
		
		$quote = '<blockquote class="' . $align . '">' . do_shortcode( $content ) . '<cite>' . $name . '</cite></blockquote>';
		
		
		return $quote;
	}
	
	add_shortcode( 'quote', 'quote' );


/* ============================================================================================================================================= */


	function drop_cap( $atts, $content = "" )
	{
		$drop_cap = '<p class="drop-cap">' . do_shortcode( $content ) . '</p>';
		
		
		return $drop_cap;
	}
	
	add_shortcode( 'drop_cap', 'drop_cap' );


/* ============================================================================================================================================= */


	function tagline( $atts, $content = "" )
	{
		$tagline = '<div class="tagline"><p>' . do_shortcode( $content ) . '</p></div>';
		
		
		return $tagline;
	}
	
	add_shortcode( 'tagline', 'tagline' );


/* ============================================================================================================================================= */


	function tag_wrap( $atts, $content = "" )
	{
		$tag_wrap = '<ul class="tags">' . do_shortcode( $content ) . '</ul>';
		
		
		return $tag_wrap;
	}
	
	add_shortcode( 'tag_wrap', 'tag_wrap' );


/* ============================================================================================================================================= */


	function tag( $atts, $content = "" )
	{
		extract( shortcode_atts( array( 'text' => "" ), $atts ) );
		
		
		$tag = '<li><a>' . $text . '</a></li>';
		
		
		return $tag;
	}
	
	add_shortcode( 'tag', 'tag' );


/* ============================================================================================================================================= */


	function pixelwars_theme_run_shortcode( $content )
	{
		global $shortcode_tags;
		
		
		// Backup current registered shortcodes and clear them all out
		$orig_shortcode_tags = $shortcode_tags;
		
		remove_all_shortcodes();
		
		
		add_shortcode( 'row', 'row' );
		add_shortcode( 'column', 'column' );
		add_shortcode( 'alert', 'alert' );
		add_shortcode( 'contact_form', 'contact_form' );
		add_shortcode( 'section_title', 'section_title' );
		add_shortcode( 'latest_from_the_blog', 'latest_from_the_blog' );
		add_shortcode( 'fun_fact', 'fun_fact' );
		add_shortcode( 'service', 'service' );
		add_shortcode( 'launch_button', 'launch_button' );
		add_shortcode( 'project_action', 'project_action' );
		add_shortcode( 'call_to_action', 'call_to_action' );
		add_shortcode( 'button', 'button' );
		add_shortcode( 'intro', 'intro' );
		add_shortcode( 'rotate_words', 'rotate_words' );
		add_shortcode( 'social_icon_wrap', 'social_icon_wrap' );
		add_shortcode( 'social_icon', 'social_icon' );
		add_shortcode( 'toggle_wrap', 'toggle_wrap' );
		add_shortcode( 'toggle', 'toggle' );
		add_shortcode( 'accordion_wrap', 'accordion_wrap' );
		add_shortcode( 'accordion', 'accordion' );
		add_shortcode( 'tab_wrap', 'tab_wrap' );
		add_shortcode( 'tab', 'tab' );
		add_shortcode( 'skill_wrap', 'skill_wrap' );
		add_shortcode( 'skill', 'skill' );
		add_shortcode( 'testimonial_wrap', 'testimonial_wrap' );
		add_shortcode( 'testimonial', 'testimonial' );
		add_shortcode( 'timeline', 'timeline' );
		add_shortcode( 'event_group_title', 'event_group_title' );
		add_shortcode( 'event', 'event' );
		add_shortcode( 'slider', 'slider' );
		add_shortcode( 'slide', 'slide' );
		add_shortcode( 'quote', 'quote' );
		add_shortcode( 'drop_cap', 'drop_cap' );
		add_shortcode( 'tagline', 'tagline' );
		add_shortcode( 'tag_wrap', 'tag_wrap' );
		add_shortcode( 'tag', 'tag' );
		
		
		// Do the shortcode ( only the one above is registered )
		$content = do_shortcode( $content );
		
		// Put the original shortcodes back
		$shortcode_tags = $orig_shortcode_tags;
		
		
		return $content;
	}
	
	add_filter( 'the_content', 'pixelwars_theme_run_shortcode', 7 );


/* ============================================================================================================================================= */


	function pixelwars__portfolio_page_lightbox_gallery( $atts )
	{
		extract( shortcode_atts( array( 'ids' => "" ), $atts ) );
		
		$output = "";
		$items_with_commas = $ids;
		
		if ( $items_with_commas != "" )
		{
			global $wpdb;
			$items_in_array = preg_split( "/[\s]*[,][\s]*/", $items_with_commas );
			
			foreach ( $items_in_array as $item )
			{
				$image = wp_get_attachment_image_src( $item, 'full' );
				$image_caption = $wpdb->get_var( $wpdb->prepare( "SELECT post_excerpt FROM $wpdb->posts WHERE ID = %s", $item ) );
				
				$output .= '<a class="lightbox" href="' . esc_url( $image[0] ) . '" title="' . esc_attr( $image_caption ) . '"></a>';
			}
		}
		
		return $output;
	}
	
	
	function pixelwars__single_gallery_content( $atts )
	{
		extract( shortcode_atts( array( 'ids'  => "",
										'size' => 'thumbnail' ), $atts ) );
		
		$output = "";
		$items_with_commas = $ids;
		
		if ( $items_with_commas != "" )
		{
			global $wpdb;
			$items_in_array = preg_split( "/[\s]*[,][\s]*/", $items_with_commas );
			
			$output .= '<ul id="carousel" class="elastislide-list">';
			
				foreach ( $items_in_array as $item )
				{
					$image_small = wp_get_attachment_image_src( $item, 'thumbnail' );
					$image_big = wp_get_attachment_image_src( $item, 'pixelwars_theme_image_size_5' );
					$image_alt = get_post_meta( $item, '_wp_attachment_image_alt', true );
					$image_caption = $wpdb->get_var( $wpdb->prepare( "SELECT post_excerpt FROM $wpdb->posts WHERE ID = %s", $item ) );
					
					$output .= '<li><a href="' . esc_url( $image_big[0] ) . '">';
					$output .= '<img alt="' . esc_attr( $image_alt ) . '" data-description="' . esc_attr( $image_caption ) . '" src="' . esc_url( $image_small[0] ) . '">';
					$output .= '</a></li>';
				}
			
			$output .= '</ul>';
		}
		
		return $output;
	}
	
	
	function pixelwars__post_gallery($output = "", $atts, $content = false, $tag = false)
	{
		$new_output = $output;
		
		if (is_page_template('template-portfolio.php') || is_tax('department'))
		{
			$pf_type = get_option(get_the_ID() . 'pf_type', 'Standard');
			
			if ($pf_type == 'Lightbox Gallery')
			{
				$new_output = pixelwars__portfolio_page_lightbox_gallery($atts);
			}
		}
		elseif (is_singular('gallery'))
		{
			$new_output = pixelwars__single_gallery_content($atts);
		}
		
		return $new_output;
	}
	
	add_filter('post_gallery', 'pixelwars__post_gallery', 10, 4);


/* ============================================================================================================================================= */


	function editor_reading_time()
	{
		$reading_time = get_option('editor_reading_time', 'Yes');
		
		if ($reading_time != 'No')
		{
			?>
				<span class="read-time"><span class="eta"></span> <?php esc_html_e('read', 'editor'); ?></span>
			<?php
		}
	}


/* ============================================================================================================================================= */


	function pixelwars_theme_customize_register($wp_customize)
	{
		$wp_customize->add_section(
			'pixelwars__section_colors',
			array(
				'title'    => __('Colors', 'editor'),
				'priority' => 30
			)
		);
		
		$wp_customize->add_section(
			'pixelwars__section_fonts',
			array(
				'title'    => __('Fonts', 'editor'),
				'priority' => 31
			)
		);
		
		$wp_customize->add_section(
			'pixelwars__section_layout',
			array(
				'title'    => __('Layout', 'editor'),
				'priority' => 32
			)
		);
		
		$wp_customize->add_section(
			'pixelwars__section_custom_css',
			array(
				'title'       => __('Custom CSS', 'editor'),
				'description' => __('Quickly add custom css.', 'editor'),
				'priority'    => 33
			)
		);
		
		
		/* ================================================== */
		
		
		$wp_customize->add_setting(
			'setting_link_color',
			array(
				'default'           => '#AB977A',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh'
			)
		);
		
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'control_link_color',
				array(
					'label'    => __('Link Color', 'editor'),
					'section'  => 'pixelwars__section_colors',
					'settings' => 'setting_link_color'
				)
			)
		);
		
		
		$wp_customize->add_setting(
			'setting_link_hover_color',
			array(
				'default'           => '#C9B69B',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh'
			)
		);
		
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'control_link_hover_color',
				array(
					'label'    => __('Link Hover Color', 'editor'),
					'section'  => 'pixelwars__section_colors',
					'settings' => 'setting_link_hover_color'
				)
			)
		);
		
		
		/* ================================================== */
		
		
		include_once 'fonts.php';
		
		
		$wp_customize->add_setting(
			'setting_content_font',
			array(
				'default'           => 'Droid Serif',
				'sanitize_callback' => 'editor_sanitize',
				'transport'         => 'refresh'
			)
		);
		
		$wp_customize->add_control(
			'control_content_font',
			array(
				'label'    => 'Body Font',
				'section'  => 'pixelwars__section_fonts',
				'settings' => 'setting_content_font',
				'type'     => 'select',
				'choices'  => $all_fonts
			)
		);
		
		
		$wp_customize->add_setting(
			'setting_heading_font',
			array(
				'default'           => 'Open Sans',
				'sanitize_callback' => 'editor_sanitize',
				'transport'         => 'refresh'
			)
		);
		
		$wp_customize->add_control(
			'control_heading_font',
			array(
				'label'    => 'Heading Font',
				'section'  => 'pixelwars__section_fonts',
				'settings' => 'setting_heading_font',
				'type'     => 'select',
				'choices'  => $all_fonts
			)
		);
		
		
		$wp_customize->add_setting(
			'setting_menu_font',
			array(
				'default'           => 'Open Sans',
				'sanitize_callback' => 'editor_sanitize',
				'transport'         => 'refresh'
			)
		);
		
		$wp_customize->add_control(
			'control_menu_font',
			array(
				'label'    => 'Menu Font',
				'section'  => 'pixelwars__section_fonts',
				'settings' => 'setting_menu_font',
				'type'     => 'select',
				'choices'  => $all_fonts
			)
		);
		
		
		$wp_customize->add_setting(
			'setting_text_logo_font',
			array(
				'default'           => 'Open Sans',
				'sanitize_callback' => 'editor_sanitize',
				'transport'         => 'refresh'
			)
		);
		
		$wp_customize->add_control(
			'control_text_logo_font',
			array(
				'label'    => 'Text Logo Font',
				'section'  => 'pixelwars__section_fonts',
				'settings' => 'setting_text_logo_font',
				'type'     => 'select',
				'choices'  => $all_fonts
			)
		);
		
		
		/* ================================================== */
		
		
		$content_width_value = array(
			'500px'   => '500px',
			'600px'   => '600px',
			'650px'   => '650px',
			'700px'   => '700px',
			'default' => '740px',
			'800px'   => '800px',
			'850px'   => '850px',
			'900px'   => '900px',
			'950px'   => '950px',
			'1000px'  => '1000px',
			'1200px'  => '1200px',
			'1400px'  => '1400px'
		);
		
		$wp_customize->add_setting(
			'setting_content_width',
			array(
				'default'           => 'default',
				'sanitize_callback' => 'editor_sanitize',
				'transport'         => 'refresh'
			)
		);
		
		$wp_customize->add_control(
			'control_content_width',
			array(
				'label'    => 'Content Width',
				'section'  => 'pixelwars__section_layout',
				'settings' => 'setting_content_width',
				'type'     => 'select',
				'choices'  => $content_width_value
			)
		);
		
		
		/* ================================================== */
		
		
		$wp_customize->add_setting(
			'pixelwars__setting_custom_css',
			array(
				'default'           => "",
				'sanitize_callback' => 'editor_sanitize',
				'capability'        => 'edit_theme_options'
			)
		);
		
		$wp_customize->add_control(
			'pixelwars__control_custom_css',
			array(
				'label'    => __('Custom CSS', 'editor'),
				'section'  => 'pixelwars__section_custom_css',
				'settings' => 'pixelwars__setting_custom_css',
				'type'     => 'textarea'
			)
		);
		
		
		/* ================================================== */
		
		
		$wp_customize->get_setting('blogname')->transport = 'postMessage';
		$wp_customize->get_setting('blogdescription')->transport = 'postMessage';
		$wp_customize->get_setting('setting_link_color')->transport = 'postMessage';
		$wp_customize->get_setting('setting_link_hover_color')->transport = 'postMessage';
		$wp_customize->get_setting('setting_content_font')->transport = 'postMessage';
		$wp_customize->get_setting('setting_heading_font')->transport = 'postMessage';
		$wp_customize->get_setting('setting_menu_font')->transport = 'postMessage';
		$wp_customize->get_setting('setting_text_logo_font')->transport = 'postMessage';
		$wp_customize->get_setting('setting_content_width')->transport = 'postMessage';
		$wp_customize->get_setting('pixelwars__setting_custom_css')->transport = 'postMessage';
	}
	
	add_action('customize_register', 'pixelwars_theme_customize_register');
	
	
	function editor_sanitize($value)
	{
		return $value;
	}
	
	
	function pixelwars_theme_customize_css()
	{
		global $pixelwars_subset;
		
		
		$extra_font_styles = get_option( 'extra_font_styles', 'No' );
		
		if ( $extra_font_styles == 'Yes' )
		{
			$font_styles = ':300,400,600,700,800,900,300italic,400italic,600italic,700italic,800italic,900italic';
		}
		else
		{
			$font_styles = ':400,700,400italic,700italic';
		}
		
		
		/* ================================================== */
		
		
		$setting_content_font = get_theme_mod( 'setting_content_font', "" );
		
		if ( $setting_content_font != "" )
		{
			?>

<!-- Body Font -->
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=<?php echo str_replace( ' ', '+', $setting_content_font ) . $font_styles . $pixelwars_subset; ?>">

<style type="text/css"> body, input, textarea, select, button { font-family: "<?php echo $setting_content_font; ?>"; } </style>
			<?php
		}
		
		
		$setting_heading_font = get_theme_mod( 'setting_heading_font', "" );
		
		if ( $setting_heading_font != "" )
		{
			?>

<!-- Heading Font -->
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=<?php echo str_replace( ' ', '+', $setting_heading_font ) . $font_styles . $pixelwars_subset; ?>">

<style type="text/css"> h1, h2, h3, h4, h5, h6, .entry-meta, .entry-header, .navigation, .post-pagination, tr th, dl dt, .header-links,
input[type=submit], input[type=button], button, a.button, .button, label, .comment .reply, .comment-meta,
.yarpp-thumbnail-title, .tab-titles, .skill-unit .bar .progress span, .owl-theme .owl-nav [class*='owl-'],
.widget_categories ul li.cat-item a { font-family: "<?php echo $setting_heading_font; ?>"; } </style>
			<?php
		}
		
		
		$setting_menu_font = get_theme_mod( 'setting_menu_font', "" );
		
		if ( $setting_menu_font != "" )
		{
			?>

<!-- Menu Font -->
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=<?php echo str_replace( ' ', '+', $setting_menu_font ) . $font_styles . $pixelwars_subset; ?>">

<style type="text/css"> .nav-menu { font-family: "<?php echo $setting_menu_font; ?>"; } </style>
			<?php
		}
		
		
		$setting_text_logo_font = get_theme_mod( 'setting_text_logo_font', "" );
		
		if ( $setting_text_logo_font != "" )
		{
			?>

<!-- Text Logo Font -->
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=<?php echo str_replace( ' ', '+', $setting_text_logo_font ) . $font_styles . $pixelwars_subset; ?>">

<style type="text/css"> .site-header .site-title { font-family: "<?php echo $setting_text_logo_font; ?>"; } </style>
			<?php
		}
		
		
		/* ================================================== */
		
		
		$setting_link_color = get_theme_mod( 'setting_link_color', "" );
		
		if ( $setting_link_color != "" )
		{
			?>

<!-- Link Color -->
<style type="text/css">
a { color: <?php echo $setting_link_color; ?>; }

.owl-theme .owl-nav div { background: <?php echo $setting_link_color; ?>; }
</style>
			<?php
		}
		
		
		/* ================================================== */
		
		
		$setting_link_hover_color = get_theme_mod( 'setting_link_hover_color', "" );
		
		if ( $setting_link_hover_color != "" )
		{
			?>

<!-- Link Hover Color -->
<style type="text/css">
a:hover, .nav-menu ul li a:hover, .nav-menu ul li:hover > a, .nav-menu ul li a.selected { color: <?php echo $setting_link_hover_color; ?>; }

.owl-theme .owl-nav div:hover { background: <?php echo $setting_link_hover_color; ?>; }
</style>
			<?php
		}
		
		
		/* ================================================== */
		
		
		$setting_content_width = get_theme_mod( 'setting_content_width', 'default' );
		
		if ( $setting_content_width != 'default' )
		{
			?>

<!-- Content Width -->
<style type="text/css"> .layout-fixed { max-width: <?php echo $setting_content_width; ?>; } </style>
			<?php
		}
		
		
		/* ================================================== */
		
		
		$pixelwars__setting_custom_css = get_theme_mod( 'pixelwars__setting_custom_css', "" );
		
		if ( $pixelwars__setting_custom_css != "" )
		{
			?>

<!-- Custom CSS -->
<style type="text/css"> <?php echo $pixelwars__setting_custom_css; ?> </style>
			<?php
		}
	}
	
	add_action( 'wp_head', 'pixelwars_theme_customize_css' );
	
	
	function pixelwars_theme_customize_preview_js()
	{
		wp_enqueue_script( 'pixelwars_theme_customizer', get_template_directory_uri() . '/js/wp-theme-customizer.js', null, null, true );
	}
	
	add_action( 'customize_preview_init', 'pixelwars_theme_customize_preview_js' );


/* ============================================================================================================================================= */


	if (! function_exists('editor_portfolio_page__post_class'))
	{
		function editor_portfolio_page__post_class()
		{
			$taxonomy         = 'department';
			$categories_slugs = "";
			$categories 	  = get_the_terms(get_the_ID(), $taxonomy);
			
			if ($categories && (! is_wp_error($categories)))
			{
				foreach ($categories as $category)
				{
					// Get post's category slug and its parent category slug.
					
					$categories_slugs .= get_term_parents_list(
						$category->term_id,
						$taxonomy,
						array(
							'format'    => 'slug',
							'separator' => ' ',
							'link'      => false,
							'inclusive' => true,
						)
					);
				}
			}
			
			$post_class = 'media-cell hentry' . ' ' . esc_attr($categories_slugs);
			
			return $post_class;
		}
	}


/* ============================================================================================================================================= */


	if ( is_admin() )
	{
		include_once 'theme-options.php';
	}
	
	
	include_once 'shortcode-generator.php';


/* ============================================================================================================================================= */


	function pixelwars_options_wp_head()
	{
		?>

			<!--[if lt IE 9]>
				<script src="<?php echo get_template_directory_uri(); ?>/js/ie.js"></script>
			<![endif]-->

		<?php
	}
	
	add_action( 'wp_head', 'pixelwars_options_wp_head' );


/* ============================================================================================================================================= */


	include_once(get_template_directory() . '/admin/install-plugins.php');
	include_once(get_template_directory() . '/admin/demo-import.php');

?>