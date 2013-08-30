<?php
include_once($CFG->dirroot . "/local/wmios/renderer.php");

class theme_udemy_local_wmios_renderer extends local_wmios_renderer{
    public function login_page($errorcode = 0){
        return <<<EOD
<div id="top-section" class="v3-home-v2 ud-page" data-page-name="redirect-ipad-to-app">
  <hgroup id="headings">
    <h2>和顶级教师一起</h2>
    <h1>学习如何管理</h1>
  </hgroup>
  <div id="auth-popup" class="signup-login-panel on">
    <div id="login">
      <form id="login-form" name="login-form" action="/login/" method="post" class="ud-formajaxify">
        <input type="hidden" name="isSubmitted" value="1">
        <div class="auth-form">
          <div class="or">登录经理人学院</div>
          <div class="fields">
            <div class="form-item email">
              <input id="email" name="username" type="text" class="text-input  " placeholder="用户名">
              <span class="error-text"> </span> </div>
            <div class="form-item password">
              <input id="password" name="password" type="password" class="text-input  " placeholder="密码">
              <span class="error-text"> </span> </div>
            <div class="form-errors"></div>
          </div>
        </div>
        <div class="form-bottom">
          <button class="login-btn btn btn-success btn-small" type="submit"> 登录 </button>
          <a href="#" class="forgot"> 忘记密码? </a> </div>
      </form>
    </div>
  </div>
  <div id="video">

  </div>
  <div id="ref"></div>
  <div id="ipad-promo"> <a href=""></a> </div>
</div>
EOD;
    }
}