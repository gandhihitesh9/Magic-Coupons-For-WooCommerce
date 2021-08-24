<?php

/**
 * Magic Coupons for WooCommerce - Public Class
 *
 * @version 1.5.4
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Magic_Coupons_Public' ) ) :

class Magic_Coupons_Public {


    /**
	 * translate_shortcode.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function translate_shortcode( $atts, $content = '' ) {
		// E.g.: `[magic_coupons_translate lang="EN,DE" lang_text="Text for EN & DE" not_lang_text="Text for other languages"]`
		if ( isset( $atts['lang_text'] ) && isset( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ?
				$atts['not_lang_text'] : $atts['lang_text'];
		}
		// E.g.: `[magic_coupons_translate lang="EN,DE"]Text for EN & DE[/magic_coupons_translate][magic_coupons_translate not_lang="EN,DE"]Text for other languages[/magic_coupons_translate]`
		return (
			( ! empty( $atts['lang'] )     && ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     defined( 'ICL_LANGUAGE_CODE' ) &&   in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ) ) )
		) ? '' : $content;
	}

    /**
     * apply_delayed_coupon
     * @version 1.0.0
     * @since 1.0.0
     */
    function apply_delayed_coupon( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
		$coupons = WC()->session->get( 'magic_coupons', array() );
		if ( ! empty( $coupons ) ) {
			WC()->session->set( 'magic_coupons', null );
			$skip_coupons = array();
			$key          = get_option( 'magic_coupons_key', 'alg_apply_coupon' );
			foreach ( $coupons as $coupon_code ) {
				if (
					'yes' === get_option( 'magic_coupons_delay_coupon_check_product', 'no' ) &&
					( $product = wc_get_product( $variation_id ? $variation_id : $product_id ) ) &&
					( $coupon_id = wc_get_coupon_id_by_code( $coupon_code ) ) && ( $coupon = new WC_Coupon( $coupon_id ) ) &&
					$coupon->is_type( 'fixed_product' ) && ! $coupon->is_valid_for_product( $product )
				) {
					$skip_coupons[] = $coupon_code;
				} else {
					$result = $this->apply_coupon( $coupon_code, $key );
				}
			}
			if ( ! empty( $skip_coupons ) ) {
				WC()->session->set( 'magic_coupons', $skip_coupons );
			}
		}
	}

    /**
	 * hide_coupon_field_on_checkout.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function hide_coupon_field_on_checkout( $enabled ) {
		return ( is_checkout() ? false : $enabled );
	}

	/**
	 * hide_coupon_field_on_cart.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function hide_coupon_field_on_cart( $enabled ) {
		return ( is_cart() ? false : $enabled );
	}

	/**
	 * delay_notice.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 */
	function delay_notice( $coupon_code, $key, $result ) {
		if ( ! $result ) {
			return;
		}
		if ( WC()->cart->is_empty() ) {
			$all_notices = WC()->session->get( 'wc_notices', array() );
			wc_clear_notices();
			WC()->session->set( 'magic_coupons_notices', $all_notices );
		}
	}

	/**
	 * display_delayed_notice.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function display_delayed_notice() {
		if ( function_exists( 'WC' ) && ! WC()->cart->is_empty() && ( $notices = WC()->session->get( 'magic_coupons_notices', array() ) ) && ! empty( $notices ) ) {
			WC()->session->set( 'magic_coupons_notices', null );
			WC()->session->set( 'wc_notices', $notices );
		}
	}


	/**
	 * apply_url_coupon.
	 *
	 * for e.g. http://yourwebsite.com/?mcw_apply_coupon=couponcode
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */

	function apply_url_coupon() {
		$key = get_option( 'magic_coupons_key', 'mcw_apply_coupon' );
		if ( isset( $_GET[ $key ] ) && '' !== $_GET[ $key ] && function_exists( 'WC' ) ) {
			$coupon_code = sanitize_text_field( $_GET[ $key ] );
			do_action( 'magic_coupons_before_coupon_applied', $coupon_code, $key );
			if ( 'yes' === get_option( 'magic_coupons_delay_coupon', 'no' ) ) {
				// Delay coupon
				$result  = false;
				$notices = get_option( 'magic_coupons_delay_coupon_notice', array() );
				$notices = array_map( 'do_shortcode', $notices );
				if ( ! WC()->cart->has_discount( $coupon_code ) ) {
					if ( wc_get_coupon_id_by_code( $coupon_code ) ) {
						$coupons = WC()->session->get( 'magic_coupons', array() );
						$coupons[] = $coupon_code;
						WC()->session->set( 'magic_coupons', array_unique( $coupons ) );
						$notice = ( isset( $notices['success'] ) ? $notices['success'] : __( 'Coupon code applied successfully.', 'magic-coupons-for-woocommerce' ) );
						if ( '' != $notice ) {
							wc_add_notice( str_replace( '%coupon_code%', $coupon_code, $notice ) );
						}
						$result = true;
					} else {
						$notice = ( isset( $notices['error_not_found'] ) ? $notices['error_not_found'] : __( 'Coupon "%coupon_code%" does not exist!', 'magic-coupons-for-woocommerce' ) );
						if ( '' != $notice ) {
							wc_add_notice( str_replace( '%coupon_code%', $coupon_code, $notice ), 'error' );
						}
					}
				} else {
					$notice = ( isset( $notices['error_applied'] ) ? $notices['error_applied'] : __( 'Coupon code already applied!', 'magic-coupons-for-woocommerce' ) );
					if ( '' != $notice ) {
						wc_add_notice( str_replace( '%coupon_code%', $coupon_code, $notice ), 'error' );
					}
				}
			} else {
				// Apply coupon
				$result = $this->apply_coupon( $coupon_code, $key );
			}
			do_action( 'magic_coupons_after_coupon_applied', $coupon_code, $key, $result );
		}
	}


    /**
	 * apply_coupon.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 */
	function apply_coupon( $coupon_code, $key ) {
		$result = WC()->cart->add_discount( $coupon_code );
		do_action( 'magic_coupons_coupon_applied', $coupon_code, $key, $result );
		return $result;
	}


	/**
	 * add_hooks
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
    public function add_hooks(){
        if ( 'yes' === get_option( 'magic_coupons_enabled', 'yes' ) ) {
			// Apply URL coupon
			add_action( 'wp_loaded', array( $this, 'apply_url_coupon' ), ( '' !== ( $priority = get_option( 'magic_coupons_priority', '' ) ) ? $priority : PHP_INT_MAX ) );
			// Delay coupon
			if ( 'yes' === get_option( 'magic_coupons_delay_coupon', 'no' ) ) {
				add_action( 'woocommerce_add_to_cart', array( $this, 'apply_delayed_coupon' ), PHP_INT_MAX, 6 );
			}
			// Delay notice
			if ( 'yes' === get_option( 'magic_coupons_delay_notice', 'no' ) ) {
				add_action( 'magic_coupons_coupon_applied', array( $this, 'delay_notice' ), 10, 3 );
				add_action( 'wp_head', array( $this, 'display_delayed_notice' ) );
			}
			add_action( 'magic_coupons_after_coupon_applied', array( $this, 'redirect' ), PHP_INT_MAX, 3 );
			// Hide coupons
			if ( 'yes' === get_option( 'magic_coupons_cart_hide_coupon', 'no' ) ) {
				add_filter( 'woocommerce_coupons_enabled', array( $this, 'hide_coupon_field_on_cart' ), PHP_INT_MAX );
			}
			if ( 'yes' === get_option( 'magic_coupons_checkout_hide_coupon', 'no' ) ) {
				add_filter( 'woocommerce_coupons_enabled', array( $this, 'hide_coupon_field_on_checkout' ), PHP_INT_MAX );
			}
			// WP Rocket: Disable empty cart caching
			if ( 'yes' === get_option( 'magic_coupons_wp_rocket_disable_cache_wc_empty_cart', 'no' ) ) {
				add_filter( 'rocket_cache_wc_empty_cart', '__return_false', PHP_INT_MAX );
			}
			// Shortcodes
			add_shortcode( 'magic_coupons_translate', array( $this, 'translate_shortcode' ) );
		}
    }


}
endif;
