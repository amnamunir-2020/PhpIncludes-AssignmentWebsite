<?php
/**
 * The template for displaying portfolio items
 *
 * @package Corporacy
 */
$corporacy_number = get_theme_mod( 'corporacy_portfolio_number', 6 );

if ( ! $corporacy_number ) {
	// If number is 0, then this section is disabled
	return;
}

$corporacy_args = array(
	'ignore_sticky_posts' => 1 // ignore sticky posts
);

$corporacy_post_list  = array();// list of valid post/page ids

$corporacy_no_of_post = 0; // for number of posts

$corporacy_args['post_type'] = 'jetpack-portfolio';

for ( $corporacy_i = 1; $corporacy_i <= $corporacy_number; $corporacy_i++ ) {
	$corporacy_post_id = '';

	$corporacy_post_id =  get_theme_mod( 'corporacy_portfolio_cpt_' . $corporacy_i );

	if ( $corporacy_post_id && '' !== $corporacy_post_id ) {
		// Polylang Support.
		if ( class_exists( 'Polylang' ) ) {
			$corporacy_post_id = pll_get_post( $corporacy_post_id, pll_current_language() );
		}

		$corporacy_post_list = array_merge( $corporacy_post_list, array( $corporacy_post_id ) );

		$corporacy_no_of_post++;
	}
}

$corporacy_args['post__in'] = $corporacy_post_list;
$corporacy_args['orderby'] ='post__in';

if ( 0 === $corporacy_no_of_post ) {
	return;
}

$corporacy_args['posts_per_page'] = $corporacy_no_of_post;
$corporacy_loop     = new WP_Query( $corporacy_args );

$corporacy_slider_select = get_theme_mod( 'corporacy_portfolio_slider', 1 );

if ( $corporacy_loop -> have_posts() ) :
	while ( $corporacy_loop -> have_posts() ) :
		$corporacy_loop -> the_post();

		get_template_part( 'template-parts/portfolio/content', 'portfolio' );

	endwhile;
	wp_reset_postdata();
endif;
