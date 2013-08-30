<?php
require_once dirname(__FILE__).'/../lib.php';
require_once($CFG->dirroot.'/lib/enrollib.php');
require_once($CFG->dirroot.'/enrol/cohort/lib.php');
require_once($CFG->dirroot.'/enrol/cohort/locallib.php');
require_once($CFG->dirroot.'/lib/coursecatlib.php');


/**
* extends /cohort/lib.php
*
* @property string $name
* @property string $idnumber
*/
class wmios_cohort extends wmios_common_object
{
    protected static $_table = 'cohort';

    protected $_fields =array(
        'id'=>0,
        'contextid'=>0,
        'name'=>'',
        'idnumber'=>0,
        'description'=>'',
        'descriptionformat'=>0,
        'component'=>'',
        'timecreated'=>0,
        'timemodified'=>0,
    );

    /**
    * get user's cohorts
    *
    * @param int|stdClass|object $user
    * @return static[]
    */
    public static function instances_belong_to_user($user)
    {
        if(is_object($user))
        {
            $user = $user->id;
        }
        return static::instances_from_select(
            "`id` in (select `cohortid` from {cohort_members} where `userid`='{$user}')");
    }

}

/**
* organization's cohort
*/
class wmios_organization_cohort extends wmios_cohort
{

    /**
    *
    *
    * @inheritdoc
    */
    public function __set($k,$v = null )
    {
        if($k == 'idnumber' && substr($v,0,2)!='o_')
        {
            throw new moodle_exception("this class must be used for organization");
        }
        parent::__set($k,$v);
    }

    /**
    * get one organization(company)'s cohort
    * one company has only one cohort.
    * we use the cohort table's idnumber field to idengtify this.
    *
    * @param string $name
    */
    public static function instance_belong_to_organization($key)
    {
        $instances = static::instances_from_select("`idnumber`='o_{$key}'");
        if(count($instances)>1)
        {
            throw new moodle_exception("one organization can only have one cohort");
        }
        return count($instances) ? current($instances) : null;
    }

    /**
    * get user's organization cohort
    *
    * @param int|stdClass|object $user
    * @return static
    */
    public static function instances_user_belong_to($user)
    {
        if(is_object($user))
        {
            $user = $user->id;
        }
        $instances = static::instances_from_select(
            "`id` in (select `cohortid` from {cohort_members} where `userid`='{$user}')".
            " and `idnumber` like 'o_%'");
        return $instances;
    }

    /**
    * the organization's special course
    *
    * @param string $name
    */
    protected static function special_course_belong_to_organization($key)
    {
        global $DB;

        $records = (array)$DB->get_records_select('course',"`idnumber`='o_{$key}'");
        if(count($records)>1)
        {
            throw new moodle_exception("one organization can only have one special course");
        }
        return count($records) ? current($records) : null;
    }

    /**
    * me belong to one organization
    * one organization has one special course
    * so me have one special course
    *
    * @param bool $must_exists True will throw exception when there isn't a special course.
    */
    public function get_special_course($must_exists = false)
    {
        $course = static::special_course_belong_to_organization(substr($this->idnumber,2));
        if(empty($course) && $must_exists)
        {
            throw new moodle_exception("there isn't a course for the organization:{$this->idnumber}");
        }
        return $course;
    }

    /**
    * create a course for the organization
    *
    * dependency enrol_cohort
    *
    *
    * @see /enrol/cohort/edit.php
    * @see create_course() coursecat::get() enrol_cohort_plugin enrol_cohort_sync
    *
    *
    */
    public function create_special_course()
    {
        $cat_root = coursecat::get(0);
        $cats = $cat_root->get_children();
        $cat_special = current($cats);
        $course = create_course((object)array(
            'fullname'=>$this->name,
            'shortname'=>$this->name,
            'idnumber'=>$this->idnumber,
            'category'=>$cat_special->id,
        ));
        $enrol = enrol_get_plugin('cohort');
        $enrol->add_instance($course, array(
            'name'=>$this->name,
            'status'=>ENROL_INSTANCE_ENABLED,
            'customint1'=>$this->id,
            'roleid'=>$enrol->get_config('roleid')));
        $trace = new null_progress_trace();
        enrol_cohort_sync($trace, $course->id);
        $trace->finished();
        return $course;
    }

    /**
    * put your comment there...
    *
    * @param string $key
    * @return bool
    */
    public function belong_to_organization($key)
    {
        return $this->idnumber === 'o_'.$key;
    }

    /**
    * I'll belong to a new organization
    *
    * @param string $key
    * @param string $name
    */
    public function change_organization($key, $name)
    {
        global $DB;

        if($this->belong_to_organization($key))
        {
            return;
        }
        $old_idnumber = $this->idnumber;
        $transaction = $DB->start_delegated_transaction();
        $this->idnumber = 'O_'.$key;
        $this->name = $name;
        cohort_update_cohort((object)$this->getFields());
        $courses = $DB->get_records('course', array('idnumber'=>$old_idnumber));
        if(count($courses)!=1)
        {
            throw new moodle_exception('course idnumber not valid');
        }
        $course = current($courses);
        $course->fullname = $this->name;
        $course->shortname = $this->name;
        $course->idnumber = $this->idnumber;
        update_course($course);
        $transaction->allow_commit();
    }

}