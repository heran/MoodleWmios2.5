<?php
use wmios\survey as survey;

class mod_surveyactivitybase_renderer extends survey\plugin_renderer_base{

    public function plugin_unsatisfied_dependencies(survey\plugin_manager $plugin_manager,$survey_version, $failed){
        $smarty = $this->_smarty;

        $str = new \stdClass();
        $str->pluginscheckfailed = get_string('pluginscheckfailed', 'admin',
            array('pluginslist' => implode(', ', array_unique($failed))));
        $str->pluginschecktodo = get_string('pluginschecktodo', 'admin');
        $str->continue = get_string('continue', 'admin');
        $str->displayname = get_string('displayname', 'core_plugin');
        $str->rootdir = get_string('rootdir', 'core_plugin');
        $str->source = get_string('source', 'core_plugin');
        $str->versiondb = get_string('versiondb', 'core_plugin');
        $str->versiondisk = get_string('versiondisk', 'core_plugin');
        $str->requires = get_string('requires', 'core_plugin');
        $str->status = get_string('status', 'core_plugin');
        $str->types['activity'] = get_string('activity', SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->plugin_source_standard = get_string('plugin_source_standard', SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->plugin_source_extendsion = get_string('plugin_source_extendsion', SURVEYACTIVITYBASE_PLUGIN_NAME);


        $plugin_types = $plugin_manager->get_plugins();


        $smarty->assign('survey_version',$survey_version);
        $smarty->assign('plugin_types',$plugin_types);
        $smarty->assign('showallplugins',false);
        $smarty->assign('str',$str);
        return $smarty->fetch('plugin_unsatisfied_dependencies.tpl');

    }

    public function plugin_upgrade_check(survey\plugin_manager $plugin_manager,$survey_version,$showallplugins=false){
        $smarty = $this->_smarty;
        $str = new \stdClass();
        $str->displayname =get_string('displayname', 'core_plugin');
        $str->rootdir = get_string('rootdir', 'core_plugin');
        $str->source = get_string('source', 'core_plugin');
        $str->versiondb = get_string('versiondb', 'core_plugin');
        $str->versiondisk = get_string('versiondisk', 'core_plugin');
        $str->requires = get_string('requires', 'core_plugin');
        $str->status = get_string('status', 'core_plugin');
        $str->types['activity'] = get_string('activity', SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->plugin_source_standard = get_string('plugin_source_standard', SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->plugin_source_extendsion = get_string('plugin_source_extendsion', SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->upgradestart = get_string('upgradestart', 'admin');
        $smarty->assign('survey_version',$survey_version);
        $smarty->assign('plugin_types',$plugin_manager->get_plugins());
        $smarty->assign('showallplugins',$showallplugins);
        $smarty->assign('str',$str);

        return $smarty->fetch('plugin_upgrade_check.tpl');
    }

    public function render_activity_list(survey\activity_list $activity_list){
        $smarty = $this->_smarty;

        $str = new \stdClass();
        $str->detail = get_string('detail',SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->name = get_string('name',SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->starttime = get_string('starttime',SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->endtime = get_string('endtime',SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->status = get_string('status',SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->delete = get_string('delete');
       // $str->preiview_report = get_string('preiview_report',SURVEYACTIVITYBASE_PLUGIN_NAME);
       // $str->download_report = get_string('download_report',SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->start = get_string('start',SURVEYACTIVITYBASE_PLUGIN_NAME);
        $str->stop = get_string('stop',SURVEYACTIVITYBASE_PLUGIN_NAME);


        $url = new stdClass();
        $params = array('cmid'=>$activity_list->get_base()->cmid);
        $url->delete_activity = new moodle_url('/mod/surveyactivitybase/delete.php',$params);
        $url->start_activity = new moodle_url('/mod/surveyactivitybase/start.php',$params);
        $url->stop_activity = new moodle_url('/mod/surveyactivitybase/stop.php',$params);

        $smarty->assign('activity_list',$activity_list);
        $smarty->assign('str',$str);
        $smarty->assign('url',$url);


        return $smarty->fetch('render_activity_list.tpl');
    }

}