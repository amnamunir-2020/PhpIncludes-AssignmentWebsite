<?php
/**
 * Featured Slider Options
 *
 * @package Corporacy
 */

/**
 * Add hero content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function corporacy_slider_options( $wp_customize ) {
	$wp_customize->add_section( 'corporacy_featured_slider', array(
			'panel' => 'corporacy_theme_options',
			'title' => esc_html__( 'Featured Slider', 'corporacy' ),
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_slider_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'corporacy_sanitize_select',
			'choices'           => corporacy_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'corporacy' ),
			'section'           => 'corporacy_featured_slider',
			'type'              => 'select',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_slider_number',
			'default'           => '4',
			'sanitize_callback' => 'corporacy_sanitize_number_range',

			'active_callback'   => 'corporacy_is_slider_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of Slides is changed (Max no of slides is 20)', 'corporacy' ),
			'input_attrs'       => array(
				'style' => 'width: 100px;',
				'min'   => 0,
				'max'   => 20,
				'step'  => 1,
			),
			'label'             => esc_html__( 'No of Slides', 'corporacy' ),
			'section'           => 'corporacy_featured_slider',
			'type'              => 'number',
		)
	);

	$slider_number = get_theme_mod( 'corporacy_slider_number', 4 );

	for ( $i = 1; $i <= $slider_number ; $i++ ) {
		// Page Sliders
		corporacy_register_option( $wp_customize, array(
				'name'              => 'corporacy_slider_page_' . $i,
				'sanitize_callback' => 'corporacy_sanitize_post',
				'active_callback'   => 'corporacy_is_slider_active',
				'label'             => esc_html__( 'Page', 'corporacy' ) . ' # ' . $i,
				'section'           => 'corporacy_featured_slider',
				'type'              => 'dropdown-pages',
			)
		);
	} // End for().
}
add_action( 'customize_register', 'corporacy_slider_options' );

/** Active Callback Functions */

if ( ! function_exists( 'corporacy_is_slider_active' ) ) :
	/**
	* Return true if slider is active
	*
	* @since 1.0
	*/
	function corporacy_is_slider_active( $control ) {
		$enable = $control->manager->get_setting( 'corporacy_slider_option' )->value();

		//return true only if previwed page on customizer matches the type option selected
		return corporacy_check_section( $enable );
	}
endif;
