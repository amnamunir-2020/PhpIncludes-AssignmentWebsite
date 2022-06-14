<?php
/**
 * The template for displaying testimonial items
 *
 * @package Corporacy
 */

$corporacy_enable = get_theme_mod( 'corporacy_testimonial_option', 'disabled' );

if ( ! corporacy_check_section( $corporacy_enable ) ) {
	// Bail if featured content is disabled
	return;
}


$corporacy_title   = get_theme_mod( 'corporacy_testimonial_title', esc_html__( 'Testimonials', 'corporacy' ) );
$corporacy_tagline = get_theme_mod( 'corporacy_testimonial_tagline' );

$corporacy_classes[] = 'section testimonial-content-section';

if ( ! $corporacy_title && ! $corporacy_tagline ) {
	$corporacy_classes[] = 'no-section-heading';
}

$corporacy_classes[] = 'style-one';
?>

<div id="testimonial-content-section" class="<?php echo esc_attr( implode( ' ', $corporacy_classes ) ); ?>">
	<div class="wrapper">

	<?php corporacy_section_heading( $corporacy_tagline, $corporacy_title ); ?>

	<?php get_template_part( 'template-parts/testimonial/post-types-testimonial' ); ?>
	</div><!-- .wrapper -->
</div><!-- .testimonial-content-section -->
