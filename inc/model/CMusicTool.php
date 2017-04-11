<?php

class CMusicTool {
	// This is the constructor for this class
	// Initialize all your default variables here
	function __construct(){
	}

	/**
	* @param  $iMusicToolId 流水號
	* @return 資訊陣列
	* @desc 得到某筆基本資訊
	*/
	function aGetMusicToolData($iMusicToolId = 0){
		global $CDbShell; //obj var
		$aRow = array();
		if($iMusicToolId == 0) return $aRow;
		$iDbq = $CDbShell->iQuery("SELECT * FROM music_tool WHERE to_id=$iMusicToolId");
		if($CDbShell->iNumRows($iDbq)==0) return $aRow;
		$aRow=$CDbShell->aFetchArray($iDbq);
		return $aRow;
	}

	function aGetMusicToolList(){
		global $CDbShell; //obj var
		$aDetails = array();
		$iDbq=$CDbShell->iQuery("SELECT to_id,to_name FROM music_tool WHERE flag=0 ORDER BY to_order ASC");
		while($aRow=$CDbShell->aFetchArray($iDbq)){
			$aDetails[] = $aRow;
		}
		return $aDetails;
	}
}

?>