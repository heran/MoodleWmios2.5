<?php
//This file is not security.
//define('CLI_SCRIPT', 1);

require_once('config.php');
require_once($CFG->dirroot . '/backup/util/includes/backup_includes.php');
 
$course_module_to_backup = 5; // Set this to one existing choice cmid in your dev site
$user_doing_the_backup   = 2; // Set this to the id of your admin accouun
 
/*$bc = new backup_controller(backup::TYPE_1ACTIVITY, $course_module_to_backup, backup::FORMAT_MOODLE,
                            backup::INTERACTIVE_NO, backup::MODE_GENERAL, $user_doing_the_backup);*/
$bc = new backup_controller(backup::TYPE_1COURSE, 3, backup::FORMAT_MOODLE, 
                            backup::INTERACTIVE_NO, backup::MODE_GENERAL, 2);
$bc->execute_plan();