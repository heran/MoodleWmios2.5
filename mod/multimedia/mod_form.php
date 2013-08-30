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
 * multimedia configuration form
 *
 * @package    mod
 * @subpackage multimedia
 * @copyright  2013 Wmios (http://wmios.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->libdir.'/filelib.php');

class mod_multimedia_mod_form extends moodleform_mod {
    function definition() {
        global $CFG, $DB,$PAGE;

        $mform = $this->_form;

        $config = get_config('multimedia');

        //-------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));
        $mform->addElement('text', 'name', get_string('name'), array('size'=>'48'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $this->add_intro_editor($config->requiremodintro);

        //-------------------------------------------------------
        $mform->addElement('header', 'contentsection', get_string('contentheader', 'multimedia'));
        /*$mform->addElement('editor', 'multimedia', get_string('content', 'multimedia'), null,
         multimedia_get_editor_options($this->context));
        $mform->addRule('multimedia', get_string('required'), 'required', null, 'client');*/

        //content
        //TODO 10 -o wmios_heran -c multimedia :Let the subplugin decide accepted_types and return_types
        $filepickeroptions = array('accepted_types' => '*','return_types'=>FILE_EXTERNAL);
        $mform->addElement('filepicker','multimedia',get_string('file'), null,$filepickeroptions);
        //$mform->addRule('multimedia', get_string('required'), 'required', null, 'client');
        $modle = array(
                'name' => 'mod_multimedia',
                'fullpath' => '/mod/multimedia/js/mod_form.js',
                'requires' => array('node', 'event')
                );
        $PAGE->requires->js_init_call('M.mod_multimedia.init_filepicker',array('multimedia'),true,$modle);
        
        $mform->addElement('hidden','content');
        $mform->addRule('content', null, 'required', null, 'client');
        $mform->setType('content',PARAM_TEXT);
        if($this->current->instance){
            $PAGE->requires->js_init_call('M.mod_multimedia.restore_filepicker',array('multimedia'),true,$modle);
        }

        //type
        //TODO 10 -o wmios_heran -c multimedia :implement a plugin chain to decide the mutimedia's type
        /*$mform->addElement('select', 'type',
            get_string('multimedia_type', 'multimedia'),
            mod_multimedia_type::get_options());
        if($this->current->instance){
            $mform->setDefault('type',$this->current->type);
        }*/
        
        //completion_max
        //TODO 10 -o wmios_heran -c multimedia :let the subplugin calculate the mutimedia's length
        /*$mform->addElement('text','completion_max',get_string('completion_max','mod_multimedia'));
        $mform->setType('completion_max', PARAM_INT);
        $mform->addRule('completion_max', get_string('need_integer','multimedia'), 'numeric', null, 'client');
        $mform->addRule('completion_max', '', 'required', null, 'client');*/

        //-------------------------------------------------------
        //TODO 1 -o wmios_heran -c multimedia :we need support popup view and new window view
        /*$mform->addElement('header', 'optionssection', get_string('optionsheader', 'multimedia'));

        if ($this->current->instance) {
            $options = resourcelib_get_displayoptions(explode(',', $config->displayoptions), $this->current->display);
        } else {
            $options = resourcelib_get_displayoptions(explode(',', $config->displayoptions));
        }
        if (count($options) == 1) {
            $mform->addElement('hidden', 'display');
            $mform->setType('display', PARAM_INT);
            reset($options);
            $mform->setDefault('display', key($options));
        } else {
            $mform->addElement('select', 'display', get_string('displayselect', 'multimedia'), $options);
            $mform->setDefault('display', $config->display);
            $mform->setAdvanced('display', $config->display_adv);
        }

        if (array_key_exists(RESOURCELIB_DISPLAY_POPUP, $options)) {
            $mform->addElement('text', 'popupwidth', get_string('popupwidth', 'multimedia'), array('size'=>3));
            if (count($options) > 1) {
                $mform->disabledIf('popupwidth', 'display', 'noteq', RESOURCELIB_DISPLAY_POPUP);
            }
            $mform->setType('popupwidth', PARAM_INT);
            $mform->setDefault('popupwidth', $config->popupwidth);
            $mform->setAdvanced('popupwidth', $config->popupwidth_adv);

            $mform->addElement('text', 'popupheight', get_string('popupheight', 'multimedia'), array('size'=>3));
            if (count($options) > 1) {
                $mform->disabledIf('popupheight', 'display', 'noteq', RESOURCELIB_DISPLAY_POPUP);
            }
            $mform->setType('popupheight', PARAM_INT);
            $mform->setDefault('popupheight', $config->popupheight);
            $mform->setAdvanced('popupheight', $config->popupheight_adv);
        }

        
        $mform->addElement('advcheckbox', 'printheading', get_string('printheading', 'multimedia'));
        $mform->setDefault('printheading', $config->printheading);
        $mform->setAdvanced('printintro', $config->printheading_adv);
        $mform->addElement('advcheckbox', 'printintro', get_string('printintro', 'multimedia'));
        $mform->setDefault('printintro', $config->printintro);
        $mform->setAdvanced('printintro', $config->printintro_adv);*/

        //-------------------------------------------------------
        $this->standard_coursemodule_elements();

        //-------------------------------------------------------
        $this->add_action_buttons();

        //-------------------------------------------------------
        $mform->addElement('hidden', 'revision');
        $mform->setType('revision', PARAM_INT);
        $mform->setDefault('revision', 1);
    }

    function data_preprocessing(&$default_values) {//die();
        if ($this->current->instance) {
            $default_values['content'] = base64_encode($default_values['content']);
        }
        /*if (!empty($default_values['displayoptions'])) {
            $displayoptions = unserialize($default_values['displayoptions']);
            if (isset($displayoptions['printintro'])) {
                $default_values['printintro'] = $displayoptions['printintro'];
            }
            if (isset($displayoptions['printheading'])) {
                $default_values['printheading'] = $displayoptions['printheading'];
            }
            if (!empty($displayoptions['popupwidth'])) {
                $default_values['popupwidth'] = $displayoptions['popupwidth'];
            }
            if (!empty($displayoptions['popupheight'])) {
                $default_values['popupheight'] = $displayoptions['popupheight'];
            }
        }*/
    }



    function add_completion_rules() {
        $mform =& $this->_form;

        $mform->addElement('checkbox', 'completionenabled', '', get_string('muststudyallthing', 'mod_multimedia'));
        return array('completionenabled');
    }

    function completion_rule_enabled($data) {
        return !empty($data['completionenabled']);
    }
}

