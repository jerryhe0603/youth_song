<?php

/**
 *  @desc 驗證資料
 *  @created 2014/06/01
 */

class validator {

	public function __construct() {
	}

	/**
	 *  @desc 檢查身分證字號
	 *  @created 2014/06/01
	 */
	public static function bCheckIdendity($id) {
	    $id=strtoupper($id);
	    $d1=substr($id,0,1);
	    if(strlen($id)!=10) {return FALSE;}
	    if(stristr('ABCDEFGHJKLMNPQRSTUVXYWZIO',$d1)===FALSE) {return FALSE;}
	    if(substr($id,1,1)!='1' && substr($id,1,1)!='2') {return FALSE;}
	    if(!is_numeric(substr($id,1,9))) {return FALSE;}

	    $num=array(
	        'A'=>'10','B'=>'11','C'=>'12','D'=>'13','E'=>'14','F'=>'15','G'=>'16','H'=>'17','J'=>'18','K'=>'19','L'=>'20','M'=>'21','N'=>'22',
	        'P'=>'23','Q'=>'24','R'=>'25','S'=>'26','T'=>'27','U'=>'28','V'=>'29','X'=>'30','Y'=>'31','W'=>'32','Z'=>'33','I'=>'34','O'=>'35',
	    );
	    $n1=substr($num[$d1],0,1)+(substr($num[$d1],1,1)*9);
	    unset($num,$d1);
	    $n2=0;
	    for($j=1;$j<9;$j++) {
	        $d4=substr($id,$j,1);
	        $n2=$n2+$d4*(9-$j);
	    }
	    $n3=$n1+$n2+substr($id,9,1);
	    if(($n3 % 10)!=0){return FALSE;}
	    return TRUE;
	}

	/**
	 *  @desc 檢查電子郵件
	 *  @created 2014/06/01
	 */
	public static function bCheckEmail($email='') {
		if (strlen(trim($email))==0) {
			return false;
		} else if (!preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/', $email)) {
			return false;
		} else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return false;
		} else if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
			return false;
		//如果信箱是gmail.com ＠前面至少為6個字且小於30個字
		} else {
			$aEmail = explode('@',$email);
			if($aEmail[1] =='gmail.com'){
				if(strlen($aEmail[0])<6) return false;
				if(strlen($aEmail[0])>30) return false;
			}else if($aEmail[1] =='yahoo.com.tw'){
				// echo "<pre>";print_r($aEmail);exit;
				if(strlen($aEmail[0])<4) return false;
			}else if($aEmail[1] =='noicd.com'){
				return false;
			}else if($aEmail[1] =='mvrht.com'){
				return false;
			}else if($aEmail[1] =='mailnesia.com'){
				return false;
			}else if($aEmail[1] =='tempmailer.de'){
				return false;
			}else if($aEmail[1] =='maileme101.com'){
				return false;
			}else if($aEmail[1] =='stromox.com'){
				return false;
			}else if($aEmail[1] =='zainmax.net'){
				return false;
			}else if($aEmail[1] =='9me.site'){
				return false;
			}else if($aEmail[1] =='dr69.site'){
				return false;
			}else if($aEmail[1] =='zain.site'){
				return false;
			}else if($aEmail[1] =='diwaq.com'){
				return false;
			}else if($aEmail[1] =='drdrb.net'){
				return false;
			}else if($aEmail[1] =='sharklasers.com'){
				return false;
			}else if($aEmail[1] =='guerrillamail.info'){
				return false;
			}else if($aEmail[1] =='grr.la'){
				return false;
			}else if($aEmail[1] =='guerrillamail.biz'){
				return false;
			}else if($aEmail[1] =='guerrillamail.com'){
				return false;
			}else if($aEmail[1] =='guerrillamail.de'){
				return false;
			}else if($aEmail[1] =='guerrillamail.net'){
				return false;
			}else if($aEmail[1] =='guerrillamail.org'){
				return false;
			}else if($aEmail[1] =='guerrillamailblock.com'){
				return false;
			}else if($aEmail[1] =='pokemail.net'){
				return false;
			}else if($aEmail[1] =='spam4.me'){
				return false;
			}else if($aEmail[1] =='yomail.info'){
				return false;
			}else if($aEmail[1] =='jetable.org'){
				return false;
			}else if($aEmail[1] =='mohmal.im'){
				return false;
			}else if($aEmail[1] =='maildrop.cc'){
				return false;
			}
		}
		return true;
	}

	/**
	 *  @desc 檢查網址
	 *  @created 2014/06/01
	 */
	public static function bCheckUrl($url='') {
		if (strlen(trim($url))==0) {
			return false;
		} else if (!filter_var($url, FILTER_VALIDATE_URL)) {
			return false;
		} else if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
			return false;
		}
		return true;
	}


	/**
	 *  @desc 驗證統一編號
	 *  @created 2014/05/22
	 */
	public static function bCheckTaxId($tax_id=0) {
		$tbNum = array(1,2,1,2,1,2,4,1);
		if(strlen($tax_id)!=8 || !eregi("^[0-9\*]{8}",$tax_id)) return false;
		$intSum = 0;
		for ($i = 0; $i < count($tbNum); $i++)
		{
		$intMultiply=substr($tax_id,$i,1)*$tbNum[$i];
		$intAddition=(floor($intMultiply / 10) + ($intMultiply % 10));
		$intSum+=$intAddition;
		}
		return ($intSum % 10 == 0 ) || ($intSum%10==9 && substr($tax_id,6,1)==7);
	}


	/**
	 *  @desc 檢查台灣手機
	 *  @created 2014/06/01
	 */
	public static function bCheckMobile($mobile='') {
		if (strlen(trim($mobile))==0) return false;

		// 手機格式1 09XX-XXX-XXX
        // preg_replace("/([0-9]{4})([0-9]{3})([0-9]{3})/","$1-$2-$3",$postData['event_mobile']);
		//$regexp1 = "/09[0-9]{2}-[0-9]{3}-[0-9]{3}/";
        //if (strlen(trim($mobile))>12) return false;

		//手機格式2 09XX-XXXXXX
		//$regexp2 = "09[0-9]{2}-[0-9]{6}";

        // 手機格式3 09XXXXXXXX
        $regexp3 = "/09[0-9]{2}[0-9]{3}[0-9]{3}/";
        if (strlen(trim($mobile))>10) return false;

		// 套用手機格式1的比對結果正常
		if (!preg_match($regexp3, $mobile)) {
			return false;
		}
		return true;
	}


	/**
	 *  @desc 檢查台灣電話號碼格式
	 *  @created 2014/06/01
	 */
	public static function bCheckTelephoe($tel='') {
		$pattern = '/^\\(?(0|\\+886)[-. ]?[2-9][\\)-. ]?([0-9][\\)-. ]?){2}([0-9][-. ]?){3}[0-9]{2}[0-9]?$/';

		if (strlen(trim($tel))==0) {
			return false;
		} else if (!preg_match($pattern, $tel)) {
			return false;
		}
		return true;
	}


	/**
	 *  @desc 檢查是否都是字串，沒有數字
	 *  @created 2014/06/01
	 */
	public static function bCheckOnlyString($str='') {
		//if (mb_strlen($str,'UTF-8')<2) return false;
		if (preg_match("/[0-9]/",$str)) { // 有數字
			return false;
		}
		return true;
	}


	/**
	 *  @desc 檢查輸入民國是否正確? 不可超過今年或小於0
	 *  @created 2014/06/01
	 */
	public static function bCheckTWYear($year=0) {
		// 檢查民國是否輸入數字
		$iNowYear = date('Y')-1911;
		if (!is_numeric($year)) {
			return false;
		} else if (preg_match("/[^0-9]{1,3}/",$year)) {
			return false;
		} else if ($year > $iNowYear) {
			return false;
		} else if ($year < 0) {
			return false;
		}
		return true;
	}


	/**
	 *  @desc 檢查網址是否為 youtube 的分享網址
	 *  @created 2014/06/01
	 */
	public static function bCheckYouTubeUrl($url='') {
		if (strlen(trim($url))==0) {
			return false;
		} else if (!preg_match("/^http:\/\/youtu.be\/.*/",$url)) {
			return false;
		}
		return true;
	}


	/**
	 *  @desc 檢查網址是否為 Facebook 個人網址
	 *  @created 2014/07/30
	 */
	public static function bCheckFBPersonUrl($url='') {
		if (strlen(trim($url))==0) {
			return false;
		} else if (preg_match("/^https:\/\/www.facebook.com\/.*/",$url) OR preg_match("/^https:\/\/m.facebook.com\/.*/",$url)) {
			return true;
		}
		return false;
	}

	/**
	 *  @desc 檢查檔案是否為圖檔
	 *  @usage array $_FILES['upload_file']: upload_file 為欄位名稱
	 *  @created 2015/10/05
	 */
	public static function bCheckUploadImageFile($aFile = array()){
		array(
			0=>"There is no error, the file uploaded with success",
			1=>"The uploaded file exceeds the upload_max_filesize directive in php.ini",
			2=>"The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
			3=>"The uploaded file was only partially uploaded",
			4=>"No file was uploaded",
			6=>"Missing a temporary folder"
		);
		if ($aFile['error'] > 0){
			//throw new Exception("An error ocurred when uploading.");
			// An error ocurred when uploading.
			return false;
		}

		// Allow certain file formats
		if(!preg_match("/jpg|jpeg|gif|png/i",$aFile['name'])){
			//throw new Exception("An error file formats when uploading.");
			return false;
		}

		// Check if image file eis as actual image or fake image
		$check = getimagesize($aFile['tmp_name']);
		if ($check ===  false){
			//throw new Exception("An error fake image when uploading.");
			return false;
		}

		//if ($check[0]>=4000 OR $check[1]>=4000){
		//	//throw new Exception("照片請勿超過4000x4000像素.");
		//	return false;
		//}

		// Check file size
		//if ($aFile['size'] > 500000){
		//	//echo "Sorry, your file is too large.";
		//	return false;
		//}
		return true;
	}

    /**
     *  @desc 檢查是否為日期格式
     *  <code>
     *  validator::bCheckDate('22,22,2222', 'mm.dd.yyyy'); // return false
     *  validator::bCheckDate('11/30/2008', 'mm/dd/yyyy'); // return true
     *  validator::bCheckDate('2008-01-30', 'yyyy-mm-dd'); // return true
     *  validator::bCheckDate('30 01 2008', 'dd mm yyyy'); // return true
     *  </code>
     *  @created 2016/04/11
     */
    public static function bCheckDate($date='',$format='yyyy/mm/dd'){
        if (strlen($date)>=6 AND strlen($format)==10){
            // find separator. Remove all other characters from $format
            $separator_only = str_replace(array('m','d','y'), '', $format);
            $separator = $separator_only[0]; // separator is first character
            if ($separator and strlen($separator_only) == 2){
                // make regex
                $regexp = str_replace('mm', '(0?[1-9]|1[0-2])', $format);
                $regexp = str_replace('dd', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
                $regexp = str_replace('yyyy', '(19|20)?[0-9][0-9]', $regexp);
                $regexp = str_replace($separator, "\\" . $separator, $regexp);

                if ($regexp != $date AND preg_match('/'.$regexp.'\z/', $date)){
                    // check date
                    $fmt = explode($separator, $format);
                    $arr = explode($separator, $date);
                    $year = $month = $day = 0;
                    foreach($fmt as $key2 => $val2){
                        if (strtolower($val2)=='yyyy')
                            $year = $arr[$key2];
                        else if (strtolower($val2)=='mm')
                            $month = $arr[$key2];
                        else if (strtolower($val2)=='dd')
                            $day = $arr[$key2];
                    }
                    if (@checkdate($month, $day, $year))
                        return true;
                }
            }
        }
        return false;
    }

    /**
     *  @desc 檢查名稱
     *  @created 2016/11/11
     */
    public static function bCheckName($name='') {
    	// echo strlen(trim($name));exit;
    	if (strlen(trim($name))==0 or strlen(trim($name))<6 or strlen(trim($name))>12) {
    		return false;
    	} else if (!preg_match("/^([\x7f-\xff]+)$/", $name)) {
    		return false;
    	}
    	return true;
    }

    /**
     *  @desc 檢查是否是合法的中國身份證字號
     *  @created 2016/11/25
     */
    function bCheckChinaUid($uid='') {
        //檢查是否是身份證號
        //轉化為大寫，如果出現x
        $uid = strtoupper($uid);
        //加權因子
        $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校驗碼串
        $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        //按順序循環處理前17位
        $sigma = 0;
        for($i = 0;$i < 17;$i++){
            //提取前17位的其中一位，並將變量類型轉為實數
            $b = (int) $uid{$i};      //提取相應的加權因子
            $w = $wi[$i];     //把從身份證號碼中提取的一位數字和加權因子相乘，並累加
            $sigma += $b * $w;
        }
        //計算序號
        $snumber = $sigma % 11;
        //按照序號從校驗碼串中提取相應的字符。
        $check_number = $ai[$snumber];
        if($uid{17} == $check_number){
            return true;
        }else{
            return false;
        }
    }

}
?>