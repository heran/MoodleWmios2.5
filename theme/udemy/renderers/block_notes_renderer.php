<?php
include_once($CFG->dirroot . "/blocks/notes/renderer.php");
class theme_udemy_block_notes_renderer extends block_notes_renderer{
    
    public function render_notes_box(){
        $smarty = get_smarty();
        $str = new stdClass();
        $str->type_your_note = get_string('type_your_note','theme_udemy');
        $str->down_load_notes = get_string('down_load_notes','theme_udemy');
        $url = new stdClass();
        $url->update_note = new moodle_url('/local/wmios/notes/update.php',array('wt'=>'json'));
        $url->get_note_list = new moodle_url('/local/wmios/notes/list.php',array('wt'=>'json'));
        $url->delete_note = new moodle_url('/local/wmios/notes/delete.php',array('wt'=>'json'));
        $smarty->assign('url',$url);
        $smarty->assign('str',$str);
        return $smarty->fetch('block/notes/render_notes_box.tpl');
    }
}