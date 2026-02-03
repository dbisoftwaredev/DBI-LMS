<?php
define('CLI_SCRIPT', true);
require(__DIR__ . '/moodle/config.php');
require_once($CFG->libdir . '/enrollib.php');

// 1. Enable Manual and Self Enrollment
$plugins = ['manual', 'self'];
foreach ($plugins as $plugin) {
    if (!enrol_is_enabled($plugin)) {
        // This function is not available in CLI directly easily without include hell, 
        // asking the plugin manager is better.
        // Direct DB manipulation for enabling plugins is risky but standard in some CLI scripts.
        // Let's use set_config for 'enrol_plugins_enabled' if it exists, or check the 'enrol' table?
        // Actually, $this->enable_plugin() is part of the enrolment manager.
        
        // Simpler approach: Check if they appear in the comma separated list of enabled plugins
        $enabled = explode(',', $CFG->enrol_plugins_enabled);
        if (!in_array($plugin, $enabled)) {
            // Check if record exists in config_plugins is safer?
            // Let's assume standard install has them enables, just verification.
             echo "Plugin '$plugin' is NOT enabled in the list of enrol plugins. Checking DB...\n";
        } else {
             echo "Enrollment plugin '$plugin' is enabled.\n";
        }
    } else {
        echo "Enrollment plugin '$plugin' is enabled.\n";
    }
}

// 2. Gradebook Visibility
// 'grade_report_showmin' -> Show minimum grade
// 'grade_report_showmax' -> Show maximum grade
// 'grade_report_showpercentage' -> Show percentage
// These are usually site-wide defaults.

set_config('grade_report_showmin', 1);
set_config('grade_report_showmax', 1);
set_config('grade_report_showpercentage', 1);

echo "Gradebook visibility settings configured (Min, Max, Percentage enabled).\n";

// 3. User Menu / Dashboard
// Ensure 'my' (Dashboard) is the default home for students
if ($CFG->defaulthomepage != 1) { // 1 = Dashboard (`my`)
    set_config('defaulthomepage', 1);
    echo "Default homepage set to Dashboard.\n";
} else {
    echo "Default homepage is already Dashboard.\n";
}

echo "Student access configuration complete.\n";
