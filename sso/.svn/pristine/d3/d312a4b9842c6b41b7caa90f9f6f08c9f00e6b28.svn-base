<?php
require_once('./Snoopy.class.php');

if(!isset($_GET['a']))
{
    header('Location:http://feel.wmios.com:7090/index.php/admin/index');
}

$client = new Snoopy();
$loginUrl = 'http://feel.wmios.com:7090/index.php/admin/authentication/sa/login';
$r = $client->submit($loginUrl,array(
                    'user'=>$_GET['a'],
                    'password'=>$_GET['p'],
                    'loginlang'=>'default',
                    'action'=>'login'));
if(!strlen($client->results) || !stristr($client->results,'登录身份'))
{
    echo '404';
}else{
    $client->setcookies();
    echo '200&PHPSESSID='.$client->cookies['PHPSESSID'];
}