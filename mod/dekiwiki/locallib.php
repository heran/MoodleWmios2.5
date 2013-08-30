<?php

define('DEKIWIKI_PLUGIN_NAME','mod_dekiwiki');

require_once($CFG->dirroot.'/local/wmios/lib.php');
require_once ($CFG->dirroot.'/local/wmios/vendor/dreamplug/http_plug.php');
require_once ($CFG->dirroot.'/local/wmios/vendor/dreamplug/dream_plug.php');
require_once ($CFG->dirroot.'/local/wmios/vendor/phplock/class.phplock.php');
require_once(dirname(__FILE__).'/lib.php');

/**
* @property int $id
* @property int $cmid
* @property int $course
* @property int $root_page_id
* @property int $course_page_id
* @property int $group_id
* @property string $name
* @property string $intro
* @property int $introformat
* @property int $timemodified
*/
class dekiwiki_instance extends wmios_module_instance
{
    protected static $_table = 'dekiwiki';

    protected $_fields =array(
        'id'=>0,
        'cmid'=>0,
        'course'=>0,
        'root_page_id'=>0,
        'course_page_id'=>0,
        'group_id'=>0,
        'name'=>'',
        'intro'=>'',
        'introformat'=>0,
        'timemodified'=>0,
    );

    /**
    * @return DreamPlug
    *
    */
    public function get_plug($login_as_admin = true)
    {
        static $plug = array('admin'=>null,'not_admin'=>null);
        if($login_as_admin)
        {
            $k = 'admin';
        }else{
            $k = 'not_admin';
        }
        if($plug[$k] == null)
        {
            $url = get_config(DEKIWIKI_PLUGIN_NAME,'server_url');
            $apikey = get_config(DEKIWIKI_PLUGIN_NAME,'apikey');
            $plug[$k] = new DreamPlug($url,DreamPlug::DREAM_FORMAT_PHP);
            $plug[$k] = $plug[$k]->With('apikey',$apikey)->WithCredentials(
                get_config(DEKIWIKI_PLUGIN_NAME,'superadmin'),
                get_config(DEKIWIKI_PLUGIN_NAME,'superpassword'));
        }
        return $plug[$k];
    }

    /**
    * the course's page name.
    *
    */
    public function get_course_page_title()
    {
        return $this->course.'.'.$this->get_course()->shortname;
    }

    /**
    *
    *
    * @param bool $urlencode
    *
    * @return string
    */
    public function get_course_page_path($urlencode = true)
    {
        $name = trim(get_config(DEKIWIKI_PLUGIN_NAME,'rootname').'/'.$this->get_course_page_title(),'/ ');
        if($urlencode)
        {
            $name = urlencode(urlencode($name));
        }
        return $name;
    }

    /**
    * @return string
    *
    */
    public function get_root_page_title()
    {
        return $this->cmid.'.'.$this->name;
    }

    /**
    * put your comment there...
    *
    * @param bool $urlencode
    *
    * @return string
    */
    public function get_root_page_path($urlencode = true)
    {
        $name =  trim($this->get_course_page_path(false).'/'.$this->get_root_page_title(),'/ ');
        if($urlencode)
        {
            $name = urlencode(urlencode($name));
        }
        return $name;
    }

    public function update_course_page()
    {
        global $CFG, $DB;
        $lock = new PHPLock($CFG->tempdir.'/',$this->course.__FUNCTION__);
        $lock->startLock ();
        while(!$lock->lock());
        //op the course page.may be course name is changed.

        //query in table
        if(!$this->course_page_id)
        {
            $this->course_page_id = (int)$DB->get_field_sql("select course_page_id from {dekiwiki} where course='{$this->course}' limit 1");
        }

        //query in deki server
        $old_coure_page = null;
        if(!$this->course_page_id)
        {
            $old_coure_page = $this->get_deki_page(null,$this->get_course_page_path(true), false);
            if($old_coure_page)
            {
                $this->course_page_id = intval($old_coure_page->attributes()->id);
            }
        }

        if($this->course_page_id)
        {
            //created
            $old_coure_page = $old_coure_page ?: $this->get_deki_page($this->course_page_id);

            $old_course_title = trim((string) $old_coure_page->title);
            $new_course_title = trim($this->get_course_page_title());
            if($old_course_title !== $new_course_title)
            {
                $new_course_page_id = $this->update_deki_page($this->course_page_id, $this->get_course_page_path(false), null);
                if($this->course_page_id != $new_course_page_id)
                {
                    $DB->execute("update {dekiwiki} set course_page_id='{$new_course_page_id}' where course='{$this->course}'");
                    $this->course_page_id = $new_course_page_id;
                }
            }

        }else{
            //creating
            $this->course_page_id = $this->create_deki_page($this->get_course_page_path(true), null, false);
            $this->set_deki_page_group($this->course_page_id, 'Viewer', 'Private');
        }


        $lock->unlock();
        $lock->endLock();
    }

    public function create_root_page()
    {
        if($this->root_page_id>0)
        {
            throw new moodle_exception('root page exisits');
        }

        //group
        $this->update_deki_group();

        //course page
        $this->update_course_page();

        //root page
        $this->root_page_id = $this->create_deki_page($this->get_root_page_path(),null, false);
        $this->set_deki_page_group($this->root_page_id, 'Semi-Contributor', 'Private');
    }

    /**
    *
    *
    */
    public function update_root_page()
    {

        if($this->root_page_id<=0)
        {
            throw new moodle_exception('root page not exisits');
        }

        //group
        $this->update_deki_group();

        //course page
        $this->update_course_page();

        $old_page = $this->get_deki_page($this->root_page_id, null, false);
        $old_title = trim((string) $old_page->title);
        $new_title = trim($this->get_root_page_title());
        if($old_title != $new_title)
        {
            $this->update_deki_page($this->root_page_id, $this->get_root_page_path(false));
        }
    }

    /**
    * put your comment there...
    *
    * @param int $page_id
    * @return SimpleXMLElement
    */
    protected function get_deki_page($page_id, $title = null, $must_exists = false)
    {
        if($title)
        {
            $r = $this->get_plug()->At('pages','='.$title)->Get();
        }else{
            $r = $this->get_plug()->At('pages',$page_id)->Get();
        }
        if($r['status']!=200)
        {
            if($must_exists)
            {
                throw new moodle_exception(print_r($r,true));
            }else{
                return null;
            }
        }
        return simplexml_load_string($r['body']);
    }

    /**
    *
    *
    * @param string $title
    * @param string $contents
    * @param bool $replace_exsits
    * @return int deki page id
    */
    protected function create_deki_page($title, $contents = null, $replace_exsits = false)
    {
        $plug = $this->get_plug()->At('pages', '='.$title, 'contents');
        if(!$replace_exsits)
        {
            $plug = $plug->With('abort', 'exists');
        }
        $r = $plug->Post($contents);
        if($r['status']!= 200)
        {
            throw new moodle_exception(print_r($r,true));
        }
        return intval(simplexml_load_string($r['body'])->page->attributes()->id);
    }

    protected function update_deki_page($page_id, $title= null, $contents = null)
    {
        $r = null;
        if($title != null)
        {
            $r = $this->get_plug()->At('pages', $page_id, 'move')->With('to',$title)->Post();
        }
        if($contents != null)
        {
            $r = $this->get_plug()->At('pages', $page_id, 'contents')->Post($contents);
        }
        if($r != null && $r['status']!= 200)
        {
            throw new moodle_exception(print_r($r,true));
        }
        return $page_id;
    }

    protected function set_deki_page_group($page_id, $role, $restriction = 'Private')
    {
        $security = array();
        $security['security'] = array(
            'permissions.page'=>array('restriction'=>$restriction),
            'grants'=>array(
                array(
                    'grant'=>array(
                        'permissions'=>array('role'=>$role),
                        'group'=>array('@id'=>$this->group_id)
                    )
                )
            )
        );//only admin can edit this page.
        $r = $this->get_plug()->At('pages',$page_id,'security')->Put($security);
        if($r['status']!= 200)
        {

        }
    }

    public function get_group_name($urlencode = true)
    {
        $name = 'moodle.'.$this->course;// . '.'.$this->get_course()->shortname;
        if($urlencode)
        {
            $name = urlencode(urlencode($name));
        }
        return $name;
    }

    protected function get_deki_group($group_id_or_name, $must_exists = false)
    {
        $r = $this->get_plug()->At('groups',$group_id_or_name)->Get();
        if($r['status'] != 200)
        {
            if($must_exists)
            {
                throw new moodle_exception(print_r($r,true));
            }else{
                return null;
            }
        }
        return simplexml_load_string($r['body']);
    }

    protected function update_deki_group($users = array())
    {
        global $CFG, $DB;

        $lock = new PHPLock($CFG->tempdir.'/',$this->course.__FUNCTION__);
        $lock->startLock ();
        while(!$lock->lock());


        //db
        if(!$this->group_id)
        {
            $this->group_id = (int)$DB->get_field_sql("select group_id from {dekiwiki} where course='{$this->course}' limit 1");

        }

        //server
        if(!$this->group_id)
        {
            $group = $this->get_deki_group('='.$this->get_group_name(true));
            if($group)
            {
                $this->group_id = intval($group->attributes()->id);
            }
        }

        //create
        if(!$this->group_id)
        {
            $group = array();
            $group['name'] = $this->get_group_name(false);
            $group['permissions.group']['role'] = 'Contributor';
            $group['users'] = $users;
            $r = $this->get_plug()->At('groups')->Post(array('group'=>$group));
            if($r['status'] == 200)
            {
                $this->group_id = intval(simplexml_load_string( $r['body'])->attributes()->id);
            }else{
                throw new moodle_exception(print_r($r,true));
            }
        }

        //user
        $this->put_enroll_user_to_deki_group();

        $lock->unlock();
        $lock->endLock();

        return $this->group_id;
    }

    public function get_course_enrolled_users($fields = null)
    {
        global $DB, $SITE;
        if(!$fields)
        {
            $fields = 'u.id, u.username, u.firstname, u.lastname, u.email, u.city, u.country, u.picture, u.lang, u.timezone, u.maildisplay, u.imagealt';
        }
        if($this->course == $SITE->id)
        {
            $sql = "SELECT {$fields} FROM  {user} u";
        }else{
            $sql  = "SELECT {$fields}
            FROM {user} u JOIN (
            SELECT DISTINCT eu1_u.id FROM {user} eu1_u
            JOIN {user_enrolments} eu1_ue ON eu1_ue.userid = eu1_u.id
            JOIN {enrol} eu1_e ON (eu1_e.id = eu1_ue.enrolid AND eu1_e.courseid = '{$this->course}')
            WHERE eu1_u.deleted = 0 AND eu1_u.id <> '1' AND eu1_ue.status = '0' AND eu1_e.status = '0'
            ) e ON e.id = u.id";
        }
        $userlist = $DB->get_recordset_sql($sql);
        return $userlist;
    }

    public function get_course_enrolled_deki_userids()
    {
        $users = $this->get_course_enrolled_users();
        $user_ids = array();
        if($users)
        {
            foreach($users as $user)
            {
                $r = $this->get_plug()->At('users','='.$user->username)->Get();
                if($r['status'] == 200)
                {
                    //user exists
                    $user_ids[] = intval(simplexml_load_string( $r['body'])->attributes()->id);
                    continue;
                }

                $new_user = array(
                    'username'=> $user->username,
                    'email' => $user->email,
                    'fullname' => fullname($user),
                    'password' => time(),
                    "permissions.user" => array(
                        'role' => "Contributor"
                    ),
                    'status' => "active"
                );
                $r = $this->get_plug(false)->At("users")->Post(array('user'=>$new_user));
                if($r['status'] == 200)
                {
                    $user_ids[] = intval(simplexml_load_string( $r['body'])->attributes()->id);
                    continue;

                }else{
                    //throw new moodle_exception('can not add user');
                }
            }
        }
        return $user_ids;
    }

    public function put_enroll_user_to_deki_group()
    {
        $user_ids =$this->get_course_enrolled_deki_userids();
        $users = array();
        foreach($user_ids as $id)
        {
            $users[] = array('@id'=>$id);
        }
        $users = array('users'=>array('user'=>$users));
        $r = $this->get_plug()->At('groups',$this->group_id,'users')->Put($users);
        if($r['status'] != 200)
        {
            throw new moodle_exception(print_r($r,true));
        }
    }

    /**
    * @inheritdoc
    *
    */
    public function save()
    {
        if($this->root_page_id<=0)
        {
            $this->create_root_page();
        }else{
            $this->update_root_page();
        }

        parent::save();
    }

    /**
    * login user can not go to view the page.
    *
    */
    public function block_current_user()
    {
        //$url = get_config(DEKIWIKI_PLUGIN_NAME,'browse_url');
        //$host = parse_url($url, PHP_URL_HOST);
        $this->put_enroll_user_to_deki_group();
        //setcookie('authtoken','',null,'/',$host);
        require_capability('mod/dekiwiki:view', $this->get_context());
    }

    /**
    * display the page.
    *
    */
    public function goto_display()
    {
        global $USER;
        $url = get_config(DEKIWIKI_PLUGIN_NAME,'browse_url');
        redirect($url.'/index.php?curid='.$this->root_page_id.'&title='.$this->get_root_page_path(false));
    }

    /**
    * dekiwiki instances for a course
    *
    * @param int $courseid
    * @return self[]
    */
    public static function instances_for_course($courseid)
    {
        return $instances = static::instances_from_select("`course`='{$courseid}'");
    }

    /**
    * when user enrol or unenrol,change the user group.
    *
    * @param mixed $eventdata
    */
    public static function user_enrol_event_handler($eventdata)
    {
        $instances = static::instances_for_course($eventdata->courseid);
        if($instances)
        {
            foreach($instances as $instance)
            {
                $instance->put_enroll_user_to_deki_group();
            }
        }
        return true;
    }

    /**
    * when one user added
    * we add the user into the deki site page.
    *
    * @param mixed $eventdata
    */
    public static function user_updated_event_handler($eventdata)
    {
        global $SITE;
        $instances = static::instances_for_course($SITE->id);
        if($instances)
        {
            foreach($instances as $instance)
            {
                $instance->put_enroll_user_to_deki_group();
            }
        }
        return true;
    }

}