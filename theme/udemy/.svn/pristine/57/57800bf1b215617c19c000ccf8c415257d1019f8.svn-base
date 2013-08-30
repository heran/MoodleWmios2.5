<?php
    include_once($CFG->dirroot . "/blocks/course_overview/renderer.php");

    /**
     * override block_course_overview
     */
    class theme_udemy_block_course_overview_renderer extends block_course_overview_renderer{

        /**
         * @override
         *
         * @inheritdoc
         *
         */
        public function course_overview($courses, $overviews) {
            global $DB,$OUTPUT,$CFG,$USER,$PAGE;
            
            $str = new stdClass();
            $str->start_the_course = get_string('start_the_course','theme_udemy');

            $html = '';
            $config = get_config('block_course_overview');

            $html .= '<div id="my-courses">';

            $allcourses = get_overview_courses($courses);

            $html .= '<div class="top tab-label-container"><h1 class="thin">'.get_string('mycourses').'</h1><div class="my-courses-nav"><ul class="gray-nav">';
            $types = array_keys($allcourses);
            $get_type = isset($_GET['type']) ? $_GET['type']: '';
            if(!in_array($get_type,$types)){
                $get_type = $types[0];
            }
            foreach($types as $k=> $type){
                $current = $get_type == $type ? 'current' : '';
                $url = clone $PAGE->url;
                $url->param('type',$type);
                $html .= '<li order="'.$k.'" class="'.$current.'" type="'.$type.'" onclick="" class="recommended-col"><label for="tab1">'.get_string('course-type-'.$type,'theme_udemy').'</label></li>';
            }
            $html .= ' </ul></div></div>';

            $html .= '<div id="list" class="tab-divs">';
            $view_this_course = get_string('view_this_course','theme_udemy');
            $next_lecture = get_string('next_lecture','theme_udemy');
            foreach($allcourses as $type=>$type_courses){
                $current = $get_type == $type ? ' current' : '';
                $html .= '<ul class="'.$current.'" type="'.$type.'">';
                foreach ($type_courses as $key => $course){
                    $course_context = context_course::instance($course->id, MUST_EXIST);
                    $cover_url = $course->cover_url;
                    $course_info = $course->course_info;
                    $course_summary = $course->course_summary;
                    $course_category = $course->course_category;
                    $coursemanagers = $course->coursemanagers;
                    $has_completion = $course->has_completion;
                    $completion_ratio = 0;
                    if($has_completion){
                        $completion_str = $course->completion_str;
                        $completion_url = $course->completion_url;
                        $completion_ratio = $course->completion_ratio;
                    }
                    $course_url = new moodle_url('/course/view.php', array('id' => $course->id));
                    if($course->first_not_completed_mod){
                        $next_mod_url = new moodle_url('/course/view.php', array('id' => $course->id,'cmid'=>$course->first_not_completed_mod->id));
                        $stringoptions = new stdClass;
                        $stringoptions->context = $course_context;
                        $next_mod_name = format_string($course->first_not_completed_mod->name, true,  $stringoptions);
                    }else{
                        $next_mod_url = $course_url;
                        $next_mod_name = get_string('none');
                    }

                    $html .= '<li>';
                    $html .= '<a class="thumb" href="'.$course_url->out().'"><img src="'.$cover_url.'"><span><span>'.$view_this_course.'</span></span></a>';
                    if($completion_ratio && $course->my_completion>0){
                        $html .= '<div class="course-progress graybox">
                        <div class="progress-bar"><span style="width: '.$completion_ratio.'"></span></div>
                        <a class="next" href="'.$next_mod_url->out().'">
                        <span class="next-lecture-text">'.$next_lecture.':</span>
                        <span class="next-lecture-title ellipsis">'.$next_mod_name.'</span>
                        </a>
                        </div>';
                    }else{
                        $html .= '<a href="'.$course_url->out().'" class="u-btn green whiteborder">'.$str->start_the_course.'</a>';
                    }

                    $html .= '<div class="titles">';
                    $attributes = array('title' => s($course->fullname),'class'=>"course-title");
                    if ($course->id > 0) {
                        $html .= html_writer::link($course_url, format_string($course->fullname, true, $course->id), $attributes).'<br />';
                    } else {
                        $html .= $this->output->heading(html_writer::link(
                                new moodle_url('/auth/mnet/jump.php', array('hostid' => $course->hostid, 'wantsurl' => '/course/view.php?id='.$course->remoteid)),
                                format_string($course->shortname, true), $attributes) . ' (' . format_string($course->hostname) . ')', 2, 'title');
                    }
                    foreach($coursemanagers as $manager)
                    {
                        $html .= '<a href="" class="ins-title">'.$manager->roleshowname.':'.$manager->fullname.'</a>';

                    }
                    $html .= '</div>';
                    $html .= '</li>';



                }
                $html .= '</ul>';
            }
            $html .= '</div>';


            return $html;
        }

        protected function get_overview_courses($courses){
            global $DB,$OUTPUT,$CFG,$USER;
            $return = array();
            $return['inprogress'] = array();
            $return['completed'] = array();
            $return['nostart'] = array();

            foreach ($courses as $key => $course) {
                $course_context = context_course::instance($course->id, MUST_EXIST);

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
                if(completion_info::is_enabled_for_site()){
                    $completion_info = new completion_info($course_info);
                    //$completion_info->is_tracked_user($USER->id);
                    if($completion_info->is_enabled()){
                        $completions = $completion_info->get_completions($USER->id);
                        $total_completion = 0;
                        $my_completion = 0;
                        foreach($completions as $completion){
                            $total_completion++;
                            if($completion->is_complete()){
                                $my_completion++;
                            }
                        }
                        if($total_completion){
                            $completion_ratio = intval(($my_completion / $total_completion)*100).'%';
                            $has_completion = true;
                            $completion_str = get_string('completion-alt-auto-y','completion',$completion_ratio);
                            $completion_url = new moodle_url('/blocks/completionstatus/details.php', array('course' => $course->id));
                        }
                        unset($completions,$total_completion,$my_completion,$completion);
                    }
                    unset($completion_info);
                }
                $course->has_completion = $has_completion;
                if($has_completion){
                    $course->completion_ratio = $completion_ratio;
                    $course->completion_url = $completion_url;
                    $course->completion_str = $completion_str;
                }



                if(!$has_completion){
                    $return['nostart'][] = $course;
                }else{
                    if($completion_ratio == '100%'){
                        $return['completed'][] = $course;
                    }elseif($completion_ratio == '0%'){
                        $return['nostart'][] = $course;
                    }else{
                        $return['inprogress'][] = $course;
                    }
                }

            }
            return $return;

        }

        public function welcome_area($msgcount) {
            return '';
        }


    }
