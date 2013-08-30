<?php
require_once(dirname(__FILE__).'/lib.php');

$settings->add(
    new admin_setting_configtext(SURVEYACTIVITY_EMPLOYENGAGE_PLUGIN_NAME.'/base_survey_id',
    get_string('base_survey_id',SURVEYACTIVITY_EMPLOYENGAGE_PLUGIN_NAME),
    get_string('base_survey_id_help',SURVEYACTIVITY_EMPLOYENGAGE_PLUGIN_NAME),
    0));