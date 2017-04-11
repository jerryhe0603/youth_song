<?php
	
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

$CDbShell = new CDbShell;
	
$sMobile = $_GET['phone'];
$sVerifyCode = $_GET['verify_code'];

$iDbq = $CDbShell->iQuery("SELECT * FROM member WHERE phone='$sMobile'");				
if (!trim($sMobile)) $aErrorMsg = _LANG_SIGN_VAILD_MOBILE;
else if (!validator::bCheckMobile($sMobile)) $aErrorMsg = _LANG_SIGN_MOBILE_ERROR;
else if($CDbShell->iNumRows($iDbq) > 0) $aErrorMsg = _LANG_SIGN_MOBILE_EXIST; 	

if(count($aErrorMsg) > 0){
	echo $aErrorMsg;
	exit;
}	



//撈出資料庫最新的手機驗證碼
$iDbq = $CDbShell->iQuery("SELECT * FROM talent_verify WHERE ta_mobile='$sMobile' ORDER BY created DESC LIMIT 1");
$aMobile = $CDbShell->aFetchArray($iDbq);
// echo 'sql:'.$aMobile['verify_code'];
// echo '<br> post:'.$sVerifyCode;exit;
if($sVerifyCode == $aMobile['verify_code']){

	// $aFields=array("is_verify");
	// $aValues=array('1');			

	// $sSql = $CDbShell->sUpdate("sign",$aFields,$aValues,'mobile='.$sMobile);	
	echo _LANG_SIGN_VERIFY_CODE_SUCCESS;
	exit;

}else{
	
	echo _LANG_SIGN_VERIFY_CODE_ERROR;
	exit;
}

?>	