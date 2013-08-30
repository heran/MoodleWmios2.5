<?php

require(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/locallib.php');

$cmid = optional_param('cmid', 0, PARAM_INT); //course module
if(!$cmid)
{
    $cmid = optional_param('id',0, PARAM_INT);
}
if($cmid)
{
    $cm = get_coursemodule_from_id('document', $cmid);
    $cm_context = context_module::instance($cm->id);
    require_capability('mod/document:upload', $cm_context);
    $document_base = document_base::instance_from_id($cm->instance);
    $document_base->set_cm($cm);
}else
{
    $base_id = required_param('base_id',PARAM_INT);
    $document_base = document_base::instance_from_id($base_id);
    $cm = get_coursemodule_from_instance('document', $base_id, $document_base->course, false, MUST_EXIST);
    $cm_context = context_module::instance($cm->id);
    require_capability('mod/document:upload', $cm_context);
}
$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
require_course_login($course, true, $cm);
/** @var document_base*/
$document_base->set_course($course);

$PAGE->set_context($cm_context);
$PAGE->set_url($FULLME);
$PAGE->set_cm($cm);
$PAGE->set_pagelayout('noregion');

/** @var mod_document_renderer*/
$renderer = $PAGE->get_renderer('mod_document');

$url_document_base = new moodle_url('/mod/document/view.php?id='.$cm->id);
$url_document_upload = new moodle_url('/mod/document/upload.php?cmid='.$cm->id);

$action = optional_param('action','',PARAM_ACTION);
switch($action)
{
    case 'file':
    {
        $de = new document_entity($document_base);

        $file = $_FILES['Filedata'];

        $data = array();
        $valid = true;
        if($file['error'])
        {
            $valid = false;
            $data['error'] = '传输错误';
        }elseif(!$de->file_size_is_valid(filesize($file['tmp_name'])))
        {
            $data['error'] = '文件大小超过限制:20M';
            $valid = false;
        }elseif(!$de->file_extension_is_valid($file['name']))
        {
            $data['error'] = '文档类型错误';
            $valid = false;
        }
        else{
            try{
                $data['key'] = $document_base->get_entity_by_upload_file($file)->key;
            }catch (Exception $e)
            {
                $valid = false;
                $data['error'] = $e->getMessage();
            }

        }
        $data['status'] = $valid ? 1 : 0;
        echo json_encode($data);

    }
    break;
    case 'edit':
    {
        $key = required_param('key',PARAM_TEXT);
        $de = $document_base->get_entity_by_key($key);

        $eform = new document_form_entity_edit($document_base, $de, $FULLME);

        if ($eform->is_cancelled())
        {
            redirect($url_document_base);
        }
        else if ($data = $eform->get_data())
        {
            if($de->status < document_entity::DOCUMENT_STATUS_FORMAL)
            {
                $de->status = document_entity::DOCUMENT_STATUS_FORMAL;//it's not a draft.
            }
            $de->setFields((array)$data);
            $document_base->save_entity($de);
            redirect($url_document_base.'&key='.$key);
        }
        else
        {
            echo $OUTPUT->header();
            $eform->set_data((object)$de->getFields());
            echo $renderer->render_document_entity_edit($eform);
            //$eform->display();
            echo $OUTPUT->footer();
        }
    }
    break;
    case 'delete':
    {
        $key = required_param('key',PARAM_TEXT);
        $entity = $document_base->get_entity_by_key($key);
        if($entity)
        {
            $document_base->delete_entity($document_base->get_entity_by_key($key));
        }
        if(optional_param('goto_list',0,PARAM_INT))
        {
            echo json_encode(array('status'=>1));
        }else{
            redirect($url_document_upload);
        }
    }
    break;
    default:
    {
        $PAGE->requires->js('/mod/document/js/swfobject.js',true);
        echo $OUTPUT->header();
        echo $renderer->render_uploader($document_base);
        echo $OUTPUT->footer();

    }
    break;
}