<?php
include_once($CFG->dirroot . "/blocks/navigation/renderer.php");
class theme_udemy_block_navigation_renderer extends block_navigation_renderer{
    /**
    * @inheritdoc
    *
    * @param settings_navigation $navigation
    */
    public function navigation_tree(global_navigation $navigation, $expansionlimit, array $options = array()) {
        if(has_capability('moodle/site:config',context_system::instance())){
            return parent::navigation_tree($navigation, $expansionlimit,  $options);
        }else{
            return null;
        }
    }
}
