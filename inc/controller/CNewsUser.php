<?php

include_once('./inc/controller/CGalaxyController.php');
include_once('./inc/model/CNews.php');
include_once('./inc/model/CNewsType.php');


class CNewsUser extends CGalaxyController{

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
			case 'view':
				return $this->tNewView(); // 明細頁
				break;
			default :
				return $this->tNewIndex(); // show
				break;
		}
	}


	function tNewIndex() {
		global $Smarty,$CJavaScript,$CDbShell,$AzDGCrypt,$session; //obj var


		$action = isset($_GET['action'])?$_GET['action']:'';

		$iPageItems = 8;

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

		$gCharSet = $session->get("gCharSet");

		if(strlen($sSearchSql)!==0)
	        $sSearchSql .= " AND `flag`='1' AND (area=0 OR area='$gCharSet')";
		else
			$sSearchSql=" `flag`='1' AND (area=0 OR area='$gCharSet')";

		//共幾筆
		$iAllItems = CNews::iGetCount($sSearchSql);

		//先計算頁數最多可幾頁，避免使用者自行修改網址進入空白頁
		if($iAllItems) $iPages=(INT)($iAllItems/$iPageItems);

		if($iPg < 0 OR $iPg > $iPages) CJavaScript::vRedirect("./"._WEB_INDEX."func=news");

		$iStart=$iPg*$iPageItems;

		//如果此頁是第一頁上一頁按鈕就不作用
		if(!$iPg){
			$iLastPage = "disabled"; 
		}else{
			$iLastPage = $iPg-1;
		}

		//如果此頁為最後一頁下一頁按鈕就不作用
		if($iPg!=$iPages){
			$iNextPage = $iPg+1;
		}else{
			$iNextPage = "disabled";
		}	

		//get objects
		$aNews = array();
		if($iAllItems!==0){
			$sPostFix = "ORDER BY $sOrder $sSort LIMIT $iStart,$iPageItems";	//sql postfix
			$aNews = CNews::aAllNews($sSearchSql,$sPostFix);

			foreach ($aNews as $key => $value) {
				$aNews[$key]['type_name'] = CNewsType::aGetName($value['type']);
				$aNews[$key]['news_active'] = "news-active"; //最新消息判斷click事件的class
			}
		}
		// $aNewsTotal = count($aNews);
		// if(count($aNews)<4){
		// 	for($i=0; $i < (4-$aNewsTotal); $i++) {
		// 		$aNews[] = array();
		// 	}
		// }

		// $Smarty->assign("lang",$gCharSet);

        $Smarty->assign("iLastPage",$iLastPage);
        $Smarty->assign("iNextPage",$iNextPage);
        $Smarty->assign("aNews",$aNews);

		$Smarty->assign("OrderUrl",$_SERVER['PHP_SELF']."?func=".$_GET['func']."&action=".$_GET['action']."&page=$iPg");
		$Smarty->assign("Total",$iAllItems);
		$Smarty->assign("PageItem",$iPageItems);

		$Smarty->assign("StartRow",$iStart+1);
		$Smarty->assign("EndRow",$iStart+$iPageItems);
		$Smarty->assign("web_index",_WEB_INDEX);
		return $output = $Smarty->fetch('./user/news_show.html');

	}

}
?>
