<?php
/*
Plugin Name: WPEnhancer Sample Plugin
Plugin URI: https//swissbarbecue.ch
Description: This is a sample Plugin to add additional functionality for WP Features
Version: 1.0.0
Author: Jonas Zauner
Author URI: https//swissbarbecue.ch
*/
if(!defined('ABSPATH')):
    die;
endif;

define ('WPEnhancer_Version','1.0.0');
define('WPENHANCER_API_SECRET', 'k[uP]0;mD)v&8L+8lq@n}l]P8#S*4u');


require_once plugin_dir_path(__FILE__).'includes/class-wpenhancer-init.php';

function WPEnhancer_activate() {
    WPEnhancer_Init::activate();
}

function WPEnhancer_deactivate() {
    WPEnhancer_Init::deactivate();
}

register_activation_hook(__FILE__,'WPEnhancer_activate');
register_deactivation_hook(__FILE__,'WPEnhancer_deactivate');

function init(){
    if(class_exists('WPEnhancer')) {
        $WPEnhancer = new WPEnhancer();
        $WPEnhancer->run();
    }
}

add_action('plugins_loaded', 'init');
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

add_action('init', function() {
    if (!is_user_logged_in()) return;
    if (!isset($_GET['showmeta'])) return;

    $user_id = get_current_user_id();
    $meta = get_user_meta($user_id);

    echo '<pre>';
    print_r($meta);
    echo '</pre>';
    exit;
});

add_shortcode('juror_id', function($atts) {
    return $this->shortcode_callback([
        'field' => 'juror_id',
        'label' => 'Deine Juror ID'
    ]);
});
