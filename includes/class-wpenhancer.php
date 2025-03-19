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
    }

    public function shortcode_callback() {
        return "<p>Dies ist ein benutzerdefinierter Shortcode!</p>";
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
        </div>
        <?php
    }
}
