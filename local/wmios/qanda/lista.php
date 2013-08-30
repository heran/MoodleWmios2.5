<?php
/**
 * Get question's answer list
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
global $USER,$COURSE,$DB,$PAGE;

$qandaid = optional_param('qandaid',0,PARAM_ACTION);//The qustion's id
$id = optional_param('id',0,PARAM_INT);//The answer's id
$courseid = required_param('courseid',PARAM_INT);//The course's id

if(!$id && !$qandaid){
    print_error(500);
}

$COURSE = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);

require_login($COURSE);

$course_context = context_course::instance($courseid);

require_capability('local/wmios:qanda_list_qanda',$course_context);

$userid = $USER->id;
$answers = array();
if($id){
    //One answer
    $answers[] = wmios_qanda_answer::instance_for_id($id);
}else{
    //The question's answers
    $answers = wmios_qanda_answer::instances_for_qandaid($qandaid,null,true);
}

//Users' id
$userids = array();
foreach($answers as $answer){  
    if(!in_array($answer->userid,$userids)){
        $userids[] = $answer->userid;
    }
}

$question = wmios_qanda::instance_for_id($qandaid);
//The whole course's question or the course module's
if($question->cmid - 0){
    $cm = get_coursemodule_from_id(null,$question->cmid);
}else{
    $cm = null;
}

$userids[] = $question->userid;

//All users' info
$users = array();
if($userids){
    $PAGE->set_context(context_system::instance());
    $users = $DB->get_records_select('user','id in ('.implode(',',$userids).')',null,null,'id,picture,firstname,lastname,imagealt,email');
    foreach($users as $user){
        $userpicture = new user_picture($user);
        $user->userpicture = $userpicture->get_url($PAGE, null)->out();
        $user->fullname = fullname($user,true);
    }
}

//Question's author
$quser = $users[$question->userid];
$quser->who_asks = get_string('who_asks','local_wmios',$quser->fullname);

echo $OUTPUT->header();
if(defined('AJAX_SCRIPT') && AJAX_SCRIPT){
    echo json_encode(array('status'=>1,'list'=>$answers,'users'=>$users,'question'=>$question,'quser'=>$quser,'cm'=>$cm));
    die();
}