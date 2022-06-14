<?php
/**
 * Add Portfolio Settings in Customizer
 *
 * @package Corporacy
 */

/**
 * Add portfolio options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function corporacy_portfolio_options( $wp_customize ) {
	// Add note to Jetpack Portfolio Section
	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_jetpack_portfolio_cpt_note',
			'sanitize_callback' => 'sanitize_text_field',
			'custom_control'    => 'Corporacy_Note_Control',
			'label'             => sprintf( esc_html__( 'For Portfolio Options for Corporacy Theme, go %1$shere%2$s', 'corporacy' ),
				 '<a href="javascript:wp.customize.section( \'corporacy_portfolio\' ).focus();">',
				 '</a>'
			),
			'section'           => 'jetpack_portfolio',
			'type'              => 'description',
			'priority'          => 1,
		)
	);

	$wp_customize->add_section( 'corporacy_portfolio', array(
			'panel'    => 'corporacy_theme_options',
			'title'    => esc_html__( 'Portfolio', 'corporacy' ),
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
            'name'              => 'corporacy_portfolio_jetpack_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Corporacy_Note_Control',
          	'active_callback'   => 'corporacy_is_ect_portfolio_inactive',
            /* translators: 1: <a>/link tag start, 2: </a>/link tag close. */
            'label'             => sprintf( esc_html__( 'For Portfolio, install %1$sEssential Content Types%2$s Plugin with Portfolio Type Enabled', 'corporacy' ),
                '<a target="_blank" href="' . esc_url( $install_url ) . '">',
                '</a>'

            ),
           'section'            => 'corporacy_portfolio',
            'type'              => 'description',
            'priority'          => 1,
        )
    );

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_portfolio_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'corporacy_sanitize_select',
			'active_callback'   => 'corporacy_is_ect_portfolio_active',
			'choices'           => corporacy_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'corporacy' ),
			'section'           => 'corporacy_portfolio',
			'type'              => 'select',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_portfolio_cpt_note',
			'sanitize_callback' => 'sanitize_text_field',
			'custom_control'    => 'Corporacy_Note_Control',
			'active_callback'   => 'corporacy_is_portfolio_active',
			'label'             => sprintf( esc_html__( 'For CPT heading and sub-heading, go %1$shere%2$s', 'corporacy' ),
				 '<a href="javascript:wp.customize.control( \'jetpack_portfolio_title\' ).focus();">',
				 '</a>'
			),
			'section'           => 'corporacy_portfolio',
			'type'              => 'description',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_portfolio_number',
			'default'           => 6,
			'sanitize_callback' => 'corporacy_sanitize_number_range',
			'active_callback'   => 'corporacy_is_portfolio_active',
			'label'             => esc_html__( 'Number of items to show', 'corporacy' ),
			'section'           => 'corporacy_portfolio',
			'type'              => 'number',
			'input_attrs'       => array(
				'style'             => 'width: 100px;',
				'min'               => 0,
			),
		)
	);

	$number = get_theme_mod( 'corporacy_portfolio_number', 6 );

	for ( $i = 1; $i <= $number ; $i++ ) {
		//for CPT
		corporacy_register_option( $wp_customize, array(
				'name'              => 'corporacy_portfolio_cpt_' . $i,
				'sanitize_callback' => 'corporacy_sanitize_post',
				'active_callback'   => 'corporacy_is_portfolio_active',
				'label'             => esc_html__( 'Portfolio', 'corporacy' ) . ' ' . $i ,
				'section'           => 'corporacy_portfolio',
				'type'              => 'select',
				'choices'           => corporacy_generate_post_array( 'jetpack-portfolio' ),
			)
		);


	} // End for().

}
add_action( 'customize_register', 'corporacy_portfolio_options' );

/**
 * Active Callback Functions
 */
if ( ! function_exists( 'corporacy_is_portfolio_active' ) ) :
	/**
	* Return true if portfolio is active
	*
	* @since 1.0
	*/
	function corporacy_is_portfolio_active( $control ) {
		$enable = $control->manager->get_setting( 'corporacy_portfolio_option' )->value();

		//return true only if previwed page on customizer matches the type of content option selected
		return ( corporacy_is_ect_portfolio_active( $control ) && corporacy_check_section( $enable ) );
	}
endif;


if ( ! function_exists( 'corporacy_is_ect_portfolio_inactive' ) ) :
    /**
    *
    * @since Corporacy 1.0
    */
    function corporacy_is_ect_portfolio_inactive( $control ) {
        return ! ( class_exists( 'Essential_Content_Jetpack_Portfolio' ) || class_exists( 'Essential_Content_Pro_Jetpack_Portfolio' ) );
    }
endif;

if ( ! function_exists( 'corporacy_is_ect_portfolio_active' ) ) :
    /**
    *
    * @since Corporacy 1.0
    */
    function corporacy_is_ect_portfolio_active( $control ) {
        return ( class_exists( 'Essential_Content_Jetpack_Portfolio' ) || class_exists( 'Essential_Content_Pro_Jetpack_Portfolio' ) );
    }
endif;
