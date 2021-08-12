<?php
/**
 * Magic Coupons for WooCommerce - Settings
 *
 * @version 1.0.0
 * @author WpExperPlugins Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(! class_exists('Magic_Coupons_Settings')):

class Magic_Coupons_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 1.0.0
     * @since 1.0.0
	 */
	function __construct() {
		$this->id    = 'mcw_url_coupons';
		$this->label = __( 'URL Coupons', 'magic-coupons-for-woocommerce' );
		parent::__construct();
		add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'custom_sanitize' ), PHP_INT_MAX, 3 );
		// Sections
		require_once( 'class-mcw-coupons-settings-section.php' );
		require_once( 'class-mcw-coupons-settings-general.php' );
	}

	/**
	 * custom_sanitize.
	 *
	 * @version 1.0.0
     * @since 1.0.0
	 */
	function custom_sanitize( $value, $option, $raw_value ) {
		if ( ! empty( $option['mcw_sanitize'] ) && function_exists( $option['mcw_sanitize'] ) ) {
			$func  = $option['mcw_sanitize'];
			$value = $func( $raw_value );
		}
		return $value;
	}

	/**
	 * get_settings.
	 *
	 * @version 1.0.0
     * @since 1.0.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge( apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), array(
			array(
				'title'     => __( 'Reset Settings', 'magic-coupons-for-woocommerce' ),
				'type'      => 'title',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
			array(
				'title'     => __( 'Reset section settings', 'magic-coupons-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Reset', 'magic-coupons-for-woocommerce' ) . '</strong>',
				'desc_tip'  => __( 'Check the box and save changes to reset.', 'magic-coupons-for-woocommerce' ),
				'id'        => $this->id . '_' . $current_section . '_reset',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
		) );
	}

	/**
	 * reset_settings_based_on_option.
	 *
	 * @version 1.0.0
     * @since 1.0.0
	 */
	function reset_settings_based_on_option() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			if ( method_exists( 'WC_Admin_Settings', 'add_message' ) ) {
				WC_Admin_Settings::add_message( __( 'Your settings have been reset.', 'magic-coupons-for-woocommerce' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'admin_notice_settings_reset' ) );
			}
		}
	}

	/**
	 * admin_notice_settings_reset.
	 *
	 * @version 1.0.0
     * @since 1.0.0
	 */
	function admin_notice_settings_reset() {
		echo '<div class="notice notice-warning is-dismissible"><p><strong>' .
			__( 'Your settings have been reset.', 'magic-coupons-for-woocommerce' ) . '</strong></p></div>';
	}

	/**
	 * Save settings.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 */
	function save() {
		parent::save();
		$this->reset_settings_based_on_option();
	}


	/**
	 * Add Hooks.
	 *
	 * @version 1.0.0
	 * @since 1.0.0
	 */
	// function add_hooks() {
	// 	add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'custom_sanitize' ), PHP_INT_MAX, 3 );
	// 	// Sections
	// 	require_once( 'class-mcw-coupons-settings-section.php' );
	// 	require_once( 'class-mcw-coupons-settings-general.php' );
	// }

}
endif;

return new Magic_Coupons_Settings();