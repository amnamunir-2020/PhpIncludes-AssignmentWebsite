<?php
/**
 * The template for displaying service content
 *
 * @package Corporacy
 */

$corporacy_enable_content = get_theme_mod( 'corporacy_service_option', 'disabled' );

if ( ! corporacy_check_section( $corporacy_enable_content ) ) {
	// Bail if service content is disabled.
	return;
}

$corporacy_tagline = get_option( 'ect_service_content' );
$corporacy_title   = get_option( 'ect_service_title', esc_html__( 'Services', 'corporacy' ) );

$corporacy_classes[] = 'service-section';
$corporacy_classes[] = 'section';


if ( ! $corporacy_title && ! $corporacy_tagline ) {
	$corporacy_classes[] = 'no-section-heading';
}
?>

<div id="service-section" class="<?php echo esc_attr( implode( ' ', $corporacy_classes ) ); ?>">
	<div class="wrapper">
		<?php corporacy_section_heading( $corporacy_tagline, $corporacy_title ); ?>

		<div class="section-content-wrapper service-content-wrapper layout-three">
			<?php get_template_part( 'template-parts/service/content-service' ); ?>
		</div><!-- .section-content-wrapper -->
	</div><!-- .wrapper -->
</div><!-- #service-section -->
