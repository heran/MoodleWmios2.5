<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

$plugin->version            = 2013052406;
$plugin->component          = 'local_wmios';
$plugin->requires           = 2012120300;
$plugin->dependencies           = array('enrol_cohort'=>2013050100);
