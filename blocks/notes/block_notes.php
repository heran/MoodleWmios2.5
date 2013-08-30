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


require_once($CFG->dirroot.'/local/wmios/notes/lib.php');

/*
* @package    blocks
* @subpackage notes
* @copyright  2013 Wmios (http://wmios.com)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*
* The community block
*/

/**
* The block for course's note
*/
class block_notes extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_notes');
    }

    public function get_content(){
        global $PAGE;
        /*$this->content = new stdClass();
        $this->content->text = '';
        return $this->content;*/
        if ($this->content !== null) {
            return $this->content;
        }
        /** @var block_notes_renderer*/
        $renderer = $PAGE->get_renderer('block_notes');
        $this->content = new stdClass();
        $this->content->text   = $renderer->render_notes_box();
        return $this->content;
    }


    public function instance_delete(){
        /** @var context_block */
        $context = $this->context;
        /** @var context_course */
        $course_context = $context->get_course_context(true);
        return wmios_note::clear_for_course_deleted($course_context->instanceid);
    }

}

