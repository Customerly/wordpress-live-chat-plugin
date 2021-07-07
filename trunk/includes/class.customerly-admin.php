<?php


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Customerly_admin
{


    /**
     * Holds the plugin options
     * @var [type]
     */
    private $options;

    /**
     * Holds athe admin notices class
     * @var [CLY_Admin_notices]
     */
    private $admin_notices;

    /**
     * PLugn is active or not
     */
    private $plugin_active;
    /**
     * API errors array
     * @var [type]
     */
    private $api_errors;

    public function __construct()
    {

        $this->textdomain = 'customerly';

        $this->admin_notices = new CLY_Admin_notices();

        $this->api_errors = array();

        $this->register_hooks();

        add_action('admin_init', array($this, 'loadSettings'));

        add_action('admin_menu', array($this, 'customerly_add_admin_menu'));

        register_setting('pluginPage', 'customerly_settings');

        $this->verify_install();

        if (is_admin()) {
            // for Admin Dashboard Only
            // Embed the Script on our Plugin's Option Page Only
            if (isset($_GET['page']) && $_GET['page'] == 'Customerly') {
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
        $appid = "";
        if (isset($_GET['appid'])) {
            $appid = $_GET['appid'];
        } else {
            if (isset($options['customerly_text_field_appid'])) {
                $appid = $options['customerly_text_field_appid'];
            }
        }

        ?>
        <input id="appID" type='text' name='customerly_settings[customerly_text_field_appid]' style="display: none"
               value='<?php echo $appid; ?>'>

        <?php
    }

    function customerly_text_field_session_token_render()
    {
        $options = get_option('customerly_settings');
        $token = "";
        if (isset($options['customerly_text_field_session_token'])) {
            $token = $options['customerly_text_field_session_token'];
        }
        ?>
        <input id="sessionToken" type='hidden'
               name='customerly_settings[customerly_text_field_session_token]'
               value='<?php echo $token; ?>'>

        <?php
    }


    function customerly_text_field_appkey_render()
    {
        $options = get_option('customerly_settings');
        $appkey = "";
        if (isset($_GET['appkey'])) {
            $appkey = $_GET['appkey'];
        } else {
            if (isset($options['customerly_text_field_appkey'])) {
                $appkey = $options['customerly_text_field_appkey'];
            }
        }
        ?>
        <input class="integration-field" id="appkey" type='text'
               name='customerly_settings[customerly_text_field_appkey]'
               value='<?php echo $appkey; ?>'>

        <?php
    }


    function customerly_options_page()
    {
        include_once(CLY_INCLUDES_PATH . "/headers.php");
        ?>


        <form id="customerlySettings" action='options.php' method='post' style="display: none">


            <?php

            settings_fields('pluginPage');
            do_settings_sections('pluginPage');

            $this->customerly_text_field_session_token_render();
            $this->customerly_text_field_appid_render();
            $this->customerly_text_field_appkey_render();
            ?>

        </form>

        <?php

        if ($this->customerly_is_configured()) {
            include_once(CLY_PAGES_PATH . "/configured.php");
        } else {
            include_once(CLY_PAGES_PATH . "/welcome.php");

        }
        ?>
        <?php
    }




    //Function that add Customerly Menu on the left sidebar
    // Will add a notification if is not setup yet
    public function customerly_add_admin_menu()
    {
        add_menu_page('Customerly',
            $this->customerly_is_configured() ? 'Live Chat' : 'Live Chat <span class="awaiting-mod">1</span>',
            'manage_options',
            'Customerly',
            array($this, 'customerly_options_page'),
            plugins_url('../assets/img/blue_fill_notification.svg', __FILE__),
            3);

        if ($this->customerly_is_configured()) {
            add_submenu_page('Customerly', 'Live Chat PRO Features', '<div class="dashicons dashicons-star-filled"></div> PRO Features', 'manage_options', 'profeatures', 'customerly_pro');
            add_submenu_page('Customerly', 'Live Chat Mobile App', '<div class="dashicons dashicons-smartphone"></div> Download App', 'manage_options', 'mobileapp', 'customerly_download_app');
            add_submenu_page('Customerly', 'Live Chat Integrations', '<div class="dashicons dashicons-buddicons-pm"></div> Integrations', 'manage_options', 'integrations', 'cutomerly_integrations');
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
            if (isset($_GET['appid'])) {
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


    /**
     * Check if contact form 7 is active
     * @return [type] [description]
     */
    public function verify_dependencies()
    {
        if (!is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
            $notice = array(
                'id' => 'cf7-not-active',
                'type' => 'warning',
                'notice' => __('CF7 Not Active', $this->textdomain),
                'dismissable_forever' => false
            );

            $this->admin_notices->wp_add_notice($notice);
        }
    }

    /**
     * Registers the required admin hooks
     * @return [type] [description]
     */
    public function register_hooks()
    {
        /**
         * Check if required plugins are active
         * @var [type]
         */
        //add_action('admin_init', array($this, 'verify_dependencies'));

        /*before sending email to user actions */
        add_action('wpcf7_before_send_mail', array($this, 'cly_cf7_send_data_to_api'));

        /* adds another tab to contact form 7 screen */
        add_filter("wpcf7_editor_panels", array($this, "add_integrations_tab"), 1, 1);

        /* actions to handle while saving the form */
        add_action("wpcf7_save_contact_form", array($this, "cly_save_contact_form_details"), 10, 1);

        add_filter("wpcf7_contact_form_properties", array($this, "add_sf_properties"), 10, 2);
    }

    /**
     * Sets the form additional properties
     * @param [type] $properties   [description]
     * @param [type] $contact_form [description]
     */
    function add_sf_properties($properties, $contact_form)
    {

        //add mail tags to allowed properties
        $properties["wpcf7_api_data"] = isset($properties["wpcf7_api_data"]) ? $properties["wpcf7_api_data"] : array();
        $properties["wpcf7_api_data_map"] = isset($properties["wpcf7_api_data_map"]) ? $properties["wpcf7_api_data_map"] : array();
        $properties["template"] = isset($properties["template"]) ? $properties["template"] : '';
        $properties["json_template"] = isset($properties["json_template"]) ? $properties["json_template"] : '';

        return $properties;
    }

    /**
     * Adds a new tab on conract form 7 screen
     * @param [type] $panels [description]
     */
    function add_integrations_tab($panels)
    {

        $integration_panel = array(
            'title' => __('Customerly Email Marketing', $this->textdomain),
            'callback' => array($this, 'wpcf7_integrations')
        );

        $panels["cly-cf7-api-integration"] = $integration_panel;

        return $panels;

    }

    /**
     * Collect the mail tags from the form
     * @return [type] [description]
     */
    function get_mail_tags($post)
    {
        $tags = apply_filters('cly_cf7_collect_mail_tags', $post->scan_form_tags());

        foreach ((array)$tags as $tag) {
            $type = trim($tag['type'], ' *');
            if (empty($type) || empty($tag['name'])) {
                continue;
            } elseif (!empty($args['include'])) {
                if (!in_array($type, $args['include'])) {
                    continue;
                }
            } elseif (!empty($args['exclude'])) {
                if (in_array($type, $args['exclude'])) {
                    continue;
                }
            }
            $mailtags[] = $tag;
        }

        return $mailtags;
    }

    /**
     * The admin tab display, settings and instructions to the admin user
     * @param  [type] $post [description]
     * @return [type]       [description]
     */
    function wpcf7_integrations($post)
    {

        $wpcf7_api_data = $post->prop('wpcf7_api_data');
        $wpcf7_api_data_map = $post->prop('wpcf7_api_data_map');
        $mail_tags = $this->get_mail_tags($post);

        $wpcf7_api_data["send_to_api"] = isset($wpcf7_api_data["send_to_api"]) ? $wpcf7_api_data["send_to_api"] : '';
        $wpcf7_api_data["debug_log"] = true;

        $debug_url = get_post_meta($post->id(), 'cly_cf7_api_debug_url', true);
        $debug_result = get_post_meta($post->id(), 'cly_cf7_api_debug_result', true);
        $debug_params = get_post_meta($post->id(), 'cly_cf7_api_debug_params', true);

        $error_logs = get_post_meta($post->id(), 'api_errors', true);

        ?>


        <h2><?php echo esc_html(__('Customerly Email Marketing', $this->textdomain)); ?></h2>

        <p>By passing your leads to Customerly you can easily create <a
                    href="https://www.customerly.io/features/audience-segmentation/?utm_source=wordpress&utm_medium=plugin&utm_campaign=cf7_integration"
                    target="_blank">Lists</a> , <a
                    href="https://www.customerly.io/features/email-marketing/?utm_source=wordpress&utm_medium=plugin&utm_campaign=cf7_integration"
                    target="_blank">Newsletters</a> and <a
                    href="https://www.customerly.io/features/marketing-funnel/?utm_source=wordpress&utm_medium=plugin&utm_campaign=cf7_integration"
                    target="_blank">Funnels</a> to keep
            everything
            organized in the same place. From Live Chat to Email Marketing you can count on Customerly. </p>
        <fieldset>
            <?php do_action('before_base_fields', $post); ?>

            <div class="cf7_row">

                <label for="wpcf7-sf-send_to_api">
                    <input type="checkbox" id="wpcf7-sf-send_to_api"
                           name="wpcf7-sf[send_to_api]" <?php checked($wpcf7_api_data["send_to_api"], "on"); ?>/>
                    <?php _e('Send lead to Customerly?', $this->textdomain); ?>
                </label>

            </div>

            <?php do_action('after_base_fields', $post); ?>

        </fieldset>


        <fieldset data-clyindex="params">
            <div class="cf7_row">
                <h2><?php echo esc_html(__('Form fields', $this->textdomain)); ?></h2>

                <table>
                    <tr>
                        <th><?php _e('Form fields', $this->textdomain); ?></th>
                        <th><?php _e('Customerly Property Name', $this->textdomain); ?></th>
                        <th></th>
                    </tr>
                    <?php foreach ($mail_tags as $mail_tag) : ?>

                        <?php if ($mail_tag->type == 'checkbox'): ?>
                            <?php foreach ($mail_tag->values as $checkbox_row): ?>
                                <tr>
                                    <th style="text-align:left;"><?php echo $mail_tag->name; ?>
                                        (<?php echo $checkbox_row; ?>)
                                    </th>
                                    <td><input type="text" id="sf-<?php echo $name; ?>"
                                               name="cly_wpcf7_api_map[<?php echo $mail_tag->name; ?>][<?php echo $checkbox_row; ?>]"
                                               class="large-text"
                                               value="<?php echo isset($wpcf7_api_data_map[$mail_tag->name][$checkbox_row]) ? $wpcf7_api_data_map[$mail_tag->name][$checkbox_row] : ""; ?>"/>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <th style="text-align:left;"><?php echo $mail_tag->name; ?></th>
                                <td><input type="text" id="sf-<?php echo $mail_tag->name; ?>"
                                           name="cly_wpcf7_api_map[<?php echo $mail_tag->name; ?>]" class="large-text"
                                           value="<?php echo isset($wpcf7_api_data_map[$mail_tag->name]) ? $wpcf7_api_data_map[$mail_tag->name] : ""; ?>"/>
                                </td>
                            </tr>
                        <?php endif; ?>

                    <?php endforeach; ?>

                </table>

            </div>
        </fieldset>


        <?php

    }

    /**
     * Saves the API settings
     * @param  [type] $contact_form [description]
     * @return [type]               [description]
     */
    public function cly_save_contact_form_details($contact_form)
    {

        $properties = $contact_form->get_properties();

        $properties['wpcf7_api_data'] = isset($_POST["wpcf7-sf"]) ? $_POST["wpcf7-sf"] : '';
        $properties['wpcf7_api_data_map'] = isset($_POST["cly_wpcf7_api_map"]) ? $_POST["cly_wpcf7_api_map"] : '';
        $properties['template'] = isset($_POST["template"]) ? $_POST["template"] : '';
        $properties['json_template'] = isset($_POST["json_template"]) ? $_POST["json_template"] : '';

        $contact_form->set_properties($properties);

    }

    /**
     * The handler that will send the data to the api
     * @param  [type] $WPCF7_ContactForm [description]
     * @return [type]                    [description]
     */
    public function cly_cf7_send_data_to_api($WPCF7_ContactForm)
    {

        $this->clear_error_log($WPCF7_ContactForm->id());

        $submission = WPCF7_Submission::get_instance();

        $url = $submission->get_meta('url');
        $this->post = $WPCF7_ContactForm;
        $cly_cf7_data = $WPCF7_ContactForm->prop('wpcf7_api_data');
        $cly_cf7_data_map = $WPCF7_ContactForm->prop('wpcf7_api_data_map');
        $cly_cf7_data_template = $WPCF7_ContactForm->prop('template');
        $cly_cf7_data['debug_log'] = true; //always save last call results for debugging


        /* check if the form is marked to be sent via API */
        if (isset($cly_cf7_data["send_to_api"]) && $cly_cf7_data["send_to_api"] == "on") {
            $record = $this->get_record($submission, $cly_cf7_data_map, 'params', $template = $cly_cf7_data_template);
            do_action('cly_cf7_api_before_sent_to_api', $record);

            $response = $this->send_lead($record, $cly_cf7_data['debug_log']);

            if (is_wp_error($response)) {
                $this->log_error($response, $WPCF7_ContactForm->id());
            } else {
                do_action('cly_cf7_api_after_sent_to_api', $record, $response);
            }
        }

    }

    /**
     * CREATE ERROR LOG FOR RECENT API TRANSMISSION ATTEMPT
     * @param  [type] $wp_error [description]
     * @param  [type] $post_id  [description]
     * @return [type]           [description]
     */
    function log_error($wp_error, $post_id)
    {
        //error log
        $this->api_errors[] = $wp_error;

        update_post_meta($post_id, 'api_errors', $this->api_errors);
    }

    function clear_error_log($post_id)
    {
        delete_post_meta($post_id, 'api_errors');
    }

    /**
     * Convert the form keys to the API keys according to the mapping instructions
     * @param  [type] $submission      [description]
     * @param  [type] $cly_cf7_data_map [description]
     * @return [type]                  [description]
     */
    function get_record($submission, $cly_cf7_data_map, $type = "params", $template = "")
    {

        $submited_data = $submission->get_posted_data();
        $record = array();

        foreach ($cly_cf7_data_map as $form_key => $cly_cf7_form_key) {

            if ($cly_cf7_form_key) {

                if (is_array($cly_cf7_form_key)) {
                    foreach ($submited_data[$form_key] as $value) {
                        if ($value) {
                            $record["fields"][$cly_cf7_form_key[$value]] = apply_filters('set_record_value', $value, $cly_cf7_form_key);
                        }
                    }
                } else {
                    $value = isset($submited_data[$form_key]) ? $submited_data[$form_key] : "";

                    //flattan radio
                    if (is_array($value)) {
                        $value = reset($value);
                    }
                    $record["fields"][$cly_cf7_form_key] = apply_filters('set_record_value', $value, $cly_cf7_form_key);
                }

            }

        }

        $record = apply_filters('cf7api_create_record', $record, $submited_data, $cly_cf7_data_map, $type, $template);

        return $record;
    }


    /**
     * Send the lead using wp_remote
     * @param  [type]  $record [description]
     * @param  boolean $debug [description]
     * @param  string $method [description]
     * @return [type]          [description]
     */

    private function send_lead($record, $debug = false)
    {
        global $wp_version;

        $options = get_option('customerly_settings');
        $api_key = $options['customerly_text_field_appkey'];

        $lead = $record["fields"];
        $url = "https://api.customerly.io/v1/leads";

        $args = array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url(),
            'blocking' => true,
            'cookies' => array(),
            'headers' => array(
                'Content-Type' => 'application/json; charset=utf-8',
                'Authentication' => "AccessToken: $api_key",
            ),
            'body' => null,
            'compress' => false,
            'decompress' => true,
            'sslverify' => true,
            'stream' => false,
            'filename' => null
        );

        $json = array(
            "leads" => array(
                array(
                    "email" => $lead["email"],
                    "attributes" => $lead
                )
            )
        );


        if (is_wp_error($json)) {
            return $json;
        } else {
            $args['body'] = json_encode($json);
        }


        $args = apply_filters('cly_cf7_api_get_args', $args);

        $url = apply_filters('cly_cf7_api_get_url', $url, $record);

        $result = wp_remote_get($url, $args);

        error_log("POST Call");
        error_log(print_r($args, 1));

        error_log("REsult Call");
        error_log(print_r($result, 1));


        if ($debug) {
            update_post_meta($this->post->id(), 'cly_cf7_api_debug_url', $record["url"]);
            update_post_meta($this->post->id(), 'cly_cf7_api_debug_params', $lead);
            update_post_meta($this->post->id(), 'cly_cf7_api_debug_result', $result);
        }

        return do_action('after_cly_cf7_api_send_lead', $result, $record);

    }

    private function parse_json($string)
    {

        $json = json_decode($string);

        if (json_last_error() === JSON_ERROR_NONE) {
            return json_encode($json);
        }

        if (json_last_error() === 0) {
            return json_encode($json);
        }

        return new WP_Error('json-error', json_last_error());

    }

    private function get_xml($lead)
    {
        $xml = "";
        if (function_exists('simplexml_load_string')) {
            libxml_use_internal_errors(true);

            $xml = simplexml_load_string($lead);

            if ($xml == false) {
                $xml = new WP_Error(
                    'xml',
                    __("XML Structure is incorrect", $this->textdomain)
                );
            }

        }

        return $xml;
    }
}


/*
 * Function that redirect people on Customerly Admin when activated
 */
function customerly_activation($plugin)
{
    if ($plugin == plugin_basename(__FILE__)) {
        exit(wp_redirect(admin_url('admin.php?page=Customerly&utm_source=wordpress&utm_campaign=afterinstallredirect')));
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

    wp_register_style('cly-admin-css', CLY_ADMIN_CSS_URL . 'customerly.css', false, '1.0.0');

    wp_enqueue_style('cly-admin-css');

    wp_register_script('cly-admin-script', CLY_ADMIN_JS_URL . 'main.js', array('jquery'), '1.0.0', true);

    wp_enqueue_script('cly-admin-script');

}

/*
 * Function that add warning error notice when is not configured
 */

global $pagenow;


function customerly_download_app()
{
    include_once(CLY_PAGES_PATH . "/mobile.php");
}

function customerly_pro()
{
    include_once(CLY_PAGES_PATH . "/profeatures.php");
}

function cutomerly_integrations()
{
    include_once(CLY_PAGES_PATH . "/integrations.php");
}


?>