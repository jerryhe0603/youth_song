<?php

include_once(PATH_ROOT.'/inc/CMisc.php');
include_once(PATH_ROOT.'/inc/model/CGalaxyClass.php');
include_once(PATH_ROOT.'/inc/model/CUser.php');

class CNews extends CGalaxyClass {

	static protected $sDBName = 'MYSQL';
	static public $aInstancePool = array();

	public function __construct($multiData){
		parent::__construct($multiData);

	}

	static public function iGetCount($sSearchSql=''){
		$oDB = self::oDB(self::$sDBName);
		$sSql = "SELECT count(news_no) as total FROM news";
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

	static public function aAllNews($sSearchSql='',$sPostFix=''){

		$oDB = self::oDB(self::$sDBName);
		$aAllNews = array();

		$sSql = "SELECT * FROM news";
		if($sSearchSql!=='') $sSql .= " WHERE $sSearchSql";
		if($sPostFix!=='') $sSql .= " $sPostFix";

		$iDbq = $oDB->iQuery($sSql);

		while($aRow = $oDB->aFetchAssoc($iDbq)){
			$aAllNews[] = $aRow;
		}
		return $aAllNews;
	}

	/**
	* @param  $news_no 流水號
	* @return 資訊陣列
	* @desc 得到某筆資訊
	*/
	static function aGetNews($news_no = 0) {

		$oDB = self::oDB(self::$sDBName);

		$aRow = array();

		if (!$news_no) return $aRow;

		$iDbq = $oDB->iQuery("SELECT * FROM news WHERE news_no='$news_no'");

		if ($oDB->iNumRows($iDbq)==0) return $aRow;

		$aRow=$oDB->aFetchAssoc($iDbq);

		return $aRow;
	}

	/*
		檢查欄位輸入有效值
		@iType 1為修改頁
	*/
	static public function sVaildData($postData=array(),$return_type=0,$iType=0){
		$aErrorMsg = array();

		if(strlen(trim($postData['title'])) == 0){
			$aErrorMsg[]=_LANG_NEWS_VAILD_TITLE;
		}

		if(strlen(trim($postData['publish_date'])) == 0){
			$aErrorMsg[]=_LANG_NEWS_VAILD_PUBLISH_DATE;
		}

		if(strlen(trim($postData['description'])) == 0){
			$aErrorMsg[]=_LANG_NEWS_VAILD_DESCRIPTION;
		}

		// //新增頁才須檢查是否有上傳封面照片
		// if(!$iType){

		// 	if(!$File['file_file']) {
		// 		$aErrorMsg[]=_LANG_NEWS_VAILD_FILE;
		// 	}
		// }

		$sErrorMsg = "";

		//form submit vaild data
		if(count($aErrorMsg) > 0){

			$sErrorMsg = implode("\\n",$aErrorMsg);
			CJavaScript::vAlert($sErrorMsg);
			exit;
		}
	}


}

?>
