<?php
/**
 * Theme Customizer
 *
 * @package Corporacy
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function corporacy_customize_register( $wp_customize ) {
	$wp_customize->add_setting(
		'corporacy_absolute_header_custom_logo',
		array(
			'theme_supports'    => array( 'custom-logo' ),
			'sanitize_callback' => 'absint',
		)
	);

	$custom_logo_args = get_theme_support( 'custom-logo' );
	$wp_customize->add_control(
		new WP_Customize_Cropped_Image_Control(
			$wp_customize,
			'corporacy_absolute_header_custom_logo',
			array(
				'label'         => esc_html__( 'Absolute Header Logo', 'corporacy' ),
				'section'       => 'title_tagline',
				'priority'      => 8.5,
				
				'height'        => isset( $custom_logo_args[0]['height'] ) ? $custom_logo_args[0]['height'] : null,
				'width'         => isset( $custom_logo_args[0]['width'] ) ? $custom_logo_args[0]['width'] : null,
				'flex_height'   => isset( $custom_logo_args[0]['flex-height'] ) ? $custom_logo_args[0]['flex-height'] : null,
				'flex_width'    => isset( $custom_logo_args[0]['flex-width'] ) ? $custom_logo_args[0]['flex-width'] : null,
				'button_labels' => array(
					'select'       => esc_html__( 'Select logo', 'corporacy' ),
					'change'       => esc_html__( 'Change logo', 'corporacy' ),
					'remove'       => esc_html__( 'Remove', 'corporacy' ),
					'default'      => esc_html__( 'Default', 'corporacy' ),
					'placeholder'  => esc_html__( 'No logo selected', 'corporacy' ),
					'frame_title'  => esc_html__( 'Select logo', 'corporacy' ),
					'frame_button' => esc_html__( 'Choose logo', 'corporacy' ),
				),
			)
		)
	);

	$wp_customize->get_setting( 'blogname' )->transport              = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport       = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'header_video' )->transport          = 'refresh';
	$wp_customize->get_setting( 'external_header_video' )->transport = 'refresh';
	$wp_customize->get_setting( 'header_image' )->transport 		 = 'refresh';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'            => '.site-title a',
			'container_inclusive' => false,
			'render_callback'     => 'corporacy_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'            => '.site-description',
			'container_inclusive' => false,
			'render_callback'     => 'corporacy_customize_partial_blogdescription',
		) );
	}

	// Important Links.
	$wp_customize->add_section( 'corporacy_important_links', array(
		'priority' => 999,
		'title'    => esc_html__( 'Important Links', 'corporacy' ),
	) );

	// Has dummy Sanitizaition function as it contains no value to be sanitized.
	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_important_links',
			'sanitize_callback' => 'sanitize_text_field',
			'custom_control'    => 'Corporacy_Important_Links_Control',
			'label'             => esc_html__( 'Important Links', 'corporacy' ),
			'section'           => 'corporacy_important_links',
			'type'              => 'corporacy_important_links',
		)
	);
	// Important Links End.
}
add_action( 'customize_register', 'corporacy_customize_register', 11 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function corporacy_customize_preview_js() {
	$min  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$path = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? 'assets/js/source/' : 'assets/js/';

	wp_enqueue_script( 'corporacy-customize-preview', get_theme_file_uri( $path . 'customize-preview' . $min . '.js' ), array( 'customize-preview' ), '20180103', true );
}
add_action( 'customize_preview_init', 'corporacy_customize_preview_js' );

/**
 * Binds the JS listener to make Customizer color_scheme control.
 *
 * @since 1.0.0
 */
function corporacy_customize_control_js() {
	wp_enqueue_style( 'corporacy-custom-controls-css', trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/css/customizer.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'corporacy_customize_control_js' );

/**
 * Include Custom Controls
 */
require get_parent_theme_file_path( 'inc/customizer/custom-controls.php' );

/**
 * Include Header Media Options
 */
require get_parent_theme_file_path( 'inc/customizer/header-media.php' );

/**
 * Include Theme Options
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options.php' );

/**
 * Include Hero Content
 */
require get_parent_theme_file_path( 'inc/customizer/hero-content.php' );

/**
 * Include Featured Slider
 */
require get_parent_theme_file_path( 'inc/customizer/featured-slider.php' );

/**
 * Include Featured Content
 */
require get_parent_theme_file_path( 'inc/customizer/featured-content.php' );

/**
 * Include Testimonial
 */
require get_parent_theme_file_path( 'inc/customizer/testimonial.php' );

/**
 * Include Portfolio
 */
require get_parent_theme_file_path( 'inc/customizer/portfolio.php' );

/**
 * Include Customizer Helper Functions
 */
require get_parent_theme_file_path( 'inc/customizer/helpers.php' );

/**
 * Include Sanitization functions
 */
require get_parent_theme_file_path( 'inc/customizer/sanitize-functions.php' );

/**
 * Include Service
 */
require get_parent_theme_file_path( 'inc/customizer/service.php' );

/**
 * Include Reset Button
 */
require get_parent_theme_file_path( 'inc/customizer/reset.php' );

/**
 * Upgrade to Pro Button
 */
require get_parent_theme_file_path( 'inc/customizer/upgrade-button/class-customize.php' );
