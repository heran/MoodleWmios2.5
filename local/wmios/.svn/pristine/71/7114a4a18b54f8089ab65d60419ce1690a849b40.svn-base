<?php

/**
 * Get question list
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

require_login();
$cmid = optional_param('cmid',0,PARAM_ACTION);//Which course module
$position = optional_param('position',0,PARAM_INT);//Where in the module
$id = optional_param('id',0,PARAM_INT);//One question's id
$courseid = optional_param('courseid',0,PARAM_INT);//Course's id

//We must know which course or which module.
if(!$courseid && !$cmid){
    print_error(500);
}

$mod = $courseid ? null : get_coursemodule_from_id(null,$cmid);
$COURSE = $DB->get_record('course', array('id'=>$courseid ?: $mod->course), '*', MUST_EXIST);
$courseid = $COURSE->id;

$course_context = context_course::instance($courseid);

require_login($COURSE,false,$mod);

require_capability('local/wmios:qanda_list_qanda',$course_context);

$userid = $USER->id;
$questions = array();
if($id){
    //One question
    $questions[] = wmios_qanda::instance_for_id($id);
}elseif(!$cmid && $courseid){
    //All questions of the course
    $questions = wmios_qanda::instances_for_courseid($courseid,true);
}else{
    //All questions of the course module
    $questions = wmios_qanda::instances_for_cmid_range($cmid,$position,$position);
}

//course modules.
$cms = array();
if($mod){
   $cms[$mod->id] = $mod; 
}
$userids = array();
foreach($questions as $question){
    if($question->cmid){
        if(!isset($cms[$question->cmid])){
            $cms[$question->cmid] = get_coursemodule_from_id(null,$question->cmid);
        }
        $question->set_cm($cms[$question->cmid]);
    }
    if(!in_array($question->userid,$userids)){
        $userids[] = $question->userid; 
    }
}

//users
$users = array();
if($userids){
    $users = $DB->get_records_select('user','id in ('.implode(',',$userids).')',null,null,'id,username,firstname,lastname');
}
foreach($users as $user){
    $user->fullname = fullname($user,true);
}

echo $OUTPUT->header();
if(defined('AJAX_SCRIPT') && AJAX_SCRIPT){
    echo json_encode(array('status'=>1,'list'=>$questions,'cms'=>$cms,'users'=>$users));
    die();
}