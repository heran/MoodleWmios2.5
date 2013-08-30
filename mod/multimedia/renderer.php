<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
* Defines the renderer for the quiz module.
*
* @package    mod
* @subpackage multimedia
* @copyright  2013 Wmios @link(http://wmios.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/


defined('MOODLE_INTERNAL') || die();


/**
* The renderer for the multimedia module.
*
* @copyright  2013 Wmios @link(http://wmios.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
class mod_multimedia_renderer extends plugin_renderer_base {
    
    /**
    * put your comment there...
    * 
    * @param string $content
    * @param stdClass $multimedia
    * @param cm_info $cm
    */
    public function print_page($content,$multimedia,$cm,$inpopup=false){
        global $OUTPUT, $PAGE, $COURSE, $USER;
        $course = $COURSE;

        $html = '';

        $options = empty($multimedia->displayoptions) ? array() : unserialize($multimedia->displayoptions);

        if ($inpopup and $multimedia->display == RESOURCELIB_DISPLAY_POPUP) {
            $PAGE->set_pagelayout('popup');
            $PAGE->set_title($course->shortname.': '.$multimedia->name);
            if (!empty($options['printheading'])) {
                $PAGE->set_heading($multimedia->name);
            } else {
                $PAGE->set_heading('');
            }
            $html .= $OUTPUT->header();

        } else {
            $PAGE->set_title($course->shortname.': '.$multimedia->name);
            $PAGE->set_heading($course->fullname);
            $PAGE->set_activity_record($multimedia);
            $html .=  $OUTPUT->header();

            if (!empty($options['printheading'])) {
                $html .=  $OUTPUT->heading(format_string($multimedia->name), 2, 'main', 'pageheading');
            }
        }

        if (!empty($options['printintro'])) {
            if (trim(strip_tags($multimedia->intro))) {
                $html .= $OUTPUT->box_start('mod_introbox', 'pageintro');
                $html .= format_module_intro('multimedia', $multimedia, $cm->id);
                $html .= $OUTPUT->box_end();
            }
        }


        $html .= $OUTPUT->box($content, "generalbox center clearfix",'cmid'.$cm->id);

        $strlastmodified = get_string("lastmodified");
        $completion_now = multimedia_get_completion_unit_now($cm,$USER->id);
        $html .= "<div class=\"modified\">$strlastmodified: ".userdate($multimedia->timemodified)."</div>";
        $html .= <<<EOD
<script type="text/javascript">
    function jsCallbackReady () {
        multimediaCompletion.ratio.install({$cm->id},{$completion_now});
    }
</script>
EOD;
        $html.= $OUTPUT->footer();
        echo $html;

    }
    
    
    /**
    * put your comment there...
    * 
    * @param string $content
    * @param stdClass $multimedia
    * @param cm_info $cm
    */
    public function render_page_xml($content,$multimedia,$cm){
        global $CFG,$USER;
        $js_url = new moodle_url('/mod/multimedia/js/completion_ratio.js');
        $completion_now = multimedia_get_completion_unit_now($cm,$USER->id)+0;
        return <<<EOD
        <div>
            <div id="cmid{$cm->id}" completion_now="{$completion_now}">
            {$content}
            </div>            
            <span class="mod-intro none" cmid="{$cm->id}">{$multimedia->intro}</span>
            <script type="text/javascript">
                function jsCallbackReady () {
                    multimedia_mod_view.install({$cm->id},{$completion_now});
                }
            </script>
        </div>
EOD;
    }
    
}
