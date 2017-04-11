<?php
$start_time = getMicrotime();
header('Content-Type: text/html; charset=utf-8');
//header("Access-Control-Allow-Origin:*");
// include basic config
include_once('./inc/config.php');
include_once('./inc/smarty.config.php');
include_once('./inc/class.session.php');
include_once('./inc/AzDGCrypt.class.inc.php');

/*
	CAUTION: DO NOT include any other class , unless it is used here
*/
include_once('./inc/CMisc.php');
include_once('./inc/CJavaScript.php');
include_once('./inc/model/CUser.php');
include_once('./inc/model/CGalaxyClass.php');
include_once('./inc/CDbShell.php');
include_once("./inc/CJavaScript.php");
include_once("./inc/class.validator.php");
include_once("./inc/CCharset.php");

//簡繁轉換套件
include_once("./inc/geoip/geoip.exe.php");

/*
	CAUTION: DO NOT new any other class here, unless it is used by every function controller
*/

$sessid      = isset($_GET['PHPSESSID'])?$_GET['PHPSESSID']:'';
$session     = new session($sessid);
$CDbShell    = new CDbShell;
$AzDGCrypt   = new AzDGCrypt("university_voice");
$CJavaScript = new CJavaScript;
$CCharset    = new CCharset;
$CMisc = new CMisc();

//default 語系
include_once('./inc/CLang.php');
CLang::$iBackendLang=1;

include_once("./lang/"._LANG.".php");

// quotes info from client
$_GET = CMisc::my_quotes($_GET);
$_POST = CMisc::my_quotes($_POST);


$func = isset($_GET['func'])?$_GET['func']:'';
$action = isset($_GET['action'])?$_GET['action']:'';

// 語系
// 1 是繁體2是簡體
if(return_country_code($_SERVER['REMOTE_ADDR'])=="CN"){
 	$session->set("gCharSet",'2');
 	$Smarty->assign('WebIndexSrc',_WEB_INDEX_SCR_CN);//發送確認信的mail裡使用者點擊的連結位置判斷國別 
 	$Smarty->assign('sCountry',"CN");//cn and tw 圖片會不同 會依後面單字判斷
}else{
	$Smarty->assign("WebIndexSrc",_WEB_INDEX_SCR_TW);//發送確認信的mail裡使用者點擊的連結位置判斷國別 
	$Smarty->assign('sCountry',"TW");
}

$lang = isset($_GET['lang'])?$_GET['lang']:'';

if($lang=='1' || $lang=="2") $session->set("gCharSet",$lang);

$gCharSet = $session->get("gCharSet");

//如果語系沒設定預設就給中文
if(!$gCharSet){
	$gCharSet="1";
	$session->set("gCharSet",$gCharSet);
}

$Smarty->assign("lang",$gCharSet);
//use $oCUser->IsPermit() to check if current user is allowed to given func & action
// try{
	// $session->get('oCurrentUser')->IsPermit($func,$action);
// }catch (Exception $e){
	// CJavaScript::vAlertRedirect($e->getMessage(),'./index.php');
	// exit;
// }
//assign subsystem server & session id


$Smarty->assign('PHPSESSID',session_id());

//map func to controller name; if more functions are add in system, add them here
switch($func){
	case 'member': //基本會員管理
		$sController = 'CMemberUser';
		break;
	case 'sign': //音樂作品投稿管理
		$sController = 'CSignUser';
		break;
	case 'news': //最新消息列表
		$sController = 'CNewsUser';
		break;
	case 'signup': //報名資訊
		$sController = 'CSignupUser';
		break;	
	default :
		$sController = '';
		break;
}

if($sController!==''){
	try {
		//include, new target controller, and run tManager
		include_once("./inc/controller/$sController.php");	//include controller.php
		$oController = new $sController();	//new target controller
		$Smarty->assign('web_index',_WEB_INDEX);
		$Smarty->assign("header",$Smarty->fetch("./user/header.html"));
		$Smarty->assign("footer",$Smarty->fetch("./user/footer.html"));
		$Smarty->assign("mail_footer",$Smarty->fetch("./user/member_mail_footer.html"));//發送mail的footer
		$Smarty->assign("bodyTpl", $oController->tManager());	//call controller entry function
	}catch (Exception $e){
		CJavaScript::vAlertRedirect($e->getMessage(),'./'._WEB_INDEX);
		exit;
	}
}else{
	$Smarty->assign('path',PATH_ROOT);
	$Smarty->assign('web_index',_WEB_INDEX);
	$Smarty->assign("header",$Smarty->fetch("./user/header.html"));
	$Smarty->assign("footer",$Smarty->fetch("./user/footer.html"));
	$Smarty->assign("bodyTpl",$Smarty->fetch("./user/index.html"));
	// $Smarty->assign("mail_footer",$Smarty->fetch("./user/member_mail_footer.html"));//發送mail的footer
}

$Smarty->assign('oCurrentUser',$session->get('oCurrentUser'));
$sTpl = $Smarty->fetch('./user/main.html');

//簡體就先編碼
if($gCharSet=="2") echo $CCharset->chg_utfcode($sTpl,"gb");
else echo $sTpl;

//if display index.html, means there no insert/update to database & add log, add log of current func & action & user
// if($func)

$time_after_tpl = getMicrotime() - $start_time;
$memory_usage = function_exists('memory_get_usage') ? number_format( memory_get_usage()/(1024*1024), 2 ) : 'N/A';
// echo 'Total Execution Time: ' .number_format($time_after_tpl, 4). ' seconds; Memory usage: ' .$memory_usage.'';
exit;

function getMicrotime()
{
    list( $usec, $sec ) = explode( ' ', microtime() );
    return ( (float)$usec + (float)$sec );
}
?>
