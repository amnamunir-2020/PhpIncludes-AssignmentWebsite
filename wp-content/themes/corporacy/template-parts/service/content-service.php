<?php
/**
 * The template for displaying service posts on the front page
 *
 * @package Corporacy
 */

$corporacy_number = get_theme_mod( 'corporacy_service_number', 3 );

$corporacy_post_list  = array();
$corporacy_no_of_post = 0;

for ( $i = 1; $i <= $corporacy_number; $i++ ) {
	$corporacy_post_id = get_theme_mod( 'corporacy_service_cpt_' . $i );

	if ( $corporacy_post_id ) {
		$corporacy_post_list[] = $corporacy_post_id;

		$corporacy_no_of_post++;
	}
}

if ( ! $corporacy_no_of_post ) {
	return;
}

$corporacy_loop = new WP_Query( array(
	'ignore_sticky_posts' => 1, // ignore sticky posts.
	'post_type'           => 'ect-service',
	'post__in'            => $corporacy_post_list,
	'orderby'             => 'post__in',
	'posts_per_page'      => $corporacy_no_of_post,
) );

while ( $corporacy_loop->have_posts() ) :
	
	$corporacy_loop->the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="hentry-inner">
			<?php corporacy_post_thumbnail( array( 110, 110 ), 'html', true ); ?>

			<div class="entry-container">
				<header class="entry-header">
					<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h2>' ); ?>
				</header>

				<div class="entry-summary"><?php the_excerpt(); ?></div><!-- .entry-summary -->
			</div><!-- .entry-container -->
		</div> <!-- .hentry-inner -->
	</article> <!-- .article -->
	<?php
endwhile;

wp_reset_postdata();
