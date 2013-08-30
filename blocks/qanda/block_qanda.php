<?PHP

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


require_once($CFG->dirroot.'/local/wmios/qanda/lib.php');

/*
* 
* @package    blocks
* @subpackage qanda
* @copyright  2013 Wmios (http://wmios.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*
* 
*/

/**
* The block for question and answer
*/
class block_qanda extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_qanda');
    }

    public function get_content(){
        global $PAGE;
        if ($this->content !== null) {
            return $this->content;
        }
        /** @var block_qanda_renderer*/
        $renderer = $PAGE->get_renderer('block_qanda');
        $this->content = new stdClass();
        $this->content->text   = $renderer->render_qanda_box();
        return $this->content;
    }
    
    public function instance_delete(){
        /** @var context_block */
        $context = $this->context;
        /** @var context_course */
        $course_context = $context->get_course_context(true);
        return wmios_qanda::clear_for_course_deleted($course_context->instanceid);
    }




}

