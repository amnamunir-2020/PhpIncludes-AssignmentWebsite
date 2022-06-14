<?php
/**
 * The template for displaying featured content
 *
 * @package Corporacy
 */

$corporacy_enable_content = get_theme_mod( 'corporacy_featured_content_option', 'disabled' );

if ( ! corporacy_check_section( $corporacy_enable_content ) ) {
	// Bail if featured content is disabled.
	return;
}


$corporacy_title   = get_option( 'featured_content_title', esc_html__( 'Contents', 'corporacy' ) );
$corporacy_tagline = get_option( 'featured_content_content' );

$layout = get_theme_mod( 'corporacy_featured_content_layout', 'layout-three' );

$corporacy_classes[] = 'layout-three cpt featured-content section';

if( ! $corporacy_title && ! $corporacy_tagline ) {
	$corporacy_classes[] = 'no-section-heading';
}
?>

<div id="featured-content-section" class="<?php echo esc_attr( implode( ' ', $corporacy_classes ) ); ?>">
	<div class="wrapper">
		<?php corporacy_section_heading( $corporacy_tagline, $corporacy_title ); ?>

		<div class="section-content-wrapper featured-content-wrapper <?php echo esc_attr( $layout ); ?>">
			<?php get_template_part( 'template-parts/featured-content/content-featured' ); ?>
		</div><!-- .section-content-wrap -->
	</div><!-- .wrapper -->
</div><!-- #featured-content-section -->
