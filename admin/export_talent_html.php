<?php

/**
* @desc 匯出目前會員影音資料(HTML)
* @created 2013/03/20
*/

set_time_limit(0);
//主要index程式

ini_set('memory_limit', '-1'); // 避免出現 Fatal error: Allowed memory size of 134217728 bytes exhausted 

header('Content-Type: text/html; charset=utf-8');

// 因為會造成下載檔案偏移, 所以需要加入下面兩行語法
@apache_setenv('no-gzip', 1);
@ini_set('zlib.output_compression', 0); 

// include files
//include files
include_once('../inc/config.php');
include_once('../inc/smarty.config.php');
include_once('../inc/class.session.php');

/*
	CAUTION: DO NOT include any other class , unless it is used here
*/
include_once('../inc/CMisc.php');
include_once('../inc/CJavaScript.php');
include_once('../inc/model/CMember.php');
include_once('../inc/model/CGalaxyClass.php');
include_once("../inc/CDbShell.php");
include_once("../inc/AzDGCrypt.class.inc.php");

//init class	

//default 語系
include_once('../inc/CLang.php');
CLang::$iBackendLang=1;
include_once("../lang/"._LANG.".php");

//new
$CDbShell = new CDbShell;
$sessid = isset($_GET['PHPSESSID'])?$_GET['PHPSESSID']:'';
$session = new session($sessid);
$AzDGCrypt = new AzDGCrypt('youth_song');
$CJavaScript = new CJavaScript;


//if user is login, there should be a $oCUser named oCurrentUser in $_SESSION
if(is_null($session->get('oCurrentUser'))){
	CJavaScript::vRedirect('./login.php');
	exit;
}	

// 壓縮檔案

require_once "../inc/dzip/dZip.inc.php";

$sDataUrl = "/data/upload/singer/1/";

$newzip = new dZip(PATH_ROOT.$sDataUrl."SIGN_".date("Y-m-d").".zip");
$sKey = $_POST['search_key'];
$sTerms = $_POST['search_terms'];


$sSql = "SELECT sign.*
				,member.name
				,member.area
				,member.sex
				,member.phone
				,member.uid
				,member.birthday
				
				FROM sign 
				LEFT JOIN member ON member.member_no=sign.member_no	WHERE";

if ($sTerms) {
	$sSql .= " $sTerms LIKE '%$sKey%' ORDER BY  created";
} else {
	$sSql .= " (1=1) ORDER BY  created " ;
}



$aUploadTotal = array();

$aTalentData = array();
$iDbq=$CDbShell->iQuery($sSql);

while($aRow = $CDbShell->aFetchArray($iDbq)) {
	$iSignNo = $aRow['sign_no'];
	
	// 會員上傳的資料
	$sUploadUrl = "";

	$sSqlWorks = " SELECT * FROM works 
					LEFT JOIN upload_file ON upload_file.works_no = works.works_no
					WHERE works.sign_no ='$iSignNo' ";
	$iDbq4 = $CDbShell->iQuery($sSqlWorks);
	$sOldWorksNo = '';
	while($aRow4=$CDbShell->aFetchArray($iDbq4)){
			
		$sWorksNo = $aRow4['works_no'];
		$iUpId = $aRow4['up_no'];

		$aUploadTotal[$iSignNo][$sWorksNo][] = $aRow4;
		$aUploadTotal[$iSignNo]['member_no'] = $aRow['member_no'];

		//建立路徑
		if($aRow['uid']) {
			
			// 履歷的圖檔放在works $aRow4['type']為作品1,2
			for($i=1;$i<4;$i++){

				if($aRow4['img_file_name_'.$i] && ($sOldWorksNo!=$sWorksNo)){
					$sUploadUrl[$aRow4['type']] .= "<br><a href='".str_pad($aRow['uid'],4,'0',STR_PAD_LEFT)."/".$aRow4['type']."/".$aRow4['img_file_name_'.$i]."' target='_BLANK'>".$aRow4['img_file_name_'.$i]."</a>,"; 
				}
			}
			$sUploadUrl[$aRow4['type']] .= "<br><a href='".str_pad($aRow['uid'],4,'0',STR_PAD_LEFT)."/".$aRow4['type']."/".$aRow4['file_name']."' target='_BLANK'>".$aRow4['file_name']."</a>,"; 
			$aWorksDetail[$aRow4['type']] = $aRow4['creative_concept'];
		}
		$sOldWorksNo = $sWorksNo;
	}

	$sUploadUrl['1'] = substr($sUploadUrl['1'],0,-1);
	$sUploadUrl['2'] = substr($sUploadUrl['2'],0,-1);
	$aRow['upload_url1'] = $sUploadUrl['1'];
	$aRow['upload_url2'] = $sUploadUrl['2'];

	//創作理念
	$aRow['creative_concept1'] = $aWorksDetail['1'];
	$aRow['creative_concept2'] = $aWorksDetail['2'];

	$aTalentData[] = $aRow;
}


$Smarty->assign("talentData",$aTalentData);

// capture the output
$output = $Smarty->fetch("./admin/export_talent_html.htm");

// echo '<pre>';print_r($aTalentData);


$sPath=getenv("document_root"); //unix絕對路徑


$filename = PATH_ROOT.$sDataUrl.md5(time()).".htm";

$fp = fopen($filename, 'w');
fwrite($fp, $output);
fclose($fp);
$newzip->addFile($filename, "index.htm"); // 加入壓縮檔案
// echo "<pre>";print_r($aUploadTotal);exit;
// 壓縮檔案
//根據履歷
foreach ($aUploadTotal as $key1 => $val1) {


	$aRow = CMember::aGetData($aUploadTotal[$key1]['member_no']);
	// echo "<pre>";print_r($aRow);exit;
	
	//檔案有依照國別分資料夾判斷國別
	$sArea = '';
	if($aRow['area']=='1'){
		$sArea='TW';
	}else{
		$sArea='CN';
	}
	$newzip->addDir(str_pad($aRow['uid'],4,'0',STR_PAD_LEFT)); // 建立壓縮的資料夾 依身份證創資料夾
	
	//根據上傳的檔案
	foreach($val1 as $key2 => $val2) {
		 
		 //單一upload_file 的資料
		 
		if(is_array($val2) && !empty($val2)){
			$sOldWorksNo='';
			
			foreach($val2 as $key3 => $sUpFile) {
		 		$newzip->addDir($aRow['uid'].'/'.$sUpFile['type']); // 建立壓縮的資料夾 依身份證創資料夾 身份證/作品1,2
		 		
		 		//檔案路徑
		 		$sDir = PATH_ROOT."/data/upload/".$sArea."/".$sUpFile['sign_no']."/";
		 		// echo "<pre>";print_r($sUpFile);exit;
		 		
		 		// 履歷的圖檔放在works $aRow4['type']為作品1,2
				for($i=1;$i<4;$i++){

					//放入圖檔
		 			$sImageFilePath  = $sDir.$sUpFile['img_file_file_'.$i];
		 			if($sUpFile['img_file_name_'.$i] && ($sOldWorksNo!=$sUpFile['works_no'])){

		 				//因為圖檔會重複撈 所以works_no不同在放檔案進入壓縮檔
		 				if (file_exists($sImageFilePath)) {
							$newzip->addFile($sImageFilePath,$aRow['uid']."/".$sUpFile['type']."/". iconv("UTF-8","BIG5", $sUpFile['img_file_name_'.$i])); // 加入壓縮檔案
						}
					}
				}

		 		//放入作品
		 		$sFilePath  = $sDir.$sUpFile['file_file']; 
		 		// echo $sFilePath.'<br>';
		 		if (file_exists($sFilePath)) {
		 			// echo 'in';exit;
					
					$newzip->addFile($sFilePath,$aRow['uid']."/".$sUpFile['type']."/". iconv("UTF-8","BIG5", $sUpFile['file_name'])); // 加入壓縮檔案
				}
				// echo 'out';exit;
				$sOldWorksNo=$sUpFile['works_no'];
		 	}
		}
		
	}
}


$newzip->save();

// 下載檔案
$sFilePath = PATH_ROOT.$sDataUrl."SIGN_".date("Y-m-d").".zip";
//chmod($sFilePath,0777);
if(!file_exists($sFilePath)){ //檢查檔案是否存在
	$CJavaScript->vAlert("Zip Not Exist");
	exit;
}

header('HTTP/1.1 200 OK');
header('Status: 200 OK');
header('Accept-Ranges: bytes');
header('Content-Encoding: none');
//header('Content-Type: application/force-download');
//header("Content-type: application/octet-stream");
header('Content-Type: application/zip');
header("Pragma: anytextexeptno-cache", true);

header('Pragma: no-cache');
header('Expires: 0');
header("Content-Disposition: attachment; filename=\"SIGN_html".date("Ymd_His").".zip\"");
header("Content-Length: ".filesize($sFilePath));
readfile($sFilePath);

exit;	

?>