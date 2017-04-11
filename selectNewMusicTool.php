<?php
/**
* @desc 擅長樂器選項API
*/
header('Content-Type: text/html; charset=utf-8');

include_once('./inc/config.php');
include_once('./inc/smarty.config.php');
include_once('./inc/class.session.php');
//default 語系
include_once('./inc/CLang.php');
CLang::$iBackendLang=1;

include_once("./lang/"._LANG.".php");

include_once("./inc/CDbShell.php");
include_once("./inc/CMisc.php");
// include_once("lib/CSkill.php");
include_once("./inc/CCharset.php");
include_once("./inc/model/CMusicTool.php");
include_once("./inc/model/CSign.php");
// include_once("lib/CMimeMail.php");

//簡繁轉換套件
include_once("./inc/geoip/geoip.exe.php");

// $CMimeMail = new CMimeMail;

//init class
$sessid      = isset($_GET['PHPSESSID'])?$_GET['PHPSESSID']:'';
$session     = new session($sessid);

$CDbShell = new CDbShell;
$CMisc = new CMisc;
// $CSkill = new CSkill;
$CCharset= new CCharset;
$CMusicTool = new CMusicTool;

$gCharSet = $session->get("gCharSet");
if(!$gCharSet) $gCharSet="tw";

// 登入使用者取得 session 使用者ID
$aMusicTool = $session->get("gMusicTool");

if(!$aMusicTool) $aMusicTool=array();

$id = (isset($_GET['id']))? trim($_GET['id']) : "";
$music_tool = (isset($_GET['music_tool']))? trim($_GET['music_tool']) : "";
$ability = (isset($_GET['ability']))? trim($_GET['ability']) : "";
$func = (isset($_GET['func']))? trim($_GET['func']) : "";



switch($func){
	case "add":
		$aMusicTool[] = array("music_tool"=>0,"ability"=>1);
		break;
	case "edit":
		if($aMusicTool[$id]){
			$aMusicTool[$id]['music_tool'] = $music_tool;
			$aMusicTool[$id]['ability'] = $ability;
		}
		break;
	case "del":
		$aTmp = array();
		for($i=0;$i<count($aMusicTool);$i++){
			if($id!=$i){
				$aTmp[] = $aMusicTool[$i];
			}
		}
		$aMusicTool = $aTmp;
		break;

}

$aMusic = CSign::$aMusicTool;
$aRow = array();

//擅長樂器種類定義在model內
foreach ($aMusic as $key => $value) {
	$aRow['to_id'] = $key;
	$aRow['to_name'] = $value;
	$aMusicToolOption[] = $aRow;
}

//專精等級定義在model內
$gability = CSign::$aLevel;

$Smarty->assign("musicToolOption",$aMusicToolOption);
$Smarty->assign("abilityOption",$gability);
$Smarty->assign("musicToolData",$aMusicTool);
$session->set("gMusicTool",$aMusicTool);

$sTpl = $Smarty->fetch("./user/select_new_music_tool.htm");

if($gCharSet=="gb") echo $CCharset->chg_utfcode($sTpl,"gb");
else echo $sTpl;
exit;

?>