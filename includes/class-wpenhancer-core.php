<?php
class WPEnhancer_Core {
    protected $version;

    public function __construct() {
        $this->version = defined('WPEnhancer_Version') ? WPEnhancer_Version : '1.0.0';

        add_action('admin_menu', [new WPEnhancer_Admin(), 'add_admin_menu']);
        add_action('init', [$this, 'register_shortcodes']);
        add_action('rest_api_init', [new WPEnhancer_API(), 'register_rest_routes']);

        $usermeta = new WPEnhancer_UserMeta();
        add_action('show_user_profile', [$usermeta, 'add_custom_user_profile_field']);
        add_action('edit_user_profile', [$usermeta, 'add_custom_user_profile_field']);
        add_action('personal_options_update', [$usermeta, 'save_custom_user_profile_field']);
        add_action('edit_user_profile_update', [$usermeta, 'save_custom_user_profile_field']);
    }

    public static function shortcode_callback($atts = []) {
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

    public function register_shortcodes() {
        $shortcodes = [
            'my_shortcode' => [],
            'custom_field_display' => [],
            'juror_id' => ['field' => 'juror_id', 'label' => 'Deine Juror ID'],
        ];
    
        foreach ($shortcodes as $tag => $preset) {
            add_shortcode($tag, function ($atts = []) use ($preset) {
                return WPEnhancer_Core::shortcode_callback(array_merge($preset, $atts));
            });
        }
    }
    
}