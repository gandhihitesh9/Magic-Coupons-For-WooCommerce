<?php
/**
 * Plugin Name: Magic Coupons for WooCommerce
 * Plugin URI: http://www.wpexpertplugins.com/
 * Description: URL Coupons for Woocommerce.
 * Author: WpExpertPlugins
 * Text Domain: mcw
 * Domain Path: /languages/
 * WC tested up to: 
 * Version: 1.0.0
 * Author URI: http://www.wpexpertplugins.com/contact-us/
 *
 * @package mcw
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 * Basic plugin definitions
 *
 * @package mcw
 * @since 1.0.0
 */
if (!defined('MCW_DIR_PATH')) {
    define('MCW_DIR_PATH', dirname(__FILE__));      // Plugin dir
}

if (!defined('MCW_VERSION')) {
    define('MCW_VERSION', '1.0.0');      // Plugin Version
}
if (!defined('MCW_PLUGIN_URL')) {
    define('MCW_PLUGIN_URL', plugin_dir_url(__FILE__));   // Plugin url
}
if (!defined('MCW_INC_DIR_PATH')) {
    define('MCW_INC_DIR_PATH', MCW_DIR_PATH . '/includes');   // Plugin include dir
}
if (!defined('MCW_PREFIX')) {
    define('MCW_PREFIX', '_mcw'); // Plugin Prefix
}
if (!defined('MCW_VAR_PREFIX')) {
    define('MCW_VAR_PREFIX', '_mcw'); // Variable Prefix
}
if (!defined('MCW_PLUGIN_NAME')) {
    define("MCW_PLUGIN_NAME", "Product Category Carousel for WooCommerce");
}
if (!defined('MCW_SLG_BASENAME')) {
    define('MCW_SLG_BASENAME', basename(MCW_DIR_PATH));
}

/**
 * Check WooCommerce plugin is active
 *
 * @package mcw
 * @since 1.0.0
 */
function mcw_check_activation() {
    if (!class_exists('WooCommerce')) {
        // is this plugin active?
        if (is_plugin_active(plugin_basename(__FILE__))) {
            // deactivate the plugin
            deactivate_plugins(plugin_basename(__FILE__));
            // unset activation notice
            unset($_GET['activate']);
            // display notice
            add_action('admin_notices', 'mcw_admin_notices');
        }
    }
}

add_action('admin_init', 'mcw_check_activation');

/**
 * Admin notices
 *
 * @package mcw
 * @since 1.0.0
 */
function mcw_admin_notices() {
    if (!class_exists('WooCommerce')) {
        echo '<div class="error notice is-dismissible">';
        echo sprintf(esc_html__('%s recommends the following plugin to use. %s', "mcw"), "<p><strong>" . MCW_PLUGIN_NAME . "</strong>", "</p>");
        echo sprintf(esc_html__('%s WooCommerce %s', "mcw"), '<p><strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">', '</a> </strong></p>');
        echo '</div>';
    }
}

/**
 * Load the plugin after the main plugin is loaded.
 *
 * @package mcw
 * @since 1.0.0
 */
function mcw_load_plugin() {

    // Check main plugin is active or not
    if (class_exists('WooCommerce')) {

        /**
         * Load Text Domain
         *
         * This gets the plugin ready for translation.
         *
         * @package mcw
         * @since 1.0.0
         */
        function mcw_load_textdomain() {

            // Set filter for plugin's languages directory
            $mcw_slg_lang_dir = dirname(plugin_basename(__FILE__)) . '/languages/';
            $mcw_slg_lang_dir = apply_filters('mcw_languages_directory', $mcw_slg_lang_dir);

            // Traditional WordPress plugin locale filter
            $locale = apply_filters('plugin_locale', get_locale(), "mcw");
            $mofile = sprintf('%1$s-%2$s.mo', "mcw", $locale);

            // Setup paths to current locale file
            $mofile_local = $mcw_slg_lang_dir . $mofile;
            $mofile_global = WP_LANG_DIR . '/' . MCW_SLG_BASENAME . '/' . $mofile;

            if (file_exists($mofile_global)) { // Look in global /wp-content/languages/mcw folder
                load_textdomain("mcw", $mofile_global);
            } elseif (file_exists($mofile_local)) { // Look in local /wp-content/plugins/mcw/languages/ folder
                load_textdomain("mcw", $mofile_local);
            } else { // Load the default language files
                load_plugin_textdomain("mcw", false, $mcw_slg_lang_dir);
            }
        }

        // Action to load plugin text domain
        add_action('plugins_loaded', 'mcw_load_textdomain');

        /**
         * Function add some script and style
         *
         * @package mcw
         * @since 1.0.0
         */
        function mcw_style_css() {
            
            wp_enqueue_style('mcw-public-style', MCW_PLUGIN_URL . 'includes/assets/css/magic-coupons.css', array(), MCW_VERSION);

            // Public script
            wp_register_script('mcw-public-js', MCW_PLUGIN_URL . 'includes/assets/js/public.js', array('jquery'), MCW_VERSION, true);
        }

        /**
         * Function add scripts in admin side
         *
         * @package mcw
         * @since 1.0.0
         */
        function mcw_admin_scripts($hook) {
            if ($hook == "toplevel_page_mcw-settings") {
                wp_register_script('mcw-admin-script', MCW_PLUGIN_URL . 'includes/assets/js/admin.js', array('jquery'), MCW_VERSION, true);

                wp_enqueue_script("mcw-admin-script");
            }
        }


        // Action to add some style and script
        // add_action('wp_enqueue_scripts', 'mcw_style_css');
        // add_action('admin_enqueue_scripts', 'mcw_admin_scripts');
        
    }
}

/**
 * admin.
 *
 * @version 1.0.0
 * @since   1.0.0
 */
function admin() {
    // Action links
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),  'action_links' );
    // Settings
    add_filter( 'woocommerce_get_settings_pages', 'add_woocommerce_settings_tab' );
    // Version update
    if ( get_option( 'magic_coupons_for_woocommerce_version', '' ) !== MCW_VERSION ) {
        add_action( 'admin_init', 'version_updated' );
    }
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
function action_links( $links ) {
    $custom_links = array();
    $custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=magic_coupons' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
    if ( 'magic-coupons-for-woocommerce.php' === basename( __FILE__ ) ) {
        $custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpexpertplugins.com/item/magic-coupons-for-woocommerce/">' .
            __( 'Go Pro', 'magic-coupons-for-woocommerce' ) . '</a>';
    }
    return array_merge( $custom_links, $links );
}

/**
 * Add URL Coupons settings tab to WooCommerce settings.
 *
 * @version 1.0.0
 * @since   1.0.0
 */
function add_woocommerce_settings_tab( $settings ) {
    $settings[] = require_once( MCW_DIR_PATH .'/includes/admin/magic-coupons-settings.php' );
    return $settings;
}

/**
 * version_updated.
 *
 * @version 1.0.0
 * @since   1.0.0
 */
function version_updated() {
    update_option( 'alg_wc_url_coupons_version', MCW_VERSION );
}

// Action to load plugin after the main plugin is loaded
add_action('plugins_loaded', 'mcw_load_plugin', 15);

require_once(MCW_DIR_PATH . '/includes/magic-coupons-public.php');
$magic_coupons_public = new Magic_Coupons_Public();
$magic_coupons_public->add_hooks();

if ( is_admin() ) {
    admin();
}