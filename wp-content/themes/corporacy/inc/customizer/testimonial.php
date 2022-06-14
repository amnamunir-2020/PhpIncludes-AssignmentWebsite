<?php
/**
 * Add Testimonial Settings in Customizer
 *
 * @package Corporacy
*/

/**
 * Add testimonial options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function corporacy_testimonial_options( $wp_customize ) {
	// Add note to Jetpack Testimonial Section
	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_jetpack_testimonial_cpt_note',
			'sanitize_callback' => 'sanitize_text_field',
			'custom_control'    => 'Corporacy_Note_Control',
			'label'             => sprintf( esc_html__( 'For Testimonial Options for Corporacy Theme, go %1$shere%2$s', 'corporacy' ),
				'<a href="javascript:wp.customize.section( \'corporacy_testimonials\' ).focus();">',
				 '</a>'
			),
		   'section'            => 'jetpack_testimonials',
			'type'              => 'description',
			'priority'          => 1,
		)
	);

	$wp_customize->add_section( 'corporacy_testimonials', array(
			'panel'    => 'corporacy_theme_options',
			'title'    => esc_html__( 'Testimonials', 'corporacy' ),
		)
	);

	$action = 'install-plugin';
    $slug   = 'essential-content-types';

    $install_url = wp_nonce_url(
        add_query_arg(
            array(
                'action' => $action,
                'plugin' => $slug
            ),
            admin_url( 'update.php' )
        ),
        $action . '_' . $slug
    );

    corporacy_register_option( $wp_customize, array(
            'name'              => 'corporacy_testimonial_jetpack_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Corporacy_Note_Control',
            'active_callback'   => 'corporacy_is_ect_testimonial_inactive',
            /* translators: 1: <a>/link tag start, 2: </a>/link tag close. */
            'label'             => sprintf( esc_html__( 'For Testimonial, install %1$sEssential Content Types%2$s Plugin with testimonial Type Enabled', 'corporacy' ),
                '<a target="_blank" href="' . esc_url( $install_url ) . '">',
                '</a>'

            ),
           'section'            => 'corporacy_testimonials',
            'type'              => 'description',
            'priority'          => 1,
        )
    );

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_testimonial_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'corporacy_sanitize_select',
			'active_callback'   => 'corporacy_is_ect_testimonial_active',
			'choices'           => corporacy_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'corporacy' ),
			'section'           => 'corporacy_testimonials',
			'type'              => 'select',
			'priority'          => 1,
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_testimonial_tagline',
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Section Tagline', 'corporacy' ),
			'active_callback'   => 'corporacy_is_testimonial_active',
			'section'           => 'corporacy_testimonials',
			'type'              => 'text',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_testimonial_title',
			'default'           => esc_html__( 'Testimonials', 'corporacy' ),
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Section Title', 'corporacy' ),
			'active_callback'   => 'corporacy_is_testimonial_active',
			'section'           => 'corporacy_testimonials',
			'type'              => 'text',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_testimonial_number',
			'default'           => '2',
			'sanitize_callback' => 'corporacy_sanitize_number_range',
			'active_callback'   => 'corporacy_is_testimonial_active',
			'label'             => esc_html__( 'Number of items', 'corporacy' ),
			'section'           => 'corporacy_testimonials',
			'type'              => 'number',
			'input_attrs'       => array(
				'style'             => 'width: 100px;',
				'min'               => 0,
			),
		)
	);

	$number = get_theme_mod( 'corporacy_testimonial_number', 2 );

	for ( $i = 1; $i <= $number ; $i++ ) {

		//for CPT
		corporacy_register_option( $wp_customize, array(
				'name'              => 'corporacy_testimonial_cpt_' . $i,
				'sanitize_callback' => 'corporacy_sanitize_post',
				'active_callback'   => 'corporacy_is_testimonial_active',
				'label'             => esc_html__( 'Testimonial', 'corporacy' ) . ' ' . $i ,
				'section'           => 'corporacy_testimonials',
				'type'              => 'select',
				'choices'           => corporacy_generate_post_array( 'jetpack-testimonial' ),
			)
		);
	} // End for().
}
add_action( 'customize_register', 'corporacy_testimonial_options' );

/**
 * Active Callback Functions
 */
if ( ! function_exists( 'corporacy_is_testimonial_active' ) ) :
	/**
	* Return true if testimonial is active
	*
	* @since 1.0
	*/
	function corporacy_is_testimonial_active( $control ) {
		$enable = $control->manager->get_setting( 'corporacy_testimonial_option' )->value();

		//return true only if previwed page on customizer matches the type of content option selected
		return ( corporacy_is_ect_testimonial_active( $control ) && corporacy_check_section( $enable ) );
	}
endif;

if ( ! function_exists( 'corporacy_is_ect_testimonial_inactive' ) ) :
    /**
    *
    * @since Corporacy 1.0
    */
    function corporacy_is_ect_testimonial_inactive( $control ) {
        return ! ( class_exists( 'Essential_Content_Jetpack_testimonial' ) || class_exists( 'Essential_Content_Pro_Jetpack_testimonial' ) );
    }
endif;

if ( ! function_exists( 'corporacy_is_ect_testimonial_active' ) ) :
    /**
    *
    * @since Corporacy 1.0
    */
    function corporacy_is_ect_testimonial_active( $control ) {
        return ( class_exists( 'Essential_Content_Jetpack_testimonial' ) || class_exists( 'Essential_Content_Pro_Jetpack_testimonial' ) );
    }
endif;
