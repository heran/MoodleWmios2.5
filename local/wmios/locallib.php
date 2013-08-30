<?php
//completion info
require_once dirname(__FILE__).'/completion/lib.php';

require_once dirname(__FILE__).'/document/lib.php';

//smarty
require_once dirname(__FILE__).'/viewlib.php';

/**
* course deleted event
*
* @param mixed $eventdata
*/
function local_wmios_course_deleted($eventdata){
    $courseid = $eventdata->id;
    return wmios_completion_info::clear_for_course_deleted($courseid);
}

/**
* mod deleted event
*
* @param mixed $eventdata
*/
function local_wmios_mod_deleted($eventdata){
    $cmid = $eventdata->cmid;
    return wmios_qanda::clear_for_mod_deleted($cmid) &&
    wmios_note::clear_for_mod_deleted($cmid) &&
    wmios_completion_info::clear_for_mod_deleted($cmid);
}

/**
* When user deleted
*
* @param mixed $eventdata
*/
function local_wmios_user_deleted($eventdata){
    $userid = $eventdata->id;
    return wmios_qanda::clear_for_user_deleted($userid) &&
    wmios_note::clear_for_user_deleted($userid) &&
    wmios_completion_info::clear_for_user_deleted($userid);
}

/**
* Convert a second number to a string which format is xx:xx*
* 70 to 01:10
* 60 to 01:00
*
* @param int $time
* @return string
*/
function wmios_format_second_to_minute($time) {
    $output = '';
    if($time>=60){
        $output .= sprintf('%02d',floor($time/60));
    }else{
        $output .= '00';
    }
    $output .= ':';
    return  $output . sprintf('%02d',$time %= 60);
}

/**
* calculate two time number's interval
* 1 minute ago
* 1 year ago
* 2 seconds ago
* If $time2 > $time1 ,the interval is zero.
*
*
* @param int $time1
* @param int $time2
* @return string
*/
function wmios_time_interval_format($time1,$time2){
    if($time1<$time2){
        $time2 = $time1;
    }
    $time1 = intval($time1);
    $time2 = intval($time2);
    $now = new DateTime('@'.$time1);
    $ago = new DateTime('@'.$time2);
    $interval = $now->diff($ago);
    $str = '';
    if($num = $interval->format('%y')){
        $plural = $num > 1 ? '_plural' : '';
        $str = get_string('years_ago'.$plural,'local_wmios',$num);
    }elseif($num = $interval->format('%m')){
        $plural = $num > 1 ? '_plural' : '';
        $str = get_string('months_ago'.$plural,'local_wmios',$num);
    }elseif($num = $interval->format('%d')){
        $plural = $num > 1 ? '_plural' : '';
        $str = get_string('days_ago'.$plural,'local_wmios',$num);
    }elseif($num = $interval->format('%h')){
        $plural = $num > 1 ? '_plural' : '';
        $str = get_string('hours_ago'.$plural,'local_wmios',$num);
    }elseif($num = $interval->format('%i')){
        $plural = $num > 1 ? '_plural' : '';
        $str = get_string('minutes_ago'.$plural,'local_wmios',$num);
    }else{
        $num = $interval->format('%s');
        $plural = $num > 1 ? '_plural' : '';
        $str = get_string('seconds_ago'.$plural,'local_wmios',$num);
    }
    return $str;
}

/**
* Don't use in yours code.
* It's parent object of notes ,qanda..
* a dbobject represent a record of table
*/
class wmios_dbobject implements JsonSerializable {

    /**
    * table name
    *
    * @var string
    */
    protected static $_table = '';

    /**
    * The table's record data
    *
    * @var array
    */
    protected $_fields = array();

    /**
    * Get record data by key
    *
    * @param string $k
    * @return mixed record value
    */
    public function __get($k){
        return isset($this->_fields[$k]) ? $this->_fields[$k] : null;
    }

    /**
    * Set record data
    *
    * @param string $k
    * @param mixed $v
    */
    public function __set($k,$v){
        $this->_fields[$k] = $v;
    }

    /**
    * Set batch record data
    *
    * @param stdClass|array $data
    * @return static
    */
    protected function set_fields($data){
        $this->_fields = (array)$data;
        return $this;
    }

    /**
    * update each field in $data
    *
    * @param stdClass|array $data
    * @return static
    */
    public function update_fields($data){
        if(is_object($data)){
            $data = (array)$data;
        }
        foreach($data as $k=>$v){
            $this->$k = $v;
        }
    }

    /**
    * Get batch record data
    * @return mixed[]
    */
    public function get_fields(){
        return $this->_fields;
    }

    /**
    *
    * @param array $fields
    * @return static
    */
    public static function instance_for_fields($fields){
        $fields = (array)$fields;
        $n = new static();
        $n->set_fields($fields);
        return $n;
    }

    /**
    * get instance from id
    *
    * @param int $id
    * @return static
    */
    public static function instance_for_id($id){
        global $DB;
        $data = $DB->get_record(static::$_table,array('id'=>$id));
        $n = new static();
        return $data ? $n->set_fields($data) : null;
    }

    /**
    * Delete one instance
    *
    * @param int $id
    * @return bool
    */
    public static function delete($id){
        global $DB;
        return $DB->delete_records(static::$_table,array('id'=>$id));
    }

    /**
    * Update self
    * @return bool
    */
    public function update(){
        global $DB;
        return $DB->update_record(static::$_table,(object)$this->_fields);
    }

    /**
    * For json_encode
    *
    */
    public function jsonSerialize() {
        return $this->get_fields();
    }
}

/**
* @property int $id
*/
class wmios_common_object
{
    protected static $_table = '';

    protected $_fields =array('id'=>0);

    /**
    * put your comment there...
    *
    * @param mixed $fields
    * @return static
    */
    public function __construct($fields){
        $this->setFields((array)$fields);
    }

    /**
    * put your comment there...
    *
    * @param mixed $fileds
    * @return static
    */
    public function setFields(array $fileds)
    {
        foreach($fileds as $k=>$v)
        {
            $this->setField($k, $v);
        }
        return $this;
    }

    /**
    * put your comment there...
    *
    * @param mixed $k
    * @param mixed $v
    * @return static
    */
    public function setField($k,$v)
    {
        if(key_exists($k, $this->_fields))
        {
            $this->_fields[$k] = $v;
        }
        return $this;
    }

    public function getField($k)
    {
        return isset($this->_fields[$k]) ? $this->_fields[$k] : null;
    }

    public function getFields()
    {
        return $this->_fields;
    }

    public function __get($k)
    {
        switch($k)
        {
            case 'parent':
                return $this->getParent();
                break;
            case 'children':
                return $this->getChildren();
                break;
            default:
                return $this->getField($k);
                break;
        }
    }

    /**
    * put your comment there...
    *
    * @param mixed $k
    * @param mixed $v
    */
    public function __set($k,$v = null )
    {
        if(is_string($k))
        {
            $this->setField($k,$v);
        }elseif(is_array($k)){
            $this->setFields($k);
        }
    }

    /**
    * @return bool
    *
    */
    public function save()
    {
        global $DB;
        $result = false;
        if($this->id <= 0)
        {
            $this->id = $DB->insert_record(static::$_table,(object)$this->getFields(),true);
            $result = $this->id > 0;
        }else{
            $result = $DB->update_record(static::$_table,(object)$this->getFields());
        }
        return $result;

    }

    /**
    * @param int $id
    * @return static
    */
    public static function instance_from_id($id)
    {
        global $DB;
        $record = $DB->get_record(static::$_table,array('id'=>$id));
        $tree = null;
        if($record)
        {
            $tree = new static($record);
        }
        return $tree;
    }

    /**
    *
    * @param string $query
    * @return static
    */
    public static function instance_from_select( $query, array $params=null, $fields='*')
    {
        global $DB;
        $record = $DB->get_record_select(static::$_table,$query,$params,$fields);
        $tree = null;
        if($record)
        {
            $tree = new static($record);
        }
        return $tree;
    }

    /**
    * put your comment there...
    *
    * @param string $query
    * @return static[]
    */
    public static function instances_from_select( $query, array $params=null, $sort='', $fields='*', $limitfrom=0, $limitnum=0)
    {
        global $DB;
        $records = $DB->get_records_select(static::$_table,$query,$params,$sort,$fields,$limitfrom,$limitnum);
        $trees = array();
        if($records)
        {
            foreach($records as $record)
            {
                $trees[] = new static($record);
            }
        }
        return $trees;
    }

    /**
    * put your comment there...
    *
    * @param array $arr
    * @return self
    */
    public static function instance_from_create($arr)
    {
        $r = new static($arr);
        if($r->save())
        {
            return $r;
        }else{
            return null;
        }
    }

    public static function delete_instance(wmios_common_object $instance)
    {
        global $DB;
        return $DB->delete_records(static::$_table,array('id'=>$instance->id));
    }
}

/**
* It's a course module instance
* Must has fields: course(course's id), cmid(course module id)
*/
class wmios_module_instance extends wmios_common_object
{
    /**
    * determined by $this->course
    * @var stdClass
    */
    private $_course = null;

    /**
    *
    * determined by $this->cmid
    *
    * @var stdClass
    */
    private $_cm = null;

    /**
    *
    *
    *
    * @var context_module
    *
    */
    private $_context = null;


    /**
    * set base's course module
    *
    * @param mixed $cm
    * @return static
    */
    public function set_cm($cm)
    {
        if($cm->instance == $this->id)
        {
            $this->_cm = $cm;
        }
        return $this;
    }

    /**
    * get the course module object
    *
    * @return stdClass
    */
    public function get_cm()
    {
        if($this->_cm === null)
        {
            $this->_cm = get_coursemodule_from_instance(SURVEYACTIVITYBASE_PLUGIN_NAME, $this->id, $this->course_id, false, MUST_EXIST);
        }
        return $this->_cm;
    }

    /**
    * put your comment there...
    *
    * @param context_module $context
    * @return static
    */
    public function set_context(\context_module $context)
    {
        if($context->instanceid == $this->cmid)
        {
            $this->_context = $context;
        }
        return $this;
    }

    /**
    * @return context_module
    *
    */
    public function get_context()
    {
        if($this->_context === null)
        {
            $this->_context = context_module::instance($this->cmid);
        }
        return $this->_context;
    }

    /**
    * set course
    *
    * @param stdClass|mixed $course
    * @return static
    */
    public function set_course($course)
    {
        if($this->course == $course->id)
        {
            $this->_course = $course;
        }
        return $this;
    }

    /**
    *
    * @return stdClass|mixed $course refrence by course
    *
    */
    public function get_course()
    {
        global $DB;
        if($this->_course === null)
        {
            $this->_course =  $DB->get_record('course', array('id'=>$this->course), '*');
        }
        return $this->_course;
    }
}