<?php
use wmios\survey as survey;

class surveyactivity_employengage_renderer extends survey\plugin_renderer_base{

    public function render_activity_details($arr,$wrapper)
    {
        global $CFG;
        $smarty = $this->_smarty;
        $str = new stdClass();
        $str->checkreports = get_string('checkreports','surveyactivity_employengage');
        $str->name = get_string('name','surveyactivity_employengage');
        $str->gender = get_string('gender','surveyactivity_employengage');
        $str->email = get_string('email','surveyactivity_employengage');
        $str->departments = get_string('departments','surveyactivity_employengage');
        $str->position = get_string('position','surveyactivity_employengage');
        $str->rstatus = get_string('rstatus','surveyactivity_employengage');
        $str->startime = get_string('starttime','surveyactivity_employengage');
        $str->endtime = get_string('endtime','surveyactivity_employengage');
        $str->status = get_string('status','surveyactivity_employengage');

        $smarty->assign('str',$str);
        $smarty->assign('data',$wrapper);        
        $smarty->assign('rows',$arr);

        $smarty->assign('report_url',new moodle_url('/mod/surveyactivitybase/activity/employengage/report.php'));
        return $smarty->fetch('details.tpl');
    }

    public function render_activity_reports($wrapper)
    {
        global $CFG;
        $smarty = $this->_smarty;

        $str = new stdClass();
        $str->startime = get_string('starttime','surveyactivity_employengage');
        $str->endtime = get_string('endtime','surveyactivity_employengage');
        $str->status = get_string('status','surveyactivity_employengage');
        $str->weidu = get_String('weidu','surveyactivity_employengage');
        $str->relists = get_string('relists','surveyactivity_employengage');
        $str->tu = get_string('tu','surveyactivity_employengage');
        $str->downloads = get_string('downloads','surveyactivity_employengage');

        $smarty->assign('data',$wrapper);

        $smarty->assign('report_url',new moodle_url('/mod/surveyactivitybase/activity/employengage/downloads.php'));
        $smarty->assign('url',new moodle_url('/mod/surveyactivitybase/activity/employengage/report.php'));

        $smarty->assign('list_url',new moodle_url('/mod/surveyactivitybase/view.php'));

        $smarty->assign('str',$str);

        return $smarty->fetch('reports.tpl');
    }

}
