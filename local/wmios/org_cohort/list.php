<?php
require_once(dirname(__FILE__).'/../../../config.php');
require_once($CFG->dirroot.'/cohort/lib.php');
require_once($CFG->dirroot.'/local/wmios/lib.php');
require_once($CFG->dirroot.'/local/wmios/org_cohort/lib.php');
require_capability('local/wmios:org_cohort_manage',context_system::instance());

$cohorts = wmios_organization_cohort::instances_from_select("idnumber like 'o_%'");

$table = new html_table();

$table->head = array(get_string('cohort'),get_string('edit'));
$table->colclasses = array ('leftalign', 'leftalign');
$table->attributes['class'] = 'admintable generaltable';
$url = new moodle_url('/');
$url = $url->out();
foreach($cohorts as /** @var wmios_organization_cohort*/$cohort)
{
    $table->data[] = array($cohort->name,
        '<a href=""> me</a>'.
        '<a href="'.$url.'/course/edit.php?id='.$cohort->get_special_course(true)->id.'"> course</a>'.
        '<a href="'.$url.'/cohort/edit.php?id='.$cohort->get_special_course(true)->id.'"> cohort</a>'.
        '<a href="'.$url.'/cohort/assign.php?id='.$cohort->id.'"> users</a>');
}

$PAGE->set_context(context_system::instance());

echo $OUTPUT->header();

echo html_writer::table($table);

echo $OUTPUT->footer();