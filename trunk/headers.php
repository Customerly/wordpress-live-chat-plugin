<?php
$current_user = wp_get_current_user();
$blogName = get_bloginfo('name');
?>

<link rel="stylesheet"
      href="<?php echo(plugins_url("/assets/css/customerly.css", __FILE__)); ?>">

<script src="<?php echo(plugins_url("/assets/js/main.js", __FILE__)); ?>"></script>
<!-- Google Tag Manager -->
<script>(function (w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
            'gtm.start':
                new Date().getTime(), event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
            'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-5JC6WRF');</script>
<!-- End Google Tag Manager -->

<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5JC6WRF"
            height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->


<!-- Customerly Live Chat Snippet Code -->
<script>
    !function () {
        var e = window, i = document, t = "customerly", n = "queue", o = "load", r = "settings", u = e[t] = e[t] || [];
        if (u.t) {
            return void u.i("[customerly] SDK already initialized. Snippet included twice.")
        }
        u.t = !0;
        u.loaded = !1;
        u.o = ["event", "attribute", "update", "show", "hide", "open", "close"];
        u[n] = [];
        u.i = function (t) {
            e.console && !u.debug && console.error && console.error(t)
        };
        u.u = function (e) {
            return function () {
                var t = Array.prototype.slice.call(arguments);
                return t.unshift(e), u[n].push(t), u
            }
        };
        u[o] = function (t) {
            u[r] = t || {};
            if (u.loaded) {
                return void u.i("[customerly] SDK already loaded. Use customerly.update to change settings.")
            }
            u.loaded = !0;
            var e = i.createElement("script");
            e.type = "text/javascript", e.async = !0, e.src = "https://messenger.customerly.io/launcher.js";
            var n = i.getElementsByTagName("script")[0];
            n.parentNode.insertBefore(e, n)
        };
        u.o.forEach(function (t) {
            u[t] = u.u(t)
        })
    }();

    customerly.load({
        "app_id": "00c4ed07",
        "email": "<?php  echo($current_user->user_email); ?>",
        "name": "<?php  echo($current_user->user_firstname); ?>",
        "direction": "right",
        "attributes": {
            "source": "wordpress_plugin"
        }
    });

</script>
<!-- End of Customerly Live Chat Snippet Code -->