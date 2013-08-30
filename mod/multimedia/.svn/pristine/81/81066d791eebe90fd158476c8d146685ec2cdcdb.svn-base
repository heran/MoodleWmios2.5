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
 * Multimedia module admin settings and defaults
 *
 * @package    mod
 * @subpackage multimedia
 * @copyright  2013 Wmios (http://wmios.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once(dirname(__FILE__).'/lib.php');

if ($ADMIN->fulltree) {
    require_once("$CFG->libdir/resourcelib.php");

    $displayoptions = resourcelib_get_displayoptions(array(RESOURCELIB_DISPLAY_OPEN, RESOURCELIB_DISPLAY_POPUP));
    $defaultdisplayoptions = array(RESOURCELIB_DISPLAY_OPEN);

    //-------- general settings ------------
    //Must input the multimedia's introduction?
    $settings->add(new admin_setting_configcheckbox('multimedia/requiremodintro',
        get_string('requiremodintro', 'admin'), get_string('configrequiremodintro', 'admin'), 1));
    // For now, mutimedia appears only when theme udemy is using. 
    // But fulture, we need support popup view and new window view
    //TODO 1 -o wmios_heran -c multimedia :we need support popup view and new window view
    /*$settings->add(new admin_setting_configmultiselect('multimedia/displayoptions',
        get_string('displayoptions', 'multimedia'), get_string('configdisplayoptions', 'multimedia'),
        $defaultdisplayoptions, $displayoptions));*/

    //-------- modedit defaults ------------
    $settings->add(new admin_setting_heading('multimediamodeditdefaults', get_string('modeditdefaults', 'admin'), get_string('condifmodeditdefaults', 'admin')));

    //TODO 1 -o wmios_heran -c multimedia :we need support popup view and new window view 
    /*$settings->add(new admin_setting_configcheckbox_with_advanced('multimedia/printheading',
        get_string('printheading', 'multimedia'), get_string('printheadingexplain', 'multimedia'),
        array('value'=>1, 'adv'=>false)));
    $settings->add(new admin_setting_configcheckbox_with_advanced('multimedia/printintro',
        get_string('printintro', 'multimedia'), get_string('printintroexplain', 'multimedia'),
        array('value'=>0, 'adv'=>false)));
    $settings->add(new admin_setting_configselect_with_advanced('multimedia/display',
        get_string('displayselect', 'multimedia'), get_string('displayselectexplain', 'multimedia'),
        array('value'=>RESOURCELIB_DISPLAY_OPEN, 'adv'=>true), $displayoptions));
    $settings->add(new admin_setting_configtext_with_advanced('multimedia/popupwidth',
        get_string('popupwidth', 'multimedia'), get_string('popupwidthexplain', 'multimedia'),
        array('value'=>620, 'adv'=>true), PARAM_INT, 7));
    $settings->add(new admin_setting_configtext_with_advanced('multimedia/popupheight',
        get_string('popupheight', 'multimedia'), get_string('popupheightexplain', 'multimedia'),
        array('value'=>450, 'adv'=>true), PARAM_INT, 7));*/
        $types = mod_multimedia_type::get_options();
    $settings->add(new admin_setting_configselect_with_advanced('multimedia/type',
        get_string('typeselect', 'multimedia'), get_string('typeselectexplain', 'multimedia'),
        array('value'=>key($types),'adv'=>true), $types));
}
