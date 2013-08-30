<?php

define('MOD_DOCUMENT_PLUGIN_NAME','mod_document');

require_once($CFG->dirroot.'/local/wmios/lib.php');
require_once(dirname(__FILE__).'/lib.php');

class document_form_entity_edit extends moodleform
{
    /** @var document_base*/
    private $_base;

    /** @var document_entity*/
    private $_entity ;

    public function __construct(document_base $base, document_entity $de = null, $action=null, $customdata=null, $method='post', $target='', $attributes=null, $editable=true) {
        $this->_base = $base;
        $this->_entity = $de;
        parent::moodleform($action,$customdata,$method,$target,$attributes,$editable);
    }

    public function get_document_entity()
    {
        return $this->_entity;
    }

    public function get_document_base()
    {
        return $this->_base;
    }


    function definition()
    {
        $mform = &$this->_form;

        $mform->addElement('select','permission',
            get_string('document_entity_permission',MOD_DOCUMENT_PLUGIN_NAME),
            document_entity::get_permission_option_array());

        $mform->addElement('text','title',get_string('document_entity_title',MOD_DOCUMENT_PLUGIN_NAME));
        $mform->addRule('title', null, 'required', null, 'client');

        $mform->addElement('textarea','summary',get_string('document_entity_summary',MOD_DOCUMENT_PLUGIN_NAME));

        $mform->addElement('text','keywords',get_string('document_entity_keywords',MOD_DOCUMENT_PLUGIN_NAME));

        $fields = $this->_base->get_document_fields();
        foreach($fields as /** @var document_field_type*/ $field_type)
        {
            if($field_type->type == document_field_type::TYPE_SELECT_SINGLE)
            {
                $mform->addElement('hidden',$field_type->name);
                $mform->setDefault($field_type->name,0);
                $mform->addElement('select',$field_type->name.'_select',$field_type->remark,
                    $field_type->dict->get_select_option_array(),null)
                    ->setAttributes(
                        array('class'=>'document_fields_change',
                            'id'=>'document_fields_change_'.$field_type->name.'_select',
                            'field'=>$field_type->name));


            }else if($field_type->type == document_field_type::TYPE_INPUT_STRING){
                $mform->addElement('text',$field_type->name,$field_type->remark);
            }
        }

        $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('save','admin'));
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

    }
}

class document_form_field_dict extends moodleform
{
    function definition() {
        $mform = &$this->_form;

        $mform->addElement('text','content',get_string('document_field_dict_content',MOD_DOCUMENT_PLUGIN_NAME));
        $mform->addRule('content', null, 'required', null, 'client');

        $mform->addElement('textarea','remark',get_string('document_field_dict_remark',MOD_DOCUMENT_PLUGIN_NAME));

        $mform->addElement('text','sort',get_string('document_field_dict_sort',MOD_DOCUMENT_PLUGIN_NAME));
        $mform->addRule('sort', null, 'numeric', null, 'client');


        $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('save','admin'));
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
    }
}

class document_form_field_type_copy extends moodleform
{
    function definition() {
        global $DB;
        $mform = &$this->_form;
        $field_types = document_field_type::get_public_field_types();
        if(count($field_types)){
            $mform->addElement('header', 'copy', get_string('copy'));
            $mform->setExpanded('copy', false);

            $mform->addElement('radio','old_type_id',get_string('not_copy_field_type',MOD_DOCUMENT_PLUGIN_NAME),null, 0);

            $course_ids = array_keys($field_types);
            $courses = $DB->get_records_select('course','id in ('.implode(',',$course_ids).')');
            foreach($field_types as $course_id=>$types)
            {
                foreach($types as $type)
                {
                    $mform->addElement('radio','old_type_id',$courses[$course_id]->shortname, ': '.$type->name,$type->id);
                }
            }
            $mform->setDefault('old_type_id',0);

            $buttonarray=array();
            $buttonarray[] = &$mform->createElement('submit', 'copy', get_string('copy'));
            $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        }
    }
}

class document_form_field_type extends moodleform
{
    function definition() {
        $mform = &$this->_form;

        $mform->addElement('header', 'edit', get_string('edit'));


        $mform->addElement('text','name',get_string('document_field_type_name',MOD_DOCUMENT_PLUGIN_NAME));
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('textarea','remark',get_string('document_field_type_remark',MOD_DOCUMENT_PLUGIN_NAME));

        $mform->addElement('select','type',get_string('document_field_type_type',MOD_DOCUMENT_PLUGIN_NAME),document_field_type::type_option_array());

        $mform->addElement('select','permission',get_string('document_field_type_permission',MOD_DOCUMENT_PLUGIN_NAME),document_field_type::permission_option_array());

        $buttonarray=array();
        $buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('save','admin'));
        $buttonarray[] = &$mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);

        //--------------------------------------------------------------



    }
}
