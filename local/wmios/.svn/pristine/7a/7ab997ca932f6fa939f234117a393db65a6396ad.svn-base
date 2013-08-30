<?php

/**
 * Get note list
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


$cmid = optional_param('cmid',0,PARAM_ACTION);//course module's id
$position = optional_param('position',0,PARAM_INT);
$note_id = optional_param('note_id',0,PARAM_INT);
$courseid = optional_param('courseid',0,PARAM_INT);

//we must know client need which course or which course module.
if(!$courseid && !$cmid){
    print_error(500);
}

$mod = $courseid ? null : get_coursemodule_from_id(null,$cmid);
$COURSE = $DB->get_record('course', array('id'=>$courseid ?: $mod->course), '*', MUST_EXIST);
$courseid = $COURSE->id;

$course_context = context_course::instance($courseid);

require_login($COURSE,false,$mod);

require_capability('local/wmios:notes_list_note',$course_context);

$userid = $USER->id;
$notes = array();
if($note_id){
    //one note
    $notes[] = wmios_note::note_for_id($note_id);
}elseif(!$cmid && $courseid){
    //whole course's notes
    $notes = wmios_note::notes_for_userid_and_courseid($userid,$courseid,true);
}else{
    //whole course module's notes.
    $notes = wmios_note::notes_for_userid_and_cmid_range($userid,$cmid,$position,$position);
}

$results = array();
$cms = array();
if($mod){
   $cms[$mod->id] = $mod; 
}
foreach($notes as $note){
    $fields = $note->get_fields();
    if($fields['cmid']){
        if(!isset($cms[$fields['cmid']])){
            $cms[$fields['cmid']] = get_coursemodule_from_id(null,$fields['cmid']);
        }
        
        //If a mod support completion ratio.we need know how to display the position str
        //100s to 01:40 (1m40s).
        $func = $cms[$fields['cmid']]->modname .'_format_unit';
        require_once($CFG->dirroot.'/mod/'.$cms[$fields['cmid']]->modname.'/lib.php');
        if(function_exists($func)){
            $fields['position_str'] = $func($cms[$fields['cmid']],$fields['position']);
        }else{
            $fields['position_str'] = '';
        }
    }else{
        $fields['position_str'] = '';
    }
    $results[] = $fields;
}

echo $OUTPUT->header();
if(defined('AJAX_SCRIPT') && AJAX_SCRIPT){
    echo json_encode(array('status'=>1,'list'=>$results,'cms'=>$cms));
    die();
}