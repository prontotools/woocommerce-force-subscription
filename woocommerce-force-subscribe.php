<?php
/*
Plugin Name: WooCommerce Force Subscribe
Plugin URI: https://github.com/prontotools/
Description: Allow you to select what subscription product to added when client buy a another product.
Version: 1.0.0
Author: Pronto Tools
Author URI: http://www.prontotools.io
*/


if ( ! class_exists( 'WC_force_Subscribe' ) && ! class_exists( 'WC_Subscriptions' ) ) {

	require_once( 'includes/class-wc-force-subscribe-settings.php' );

	class WC_force_Subscribe {
		/**
		 * The prefix for force sell subscription settings
		 *
		 * @since 1.0
		 */
		public static $option_prefix = 'woocommerce_force_subscribe';

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			add_action( 'woocommerce_add_to_cart', array( $this, 'force_add_subscription' ), 11, 6 );
		}

		public function force_add_subscription( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
			$subscription_product_id = get_option( self::$option_prefix . '_product' );
			$cart_contents = WC()->cart->get_cart();
			$force_add = true;
			if ( is_user_logged_in() ) {
				$current_user   = wp_get_current_user();
				$user_id = $current_user->ID;
				$has_subscriptions = wcs_user_has_subscription( $user_id, $subscription_product_id, 'active' );
				if ( $has_subscriptions ) {
					$force_add = false;
				}
			}
			foreach ($cart_contents as $key => $value) {
				if ( $value['product_id'] == $subscription_product_id ) {
					$force_add = false;
				}
			}
			if ( $force_add ) {
				$result = WC()->cart->add_to_cart( $subscription_product_id, 1);
			}
		}

	}

	return new WC_force_Subscribe();

}