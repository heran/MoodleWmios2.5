<?php

require_once(dirname(__FILE__).'/../vendor/dreamplug/http_plug.php');
require_once(dirname(__FILE__).'/../vendor/dreamplug/dream_plug.php');

/**
* @property int $user_id
* @property int $permission
* @property int $course_id
* @property int $cmid
* @property int $base_id
* @property int $status
* @property string $user_name
* @property int $id
* @property int $key
* @property int $create_time
* @property int $update_time
* @property string $directory
* @property string $title
* @property string $summary
* @property string $keywords
* @property string $file_name
* @property int $file_size
* @property string $file_extension
*/
class document_entity extends document_common
{
    private $_base = null;

    const DOCUMENT_STATUS_DELETE = -1;
    const DOCUMENT_STATUS_DRAFT = 0;
    const DOCUMENT_STATUS_FORMAL = 1;
    const DOCUMENT_STATUS_CONVERTED = 1;

    const DOCUMENT_PERMISSION_PRIVATE = 0;
    const DOCUMENT_PERMISSION_PUBLIC_READ = 1;
    const DOCUMENT_PERMISSION_PUBLIC_COPY = 2;
    const DOCUMENT_PERMISSION_PUBLIC_DOWNLOAD = 3;

    const DOCUMENT_PUT_URI = '/doconvert/document';

    protected $_client_fields = null;

    protected $_server_fields = null;

    protected $_preview_urls = null;

    protected $_highlights = null;

    protected $_fields =array(
        'id'=>0,
        'key'=>'',
        'create_time'=>0,
        'update_time'=>0,
        'directory'=>'',
        'title'=>'',
        'summary'=>'',
        'keywords'=>'',
        'file_name'=>'',
        'file_size'=>'',
        'file_extension'=>'',
        'text'=>''
    );

    public function __construct(document_base $base)
    {
        $this->_server_fields = array_keys($this->_fields);

        $this->_base = $base;

        $fields = $base->refrence_fields_to_server();

        foreach($fields as $field_name)
        {
            $this->_fields[$field_name] = 0;
        }

    }

    public function setField($k,$v)
    {
        //select need a chain for search
        $field_types = $this->_base->get_document_fields_by_type(document_field_type::TYPE_SELECT_SINGLE);
        $keys = array_keys($field_types);
        if(in_array($k,$keys) && $v)
        {
            /** @var document_field_tree */
            $dict = $field_types[$k]->dict->find_descendent_by_id($v);
            if($dict)
            {
                parent::setField($k.'_chain',$dict->getIdChain(false));
            }
        }
        return parent::setField($k,$v);
    }

    /**
    * @return document_base
    *
    */
    public function get_base()
    {
        return $this->_base;
    }

    public function get_server_fields()
    {
        $return = array();

        foreach($this->_base->refrence_fields_to_server() as $server_key=>$my_key)
        {
            $return[$server_key] = $this->getField($my_key);
        }

        foreach($this->_server_fields as $key)
        {
            $return[$key] = $this->getField($key);
        }
        return $return;

    }

    public function set_server_fields($data)
    {

        foreach($this->_base->refrence_fields_to_server() as $server_key=>$my_key)
        {
            if(isset($data['fields']) && isset($data['fields'][$server_key]))
            {
                $this->setField($my_key,$data['fields'][$server_key]);
            }else if(isset($data[$server_key])){
                $this->setField($my_key,$data[$server_key]);
            }
        }
        foreach($this->_server_fields as $key)
        {
            if(isset($data[$key]))
            {
                $this->setField($key,$data[$key]);
            }
        }
    }

    public function set_highlights(array $highlights)
    {
        $this->_highlights = $highlights;
    }

    public function get_highlights($k = null)
    {
        if($k )
        {
            if(isset($this->_highlights[$k]))
            {
                return $this->_highlights[$k];
            }else{
                return null;
            }
        }
        return $this->_highlights;
    }

    public function set_preview_urls($types)
    {
        $this->_preview_urls = array();
        foreach($types as $k=>$v)
        {
            $this->_preview_urls[strtolower($k)] =$v;

        }
        return $this;
    }

    public function get_preview_url($type)
    {
        $type =strtolower($type);
        return isset($this->_preview_urls[$type]) ? $this->_preview_urls[$type] : null;
    }

    public  function file_size_is_valid($fileSize)
    {
        //$option = self::getOption();
        if($fileSize<= 0 || $fileSize >= 20 * 1024 * 1024)
        {
            return false;
        }else{
            return true;
        }

    }

    public  function get_file_extension($fileName,$tolower = false)
    {
        $tmp = array_reverse( explode('.',$fileName));
        return count($tmp)>1 ? ($tolower ? strtolower($tmp[0]) : $tmp[0]) : '';
    }

    public  function file_extension_is_valid($fileName)
    {
        $fileExtension = self::get_file_extension($fileName);
        if(!in_array(strtolower($fileExtension),array('pdf','swf','doc','docx','xls','xlsx','ppt','pptx','txt','jpg','gif','png')))
        {
            return false;
        }else{
            return true;
        }

    }

    public static function get_permission_option_array()
    {
        return array(
            self::DOCUMENT_PERMISSION_PRIVATE=>get_string('document_entity_permission_private', LOCAL_WMIOS_PLUGIN_NAME),
            self::DOCUMENT_PERMISSION_PUBLIC_READ=>get_string('document_entity_permission_public_read', LOCAL_WMIOS_PLUGIN_NAME),
            self::DOCUMENT_PERMISSION_PUBLIC_COPY=>get_string('document_entity_permission_public_copy', LOCAL_WMIOS_PLUGIN_NAME),
            self::DOCUMENT_PERMISSION_PUBLIC_DOWNLOAD=>get_string('document_entity_permission_public_download', LOCAL_WMIOS_PLUGIN_NAME),
            );
    }

    /**
    * put your comment there...
    *
    * @param int $user_id
    * @return bool
    */
    public function belong_to($user_id)
    {
        return $this->user_id == $user_id;
    }

    public function belong_to_current_user($user_id)
    {
        return $this->user_id == $user_id;
    }

    public function can_be_download_by_current_user()
    {
        global $USER;
        return $this->user_id == $USER->id || $this->status == self::DOCUMENT_PERMISSION_PUBLIC_DOWNLOAD;
    }
}

/**
* @property int $id
* @property int $course
* @property string $name
* @property string $intro
*/
class document_base extends document_common{

    protected static $_table = 'document';

    protected $_fileds_to_server = null;

    /** @var stdClass|mixed course module */
    protected $_cm = null;

    /** @var stdClass|mixed course*/
    protected $_course = null;

    /** @var DreamPlug*/
    protected $_plug = null;

    private $_spellchecks = null;

    protected $_document_fields = null;

    protected $_fields = array(
        'id'=>0,
        'course'=>0,
        'name'=>'',
        'intro'=>'',
        'introformat'=>0,
        'timemodified'=>0
    );

    public function __construct($fields)
    {
        parent::__construct($fields);

        $config = (array)get_config('local_wmios');
        $plug = new DreamPlug(trim($config['document_apiurl'],'/ '));
        $this->_plug = $plug->With('dream.out.format',DreamPlug::DREAM_FORMAT_PHP)
            ->WithHeader('X-ClientKey',$config['document_client_key'],true)
            ->WithHeader('X-ClientPas',$config['document_client_pas'],true);
    }

    /**
    * Delete a document entity
    *
    * @param document_entity $entity
    * @return bool
    */
    public function delete_entity(document_entity $entity)
    {
        if(!$entity){
            return false;
        }
        $entity->status = document_entity::DOCUMENT_STATUS_DELETE;
        $this->save_entity($entity);
        return true;
    }

    /**
    * put your comment there...
    *
    * @param array $q
    * @param int $total
    * @param int $page
    * @param int $perpage
    * @return document_entity[]
    */
    public function get_entities_by_query($q, &$total, &$subsidiary, $page = 0, $perpage = 0)
    {
        /** @var DreamPlug*/
        $plug = $this->_plug->At('doconvert', 'documents');
        $fields = $this->refrence_fields_to_server();
        $q['cmid'] = $this->get_cm()->id;//in this base.
        if(!isset($q['min_status']))
        {
            $q['min_status'] = document_entity::DOCUMENT_STATUS_DRAFT;
        }

        if(isset($q['searchwords']))
        {
            $q['query_type'] = 'everything';
            $q['highlight_field'] = 'title,summary,keywords,file_name,text';
            $q['highlight_before_term'] = '<span class="highlight">';
            $q['highlight_after_term'] = '</span>';
            $q['order_by'] = 'score desc';
            $q['spellcheck'] = 'true';
            $q['terms'] = 'true';
        }
        $q['mlt'] = (isset($q['mlt']) && $q['mlt']) ? 'true':'false';
        $q['mltfq'] = (isset($q['mlt']) && $q['mlt']) ?  current(array_keys($fields,'cmid')).':'.$q['cmid'] : '';

        foreach((array)$q as $k=>$v)
        {
            $set = false;

            // max mysql where field <= $v
            // min  mysql where field >= $v
            // left  mysql where left(field,len) = $v
            foreach( array('min_','max_','left_','not_') as $tor)
            {
                if(substr($k,0,strlen($tor)) != $tor)
                {
                    continue;
                }
                $k =  substr($k,strlen($tor));
                if(in_array($k,$fields))
                {
                    $plug = $plug->With($tor.array_search($k,$fields),$v);
                }else{
                    $plug = $plug->With($tor.$k,$v);
                }
                $set = true;
                break;
            }
            if($set)
            {
                continue;
            }
            if(in_array($k,$fields))
            {
                $plug = $plug->With(array_search($k,$fields),$v);
            }
            else{
                $plug = $plug->With($k,$v);
            }

        }
        if( $perpage){
            $plug = $plug->With('start',$page*$perpage)->With('limit',$perpage);
        }
        $result = $plug->Get();
        $return = array();
        if($result['status'] == 200)
        {
            $all = $result['body']['result'];
            $total = $all['total'];
            if(is_array($all['documents']) && count($all['documents']))
            {
                foreach($all['documents'] as $doc)
                {

                    $de = new document_entity($this);
                    $de->set_server_fields($doc);
                    if(isset($doc['preview']))
                    {
                        $de->set_preview_urls($doc['preview']);
                    }
                    if(isset($doc['highlights']))
                    {
                        $de->set_highlights($doc['highlights']);
                    }
                    $return[$de->key] = $de;
                }
            }

            if(isset($all['spellcheck']) && is_array($all['spellcheck']))
            {
                foreach($all['spellcheck'] as $suggestion)
                {
                    $subsidiary['spellcheck'] = array();
                    foreach((array)$suggestion['word'] as $word)
                    {
                        if(!$word)
                        {
                            continue;
                        }
                        $subsidiary['spellcheck'][] = $word;
                    }
                }
            }

            if(isset($all['terms']) && is_array($all['terms']))
            {
                $subsidiary['terms'] = array();
                foreach((array)$all['terms']['term'] as $term)
                {
                    if(!$term)
                    {
                        continue;
                    }
                    $subsidiary['terms'][] = $term;
                }
            }

            if(isset($all['mlt']) && is_array($all['mlt']))
            {
                $subsidiary['mlt'] = $all['mlt']['document'];
            }



        }else{
            throw new moodle_exception('can not find documents:');
        }
        return $return;
    }

    /**
    * put your comment there...
    *
    * @param mixed $key
    * @return document_entity
    */
    public function get_entity_by_key($key,$get_similiar = true)
    {
        $total;
        $result = $this->get_entities_by_query(array('key'=>$key,'mlt'=>$get_similiar),$total,$nothing);
        if(count($result)!=1)
        {
            return null;
        }
        return current($result);

    }

    /**
    * put your comment there...
    *
    * @param int $id
    * @return document_entity
    */
    public function get_entity_by_id($id,$get_similiar = false)
    {
        $total;
        $result = $this->get_entities_by_query(array('id'=>$id,'mlt'=>$get_similiar),$total);
        if(count($result)!=1)
        {
        //throw
        }
        return current($result);
    }

    /**
    * put your comment there...
    *
    * @param document_entity $entity
    * @param byte[] $content
    * @return bool
    */
    public function save_entity(document_entity $entity, $content = null)
    {
        $plug = $entity->key ?
            $this->_plug->At('doconvert','document',$entity->key) :
            $this->_plug->At('doconvert','document');
        $fields = $entity->get_server_fields();
        if($content)
        {
            $fields['content'] = base64_encode($content);
        }
        $result = $plug->Post((array)array('document'=>$fields));
        if($result['status'] == 200)
        {
            $entity->set_server_fields(current($result['body']));
        }else{
            throw new moodle_exception('can not save this ');
        }
        return true;
    }

    /**
    * put your comment there...
    *
    * @param array $file
    * @return document_entity
    */
    public function get_entity_by_upload_file($file)
    {
        global $USER;

        $entity = new document_entity($this);
        $key = null;
        $plug = $this->_plug->At('doconvert','document');
        $entity->file_name = $file['name'];
        $entity->title = $file['name'];
        $entity->base_id = $this->id;
        $entity->course_id = $this->course_id;
        $entity->cmid = $this->get_cm()->id;
        $entity->directory = $this->course_id.'/'.$this->cmid;
        $entity->user_id = $USER->id;
        $entity->user_name = fullname($USER);
        $entity->status = document_entity::DOCUMENT_STATUS_DRAFT;
        $entity->permission = document_entity::DOCUMENT_PERMISSION_PUBLIC_READ;

        $this->save_entity($entity,file_get_contents($file['tmp_name']));
        return $entity;
    }

    /**
    * put your comment there...
    *
    * @param int $user_id
    * @return document_entity[]
    */
    public function get_user_draft_entities($user_id)
    {
        $q = array('user_id'=>$user_id,'status'=>document_entity::DOCUMENT_STATUS_DRAFT);
        return $this->get_entities_by_query($q, $total, $nothing);
    }


    /**
    * put your comment there...
    *
    * @param mixed $cm
    * @return self
    */
    public function set_cm($cm)
    {
        if($cm->instance == $this->id)
        $this->_cm = $cm;
        return $this;
    }

    public function get_cm()
    {
        if($this->_cm === null)
        {
            $this->_cm = get_coursemodule_from_instance('document', $this->id, $this->course_id, false, MUST_EXIST);
        }
        return $this->_cm;
    }

    /**
    * put your comment there...
    *
    * @param stdClass|mixed $course
    * @return self
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
    * @return stdClass|mixed $course refrence by course_id
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

    /**
    * put your comment there...
    *
    * @param array $fields
    * @param int $preclear -1 0 1
    */
    public function update_document_fields($fields, $preclear = 0)
    {
        global $DB;
        if($this->id <= 0){
            throw new moodle_exception('no base id');
        }
        if($preclear<0)
        {
            $DB->delete_records_select('document_field', "mid='{$this->id}' and type<0");
        }else if($preclear>0)
        {
            $DB->delete_records_select('document_field', "mid='{$this->id}' and type>0");
        }
        foreach($fields as $p)
        {
            $DB->insert_record('document_field',(object)array('mid'=>$this->id,'type'=>$p));
        }
    }

    /**
    * @return int[]
    *
    */
    public function get_document_fields_id()
    {
       return static::document_fields_id_by_baseid($this->id);
    }

    /**
    * put your comment there...
    *
    * @param int $base_id
    * @return int[]
    */
    public static function document_fields_id_by_baseid($base_id)
    {
        global $DB;
        $tmp =  $DB->get_records('document_field',array('mid'=>$base_id));
        $return = array();
        foreach($tmp as $record)
        {
            $return[] = $record->type;
        }
        return $return;
    }

    /**
    * @return document_field_type[]
    *
    */
    public function get_document_fields()
    {
        if($this->_document_fields === null)
        {
            $ids = $this->get_document_fields_id();
            $return = array();
            if(count($ids))
            {
                $ids = implode(',',$ids);
                $return = document_field_type::instances_from_select('id in ('.$ids.')');
            }
            $this->_document_fields = $return;
        }
        return $this->_document_fields;

    }

    /**
    * put your comment there...
    *
    * @param mixed $type
    * @return document_field_type[]
    */
    public function get_document_fields_by_type($type)
    {
        $fields = $this->get_document_fields();
        $return = array();
        foreach($fields as $field)
        {
            if($field->type == $type)
            {
                $return[$field->name] = $field;
            }
        }
        return $return;

    }

    public function refrence_fields_to_server()
    {
        if($this->_fileds_to_server === null)
        {
            $int_i=6;
            $text_i = 6;
            $char_i = 6;
            $return = array();
            $return['int_'.$int_i++] = 'user_id';
            $return['text_'.$text_i++] = 'user_name';
            $return['int_'.$int_i++] = 'permission';
            $return['int_'.$int_i++] = 'status';
            $return['int_'.$int_i++] = 'course_id';
            $return['int_'.$int_i++] = 'cmid';
            $return['int_'.$int_i++] = 'base_id';

            $user_defined_fields = $this->get_document_fields();
            ksort($user_defined_fields);
            foreach($user_defined_fields as $field_type)
            {
                /** @var document_field_type*/
                $field_type;
                if($field_type->is_dictionary_type())
                {
                    $return['int_'.$int_i++] = $field_type->name;
                    $return['char_'.$char_i++] = $field_type->name.'_chain';

                }else{
                    $return['text_'.$text_i++] = $field_type->name;
                }

            }
            $this->_fileds_to_server = $return;
        }
        return $this->_fileds_to_server;
    }

    /**
    * put your comment there...
    *
    * @return static[]
    */
    public static function get_current_user_all_bases()
    {
        $courses = enrol_get_my_courses('id, shortname, fullname, modinfo, sectioncache');
        $bases = array();
        if($courses)
        {
            $bases = (array)self::instances_from_select('course in ('.implode(',',array_keys($courses)).')');
            foreach($bases as &$base)
            {
                $base->set_course($courses[$base->course]);
            }
        }
        return $bases;
    }

    /**
    * When user is in one document base, he need go to some other base.
    *
    */
    public function get_course_document_base_navigation_option_array()
    {
        $bases = (array)self::get_current_user_all_bases();
        $return = array();
        foreach($bases as $base)
        {
            if($base->id == $this->id)
            {
                $return[$base->id] =  get_string('current_document_base',MOD_DOCUMENT_PLUGIN_NAME).' : '.$base->name;

            }else
            {
                $return[$base->id] = $base->get_course()->shortname.' : '.$base->name;
            }

        }
        return $return;
    }

}

/**
* @property int $id
* @property int $course_id
* @property string $name
* @property string $type document_field_type::TYPE_*
* @property int $dict_root
* @property int $user_id
* @property int $permission
* @property int $updatetime
* @property string $remark
* @property document_field_tree $dict
*/
class document_field_type extends document_common
{
    /** type dictionary*/
    const TYPE_SELECT_SINGLE = 'select_single';

    /** type dictionary*/
    const TYPE_SELECT_MULTI = 'select_multi';

    /** type string*/
    const TYPE_INPUT_STRING = 'input_string';

    /** type int unix time*/
    const TYPE_INPUT_TIME = 'input_time';

    const PERMISSION_PUBLIC = 1;
    const PERMISSION_PRIVATE = 0;

    const PREDEFINED_USER_ID = -1;
    const PREDEFINED_USER_NAME = -2;
    const PREDEFINED_PERMISSION = -3;
    const PREDEFINED_STATUS = -4;

    protected static $_table = 'document_field_type';

    protected $_dict = null;

    protected $_fields = array(
        'id'=>0,
        'course_id'=>0,
        'name'=>'',
        'type'=>self::TYPE_INPUT_STRING,
        'dict_root'=>0,
        'user_id'=>0,
        'permission'=>self::PERMISSION_PUBLIC,
        'updatetime'=>0,
        'remark'=>'',
    );

    public function __get($k)
    {
        switch($k)
        {
            case 'dict':
                return $this->dict_root ? document_field_tree::instance_from_id($this->dict_root) : null;
                break;
            default:
                return parent::__get($k);
                break;
        }
    }

    public static function type_option_array()
    {
        return array(
            //static::TYPE_SELECT_MULTI=>get_string(static::TYPE_SELECT_MULTI,MOD_DOCUMENT_PLUGIN_NAME),
            static::TYPE_SELECT_SINGLE=>get_string(static::TYPE_SELECT_SINGLE,MOD_DOCUMENT_PLUGIN_NAME),
            static::TYPE_INPUT_STRING=>get_string(static::TYPE_INPUT_STRING,MOD_DOCUMENT_PLUGIN_NAME),
            //static::TYPE_INPUT_TIME=>get_string(static::TYPE_INPUT_TIME,MOD_DOCUMENT_PLUGIN_NAME),
        );
    }

    public static function permission_option_array()
    {
        return array(
            static::PERMISSION_PUBLIC=>get_string('field_type_permission_'.static::PERMISSION_PUBLIC,MOD_DOCUMENT_PLUGIN_NAME),
            static::PERMISSION_PRIVATE=>get_string('field_type_permission_'.static::PERMISSION_PRIVATE,MOD_DOCUMENT_PLUGIN_NAME),
        );
    }

    public function is_dictionary_type()
    {
        return in_array($this->type ,array(static::TYPE_SELECT_MULTI,static::TYPE_SELECT_SINGLE));
    }

    public function is_mine()
    {
        global $USER;
        return $this->user_id == $USER->id;
    }

    /**
    *
    * copy a new field type
    *
    * @param document_field_type $old
    * @param int $course_id into which course when 0, copy into the same course.
    */
    public static function copy_new(document_field_type $old, $course_id = 0)
    {
        //TODO use transction

        $new = new static($old->getFields());
        $new->id = 0;
        $new->remark = get_string('copy').':'.$new->remark;
        $new->course_id = $course_id ?: $new->course_id ;
        $new->save();
        if($old->is_dictionary_type())
        {
            $new->dict_root = document_field_tree::copy_new( $old->dict,$new)->id;
        }
        $new->save();
        return $new;
    }

    /**
    * all public type for copy
    *
    * @param int $course_id
    * @return document_field_type[][]
    */
    public static function get_public_field_types($course_id = 0)
    {
        $tmp = static::instances_from_select(
            "permission='".static::PERMISSION_PUBLIC."'" .($course_id ? " and course_id='{$course_id}'":""));
        $types = array();
        foreach($tmp as $type)
        {
            if(has_capability('mod/document:field_manage',context_course::instance($type->course_id)))
            {
                if(!isset($types[$type->course_id]))
                {
                    $types[$type->course_id] = array();
                }
                $types[$type->course_id][] = $type;
            }
        }
        return $types;

    }

    /**
    * put your comment there...
    *
    * @param int $course_id
    * @return document_field_type[]
    */
    public static function get_types_by_courseid($course_id)
    {
        return static::instances_from_select("course_id='{$course_id}'");
    }


}

/**
* @property self $parent
* @property self[] $children
* @property int $id
* @property int $pid
* @property string $content
* @property int $level
* @property int $sort
* @property string $remark
* @property int $status
* @property int $updatetime
*
*/
class document_field_tree extends document_common{

    protected static $_table = 'document_field_dict';



    protected $_fields = array(
        'id'=>0,
        'pid'=>0,
        'content'=>'',
        'level'=>0,
        'sort'=>0,
        'remark'=>'',
        'status'=>0,
        'updatetime'=>0,
    );

    protected $_parent = null;

    protected $_children = null;

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
                return parent::__get($k);
                break;
        }
    }

    public function getChildren()
    {
        global $DB;

        if($this->_children === null)
        {
            $this->_children = static::instances_from_select("pid='{$this->id}' and level={$this->level}+1",null,'sort desc');;
        }
        return (array)$this->_children;
    }

    public function getParent()
    {
        //level eq 0 ,it is a root.
        //also pid eq 0, it is a root.
        //now pid neq 0 ,but level eq 0 , it is root.
        if(!$this->pid || $this->level == 0) return null;
        if($this->_parent === null)
        {
            $this->_parent = static::instance_from_id($this->pid);
        }
        return $this->_parent;
    }

    public function get_select_option_array()
    {
        $return = array(0=>get_string('all').$this->content);
        $children = (array)$this->getChildren();
        foreach($children as $child)
        {
            $return[$child->id] = $child->content;
        }
        return $return;

    }

    public function getIdChain($typeArray = true)
    {
        $chain = array();
        $parent = $this->parent;
        $chain[] = $this->id;
        while($parent)
        {
            $chain[] = $parent->id;
            $parent = $parent->parent;
        }
        $chain = array_reverse($chain);
        if($typeArray )
        {
            return $chain;

        }else{
            return implode('-',$chain).'-';
        }
    }

    public function getContentChain($typeArray = true)
    {
        $chain = array();
        $parent = $this->parent;
        $chain[] = $this->content;
        while($parent)
        {
            $chain[] = $parent->content;
            $parent = $parent->parent;
        }
        $chain = array_reverse($chain);
        if($typeArray )
        {
            return $chain;

        }else{
            return implode('-',$chain);
        }

    }

    /**
    * put your comment there...
    *
    * @param int $id
    * @return self
    */
    public function find_descendent_by_id($id)
    {
        if($this->id == $id)
        {
            return $this;
        }else{
            foreach($this->getChildren() as $child)
            {
               $r = $child->find_descendent_by_id($id);
               if($r)
               {
                   return $r;
               }
            }
        }
        return null;
    }

    public function toArray()
    {
        $return = $this->getFields();
        $return['children'] = array();
        if($this->children)
        {
            foreach($this->children as $child)
            {
                $return['children']['c'.$child->id] = $child->toArray();
            }

        }
        return $return;
    }

    /**
    * put your comment there...
    *
    * @param document_field_tree $old
    * @param document_field_tree|document_field_type $parent
    * @return static
    */
    public static function copy_new(document_field_tree $old, $parent)
    {
        $new = new static($old->getFields());
        $new->id = 0;
        $new->pid = $parent->id;
        if($new->level == 0)
        {
            // when level eq 0, mean $parent is document_field_type
            $new->content = $parent->name;
        }
        $new->save();
        if($old->children)
        {
            foreach($old->children as $child)
            {
                static::copy_new($child, $new);
            }
        }
        return $new;
    }




}

/**
* @property int $id
*/
class document_common extends wmios_common_object
{

}