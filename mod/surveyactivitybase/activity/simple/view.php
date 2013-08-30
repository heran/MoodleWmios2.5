<?php
use wmios\survey as survey;

require_once(dirname(__FILE__).'/../../../../config.php');
require_login();
require_capability('mod/surveyactivity:update_activity',context_system::instance());

require_once($CFG->dirroot.'/mod/surveyactivity/lib.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('mod_surveyactivity');
$PAGE->set_title('问卷调查');
$PAGE->set_heading('问卷调查');

/**
* @var \mod_surveyactivity_activity_simple_renderer
*/
$renderer = $PAGE->get_renderer('mod_surveyactivity_activity_simple');


echo $renderer->header();

echo $renderer->test();

echo $renderer->footer();
