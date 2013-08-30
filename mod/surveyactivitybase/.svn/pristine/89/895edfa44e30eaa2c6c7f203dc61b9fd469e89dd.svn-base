<?php
use wmios\survey as survey;
require_once(dirname(__FILE__).'/../../../../config.php');
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

$wrapper = survey\activity_wrapper::instance_from_id(required_param('id',PARAM_INT));

//调查ID
$surveyid = $wrapper->get_activity_instance()->get_instance()->survey_id;

//参与人员的信息
$querys = '';
$rows = $wrapper->get_activity_instance()->users_reports($querys);

if(!$rows)
{
    throw new \moodle_exception('database is wrong');
}

$renderer = $PAGE->get_renderer('surveyactivity_employengage');

echo $renderer->header();

echo $renderer->render_activity_details($rows,$wrapper);

echo $renderer->footer();