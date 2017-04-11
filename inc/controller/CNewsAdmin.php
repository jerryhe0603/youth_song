<?php

/********************************************************************
 * @heading(標題):
			CNewAdmin 最新消息管理類別
 * @author(作者) :
 * @purpose(目的) :
			最新消息管理類別
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
			2016/07/12
 ********************************************************************/

include_once('../inc/controller/CGalaxyController.php');
include_once('../inc/model/CNews.php');
include_once('../inc/model/CNewsType.php');


Class CNewsAdmin extends CGalaxyController {

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
											// "news_no" 	=> '序號',
											"title" 	=> '標題',
											"publish_date" 	=> '發布日期'
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
					return $this->vNewsActive();
					break;
				case "add": // 新增
					return $this->tNewsAdd();
					break;
				case "edit": // 修改
					return $this->tNewsEdit();
					break;
				case "view": // 瀏覽
					return $this->tNewsView();
					break;
				case "del": // 刪除
					return $this->vNewsDelete();
					break;
				case "list":
				default : //show
					return $this->tNewsIndex();
					break;
			}
		}catch (Exception $e){
			switch($e->getCode()){
				case self::BACK_TO_LIST:
					$sUrl = $_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=list';
					if(!empty($_GET['news_no']))
						$sUrl .= '&goid='.$_GET['news_no'];
					CJavaScript::vAlertRedirect($e->getMessage(),$sUrl);
					break;
				case self::BACK_TO_VIEW:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=view&news_no='.$_GET['news_no'].isset($_GET['admin'])?"&admin=1":"");
					break;
				case self::BACK_TO_EDIT:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=edit&news_no='.$_GET['news_no']);
					break;
				case self::BACK_TO_ADD:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=add');
					break;
				case self::BACK_TO_ADMIN:
					$sUrl = $_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=admin';
					if(!empty($_GET['news_no']))
						$sUrl .= '&goid='.$_GET['news_no'];
					CJavaScript::vAlertRedirect($e->getMessage(),$sUrl);
					break;
				case self::BACK_TO_NOW:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action='.$_GET['action']);
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
    function tNewsIndex(){

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

		if(empty($_GET['order'])) $sOrder = "publish_date";
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
			$iPg = $oDB->iGetItemAtPage("news","news_no",$goid,$iPageItems,$sSearchSql,"ORDER BY $sOrder $sSort");
		}

		//共幾筆
		$iAllItems = CNews::iGetCount($sSearchSql);
		$iStart=$iPg*$iPageItems;

		//get objects
		$aNews = array();
		if($iAllItems!==0){
			$sPostFix = "ORDER BY $sOrder $sSort LIMIT $iStart,$iPageItems";	//sql postfix
			$aNews = CNews::aAllNews($sSearchSql,$sPostFix);

			foreach ($aNews as $key => $value) {
				$aNews[$key]['type_name'] = CNewsType::aGetName($value['type']);
			}
		}
        $Smarty->assign("aNews",$aNews);

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

		return $output = $Smarty->fetch('./admin/news_show.html');
	}

	private function tNewsView(){

		$Smarty = self::$Smarty;
		$session = self::$session;
		$oDB = self::oDB(self::$sDBName);

		if(empty($_GET['news_no']))
			throw new Exception('',$bAdmin?self::BACK_TO_ADMIN:self::BACK_TO_LIST);


		$news_no = $_GET['news_no'];
		$aNews = CNews::aGetNews($news_no);
		$aNews['type_name'] = CNewsType::aGetName($aNews['type']);


		if(!$aNews)
			throw new Exception('news not found',$bAdmin?self::BACK_TO_ADMIN:self::BACK_TO_LIST);

		$Smarty->assign('aNews',$aNews);

		return $output = $Smarty->fetch('./admin/news_view.html');

	}

	private function tNewsAdd(){

		$Smarty = self::$Smarty;
		$session = self::$session;
		$oDB = self::oDB(self::$sDBName);

		if(!$_POST){

			$aNewsType = CNewsType::aAllType("flag=1");
			// echo "<pre>";print_r($aNewsType);exit;
			$Smarty->assign("aNewsType",$aNewsType);
			return $output = $Smarty->fetch('./admin/news_add.html');


		}else{
			//先將上傳檔案放置post變數，以利檢查時能得知有無上傳
			$aRow=&$_POST;

			// echo "<pre>";print_r($aRow);
			// echo "<pre>";print_r($aFile);exit;

			$aRow['description']=stripslashes($aRow['description']); //去除跳脫字元

			// vaild data
			$sErrorMsg = "";

			// server vaild data
			if ($_GET['js_valid']==1) {
				CNews::sVaildData($aRow,0,0);
			} else{
				CNews::sVaildData($aRow,0,0);
			}


			$sDate = date("Y-m-d H:i:s");
			if (!$aRow['flag']) $aRow['flag']=0;

			// 新增
			$aFields = array();
			$aValues = array();
			$iNewsNo = $oDB->guid();
			
			$aFields = array("news_no","title","area","type","publish_date","description","flag","created","modified");
			$aValues = array($iNewsNo,$aRow['title'],$aRow['area'],$aRow['type'],$aRow['publish_date'],$aRow['description'],$aRow['flag'],$sDate,$sDate);
			$sSql = $oDB->sInsert("news",$aFields,$aValues);

			if ($sSql) {

				CJavaScript::vAlert(_LANG_NEWS_ADD_SUCCESS);
				CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=list&goid=$iNewsNo");
				exit;
				//封面圖片暫不需要先註解
				// $iNewsNo=$oDB->iGetInsertId();

				// if($this->iUploadFile('news',$iNewsNo,$aFile)){
				// 	CJavaScript::vAlert(_LANG_NEWS_ADD_SUCCESS);
				// 	CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=list&goid=$iNewsNo");
				// 	exit;
				// }else{
				// 	//新增檔案失敗刪除
				// 	CJavaScript::vAlert(_LANG_NEWS_ADD_FAILURE);
				// 	$oDB->iQuery("DELETE FROM news WHERE news_no = $iNewsNo");

				// }
			}

			CJavaScript::vAlert(_LANG_NEWS_ADD_FAILURE);
			CJavaScript::vBack();
			exit;
		}
	}

	private function tNewsEdit(){

		$Smarty = self::$Smarty;
		$session = self::$session;
		$oDB = self::oDB(self::$sDBName);


		if (!isset($_GET['news_no'])) {
			CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=list");
			exit;
		} else $iNewsNo = $_GET['news_no'];

		$aNews = CNews::aGetNews($iNewsNo);

		if (!$aNews) {
			CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']);
			exit;
		}

		if(!$_POST){

			$aNewsType = CNewsType::aAllType("flag=1");
			// echo "<pre>";print_r($aNewsType);exit;
			$Smarty->assign("aNewsType",$aNewsType);

			$Smarty->assign("NewsEditSubmit",$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&time=".time());
			$Smarty->assign("aNews",$aNews);
			return $output = $Smarty->fetch('./admin/news_edit.html');


		}else{

			$aRow=&$_POST;
			$aRow['description']=stripslashes($aRow['description']); //去除跳脫字元

			// vaild data
			$sErrorMsg = "";
			// server vaild data
			if ($_GET['js_valid']==1) {
				CNews::sVaildData($aRow,0,1);
			} else{
				CNews::sVaildData($aRow,0,1);
			}

			$sDate = date("Y-m-d H:i:s");
			if ($aRow['flag'] =='') $aRow['flag']=0;

			// 新增
			$aFields = array();
			$aValues = array();

			$aFields = array("title","area","type","publish_date","description","flag","modified","is_sync");
			$aValues = array($aRow['title'],$aRow['area'],$aRow['type'],$aRow['publish_date'],$aRow['description'],$aRow['flag'],$sDate,"0");
			$sSql = $oDB->sUpdate("news",$aFields,$aValues,"news_no='$iNewsNo'");

			if($sSql){

				CJavaScript::vAlert(_LANG_NEWS_EDIT_SUCCESS);
				CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=list&goid=$iNewsNo");
				exit;
				//暫不需要封面照片先註解
				// $CUser->bAddUserLog($gUserId,"file",$iNewsNo,$_GET['func'],$_GET['action']);
				// if(!$aFile['file_file']['name']){
				// 	CJavaScript::vAlert(_LANG_NEWS_EDIT_SUCCESS);
				// 	CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=list&goid=$iNewsNo");
				// 	exit;
				// }

				// if($this->iUploadFile('news',$iNewsNo,$aFile)){
				// 	//刪除舊的檔案
				// 	$sFilePath = "../data/news/".$iNewsNo."/".$aNews['file_file'];
				// 	@unlink("$sFilePath");
				// 	CJavaScript::vAlert(_LANG_NEWS_EDIT_SUCCESS);
				// 	CJavaScript::vUpdateThisFrame($_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=list&goid=$iNewsNo");
				// 	exit;
				// }else{
				// 	CJavaScript::vAlert(_LANG_NEWS_EDIT_FAILURE);
				// }
			}
			CJavaScript::vAlert(_LANG_NEWS_EDIT_FAILURE);
			CJavaScript::vBack();
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
	public function vNewsActive(){

		$oDB = self::oDB(self::$sDBName);

		$aRow=&$_GET;
		$news_no = $aRow['news_no'];

		if($_GET['flag']==='1')
			$this->bStatus='0';
		else
			$this->bStatus='1';

		$aValues = array('flag'=>$this->bStatus
						 ,"is_sync"=>"0");

		$oDB->sUpdate("news", array_keys($aValues), array_values($aValues),"news_no='$news_no' ");
		CJavaScript::vRedirect($_SERVER['PHP_SELF'].'?func='.$_GET['func']."&action=list&goid=$news_no");

	}

	public function vNewsDelete(){

		$oDB = self::oDB(self::$sDBName);

		$aRow=&$_GET;
		$news_no = $aRow['news_no'];

		$this->bStatus='9';

		$aValues = array('flag'=>$this->bStatus
						 ,"is_sync"=>"0");

		$oDB->sUpdate("news", array_keys($aValues), array_values($aValues),"news_no='$news_no' ");
		CJavaScript::vRedirect($_SERVER['PHP_SELF'].'?func='.$_GET['func']."&action=list&goid=$news_no");

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
			$sDir = '../data/news';
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

				$sSql = $oDB->sUpdate($sSource,$aFields,$aValues,"news_no=$iDataId");
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