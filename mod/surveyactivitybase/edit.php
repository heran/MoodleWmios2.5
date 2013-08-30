<?php
use wmios\survey as survey;

require_once(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/lib.php');

$cmid = optional_param('cmid', 0, PARAM_INT); //course module
if(!$cmid)
{
    $cmid = optional_param('id',0, PARAM_INT);
}
if($cmid)
{
    $cm = get_coursemodule_from_id('surveyactivitybase', $cmid);
    $cm_context = context_module::instance($cm->id);
    require_capability('mod/surveyactivitybase:update_activity', $cm_context);
    $sa_base = survey\activity_base::instance_from_id($cm->instance);
}else
{
    $base_id = required_param('base_id',PARAM_INT);
    $sa_base = document_base::instance_from_id($base_id);
    $cm = get_coursemodule_from_instance('surveyactivitybase', $base_id, $sa_base->course_id, false, MUST_EXIST);
    $cm_context = context_module::instance($cm->id);
    require_capability('mod/surveyactivitybase:update_activity', $cm_context);
}
$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
require_course_login($course, true, $cm);
$sa_base->set_course($course);
$sa_base->set_cm($cm);
$sa_base->set_context($cm_context);
survey\tool::set_surveyactivity_base($sa_base);

$add    = optional_param('add', '', PARAM_ALPHA);  //activity name
$update = optional_param('update', 0, PARAM_INT); //mod_surveyactivity_instance table's id

$userid = optional_param_array('userid',array(),PARAM_INT);

$str = new stdClass();
$str->activity_edit = get_string('activity_edit',SURVEYACTIVITYBASE_PLUGIN_NAME);

$PAGE->set_context($cm_context);
$PAGE->set_url($FULLME);
$PAGE->set_cm($cm);
$PAGE->set_pagelayout('noregion');
$PAGE->set_title($course->shortname.':'.$sa_base->name);
$PAGE->set_heading($str->activity_edit);

/**
* The activity class name
*
* @var String
*/
$activity_name = null;

/**
* The data
*
* @var array
*/
$data = array();

/**
*
* @var \wmios\survey\activity_wrapper
*/
$activity_wrapper = null;

if(!empty($add)){
    $activity_name = $add;
}elseif(!empty($update)){
    $activity_wrapper = survey\activity_wrapper::instance_from_id($update);
    if($activity_wrapper->creator_id != $USER->id && !has_capability('mod/surveyactivitybase:manage_system',$cm_context)){
        print_error('only creator and admin can edit this activity');
    }
    $activity_name = $activity_wrapper->activity;
}else{
    print_error('invalidaction');
}

require_capability('surveyactivity/'.$activity_name.':edit',$cm_context);

/**
* @var \wmios\survey\activity
*/
$activity_class_name = survey\activity_wrapper::get_interface_class_by_name($activity_name);


$mform = new survey\moodleform_activity($activity_name, $activity_wrapper,$FULLME);

if(!empty($add)){
    $data = $activity_class_name::get_add_instance_default_data();
    $data['general_activity'] = $activity_name;
    $data['general_id'] = 0;

    $data['general_userid'] = $userid ;
}elseif(!empty($update)){
    $data = $activity_wrapper->get_activity_instance()->get_update_instance_data();
    $data['general_name'] = $activity_wrapper->name;
    $data['general_description']['text'] = $activity_wrapper->description;
    $data['general_starttime'] = $activity_wrapper->starttime;
    $data['general_endtime'] = $activity_wrapper->endtime;
    $data['general_activity'] = $activity_name;
    $data['general_id'] = $update;   
}
$mform->set_data($data);

/**
*
* @var \mod_surveyactivitybase_core_renderer
*/
$renderer = $PAGE->get_renderer('surveyactivitybase');

$activity_list_url = new moodle_url('/mod/surveyactivitybase/view.php?cmid='.$cmid);

if ($mform->is_cancelled()) {
    redirect($activity_list_url);
} else if ($fromform = $mform->get_data()) {
    $fromform = (array)$fromform;

    $general = new stdClass();
    $special = new stdClass();
    $general->starttime = 0;
    $general->endtime = 0;
    $general->base_id = $sa_base->id;
    $general->cmid = $cm->id;
    foreach($fromform as $k=>$v){
        if(substr($k,0,strlen('general_'))==='general_'){
            $k = str_ireplace('general_','',$k);
            $general->$k = $v;
        }else{
            $special->$k = $v;
        }
    }
    if(!empty($general->description)) {
        $general->description = $general->description['text'];
    }

    $transaction = $DB->start_delegated_transaction();
    if(!empty($add)){
        $instance_id = $activity_class_name::add_instance($general,$special,$mform);
        if(!is_int($instance_id) || $instance_id < 0 ){
            throw new \Exception('can not create activity instance');
        }
        $general->instance_id = $instance_id;
        $general->creator_id = $USER->id;
        $activity_wrapper = new survey\activity_wrapper($general);
        $activity_wrapper->save();
    }else{
        $activity_wrapper->get_activity_instance()->update_instance($general,$special,$mform);

        $activity_wrapper->setFields($general);

        if(!$activity_wrapper->save()){
            throw new Exception('can not update survey instance');
        }
        $activity_list_url->set_anchor('survey-'.$activity_wrapper->id);
    }
    $transaction->allow_commit();

    if($general->save_and_return){
        redirect($activity_list_url);
    }else{
        redirect( $activity_wrapper->get_activity_instance()->get_view_url());
    }

}else{
    echo $renderer->header();
    $mform->display();

    echo $renderer->footer();
}
die();