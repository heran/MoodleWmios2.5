<?php
require_once($CFG->dirroot .'/mod/multimedia/renderer.php');
class theme_udemy_mod_multimedia_renderer extends mod_multimedia_renderer{

    /**
    * put your comment there...
    * 
    * @param cm_info $mod
    * @param mixed $modorder
    * @param mixed $course
    * @param mixed $completioninfo
    */
    public function print_mod_line($mod,$modorder,$course,$completioninfo){
        
        global $USER,$DB;
        $multimedia = $DB->get_record('multimedia', array('id'=>$mod->instance), '*', MUST_EXIST);
        $time = $this->vtime($multimedia->completion_max);

        $instancename = $mod->get_formatted_name();

        $ratio = $completioninfo->get_ratio($mod,$USER->id)->completionstate*100.0;
        $ratio = substr($ratio,0,5);

        if($ratio<= WMIOS_COMPLETION_RATIO_NOT_START){
            $view_str = get_string('start_lecture','theme_udemy');
        }else{
            $view_str = get_string('revisit_lecture','theme_udemy');
        }

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


        echo <<<EOD
        <li modname="{$mod->modname}" modorder="{$modorder}" courseid="{$course->id}" cmid="{$mod->id}" id="{$mod->modname}-{$mod->id}-line" class="modalview" available="{$available}" availableinfo="{$info}">
            <span style="display:none;">{$info}</span>
            <div class="circle"><span style="width:{$ratio}%"></span></div>
            <h6><a href="{$url}#" target="_blank">{$instancename}</a></h6>
            <span class="lecture-type {$mod->modname}"></span>
            <time>{$time}</time>
            <a href="{$url}#" target="_blank" class="u-btn green">{$view_str}</a>
        </li>
EOD;
    }

    protected function vtime($time) {
        $output = '';
        if($time>=60){
            $output .= sprintf('%02d',floor($time/60));
        }else{
            $output .= '00';
        }
        $output .= ':';
        return  $output . sprintf('%02d',$time %= 60);
    }

   

}
