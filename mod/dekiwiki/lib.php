<?php

defined('MOODLE_INTERNAL') || die;
require_once (dirname(__FILE__) . '/locallib.php');
require_once ($CFG->dirroot.'/local/wmios/completion/lib.php');
require_once ($CFG->dirroot.'/local/wmios/lib.php');


function dekiwiki_supports($feature) {
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


function dekiwiki_reset_userdata($data) {
    return array();
}


function dekiwiki_add_instance($data, $mform = null) {
    global $USER,$COURSE;

    $data->cmid = $data->coursemodule;
    $data->course = $COURSE->id;
    $data->timemodified = time();
    $dekiwiki = new dekiwiki_instance($data);
    $dekiwiki->save();
    return $dekiwiki->id;
}


function dekiwiki_update_instance($data, $mform)
{
    global $USER,$COURSE;
    $data->timemodified = time();

    /** @var dekiwiki_instance*/
    $dekiwiki = dekiwiki_instance::instance_from_id($data->instance);
    $dekiwiki->setFields((array)$data);
    $dekiwiki->save();

    return true;


}


function dekiwiki_delete_instance($id) {//TODO delete document_completion

    return true;
}