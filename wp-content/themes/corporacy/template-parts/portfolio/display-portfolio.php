<?php
/**
 * The template for displaying portfolio items
 *
 * @package Corporacy
 */
?>

<?php
$enable = get_theme_mod( 'corporacy_portfolio_option', 'disabled' );

if ( ! corporacy_check_section( $enable ) ) {
	// Bail if portfolio section is disabled.
	return;
}

$corporacy_tagline = get_option( 'jetpack_portfolio_content' );
$corporacy_title   = get_option( 'jetpack_portfolio_title', esc_html__( 'Projects', 'corporacy' ) );
?>

<div id="portfolio-content-section" class="jetpack-portfolio section layout-four">
	<div class="wrapper">
		<?php corporacy_section_heading( $corporacy_tagline, $corporacy_title ); ?>

		<div class="section-content-wrapper portfolio-content-wrapper layout-four">
			<div class="grid">
				<?php get_template_part( 'template-parts/portfolio/post-types-portfolio' ); ?>
			</div>
		</div><!-- .section-content-wrap -->
	</div><!-- .wrapper -->
</div><!-- #portfolio-section -->
