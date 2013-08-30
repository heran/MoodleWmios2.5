<?php
namespace wmios\survey;
use \navigation_node,\moodleform,\MoodleQuickForm,\stdClass;

require_once ($CFG->libdir.'/formslib.php');

interface i_activity
{
    /**
    * @param activity_wrapper $wrapper
    */
    public function __construct(activity_wrapper $wrapper);

    /**
    *
    * Extend survey's main navition
    *
    * Use $nav->add_node() add some note.
    *
    * @see \wmios\survey\tool::get_nav()
    *
    * @param navigation_node $nav
    */
    public static function extend_nav(navigation_node $nav, activity_wrapper $wrapper = null);

    /**
    * When update or add a activity,You need change the form
    *
    * @param MoodleQuickForm $mform
    * @param activity_wrapper $activity_wrapper
    */
    public static function process_update_form(MoodleQuickForm $mform,activity_wrapper $activity_wrapper = null);

    /**
    * When user save data
    *
    * @param stdClass $general
    * @param stdClass $special
    * @param moodleform_activity $mform
    * @return int the instance id
    */
    public static function add_instance(stdClass $general, stdClass $special, moodleform_activity $mform);

    /**
    * Get some default data for add instance
    *
    * @return array
    */
    public static function get_add_instance_default_data();

    /**
    * When user update data
    *
    * @param stdClass $general
    * @param stdClass $special
    * @param moodleform_activity $mform
    * @return bool
    */
    public function update_instance(stdClass $general, stdClass $special, moodleform_activity $mform);

    /**
    * Get some default data for update instance
    * .
    * @return array
    */
    public function get_update_instance_data();

    /**
    * When user delete an instance
    *
    * @param int $id
    * @return bool
    */
    public static function delete_instance($id);

    /**
    * Get the moodle_url for view activity detail,
    * This url is the main view's url
    *
    * @return \moodle_url
    *
    */
    public function get_view_url();

    /**
    *
    * True if this type has a global report.
    * What is global report?
    * May be a report can describe the whole survey activity.
    *
    * @return bool
    *
    */
    public function has_global_report();

    /**
    * If has global report, return the report view url.
    * usually user can go to report display page ,html
    *
    * @return \moodle_url
    */
    public function get_global_report_view_url();

    /**
    * If has global reprot ,return the report download url
    * user can use this to get the pdf\word report
    *
    * @return \moodle_url
    *
    */
    public function get_global_report_download_url();

    /**
    * start a activity
    *
    * send a invite email?
    *
    * you only define this, it is called by wrapper
    *
    * @return bool true if success
    */
    public function start();

    /**
    * stop a activity
    *
    * can not do a survey now.
    *
    * you only define this, it is called by wrapper
    *
    * @return bool ture if success
    *
    */
    public function stop();

    /**
    *  get a fresh status.may be goto server, get status.
    * @return bool
    *
    */
    public function check_complete();

    /**
    * @return int $status status activity_wrapper::STATUS_NEW ...
    *
    */
    public function get_status();

    /**
    * display string for the activity status.
    * @return string
    *
    */
    public function get_status_display();
}

/**
*
*/
abstract class activity implements i_activity{

    protected $_wrapper = null;

    public function __construct(activity_wrapper $wrapper){
        $this->_wrapper = $wrapper;
    }


    public static function delete_instance($id){
        return false;
    }

    public final function get_status()
    {
        return $this->_wrapper->get_status();
    }

    public final function get_status_display(){
        return $this->_wrapper->get_status_display();
    }

    /**
    * return the wrapper
    *
    * @return \wmios\survey\activity_wrapper
    *
    */
    public final function get_wrapper(){
        return $this->_wrapper;
    }

}

class moodleform_activity extends moodleform {

    /** @var activity_wrapper*/
    protected $_activity_wrapper;

    protected $_activity_type = '';

    /**
    *
    * @param plugininfo_activity $activity_plugin
    * @param activity_wrapper $activity_wrapper
    * @param \String $action html form action
    * @param array $customdata
    * @param \String $method
    * @param \String $target
    * @param mixed $attributes
    * @param mixed $editable
    * @return moodleform_activity
    */
    public function __construct($activity_type, activity_wrapper $activity_wrapper = null, $action=null, $customdata=null, $method='post', $target='', $attributes=null, $editable=true) {
        activity_wrapper::required_classes($activity_type);
        $this->_activity_type = $activity_type;
        $this->_activity_wrapper = $activity_wrapper;
        parent::moodleform($action, $customdata, $method, $target, $attributes, $editable);
    }

    public function definition() {
        $mform    = &$this->_form;

        //-------------------------------------general-----------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));
        $mform->addElement('text', 'general_name', get_string('name'), array('size'=>'48'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('general_name', PARAM_TEXT);
        } else {
            $mform->setType('general_name', PARAM_CLEANHTML);
        }
        $mform->addRule('general_name', null, 'required', null, 'client');
        $mform->addElement('editor', 'general_description', get_string('description'), null, array('maxfiles'=>EDITOR_UNLIMITED_FILES, 'noclean'=>true, 'context'=>\context_system::instance()));
        $mform->setType('general_description', PARAM_RAW);
        $mform->addElement('date_time_selector', 'general_starttime',get_string('starttime',SURVEYACTIVITYBASE_PLUGIN_NAME),array('optional'=>true));
        $mform->addElement('date_time_selector', 'general_endtime',get_string('endtime',SURVEYACTIVITYBASE_PLUGIN_NAME),array('optional'=>true));
        $mform->addElement('hidden', 'general_activity');
        $mform->addElement('hidden', 'general_id');

        //-------------------------------------special-----------------------------

        $mform->addElement('header', 'special', get_string('pluginname', activity_wrapper::get_activity_component_by_name($this->_activity_type)));
        /**
        * @var \wmios\survey\activity
        */
        $classname = activity_wrapper::get_interface_class_by_name($this->_activity_type);
        $classname::process_update_form($mform,$this->_activity_wrapper);

        //-------------------------------------buttons-----------------------------
        // elements in a row need a group
        $buttonarray = array();
        $buttonarray[] = &$mform->createElement('submit', 'general_save_and_return',
            get_string('save_and_return_activity_list',SURVEYACTIVITYBASE_PLUGIN_NAME));
        $buttonarray[] = &$mform->createElement('submit', 'general_save_and_display',
            get_string('save_and_display_activity',SURVEYACTIVITYBASE_PLUGIN_NAME));
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->setType('buttonar', PARAM_RAW);
        $mform->closeHeaderBefore('buttonar');

    }
}