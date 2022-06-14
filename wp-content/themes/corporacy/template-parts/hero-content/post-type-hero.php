<?php
/**
 * The template used for displaying hero content
 *
 * @package Corporacy
 */

if ( $corporacy_id = get_theme_mod( 'corporacy_hero_content' ) ) {
	$corporacy_args['page_id'] = absint( $corporacy_id );
}

// If $corporacy_args is empty return false
if ( empty( $corporacy_args ) ) {
	return;
}

// Create a new WP_Query using the argument previously created
$hero_query = new WP_Query( $corporacy_args );
if ( $hero_query->have_posts() ) :
	while ( $hero_query->have_posts() ) :
		$hero_query->the_post();
		?>
		<div id="hero-section" class="hero-section section content-align-right text-align-left">
			<div class="wrapper">
				<div class="section-content-wrapper hero-content-wrapper">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="hentry-inner">
							<?php $post_thumbnail = corporacy_post_thumbnail( array( 670, 750 ), 'html', false ); // corporacy_post_thumbnail( $image_size, $corporacy_type = 'html', $echo = true, $no_thumb = false ).

						if ( has_post_thumbnail() ) :
							echo $post_thumbnail;
							?>
							<div class="entry-container">
						<?php else : ?>
							<div class="entry-container full-width">
						<?php endif; ?>
							<?php $corporacy_tagline = get_theme_mod( 'corporacy_hero_content_tagline' ); ?>
							<header class="entry-header">
								<?php if ( $corporacy_tagline ) : ?>
									<div class="section-tagline">
										<?php echo wp_kses_post( $corporacy_tagline ); ?>
									</div><!-- .section-tagline -->
								<?php endif; ?>

								<h2 class="entry-title"><?php the_title(); ?></h2>
							</header><!-- .entry-header -->

							<div class="entry-summary">
								<?php the_excerpt();  ?>
							</div><!-- .entry-summary -->

							<?php if ( get_edit_post_link() ) : ?>
								<footer class="entry-footer">
									<div class="entry-meta">
										<?php
											edit_post_link(
												sprintf(
													/* translators: %s: Name of current post */
													esc_html__( 'Edit %s', 'corporacy' ),
													the_title( '<span class="screen-reader-text">"', '"</span>', false )
												),
												'<span class="edit-link">',
												'</span>'
											);
										?>
									</div>	<!-- .entry-meta -->
								</footer><!-- .entry-footer -->
							<?php endif; ?>
						</div><!-- .hentry-inner -->
					</article>
				</div><!-- .section-content-wrapper -->
			</div><!-- .wrapper -->
		</div><!-- .section -->
	<?php
	endwhile;

	wp_reset_postdata();
endif;
