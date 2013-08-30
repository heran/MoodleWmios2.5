<?php
require_once($CFG->dirroot . '/course/renderer.php');
class theme_udemy_core_course_renderer extends core_course_renderer{

    /**
    * smarty class
    *
    * @var Smarty
    */
    protected $_smarty = null;

    /**
    * @return Smarty
    *
    */
    public function get_smarty() {
        if($this->_smarty === null){
            $this->_smarty = get_smarty('core_course',null,$this->page);
        }
        return $this->_smarty;
    }

    /**
    * @inheritdoc
    *
    * fixme 10 -o wmios_heran -c course : We change the flow of course list. So sorry!
    * Now course/index only can browse the course or search. Also there isn't course/search.
    *
    * @param int|stdClass|coursecat $category
    */
    public function course_category($category){
        $browse_course = optional_param('browse',null,PARAM_ALPHA) === 'courses';
        $browse_course = $browse_course && optional_param('categoryid',null,PARAM_INT);
        $search = (bool)optional_param('search',null,PARAM_ALPHA);
        if( !$browse_course && !$search ){
            $cats = coursecat::make_categories_list();
            redirect(new moodle_url('/course/index.php',array('browse'=>'courses','categoryid'=>key($cats))));
        }

        if($search){
            $this->page->set_pagetype('course-index-search');
            //when only search, we need cats list.
            $select = new single_select(new moodle_url('/course/index.php'), 'categoryid',
                coursecat::make_categories_list());
            $select->class = 'none';
            $content = $this->render($select);

        }else{
            $content = parent::course_category($category);
            //when from ajax
            $ajax = optional_param('ajax',null,PARAM_ALPHA);
            if($ajax){
                echo $content;
                die();
            }
        }

        $this->page->requires->jquery_plugin('handlebars','theme_udemy');
        $smarty = $this->get_smarty();
        $smarty->assign('mycourses' ,(array)enrol_get_my_courses('id') );
        $content .= $smarty->fetch('course_category.tpl');

        return $content;
    }
}