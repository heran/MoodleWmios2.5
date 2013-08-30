<?php
require_once("$CFG->libdir/filelib.php");
require_once("$CFG->libdir/resourcelib.php");
require_once(dirname(__FILE__).'/lib.php');


/**
* multimedia type base class
* @property int $id
*/
class multimedia_base{
    
    /**
    * @var cm_info|stdClass
    */
    protected $_cm = null;
    
    /**
    * @var stdClass
    */
    protected $_multimedia = null;

    public function __construct(stdClass $multimedia, $cm = null){
        $this->set_cm($cm);
        $this->set_multimedia($multimedia);
    }
    
    public function __get($k){
        return isset($this->_multimedia->$k) ? $this->_multimedia->$k : null;
    }
    
    public function set_multimedia(stdClass $multimedia){
        $this->_multimedia = $multimedia;
    }
    
    public function get_multimedia(){
        return $this->_multimedia;
    }
    
    public function set_cm( $cm){
        $this->_cm = $cm;
    }
    
    public function get_cm(){
        return $this->_cm;
    }

    static function get_unit(){

    }

    static function get_units(){

    }

    /**
    * 
    * @param cm_info|stdClass $cm
    * @return self
    */
    final static function instance_for_course_module( $cm){
        global $DB;
        static $instance = array();
        if(!isset($instance[$cm->id])){
            $record = $DB->get_record('multimedia',array('id'=>$cm->instance));
            if(!$record){
                return null;
            }
            $type = mod_multimedia_type::get_type($record->type);

            /**
            * @var multimedia_base
            */
            $class_name = $type['class'];
            $instance[$cm->id] = new $class_name($record,$cm);
        }
        return $instance[$cm->id];
    }
    
    /**
    * 
    * @param int $id
    * @return self
    */
    final static function instance_for_multimedia_id($id){
        global $DB;
        $cm = get_coursemodule_from_instance('multimedia',$id);
        return self::instance_for_course_module($cm);
    }
    
    public function format_unit($num){
        
    }
    
    public function get_completion_unit_now($userid){
        global $DB;
        return $DB->get_field('multimedia_completion','completion_now',array('mid'=>$this->id,'userid'=>$userid));
    }
    
    public function set_completion_unit_now($userid,$now){
        global $DB;
        $last = $this->get_completion_unit_now($userid);
        if($last == $now){
            return false;
        }elseif($last===false){
            return $DB->insert_record('multimedia_completion',
                (object)array(
                    'userid'=>$userid,
                    'mid'=>$this->id,
                    'completion_now'=>$now,
                    'timemodified'=>time()));
        }else{
            return $DB->set_field('multimedia_completion','completion_now',$now,
                array('mid'=>$this->id,'userid'=>$userid,'timemodified'=>time()));
        }
    }
    
    public function get_completion_unit_max(){
        global $DB;
        return $DB->get_field('multimedia','completion_max',array('id'=>$this->id));

    }
    
    public function enabled_completion_ratio(){
        global $DB;
        return $DB->get_field('multimedia','completionenabled',array('id'=>$this->id));
    }
    
    final static public function add_instance($data, $mform = null){
        global $CFG, $DB;
        require_once("$CFG->libdir/resourcelib.php");

        $data->timemodified = time();
        
        /*$displayoptions = array();
        if ($data->display == RESOURCELIB_DISPLAY_POPUP) {
            $displayoptions['popupwidth']  = $data->popupwidth;
            $displayoptions['popupheight'] = $data->popupheight;
        }
        $displayoptions['printheading'] = $data->printheading;
        $displayoptions['printintro']   = $data->printintro;
        $data->displayoptions = serialize($displayoptions);*/
        $data->displayoptions = '';
        $data->display = RESOURCELIB_DISPLAY_OPEN;
        
        $data->content = base64_decode($data->content);
        $data->contentformat = FORMAT_HTML;
        
        $type = mod_multimedia_type::find_type_by_content($data->content);
        if(!$type){
            return false;
        }
        /** @var multimedia_base */
        $class_name = $type['class'];
        $data->completion_max = $class_name::parse_multimedia_length($data->content);
        
        $data->type = $type['type'];

        $data->id = $DB->insert_record('multimedia', $data);

        // we need to use context now, so we need to make sure all needed info is already in db
        //$DB->set_field('course_modules', 'instance', $data->id, array('id'=>$cmid));
        //$context = context_module::instance($cmid);

        return $data->id;
    }
    
    final static public function update_instance($data, $mform = null) {
        global $CFG, $DB;
        require_once("$CFG->libdir/resourcelib.php");

        $data->timemodified = time();
        $data->id           = $data->instance;
        $data->revision++;

        /*$displayoptions = array();
        if ($data->display == RESOURCELIB_DISPLAY_POPUP) {
            $displayoptions['popupwidth']  = $data->popupwidth;
            $displayoptions['popupheight'] = $data->popupheight;
        }
        $displayoptions['printheading'] = $data->printheading;
        $displayoptions['printintro']   = $data->printintro;
        $data->displayoptions = serialize($displayoptions);*/
        $data->displayoptions = '';
        $data->display = RESOURCELIB_DISPLAY_OPEN;

        $data->content       = clean_param( base64_decode($data->content),PARAM_CLEANHTML);
        $data->contentformat = FORMAT_HTML;
        
        $type = mod_multimedia_type::find_type_by_content($data->content);
        if(!$type){
            return false;
        }
        /** @var multimedia_base */
        $class_name = $type['class'];
        $data->completion_max = $class_name::parse_multimedia_length($data->content);
        
        $data->type = $type['type'];

        $DB->update_record('multimedia', $data);

        /*$context = context_module::instance($cmid);
        if ($draftitemid) {
            $data->content = file_save_draft_area_files($draftitemid, $context->id, 'mod_multimedia', 'content', 0, multimedia_get_editor_options($context), $data->content);
            $DB->update_record('multimedia', $data);
        }*/

        return true;
    }
    
    static public function delete_instance($id) {//TODO delete multimedia_completion

        global $DB;

        if (!$multimedia = $DB->get_record('multimedia', array('id'=>$id))) {
            return false;
        }

        // note: all context files are deleted automatically

        $DB->delete_records('multimedia', array('id'=>$multimedia->id));

        return true;
    }
    
    /**
    * True if the html string contain multimedia
    * 
    * @param string $content
    * @return bool
    */
    public static function contain_multimedia($content){
        return false;
    }
    
    /**
    * If the html string contain multimedia,then it has how many seconds(video),slides(ppt)?
    *  
    * @param string $content
    * @return int
    */
    public static function parse_multimedia_length($content){
        return -1;
    }
}

/**
* Get Some type info
*/
class mod_multimedia_type{

    /**
    * type info detail
    * 
    * @var array
    */
    static protected $_multimedia_types = null;

    /**
    * from get_plugin_list();
    * @see get_plugin_list()
    * 
    * @var mixed
    */
    static protected $_plugins_info = null;

    protected static function get_plugins_info(){
        
        if(static::$_plugins_info === null){
            $dir = new DirectoryIterator( dirname(__FILE__).'/type');
            foreach($dir as $file){
                if($file->isDot()){
                    continue;
                }elseif(!stristr($file->getFilename(),'.class.php'))
                {
                    continue;
                }
                $pluginname = str_ireplace('.class.php', '', $file->getFilename());
                
                require_once ($file->getPathname());
                static::$_plugins_info[$pluginname] = $file;
            }
        }
        return static::$_plugins_info;
    }

    public static function get_type($name){
        $types = static::get_types();
        return isset($types[$name]) ? $types[$name] : null;
    }

    public static function get_types(){
        if(static::$_multimedia_types === null){
            static::$_multimedia_types = array();
            $plugins = static::get_plugins_info();
            foreach($plugins as $plugin_name=>$nothing){
            	/* @var $class_name  multimedia_base   */
            	/** @var  multimedia_base   */
                $class_name = 'multimedia_'.$plugin_name;
                static::$_multimedia_types[$plugin_name]['name'] = get_string('type_'.$plugin_name,'mod_multimedia');
                static::$_multimedia_types[$plugin_name]['unit'] = $class_name::get_unit();
                static::$_multimedia_types[$plugin_name]['units'] = $class_name::get_units();
                static::$_multimedia_types[$plugin_name]['class'] = $class_name;
                static::$_multimedia_types[$plugin_name]['type'] = $plugin_name;
            }         
        }
        return static::$_multimedia_types;
    }

    public static function get_options(){
        $types = static::get_types();
        $return = array();
        foreach($types as $name=>$type){
            $return[$name] = $type['name'];
        }
        return $return;
    }
    
    /**
    * when client post a content field, call me to get a multimedia type class name
    * 
    * @param string $content html content
    * @return array|null
    */
    public static function find_type_by_content($content){
        $types = static::get_types();
        $class = null;
        foreach($types as $type){
            /** @var multimedia_base */
            $type_class = $type['class'];
            if($type_class::contain_multimedia($content)){
                $class = $type;
                break;
            }            
        }
        return $class;
    }
}

/**
* File browsing support class
*/
class multimedia_content_file_info extends file_info_stored {
    public function get_parent() {
        if ($this->lf->get_filepath() === '/' and $this->lf->get_filename() === '.') {
            return $this->browser->get_file_info($this->context);
        }
        return parent::get_parent();
    }
    public function get_visible_name() {
        if ($this->lf->get_filepath() === '/' and $this->lf->get_filename() === '.') {
            return $this->topvisiblename;
        }
        return parent::get_visible_name();
    }
}

function multimedia_get_editor_options($context) {
    global $CFG;
    return array('accepted_types' => '*','return_types'=>FILE_EXTERNAL);
    return array('subdirs'=>1, 'maxbytes'=>$CFG->maxbytes, 'maxfiles'=>1, 'changeformat'=>0, 'context'=>$context, 'noclean'=>1, 'trusttext'=>0);
}

