<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_URL_Coupons_Settings_General' ) ) :

class Magic_Coupons_General_Settings extends Magic_Coupons_Settings {

/**
	 * Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'magic-coupons-for-woocommerce' );
		parent::__construct();
	}

    function get_settings() {

		$main_settings = array(
			array(
				'title'    => __( 'URL Coupons Options', 'magic-coupons-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'magic_coupons_options',
			),
			array(
				'title'    => __( 'URL Coupons', 'magic-coupons-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable plugin', 'magic-coupons-for-woocommerce' ) . '</strong>',
				'id'       => 'magic_coupons_enabled',
				'default'  => 'yes',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'magic_coupons_options',
			),
		);

		$general_settings = array(
			array(
				'title'    => __( 'General Options', 'magic-coupons-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'magic_coupons_general_options',
			),
			array(
				'title'    => __( 'URL coupons key', 'magic-coupons-for-woocommerce' ),
				'desc_tip' => __( 'URL key. If you change this, make sure it\'s unique and is not used anywhere on your site (e.g. by another plugin).', 'magic-coupons-for-woocommerce' ),
				'desc'     => '<p>' . sprintf( __( 'Your customers can apply shop\'s standard coupons by visiting URL. E.g.: %s.', 'magic-coupons-for-woocommerce' ),
					'<code>' . site_url() . '/?' . '<strong>' . get_option( 'magic_coupons_key', 'apply_magic_coupon' ) . '</strong>' . '=couponcode' . '</code>' ) . '</p>',
				'id'       => 'magic_coupons_key',
				'default'  => 'apply_magic_coupon',
				'type'     => 'text',
			),
			array(
				'title'    => __( 'Delay coupon', 'magic-coupons-for-woocommerce' ),
				'desc'     => __( 'Enable', 'magic-coupons-for-woocommerce' ),
				'desc_tip' => __( 'Delay applying the coupon until some product is added to the cart.', 'magic-coupons-for-woocommerce' ),
				'id'       => 'magic_coupons_delay_coupon',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'desc'     => __( 'Success notice', 'magic-coupons-for-woocommerce' ),
				'desc_tip' => __( 'Ignored if empty.', 'magic-coupons-for-woocommerce' ) . ' ' .
					sprintf( __( 'Available placeholder(s): %s.', 'magic-coupons-for-woocommerce' ), '%coupon_code%' ),
				'id'       => 'magic_coupons_delay_coupon_notice[success]',
				'default'  => __( 'Coupon code applied successfully.', 'magic-coupons-for-woocommerce' ),
				'type'     => 'textarea',
				'css'      => 'width:100%;',
			),
			array(
				'desc'     => __( 'Error notice', 'magic-coupons-for-woocommerce' ) . ': ' .
					__( 'Coupon already applied', 'magic-coupons-for-woocommerce' ),
				'desc_tip' => __( 'Ignored if empty.', 'magic-coupons-for-woocommerce' ) . ' ' .
					sprintf( __( 'Available placeholder(s): %s.', 'magic-coupons-for-woocommerce' ), '%coupon_code%' ),
				'id'       => 'magic_coupons_delay_coupon_notice[error_applied]',
				'default'  => __( 'Coupon code already applied!', 'magic-coupons-for-woocommerce' ),
				'type'     => 'textarea',
				'css'      => 'width:100%;',
			),
			array(
				'desc'     => __( 'Error notice', 'magic-coupons-for-woocommerce' ) . ': ' .
					__( 'Coupon does not exist', 'magic-coupons-for-woocommerce' ),
				'desc_tip' => __( 'Ignored if empty.', 'magic-coupons-for-woocommerce' ) . ' ' .
					sprintf( __( 'Available placeholder(s): %s.', 'magic-coupons-for-woocommerce' ), '%coupon_code%' ),
				'id'       => 'magic_coupons_delay_coupon_notice[error_not_found]',
				'default'  => __( 'Coupon "%coupon_code%" does not exist!', 'magic-coupons-for-woocommerce' ),
				'type'     => 'textarea',
				'css'      => 'width:100%;',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'magic_coupons_general_options',
			),
		);

		$hide_coupon_settings = array(
			array(
				'title'    => __( 'Hide Coupon Options', 'magic-coupons-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'magic_coupons_hide_coupon_options',
			),
			array(
				'title'    => __( 'Hide coupon on cart page', 'magic-coupons-for-woocommerce' ),
				'desc'     => __( 'Hide', 'magic-coupons-for-woocommerce' ),
				'desc_tip' => __( 'Enable this if you want to hide standard coupon input field on the cart page.', 'magic-coupons-for-woocommerce' ),
				'id'       => 'magic_coupons_cart_hide_coupon',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'title'    => __( 'Hide coupon on checkout page', 'magic-coupons-for-woocommerce' ),
				'desc'     => __( 'Hide', 'magic-coupons-for-woocommerce' ),
				'desc_tip' => __( 'Enable this if you want to hide standard coupon input field on the checkout page.', 'magic-coupons-for-woocommerce' ),
				'id'       => 'magic_coupons_checkout_hide_coupon',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'magic_coupons_hide_coupon_options',
			),
		);

		$notice_settings = array(
			array(
				'title'    => __( 'Notice Options', 'magic-coupons-for-woocommerce' ),
				'desc'     => apply_filters( 'magic_coupons_settings',''),
				'type'     => 'title',
				'id'       => 'magic_coupons_notice_options',
			),
			array(
				'title'    => __( 'Delay notice', 'magic-coupons-for-woocommerce' ),
				'desc'     => __( 'Delay', 'magic-coupons-for-woocommerce' ),
				'desc_tip' => __( 'Delay the "Coupon code applied successfully" notice if the cart is empty when applying a URL coupon.', 'magic-coupons-for-woocommerce' ) . ' ' .
					__( 'Notice will be delayed until there is at least one product in the cart.', 'magic-coupons-for-woocommerce' ),
				'id'       => 'magic_coupons_delay_notice',
				'default'  => 'no',
				'type'     => 'checkbox',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'magic_coupons_notice_options',
			),
		);

		$notes = array(
			array(
				'title'    => __( 'Notes', 'magic-coupons-for-woocommerce' ),
				'desc'     => '<span class="dashicons dashicons-info"></span> ' .
					sprintf( __( 'If you are using URL to a page where no WooCommerce notices are displayed, try adding our %s shortcode to the content.', 'magic-coupons-for-woocommerce' ),
						'<code>[magic_coupons_print_notices]</code>' ) . ' ' .
					__( 'Please note that this shortcode will print all WooCommerce notices (i.e. not only from our plugin, or notices related to the coupons).', 'magic-coupons-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'magic_coupons_notes',
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'magic_coupons_notes',
			),
		);

		return array_merge( $main_settings, $general_settings, $hide_coupon_settings, $notice_settings, $notes );
	}

}
endif;

return new Magic_Coupons_General_Settings();