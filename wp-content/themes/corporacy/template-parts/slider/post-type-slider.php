<?php
/**
 * The template used for displaying slider
 *
 * @package Corporacy
 */

$corporacy_quantity   = get_theme_mod( 'corporacy_slider_number', 4 );
$corporacy_no_of_post = 0; // for number of posts
$corporacy_post_list  = array(); // list of valid post/page ids

$corporacy_args = array(
	'ignore_sticky_posts' => 1, // ignore sticky posts
	'post_type' => 'page',
);

//Get valid number of posts
for ( $i = 1; $i <= $corporacy_quantity; $i++ ) {
	$corporacy_id = get_theme_mod( 'corporacy_slider_page_' . $i );

	if ( $corporacy_id && '' !== $corporacy_id ) {
		$corporacy_post_list = array_merge( $corporacy_post_list, array( $corporacy_id ) );

		$corporacy_no_of_post++;
	}
}

$corporacy_args['post__in'] = $corporacy_post_list;
$corporacy_args['orderby'] = 'post__in';

if ( ! $corporacy_no_of_post ) {
	return;
}

$corporacy_args['posts_per_page'] = $corporacy_no_of_post;
$corporacy_loop = new WP_Query( $corporacy_args );

while ( $corporacy_loop->have_posts() ) :
	$corporacy_loop->the_post();

	$corporacy_classes = 'post post-' . get_the_ID() . ' hentry slides';

	?>
	<article class="<?php echo esc_attr( $corporacy_classes ); ?>">
		<div class="hentry-inner">
			<?php corporacy_post_thumbnail( 'corporacy-slider', 'html', true, true ); ?>

			<div class="entry-container">
				<div class="content-container">
					<header class="entry-header">
						<h2 class="entry-title">
							<a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h2>
					</header>

					<div class="entry-summary"><?php the_excerpt(); ?></div><!-- .entry-summary -->
				</div> <!--  .content-container  -->
			</div><!-- .entry-container -->
		</div><!-- .hentry-inner -->
	</article><!-- .slides -->
<?php
endwhile;

wp_reset_postdata();
