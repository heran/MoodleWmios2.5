<?php
require_once dirname ( __FILE__ ) . '/survey_client.php';
require_once dirname ( __FILE__ ) . '/userinfo.php';
use wmios\survey as survey;

class surveyactivity_simple_renderer extends survey\plugin_renderer_base{
   public function test(){
       $smarty = $this->_smarty;
       $smarty->assign('users','添加参与人员');

       return $smarty->fetch('test.tpl');
   }
}
