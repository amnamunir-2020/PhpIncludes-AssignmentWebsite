<?php
/**
 * The template used for displaying hero content
 *
 * @package Corporacy
 */

$corporacy_enable_section = get_theme_mod( 'corporacy_hero_content_visibility', 'disabled' );

if ( ! corporacy_check_section( $corporacy_enable_section ) ) {
	// Bail if hero content is not enabled
	return;
}

get_template_part( 'template-parts/hero-content/post-type-hero' );
