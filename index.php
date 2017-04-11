<?php

include_once('./inc/config.php');
include_once('./inc/smarty.config.php');
include_once('./inc/class.session.php');
include_once('./inc/CMisc.php');
include_once('./inc/CDbShell.php');
//簡繁轉換套件
include_once("./inc/geoip/geoip.exe.php");


$password      = isset($_GET['password'])?$_GET['password']:'';
// if($password!='hellotw'){
// 	echo 'http://www.youth-song.com';
// 	exit;
// }	

// quotes info from client
$_GET        = CMisc::my_quotes($_GET);
$_POST       = CMisc::my_quotes($_POST);
$func        = isset($_GET['func'])?$_GET['func']:'';
$account     = isset($_GET['account'])?$_GET['account']:'';
$verify_code = isset($_GET['verify_code'])?$_GET['verify_code']:'';//信件驗證
$action      = isset($_GET['action'])?$_GET['action']:'';
$lang        = isset($_GET['lang'])?$_GET['lang']:'';
$sessid      = isset($_GET['PHPSESSID'])?$_GET['PHPSESSID']:'';
$sessid      = isset($_GET['PHPSESSID'])?$_GET['PHPSESSID']:'';

// $session->set("WebIndexSrc",'http:');
// $from_ip	='58.218.199.147';//CN
// $from_ip	='111.248.149.170';//TW
// $from_ip	=$_SERVER['REMOTE_ADDR'];
if (!empty($_SERVER['HTTP_CLIENT_IP'])){
  $from_ip = $_SERVER['HTTP_CLIENT_IP'];
}else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
  $from_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
  $from_ip = $_SERVER['REMOTE_ADDR'];
}

$country = return_country_code($from_ip);//判斷國別
$to_index ="&func=".$func."&action=".$action."&lang=".$lang."&sessid=".$sessid."&account=".$account."&verify_code=".$verify_code;

if($country=='TW'){
	$frame_src= _WEB_ADDRESS_TW.$to_index;
}else{
	$frame_src= _WEB_ADDRESS_CN.$to_index;
}

$Smarty->assign("frame_src",$frame_src);

$frame_index = $Smarty->fetch('./user/frame_index.html');
echo $frame_index;