<?php
define('CLI_SCRIPT', true);
require(__DIR__ . '/moodle/config.php');

$courses = get_courses();

echo "Existing Courses:\n";
foreach ($courses as $course) {
    if ($course->id == 1) continue; // Skip site course
    $completion_status = $course->enablecompletion ? '[Completion Enabled]' : '[Completion Disabled]';
    echo "- ID: {$course->id} | {$course->fullname} ({$course->shortname}) $completion_status\n";
}
