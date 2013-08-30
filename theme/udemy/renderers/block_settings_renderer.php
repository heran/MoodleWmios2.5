<?php
include_once($CFG->dirroot . "/blocks/settings/renderer.php");
class theme_udemy_block_settings_renderer extends block_settings_renderer{
    /**
    * @inheritdoc
    *
    * @param settings_navigation $navigation
    */
    public function settings_tree(settings_navigation $navigation) {
        if(has_capability('moodle/site:config',context_system::instance())){
            return parent::settings_tree($navigation);
        }else{
            return null;
        }
    }
}
?>
