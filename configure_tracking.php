<?php
define('CLI_SCRIPT', true);
require(__DIR__ . '/moodle/config.php');

// Enable completion tracking site-wide
if ($CFG->enablecompletion != 1) {
    set_config('enablecompletion', 1);
    echo "Completion tracking enabled site-wide.\n";
} else {
    echo "Completion tracking is already enabled site-wide.\n";
}

// Check other relevant settings
// 'enableavailability' is often needed for conditional access based on completion
if (empty($CFG->enableavailability)) {
    set_config('enableavailability', 1);
    echo "Conditional access (availability) enabled site-wide.\n";
} else {
    echo "Conditional access is already enabled.\n";
}

echo "Tracking configuration verified.\n";
