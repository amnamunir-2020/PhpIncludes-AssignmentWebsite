<?php
/**
 * The template for displaying testimonial items
 *
 * @package Corporacy
 */

$corporacy_number = get_theme_mod( 'corporacy_testimonial_number', 2 );

if ( ! $corporacy_number ) {
	// If number is 0, then this section is disabled
	return;
}

?>

<div class="section-content-wrapper testimonial-content-wrapper testimonial-slider owl-carousel">
	<?php
		$corporacy_loop = new WP_Query( corporacy_testimonial_posts_args() );

		if ( $corporacy_loop -> have_posts() ) :
			while ( $corporacy_loop -> have_posts() ) :
				$corporacy_loop -> the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="hentry-inner">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php corporacy_post_thumbnail( array( 670, 670 ), 'html', true ); ?>
						<?php endif; ?>
						
						<div class="entry-container">
							<div class="entry-content">
								<?php the_content(); ?>
							</div>

							<?php
							$position = get_post_meta( get_the_id(), 'ect_testimonial_position', true );
							?>
							<header class="entry-header">
								<h2 class="entry-title"><a href=<?php the_permalink(); ?>><?php the_title(); ?></a></h2>
								
								<?php if ( $position ) : ?>
									<div class="entry-meta"><span class="position">
										<?php echo esc_html( $position ); ?></span>
									</div>
								<?php endif; ?>
							</header>
						</div><!-- .entry-container -->
					</div><!-- .hentry-inner -->
				</article>
			<?php endwhile;
			wp_reset_postdata();
		endif;
	?>
</div><!-- .section-content-wrapper -->

