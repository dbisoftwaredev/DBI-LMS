<?php
define('CLI_SCRIPT', true);

require(__DIR__ . '/moodle/config.php');
// Force debugging
$CFG->debug = 32767; // DEBUG_DEVELOPER
$CFG->debugdisplay = 1;

require_once($CFG->libdir . '/authlib.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->dirroot . '/lib/enrollib.php');

// Ensure we are running as admin
\core\session\manager::set_user(get_admin());

$password = 'TestPass123!';

function create_test_user($username, $firstname, $lastname, $email, $password) {
    global $DB;
    $user = $DB->get_record('user', array('username' => $username));
    if (!$user) {
        $user = create_user_record($username, $password, 'manual');
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->confirmed = 1;
        $user->maildisplay = 1;
        $user->city = 'Test City';
        $user->country = 'US';
        $DB->update_record('user', $user);
        echo "Created user: $username\n";
    } else {
        echo "User already exists: $username\n";
    }
    return $DB->get_record('user', array('username' => $username));
}

// 1. Create Users
$u_podadmin = create_test_user('podadmin', 'Pod', 'Admin', 'podadmin@example.com', $password);
$u_instructor = create_test_user('instructor', 'Jane', 'Instructor', 'instructor@example.com', $password);
$u_student = create_test_user('student', 'John', 'Student', 'student@example.com', $password);

// 2. Create Category "Test Pod"
$category = $DB->get_record('course_categories', array('name' => 'Test Pod'));
if (!$category) {
    $newcategory = new stdClass();
    $newcategory->name = 'Test Pod';
    $newcategory->parent = 0; // Top level
    $category = \core_course_category::create($newcategory, array('disableevents'=>true))->get_db_record();
    echo "Created Category: Test Pod\n";
} else {
    echo "Category already exists: Test Pod\n";
}

// 3. Assign Pod Admin (Manager Role) to Category
$manager_role = $DB->get_record('role', array('shortname' => 'manager'));
$context_cat = context_coursecat::instance($category->id);
role_assign($manager_role->id, $u_podadmin->id, $context_cat->id);
echo "Assigned Pod Admin to Test Pod Category\n";

// 4. Create Course "Test Course 101"
$course = $DB->get_record('course', array('shortname' => 'TC101'));
if (!$course) {
    $course_data = new stdClass();
    $course_data->fullname = 'Test Course 101';
    $course_data->shortname = 'TC101';
    $course_data->category = $category->id;
    $course = create_course($course_data);
    echo "Created Course: Test Course 101\n";
} else {
    echo "Course already exists: TC101\n";
}

// 5. Enrol Instructor and Student
$enrol_manual = enrol_get_plugin('manual');
$teacher_role = $DB->get_record('role', array('shortname' => 'editingteacher'));
$student_role = $DB->get_record('role', array('shortname' => 'student'));

$instance = $DB->get_record('enrol', array('courseid' => $course->id, 'enrol' => 'manual'));
if ($instance) {
    $enrol_manual->enrol_user($instance, $u_instructor->id, $teacher_role->id);
    echo "Enrolled Instructor to TC101\n";
    
    $enrol_manual->enrol_user($instance, $u_student->id, $student_role->id);
    echo "Enrolled Student to TC101\n";
}

echo "Setup Complete!\n";
