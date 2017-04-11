<?php

//echo "1:OK";exit; // TODO: test

/**
* @desc 世大運報名送出手機認證碼
* @author Jerome
*/
include_once('./inc/config.php');

//default 語系
include_once('./inc/CLang.php');
CLang::$iBackendLang=1;

include_once("./lang/"._LANG.".php");
include_once('./inc/class.session.php');
include_once('./inc/CDbShell.php');
include_once('./inc/CMisc.php');
include_once("./inc/CMimeMail.php");
include_once("./inc/CJavaScript.php");
include_once("./inc/class.validator.php");
include_once("./inc/CCharset.php");

//設定時區為台北
date_default_timezone_set("Asia/Taipei");

$CMimeMail = new CMimeMail;

//init class
$session = new session($sessid);
$CDbShell = new CDbShell;
$CMisc = new CMisc;
$CCharset    = new CCharset;

$sMobile = $_GET['phone'];
$sArea = $_GET['area'];

if($sArea=='')$aErrorMsg = "請先選擇註冊地區";
// $aErrorMsg = $sArea;

$iDbq = $CDbShell->iQuery("SELECT * FROM member WHERE phone='$sMobile'");
if (!trim($sMobile)) $aErrorMsg = _LANG_SIGN_VAILD_MOBILE;
else if (!validator::bCheckMobile($sMobile)) $aErrorMsg = _LANG_SIGN_MOBILE_ERROR;
else if($CDbShell->iNumRows($iDbq) > 0) $aErrorMsg = _LANG_SIGN_MOBILE_EXIST;

if(count($aErrorMsg) > 0){
	echo $aErrorMsg;
	exit;
}


// echo '<pre>';print_r($aErrorMsg);exit;

//是否超過三次
//撈資料庫看比數是否有三筆
$iDbq = $CDbShell->iQuery("SELECT ta_mobile,verify_code FROM talent_verify WHERE ta_mobile='$sMobile'");

if($CDbShell->iNumRows($iDbq)==3){
	echo _LANG_SIGN_MOBILE_VERIFY_LIMIT;
	exit;
}

//$iVerifyCodeCount = $iVerifyCodeCount+1;
//$session->vSetVar("gVerifyCodeCount",$iVerifyCodeCount);

$sVerifyCode = $CMisc->sRandomNumber("",6);

$sMsg = "您好，您註冊「青春頌──兩岸青年原創金曲大選」活動官網會員，手機認證碼為：$sVerifyCode";
if($sArea=='2'){
	$sMsg = $CCharset->chg_utfcode($sMsg,"gb");
}

$sendMsg=urlencode($sMsg);

// 帳密請勿異動
$req_sms  = "UID=tsaihungpin";
$req_sms .= "&PW=8864";

// 編碼
$req_sms .= "&MT=8"; //UTF8

// SM 發送的訊息內容
$req_sms .= "&SM=$sendMsg";

// DA 接收的手機號碼
$req_sms .= "&DA=$sMobile";

// 呼叫API
$fd_sms =@fopen("http://eumps.tytech.com.tw:5566/EUMPS/SendMsg?".$req_sms,"r");
if (!$fd_sms) {
	$buffer_sms = -999;		//失敗
}else {
	$buffer_sms = fgets($fd_sms,4096);
	@fclose ($fd_sms);
}

switch($buffer_sms){
	case 0:
		$aFields=array("ta_mobile","verify_code","created");
		$aValues=array($sMobile,$sVerifyCode,date("Y-m-d H:i:s"));
		$sSql = $CDbShell->sInsert("talent_verify",$aFields,$aValues);

		echo '1';
		exit;

	default:
		echo $buffer_sms;
		exit;
}

/**
* @	回傳參數( 無交接手冊，故在網路上找到的，無法確認正確性 )
* @		 0		傳送完成
* @		-1		參數錯誤
* @		-2		帳號或密碼錯誤
* @		-3		DA 接收者行動電話號碼錯誤
* @		-4		DT(預計發送時間)已過去24 小時以上
* @		-5		Short Message 內容長度超過限制
* @		-6		DT(預計發送時間)格式錯誤
* @		-7		Client IP 遭拒絕存取或是帳號已遭暫停使用
* @		-8		EUMPS 後端發送平台發生不明的內部錯誤
* @		-100	EUMPS 後端發送平台維護中，暫時停止營運
* @		-101	EUMPS 後端發送平台資料庫存取錯誤，暫時停止營運
* @		-201	帳戶餘額不足
* @		-202	網路錯誤，連接EUMPS 後端發送平台錯誤
* @		-203	EUMPS 資料庫存取發生錯誤
* @
* @		自行定義
* @		-999	檔案開啟失敗
*/

?>