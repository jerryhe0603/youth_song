<?php

class CMisc
{

	function __construct() {
	}
	static public function my_quotes($data) {

		if(is_array($data)) {
			foreach($data as $key => $val) {
				if(is_array($data[$key])) {
					$data[$key] = self::my_quotes($data[$key]);
				} else {
					$data[$key] = get_magic_quotes_gpc() ?  trim($data[$key]) : addslashes(trim($data[$key]));
				}
			}
		} else {
			$data = get_magic_quotes_gpc() ?  trim($data) : addslashes(trim($data));
		}

		return $data;
	}

	static public function my_quotes2($tar) {

		if( !is_array($tar) )
			return get_magic_quotes_gpc() ?  trim($tar) : addslashes(trim($tar));

		return array_map(self::my_quotes2, $tar); //pass ref to function
	}



	static public function my_quotes_php5($data) {
		if(is_array($data)) {
			foreach($data as $key => $val) {
				if(is_array($data[$key])) {
					$data[$key] = self::my_quotes_php5($data[$key]);
				} else {
					$data[$key] = get_magic_quotes_gpc() ?  trim($data[$key]) : addslashes(trim($data[$key]));
				}
			}
		} else {
			$data = get_magic_quotes_gpc() ?  trim($data) : addslashes(trim($data));
		}
		return $data;
	}




	static public function sMakePageBar($iAllItems,$iPageItems,$iPg=0,$sUrl)
	{
		$PHP_SELF = $_SERVER['PHP_SELF'];
		if ($iPageItems) $iPages=(INT)($iAllItems/$iPageItems);
		else $iPages=0;
		$sPageBar="";
		$current=0;
		if($iPageItems AND $iAllItems%$iPageItems==0) $iPages--;
			if($iPages > 0) {

				//=====Make PageBar===============

				$iPrevPage=$iPg-1;
				if($iPrevPage<0) $iPrevPage=0;
				$iNextPage=$iPg+1;
				if($iNextPage>$iPages) $iNextPage=$iPages;

				//if($iPg>0) $sPageBar.="<li><a href='$PHP_SELF?$sUrl&page=0'> "._LANG_FIRST_PAGE." </a></li>";
						//else $sPageBar.="&nbsp;"._LANG_FIRST_PAGE." ";
				if($iPg>0){
					$sPageBar.="<li class='prev '><a href='$PHP_SELF?$sUrl&items=$iPageItems&page=$iPrevPage'><i class='icon-double-angle-left'></i></a></li>";
				}
				else $sPageBar.="<li class='prev disabled'><a href='$PHP_SELF?$sUrl&items=$iPageItems&page=$iPrevPage'><i class='icon-double-angle-left'></i></a></li>";

				$iTp=9;
					$iPi=0;
					$iPage=$iPg;
					$iHpages=$iPages;
					if($iPages>$iTp) {
							$htp=(INT)($iTp/2);
							$iPi=$iPage-$htp;
							if($iPi<0) $iPi=0;
							$iHpages=$iPi+$iTp;
							if($iHpages >$iPages ) {
							  $iHpages=$iPages;
							  $iPi=$iHpages-$iTp;
							 }
					}

						for($i=$iPi;$i<=$iHpages;$i++) {
							if($iPage!=$i){
								$sPageBar.= "<li><a href='$PHP_SELF?$sUrl&items=$iPageItems&page=$i'>";

							 $sPageBar.=($i+1);
							 $sPageBar.="</a></li>";
							} else {
									$sPageBar.="<li class='active'><a>".($i+1)."</a></li>";
									$current = $i+1;
							}
						}
					if($iPg < $iPages){
					$sPageBar.="<li class='next'><a href='$PHP_SELF?$sUrl&items=$iPageItems&page=$iNextPage' ><i class='icon-double-angle-right'></i></a></li>";
				}

						$sPageBar.='<li><a>Page '.($current).' of '.($iPages+1).'</a></li>';
				//=====Make PageBar===============
			}

		if($sPageBar) {
			$sPageBar = "<ul>".$sPageBar."</ul>";
		}

		return $sPageBar;
	}

	/**
	* @param $iAllItems 資料比數 $iPageItems 每頁顯示比數 $iPg 目前頁數 $sUrl 頁數連結
	* @return 數字
	* @desc 得到某筆資料是在第幾頁
	*/
	public static function sMakePageBar2($iAllItems,$iPageItems,$iPg=0,$sUrl) {
		$iAllItems = (INT)$iAllItems;
		$iPageItems = (INT)$iPageItems;
		if ($iPageItems) $iPages=(INT)($iAllItems/$iPageItems);
		if ($iPageItems) {
			if ($iAllItems%$iPageItems==0) $iPages--;
		}
		$sPageBar="";
		if($iPages > 0) {
			//=====Make PageBar===============
			$iPrevPage=$iPg-1;
			if($iPrevPage<0) $iPrevPage=0;
			$iNextPage=$iPg+1;
			if ($iNextPage>$iPages) $iNextPage=$iPages;
			$sPageBar="<ul class='pagination'>";
			if($iPg>0) $sPageBar.="<li><a href='".$_SERVER['PHP_SELF']."?$sUrl&page=0'> "._LANG_FIRST_PAGE." </a></li>";
			if ($iPg>0) {
				$sPageBar.="<li><a href='".$_SERVER['PHP_SELF']."?$sUrl&page=$iPrevPage'> "._LANG_PREV_PAGE." </a></li>";
			}

			$iTp=9;
			$iPi=0;
			$iPage=$iPg;
			$iHpages=$iPages;
			if($iPages>$iTp) {
				$htp=(INT)($iTp/2);
				$iPi=$iPage-$htp;
				if($iPi<0) $iPi=0;
				$iHpages=$iPi+$iTp;
				if($iHpages >$iPages ) {
					$iHpages=$iPages;
					$iPi=$iHpages-$iTp;
				}
			}

			for($i=$iPi;$i<=$iHpages;$i++) {
				if($iPage!=$i){
					$sPageBar.= "<li><a href=".$_SERVER['PHP_SELF']."?$sUrl&page=$i>";
					$sPageBar.=" ".($i+1)." ";
					$sPageBar.="</a></li>";
				} else {
					$sPageBar.="<li class='active'><a href='javascript:void(0);'>".($i+1)."</a></li>";
					$current = $i+1;
				}
			}
			if($iPg < $iPages){
				$sPageBar.="<li><a href='".$_SERVER['PHP_SELF']."?$sUrl&page=$iNextPage'> "._LANG_NEXT_PAGE." </a></li>";
			}
			if($iPg<$iPages ) $sPageBar.="<li><a href='".$_SERVER['PHP_SELF']."?$sUrl&page=$iPages'> "._LANG_LAST_PAGE." </a></li>";
			$sPageBar.="<li><a>Page $current of ".($iPages+1)."</a></li></ul>";
			//=====Make PageBar===============
		}
		return $sPageBar;
	}

	/**
	* @param $iAllItems 資料比數 $iPageItems 每頁顯示比數 $iPg 目前頁數 $sUrl 頁數連結
	* @return 數字
	* @desc 得到某筆資料是在第幾頁
	*/
	public static function sMakePageBar3($iAllItems,$iPageItems,$iPg=0,$sUrl) {
		$iAllItems = (INT)$iAllItems;
		$iPageItems = (INT)$iPageItems;
		if ($iPageItems) $iPages=(INT)($iAllItems/$iPageItems);
		if ($iPageItems) {
			if ($iAllItems%$iPageItems==0) $iPages--;
		}
		$sPageBar="";
		if($iPages > 0) {
			//=====Make PageBar===============
			$iPrevPage=$iPg-1;
			if($iPrevPage<0) $iPrevPage=0;
			$iNextPage=$iPg+1;
			if ($iNextPage>$iPages) $iNextPage=$iPages;
			$sPageBar="<ul>";
			if($iPg>0) $sPageBar.="<li><a href='".$_SERVER['PHP_SELF']."?$sUrl&page=0'> <i class='icon-double-angle-left'></i> </a></li>";
			if ($iPg>0) {
				$sPageBar.="<li><a href='".$_SERVER['PHP_SELF']."?$sUrl&page=$iPrevPage'> <i class='icon-angle-left'></i> </a></li>";
			}

			$iTp=9;
			$iPi=0;
			$iPage=$iPg;
			$iHpages=$iPages;
			if($iPages>$iTp) {
				$htp=(INT)($iTp/2);
				$iPi=$iPage-$htp;
				if($iPi<0) $iPi=0;
				$iHpages=$iPi+$iTp;
				if($iHpages >$iPages ) {
					$iHpages=$iPages;
					$iPi=$iHpages-$iTp;
				}
			}

			for($i=$iPi;$i<=$iHpages;$i++) {
				if($iPage!=$i){
					$sPageBar.= "<li><a href=".$_SERVER['PHP_SELF']."?$sUrl&page=$i>";
					$sPageBar.=" ".($i+1)." ";
					$sPageBar.="</a></li>";
				} else {
					$sPageBar.="<li class='active'><a href='javascript:void(0);'>".($i+1)."</a></li>";
					$current = $i+1;
				}
			}
			if($iPg < $iPages){
				$sPageBar.="<li><a href='".$_SERVER['PHP_SELF']."?$sUrl&page=$iNextPage'> <i class='icon-angle-right'></i> </a></li>";
			}
			if($iPg<$iPages ) $sPageBar.="<li><a href='".$_SERVER['PHP_SELF']."?$sUrl&page=$iPages'> <i class='icon-double-angle-right'></i> </a></li>";
			$sPageBar.="<li><a>Page $current of ".($iPages+1)."</a></li></ul>";
			//=====Make PageBar===============
		}
		return $sPageBar;
	}




	static public function uuid_v1($prefix = '') {
		//8-4-4-4-12
		//440612e0-9cea-4751-8e59-07ebffc589c2
		$uuid  = substr(strtoupper(md5(uniqid(mt_rand(), true))),0,8) . '-';
		$uuid .= substr(strtoupper(md5(uniqid(mt_rand(), true))),8,4) . '-';
		$uuid .= substr(strtoupper(md5(uniqid(mt_rand(), true))),12,4) . '-';
		$uuid .= substr(strtoupper(md5(uniqid(mt_rand(), true))),16,4) . '-';
		$uuid .= substr(strtoupper(md5(uniqid(mt_rand(), true))),20,12);

		return $prefix . $uuid;
	}
//	/**
//	 *
//	 * @desc 多樣化頁條
//	 * @param $iAllItems
//	 * @param $iPageItems
//	 * @param $iPg
//	 * @param $sUrl
//	 */
//	function sRenderMultiPageBar($totalNum,$limitNum,$pg=0,$attributes=array()){
//		$PHP_SELF = $_SERVER['PHP_SELF'];
//		$iPages=(INT)($totalNum/$limitNum);
//		if($totalNum%$limitNum==0) $iPages--;
//			if($iPages > 0) {
//
//				$sPageBar="";
//				$iPrevPage=$pg-1;
//				if($iPrevPage<0){
//					$iPrevPage=0;
//				}
//				$iNextPage=$pg+1;
//				if($iNextPage>$iPages){
//					$iNextPage=$iPages;
//				}
//				$sPageBar="";
//
//				if($pg>0){
//					$sPageBar.="&nbsp;<a href='$PHP_SELF?$sUrl&page=0' class='page-x' > "._LANG_FIRST_PAGE." </a>";
//				}
//				if($pg>0){
//					$sPageBar.="&nbsp;<a href='$PHP_SELF?$sUrl&page=$iPrevPage' class='page-x' > "._LANG_PREV_PAGE." </a>";
//				}
//
//				$iTp=9;
//				$iPi=0;
//				$iPage=$pg;
//				$iHpages=$iPages;
//				if($iPages>$iTp) {
//					$htp=(INT)($iTp/2);
//					$iPi=$iPage-$htp;
//					if($iPi<0) $iPi=0;
//					$iHpages=$iPi+$iTp;
//					if($iHpages >$iPages ) {
//						$iHpages=$iPages;
//						$iPi=$iHpages-$iTp;
//					}
//				}
//
//				for($i=$iPi;$i<=$iHpages;$i++) {
//					if($iPage!=$i){
//						$sPageBar.= "&nbsp;<a href='$PHP_SELF?$sUrl&page=$i' class='page-x'>";
//
//						$sPageBar.=($i+1);
//						$sPageBar.="</a>&nbsp;";
//					} else {
//						$sPageBar.="&nbsp;<span class='active page-x'>".($i+1)."</span>&nbsp;";
//						$current = $i+1;
//					}
//				}
//				if($pg < $iPages){
//					$sPageBar.="&nbsp;<a href='$PHP_SELF?$sUrl&page=$iNextPage' class='page-x' >"._LANG_NEXT_PAGE."</a>&nbsp;";
//				}
//				if($pg < $iPages ){
//					$sPageBar.="&nbsp;<a href='$PHP_SELF?$sUrl&page=$iPages' class='page-x'>"._LANG_LAST_PAGE."</a>&nbsp;";
//				}
//				$sPageBar.='&nbsp;<span class="inactive page-x-of-y">Page '.($current).' of '.($iPages+1).'</span>&nbsp;';
//			}
//		return $sPageBar;
//
//	}

	static public function sConvToBase64($String)
	{
		/*
		author: roger.tsai@iwant-in.net
		program: 將文章的中文字作編碼 好讓 mysql 作全文檢索
		*/

		//先將 \r 去掉後面會處理 \n
		$String=str_replace("\r","",$String);

		$b_last_str_type='';
		$ary_conv_str=array();
		for($i_ascii=0;$i_ascii<strlen($String);$i_ascii++)
		{
			$i_part_of_ascii = ord(substr($String,$i_ascii,1)) ;
			if($i_part_of_ascii>127)
			{
				//中文字
				$Str=substr($String,$i_ascii,3);
				$part1=base_convert(ord($Str[0]),10,16);
				$part2=base_convert(ord($Str[1]),10,16);
				$part3=base_convert(ord($Str[2]),10,16);
				if ($b_last_str_type == 'en'){$ary_conv_str[] = ' ';}
				$ary_conv_str[] = "$part1$part2$part3";
				$ary_conv_str[] = ' ';
				$b_last_str_type  = 'ch';
				$i_ascii +=2;
			}
			else if ($i_part_of_ascii == 9 || $i_part_of_ascii == 32 || $i_part_of_ascii == 10) //9 = " " 32 = "\t" 10 = "\n"
			{
				//如果前一個字原是英文
				if ($b_last_str_type=='en')
				{
					$ary_conv_str[] =  " ";
					$b_last_str_type  = 'space';
				}
			}
			else
			{
				//if ($b_last_str_type == 'ch'){$ary_conv_str[] = ' ';}
				$ary_conv_str[] = substr($String,$i_ascii,1) ;
				$b_last_str_type ='en';
			}
		}

		return implode("", $ary_conv_str);
	}

	/**
	 *
	 * @desc 取得網址的domain
	 * @author Joe
	 * @param $url 一個網址
	 */
	static public function getDomain($url){
		$pieces = parse_url($url);
		$domain = isset($pieces['host']) ? $pieces['host'] : '';

		if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)){
			return $regs['domain'];
		}

		return false;
	}

	/**
	* @param $array  陣列 $id
	* @return array
	* @desc 排序hash array 依照欄位 desc asc
	*/

	static public function msort($array, $id="", $sort_ascending=true) {
        if($id=="") return $array;
		$temp_array = array();
        while(count($array)>0) {
            $lowest_id = 0;
            $index=0;
            foreach ($array as $item) {
                if (isset($item[$id])) {
                                    if ($array[$lowest_id][$id]) {
                    if ($item[$id]<$array[$lowest_id][$id]) {
                        $lowest_id = $index;
                    }
                    }
                                }
                $index++;
            }
            $temp_array[] = $array[$lowest_id];
            $array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
        }
                if ($sort_ascending) {
            return $temp_array;
                } else {
                    return array_reverse($temp_array);
                }
    }

    /*
    	debug function
    */
    static public function vPrintR($var){
    	echo '<pre>';
    	print_r($var);
    	echo '</pre>';
    }

    /*
		html quote
    */
	static public function mHtmlQuote($data){
		if(is_array($data)) {
			foreach($data as $key => $val) {
				if(is_array($data[$key])) {
					$data[$key] = self::mHtmlQuote($data[$key]);
				}elseif(is_object($data[$key])){
					foreach ($data[$key] as $name => $value) {
						$data[$key]->$name = self::mHtmlQuote($data[$key]->$name);
					}
				}else{
					$data[$key] = htmlspecialchars($data[$key]);
				}
			}
		} else {
			$data = htmlspecialchars($data);
		}

		return $data;
	}

    /*
		encode file to base64_string file
		example:	vEncodeBase64('/tmp/file.png','/tmp/encoded.png');
    */
	static public function vEncodeBase64 ($sInputFilePath,$sBase64FilePath) {

		//file filter
		if(!file_exists($sInputFilePath)){
			echo 'input file not exist';
			return;
		}
		if(file_exists($sBase64FilePath)){
			echo 'output file already exist';
			return;
		}

		//get file type
		$oFinfo = finfo_open(FILEINFO_MIME_TYPE);
		$sFileType = finfo_file($oFinfo, $sInputFilePath);
		finfo_close($oFinfo);

		//encode base64 with file type
        $imgbinary = fread(fopen($sInputFilePath, "r"), filesize($sInputFilePath));
		$sBase64String = 'data:' . $sFileType . ';base64,' . base64_encode($imgbinary);

        //create output file
	    $rFile = fopen( $sBase64FilePath, "wb" );
	    fwrite( $rFile, $sBase64String );
	    fclose( $rFile );
        return;
	}


    /*
		decode base64_string file to given path
		example:	vDecodeBase64('/tmp/base_64.png','/tmp/decoded.png');
    */
    static public function vDecodeBase64($sBase64FilePath, $sOutputFilePath ) {

    	//file filter
    	if(!file_exists($sBase64FilePath)){
			echo 'input file not exist';
			return;
		}
		if(file_exists($sOutputFilePath)){
			echo 'output file already exist';
			return;
		}

    	//get base64 string
    	$sBase64String = file_get_contents($sBase64FilePath);
    	$aData = explode(',', $sBase64String);

    	//create output file
	    $rFile = fopen( $sOutputFilePath, "wb" );
	    fwrite( $rFile, base64_decode($aData[1]) );
	    fclose( $rFile );

	    return;
	}

	/*
		remove directory and all file inside
	*/
	static public function vPurgeDir($sDirPath){
		if(!is_dir($sDirPath))
			return;

		//parse dir
		$aFileNames = scandir($sDirPath);

		foreach ($aFileNames as $sFileName) {
			if($sFileName=='.' || $sFileName=='..')
				continue;

			$sCurrentPath = $sDirPath.'/'.$sFileName;

			if(is_dir($sCurrentPath))
				self::vPurgeDir($sCurrentPath);
			else
				unlink($sCurrentPath);
		}
		rmdir($sDirPath);
		return;
	}

	/*
		close bumblebee queue window
	*/
	static public function vBumblebeeExit() {
		echo
			"<script>
				setTimeout(function() {
					bumblebee_network.webSocketSendMessage('{\"state\":1}');
				}, 2000);
			</script>";
	}

	/*
		close bumblebee sub window
	*/
	static public function vBumblebeeCloseWindow($iDelay=2000){
		echo "<script>";
		echo "setTimeout(function() {";
		echo "bumblebee_setting.closeWindow( bumblebee_setting.getWinGUID() );";
		echo "}, $iDelay)";
		echo "</script>";
		exit;
	}

	static public function iChangeTime($iTime) {

		$date   = date('Y-m-d', $iTime);

		$hour   = date('H', $iTime);

		$minute = date('i', $iTime);

		$minute = intval($minute / 3) * 3;

		return strtotime("$date $hour:$minute:00");
	}

	static public function aCurl($sUrl,$aOptions){
		$curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $sUrl);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_POST, true);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($aOptions));
		$jResult = curl_exec($curl);
		curl_close($curl);
		$aResult = json_decode($jResult,true);
		return $aResult;
	}

    /**
     *  @desc 判斷是否為 Command line
     *  @created 2015/11/27
     */
    static public function isCommandLineInterface() {
        return (php_sapi_name() === 'cli');
    }


	/**
	 *  @desc Command line echo message
	 *  @usage logs("總計蒐錄: " . $task_article_total);
	 *  @param $color string "green", "red", "blue"
	 *  @created 2014/09/05
	 */
	static public function logs($s, $isNewLine = true, $isBig5 = false, $color = '') {
		$str = '';
		if($isNewLine) {
			$newline = "\n";
		} else {
			$newline = ", ";
		}

        if (!self::isCommandLineInterface()) {
            if($isNewLine) $newline = "<br>";
            else $newline = "";
            if ($color) {
                $str = "<font color=$color>$s</font>".$newline;
            } else {
                 $str = $s.$newline;
            }
        } else if(PHP_OS == 'WINNT' || PHP_OS == 'WIN') {
			if (!$isBig5) $str = mb_convert_encoding($s, "CP950", "UTF-8").$newline;
			else $str = mb_convert_encoding($s, "CP950", "Big5").$newline;
		} else {
			//if ($isBig5)
			//	$str = mb_convert_encoding($s, "Big5", "UTF-8").$newline;
			//else
				$str = $s.$newline;
		}

		echo $str;
        if (!self::isCommandLineInterface()) {
            flush();
            ob_flush();
        }
	}


	/**
	* @param $sThisString 資料 $sLength 擷取長度
	* @return 字串
	* @desc 得到已經擷取資料長度的字串
	*/
	static public function sStrChop($sThisString,$sLength = 50) {
		$mb_string=false;
		if (function_exists('mb_substr')) {
			$mb_string = true;
		}
		$sResult="";
    	$sMyString=trim($sThisString);

		if ($mb_string) {
			if (mb_strlen($sMyString,"UTF-8")>$sLength) {
				$sResult=mb_substr($sMyString,0,$sLength,"UTF-8");
				$sResult = "$sResult";
			} else {
				$sResult=$sMyString;
			}
		} else {
			if (strlen($sMyString)>$sLength) {
				$sResult=substr($sMyString,0,$sLength);
				$sResult = "$sResult";
			} else {
				$sResult=$sMyString;
			}
    	}
		return($sResult);
  	}

	function sRandomString($sString,$sNum){ //(字元,回傳幾位)
		$rs = '';
        if(strlen($sString)==0){
            $s="ABCDEFGHJKMNPQRSTUVWXYZ";
            $s.="abcdefghjkmnpqrstuvwxyz";
            $s.="23456789";
        } else {
            $s=$sString;
        }
        for($i=0;$i<$sNum;$i++){
            $rs.=$s{rand(0,strlen($s)-1)};
        }
        return $rs;
    }

	function sRandomNumber($sString,$sNum){ //(數字,回傳幾位)
        if(strlen($sString)==0){
            $s.="0123456789";
        } else {
            $s=$sString;
        }
        for($i=0;$i<$sNum;$i++){
            $rs.=$s{rand(0,strlen($s)-1)};
        }
        return $rs;
    }




}
?>