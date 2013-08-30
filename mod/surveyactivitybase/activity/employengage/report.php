<?php
use wmios\survey as survey;
require_once(dirname(__FILE__).'/../../../../config.php');
require_once(dirname(__FILE__).'/lib.php');

$cmid = optional_param('cmid', 0, PARAM_INT); //course module
if(!$cmid)
{
    $cmid = optional_param('id',0, PARAM_INT);
}
if($cmid)
{
    $cm = get_coursemodule_from_id('surveyactivitybase', $cmid);
    $cm_context = context_module::instance($cm->id);
    require_capability('mod/surveyactivitybase:update_activity', $cm_context);
    $sa_base = survey\activity_base::instance_from_id($cm->instance);
}else
{
    $base_id = required_param('base_id',PARAM_INT);
    $sa_base = document_base::instance_from_id($base_id);
    $cm = get_coursemodule_from_instance('surveyactivitybase', $base_id, $sa_base->course_id, false, MUST_EXIST);
    $cm_context = context_module::instance($cm->id);
    require_capability('mod/surveyactivitybase:update_activity', $cm_context);
}
$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
require_course_login($course, true, $cm);
$sa_base->set_course($course);
$sa_base->set_cm($cm);
$sa_base->set_context($cm_context);
survey\tool::set_surveyactivity_base($sa_base);

$wrapper = survey\activity_wrapper::instance_from_id(required_param('id',PARAM_INT));
$surveyid = $wrapper->get_activity_instance()->get_instance()->survey_id;


//$wrapper->start();

//判断报告是否已完成
/** @return bool*/
$iscomplete = $wrapper->is_complete();

if($iscomplete)
{

    //按条件查询调查报告 （参数数组）
    $dim1s = array(
        'alls'         =>get_string('alls','surveyactivity_employengage'),
        'gender'       =>get_string('gender','surveyactivity_employengage'),
        'age'          =>get_string('age','surveyactivity_employengage'),
        'provinces'    =>get_string('provinces','surveyactivity_employengage'),
        'departments'  =>get_string('departments','surveyactivity_employengage'),

    );

    //url get 传参
    $dim1 = optional_param('dim1', '', PARAM_ALPHANUM);
    if(!key_exists($dim1, $dim1s))
    {
        $dim1 ='alls';
    }

    //查询数据库字符串
    $querys = '';

    //按条件查询调查报告 （参数数组）
    $dim2s =array();

    //url get 传参
    $dim2 = optional_param('dim2', '',PARAM_TEXT);

    switch($dim1)
    { 
        case 'gender':
            $dim2s = $wrapper->get_activity_instance()->get_genders();

            if(!in_array($dim2,$dim2s))
            {
                $dim2 = current($dim2s);
            }

            if(empty($dim2))
            {
                $dim2 = current($dim2s);
            }

            $querys = "`gender`='{$dim2}'";

            break;
        case 'provinces':
        $records = $wrapper->get_activity_instance()->users_reports($querys);
        $dim2s =$wrapper->get_activity_instance()->check_provinces($records);
        $dim2 = explode('-',$dim2);
        $num = count($dim2); 
        switch($num)
        {
            case 1:
                foreach($dim2s as $chengs)
                {
                    foreach($chengs as $kk => $vv)
                    {
                        $citys[]=$kk;
                    }
                }

                $ck = $citys[0];

                foreach($dim2 as $datas)
                {
                    if(empty($datas))
                    {
                        $dim2sk = array_slice(array_keys($dim2s),0,1);
                        array_push($dim2,$dim2sk[0],$ck);
                        $dim2 = array_slice($dim2,1,2);
                        $querys = "`provinces`='{$dim2[0]}' and `city`='{$dim2[1]}'";
                    }
                    else
                    {
                        foreach($dim2s as  $ks=>$vs)
                        {
                            if($ks == $datas[0])
                            {
                                $city= array_keys($vs);
                                array_push($dim2,$city[0]);
                            }
                        }
                        $querys = "`provinces`='{$dim2[0]}' and `city`='{$dim2[1]}'";
                    }

                }
                break;
            case 2:
                $querys = "`provinces`='{$dim2[0]}' and `city`='{$dim2[1]}'";

                break;
            default: $querys=''; break;
        }

        break;
        case 'departments':
            $records = $wrapper->get_activity_instance()->users_reports($querys);
            $dim2s =$wrapper->get_activity_instance()->check_department($records);

            $dim2 = explode('-',$dim2);
            $tmp = $dim2s;
            $valid = true;
            foreach($dim2 as $department)
            {
                if(!isset($tmp[$department]))
                {
                    $valid = false;
                    break;
                }
                $tmp = $tmp[$department];
            }
            if(!$valid)
            {
                $dim2 = array_slice(array_keys($dim2s),0,1);
            }

            $num = count($dim2);
            $tmps = array('one','two','three','four','five','six','seven','eight','nine','ten');
            $results = array();
            for($i=0;$i<$num;$i++)
            {

                $results[]= " `dplevel_{$tmps[$i]}`='{$dim2[$i]}'";
            }
            $querys = implode(' and ',$results);

            break;
        default: $querys=''; break;
    }

    //按条件查询数据库
    $rows = $wrapper->get_activity_instance()->users_reports($querys);

    if(empty($rows))
    {
        throw new \moodle_exception('database is wrong');
    }

    //调查结果
    $results = $wrapper->get_activity_instance()->reports_results($rows);

    if(empty($results))
    {
        throw new \moodle_exception('database is wrong');
    }

    //设置雷达图基本参数
    $radar = array();
     
    foreach($results as $datas)
    {
        foreach($datas as $k=>$v)
        {
            switch($k)
            {
                case 'requirements':
                case 'management':                
                case 'ownership':                
                case 'operation':                 
                case 'development':               
                case 'yvalues':   $radar[] = $v;break;
            }
        }

    }
    
    $namearr = array("基本要求","基本管理","组织运作","员工归属","员工发展","愿景价值观");

    //生成雷达图
    /** @var stored_file*/
   
   $radars = $wrapper->get_activity_instance()->check_chartradars($namearr,$radar,$dim1,$dim2);
    if(empty($radars))
    {
        throw new \moodle_exception('database is wrong');
    }else
    {
        $radarsUrl = moodle_url::make_pluginfile_url($radars->get_contextid(),$radars->get_component(),  
            $radars->get_filearea(),$radars->get_itemid(), $radars->get_filepath(), $radars->get_filename());
    }

   //生成柱形图
    /** @var stored_file*/
   
    $bars = $wrapper->get_activity_instance()->check_chartbars($results['statics'],$dim1,$dim2);   
    
    if(empty($bars))
    {
        throw new \moodle_exception('database is wrong');
    }else
    {
        $barsUrl = moodle_url::make_pluginfile_url($bars->get_contextid(),$bars->get_component(),  
            $bars->get_filearea(),$bars->get_itemid(), $bars->get_filepath(), $bars->get_filename());

    }

    //生成PDF
    /** @@var stored_file*/
    if(!empty($radars) && !empty($bars))
    {
        $reportspdf = $wrapper->get_activity_instance()->check_reportspdf($radars,$bars,$dim1,$dim2);

        if(empty($reportspdf))
        {
            throw new \moodle_exception('database is wrong');
        }else
        {
            $downloadpdf = moodle_url::make_pluginfile_url($reportspdf->get_contextid(),$reportspdf->get_component(),  
                $reportspdf->get_filearea(),$reportspdf->get_itemid(), $reportspdf->get_filepath(), $reportspdf->get_filename());
        }  

    }

    /** @var surveyactivity_employengage_renderer*/
    $renderer = $PAGE->get_renderer('surveyactivity_employengage');

    $renderer->get_smarty()->assign('dim1',$dim1);
    $renderer->get_smarty()->assign('dim1s',$dim1s);
    $renderer->get_smarty()->assign('dim2',$dim2);
    $renderer->get_smarty()->assign('dim2s',$dim2s);
    $renderer->get_smarty()->assign('surveyid',$surveyid);
    $renderer->get_smarty()->assign('radarsUrl',$radarsUrl->out());
    $renderer->get_smarty()->assign('barsUrl',$barsUrl->out());
    $renderer->get_smarty()->assign('downloadpdf',$downloadpdf);
    $renderer->get_smarty()->assign('iscomplete',$iscomplete);


    echo $renderer->header();

    echo $renderer->render_activity_reports($wrapper);

    echo $renderer->footer();
}else{

    /** @var surveyactivity_employengage_renderer*/
    $renderer = $PAGE->get_renderer('surveyactivity_employengage');
    $renderer->get_smarty()->assign('iscomplete',$iscomplete);

    echo $renderer->header();

    echo $renderer->render_activity_reports($wrapper);

    echo $renderer->footer();

}

