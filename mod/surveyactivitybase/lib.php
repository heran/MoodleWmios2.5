<?php
use wmios\survey as survey;

defined('MOODLE_INTERNAL') || die;

require_once (dirname(__FILE__) . '/lib/locallib.php');
require_once ($CFG->dirroot.'/local/wmios/completion/lib.php');
require_once ($CFG->dirroot.'/local/wmios/lib.php');

define('SURVEYACTIVITYBASE_PLUGIN_NAME','surveyactivitybase');

/**
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function surveyactivitybase_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:                     return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_GROUPS:                            return false;
        case FEATURE_GROUPINGS:                         return false;
        case FEATURE_GROUPMEMBERSONLY:                  return true;
        case FEATURE_MOD_INTRO:                         return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:           return false;
        case FEATURE_COMPLETION_HAS_RULES:              return false;
        case FEATURE_MODEDIT_DEFAULT_COMPLETION:        return false;
        case FEATURE_GRADE_HAS_GRADE:                   return false;
        case FEATURE_GRADE_OUTCOMES:                    return false;
        case FEATURE_BACKUP_MOODLE2:                    return true;
        case FEATURE_SHOW_DESCRIPTION:                  return true;
        case WMIOS_FEATURE_COMPLETION_TRACKS_RATIOS:    return false;

        default: return null;
    }
}

/**
 * This function is used by the reset_course_userdata function in moodlelib.
 * @param $data the data submitted from the reset course.
 * @return array status array
 */
function surveyactivitybase_reset_userdata($data) {
    return array();
}

/**
 * @param stdClass $data
 * @param mod_surveyactivity_mod_form $mform
 * @return int new multimedia instance id
 */
function surveyactivitybase_add_instance($data, $mform = null) {
    global $USER,$COURSE,$DB;

    $data->cmid = $data->coursemodule;
    $data->course = $COURSE->id;
    $data->timemodified = time();
    $base = new survey\activity_base($data);
    $base->save();
    return $base->id;
}

/**
 * @param object $data
 * @param object $mform
 * @return bool true
 */
function surveyactivitybase_update_instance($data, $mform)
{
    global $USER,$COURSE,$DB;

    $data->id = $data->instance;
    $data->cmid = $data->coursemodule;
    $data->course = $COURSE->id;
    $data->timemodified = time();
    $base = new survey\activity_base($data);
    $base->save();
    return true;
}

/**
 * @param int $id
 * @return bool true
 */
function surveyactivitybase_delete_instance($id) {
    return true;
}