<?php

/********************************************************************
 * @heading(標題):
			CSignUser 音樂作品管理類別
 * @author(作者) :
 * @purpose(目的) :
			音樂作品管理類別
 * @usage(用法) :
 * @reference(參考資料) :
 * @restriction(限制) :
 * @revision history(修改紀錄) :
			修改日期:
			修改人姓名:
			修改內容:
 * @copyright(版權所有) :
			銀河互動網路股份有限公司 iWant-in inc.。
 * @note(說明) :
 * @created(建立日期) :
			2016/11/28
 ********************************************************************/


include_once('./inc/controller/CGalaxyController.php');
include_once('./inc/controller/CMemberUser.php');
include_once('./inc/model/CSign.php');
include_once('./inc/model/CMember.php');
include_once('./PHPMailer/PHPMailerAutoload.php');

class CSignUser extends CGalaxyController{

	// This is the constructor for this class
	// Initialize all your default variables here

	const BACK_TO_LIST = 1;
	const BACK_TO_VIEW = 2;
	const BACK_TO_ADD = 3;
	const BACK_TO_EDIT = 4;
	const BACK_TO_ADMIN = 5;
	const BACK_TO_NOW = 6;

	private static $sDBName = 'MYSQL';

	function __construct(){
	}

	function tManager() {
		$action = isset($_GET['action'])?$_GET['action']:"list";
		switch($action){
			case "logout": // 登出
				return $this->tSignLogout();
				break;
			case "add": //填寫作品履歷
				return $this->tSignAdd();
				break;
			case "add_step2": //投稿作品
				return $this->tSignAddStep2();
				break;	
			case "edit": //修改投稿作品
				return $this->tSignEdit();
				break;
			case "edit_step2": //修改投稿作品
				return $this->tSignEditStep2();
				break;	
			case "index":
			default :// 會員首頁
				return $this->tSignIndex(); // show
				break;
		}
	}

	//投稿報名頁面首頁
	function tSignIndex(){
		global $Smarty,$CJavaScript,$CDbShell,$session; //obj var

		$member_no = 0;

		//有session表示已登入
		if($session->get('member_no')){
			$member_no = $session->get('member_no');
			$member_area = $session->get('area');//會員註冊的國別,跟member_no的一起紀錄
			if($member_area=='1'){
				$area_name="TW";;
			}else{
				$area_name="CN";
			}
		}

		//沒session表示非法登入就導回登入頁面
		if(!$member_no) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member&action=login');

		$aSignData = array();
		//
		$aSignData = CSign::aGetData($member_no);

		//抓作品
		$aWorksData = array();
		$aWorksData = CSign::aGetWorksData($aSignData['sign_no']);
		

		//抓不到投稿資料就進入投稿報名頁面
		if(!$aSignData) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=sign&action=add');
		if(!$aWorksData) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=sign&action=add_step2&sign_no='.$aSignData['sign_no']);

		//撈出來有演藝專長才將相對應專長序號轉為中文名稱
		// if($aSignData['specialty']){
		// 	$specialty = explode(',',$aSignData['specialty']);

		// 	$str = '';
		// 	foreach ($specialty as $value){
		// 		$str .= CSign::$aSpecialty[$value].'、';
		// 	}

		// 	//全形頓號切除
		// 	$aSignData['specialty'] = substr($str,0,-3);
		// }

		//抓出擅長樂器的序號 並將對照中文名稱加上去
		// $aMusic = CSign::aGetSignDetail($aSignData['sign_no'],'music_tool');
		// if($aMusic){

		// 	foreach ($aMusic as $key => $value) {
		// 		//抓取中文及序號對照表
		// 		$aMusic[$key]['tool_name'] = CSign::$aMusicTool[$value['tool_no']];
		// 		$aMusic[$key]['level_name'] = CSign::$aLevel[$value['level']];
		// 	}
		// }
		
		// $aSignData['music_tool'] = $aMusic;

		$aWorks = CSign::aGetSignDetail($aSignData['sign_no'],'works');

		//撈出作品的上傳檔案
		foreach ($aWorks as $key => $value) {

			$aWorks[$key]['lyricist_file'] = CSign::aGetFileData($value['works_no'],1);
			$aWorks[$key]['song_file'] = CSign::aGetFileData($value['works_no'],2);
		}

		$aSignData['works'] = $aWorks;
		// echo "<pre>";print_r($aSignData);exit;

		$Smarty->assign('aSignData',$aSignData);
		$Smarty->assign('sAreaName',$area_name);
		$Smarty->assign("web_index",_WEB_INDEX);
		return $output = $Smarty->fetch('./user/sign_show.html');

	}

	/**
	 *投稿作品
	 */
	function tSignAdd() {
		global $Smarty,$CJavaScript,$CDbShell,$session,$CMisc; //obj var
		
		//有session表示有登入
		if($session->get('member_no')){
			$member_no = $session->get('member_no');
			$member_area = $session->get('area');//會員註冊的國別,跟member_no的一起紀錄
			if($member_area=='1'){
				$area_name="TW";
			}else{
				$area_name="CN";
			}
		}

		//報名期別 定義在model內
		$iType = CSign::$iType;

		//沒session表示非法登入就導回登入頁面
		if(!$member_no) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member');

		if(!$_POST){

			unset($_SESSION['gMusicTool']);

			$aSignData = array();
			$aSignData = CSign::aGetData($member_no);
			
			echo $aMemberData['name'];
			$aWorksData = array();
			$aWorksData = CSign::aGetWorksData($aSignData['sign_no']);
			// echo "<pre>";print_r($aWorksData);exit;
			//抓到有投稿報名紀錄就導回明細頁
			if($aSignData) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=sign&action=index');

			$aSpecialty = CSign::$aSpecialty;

			$Smarty->assign('aSpecialty',$aSpecialty);
			$Smarty->assign("web_index",_WEB_INDEX);
			return $output = $Smarty->fetch('./user/sign_add.html');

		} else {

			$aRow=&$_POST;
			$aFile=&$_FILES;

			CSign::sVaildData($aRow,$aFile);

			$aMemberData = CMember::aGetData($member_no);
			// echo "<pre>";print_r($_FILES);
			// echo "<pre>";print_r($aRow);exit;
			$sDate = date("Y-m-d H:i:s");

			$specialty = '';

			//如果有選擇演藝專長才將陣列重組成字串 改為直接輸入
			// if(isset($aRow['specialty'])){
			// 	$specialty = implode (',', $aRow['specialty']);
			// }

			$iSignNo =$CDbShell->guid();

			$aFields=array("sign_no","member_no","member_name","type","team_name",'school','department','management',"specialty","music_tool",'experience','flag',"created","modified");
			$aValues=array($iSignNo,$member_no,$aMemberData["name"],$iType,$aRow['team_name'],$aRow['school'],$aRow['department'],$aRow['management'],$aRow['specialty'],$aRow['music_tool_data'],$aRow['experience'],1,$sDate,$sDate);

			$sSql = $CDbShell->sInsert("sign",$aFields,$aValues);

			if($sSql){

				// $iSignNo = $CDbShell->iGetInsertId();
				// echo 'iSignNo:'.$iSignNo;exit;
				/*
				// 擅長樂器 先清空在新增
				$sSql = "DELETE FROM music_tool WHERE sign_no = '$iSignNo'";
				$res = $CDbShell->iQuery($sSql);

				$aMusicTool = $session->get("gMusicTool");
				for($i=0;$i<count($aMusicTool);$i++){
					if($aRow['music_tool_'.$i]){
						$aFields=array("sign_no","tool_no","level");
						$aValues=array($iSignNo,$aRow['music_tool_'.$i],$aRow['music_tool_ability_'.$i]);
						$sSql = $CDbShell->sInsert("music_tool",$aFields,$aValues);
					}
				}
				unset($_SESSION['gMusicTool']);
				*/

				// if(!$this->bSignOkMail($member_no)) CJavaScript::vAlertRedirect(_LANG_SENT_MAIL_ERROR,"./"._WEB_INDEX."func=member");

				// CJavaScript::vAlertRedirect(_LANG_SIGN_SIGNUP_SUCCESS,"./"._WEB_INDEX."func=sign");
				$CJavaScript->vRedirect('./'._WEB_INDEX.'func=sign&action=add_step2&sign_no='.$iSignNo);
			}

			CJavaScript::vAlertRedirect(_LANG_SIGN_SIGNUP_FAILURE,"./"._WEB_INDEX."func=member");
		}
	}

	/**
	 *投稿作品
	 */
	function tSignAddStep2() {
		global $Smarty,$CJavaScript,$CDbShell,$session,$CMisc; //obj var

		//有session表示有登入
		if($session->get('member_no')){
			$member_no = $session->get('member_no');
			$member_area = $session->get('area');//會員註冊的國別,跟member_no的一起紀錄
			if($member_area=='1'){
				$area_name="TW";
			}else{
				$area_name="CN";
			}
		}

		//報名期別 定義在model內
		$iType = CSign::$iType;

		//沒session表示非法登入就導回登入頁面
		if(!$member_no) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member');

		if(!$_POST){

			//如果沒有主檔id就導回首頁
			$iSignNo = isset($_GET['sign_no'])?$_GET['sign_no']:"";
			
			if($iSignNo==''){
				CJavaScript::vAlertRedirect(_LANG_SIGN_VAILD_AUTH,"./"._WEB_INDEX."func=member");
			}

			unset($_SESSION['gMusicTool']);

			$aSignData = array();
			$aSignData = CSign::aGetData($member_no);

			$aWorksData = array();
			$aWorksData = CSign::aGetWorksData($aSignData['sign_no']);

			//抓到有投稿報名紀錄跟作品紀錄就導回明細頁
			if($aSignData){
				if($aWorksData){
					$CJavaScript->vRedirect('./'._WEB_INDEX.'func=sign&action=index');
				}
			} 

			$aSpecialty = CSign::$aSpecialty;

			$Smarty->assign('iSignNo',$iSignNo);
			$Smarty->assign('aSpecialty',$aSpecialty);
			$Smarty->assign("web_index",_WEB_INDEX);
			return $output = $Smarty->fetch('./user/sign_add_step2.html');

		} else {

			$aRow=&$_POST;
			$aFile=&$_FILES;

			// if($member_no=='F0AF32E6-29AD-F244-F09B-42EF6051F953'){
			// 	// echo '歌詞檔名：'.$aFile['lyric_file_1']['name'];
			// 	echo '創作裡念'.$aRow['creative_concept_1'];
			// 	echo "<pre>"; print_r($aRow);
			// 	exit;
			// }
			

			CSign::sVaildDataStep2($aRow,$aFile);
			// echo "<pre>";print_r($_FILES);
			// echo "<pre>";print_r($aRow);exit;
			$sDate = date("Y-m-d H:i:s");

			$iSignNo = $aRow['sign_no'];

			if($iSignNo!=''){

				unset($_SESSION['gMusicTool']);

				//上傳個人圖片
				// for ($i=1; $i < 4 ; $i++) {
				// 	//如果有上傳檔名就跑上傳檔案function
				// 	if($aFile['img_'.$i]['name']){
				// 		$this->iUploadImg($iSignNo,$aFile['img_'.$i],$i,$area_name);
				// 	}

				// }

				//上傳作品
				for ($i=1; $i < 3 ; $i++) {
					//如果有填寫歌曲名稱才當作有作品
					if($aRow['song_name_'.$i]){
						$iWorksNo =$CDbShell->guid();//撈UUID

						$aFields=array("works_no","sign_no",'type','song_name','lyricist','composer','creative_concept',"created","modified");
						$aValues=array($iWorksNo,$iSignNo,$i,$aRow['song_name_'.$i],$aRow['lyricist_'.$i],$aRow['composer_'.$i],$aRow['creative_concept_'.$i],$sDate,$sDate);
						$sSql_works = $CDbShell->sInsert("works",$aFields,$aValues);

						if($sSql_works){
							//上傳個人圖片
							for ($j=1; $j < 4 ; $j++) {
								//如果有上傳檔名就跑上傳檔案function
								if($aFile['img_'.$i.'_'.$j]['name']){
									$this->iUploadImg($iWorksNo,$aFile['img_'.$i.'_'.$j],$j,$area_name,$iSignNo);
								}

							}
							// $iWorksNo = $CDbShell->iGetInsertId();

							if(!$this->iUploadWorks($iWorksNo,$aFile['lyric_file_'.$i],$iSignNo,1,$area_name)){
								CJavaScript::vAlertRedirect(_LANG_SIGN_SIGNUP_FAILURE,"./"._WEB_INDEX."func=member");
							}

							if(!$this->iUploadWorks($iWorksNo,$aFile['song_file_'.$i],$iSignNo,2,$area_name)){
								CJavaScript::vAlertRedirect(_LANG_SIGN_SIGNUP_FAILURE,"./"._WEB_INDEX."func=member");
							}

						}else{
							CJavaScript::vAlertRedirect(_LANG_SIGN_SIGNUP_FAILURE,"./"._WEB_INDEX."func=member");
						}
					}

				}

				if(!$this->bSignOkMail($member_no)) CJavaScript::vAlertRedirect(_LANG_SENT_MAIL_ERROR,"./"._WEB_INDEX."func=member");

				CJavaScript::vAlertRedirect(_LANG_SIGN_SIGNUP_SUCCESS,"./"._WEB_INDEX."func=sign");
			}

			CJavaScript::vAlertRedirect(_LANG_SIGN_SIGNUP_FAILURE,"./"._WEB_INDEX."func=member");
		}
	}

	function tSignEdit() {
		global $Smarty,$CJavaScript,$CDbShell,$session; //obj var

		//有session表示有登入
		if($session->get('member_no')){
			$member_no = $session->get('member_no');
			$member_area = $session->get('area');//會員註冊的國別,跟member_no的一起紀錄
			if($member_area=='1'){
				$area_name="TW";;
			}else{
				$area_name="CN";
			}
		}

		//沒session表示非法登入就導回登入頁面
		if(!$member_no) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member');

		$aSignData = array();
		$aSignData = CSign::aGetData($member_no);

		//抓作品
		$aWorksData = array();
		$aWorksData = CSign::aGetWorksData($aSignData['sign_no']);

		if(!$_POST){
			
			//抓不到有投稿報名紀錄就導回新增頁面
			if(!$aSignData) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=sign&action=add');
			if(!$aWorksData) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=sign&action=add_step2&sign_no='.$aSignData['sign_no']);

			//演藝專長
			// $specialty = explode(',',$aSignData['specialty']);
			// echo '<pre>';print_r($specialty);exit;
			//抓出選單列表
			// $aSpecialty = CSign::$aSpecialty;

			//跑迴圈如果資料庫有對到號碼就把選項勾選
			// $aNewSpecialty = array();
			// foreach ($aSpecialty as $key_1 => $value_1) {
			// 	$aNewSpecialty[$key_1]['sk_no'] = $key_1;
			// 	$aNewSpecialty[$key_1]['sk_name'] = $value_1;
			// 	foreach ($specialty as $key_2 => $value_2) {
			// 		if($key_1 == $value_2){
			// 			$aNewSpecialty[$key_1]['check']=1;
			// 			break;
			// 		}
			// 	}
			// }

			// 擅長樂器
			// $aSignData['music_tool'] = CSign::aGetSignDetail($aSignData['sign_no'],'music_tool');

			// $aMusicTool = array();

			// for($i=0;$i<count($aSignData['music_tool']);$i++){
			// 	$aMusicTool[]= array("music_tool"=>$aSignData['music_tool'][$i]['tool_no'],"ability"=>$aSignData['music_tool'][$i]['level']);
			// }
			// $session->set("gMusicTool",$aMusicTool);

			// $iDbq = $CDbShell->iQuery("SELECT * FROM talent_upload_singer WHERE ta_id=$gMemberId AND up_type=1 LIMIT 0,1");
			// $aRow=$CDbShell->aFetchArray($iDbq);

			// echo "<pre>";print_r($aSignData);exit;
			// $Smarty->assign('aSpecialty',$aNewSpecialty);
			$Smarty->assign('aSignData',$aSignData);
			$Smarty->assign('sAreaName',$area_name);
			$Smarty->assign("web_index",_WEB_INDEX);
			return $output = $Smarty->fetch('./user/sign_edit.html');

		} else {

			$aRow=&$_POST;
			$aFile=&$_FILES;

			// 擅長樂器
			// $aSignData['music_tool'] = CSign::aGetSignDetail($aSignData['sign_no'],'music_tool');
 			$aWorks = CSign::aGetSignDetail($aSignData['sign_no'],'works');

			if(count($aWorks) == 1) $aWorks[]=array();
			$aSignData['works'] = $aWorks;

			// echo "<pre>";print_r($aSignData);exit;
			// echo "<pre>";print_r($aFile);exit;

			//修改頁檢查第二個傳入參數
			CSign::sVaildData($aRow,$aFile,$aSignData);

			$sDate = date("Y-m-d H:i:s");

			$iSignNo = $aSignData['sign_no'];

			// $specialty = '';

			// //如果有選擇演藝專長才將陣列重組成字串
			// if(isset($aRow['specialty'])){
			// 	$specialty = implode (',', $aRow['specialty']);
			// }

			$aFields=array("team_name",'school','department','management',"specialty","music_tool",'experience','flag',"created","modified","is_sync");
			$aValues=array($aRow['team_name'],$aRow['school'],$aRow['department'],$aRow['management'],$aRow['specialty'],$aRow['music_tool_data'],$aRow['experience'],1,$sDate,$sDate,"0");

			$sSql = $CDbShell->sUpdate("sign",$aFields,$aValues,"sign_no = '$iSignNo'");

			if($sSql){

				// 擅長樂器 先清空在新增
				// $sSql = "DELETE FROM music_tool WHERE sign_no = '$iSignNo'";
				// $res = $CDbShell->iQuery($sSql);

				// $aMusicTool = $session->get("gMusicTool");
				// for($i=0;$i<count($aMusicTool);$i++){
				// 	if($aRow['music_tool_'.$i]){
				// 		$aFields=array("sign_no","tool_no","level");
				// 		$aValues=array($iSignNo,$aRow['music_tool_'.$i],$aRow['music_tool_ability_'.$i]);
				// 		$sSql = $CDbShell->sInsert("music_tool",$aFields,$aValues);
				// 	}
				// }

				unset($_SESSION['gMusicTool']);

				//比對有無修改資料，告知報名者修改的欄位
				$update = '';

				//把修改後的擅長樂器撈出來比對修改前的有無不同
				// $music_tool = CSign::aGetSignDetail($iSignNo,'music_tool');

				// echo "<pre>";print_r($aSignData['music_tool']);exit;
				// echo "<pre>";print_r($music_tool);exit;

				if($aSignData['team_name']!=$aRow['team_name']) $update .= '團隊名稱、' ;
				// if($aSignData['sign_type']!=$aRow['sign_type']) $update .= '報名組別、' ;
				// if($aFile['img_1']['name']!='' or $aFile['img_2']['name']!='' or $aFile['img_3']['name']!='') $update .= '個人圖片、' ;
				if($aSignData['school']!=$aRow['school']) $update .= '學校、' ;
				if($aSignData['department']!=$aRow['department']) $update .= '系所、' ;
				if($aSignData['management']!=$aRow['management']) $update .= '經紀約、' ;
				if($aSignData['specialty']!=$aRow['specialty']) $update .= '演藝專長、' ;
				if($aSignData['music_tool']!=$aRow['music_tool_data']) $update .= '擅長樂器、' ;
				if($aSignData['experience']!=$aRow['experience']) $update .= '演藝/參賽經歷、' ;

				if($update!='') $update = substr($update,0,-3);

				// 顯示報名後資訊
				$str = "您的資料已修改完成，\\n";
				$str.= "修改項目為：$update\\n\\n";
				$str.= "入圍名單及相關資訊，將公佈於徵選活動官網，請密切關注，感謝您的支持與配合，謝謝";
				
				//有修改就跳提醒 發mail
				if($update!=''){
					if(!$this->bSignEditMail($member_no,$str)) CJavaScript::vAlertRedirect(_LANG_SENT_MAIL_ERROR,"./"._WEB_INDEX."func=member");
					// CJavaScript::vAlertRedirect($str,"./"._WEB_INDEX."func=sign&action=index");
					CJavaScript::vAlertRedirect($str,"./"._WEB_INDEX."func=sign&action=edit_step2&sign_no=".$iSignNo);
				}else{
					CJavaScript::vRedirect("./"._WEB_INDEX."func=sign&action=edit_step2&sign_no=".$iSignNo);
				}
			}
		}
	}

	function tSignEditStep2() {
		global $Smarty,$CJavaScript,$CDbShell,$session; //obj var

		//有session表示有登入
		if($session->get('member_no')){
			$member_no = $session->get('member_no');
			$member_area = $session->get('area');//會員註冊的國別,跟member_no的一起紀錄
			if($member_area=='1'){
				$area_name="TW";;
			}else{
				$area_name="CN";
			}
		}

		//沒session表示非法登入就導回登入頁面
		if(!$member_no) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member');

		$aSignData = array();
		$aSignData = CSign::aGetData($member_no);

		//抓作品
		$aWorksData = array();
		$aWorksData = CSign::aGetWorksData($aSignData['sign_no']);

		if(!$_POST){

			//抓不到有投稿報名紀錄就導回新增頁面
			if(!$aSignData) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=sign&action=add');
			if(!$aWorksData) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=sign&action=add_step2&sign_no='.$aSignData['sign_no']);

			//報名的作品主檔
			$aWorks = CSign::aGetSignDetail($aSignData['sign_no'],'works');

			//撈出作品的上傳檔案
			foreach ($aWorks as $key => $value) {

				$aWorks[$key]['lyricist_file'] = CSign::aGetFileData($value['works_no'],1);
				$aWorks[$key]['song_file'] = CSign::aGetFileData($value['works_no'],2);
			}

			if(count($aWorks) == 1) $aWorks[]=array();

			$aSignData['works'] = $aWorks;

			// $iDbq = $CDbShell->iQuery("SELECT * FROM talent_upload_singer WHERE ta_id=$gMemberId AND up_type=1 LIMIT 0,1");
			// $aRow=$CDbShell->aFetchArray($iDbq);

			// echo "<pre>";print_r($aSignData);exit;
			$Smarty->assign('iSignNo',$aSignData['sign_no']);
			$Smarty->assign('aSignData',$aSignData);
			$Smarty->assign('sAreaName',$area_name);
			$Smarty->assign("web_index",_WEB_INDEX);
			return $output = $Smarty->fetch('./user/sign_edit_step2.html');

		} else {

			$aRow=&$_POST;
			$aFile=&$_FILES;

			// 擅長樂器
			$aSignData['music_tool'] = CSign::aGetSignDetail($aSignData['sign_no'],'music_tool');
 			$aWorks = CSign::aGetSignDetail($aSignData['sign_no'],'works');

			if(count($aWorks) == 1) $aWorks[]=array('works_no'=>'',
													'sign_no'=>'',
													'type'=>'',
													'song_name'=>'',
													'composer'=>'',
													'lyricist'=>'',
													'creative_concept'=>''
													);
			$aSignData['works'] = $aWorks;

			// echo "<pre>";print_r($aSignData);exit;
			// echo "<pre>";print_r($aFile);exit;

			//修改頁檢查第二個傳入參數
			CSign::sVaildDataStep2($aRow,$aFile,$aSignData);

			$sDate = date("Y-m-d H:i:s");

			$iSignNo = $aSignData['sign_no'];


			if($aRow['sign_no']!=''){

				// //上傳作品
				for ($i=1; $i < 3 ; $i++) {
					//如果有填寫歌曲名稱才當作有作品
					if($aRow['song_name_'.$i]){

						//抓出作品序號 作品一＆作品二
						$iWorksNo = CSign::iGetWorksNo($iSignNo,$i);
						// echo $iWorksNo;

						if($iWorksNo=='0'){
							$iWorksNo =$CDbShell->guid();//撈UUID

							$aFields=array("works_no","sign_no",'type','song_name','lyricist','composer','creative_concept',"created","modified");
							$aValues=array($iWorksNo,$iSignNo,$i,$aRow['song_name_'.$i],$aRow['lyricist_'.$i],$aRow['composer_'.$i],$aRow['creative_concept_'.$i],$sDate,$sDate);
							$sSql_works = $CDbShell->sInsert("works",$aFields,$aValues);
						
						}else{
							$aFields=array('song_name','lyricist','composer','creative_concept',"modified","is_sync");
							$aValues=array($aRow['song_name_'.$i],$aRow['lyricist_'.$i],$aRow['composer_'.$i],$aRow['creative_concept_'.$i],$sDate,"0");

							$sSql_works = $CDbShell->sUpdate("works",$aFields,$aValues,"works_no ='$iWorksNo'");
						}
						

						if($sSql_works){

							if($aFile['lyric_file_'.$i]['name']){
								//抓出舊有的檔案名稱如果上傳成功就刪除舊有的
								$old_file_file = CSign::aGetFileData($iWorksNo,1);

								if(!$this->iUploadWorks($iWorksNo,$aFile['lyric_file_'.$i],$iSignNo,1,$area_name)){
									CJavaScript::vAlertRedirect(_LANG_SIGN_EDIT_FAILURE,"./"._WEB_INDEX."func=sign&action=index");
								}else{
									//上傳成功就刪除舊有檔案
									@unlink("./data/upload/".$area_name."/".$iSignNo.'/'.$old_file_file['file_file']);
								}
							}

							if($aFile['song_file_'.$i]['name']){
								//抓出舊有的檔案名稱如果上傳成功就刪除舊有的
								$old_file_file = CSign::aGetFileData($iWorksNo,2);

								if(!$this->iUploadWorks($iWorksNo,$aFile['song_file_'.$i],$iSignNo,2,$area_name)){
									CJavaScript::vAlertRedirect(_LANG_SIGN_EDIT_FAILURE,"./"._WEB_INDEX."func=sign&action=index");
								}else{
									//上傳成功就刪除舊有檔案
									@unlink("./data/upload/".$area_name."/".$iSignNo.'/'.$old_file_file['file_file']);
								}
							}

							//上傳個人圖片
							for ($j=1; $j < 4 ; $j++) {
								//如果有上傳檔名就跑上傳檔案function
								if($aFile['img_'.$i.'_'.$j]['name']){
									$this->iUploadImg($iWorksNo,$aFile['img_'.$i.'_'.$j],$j,$area_name,$iSignNo);
									//上傳成功刪除舊有的檔案
									@unlink("./data/upload/".$area_name."/".$iSignNo.'/'.$aSignData['img_file_file_'.$i.'_'.$j]);
								}

							}

						}else{
							CJavaScript::vAlertRedirect(_LANG_SIGN_EDIT_FAILURE,"./"._WEB_INDEX."func=sign&action=index");
						}
					}

				}

				//比對有無修改資料，告知報名者修改的欄位
				$update = '';

				//把修改後的擅長樂器撈出來比對修改前的有無不同
				$music_tool = CSign::aGetSignDetail($iSignNo,'music_tool');

				// echo "<pre>";print_r($aSignData['music_tool']);exit;
				// echo "<pre>";print_r($music_tool);exit;

				$works = $aSignData['works'];

				// if($member_no=='714A65D2-E87A-370B-B033-FAD4073251DB'){
				// 	echo "<pre>";print_r($works);exit;
				// }

				foreach ($works as $key => $value) {

					$num = $key+1;

					if($value['song_name']!=$aRow['song_name_'.$num]) $update .= "作品$num 歌曲名稱、" ;
					if($value['lyricist']!=$aRow['lyricist_'.$num]) $update .= "作品$num 詞作者、" ;
					if($value['composer']!=$aRow['composer_'.$num]) $update .= "作品$num 曲作者、" ;
					if($aFile['lyric_file_'.$num]['name']!='' ) $update .= "作品$num 參賽作品歌詞、" ;
					if($aFile['song_file_'.$num]['name']!='' ) $update .= "作品$num 參賽作品歌曲、" ;
					if($value['creative_concept']!=$aRow['creative_concept_'.$num]) $update .= "作品$num 創作理念、" ;
					if($aFile['img_'.$num.'_1']['name']!='' or $aFile['img_'.$num.'_2']['name']!='' or $aFile['img_'.$num.'_3']['name']!='') $update .= '作品'.$num.'個人圖片、' ;
				}
				
				
				if($update!='') $update = substr($update,0,-3);

				// 顯示報名後資訊
				$str = "您的資料已修改完成，\\n";
				$str.= "修改項目為：$update\\n\\n";
				$str.= "入圍名單及相關資訊，將公佈於徵選活動官網，請密切關注，感謝您的支持與配合，謝謝";
				
				//有修改就發mail 及顯示修改資訊
				if($update!=''){

					if(!$this->bSignEditMail($member_no,$str)) CJavaScript::vAlertRedirect(_LANG_SENT_MAIL_ERROR,"./"._WEB_INDEX."func=member");
					
					CJavaScript::vAlertRedirect($str,"./"._WEB_INDEX."func=sign&action=index");
				}else{
					CJavaScript::vRedirect("./"._WEB_INDEX."func=sign&action=index");
				}

				// CJavaScript::vAlertRedirect($str,"./"._WEB_INDEX."func=sign&action=index");
			}
		}
	}

	/**
	* @param $iSignNo 流水號  $aFile 上傳檔案資訊陣列 $iSequence 第幾張圖片 $area_name 註冊地區名稱
	* @return 0 失敗 1 成功
	* @desc 上傳個人圖片
	*/
	function iUploadImg($iWorksNo = '',$aFile=array(),$iSequence = 0,$area_name='',$iSignNo) {
		global $CJavaScript,$CDbShell; //obj var

		// echo $iWorksNo.',';
		// echo "<pre>";print_r($aFile);
		// echo $iSequence;exit;
		if($iWorksNo =='' || $iSignNo=='' || !$aFile['tmp_name']) return 0;
		$iError = 0;
		if($aFile['tmp_name']){
			
			$sDir = './data/upload/'.$area_name.'/';
			
			$sFileName = "";
			$iError = 1;

			if(!is_dir($sDir))mkdir($sDir,0777);
			$sDir .= "$iSignNo/";

			if(!is_dir($sDir))mkdir($sDir,0777);
			$aTmp = explode(".",$aFile['name']);
			$c = count($aTmp);
			if($aTmp[$c-1]) {
				$sFileName = md5(uniqid(rand(), true))."_src.".$aTmp[$c-1];
			} else {
				$sFileName = md5(uniqid(rand(), true))."_src.".$aTmp[1];
			}
			if ($dh = opendir($sDir)) {
				while(false !== ($f = readdir($dh))) {
					if ($f != "." && $f != "..") {
						if(preg_match("/file/i",$f)) $sOldImg = $f;
					}
				}
			}
			closedir($dh);
			// echo "OK";exit;
			$sDate = date("Y-m-d H:i:s");

			if(copy($aFile['tmp_name'], $sDir.$sFileName)) {

				// 非圖片處理
				chmod($sDir.$sFileName,0777);

				//先砍掉現有的檔案在新增
				// $CDbShell->iQuery("DELETE FROM upload_file WHERE ta_id = $iWorksNo AND up_type = $iType");

				$aFields=array("img_file_file_".$iSequence,"img_file_name_".$iSequence,"is_sync");
				$aValues=array($sFileName,$aFile['name'],"0");

				$sSql = $CDbShell->sUpdate('works',$aFields,$aValues,"works_no ='$iWorksNo' ");
				if($sSql){
					$iError=0;
					@unlink($aFile['tmp_name']);
				}else{
					//失敗就把註冊資料刪除
					$CDbShell->iQuery("DELETE FROM works where works_no='$iWorksNo' ");
				}

			} else {
				$CDbShell->iQuery("DELETE FROM works where works_no='$iWorksNo' ");
			}
		}

		if($iError) return 0;
		return 1;
	}

	/**
	* @param $iSignNo 流水號  $aFile 上傳檔案資訊陣列 $iSignNo 要存放的會員序號 $iType 檔案類型
	* @return 0 失敗 1 成功
	* @desc 上傳作品
	*/
	function iUploadWorks($iWorksNo = 0,$aFile=array(),$iSignNo = 0,$iType,$area_name='') {
		global $CJavaScript,$CDbShell,$session; //obj var

		// echo $iSignNo.',';
		// echo "<pre>";print_r($aFile);
		// echo $iSequence;exit;
		if(!$iWorksNo || !$aFile['tmp_name'] || !$iSignNo) return 0;
		$iError = 0;

		if($aFile['tmp_name']){
			
			$sDir = './data/upload/'.$area_name.'/';

			$sFileName = "";
			$iError = 1;

			if(!is_dir($sDir))mkdir($sDir,0777);
			$sDir .= "$iSignNo/";

			if(!is_dir($sDir))mkdir($sDir,0777);
			$aTmp = explode(".",$aFile['name']);
			$c = count($aTmp);
			if($aTmp[$c-1]) {
				$sFileName = md5(uniqid(rand(), true))."_src.".$aTmp[$c-1];
			} else {
				$sFileName = md5(uniqid(rand(), true))."_src.".$aTmp[1];
			}
			if ($dh = opendir($sDir)) {
				while(false !== ($f = readdir($dh))) {
					if ($f != "." && $f != "..") {
						if(preg_match("/file/i",$f)) $sOldImg = $f;
					}
				}
			}
			closedir($dh);

			$sDate = date("Y-m-d H:i:s");

			if(copy($aFile['tmp_name'], $sDir.$sFileName)) {

				// 非圖片處理
				chmod($sDir.$sFileName,0777);

				//先砍掉現有的檔案在新增
				$CDbShell->iQuery("DELETE FROM upload_file WHERE works_no = '$iWorksNo' AND up_type = $iType");

				$up_no =$CDbShell->guid();
				$aFields=array("up_no",'works_no',"up_type","file_file","file_name",'file_type','file_size','created','modified');
				$aValues=array($up_no,$iWorksNo,$iType,$sFileName,$aFile['name'],$aFile['type'],$aFile['size'],$sDate,$sDate);

				$sSql = $CDbShell->sInsert('upload_file',$aFields,$aValues);

				//改直接修改
				// $aFields=array('works_no',"up_type","file_file","file_name",'file_type','file_size','created','modified','is_sync');
				// $aValues=array($iWorksNo,$iType,$sFileName,$aFile['name'],$aFile['type'],$aFile['size'],$sDate,$sDate,"0");
				// $sSql = $CDbShell->sUpdate('upload_file',$aFields,$aValues,"works_no = '$iWorksNo' AND up_type = $iType ");


				if($sSql){
					$iError=0;
					@unlink($aFile['tmp_name']);
				}else{
					return 0;
					//失敗就把註冊資料刪除
					// $CDbShell->iQuery("DELETE FROM upload_file where sign_no='$iSignNo' ");
				}

			} else {
				return 0;
				// $CDbShell->iQuery("DELETE FROM sign where sign_no='$iSignNo' ");
			}
		}

		if($iError) return 0;
		return 1;
	}

	//投稿成功系統寄出通知信
	function bSignOkMail($member_no = 0){

		global $Smarty,$CJavaScript,$CDbShell,$PHPMailer,$session,$CCharset; //obj var

		if(!$member_no) return false;
		// $WebIndexSrc = $session->get("WebIndexSrc");//在youth_index 設定初始值判斷國別
		$aRow = CMember::aGetData($member_no);

		$Smarty->assign("aRow",$aRow);
		// $Smarty->assign('WebIndexSrc',$WebIndexSrc);
		$mailBody = $Smarty->fetch("./user/sign_signup_add_ok_mail.html");

		$gCharSet = $session->get("gCharSet");
		//如果是簡體就重新編碼
		if($gCharSet=="gb"){
			$mailBody = $CCharset->chg_utfcode($mailBody,"gb");
		}

		//信件的相關資訊
		$mail = CMemberUser::bMailInformation($aRow);

		
		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->SMTPOptions = array(
		    	'ssl' => array(
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    )
		);
		// $mail->SMTPDebug = 3;
		$mail->SMTPAuth = false;
		$mail->SMTPSecure = false;
		$mail->Host = "172.16.1.89";
		$mail->Port = 25;
		$mail->CharSet = 'UTF-8';

		// 收件者信箱
		$email= $aRow['account'];
		// 收件者的名稱or暱稱
		$name= $aRow['name'];

		$webmaster_email = _Web_Master_Email;
		$mail->From = $webmaster_email;

		// 寄件者名稱(你自己要顯示的名稱)
		$mail->FromName = _Web_Master_Email_Name;
		//回覆信件至此信箱

		//這不用改
		$mail->AddAddress($email,$name);
		$mail->AddReplyTo($webmaster_email,_Web_Master_Email_Name);

		//CC and BCC
		// $mail->addBcc("bill.yeh@iwant-in.net","Bill");
		// $mail->addBcc("tzuhung.lin@iwant-in.net","Bill");
		// $mail->addBcc("jiexun.lu@iwant-in.net","Jason");
		$mail->addBcc("jerry.he@iwant-in.net","Jerry");

		//Send HTML or Plain Text email
		$mail->isHTML(true); 

		// 信件標題
		$mail->Subject  = "您已完成報名青春頌─兩岸青年原創金曲大選";

		//信件內容(html版，就是可以有html標籤的如粗體、斜體之類)
		$mail->Body = $mailBody;
		//信件內容(純文字版)
		// $mail->AltBody = $mailBody;

		// if($mail->Send()){
		// 	return true;
		// //如果有錯誤會印出原因
		// }else{
		// 	return false;
		// }

		if(!$mail->send()){
		    echo "Mailer Error: " . $mail->ErrorInfo;
		    exit;
		    return true;
		}else{
		    return true;
		}

	}

	//投稿成功系統寄出通知信
	function bSignEditMail($member_no = 0,$edit_str){

		global $Smarty,$CJavaScript,$CDbShell,$PHPMailer,$session,$CCharset; //obj var

		if(!$member_no) return false;
		$WebIndexSrc = $session->get("WebIndexSrc");//在youth_index 設定初始值判斷國別
		$aRow = CMember::aGetData($member_no);

		$Smarty->assign("aRow",$aRow);

		$edit_str = str_replace('\\n','<br>',$edit_str);
		$Smarty->assign("sEditDetail",$edit_str);
		$Smarty->assign('WebIndexSrc',$WebIndexSrc);
		$mailBody = $Smarty->fetch("./user/sign_signup_edit_mail.html");

		$gCharSet = $session->get("gCharSet");
		//如果是簡體就重新編碼
		if($gCharSet=="gb"){
			$mailBody = $CCharset->chg_utfcode($mailBody,"gb");
		}

		//信件的相關資訊
		$mail = CMemberUser::bMailInformation($aRow);

		// 信件標題
		$mail->Subject  = "您已修改青春頌─兩岸青年原創金曲大選！";

		//信件內容(html版，就是可以有html標籤的如粗體、斜體之類)
		$mail->Body = $mailBody;
		//信件內容(純文字版)
		// $mail->AltBody = $mailBody;

		// if($mail->Send()){
		// 	return true;
		// //如果有錯誤會印出原因
		// }else{
		// 	return false;
		// }

		if(!$mail->send()){
		    echo "Mailer Error: " . $mail->ErrorInfo;
		    exit;
		    return true;
		}else{
		    return true;
		}

	}


}