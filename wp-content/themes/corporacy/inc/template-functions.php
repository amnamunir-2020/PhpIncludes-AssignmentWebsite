<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Corporacy
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function corporacy_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Always add a front-page class to the front page.
	if ( is_front_page() && ! is_home() ) {
		$classes[] = 'page-template-front-page';
	}

	$classes['layout-type'] = 'fluid-layout';

	// Adds a class of navigation-(default|classic) to blogs.
	if ( 'modern' === get_theme_mod( 'corporacy_menu_type', 'classic' ) ) {
		$classes[] = 'navigation-default';
	} else {
		$classes[] = 'navigation-classic';
	}

	// Adds a class with respect to layout selected.
	$layout  = corporacy_get_theme_layout();
	$sidebar = corporacy_get_sidebar_id();

	$layout_class = "no-sidebar content-width-layout";

	if ( 'right-sidebar' === $layout ) {
		if ( '' !== $sidebar ) {
			$layout_class = 'two-columns-layout content-left';
		}
	}

	$classes[] = $layout_class;

	$classes['content_layout'] = 'excerpt';

	$classes[] = 'header-media-fluid';

	$enable_sticky_playlist = get_theme_mod( 'corporacy_sticky_playlist_visibility', 'disabled' );

	if ( corporacy_check_section( $enable_sticky_playlist ) ) {
		$classes[] = 'sticky-playlist-enabled';
	}

	$enable_slider = corporacy_check_section( get_theme_mod( 'corporacy_slider_option', 'disabled' ) );

	$header_image = corporacy_featured_overall_image();

	if ( 'disable' !== $header_image ) {
		$classes[] = 'has-header-media';
	}

	if ( $enable_slider || 'disable' !== $header_image ) {
		$classes['header-type'] = 'absolute-header';
	} else {
		$classes['header-type'] = 'non-absolute-header';
	}

	if ( ! ( corporacy_has_header_media_text() ) ) {
		$classes[] = 'header-media-text-disabled';
	}

	// Add a class if there is a custom header.
	if ( has_header_image() ) {
		$classes[] = 'has-header-image';
	}

	// Added color scheme to body class.
	$classes[] = 'color-scheme-' . esc_attr( get_theme_mod( 'color_scheme', 'default' ) );

	if ( get_theme_mod( 'corporacy_gradient_button', 1 ) ) {
		$classes['gradient-button'] = 'is-gradient-button';
	}

	// Add class if absolute header logo is added.
	if ( get_theme_mod( 'corporacy_absolute_header_custom_logo' ) ) {
		$classes['absolute-header-logo'] = 'has-absolute-header-logo';
	}

	return $classes;
}
add_filter( 'body_class', 'corporacy_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function corporacy_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'corporacy_pingback_header' );

/**
 * Adds custom overlay for Header Media
 */
function corporacy_header_media_image_overlay_css() {
	$overlay = get_theme_mod( 'corporacy_header_media_image_opacity' );

	if ( $overlay ) {
		$overlay_bg = $overlay / 100;

		$css = '.custom-header-overlay {
			background-color: rgba(0, 0, 0, ' . esc_attr( $overlay_bg ) . ' );
		} '; // Dividing by 100 as the option is shown as % for user

		wp_add_inline_style( 'corporacy-style', $css );
	}


}
add_action( 'wp_enqueue_scripts', 'corporacy_header_media_image_overlay_css', 11 );

/**
 * Remove first post from blog as it is already show via recent post template
 */
function corporacy_alter_home( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$cats = get_theme_mod( 'corporacy_front_page_category' );

		if ( is_array( $cats ) && ! in_array( '0', $cats ) ) {
			$query->query_vars['category__in'] = $cats;
		}
	}
}
add_action( 'pre_get_posts', 'corporacy_alter_home' );

/**
 * Function to add Scroll Up icon
 */
function corporacy_scrollup() {
	$disable_scrollup =  ! get_theme_mod( 'corporacy_disable_scrollup', 1 );

	if ( $disable_scrollup ) {
		return;
	}

	echo '<a href="#masthead" id="scrollup" class="backtotop">' .  corporacy_get_svg( array( 'icon' => 'scrollup' ) ) . '<span class="screen-reader-text">' . esc_html__( 'Scroll Up', 'corporacy' ) . '</span></a>' ;

}
add_action( 'wp_footer', 'corporacy_scrollup', 1 );

if ( ! function_exists( 'corporacy_content_nav' ) ) :
	/**
	 * Display navigation/pagination when applicable
	 *
	 * @since 1.0.0
	 */
	function corporacy_content_nav() {
		global $wp_query;

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}

		$pagination_type = get_theme_mod( 'corporacy_pagination_type', 'default' );

		if ( ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) || class_exists( 'Catch_Infinite_Scroll' ) ) {
			// Support infinite scroll plugins.
			the_posts_navigation();
		} elseif ( 'numeric' === $pagination_type && function_exists( 'the_posts_pagination' ) ) {
			the_posts_pagination( array(
				'prev_text'          => '<span>' . esc_html__( 'Prev', 'corporacy' ) . '</span>',
				'next_text'          => '<span>' . esc_html__( 'Next', 'corporacy' ) . '</span>',
				'screen_reader_text' => '<span class="nav-subtitle screen-reader-text">' . esc_html__( 'Page', 'corporacy' ) . ' </span>',
			) );
		} else {
			the_posts_navigation();
		}
	}
endif; // corporacy_content_nav

/**
 * Check if a section is enabled or not based on the $value parameter
 * @param  string $value Value of the section that is to be checked
 * @return boolean return true if section is enabled otherwise false
 */
function corporacy_check_section( $value ) {
	return ( 'entire-site' == $value  || ( is_front_page() && 'homepage' === $value ) );
}

/**
 * Return the first image in a post. Works inside a loop.
 * @param [integer] $post_id [Post or page id]
 * @param [string/array] $size Image size. Either a string keyword (thumbnail, medium, large or full) or a 2-item array representing width and height in pixels, e.g. array(32,32).
 * @param [string/array] $attr Query string or array of attributes.
 * @return [string] image html
 *
 * @since 1.0.0
 */
function corporacy_get_first_image( $postID, $size, $attr, $src = false ) {
	ob_start();

	ob_end_clean();

	$image 	= '';

	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_post_field('post_content', $postID ) , $matches);

	if ( isset( $matches[1][0] ) ) {
		// Get first image.
		$first_img = $matches[1][0];

		if ( $src ) {
			//Return url of src is true
			return $first_img;
		}

		return '<img class="wp-post-image" src="'. esc_url( $first_img ) .'">';
	}

	return false;
}

function corporacy_get_theme_layout() {
	$layout = '';

	if ( is_page_template( 'templates/no-sidebar.php' ) ) {
		$layout = 'no-sidebar';
	} if ( is_page_template( 'templates/right-sidebar.php' ) ) {
		$layout = 'right-sidebar';
	} else {
		$layout = get_theme_mod( 'corporacy_default_layout', 'right-sidebar' );

		if ( is_home() || is_archive() ) {
			$layout = get_theme_mod( 'corporacy_homepage_archive_layout', 'right-sidebar' );
		}
	}

	return $layout;
}

function corporacy_get_sidebar_id() {
	$sidebar = $id = '';

	$layout = corporacy_get_theme_layout();

	if ( 'no-sidebar' === $layout ) {
		return $sidebar;
	}

	if ( is_active_sidebar( 'sidebar-1' ) ) {
		$sidebar = 'sidebar-1'; // Primary Sidebar.
	}

	return $sidebar;
}

if ( ! function_exists( 'corporacy_truncate_phrase' ) ) :
	/**
	 * Return a phrase shortened in length to a maximum number of characters.
	 *
	 * Result will be truncated at the last white space in the original string. In this function the word separator is a
	 * single space. Other white space characters (like newlines and tabs) are ignored.
	 *
	 * If the first `$max_characters` of the string does not contain a space character, an empty string will be returned.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text            A string to be shortened.
	 * @param integer $max_characters The maximum number of characters to return.
	 *
	 * @return string Truncated string
	 */
	function corporacy_truncate_phrase( $text, $max_characters ) {

		$text = trim( $text );

		if ( mb_strlen( $text ) > $max_characters ) {
			//* Truncate $text to $max_characters + 1
			$text = mb_substr( $text, 0, $max_characters + 1 );

			//* Truncate to the last space in the truncated string
			$text = trim( mb_substr( $text, 0, mb_strrpos( $text, ' ' ) ) );
		}

		return $text;
	}
endif; //corporacy_truncate_phrase

if ( ! function_exists( 'corporacy_get_the_content_limit' ) ) :
	/**
	 * Return content stripped down and limited content.
	 *
	 * Strips out tags and shortcodes, limits the output to `$max_char` characters, and appends an ellipsis and more link to the end.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $max_characters The maximum number of characters to return.
	 * @param string  $more_link_text Optional. Text of the more link. Default is "(more...)".
	 * @param bool    $stripteaser    Optional. Strip teaser content before the more text. Default is false.
	 *
	 * @return string Limited content.
	 */
	function corporacy_get_the_content_limit( $max_characters, $more_link_text = '(more...)', $stripteaser = false ) {

		$content = get_the_content( '', $stripteaser );

		// Strip tags and shortcodes so the content truncation count is done correctly.
		$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'get_the_content_limit_allowedtags', '<script>,<style>' ) );

		// Remove inline styles / .
		$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

		// Truncate $content to $max_char
		$content = corporacy_truncate_phrase( $content, $max_characters );

		// More link?
		if ( $more_link_text ) {
			$link   = apply_filters( 'get_the_content_more_link', sprintf( '<a href="%s" class="more-link">%s</a>', esc_url( get_permalink() ), $more_link_text ), $more_link_text );
			$output = sprintf( '<p>%s %s</p>', $content, $link );
		} else {
			$output = sprintf( '<p>%s</p>', $content );
			$link = '';
		}

		return apply_filters( 'corporacy_get_the_content_limit', $output, $content, $link, $max_characters );

	}
endif; //corporacy_get_the_content_limit

if ( ! function_exists( 'corporacy_sections' ) ) :
	/**
	 * Display Sections on header and footer with respect to the section option set in corporacy_sections_sort
	 */
	function corporacy_sections( $selector = 'header' ) {
		get_template_part( 'template-parts/header/header-media' );
		get_template_part( 'template-parts/slider/display-slider' );
		get_template_part( 'template-parts/service/display-service' );
		get_template_part( 'template-parts/hero-content/content-hero' );
		get_template_part( 'template-parts/testimonial/display-testimonial' );
		get_template_part( 'template-parts/featured-content/display-featured' );
		get_template_part( 'template-parts/portfolio/display-portfolio' );
	}
endif;

if ( ! function_exists( 'corporacy_post_thumbnail' ) ) :
	/**
	 * $image_size post thumbnail size
	 * $type html, html-with-bg, url
	 * $echo echo true/false
	 * $no_thumb display no-thumb image or not
	 */
	function corporacy_post_thumbnail( $image_size = 'post-thumbnail', $type = 'html', $echo = true, $no_thumb = false ) {
		$image = $image_url = '';

		if ( has_post_thumbnail() ) {
			$image_url = get_the_post_thumbnail_url( get_the_ID(), $image_size );
			$image     = get_the_post_thumbnail( get_the_ID(), $image_size );
		} else {
			if ( is_array( $image_size ) && $no_thumb ) {
				$image_url  = trailingslashit( get_template_directory_uri() ) . 'assets/images/no-thumb-' . $image_size[0] . 'x' . $image_size[1] . '.jpg';
				$image      = '<img src="' . esc_url( $image_url ) . '" alt="" />';
			} elseif ( $no_thumb ) {
				global $_wp_additional_image_sizes;

				$image_url  = trailingslashit( get_template_directory_uri() ) . 'assets/images/no-thumb-1920x1080.jpg';

				if ( array_key_exists( $image_size, $_wp_additional_image_sizes ) ) {
					$image_url  = trailingslashit( get_template_directory_uri() ) . 'assets/images/no-thumb-' . $_wp_additional_image_sizes[ $image_size ]['width'] . 'x' . $_wp_additional_image_sizes[ $image_size ]['height'] . '.jpg';
				}

				$image      = '<img src="' . esc_url( $image_url ) . '" alt="" />';
			}

			// Get the first image in page, returns false if there is no image.
			$first_image_url = corporacy_get_first_image( get_the_ID(), $image_size, '', true );

			// Set value of image as first image if there is an image present in the page.
			if ( $first_image_url ) {
				$image_url = $first_image_url;
				$image = '<img class="wp-post-image" src="'. esc_url( $image_url ) .'">';
			}
		}

		if ( ! $image_url ) {
			// Bail if there is no image url at this stage.
			return;
		}

		if ( 'url' === $type ) {
			return $image_url;
		}

		$output = '<div';

		if ( 'html-with-bg' === $type ) {
			$output .= ' class="post-thumbnail-background" style="background-image: url( ' . esc_url( $image_url ) . ' )"';
		} else {
			$output .= ' class="post-thumbnail"';
		}

		$output .= '>';

		$output .= '<a class="cover-link" href="' . esc_url( get_the_permalink() ) . '" title="' . the_title_attribute( 'echo=0' ) . '">';

		if ( 'html-with-bg' !== $type ) {
			$output .= $image;
		}

		$output .= '</a></div><!-- .post-thumbnail -->';

		if ( ! $echo ) {
			return $output;
		}

		echo $output;
	}
endif;

if ( ! function_exists( 'corporacy_testimonial_posts_args' ) ) :
	function corporacy_testimonial_posts_args() {
		$number = get_theme_mod( 'corporacy_testimonial_number', 2 );

		$post_list  = array();// list of valid post/page ids

		for ( $i = 1; $i <= $number; $i++ ) {
			$post_id =  get_theme_mod( 'corporacy_testimonial_cpt_' . $i );

			if ( $post_id ) {
				// Polylang Support.
				if ( class_exists( 'Polylang' ) ) {
					$post_id = pll_get_post( $post_id, pll_current_language() );
				}

				$post_list[] = $post_id;

			}
		}

		$args = array(
			'ignore_sticky_posts' => 1, // ignore sticky posts
			'post__in'            => $post_list,
			'orderby'             => 'post__in',
			'posts_per_page'      => $number,
			'post_type'           => 'jetpack-testimonial',
		);

		return $args;
	}
endif;

if ( ! function_exists( 'corporacy_primary_menu_active_buttons' ) ) :

	/**
	 * Menu top button
	 */
	function corporacy_primary_menu_active_buttons() {
		$login_label  = get_theme_mod( 'corporacy_secondary_button_label' );
		$login_link   = get_theme_mod( 'corporacy_secondary_button_link' );
		$login_target = get_theme_mod( 'corporacy_secondary_button_target' ) ? '_blank': '_self';

		if( $login_label ) : ?>
			<div class ="action-buttons">
				<?php if( $login_label ) : ?>
					<a class="button is-ghost-button" target="<?php echo $login_target; ?>" href="<?php echo esc_url( $login_link ); ?>"><?php echo esc_html( $login_label ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif;
	}
endif;

/**
 * Logo for absolute_header
 *
 * @param integer $blog_id
 * @return void
 */
function corporacy_absolute_header_logo( $blog_id = 0 ) {
	$switched_blog = false;

	if ( is_multisite() && ! empty( $blog_id ) && get_current_blog_id() !== (int) $blog_id ) {
		switch_to_blog( $blog_id );
		$switched_blog = true;
	}

	$custom_logo_id = get_theme_mod( 'corporacy_absolute_header_custom_logo' );

	// We have a logo. Logo is go.
	if ( $custom_logo_id ) {
		$custom_logo_attr = array(
			'class'   => 'custom-logo',
			'loading' => false,
		);

		$unlink_homepage_logo = (bool) get_theme_support( 'custom-logo', 'unlink-homepage-logo' );

		if ( $unlink_homepage_logo && is_front_page() && ! is_paged() ) {
			/*
			 * If on the home page, set the logo alt attribute to an empty string,
			 * as the image is decorative and doesn't need its purpose to be described.
			 */
			$custom_logo_attr['alt'] = '';
		} else {
			/*
			 * If the logo alt attribute is empty, get the site title and explicitly pass it
			 * to the attributes used by wp_get_attachment_image().
			 */
			$image_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
			if ( empty( $image_alt ) ) {
				$custom_logo_attr['alt'] = get_bloginfo( 'name', 'display' );
			}
		}

		/**
		 * Filters the list of custom logo image attributes.
		 *
		 * @since 5.5.0
		 *
		 * @param array $custom_logo_attr Custom logo image attributes.
		 * @param int   $custom_logo_id   Custom logo attachment ID.
		 * @param int   $blog_id          ID of the blog to get the custom logo for.
		 */
		$custom_logo_attr = apply_filters( 'get_custom_logo_image_attributes', $custom_logo_attr, $custom_logo_id, $blog_id );

		/*
		 * If the alt attribute is not empty, there's no need to explicitly pass it
		 * because wp_get_attachment_image() already adds the alt attribute.
		 */
		$image = wp_get_attachment_image( $custom_logo_id, 'full', false, $custom_logo_attr );

		if ( $unlink_homepage_logo && is_front_page() && ! is_paged() ) {
			// If on the home page, don't link the logo to home.
			printf(
				'<span class="custom-logo-link absolute-header-logo">%1$s</span>',
				$image
			);
		} else {
			$aria_current = is_front_page() && ! is_paged() ? ' aria-current="page"' : '';

			printf(
				'<a href="%1$s" class="custom-logo-link absolute-header-logo" rel="home"%2$s>%3$s</a>',
				esc_url( home_url( '/' ) ),
				$aria_current,
				$image
			);
		}
	} elseif ( is_customize_preview() ) {
		// If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
		printf(
			'<a href="%1$s" class="custom-logo-link absolute-header-logo" style="display:none;"><img class="custom-logo" alt="" /></a>',
			esc_url( home_url( '/' ) )
		);
	}

	if ( $switched_blog ) {
		restore_current_blog();
	}
}
