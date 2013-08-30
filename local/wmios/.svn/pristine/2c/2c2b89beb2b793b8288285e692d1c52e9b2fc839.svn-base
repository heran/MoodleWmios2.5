<?php


/**
 * Delete one Note
 * Require params are: note_id courseid
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

$note_id = required_param('note_id',PARAM_INT);
$courseid = required_param('courseid',PARAM_INT);

$COURSE = $DB->get_record('course', array('id'=>$courseid ), '*', MUST_EXIST);
$course_context = context_course::instance($courseid);

require_login($COURSE,false);

require_capability('local/wmios:notes_update_note',$course_context);

$note = wmios_note::note_for_id($note_id);

$result = false;
$info = '';
if(!$note){
    $info = "There isn't the note";
}elseif($note->userid == $USER->id){
    //Only the author can delete it.
    $result = (bool)wmios_note::delete($note_id);
}else{
    $info = 'Only author can delete the note.';
}

echo $OUTPUT->header();
if(defined('AJAX_SCRIPT') && AJAX_SCRIPT){
    echo json_encode(array('status'=>$result,'note_id'=>$note_id,'info'=>$info));
    die();
}