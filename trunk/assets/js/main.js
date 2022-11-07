jQuery(document).ready(function () {

    replaceFooter();
    if (configured === false) {
        var searchParams = new URLSearchParams(window.location.search);
        if (searchParams.has('projectId') === true) {
            var appid = searchParams.get('projectId');
            jQuery('#appID').val(appid);
            save_main_options_ajax();
        }
    } else {
        console.log(" configured");
    }

});


function reset() {

    jQuery('#appID').val("");
    jQuery('#sessionToken').val("");
    jQuery('#appkey').val("");
    save_main_options_ajax();
}

function show_manual_config() {
    jQuery('.customerly_manual_config').slideDown();
    jQuery('.customerly_register').slideUp();
}

function show_register() {
    jQuery('.customerly_register').slideDown();
    jQuery('.customerly_manual_config').slideUp();
}

function show_error(position, message) {

    if (position === 'login') {
        jQuery('#error_message_login').html(message);
        jQuery('#error_message_login').slideDown();

        setTimeout(function () {
            jQuery('#error_message_login').html("").slideUp();
        }, 10000);
    } else {
        jQuery('#error_message').html(message);
        jQuery('#error_message').slideDown();

        setTimeout(function () {
            jQuery('#error_message').html("").slideUp();
        }, 10000);
    }

}

function manual_setup(){
    var project_id = jQuery('#project_id').val();
    jQuery('#appID').val(project_id);
    save_main_options_ajax();
}

function register_account() {
    try {
        var name = jQuery('#name').val();
        var projectName = encodeURIComponent(jQuery('#app_name').val());
        let domain = (new URL(jQuery('#domain').val()));
        var projectDomain = encodeURIComponent(domain.hostname);
        var redirectUrl = encodeURIComponent(window.location.href);
        var email = jQuery('#email').val();

    } catch (e) {
        console.log("error", e);
    }

    var signupUrl = "https://app.customerly.io/wordpress?" + "email="+email+"&projectName=" + projectName + "&projectDomain=" + projectDomain + "&name=" + name + "&redirectUrl=" + redirectUrl + "&utm_source=wordpress&utm_medium=wp_plugin&utm_campaign=wp_signup";
    window.open( signupUrl, "_blank");

}

function save_main_options_ajax() {
    jQuery('#customerlySettings').submit();
}

function replaceFooter() {
    jQuery('#footer-upgrade').hide();
    jQuery("#footer-left").html('Do you like <strong>Customerly</strong>? Please leave us a <a href="https://go.customerly.io/wpreview" target="_blank">★★★★★ review</a>. We appreciate your support!');
}
