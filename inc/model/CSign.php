<?php

include_once(PATH_ROOT.'/inc/CMisc.php');
include_once(PATH_ROOT.'/inc/model/CGalaxyClass.php');
include_once(PATH_ROOT.'/inc/model/CUser.php');

class CSign extends CGalaxyClass {

	static protected $sDBName = 'MYSQL';
	static public $aInstancePool = array();

	static public $aSpecialty = array(
									'0' => '作詞',
									'1' => '作曲',
									'2' => '編曲',
									'3' => '演唱',
									'4' => '戲劇',
									'5' => '主持',
									'6' => '舞蹈',
									'7' => '模仿',
									'8' => '魔術',
									'9' => '走秀'
								);


	static public $aMusicTool = array(
									'1' => '鋼琴',
									'2' => '小提琴',
									'3' => '木吉他',
									'4' => '電吉他',
									'5' => '爵士鼓',
									'6' => '鍵盤',
									'7' => '豎琴',
									'8' => '薩克斯風',
									'9' => '雙簧管',
									'10' => '長笛',
									'11' => '二胡'
								);

	static public $aLevel = array(
									'0' => '精通',
									'1' => '普通',
									'2' => '略懂'
								);

	//現在的報名期別
	static public $iType = 1;



	public function __construct($multiData){
		parent::__construct($multiData);

	}

	static public function aAllSign($sSearchSql='',$sPostFix=''){

		$oDB = self::oDB(self::$sDBName);
		$aAllMember = array();

		$sSql = "SELECT * FROM sign";
		if($sSearchSql!=='') $sSql .= " WHERE $sSearchSql";
		if($sPostFix!=='') $sSql .= " $sPostFix";

		$iDbq = $oDB->iQuery($sSql);

		while($aRow = $oDB->aFetchAssoc($iDbq)){
			$aAllMember[] = $aRow;
		}

		return $aAllMember;
	}

	static public function iGetCount($sSearchSql=''){
		$oDB = self::oDB(self::$sDBName);
		$sSql = "SELECT count(member_no) as total FROM sign";
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

	//得到會員序號所對應的會員名稱
	static public function sGetMemberName($member_no = ''){

		$oDB = self::oDB(self::$sDBName);

		$sName = '' ;

		$sSql = "SELECT name FROM member WHERE member_no = '$member_no'";
		$iDbq = $oDB->iQuery($sSql);
		$aRow = $oDB->aFetchAssoc($iDbq);

		if(!$aRow) return $sName;

		return $aRow['name'];
	}

	//得到會員序號所對應的會員註冊地區
	static public function sGetMemberArea($member_no = ''){

		$oDB = self::oDB(self::$sDBName);

		$sName = '' ;

		$sSql = "SELECT area FROM member WHERE member_no = '$member_no'";
		$iDbq = $oDB->iQuery($sSql);
		$aRow = $oDB->aFetchAssoc($iDbq);

		if(!$aRow) return $sName;

		return $aRow['area'];
	}

	//前台使用為主
	//利用會員編號取得屬於該時間期數的報名表資料
	//iType 此次活動第幾期 報名表主檔
	static public function aGetData($member_no = ''){

		$oDB = self::oDB(self::$sDBName);

		$iType = self::$iType;
		$aRow = array();
		$sSql = "SELECT * FROM sign WHERE member_no = '$member_no' AND type = '$iType' AND flag=1";
		$iDbq = $oDB->iQuery($sSql);

		if ($oDB->iNumRows($iDbq)==0) return $aRow;

		$aRow = $oDB->aFetchAssoc($iDbq);

		return $aRow;
	}

	//取得作品資料
	static public function aGetWorksData($sign_no = ''){

		$oDB = self::oDB(self::$sDBName);

		$aRow = array();
		$sSql = "SELECT * FROM works WHERE sign_no = '$sign_no' ";
		$iDbq = $oDB->iQuery($sSql);

		if ($oDB->iNumRows($iDbq)==0) return $aRow;

		$aRow = $oDB->aFetchAssoc($iDbq);

		return $aRow;
	}

	//後台使用為主
	//序號取得報名表資料
	static public function aGetSignData($sign_no = ''){

		$oDB = self::oDB(self::$sDBName);

		$iType = self::$iType;
		$aRow = array();
		$sSql = "SELECT * FROM sign WHERE sign_no = '$sign_no' AND flag != 9";
		$iDbq = $oDB->iQuery($sSql);

		if ($oDB->iNumRows($iDbq)==0) return $aRow;

		$aRow = $oDB->aFetchAssoc($iDbq);

		return $aRow;
	}

	//@sign_no 報名表序號
	//@table 要抓的明細table 報名表序號
	//抓取報名表明細資料
	static public function aGetSignDetail($sign_no = 0 , $table = ''){

		$oDB = self::oDB(self::$sDBName);

		$aRow = array();

		if(!$sign_no) return $aRow;
		if(!$table) return $aRow;

		$order_by='';
		if($table=='works')$order_by=' ORDER BY type ';

		$sSql = "SELECT * FROM $table WHERE sign_no = '$sign_no' $order_by ";
		$iDbq = $oDB->iQuery($sSql);

		if ($oDB->iNumRows($iDbq)==0) return $aRow;

		while($aData = $oDB->aFetchAssoc($iDbq)){
			$aRow[] = $aData;
		}

		return $aRow;
	}

	//@works_no 作品主檔序號
	//@iType 檔案類型1歌詞2歌曲
	//抓取作品上傳檔案資料
	static public function aGetFileData($works_no = 0,$iType){

		$oDB = self::oDB(self::$sDBName);

		$aRow = array();

		if(!$works_no) return $aRow;

		$sSql = "SELECT * FROM upload_file WHERE works_no = '$works_no' AND up_type = '$iType'";
		$iDbq = $oDB->iQuery($sSql);

		if ($oDB->iNumRows($iDbq)==0) return $aRow;

		$aRow = $oDB->aFetchAssoc($iDbq);

		return $aRow;
	}

	//檢查作品投稿欄位有無輸入及上傳檔案大小限制及類型限制 有aSignData 表示是修改頁
	static public function sVaildData($postData=array(),$fileData=array(),$aSignData = array()){
		global $session,$CJavaScript; //obj var
		// echo "<pre>";print_r($postData);
		// echo "<pre>";print_r($fileData);exit;
		$oDB = self::oDB(self::$sDBName);

		$aErrorMsg = array();

		
		// if (!trim($postData['school'])) $aErrorMsg[] = _LANG_SIGN_VAILD_SCHOOL;
		if ($postData['management']=='') $aErrorMsg[] = "請選擇是否有經紀約";
		

		$sErrorMsg = "";

		//form submit vaild data
		if(count($aErrorMsg) > 0){

			$sErrorMsg = implode("\\n",$aErrorMsg);
			CJavaScript::vAlert($sErrorMsg);
			CJavaScript::vBack();

		}

	}

	//檢查作品投稿欄位有無輸入及上傳檔案大小限制及類型限制 有aSignData 表示是修改頁
	static public function sVaildDataStep2($postData=array(),$fileData=array(),$aSignData = array()){
		global $session,$CJavaScript; //obj var
		// echo "<pre>";print_r($postData);
		// echo "<pre>";print_r($fileData);exit;
		$oDB = self::oDB(self::$sDBName);

		$aErrorMsg = array();

		//修改頁不檢查是否至少有上傳一張圖片
		if (!($fileData['img_1_1']['name']) AND !($fileData['img_1_2']['name']) AND !($fileData['img_1_3']['name']) AND !$aSignData){
			$aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE;
		}else{

			//有上傳圖片才判斷大小跟檔案類型
			if($fileData['img_1_1']['name']){
				if($fileData['img_1_1']['size'] > 3145728) $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_SIZE;

				$file_type = strtolower(substr($fileData['img_1_1']['name'],-4));
				if($file_type!= '.jpg' AND $file_type!= '.png' AND $file_type!= '.gif') $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_TYPE;
			}

			if($fileData['img_1_2']['name']){
				if($fileData['img_1_2']['size'] > 3145728) $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_SIZE;

				$file_type = strtolower(substr($fileData['img_1_2']['name'],-4));
				if($file_type!= '.jpg' AND $file_type!= '.png' AND $file_type!= '.gif') $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_TYPE;
			}

			if($fileData['img_1_3']['name']){
				if($fileData['img_1_3']['size'] > 3145728) $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_SIZE;

				$file_type = strtolower(substr($fileData['img_1_3']['name'],-4));
				if($file_type!= '.jpg' AND $file_type!= '.png' AND $file_type!= '.gif') $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_TYPE;
			}

		}

		if (!trim($postData['song_name_1'])) $aErrorMsg[] = _LANG_SIGN_VAILD_SONG_NAME_1;
		if (!trim($postData['lyricist_1'])) $aErrorMsg[] = _LANG_SIGN_VAILD_LYRICIST_1;
		if (!trim($postData['composer_1'])) $aErrorMsg[] = _LANG_SIGN_VAILD_COMPOSER_1;

		//有上傳歌詞檔案才判斷3MB及類型
		//修改頁不檢查有無上傳檔案
		if (!$fileData['lyric_file_1']['name'] AND !$aSignData){
			$aErrorMsg[] = _LANG_SIGN_VAILD_LYRICIST_FILE_1;
		}else{
			//修改頁如果沒上傳檔案不檢查類型
			if($fileData['lyric_file_1']['name']){
				if($fileData['lyric_file_1']['size'] > 3145728) $aErrorMsg[] = _LANG_SIGN_VAILD_LYRICIST_FILE_SIZE_1;

				$file_type = strtolower(substr($fileData['lyric_file_1']['name'],-4));
				if($file_type!= '.txt' AND $file_type!= '.doc' AND $file_type!= 'docx') $aErrorMsg[] = _LANG_SIGN_VAILD_LYRICIST_FILE_TYPE_1;
			}
		}
		//有上傳音樂檔案才判斷10MB及類型
		//修改頁不檢查有無上傳檔案
		if (!$fileData['song_file_1']['name'] AND !$aSignData){
			$aErrorMsg[] = _LANG_SIGN_VAILD_SONG_FILE_1;
		}else{
			//修改頁如果沒上傳檔案不檢查類型
			if($fileData['song_file_1']['name']){
				if($fileData['song_file_1']['size'] > 10485760) $aErrorMsg[] = _LANG_SIGN_VAILD_SONG_FILE_SIZE_1;

				$file_type = strtolower(substr($fileData['song_file_1']['name'],-4));
				if($file_type!= '.mp3' AND $file_type!= '.mp4' AND $file_type!= '.wav') $aErrorMsg[] = _LANG_SIGN_VAILD_SONG_FILE_TYPE_1;
			}
		}

		if (!trim($postData['creative_concept_1'])){
			$aErrorMsg[] = _LANG_SIGN_VAILD_CREATIVE_CONCEPT_1;
		}else{
			if(mb_strlen(trim($postData['creative_concept_1']),'utf-8') < 100 || mb_strlen(trim($postData['creative_concept_1']),'utf-8') > 300 ){
				$aErrorMsg[] = _LANG_SIGN_VAILD_CREATIVE_CONCEPT_LENGTH_1;
			}
		}

		//當作品二歌曲名稱有輸入，才去判斷所有作品二的作品欄位有無輸入及大小跟類型限制
		if(trim($postData['song_name_2'])){

			//修改頁不檢查是否至少有上傳一張圖片
			if (!($fileData['img_2_1']['name']) AND !($fileData['img_2_2']['name']) AND !($fileData['img_2_3']['name']) AND !$aSignData){
				$aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE;
			}else{

				//有上傳圖片才判斷大小跟檔案類型
				if($fileData['img_2_1']['name']){
					if($fileData['img_2_1']['size'] > 3145728) $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_SIZE;

					$file_type = strtolower(substr($fileData['img_2_1']['name'],-4));
					if($file_type!= '.jpg' AND $file_type!= '.png' AND $file_type!= '.gif') $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_TYPE;
				}

				if($fileData['img_2_2']['name']){
					if($fileData['img_2_2']['size'] > 3145728) $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_SIZE;

					$file_type = strtolower(substr($fileData['img_2_2']['name'],-4));
					if($file_type!= '.jpg' AND $file_type!= '.png' AND $file_type!= '.gif') $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_TYPE;
				}

				if($fileData['img_2_3']['name']){
					if($fileData['img_2_3']['size'] > 3145728) $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_SIZE;

					$file_type = strtolower(substr($fileData['img_2_3']['name'],-4));
					if($file_type!= '.jpg' AND $file_type!= '.png' AND $file_type!= '.gif') $aErrorMsg[] = _LANG_SIGN_VAILD_IMG_FILE_TYPE;
				}

			}

			if (!trim($postData['lyricist_2'])) $aErrorMsg[] = _LANG_SIGN_VAILD_LYRICIST_2;
			if (!trim($postData['composer_2'])) $aErrorMsg[] = _LANG_SIGN_VAILD_COMPOSER_2;

			//修改頁須先檢查資料庫有無已經上傳的檔案如果有表示2報名者先前已經報名過作品2 此時作品二檔案沒上傳也沒關係
			if($aSignData){

				$is_upload = 0;
				$iSignNo = $aSignData['sign_no'];

				$iDbq = $oDB->iQuery("SELECT * FROM works WHERE sign_no = '$iSignNo' ");

				//資料庫內有兩筆作品資料表示報名者之前有上傳作品二
				if ($oDB->iNumRows($iDbq)==2) $is_upload =1;
			}

			//有上傳歌詞檔案才判斷3MB及類型
			if (!$fileData['lyric_file_2']['name'] AND !$is_upload){
				$aErrorMsg[] = _LANG_SIGN_VAILD_LYRICIST_FILE_2;
			}else{

				//修改頁如果沒上傳檔案不檢查類型
				if($fileData['lyric_file_2']['name']){
					if($fileData['lyric_file_2']['size'] > 3145728) $aErrorMsg[] = _LANG_SIGN_VAILD_LYRICIST_FILE_SIZE_2;

					$file_type = strtolower(substr($fileData['lyric_file_2']['name'],-4));
					if($file_type!= '.txt' AND $file_type!= '.doc' AND $file_type!= 'docx') $aErrorMsg[] = _LANG_SIGN_VAILD_LYRICIST_FILE_TYPE_2;
				}

			}
			//有上傳音樂檔案才判斷10MB及類型
			if (!($fileData['song_file_2']['name']) AND !$is_upload){
				$aErrorMsg[] = _LANG_SIGN_VAILD_SONG_FILE_2;
			}else{

				//修改頁如果沒上傳檔案不檢查類型
				if($fileData['song_file_2']['name']){
					if($fileData['song_file_2']['size'] > 10485760) $aErrorMsg[] = _LANG_SIGN_VAILD_SONG_FILE_SIZE_2;

					$file_type = strtolower(substr($fileData['song_file_2']['name'],-4));
					if($file_type!= '.mp3' AND $file_type!= '.mp4' AND $file_type!= '.wav') $aErrorMsg[] = _LANG_SIGN_VAILD_SONG_FILE_TYPE_2;
				}

			}

			if (!trim($postData['creative_concept_2'])){
				$aErrorMsg[] = _LANG_SIGN_VAILD_CREATIVE_CONCEPT_2;
			}else{
				if(mb_strlen(trim($postData['creative_concept_2']),'utf-8') < 100 || mb_strlen(trim($postData['creative_concept_2']),'utf-8') > 300 ){
					$aErrorMsg[] = _LANG_SIGN_VAILD_CREATIVE_CONCEPT_LENGTH_2;
				}
			}
		}

		$sErrorMsg = "";

		//form submit vaild data
		if(count($aErrorMsg) > 0){

			$sErrorMsg = implode("\\n",$aErrorMsg);
			CJavaScript::vAlert($sErrorMsg);
			CJavaScript::vBack();

		}

	}

	//利用報名表序號及作品類型去得作品序號
	static public function iGetWorksNo($iSignNo = 0,$iType = 0){

		$oDB = self::oDB(self::$sDBName);

		$iNum = 0;

		if(!$iSignNo) return $iNum;

		$sSql = "SELECT * FROM works WHERE sign_no = '$iSignNo' AND type = '$iType'";
		$iDbq = $oDB->iQuery($sSql);

		if ($oDB->iNumRows($iDbq)==0) return $iNum;

		$aRow = $oDB->aFetchAssoc($iDbq);

		$iNum = $aRow['works_no'];

		return $iNum;
	}

}

?>