<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.




defined('MOODLE_INTERNAL') || die();

class mod_document_renderer extends plugin_renderer_base {
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
            $this->_smarty = get_smarty('mod_document',null,$this->page);
            $url_mod_document = new moodle_url('/mod/document');
            $this->_smarty->assign('url_mod_document',$url_mod_document->out(false));
        }
        return $this->_smarty;
    }

    public function render_field_dict_list(document_field_tree $field_dict_root, document_field_type $field_type)
    {

        $url_base = new moodle_url('/mod/document/field_manage.php',array(
            'course_id'=>$field_type->course_id,
            'type_id'=>$field_type->id,
            'action'=>'field_dict'
            ));
        $url_type = new moodle_url('/mod/document/field_manage.php',array('action'=>'field_type','subaction'=>'list','course_id'=>$field_type->course_id));

        $smarty = $this->get_smarty();
        $smarty->assign('field_dict_root' ,$field_dict_root);
        $smarty->assign('field_type' ,$field_type);
        $smarty->assign('url_base',$url_base->out(false));
        $smarty->assign('url_type',$url_type->out(false));
        return $smarty->fetch('field_dict_list.tpl');
    }

    /**
    * put your comment there...
    *
    * @param document_field_type[] $field_types
    * @param mixed $COURSE
    */
    public function render_field_type_list(array $field_types,$COURSE)
    {
        $url_base = new moodle_url('/mod/document/field_manage.php',array(
            'course_id'=>$COURSE->id
        ));

        $smarty = $this->get_smarty();
        $smarty->assign('field_types' ,$field_types);
        $smarty->assign('course' ,$COURSE);
        $smarty->assign('url_base',$url_base->out(false));
        $smarty->assign('permission_str',document_field_type::permission_option_array());
        return $smarty->fetch('field_type_list.tpl');
    }

    public function render_uploader(document_base $base)
    {
        global $USER;

        $url_upload = new moodle_url('/mod/document/upload.php?cmid='.$base->get_cm()->id);
        $url_document = new moodle_url('/mod/document/');
        $drafts = $base->get_user_draft_entities($USER->id);

        $smarty = $this->get_smarty();
        $smarty->assign('base',$base);
        $smarty->assign('has_drafts',count($drafts)>0);
        $smarty->assign('drafts',$drafts);
        $smarty->assign('url_upload',$url_upload->out(false));
        $smarty->assign('url_document',$url_document->out(false));
        return $smarty->fetch('uploader.tpl');
    }

    public function render_document_entity(document_entity $de)
    {
        $cmid = $de->get_base()->get_cm()->id;
        $url_document_base = new moodle_url('/mod/document/view.php',array('cmid'=>$cmid));
        $url_document_entity_edit = new moodle_url('/mod/document/upload.php',
            array('key'=>$de->key,'action'=>'edit','cmid'=>$cmid));
        $smarty = $this->get_smarty();
        $smarty->assign('de',$de);
        $smarty->assign('url_document_base',$url_document_base);
        $smarty->assign('url_document_entity_edit',$url_document_entity_edit);
        return $smarty->fetch('document_entity_view.tpl');
    }

    public function render_document_entity_edit(document_form_entity_edit $eform)
    {
        global $USER;

        $drafts = $eform->get_document_base()->get_user_draft_entities($USER->id);
        unset($drafts[$eform->get_document_entity()->key]);

        $smarty = $this->get_smarty();
        $smarty->assign('has_drafts',count($drafts)>0);
        $smarty->assign('drafts',$drafts);
        $smarty->assign('eform',$eform);
        $smarty->assign('is_uploading', $eform->get_document_entity()->status == document_entity::DOCUMENT_STATUS_DRAFT);
        $smarty->assign('de',$eform->get_document_entity());
        $smarty->assign('base',$eform->get_document_base());
        return $smarty->fetch('document_entity_edit.tpl');
    }



    public function render_document_entity_list($in_search, document_base $base,  $des, $total, $tmpl, $slice = false)
    {
        global $OUTPUT;
        $smarty = $this->get_smarty();
        foreach($tmpl as $k=>$v)
        {
            $smarty->assign($k,$v);
        }
        $smarty->assign('base',$base);
        $smarty->assign('des',$des);
        $smarty->assign('total',$total);
        $smarty->assign('tmpl',$tmpl);
        $smarty->assign('file_extensions',array('doc,docx'=>'DOC(X)','ppt,pptx'=>'PPT(X)','xls,xlsx'=>'XLS(X)','pdf'=>'PDF(X)','txt'=>'TXT(X)'));
        $tmpl['base_id'] = $base->id;

        $tmp = $tmpl;
        unset($tmp['page'],$tmp['perpage']);
        $base_url = new moodle_url('/mod/document/view.php',$tmp);
        $smarty->assign('pagingbar',$OUTPUT->paging_bar($total, $tmpl['page'], $tmpl['perpage'], $base_url->out(false)));


        $smarty->assign('get_params',json_encode($tmp));
        $smarty->assign('slice',$slice);

        return  $smarty->fetch($in_search ? 'document_entity_search.tpl': 'document_entity_list.tpl')  ;
    }
}