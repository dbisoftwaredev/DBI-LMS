<?php
define('CLI_SCRIPT', true);
require(__DIR__ . '/moodle/config.php');

$roles = role_get_names(null, ROLENAME_BOTH);

echo "Existing Roles:\n";
foreach ($roles as $role) {
    echo "- " . $role->shortname . " (" . $role->localname . ")\n";
}
