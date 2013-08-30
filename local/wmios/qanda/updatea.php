<?php

/**
 * Update or create an answer
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

$qandaid = optional_param('qandaid',0,PARAM_ACTION);//Question's id
$id = optional_param('id',0,PARAM_INT);//Answer's id
$courseid = required_param('courseid',PARAM_INT);//Course's id
$answer = required_param('answer',PARAM_CLEANHTML);//Answer's content

if(!$id && !$qandaid){
    print_error(500);
}

$COURSE = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);

require_login($COURSE);

$course_context = context_course::instance($courseid);

require_capability('local/wmios:qanda_update_question',$course_context);


$userid = $USER->id;
$result = true;
$answer_o_id = 0;
if($id){
    //Update
    $answer_o = wmios_qanda_answer::instance_for_id($id);
    if($answer_o){
        $answer_o->answer = $answer;
        $result = (bool)$answer_o->update();
    }
    $answer_o_id = $id;
}else{
    //Add
    $answer_o = wmios_qanda_answer::create($answer,$userid,$qandaid);
    if($answer_o){
        $result = true;
        $answer_o_id = $answer_o->id;
    }
}

echo $OUTPUT->header();
if(defined('AJAX_SCRIPT') && AJAX_SCRIPT){
    echo json_encode(array('status'=>$result,'id'=>$answer_o_id));
    die();
}