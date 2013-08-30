<?php

define('LOCAL_WMIOS_PLUGIN_NAME','local_wmios');
require_once dirname(__FILE__).'/locallib.php';
require_once($CFG->dirroot.'/enrol/locallib.php');
require_once($CFG->libdir. '/filestorage/file_storage.php');
require_once($CFG->dirroot. '/course/lib.php');
require_once($CFG->dirroot. '/lib/pluginlib.php');
require_once($CFG->libdir. '/filelib.php');

/**
* get course's cover url
*
* @param stdClass $course
*/
function get_course_cover_url(stdClass $course,$preview = 'thumb'){
    global $CFG, $OUTPUT;

    $cover_url = $OUTPUT->pix_url('cover-default','theme');
    if (empty($CFG->courseoverviewfileslimit)) {
        return $cover_url;
    }

    $fs = get_file_storage();
    $context = context_course::instance($course->id);
    /** @var stored_file[] */
    $files = $fs->get_area_files($context->id, 'course', 'overviewfiles', false, 'filename', false);
    if (count($files)) {
        foreach ($files as $key => $file) {
            if(!$file->is_valid_image()){
                continue;
            }
            $filename = $file->get_filename();
            //file type must match.
            $overviewfilesoptions = course_overviewfiles_options($course->id);
            $acceptedtypes = $overviewfilesoptions['accepted_types'];
            if ($acceptedtypes === '*' ||  file_extension_in_typegroup($filename, $acceptedtypes)) {
                $cover_url = file_encode_url("{$CFG->wwwroot}/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), false);
            }
            //file name must cover.
            if(strtolower( pathinfo( $filename,PATHINFO_FILENAME)) === 'cover'){
                break;
            }
        }
    }
    return $cover_url;

    /*global $OUTPUT,$CFG;
    $course_context = context_course::instance($course->id, MUST_EXIST);

    $browser = new file_browser();
    $fs = get_file_storage();
    $filepath = '/';
    $filename = 'cover.png';
    if (!$storedfile = $fs->get_file($course_context->id, 'course', 'summary', 0, $filepath, $filename)) {
        if ($filepath === '/' and $filename === '.') {
            $storedfile = new virtual_root_file($course_context->id, 'course', 'summary', 0);
        }
    }
    $urlbase = $CFG->wwwroot.'/pluginfile.php';
    $cover_info = $storedfile ? new file_info_stored($browser, $course_context, $storedfile, $urlbase, get_string('areacourseintro', 'repository'), false, true, true, false) : null;

    //$browser = new file_browser();
    //$cover_info  = $browser->get_file_info($course_context, 'course', 'summary', 0, '/', 'cover.png');
    if($cover_info)
    {
        $mUrl = new moodle_url($cover_info->get_url());
        $cover_url = $mUrl->out(false, array('preview' => $preview, 'oid' => $cover_info->get_timemodified()));
    }else{
        $cover_url = $OUTPUT->pix_url('cover-default','theme');
    }
    return $cover_url;*/
}

/**
* get the course's category list
* The List is a stack./CatA/CatB/CatC/Course becomes array(CatC,CatB,CatA),The Cat is a record of course_categories
*
* @param stdClass $course
* @return array. category is a stdClass
*/
function get_course_category_list(stdClass $course){
    global $DB;
    $cats = array();
    $thiscat = $DB->get_record('course_categories', array('id' => $course->category));
    $tmp = explode('/',trim($thiscat->path,'/'));
    if(count($tmp)>1)
    {
        for($i=0;$i<count($tmp)-1;$i++)
        {
            $cats[] = $DB->get_record('course_categories', array('id' => $tmp[$i]));
        }
    }
    $cats [] = $thiscat;
    return $cats;
}

function render_course_category_list(stdClass $course){
    $cats = get_course_category_list($course);
    $course_category = '<ul>';
    foreach($cats as $k=> $cat)
    {
        $catname = format_string($cat->name, true, array('context' => context_coursecat::instance($cat->id)));
        $caturl = new moodle_url('/course/categoryn.php',array('id'=>$cat->id));
        $course_category .= '<li'.(($k==count($cats)-1)?' class="last"':'').'>'.html_writer::link($caturl,$catname).'</li>';
    }
    $course_category .= '</ul>';
    return $course_category;
}

/**
* get courses for overview with extra info
*
* @param array $courses. course is a stdClass
* @param bool $bytype.  If true ,the course identied by inprogress nostart completed nostart.
*
* @return stdClass[].
*       If $bytype is ture,
*       return array('inprogress'=>array(), 'nostart'=>array(), 'completed'=>array(), 'nostart'=>array())
*       $course->completion_ratio = 0-1;
*       $course->completion_url = moodle_url;
*       $course->completion_str = 10%;
*       $course->my_completion = 5;
*       $course->total_completion = 10;
*       $course->not_completed_cmids = array();
*       $course->not_completed_cmids = array();
*       $course->first_not_completed_mod = cm_info;
*       $course->completion_info = wmios_completion_info;
*       $course->enrolment_manager = course_enrolment_manager;
*/
function get_overview_courses($courses,$bytype = true){
    global $DB,$OUTPUT,$CFG,$USER,$PAGE;
    $return = array();
    $return['inprogress'] = array();
    $return['completed'] = array();
    $return['nostart'] = array();

    if(!is_array($courses)){
        $courses = array($courses);
    }
    foreach ($courses as $key => $course) {
        $course_context = context_course::instance($course->id, MUST_EXIST);

        $course->modinfo_obj = get_fast_modinfo($course);

        $course->cover_url = get_course_cover_url($course,null);

        //course info
        $course->course_info = $course_info = $DB->get_record('course',  array('id' => $course->id), '*', MUST_EXIST);
        $course_summary = '';
        $tmp = (array)explode("\n", strip_tags($course_info->summary));
        foreach($tmp as $line)
        {
            $line = trim($line,"\r\n ");
            if(!$line)continue;
            $course_summary .= '<p>'.$line.'</p>';
        }
        unset($tmp);
        $course->course_summary = $course_summary;

        //category
        $course_category = '';
        $thiscat = $DB->get_record('course_categories', array('id' => $course_info->category));
        $tmp = explode('/',trim($thiscat->path,'/'));
        if(count($tmp)>1)
        {
            for($i=0;$i<count($tmp)-1;$i++)
            {
                $course_category .= $DB->get_record('course_categories', array('id' => $tmp[$i]))->name . '/';
            }
        }
        $course_category .= $thiscat->name;
        $course->course_category = $course_category;

        $managerroles = explode(',', $CFG->coursecontact);
        $coursemanagers = get_role_users($managerroles, $course_context, false);
        $canviewfullnames = has_capability('moodle/site:viewfullnames', $course_context);
        foreach($coursemanagers as &$manager)
        {
            $role = new stdClass();
            $role->id = $manager->roleid;
            $role->name = $manager->rolename;
            $role->shortname = $manager->roleshortname;
            $role->coursealias = $manager->rolecoursealias;
            $manager->roleshowname = role_get_name($role, $course_context, ROLENAME_ALIAS);
            $manager->fullname = fullname($manager,$canviewfullnames);
            unset($role);
        }
        $course->coursemanagers = $coursemanagers;



        $has_completion = false;
        //User has Started the course
        $user_started = false;
        $completion_ratio = 0;
        $completion_info = null;
        $not_completed_cmids= array();
        if(completion_info::is_enabled_for_site()){
            $completion_info = new wmios_completion_info($course_info);
            //$completion_info->is_tracked_user($USER->id);
            if($completion_info->is_enabled()){
                //Only for activity
                $completions = $completion_info->get_completions($USER->id,COMPLETION_CRITERIA_TYPE_ACTIVITY);
                $total_completion = 0;
                $my_completion = 0;
                foreach($completions as /** @var completion_criteria_completion*/ $completion){

                    $total_completion++;
                    if($completion->is_complete()){
                        $user_started = true;
                        $my_completion++;
                    }else{
                        if(!$user_started){
                            $ratio = $completion_info->get_ratio($course->modinfo_obj->cms[$completion->get_criteria()->moduleinstance],$USER->id)->completionstate;
                            if($ratio-0.0 > WMIOS_COMPLETION_RATIO_START){
                                $user_started = true;
                            }
                        }

                        $not_completed_cmids[] = $completion->get_criteria()->moduleinstance;
                    }
                }
                if($total_completion){
                    $completion_ratio = intval(($my_completion / $total_completion)*100).'%';
                    $has_completion = true;
                    $completion_str = get_string('completion-alt-auto-y','completion',$completion_ratio);
                    $completion_url = new moodle_url('/blocks/completionstatus/details.php', array('course' => $course->id));
                }
                //unset($completions,$total_completion,$my_completion,$completion);
            }
        }
        $course->has_completion = $has_completion;
        $course->completion_info = $completion_info;
        $course->user_started = $user_started;
        if($has_completion){
            $course->completion_ratio = $completion_ratio;
            $course->completion_url = $completion_url;
            $course->completion_str = $completion_str;
            $course->my_completion = $my_completion;
            $course->total_completion = $total_completion;
            $course->not_completed_cmids = $not_completed_cmids;

            $sections = $course->modinfo_obj->sections;
            $first_not_completed_id = 0;
            $firt_m_number = 0;
            foreach($sections as $sk=> $section){
                if(!$sk)continue;
                foreach($section as $m_number){
                    if(!$firt_m_number)$firt_m_number = $m_number;
                    if(in_array($m_number,$not_completed_cmids)){
                        $first_not_completed_id = $m_number;
                        break;
                    }
                }
                if($first_not_completed_id){
                    break;
                }
            }
            if(!$first_not_completed_id){
                $first_not_completed_id = $firt_m_number;
            }
            $course->first_not_completed_mod = $first_not_completed_id ? $course->modinfo_obj->cms[$first_not_completed_id] : null;
            unset($firt_m_number,$sections,$section,$first_not_completed_id);
        }else{
            $course->not_completed_cmids = array();
            $course->first_not_completed_mod = null;
        }






        //enrol info
        $course->enrolment_manager = new course_enrolment_manager($PAGE, $course, 0);


        if(!$bytype){
            $return[] = $course;
        }else{
            if(!$has_completion){
                $return['nostart'][] = $course;
            }else{
                if($completion_ratio == '100%'){
                    $return['completed'][] = $course;
                }elseif($completion_ratio === '0%'){
                    $return['nostart'][] = $course;
                }else{
                    $return['inprogress'][] = $course;
                }
            }

        }



    }
    return $return;

}

/**
*重写类，由于方法load_for_user在current courses 有错误，
* 在用户没有报名一门课程的时候调用global_navigation_for_ajax 会出错。
*
*/
class mycourses_navigation extends global_navigation_for_ajax{
    function load_for_user($user=null, $forceforcontext=false) {
        return null;
    }
}

/**
* 得到/local/应用所添加的导航菜单
* @return navigation_node
*
*/
function get_my_app_nav(){
    $nav = new navigation_node(array('text'=>get_string('my_application',LOCAL_WMIOS_PLUGIN_NAME)));

    $pluginman = plugin_manager::instance();
    /** @var plugininfo_local[]*/
    $local_plugins = $pluginman->get_plugins_of_type('local');
    foreach($local_plugins as $local_plugin)
    {
        //may be the plugin isn't installed.
        if(!$local_plugin->versiondb)
        {
            continue;
        }
        require_once($local_plugin->rootdir.'/lib.php');
        $function = "local_{$local_plugin->name}_extends_my_app_nav";
        if(function_exists($function)){
            $ns = $function();
            if(!is_array($ns)){
                $ns = array($ns);
            }
            foreach($ns as $n){
                if(isset($n)&& is_object($n) && is_a($n,'navigation_node')){
                    $nav->add_node($n);
                }
            }
        }
    }
    return $nav;
}
