<?php
namespace wmios\survey;
use \MoodleQuickForm,\moodle_url,\stdClass;

class activity_simple extends activity{
    public static function process_update_form(MoodleQuickForm $mform,activity_wrapper $activity_wrapper = null){
        $mform->addElement('text','dd','ss');
       // $mform->addElement('select', 'theme', get_string('forcetheme'), $themes);
    }

    public static function get_add_instance_default_data(){
        return array();
    }

    public function update_instance(stdClass $general, stdClass $special, moodleform_activity $mform){
        return true;
    }

    public static function delete_instance($id){
        return true;
    }

    public function get_update_instance_data(){
        return array('dd'=>time());
    }

    public function get_view_url(){
        return new moodle_url('/mod/surveyactivity/activity/simple/view.php',array('id'=>$this->_wrapper->id));
    }

    /**
    *
    * True if this type has a global report.
    *
    * @return bool
    *
    */
    public function has_global_report()
    {
        return true;
    }

    public function start()
    {

    }

    public function stop()
    {

    }

    public function get_global_report_view_url()
    {

    }

    public function get_global_report_download_url()
    {

    }
    
    public function check_complete()
    {
         
    }
    
    public static function add_instance(stdClass $general, stdClass $special, moodleform_activity $mform)
    {
        return true;
    }
    
    public static function extend_nav(\navigation_node $nav, activity_wrapper $wrapper = null)
    {
        return true;
    }
    
}