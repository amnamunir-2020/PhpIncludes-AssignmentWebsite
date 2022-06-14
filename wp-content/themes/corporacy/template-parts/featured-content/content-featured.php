<?php
/**
 * The template for displaying featured posts on the front page
 *
 * @package Corporacy
 */
$corporacy_number     = get_theme_mod( 'corporacy_featured_content_number', 3 );

if ( ! $corporacy_number ) {
	// If number is 0, then this section is disabled
	return;
}

$corporacy_post_list  = array();
$corporacy_no_of_post = 0;

$corporacy_args = array(
	'orderby'             => 'post__in',
	'ignore_sticky_posts' => 1, // ignore sticky posts
	'post_type'           => 'featured-content',
);

for ( $i = 1; $i <= $corporacy_number; $i++ ) {
	$corporacy_post_id = '';

	$corporacy_post_id =  get_theme_mod( 'corporacy_featured_content_cpt_' . $i );

	if ( $corporacy_post_id && '' !== $corporacy_post_id ) {
		// Polylang Support.
		if ( class_exists( 'Polylang' ) ) {
			$corporacy_post_id = pll_get_post( $corporacy_post_id, pll_current_language() );
		}

		$corporacy_post_list = array_merge( $corporacy_post_list, array( $corporacy_post_id ) );

		$corporacy_no_of_post++;
	}
}

if ( ! $corporacy_no_of_post ) {
	return;
}

$corporacy_args['posts_per_page'] = $corporacy_no_of_post;
$corporacy_args['post__in']       = $corporacy_post_list;

$corporacy_loop = new WP_Query( $corporacy_args );

while ( $corporacy_loop->have_posts() ) :
	
	$corporacy_loop->the_post();

	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="hentry-inner">
			<?php corporacy_post_thumbnail( array( 670, 446 ) ); ?>

			<div class="entry-container">
				<header class="entry-header">
					<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">','</a></h2>' ); ?>

					<div class="entry-meta">
						<?php corporacy_cat_list(); ?>
					</div><!-- .entry-meta -->
				</header>
			</div><!-- .entry-container -->
		</div><!-- .hentry-inner -->
	</article>
<?php
endwhile;

wp_reset_postdata();
