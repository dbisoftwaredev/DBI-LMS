<?php
define('CLI_SCRIPT', true);
require(__DIR__ . '/moodle/config.php');
require_once($CFG->dirroot . '/course/lib.php');

// Simple CLI argument parsing
// Usage: php create_pod.php "Pod Name" [ParentID]
$pod_name = isset($argv[1]) ? $argv[1] : null;
$parent_id = isset($argv[2]) ? (int)$argv[2] : 0;

if (!$pod_name) {
    echo "Usage: php create_pod.php \"Pod Name\" [ParentID]\n";
    exit(1);
}

// Check if category exists
if ($DB->record_exists('course_categories', array('name' => $pod_name))) {
    echo "Pod (Category) '$pod_name' already exists.\n";
    exit(0);
}

$newcategory = new stdClass();
$newcategory->name = $pod_name;
$newcategory->parent = $parent_id;

$category = \core_course_category::create($newcategory);

echo "Created Pod: " . $category->get_formatted_name() . " (ID: " . $category->id . ")\n";
