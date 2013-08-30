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


/**
* @var \mod_surveyactivitybase_renderer
*/
$renderer = $PAGE->get_renderer('surveyactivitybase');

$str = new stdClass();
$str->activity_list = get_string('activity_list',SURVEYACTIVITYBASE_PLUGIN_NAME);

$PAGE->set_context($cm_context);
$PAGE->set_url($FULLME);
$PAGE->set_cm($cm);
$PAGE->set_pagelayout('noregion');
$PAGE->set_title($course->shortname.':'.$sa_base->name);
$PAGE->set_heading($str->activity_list);


echo $renderer->header();

echo $renderer->render_activity_list(new survey\activity_list($sa_base));


echo $renderer->footer();