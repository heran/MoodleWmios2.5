<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once(dirname(__FILE__) . '/lib.php');


$settings->add(new admin_setting_heading('dekiwiki', get_string('pluginname', DEKIWIKI_PLUGIN_NAME),
    get_string('pluginname', DEKIWIKI_PLUGIN_NAME)));

$adminsetting = new admin_setting_configtext('rootname', get_string('rootname',DEKIWIKI_PLUGIN_NAME),
    get_string('browse_url_desc', DEKIWIKI_PLUGIN_NAME),'', PARAM_URL);
$adminsetting->plugin = DEKIWIKI_PLUGIN_NAME;
$settings->add($adminsetting);

$adminsetting = new admin_setting_configtext('browse_url', get_string('browse_url', DEKIWIKI_PLUGIN_NAME),
    get_string('browse_url_desc', DEKIWIKI_PLUGIN_NAME),'', PARAM_URL);
$adminsetting->plugin = DEKIWIKI_PLUGIN_NAME;
$settings->add($adminsetting);

$adminsetting = new admin_setting_configtext('server_url', get_string('server_url', DEKIWIKI_PLUGIN_NAME),
    get_string('server_url_desc', DEKIWIKI_PLUGIN_NAME),'', PARAM_URL);
$adminsetting->plugin = DEKIWIKI_PLUGIN_NAME;
$settings->add($adminsetting);

$adminsetting = new admin_setting_configtext(
    'apikey',
    get_string('apikey', DEKIWIKI_PLUGIN_NAME),
    get_string('apikey_desc', DEKIWIKI_PLUGIN_NAME), '', PARAM_TEXT);
$adminsetting->plugin = DEKIWIKI_PLUGIN_NAME;
$settings->add($adminsetting);

$adminsetting = new admin_setting_configtext(
    'superadmin',
    get_string('superadmin', DEKIWIKI_PLUGIN_NAME),
    get_string('superadmin_desc', DEKIWIKI_PLUGIN_NAME), '', PARAM_TEXT);
$adminsetting->plugin = DEKIWIKI_PLUGIN_NAME;
$settings->add($adminsetting);

$adminsetting = new admin_setting_configtext(
    'superpassword',
    get_string('superpassword', DEKIWIKI_PLUGIN_NAME),
    get_string('superpassword_desc', DEKIWIKI_PLUGIN_NAME), '', PARAM_TEXT);
$adminsetting->plugin = DEKIWIKI_PLUGIN_NAME;
$settings->add($adminsetting);
/*
$adminsetting = new admin_setting_configpasswordunmask('password',
    get_string('survey_password', DEKIWIKI_PLUGIN_NAME),
    get_string('survey_password_desc', DEKIWIKI_PLUGIN_NAME), '');
$adminsetting->plugin = DEKIWIKI_PLUGIN_NAME;
$settings->add($adminsetting);*/