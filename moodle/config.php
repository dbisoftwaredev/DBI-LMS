<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mariadb';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'bitnami_moodle';
$CFG->dbuser    = 'root';
$CFG->dbpass    = 'paul';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => 3306,
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);

if (empty($_SERVER['HTTP_HOST'])) {
  $_SERVER['HTTP_HOST'] = 'localhost:8000';
}
$CFG->wwwroot   = 'http://localhost:8000';
$CFG->dataroot  = 'C:/myProjects/dbi/noodle_project/moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

$CFG->debug = 32767; // DEBUG_DEVELOPER
$CFG->debugdisplay = 1;

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
