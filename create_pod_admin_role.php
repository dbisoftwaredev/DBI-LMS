<?php
define('CLI_SCRIPT', true);
require(__DIR__ . '/moodle/config.php');

$role_name = 'Pod Admin';
$role_shortname = 'podadmin';
$role_description = 'Administrator for a specific Pod (Category)';

if ($existing_role = $DB->get_record('role', array('shortname' => $role_shortname))) {
    echo "Role '$role_shortname' already exists (id: " . $existing_role->id . ").\n";
    exit(0);
}

// Create role based on 'manager' archetype but scoped generally initially
// We will assign it at Category context later
$role_id = create_role($role_name, $role_shortname, $role_description, 'manager');

// Set context levels where this role can be assigned (Category level)
set_role_contextlevels($role_id, [CONTEXT_COURSECAT]);

echo "Successfully created role '$role_name' with id: $role_id\n";
