<?php

include_once(PATH_ROOT.'/inc/CMisc.php');
include_once(PATH_ROOT.'/inc/model/CGalaxyClass.php');
include_once(PATH_ROOT.'/inc/model/CUser.php');

class CNewsType extends CGalaxyClass {

	static protected $sDBName = 'MYSQL';

	public function __construct($multiData){
		parent::__construct($multiData);

	}

	static public function iGetCount($sSearchSql=''){
		$oDB = self::oDB(self::$sDBName);
		$sSql = "SELECT count(type_no) as total FROM news_type";
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

	static public function aAllType($sSearchSql='',$sPostFix='',$crpt=0){

		$oDB = self::oDB(self::$sDBName);
		$aAllType = array();

		$sSql = "SELECT * FROM news_type";
		if($sSearchSql!=='') $sSql .= " WHERE $sSearchSql";
		if($sPostFix!=='') $sSql .= " $sPostFix";

		$iDbq = $oDB->iQuery($sSql);

		while($aRow = $oDB->aFetchAssoc($iDbq)){
			$aAllType[] = $aRow;
		}

		return $aAllType;
	}

	/**
	* @param  $type_no 流水號
	* @return 資訊陣列
	* @desc 得到某筆資訊
	*/
	static function aGetType($type_no = 0) {

		$oDB = self::oDB(self::$sDBName);

		$aRow = array();

		if (!$type_no) return $aRow;

		$iDbq = $oDB->iQuery("SELECT * FROM news_type WHERE type_no='$type_no'");

		if ($oDB->iNumRows($iDbq)==0) return $aRow;

		$aRow=$oDB->aFetchAssoc($iDbq);

		return $aRow;
	}


	/**
	* @param  $type_no 流水號
	* @return 類別名稱
	* @desc 得到某筆資訊
	*/
	static function aGetName($type_no = 0) {

		$oDB = self::oDB(self::$sDBName);

		$sName = '';
		$aRow = array();

		if (!$type_no) return $sName;

		$iDbq = $oDB->iQuery("SELECT * FROM news_type WHERE type_no='$type_no'");

		if ($oDB->iNumRows($iDbq)==0) return $sName;

		$aRow=$oDB->aFetchAssoc($iDbq);

		$sName = $aRow['type_name'];

		return $sName;
	}

	/*
		檢查欄位輸入有效值
		@iTypeNo 有值表示為修改頁
	*/
	static public function sVaildData($postData=array(),$return_type=0,$iTypeNo){
		$oDB = self::oDB(self::$sDBName);
		$aErrorMsg = array();

		$sTypeName = trim($postData['type_name']);

		if(strlen($sTypeName) == 0){
			$aErrorMsg[]=_LANG_NEWS_TYPE_VAILD_NAME;
		}

		if($sTypeName){
			if($iTypeNo){
				$sSql = "SELECT * FROM news_type WHERE type_name = '$sTypeName' AND type_no !='$iTypeNo'";
			}else{
				$sSql = "SELECT * FROM news_type WHERE type_name = '$sTypeName'";
			}	
			$oDB->iQuery($sSql);
			if ($oDB->iNumRows($iDbq) >0 ) $aErrorMsg[]=_LANG_NEWS_TYPE_VAILD_NAME_DOUBLE;
		}

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
