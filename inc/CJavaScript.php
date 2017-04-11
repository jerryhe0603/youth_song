<?php
/**
 * CJavascrip 類別
 *
 * @package dcmc
 */
class CJavaScript {

	/**
	 * 建構子
	 */
	function __construct() {
	}

	/**
	 * javascrip
	 *
	 * @param string $msg 專案序號
	 */
	 public function vJsAlertHistory($msg, $go = -1) {
		header('Content-Type: text/html; charset=utf-8');

		echo "<script>";
		echo "alert('".$msg."');";
		echo "window.history.go($go);";
		echo "</script>";
		exit;
	}

	 public function vJsHistory($go = -1) {
		header('Content-Type: text/html; charset=utf-8');

		echo "<script>";
		echo "window.history.go($go);";
		echo "</script>";
		exit;
	}

	static public function vAlertRedirect($msg , $url) {
		header('Content-Type: text/html; charset=utf-8');

		echo "<script>";
		if($msg) {
                    echo "alert('".$msg."');";
                }
		//echo "window.location.href='./index.php?mode=logout'";
		echo "window.location.href='$url'";
		echo "</script>";
		exit;
	}

	static public function vAlert($msg) {
		echo "<script>";
		echo "alert('".$msg."');";
		echo "</script>";
	}

	static public function vRedirect($url) {
		echo "<script>";
		echo "window.location.href='$url'";
		echo "</script>";
		exit;
	}

	/**
	* @param 設定 $go 第幾頁 負數為前幾頁
        	* @desc  回第幾頁
       	*/
	static public function vBack($go=-1) {
		echo "<script language=javascript>";
		echo "Javascript:history.go($go)";
		echo "</script>";
		exit;
	}
	/**
	* @param 設定 $sSrc url src  $iCache=1 將url多一變數加入時間就部會被IE cache
        	* @desc  重導目前這層
   	*/
	static public function vUpdateThisFrame($sSrc,$iCache=0){
		if(!$iCache)	$sSrc = self::sRndSrc($sSrc);
		echo "\n<script language=javascript>\n";
		echo "window.location.href='$sSrc';\n";
		echo "</script>\n";
	}
	/**
	* @param 設定 $sSrc url src
        	* @desc  將url加一time
       	*/
	static public function sRndSrc($sSrc){

		if(preg_match("/\?/",$sSrc)) {
			$sSrc=$sSrc."&"."ranload=".time();
		} else $sSrc=$sSrc."?"."ranload=".time();
		return $sSrc;
	}
}
?>