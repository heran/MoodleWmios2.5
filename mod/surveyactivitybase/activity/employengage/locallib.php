<?php
namespace wmios\survey;
use \MoodleQuickForm,\moodle_url,\stdClass;

define('SURVEYACTIVITY_EMPLOYENGAGE_PLUGIN_NAME','surveyactivity_employengage');


/**
* @property int $id
* @property int $status
* @property int $survey_id
* @property int $reportstatus
*/
class activity_employengage_instance extends \wmios_common_object
{
    const STATUS_EMPLOYENGAGE_COMPLETE = 1;
    const STATUS_EMPLOYENGAGE_UNCOMPLETE = 0;
    const STATUS_EMPLOYENGAGE_WRONG = -1;
    protected static $_table = 'surveyactivity_employengage';

    /** @var activity_wrapper*/
    protected $_wrapper = null;

    protected $_fields =array(
        'id'=>0,
        'status'=>0,
        'survey_id'=>0,
        'reportstatus'=>0,
    );

    /**
    * put your comment there...
    *
    * @param activity_wrapper $wrapper
    * @return activity_employengage_instance
    */
    public function set_wrapper(activity_wrapper $wrapper)
    {
        if($this->id == $wrapper->instance_id)
        {
            $this->_wrapper = $wrapper;
        }
        return $this;
    }

    /**
    * @return activity_wrapper
    *
    */
    public function get_wrapper()
    {
        if($this->_wrapper == null)
        {
            $this->_wrapper == activity_wrapper::instance_for_activity_and_instance('employengage',$this->id);
        }
        return $this->_wrapper;
    }

}
/**
* @property int  $id
* @property int  $surveyid
* @property varchar $firstname
* @property varchar $lastname
* @property varchar $email
* @property varchar $dplevel_one
* @property varchar $dplevel_two
* @property varchar $dplevel_three
* @property varchar $dplevel_four
* @property varchar $dplevel_five
* @property varchar $dplevel_six
* @property varchar $dplevel_seven
* @property varchar $dplevel_eight
* @property varchar $dplevel_nine
* @property varchar $dplevel_ten
* @property varchar $position
* @property varchar $age
* @property varchar $provinces
* @property varchar $gender
* @property varchar $city
* @property varchar $token
* @property int $requirements
* @property int $ownership
* @property int $operation
* @property int $development
* @property int $yvalues
* @property int $averages
* @property int $status
* @property int $status2
* @property int $q1 
* @property int $q2
* @property int $q3
* @property int $q4
* @property int $q5
* @property int $q6
* @property int $q7
* @property int $q8
* @property int $q9
* @property int $q10
* @property int $q11
* @property int $q12
* @property int $q13
* @property int $q14
* @property int $q15
* @property int $q16
* @property int $q17
* @property int $q18
*/
class activity_employengage_users_instance extends \wmios_common_object
{

    protected static $_table = 'surveyactivity_employ_users';

    protected $_fields =array('id'=>0, 
        'surveyid'=>0,
        'firstname'=>'',
        'lastname'=>'',
        'email'=>'',
        'dplevel_one'=>'', 
        'dplevel_two'=>'', 
        'dplevel_three'=>'',
        'dplevel_four'=>'',
        'dplevel_five'=>'',
        'dplevel_six'=>'',
        'dplevel_seven'=>'',
        'dplevel_eight'=>'',
        'dplevel_nine'=>'',
        'dplevel_ten'=>'',
        'position'=>'',
        'age'=>0,
        'provinces'=>'',
        'city'=>'',
        'gender'=>'',
        'token'=>'',
        'requirements'=>'',
        'management'=>'',
        'ownership'=>'',
        'operation'=>'',
        'development'=>'',
        'yvalues'=>'',
        'averages'=>'',
        'q1'=>'',
        'q2'=>'',
        'q3'=>'',
        'q4'=>'',
        'q5'=>'',
        'q6'=>'',
        'q7'=>'',
        'q8'=>'',
        'q9'=>'',
        'q10'=>'',
        'q11'=>'',
        'q12'=>'',
        'q13'=>'',
        'q14'=>'',
        'q15'=>'',
        'q16'=>'',
        'q17'=>'',
        'q18'=>'', 
        'status'=>'', 
        'status2'=>'',     
    );
    
    

}