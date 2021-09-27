<?php

/**
 * Live Chat from Customerly
 *
 *
 *
 * @wordpress-plugin
 * Plugin Name:       Live Chat - Customerly
 * Plugin URI:        https://www.customerly.io/?utm_medium=wp_plugin
 * Description:       The Live Chat with Super Powers is here. Add Free Live Chat to your WordPress and talk with your visitors, generate leads and increase sales.
 * Version:           2.3
 * Author:            Customerly
 * Author URI:        https://www.customerly.io/features/live-chat-plugin-for-wordpress/?utm_source=wordpress&utm_medium=plugin
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('CLY_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('CLY_INCLUDES_PATH', plugin_dir_path(__FILE__) . 'includes/');
define('CLY_PAGES_PATH', plugin_dir_path(__FILE__) . 'pages/');
define('CLY_ADMIN_JS_URL', plugin_dir_url(__FILE__) . 'assets/js/');
define('CLY_ADMIN_CSS_URL', plugin_dir_url(__FILE__) . 'assets/css/');


add_action('admin_enqueue_scripts', 'load_admin_scripts');
wp_enqueue_script('wp-deactivation-message', plugins_url('js/message.js', dirname(__FILE__)), array());


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
require_once CLY_INCLUDES_PATH . 'class.customerly.php';

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
    $customerly->version = '2.3';
    $customerly->plugin_basename = plugin_basename(__FILE__);
    $customerly->init();


}


cly_init_customerly();
