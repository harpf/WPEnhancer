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