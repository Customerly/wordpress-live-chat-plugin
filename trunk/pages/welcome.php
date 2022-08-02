<?php
$current_user = wp_get_current_user();
$blogName = get_bloginfo('name');
$email = $current_user->user_email;
$domain = get_site_url();

?>
<script>
    var configured = false;
</script>
<div class="floating-header-section">
    <div class="section-content">
        <div class="section-item">

            <img class="icon-customerly" height="90"
                 src="<?php echo(plugins_url("../assets/img/blue_fill_notification.svg", __FILE__)); ?>">
            <h1>Install Customerly Live Chat</h1>
            <p><strong>25.000+ Websites </strong> uses Customerly Live Chat to talk with their customers via Chat and Email. </p>
            <div class="customerly_register">

                <div style="margin: 10px 0; display:none" >
                    <input class="input-field" type="text" placeholder="Your Name..." name="name" id="name"
                           required="required"
                           value="<?php echo($current_user->first_name); ?>"/>

                    <input class="input-field" placeholder="Project Name..." type="text" name="app_name" id="app_name"
                           required="required"
                           value="<?php echo($blogName); ?>"/>

                    <input class="input-field" placeholder="Email..." type="text" name="email" id="email"
                           required="required"
                           value="<?php echo($current_user->user_email); ?>"/>

                    <input value="<?php echo($domain); ?>" type="hidden" id="domain"/>

                    <label style="color: red; display: none" id="error_message"></label>
                </div>

                <div class="cta-container">
                    <div id="register-loader" class="lds-ring" style="display: none">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <input type="submit" name="submit" id="register-button" class="button button-start"
                           onclick="register_account();"
                           value="Start configuration"/>
                </div>

            </div>
            <div class="customerly_manual_config" style="display: none">

                <h3>Add your Project ID and connect</h3>
                <div style="margin: 10px 0">
                      <input class="input-field" placeholder="es. 92ac34f4" type="text" name="project_id" id="project_id"
                                               required="required" value=""/>
                </div>
                 <p> Learn more on how to get your Project ID <a target="_blank" href="https://docs.customerly.io/project-settings-set-up-the-important-assets-of-your-project/what-s-a-customerly-project-id-and-where-can-i-get-it" >here</a></p>


                <div class="cta-container">
                    <div id="login-loader" class="lds-ring" style="display: none">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <input type="submit" name="submit" id="login-button" class="button button-start" onclick="manual_setup();"
                           value="Connect project"/>
                </div>


            </div>

        </div>
        <div class="customerly_register" style="margin: 20px"> Already have an account? <a onclick="show_manual_config();"
                                                                                           style="cursor: pointer">
                Configure manually</a></div>
        <div class="customerly_login" style="margin: 20px; display: none;"> Need an account? <a
                    onclick="show_register();" style="cursor: pointer"> Register</a></div>
    </div>
</div>