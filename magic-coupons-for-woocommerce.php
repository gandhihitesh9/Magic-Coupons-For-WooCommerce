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
    define("MCW_PLUGIN_NAME", "Magic Coupons for WooCommerce");
}
if (!defined('MCW_SLG_BASENAME')) {
    define('MCW_SLG_BASENAME', basename(MCW_DIR_PATH));
}
if (!defined('MCW_BASENAME')) {
    define('MCW_BASENAME', plugin_basename(__FILE__));
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
// Action to load plugin after the main plugin is loaded
add_action('plugins_loaded', 'mcw_load_textdomain');

global $magic_coupons_admin, $magic_coupons_public;

require_once(MCW_DIR_PATH . '/includes/admin/magic-coupons-admin.php');
$magic_coupons_admin = new Magic_Coupons_Admin();
$magic_coupons_admin->add_hooks();

require_once(MCW_DIR_PATH . '/includes/magic-coupons-public.php');
$magic_coupons_public = new Magic_Coupons_Public();
$magic_coupons_public->add_hooks();