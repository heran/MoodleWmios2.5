<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once(dirname(__FILE__) . '/lib.php');


$settings->add(new admin_setting_heading('survey', get_string('survey', SURVEYACTIVITYBASE_PLUGIN_NAME),
    get_string('survey', SURVEYACTIVITYBASE_PLUGIN_NAME)));

$adminsetting = new admin_setting_configtext('uri', get_string('survey_uri', SURVEYACTIVITYBASE_PLUGIN_NAME),
    get_string('survey_uri_desc', SURVEYACTIVITYBASE_PLUGIN_NAME),'', PARAM_URL);
$adminsetting->plugin = SURVEYACTIVITYBASE_PLUGIN_NAME;
$settings->add($adminsetting);

$adminsetting = new admin_setting_configtext(
    'username',
    get_string('username', SURVEYACTIVITYBASE_PLUGIN_NAME),
    get_string('username', SURVEYACTIVITYBASE_PLUGIN_NAME), '', PARAM_USERNAME);
$adminsetting->plugin = SURVEYACTIVITYBASE_PLUGIN_NAME;
$settings->add($adminsetting);

$adminsetting = new admin_setting_configpasswordunmask('password',
    get_string('survey_password', SURVEYACTIVITYBASE_PLUGIN_NAME),
    get_string('survey_password_desc', SURVEYACTIVITYBASE_PLUGIN_NAME), '');
$adminsetting->plugin = SURVEYACTIVITYBASE_PLUGIN_NAME;
$settings->add($adminsetting);

//subplugins setting tree
\wmios\survey\tool::add_admin_plugin_settings($ADMIN, $settings, $module);