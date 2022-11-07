<?php

/**
 * Live Chat by Customerly
 *
 *
 *
 * @wordpress-plugin
 * Plugin Name:       Live Chat by Customerly - FREE Live Chat & Video Live Chat for WP
 * Plugin URI:        https://www.customerly.io/?utm_source=wordpress&utm_medium=plugin&utm_campaign=plugin_uri
 * Description:       Live Chat software to chat with real-time visitors, generate leads, and increase sales.
 * Version:           2.5
 * Author:            Customerly
 * Author URI:        https://www.customerly.io/features/live-chat-plugin-for-wordpress/?utm_source=wordpress&utm_medium=plugin&utm_campaign=plugin_author_uri
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('CUSTOMERLY_CHAT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('CUSTOMERLY_CHAT_INCLUDES_PATH', plugin_dir_path(__FILE__) . 'includes/');
define('CUSTOMERLY_CHAT_PAGES_PATH', plugin_dir_path(__FILE__) . 'pages/');
define('CUSTOMERLY_CHAT_ADMIN_JS_URL', plugin_dir_url(__FILE__) . 'assets/js/');
define('CUSTOMERLY_CHAT_ADMIN_CSS_URL', plugin_dir_url(__FILE__) . 'assets/css/');
const CUSTOMERLY_CHAT_API_BASE_URL = 'https://api.customerly.io/v1/';


add_action('admin_enqueue_scripts', 'load_admin_scripts');


add_action('plugins_loaded', 'customerly_textdomain');
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function customerly_textdomain()
{
    load_plugin_textdomain('customerly', false, basename(dirname(__FILE__)) . '/languages');
}

/**
 * The core plugin class
 */
require_once CUSTOMERLY_CHAT_INCLUDES_PATH . 'class.customerly.php';

/**
 * Activation and deactivation hooks
 *
 */



register_activation_hook(__FILE__, 'cly_activation_handler');
add_action('admin_init', 'cly_redirect');


register_deactivation_hook(__FILE__, 'cly_deactivation_handler');


function cly_deactivation_handler()
{
}

/**
 * Begins execution of the plugin.
 *
 * Init the plugin process
 *
 * @since    1.0.0
 */
function cly_init_customerly()
{
    global $customerly;

    $customerly = new Customerly();
    $customerly->version = '2.5';
    $customerly->plugin_basename = plugin_basename(__FILE__);
    $customerly->init();
}


cly_init_customerly();