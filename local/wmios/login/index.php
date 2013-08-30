<?php

/**
 * Login page
 *
 * @package    local
 * @subpackage wmios
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
require_once(dirname(dirname((dirname(dirname(__FILE__))))) . '/config.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/wmios/login/index.php');
$PAGE->set_title(get_string('login'));
$PAGE->set_heading(get_string('login'));


$PAGE->set_pagelayout('noregion');
echo $OUTPUT->header();
/** @var local_wmios_renderer*/
$renderer = $PAGE->get_renderer(LOCAL_WMIOS_PLUGIN_NAME);
echo $renderer->login_page(optional_param('errorcode',0,PARAM_INT));
echo $OUTPUT->footer();