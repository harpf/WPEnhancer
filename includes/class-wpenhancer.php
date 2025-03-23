<?php
class WPEnhancer {
    protected $version;

    /**
     * class construction
     */
    public function __construct(){
        if(defined('WPEnhancer_Version')):
            $this ->version = WPEnhancer_Version;
        else:
            $this->version = '1.0.0';
        endif;
        
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('init', [$this, 'register_shortcodes']);

        add_action('show_user_profile', [$this, 'add_custom_user_profile_field']);
        add_action('edit_user_profile', [$this, 'add_custom_user_profile_field']);
        add_action('personal_options_update', [$this, 'save_custom_user_profile_field']);
        add_action('edit_user_profile_update', [$this, 'save_custom_user_profile_field']);

        add_action('rest_api_init', [$this, 'register_rest_routes']);       
        
    }

    /**
     * function to run setup and install code
     */
    public function run(){
        if(is_admin()):
            //run code for only admins
        endif;
    }

    /**
     * get current version number
     */
    public function get_version(){
        return $this->version;
    }

    // Beispiel: PHP-Snippet-Funktion
    public static function my_custom_snippet() {
        return "Dies ist ein benutzerdefiniertes Snippet!";
    }

    // Beispiel: Shortcode-Registrierung
    public function register_shortcodes() {
        add_shortcode('my_shortcode', [$this, 'shortcode_callback']);
        add_shortcode('favorite_grill', [$this, 'shortcode_callback']);
        add_shortcode('custom_field_display', [$this, 'shortcode_callback']);
    }

    public function shortcode_callback($atts = []) {
        $atts = shortcode_atts([
            'field' => 'juror_id',
            'label' => 'Deine Juror ID'
        ], $atts);
    
        $user_id = get_current_user_id();
        if (!$user_id) return '<p>Bitte einloggen.</p>';
    
        $value = get_user_meta($user_id, $atts['field'], true);
        if (empty($value)) return '<p>Kein Wert gefunden.</p>';
    
        return "<p>{$atts['label']}: <strong>" . esc_html($value) . "</strong></p>";
    }    

    // Admin-Menü hinzufügen
    public function add_admin_menu() {
        add_menu_page(
            'WPEnhancer Snippets & Shortcodes',
            'WPEnhancer',
            'manage_options',
            'wpenhancer-snippets',
            [$this, 'admin_page'],
            'dashicons-editor-code',
            100
        );
    }

    // Admin-Seite rendern
    public function admin_page() {
        $elementor_version = $simple_membership_version = 'Nicht installiert';
        $elementor_compatible = $simple_membership_compatible = '❌ Nicht kompatibel';
    
        if (is_plugin_active('elementor/elementor.php')) {
            $elementor_data = get_plugin_data(WP_PLUGIN_DIR . '/elementor/elementor.php');
            $elementor_version_number = $elementor_data['Version'];
            $elementor_version = '✔ Installiert (Version: ' . $elementor_version_number . ')';
    
            if (version_compare($elementor_version_number, '3.0.0', '>=')) {
                $elementor_compatible = '✔ Kompatibel';
            }
        }
    
        if (is_plugin_active('simple-membership/simple-membership.php')) {
            $simple_membership_data = get_plugin_data(WP_PLUGIN_DIR . '/simple-membership/simple-membership.php');
            $simple_membership_version_number = $simple_membership_data['Version'];
            $simple_membership_version = '✔ Installiert (Version: ' . $simple_membership_version_number . ')';
    
            if (version_compare($simple_membership_version_number, '4.0.0', '>=')) {
                $simple_membership_compatible = '✔ Kompatibel';
            }
        }
    
        // API-Key generieren, falls Button geklickt wurde
        if (isset($_POST['wpenhancer_generate_key']) && check_admin_referer('wpenhancer_generate_key_action')) {
            $new_key = $this->generate_api_key();
            $this->set_api_key($new_key);
            echo '<div class="notice notice-success"><p>🔑 Neuer API-Key generiert.</p></div>';
        }
    
        $current_key = esc_html($this->get_stored_api_key());
        ?>
    
        <div class="wrap" style="max-width: 800px; margin: auto;">
            <h1 style="text-align: center; color: #0073aa;">WPEnhancer Snippets & Shortcodes</h1>
    
            <div style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
                <h2 style="color: #23282d;">Built-in Snippets</h2>
                <ul>
                    <li><strong>my_custom_snippet()</strong> - <?php echo self::my_custom_snippet(); ?></li>
                </ul>
            </div>
    
            <br>
    
            <div style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
                <h2 style="color: #23282d;">Built-in Shortcodes</h2>
                <ul>
                    <li><strong>[my_shortcode]</strong> - Gibt HTML aus</li>
                </ul>
            </div>
    
            <br>
    
            <div style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
                <h2 style="color: #23282d;">Plugin-kompatible Snippets & Shortcodes</h2>
                <p><strong>Elementor:</strong> <?php echo $elementor_version; ?> - <strong><?php echo $elementor_compatible; ?></strong></p>
                <p><strong>Simple Membership:</strong> <?php echo $simple_membership_version; ?> - <strong><?php echo $simple_membership_compatible; ?></strong></p>
            </div>
    
            <br>
    
            <div style="background: #fff8e1; padding: 20px; border-radius: 5px;">
                <h2 style="color: #cc8800;">API-Key Verwaltung 🔐</h2>
                <p>Der API-Key wird für Power Automate oder externe REST-Calls verwendet. Er sollte sicher aufbewahrt werden.</p>
    
                <textarea readonly style="width:100%; font-family:monospace; font-size:14px;"><?php echo $current_key ?: 'Noch kein API-Key generiert.'; ?></textarea>
    
                <form method="post">
                    <?php wp_nonce_field('wpenhancer_generate_key_action'); ?>
                    <p>
                        <input type="submit" name="wpenhancer_generate_key" class="button button-primary" value="🔁 Neuen API-Key generieren" />
                    </p>
                </form>
            </div>
        </div>
    
        <?php
    }    

    public function add_custom_user_profile_field($user) {
        ?>
        <h3>WPEnhancer Benutzerfeld</h3>
        <table class="form-table">
            <tr>
                <th><label for="juror_id">Juror ID</label></th>
                <td>
                    <input type="text" name="juror_id" id="juror_id"
                           value="<?php echo esc_attr(get_user_meta($user->ID, 'juror_id', true)); ?>"
                           class="regular-text" /><br/>
                    <span class="description">Bitte gib deine Juror ID ein.</span>
                </td>
            </tr>
        </table>
        <?php
    }
    
    public function save_custom_user_profile_field($user_id) {
        if (!current_user_can('edit_user', $user_id)) return;
    
        if (isset($_POST['juror_id'])) {
            update_user_meta($user_id, 'juror_id', sanitize_text_field($_POST['juror_id']));
        }
    }  
    
    public function handle_jurorid_submission($request) {
        $params = $request->get_json_params();
    
        $email = isset($params['email']) ? sanitize_email($params['email']) : null;
        $juror_id = isset($params['juror_id']) ? sanitize_text_field($params['juror_id']) : null;
    
        if (!$email || !$juror_id) {
            return new WP_REST_Response(['message' => 'Ungültige Anfrage'], 400);
        }
    
        $user = get_user_by('email', $email);
        if (!$user) {
            return new WP_REST_Response(['message' => 'Benutzer nicht gefunden'], 404);
        }
    
        update_user_meta($user->ID, 'juror_id', $juror_id);
    
        return new WP_REST_Response([
            'message' => 'Juror ID gespeichert',
            'user_id' => $user->ID,
            'juror_id' => $juror_id
        ], 200);
    }
    
    public function register_rest_routes() {
        register_rest_route('wpenhancer/v1', '/set-jurorid', [
            'methods'  => 'POST',
            'callback' => [$this, 'handle_jurorid_submission'],
            'permission_callback' => [$this, 'check_api_token'],
        ]);
    }

    // Einen neuen Key generieren
    public function generate_api_key() {
        return bin2hex(random_bytes(32)); // 64-stelliger sicherer Hex-Token
    }
    
    public function get_stored_api_key() {
        return get_option('wpenhancer_api_key', '');
    }
    
    public function set_api_key($key) {
        update_option('wpenhancer_api_key', $key);
    }
    

    public function check_api_token($request) {
        $headers = $request->get_headers();
    
        // x-api-key prüfen
        if (!isset($headers['x_api_key'][0])) {
            return false;
        }
    
        $provided_token = $headers['x_api_key'][0];
        $stored_token = $this->get_stored_api_key();
    
        return hash_equals($stored_token, $provided_token);
    }    
    
}
