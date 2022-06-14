<?php
/**
 * Header Media Options
 *
 * @package Corporacy
 */

/**
 * Add Header Media options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function corporacy_header_media_options( $wp_customize ) {
	$wp_customize->get_section( 'header_image' )->description = esc_html__( 'If you add video, it will only show up on Homepage/FrontPage. Other Pages will use Header/Post/Page Image depending on your selection of option. Header Image will be used as a fallback while the video loads ', 'corporacy' );

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_header_media_option',
			'default'           => 'entire-site',
			'sanitize_callback' => 'corporacy_sanitize_select',
			'choices'           => array(
				'homepage'    => esc_html__( 'Homepage / Frontpage', 'corporacy' ),
				'entire-site' => esc_html__( 'Entire Site', 'corporacy' ),
				'disable'     => esc_html__( 'Disabled', 'corporacy' ),
			),
			'label'             => esc_html__( 'Enable on', 'corporacy' ),
			'section'           => 'header_image',
			'type'              => 'select',
			'priority'          => 1,
		)
	);
	
	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_header_media_image_position_desktop',
			'default'           => 'center center',
			'sanitize_callback' => 'corporacy_sanitize_select',
			'label'             => esc_html__( 'Image Position (Desktop View)', 'corporacy' ),
			'section'           => 'header_image',
			'type'              => 'select',
			'choices'           => array(
				'left top'      => esc_html__( 'Left Top', 'corporacy' ),
				'left center'   => esc_html__( 'Left Center', 'corporacy' ),
				'left bottom'   => esc_html__( 'Left Bottom', 'corporacy' ),
				'right top'     => esc_html__( 'Right Top', 'corporacy' ),
				'right center'  => esc_html__( 'Right Center', 'corporacy' ),
				'right bottom'  => esc_html__( 'Right Bottom', 'corporacy' ),
				'center top'    => esc_html__( 'Center Top', 'corporacy' ),
				'center center' => esc_html__( 'Center Center', 'corporacy' ),
				'center bottom' => esc_html__( 'Center Bottom', 'corporacy' ),
			),
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_header_media_image_position_mobile',
			'default'           => 'center center',
			'sanitize_callback' => 'corporacy_sanitize_select',
			'label'             => esc_html__( 'Image Position (Mobile View)', 'corporacy' ),
			'section'           => 'header_image',
			'type'              => 'select',
			'choices'           => array(
				'left top'      => esc_html__( 'Left Top', 'corporacy' ),
				'left center'   => esc_html__( 'Left Center', 'corporacy' ),
				'left bottom'   => esc_html__( 'Left Bottom', 'corporacy' ),
				'right top'     => esc_html__( 'Right Top', 'corporacy' ),
				'right center'  => esc_html__( 'Right Center', 'corporacy' ),
				'right bottom'  => esc_html__( 'Right Bottom', 'corporacy' ),
				'center top'    => esc_html__( 'Center Top', 'corporacy' ),
				'center center' => esc_html__( 'Center Center', 'corporacy' ),
				'center bottom' => esc_html__( 'Center Bottom', 'corporacy' ),
			),
		)
	);

	/*Overlay Option for Header Media*/
	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_header_media_image_opacity',
			'default'           => '0',
			'sanitize_callback' => 'corporacy_sanitize_number_range',
			'label'             => esc_html__( 'Header Media Overlay', 'corporacy' ),
			'section'           => 'header_image',
			'type'              => 'number',
			'input_attrs'       => array(
				'style' => 'width: 60px;',
				'min'   => 0,
				'max'   => 100,
			),
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_header_media_before_subtitle',
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Tagline', 'corporacy' ),
			'section'           => 'header_image',
			'type'              => 'text',
		)
	);

    corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_header_media_title',
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Header Media Title', 'corporacy' ),
			'section'           => 'header_image',
			'type'              => 'text',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_header_media_text',
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Site Header Text', 'corporacy' ),
			'section'           => 'header_image',
			'type'              => 'textarea',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_header_media_url',
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
			'label'             => esc_html__( 'Header Media Url', 'corporacy' ),
			'section'           => 'header_image',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_header_media_url_text',
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Header Media Url Text', 'corporacy' ),
			'section'           => 'header_image',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_header_url_target',
			'sanitize_callback' => 'corporacy_sanitize_checkbox',
			'label'             => esc_html__( 'Open Link in New Window/Tab', 'corporacy' ),
			'section'           => 'header_image',
			'custom_control'    => 'Corporacy_Toggle_Control',
		)
	);
}
add_action( 'customize_register', 'corporacy_header_media_options' );
