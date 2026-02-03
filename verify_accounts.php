<?php
define('CLI_SCRIPT', true);
require(__DIR__ . '/moodle/config.php');
require_once($CFG->libdir . '/accesslib.php');
require_once($CFG->dirroot . '/user/lib.php');

$users_to_check = ['admin', 'podadmin', 'instructor', 'student'];

echo "---------------------------------------------------\n";
echo "Account Verification Report\n";
echo "---------------------------------------------------\n";

foreach ($users_to_check as $username) {
    $user = $DB->get_record('user', array('username' => $username, 'deleted' => 0));
    
    if ($user) {
        echo "User: " . str_pad($username, 15) . " | ID: " . str_pad($user->id, 5) . " | Email: $user->email\n";
        
        // Get roles
        // We need to check roles in all contexts? That's expensive.
        // Let's check system roles and specific known context roles.
        
        $sql = "SELECT r.shortname, c.contextlevel, c.instanceid
                FROM {role_assignments} ra
                JOIN {role} r ON r.id = ra.roleid
                JOIN {context} c ON c.id = ra.contextid
                WHERE ra.userid = ?";
                
        $assignments = $DB->get_records_sql($sql, array($user->id));
        
        if ($assignments) {
            foreach ($assignments as $ra) {
                $context_name = '';
                if ($ra->contextlevel == CONTEXT_SYSTEM) {
                    $context_name = "System";
                } elseif ($ra->contextlevel == CONTEXT_COURSECAT) {
                    $cat = $DB->get_record('course_categories', array('id' => $ra->instanceid));
                    $context_name = "Category: " . ($cat ? $cat->name : 'Unknown');
                } elseif ($ra->contextlevel == CONTEXT_COURSE) {
                    $course = $DB->get_record('course', array('id' => $ra->instanceid));
                    $context_name = "Course: " . ($course ? $course->shortname : 'Unknown');
                } else {
                    $context_name = "Context Level: " . $ra->contextlevel . " Instance: " . $ra->instanceid;
                }
                
                echo "  - Role: " . str_pad($ra->shortname, 15) . " | Context: $context_name\n";
            }
        } else {
            echo "  - No specific role assignments found.\n";
        }
    } else {
        echo "User: " . str_pad($username, 15) . " | NOT FOUND\n";
    }
    echo "---------------------------------------------------\n";
}
