<?php
require_once($CFG->dirroot.'/local/wmios/lib.php');

/**
* question object
* @property int $id
* @property int $courseid
* @property int $cmid which mod
* @property int $position where in the mod
* @property int $userid
* @property string $title question title
* @property string $content question content
* @property int $modifiedtime when modified
*/
class wmios_qanda extends wmios_dbobject{

    /**
    * table name
    * 
    * @var String
    */
    protected static $_table = 'qanda';
    
    /**
    * course module info
    * May be null
    * 
    * @var cm_info|stdClass
    */
    protected $_cm = null;
    
    /**
    * User info
    * 
    * @var stdClass
    */
    protected $_user = null;
    
    /**
    * course module info
    * 
    * @return cm_info
    * 
    */
    public function get_cm(){
        if($this->_cm === null && $this->cmid){
            $this->_cm = get_coursemodule_from_id(null,$this->cmid);
        }
        return $this->_cm;
    }
    
    /**
    * Set course module info
    * 
    * @param cm_info|stdClass $cm
    */
    public function set_cm( $cm){
        if($this->cmid){
            $this->_cm = $cm;
        }
    }
    
    /**
    * Set course module and cmid
    * 
    * @param cm_info|stdClass $cm
    */
    public function update_cm($cm){
        $this->cmid = $cm->id;
        $this->_cm = $cm;
    }
    
    /**
    * Get the user who asked question
    * @return stdClass the record of user table
    * 
    */
    public function get_user(){
        global $DB;
        
        if($this->_user === null){
            $this->_user = $DB->get_record('user',array('id'=>$this->userid));
        }
        return $this->_user;
    }
    
    /**
    * Set user info 
    * 
    * @param stdClass $user user info
    */
    public function set_user($user){
        if($this->userid == $user->id){
            $this->_user = $user;    
        }
    }

    /**
    * Get a spec. user's questions of one course module
    * 
    * @param int $userid
    * @param int $cmid
    * @param string $sort order string ,used in sql
    * @return self[]
    */
    public static function instances_for_userid_and_cmid($userid, $cmid, $sort = 'modifiedtime desc'){
        global $DB;
        $records = $DB->get_records(static::$_table,array('userid'=>$userid,'cmid'=>$cmid),$sort);
        $results = array();
        if($records)foreach($records as $record){
            $results[] = static::instance_for_fields($record);
        }
        return $results;
    }

    /**
    * Get a spec. user's questions of one course module with position range
    * 
    * @param int $userid
    * @param int $cmid
    * @param int $min_position ge 0
    * @param int $max_position ge 0
    * @param string $sort
    * @return self[]
    */
    public static function instances_for_userid_and_cmid_range($userid, $cmid,$min_position =0 ,$max_position=0,$sort = 'modifiedtime desc'){
        global $DB;
        $where = ' userid=? and cmid=? ';
        $params = array($userid, $cmid);
        if($min_position){
            $where .= ' and position>=? ';
            $params[] = $min_position;
        }
        if($max_position){
            $where .= ' and position<=? ';
            $params[] = $max_position;
        }

        $records = $DB->get_records_sql('select * from {'.static::$_table.'} where '.$where.($sort ? ' ORDER BY '.$sort : ''),$params);
        $results = array();
        if($records)foreach($records as $record){
            $results[] = static::instance_for_fields($record);
        }
        return $results;
    }

    /**
    * Get questions of one course module with position range
    * 
    * @param int $cmid
    * @param int $min_position ge 0
    * @param int $max_position ge 0
    * @param string $sort
    * @return self[]
    */
    public static function instances_for_cmid_range($cmid,$min_position =0 ,$max_position=0,$sort = 'modifiedtime desc'){
        global $DB;
        $where = ' cmid=? ';
        $params = array($cmid);
        if($min_position){
            $where .= ' and position>=? ';
            $params[] = $min_position;
        }
        if($max_position){
            $where .= ' and position<=? ';
            $params[] = $max_position;
        }

        $records = $DB->get_records_sql('select * from {'.static::$_table.'} where '.$where.($sort ? ' ORDER BY '.$sort : ''),$params);
        $results = array();
        if($records)foreach($records as $record){
            $results[] = static::instance_for_fields($record);
        }
        return $results;
    }

    /**
    * Get a spec. user's questions of a course
    * 
    * @param int $userid
    * @param int $courseid
    * @param bool $include_mod False if only need the questions that belong one mod
    * @param string $sort
    * @return self[]
    */
    public static function instances_for_userid_and_courseid($userid, $courseid, $include_mod = false,$sort = 'modifiedtime desc'){
        global $DB;
        $where = ' userid=? and courseid=?';
        $params = array($userid,$courseid);
        if(!$include_mod){
            $where .= ' and cmid>0';
        }
        $records = $DB->get_records_sql('select * from {'.static::$_table.'} where '.$where.($sort ? ' ORDER BY '.$sort : ''),$params);
        $results = array();
        if($records)foreach($records as $record){
            $results[] = static::instance_for_fields($record);
        }
        return $results;
    }

    /**
    * Get questions of a course
    * 
    * @param int $courseid
    * @param bool $include_mod False if only need the questions that belong one mod
    * @param string $sort
    * @return self[]
    */
    public static function instances_for_courseid($courseid, $include_mod = false,$sort = 'modifiedtime desc'){
        global $DB;
        $where = ' courseid=?';
        $params = array($courseid);
        if(!$include_mod){
            $where .= ' and cmid>0';
        }
        $records = $DB->get_records_sql('select * from {'.static::$_table.'} where '.$where.($sort ? ' ORDER BY '.$sort : ''),$params);
        $results = array();
        if($records)foreach($records as $record){
            $results[] = static::instance_for_fields($record);
        }
        return $results;
    }

    /**
    * Create one question
    * 
    * @param string $title
    * @param string $content
    * @param int $userid
    * @param int $courseid
    * @param int $cmid
    * @param int $position
    * @return self
    */
    public static function create($title,$content,$userid,$courseid,$cmid = 0,$position = 0){
        global $DB;
        $data = array(
            'title'=>$title,
            'content'=>$content,
            'userid'=>$userid,
            'courseid'=>$courseid,
            'cmid'=>$cmid,
            'position'=>$position,
            'modifiedtime'=>time());
        $id = $DB->insert_record(static::$_table,(object)$data,true);
        $data['id'] = $id;
        return static::instance_for_fields($data);
    }

    /**
    * @inheritdoc
    */
    public static function delete($id){
        return wmios_qanda_answer::clear_by_qandaid($id) &&
        wmios_qanda_follow::clear_by_qandaid($id) && parent::delete($id);
    }
    
    /**
    * when course deleted
    * when block_qanda instance deleted
    * 
    * @param mixed $courseid
    */
    public static function clear_for_course_deleted($courseid){
        global $DB;
        $ids = $DB->get_fieldset_select('qanda','id','courseid=?',array($courseid));
        return wmios_qanda_answer::clear_by_qandaids($ids) &&
        wmios_qanda_follow::clear_by_qandaids($ids) &&
        $DB->delete_records(static::$_table, array('courseid'=>$courseid));
        
    }
    
    /**
    * when mod deleted
    * 
    * @param mixed $courseid
    */
    public static function clear_for_mod_deleted($cmid){
        global $DB;
        $ids = $DB->get_fieldset_select('qanda','id','cmid=?',array($cmid));
        return wmios_qanda_answer::clear_by_qandaids($ids) &&
        wmios_qanda_follow::clear_by_qandaids($ids) &&
        $DB->delete_records(static::$_table, array('cmid'=>$cmid));
        
    }
    
    /**
    * when user deleted
    * 
    * @param int $userid
    */
    public static function clear_for_user_deleted($userid){
        //Fixme 10 -o wmios_heran -c qanda :Need delete user info???
        //If one user has been deleted, His questions and answers may be followed by other users.
        global $DB;
        $ids = $DB->get_fieldset_select('qanda','id','userid=?',array($userid));
        return 
            wmios_qanda_answer::clear_by_qandaids($ids) &&
            wmios_qanda_answer::clear_by_userid($userid) &&
            wmios_qanda_follow::clear_by_qandaids($ids) &&
            wmios_qanda_follow::clear_by_userid($userid) &&
            $DB->delete_records(static::$_table, array('userid'=>$userid));
        
    }

    //answer    
    
    /**
    * Create an answer
    * 
    * @param int $userid
    * @param string $answer
    * @return wmios_qanda_answer
    */
    public function answer($userid,$answer){
        return wmios_qanda_answer::create($answer,$userid,$this->id);
    }

    /**
    * The answers of question
    * 
    * @param int $userid
    * @return wmios_qanda_answer[]
    */
    public function get_answers($userid = 0){
        return wmios_qanda_answer::instances_for_qandaid($this->id,$userid);
    }

    /**
    * Delete one answer
    * 
    * @param int $id answer's id
    * @return bool
    */
    public function delete_answer($id){
        if(is_a($id,'wmios_qanda_answer')){
            $id = $id->id;
        }
        return wmios_qanda_answer::delete($id);
    }

    //follow
    
    /**
    * Add one follower
    * 
    * @param int $userid
    * @return wmios_qanda_follow[]
    */
    public function add_follower($userid){
        return wmios_qanda_follow::create($userid,$this->id);
    }

    /**
    * Get followers
    * @return wmios_qanda_follow[]
    * 
    */
    public function get_followers(){
        return wmios_qanda_follow::instances_for_qandaid($this->id);
    }

    /**
    * Delete one follower
    * 
    * @param int $userid
    * @return bool
    */
    public function delete_follower($userid){
        return wmios_qanda_follow::unfollow($this->id,$userid);
    }
    
    /**
    * json_encode
    * 
    */
    public function jsonSerialize() {       
        global $CFG;
        $fields = $this->get_fields();
        $answers = $this->get_answers();
        $followers = $this->get_followers();
        $fields['answers'] = $answers;
        $fields['answers_str'] = get_string('n_answers','local_wmios',count($answers));
        $fields['followers'] = $followers;
        
        if($this->modifiedtime){
            $fields['asked_time'] = get_string('when_asked_this_question','local_wmios',wmios_time_interval_format(time(),$this->modifiedtime));
        }else{
            $fields['asked_time'] = '';
        }
        
        $fields['position_str'] = '';
        if($this->cmid){
            $cm = $this->get_cm();
            $func = $cm->modname .'_format_unit';
            require_once($CFG->dirroot.'/mod/'.$cm->modname.'/lib.php');
            if(function_exists($func)){
                $fields['position_str'] = $func($cm ,$this->position);
            }
        }
        
        
        return $fields;
    }

}

/**
* Question's answer
* @property int $id
* @property int $qandaid
* @property int $userid
* @property string $answer answer content
* @property int $modifiedtime
*/
class wmios_qanda_answer extends wmios_dbobject{

    /**
    * Table name
    * 
    * @var String
    */
    protected static $_table = 'qanda_answer';

    /**
    * Create one answer
    * 
    * @param string $answer
    * @param int $userid
    * @param int $qandaid
    * @return self
    */
    public static function create($answer,$userid,$qandaid){
        global $DB;
        $data = array(
            'qandaid'=>$qandaid,
            'userid'=>$userid,
            'answer'=>$answer,
            'modifiedtime'=>time());
        $id = $DB->insert_record(static::$_table,(object)$data,true);
        $data['id'] = $id;
        return static::instance_for_fields($data);
    }

    /**
    * Get answers of a question
    * 
    * @param int $qandaid
    * @param int $userid
    * @param string $sort
    * @return self[]
    */
    public static function instances_for_qandaid($qandaid,$userid = 0,$sort = 'modifiedtime desc'){
        global $DB;
        $params['qandaid'] = $qandaid;
        if($userid){
            $params['userid'] = $qandaid;
        }
        $records = $DB->get_records(static::$_table,$params,$sort);
        $results = array();
        if($records)foreach($records as $record){
            $results[] = static::instance_for_fields($record);
        }
        return $results;        
    }

    /**
    * Delete Answers of a question
    * 
    * @param int $qandaid
    * @return bool
    */
    public static function clear_by_qandaid($qandaid){
        global $DB;
        return $DB->delete_records(static::$_table,array('qandaid'=>$qandaid));
    }
    
    /**
    * delete answers for a question set.
    * 
    * @param int[] $qandaids
    * @return bool
    */
    public static function clear_by_qandaids($qandaids){
        global $DB;
        return $DB->delete_records_list(static::$_table, 'qandaid', $qandaids);
    }
    
    /**
    * delete answers for a user.
    * 
    * @param int $userid
    * @return bool
    */
    public static function clear_by_userid($userid){
        global $DB;
        return $DB->delete_records(static::$_table, array('userid'=>$userid));
    }
    
    /**
    * Get the question of a answer
    * @return wmios_qanda
    * 
    */
    public function get_question(){
        return wmios_qanda::instance_for_id($this->qandaid);
    }
    
    /**
    * json_encode
    * 
    */
    public function jsonSerialize() {
        $fields = $this->get_fields();
         if($this->modifiedtime){
            $fields['asked_time'] = get_string('when_answered_this_question','local_wmios',wmios_time_interval_format(time(),$this->modifiedtime));
        }else{
            $fields['asked_time'] = '';
        }
        return $fields;
    }
}

/**
* The question's follow
*/
class wmios_qanda_follow extends wmios_dbobject{
    
    /**
    * Table name
    * 
    * @var String
    */
    protected static $_table = 'qanda_follow';

    /**
    * Follow question
    * 
    * @param int $userid
    * @param int $qandaid
    * @return self
    */
    public static function create($userid,$qandaid){
        global $DB;
        $data = array(
            'qandaid'=>$qandaid,
            'userid'=>$userid,
            'modifiedtime'=>time());
        $id = $DB->insert_record(static::$_table,(object)$data,true);
        $data['id'] = $id;
        return static::instance_for_fields($data);
    }

    /**
    * Get followers of question
    * 
    * @param int $qandaid
    * @param int $userid if greater than zero,check exisit
    * @param string $sort
    * @return self[]
    */
    public static function instances_for_qandaid($qandaid,$userid = 0,$sort = 'modifiedtime desc'){
        global $DB;
        $params['qandaid'] = $qandaid;
        if($userid){
            $params['userid'] = $userid;
        }
        $records = $DB->get_records(static::$_table,$params,$sort);
        $results = array();
        if($records)foreach($records as $record){
            $results[] = static::instance_for_fields($record);
        }
        return $results;   
    }

    /**
    * Get questions followed by a user
    * 
    * @param int $userid
    * @param string $sort
    * @return self[]
    */
    public static function instances_for_userid($userid,$sort = 'modifiedtime desc'){
        global $DB;

        $params['userid'] = $qandaid;
        $records = $DB->get_records(static::$_table,$params,$sort);
        $results = array();
        if($records)foreach($records as $record){
            $results[] = static::instance_for_fields($record);
        }
        return $results;
    }

    /**
    * Unfollow a question
    * 
    * @param int $qandaid
    * @param int $userid
    * @return bool
    */
    public static function unfollow($qandaid,$userid){
        global $DB;
        return $DB->delete_records(static::$_table,array('qandaid'=>$qandaid,'userid'=>$userid));
    }

    /**
    * Delete followers of a question
    * 
    * @param int $qandaid
    * @return bool
    */
    public static function clear_by_qandaid($qandaid){
        global $DB;
        return $DB->delete_records(static::$_table,array('qandaid'=>$qandaid));
    }
    
    /**
    * Deleted follows for a question set.
    * 
    * @param int[] $qandaids
    * @return bool
    */
    public static function clear_by_qandaids($qandaids){
        global $DB;
        return $DB->delete_records_list(static::$_table, 'qandaid', $qandaids);
    }
    
    /**
    * Deleted follows for a user.
    * 
    * @param int $userid
    * @return bool
    */
    public static function clear_by_userid($userid){
        global $DB;
        return $DB->delete_records(static::$_table,array('userid'=>$userid));
    }
}