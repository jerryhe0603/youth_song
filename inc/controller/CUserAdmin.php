<?php

include_once('../inc/controller/CGalaxyController.php');
include_once('../inc/model/CUser.php');


Class CUserAdmin extends CGalaxyController {

 	/*
		exception code of this controller
	*/
	const BACK_TO_LIST = 1;
	const BACK_TO_VIEW = 2;
	const BACK_TO_ADD = 3;
	const BACK_TO_EDIT = 4;
	const BACK_TO_ADMIN = 5;
	const BACK_TO_NOW = 6;

	private static $sDBName = 'MYSQL';

	static public $aSearchOption = array(	
											// "user_no" 	=> '序號',
											"user_name" 	=> '使用者名稱',
											);


    // This is the constructor for this class
	// Initialize all your default variables here
	function __construct() {
	}

	public function __destruct() {
	}

	/**
	* @param
	* @return tpl
	* @desc 管理
	* @created 2012/07/09
	*/
	function tManager() {
		$action = isset($_GET['action'])?$_GET['action']:"list";
		try{
			switch($action) {
				case "active": // 啟用/停用
					return $this->vUserActive();
					break;
				case "add": // 新增
					return $this->tUserAdd();
					break;
				case "edit": // 修改
					return $this->tUserEdit();
					break;
				case "view": // 瀏覽
					return $this->tUserView();
					break;
				case "del": // 刪除
					return $this->vUserDelete();
					break;
				case "list":
				default : //show
					return $this->tUserIndex();
					break;
			}
		}catch (Exception $e){
			switch($e->getCode()){
				case self::BACK_TO_LIST:
					$sUrl = $_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=list';
					if(!empty($_GET['user_no']))
						$sUrl .= '&goid='.$_GET['user_no'];
					CJavaScript::vAlertRedirect($e->getMessage(),$sUrl);
					break;
				case self::BACK_TO_VIEW:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=view&user_no='.$_GET['user_no'].isset($_GET['admin'])?"&admin=1":"");
					break;
				case self::BACK_TO_EDIT:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=edit&user_no='.$_GET['user_no']);
					break;
				case self::BACK_TO_ADD:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=add');
					break;
				case self::BACK_TO_ADMIN:
					$sUrl = $_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=admin';
					if(!empty($_GET['user_no']))
						$sUrl .= '&goid='.$_GET['user_no'];
					CJavaScript::vAlertRedirect($e->getMessage(),$sUrl);
					break;
				case self::BACK_TO_NOW:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action='.$_GET['action'].'&user_no='.$_GET['user_no']);
					break;
				default:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF']);
					break;
			}
		}
		exit;
	}

    /**
     *  @desc 報名列表
     *  @created 2016/04/20
     */
    function tUserIndex(){

		$Smarty = self::$Smarty;
		$session = self::$session;
		$oDB = self::oDB(self::$sDBName);


		$js_valid = isset($_GET['js_valid'])?$_GET['js_valid']:0;
		$action = isset($_GET['action'])?$_GET['action']:'';

		if(!empty($_POST)) {
			if($js_valid==1) {
				$this->vaildSearch($_POST,1);	//client javascript vaild data
			}else{
				$this->vaildSearch($_POST,0);	//form submit vaild data
			}
		}
		if(empty($_GET['items'])) $iPageItems = PAGING_NUM;
		else $iPageItems = $_GET['items'];

		if(empty($_GET['order'])) $sOrder = "created";
		else $sOrder = $_GET['order'];

		if(empty($_GET['sort'])) $sSort = "DESC";
		else $sSort = $_GET['sort'];

		if(empty($_GET['page'])) $iPg = 0;
		else $iPg = $_GET['page'];

		if(empty($_GET['goid'])) $goid = 0;
		else $goid = $_GET['goid'];

		if(isset($_GET['search']) AND $_GET['search'] === '1'){
			$sSearchSql = $this->sGetSearchSql($_POST);
			$sSearch = '&search=1';
		}else{
			$sSearchSql ='';
			$sSearch = '';
		}

		if(strlen($sSearchSql)!==0)
	        $sSearchSql .= " AND `flag`!='9'";
		else
			$sSearchSql=" `flag`!='9'";


		// 得到某筆資料是在第幾頁
		if($goid){
			$oDB = self::oDB("USER");
			$iPg = $oDB->iGetItemAtPage("user","user_no",$goid,$iPageItems,$sSearchSql,"ORDER BY $sOrder $sSort");
		}

		//共幾筆
		$iAllItems = CUser::iGetCount($sSearchSql);
		$iStart=$iPg*$iPageItems;

		//get objects
		$aUser = array();
		if($iAllItems!==0){
			$sPostFix = "ORDER BY $sOrder $sSort LIMIT $iStart,$iPageItems";	//sql postfix
			$aUser = CUser::aAllUser($sSearchSql,$sPostFix);
			// echo '<pre>';print_r($aUser);exit;
			// if(count($aUser)===0)
				// CJavaScript::vAlertRedirect("",$_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=admin&items=".$PageItem);
		}
        $Smarty->assign("aUser",$aUser);

		//assign frame attribute
		$Smarty->assign("NowOrder",$sOrder);
		$Smarty->assign("NowSort",$sSort);

		$Smarty->assign("OrderUrl",$_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=".$_GET['action']."&page=$iPg");
		$Smarty->assign("OrderSort",(strtoupper($sSort)=="DESC")?"ASC":"DESC");

		$Smarty->assign('searchKey',	$session->get("s_sign_key") );
		$Smarty->assign('searchTerm',	$session->get("s_sign_terms") );
		$Smarty->assign('searchOption',	self::$aSearchOption);

		$Smarty->assign("Total",$iAllItems);
		$Smarty->assign("PageItem",$iPageItems);

		$Smarty->assign("StartRow",$iStart+1);
		$Smarty->assign("EndRow",$iStart+$iPageItems);

		$Smarty->assign("iPg",$iPg);
		$Smarty->assign('PageBar',	CMisc::sMakePageBar($iAllItems, $iPageItems, $iPg, "func=".$_GET['func']."&action=".$_GET['action']."$sSearch&order=$sOrder&sort=$sSort"));

		return $output = $Smarty->fetch('./admin/user_show.html');
	}

	private function tUserView(){

		$Smarty = self::$Smarty;
		$session = self::$session;
		$oDB = self::oDB(self::$sDBName);

		if(empty($_GET['user_no']))
			throw new Exception('',$bAdmin?self::BACK_TO_ADMIN:self::BACK_TO_LIST);


		$user_no = $_GET['user_no'];
		$aUser = CUser::aGetMessage($user_no);

		if(!$aUser)
			throw new Exception('user not found',$bAdmin?self::BACK_TO_ADMIN:self::BACK_TO_LIST);

		$Smarty->assign('aUser',$aUser);

		return $output = $Smarty->fetch('./admin/new_view.html');

	}

	private function tUserAdd(){

		$Smarty = self::$Smarty;
		$session = self::$session;
		$oDB = self::oDB(self::$sDBName);

		if(!$_POST){

			return $output = $Smarty->fetch('./admin/user_add.html');


		}else{

			$aRow=&$_POST;

			// vaild data
			$sErrorMsg = "";

			// server vaild data
			if ($_GET['js_valid']==1) {
				$this->sVaildUserData($_POST,0);
			} else{
				$this->sVaildUserData($_POST,0);
			}

			$aRow['user_password'] = md5($aRow['user_password']);

			$sDate = date("Y-m-d H:i:s");
			if ($aRow['flag'] =='') $aRow['flag']=0;

			// 新增
			$aFields = array();
			$aValues = array();
			$iUser_no = $oDB->guid();
			$aFields = array("user_no","user_name","user_account","user_password","flag","created","modified");
			$aValues = array($iUser_no,$aRow['user_name'],$aRow['user_account'],$aRow['user_password'],$aRow['flag'],$sDate,$sDate);
			$sSql = $oDB->sInsert("user",$aFields,$aValues);

			if ($sSql) {

				CJavaScript::vAlert(_LANG_USER_ADD_SUCCESS);
				CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=show");
				exit;

			}

			CJavaScript::vAlert(_LANG_USER_ADD_FAILURE);
			CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=show");
			exit;

		}
	}

	private function tUserEdit(){

		$Smarty = self::$Smarty;
		$session = self::$session;
		$oDB = self::oDB(self::$sDBName);


		if (!isset($_GET['user_no'])) {
			CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=list");
			exit;
		} else $iAaId = $_GET['user_no'];

		$aUser = CUser::aGetUser($iAaId);

		if (!$aUser) {
			CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']);
			exit;
		}

		if(!$_POST){

			$Smarty->assign("aUser",$aUser);
			return $output = $Smarty->fetch('./admin/user_edit.html');


		}else{

			$aRow=&$_POST;

			// vaild data
			$sErrorMsg = "";
			// server vaild data
			if ($_GET['js_valid']==1) {
				$this->sVaildUserData($_POST,0,$iAaId);
			} else{
				$this->sVaildUserData($_POST,0,$iAaId);
			}

			$aRow['user_password'] = md5($aRow['user_password']);

			$sDate = date("Y-m-d H:i:s");
			if ($aRow['flag'] =='') $aRow['flag']=0;


			$aFields = array("user_name","user_account","user_password","flag","modified","is_sync");
			$aValues = array($aRow['user_name'],$aRow['user_account'],$aRow['user_password'],$aRow['flag'],$sDate,"0");
			$sSql = $oDB->sUpdate("user",$aFields,$aValues,"user_no='$iAaId'");

			if ($sSql) {

				CJavaScript::vAlert(_LANG_USER_EDIT_SUCCESS);
				CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=show");
				exit;

			}

			CJavaScript::vAlert(_LANG_USER_EDIT_FAILURE);
			CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=show");
			exit;

		}


	}


	/*
		check if the search string is vaild
	*/
	private function vaildSearch($postData=array(),$return_type=0){
		$aErrorMsg = array();

		if(strlen(trim($postData['s_key'])) == 0){
			$aErrorMsg[]=_LANG_VAILD_SEARCH_KEY;
		}
		$sErrorMsg = "";

		//client javascript vaild data
		if($return_type==1) {
			$sErrorMsg = implode("<BR>",$aErrorMsg);
			echo $sErrorMsg;
			exit;
		}
		//form submit vaild data
		if(count($aErrorMsg) > 0){
			$sErrorMsg = implode('\n',$aErrorMsg);
			if($_GET['action']==='admin')
				throw new Exception(sprintf($sErrorMsg),self::BACK_TO_ADMIN);
			else
				throw new Exception(sprintf($sErrorMsg),self::BACK_TO_LIST);

		}
	}

	/*
		檢查欄位輸入有效值
	*/
	private function sVaildUserData($postData=array(),$return_type=0,$user_no=0){

		$aErrorMsg = array();

		if(strlen(trim($postData['user_name'])) == 0){
			$aErrorMsg[]=_LANG_VAILD_USER_NAME;
		}

		if(strlen(trim($postData['user_account'])) == 0){
			$aErrorMsg[]=_LANG_VAILD_USER_ACCOUNT;
		}else if(!$user_no){
			if ($this->sCheckAccount($postData['user_account'])) $aErrorMsg[] = _LANG_USED_USER_ACCOUNT;
		}else{
			if ($this->sCheckAccount($postData['user_account'],$user_no)) $aErrorMsg[] = _LANG_USED_USER_ACCOUNT;
		}

		if(strlen(trim($postData['user_password'])) == 0){
			$aErrorMsg[]=_LANG_VAILD_USER_PASSWORD;
		}

		$sErrorMsg = "";

		//client javascript vaild data
		if($return_type==1) {
			$sErrorMsg = implode("<BR>",$aErrorMsg);
			echo $sErrorMsg;
			exit;
		}
		//form submit vaild data
		if(count($aErrorMsg) > 0){
			$sErrorMsg = implode('\n',$aErrorMsg);
			throw new Exception(sprintf($sErrorMsg),self::BACK_TO_NOW);

		}
	}

	/*
		change search project name into sql string
	*/
	private function sGetSearchSql($aPost){
		$session = self::$session;

		if(count($aPost)){
			$sKey = trim($aPost['s_key']);
			$sTerms = trim($aPost['s_terms']);
		}else{
			$sKey = $session->get("s_sign_key");
			$sTerms =  $session->get("s_sign_terms");
		}
		$sSql = "";

		if(!$sKey) {
			$session->set("s_sign_key","");
			$session->set("s_sign_terms","");
			return $sSql;
		}
		$session->set("s_sign_key",$sKey);
		$session->set("s_sign_terms",$sTerms);

		switch($sTerms){
			default :
				$sSql = $sSql." (`$sTerms` LIKE '%$sKey%')";
				break;
		}
		return $sSql;
	}

	/*
		activate this oCBeautyProject
	*/
	public function vUserActive(){

		$oDB = self::oDB(self::$sDBName);

		$aRow=&$_GET;
		$user_no = $aRow['user_no'];

		if($_GET['flag']==='1')
			$this->bStatus='0';
		else
			$this->bStatus='1';

		$aValues = array('flag'=>$this->bStatus
						,"is_sync"=>"0"
						);

		$oDB->sUpdate("user", array_keys($aValues), array_values($aValues),"user_no='$user_no'");
		CJavaScript::vRedirect($_SERVER['PHP_SELF'].'?func='.$_GET['func']."&action=list&goid=$user_no");

	}

	public function vUserDelete(){

		$oDB = self::oDB(self::$sDBName);

		$aRow=&$_GET;
		$user_no = $aRow['user_no'];

		$oDB->vDelete("user","user_no='$user_no'");
		CJavaScript::vRedirect($_SERVER['PHP_SELF'].'?func='.$_GET['func']."&action=list&goid=$user_no");

	}

	public function sCheckAccount($userAccount,$user_no=0){

		$oDB = self::oDB(self::$sDBName);

		$sql = "SELECT * FROM user WHERE user_account='$userAccount'";

		$where = '';

		if($user_no) $sql .= " AND user_no !='$user_no'" ;

		$iDbq = $oDB->iQuery($sql);

		if($oDB->iNumRows($iDbq) > 0){
			return 1;
		}else{
			return 0;
		}

	}

	/**
	* @param $iDataId 流水號  $aFile 上傳檔案資訊陣列
	* @return 0 失敗 1 成功
	* @desc 上傳檔案
	*/
	function iUploadFile($sSource,$iDataId = 0,$aFile) {

		$oDB = self::oDB(self::$sDBName);

		if($iDataId ==0 || !$aFile['file_file']['tmp_name']) return 0;
		$iError = 0;

		if($aFile['file_file']['tmp_name']){
			$sDir = CUser::sGetDataPath($sSource);
			$sFileName = "";
			$iError = 1;

			if(!is_dir($sDir))mkdir($sDir,0777);
			$sDir=$sDir."/$iDataId/";

			if(!is_dir($sDir))mkdir($sDir,0777);
			$aTmp = explode(".",$aFile['file_file']['name']);
			$c = count($aTmp);
			if($aTmp[$c-1]) {
				$sFileName = $iSolutionId.md5(uniqid(rand(), true))."_src.".$aTmp[$c-1];
				//$sFileName = "file_data.".$aTmp[$c-1];
			} else {
				$sFileName = $iSolutionId.md5(uniqid(rand(), true))."_src.".$aTmp[1];
				//$sFileName = "file_".$aFile['file_file']['name'];
			}
			if ($dh = opendir($sDir)) {
				while(false !== ($f = readdir($dh))) {
					if ($f != "." && $f != "..") {
						if(preg_match("/file/i",$f)) $sOldImg = $f;
					}
				}
			}
			closedir($dh);

			if(copy($aFile['file_file']['tmp_name'], $sDir.$sFileName)) {

				// 非圖片處理
				chmod($sDir.$sFileName,0777);
				if(preg_match("/image/i",$aFile['file_file']['type'])) $iType=0;
				elseif(preg_match("/vedio/i",$aFile['file_file']['type'])) $iType=1;
				else $iType=2;

				//insert file name to db
				$aFields=array("file_file","file_name","is_sync");
				$aValues=array($sFileName,$aFile['file_file']['name'],"0");

				$sSql = $oDB->sUpdate($sSource,$aFields,$aValues,"user_no=$iDataId");
				if($sSql){
					$iError=0;
					@unlink($aFile['file_file']['tmp_name']);
				}


			} else {
				// Copy Fail.
			}
		}

		if($iError) return 0;
		return 1;
	}




}
?>