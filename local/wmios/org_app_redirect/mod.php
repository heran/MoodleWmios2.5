<?php
require_once(dirname(__FILE__).'/../../../config.php');
require_once($CFG->dirroot.'/local/wmios/lib.php');
require_once($CFG->dirroot.'/local/wmios/org_cohort/lib.php');
require_once(dirname(__FILE__).'/lib.php');
require_login();

$mod_name = required_param('component', PARAM_ALPHANUMEXT);

/** @var wmios_organization_cohort*/
$cohorts = wmios_organization_cohort::instances_user_belong_to($USER);
$special_courses = array();
if(!empty($cohorts))
{
    $special_courses = array();
    foreach($cohorts as $cohort)
    {
        $special_courses[] = $cohort->get_special_course(false);
    }
}else{
    $special_courses = array($SITE);
}
$course_mods = array();
$courses = array();
foreach($special_courses as $course)
{
    $course_mods[$course->id] = get_coursemodules_in_course($mod_name, $course->id);
    $courses[$course->id] = $course;
}
if(count($course_mods) == 1)
{
    $mods = current($course_mods);
    if(count($mods) == 1)
    {
        $mod = current($mods);
        $url = new moodle_url("/mod/{$mod_name}/view.php?id={$mod->id}");
        header('Location:'.$url->out(false));
        die();
    }
}

$PAGE->set_context(context_system::instance());
echo $OUTPUT->header();
if(count($course_mods))
{
    reset($course_mods);

    $table = new html_table();

    $table->head = array(get_string('course'),get_string('modulename',$mod_name));
    $table->colclasses = array ('leftalign', 'leftalign');
    $table->attributes['class'] = 'generaltable';

    $url = new moodle_url('/');
    $url = $url->out();
    foreach($course_mods as $courseid=>$mods)
    {
        foreach($mods as $mod)
        {
        $table->data[] = array(
            '<a href="'.$url.'/course/view.php?id='.$courses[$courseid]->id.'" target="_self">'.$courses[$courseid]->fullname.'</a>',
            '<a href="'.$url.'/mod/'.$mod_name.'/view.php?id='.$mod->id.'">'.$mod->name.'</a>');
        }

    }
    echo html_writer::table($table);
}else{
    print_error('no mod');
}

echo $OUTPUT->footer();