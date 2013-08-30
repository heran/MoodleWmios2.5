<?php
require_once($CFG->dirroot.'/local/wmios/lib.php');

/**
* @author Heran
* For user's note info
* 
* @property int $id
* @property int $cmid mod's id
* @property int $userid
* @property int $position mod's position
* @property int $courseid
* @property string $text note's content
* @property int $modifiedtime dateline
*/
class wmios_note extends wmios_dbobject{

    /**
    * Table name
    * 
    * @var String
    */
    protected static $_table = 'notes';

    public static function note_for_fields($fields){
        return self::instance_for_fields($fields);
    }

    public static function note_for_id($id){
        return self::instance_for_id($id);
    }

    /**
    * Get a spec. user's notes for one course module
    * 
    * @param int $userid
    * @param int $cmid
    * @param string $sort
    * @return self[]
    */
    public static function notes_for_userid_and_cmid($userid, $cmid, $sort = 'modifiedtime desc'){
        global $DB;
        $records = $DB->get_records(static::$_table,array('userid'=>$userid,'cmid'=>$cmid),$sort);
        $results = array();
        if($records)foreach($records as $record){
            $results[] = static::note_for_fields($record);
        }
        return $results;
    }

    /**
    * Get a spec. user's notes for one mod and position range
    * 
    * @param int $userid
    * @param int $cmid
    * @param int $min_position
    * @param int $max_position
    * @param string $sort
    * @return self[]
    */
    public static function notes_for_userid_and_cmid_range($userid, $cmid,$min_position =0 ,$max_position=0,$sort = 'modifiedtime desc'){
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
        
        $records = $DB->get_records_sql('select * from {notes} where '.$where.($sort ? ' ORDER BY '.$sort : ''),$params);
        $results = array();
        if($records)foreach($records as $record){
            $results[] = static::note_for_fields($record);
        }
        return $results;
    }

    /**
    * Get a spec. user's Notes for one course
    * 
    * @param mixed $userid
    * @param mixed $courseid
    * @param mixed $include_mod
    * @param mixed $sort
    * @return self[]
    */
    public static function notes_for_userid_and_courseid($userid, $courseid, $include_mod = false,$sort = 'modifiedtime desc'){
        global $DB;
        $where = ' userid=? and courseid=?';
        $params = array($userid,$courseid);
        if(!$include_mod){
            $where .= ' and cmid>0';
        }
        $records = $DB->get_records_sql('select * from {notes} where '.$where.($sort ? ' ORDER BY '.$sort : ''),$params);
        $results = array();
        if($records)foreach($records as $record){
            $results[] = static::note_for_fields($record);
        }
        return $results;
    }

    /**
    * Create one note
    * 
    * @param string $text
    * @param int $userid
    * @param int $courseid
    * @param int $cmid
    * @param int $position
    * @return self
    */
    public static function create($text,$userid,$courseid,$cmid = 0,$position = 0){
        global $DB;
        $data = array(
            'text'=>$text,
            'userid'=>$userid,
            'courseid'=>$courseid,
            'cmid'=>$cmid,
            'position'=>$position,
            'modifiedtime'=>time());
        $id = $DB->insert_record(static::$_table,(object)$data,true);
        $data['id'] = $id;
        return static::note_for_fields($data);

    }
    
    /**
    * when course deleted
    * when block_notes instance deleted
    * 
    * @param mixed $courseid
    */
    public static function clear_for_course_deleted($courseid){
        global $DB;
        return  (bool)$DB->delete_records(static::$_table, array('courseid'=>$courseid));
        
    }
    
    /**
    * when mod deleted
    * 
    * @param mixed $courseid
    */
    public static function clear_for_mod_deleted($cmid){
        global $DB;
        return (bool)$DB->delete_records(static::$_table, array('cmid'=>$cmid));
    }
    
    /**
    * when user deleted
    * 
    * @param mixed $courseid
    */
    public static function clear_for_user_deleted($userid){
        global $DB;
        return (bool)$DB->delete_records(static::$_table, array('userid'=>$userid));
    }    
}
