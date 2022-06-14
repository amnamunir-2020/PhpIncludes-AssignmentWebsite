<?php
/**
 * The template used for displaying slider
 *
 * @package Corporacy
 */

$corporacy_enable_slider = get_theme_mod( 'corporacy_slider_option', 'disabled' );

if ( ! corporacy_check_section( $corporacy_enable_slider ) ) {
	return;
}
?>

<div id="feature-slider-section" class="feature-slider-section section text-align-left content-align-left page">
	<div class="wrapper section-content-wrapper feature-slider-wrapper">
		<div class="main-slider owl-carousel">
			<?php get_template_part( 'template-parts/slider/post-type-slider' ); ?>
		</div><!-- .main-slider -->
	</div><!-- .wrapper -->
</div><!-- #feature-slider -->

