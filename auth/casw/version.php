<?php

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2013050103;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->requires  = 2013050100;        // Requires this Moodle version
$plugin->component = 'auth_casw';        // Full name of the plugin (used for diagnostics)
$plugin->dependencies = array('local_wmios' => 2013042400,'auth_cas'=>2013050100);