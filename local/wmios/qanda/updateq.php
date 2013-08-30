<?php

/**
 * Update or create a question
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
$position = optional_param('position',0,PARAM_INT);//Where
$id = optional_param('id',0,PARAM_INT);//The question's id
$courseid = optional_param('courseid',0,PARAM_INT);//Which course.
$title = required_param('title',PARAM_CLEANHTML);//question's title
$content = required_param('content',PARAM_CLEANHTML);//question's content

//which course or which course module
if(!$courseid && !$cmid ){
    print_error(500);
}

$mod = $courseid ? null : get_coursemodule_from_id(null,$cmid);
$COURSE = $DB->get_record('course', array('id'=>$courseid ?: $mod->course), '*', MUST_EXIST);
$courseid = $COURSE->id;

$course_context = context_course::instance($courseid);

require_login($COURSE,false,$mod);

require_capability('local/wmios:qanda_update_question',$course_context);

$userid = $USER->id;
$result = false;
$question_id = 0;
if($id){
    //update
    $question = wmios_qanda::instance_for_id($id);
    if($question){
        $question->title = $title;
        $question->content = $content;
        $result = (bool)$question->update();
    }
    $question_id = $id;
}else{
    //add
    $question = wmios_qanda::create($title,$content,$userid,$courseid,$cmid,$position);
    if($question){
        $result = true;
        $question_id = $question->id;
    }
}

echo $OUTPUT->header();
if(defined('AJAX_SCRIPT') && AJAX_SCRIPT){
    echo json_encode(array('status'=>$result,'id'=>$question_id));
    die();
}