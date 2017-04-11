<?php

$sNowPath = realpath(dirname(dirname(dirname( __FILE__ ))));

include_once($sNowPath.'/inc/model/CUser.php');
include_once($sNowPath.'/inc/CDbShell.php');

Class CGalaxyClass
{
	static protected $aDB = array();
    static public $session;

    public $sCreated;
	public $sModified;
	public $bStatus;

	protected $__iUserNo;
	protected $__oLastUser;

	public function __construct($multiData){
        if(!isset($multiData['user_no'])){
            if(!is_null(self::$session))
                $oCUser = self::$session->get('oCurrentUser');
            if(!is_null($oCUser))
                $multiData['user_no'] = $oCUser->iUserNo;
        }
        $this->__iUserNo = isset($multiData['user_no'])?$multiData['user_no']:0;
	}

	public function __get($varName)
    {
        return $this->$varName;
    }


    public function sLocalCreateTime(){
        return date('Y-m-d H:i:s',strtotime('+8hour',strtotime($this->sCreateTime)));
    }

    public function sLocalModifiedTime(){
        return date('Y-m-d H:i:s',strtotime('+8hour',strtotime($this->sModifiedTime)));
    }

    static public function oDB($sDBName){
        if(!isset(self::$aDB[$sDBName])){
            self::$aDB[$sDBName] = new CDbShell(    constant('_'.$sDBName.'_DB'),
                                                    constant('_'.$sDBName.'_HOST'),
                                                    constant('_'.$sDBName.'_USER'),
                                                    constant('_'.$sDBName.'_PASS')
                                                    );
        }
        return self::$aDB[$sDBName];
    }


}
?>