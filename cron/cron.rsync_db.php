<?php 
/*
sleep(1);
echo $argv[1]."\n";
exit;
*/

include_once('../inc/config.php');
// include_once('../inc/model/CRsync.php');
include_once('../inc/CDbShell.php');

// $RsyncUser = new CRsyncUser;

$sTWDBName = 'TW';
$sCNDBName = 'CN';

//$sTableName 該更新哪個table
$sTableName=$argv[1];
// $sTableName = 'upload_file';
if($sTableName=='')exit;
//判斷$sTableName 該更新哪個table
//資料庫連線 找出要同步的資料
$aTWDetails = vRsyncTable($sTWDBName,$sTableName);
$aCNDetails = vRsyncTable($sCNDBName,$sTableName);

//同步cn 資料到 tw 
vRsync($aCNDetails,$sTWDBName,$sCNDBName,$sTableName);

//同步tw 資料到 cn
vRsync($aTWDetails,$sCNDBName,$sTWDBName,$sTableName);


//找出未同步資料
function vRsyncTable($sDBName='',$sTableName=''){
	
	$sSearchSql ="is_sync = '0'";
	
	$oDB = new CDbShell(
						constant('_'.$sDBName.'_DB')
						,constant('_'.$sDBName.'_HOST')
						,constant('_'.$sDBName.'_USER')
						,constant('_'.$sDBName.'_PASS')
						);
	$aAllDetails = array();

	$sSql = "SELECT * FROM $sTableName";
	if($sSearchSql!=='') $sSql .= " WHERE $sSearchSql";
	$iDbq = $oDB->iQuery($sSql);

	while($aRow = $oDB->aFetchAssoc($iDbq)){
		$aAllDetails[] = $aRow;
	}

	return $aAllDetails;
}


/**
 * 同步資料
 * @param  string $aDetails   [還沒被同步的資料]
 * @param  string $sRsyncDBName  [同步到的db]
 * @param  string $sCurrentDBName  [當前db]
 * @param  string $sTableName [要同步的table]
 * @return [type]             [description]
 */
function vRsync($aDetails='',$sRsyncDBName='',$sCurrentDBName='',$sTableName=''){
	//insert 到tw
	$Rsync_oDB = new CDbShell(
						constant('_'.$sRsyncDBName.'_DB')
						,constant('_'.$sRsyncDBName.'_HOST')
						,constant('_'.$sRsyncDBName.'_USER')
						,constant('_'.$sRsyncDBName.'_PASS')
						);
	$Current_oDB = new CDbShell(
						constant('_'.$sCurrentDBName.'_DB')
						,constant('_'.$sCurrentDBName.'_HOST')
						,constant('_'.$sCurrentDBName.'_USER')
						,constant('_'.$sCurrentDBName.'_PASS')
						);
	
	//所有欄位
	$aTableColumns = $Rsync_oDB->aGetAllFieldsInfo($sTableName);
	$aFields = array();
	$Columns_id = '';//紀錄存id的欄位
	foreach ($aTableColumns as $key => $value) {
		if($key=='0')$Columns_id = $aTableColumns[$key][0];
		$aFields[] = $aTableColumns[$key][0];
	}

	//該table 多筆資料
	foreach ($aDetails as $key => $value) {
		$aValues = array();
		$aDetail_arr = $aDetails[$key];
		$Columns_id_value = '';
		
		$sSignNo='';
		$sToolNo='';
		$sWorksNo='';
		$sUpType='';
		//把該table 單筆資料得欄位已陣列存起來
		foreach ($aDetail_arr as $dkey => $dvalue) {
			//把該table的主要ID存下來
			if($dkey==$Columns_id)$Columns_id_value =$dvalue; 
			
			//music tool 會刪掉重新新增
			if($sTableName=='music_tool'){
				
				if($dkey=='sign_no')$sSignNo = $dvalue;
				if($dkey=='tool_no')$sToolNo = $dvalue;
			
			//upload_file 會刪掉重新新增
			}else if($sTableName=='upload_file'){
				
				if($dkey=='works_no')$sWorksNo = $dvalue;
				if($dkey=='up_type')$sUpType = $dvalue;
			}
			

			if($dkey=='is_sync'){//同步欄位要等於1
				$aValues[] = '1';
			}else{
				$aValues[] = $dvalue;
			}
		}

		//看同步到的db資料是否已存在,可能有修改過需要重新同步
		$sSql = "SELECT * FROM $sTableName  WHERE $Columns_id = '$Columns_id_value' ";
		$iDbq = $Rsync_oDB->iQuery($sSql);
		$num = $Rsync_oDB->iNumRows($iDbq);

		if($Rsync_oDB->iNumRows($iDbq)){//已有資料了
			
			//如果是 music_tool 就先刪掉,重新新增 須判斷兩個欄位刪除
			if($sTableName=='music_tool'){
				$sDeleteSql = "DELETE FROM music_tool WHERE sign_no = '$sSignNo' ";
				
				$Rsync_oDB->iQuery($sDeleteSql);
				$Rsync_oDB->sInsert($sTableName,$aFields,$aValues);
			//如果是 upload_file 就先刪掉,重新新增 須判斷兩個欄位刪除
			}else if ($sTableName=='upload_file') {
				$sDeleteSql = "DELETE FROM upload_file WHERE works_no = '$sWorksNo' AND up_type ='$sUpType' ";
				// echo $sDeleteSql.'<br>';
				$Rsync_oDB->iQuery($sDeleteSql);
				$Rsync_oDB->sInsert($sTableName,$aFields,$aValues);

			}else{
				//進行更新
				$sSql = $Rsync_oDB->sUpdate($sTableName,$aFields,$aValues,"$Columns_id='$Columns_id_value'");
			}
		}else{
			//新增至要同步的地方
			$Rsync_oDB->sInsert($sTableName,$aFields,$aValues);
		}
		//更新目前的db的is_sync=1 代表已同步
		$sSql = "UPDATE $sTableName SET is_sync='1' WHERE $Columns_id = '$Columns_id_value' ";
		$Current_oDB->iQuery($sSql);

		// echo $sSql.'<br>';
	}

	// return $aValues;	
}

?>