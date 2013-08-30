<?php
require_once($CFG->libdir.'/completionlib.php');
/**
* Completion Ratio track
* The mod has not ratio track support
*/
define('WMIOS_COMPLETION_RATIO_NONE',-2.0);

/**
* Completion Ratio track
* Hasn't started
*/
define('WMIOS_COMPLETION_RATIO_NOT_START',-1.0);

/**
* Completion Ratio track
* Starting
*/
define('WMIOS_COMPLETION_RATIO_START',0.0);

/**
* Completion Ratio track
* Has completed
*/
define('WMIOS_COMPLETION_RATIO_END',1.0);

/**
* True if mod has code to track completion ratio
* IF a mod can track completion ratio ,It must have these functions:
*   modname_enabled_completion_ratio($cm) The mod enable the completion radio?
*   modname_get_completion_unit($cm) The mod resource's unit that be used for calculate ratio.
*       e.g. s, slide, page
*   modname_get_completion_unit_max($cm) One mod resource's max number.e.g. 100(s),75(slides)
*   modname_get_completion_unit_now($cm,$userid) The number for special user and mod.e.g. 50(s),25(slides)
*   modname_get_completion_ratio($cm,$userid) The ratio for special user and mod.
*       call wmios_completion_info::get_ratio
*   ratio is modname_get_completion_unit_now/modname_get_completion_unit_max. 0.0-1.0
*
*/
define('WMIOS_FEATURE_COMPLETION_TRACKS_RATIOS','completion_tracks_ratios');

/**
* Can track course activity completion ratio
* The ratio is between 0.0-1.0
* @inheritdoc
*
*/
class wmios_completion_info extends completion_info{

    /**
    * Get mod's completion ratio
    *
    * @param stdClass|cm_info $cm course_module
    * @param int $userid
    * @return stdClass wmios_cm_completion
    *     int id autoincrement
    *     int cmid course_module_id
    *     int userid
    *     float completionstate 0.0-1.0 the completion ratio
    *     int timemodified
    */
    public function get_ratio($cm, $userid){
        global $DB;
        if(!$this->enabled_ratio($cm)){
            $dao = new stdClass();
            $completion_type = $this->is_enabled($cm);
            if($completion_type == COMPLETION_TRACKING_NONE){
                $dao->completionstate = WMIOS_COMPLETION_RATIO_NONE;
            }else{
                $data = $this->get_data($cm,true);
                if($data->timemodified == 0){
                    $dao->completionstate = WMIOS_COMPLETION_RATIO_NOT_START;
                }elseif($data->completionstate == COMPLETION_INCOMPLETE){
                    $dao->completionstate = WMIOS_COMPLETION_RATIO_START;
                }else{
                    $dao->completionstate = WMIOS_COMPLETION_RATIO_END;
                }
            }

        }else{
            $dao = $DB->get_record('course_modules_comple_ratio',array('cmid'=>$cm->id,'userid'=>$userid));
            if(!$dao){
                $dao = new stdClass();
                $dao->completionstate = WMIOS_COMPLETION_RATIO_NOT_START;
            }
        }
        return $dao;
    }

    /**
    * Set mod's completion ratio
    * False if the mod isn't support WMIOS_FEATURE_COMPLETION_TRACKS_RATIOS
    * False if the db record is  WMIOS_COMPLETION_RATIO_END    *
    * True if the $ratio is differt the db record
    *
    *
    * @param cm_info $cm course_module
    * @param int $userid
    * @param float $ratio range is 0.0-1.0
    * @return boolean
    */
    public function set_ratio($cm, $userid, $ratio){
        global $DB;
        $ratio+= 0;
        if(!$this->enabled_ratio($cm)){
            return false;
        }

        $ratio_dao = $this->get_ratio($cm,$userid);
        $ratio_dao->completionstate += 0;
        if($ratio_dao->completionstate == WMIOS_COMPLETION_RATIO_END ){
            return false;
        }
        if($ratio >WMIOS_COMPLETION_RATIO_END){
            $ratio = WMIOS_COMPLETION_RATIO_END;
        }elseif($ratio < WMIOS_COMPLETION_RATIO_START){
            $ratio = WMIOS_COMPLETION_RATIO_START;
        }
        if( $ratio_dao->completionstate >= $ratio){
            return false;
        }
        if($ratio_dao->completionstate == WMIOS_COMPLETION_RATIO_NOT_START){
            $DB->insert_record('course_modules_comple_ratio',
                (object)array(
                    'cmid'=>$cm->id,
                    'userid'=>$userid,
                    'completionstate'=>$ratio,
                    'courseid'=>$cm->course,
                    'timemodified'=>time()));
        }else{
            $ratio_dao->completionstate = $ratio;
            $ratio_dao->timemodified = time();
            $DB->update_record('course_modules_comple_ratio',$ratio_dao);
        }
        if($ratio == WMIOS_COMPLETION_RATIO_END){
            $this->update_state($cm,COMPLETION_COMPLETE,$userid);
        }
        return true;
    }

    /**
    * True if the course module can track completion ratio.
    * 
    * @param cm_info|stdClass $cm
    * @return bool
    */
    public function enabled_ratio($cm){
        $enable = true;
        $enable = plugin_supports('mod', $cm->modname, WMIOS_FEATURE_COMPLETION_TRACKS_RATIOS);
        $ef = $cm->modname .'_enabled_completion_ratio';
        $enable = $enable && function_exists($ef) && $ef($cm);
        return $enable;
    }
    
    /**
    * when course deleted
    * 
    * @param int $courseid
    */
    public static function clear_for_course_deleted($courseid){
        global $DB;
        return $DB->delete_records('course_modules_comple_ratio',array('courseid'=>$courseid));
    }
    
    /**
    * When course module deleted
    * 
    * @param int $cmid
    */
    public static function clear_for_mod_deleted($cmid){
        global $DB;
        return $DB->delete_records('course_modules_comple_ratio',array('cmid'=>$cmid));
    }
    
    /**
    * When user deleted
    * 
    * @param int $cmid
    */
    public static function clear_for_user_deleted($userid){
        global $DB;
        return $DB->delete_records('course_modules_comple_ratio',array('userid'=>$userid));
    }
}