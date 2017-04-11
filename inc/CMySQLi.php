<?php

/**
* 	new CDbShell using "mysqli" and "object oriented style"
*	by Ophidian.Wang @ 2014-12-16
*/

include_once('../inc/config.php');

class CMySQLi extends mysqli
{
	public $sDBName;

	private $aResult =array();
	private $iQueryCount = 0;

	private $iTransactionLayer = 0;	//used to control nested transaction(for nested classes' functions)
	static protected $aDB = array();

	static public function oDB($sDBName){
		if(!isset(self::$aDB[$sDBName])){
			self::$aDB[$sDBName] = new CMySQLi(	constant('_'.$sDBName.'_HOST'),
												constant('_'.$sDBName.'_USER'),
												constant('_'.$sDBName.'_PASS'),
												constant('_'.$sDBName.'_DB')
												);
		}
		return self::$aDB[$sDBName];
	}

	public function __construct($sHost='',$sUser='',$sPass='',$sDb='')
	{
		if($sDb==='' || $sHost==='' || $sUser==='' || $sPass==='')
			die("CMySQLi->__construct: require connection info");

		$this->sDBName = $sDb;
			
		parent::init();

        if (!parent::options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) {
            die('CMySQLi->__construct: Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');
        }

        //try connect to mysql, if success, break; retry 10 times
		$iRetry=0;
		while($iRetry<10){
			if(parent::real_connect($sHost, $sUser, $sPass, $sDb))
				break;

			$iRetry++;
		}

		//after 10 times retry, if still got error, die
		if(mysqli_connect_errno()!=0)
			die('CMySQLi->__construct: Connection failed for 10 times, error_msg:'.mysqli_connect_error());

		//after all, set character
		$this->bSetCharacter();	//default is utf8
	}

	public function __destruct()
	{
		$this->vFreeAll();

		parent::close();
		
		if($this->iTransactionLayer !== 0){
			$sLogicErrorMsg = "vBegin & vCommit's quantity do not match on database: {$this->sDBName}!";
			die($sLogicErrorMsg);
		}
	}

	public function iQuery($sSql) {

		$mResult = parent::query($sSql);
		if($mResult==false)
			throw new Exception("CMySQLi->iQuery: ".$this->error." : SQL= $sSql");

		if(is_object($mResult) && get_class($mResult)=='mysqli_result'){
			$this->iQueryCount++;
			$this->aResult[$this->iQueryCount] = $mResult;
			return $this->iQueryCount;
		}else{
			return 0;
		}
	}


	/*
	free single mysqli_result
	*/
	public function vFree($iDbq){
		$this->aResult[$iDbq]->free();
		unset($this->aResult[$iDbq]);
	}

	public function vFreeAll(){
		//free all result
		foreach ($this->aResult as $oResult) {
			$oResult->free();
		}
		unset($this->aResult);
		$this->aResult = array();
	}

	/******************** FETCH FUNCTION START ********************/

	public function aFetchAssoc($iDbq){
		if(!isset($this->aResult[$iDbq]))
			return false;

		return $this->aResult[$iDbq]->fetch_assoc();
	}

	public function aFetchArray($iDbq){
		if(!isset($this->aResult[$iDbq]))
			return false;

		return $this->aResult[$iDbq]->fetch_array();
	}

	public function aFetchRow ($iDbq) {
		if(!isset($this->aResult[$iDbq]))
			return false;

		return $this->aResult[$iDbq]->fetch_row();
	}

	public function iNumFields($iDbq){
		if(!isset($this->aResult[$iDbq]))
			return false;

		return $this->aResult[$iDbq]->field_count;
    }

    public function iNumRows($iDbq){
		if(!isset($this->aResult[$iDbq]))
			return false;

		return $this->aResult[$iDbq]->num_rows;
	}

	/******************** FETCH FUNCTION END ********************/


	/******************** INSERT/UPDATE/DELETE FUNCTION START ********************/

	public function vInsert($sTable,$aInsert) {

		if(!is_array($aInsert) || empty($aInsert))
			return;

		$aField = array_keys($aInsert);
		$aValue = array_values($aInsert);

		$sSql="INSERT INTO $sTable ( ";
		for($i=1;$i<=count($aField);$i++) {                      
			$sSql.="`".$aField[$i-1]."`";
			if($i!=count($aField)) $sSql.=",";        
		} 
		$sSql.=") values(";
		
		for($i=1;$i<=count($aValue);$i++) {
			$sSql.="'".$aValue[$i-1]."'";
			if($i!=count($aValue)) $sSql.=",";        
		} 
		$sSql.=")";
	
		try{
			$this->iQuery($sSql);
		}catch(Exception $e){
			throw new Exception("CMySQLi->vInsert: ".$e->getMessage());
		}
	}

	public function vUpdate($sTable,$aUpdate,$sWhere) {
		
		if(!is_array($aUpdate) || empty($aUpdate))
			return;

		$aField = array_keys($aUpdate);
		$aValue = array_values($aUpdate);
		
		$sSql="update $sTable set ";
		for($i=0;$i<count($aField);$i++) {
			$sSql.=$aField[$i]."='".$aValue[$i]."'";
			if(($i+1)!=count($aField)) $sSql.=",";
		} 
		$sSql.=" where ".$sWhere;

		try{
			$this->iQuery($sSql);
		}catch(Exception $e){
			throw new Exception("CMySQLi->vUpdate: ".$e->getMessage());
		}
    }

	public function vDelete($sTable,$sWhere){
		try{
			$this->iQuery("DELETE FROM $sTable WHERE $sWhere");
		}catch(Exception $e){
			throw new Exception("CMySQLi->vDelete: ".$e->getMessage());
		}
	}

    public function iGetInsertId(){
		return $this->insert_id;
	}

	/******************** INSERT/UPDATE/DELETE FUNCTION END ********************/

	
	/******************** TRANSACTION FUNCTION START ********************/

	public function vBegin () {
		//if this CDbShell already start a trancation, it won't "begin" again , but only +1 on layer
		if($this->iTransactionLayer===0)
			$this->iQuery("begin");
		$this->iTransactionLayer++;
	}

	public function vCommit () {
		//if this CDbShell's trancation layer is more than 1, it won't "commit" right away
		//, but only -1 on layer, there will be another vCommit later
		$this->iTransactionLayer--;
		if($this->iTransactionLayer===0)
			$this->iQuery("commit");
	}

	public function vRollback () {
		$this->iTransactionLayer = 0;
		$this->iQuery("rollback");
	}

	/******************** TRANSACTION FUNCTION END ********************/


	/******************** CHECK EXIST FUNCTION START ********************/

	public function bIsTableExist($sTable){
		$iDbq = $this->iQuery("SHOW TABLES LIKE '%$sTable%'"); 
		if($this->iNumRows($iDbq))
			return true;
		return false;
	}
	
	public function bIsDatabaseExist($sDatabase){
		$iDbq = $this->iQuery("SHOW DATABASES LIKE '$sDatabase'"); 
		if($this->iNumRows($iDbq))
			return true;
		return false;
	}


	/******************** CHECK EXIST FUNCTION END ********************/


	/******************** TABLE INFO FUNCTION START ********************/

	public function aGetAllFieldsInfo($sTable){
		$iDbq = $this->iQuery("SHOW FULL FIELDS FROM $sTable"); 
		while($aRow=$this->aFetchArray($iDbq)){
			//array( Field  	 Type  	 Collation  	 Null  	 Key  	 Default  	 Extra  	 Privileges  	 Comment)
			$aFields[]=$aRow;
		}	
		return $aFields;		
	}

	public function aGetCreateTableInfo($sTable){
		$this->iQuery("SET SQL_QUOTE_SHOW_CREATE = 1");
		$iDbq = $this->iQuery("SHOW CREATE TABLE $sTable");
		return $this->aFetchArray($iDbq);
	}

	/******************** TABLE INFO FUNCTION END ********************/


	/******************** OTHER FUNCTION START ********************/

	public function bSetCharacter($encode = "utf8") {
		$this->iQuery("SET character_set_client = $encode");
		$this->iQuery("SET character_set_results = $encode");
		$this->iQuery("SET character_set_connection = $encode");
	}

	public function iGetItemAtPage($sTable="",$sField="",$iGoId,$iPageItems,$sSearchSql='',$sPostFix=''){
			
		if(!$sTable || !$sField)
			return 0;

		$sSql = "SELECT $sField  FROM $sTable";
		if($sSearchSql!=='')
			$sSql .= " WHERE $sSearchSql";
		if($sPostFix!=='')
			$sSql .= " $sPostFix";
		
		$iDbq = $this->iQuery($sSql);
		$i=0;
		$biFind = false;
		while($aRow=$this->aFetchAssoc($iDbq)) {
			if($aRow[$sField]==$iGoId) {
				$biFind = true;
				break;
			}	
			$i++;
		}
		if(!$biFind) $i=0;
		
		return (INT)($i/$iPageItems);
	}

	public function iGetJoinItemAtPage($sTable1="",$sTable2="",$sField="", $iGoId, $iPageItems,$sSearchSql='',$sPostFix=''){
				
		if(!$sTable ||$sTable2 || !$sField) return 0;
		$sSql = "SELECT $sField FROM $sTable1 LEFT JOIN $sTable2 ON $sTable1.$sField=$sTable2.$sField";
		if($sSearchSql!=='')
			$sSql .= " WHERE $sSearchSql";
		if($sPostFix!=='')
			$sSql .= " $sPostFix";
		$iDbq = $this->iQuery($sSql);
        $i=0;
		$biFind = false;
		while($aRow=$this->aFetchArray($iDbq)) {
			if($aRow[$sField]==$iGoId) {
				$biFind = true;
				break;
			}	
			$i++;
		}
		if(!$biFind) $i=0;
		
		return (INT)($i/$iPageItems);
	}
}



?>