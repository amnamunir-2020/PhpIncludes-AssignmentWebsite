<?php
/**
 * Template for displaying search forms in Corporacy
 *
 * @package Corporacy
 */
?>

<?php
$unique_id   = esc_attr( uniqid( 'search-form-' ) );
$search_text = get_theme_mod( 'corporacy_header_search_text', esc_html__( 'Search a keyword', 'corporacy' ) );
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo $unique_id; ?>">
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'corporacy' ); ?></span>
		<input type="search" id="<?php echo $unique_id; ?>" class="search-field" placeholder="<?php echo esc_attr( $search_text ); ?>" value="<?php the_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="search-submit">
		<?php echo corporacy_get_svg( array( 'icon' => 'search' ) ); ?>
		<span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'corporacy' ); ?></span></button>
</form>
