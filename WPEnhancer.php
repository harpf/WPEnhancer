<?php
/*
Plugin Name: WPEnhancer Sample Plugin
Plugin URI: https://swissbarbecue.ch
Description: Plugin für benutzerdefinierte Shortcodes, Snippets und API-Zugriffe in WordPress.
Version: 1.0.0
Author: Jonas Zauner
Author URI: https://swissbarbecue.ch
*/

if (!defined('ABSPATH')) {
    exit;
}

define('WPEnhancer_Version', '1.0.0');

// 👉 Alle benötigten Klassen laden
require_once plugin_dir_path(__FILE__) . 'includes/class-wpenhancer-init.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-wpenhancer-core.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-wpenhancer-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-wpenhancer-usermeta.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-wpenhancer-api.php';

// 👉 Aktivierung / Deaktivierung
function WPEnhancer_activate() {
    WPEnhancer_Init::activate();
}
function WPEnhancer_deactivate() {
    WPEnhancer_Init::deactivate();
}
register_activation_hook(__FILE__, 'WPEnhancer_activate');
register_deactivation_hook(__FILE__, 'WPEnhancer_deactivate');

// 👉 Plugin initialisieren
function WPEnhancer_init() {
    if (class_exists('WPEnhancer_Core')) {
        new WPEnhancer_Core();
    }
}
add_action('plugins_loaded', 'WPEnhancer_init');

function WPEnhancer_Juryregistration() {
    if ( is_user_logged_in() ) {
        // Benutzer holen (Simple Membership oder normal)
        if ( class_exists( 'SwpmMemberUtils' ) && SwpmMemberUtils::is_member_logged_in() ) {
            $email = SwpmMemberUtils::get_logged_in_members_email();
        } else {
            $current_user = wp_get_current_user();
            $email = $current_user->user_email;
        }

        if ( empty( $email ) ) {
            return '<p>Keine gültige E-Mail gefunden.</p>';
        }

        $email = esc_js( $email );

        ob_start();
        ?>

        <div id="form1">

            <!-- Seamless Embed Script -->
            <script src="https://www.cognitoforms.com/f/seamless.js"
                    data-key="vppJlYndQEekJiWM7tSHvA"
                    data-form="3">
            </script>
        </div>
        
        <!-- Prefill vor seamless.js -->
        <script>
            Cognito.mount("3","#form1").prefill({
                "Email": "<?php echo $email; ?>"
            });
        </script>

        
        <?php
        return ob_get_clean();
    } else {
        return '<p>Bitte melde dich an, um das Formular auszufüllen.</p>';
    }
}

add_shortcode('Juryregistration', 'WPEnhancer_Juryregistration');

// 👉 Benutzer-Meta anzeigen (Debug-Zweck)
add_action('init', function () {
    if (!is_user_logged_in() || !isset($_GET['showmeta'])) return;
    echo '<pre>';
    print_r(get_user_meta(get_current_user_id()));
    echo '</pre>';
    exit;
});


