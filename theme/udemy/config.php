<?php

/**
 *
 *
 * @package   moodle_wmioss
 * @copyright 2012 Wmios (http://wmios.com/)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
include_once(dirname(__FILE__).'/lib.php');
 //name
$THEME->name = 'udemy';

//extends from
$THEME->parents = array(
    'base',
);

//style sheets
$sheet_directory = new DirectoryIterator(dirname(__FILE__).'/style');
foreach($sheet_directory as $sheetfile){
    if($sheetfile->isFile()){
        $THEME->sheets[] = str_ireplace('.css','',$sheetfile->getFilename());
    }
}

//exclude parents' sheets
$THEME->parents_exclude_sheets = array(
        'base'=>array(
            'pagelayout',
        ),
        'canvas'=>array(
            'pagelayout',
        ),
);

//Whether user can dock blocks
$THEME->enable_dock = true;

//An array of stylesheets to include within the body of the editor.
$THEME->editor_sheets = array('editor');

$THEME->layouts = array(
    'base' => array(
        'file' => 'general.php',
        'regions' => array('side-post'),
        'defaultregion' => 'side-post',
    ),
    'standard' => array(
        'file' => 'general.php',
        'regions' => array('side-post'),
        'defaultregion' => 'side-post',
    ),
    'course' => array(
        'file' => 'general.php',
        'regions' => array('side-post'),
        'defaultregion' => 'side-post'
    ),
    'coursecategory' => array(
        'file' => 'general.php',
        'regions' => array(),
        'defaultregion' => '',
    ),
    'incourse' => array(
        'file' => 'general.php',
        'regions' => array('side-post'),
        'defaultregion' => 'side-post'
    ),
    'frontpage' => array(
        'file' => 'general.php',
        'regions' => array('side-post'),
        'defaultregion' => 'side-post',
    ),
    'admin' => array(
        'file' => 'general.php',
        'regions' => array('side-post'),
        'defaultregion' => 'side-post',
    ),
    'mydashboard' => array(
        'file' => 'general.php',
        'regions' => array(),
        'defaultregion' => 'side-post',
        'options' => array('langmenu'=>false),
    ),
    'mypublic' => array(
        'file' => 'general.php',
        'regions' => array('side-post'),
        'defaultregion' => 'side-post',
    ),
    'login' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('langmenu'=>true),
    ),
    'popup' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('nofooter'=>true, 'noblocks'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'nocourseheaderfooter'=>true),
    ),
    'frametop' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('nofooter'=>true, 'nocoursefooter'=>true),
    ),
    'maintenance' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'nocourseheaderfooter'=>true),
    ),
    'local_survey' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('nocourseheaderfooter'=>true),
    ),
    'local_survey_maintenance' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('nocourseheaderfooter'=>true),
    ),
    'embedded' => array(
        'theme' => 'canvas',
        'file' => 'embedded.php',
        'regions' => array(),
        'options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'nocourseheaderfooter'=>true),
    ),
    // Should display the content and basic headers only.
    'print' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('nofooter'=>true, 'noblocks'=>true, 'nonavbar'=>false, 'nocustommenu'=>true, 'nocourseheaderfooter'=>true),
    ),
    'report' => array(
        'file' => 'general.php',
        'regions' => array('side-post'),
        'defaultregion' => 'side-post',
    ),
    'redirect' => array(
        'file' => 'general.php',
        'regions' => array(),//'nocustommenu'=>true,'nofooter'=>true,
        'options' => array('nonavbar'=>true,  'nocourseheaderfooter'=>true, 'nocustommenu'=>true, 'nofooter'=>true, 'noblocks'=>true),
    ),
    'noregion' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('langmenu'=>false),
    )
);


// $THEME->csspostprocess

////////////////////////////////////////////////////
// Allows the user to provide the name of a function
// that all CSS should be passed to before being
// delivered.
////////////////////////////////////////////////////

$THEME->javascripts = array(
);


////////////////////////////////////////////////////
// An array containing the names of JavaScript files
// located in /javascript/ to include in the theme.
// (gets included in the head)
////////////////////////////////////////////////////

// $THEME->javascripts_footer

////////////////////////////////////////////////////
// As above but will be included in the page footer.
////////////////////////////////////////////////////

//$THEME->larrow    = '&lang;';

////////////////////////////////////////////////////
// Overrides the left arrow image used throughout
// Moodle
////////////////////////////////////////////////////

//$THEME->rarrow    = '&rang;';

////////////////////////////////////////////////////
// Overrides the right arrow image used throughout Moodle
////////////////////////////////////////////////////

// $THEME->layouts

////////////////////////////////////////////////////
// An array setting the layouts for the theme
////////////////////////////////////////////////////

// $THEME->parents_exclude_javascripts

////////////////////////////////////////////////////
// An array of JavaScript files NOT to inherit from
// the themes parents
////////////////////////////////////////////////////

// $THEME->parents_exclude_sheets

////////////////////////////////////////////////////
// An array of stylesheets not to inherit from the
// themes parents
////////////////////////////////////////////////////

// $THEME->plugins_exclude_sheets

////////////////////////////////////////////////////
// An array of plugin sheets to ignore and not
// include.
////////////////////////////////////////////////////

$THEME->rendererfactory = 'theme_overridden_renderer_factory';

////////////////////////////////////////////////////
// Sets a custom render factory to use with the
// theme, used when working with custom renderers.
////////////////////////////////////////////////////