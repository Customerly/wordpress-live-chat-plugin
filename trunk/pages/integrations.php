<?php

$options = get_option('customerly_settings');

$plugins_url = plugins_url();
$baseURL = $plugins_url . "/customerly/";

?>
<div class="container">
    <h1>Create Email Campaigns and Funnels</h1>
    <h3>Are you using forms to collect leads? Create Funnels and Campaigns with Customerly. Integrate with your
        services.</h3>
</div>
<div class="content intgrations">

    <div class="section-item integration-item">

        <img class="integration-icon"
             src="<?php echo(plugins_url("../assets/img/integrations/elementor.jpg", __FILE__)); ?>">
        <h1>Elementor <a
                    href="https://wordpress.org/plugins/elementor/" target="_blank">
                <div class="dashicons dashicons-external"></div>
            </a></h1>
        <div class="integration-content">
            <p>Connect your in-page forms submission to Customerly to collect leads and create
                Email
                Campaigns or Funnels</p>
            <b>Webhook POST URL</b>
            <div class="integration-url"><?php echo($baseURL . 'elementor.php'); ?></div>
        </div>

        <a class="button button-start"
           href="https://go.customerly.io/elementor"
           target="_blank"> Learn more</a>

    </div>
    <div class="section-item integration-item">

        <img class="integration-icon"
             src="<?php echo(plugins_url("../assets/img/integrations/zapier.jpg", __FILE__)); ?>">
        <h1>Zapier <a
                    href="https://zapier.com/?utm_source=customerly&utm_medium=wordpress&utm_campaign=plugin_Customerly"
                    target="_blank">
                <div class="dashicons dashicons-external"></div>
            </a></h1>

        <div class="integration-content">
            <p>Integrate almost everything with Customerly thanks to Zapier</p>
            <div style="margin-top: 7px">
                <div class="dashicons dashicons-yes" style="color: #00a9ff;"></div>
                Connect Any Landing page builder with Customerly Email Campaign
            </div>
            <div style="margin-top: 7px">
                <div class="dashicons dashicons-yes" style="color: #00a9ff;"></div>
                Connect Any Form with Customerly Funnel
            </div>

            <div style="margin-top: 7px">
                <div class="dashicons dashicons-yes" style="color: #00a9ff;"></div>
                Create Campaigns or Funnel importing Leads
            </div>


        </div>

        <a class="button button-start"
           href="https://docs.customerly.help/getting-started/how-to-integrate-customerly-with-zapier"
           target="_blank"> Learn more</a>

    </div>
</div>





