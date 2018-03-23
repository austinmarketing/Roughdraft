<?php

/**
 * Registers options with the default WP Theme Customizer
 * Use this file to customise options, remove or add controls.
 * $wp_customize calls go in this document.
 */
function tcx_register_theme_customizer( $wp_customize ) {

  // Uncomment the below lines to remove the default customize sections 

 // $wp_customize->remove_section('title_tagline');
 // $wp_customize->remove_section('colors');
 // $wp_customize->remove_section('static_front_page');
 // $wp_customize->remove_section('nav');
 
 // Remove dafulat backgroup image upload option section
 $wp_customize->remove_section('background_image');

 // Uncomment the below lines to remove the default controls
 // $wp_customize->remove_control('blogdescription');
  
 // Uncomment the following to change the default section titles
 // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
 // $wp_customize->get_section('background_image')->title = __( 'Images' );

	$wp_customize->add_setting(
		'tcx_link_color',
		array(
			'default'     => '#000000',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'   => 'postMessage'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'link_color',
			array(
			    'label'      => 'Link Color',
			    'section'    => 'colors',
			    'settings'   => 'tcx_link_color'
			)
		)
	);

	/*-----------------------------------------------------------*
	 * Defining our own 'Display Options' section
	 *-----------------------------------------------------------*/

	$wp_customize->add_section(
		'tcx_display_options',
		array(
			'title'     => 'Display Options',
			'priority'  => 200
		)
	);

	/* Display Header */
	$wp_customize->add_setting(
		'tcx_toggle_comments',
		array(
			'default'    =>  'true',
			'sanitize_callback' => 'sanitize_option',
			'transport'  =>  'postMessage'
		)
	);

	$wp_customize->add_control(
		'tcx_toggle_comments',
		array(
			'section'   => 'tcx_display_options',
			'label'     => 'Disable Comments',
			'type'      => 'checkbox'
		)
	);

	/* Display Telephone Number */
	// Does not show if empty
	// Use this in the template to output this setting: echo get_theme_mod( 'tcx_telephone_number_text' );
	$wp_customize->add_setting(
		'tcx_telephone_number_text',
		array(
			'default'            => '',
			'sanitize_callback'  => 'tcx_sanitize_tel',
			'transport'          => 'postMessage'
		)
	);

	$wp_customize->add_control(
		'tcx_telephone_number_text',
		array(
			'section'  => 'tcx_display_options',
			'label'    => 'Telephone Number',
			'type'     => 'text'
		)
	);

	/*-----------------------------------------------------------*
	 * Defining our own 'Advanced Options' section
	 *-----------------------------------------------------------*/

	$wp_customize->add_section(
		'tcx_advanced_options',
		array(
			'title'     => 'Advanced Options',
			'priority'  => 201
		)
	);

	/* Background Image */
	$wp_customize->add_setting(
		'tcx_background_image',
		array(
		    'default'      => '',
		    'sanitize_callback' => 'esc_url_raw',
		    'transport'    => 'postMessage'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'tcx_background_image',
			array(
			    'label'    => 'Background Image',
			    'settings' => 'tcx_background_image',
			    'section'  => 'tcx_advanced_options'
			)
		)
	);

} // end tcx_register_theme_customizer
add_action( 'customize_register', 'tcx_register_theme_customizer' );

/**
 * Sanitizes the incoming input and returns it prior to serialization.
 *
 * @param      string    $input    The string to sanitize
 * @return     string              The sanitized string
 */
function tcx_sanitize_tel( $input ) {
	return strip_tags( stripslashes( $input ) );
} // end tcx_sanitize_tel

/**
 * Writes styles out the `<head>` element of the page based on the configuration options
 * saved in the Theme Customizer.
 */
function tcx_customizer_css() {
?>
	 <style type="text/css">
	     a { color: <?php echo get_theme_mod( 'tcx_link_color' ); ?>; }
	 </style>
<?php

		if( false === get_theme_mod( 'tcx_toggle_comments' ) ) {
			// Run code to deactivate comments here
		} // end if
		
} // end tcx_customizer_css
add_action( 'wp_head', 'tcx_customizer_css' );

/**
 * Registers the Theme Customizer Preview with WordPress.
 *
 */
function tcx_customizer_live_preview() {

	wp_enqueue_script(
		'tcx-theme-customizer',
		get_template_directory_uri() . '/js/theme-customizer.js',
		array( 'jquery', 'customize-preview' ),
		'1.0.0',
		true
	);

} // end tcx_customizer_live_preview
add_action( 'customize_preview_init', 'tcx_customizer_live_preview' );