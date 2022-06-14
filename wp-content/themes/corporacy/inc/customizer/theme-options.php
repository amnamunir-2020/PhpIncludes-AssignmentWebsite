<?php
/**
 * Theme Options
 *
 * @package Corporacy
 */

/**
 * Add theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function corporacy_theme_options( $wp_customize ) {
	$wp_customize->add_panel( 'corporacy_theme_options', array(
		'title'    => esc_html__( 'Theme Options', 'corporacy' ),
		'priority' => 130,
	) );

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_latest_posts_title',
			'default'           => esc_html__( 'News', 'corporacy' ),
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Latest Posts Title', 'corporacy' ),
			'section'           => 'corporacy_theme_options',
		)
	);

	// Layout Options
	$wp_customize->add_section( 'corporacy_layout_options', array(
		'title' => esc_html__( 'Layout Options', 'corporacy' ),
		'panel' => 'corporacy_theme_options',
		)
	);

	/* Default Layout */
	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_default_layout',
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'corporacy_sanitize_select',
			'label'             => esc_html__( 'Default Layout', 'corporacy' ),
			'section'           => 'corporacy_layout_options',
			'type'              => 'select',
			'choices'           => array(
				'right-sidebar' => esc_html__( 'Right Sidebar ( Content, Primary Sidebar )', 'corporacy' ),
				'no-sidebar'    => esc_html__( 'No Sidebar', 'corporacy' ),
			),
		)
	);

	/* Homepage/Archive Layout */
	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_homepage_archive_layout',
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'corporacy_sanitize_select',
			'label'             => esc_html__( 'Homepage/Archive Layout', 'corporacy' ),
			'section'           => 'corporacy_layout_options',
			'type'              => 'select',
			'choices'           => array(
				'right-sidebar' => esc_html__( 'Right Sidebar ( Content, Primary Sidebar )', 'corporacy' ),
				'no-sidebar'    => esc_html__( 'No Sidebar', 'corporacy' ),
			),
		)
	);

	// Excerpt Options.
	$wp_customize->add_section( 'corporacy_excerpt_options', array(
		'panel'     => 'corporacy_theme_options',
		'title'     => esc_html__( 'Excerpt Options', 'corporacy' ),
	) );

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_excerpt_length',
			'default'           => '20',
			'sanitize_callback' => 'absint',
			'input_attrs' => array(
				'min'   => 10,
				'max'   => 200,
				'step'  => 5,
				'style' => 'width: 60px;',
			),
			'label'    => esc_html__( 'Excerpt Length (words)', 'corporacy' ),
			'section'  => 'corporacy_excerpt_options',
			'type'     => 'number',
		)
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_excerpt_more_text',
			'default'           => esc_html__( 'Continue reading', 'corporacy' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Read More Text', 'corporacy' ),
			'section'           => 'corporacy_excerpt_options',
			'type'              => 'text',
		)
	);

	// Excerpt Options.
	$wp_customize->add_section( 'corporacy_search_options', array(
		'panel'     => 'corporacy_theme_options',
		'title'     => esc_html__( 'Search Options', 'corporacy' ),
	) );

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_search_text',
			'default'           => esc_html__( 'Search', 'corporacy' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Search Text', 'corporacy' ),
			'section'           => 'corporacy_search_options',
			'type'              => 'text',
		)
	);

	// Homepage / Frontpage Options.
	$wp_customize->add_section( 'corporacy_homepage_options', array(
		'description' => esc_html__( 'Only posts that belong to the categories selected here will be displayed on the front page', 'corporacy' ),
		'panel'       => 'corporacy_theme_options',
		'title'       => esc_html__( 'Homepage / Frontpage Options', 'corporacy' ),
	) );

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_front_page_category',
			'sanitize_callback' => 'corporacy_sanitize_category_list',
			'custom_control'    => 'Corporacy_Multi_Cat',
			'label'             => esc_html__( 'Categories', 'corporacy' ),
			'section'           => 'corporacy_homepage_options',
			'type'              => 'dropdown-categories',
		)
	);

	//Menu Options
	$wp_customize->add_section( 'corporacy_menu_options', array(
		'description' => esc_html__( 'Extra Menu Options specific to this theme', 'corporacy' ),
		'title'       => esc_html__( 'Menu Options', 'corporacy' ),
		'panel'       => 'corporacy_theme_options',
	) );

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_secondary_button_label',
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Secondary Button Label', 'corporacy' ),
			'section'           => 'corporacy_menu_options',
			'type'              => 'text',
		)
	);

	corporacy_register_option( $wp_customize, array(
	        'name'              => 'corporacy_secondary_button_link',
	        'sanitize_callback' => 'esc_url_raw',
	        'label'             => esc_html__( 'Secondary Button Link', 'corporacy' ),
	        'section'           => 'corporacy_menu_options',
	    )
	);

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_secondary_button_target',
			'default'			=> 1,
			'sanitize_callback' => 'corporacy_sanitize_checkbox',
			'label'             => esc_html__( 'Open in New Tab', 'corporacy' ),
			'section'           => 'corporacy_menu_options',
			'custom_control'    => 'Corporacy_Toggle_Control',
		)
	);
	//Menu Options End

	// Pagination Options.
	$pagination_type = get_theme_mod( 'corporacy_pagination_type', 'default' );

	$nav_desc = '';

	/**
	* Check if navigation type is Jetpack Infinite Scroll and if it is enabled
	*/
	$nav_desc = sprintf(
		wp_kses(
			__( 'For infinite scrolling, use %1$sCatch Infinite Scroll Plugin%2$s with Infinite Scroll module Enabled.', 'corporacy' ),
			array(
				'a' => array(
					'href' => array(),
					'target' => array(),
				),
				'br'=> array()
			)
		),
		'<a target="_blank" href="https://wordpress.org/plugins/catch-infinite-scroll/">',
		'</a>'
	);

	$wp_customize->add_section( 'corporacy_pagination_options', array(
		'description'     => $nav_desc,
		'panel'           => 'corporacy_theme_options',
		'title'           => esc_html__( 'Pagination Options', 'corporacy' ),
		'active_callback' => 'corporacy_scroll_plugins_inactive'
	) );

	corporacy_register_option( $wp_customize, array(
			'name'              => 'corporacy_pagination_type',
			'default'           => 'default',
			'sanitize_callback' => 'corporacy_sanitize_select',
			'choices'           => corporacy_get_pagination_types(),
			'label'             => esc_html__( 'Pagination type', 'corporacy' ),
			'section'           => 'corporacy_pagination_options',
			'type'              => 'select',
		)
	);

	/* Scrollup Options */
	$wp_customize->add_section( 'corporacy_scrollup', array(
		'panel'    => 'corporacy_theme_options',
		'title'    => esc_html__( 'Scrollup Options', 'corporacy' ),
	) );

	$action = 'install-plugin';
	$slug   = 'to-top';

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

	// Add note to Scroll up Section
    corporacy_register_option( $wp_customize, array(
            'name'              => 'corporacy_to_top_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Corporacy_Note_Control',
            'active_callback'   => 'corporacy_is_to_top_inactive',
            /* translators: 1: <a>/link tag start, 2: </a>/link tag close. */
            'label'             => sprintf( esc_html__( 'For Scroll Up, install %1$sTo Top%2$s Plugin', 'corporacy' ),
                '<a target="_blank" href="' . esc_url( $install_url ) . '">',
                '</a>'

            ),
           'section'            => 'corporacy_scrollup',
            'type'              => 'description',
            'priority'          => 1,
        )
	);
}
add_action( 'customize_register', 'corporacy_theme_options' );

/** Active Callback Functions */

if ( ! function_exists( 'corporacy_scroll_plugins_inactive' ) ) :
	/**
	* Return true if infinite scroll functionality exists
	*
	* @since 1.0
	*/
	function corporacy_scroll_plugins_inactive( $control ) {
		if ( ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) || class_exists( 'Catch_Infinite_Scroll' ) ) {
			// Support infinite scroll plugins.
			return false;
		}

		return true;
	}
endif;

if ( ! function_exists( 'corporacy_is_static_page_enabled' ) ) :
	/**
	* Return true if A Static Page is enabled
	*
	* @since 1.0.0
	*/
	function corporacy_is_static_page_enabled( $control ) {
		$enable = $control->manager->get_setting( 'show_on_front' )->value();
		if ( 'page' === $enable ) {
			return true;
		}
		return false;
	}
endif;

if ( ! function_exists( 'corporacy_is_to_top_inactive' ) ) :
    /**
    * Return true if featured_content is active
    *
    * @since Simclick 0.1
    */
    function corporacy_is_to_top_inactive( $control ) {
        return ! ( class_exists( 'To_Top' ) );
    }
endif;
