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

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->libdir.'/filelib.php');

class mod_document_mod_form extends moodleform_mod {
    function definition() {
        global $CFG, $DB,$PAGE,$COURSE;
        $mform = &$this->_form;

        $mform->addElement('header', 'general', get_string('general', 'form'));
        $mform->addElement('text', 'name', get_string('name'), array('size'=>'48'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $this->add_intro_editor(false);

        //-------------------------------------predefined field
        $mform->addElement('header', 'document_fields', get_string('document_fields', MOD_DOCUMENT_PLUGIN_NAME));
        $mform->setExpanded('document_fields', true);
        $userdefined_field_types = document_field_type::get_types_by_courseid($COURSE->id);
        $userdefined_select = array();
        if($userdefined_field_types)foreach($userdefined_field_types as $field_type)
        {
            $userdefined_select[$field_type->id] = $field_type->name;
        }
        if(count($userdefined_select))
        {
            $mform->addElement('select', 'userdefined_field_types',get_string('userdefined_field_types',MOD_DOCUMENT_PLUGIN_NAME),
                $userdefined_select,array('multiple'=>true));
        }
        if(isset($this->current) && $this->current->instance>0)
        {
            $old_fields = document_base::document_fields_id_by_baseid($this->current->instance);
            $mform->setDefault('predefined_field_types',$old_fields);
            $mform->setDefault('userdefined_field_types',$old_fields);
        }

         //-------------------------------------------------------
        $this->standard_coursemodule_elements();

        //-------------------------------------------------------
        $this->add_action_buttons();

        //-------------------------------------------------------
        $mform->addElement('hidden', 'revision');
        $mform->setType('revision', PARAM_INT);
        $mform->setDefault('revision', 1);


    }


}
