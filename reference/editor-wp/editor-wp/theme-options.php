<?php

	function pixelwars__create_tabs( $current = 'general' )
	{
		$tabs = array(  'general'     => 'General',
						'style'       => 'Style',
						'blog'        => 'Blog',
						'main-slider' => 'Main Slider',
						'portfolio'   => 'Portfolio',
						'gallery'     => 'Gallery',
						'sidebar'     => 'Sidebar' );
		
		?>
			<h1>Theme Options</h1>
			
			<h2 class="nav-tab-wrapper">
				<?php
					foreach ( $tabs as $tab => $name )
					{
						$class = ( $tab == $current ) ? ' nav-tab-active' : "";
						
						echo "<a class='nav-tab$class' href='?page=theme-options&tab=$tab'>$name</a>";
					}
				?>
			</h2>
		<?php
	}


/* ============================================================================================================================================ */


	function pixelwars__theme_options_page()
	{
		global $pagenow;
		
		?>
			<div class="wrap wrap2">
				<div class="status">
					<img alt="..." src="<?php echo get_template_directory_uri(); ?>/admin/ajax-loader.gif">
					
					<strong></strong>
				</div>
				
				
				<script>
					jQuery(document).ready(function($)
					{
					// -------------------------------------------------------------------------
					
						var uploadID = '',
							uploadImg = '';

						jQuery( '.upload-button' ).click(function()
						{
							uploadID = jQuery(this).prev( 'input' );
							uploadImg = jQuery(this).next( 'img' );
							formfield = jQuery( '.upload' ).attr( 'name' );
							tb_show( "", 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true' );
							return false;
						});
						
						window.send_to_editor = function( html )
						{
							imgurl = jQuery( 'img', html ).attr( 'src' );
							uploadID.val( imgurl );
							uploadImg.attr('src', imgurl);
							tb_remove();
						}
					
					
					// -------------------------------------------------------------------------
					
					
						$( ".alert-success p" ).click(function()
						{
							$(this).fadeOut( "slow", function()
							{
								$( ".alert-success" ).slideUp( "slow" );
							});
						});
					
					
					// -------------------------------------------------------------------------
					
					
						$( '.color' ).change( function()
						{
							var myColor = $( this ).val();
							
							$( this ).prev( 'div' ).find( 'div' ).css( 'backgroundColor', '#' + myColor );
						});
						
						
						$( '.color' ).keypress( function()
						{
							var myColor = $( this ).val();
							
							$( this ).prev( 'div' ).find( 'div' ).css( 'backgroundColor', '#' + myColor );
						});
					
					
					// -------------------------------------------------------------------------
					
					
						$( 'form.ajax-form' ).submit(function()
						{
							$.ajax(
							{
								data: $( this ).serialize(),
								type: "POST",
								beforeSend: function()
								{
									$( '.status' ).removeClass( 'status-done' );
									$( '.status img' ).show();
									$( '.status strong' ).html( 'Saving...' );
									$( '.status' ).fadeIn();
								},
								success: function(data)
								{
									$( '.status img' ).hide();
									$( '.status' ).addClass( 'status-done' );
									$( '.status strong' ).html( 'Done.' );
									$( '.status' ).delay( 1000 ).fadeOut();
								}
							});
							
							return false;
						});
					
					
					// -------------------------------------------------------------------------
					});
				</script>
				
				
				<?php
					
					if ( isset( $_GET['tab'] ) )
					{
						pixelwars__create_tabs( $_GET['tab'] );
					}	
					else
					{
						pixelwars__create_tabs( 'general' );
					}
					
				?>
				
				
				<div id="poststuff">
					<?php
					
						// theme options page
						if ( $pagenow == 'themes.php' && $_GET['page'] == 'theme-options' )
						{
							// tab from url
							if ( isset( $_GET['tab'] ) )
							{
								$tab = $_GET['tab'];
							}
							else
							{
								$tab = 'general'; 
							}
							
							
							switch ( $tab )
							{
								case 'general' :
								
									if (isset($_GET['saved']) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<form method="post" class="ajax-form" action="<?php admin_url( 'themes.php?page=theme-options' ); ?>">
													<?php
														wp_nonce_field( "settings-page" );
													?>
													
													
													<table>
														<tr>
															<td class="option-left">
																<h4>Logo Type</h4>
																
																<?php
																	$logo_type = get_option( 'logo_type', 'Text Logo' );
																?>
																
																<select id="logo_type" name="logo_type">
																	<option <?php if ( $logo_type == 'Text Logo' ) { echo 'selected="selected"'; } ?>>Text Logo</option>
																	
																	<option <?php if ( $logo_type == 'Image Logo' ) { echo 'selected="selected"'; } ?>>Image Logo</option>
																</select>
															</td>
															
															<td class="option-right">
																Select logo type.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Image Logo</h4>
																
																<?php
																	$logo_image = get_option( 'logo_image', "" );
																?>
																
																<input type="text" id="logo_image" name="logo_image" class="upload code2" value="<?php echo esc_url( $logo_image ); ?>">
																
																<input type="button" class="button upload-button" style="margin-top: 10px;" value="Browse">
																
																<img style="margin-top: 10px; max-height: 50px;" align="right" src="<?php echo esc_url($logo_image); ?>">
															</td>
															
															<td class="option-right">
																Upload a logo or specify an image address of your online logo.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Text Logo</h4>
																
																<?php
																	$select_text_logo = get_option( 'select_text_logo', 'WordPress Site Title' );
																?>
																
																<select id="select_text_logo" name="select_text_logo">
																	<option <?php if ( $select_text_logo == 'WordPress Site Title' ) { echo 'selected="selected"'; } ?>>WordPress Site Title</option>
																	
																	<option <?php if ( $select_text_logo == 'Theme Site Title' ) { echo 'selected="selected"'; } ?>>Theme Site Title</option>
																</select>
																
																
																<h4>Theme Site Title</h4>
																
																<?php
																	$theme_site_title = stripcslashes( get_option( 'theme_site_title', "" ) );
																?>
																
																<input type="text" id="theme_site_title" name="theme_site_title" value="<?php echo esc_attr( $theme_site_title ); ?>">
															</td>
															
															<td class="option-right">
																Site title.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Tagline</h4>
																
																<?php
																	$select_tagline = get_option( 'select_tagline', 'WordPress Tagline' );
																?>
																<select id="select_tagline" name="select_tagline">
																	<option <?php if ( $select_tagline == 'WordPress Tagline' ) { echo 'selected="selected"'; } ?>>WordPress Tagline</option>
																	
																	<option <?php if ( $select_tagline == 'Theme Tagline' ) { echo 'selected="selected"'; } ?>>Theme Tagline</option>
																</select>
																
																
																<h4>Theme Tagline</h4>
																
																<?php
																	$theme_tagline = stripcslashes( get_option( 'theme_tagline', "" ) );
																?>
																
																<input type="text" id="theme_tagline" name="theme_tagline" value="<?php echo esc_attr( $theme_tagline ); ?>">
															</td>
															
															<td class="option-right">
																In a few words, explain what this site is about.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Login Logo</h4>
																
																<?php
																	$logo_login = get_option( 'logo_login', "" );
																?>
																
																<input type="text" id="logo_login" name="logo_login" class="upload code2" style="width: 100%;" value="<?php echo esc_url( $logo_login ); ?>">
																
																<input type="button" class="button upload-button" style="margin-top: 10px;" value="Browse">
																
																<img style="margin-top: 10px; max-height: 50px;" align="right" src="<?php echo esc_url( $logo_login ); ?>">
																
																<br>
																
																<?php
																	$logo_login_hide = get_option( 'logo_login_hide', false );
																?>
																
																<label><input type="checkbox" id="logo_login_hide" name="logo_login_hide" <?php if ( $logo_login_hide ) { echo 'checked="checked"'; } ?>> Hide Login Logo Module</label>
															</td>
															
															<td class="option-right">
																A PNG image.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								
								break;
								
								
								case 'style' :
								
									if (isset($_GET['saved']) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<form class="ajax-form" method="post" action="<?php admin_url( 'themes.php?page=theme-options' ); ?>">
													<?php
														wp_nonce_field( "settings-page" );
													?>
													
													
													<table>
														<tr>
															<td class="option-left">
																<h4>Fonts and Colors</h4>
																
																<?php
																	echo '<a href="' . esc_url( admin_url( 'customize.php' ) ) . '">Customize</a>';
																?>
															</td>
															
															<td class="option-right">
																Select from theme customizer.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Character Sets</h4>
																
																<label><input type="checkbox" id="char_set_latin" name="char_set_latin" <?php if ( get_option( 'char_set_latin', true ) ) { echo 'checked="checked"'; } ?>> Latin</label>
																
																<br>
																
																<label><input type="checkbox" id="char_set_latin_ext" name="char_set_latin_ext" <?php if ( get_option( 'char_set_latin_ext' ) ) { echo 'checked="checked"'; } ?>> Latin Extended</label>
																
																<br>
																
																<label><input type="checkbox" id="char_set_cyrillic" name="char_set_cyrillic" <?php if ( get_option( 'char_set_cyrillic' ) ) { echo 'checked="checked"'; } ?>> Cyrillic</label>
																
																<br>
																
																<label><input type="checkbox" id="char_set_cyrillic_ext" name="char_set_cyrillic_ext" <?php if ( get_option( 'char_set_cyrillic_ext' ) ) { echo 'checked="checked"'; } ?>> Cyrillic Extended</label>
																
																<br>
																
																<label><input type="checkbox" id="char_set_greek" name="char_set_greek" <?php if ( get_option( 'char_set_greek' ) ) { echo 'checked="checked"'; } ?>> Greek</label>
																
																<br>
																
																<label><input type="checkbox" id="char_set_greek_ext" name="char_set_greek_ext" <?php if ( get_option( 'char_set_greek_ext' ) ) { echo 'checked="checked"'; } ?>> Greek Extended</label>
																
																<br>
																
																<label><input type="checkbox" id="char_set_vietnamese" name="char_set_vietnamese" <?php if ( get_option( 'char_set_vietnamese' ) ) { echo 'checked="checked"'; } ?>> Vietnamese</label>
															</td>
															
															<td class="option-right">
																Select any of them to include to the Google Fonts if the selected fonts have ones of them in their family.
																<br>
																<br>
																To see the supported character sets visit Google Fonts online.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Font Styles</h4>
																
																<?php
																	$extra_font_styles = get_option( 'extra_font_styles', 'No' );
																?>
																
																<select id="extra_font_styles" name="extra_font_styles">
																	<option <?php if ( $extra_font_styles == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																	
																	<option <?php if ( $extra_font_styles == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															
															<td class="option-right">
																Bold and italic styles.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Fixed Menu</h4>
																
																<?php
																	$fixed_header = get_option( 'fixed_header', 'Yes' );
																?>
																
																<select id="fixed_header" name="fixed_header">
																	<option <?php if ( $fixed_header == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																	
																	<option <?php if ( $fixed_header == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															
															<td class="option-right">
																Enable/disable.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Menu Search</h4>
																
																<?php
																	$nav_menu_search = get_option( 'nav_menu_search', 'No' );
																?>
																
																<select id="nav_menu_search" name="nav_menu_search">
																	<option <?php if ( $nav_menu_search == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																	
																	<option <?php if ( $nav_menu_search == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															
															<td class="option-right">
																Show/hide.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Mobile Zoom</h4>
																
																<?php
																	$mobile_zoom = get_option( 'mobile_zoom', 'Yes' );
																?>
																
																<select id="mobile_zoom" name="mobile_zoom">
																	<option <?php if ( $mobile_zoom == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																	
																	<option <?php if ( $mobile_zoom == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															
															<td class="option-right">
																Enable/disable.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Footer Widget Locations</h4>
																
																<?php
																	$footer_widget_locations = get_option( 'footer_widget_locations', 'No' );
																?>
																
																<select id="footer_widget_locations" name="footer_widget_locations">
																	<option <?php if ( $footer_widget_locations == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																	
																	<option <?php if ( $footer_widget_locations == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															
															<td class="option-right">
																Enable/disable.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Page Comments</h4>
																
																<?php
																	$page_comments = get_option( 'page_comments', 'Yes' );
																?>
																
																<select id="page_comments" name="page_comments">
																	<option <?php if ( $page_comments == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																	
																	<option <?php if ( $page_comments == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															
															<td class="option-right">
																Allow page comments.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								
								break;
								
								
								case 'blog' :
								
									if (isset($_GET['saved']) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<form class="ajax-form" method="post" action="<?php admin_url('themes.php?page=theme-options'); ?>">
													<?php
														wp_nonce_field('settings-page');
													?>
													<table>
														<tr>
															<td class="option-left">
																<h4>Blog Type</h4>
																<?php
																	$blog_type = get_option( 'blog_type', 'Regular' );
																?>
																<select id="blog_type" name="blog_type">
																	<option <?php if ( $blog_type == 'Regular' ) { echo 'selected="selected"'; } ?>>Regular</option>
																	<option <?php if ( $blog_type == 'Simple' ) { echo 'selected="selected"'; } ?>>Simple</option>
																	<option <?php if ( $blog_type == 'Masonry' ) { echo 'selected="selected"'; } ?>>Masonry</option>
																</select>
																
																<h4>Category Archive Type</h4>
																<?php
																	$category_archive_type = get_option( 'category_archive_type', 'Regular' );
																?>
																<select id="category_archive_type" name="category_archive_type">
																	<option <?php if ( $category_archive_type == 'Regular' ) { echo 'selected="selected"'; } ?>>Regular</option>
																	<option <?php if ( $category_archive_type == 'Simple' ) { echo 'selected="selected"'; } ?>>Simple</option>
																	<option <?php if ( $category_archive_type == 'Masonry' ) { echo 'selected="selected"'; } ?>>Masonry</option>
																</select>
																
																<h4>Tag Archive Type</h4>
																<?php
																	$tag_archive_type = get_option( 'tag_archive_type', 'Regular' );
																?>
																<select id="tag_archive_type" name="tag_archive_type">
																	<option <?php if ( $tag_archive_type == 'Regular' ) { echo 'selected="selected"'; } ?>>Regular</option>
																	<option <?php if ( $tag_archive_type == 'Simple' ) { echo 'selected="selected"'; } ?>>Simple</option>
																	<option <?php if ( $tag_archive_type == 'Masonry' ) { echo 'selected="selected"'; } ?>>Masonry</option>
																</select>
																
																<h4>Author Archive Type</h4>
																<?php
																	$author_archive_type = get_option( 'author_archive_type', 'Regular' );
																?>
																<select id="author_archive_type" name="author_archive_type">
																	<option <?php if ( $author_archive_type == 'Regular' ) { echo 'selected="selected"'; } ?>>Regular</option>
																	<option <?php if ( $author_archive_type == 'Simple' ) { echo 'selected="selected"'; } ?>>Simple</option>
																	<option <?php if ( $author_archive_type == 'Masonry' ) { echo 'selected="selected"'; } ?>>Masonry</option>
																</select>
																
																<h4>Date Archive Type</h4>
																<?php
																	$date_archive_type = get_option( 'date_archive_type', 'Regular' );
																?>
																<select id="date_archive_type" name="date_archive_type">
																	<option <?php if ( $date_archive_type == 'Regular' ) { echo 'selected="selected"'; } ?>>Regular</option>
																	<option <?php if ( $date_archive_type == 'Simple' ) { echo 'selected="selected"'; } ?>>Simple</option>
																	<option <?php if ( $date_archive_type == 'Masonry' ) { echo 'selected="selected"'; } ?>>Masonry</option>
																</select>
																
																<h4>Search Result Type</h4>
																<?php
																	$search_result_type = get_option( 'search_result_type', 'Regular' );
																?>
																<select id="search_result_type" name="search_result_type">
																	<option <?php if ( $search_result_type == 'Regular' ) { echo 'selected="selected"'; } ?>>Regular</option>
																	<option <?php if ( $search_result_type == 'Simple' ) { echo 'selected="selected"'; } ?>>Simple</option>
																	<option <?php if ( $search_result_type == 'Masonry' ) { echo 'selected="selected"'; } ?>>Masonry</option>
																</select>
															</td>
															<td class="option-right">
																Select layout type.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Blog Sidebar</h4>
																<?php
																	$blog_sidebar = get_option( 'blog_sidebar', 'Yes' );
																?>
																<select id="blog_sidebar" name="blog_sidebar">
																	<option <?php if ( $blog_sidebar == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																	<option <?php if ( $blog_sidebar == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																</select>
																
																<h4>Post Sidebar</h4>
																<?php
																	$post_sidebar = get_option( 'post_sidebar', 'Yes' );
																?>
																<select id="post_sidebar" name="post_sidebar">
																	<option <?php if ( $post_sidebar == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																	<option <?php if ( $post_sidebar == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															<td class="option-right">
																Enable/disable.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Automatic Excerpt</h4>
																<?php
																	$theme_excerpt = get_option( 'theme_excerpt', 'No' );
																?>
																<select id="theme_excerpt" name="theme_excerpt">
																	<option <?php if ( $theme_excerpt == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																	<option <?php if ( $theme_excerpt == 'standard' ) { echo 'selected="selected"'; } ?> value="standard">Yes - Only for standard format</option>
																	<option <?php if ( $theme_excerpt == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes - For all post formats</option>
																</select>
															</td>
															<td class="option-right">
																Generates an excerpt from the post content.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Blog Masonry Layout</h4>
																<?php
																	$blog_masonry_layout = get_option( 'blog_masonry_layout', 'masonry' );
																?>
																<select id="blog_masonry_layout" name="blog_masonry_layout">
																	<option <?php if ( $blog_masonry_layout == 'masonry' ) { echo 'selected="selected"'; } ?>>masonry</option>
																	<option <?php if ( $blog_masonry_layout == 'fitRows' ) { echo 'selected="selected"'; } ?>>fitRows</option>
																</select>
																
																<h4>Blog Masonry Post Width</h4>
																<?php
																	$blog_masonry_item_width = get_option( 'blog_masonry_item_width', '340' );
																?>
																<input type="number" min="100" max="1920" step="10" size="6" maxlength="6" id="blog_masonry_item_width" name="blog_masonry_item_width" value="<?php echo esc_attr( $blog_masonry_item_width ); ?>">
																<span style="font-size: 11px; color: #666;">Default: 340 px</span>
															</td>
															<td class="option-right">
																Item width.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Author Info Box</h4>
																
																<?php
																	$about_the_author_module = get_option( 'about_the_author_module', 'Yes' );
																?>
																
																<select id="about_the_author_module" name="about_the_author_module">
																	<option <?php if ( $about_the_author_module == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																	
																	<option <?php if ( $about_the_author_module == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															
															<td class="option-right">
																Enable/disable.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Related Posts</h4>
																
																<?php
																	$pixelwars__related_posts = get_option( 'pixelwars__related_posts', 'Yes' );
																?>
																
																<select id="pixelwars__related_posts" name="pixelwars__related_posts">
																	<option <?php if ( $pixelwars__related_posts == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																	
																	<option <?php if ( $pixelwars__related_posts == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															
															<td class="option-right">
																Enable/disable.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Numbered Pagination</h4>
																
																<?php
																	$pagination = get_option( 'pagination', 'No' );
																?>
																
																<select id="pagination" name="pagination">
																	<option <?php if ( $pagination == 'Yes' ) { echo 'selected="selected"'; } ?>>Yes</option>
																	
																	<option <?php if ( $pagination == 'No' ) { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															
															<td class="option-right">
																Use numbered pagination instead of Older-Newer links.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Reading Time</h4>
																<?php
																	$editor_reading_time = get_option('editor_reading_time', 'Yes');
																?>
																<select name="editor_reading_time" style="width: 100%;">
																	<option <?php if ($editor_reading_time == 'Yes') { echo 'selected="selected"'; } ?>>Yes</option>
																	<option <?php if ($editor_reading_time == 'No') { echo 'selected="selected"'; } ?>>No</option>
																</select>
															</td>
															<td class="option-right">
																Enable/disable.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								break;
								
								
								case 'main-slider' :
								
									if (isset($_GET['saved']) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									
									?>
										<div class="postbox">
											<div class="inside">
												<form class="ajax-form" method="post" action="<?php admin_url( 'themes.php?page=theme-options' ); ?>">
													<?php
														wp_nonce_field( "settings-page" );
													?>
													<table>
														<tr>
															<td class="option-left">
																<h4>Activate</h4>
																<?php
																	$main_slider = get_option( 'main_slider', 'No' );
																?>
																<select id="main_slider" name="main_slider">
																	<option>No</option>
																	<option <?php if ( $main_slider == 'Yes' ) { echo 'selected="selected"'; } ?> value="Yes">Yes - exclude sticky posts from blog</option>
																	<option <?php if ( $main_slider == 'Yes2' ) { echo 'selected="selected"'; } ?> value="Yes2">Yes - include sticky posts to blog</option>
																</select>
															</td>
															<td class="option-right">
																Enable/disable.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Slides</h4>
																<?php
																	$editor_main_slider_slides = get_option( 'editor_main_slider_slides', 'sticky' );
																?>
																<select name="editor_main_slider_slides">
																	<option <?php if ( $editor_main_slider_slides == 'sticky' ) { echo 'selected="selected"'; } ?> value="sticky">Sticky posts</option>
																	<option <?php if ( $editor_main_slider_slides == 'latest' ) { echo 'selected="selected"'; } ?> value="latest">Latest posts</option>
																</select>
																
																<h4>Slides Count</h4>
																<?php
																	$editor_main_slider_latest_posts_count = get_option( 'editor_main_slider_latest_posts_count', '5' );
																?>
																<input type="number" min="1" max="20" step="1" name="editor_main_slider_latest_posts_count" value="<?php echo esc_attr( $editor_main_slider_latest_posts_count ); ?>">
															</td>
															<td class="option-right">
																Create sticky posts with featured image or show latest posts.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<h4>Show Items</h4>
																<?php
																	$pixelwars_homepage_owl_carousel_items = get_option( 'pixelwars_homepage_owl_carousel_items', '3' );
																?>
																<select id="pixelwars_homepage_owl_carousel_items" name="pixelwars_homepage_owl_carousel_items">
																	<option <?php if ( $pixelwars_homepage_owl_carousel_items == '1' ) { echo 'selected="selected"'; } ?>>1</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_items == '2' ) { echo 'selected="selected"'; } ?>>2</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_items == '3' ) { echo 'selected="selected"'; } ?>>3</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_items == '4' ) { echo 'selected="selected"'; } ?>>4</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_items == '5' ) { echo 'selected="selected"'; } ?>>5</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_items == '6' ) { echo 'selected="selected"'; } ?>>6</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_items == '7' ) { echo 'selected="selected"'; } ?>>7</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_items == '8' ) { echo 'selected="selected"'; } ?>>8</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_items == '9' ) { echo 'selected="selected"'; } ?>>9</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_items == '10' ) { echo 'selected="selected"'; } ?>>10</option>
																</select>
																
																<h4>Loop</h4>
																<?php
																	$pixelwars_homepage_owl_carousel_loop = get_option( 'pixelwars_homepage_owl_carousel_loop', 'true' );
																?>
																<select id="pixelwars_homepage_owl_carousel_loop" name="pixelwars_homepage_owl_carousel_loop">
																	<option <?php if ( $pixelwars_homepage_owl_carousel_loop == 'true' ) { echo 'selected="selected"'; } ?> value="true">Yes</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_loop == 'false' ) { echo 'selected="selected"'; } ?> value="false">No</option>
																</select>
																
																<h4>Center</h4>
																<?php
																	$pixelwars_homepage_owl_carousel_center = get_option( 'pixelwars_homepage_owl_carousel_center', 'false' );
																?>
																<select id="pixelwars_homepage_owl_carousel_center" name="pixelwars_homepage_owl_carousel_center">
																	<option <?php if ( $pixelwars_homepage_owl_carousel_center == 'true' ) { echo 'selected="selected"'; } ?> value="true">Yes</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_center == 'false' ) { echo 'selected="selected"'; } ?> value="false">No</option>
																</select>
																
																<h4>Mouse Drag</h4>
																<?php
																	$pixelwars_homepage_owl_carousel_mouse_drag = get_option( 'pixelwars_homepage_owl_carousel_mouse_drag', 'true' );
																?>
																<select id="pixelwars_homepage_owl_carousel_mouse_drag" name="pixelwars_homepage_owl_carousel_mouse_drag">
																	<option <?php if ( $pixelwars_homepage_owl_carousel_mouse_drag == 'true' ) { echo 'selected="selected"'; } ?> value="true">Yes</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_mouse_drag == 'false' ) { echo 'selected="selected"'; } ?> value="false">No</option>
																</select>
																
																<h4>Prev/Next Buttons</h4>
																<?php
																	$pixelwars_homepage_owl_carousel_nav_links = get_option( 'pixelwars_homepage_owl_carousel_nav_links', 'true' );
																?>
																<select id="pixelwars_homepage_owl_carousel_nav_links" name="pixelwars_homepage_owl_carousel_nav_links">
																	<option <?php if ( $pixelwars_homepage_owl_carousel_nav_links == 'true' ) { echo 'selected="selected"'; } ?> value="true">Yes</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_nav_links == 'false' ) { echo 'selected="selected"'; } ?> value="false">No</option>
																</select>
																
																<h4>Nav Dots</h4>
																<?php
																	$pixelwars_homepage_owl_carousel_nav_dots = get_option( 'pixelwars_homepage_owl_carousel_nav_dots', 'false' );
																?>
																<select id="pixelwars_homepage_owl_carousel_nav_dots" name="pixelwars_homepage_owl_carousel_nav_dots">
																	<option <?php if ( $pixelwars_homepage_owl_carousel_nav_dots == 'true' ) { echo 'selected="selected"'; } ?> value="true">Yes</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_nav_dots == 'false' ) { echo 'selected="selected"'; } ?> value="false">No</option>
																</select>
																
																<h4>Autoplay</h4>
																<?php
																	$pixelwars_homepage_owl_carousel_autoplay = get_option( 'pixelwars_homepage_owl_carousel_autoplay', 'false' );
																?>
																<select id="pixelwars_homepage_owl_carousel_autoplay" name="pixelwars_homepage_owl_carousel_autoplay">
																	<option <?php if ( $pixelwars_homepage_owl_carousel_autoplay == 'true' ) { echo 'selected="selected"'; } ?> value="true">Yes</option>
																	<option <?php if ( $pixelwars_homepage_owl_carousel_autoplay == 'false' ) { echo 'selected="selected"'; } ?> value="false">No</option>
																</select>
																
																<h4>Slide Transiton Speed</h4>
																<?php
																	$pixelwars_homepage_owl_carousel_autoplay_speed = get_option( 'pixelwars_homepage_owl_carousel_autoplay_speed', '600' );
																?>
																<input type="number" min="100" max="1000" step="100" size="6" maxlength="6" id="pixelwars_homepage_owl_carousel_autoplay_speed" name="pixelwars_homepage_owl_carousel_autoplay_speed" value="<?php echo esc_attr( $pixelwars_homepage_owl_carousel_autoplay_speed ); ?>">
																<span style="font-size: 11px; color: #666;">Default: 600 milliseconds</span>
																
																<h4>Slide Interval Time</h4>
																<?php
																	$pixelwars_homepage_owl_carousel_autoplay_timeout = get_option( 'pixelwars_homepage_owl_carousel_autoplay_timeout', '2000' );
																?>
																<input type="number" min="500" max="10000" step="250" size="6" maxlength="6" id="pixelwars_homepage_owl_carousel_autoplay_timeout" name="pixelwars_homepage_owl_carousel_autoplay_timeout" value="<?php echo esc_attr( $pixelwars_homepage_owl_carousel_autoplay_timeout ); ?>">
																<span style="font-size: 11px; color: #666;">Default: 2000 milliseconds</span>
															</td>
															<td class="option-right">
																Slider properties.
															</td>
														</tr>
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								break;
								
								case 'portfolio' :
								
									if (isset($_GET['saved']) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<form class="ajax-form" method="post" action="<?php admin_url( 'themes.php?page=theme-options' ); ?>">
													<?php
														wp_nonce_field( 'settings-page' );
													?>
													
													
													<table>
														<tr>
															<td class="option-left">
																<h4>Layout</h4>
																
																<?php
																	$portfolio_layout = get_option( 'portfolio_layout', 'masonry' );
																?>
																
																<select id="portfolio_layout" name="portfolio_layout">
																	<option <?php if ( $portfolio_layout == 'masonry' ) { echo 'selected="selected"'; } ?>>masonry</option>
																	
																	<option <?php if ( $portfolio_layout == 'fitRows' ) { echo 'selected="selected"'; } ?>>fitRows</option>
																</select>
															</td>
															
															<td class="option-right">
																Portfolio page layout.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Item Width (px)</h4>
																
																<?php
																	$portfolio_columns = get_option( 'portfolio_columns', '360' );
																?>
																
																<input type="number" min="100" max="500" step="10" size="6" maxlength="6" id="portfolio_columns" name="portfolio_columns" value="<?php echo esc_attr( $portfolio_columns ); ?>">
																
																<span style="font-size: 11px; color: #666;">Default: 360 px</span>
															</td>
															
															<td class="option-right">
																Portfolio item width.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								
								break;
								
								
								case 'gallery' :
								
									if (isset($_GET['saved']) == 'true')
									{
										echo '<div class="alert-success" title="Click to close"><p><strong>Saved.</strong></p></div>';
									}
									
									
									?>
										<div class="postbox">
											<div class="inside">
												<form class="ajax-form" method="post" action="<?php admin_url( 'themes.php?page=theme-options' ); ?>">
													<?php
														wp_nonce_field( 'settings-page' );
													?>
													
													
													<table>
														<tr>
															<td class="option-left">
																<h4>Layout</h4>
																
																<?php
																	$gallery_layout = get_option( 'gallery_layout', 'masonry' );
																?>
																
																<select id="gallery_layout" name="gallery_layout">
																	<option>masonry</option>
																	
																	<option <?php if ( $gallery_layout == 'fitRows' ) { echo 'selected="selected"'; } ?>>fitRows</option>
																</select>
															</td>
															
															<td class="option-right">
																Gallery page layout.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Item Width (px)</h4>
																
																<?php
																	$gallery_columns = get_option( 'gallery_columns', '420' );
																?>
																
																<input type="number" min="100" max="500" step="10" id="gallery_columns" name="gallery_columns" value="<?php echo esc_attr( $gallery_columns ); ?>">
																
																<span style="font-size: 11px; color: #666;">Default: 420 px</span>
															</td>
															
															<td class="option-right">
																Gallery item width.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Save Changes">
																
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															
															<td class="option-right">
																
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								break;
								
								
								case 'sidebar' :
								
									if (isset($_GET['saved']) == 'true')
									{
										$no_sidebar_name = get_option( 'no_sidebar_name' );
										
										if ( $no_sidebar_name == "" )
										{
											echo '<div class="alert-success" title="Click to close"><p><strong>Enter a text for new sidebar name.</strong></p></div>';
										}
										else
										{
											echo '<div class="alert-success" title="Click to close"><p><strong>Created.</strong></p></div>';
										}
									}
									elseif (isset($_GET['deleted']) == 'true')
									{
										delete_option( 'sidebars_with_commas' );
										
										echo '<div class="alert-success" title="Click to close"><p><strong>Deleted.</strong></p></div>';
									}
									
									?>
										<div class="postbox">
											<div class="inside">
												<?php
													$wp_admin_url = admin_url( 'themes.php?page=theme-options&tab=sidebar' );
												?>
												
												<form method="post" action="<?php echo esc_url( $wp_admin_url ); ?>">
													<?php
														wp_nonce_field( "settings-page" );
													?>
													
													
													<table>
														<tr>
															<td class="option-left">
																<h4>New Sidebar</h4>
																
																<input type="text" id="new_sidebar_name" name="new_sidebar_name" required="required" style="width: 100%;" value="">
															</td>
															
															<td class="option-right">
																Enter a text for a new sidebar name.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<input type="submit" name="submit" class="button button-primary button-large" value="Create">
																
																<input type="hidden" name="settings-submit" value="Y">
															</td>
															
															<td class="option-right">
																Create new sidebar.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<h4>Sidebars</h4>
																
																<select id="sidebars" name="sidebars" style="width: 100%;" size="10" disabled="disabled">
																	<?php
																		$sidebars_with_commas = get_option( 'sidebars_with_commas' );
																		
																		if ( $sidebars_with_commas != "" )
																		{
																			$sidebars = preg_split("/[\s]*[,][\s]*/", $sidebars_with_commas);

																			foreach ( $sidebars as $sidebar_name )
																			{
																				echo '<option>' . $sidebar_name . '</option>';
																			}
																		}
																	?>
																</select>
															</td>
															
															<td class="option-right">
																New sidebar name must be different from created sidebar names.
															</td>
														</tr>
														
														
														<tr>
															<td class="option-left">
																<?php
																	$wp_admin_url = admin_url( 'themes.php?page=theme-options&tab=sidebar&deleted=true' );
																?>
																<a href="<?php echo esc_url( $wp_admin_url ); ?>" class="button button-primary button-large" style="margin-top: 20px;">Delete</a>
															</td>
															
															<td class="option-right">
																Remove.
															</td>
														</tr>
													</table>
												</form>
											</div>
										</div>
									<?php
								
								break;
							}
						}
					?>
				</div>
			</div>
		<?php
	}


/* ============================================================================================================================================ */


	function pixelwars__theme_save_settings()
	{
		global $pagenow;
		
		if ( $pagenow == 'themes.php' && $_GET['page'] == 'theme-options' )
		{
			if ( isset ( $_GET['tab'] ) )
			{
				$tab = $_GET['tab'];
			}
			else
			{
				$tab = 'general';
			}
			
			
			switch ( $tab )
			{
				case 'general' :
				
					update_option( 'logo_type', $_POST['logo_type'] );
					
					update_option( 'logo_image', $_POST['logo_image'] );
					
					update_option( 'select_text_logo', $_POST['select_text_logo'] );
					update_option( 'theme_site_title', $_POST['theme_site_title'] );
					
					update_option( 'select_tagline', $_POST['select_tagline'] );
					update_option( 'theme_tagline', $_POST['theme_tagline'] );
					
					update_option( 'logo_login', $_POST['logo_login'] );
					update_option( 'logo_login_hide', $_POST['logo_login_hide'] );
					
					update_option( 'favicon', $_POST['favicon'] );
					update_option( 'apple_touch_icon', $_POST['apple_touch_icon'] );
				
				break;
				
				
				case 'style' :
				
					update_option( 'char_set_latin', $_POST['char_set_latin'] );
					update_option( 'char_set_latin_ext', $_POST['char_set_latin_ext'] );
					update_option( 'char_set_cyrillic', $_POST['char_set_cyrillic'] );
					update_option( 'char_set_cyrillic_ext', $_POST['char_set_cyrillic_ext'] );
					update_option( 'char_set_greek', $_POST['char_set_greek'] );
					update_option( 'char_set_greek_ext', $_POST['char_set_greek_ext'] );
					update_option( 'char_set_vietnamese', $_POST['char_set_vietnamese'] );
					
					update_option( 'extra_font_styles', $_POST['extra_font_styles'] );
					update_option( 'fixed_header', $_POST['fixed_header'] );
					update_option( 'nav_menu_search', $_POST['nav_menu_search'] );
					update_option( 'mobile_zoom', $_POST['mobile_zoom'] );
					update_option( 'footer_widget_locations', $_POST['footer_widget_locations'] );
					update_option( 'page_comments', $_POST['page_comments'] );
				
				break;
				
				
				case 'blog' :
				
					update_option( 'blog_type', $_POST['blog_type'] );
					update_option( 'category_archive_type', $_POST['category_archive_type'] );
					update_option( 'tag_archive_type', $_POST['tag_archive_type'] );
					update_option( 'author_archive_type', $_POST['author_archive_type'] );
					update_option( 'date_archive_type', $_POST['date_archive_type'] );
					update_option( 'search_result_type', $_POST['search_result_type'] );
					
					update_option( 'blog_sidebar', $_POST['blog_sidebar'] );
					update_option( 'post_sidebar', $_POST['post_sidebar'] );
					
					update_option( 'theme_excerpt', $_POST['theme_excerpt'] );
					
					update_option( 'blog_masonry_layout', $_POST['blog_masonry_layout'] );
					update_option( 'blog_masonry_item_width', $_POST['blog_masonry_item_width'] );
					
					update_option( 'about_the_author_module', $_POST['about_the_author_module'] );
					update_option( 'pixelwars__related_posts', $_POST['pixelwars__related_posts'] );
					update_option( 'pagination', $_POST['pagination'] );
					update_option( 'editor_reading_time', $_POST['editor_reading_time'] );
				
				break;
				
				
				case 'main-slider' :
				
					update_option( 'main_slider', $_POST['main_slider'] );
					update_option( 'editor_main_slider_slides', $_POST['editor_main_slider_slides'] );
					update_option( 'editor_main_slider_latest_posts_count', $_POST['editor_main_slider_latest_posts_count'] );
					update_option( 'pixelwars_homepage_owl_carousel_items', $_POST['pixelwars_homepage_owl_carousel_items'] );
					update_option( 'pixelwars_homepage_owl_carousel_loop', $_POST['pixelwars_homepage_owl_carousel_loop'] );
					update_option( 'pixelwars_homepage_owl_carousel_center', $_POST['pixelwars_homepage_owl_carousel_center'] );
					update_option( 'pixelwars_homepage_owl_carousel_mouse_drag', $_POST['pixelwars_homepage_owl_carousel_mouse_drag'] );
					update_option( 'pixelwars_homepage_owl_carousel_nav_links', $_POST['pixelwars_homepage_owl_carousel_nav_links'] );
					update_option( 'pixelwars_homepage_owl_carousel_nav_dots', $_POST['pixelwars_homepage_owl_carousel_nav_dots'] );
					update_option( 'pixelwars_homepage_owl_carousel_autoplay', $_POST['pixelwars_homepage_owl_carousel_autoplay'] );
					update_option( 'pixelwars_homepage_owl_carousel_autoplay_speed', $_POST['pixelwars_homepage_owl_carousel_autoplay_speed'] );
					update_option( 'pixelwars_homepage_owl_carousel_autoplay_timeout', $_POST['pixelwars_homepage_owl_carousel_autoplay_timeout'] );
				
				break;
				
				
				case 'portfolio' :
				
					update_option( 'portfolio_columns', $_POST['portfolio_columns'] );
					update_option( 'portfolio_layout', $_POST['portfolio_layout'] );
				
				break;
				
				
				case 'gallery' :
				
					update_option( 'gallery_layout', $_POST['gallery_layout'] );
					update_option( 'gallery_columns', $_POST['gallery_columns'] );
				
				break;
				
				
				case 'sidebar' :
				
					update_option( 'no_sidebar_name', esc_attr( $_POST['new_sidebar_name'] ) );
					
					if ( esc_attr( $_POST['new_sidebar_name'] ) != "" )
					{
						$sidebars_with_commas = get_option( 'sidebars_with_commas', "" );
						
						if ( $sidebars_with_commas == "" )
						{
							update_option( 'sidebars_with_commas', esc_attr( $_POST['new_sidebar_name'] ) );
						}
						else
						{
							update_option( 'sidebars_with_commas', get_option( 'sidebars_with_commas' ) . ',' . esc_attr( $_POST['new_sidebar_name'] ) );
						}
					}
				
				break;
			}
		}
	}


/* ============================================================================================================================================ */


	function pixelwars__load_settings_page()
	{
		if ( isset( $_POST["settings-submit"] ) == 'Y' )
		{
			check_admin_referer( "settings-page" );
			
			pixelwars__theme_save_settings();
			
			$url_parameters = isset( $_GET['tab'] ) ? 'tab=' . $_GET['tab'] . '&saved=true' : 'saved=true';
			
			wp_redirect( admin_url( 'themes.php?page=theme-options&' . $url_parameters ) );
			
			exit;
		}
	}


/* ============================================================================================================================================ */


	function pixelwars__theme_menu()
	{
		$settings_page = add_theme_page('Theme Options',
										'Theme Options',
										'edit_theme_options',
										'theme-options',
										'pixelwars__theme_options_page' );
		
		add_action( "load-{$settings_page}", 'pixelwars__load_settings_page' );
	}
	
	add_action( 'admin_menu', 'pixelwars__theme_menu' );

?>