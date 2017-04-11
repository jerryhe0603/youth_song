<?php

/********************************************************************
 * @heading(標題):
			CMemberAdmin 基本會員管理類別
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
			2016/12/02
 ********************************************************************/
include_once('../inc/controller/CGalaxyController.php');
include_once('../inc/model/CMember.php');


Class CMemberAdmin extends CGalaxyController {

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
											// "member_no" 	=> '會員序號',
											"name" 	=> '會員名稱',
											"phone" => '聯絡手機',
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
					return $this->vMemberActive();
					break;
				case "view": // 瀏覽
					return $this->tMemberView();
					break;
				case "list":
				default : //show
					return $this->tMemberIndex();
					break;
			}
		}catch (Exception $e){
			switch($e->getCode()){
				case self::BACK_TO_LIST:
					$sUrl = $_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=list';
					if(!empty($_GET['member_no']))
						$sUrl .= '&goid='.$_GET['member_no'];
					CJavaScript::vAlertRedirect($e->getMessage(),$sUrl);
					break;
				case self::BACK_TO_VIEW:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=view&member_no='.$_GET['member_no'].isset($_GET['admin'])?"&admin=1":"");
					break;
				case self::BACK_TO_EDIT:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=edit&member_no='.$_GET['member_no']);
					break;
				case self::BACK_TO_ADD:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=add');
					break;
				case self::BACK_TO_ADMIN:
					$sUrl = $_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action=admin';
					if(!empty($_GET['member_no']))
						$sUrl .= '&goid='.$_GET['member_no'];
					CJavaScript::vAlertRedirect($e->getMessage(),$sUrl);
					break;
				case self::BACK_TO_NOW:
					CJavaScript::vAlertRedirect($e->getMessage(),$_SERVER['PHP_SELF'].'?func='.$_GET['func'].'&action='.$_GET['action'].'&member_no='.$_GET['member_no']);
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
    function tMemberIndex(){

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
			$iPg = $oDB->iGetItemAtPage("member","member_no",$goid,$iPageItems,$sSearchSql,"ORDER BY $sOrder $sSort");
		}

		//共幾筆
		$iAllItems = CMember::iGetCount($sSearchSql);
		$iStart=$iPg*$iPageItems;

		//get objects
		$aMember = array();
		if($iAllItems!==0){
			$sPostFix = "ORDER BY $sOrder $sSort LIMIT $iStart,$iPageItems";	//sql postfix
			$aMember = CMember::aAllMember($sSearchSql,$sPostFix);
			// echo '<pre>';print_r($aMember);exit;
			// if(count($aMember)===0)
				// CJavaScript::vAlertRedirect("",$_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=admin&items=".$PageItem);
		}
        $Smarty->assign("aMember",$aMember);

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
		$Smarty->assign("web_index",_WEB_INDEX);
		return $output = $Smarty->fetch('./admin/member_show.html');
	}

	private function tMemberView(){

		$Smarty = self::$Smarty;
		$session = self::$session;
		$oDB = self::oDB(self::$sDBName);

		if(empty($_GET['member_no']))
			throw new Exception('',$bAdmin?self::BACK_TO_ADMIN:self::BACK_TO_LIST);

		$member_no = $_GET['member_no'];
		$aMember = CMember::aGetData($member_no);

		$aMember['sign'] = CMember::aGetAllSign($member_no);
		// echo "<pre>";print_r($aMember);exit;

		if(!$aMember)
			throw new Exception('member not found',$bAdmin?self::BACK_TO_ADMIN:self::BACK_TO_LIST);

		$Smarty->assign('aMember',$aMember);

		return $output = $Smarty->fetch('./admin/member_view.html');

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
	public function vMemberActive(){

		$oDB = self::oDB(self::$sDBName);

		$aRow=&$_GET;
		$member_no = $aRow['member_no'];

		if($_GET['flag']==='1')
			$this->bStatus='0';
		else
			$this->bStatus='1';

		$aValues = array('flag'=>$this->bStatus
						 ,"is_sync"=>"0");

		$oDB->sUpdate("member", array_keys($aValues), array_values($aValues),"member_no='$member_no' ");
		CJavaScript::vRedirect($_SERVER['PHP_SELF'].'?func='.$_GET['func']."&action=list&goid=$member_no");

	}

}
?>