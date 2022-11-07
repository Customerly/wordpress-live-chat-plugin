<?php


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Customerly_admin
{

    public function __construct()
    {

        $this->textdomain = 'customerly';

        $this->admin_notices = new CLY_Admin_notices();

        add_action('admin_init', array($this, 'loadSettings'));

        add_action('admin_menu', array($this, 'customerly_add_admin_menu'));

        register_setting('pluginPage', 'customerly_settings');

        $this->verify_install();

        if (is_admin()) {
            // for Admin Dashboard Only
            // Embed the Script on our Plugin's Option Page Only
            $page = sanitize_text_field($_GET['page']);
            if (isset($page) && $page == 'Customerly') {
                wp_enqueue_script('jquery');
                wp_enqueue_script('jquery-form');
            }
        }


    }

    function loadSettings()
    {

        add_settings_field(
            'customerly_text_field_appid',
            __('Application ID', 'customerly.io'),
            array($this, 'customerly_text_field_appid_render'),
            'pluginPage',
            'customerly_pluginPage_section'
        );


        add_settings_field(
            'customerly_text_field_appkey',
            __('Application Access Token', 'customerly.io'),
            array($this, 'customerly_text_field_appkey_render'),
            'pluginPage',
            'customerly_pluginPage_section'
        );

    }


    function customerly_text_field_appid_render()
    {
        $options = get_option('customerly_settings');
        $appid = sanitize_text_field($_GET['appid']);

        if (!isset($appid) && isset($options['customerly_text_field_appid'])) {
            $appid = $options['customerly_text_field_appid'];
        }

        ?>
        <input id="appID" type='text' name='customerly_settings[customerly_text_field_appid]'
               style="display: none"
               value='<?php echo esc_attr($appid); ?>'>
        <?php
    }


    function customerly_options_page()
    {
        include_once(CUSTOMERLY_CHAT_INCLUDES_PATH . "/headers.php");
        ?>
        <form id="customerlySettings" action='options.php' method='post' style="display: none">
            <?php

            settings_fields('pluginPage');
            do_settings_sections('pluginPage');

            $this->customerly_text_field_appid_render();
            ?>
        </form>
        <?php
            if ($this->customerly_is_configured()) {
                include_once(CUSTOMERLY_CHAT_PAGES_PATH . "/configured.php");
            } else {
                include_once(CUSTOMERLY_CHAT_PAGES_PATH . "/welcome.php");
            }
        ?>
        <?php
    }




    //Function that add Customerly Menu on the left sidebar
    // Will add a notification if is not setup yet
    public function customerly_add_admin_menu()
    {
        add_menu_page('Customerly',
            $this->customerly_is_configured() ? 'Customerly' : 'Live Chat <span class="awaiting-mod">1</span>',
            'manage_options',
            'Customerly',
            array($this, 'customerly_options_page'),
            plugins_url('../assets/img/blue_fill_notification.svg', __FILE__),
            3);

        if ($this->customerly_is_configured()) {
            add_submenu_page('Customerly', 'Live Chat Triggers', '<div class="dashicons dashicons-star-filled"></div> Get more clients', 'manage_options', 'chat-triggers', 'customerly_chat_triggers');
            add_submenu_page('Customerly', 'Newsletters', '<div class="dashicons dashicons-email"></div> Send Newsletters', 'manage_options', 'newsletter', 'customerly_newsletter');
            add_submenu_page('Customerly', 'Workflows', '<div class="dashicons dashicons-networking"></div> Workflows', 'manage_options', 'workflow', 'customerly_workflow');
            add_submenu_page('Customerly', 'CRM', '<div class="dashicons dashicons-businessman"></div> CRM', 'manage_options', 'crm', 'customerly_crm');
            add_submenu_page('Customerly', 'Live Chat Mobile App', '<div class="dashicons dashicons-smartphone"></div> Download App', 'manage_options', 'mobileapp', 'customerly_download_app');
            //add_submenu_page('Customerly', 'Live Chat Integrations', '<div class="dashicons dashicons-buddicons-pm"></div> Integrations', 'manage_options', 'integrations', 'cutomerly_integrations');
        }
        global $menu;

    }


    /*
     * Function that check if customerly has been configured with an appid
     */
    public function customerly_is_configured()
    {
        $options = get_option('customerly_settings');
        //IF is not configured return false

        if (!isset($options['customerly_text_field_appid']) || strlen($options['customerly_text_field_appid']) < 8) {
            if (isset($_GET['appid'])){
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * Check if the user has created or connected a customerly account
     * @return [type] [description]
     */
    public function verify_install()
    {
        if (!$this->customerly_is_configured() &&
            (isset($_GET['page']) && $_GET['page'] != "Customerly")
            || (!isset($_GET['page']) && !$this->customerly_is_configured())) {
            add_action('admin_notices', array($this, 'show_install_notice'));
        }

    }

    public function show_install_notice()
    {

        echo '<div class="notice notice-warning is-dismissible">
             <p>Your Live Chat is not ready yet. <a href="' . admin_url("admin.php?page=Customerly&utm_source=wordpress&utm_campaign=topbanner") . '">Follow these instructions</a> to go live and get more customers. </p>
         </div>';
    }
}


/*
 * Function that redirect people on Customerly Admin when activated
 */
function cly_activation_handler() {
    add_option('cly_do_activation_redirect', true);
}

function cly_redirect() {
    if (get_option('cly_do_activation_redirect', false)) {
        delete_option('cly_do_activation_redirect');
        wp_redirect(admin_url( 'admin.php?page=Customerly' ));
    }
}



/*
 * Function that add a link in the description of the plugin list
 */
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'plugin_add_settings_link');

function plugin_add_settings_link($links)
{
    $settings_link = '<a href="admin.php?page=Customerly&utm_source=wordpress&utm_campaign=pluginlisthowto"> How to go live?</a>';
    array_unshift($links, $settings_link);
    return $links;
}

/**
 * load admin scripts and styles
 * @return [type] [description]
 */
function load_admin_scripts()
{

    wp_register_style('cly-admin-css', CUSTOMERLY_CHAT_ADMIN_CSS_URL . 'customerly.css', false, '1.0.0');

    wp_enqueue_style('cly-admin-css');

    wp_register_script('cly-admin-script', CUSTOMERLY_CHAT_ADMIN_JS_URL . 'main.js', array('jquery'), '1.0.0', true);

    wp_enqueue_script('cly-admin-script');

}

/*
 * Function that add warning error notice when is not configured
 */

global $pagenow;


function customerly_download_app()
{
    include_once(CUSTOMERLY_CHAT_PAGES_PATH . "/mobile.php");
}

function customerly_chat_triggers()
{
    include_once(CUSTOMERLY_CHAT_PAGES_PATH . "/chat-triggers.php");
}

function customerly_newsletter()
{
    include_once(CUSTOMERLY_CHAT_PAGES_PATH . "/newsletter.php");
}

function customerly_workflow()
{
    include_once(CUSTOMERLY_CHAT_PAGES_PATH . "/workflow.php");
}

function customerly_crm()
{
    include_once(CUSTOMERLY_CHAT_PAGES_PATH . "/crm.php");
}

function cutomerly_integrations()
{
    include_once(CUSTOMERLY_CHAT_PAGES_PATH . "/integrations.php");
}


?>