<?php
define('CLI_SCRIPT', true);
require(__DIR__ . '/moodle/config.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/user/lib.php');

// Usage: php assign_pod_admin.php "Pod Name" "Username"
$pod_name = isset($argv[1]) ? $argv[1] : null;
$username = isset($argv[2]) ? $argv[2] : null;

if (!$pod_name || !$username) {
    echo "Usage: php assign_pod_admin.php \"Pod Name\" \"Username\"\n";
    exit(1);
}

// Get User
$user = $DB->get_record('user', array('username' => $username, 'deleted' => 0));
if (!$user) {
    echo "Error: User '$username' not found.\n";
    exit(1);
}

// Get Category
$category_record = $DB->get_record('course_categories', array('name' => $pod_name));
if (!$category_record) {
    echo "Error: Pod '$pod_name' not found.\n";
    exit(1);
}
// Load full category object
$category = \core_course_category::get($category_record->id);
$context = $category->get_context();

// Get Pod Admin Role
$role = $DB->get_record('role', array('shortname' => 'podadmin'));
if (!$role) {
    echo "Error: Role 'podadmin' not found. Please run create_pod_admin_role.php first.\n";
    exit(1);
}

// Check if already assigned
if (user_has_role_assignment($user->id, $role->id, $context->id)) {
    echo "User '$username' is already a Pod Admin of '$pod_name'.\n";
    exit(0);
}

// Assign Role
role_assign($role->id, $user->id, $context->id);
echo "Successfully assigned '$username' as Pod Admin for '$pod_name'.\n";
