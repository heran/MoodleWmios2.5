<?php

require_once ('./config.php');
$fs = get_file_storage();

$fileinfo = array(
    'contextid' => 262, // ID of context
    'component' => 'surveyactivity_employengage',     // usually = table name
    'filearea' => 'asdasdas',     // usually = table name
    'itemid' => 0,               // usually = ID of row in table
    'filepath' => '/radar/',           // any path beginning and ending in /
    'filename' => 'myfile.txt'); // any filename

// Create file containing text 'hello world'
//$fs->create_file_from_string($fileinfo, 'hello world'.time());
$file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],$fileinfo['itemid'],$fileinfo['filepath'], $fileinfo['filename']);
//echo $file->get_content();
echo moodle_url::make_pluginfile_url($fileinfo['contextid'], $fileinfo['component'],  $fileinfo['filearea'],
            $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename'])->out(false);
$y=1;