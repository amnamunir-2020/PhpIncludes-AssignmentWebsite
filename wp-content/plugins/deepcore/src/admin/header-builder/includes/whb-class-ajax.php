<?php
/**
 * Header Builder - AJAX Class.
 *
 * @author  Webnus
 */

// don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

if ( ! class_exists( 'WHB_Ajax' ) ) :

	class WHB_Ajax {

		/**
		 * Instance of this class.
		 *
		 * @since   1.0.0
		 * @access  private
		 * @var     WHB_Ajax
		 */
		private static $instance;

		/**
		 * Provides access to a single instance of a module using the singleton pattern.
		 *
		 * @since   1.0.0
		 * @return  object
		 */
		public static function get_instance() {

			if ( self::$instance === null ) {
				self::$instance = new self();
			}

			return self::$instance;

		}

		/**
		 * Constructor.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {
			// Save WEBNUS Header Builder data
			add_action( 'wp_ajax_whb_save_data', array( $this, 'save_data' ) );
			// Live styling tab in frontend
			add_action( 'wp_ajax_whb_live_dynamic_styles', array( $this, 'live_dynamic_styles' ) );
			// Publish header
			add_action( 'wp_ajax_whb_publish', array( $this, 'publish' ) );
			// Export header
			add_action( 'wp_ajax_whb_export', array( $this, 'export_header' ) );
			// Get component fields
			add_action( 'wp_ajax_whb_get_component_fields', array( $this, 'get_component_fields' ) );
			// Get components
			// add_action( 'wp_ajax_whb_component_detector',			array( $this, 'whb_component_detector' ) );
			// Remove item from woocommerce cart
			add_action( 'wp_ajax_nopriv_wn_woo_ajax_update_cart', array( $this, 'remove_item_from_cart' ) );
			add_action( 'wp_ajax_wn_woo_ajax_update_cart', array( $this, 'remove_item_from_cart' ) );
			// Add item to woocommerce cart
			add_action( 'wp_ajax_nopriv_wn_woo_ajax_add_to_cart', array( $this, 'add_item_to_cart' ) );
			add_action( 'wp_ajax_wn_woo_ajax_add_to_cart', array( $this, 'add_item_to_cart' ) );

		}

		/**
		 * Save all data.
		 *
		 * @since   1.0.0
		 */
		public function save_data() {
			check_ajax_referer( 'whb-nonce', 'nonce' );

			$sa_frontendComponents = wp_filter_post_kses( $_POST['frontendComponents'] );
			$frontendComponents    = maybe_unserialize( json_decode( stripslashes( $sa_frontendComponents ), true ) );
			echo WHB_Output::output( true, $frontendComponents );
			wp_die(); // this is required to terminate immediately and return a proper response
		}

		/**
		 * Live styling tab and other dynamic styles.
		 *
		 * @since   1.0.0
		 */
		public function live_dynamic_styles() {
			check_ajax_referer( 'whb-nonce', 'nonce' );
			echo WHB_Helper::set_dynamic_styles( '' );
			echo WHB_Helper::get_dynamic_styles();
			wp_die(); // this is required to terminate immediately and return a proper response
		}

		/**
		 * Publish all data.
		 *
		 * @since   1.0.0
		 */
		public function publish() {
			check_ajax_referer( 'whb-nonce', 'nonce' );

			$sa_components = wp_filter_post_kses( $_POST['components'] );
			$components    = maybe_unserialize( json_decode( stripslashes( $sa_components ), true ) );

			$sa_editorComponents = wp_filter_post_kses( $_POST['editorComponents'] );
			$editorComponents    = maybe_unserialize( json_decode( stripslashes( $sa_editorComponents ), true ) );

			$sa_frontendComponents = wp_filter_post_kses( $_POST['frontendComponents'] );
			$frontendComponents    = maybe_unserialize( json_decode( stripslashes( $sa_frontendComponents ), true ) );
			$used_components       = array();

			if ( $frontendComponents ) {
				foreach ( $frontendComponents as $ed => $views ) {
					foreach ( $views as $fg => $locations ) {
						foreach ( $locations as $hj => $settings ) {
							foreach ( $settings as $ty => $setting ) {
								if ( is_array( $setting ) ) {
									foreach ( $setting as $we => $element ) {
										if ( 'name' == $we ) {
											$used_components[ $element ] = $element;
										}
									}
								}
							}
						}
					}
				}
				$used_components = json_encode( $used_components );
			}

			if ( WHB_Multilanguage::language() ) {
				update_option( WHB_Multilanguage::language() . '_whb_data_components', $components );
				update_option( WHB_Multilanguage::language() . '_whb_data_editor_components', $editorComponents );
				update_option( WHB_Multilanguage::language() . '_whb_data_frontend_components', $frontendComponents );
				update_option( WHB_Multilanguage::language() . '_whb_styles', $used_components );
			} else {
				update_option( 'whb_data_components', $components );
				update_option( 'whb_data_editor_components', $editorComponents );
				update_option( 'whb_data_frontend_components', $frontendComponents );
				update_option( 'whb_styles', $used_components );
			}
			wp_die(); // this is required to terminate immediately and return a proper response
		}

		/**
		 * Export header.
		 *
		 * @since   1.0.0
		 */
		public function export_header() {
			$content                                 = array();
			$lang = isset( $_GET['lang'] ) ? sanitize_text_field( $_GET['lang'] ) . '_' : '';
			$content[$lang . 'whb_data_components']          = get_option( $lang . 'whb_data_components' );
			$content[$lang . 'whb_data_editor_components']   = get_option( $lang . 'whb_data_editor_components' );
			$content[$lang . 'whb_data_frontend_components'] = get_option( $lang . 'whb_data_frontend_components' );
			$content                                 = json_encode( $content );
			header( 'Content-Description: File Transfer' );
			header( 'Content-type: application/txt' );
			header( 'Content-Disposition: attachment; filename="webnus-header-builder-' . date( 'd-m-Y' ) . '.json"' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate' );
			header( 'Pragma: public' );
			echo wp_unslash( $content );
			exit;
		}

		/**
		 * Get component fields.
		 *
		 * @since   1.0.0
		 */
		public function get_component_fields() {

			check_ajax_referer( 'whb-nonce', 'nonce' );

			$el_name = wp_kses_post( $_POST['el_name'] );
			ob_start();

			include_once WHB_Helper::get_file( 'includes/elements/components/backend/' . WHB::FILE_PREFIX . $el_name . '-backend.php' );

			$output = ob_get_contents();
			ob_end_clean();

			echo '' . $output;

			wp_die(); // this is required to terminate immediately and return a proper response

		}

		/**
		 * Get component fields.
		 *
		 * @since   1.0.0
		 */
		public function whb_component_detector() {
			check_ajax_referer( 'whb-nonce', 'nonce' );

			$sa_detectedcomponents = wp_kses_post( $_POST['detectedcomponents'] );
			$components            = maybe_unserialize( json_decode( stripslashes( $sa_detectedcomponents ), true ) );
			$used_components       = array();
			if ( $components ) {
				foreach ( $components as $key => $views ) {
					foreach ( $views as $key => $locations ) {
						foreach ( $locations as $key => $settings ) {
							foreach ( $settings as $key => $setting ) {
								foreach ( $setting as $key => $element ) {
									if ( 'name' == $key ) {
										$used_components[ $element ] = $element;
									}
								}
							}
						}
					}
				}
				update_option( 'whb_styles', json_encode( $used_components ) );
			}
			wp_die();
		}

		/**
		 * Remove item from woocommerce cart.
		 *
		 * @since   1.0.0
		 * @return  cart items
		 */
		public function remove_item_from_cart() {

			// Set the product ID to remove
			$cart_product_id = sanitize_text_field( $_POST['cart_product_id'] );
			$prod_to_remove  = array( $cart_product_id );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$cart_item_id = $cart_item['data']->get_id();

				if ( in_array( $cart_item_id, $prod_to_remove ) ) {
					WC()->cart->remove_cart_item( $cart_item_key );
				}
			}

			wn_mini_cart();

			wp_die(); // this is required to terminate immediately and return a proper response

		}

		/**
		 * Add item to woocommerce cart.
		 *
		 * @since   1.0.0
		 * @return  cart items
		 */
		public function add_item_to_cart() {

			if ( isset( $_POST ) ) {
				$product_id     = apply_filters( 'woocommerce_add_to_cart_product_id', sanitize_text_field( $_POST['product_id'] ) );
				$quantity       = empty($_POST['quantity']) ? 1 : wc_stock_amount( sanitize_text_field( $_POST['quantity'] ) );
				$variation_id   = isset( $_POST['variation_id'] ) ? sanitize_text_field( $_POST['variation_id'] ) : '';
				$validation     = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
				$product_status = get_post_status( $product_id );

				if ( $validation && WC()->cart->add_to_cart( $product_id, $quantity, $variation_id ) && 'publish' === $product_status ) {
					do_action( 'woocommerce_ajax_added_to_cart', $product_id );
					wn_mini_cart();
				}

			}

			wp_die();
		}

		/**
		 * Add Parameters to login ajax
		 *
		 * @since   5.0.0
		 * @return  $localize_data
		 */
		public function login_ajax_parameters() {

			$localize_data = array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce('whb_nonce'),
			);

			return $localize_data;

		}

		/**
		 * login Ajax
		 *
		 * @since   5.0.0
		 * @return  $localize_data
		 */
		public function ajax_login() {

			// Nonce is checked, get the POST data and sign user on
			$info                  = array();
			$info['user_login']    = sanitize_text_field($_POST['username']);
			$info['user_password'] = sanitize_text_field($_POST['password']);
			$info['remember']      = true;

			$user_signon = wp_signon( $info, false );

			if ( ! is_wp_error( $user_signon ) ) {
				wp_set_current_user( $user_signon->ID );
				wp_set_auth_cookie( $user_signon->ID );
				echo json_encode(
					array(
						'loggedin' => true,
						'message'  => esc_html__(
							'Login successful, redirecting...',
							'deep'
						),
					)
				);
			} else {
				echo json_encode(
					array(
						'loggedin' => false,
						'message'  => $user_signon->errors,
					)
				);
			}

			die();
		}

	}

endif;
