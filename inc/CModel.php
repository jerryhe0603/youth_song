<?php

/**
 *  @desc 資料庫 Model 基本型
 *  @created 2014/02/26
 */

class CModel {

	protected $aDB = array();

	function __construct() {
		//$this->GENEISS_DB = new CDbShell(_GENESIS_DB, _GENESIS_HOST, _GENESIS_USER, _GENESIS_PASS); // 創世紀
		//$this->COMPANY_DB = new CDbShell(_COMPANY_DB, _COMPANY_HOST, _COMPANY_USER, _COMPANY_PASS); // 創世紀(公司)
		//$this->OLDCAT_DB = new CDbShell(_OLDCAT_DB,_OLDCAT_HOST,_OLDCAT_USER,_OLDCAT_PASS); // 貓舍
		//$this->USER_DB = new CDbShell(_USER_DB, _USER_HOST, _USER_USER, _USER_PASS); // 創世紀(使用者)
		//$this->ORDER_DB = new CDbShell(_ORDER_DB, _ORDER_HOST, _ORDER_USER, _ORDER_PASS); // 創世紀(訂單)
		/*
		$this->GENEISS_DB = $this->oDB("GENESIS");
		$this->COMPANY_DB = $this->oDB("COMPANY");
		$this->OLDCAT_DB = $this->oDB("OLDCAT");
		$this->USER_DB = $this->oDB("USER");
		$this->ORDER_DB = $this->oDB("ORDER");
		*/
	}
	
	/**
	 *
	 * @param string $name Name of the model
	 * @param string $path Location of the models
	 */
	public function loadModel($name, $modelPath = 'libs/models/') {
		require $modelPath . $name.'.php';

		/*
		if (file_exists($path)) {
			//require $modelPath . $name.'.php'; // 使用 Require 可以保證只載入一次，目前過度套用時期，所以先使用 include_once
			include_once $modelPath . $name.'.php';
			return new $name();
		} else if (file_exists('../lib/model/' . $name.'.php')) {
			//require $modelPath . $name.'.php'; // 使用 Require 可以保證只載入一次，目前過度套用時期，所以先使用 include_once
			include_once '../lib/model/' . $name.'.php';
			return new $name();
		} else {
			trigger_error("no $name model, $path", E_USER_ERROR);
		}*/
	}
	
	public function oDB($sDBName) {
        if (!isset($this->aDB[$sDBName])) {
			$this->aDB[$sDBName] = new CDbShell (
				constant('_'.$sDBName.'_DB'),
				constant('_'.$sDBName.'_HOST'),
				constant('_'.$sDBName.'_USER'),
				constant('_'.$sDBName.'_PASS')
			);
		}
		return $this->aDB[$sDBName];
       
    }
	

}

?>