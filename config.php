<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = '192.168.1.94';
$CFG->dbname    = 'moodle_test_2.5';
$CFG->dbuser    = 'root';
$CFG->dbpass    = 'root';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbsocket' => 0,
);

$CFG->wwwroot   = 'http://u25.wmios.com';
$CFG->dataroot  = 'D:\\workspace\\moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;
$CFG->defaultblocks_topics = ':search_forums,recent_activity,notes,qanda';

$CFG->keeptempdirectoriesonbackup = true;//Not safe

require_once(dirname(__FILE__) . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
