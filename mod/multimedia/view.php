<?php

/**
* Page module version information
*
* @package    mod
* @subpackage multimedia
* @copyright  2013 Wmios (http://wmios.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir.'/conditionlib.php');

/** @var core_renderer*/
global $OUTPUT;

$id      = optional_param('id', 0, PARAM_INT); // Course Module ID
$p       = optional_param('p', 0, PARAM_INT);  // Multimedia instance ID
$inpopup = optional_param('inpopup', 0, PARAM_BOOL);
$wt = optional_param('wt','html',PARAM_ACTION);


if ($p) {
    if (!$multimedia = $DB->get_record('multimedia', array('id'=>$p))) {
        print_error('invalidaccessparameter');
    }
    $cm = get_coursemodule_from_instance('multimedia', $multimedia->id, $multimedia->course, false, MUST_EXIST);

} else {
    if (!$cm = get_coursemodule_from_id('multimedia', $id)) {
        print_error('invalidcoursemodule');
    }
    $multimedia = $DB->get_record('multimedia', array('id'=>$cm->instance), '*', MUST_EXIST);
}

$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/multimedia:view', $context);

add_to_log($course->id, 'multimedia', 'view', 'view.php?id='.$cm->id, $multimedia->id, $cm->id);

// Update 'viewed' state if required by completion system
require_once($CFG->libdir . '/completionlib.php');
$completion = new wmios_completion_info($course);
$completion->set_module_viewed($cm);
if($completion->enabled_ratio($cm)){
    $PAGE->requires->js(new moodle_url('/mod/multimedia/js/view.js'),false);
}

$PAGE->set_url('/mod/multimedia/view.php', array('id' => $cm->id));



$cm = get_fast_modinfo($course,$USER->id)->get_cm($cm->id);
$ci = new condition_info($cm);
$info = '';
$enabled = true;
if($ci){
    $enabled = $ci->is_available($info);
}
if($enabled){
    $content = file_rewrite_pluginfile_urls($multimedia->content, 'pluginfile.php', $context->id, 'mod_multimedia', 'content', null);
    $formatoptions = new stdClass;
    $formatoptions->noclean = true;
    $formatoptions->overflowdiv = true;
    $formatoptions->context = $context;
    $content = format_text($content, $multimedia->contentformat, $formatoptions);
}else{
    $content = $info;
}

add_to_log($course->id, 'multimedia', 'view', 'view.php?id='.$cm->id, $multimedia->id, $cm->id);

/** @var mod_multimedia_renderer*/
$renderer = $PAGE->get_renderer('mod_multimedia');
if($wt=='xml'){
    echo $renderer->render_page_xml($content,$multimedia,$cm,$enabled);
}else{
    $renderer->print_page($content,$multimedia,$cm);
}
die();