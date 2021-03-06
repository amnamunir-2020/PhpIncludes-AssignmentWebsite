<?php
/**
 * The Template for displaying all single courses.
 *
 * @package     LifterLMS/Templates
 *
 * @since       1.0.0
 * @version     3.14.0
 */

defined( 'ABSPATH' ) || exit;
?>
<li <?php post_class( 'llms-loop-item' ); ?>>
	<div class="llms-loop-item-content">

	<?php
		/**
		 * Hook: lifterlms_before_loop_item
		 *
		 * @hooked lifterlms_loop_featured_video - 8
		 * @hooked lifterlms_loop_link_start - 10
		 */
		do_action( 'lifterlms_before_loop_item' );
	?>

	<?php
		/**
		 * Hook: lifterlms_before_loop_item_title
		 *
		 * @hooked lifterlms_template_loop_thumbnail - 10
		 * @hooked lifterlms_template_loop_progress - 15
		 */
		do_action( 'lifterlms_before_loop_item_title' );
	?>

	<h4 class="llms-loop-title"><?php the_title(); ?></h4>

	<footer class="llms-loop-item-footer">
		<?php
            lifterlms_template_loop_length();
            lifterlms_template_loop_difficulty();
            lifterlms_template_loop_enroll_status();
            lifterlms_template_loop_enroll_date();
            lifterlms_template_loop_author();
		?>
	</footer>

	<?php
		/**
		 * Hook: lifterlms_after_loop_item
		 *
		 * @hooked lifterlms_loop_link_end - 5
		 */
		do_action( 'lifterlms_after_loop_item' );
	?>

	</div><!-- .llms-loop-item-content -->
</li><!-- .llms-loop-item -->
