<?php

$options = get_option('customerly_settings');

if (isset($options['customerly_text_field_appid'])) {
    $customize = "https://app.customerly.io/projects/" . $options['customerly_text_field_appid']."/settings/messenger/appearance";
    $inbox = "https://app.customerly.io/projects/" . $options['customerly_text_field_appid']."/conversations";
}
?>
<script>
    var configured = true;
</script>
<div class="floating-header-section" id="customerly_configured">
    <div class="section-content">
        <div class="section-item">

            <img class="icon-customerly" height="120"
                 src="<?php echo(plugins_url("../assets/img/logo-blue.svg", __FILE__)); ?>">
            <h1>Connected with Customerly</h1>
            <p class="margin-bottom">Your live chat is up and running. Yay 😎 <br> To check your incoming conversation
                visit your inbox. </p>

            <div class="button-container">

                <a class="button button-inbox"
                   href="<?php echo esc_url($inbox); ?>"
                   target="_blank"> Open Inboxes</a>

                <a class="button button-start"
                                      href="<?php echo esc_url($customize); ?>"
                                  target="_blank"> Customize Live Chat</a>

                <button class="button button-hero"
                        onclick="reset()"
                        target="_blank"> Reconfigure
                </button>
            </div>

        </div>
        <p>Loving Customerly ❤️? <a href="https://go.customerly.io/wpreview">Rate us ★★★★★</a></p>
    </div>

</div>