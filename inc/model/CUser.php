<?php

$sNowPath = realpath(dirname(dirname(dirname( __FILE__ ))));
include_once($sNowPath.'/inc/model/CGalaxyClass.php');
include_once($sNowPath.'/inc/model/CGroup.php');
include_once($sNowPath.'/inc/model/CRule.php');
include_once($sNowPath.'/inc/model/CUserLog.php');

class CUser extends CGalaxyClass
{
	//vital member
	protected $iUserNo;	//user_no in user.user, read only after construct
	public $sName;	//user name
	protected $sAccount;	//account to authorized user, read only after construct
	//optional memeber
	private $sPassword;	//password to authorized user, sha1 in hex
	public $sEmail;	//email address
	public $sTel;	//telephone #
	public $sFax;	//fax #
	public $sMobile;	//mobile phone #
	public $sAddrId;	//post #
	public $sAddr;	//address
	public $created;	//created
	public $modified;	//modified
	//members that set only when corresponding function is called
	protected $__aCGroup = array();
	protected $__aCRule = array();
	//database setting
	static protected $sDBName = 'MYSQL';

	//instance pool
	static public $aInstancePool = array();

	/*
		constructor of $oCUser
		some class member are essential, must be initialized, or throw exception
		some class member are optional, may not be initialized
	*/
	public function __construct($multiData){
		parent::__construct($multiData);
		if(!is_array($multiData))
			throw new Exception("CUser: __construct failed, require an array");
		//initialize vital member
		if(isset($multiData['user_no']))
			$this->iUserNo = $multiData['user_no'];
		else
			$this->iUserNo = 0;
		$this->sName = $multiData['user_name'];
		$this->sAccount = $multiData['user_account'];
		if(!isset($this->iUserNo) || !isset($this->sName) || !isset($this->sAccount))
			throw new Exception("CUser: __construct failed, lack of vital member");
		//initialize optional member
		$this->sPassword = $multiData['user_password'];
		$this->sEmail = isset($multiData['user_email'])?$multiData['user_email']:'';
		$this->sTel = isset($multiData['user_tel'])?$multiData['user_tel']:'';
		$this->sFax = isset($multiData['user_fax'])?$multiData['user_fax']:'';
		$this->sMobile = isset($multiData['user_mobile'])?$multiData['user_mobile']:'';
		$this->sAddrId = isset($multiData['addr_id'])?$multiData['addr_id']:0;
		$this->sAddr = isset($multiData['user_addr'])?$multiData['user_addr']:'';
		$this->user_name = isset($multiData['user_name'])?$multiData['user_name']:'';
		//galaxy class memeber
		$this->bStatus = isset($multiData['flag'])?$multiData['flag']:'0';
		$this->sCreated = isset($multiData['created'])?$multiData['created']:date("Y-m-d H:i:s");
		$this->sModified = isset($multiData['modified'])?$multiData['modified']:date("Y-m-d H:i:s");

	}

	public function __destruct(){
		unset($this->__aCGroup);
		unset($this->__aCRule);
	}

	//php default function, let private member become read-only class member for others
    public function __get($varName)
    {
        return $this->$varName;
    }

	//static functions, most are used to find&get $oCUser
	/*
		get $oCUser by certain user_no
	*/
	static public function oGetUser($iUserNo){
		$oDB = self::oDB(self::$sDBName);
		//if already queryed
		if(isset(self::$aInstancePool[$iUserNo]))
			return self::$aInstancePool[$iUserNo];

		$sSql = "SELECT * FROM user WHERE user_no='$iUserNo'";
		$iDbq = $oDB->iQuery($sSql);
		$aRow = $oDB->aFetchAssoc($iDbq);
		//if($aRow === false || $oDB->iNumRows($iDbq)>1)
		if(!$aRow)
			return null;

		$oCUser = new CUser($aRow);
		self::$aInstancePool[$iUserNo] = $oCUser;
		return $oCUser;
	}

	static public function aGetUser($iUserNo){

		$oDB = self::oDB(self::$sDBName);

		$sSql = "SELECT * FROM user WHERE user_no='$iUserNo'";
		$iDbq = $oDB->iQuery($sSql);
		$aRow = $oDB->aFetchAssoc($iDbq);

		if(!$aRow){
			return null;
		}else{
			return $aRow;
		}
	}

	/*
		get all iwant user in an array
		if $sSearchSql is given, query only match users
		example1: $aCUsers = CUser::aAlluser('addr_id=12')
		example3: $aCUsers = CUSer::aAlluser('','ORDER BY createtime DESC LIMIT 0,10')
		CAUTION: this function may query lots of data from user DB, make sure you need all of these users
	*/
	static public function aAllUser($sSearchSql='',$sPostFix=''){
		$oDB = self::oDB(self::$sDBName);
		$aAllUser = array();
		$sSql = "SELECT * FROM `user`";
		if($sSearchSql!=='')
			$sSql .= " WHERE $sSearchSql";
		if($sPostFix!=='')
			$sSql .= " $sPostFix";
		$iDbq = $oDB->iQuery($sSql);
		while($aRow = $oDB->aFetchAssoc($iDbq)){
			$aAllUser[] = $aRow;
		}
		return $aAllUser;
	}

	/*
		get all group user in an array
		if $sSearchSql is given, query only match users
		example1: $aCUsers = CUser::aGroupUser('galaxy_group_user_rel.group_no=3')
		example2: $aCUsers = CUser::aGroupUser('galaxy_group_user_rel.group_no IN (3,26)')
		example3: $aCUsers = CUser::aGroupUser('galaxy_group_user_rel.group_no=3','ORDER BY user.createtime DESC LIMIT 0,10')
		caution: this function may query lots of data from user DB, make sure you need all of these users
	*/
	static public function aGroupUser($sSearchSql='',$sPostFix=''){
		$oDB = self::oDB(self::$sDBName);
		$aAllUser = array();

		$sSql = "SELECT * FROM `user` INNER JOIN `galaxy_group_user_rel` ON user.user_no=galaxy_group_user_rel.user_no";
		if ($sSearchSql!=='')
			$sSql .= " WHERE $sSearchSql";
		if($sPostFix!=='')
			$sSql .= " $sPostFix";
		$iDbq = $oDB->iQuery($sSql);
		while($aRow = $oDB->aFetchAssoc($iDbq)) {
			$aAllUser[] = new CUser($aRow);
		}
		return $aAllUser;
	}

	/*
		get count of user who match query
	*/
	static public function iGetCount($sSearchSql=''){
		$oDB = self::oDB(self::$sDBName);
		$sSql = "SELECT count(user_no) as total FROM user";
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

	/*
		login $oCurrentUser, and set in $session
	*/
	static public function vLogin($sUserAccount,$sUserPassword){
		$session = self::$session;
		$oCUser = self::oFindUserByAcc($sUserAccount);	//find user by acc
		if(!isset($oCUser))
			throw new Exception("CUser: not such user account($sUserAccount)");
		if($oCUser->bStatus=='0')
			throw new Exception("CUser: user account($sUserAccount) is deprecated");
		$oCUser->vAuthorize($sUserPassword);	//check the password

		//set oCurrentUser in session
		$session->sess_unset();
		$session->set('oCurrentUser', 	$oCUser);

		//for genesis & queue
		$session->set('user_name',$oCUser->sName);
		$session->set('user_no',$oCUser->iUserNo);
		$session->set('user_password',$sUserPassword);
	}

	/*
		find & get $oCUser by user_account
	*/
	static public function oFindUserByAcc($sUserAccount){
		$oDB = self::oDB(self::$sDBName);
		$sSql = "SELECT * FROM user WHERE `user_account`='$sUserAccount'";
		$iDbq = $oDB->iQuery($sSql);
		$aRow = $oDB->aFetchAssoc($iDbq);
		if(!$aRow || $oDB->iNumRows($iDbq)>1)
			return null;

		if(isset(self::$aInstancePool[$aRow['user_no']]))
			return self::$aInstancePool[$aRow['user_no']];

		$oCUser = new CUser($aRow);
		self::$aInstancePool[$oCUser->iUserNo] = $oCUser;
		return $oCUser;
	}

	/*
		find & get $0CUser by user_account without @domain.name
	*/
	static public function oFindUserByShortAcc($sShortAccount){
		$oDB = self::oDB(self::$sDBName);
		$sSql = "SELECT * FROM user WHERE `user_account` LIKE '$sShortAccount%'";
		$iDbq = $oDB->iQuery($sSql);
		$aRow = $oDB->aFetchAssoc($iDbq);
		if(!$aRow || $oDB->iNumRows($iDbq)>1)
			return null;

		if(!is_null(self::$aInstancePool[$aRow['user_no']]))
			return self::$aInstancePool[$aRow['user_no']];

		$oCUser = new CUser($aRow);
		self::$aInstancePool[$iUserNo] = $oCUser;
		return $oCUser;
	}

	/*
		find & get $oCUser by any field and any value targeted
	*/
	static public function oFindUserByField($sField,$sValue){
		$oDB = self::oDB(self::$sDBName);
		$sSql = $sSql = "SELECT * FROM user WHERE `$sField`=$sValue";
		$iDbq = $oDB->iQuery($sSql);
		$aRow = $oDB->aFetchAssoc($iDbq);
		if($aRow === false || $oDB->iNumRows($iDbq)>1)
			return null;

		if(!is_null(self::$aInstancePool[$aRow['user_no']]))
			return self::$aInstancePool[$aRow['user_no']];

		$oCUser = new CUser($aRow);
		self::$aInstancePool[$iUserNo] = $oCUser;
		return $oCUser;
	}

	//class function
	/*
		public function to check if $oCUser match the password
	*/
	public function vAuthorize($sUserPassword){
		if(!isset($this->sPassword))
			throw new Exception("CUser->vAuthorize: this user has no password; not allowed to login!");
		if($this->sPassword!==md5($sUserPassword))
			throw new Exception("CUser->vAuthorize: user and password not match!");
	}

	/*
		set and get group of $oCUser
	*/
	public function aGroup(){
		$oDB = self::oDB(self::$sDBName);
		if(empty($this->__aCGroup)){
			$sSql = "SELECT * FROM galaxy_group_user_rel WHERE user_no = '{$this->iUserNo}'";
			$iDbq = $oDB->iQuery2($sSql);
			while($aRow = $oDB->aFetchAssoc($iDbq)) {
				$this->__aCGroup[$aRow['group_no']] = CGroup::oGetGroup($aRow['group_no']);
			}
		}
		return $this->__aCGroup;
	}

	/*
		set and get rule of $oCUser
	*/
	public function aRule(){
		$oDB = self::oDB(self::$sDBName);
		if(empty($this->__aCRule)){
			$sGroups = array();
			foreach($this->aGroup() AS $oCGroup) {
				$aGroups[] = $oCGroup->iGroupNo;
			}

			if(empty($aGroups))
				return $this->__aCRule;

			$sGroups = implode(",", $aGroups);
			$sSql = "SELECT * FROM galaxy_group_rule_rel WHERE group_no in ($sGroups)";
			$iDbq = $oDB->iQuery($sSql);
			while($aRow = $oDB->aFetchAssoc($iDbq)) {
				$this->__aCRule[$aRow['rule_no']] = CRule::oGetRule($aRow['rule_no']);
			}
		}
		return $this->__aCRule;
	}

	/*
		check if $oCUser is allow to access target page
		if not, throw exception
	*/
	public function IsPermit($sFunc,$sAction){
		if($sFunc=='' && $sAction=='')
			return;
		foreach ($this->aRule() AS $oCRule) {
			if($oCRule->sFunc===$sFunc && $sAction=='')
				return;
			if($oCRule->sFunc===$sFunc && $oCRule->sAction===$sAction)
				return;
		}
		throw new Exception("CUser->IsPermit: current user is not allow to this page");
	}
	/*
		update user data in this CUser to user DB
		if you want to update user data in DB, get a CUser of that user, change member value, and call this function
		$oCUser->iUserNo & sAccount are not changeable
	*/
	public function vUpdateUser(){
		$oDB = self::oDB(self::$sDBName);
		$oCurrentUser = self::$session->get('oCurrentUser');
		$aValues = array(	'user_name'=>$this->sName,
							'user_password'=>$this->sPassword,
							'user_email'=>$this->sEmail,
							'user_tel'=>$this->sTel,
							'user_fax'=>$this->sFax,
							'user_mobile'=>$this->sMobile,
							'addr_id'=>$this->sAddrId,
							'user_addr'=>$this->sAddr,
							'flag'=>$this->bStatus
							);
		try{
			$oDB->vBegin();
			$oDB->sUpdate("user", array_keys($aValues), array_values($aValues), "`user_no` = {$this->iUserNo}");
			$oDB->vDelete('galaxy_group_user_rel',"`user_no`={$this->iUserNo}");
			foreach ($this->__aCGroup as $oCGroup) {
				$aGpValues = array(	'group_no'=>$oCGroup->iGroupNo,
										'user_no'=>$this->iUserNo
										);
				$oDB->sInsert('galaxy_group_user_rel',array_keys($aGpValues),array_values($aGpValues));
			}
			$oDB->vCommit();
		}catch (Exception $e){
			$oDB->vRollback();
			throw new Exception("CUser->vUpdateUser: ".$e->getMessage());
		}
	}

	/*
		add $oCUser data to user DB
		if you want to create a new user in DB, new a CUser and call this function
		if account already exist, throw exception
		return insert id(user_no), user may find this new CUser by user_no
	*/
	public function iAddUser(){
		$oDB = self::oDB(self::$sDBName);
		$oCurrentUser = self::$session->get('oCurrentUser');
		$oCUser = self::oFindUserByAcc($this->sAccount);	//find user by acc
		if(isset($oCUser))
			throw new Exception("CUser->vAddUser: user account($this->sAccount) already exist");
		$aValues = array(	'user_name'=>$this->sName,
							'user_account'=>$this->sAccount,
							'user_password'=>$this->sPassword,
							'user_email'=>$this->sEmail,
							'user_tel'=>$this->sTel,
							'user_fax'=>$this->sFax,
							'user_mobile'=>$this->sMobile,
							'addr_id'=>$this->sAddrId,
							'user_addr'=>$this->sAddr,
							'createtime'=>date("Y-m-d H:i:s"),
							'flag'=>$this->bStatus
							);
		try{
			$oDB->vBegin();
			$oDB->sInsert('user',array_keys($aValues),array_values($aValues));
			$this->iUserNo = $oDB->iGetInsertId();
			foreach ($this->__aCGroup as $oCGroup) {
				$aGpValues = array(	'group_no'=>$oCGroup->iGroupNo,
										'user_no'=>$this->iUserNo
										);
				$oDB->sInsert('galaxy_group_user_rel',array_keys($aGpValues),array_values($aGpValues));
			}
			$oDB->vCommit();
			return $this->iUserNo;
		}catch (Exception $e){
			$oDB->vRollback();
			throw new Exception("CUser->iAddUser: ".$e->getMessage());
		}
	}

	/*
		delete user data from DB by user_no, does not require a oCuser to
	*/

	/*
		activate this oCUser
	*/
	public function vActivate(){
		$oDB = self::oDB(self::$sDBName);
		$oCurrentUser = self::$session->get('oCurrentUser');

		if($this->bStatus==='1')
			$this->bStatus='0';
		else
			$this->bStatus='1';
		$aValues = array('flag'=>$this->bStatus);
		try{
			$oDB->sUpdate("user", array_keys($aValues), array_values($aValues), "`user_no` = {$this->iUserNo}");
		}catch (Exception $e){
			throw new Exception("CUser->vActivate: ".$e->getMessage());
		}
	}

	/*
		change password
	*/
	public function vChangePassword($sPassword){
		$this->sPassword = md5($sPassword);
	}

	/*
		set groups by array(group_no)
	*/
	public function vSetGroups($aGroupNos){
		if(!is_array($aGroupNos))
			return;
		$aGroupNos = array_unique($aGroupNos);
		$this->__aCGroup = array();	//clear all group
		foreach ($aGroupNos as $iGroupNo) {
			$this->__aCGroup[$iGroupNo] = new CGroup(array('group_no'=>$iGroupNo));
		}
	}


	/**
	* @param  $user_no 使用者流水號
	* @return 使用者姓名
	* @desc 得到某筆使用者姓名
	*/
	public static function sGetUserName($user_no="") {
		if($user_no == "") return "";
		$oDB = self::oDB(self::$sDBName);
		$aRow = array();
		$iDbq = $oDB->iQuery("SELECT user_name FROM user WHERE user_no=$user_no");
		if($oDB->iNumRows($iDbq)==0) return "";
		$aRow=$oDB->aFetchAssoc($iDbq);
		return $aRow['user_name'];

	}




	/**
	 *  @desc 貓舍使用者 參考oldcat CUser aGetActiveSaleUserData function
	 *  @create 2015/02/11
	 */
	public static function aGetActiveSaleUserData()
	{
		$oDB = self::oDB(self::$sDBName);
		$aRow = array();
		$iDbq = $oDB->iQuery("SELECT a.*,b.* FROM user AS a inner join user_oldcat AS b ON a.user_no=b.user_no WHERE b.mr_id in (4,5,10) AND a.flag='1' AND b.flag=1");
		if($oDB->iNumRows($iDbq)==0) return	array();
		$DataArr=array();
		while($aRow=$oDB->aFetchAssoc($iDbq))
			array_push($DataArr,$aRow);
		return $DataArr;
	}


	/**
	 * 取得所有使用者的資料 (Pagination)
	 *
	 * @param int $page 分頁數
	 * @param int $limit 單頁顯示筆數
	 * @param int $sOrder 排序欄位
	 * @param int $sSort 排序方式
	 */
	static public function AllUserPagination($page = 0, $limit = 50, $order = "user_no", $sort = "ASC", $where) {
		$aRow = self::aAllUser($where, "ORDER BY $order $sort LIMIT " . ( $page * $limit ) . " , $limit");
		return $aRow;
	}



	/**
	* @param  $value 值 , $field 欄位
	* @return 編修時檢查是否重複
	* @desc 是否重複
	* @created 2013/09/02
	*/
	static public function bEditIsExist($id = "",$field = "",$value = "") {
		$oDB = self::oDB(self::$sDBName);

		if ($value == "") return false;
		$iDbq = $oDB->iQuery("SELECT * FROM user WHERE flag!=9 AND user_no != '$id' AND $field = '$value'");
		if($oDB->iNumRows($iDbq)==0) return true;
		return false;
	}




	/**
	* @param  $sUserAccount 使用者帳號
	* @return 是否重複
	* @desc 使用者帳號是否重複
	*/
	static public function bIsExist($sUserAccount = ""){
		$oDB = self::oDB(self::$sDBName);

		if($sUserAccount == "") return false;
		$iDbq = $oDB->iQuery("SELECT * FROM user WHERE user_account='".trim($sUserAccount)."'");
		if($oDB->iNumRows($iDbq)==0) return true;
		return false;
	}



	/**
	 * 新增使用者
	 *
	 * @param int $UserData 使用者資料
	 */
	static public function AddUser($data) {
		$oDB = self::oDB(self::$sDBName);

		$aField = array_keys($data);
		$aValue = array_values($data);

		$sql = $oDB->sInsert("user", $aField, $aValue);

		if(!$sql) trigger_error("add user fail", E_USER_ERROR);

		return $oDB->iGetInsertId();
	}


	/**
	 * 更新使用者密碼
	 *
	 * @param int $user_no 使用者序號
	 * @param int $password 使用者密碼
	 */
	static public function NewPassWord($user_no, $password) {
		$oDB = self::oDB(self::$sDBName);

		$sql = "UPDATE user
			SET user_password = '".$password."'
			WHERE user_no = '".$user_no."'";
		$iRes = $oDB->iQuery($sql);
	}


	/**
	 * 更新使用者
	 *
	 * @param int $user_no 使用者序號
	 * @param int $UserData 使用者資料
	 */
	static public function UpdateUser($user_no, $UserData) {
		$oDB = self::oDB(self::$sDBName);

		$aField = array_keys($UserData);
		$aValue = array_values($UserData);

		$sql = $oDB->sUpdate("`user`", $aField, $aValue, "`user_no` = $user_no");

		if(!$sql) trigger_error("update user fail", E_USER_ERROR);
	}



	/**
	 *  @desc 抓取使用者List
	 *  @param array user_no
	 *  @return array user_no,user_name
	 */
	static public function aGetUserListByaUserNo($aUserNo = array()) {
		$oDB = self::oDB(self::$sDBName);

		$aReturn = array();
		if (!$aUserNo) return $aReturn;
		$sUserNo = implode(",",$aUserNo);
		$iDbq2 = $oDB->iQuery("SELECT user_no,user_name FROM user WHERE flag='1' AND user_no in ($sUserNo)");
		while ($aRow2 = $oDB->aFetchAssoc($iDbq2)) {
			$aReturn[] = $aRow2;
		}
		return $aReturn;
	}



}
?>