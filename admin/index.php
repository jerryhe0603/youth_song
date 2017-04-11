<?php

$start_time = getMicrotime();
header('Content-Type: text/html; charset=utf-8');

// include basic config
include_once('../inc/config.php');
include_once('../inc/smarty.config.php');
include_once('../inc/class.session.php');

/*
	CAUTION: DO NOT include any other class , unless it is used here
*/
include_once('../inc/CMisc.php');
include_once('../inc/CJavaScript.php');
include_once('../inc/model/CUser.php');
include_once('../inc/model/CGalaxyClass.php');

/*
	CAUTION: DO NOT new any other class here, unless it is used by every function controller
*/
$sessid = isset($_GET['PHPSESSID'])?$_GET['PHPSESSID']:'';
$session = new session($sessid);
CGalaxyClass::$session =$session;	//insert to basic class static member

//default 語系
include_once('../inc/CLang.php');
CLang::$iBackendLang=1;

include_once("../lang/"._LANG.".php");

//quotes info from client
//$_GET = CMisc::my_quotes($_GET);
//$_POST = CMisc::my_quotes($_POST);

$func = isset($_GET['func'])?$_GET['func']:'';
$action = isset($_GET['action'])?$_GET['action']:'';

//if user is login, there should be a $oCUser named oCurrentUser in $_SESSION
if(is_null($session->get('oCurrentUser'))){
	CJavaScript::vRedirect('./login.php');
	exit;
}


//use $oCUser->IsPermit() to check if current user is allowed to given func & action
// try{
	// $session->get('oCurrentUser')->IsPermit($func,$action);
// }catch (Exception $e){
	// CJavaScript::vAlertRedirect($e->getMessage(),'./index.php');
	// exit;
// }

//menu filter
$oCurrentUser = $session->get('oCurrentUser');
// foreach($oCurrentUser->aGroup() as $oGroup){
// 	if($oGroup->iGroupNo == 23){
// 		//CMisc::vPrintR('project_admin');
// 		$Smarty->assign('bProjectAdmin','1');
// 	}else if($oGroup->iGroupNo == 22){
// 		//CMisc::vPrintR('super_admin');
// 		$Smarty->assign('bSuperAdmin','1');
// 		$Smarty->assign('bProjectAdmin','1');
// 		break;
// 	}
// }

//assign subsystem server & session id
$Smarty->assign('PHPSESSID',session_id());

//map func to controller name; if more functions are add in system, add them here
switch($func){
	case 'user': // 使用者管理
		$sController = 'CUserAdmin';
		break;
	case 'news_type': // 最新消息類別管理
		$sController = 'CNewsTypeAdmin';
		break;
	case 'news': // 最新消息管理
		$sController = 'CNewsAdmin';
		break;
	case 'member': // 會員管理
		$sController = 'CMemberAdmin';
		break;
	case 'sign': // 報名表管理
		$sController = 'CSignAdmin';
		break;
	default:
		$sController = '';
		break;
}

if($sController!==''){
	try {
		//include, new target controller, and run tManager
		include_once("../inc/controller/$sController.php");	//include controller.php
		$oController = new $sController();	//new target controller
		$Smarty->assign("bodyTpl", $oController->tManager());	//call controller entry function
	}catch (Exception $e){
		CJavaScript::vAlertRedirect($e->getMessage(),'./login.php');
		exit;
	}
}

$Smarty->assign('oCurrentUser',$session->get('oCurrentUser'));
$Smarty->display('./admin/index.html');

//if display index.html, means there no insert/update to database & add log, add log of current func & action & user
if($func)

$time_after_tpl = getMicrotime() - $start_time;
$memory_usage = function_exists('memory_get_usage') ? number_format( memory_get_usage()/(1024*1024), 2 ) : 'N/A';
// echo '<!-- Total Execution Time: ' .number_format($time_after_tpl, 4). ' seconds; Memory usage: ' .$memory_usage.'-->';
exit;

function getMicrotime()
{
    list( $usec, $sec ) = explode( ' ', microtime() );
    return ( (float)$usec + (float)$sec );
}

?>