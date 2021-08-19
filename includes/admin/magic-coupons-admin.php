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

class Magic_Coupons_Admin
{

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
    public function mcw_admin_html()
    {
        require_once(MCW_DIR_PATH . '/includes/admin/html/mcw-settings-html.php');
    }


    /**
     * Show action links on the plugin screen.
     *
     * @version 1.0.0
     * @since   1.0.0
     *
     * @param   mixed $links
     * @return  array
     */
    public function action_links($links)
    {
        $custom_links = array();
        $custom_links[] = '<a href="' . admin_url('admin.php?page=wc-settings&tab=magic_coupons') . '">' . __('Settings', 'woocommerce') . '</a>';

        return array_merge($custom_links, $links);
    }

    /**
     * Add URL Coupons settings tab to WooCommerce settings.
     *
     * @version 1.0.0
     * @since   1.0.0
     */
    public function add_woocommerce_settings_tab($settings)
    {
        $settings[] = require_once(MCW_DIR_PATH . '/includes/admin/magic-coupons-settings.php');
        return $settings;
    }

    /**
     * Adding Hooks
     *
     * @package mcw
     * @since 1.0.0
     */
    public function add_hooks()
    {
        // add_action('admin_menu', array($this, "mcw_admin_menu"));

        // Action links
        add_filter('plugin_action_links_' . MCW_BASENAME,  array($this, 'action_links'));
        // Settings
        add_filter('woocommerce_get_settings_pages', array($this, 'add_woocommerce_settings_tab'));
    }
}
