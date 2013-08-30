<?php  

/**
* Import smarty
*
* @package    local
* @subpackage wmios
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/


require_once(dirname(__FILE__).'/vendor/Smarty3113/Smarty.class.php');

/**
* 
* Get smarty parser.
* 
* Where is the starty's template_dir going.
* 
* 1. If call by get_smarty('block_notes',null),The template_dir will go as follow
*    a. /theme/{$theme_name}/view/scripts/block/notes
*    b. /blocks/notes/view/scripts/{$theme_name}/
*    c. /blocks/notes/view/scripts/default/
* 2.If call by get_smarty(null,'$CFG->dirroot/a/b/c/d/[view|views]'),The template_dir will go as follow
*    a. /theme/{$theme_name}/view/scripts//a/b/c/d/
*    b. $CFG->dirroot/a/b/c/d/[view|views]/scripts/{$theme_name}/
*    C. $CFG->dirroot/a/b/c/d/[view|views]/scripts/default/
* 
* @param \String $teplate_dir the teplate bae dir.Default in local/wmios/view
* @param \moodle_page $page which page is smarty used by.
* @return Smarty
* 
*/
function get_smarty($component = '',$teplate_dir = '',moodle_page $page = null){
    global $CFG,$PAGE;
    
    if(!$page){
        $page = $PAGE;
    }
    
    $smarty = new Smarty();

    $compiledir = $CFG->dataroot.'/smarty/templates_c/';
    $cachedir = $CFG->dataroot.'/smarty/cache/';
    if(!is_dir($compiledir)){
        mkdir($compiledir,0777,true);
    }
    if(!is_dir($cachedir)){
        mkdir($cachedir,0777,true);
    }




    if($component){
        $tmp_dir = implode('/', normalize_component($component) );
        $script_dir = $page->theme->dir.'/view/scripts/'. $tmp_dir .'/';
        $config_dir = $page->theme->dir.'/view/configs/'. $tmp_dir .'/';
        if(!is_readable($script_dir)){
            $plugin_dir = get_component_directory($component);
            $script_dir = $plugin_dir .'/view/scripts/'.$page->theme->name.'/';
            $config_dir = $plugin_dir .'/view/configs/'.$page->theme->name.'/';
            if(!is_readable($script_dir)){
                $script_dir = $plugin_dir .'/view/scripts/default/';
                $config_dir = $plugin_dir .'/view/configs/default/';
            }
        }
    }else{
        if(!$teplate_dir){
            $teplate_dir = dirname(__FILE__).'/view';
        }
        $script_dir = $teplate_dir.'/scripts/'. $page->theme->name.'/';
        $config_dir = $teplate_dir.'/configs/'. $page->theme->name.'/';
        if(!is_dir($script_dir)){
            $script_dir = $teplate_dir.'/scripts/default/';
            $config_dir = $teplate_dir.'/configs/default/';
        }


        $theme_script_dir = str_ireplace($CFG->dirroot,'',$teplate_dir);
        if(substr($theme_script_dir,-5) === '/view'){
            $theme_script_dir = substr($theme_script_dir,0,strlen($theme_script_dir)-5);
        }elseif(substr($theme_script_dir,-6) === '/views'){
            $theme_script_dir = substr($theme_script_dir,0,strlen($theme_script_dir)-6);
        }
        $theme_script_dir = $page->theme->dir.'/view/scripts/'. $theme_script_dir.'/';
        if(is_readable($theme_script_dir)){
            $script_dir = $theme_script_dir;
            $config_dir = $page->theme->dir.'/view/configs/'. $theme_script_dir.'/';
        }
    }

    $smarty->setTemplateDir($script_dir);
    $smarty->setConfigDir($config_dir);

    $smarty->setCompileDir($compiledir);            
    $smarty->setCacheDir($cachedir);
    return $smarty;
}