<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Corporacy
 */

/**
 * Sets up the WordPress core custom header and custom background features.
 *
 * @since 1.0.0
 *
 * @see corporacy_header_style()
 */
function corporacy_custom_header_and_bg() {
	add_theme_support( 'custom-background', apply_filters( 'corporacy_custom_bg_args', array(
		'default-color' => '#ffffff',
	) ) );

	/**
	 * Filter the arguments used when adding 'custom-header' support in Corporacy.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 *     @type string $default-text-color Default color of the header text.
	 *     @type int      $width            Width in pixels of the custom header image. Default 1200.
	 *     @type int      $height           Height in pixels of the custom header image. Default 280.
	 *     @type bool     $flex-height      Whether to allow flexible-height header images. Default true.
	 *     @type callable $wp-head-callback Callback function used to style the header image and text
	 *                                      displayed on the blog.
	 * }
	 */
	add_theme_support( 'custom-header', apply_filters( 'corporacy_custom_header_args', array(
		'default-image'      => get_parent_theme_file_uri( '/assets/images/header-image.png' ),
		'default-text-color' => '#000000',
		'width'              => 1920,
		'height'             => 1080,
		'flex-height'        => true,
		'flex-height'        => true,
		'wp-head-callback'   => 'corporacy_header_style',
		'video'              => true,
	) ) );

	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/assets/images/header-image.png',
			'thumbnail_url' => '%s/assets/images/header-image-275x155.png',
			'description'   => esc_html__( 'Default Header Image', 'corporacy' ),
		)
	) );
}
add_action( 'after_setup_theme', 'corporacy_custom_header_and_bg' );

if ( ! function_exists( 'corporacy_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see corporacy_custom_header_setup().
	 */
	function corporacy_header_style() {
		$header_image = corporacy_featured_overall_image();

	    if ( 'disable' !== $header_image ) :
	    	/**
	    	 * Image Position CSS
	    	 */
			$image_position_mobile  = get_theme_mod( 'corporacy_header_media_image_position_mobile', 'center center' );
			$image_position_desktop = get_theme_mod( 'corporacy_header_media_image_position_desktop' , 'center center' );

			?>
	        <style type="text/css" rel="header-image">
	            .custom-header .wrapper:before {
	                background-image: url( <?php echo esc_url( $header_image ); ?>);
	                <?php if ( 'center center' !== $image_position_mobile ) : ?>
					background-position: <?php echo esc_attr( $image_position_mobile ); ?>;
					<?php endif; ?>
					background-repeat: no-repeat;
					background-size: cover;
	            }

	            <?php if ( 'center center' !== $image_position_desktop ) : ?>
	            @media only screen and (min-width: 64em) {
					.custom-header .wrapper:before {
						background-position: <?php echo esc_attr( $image_position_desktop ); ?>;
					}
				}
				<?php endif; ?>
	        </style>
	    <?php
	    endif;

	    $header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
		?>
			.site-title a,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php
			// If the user has set a custom color for the text use that.
			else :
		?>
			.absolute-header .site-title a,
			.absolute-header .site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif;

if ( ! function_exists( 'corporacy_featured_image' ) ) :
	/**
	 * Template for Featured Header Image from theme options
	 *
	 * To override this in a child theme
	 * simply create your own corporacy_featured_image(), and that function will be used instead.
	 *
	 * @since 1.0.0
	 */
	function corporacy_featured_image() {
		if ( is_header_video_active() && has_header_video() ) {
			return get_header_image();
		}

		$thumbnail = 'corporacy-slider';

		if ( is_post_type_archive( 'jetpack-testimonial' ) ) {
			$jetpack_options = get_theme_mod( 'jetpack_testimonials' );

			if ( isset( $jetpack_options['featured-image'] ) && '' !== $jetpack_options['featured-image'] ) {
				$image = wp_get_attachment_image_src( (int) $jetpack_options['featured-image'], $thumbnail );
				return $image['0'];
			} else {
				return false;
			}
		} elseif ( is_post_type_archive( 'jetpack-portfolio' ) || is_post_type_archive( 'featured-content' ) || is_post_type_archive( 'ect-service' ) ) {
			$option = '';

			if ( is_post_type_archive( 'jetpack-portfolio' ) ) {
				$option = 'jetpack_portfolio_featured_image';
			} elseif ( is_post_type_archive( 'featured-content' ) ) {
				$option = 'featured_content_featured_image';
			} elseif ( is_post_type_archive( 'ect-service' ) ) {
				$option = 'ect_service_featured_image';
			}

			$featured_image = get_option( $option );

			if ( '' !== $featured_image ) {
				$image = wp_get_attachment_image_src( (int) $featured_image, $thumbnail );
				return isset( $image[0] ) ? $image[0] : false;
			} else {
				return get_header_image();
			}
		} elseif ( is_header_video_active() && has_header_video() ) {
			return true;
		} else {
			return get_header_image();
		}
	} // corporacy_featured_image
endif;

if ( ! function_exists( 'corporacy_featured_page_post_image' ) ) :
	/**
	 * Template for Featured Header Image from Post and Page
	 *
	 * To override this in a child theme
	 * simply create your own corporacy_featured_imaage_pagepost(), and that function will be used instead.
	 *
	 * @since 1.0.0
	 */
	function corporacy_featured_page_post_image() {
		$thumbnail = 'corporacy-slider';

		if ( is_home() && $blog_id = get_option('page_for_posts') ) {
			if ( has_post_thumbnail( $blog_id  ) ) {
		    	return get_the_post_thumbnail_url( $blog_id, $thumbnail );
			} else {
				return  corporacy_featured_image();
			}
		} elseif ( ! has_post_thumbnail() && is_front_page() ) {
			return  corporacy_featured_image();
		} elseif ( is_home() && is_front_page() ) {
			return  corporacy_featured_image();
		}

		return get_the_post_thumbnail_url( get_the_id(), $thumbnail );
	} // corporacy_featured_page_post_image
endif;

if ( ! function_exists( 'corporacy_featured_overall_image' ) ) :
	/**
	 * Template for Featured Header Image from theme options
	 *
	 * To override this in a child theme
	 * simply create your own corporacy_featured_pagepost_image(), and that function will be used instead.
	 *
	 * @since 1.0.0
	 */
	function corporacy_featured_overall_image() {
		global $post;
		$enable = get_theme_mod( 'corporacy_header_media_option', 'entire-site' );

		// Check Enable/Disable header image in Page/Post Meta box
		if ( is_singular() ) {
			return corporacy_featured_page_post_image();
		}

		// Check Homepage
		if ( 'homepage' === $enable ) {
			if ( is_front_page() ) {
				return corporacy_featured_image();
			}
		} elseif ( 'entire-site' === $enable ) {
			// Check Entire Site
			return corporacy_featured_image();
		}

		return 'disable';
	} // corporacy_featured_overall_image
endif;

if ( ! function_exists( 'corporacy_header_media_text' ) ):
	/**
	 * Display Header Media Text
	 *
	 * @since 1.0.0
	 */
	function corporacy_header_media_text() {
		if ( ! corporacy_has_header_media_text() ) { 
			// Bail early if header media text is disabled on front page
			return false;
		}

		$before_subtitle = get_theme_mod( 'corporacy_header_media_before_subtitle' );
		?>
		<div class="custom-header-content sections header-media-section content-align-left text-align-left">
			<div class="custom-header-content-wrapper">

				<?php if( is_front_page() && $before_subtitle ) : ?>
					<div class="section-tagline">
						<?php echo esc_html( $before_subtitle ); ?>
					</div>
				<?php endif;
				
				$before = '<div class="section-title-wrapper"><h2 class="entry-title';

				if ( is_singular() ) {
					$before = '<div class="section-title-wrapper"><h1 class="entry-title';
				}

				$before .= '">';

				if ( is_singular() ) {
					corporacy_header_title( $before, '</h1></div>' );
				} else {
					corporacy_header_title( $before, '</h2></div>' );
				}
				?>


				<?php corporacy_header_description( '<div class="site-header-text">', '</div>' ); ?>

				<?php if ( is_front_page() ) :
					$header_media_url_text = get_theme_mod( 'corporacy_header_media_url_text' );
				?>

					<?php if ( $header_media_url_text  ) : 
						?>
						<p>
							<?php if ( $header_media_url_text ) : 
								$header_media_url = get_theme_mod( 'corporacy_header_media_url', '#' );
								?>	
								<span class="more-button"><a href="<?php echo esc_url( $header_media_url ); ?>" target="<?php echo esc_attr( get_theme_mod( 'corporacy_header_url_target' ) ? '_blank' : '_self' ); ?>" class="more-link"><?php echo esc_html( $header_media_url_text ); ?><span class="screen-reader-text"><?php echo wp_kses_post( $header_media_url_text ); ?></span></a></span>
							<?php endif; ?>
						</p>
					<?php endif; ?>
				<?php endif; ?>
			</div><!-- .custom-header-content-wrapper -->
		</div><!-- .custom-header-content -->
		<?php
	} // corporacy_header_media_text.
endif;

if ( ! function_exists( 'corporacy_has_header_media_text' ) ):
	/**
	 * Return Header Media Text fro front page
	 *
	 * @since 1.0.0
	 */
	function corporacy_has_header_media_text() {
		$header_image = corporacy_featured_overall_image();

		if ( is_front_page() ) {
			$header_media_title    = get_theme_mod( 'corporacy_header_media_title' );
			$header_media_text     = get_theme_mod( 'corporacy_header_media_text' );
			$header_media_url      = get_theme_mod( 'corporacy_header_media_url');
			$header_media_url_text = get_theme_mod( 'corporacy_header_media_url_text');

			if ( ! $header_media_title && ! $header_media_text && ! $header_media_url && ! $header_media_url_text ) {
				// Bail early if header media text is disabled
				return false;
			}
		} elseif ( 'disable' === $header_image ) {
			return false;
		}

		return true;
	} // corporacy_has_header_media_text.
endif;

if ( ! function_exists( 'corporacy_header_title' ) ) :
	/**
	 * Display header media text
	 */
	function corporacy_header_title( $before = '', $after = '' ) {
		if ( is_front_page() ) {
			$header_media_title = get_theme_mod( 'corporacy_header_media_title' );
			if ( $header_media_title ) {
				echo $before . wp_kses_post( $header_media_title ) . $after;
			}
		} else if ( is_home() ) {
			$header_media_title = get_theme_mod( 'corporacy_static_page_heading','Archives' );
			if ( $header_media_title ) {
				echo $before . wp_kses_post( $header_media_title ) . $after;
			}
		} elseif ( is_singular() ) {
			the_title( $before, $after );
		} elseif ( is_404() ) {
			echo $before . esc_html__( 'Nothing Found', 'corporacy' ) . $after;
		} elseif ( is_search() ) {
			/* translators: %s: search query. */
			echo $before . sprintf( esc_html__( 'Search Results for: %s', 'corporacy' ), '<span>' . get_search_query() . '</span>' ) . $after;
		} else {
			the_archive_title( $before, $after );
		}
	}
endif;

if ( ! function_exists( 'corporacy_header_description' ) ) :
	/**
	 * Display header media description
	 */
	function corporacy_header_description( $before = '', $after = '' ) {
		if ( is_front_page() && get_theme_mod( 'corporacy_header_media_text' ) ) {
			echo $before . '<p>' . wp_kses_post( get_theme_mod( 'corporacy_header_media_text' ) ) . '</p>' . $after;
		} elseif ( is_singular() && ! is_page() ) {
			echo $before . '<div class="entry-header"><div class="entry-meta">';
				corporacy_posted_on();
			echo '</div><!-- .entry-meta --></div>' . $after;
		} elseif ( is_404() ) {
			echo $before . '<p>' . esc_html__( 'Oops! That page can&rsquo;t be found', 'corporacy' ) . '</p>' . $after;
		} else {
			the_archive_description( $before, $after );
		}
	}
endif;


if ( ! function_exists( 'corporacy_video_controls' ) ) :
	/**
	 * Customize video play/pause button in the custom header.
	 */
	function corporacy_video_controls( $settings ) {
		$settings['l10n']['play'] = '<span class="screen-reader-text">' . esc_html__('Play background video', 'corporacy') . '</span>' . corporacy_get_svg(array('icon' => 'play'));
		$settings['l10n']['pause'] = '<span class="screen-reader-text">' . esc_html__('Pause background video', 'corporacy') . '</span>' . corporacy_get_svg(array('icon' => 'pause'));
		return $settings;
	}
add_filter('header_video_settings', 'corporacy_video_controls');
endif;
