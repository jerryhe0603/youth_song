<?php

/********************************************************************
 * @heading(標題):
			CMemberUser 基本會員管理類別
 * @author(作者) :
 * @purpose(目的) :
			基本會員管理類別
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
			2016/11/24
 ********************************************************************/

include_once('./inc/controller/CGalaxyController.php');
include_once('./inc/model/CMember.php');
include_once('./PHPMailer/PHPMailerAutoload.php');

class CMemberUser extends CGalaxyController{

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
			case "login": // 登入
				return $this->tMemberLogin();
				break;
			case "logout": // 登出
				return $this->tMemberLogout();
				break;
			case "logout_index": // 登出
				return $this->tMemberLogoutIndex();
				break;
			case "member_signup": // 註冊帳號
				return $this->tMemberSignup();
				break;
			case "add": // 註冊新會員
				return $this->tMemberAdd();
				break;
			case "open_account": // 開通帳號
				return $this->tMemberOpenAccount();
				break;
			case "edit": // 修改基本資料
				return $this->tMemberEdit();
				break;
			case "forget_password": // 忘記帳號＆忘記密碼
				return $this->tMemberForgetPassword();
				break;
			case "resent": //重新發送啟用信
				return $this->tMemberResent();
				break;
			default :// 會員首頁
				return $this->tMemberIndex(); // show
				break;
		}
	}

	//票選首頁
	function tMemberIndex() {
		global $Smarty,$CJavaScript,$CDbShell,$session; //obj var

		$member_no = 0;

		//有session表示已登入，就導到會員基本資料頁面
		if($session->get('member_no')){
			$member_no = $session->get('member_no');
		}else{
			$member_no = 0;
		}

		if(!$member_no) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member&action=login');

		$aData = array();
		$aData = CMember::aGetData($member_no);

		//抓不到資料也導回去登入頁面
		if(!$aData) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member&action=login');
		// echo "<pre>";print_r($aData);exit;
		$Smarty->assign('aData',$aData);
		$Smarty->assign("web_index",_WEB_INDEX);
		return $output = $Smarty->fetch('./user/member_show.html');

	}

	function tMemberLogin() {
		global $Smarty,$CJavaScript,$CDbShell,$session; //obj var

		$member_no = '';
		//有session表示已登入，就導到會員基本資料頁面
		if($session->get('member_no')){
			$member_no = $session->get('member_no');
		}

		if($member_no) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member');

		if(!$_POST){

			// $CJavaScript->vRedirect('./index.php?func=member&action=login');
			// $Smarty->assign("VoteLoginSubmit",$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&time=".time());
			$Smarty->assign('appId',_FB_APPID);
			$Smarty->assign('web_index',_WEB_INDEX);
			return $output = $Smarty->fetch('./user/member_login.html');

		} else {

			$aRow=&$_POST;

			CMember::sVaildLoginData($aRow);

			$CJavaScript->vRedirect("./"._WEB_INDEX."func=member");
			// CJavaScript::vAlertRedirect(_LANG_LOGIN_SUCCESS,"./"._WEB_INDEX."func=member");

		}
	}

	function tMemberLogout() {
		global $CJavaScript,$session; //obj var

		unset($_SESSION['member_no']);
		// session_unset();
		CJavaScript::vAlertRedirect(_LANG_LOGOUT_SUCCESS,"./"._WEB_INDEX."func=member");

	}

	function tMemberLogoutIndex() {
		global $CJavaScript,$session; //obj var

		unset($_SESSION['member_no']);
		// session_unset();
		CJavaScript::vAlertRedirect(_LANG_LOGOUT_SUCCESS,"./"._WEB_INDEX);

	}

	/**
	 * 會員註冊帳號
	 */
	function tMemberSignup() {
		global $Smarty,$CJavaScript,$CDbShell,$session,$CMisc; //obj var

		//有session表示已登入，就導到會員基本資料頁面，不讓使用者在進行註冊
		if($session->get('member_no')){
			$member_no = $session->get('member_no');
		}else{
			$member_no = 0;
		}

		if($member_no) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member');
		if(!$_POST){

			$token = md5(uniqid(rand(), true));
			$session->set("token",$token);

			$Smarty->assign("token",$token);
			$Smarty->assign('appId',_FB_APPID);
			$Smarty->assign("web_index",_WEB_INDEX);
			return $output = $Smarty->fetch('./user/member_signup.html');

		} else {

			$aRow=&$_POST;
			// echo "<pre>";print_r($aRow);exit;
			CMember::sVaildMemberSignupData($aRow);

			$sToken = $session->get("token");
			// 防止遠端表格 POST(remote form posting) (check token)
			if ($aRow['token'] != $sToken or !$sToken or !$aRow['token']) {
				CJavaScript::vAlertRedirect(_LANG_MEMBER_SIGNUP_FAILURE,"./"._WEB_INDEX."func=member");
			}

			if($aRow['from_fb'] && $aRow['account']=='')$aRow['account']=$aRow['fb_account'];

			$session->set('member_account',$aRow['account']);
			// $session->set('member_password',$aRow['password']);

			$member_detail='';
			$member_detail='&member_account='.$aRow['account'].'&member_password='.$aRow['password'].'&from_fb='.$aRow['from_fb'];

			$CJavaScript->vRedirect('./'._WEB_INDEX.'func=member&action=add'.$member_detail);
		}
	}

	function tMemberAdd() {
		global $Smarty,$CJavaScript,$CDbShell,$session,$CMisc; //obj var

		//有session表示已登入，就導到會員基本資料頁面，不讓使用者在進行註冊
		if($session->get('member_no')){
			$member_no = $session->get('member_no');
		}else{
			$member_no = 0;
		}

		if($member_no) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member');

		if(!$_POST){
			$smember_account =  $session->get('member_account');
			$sMemberAccount = isset($_GET['member_account'])?$_GET['member_account']:"";
			$sMemberPassword = isset($_GET['member_password'])?$_GET['member_password']:"";
			$sFromFb = isset($_GET['from_fb'])?$_GET['from_fb']:"0";

			//如果session跟post過來的值不一樣就導回
			// echo $sMemberAccount.'=='.$smember_account;exit;
			if (($smember_account != $sMemberAccount) || ($sMemberAccount=='')) {
				CJavaScript::vAlertRedirect(_LANG_MEMBER_SIGNUP_FAILURE,"./"._WEB_INDEX."func=member");
			}

			$token = md5(uniqid(rand(), true));
			$session->set("token",$token);




			$Smarty->assign("member_account",$sMemberAccount);
			$Smarty->assign("member_password",$sMemberPassword);
			$Smarty->assign("from_fb",$sFromFb);

			$Smarty->assign("token",$token);
			$Smarty->assign("web_index",_WEB_INDEX);
			return $output = $Smarty->fetch('./user/member_add.html');

		} else {


			$aRow=&$_POST;

			$smember_account =  $session->get('member_account');
			$sMemberAccount = $aRow['member_account'];
			$sMemberPassword = $aRow['member_password'];
			$sFromFb = $aRow['from_fb'];
			$aRow['birthday'] = $aRow['year'].'-'.$aRow['month'].'-'.$aRow['day'];
			// echo $aRow['birthday'];exit;
			//如果session跟post過來的值不一樣就導回
			if ($smember_account != $sMemberAccount) {
				CJavaScript::vAlertRedirect(_LANG_MEMBER_SIGNUP_FAILURE,"./"._WEB_INDEX."func=member");
			}


			// echo "<pre>";print_r($aRow);exit;
			CMember::sVaildSignupData($aRow);

			$sToken = $session->get("token");
			// 防止遠端表格 POST(remote form posting) (check token)
			if ($aRow['token'] != $sToken or !$sToken or !$aRow['token']) {
				CJavaScript::vAlertRedirect(_LANG_MEMBER_SIGNUP_FAILURE,"./"._WEB_INDEX."func=member");
			}

			$sDate = date("Y-m-d H:i:s");
			//信箱驗證碼
			$sVerifyCode = $CMisc->sRandomString('',10);

			$sMemberPassword = md5($sMemberPassword);

			$sUserIP = '';

			if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		   		$sUserIP = $_SERVER['HTTP_CLIENT_IP'];
			}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
				$sUserIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}else{
				$sUserIP= $_SERVER['REMOTE_ADDR'];
			}


			$sMemberId =$CDbShell->guid();
			//新增一個使用者
			$aFields=array("member_no","account","password","name",'area','sex','uid','birthday',"phone",'address',"flag","verify_code","created","modified","user_ip","user_agent","session_key","from_fb","fb_name");
			$aValues=array($sMemberId,$sMemberAccount,$sMemberPassword,$aRow['name'],$aRow['area'],$aRow['sex'],$aRow['uid'],$aRow['birthday'],$aRow['phone'],$aRow['address'],0,$sVerifyCode,$sDate,$sDate,$sUserIP,$_SERVER['HTTP_USER_AGENT'],session_id(),$sFromFb,$aRow['fb_name']);

			$sSql = $CDbShell->sInsert("member",$aFields,$aValues);

			if($sSql){

				// $insertId = $CDbShell->iGetInsertId();

				$aMember =  CMember::aGetData($sMemberId);
				// echo "<pre>";print_r($aMember);exit;
				//發送信件
				if(!$this->bSignOkMail($aMember)) CJavaScript::vAlertRedirect(_LANG_SENT_MAIL_ERROR,"./"._WEB_INDEX."func=member");

				CJavaScript::vAlertRedirect(_LANG_MEMBER_SIGNUP_SUCCESS.'('.$sMemberAccount.')'._LANG_MEMBER_SIGNUP_SUCCESS2,"./"._WEB_INDEX."func=member");
			}

			CJavaScript::vAlertRedirect(_LANG_MEMBER_SIGNUP_FAILURE,"./"._WEB_INDEX."func=member");
		}
	}

	function tMemberEdit() {
		global $Smarty,$CJavaScript,$CDbShell,$session; //obj var

		//有session表示有登入，才讓使用者進行修改，不然就導回登入頁面
		if($session->get('member_no')){
			$member_no = $session->get('member_no');
		}else{
			$member_no = 0 ;
		}

		if(!$member_no) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member');

		$aData = array();
		$aData = CMember::aGetData($member_no);

		//抓不到資料也導回去登入頁面
		if(!$aData) $CJavaScript->vRedirect('./'._WEB_INDEX.'func=member&action=login');

		if(!$_POST){

			// echo "<pre>";print_r($aData);exit;
			$Smarty->assign('aData',$aData);
			$Smarty->assign("web_index",_WEB_INDEX);
			return $output = $Smarty->fetch('./user/member_edit.html');

		} else {

			$aRow=&$_POST;

			//修改頁檢查第二個傳入參數
			CMember::sVaildSignupData($aRow,$member_no);

			$sDate = date("Y-m-d H:i:s");

			$password ='';
			if(trim($aRow['password'])){
				$password = md5(trim($aRow['password']));
			}else{
				$password = $aData['password'];
			}

			//新增一個使用者
			$aFields=array("password","name",'sex','birthday','phone','address',"modified","is_sync");
			$aValues=array($password,$aRow['name'],$aRow['sex'],$aRow['birthday'],$aRow['phone'],$aRow['address'],$sDate,"0");

			$sSql = $CDbShell->sUpdate("member",$aFields,$aValues,"member_no='$member_no'");

			if($sSql){

				CJavaScript::vAlertRedirect(_LANG_MEMBER_EDIT_SUCCESS,"./"._WEB_INDEX."func=member");
			}

			CJavaScript::vAlertRedirect(_LANG_MEMBER_EDIT_FAILURE,"./"._WEB_INDEX."func=member");
		}
	}

	//忘記密碼
	function tMemberForgetPassword() {
		global $Smarty,$CJavaScript,$CDbShell,$session,$CMisc; //obj var

		if(!$_POST){

			// $CJavaScript->vRedirect('./index.php?func=member');
			// $Smarty->assign("VoteSignupSubmit",$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&time=".time());
			$Smarty->assign("web_index",_WEB_INDEX);
			return $output = $Smarty->fetch('./user/member_forget_password.html');

		} else {

			$aRow=&$_POST;

			CMember::sVaildForgetData($aRow);
			$sDate = date("Y-m-d H:i:s");
			$account = $aRow['account'];
			$uid = $aRow['uid'];

			if($account!=''){
				$sSqlMember = "SELECT * FROM member WHERE account = '$account'";
			}else if($uid!=''){
				$sSqlMember = "SELECT * FROM member WHERE uid = '$uid'";
			}

			//撈出使用者資料重置密碼並且寄送密碼信
			$iDbq = $CDbShell->iQuery($sSqlMember);
			$aMemberData = $CDbShell->aFetchAssoc($iDbq);

			if($aMemberData){

				$reset_password = $CMisc->sRandomString('',8);
				$md5_reset = md5($reset_password);

				$member_no = $aMemberData['member_no'];

				$aFields=array("password","modified","is_sync");
				$aValues=array($md5_reset,$sDate,"0");

				$sSql = $CDbShell->sUpdate("member",$aFields,$aValues,"member_no='$member_no'");

				if($sSql){

					$this->bResetPasswordMail($aMemberData,$reset_password);
					CJavaScript::vAlertRedirect(_LANG_SENT_PASSWORD_LETTER_SUCCESS,"./"._WEB_INDEX."func=member");
				}
			}

			CJavaScript::vAlertRedirect(_LANG_SENT_PASSWORD_LETTER_FAILURE,"./"._WEB_INDEX."func=member");
		}
	}

	//開通帳號
	function tMemberOpenAccount() {
		global $Smarty,$CJavaScript,$CDbShell; //obj var

		if(empty($_GET['account'])) CJavaScript::vUpdateThisFrame('./'._WEB_INDEX.'func=member');
		if(empty($_GET['verify_code'])) CJavaScript::vUpdateThisFrame('./'._WEB_INDEX.'func=member');

		$account = $_GET['account'];
		$verify_code = $_GET['verify_code'];

		$sSql = "SELECT * FROM member WHERE account='$account' AND verify_code='$verify_code'";
		$iDbq = $CDbShell->iQuery($sSql);
		//如果比對有資料就更新狀態為啟用
		if($CDbShell->iNumRows($iDbq)){

			$sDate = date("Y-m-d H:i:s");

			$aFields=array("flag",'start_time',"is_sync");
			$aValues=array(1,$sDate,"0");

			$sSql = $CDbShell->sUpdate("member",$aFields,$aValues,"account='$account'");
			CJavaScript::vAlertRedirect(_LANG_OPEN_ACCOUNT_SUCCESS,"./"._WEB_INDEX."func=member");

		}else{
			//比對不到就表示驗證碼錯誤
			CJavaScript::vAlertRedirect(_LANG_OPEN_ACCOUNT_FAILURE,"./"._WEB_INDEX."func=member");
		}
	}

	//重新發送啟用信
	function tMemberResent() {
		global $Smarty,$CJavaScript,$CDbShell,$session,$CMisc; //obj var

		if(!$_POST){
			$Smarty->assign("web_index",_WEB_INDEX);
			return $output = $Smarty->fetch('./user/member_resent_open_account.html');

		} else {

			$aRow=&$_POST;

			CMember::sVaildResent($aRow);

			$sDate = date("Y-m-d H:i:s");
			$account = $aRow['account'];

			//撈出使用者資料重置密碼並且寄送密碼信
			$iDbq = $CDbShell->iQuery("SELECT * FROM member WHERE account = '$account'");
			$aVoteData = $CDbShell->aFetchAssoc($iDbq);

			if($aVoteData){
				$this->bSignOkMail($aVoteData);
				CJavaScript::vAlertRedirect(_LANG_RESENT_OPEN_LETTER_SUCCESS,"./"._WEB_INDEX."func=member");
			}

			CJavaScript::vAlertRedirect(_LANG_RESENT_OPEN_LETTER_FAILURE,"./"._WEB_INDEX."func=member");
		}
	}

	//註冊成功系統寄出確認信
	function bSignOkMail($aRow=array()){

		global $Smarty,$CJavaScript,$CDbShell,$PHPMailer,$session,$CCharset; //obj var

		if(!$aRow) return false;

		$Smarty->assign("aRow",$aRow);

		$mailBody = $Smarty->fetch("./user/member_signup_add_ok_mail.html");

		$gCharSet = $session->get("gCharSet");
		//如果是簡體就重新編碼
		if($gCharSet=="gb"){
			$mailBody = $CCharset->chg_utfcode($mailBody,"gb");
		}

		//信件的相關資訊
		$mail = $this->bMailInformation($aRow);

		// 信件標題
		$mail->Subject  = "您已註冊青春頌─兩岸青年原創金曲大選，此為會員帳號啟用信";

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

	//系統寄送重置密碼信
	function bResetPasswordMail($aRow=array(),$sPassword=''){

		global $Smarty,$CJavaScript,$CDbShell,$PHPMailer,$CCharset,$session; //obj var

		if(!$aRow) return false;

		$Smarty->assign("aRow",$aRow);
		$Smarty->assign("sPassword",$sPassword);

		$mailBody = $Smarty->fetch("./user/member_reset_password_mail.html");

		$gCharSet = $session->get("gCharSet");
		//如果是簡體就重新編碼
		if($gCharSet=="gb"){
			$mailBody = $CCharset->chg_utfcode($mailBody,"gb");
		}

		//信件的相關資訊
		$mail = $this->bMailInformation($aRow);


		// 信件標題
		$mail->Subject  = "密碼重置信件青春頌─兩岸青年原創金曲大選";

		//信件內容(html版，就是可以有html標籤的如粗體、斜體之類)
		$mail->Body = $mailBody;
		//信件內容(純文字版)
		// $mail->AltBody = $mailBody;

		if(!$mail->send()){
		    echo "Mailer Error: " . $mail->ErrorInfo;
		    exit;
		    return true;
		}else{
		    return true;
		}
	}

	/**
	 * 信箱的host 寄件人等相關設定
	 * @param  [type] $aRow 寄件人陣列
	 * @return [type]       [description]
	 */
	static function bMailInformation($aRow){
		// global $Smarty,$CJavaScript,$CDbShell,$PHPMailer,$session,$CCharset; //obj var
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
		// $mail->addBcc("jerry.he@iwant-in.net","Jerry");

		//Send HTML or Plain Text email
		$mail->isHTML(true);
		return $mail;
	}


}