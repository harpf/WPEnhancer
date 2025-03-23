<?php
class WPEnhancer_Init {
    public static function activate() {
        // Optional: Flush rewrite rules oder Setup-Code
    }

    public static function deactivate() {
        // Optional: Clean-up oder Cache löschen
    }
}

$includes_path = plugin_dir_path(__FILE__);

require_once $includes_path . 'class-wpenhancer-core.php';
require_once $includes_path . 'class-wpenhancer-admin.php';
require_once $includes_path . 'class-wpenhancer-usermeta.php';
require_once $includes_path . 'class-wpenhancer-api.php';
