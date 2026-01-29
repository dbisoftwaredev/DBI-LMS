<?php
define('CLI_SCRIPT', true);
require(__DIR__ . '/config.php');

$config_file = $CFG->dirroot . '/config.php';
$content = file_get_contents($config_file);

if (strpos($content, 'preventfilelocking') === false) {
    // Append the setting before the closing PHP tag or at the end
    $setting = "\n// Fix for Docker on Windows file locking issues\n\$CFG->preventfilelocking = true;\n";
    
    // Check if there is a closing ?> tag
    /*
    if (strpos($content, '?>') !== false) {
        $content = str_replace('?>', $setting . '?>', $content);
    } else {
        $content .= $setting;
    }
    */
    // Safest place is usually right after the $CFG definition loop or at the end of the file.
    // Bitnami's config.php usually ends with require_once(lib/setup.php). 
    // We should put it BEFORE that.
    
    if (strpos($content, "require_once(__DIR__ . '/lib/setup.php');") !== false) {
        $content = str_replace("require_once(__DIR__ . '/lib/setup.php');", $setting . "\nrequire_once(__DIR__ . '/lib/setup.php');", $content);
        file_put_contents($config_file, $content);
        echo "Successfully patched config.php to disable file locking.\n";
    } else {
        echo "Could not find standard setup require line in config.php. Manual intervention needed.\n";
    }
} else {
    echo "File locking already disabled.\n";
}
