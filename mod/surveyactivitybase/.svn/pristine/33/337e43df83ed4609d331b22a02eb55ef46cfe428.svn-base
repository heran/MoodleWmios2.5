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

$instance_id = required_param('instance_id', PARAM_INT); //mod_surveyactivity table's id
$confirm = optional_param('confirm', 0, PARAM_BOOL);


$PAGE->set_context($cm_context);


$activity_wrapper = survey\activity_wrapper::instance_from_id($instance_id);

if(!$activity_wrapper){
    print_error('Delete Survey Need valid $id');
}

if($activity_wrapper->creator_id != $USER->id && !has_capability('mod/surveyactivitybase:manage_system',$cm_context)){
    print_error('only creator and admin can delete this activity');
}




if (!$confirm or !confirm_sesskey()) {



    $formcontinue = new single_button(
        new moodle_url('/mod/surveyactivitybase/delete.php',
            array('confirm'=>1,'cmid'=>$cmid, 'instance_id'=>$id, 'sesskey'=>sesskey())),
        get_string('yes'));
    $formcancel = new single_button(
        new moodle_url('/mod/surveyactivitybase/view.php?cmid='.$cmid.'#surveyactivity_'.$activity_wrapper->id),
        get_string('no'),
        'get');

    $strdeletecheck = get_string('deletecheck', '', $activity_wrapper->get_activity_component());
    $strdeletecheckfull = get_string('deletecheckfull', '', $activity_wrapper->get_activity_component());

    $PAGE->set_cm($cm);
    $PAGE->set_pagelayout('noregion');
    $PAGE->set_title($course->shortname.':'.$sa_base->name.$strdeletecheck);
    $PAGE->set_heading($strdeletecheck);
    $PAGE->navbar->add($strdeletecheck);

    /**
    * @var \mod_surveyactivitybase_core_renderer
    */
    $renderer = get_renderer('surveyactivitybase');

    echo $renderer->header();
    echo $renderer->box_start('noticebox');
    echo $renderer->confirm($strdeletecheckfull, $formcontinue, $formcancel);
    echo $renderer->box_end();
    echo $renderer->footer();

}else{
    if(!survey\activity_wrapper::delete($id)){
        print_error('Delete Survey Activity Error');
    }else{
        redirect(new moodle_url('/mod/surveyactivitybase/view.php?cmid='.$cmid));
    }
}



