<?php
include_once($CFG->dirroot . '/course/format/topics/renderer.php');
include_once($CFG->dirroot.'/local/wmios/vendor/simplehtmldom_1_5/simple_html_dom.php');

class theme_udemy_format_topics_renderer extends format_topics_renderer{

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
            $this->_smarty = get_smarty('format_topics',null,$this->page);
        }
        return $this->_smarty;
    }

    /**
    * @inheritdoc
    */
    public function print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused) {
        global $PAGE;

        $context = context_course::instance($course->id);

        $user_in_edit = $PAGE->user_is_editing() && has_capability('moodle/course:update', $context);
        if ($user_in_edit || $course->coursedisplay != COURSE_DISPLAY_MULTIPAGE) {
            parent::print_multiple_section_page($course, $sections, $mods, $modnames, $modnamesused);
            return;
        }



        $course = course_get_format($course)->get_course();
        $tmp  = get_overview_courses($course,false);
        $course = $tmp[0];
        unset($tmp);
        $modinfo = $course->modinfo_obj;

        $mod_section_info = $modinfo->get_section_info_all();
        $sections = array();
        foreach ($mod_section_info as $section) {
            if ($section->uservisible && $section->section <= $course->numsections) {
                $sections[] = $section;
            }
        }


        $completioninfo = new wmios_completion_info($course);
        $this->print_progress($course);
        $this->print_sections($course, $sections,$modinfo,$course->completion_info);
        $this->print_modal_box($course);
        return;
    }

    protected function print_modal_box($course){
        global $PAGE,$COURSE;
        /** @var course_modinfo*/
        $modinfo = $course->modinfo_obj;
        $instances = $modinfo->get_instances();
        if($PAGE->blocks->is_block_present('notes')){
            $has_notes = true;
        }else{
            $has_notes = false;
        }
        if($PAGE->blocks->is_block_present('qanda')){
            $has_qanda = true;
        }else{
            $has_qanda = false;
        }

        $str = new stdClass();
        $str->previous_lecture = get_string('prev_lecture','theme_udemy');
        $str->next_lecture = get_string('next_lecture','theme_udemy');
        $str->back_to_course = get_string('back_to_course','theme_udemy');
        $str->auto_play = get_string('auto_play','theme_udemy');
        $str->resume_mod = get_string('resume_mod','theme_udemy');
        $str->mod_intro = get_string('mod_intro','theme_udemy');
        $str->qanda = get_string('qanda','block_qanda');
        $str->notes = get_string('notes','block_notes');
        $str->type_your_note = get_string('type_your_note','theme_udemy');
        $str->down_load_notes = get_string('down_load_notes','theme_udemy');
        $str->back = get_string('back');
        $str->follow = get_string('follow','theme_udemy');
        $str->type_your_question = get_string('type_your_question','theme_udemy');
        $urls = new stdClass();
        $urls->ask_question = new moodle_url('/local/wmios/qanda/updateq.php',array('wt'=>'json'));
        $urls->get_question_list = new moodle_url('/local/wmios/qanda/listq.php',array('wt'=>'json'));
        $urls->add_question_answer = new moodle_url('/local/wmios/qanda/updatea.php',array('wt'=>'json'));
        $urls->get_answer_list = new moodle_url('/local/wmios/qanda/lista.php',array('wt'=>'json'));
        $smarty = $this->get_smarty();
        $smarty->assign('has_notes',$has_notes);
        $smarty->assign('has_qanda',$has_qanda);
        $smarty->assign('str',$str);
        $smarty->assign('urls',$urls);
        return $smarty->display('modal_box.tpl');
    }

    protected function print_progress($course){

        if(!$course->has_completion){
            return;
        }

        $str = new stdClass();
        $str->you_completed_course = get_string('you_completed_course','theme_udemy',array('my'=>$course->my_completion,'total'=>$course->total_completion));

        if($course->first_not_completed_mod){
            /** @var cm_info*/
            $mod = $course->first_not_completed_mod;
            $cmid = $mod->id;
            $href = $mod->get_url();
            $coursecontext = context_course::instance($course->id);
            $stringoptions = new stdClass;
            $stringoptions->context = $coursecontext;
            $instancename = format_string($mod->name, true,  $stringoptions);
            if($course->user_started){
                $str->continue_course = get_string('continue_with_course_mod','theme_udemy',$instancename);
            }else{
                $str->continue_course = get_string('start_with_course_mod','theme_udemy',$instancename);
            }
        }else{
            $cmid = 0;
            $href = '#';
            $str->continue_course = get_string('course_has_no_mod','theme_udemy');
        }
        $smarty = $this->get_smarty();
        $smarty->assign('course',$course);
        $smarty->assign('str',$str);
        $smarty->assign('cmid',$cmid);
        $smarty->assign('href',$href);
        return $smarty->display('progress.tpl');
    }

    protected function print_sections($course, $sections,$modinfo,$completioninfo){
        $str = new stdClass();
        $str->curriculum = get_string('curriculum','theme_udemy');
        echo <<<EOD
<div class="dashboard-panel" id="curriculum">
<h4 class="header">{$str->curriculum}</h4>
<div class="list">
    <div class="wrapper">
EOD;
        $ct = count($sections);
        $modorder = 0;
        for ($i=0;$i<count($sections); $i++) {
            $section = $sections[$i];
            $section->first = !$i ?: false;
            $section->last = $i==$ct ?: false;
            $this->print_section($course, $section,$modinfo,$completioninfo,$modorder);
        }

        echo <<<EOD
    </div>
</div>
</div>

EOD;
        return;
    }

    protected function print_section($course, $section,$modinfo,$completioninfo,&$modorder){
        $str = new stdClass();
        $str->section = get_string('section','theme_udemy');
        if($section->section!=0){
            echo <<<EOD
<h5 sectionnumber="{$section->section}">
<span class="chapter">SECTION</span>
<span class="no">{$section->section}</span>
<span class="title">{$this->section_title($section,$course)}<span></span></span>
</h5>
EOD;
        }
        if(!isset($section->notshowmods) || !$section->notshowmods){

            if (!empty($modinfo->sections[$section->section])) {

                echo '<ul>';
                foreach ($modinfo->sections[$section->section] as $modnumber) {
                    $mod = $modinfo->cms[$modnumber];
                    if (!$mod->uservisible) {
                        continue;
                    }
                    if($section->section>=0){
                        $modorder++;
                    }
                    $this->print_mod($mod,$modorder,$course,$completioninfo);
                }
                echo '</ul>';
            }
        }
        return;
        global $CFG;


        echo '<li id="section-'.$section->section.'" class="course-section-box'
        .($section->first ? ' first': '').($section->last ? ' last': '').'">';
        echo '  <div class="section-line"></div>';
        echo '  <div class="course-section-header-box">';

        if($section->section!=0){
            echo '  <h5 class="course-section-title-box">';
            echo '      <span class="section-number">'.$section->section.'</span>';
            echo '      <span class="section-title">'.$this->section_title($section,$course).'</span>';
            echo '  </h5>';
        }

        //if(!$section->notshowmods){
        $summary = $this->format_summary_text($section);
        $summarydom = @str_get_html($summary);
        $summaryimg = $summarydom ? $summarydom ->find('img') : null;
        echo '      <div class="course-section-summary'.($summaryimg ? ' has-img': '').'">';
        if($summaryimg){
            echo '       <div class="course-section-summary-img"><img src="'.$summaryimg[0]->src.'" /></div>';
        }
        echo '          <div class="course-section-summary-text">',nl2br(trim(strip_tags($summary)," \n\r")),'</div>';
        echo '      </div>';
        unset($summary,$summarydom,$summaryimg);
        //}

        echo '  </div>';


        //if(!isset($section->notshowmods) || !$section->notshowmods){
        $modinfo = get_fast_modinfo($course);
        $completioninfo = new completion_info($course);
        if (!empty($modinfo->sections[$section->section])) {

            echo '<ul class="course-section-mods-box">';
            foreach ($modinfo->sections[$section->section] as $modnumber) {
                $mod = $modinfo->cms[$modnumber];
                if (!$mod->uservisible) {
                    continue;
                }
                $this->print_mod($mod,$modnumber,$course,$completioninfo);
            }
            echo '</ul>';
        }
        //}

        echo '</li>';
    }

    /**
    * put your comment there...
    *
    * @param cm_info $mod
    * @param mixed $modnumber
    * @param mixed $course
    * @param wmios_completion_info $completioninfo
    */
    protected function print_mod($mod,$modorder,$course,$completioninfo){
        global $CFG,$PAGE,$USER;

        $renderfile = "{$CFG->dirroot}/mod/{$mod->modname}/renderer.php";
        if(is_readable($renderfile) ){
            if( $renderer = $PAGE->get_renderer('mod_'.$mod->modname)){
                if(method_exists($renderer,'print_mod_line')){
                    $renderer->print_mod_line(
                        $mod,$modorder,$course,$completioninfo);
                    return ;
                }
            }

        }



        $instancename = $mod->get_formatted_name();

        $ratio = $completioninfo->get_ratio($mod,$USER->id)->completionstate*100.0;
        $ratio = substr($ratio,0,5);

        if($ratio<= WMIOS_COMPLETION_RATIO_NOT_START){
            $view_str = get_string('start_lecture','theme_udemy');
        }else{
            $view_str = get_string('revisit_lecture','theme_udemy');
        }
        /*$completion = $completioninfo->is_enabled($mod);
        if($completion == COMPLETION_TRACKING_MANUAL){
        $completiondata = $completioninfo->get_data($mod,true);
        switch($completiondata->completionstate) {
        case COMPLETION_INCOMPLETE:
        $completion_class = 'manual-n'; break;
        case COMPLETION_COMPLETE:
        $completion_class = 'manual-y'; break;
        }
        $imgtitle = get_string('set-completion-' . $completion_class, 'theme_outdo');
        $newstate = $completiondata->completionstate==COMPLETION_COMPLETE
        ? COMPLETION_INCOMPLETE
        : COMPLETION_COMPLETE;
        $manual_complete = "<div class='togglecompletion' style='display:inline'>";
        $manual_complete .= "  <form class='' method='post' action='".$CFG->wwwroot."/course/togglecompletion.php'>";
        $manual_complete .= "      <input type='hidden' name='id' value='{$mod->id}' />";
        $manual_complete .= "      <input type='hidden' name='modulename' value='".s($mod->name)."' />";
        $manual_complete .= "      <input type='hidden' name='sesskey' value='".sesskey()."' />";
        $manual_complete .= "      <input type='hidden' name='completionstate' value='{$newstate}' />";
        $manual_complete .= "      <input type='submit' value='".$imgtitle."'  title='{$imgtitle}' />";
        $manual_complete .= "  </form>";
        $manual_complete .= "</div>";
        }else{
        $manual_complete = '';
        }*/

        $url = $mod->get_url();


        $ci = new condition_info($mod);
        $info = '';
        $enabled = true;
        if($ci){
            $enabled = $ci->is_available($info);
        }
        if($enabled){
            $available = '1';
        }else{
            $available = '0';
        }

        //Some mods support modal view
        $modalclass = '';
        if(in_array($mod->modname,array('multimedia','page','choice','book','assign','resource'))){
            $modalclass = 'modalview';
        }


        echo <<<EOD
<li modname="{$mod->modname}" modorder="{$modorder}" courseid="{$course->id}" cmid="{$mod->id}" id="{$mod->modname}-{$mod->id}-line" class="{$modalclass}" available="{$available}" availableinfo="{$info}">
<span style="display:none;">{$info}</span>
<div class="circle"><span style="width:{$ratio}%"></span></div>
<h6><a href="{$url}#" target="_blank">{$instancename}</a></h6>
<span class="lecture-type {$mod->modname}"><img src="{$mod->get_icon_url()}" /></span>
<time></time>
<a href="{$url}#" target="_blank" class="u-btn green">{$view_str}</a>
</li>
EOD;

        return;
    }





    /**
    * @inheritdoc
    */
    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE;

        $context = context_course::instance($course->id);

        $user_in_edit = $PAGE->user_is_editing() && has_capability('moodle/course:update', $context);
        if ($user_in_edit || $course->coursedisplay != COURSE_DISPLAY_MULTIPAGE) {
            parent::print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection);
            return;
        }

        if($displaysection>$course->numsections){
            if (!$course->hiddensections) {
                echo $this->start_section_list();
                echo $this->section_hidden($displaysection);
                echo $this->end_section_list();
            }
            return;
        }


        $course = course_get_format($course)->get_course();
        $tmp  = get_overview_courses($course,false);
        $course = $tmp[0];
        unset($tmp);
        $modinfo = $course->modinfo_obj;
        $mod_section_info = $modinfo->get_section_info_all();


        $presection = $thissection = $nextsection = null;
        $presection = $mod_section_info[1];
        foreach ($mod_section_info as $section) {
            if($section->section == 0 || $section->section > $course->numsections)continue;
            if($thissection){
                $nextsection = $section;
                break;
            }
            if($section->section == $displaysection){
                $thissection = $section;
            }else{
                $presection = $section;
            }
        }
        if($presection && $thissection && $thissection->section == $presection->section){
            $presection = null;
        }
        if($nextsection && $thissection && $thissection->section == $nextsection->section){
            $nextsection = null;
        }

        if (!$thissection || !$thissection->uservisible) {
            if (!$course->hiddensections) {
                echo $this->start_section_list();
                echo $this->section_hidden($displaysection);
                echo $this->end_section_list();
            }
            // Can't view this section.
            return;
        }

        $sections = array();
        if($mod_section_info[0]->section == 0){
            $sections[] = $mod_section_info[0];
        }
        if($presection){
            $presection->notshowmods = true;
            $sections[] = $presection;
        }
        $sections[] = $thissection;
        if($nextsection){
            $nextsection->notshowmods = true;
            $sections[] = $nextsection;
        }


        $completioninfo = new wmios_completion_info($course);
        $this->print_progress($course);
        $this->print_sections($course, $sections,$modinfo,$course->completion_info);
        $this->print_modal_box($course);
        return;

    }
}