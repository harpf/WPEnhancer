<?php
class WPEnhancer_Admin {

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

        if (isset($_POST['wpenhancer_generate_key']) && check_admin_referer('wpenhancer_generate_key_action')) {
            $new_key = bin2hex(random_bytes(32));
            update_option('wpenhancer_api_key', $new_key);
            echo '<div class="notice notice-success"><p>🔑 Neuer API-Key generiert.</p></div>';
        }

        $current_key = esc_html(get_option('wpenhancer_api_key', ''));

        ?>
        <div class="wrap" style="max-width: 800px; margin: auto;">
            <h1 style="text-align: center; color: #0073aa;">WPEnhancer Snippets & Shortcodes</h1>

            <div style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
                <h2 style="color: #23282d;">Built-in Snippets</h2>
                <ul>
                    <li><strong>my_custom_snippet()</strong> - Gibt einen Beispieltext zurück</li>
                </ul>
            </div>

            <br>

            <div style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
                <h2 style="color: #23282d;">Built-in Shortcodes</h2>
                <ul>
                    <li><strong>[my_shortcode]</strong> - Gibt HTML aus</li>
                    <li><strong>[custom_field_display]</strong> - Zeigt benutzerdefiniertes Benutzerfeld an</li>
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
                <p>Der API-Key wird für externe REST-Calls benötigt. Bewahre ihn sicher auf.</p>

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
}
