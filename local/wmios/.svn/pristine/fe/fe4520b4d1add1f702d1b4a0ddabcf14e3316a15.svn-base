<?php

/**
 * Update completion ratio
 *
 * @package    local
 * @subpackage wmios
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/../../../config.php');
require_once(dirname(__FILE__).'/lib.php');
global $USER,$COURSE,$DB;

//login
require_login();


$cmid = required_param('cmid',PARAM_ACTION);
$mod = get_coursemodule_from_id(null,$cmid);
$now = required_param('now',PARAM_INT);

$COURSE = $DB->get_record('course', array('id'=>$mod->course), '*', MUST_EXIST);

$modname = $mod->modname;
require_once($CFG->dirroot.'/mod/'.$modname.'/lib.php');

//The max number
$max_func = $modname.'_get_completion_unit_max';
$max = $max_func($mod);
$is_end = optional_param('end','no',PARAM_ACTION);
if($is_end == 'yes'){
    $now = $max;
}
$ratio = $now/$max;
$setnow_func = $modname.'_set_completion_unit_now';
$setnow_func($mod,$USER->id,$now);

$completion_info = new wmios_completion_info($COURSE);
$result = $completion_info->set_ratio($mod,$USER->id,$ratio);
$ratio = $completion_info->get_ratio($mod,$USER->id)->completionstate;
$ratio_str = intval($ratio*100).'%';


$wt = optional_param('wt','json',PARAM_ALPHANUMEXT);
switch($wt){
    case 'json':
    echo json_encode(array('status'=>1,'ratio_str'=>$ratio_str,'ratio'=>$ratio,'cmid'=>$cmid));
    break;
}