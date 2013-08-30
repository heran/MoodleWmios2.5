<?php
require_once dirname ( __FILE__ ) . '/jsonRPCClient.php';
class survey_client {

    /**
    添加用户的信息
    @var 二维array
    */
    private $userinfo;

    /**
    * 调查编号
    * @var int
    */
    private $surveyid;

    /**
    * 令牌
    * @var boolean
    */
    private $tokenkey;

    /**
    *文档的类型
    *@var string
    */
    private $doctype;

    /**
    * 图形
    * 
    * @var int  /0 or 1
    */
    private $surveygragh;

    /**
    * 
    * 语言
    * @var string
    */
    private $surveylanguage;

    /**
    * 完成的状态选项
    * @var string
    */
    private $statname;

    /**
    * 调查相关属性
    * @var array
    */
    private $surveyproarr;

    /**
    * 导入数据路径
    * @var string
    */
    private $csurveydata;

    /**
    * 导入数据类型
    * @var string  /lss,csv,xls,zip
    */
    private $csurveydatatype;

    /**
    * 设置调查的名字
    * @var string
    */
    private $csurveyname;

    /**
    * 导入调查设置的序号
    * @var int
    */
    private $csurveyid;

    /**
    * jsonRPCClient实例对象
    *
    * @var  object
    */
    static $surveyclient = null;

    /**
    * sessionkey
    *
    * @var string
    */
    static $sessionkey = null;

    /**
    * 连接URL
    *
    * @var string
    */
    private $clienturl;

    /**
    * 用户名
    * @var string
    */
    private $username;

    /**
    * 密码
    * @var string
    */
    private $password;

    /**
    * 
    * 提醒天数
    * @var int
    */
    private $mindays;

    /**
    * 
    * 提醒次数
    * @var int
    */
    private $maxnum;

    /**
    * 
    * 
    * @var string
    */
    private $langcode;
    /**
    * 
    * 
    * @var string
    */
    private $completionstatus;
    /**
    * 
    * 
    * @var string
    */
    private $headingtype;
    /**
    * 
    * 
    * @var string
    */
    private $responsetype; 
    /**
    * 
    * 
    * @var int
    */
    private $fromresponse;
    /**
    * 
    * 
    * @var int
    */
    private $toresponse;

    /**
    * 构造方法
    *
    * @param string $clienturl
    * @param string $username
    * @param string $password
    */
    public function __construct($clienturl, $username, $password) {
        $this->clienturl = $clienturl;
        $this->username = $username;
        $this->password = $password;
        if (self::$surveyclient == null) {
            self::$surveyclient = new jsonRPCClient ( $this->clienturl );
            self::$sessionkey = self::$surveyclient->get_session_key ( $this->username, $this->password );
        }
    }

    /**
    * 添加调查参与者
    * @param int    $surveyid
    * @param 2array $userinfo
    * @param bloean $tokenkey
    * @return String[]
    */
    public function add_usersurvey($surveyid, $userinfo, $tokenkey =false) {
        if (empty ( $surveyid ) || empty ( $userinfo )) {
            return false;
        }
        $this->surveyid = $surveyid;
        $this->tokenkey = $tokenkey;
        $this->userinfo = $userinfo;
        return self::$surveyclient->add_participants ( self::$sessionkey, $this->surveyid, $this->userinfo, $this->tokenkey );

    }


    /**
    * 邀请参与者加入
    * @param int $surveyid
    * @throws Exception
    * @return String[]
    */
    public function invite_users($surveyid) {
        if (empty ( $surveyid )) {
            return false;
        }
        $this->surveyid = $surveyid;
        return self::$surveyclient->invite_participants ( self::$sessionkey, $this->surveyid );
    }


    /**
    * 提醒参与者
    * @param int $surveyid
    * @param int $mindays
    * @param int $maxnum
    * @return String[]
    */
    public function remind_users($surveyid, $mindays = null, $maxnum = null) {
        if (empty ( $surveyid )) {
            return false;
        }
        $this->surveyid = $surveyid;
        if (! empty ( $mindays )) {
            $this->mindays = $mindays;
        }
        if (! empty ( $maxnum )) {
            $this->maxnum = $maxnum;
        }
        return self::$surveyclient->remind_participants ( self::$sessionkey, $this->surveyid, $this->mindays, $this->maxnum );

    }

    /**
    * 调查的完成状态
    * @param int $surveyid
    * @param string $statname
    * @return  String Base64     
    */
    public function survey_state($surveyid, $statname) {
        if (empty ( $surveyid ) || empty ( $statname )) {
            return false;
        }
        $this->surveyid = $surveyid;
        $this->statname = $statname;
        return self::$surveyclient->get_summary ( self::$sessionkey, $this->surveyid, $this->statname );
    }

    /**
    * 统计结果
    * @param int $surveyid
    * @param string $doctype
    * @param string $surveylanguage
    * @param int $surveygragh
    * @return String Base64
    */
    public function survey_summary($surveyid, $doctype, $surveylanguage = null, $surveygragh = null) {
        if (empty ( $surveyid ) || empty ( $doctype )) {
            return false;
        }
        if (! empty ( $surveylanguage )) {
            $this->surveylanguage = $surveylanguage;
        }
        if (! empty ( $surveygragh )) {
            $this->surveygragh = $surveygragh;
        }
        $this->doctype = $doctype;
        $this->surveyid = $surveyid;
        return self::$surveyclient->export_statistics ( self::$sessionkey, $this->surveyid, $this->doctype,$this->surveylanguage,$this->surveygragh);
    }

    /**
    * 获得调查信息
    * @param int $surveyid
    * @param array $surveyproarr
    * @throws Exception
    * @return String
    */
    public function get_surveyinfo($surveyid,$surveyproarr){
        if(empty($surveyid) || empty($surveyproarr)){
            return false;		
        }
        $this->surveyid=$surveyid;
        $this->surveyproarr=$surveyproarr;
        return self::$surveyclient->get_survey_properties(self::$sessionkey,$this->surveyid,$this->surveyproarr);
    }

    /**
    * 复制调查
    * @param string $csurveydataurl
    * @param string $csurveydatatype
    * @param string $csurveyname
    * @param int $csurveyid
    * @return int
    */
    public function copy_survey($csurveydata,$csurveydatatype,$csurveyname=null,$csurveyid=null){
        if(empty($csurveydata) || empty($csurveydatatype)){
            return false;
        }
        $this->csurveydata=base64_encode(file_get_contents($csurveydata));
        $this->csurveydatatype=$csurveydatatype;
        if(!empty($csurveyid)){
            $this->csurveyid=$csurveyid;

        }

        if(!empty($csurveyname)){
            $this->csurveyname=$csurveyname;
        }
        return self::$surveyclient->import_survey(self::$sessionkey,$this->csurveydata,$this->csurveydatatype,$this->csurveyname,$this->csurveyid);
    }

    /**
    * 初始化操作代码表
    * @param int $surveyid
    * @return array()
    * 
    * 
    */
    public function init_tokens($surveyid){
        if(empty($surveyid)){
            return false;
        }

        $this->surveyid = $surveyid;

        return self::$surveyclient->activate_tokens(self::$sessionkey,$this->surveyid);
    }

    /**
    * 统计报告
    * @param int $surveyid
    * @param string $doctype
    * @param string $langcode
    * @param string $completionstatus
    * @param string $headingtype
    * @param string $responsetype  
    * @param int  $fromresponse 
    * @param int $toresponse
    * @return string (64-encoded) 
    */
    public function export_results($surveyid,$doctype,$langcode=null,$completionstatus=null,$headingtype=null,$responsetype=null,$fromresponse=null,$toresponse=null ){
        if(empty($surveyid) || empty($doctype)){
            return false;
        }
        
        $this->surveyid = $surveyid;
        $this->doctype = $doctype;
        
        if(!empty($langcode))
        {
            $this->langcode = $langcode;
        }
        if(!empty($completionstatus))
        {
            $this->completionstatus = $completionstatus;
        }
        if(!empty($headingtype))
        {
            $this->headingtype = $headingtype;
        }
        if(!empty($responsetype))
        {
            $this->responsetype = $responsetype;
        }
        if(!empty($fromresponse))
        {
            $this->fromresponse = $fromresponse;
        }
        if(!empty($toresponse))
        {
            $this->toresponse = $toresponse;
        }

        return self::$surveyclient->export_responses(self::$sessionkey,$this->surveyid,$this->doctype,$this->langcode,$this->completionstatus,$this->headingtype,$this->responsetype,$this->fromresponse,$this->toresponse);
    }
    
    /**
    * 激活调查
    * 
    * @param mixed $surveyid
    */
    public function active_survey($surveyid){
        if(empty($surveyid)){
            return false;
            
        }
        $this->surveyid = $surveyid;
       return self::$surveyclient->activate_survey(self::$sessionkey,$this->surveyid);
        
    }

    /**
    * 调用survey_client类中没写的方法
    * 
    * @param mixed $methods
    * @param mixed $args
    */
    public function __call($methods,$args){
        return self::$surveyclient->__call($methods,$args);
    }

}



