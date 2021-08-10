<?php
/**
 * Magic Coupons for WooCommerce - Admin
 *
 * @version 1.0.0
 * @author WpExperPlugins Ltd.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Magic_Coupon_Admin {

    /**
     *  Add Top Level Menu Page
     *
     * @package mcw
     * @since 1.0.0
     */
    // public function mcw_admin_menu() {
    //     add_menu_page(esc_html__('Category Carousel', "product-cat-carousel"), esc_html__('Procc Shortcode Generator', "product-cat-carousel"), 'manage_options', 'mcw-settings', array($this, "mcw_admin_html"));
    //     add_submenu_page("mcw-settings", esc_html__('Recently Sold Products', "product-cat-carousel"), esc_html__('Recently Sold Products', "product-cat-carousel"), 'manage_options', 'recently-sold-products-shortcode', array($this, "mcw_rsp_html"));

    // }

    /**
     *  Add Html page for setting page
     *
     * @package mcw
     * @since 1.0.0
     */
    public function mcw_admin_html() {
        require_once(WOOCC_DIR_PATH . '/includes/admin/html/mcw-settings-html.php');
    }
    /**
     * Adding Hooks
     *
     * @package mcw
     * @since 1.0.0
     */
    public function add_hooks() {
        add_action('admin_menu', array($this, "mcw_admin_menu"));
    }
}