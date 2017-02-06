<?php
/**
 * WooCommerce Force Subscribe Settings
 *
 * @author      ProntoTools
 * @category    Admin
 * @package     WooCommerce/Admin
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_Force_Subscribe_Setting' ) ) {

	/**
	 * WC_Force_Subscribe_Setting.
	 */
	class WC_Force_Subscribe_Setting {

		/**
		 * The WooCommerce settings tab name
		 *
		 * @since 1.0
		 */
		public static $tab_name = 'subscriptions';

		/**
		 * The prefix for subscription settings
		 *
		 * @since 1.0
		 */
		public static $option_prefix = 'woocommerce_force_subscribe';

		/**
		 * Constructor.
		 */
		public function __construct() {
			add_filter( 'woocommerce_subscription_settings', array( $this, 'add_settings' ) );
		}

		/**
		 * Get settings array.
		 *
		 * @return array
		 */
		public function add_settings( $settings ) {
			$args = array(
				'post_type'      => 'product',
				'post_per_page'  => -1
			);
			$subscription_product_query = new WP_Query( $args );
			$subscription_product_list = array();

			foreach ($subscription_product_query->posts as $product) {
				$subscription_product_list[ $product->ID ] = $product->post_title;
			}

			$force_subscribe_settings = array(
				array(
					'name'     => __( 'Force Subscribe Subscription Product', 'woocommerce-force-subscribe' ),
					'type'     => 'title',
					'desc'     => '',
					'id'       => self::$option_prefix . '_text',
				),
				array(
					'name'     => __( 'Force Subscribe Product', 'woocommerce-force-subscribe' ),
					'desc'     => __( 'A subscription product that will force when customer buy another product', 'woocommerce-force-subscribe' ),
					'tip'      => '',
					'id'       => self::$option_prefix . '_product',
					'css'      => 'min-width:150px;',
					'class'    => 'wc-enhanced-select',
					'type'     => 'select',
					'options'  => $subscription_product_list,
					'desc_tip' => true,
				),
				array( 
					'type'     => 'sectionend', 
					'id'       => self::$option_prefix . '_text' 
				)
			);

			$settings = array_merge( $settings, $force_subscribe_settings );

			return $settings;
		}

	}

	return new WC_Force_Subscribe_Setting();
}
