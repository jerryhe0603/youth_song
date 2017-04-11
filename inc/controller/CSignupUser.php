<?php

class CSignupUser {
//extends CGalaxyController{

	function __construct(){
	}

	function tManager() {
		$action = isset($_GET['action'])?$_GET['action']:"list";
		switch($action){
			case 'view':
				return $this->tNewView(); // 明細頁
				break;
			default :
				return $this->tCSignupIndex(); // show
				break;
		}
	}


	function tCSignupIndex() {
		global $Smarty;
		return $output = $Smarty->fetch('./user/signup.html');

	}

}
?>
