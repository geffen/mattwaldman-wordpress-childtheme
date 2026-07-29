<?php
	$pagination = get_option('pagination', 'No');
	
	if ($pagination == 'Yes')
	{
		the_posts_pagination(array('screen_reader_text' => esc_html__('Posts navigation', 'editor'),
								   'prev_text'          => esc_html__('Prev', 'editor'),
								   'next_text'          => esc_html__('Next', 'editor'),
								   'end_size' 			=> 1,
								   'mid_size' 			=> 1,
								   'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__('Page', 'editor') . ' </span>'));
	}
	else
	{
		?>
			<nav class="navigation" role="navigation">
				<div class="nav-previous">
					<?php
						next_posts_link('<span class="meta-nav">&#8592;</span> ' . esc_html__('Older posts', 'editor'));
					?>
				</div>
				<div class="nav-next">
					<?php
						previous_posts_link(esc_html__('Newer posts', 'editor') . ' <span class="meta-nav">&#8594;</span>');
					?>
				</div>
			</nav> <!-- .navigation -->
		<?php
	}
?>