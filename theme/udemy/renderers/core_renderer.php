<?php
/**
* @package theme
* @subpackage outdo
*/
class theme_udemy_core_renderer extends core_renderer {

    /**
    * smarty class
    *
    * @var Smarty
    */
    protected $_smarty = null;

    /**
    * @return Smarty
    *
    */
    public function get_smarty() {
        if($this->_smarty === null){
            $this->_smarty = get_smarty('core',null,$this->page);
        }
        return $this->_smarty;
    }


    /**
    * Display Course'Info in the page header.
    * @return String
    */
    public function course_info_header(){
        global $COURSE,$SITE,$CFG;

        if($COURSE == $SITE){
            return '';
        }
        if($this->page->context->contextlevel != CONTEXT_COURSE)
        {
            return '';
        }

        $cover_url = get_course_cover_url($COURSE);

        $course_category = render_course_category_list($COURSE);

        $course_fullname = format_string($COURSE->fullname, true, $COURSE->id);
        $tmp = get_overview_courses($COURSE,false);
        $course_overview_info = $tmp[0];

        $manager_str= '';
        $managers = array();
        if(isset($course_overview_info->coursemanagers))foreach($course_overview_info->coursemanagers as $manager){
            $managers[] = $manager->fullname;
        }
        $manager_str = implode('  ',$managers);

        $course_user_url = $course_view_url = new moodle_url('/course/view.php?id='.$COURSE->id);
        $blog_url = null;//get_string('blogscourse','blog')
        if(has_capability('moodle/course:viewparticipants', context_course::instance($COURSE->id))){
            $course_user_url = new moodle_url('/user/index.php?id='.$COURSE->id);

            //course's blog
            $currentgroup = groups_get_course_group($COURSE, true);
            if ($COURSE->id && !$currentgroup) {
                $filterselect = $COURSE->id;
            } else {
                $filterselect = $currentgroup;
            }
            $filterselect = clean_param($filterselect, PARAM_INT);
            if ( ($CFG->bloglevel == BLOG_GLOBAL_LEVEL or $CFG->bloglevel == BLOG_SITE_LEVEL )
                and has_capability('moodle/blog:view', context_system::instance())) {

                $blog_url = new moodle_url('/blog/index.php', array('courseid' => $filterselect));
            }
        }


        $smarty = $this->get_smarty();
        $smarty->assign('cover_url',$cover_url);
        $smarty->assign('course_fullname',$course_fullname);
        $smarty->assign('course_overview_info',$course_overview_info);
        $smarty->assign('manager_str',$manager_str);
        $smarty->assign('course_view_url',$course_view_url);
        $smarty->assign('course_user_url',$course_user_url);
        $smarty->assign('blog_url',$blog_url);

        return $smarty->fetch('course_info_header.tpl');
    }

    /**
    * @inheritdoc
    */
    public function block(block_contents $bc, $region) {
        $bc = clone($bc); // Avoid messing up the object passed in.
        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        }
        $skiptitle = strip_tags($bc->title);
        if ($bc->blockinstanceid && !empty($skiptitle)) {
            $bc->attributes['aria-labelledby'] = 'instance-'.$bc->blockinstanceid.'-header';
        } else if (!empty($bc->arialabel)) {
            $bc->attributes['aria-label'] = $bc->arialabel;
        }
        if ($bc->collapsible == block_contents::HIDDEN) {
            $bc->add_class('hidden');
        }
        if (!empty($bc->controls)) {
            $bc->add_class('block_with_controls');
        }
        $bc->add_class('dashboard-panel');

        $output = '';

        $output .= html_writer::start_tag('div', $bc->attributes);

        $output .= $this->block_header($bc);
        $output .= $this->block_content($bc);

        $output .= html_writer::end_tag('div');

        $output .= $this->block_annotation($bc);

        $this->init_block_hider_js($bc);
        return $output;
    }

    /**
    * @inheritdoc
    *
    * @param block_contents $bc
    * @return String
    */
    protected function block_header(block_contents $bc) {

        $title = '';
        if ($bc->title) {
            $attributes = array();
            if ($bc->blockinstanceid) {
                $attributes['id'] = 'instance-'.$bc->blockinstanceid.'-header';
            }
            $title = html_writer::tag('h2', $bc->title, $attributes);
        }

        $controlshtml = $this->block_controls($bc->controls);

        $output = '';
        if ($title || $controlshtml) {
            //$tmp = '<div class="title"><h2>1</h2></div>';
            $tmp = html_writer::tag('div',html_writer::tag('div', '', array('class'=>'block_action')).$title . $controlshtml,array('class' => 'title'));
            $output .= html_writer::tag('div',$tmp,array('class' => 'header block-header'));
        }
        return $output;
    }

    /**
    * @inheritdoc
    *
    * @param mixed $controls
    * @return String
    */
    public function block_controls($controls) {
        if (empty($controls)) {
            return '';
        }
        $controlshtml = array();
        foreach ($controls as $control) {
            $controlshtml[] = html_writer::tag('a',
                html_writer::empty_tag('img',  array('src' => $this->pix_url($control['icon'])->out(false), 'alt' => $control['caption'])),
                array('class' => 'icon ' . $control['class'],'title' => $control['caption'], 'href' => $control['url']));
        }
        return html_writer::tag('span', implode('', $controlshtml), array('class' => 'commands'));
    }

    /**
    * put your comment there...
    *
    * @param navigation_node $node
    * @return String
    */
    protected function nav_node(navigation_node $node, $gointo = true){

        if(!$node->has_children()){
            return '';
        }

        $html = '';
        $html .= '<ul>';
        $items = $node->children;
        $divide = '';
        foreach($items as /** @var navigation_node*/$item){
            $text = $item->get_content();
            if($text == '-'){
                $divide = 'top-bordered';
                continue;
            }

            $action = '';
            if(is_a($item->action,'action_link')){
                $action = $this->render($item->action);
            }else{

                if(is_a($item->action,'moodle_url')){
                    $url = $item->action->out(false);
                }else{
                    $url = '#';
                }
                $action = '<a class="ellipsis" href="'.$url.'" node_type="'.$item->nodetype.'">'.$text.'</a>';
            }

            $html .= '<li status="close" class="'.$divide.'">'.$action;
            if($divide){
                $divide = '';
            }
            if($gointo){
                $html .= $this->nav_node($item,$gointo);
            }
            $html .= '</li>';

        }

        $html .= '</ul>';
        return $html;
    }

    /**
    * search form
    *
    * @param String $searchvalue
    * @return String
    */
    public function header_settings_search_form($searchvalue){
        global $CFG;
        $formtarget = new moodle_url("$CFG->wwwroot/$CFG->admin/search.php");
        $content = html_writer::start_tag('form', array('class'=>'adminsearchform', 'method'=>'get', 'action'=>$formtarget, 'role' => 'search'));
        $content .= html_writer::empty_tag('input', array('id'=>'adminsearchquery', 'class'=>'ui-autocomplete-input', 'type'=>'text', 'name'=>'query', 'value'=>s($searchvalue)));
        $content .= html_writer::empty_tag('input', array('type'=>'submit', 'value'=>s(get_string('search'))));
        $content .= html_writer::end_tag('form');
        return $content;
    }

    /**
    *
    * //For the top menu
    * User's personal navigation.
    * Such as profile forum and so on.
    *
    * @return String
    *
    */
    public function header_profile(){
        global $CFG;
        $PAGE = $this->page;

        $navigation = clone $PAGE->navigation;
        $divider = 0;

        $myprofile = $navigation->get('myprofile');

        $tmp = new navigation_node(array('text'=>'nav'));

        //We Need igore some function
        //They are igored just for a time.
        //Will come back
        //By Heran At 2013-05-09 11:46:47
        $ingores = array(
            get_string('repositories', 'repository'),
        );

        foreach($myprofile->children as $child){
            /**
            * @var navigation_node
            */
            $child;
            if(in_array($child->text,$ingores)){
                continue;
            }
            $tmp->children->add(clone $child);
        }

        // Calendar
        //Come back soon
        //$calendarurl = new moodle_url('/calendar/view.php', array('view' => 'month'));
        //$tmp->add(get_string('calendar', 'calendar'), $calendarurl);

        $tmp->children->add(new navigation_node(array('text'=>'-','key'=>'divider'.$divider++)));

        // blogs
        /*if (!empty($CFG->enableblogs)
            and ($CFG->bloglevel == BLOG_GLOBAL_LEVEL or ($CFG->bloglevel == BLOG_SITE_LEVEL))
            and has_capability('moodle/blog:view', context_system::instance())) {
            $blogsurls = new moodle_url('/blog/index.php', array('courseid' => 0));
            $tmp->add(get_string('blogssite','blog'), $blogsurls->out(),navigation_node::TYPE_CUSTOM,null,'blogssite');
        }*/

        //Site News
        //Site Page's Forum
        foreach($navigation->children as $n_child){
            if($n_child->text === get_string('sitepages')){
                foreach($n_child->children as $site_node){
                    if($site_node->action && in_array($site_node->action->get_path(false),
                        array(
                            '/blog/index.php',
                            '/mod/forum/view.php',
                            '/mod/document/view.php',
                            '/mod/dekiwiki/view.php',
                            )
                        )){
                        $tmp->children->add($site_node);
                    }
                }
                break;
            }
        }

        $tmp->children->add(new navigation_node(array('text'=>'-','key'=>'divider'.$divider++)));

        //We hide extenal message settings
        //Why? We need time for styles
        //By Heran At 2013-05-09 14:16:06
        $usercurrentsettings = clone $PAGE->settingsnav->get('usercurrentsettings');
        $user_setting_node = new navigation_node( array(
            'text'=>$usercurrentsettings->text,
            'type'=>navigation_node::NODETYPE_BRANCH,
            'key'=>'usercurrentsettings'));

        $str_m = get_string_manager();
        $message_arr = array();
        if($str_m->string_exists('editmymessage', 'message')){
            $message_arr[] = get_string('editmymessage', 'message');
        }
        if($str_m->string_exists('messaging', 'message')){
            $message_arr[] = get_string('messaging', 'message');
        }
        foreach($usercurrentsettings->children as $child){

            if(!in_array( $child->text,$message_arr)){
                $user_setting_node->children->add(clone $child) ;
            }
        }



        $tmp->children->add( $user_setting_node );
        $tmp->children->add(new navigation_node(array('text'=>'-','key'=>'divider'.$divider++)));
        $tmp->children->add(new navigation_node(array('text'=>get_string('logout'),'key'=>'logout','action'=>new moodle_url('/login/logout.php?sesskey='.sesskey()))));
        return $this->nav_node($tmp);

    }

    /**
    * @return String
    *
    */
    public function header_settingsnav(){
        $PAGE = $this->page;

        /** @var global_navigation*/
        $settingsnav = clone $PAGE->settingsnav;

        /** @var global_navigation*/
        $navigation = clone $PAGE->navigation;

        $tmp = new navigation_node(array('text'=>'nav'));

        foreach($navigation->children as $child){
            if(!$tmp->get($child->key)) $tmp->children->add($child);
        }

        $tmp->children->add(new navigation_node(array('text'=>'-')));

        foreach($settingsnav->children as /** @var navigation_node*/$child){
            $tmp->children->add($child);
        }

        $html = $this->nav_node($tmp);
        if(has_capability('moodle/site:config',context_system::instance())){
            //Only Admin Can Need So many functions
            $html = substr($html,0,strlen($html)-5). '<li>'.$this->header_settings_search_form(optional_param('query', '', PARAM_RAW)).'</li>'.'</ul>';
        }

        /* foreach($navigation->children as $child){
        $tmp->children->add($child);
        }

        foreach($navigation->children as $child){
        if($child->key == 'myprofile' || $child->key == 'mycourses'|| $child->key == 'currentcourse' ){

        }else{
        $tmp->children->add($child);
        }
        }

        $tmp->children->add(new navigation_node(array('text'=>'-')));
        foreach($settingsnav->children as $child){
        if($child->key == 'usercurrentsettings'|| $child->key == '1' || $child->key == 'courseadmin'){

        }else{
        $tmp->children->add($child);
        }
        }
        $html = $this->nav_node($tmp);
        $html = substr($html,0,strlen($html)-5). '<li>'.$this->header_settings_search_form(optional_param('query', '', PARAM_RAW)).'</li>'.'</ul>';*/

        return $html;
    }

    /**
    * @return String
    *
    */
    public function get_current_course_nav(){
        global $PAGE,$COURSE,$CFG;

        /** @var global_navigation*/
        $settingsnav = clone $PAGE->settingsnav;

        /** @var global_navigation*/
        $navigation = clone $PAGE->navigation;

        //We Need igore some function
        //They are igored just for a time.
        //Will come back
        //By Heran At 2013-05-09 11:46:47
        $nav_ingores = array(
            'participants',//There are course students list, course blog, course notes(/notes/)
        );

        $tmp = new navigation_node(array('text'=>'nav'));
        $course_node = null;
        foreach($navigation->children as /** @var navigation_node*/$child){
            if( $child->key == 'currentcourse'){
                $course_node = $child->get($COURSE->id);

            }
        }
        if($course_node)foreach($course_node->children as $child){
            if(in_array($child->key,$nav_ingores)){
                continue;
            }
            $tmp->add($child->get_content(),$child->action,navigation_node::TYPE_CUSTOM,'',$child->key);
        }

        $tmp->children->add(new navigation_node(array('text'=>'-','key'=>'divider-setting')));
        $has_setting = false;
        foreach($settingsnav->children as /** @var navigation_node*/$child){
            //We hide the student's grade book for a time
            //I will come back soon
            //At 2013-05-09 13:58:50
            if(($child->key == '1' || $child->key == 'courseadmin')
                && has_capability('moodle/course:update',context_course::instance($COURSE->id)) ){

                $tmp->children->add(clone $child);
                $has_setting = true;
            }
        }
        if(!$has_setting){
            $tmp->children->remove('divider-setting');
        }

        return $tmp->has_children() ? $tmp : null;
    }

    /**
    * When user in one mod or activity
    * He needs the navigation
    *
    * @param context_module $context
    * @return navigation_node|false
    */
    public function get_current_module_nav(context_module $context){

        $old_node = $this->page->navigation->find($context->instanceid, navigation_node::TYPE_ACTIVITY);
        $activity_node = null;
        if($old_node ){
            $activity_node = clone($old_node);
            $activity_node->add('-');
            $activity_node->add($activity_node->get_content(),$activity_node->action);
        }
        return $activity_node;
    }


    /**
    * Some page help buttons
    *
    */
    public function page_bottom_help_button(){
        return $this->get_smarty()->fetch('page_bottom_help_button.tpl');
    }



    /**
    * @inheritdoc
    *
    * @param paging_bar $pagingbar
    * @return String
    */
    protected function render_paging_bar(paging_bar $pagingbar) {
        $output = '';
        $pagingbar = clone($pagingbar);
        $pagingbar->prepare($this, $this->page, $this->target);

        if ($pagingbar->totalcount > $pagingbar->perpage) {

            if (!empty($pagingbar->previouslink)) {
                $output .= '<li>' . str_ireplace(get_string('previous'),'&lt;', $pagingbar->previouslink) . '</li>';
            }

            if (!empty($pagingbar->firstlink)) {
                $output .= '<li>' . $pagingbar->firstlink . '...</li>';
            }

            foreach ($pagingbar->pagelinks as $link) {
                if(stristr($link,'<a')){
                    $output .= "<li>$link</li>";
                }else{
                    $output .= "<li><a class=\"current\">$link</span></a>";
                }

            }

            if (!empty($pagingbar->lastlink)) {
                $output .= '<li>...' . $pagingbar->lastlink . '</li>';
            }

            if (!empty($pagingbar->nextlink)) {
                $output .= '<li>' . str_ireplace(get_string('next'),'&gt;', $pagingbar->nextlink) . '</li>';
            }
        }

        return html_writer::tag('ul', $output, array('class' => 'paging'));
    }
}