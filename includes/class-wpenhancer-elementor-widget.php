<?php
if (!defined('ABSPATH')) {
    exit; // Sicherheitscheck
}

class WPEnhancer_Elementor_Button_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wpenhancer_button';
    }

    public function get_title() {
        return __('WPEnhancer Button', 'wpenhancer');
    }

    public function get_icon() {
        return 'eicon-button';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Inhalt', 'wpenhancer'),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'wpenhancer'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Klicke hier', 'wpenhancer'),
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => __('Button Link', 'wpenhancer'),
                'type' => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                    'is_external' => true,
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo '<a href="' . esc_url($settings['button_link']['url']) . '" class="elementor-button elementor-size-md" target="_blank">';
        echo esc_html($settings['button_text']);
        echo '</a>';
    }
}
