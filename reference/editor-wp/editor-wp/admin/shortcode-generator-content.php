<form class="shortcodes-wrap">
	<br>
	<label for="shortcodes_list">Shortcodes:</label>
	<br>
	
	
	<select id="shortcodes_list" name="shortcodes_list" class="widefat shortcodes-list" style="width: 50%;">
		<option></option>
		
		
		<option value="[row]column shortcode here.[/row]">row</option>
		
		<option value="[column width=&quot;&quot;]Content here.[/column]">column</option>
		
		
		<option value="[section_title align=&quot;center&quot; text=&quot;&quot;]">section_title</option>
		
		
		<option value="[button text=&quot;&quot; url=&quot;&quot;]">button</option>
		
		<option value="[launch_button]button shortcode here.[/launch_button]">launch_button</option>
		
		
		<option value="[call_to_action title=&quot;&quot; text=&quot;&quot;]button shortcode here.[/call_to_action]">call_to_action</option>
		
		<option value="[project_action]button shortcode here.[/project_action]">project_action</option>
		
		
		<option value="[social_icon_wrap]social_icon shortcode here.[/social_icon_wrap]">social_icon_wrap</option>
		
		<option value="[social_icon type=&quot;&quot; url=&quot;&quot;]">social_icon</option>
		
		
		<option value="[intro]Text here.[/intro]">intro</option>
		
		<option value="[rotate_words titles=&quot;&quot;]">rotate_words</option>
		
		
		<option value="[tagline]Text here.[/tagline]">tagline</option>
		
		<option value="[drop_cap]Text here.[/drop_cap]">drop_cap</option>
		
		<option value="[quote align=&quot;&quot; name=&quot;&quot;]Text here.[/quote]">quote</option>
		
		<option value="[alert type=&quot;&quot;]Text here.[/alert]">alert</option>
		
		<option value="[contact_form to=&quot;&quot; subject=&quot;&quot;]">contact_form</option>
		
		<option value="[latest_from_the_blog items=&quot;10&quot;]">latest_from_the_blog</option>
		
		
		<option value="[slider items=&quot;1&quot; loop=&quot;true&quot; center=&quot;false&quot; mouse_drag=&quot;true&quot; nav=&quot;true&quot; dots=&quot;true&quot; autoplay=&quot;false&quot; speed=&quot;600&quot; timeout=&quot;2000&quot;]slide shortcode here.[/slider]">slider</option>
		
		<option value="[slide title=&quot;&quot; image=&quot;&quot;]">slide</option>
		
		
		<option value="[tab_wrap titles=&quot;&quot; active=&quot;&quot;]tab shortcode here.[/tab_wrap]">tab_wrap</option>
		
		<option value="[tab]Text here.[/tab]">tab</option>
		
		
		<option value="[accordion_wrap]accordion shortcode here.[/accordion_wrap]">accordion_wrap</option>
		
		<option value="[accordion title=&quot;&quot;]Text here.[/accordion]">accordion</option>
		
		
		<option value="[toggle_wrap]toggle shortcode here.[/toggle_wrap]">toggle_wrap</option>
		
		<option value="[toggle title=&quot;&quot;]Text here.[/toggle]">toggle</option>
		
		
		<option value="[service icon=&quot;&quot; title=&quot;&quot; text=&quot;&quot;]">service</option>
		
		<option value="[fun_fact icon=&quot;&quot; text=&quot;&quot;]">fun_fact</option>
		
		
		<option value="[skill_wrap]skill shortcode here.[/skill_wrap]">skill_wrap</option>
		
		<option value="[skill title=&quot;&quot; percent=&quot;&quot;]">skill</option>
		
		
		<option value="[testimonial_wrap]testimonial shortcode here.[/testimonial_wrap]">testimonial_wrap</option>
		
		<option value="[testimonial image=&quot;&quot; title=&quot;&quot; sub_title=&quot;&quot;]Text here.[/testimonial]">testimonial</option>
		
		
		<option value="[tag_wrap]tag shortcode here.[/tag_wrap]">tag_wrap</option>
		
		<option value="[tag text=&quot;&quot;]">tag</option>
		
		
		<option value="[timeline]event shortcode here.[/timeline]">timeline</option>
		
		<option value="[event_group_title icon=&quot;&quot; text=&quot;&quot;]">event_group_title</option>
		
		<option value="[event date=&quot;&quot; title=&quot;&quot; sub_title=&quot;&quot;]Text here.[/event]">event</option>
	</select>
	
	
	<br>
	<br>
	
	<button type="button" class="button button-primary button-large button-insert-shortcode">Insert Shortcode</button>
</form>


<script>
	jQuery(document).ready(function($)
	{
		var selected_shortcode = "";
		
		
		$( '.shortcodes-list' ).change( function()
		{
			selected_shortcode = $( '.shortcodes-list' ).val();
		});
		
		
		$( '.button-insert-shortcode' ).click( function()
		{
			// add shortcode to content editor
			if ( window.tinyMCE )
			{
				var tmce_ver = window.tinyMCE.majorVersion;
				
				if ( tmce_ver < "4" )
				{
					window.tinyMCE.execInstanceCommand( 'content', 'mceInsertContent', false, selected_shortcode );
				}
				else
				{
					parent.tinyMCE.execCommand( 'mceInsertContent', false, selected_shortcode );
				}
				
				
				tb_remove();
			}
			// end add shortcode to content editor
		});
	});
</script>