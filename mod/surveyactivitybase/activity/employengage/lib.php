<?php
namespace wmios\survey {
    use \MoodleQuickForm,\moodle_url,\stdClass;
    require_once($CFG->dirroot.'/mod/surveyactivitybase/lib/survey_client.php');
    require_once($CFG->dirroot.'/mod/surveyactivitybase/lib.php');
    require_once(dirname(__FILE__).'/locallib.php');
    require_once($CFG->dirroot.'/mod/surveyactivitybase/lib/fpdf/fpdf.php');

    require_once($CFG->dirroot.'/mod/surveyactivitybase/lib/pcharts/pData.class.php');
    require_once($CFG->dirroot.'/mod/surveyactivitybase/lib/pcharts/pDraw.class.php');
    require_once($CFG->dirroot.'/mod/surveyactivitybase/lib/pcharts/pRadar.class.php');
    require_once($CFG->dirroot.'/mod/surveyactivitybase/lib/pcharts/pImage.class.php');

    define('FPDF_FONTPATH','font/');
    class activity_employengage extends activity{
        protected $_instance = null;

        public static function extend_nav(\navigation_node $nav, activity_wrapper $wrapper = null)
        {
            return true;
        }

        /**
        * 判断是否完成调查
        * @return bool
        */
        public function check_complete()
        {
            global $DB;
            $status = $this->get_instance()->status;
            $endtime = $this->_wrapper->endtime;

            if($status == activity_employengage_instance::STATUS_EMPLOYENGAGE_COMPLETE )
            {
                return true;    
            }
            elseif ($status == activity_employengage_instance::STATUS_EMPLOYENGAGE_UNCOMPLETE)
            {

                $reportsdata = $this->get_reportsdata();  
                if(!$reportsdata)
                {
                    // $this->get_instance()->status = activity_employengage_instance::STATUS_EMPLOYENGAGE_WRONG;
                    //  $this->get_instance()->save();
                    return false;

                } 

                if($endtime < time())
                {
                    //更新status
                    $this->get_instance()->status = activity_employengage_instance::STATUS_EMPLOYENGAGE_COMPLETE;
                    if($this->get_instance()->save())
                    {
                        return true;
                    }else{
                        throw new \moodle_exception("wrong");
                    }

                }elseif ($endtime > time())
                {

                    $ct = $DB->get_field_sql('select COUNT(`status`) from {surveyactivity_employ_users} where `surveyid`='.$this->get_instance()->survey_id.' and `status`= 0');
                    if($ct == 0)
                    {
                        $this->get_instance()->status = activity_employengage_instance::STATUS_EMPLOYENGAGE_COMPLETE;
                        if($this->get_instance()->save())
                        {
                            return true;
                        }else
                        {
                            throw new \moodle_exception("wrong");
                        }

                    }else{
                        return false;
                    }

                }

            }
            elseif ($status == activity_employengage_instance::STATUS_EMPLOYENGAGE_WRONG) 
            {

                throw new \moodle_exception("wrong");
            }

        }

        public static function process_update_form(MoodleQuickForm $mform,activity_wrapper $activity_wrapper = null)
        {
            global $DB;
            if(!$activity_wrapper || $activity_wrapper->is_new())
            {
                $filepickeroptions = array('accepted_types' => '.csv','return_types'=>FILE_INTERNAL);
                $mform->addElement('filepicker','userfile',get_string('userfile',SURVEYACTIVITYBASE_PLUGIN_NAME), null,$filepickeroptions);   
            }
            $str = '<table cellspacing="0">';
            $str.= '<thead><tr>';
            $str.= '<th class="header c0" scope="col">选择<div class="commands"></div></th>';
            $str.= '<th class="header c2" scope="col">姓名<div class="commands"></div></th>';
            $str.= '<th class="header c3" scope="col">Email地址 <div class="commands"></div></th>';
            $str.= '<th class="header c4" scope="col">地区<div class="commands"></div></th>';
            $str.= '</tr></thead>';
            $str.= '<tbody>';

            $usersobject = $DB->get_records_sql('select * from {user}');
            foreach($usersobject as $usersdata)
            {
                $str.= '<tr class="r1">
                <td class="cell c0"><input type="checkbox" class="usercheckbox" name="userid[]" value="'.$usersdata->id.'"></td>
                <td class="cell c2" align="center"><strong>'.$usersdata->firstname.$usersdata->lastname.'</strong></td>
                <td class="cell c3" align="center">'.$usersdata->email.'</td>
                <td class="cell c4" align="center">'.$usersdata->city.'</td></tr>';
            }          

            $str.= '</tbody></table>';

            $mform->addElement('html',$str);
            $mform->addElement('checkbox','userid');
        }

        public static function get_add_instance_default_data(){
            return array();
        }

        /**
        * @return activity_employengage_instance
        * 
        */
        public function get_instance()
        {
            if($this->_instance == null)
            {
                $this->_instance = activity_employengage_instance::instance_from_id($this->_wrapper->instance_id);
            } 
            return $this->_instance;
        }

        /**
        * @return  activity_employengage_users_instance
        * 
        */
        public function get_usersinstance()
        {
            if($this->_instance == null)
            {
                $this->_instance = activity_employengage_users_instance::instance_from_id($this->_wrapper->instance_id);
            } 
            return $this->_instance;
        }

        public static function add_instance(stdClass $general, stdClass $special, moodleform_activity $mform)
        {
            global $DB, $CFG;

            //导入用户信息
            $users = self::process_survey_users_from_userfile($mform, true);
            //从limesurvey复制调查
            $url = get_config(SURVEYACTIVITYBASE_PLUGIN_NAME,'uri');
            $username = get_config(SURVEYACTIVITYBASE_PLUGIN_NAME,'username');
            $password = get_config(SURVEYACTIVITYBASE_PLUGIN_NAME,'password');

            $surveyclient = new \survey_client($url,$username,$password);
            $file = $CFG->dirroot.'/mod/surveyactivitybase/activity/employengage/file/limesurvey_survey.lss';
            $type = 'lss';
            $name = 'mytests';

            $new_survey_id = $surveyclient->copy_survey($file,$type,$name);
            if($new_survey_id)
            {                
                $us = array();
                $forms = $mform->get_data();
                if(isset( $forms->userid))
                {
                    $arrs =array();
                    $userarr=$forms->userid;
                    foreach($userarr as $userdatas)
                    {
                        $rows=$DB->get_record_sql('select * from {user} where id ='.$userdatas);
                        $arrs['firstname'] = $rows->firstname;
                        $arrs['lastname'] = $rows->lastname;
                        $arrs['email'] =  $rows->email;
                        $arrs['city'] = $rows->city;
                        $arrs['token'] = rand(100000,999999);
                        $us[] = $arrs;
                    }
                }

                //初始化limesurvey操作代码表/limesurvey添加用户并且将用户信息存入MOODLE数据库
                if($surveyclient->init_tokens($new_survey_id))
                {
                    $arr = array();

                    foreach($users as $d)
                    {
                        $arr['email'] = $d['email'];
                        $arr['firstname'] =iconv('GB2312','UTF-8', $d['firstname']);
                        $arr['lastname'] = iconv('GB2312','UTF-8',$d['lastname']);
                        $arr['token'] = rand(100000,999999);
                        $arr['dplevel_one'] = iconv('GB2312','UTF-8',$d['dplevel_one']);
                        $arr['dplevel_two'] = iconv('GB2312','UTF-8',$d['dplevel_two']); 
                        $arr['dplevel_three'] = iconv('GB2312','UTF-8',$d['dplevel_three']);
                        $arr['dplevel_four'] = iconv('GB2312','UTF-8',$d['dplevel_four']);
                        $arr['dplevel_five'] = iconv('GB2312','UTF-8',$d['dplevel_five']);
                        $arr['dplevel_six'] = iconv('GB2312','UTF-8',$d['dplevel_six']);
                        $arr['dplevel_seven'] = iconv('GB2312','UTF-8',$d['dplevel_seven']);
                        $arr['dplevel_eight'] = iconv('GB2312','UTF-8',$d['dplevel_eight']);
                        $arr['dplevel_nine'] = iconv('GB2312','UTF-8',$d['dplevel_nine']);
                        $arr['dplevel_ten'] = iconv('GB2312','UTF-8',$d['dplevel_ten']);
                        $arr['position'] = iconv('GB2312','UTF-8',$d['position']);
                        $arr['age'] = $d['age'];
                        $arr['provinces'] = iconv('GB2312','UTF-8',$d['provinces']);
                        $arr['city'] = iconv('GB2312','UTF-8',$d['city']);
                        $arr['gender'] = iconv('GB2312','UTF-8',$d['gender']);
                        $us[] = $arr;
                    }
                    //limesurvey添加用户
                    if($cheng=$surveyclient->add_usersurvey($new_survey_id,$us))
                    {
                        //用户信息存数据库
                        foreach($us as $data)
                        {
                            $data['surveyid'] = $new_survey_id;
                            $userinstance = new activity_employengage_users_instance($data);

                            $bool=$userinstance->save();
                            if(!$bool)
                            {
                                throw new \moodle_exception("wrong");
                            }
                        }
                    }
                    else
                    {
                        throw new \moodle_exception("wrong");
                    }
                }
                else
                {
                    throw new \moodle_exception("wrong");

                }

                $data = array('survey_id'=>$new_survey_id);
                $instance = new activity_employengage_instance($data);
                $instance->save();
                return $instance->id;

            }
            else
            {
                throw new \moodle_exception("wrong");

            }

        }

        public function update_instance(stdClass $general, stdClass $special, moodleform_activity $mform){
            if($this->_wrapper->is_new())
            {
                $users = self::process_survey_users_from_userfile($mform, false);
                if($users)
                {
                    //push users to survey server.
                }
                //post the date time ,survey name, survey intro,users to server.
                //get the result ,return the result,true or false.
                //throw exception
            }
            return true;
        }

        protected static function process_survey_users_from_userfile(moodleform_activity $mform, $force = false)
        {
            $users = array();

            $userfile = $mform->get_file_content('userfile');
            if(!strlen($userfile))
            {
                if($force)
                {
                    throw new \Exception('user file need content 1');
                }else{
                    return $users;
                }
            }

            $lines = explode("\n",$userfile);
            if(count($lines)<2)
            {
                if($force)
                {
                    throw new \Exception('user file need content 2');
                }else{
                    return $users;
                }
            }
            $ks = explode(',',trim($lines[0],"\r\n, "));
            for($i=1;$i<count($lines);$i++)
            {
                if($lines[$i] == "")continue;
                $user = array();
                $vs = explode(',', trim($lines[$i],"\r"));
                foreach($vs as $k=> $v)
                {
                    $user[trim($ks[$k],"'\n\" ")] = trim($v,"'\n\" ");
                }
                $users[] = $user;
            }
            return $users;
        }

        /**
        * 将csv文件转换成数组
        * 
        * @param mixed $userfile
        * @param mixed $force
        * @return array
        */
        public function survey_reports($userfile, $force = false)
        {

            $users = array();

            // $userfile = $mform->get_file_content('userfile');

            if(!strlen($userfile))
            {
                if($force)
                {
                    throw new \Exception('user file need content 1');
                }else{
                    return $users;
                }
            }

            $lines = explode("\n",$userfile);
            if(count($lines)<2)
            {
                if($force)
                {
                    throw new \Exception('user file need content 2');
                }else{
                    return $users;
                }
            }
            $ks = explode(',',trim($lines[0],"\r\n, "));
            for($i=1;$i<count($lines);$i++)
            {
                if($lines[$i] == "")continue;
                $user = array();
                $vs = explode(',', trim($lines[$i],"\r"));
                foreach($vs as $k=> $v)
                {
                    $user[trim($ks[$k],"'\n\" ")] = trim($v,"'\n\" ");
                }
                $users[] = $user;
            }
            return $users;

        }

        public static function delete_instance($id){
            return true;
        }

        public function get_update_instance_data(){
            return array('dd'=>time());
        }

        public function get_view_url(){
            return new moodle_url('/mod/surveyactivitybase/activity/employengage/detail.php',array('id'=>$this->_wrapper->id,'cmid'=>$this->_wrapper->cmid));

        }

        public function start_get_view_url(){
            return new moodle_url('/mod/surveyactivitybase/view.php',array('id'=>$this->_wrapper->cmid));
        }

        /**
        *
        * True if this type has a global report.
        *
        * @return bool
        *
        */
        public function has_global_report()
        {
            return true;
        }

        /**
        * 开始调查
        * @return bool
        */
        public function start()
        {
            @set_time_limit(300);
            //start survey
            $url = get_config(SURVEYACTIVITYBASE_PLUGIN_NAME,'uri');
            $username = get_config(SURVEYACTIVITYBASE_PLUGIN_NAME,'username');
            $password = get_config(SURVEYACTIVITYBASE_PLUGIN_NAME,'password');

            $surveyclient= new \survey_client($url,$username,$password);

            $surveyid = $this->get_instance()->survey_id;
            //激活limesurvey中的调查
            $active = $surveyclient->active_survey($surveyid);

            if($active)
            {
                //邀请用户参与发送邮件
                $access=$surveyclient->invite_users($surveyid);

                if($access)
                {
                    return true;  

                }
            }else{

                return false;
            }

        }


        public function stop()
        {
            //stop survey
            return true;
        }


        public function get_global_report_view_url()
        {

            return new moodle_url('/mod/surveyactivitybase/activity/employengage/report.php',array('id'=>$this->_wrapper->id,'cmid'=>$this->_wrapper->cmid));
        }


        public function get_global_report_download_url()
        {

            return new moodle_url('/mod/surveyactivitybase/activity/employengage/report.php',array('id'=>$this->_wrapper->id,'cmid'=>$this->_wrapper->cmid));

        }

        /**
        * 调查报告的信息存到数据库
        * 
        * @param mixed $data
        * @return static
        */
        public function activity_instance_updates($data)
        {

            $str = $data['操作代码'];
            $query = "token ='".$str."'";
            $rowobj = activity_employengage_users_instance::instance_from_select($query);

            unset($data['id']);
            return $rowobj->setFields($data)->save();

        }       

        /**
        * 调查报告的计算分数
        * 
        * @param mixed $arr
        * @return array[][]
        */
        public function reports_results($arr)
        {
            $summary = array();
            $sum = array();
            $statics = array();
            $avg = array();
            $count = count($arr);
            foreach($arr as $d)
            {
                foreach($d as $k=>$v)
                {
                    if(!isset($sum[$k]))
                    {
                        $sum[$k] = 0;    
                    }
                    if(preg_match('/^q\d*/',$k,$matches))
                    {
                        foreach($matches as $key)
                        {
                            switch($d[$key])
                            {
                                case 1:$v = "5"; break;
                                case 2:$v = "4"; break;
                                case 3:$v = "3"; break;
                                case 4:$v = "2"; break;
                                case 5:$v = "1"; break;

                            }

                        }

                    }
                    $sum[$k] += $v;
                }
                $newk = sprintf('%.1f',$d['averages']);

                if(!isset($statics[$newk]))
                {
                    $statics[$newk] = 0;
                }

                $statics[$newk]++;
            }
            foreach($sum as $keys=>$vs)
            {
                $avg[$keys] = $vs/$count;
            }

            if(count($avg)>0)
            {
                $summary['avg'] = $avg;
            }
            if(count($statics)>0)
            {
                $summary['statics'] = $statics;
            }

            return $summary;
        }

        /**
        * 按条件查询调查报告
        * 
        * @param mixed $str
        * @return array[][]
        */
        public function users_reports($str=null)
        {
            $rows = array();
            $query = "surveyid ='".$this->get_instance()->survey_id."' ";
            if($str)
            {
                $query.=' and '.$str;
            }
            $rowarr = activity_employengage_users_instance::instances_from_select($query);
            if($rowarr)
            {
                foreach($rowarr as $dataobj)
                {
                    $rows[] = $dataobj->getFields();
                } 

            } 
            return $rows;

        }

        /**
        * 获取性别为男或女
        * @return array()
        */
        public function get_genders()
        {
            $rows = array();
            global $DB;
            $records = $DB->get_records_sql('select DISTINCT gender from {surveyactivity_employ_users} where `surveyid`='.$this->get_instance()->survey_id);

            foreach($records as $k =>$v)
            {
                $rows[]=$k;
            }
            return $rows;

        }

        /**
        * 部门查询
        * 
        * @param mixed $records array()
        * @return array
        */
        public function check_department($records)
        {
            foreach($records as $record)
            {
                foreach(array('one','two','three','four','five','six','seven','eight','nine','ten') as $i)
                {
                    $k = 'dplevel_'.$i;
                    if(empty($record[$k]))
                    {
                        break;
                    }
                    $cmd = '$dim2s';
                    foreach(array('one','two','three','four','five','six','seven','eight','nine','ten') as $j)
                    {              
                        $cmd .= '[\''.$record['dplevel_'.$j].'\']';
                        if($i == $j)
                        {
                            break;
                        }
                    }
                    eval(
                        'if(!isset('.$cmd.'))
                        {
                        '.$cmd.'=array();
                        }'
                    );

                }
            }
            return $dim2s;
        }

        /**
        * 地区查询
        * 
        * @param mixed $records array()
        * @return array
        */
        public function check_provinces($records)
        {  
            foreach($records as $record)
            {
                $city[] = $record['city'];         

                foreach($city as $v)
                {
                    $k = $record['provinces'];
                    if(empty($k))
                    {
                        break;
                    }
                    if($v == $record['city'])
                    {
                        $rows[$k][$v] = array();
                    }

                }
            }
            return $rows;
        }

        /**
        * 生成bar图表
        * 
        * @param mixed $arrbar
        * @param mixed $param
        * @param mixed $param2
        * @return \stored_file
        */
        public function check_chartbars($arrbar,$param,$param2)
        {
            global $CFG;

            ksort($arrbar);
            $pathes = array($this->get_instance()->survey_id, $param);


            if(!empty($param2))
            {
                if(!is_array($param2))
                {
                    $param2 = array($param2);
                }
                $pathes = array_merge($pathes,$param2);
            }
            $filepath = '/'.implode('/',$pathes).'/';

            $filename = 'bar.png';

            $fs = get_file_storage();

            $fileinfo = array(
                'contextid' => $this->_wrapper->get_base()->get_context()->id, // ID of context
                'component' => 'surveyactivity_employengage',     // usually = table name
                'filearea' => 'report',     // usually = table name
                'itemid' => 0,               // usually = ID of row in table
                'filepath' => $filepath,           // any path beginning and ending in /
                'filename' => $filename); // any filename
            $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],$fileinfo['itemid'],$fileinfo['filepath'], $fileinfo['filename']);
            if($file !== false )
            {
                return $file;
            }


            /* Create and populate the pData object */
            $barData = new \pData();  

            foreach($arrbar as $k=>$v)
            {
                $barData->addPoints(array($v),"Serie1");
                $barData->addPoints(array($k),"Serie2");

            }

            $barData->setAxisName(0,"人数");

            $barData->setAbscissa("Serie2");

            $barData->setAbscissaName("分数");
            //$barData->setAbscissa("aa");

            /* Create the pChart object */
            $bar = new \pImage(700,230,$barData);

            /* Turn of Antialiasing */
            $bar->Antialias = FALSE;

            /* Set the default font */
            $bar->setFontProperties(array("FontName"=>"Fonts/simhei.ttf","FontSize"=>8,"R"=>41,"G"=>36,"B"=>33,));

            /* Define the chart area */
            $bar->setGraphArea(60,40,650,200);

            /* Draw the scale */
            $scaleSettings = array("AxisR"=>41,"AxisG"=>36,"AxisB"=>33,"Mode"=>SCALE_MODE_START0,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
            $bar->drawScale($scaleSettings);

            /* Turn on shadow computing */ 
            $bar->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

            /* Draw the chart */
            $bar->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
            $settings = array("DisplayValues"=>TRUE,"Surrounding"=>30,"DisplayR"=>41,"DisplayG"=>36,"DisplayB"=>33,);
            $bar->drawBarChart($settings);

            //生成图表
            $bar_tmp_dir = $CFG->tempdir.'/picture/';
            if(!is_dir($bar_tmp_dir))
            {
                mkdir($bar_tmp_dir);     
            }

            $imageFile = $bar_tmp_dir.$filename; 

            $bar->render($imageFile); 

            // Create file containing text 'hello world'
            return $fs->create_file_from_pathname($fileinfo, $imageFile);
        }

        /**
        * 生成radar图
        * 
        * @param mixed $namearr
        * @param mixed $arr
        * @param mixed $param
        * @param mixed $param2
        * @return \stored_file
        */
        public function check_chartradars($namearr,$arr,$param,$param2)
        {
            global $CFG;

            $pathes = array($this->get_instance()->survey_id, $param);


            if(!empty($param2))
            {
                if(!is_array($param2))
                {
                    $param2 = array($param2);
                }
                $pathes = array_merge($pathes,$param2);
            }
            $filepath = '/'.implode('/',$pathes).'/';

            $filename = 'radar.png';

            $fs = get_file_storage();

            $fileinfo = array(
                'contextid' => $this->_wrapper->get_base()->get_context()->id, // ID of context
                'component' => 'surveyactivity_employengage',     // usually = table name
                'filearea' => 'report',     // usually = table name
                'itemid' => 0,               // usually = ID of row in table
                'filepath' => $filepath,           // any path beginning and ending in /
                'filename' => $filename); // any filename
            $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],$fileinfo['itemid'],$fileinfo['filepath'], $fileinfo['filename']);
            if($file !== false )
            {
                return $file;
            }

            /* Create and populate the pData object */
            $radarData = new \pData();   
            $radarData->addPoints($arr,"ScoreA");  

            /* Define the absissa serie */
            $radarData->addPoints($namearr,"Labels");
            $radarData->setAbscissa("Labels");

            /* Create the pChart object */
            $radar = new \pImage(400,400,$radarData);


            /* Overlay some gradient areas */
            $Settings = array("StartR"=>194, "StartG"=>231, "StartB"=>44, "EndR"=>43, "EndG"=>107, "EndB"=>58, "Alpha"=>80);
            $radar->drawGradientArea(0,0,400,400,DIRECTION_VERTICAL,$Settings);


            /* Set the default font properties */ 
            $radar->setFontProperties(array("FontName"=>"Fonts/simhei.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));

            /* Enable shadow computing */ 
            $radar->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

            /* Create the pRadar object */ 
            $SplitChart = new \pRadar();

            /* Draw a radar chart */ 
            $radar->setGraphArea(48,5,350,370);
            $Options = array("FixedMax"=>5,"Layout"=>RADAR_LAYOUT_STAR,"LabelPos"=>RADAR_LABELS_HORIZONTAL,"BackgroundGradient"=>array("StartR"=>255,"StartG"=>255,"StartB"=>255,"StartAlpha"=>100,"EndR"=>88,"EndG"=>87,"EndB"=>86,"EndAlpha"=>50), "FontName"=>"fonts/simhei.ttf","FontSize"=>6);
            $SplitChart->drawRadar($radar,$radarData,$Options);


            $radar_tmp_dir = $CFG->tempdir.'/picture/';
            if(!is_dir($radar_tmp_dir))
            {
                mkdir($radar_tmp_dir);     
            }

            $imageFile = $radar_tmp_dir.$filename; 

            $radar->render($imageFile); 


            return $fs->create_file_from_pathname($fileinfo, $imageFile);

        }

        /**
        * 生成PDF
        * 
        * @param mixed $radars
        * @param mixed $bars
        * @param mixed $param
        * @param mixed $param2
        * @return \stored_file
        */
        public function check_reportspdf($radars,$bars,$param,$param2)
        {
            global $CFG;

            $pathes = array($this->get_instance()->survey_id, $param);

            if(!empty($param2))
            {
                if(!is_array($param2))
                {
                    $param2 = array($param2);
                }
                $pathes = array_merge($pathes,$param2);
            }
            $filepath = '/'.implode('/',$pathes).'/';
            $fname = implode('-',$pathes);
            $filename = $fname.'-reports.pdf';

            $fs = get_file_storage();

            $fileinfo = array(
                'contextid' => $this->_wrapper->get_base()->get_context()->id, 
                'component' => 'surveyactivity_employengage',    
                'filearea' => 'report',     
                'itemid' => 0,               
                'filepath' => $filepath,          
                'filename' => $filename);

            $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],$fileinfo['itemid'],$fileinfo['filepath'], $fileinfo['filename']);

            if($file !== false )
            {
                return $file;
            }

            $pdfdir = $CFG->tempdir.'/pdf/';
            if(!is_dir($pdfdir))
            {
                mkdir($pdfdir);     
            }
            $imgdir = $CFG->tempdir.'/picture/';

            $pdf = new \FPDF('P','mm','A4'); 
            $pdf->Open();

            $pdf->AddPage();  
            $pdf->SetFont('Courier','I',20); 

            $pdf->Image($imgdir.$radars->get_filename(),50,20,100,80);
            $pdf->Image($imgdir.$bars->get_filename(),30,120,150,80);

            $filename = iconv('UTF-8','GB2312',$filename);

            $pdfpathname = $pdfdir.md5($filepath).$filename;

            $pdf->Output($pdfpathname,"F");

            return $fs->create_file_from_pathname($fileinfo, $pdfpathname);
        }

        /**
        * 从limesurvey获取整个调查报告csv文件
        * @return string
        */
        public function get_limesurveyreports()
        {
            $surveyr_text = '' ;
            $url = get_config(SURVEYACTIVITYBASE_PLUGIN_NAME,'uri');
            $username = get_config(SURVEYACTIVITYBASE_PLUGIN_NAME,'username');
            $password = get_config(SURVEYACTIVITYBASE_PLUGIN_NAME,'password');

            $surveyclient = new \survey_client($url,$username,$password);
            $surveyid = $this->get_instance()->survey_id;

            $doctype = "csv";
            $reportstext = $surveyclient->export_results($surveyid,$doctype);
            if(is_array($reportstext)){
                return $surveyr_text;
            }else{
                return $surveyr_text = base64_decode($reportstext);
            }

        }

        /**
        * 获取报告数据存入数据库
        * @return bool
        */
        public function get_reportsdata()
        {
            $bool = false;
            $surveyr_text = $this->get_limesurveyreports();
            if(!empty($surveyr_text))
            {
                $reports = $this->survey_reports($surveyr_text,true);
                foreach($reports as $data)
                {
                    $data['status'] = activity_employengage_instance::STATUS_EMPLOYENGAGE_COMPLETE;
                    $bool = $this->activity_instance_updates($data);

                }
            }

            return $bool;
        }

    }
}
namespace {

    /**
    * file serving callback
    *
    * 
    * @package  mod_surveyactivitybase_employengage
    * @category files
    * @param stdClass $course course object
    * @param stdClass $cm course module object
    * @param stdClass $context context object
    * @param string $filearea file area
    * @param array $args extra arguments
    * @param bool $forcedownload whether or not force download
    * @param array $options additional options affecting the file serving
    * @return bool false if the file was not found, just send the file otherwise and do not return anything
    */
    function surveyactivity_employengage_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) 
    {
        global $CFG;

        if ($context->contextlevel != CONTEXT_MODULE) {
            return false;
        }

        require_login($course, true, $cm);

        if ($filearea == 'report') {

            $relativepath = implode('/', $args);

            $fullpath = "/$context->id/surveyactivity_employengage/report/$relativepath";

            $fs = get_file_storage();
            if (!$file = $fs->get_file_by_hash(sha1($fullpath)) or $file->is_directory()) {
                return false;
            }

            $lifetime = isset($CFG->filelifetime) ? $CFG->filelifetime : 86400;

            send_stored_file($file, $lifetime, 0, $options);
        }
    }
}