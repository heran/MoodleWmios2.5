<?php
defined('MOODLE_INTERNAL') || die();

$module->version   = 2013062907;       // The current module version (Date: YYYYMMDDXX)
$module->requires  = 2012112900;    // Requires this Moodle version
$module->component = 'mod_dekiwiki';       // Full name of the plugin (used for diagnostics)
$module->cron      = 0;
$module->dependencies = array('local_wmios' => 2013042400);