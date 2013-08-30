<?php
//Added By Heran At 2013-07-29 16:03
require_once(dirname(__FILE__) . '/../phpCAS/CAS.php');
phpCAS::setDebug();
phpCAS::client(CAS_VERSION_2_0, '211.167.112.186', 9501, '');
phpCAS::setNoCasServerValidation();
phpCAS::handleLogoutRequests(false,false);
phpCAS::forceAuthentication();

$user = phpCAS::getUser();

if($wgUser->isAnonymous() || strtolower($wgUser->getUsername())!=strtolower($user))
{
    $plug = new DreamPlug("http://{$user}@{$_SERVER['HTTP_HOST']}/@api/deki");
    $result = $plug-> At('users', 'authenticate')
        ->With('apikey',$wgDekiApiKey)->With('authprovider',1)->Post();
    if($result['status'] == 200)
    {
        DekiToken::set($result['body']);
        header('Location:index.php');
    } else{
        print_r($result);
    }print_r($result);
    exit;
}else if($_GET['title']=='Special:Userlogout'){
    $_SESSION = array();
    phpCAS::logoutWithRedirectService('http://u.wmios.com');
}