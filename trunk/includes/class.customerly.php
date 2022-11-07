<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Customerly
{

    /**
     * The plugin identifier.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name unique plugin id.
     */
    protected $plugin_name;

    /**
     * save the instance of the plugin for static actions.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $instance an instance of the class.
     */
    public static $instance;

    /**
     * a reference to the admin class.
     *
     * @since    1.0.0
     * @access   protected
     * @var      object
     */
    public $admin;

    /**
     * Define the plugin functionality.
     *
     * set plugin name and version , and load dependencies
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->plugin_name = 'customerly';


        $this->load_dependencies();

        /**
         * Create an instance of the admin class
         * @var Customerly_admin
         */
        $this->admin = new Customerly_admin();
        $this->admin->plugin_name = $this->plugin_name;

        /**
         * save the instance for static actions
         *
         */
        self::$instance = $this;

        add_action('wp_footer', array($this, 'customerly_output_widget'));

    }

    public function get_customerly_project_id(){
        $options = get_option('customerly_settings');
        return $options['customerly_text_field_appid'] ?? "";
    }

    public function get_customerly_project_token(){
        $options = get_option('customerly_settings');
        return $options['customerly_text_field_appkey'] ?? "";
    }

    public function get_customerly_snippet(){
        global $user_ID;
        $project_id = self::get_customerly_project_id();

        $current_user = wp_get_current_user();

        $username = $current_user->user_login;
        $email = $current_user->user_email;
        $name = $current_user->display_name;


        $snippet = '<!-- Customerly Live Chat Snippet Code --><script>!function(){var e=window,i=document,t="customerly",n="queue",o="load",r="settings",u=e[t]=e[t]||[];if(u.t){return void u.i("[customerly] SDK already initialized. Snippet included twice.")}u.t=!0;u.loaded=!1;u.o=["event","attribute","update","show","hide","open","close"];u[n]=[];u.i=function(t){e.console&&!u.debug&&console.error&&console.error(t)};u.u=function(e){return function(){var t=Array.prototype.slice.call(arguments);return t.unshift(e),u[n].push(t),u}};u[o]=function(t){u[r]=t||{};if(u.loaded){return void u.i("[customerly] SDK already loaded. Use customerly.update to change settings.")}u.loaded=!0;var e=i.createElement("script");e.type="text/javascript",e.async=!0,e.src="https://messenger.customerly.io/launcher.js";var n=i.getElementsByTagName("script")[0];n.parentNode.insertBefore(e,n)};u.o.forEach(function(t){u[t]=u.u(t)})}();</script><!-- End of Customerly Live Chat Snippet Code -->';

        if ($user_ID == '') {
            $snippet .='<script type="text/javascript">
                    customerly.load({"app_id": "' . $project_id . '"});
			   </script>';
        } else {
            $snippet .='<script type="text/javascript">
                    customerly.load({
                    app_id: "' . $project_id . '",
                    user_id:"' . $user_ID . '",
                    name:"' . $name . '",
                    email: "' . $email . '",
                    attributes: {
                        username: "' . $username . '",
                    },
                     screenshotAvailable: true,
                    position: {
                        desktop: {
                            bottom: 50,
                            side: 50
                        },
                        mobile: {
                            bottom: 30,
                            side: 30
                        }
                    }
                    });
			  </script>';
        }
        return $snippet;
    }
    /*
    * Function that Render the actual widget in all the web pages
    */
    public function customerly_output_widget()
    {
        print $this->get_customerly_snippet();
    }


    public function init()
    {

    }

    /**
     * Loads the required plugin files
     * @return [type] [description]
     */
    public function load_dependencies()
    {
        /**
         * General global plugin functions
         */
        require_once CUSTOMERLY_CHAT_INCLUDES_PATH . 'class.customerly-helpers.php';
        /**
         * admin notices class
         */
        require_once CUSTOMERLY_CHAT_INCLUDES_PATH . 'class.customerly-admin-notices.php';
        /**
         * admin notices clclass
         */
        require_once CUSTOMERLY_CHAT_INCLUDES_PATH . 'class.customerly-admin.php';
    }

    /**
     * Get the current plugin instance
     * @return [type] [description]
     */
    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


}


