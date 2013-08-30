<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once(dirname(__FILE__) . '/locallib.php');

if ($hassiteconfig) {
    $ADMIN->add('localplugins',new admin_category('localwmioscat',get_string('pluginname', 'local_wmios')));
    $settings = new admin_settingpage('local_wmios', get_string('pluginname', 'local_wmios'));
    $ADMIN->add('localwmioscat', $settings);


    $settings->add(new admin_setting_heading('document', get_string('document', LOCAL_WMIOS_PLUGIN_NAME),
                       get_string('document_desc', LOCAL_WMIOS_PLUGIN_NAME)));

    $adminsetting = new admin_setting_configtext(
        'document_apiurl',
        get_string('document_apiurl', LOCAL_WMIOS_PLUGIN_NAME),
        get_string('document_apiurl_desc', LOCAL_WMIOS_PLUGIN_NAME), '', PARAM_URL);
    $adminsetting->plugin = LOCAL_WMIOS_PLUGIN_NAME;
    $settings->add($adminsetting);

    $adminsetting = new admin_setting_configtext(
        'document_client_key',
        get_string('document_client_key', LOCAL_WMIOS_PLUGIN_NAME),
        get_string('document_client_key_desc', LOCAL_WMIOS_PLUGIN_NAME), '', PARAM_URL);
    $adminsetting->plugin = LOCAL_WMIOS_PLUGIN_NAME;
    $settings->add($adminsetting);

    $adminsetting = new admin_setting_configtext(
        'document_client_pas',
        get_string('document_client_pas', LOCAL_WMIOS_PLUGIN_NAME),
        get_string('document_client_pas_desc', LOCAL_WMIOS_PLUGIN_NAME), '', PARAM_URL);
    $adminsetting->plugin = LOCAL_WMIOS_PLUGIN_NAME;
    $settings->add($adminsetting);
    
    //cohort organization
    $tmp_url = new moodle_url('/local/wmios/org_cohort/list.php');
    $ADMIN->add('localwmioscat',
        new admin_externalpage('local_wmios_org_cohort',
            get_string('organization_manage',LOCAL_WMIOS_PLUGIN_NAME),$tmp_url->out()));
}