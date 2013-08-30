<?php
//This file is not security.
//define('CLI_SCRIPT', 1);

require_once('config.php');
require_once($CFG->dirroot . '/backup/util/includes/restore_includes.php');

$transaction = $DB->start_delegated_transaction();

// Create new course
$courseid = restore_dbops::create_new_course('restore_test13', 'restore_test13', 1);

// Restore backup into course
$controller = new restore_controller('3c4ba56202b544bc2e42fad10e5b99d3', $courseid, 
    backup::INTERACTIVE_NO, backup::MODE_SAMESITE, 2,
    backup::TARGET_NEW_COURSE);
$controller->execute_precheck();
$controller->execute_plan();

// Commit
$transaction->allow_commit();