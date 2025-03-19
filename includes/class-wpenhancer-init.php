<?php
class WPEnhancer_Init{
    public static function activate(){
        // reset permalinks
    }

    public static function deactivate(){
        // remove data
    }
}

require_once plugin_dir_path(__FILE__) . 'class-wpenhancer.php';