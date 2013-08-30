<?php

/**
* Update or create one Note
* 
*
* @package    local
* @subpackage wmios
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

if ($_GET['wt'] == 'json' && !defined('AJAX_SCRIPT')) {
    define('AJAX_SCRIPT', true);
}

require_once(dirname(__FILE__).'/../../../config.php');
require_once(dirname(__FILE__).'/lib.php');
global $USER,$COURSE,$DB;

$cmid = optional_param('cmid',0,PARAM_ACTION);
$position = optional_param('position',0,PARAM_INT);
$note_id = optional_param('note_id',0,PARAM_INT);
$courseid = optional_param('courseid',0,PARAM_INT);
$text = required_param('text',PARAM_CLEANHTML);

//we must know client need which course or which course module.
if(!$courseid && !$cmid){
    print_error(500);
}
$mod = $courseid ? null : get_coursemodule_from_id(null,$cmid);
$COURSE = $DB->get_record('course', array('id'=>$courseid ?: $mod->course), '*', MUST_EXIST);
$courseid = $COURSE->id;

$course_context = context_course::instance($courseid);

require_login($COURSE,false,$mod);//$mod may be null

require_capability('local/wmios:notes_update_note',$course_context);

$userid = $USER->id;
if($note_id){
    //update a note
    $note = wmios_note::note_for_id($note_id);
    if($note){
        $note->text = $text;
        $note->update();
    }
}else{
    //create a note
    $note = wmios_note::create($text,$userid,$courseid,$cmid,$position);
    $note_id = $note->id;
}

echo $OUTPUT->header();
if(defined('AJAX_SCRIPT') && AJAX_SCRIPT){
    echo json_encode(array('status'=>1,'note_id'=>$note_id));
    die();
}