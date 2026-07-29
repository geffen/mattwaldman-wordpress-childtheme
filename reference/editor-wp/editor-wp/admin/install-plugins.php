<?php

	require_once get_template_directory() . '/admin/class-tgm-plugin-activation.php';
	
	function editor_plugins()
	{
		$config = array(
			'id'           => 'editor_tgmpa',
			'default_path' => "",
			'menu'         => 'editor-install-plugins',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'has_notices'  => true,
			'dismissable'  => true,
			'dismiss_msg'  => esc_html__('Install Plugins', 'editor'),
			'is_automatic' => true,
			'message'      => "",
			'strings'      => array('nag_type' => 'updated')
		);
		
		$plugins = array(
			array(
				'name'     => esc_html__('One Click Demo Import', 'editor'),
				'slug'     => 'one-click-demo-import',
				'required' => false
			),
			array(
				'name'     => esc_html__('Regenerate Thumbnails', 'editor'),
				'slug'     => 'regenerate-thumbnails',
				'required' => false
			),
			array(
				'name'     => esc_html__('Loco Translate', 'editor'),
				'slug'     => 'loco-translate',
				'required' => false
			),
			array(
				'name'     => esc_html__('Instagram Feed Gallery', 'editor'),
				'slug'     => 'insta-gallery',
				'required' => false
			),
			array(
				'name'     => esc_html__('Top 10 - Popular posts', 'editor'),
				'slug'     => 'top-10',
				'required' => false
			)
		);
		
		tgmpa($plugins, $config);
	}
	
	add_action('tgmpa_register', 'editor_plugins');

?>