<?php
/**
 * Hero Content Options
 *
 * @package Corporacy
 */

/**
 * Add hero content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function corporacy_hero_content_options( $wp_customize ) {
	$wp_customize->add_section( 'corporacy_hero_content_options', array(
			'title' => esc_html__( 'Hero Content', 'corporacy' ),
			'panel' => 'corporacy_theme_options',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_hero_content_visibility',
			'default'           => 'disabled',
			'sanitize_callback' => 'corporacy_sanitize_select',
			'choices'           => corporacy_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'corporacy' ),
			'section'           => 'corporacy_hero_content_options',
			'type'              => 'select',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_hero_content_tagline',
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'corporacy_is_hero_content_active',
			'label'             => esc_html__( 'Section Tagline', 'corporacy' ),
			'section'           => 'corporacy_hero_content_options',
			'type'              => 'text',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_hero_content',
			'default'           => '0',
			'sanitize_callback' => 'corporacy_sanitize_post',
			'active_callback'   => 'corporacy_is_hero_content_active',
			'label'             => esc_html__( 'Page', 'corporacy' ),
			'section'           => 'corporacy_hero_content_options',
			'type'              => 'dropdown-pages',
		)
	);
}
add_action( 'customize_register', 'corporacy_hero_content_options' );

/** Active Callback Functions **/
if ( ! function_exists( 'corporacy_is_hero_content_active' ) ) :
	/**
	* Return true if hero content is active
	*
	* @since 1.0.0
	*/
	function corporacy_is_hero_content_active( $control ) {
		$enable = $control->manager->get_setting( 'corporacy_hero_content_visibility' )->value();

		return corporacy_check_section( $enable );
	}
endif;
