<?php
require_once(dirname(__FILE__).'/../../../config.php');
require_once($CFG->dirroot.'/cohort/lib.php');
require_once($CFG->dirroot.'/local/wmios/lib.php');
require_once($CFG->dirroot.'/local/wmios/org_cohort/lib.php');
require_capability('local/wmios:org_cohort_manage',context_system::instance());

$key = required_param('key', PARAM_ALPHANUM);
$old_key = optional_param('old_key', false, PARAM_ALPHANUM);

// edit mode
$edit = $old_key !== false;

//original cohort,when edit mode , from old key; new mode , from key.
//when edit mode, change old_key to key.
//when old_key not valid, get empty cohort. create new one from key.
$cohort = wmios_organization_cohort::instance_belong_to_organization($edit ? $old_key : $key);

//create
if(empty($cohort))
{
    $transaction = $DB->start_delegated_transaction();
    $data = new stdClass();
    $data->idnumber = 'o_'.$key;
    $data->name = required_param('name', PARAM_TEXT);
    $context = context_system::instance();
    $data->contextid = $context->id;
    $data->id = cohort_add_cohort($data);
    $cohort = new wmios_organization_cohort($data);
    $cohort->create_special_course();
    $transaction->allow_commit();
}

if($edit && !$cohort->belong_to_organization($key))
{
    $cohort->change_organization($key, required_param('name', PARAM_TEXT));
}

redirect(new moodle_url('/local/wmios/org_cohort/list.php'));