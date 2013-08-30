<?php
include_once($CFG->dirroot . "/blocks/qanda/renderer.php");
class theme_udemy_block_qanda_renderer extends block_qanda_renderer{

    public function render_qanda_box(){
        $smarty = get_smarty();
        $str = new stdClass();
        $str->type_your_question = get_string('type_your_question','theme_udemy');
        $str->back = get_string('back');
        $str->follow = get_string('follow','theme_udemy');
        $url = new stdClass();
        $url->ask_question = new moodle_url('/local/wmios/qanda/updateq.php',array('wt'=>'json'));
        $url->get_question_list = new moodle_url('/local/wmios/qanda/listq.php',array('wt'=>'json'));
        $url->add_question_answer = new moodle_url('/local/wmios/qanda/updatea.php',array('wt'=>'json'));
        $url->get_answer_list = new moodle_url('/local/wmios/qanda/lista.php',array('wt'=>'json'));
        $smarty->assign('url',$url);
        $smarty->assign('str',$str);
        return $smarty->fetch('block/qanda/render_qanda_box.tpl');
    }
}