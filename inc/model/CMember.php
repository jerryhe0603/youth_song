<?php

include_once(PATH_ROOT.'/inc/CMisc.php');
include_once(PATH_ROOT.'/inc/model/CGalaxyClass.php');
include_once(PATH_ROOT.'/inc/model/CUser.php');

class CMember extends CGalaxyClass {

	static protected $sDBName = 'MYSQL';
	static public $aInstancePool = array();

	public function __construct($multiData){
		parent::__construct($multiData);

	}

	static public function aAllMember($sSearchSql='',$sPostFix=''){

		$oDB = self::oDB(self::$sDBName);
		$aAllMember = array();

		$sSql = "SELECT * FROM member";
		if($sSearchSql!=='') $sSql .= " WHERE $sSearchSql";
		if($sPostFix!=='') $sSql .= " $sPostFix";

		$iDbq = $oDB->iQuery($sSql);

		while($aRow = $oDB->aFetchAssoc($iDbq)){
			$aAllMember[] = $aRow;
		}

		return $aAllMember;
	}

	static public function aGetData($member_no = ''){

		$oDB = self::oDB(self::$sDBName);

		$aRow = array();

		$sSql = "SELECT * FROM member WHERE member_no = '$member_no'";
		$iDbq = $oDB->iQuery($sSql);

		if ($oDB->iNumRows($iDbq)==0) return $aRow;

		$aRow = $oDB->aFetchAssoc($iDbq);

		return $aRow;
	}

	static public function iGetCount($sSearchSql=''){
		$oDB = self::oDB(self::$sDBName);
		$sSql = "SELECT count(member_no) as total FROM member";
		if($sSearchSql!=='')
			$sSql .= " WHERE $sSearchSql";
		$iDbq = $oDB->iQuery($sSql);
		$aRow = $oDB->aFetchAssoc($iDbq);
		if($aRow!==false)
			$iCount = (int)$aRow['total'];
		else
			$iCount = 0;
		return $iCount;
	}

	//取得某個會員編號底下全部的報名表
	static public function aGetAllSign($member_no = 0){
		$oDB = self::oDB(self::$sDBName);

		$aSign = array();

		if(!$member_no) return $aSign;

		$sSql = "SELECT * FROM sign WHERE member_no= '$member_no' AND flag != 9 ORDER BY type ";

		$iDbq = $oDB->iQuery($sSql);

		while($aRow = $oDB->aFetchAssoc($iDbq)){
			$aSign[] = $aRow;
		}

		return $aSign;
	}

	//檢查登入
	static public function sVaildLoginData($postData=array()){
		global $session; //obj var

		$oDB = self::oDB(self::$sDBName);

		$aErrorMsg = array();

		if(!$postData['from_fb']){
			if (!trim($postData['account'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT;
			if (!trim($postData['password'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_PASSWORD;
			$account = $postData['account'];
			$password = md5($postData['password']);

			if(trim($postData['account']) AND trim($postData['password'])){

				$sSql = "SELECT * FROM member WHERE account = '$account' AND password = '$password'";
				// echo $sSql;exit;
				$iDbq = $oDB->iQuery($sSql);
				$aRow = $oDB->aFetchAssoc($iDbq);

				if($oDB->iNumRows($iDbq)==0 ){
					$aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT_PASSWORD_MISTAKE;
				}else{
					if(!$aRow['flag']) $aErrorMsg[] = _LANG_MEMBER_NOT_OPEN_ACCOUNT;
					if($aRow['flag'] == 9) $aErrorMsg[] = _LANG_VALID_ACCOUNT;
				}
			}


		}else{

			if (!trim($postData['fb_account'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT;
			$account = $postData['fb_account'];

			$sSql = "SELECT * FROM member WHERE account = '$account' AND from_fb = '1' ";
			// echo $sSql;exit;
			$iDbq = $oDB->iQuery($sSql);
			$aRow = $oDB->aFetchAssoc($iDbq);

			if($oDB->iNumRows($iDbq)==0 ){
				$aErrorMsg[] = _LANG_MEMBER_VAILD_FB_ACCOUNT_ERROR;
			}else{
				if(!$aRow['flag']) $aErrorMsg[] = _LANG_MEMBER_NOT_OPEN_ACCOUNT;
				if($aRow['flag'] == 9) $aErrorMsg[] = _LANG_VALID_ACCOUNT;
			}


		}
		

		

		// if(trim($postData['account']) AND trim($postData['password'])){

		// 	$sSql = "SELECT * FROM member WHERE account = '$account' AND password = '$password'";
		// 	// echo $sSql;exit;
		// 	$iDbq = $oDB->iQuery($sSql);
		// 	$aRow = $oDB->aFetchAssoc($iDbq);

		// 	if($oDB->iNumRows($iDbq)==0 ){
		// 		$aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT_PASSWORD_MISTAKE;
		// 	}else{
		// 		if(!$aRow['flag']) $aErrorMsg[] = _LANG_MEMBER_NOT_OPEN_ACCOUNT;
		// 		if($aRow['flag'] == 9) $aErrorMsg[] = _LANG_VALID_ACCOUNT;
		// 	}
		// }

		$sErrorMsg = "";

		//form submit vaild data
		if(count($aErrorMsg) > 0){

			$sErrorMsg = implode("\\n",$aErrorMsg);
			CJavaScript::vAlertRedirect($sErrorMsg,"./"._WEB_INDEX."func=member");

		}else{

			//將登入的使用者直接存入session
			$session->set('member_no',$aRow['member_no']);
			$session->set('area',$aRow['area']);//註冊時的國別 1為tw 2為cn
			return 1;
		}

	}


	//檢查會院帳號註冊欄位值 @member_no 有值為修改頁檢查
	static public function sVaildMemberSignupData($postData=array(),$member_no=0){
		global $session,$CDbShell; //obj var

		$oDB = self::oDB(self::$sDBName);
		$aErrorMsg = array();
		// echo "<pre>";print_r($postData);exit;

		//區分修改頁跟註冊頁檢查不同
		if(!$member_no){
			//先判斷有填寫帳號，有填寫在判斷帳號可行性
			if($postData['from_fb'] && $postData['account']=='')$postData['account'] = $postData['fb_account'];

			if (!trim($postData['account'])){

				$aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT;

			}else{

				$account = trim($postData['account']);
				$sSql = "SELECT * FROM member WHERE account = '$account'";
				$iDbq = $oDB->iQuery($sSql);
				//有填寫帳號先比對資料庫是否已有帳號
				if($oDB->iNumRows($iDbq) > 0){
					$aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT_DOUBLE;
				//資料庫沒有在比信箱帳號的正確性
				}else if (!validator::bCheckEmail($account)){
					$aErrorMsg[] = _LANG_MEMBER_EMAIL_ERROR;
				}
			}

			//如果使用fb登入就不用檢查密碼
			if(!$postData['from_fb']){
				if (!trim($postData['password'])){
				$aErrorMsg[] = _LANG_MEMBER_VAILD_PASSWORD;
				}else if(strlen($postData['password']) < 6){
					$aErrorMsg[] = _LANG_MEMBER_PASSWORD_LENGTH;
				}

				if (!trim($postData['confirm_password'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_CONFIRM_PASSWORD;
			}
			

		}else{
			//修改頁不檢查帳號
			if (trim($postData['password']) AND strlen($postData['password']) < 6) $aErrorMsg[] = _LANG_MEMBER_PASSWORD_LENGTH;

		}

		if($postData['confirm_password'] != trim($postData['password']))  $aErrorMsg[] = _LANG_MEMBER_VAILD_PASSWORD_MISTAKE;


		$sErrorMsg = "";

		//form submit vaild data
		if(count($aErrorMsg) > 0){

			$sErrorMsg = implode("\\n",$aErrorMsg);
			CJavaScript::vAlert($sErrorMsg);
			CJavaScript::vBack();
			exit;

		}else{
			return 1;
		}
	}

	//檢查註冊欄位值 @member_no 有值為修改頁檢查
	static public function sVaildSignupData($postData=array(),$member_no=0){
		global $session,$CDbShell; //obj var

		$oDB = self::oDB(self::$sDBName);
		$aErrorMsg = array();
		// echo "<pre>";print_r($postData);exit;

		//區分修改頁跟註冊頁檢查不同
		if(!$member_no){
			//先判斷有填寫帳號，有填寫在判斷帳號可行性
			// if (!trim($postData['account'])){

			// 	$aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT;

			// }else{

			// 	$account = trim($postData['account']);
			// 	$sSql = "SELECT * FROM member WHERE account = '$account'";
			// 	$iDbq = $oDB->iQuery($sSql);
			// 	//有填寫帳號先比對資料庫是否已有帳號
			// 	if($oDB->iNumRows($iDbq) > 0){
			// 		$aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT_DOUBLE;
			// 	//資料庫沒有在比信箱帳號的正確性
			// 	}else if (!validator::bCheckEmail($account)){
			// 		$aErrorMsg[] = _LANG_MEMBER_EMAIL_ERROR;
			// 	}
			// }

			// if (!trim($postData['password'])){
			// 	$aErrorMsg[] = _LANG_MEMBER_VAILD_PASSWORD;
			// }else if(strlen($postData['password']) < 6){
			// 	$aErrorMsg[] = _LANG_MEMBER_PASSWORD_LENGTH;
			// }

			// if (!trim($postData['confirm_password'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_CONFIRM_PASSWORD;

		}else{
			//修改頁不檢查帳號
			if (trim($postData['password']) AND strlen($postData['password']) < 6) $aErrorMsg[] = _LANG_MEMBER_PASSWORD_LENGTH;

		}

		if($postData['confirm_password'] != trim($postData['password']))  $aErrorMsg[] = _LANG_MEMBER_VAILD_PASSWORD_MISTAKE;

		if (!trim($postData['name'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_NAME;

		if (!trim($postData['area'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_AREA;
		if ($postData['sex']=='') $aErrorMsg[] = _LANG_MEMBER_VAILD_SEX;

		//註冊頁才須檢查身份證
		if(!$member_no){
			//身份證字號驗證
			if (!trim($postData['uid'])){
				 $aErrorMsg[] = _LANG_MEMBER_VAILD_UID;
			}else{

				$uid = trim($postData['uid']);

				if($postData['area'] ==1){
					//台灣地區身份證驗證
					if(!validator::bCheckIdendity($uid)) $aErrorMsg[] = _LANG_MEMBER_UID_ERROR;
				}else if($postData['area'] ==2){
					//中國地區身份證驗證
					if(!validator::bCheckChinaUid($uid)) $aErrorMsg[] = _LANG_MEMBER_UID_ERROR;
				}

				$sSql = "SELECT * FROM member WHERE uid = '$uid'";
				$iDbq = $oDB->iQuery($sSql);
				if($oDB->iNumRows($iDbq) > 0) $aErrorMsg[] = _LANG_MEMBER_UID_DOUBLE;

			}
		}

		if (!trim($postData['year']) || !trim($postData['month']) || !trim($postData['day'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_BIRTHDAY;
		// if (!trim($postData['birthday'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_BIRTHDAY;


		//判斷有無輸入手機，有輸入就進行可行性判斷
		if (!trim($postData['phone'])){
			$aErrorMsg[] = _LANG_MEMBER_VAILD_PHONE;
		}else{
			$phone = $postData['phone'];
			//修改頁的重複性判斷
			if($member_no){
				$iDbq = $CDbShell->iQuery("SELECT * FROM member WHERE phone='$phone' AND member_no!='$member_no'");
			}else{
				$iDbq = $CDbShell->iQuery("SELECT * FROM member WHERE phone='$phone'");
			}

			if($CDbShell->iNumRows($iDbq) > 0) $aErrorMsg[] = _LANG_MEMBER_PHONE_EXIST;
		}

		if (!trim($postData['address'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_ADDRESS;

		//台灣地區臉書名稱必填
		if($postData['area'] ==1){
			if (!trim($postData['fb_name'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_FB_NAME;
		}
		

		//註冊時再次驗證手機號碼跟驗證碼的正確性
		if(!$member_no){
						
			$mobile = $postData['phone'];
			$iDbq = $CDbShell->iQuery("SELECT * FROM member WHERE phone='$mobile'");				

			if (!trim($postData['phone'])) $aErrorMsg[] = _LANG_SIGN_VAILD_MOBILE;
			else if (!validator::bCheckMobile($postData['phone'])) $aErrorMsg[] = _LANG_SIGN_MOBILE_ERROR;
			else if($CDbShell->iNumRows($iDbq) > 0) $aErrorMsg[] = _LANG_SIGN_MOBILE_EXIST;

			$iDbq = $CDbShell->iQuery("SELECT * FROM talent_verify WHERE ta_mobile='$mobile' ORDER BY created DESC LIMIT 1");
			$aMobile = $CDbShell->aFetchArray($iDbq);	
			
			if(!trim($postData['verify_code'])){
				$aErrorMsg[] = _LANG_SIGN_VERIFY_CODE_ERROR;					
			}else if($postData['verify_code'] <> $aMobile['verify_code']){ 
				$aErrorMsg[] = _LANG_SIGN_VERIFY_CODE_ERROR;			
			}		
		}

		//註冊時才須檢查圖形驗證
		if(!$member_no){
			if(!$session->get("gAuthCode")){
				$aErrorMsg[]=_LANG_MEMBER_VAILD_AUTH_CODE_ERROR;
			}elseif(strlen(trim($postData['auth_code'])) == 0){
				$aErrorMsg[]=_LANG_MEMBER_VAILD_AUTH_CODE;
			}elseif($postData['auth_code'] != $session->get("gAuthCode")){
				$aErrorMsg[]=_LANG_MEMBER_VAILD_AUTH_CODE_ERROR;
			}
		}


		if (!trim($postData['agree'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_AGREE;

		$sErrorMsg = "";

		//form submit vaild data
		if(count($aErrorMsg) > 0){

			$sErrorMsg = implode("\\n",$aErrorMsg);
			CJavaScript::vAlert($sErrorMsg);
			CJavaScript::vBack();
			exit;

		}else{
			return 1;
		}
	}

	static public function sVaildForgetData($postData=array()){
		global $CDbShell; //obj var

		$oDB = self::oDB(self::$sDBName);
		$aErrorMsg = array();

		if($postData['account']!=''){
			$sType='1';
		}else{
			$sType='2';
		}

		//忘記密碼
		if($sType == '1'){

			if (!trim($postData['account'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT;
			if (!trim($postData['phone'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_PHONE;

			$account = trim($postData['account']);
			$mobile = trim($postData['phone']);

			if($mobile and $account){
				$iDbq = $CDbShell->iQuery("SELECT * FROM member WHERE phone='$mobile' AND account = '$account'");
				if($oDB->iNumRows($iDbq) == 0){
					$aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT_PASSWORD_PHONE_MISTAKE;
				}else{
					//如果帳號尚未開通，則提醒使用者先開通
					// $aRow = $oDB->aFetchAssoc($iDbq);
					// if(!$aRow['flag']) $aErrorMsg[] = _LANG_MEMBER_NOT_OPEN_ACCOUNT;
				}
			}
		//忘記帳號
		}else if($sType == '2'){
			if (!trim($postData['name'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_NAME;
			if (!trim($postData['uid'])) $aErrorMsg[] = _LANG_MEMBER_VAILD_UID;

			$name = trim($postData['name']);
			$uid = trim($postData['uid']);

			if($name and $uid){
				$iDbq = $CDbShell->iQuery("SELECT * FROM member WHERE name='$name' AND uid = '$uid'");
				if($oDB->iNumRows($iDbq) == 0){
					$aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT_ERROR;
				}else{
					$aRow = $oDB->aFetchAssoc($iDbq);
					$aErrorMsg[] = _LANG_MEMBER_ACCOUNT.$aRow['account'];
				}

			}
		}

		$sErrorMsg = "";

		//form submit vaild data
		if(count($aErrorMsg) > 0){

			$sErrorMsg = implode("\\n",$aErrorMsg);
			CJavaScript::vAlert($sErrorMsg);
			CJavaScript::vBack();
			exit;

		}else{
			return 1;
		}

	}

	static public function sVaildResent($postData=array()){
		global $CDbShell; //obj var

		$oDB = self::oDB(self::$sDBName);
		$aErrorMsg = array();

		if (!trim($postData['account'])) $aErrorMsg[] = _LANG_SIGN_VAILD_ACCOUNT;
		// if (!trim($postData['password'])) $aErrorMsg[] = _LANG_SIGN_VAILD_PASSWORD;

		$account = trim($postData['account']);
		// $password = trim($postData['password']);

		if($account){

			// $password =md5($password);

			$iDbq = $CDbShell->iQuery("SELECT * FROM member WHERE account='$account' ");
			if($oDB->iNumRows($iDbq)){

				$aRow = $oDB->aFetchAssoc($iDbq);
				//flag為1表示已啟用帳號就不進行寄信直接告知使用者
				if($aRow['flag']) $aErrorMsg[] = _LANG_ACCOUNT_IS_OPEN;

			}else{
				$aErrorMsg[] = _LANG_MEMBER_VAILD_ACCOUNT_PASSWORD_MISTAKE;
			}

		}

		$sErrorMsg = "";

		//form submit vaild data
		if(count($aErrorMsg) > 0){

			$sErrorMsg = implode("\\n",$aErrorMsg);
			CJavaScript::vAlert($sErrorMsg);
			CJavaScript::vBack();
			exit;

		}else{
			return 1;
		}

	}
}

?>