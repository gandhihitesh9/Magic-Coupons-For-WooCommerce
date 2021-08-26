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
        // Action links
        add_filter('plugin_action_links_' . MCW_BASENAME,  array($this, 'action_links'));
        // Settings
        add_filter('woocommerce_get_settings_pages', array($this, 'add_woocommerce_settings_tab'));
    }
}
