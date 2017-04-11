<?php

include_once('./inc/config.php');
include_once("./lang/"._LANG.".php");
include_once('./inc/class.session.php');

/*定义header，声明图片文件，最好是png，无版权之扰; */
/*生成新的四位整数验证码 */

header('Content-type:image/png');
header('Content-Disposition:filename=image_code.png');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

$sessid      = isset($_GET['PHPSESSID'])?$_GET['PHPSESSID']:'';
$session     = new session($sessid);

/* 字形檔的路徑 */
//$TTF = $_SERVER["DOCUMENT_ROOT"]."/lib/font/w7.otf";
$TTF =  "./font/w7.otf";
//$TTF =  dirname(__FILE__)."/lib/font/DejaVuSerif-Bold.ttf";

/* 開啟 SESSION */
//if (!isset($_SESSION)) { session_start(); }

/* 設定亂數種子 */
mt_srand((double)microtime()*1000000);

$_GET['t']='post';
/* 類型選擇 */
switch ($_GET['t']) {
case 'post':
    //定義用來顯是在圖片上的文字
    $codeBase='ABCDEFGHJKLMNOPRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz';

    $codeLength=5; //定義循環隨機抽取的位數
    $codeName='checkPostCode'; // 認證碼名稱

    $textSize=18; // 字體大小(點)
    $proportion=0.5; //字寬加乘比例
    break;

case 'min_post':
    //定義用來顯是在圖片上的文字
    $codeBase='ABCDEFGHJKLMNPRSTUVWXYZ23456789';

    $codeLength=5; // 定義循環隨機抽取的位數
    $codeName='checkMinPostCode'; // 認證碼名稱
    $textSize=18; // 字體大小(點)
    $proportion=0.2; // 字寬加乘比例
    break;

case 'shoutbox':
    //定義用來顯是在圖片上的文字
    $codeBase='1234567890';

    $codeLength=3; // 定義循環隨機抽取的位數
    $codeName='checkShoutCode'; // 認證碼名稱
    $imageX=60; $imageY=24; // 圖片的長寬
    $textSize=18; // 字體大小(點)
    $proportion=0.2; // 字寬加乘比例
    break;

case 'comm':
default:
    //定義用來顯是在圖片上的文字
    $codeBase='1234567890';

    $codeLength=3; // 定義循環隨機抽取的位數
    $codeName='checkMsgCode'; // 認證碼名稱
    $textSize=18; // 字體大小(點)
    $proportion=0.2; // 字寬加乘比例
    break;
}

// 圖片的高度
$imageY=intval($textSize*(1+$proportion));
// 圖片的寬度
$imageX=$imageY*$codeLength;


/*建立圖片物件 */
$im = @imagecreatetruecolor($imageX, $imageY) or die('Image Error');

/* 設定底色 */
imagefill($im,0,0,
    imagecolorallocate($im, 255,255,255)
);


/* 建立筆刷 */
$s=intval($textSize/8);
$brush = imagecreate($s,$s);
imagesetbrush($im,$brush);

/*底色干擾線條 */
$l=$codeLength*7;
for($i=0;$i<$l;$i++){
    imagefill($brush,0,0,imagecolorallocate(
        $brush, rand(127,255),rand(127,255),rand(127,255)
    ));
    imageline(
        $im,
        rand(0,$imageX),
        rand(0,$imageY),
        rand($imageY,$imageX),
        rand(0,$imageY),
        IMG_COLOR_BRUSHED
    );
}


/*得到字串的長度;减1是因为截取字符是从0开始起算;*/
$l = strlen($codeBase)-1;
$widgetSize=$imageX/$codeLength;
$codeStr = '';

/*循環隨機抽取前面定義的文字*/
for($i=0;$i<$codeLength;$i++){
    /*每次随机抽取一位数字;从第一个字到该字串最大长度, */
    $num=rand(0,$l);
    $codeStr.= $codeBase[$num];

    /*利用true type字型來產生圖片 */
    $Color = imagecolorallocate( //  文字顏色
        $im,rand(0,60),rand(0,60),rand(0,60)
    );
    imagettftext(
        $im,
        $textSize,
        rand(-25,25),
        $widgetSize*($i+$proportion),
        $textSize,
        $Color,
        $TTF,
        $codeBase[$num]
    );

    /*
    imagettftext (int im, int size, int angle,
    int x, int y, int col,
    string fontfile, string text)

    im 圖片物件
    size 文字大小
    angle 0度將會由左到右讀取文字，而更高的值表示逆時鐘旋轉
    x y 文字起始座標
    col 顏色物件
    fontfile 字形路徑，為主機實體目錄的絕對路徑，可自行設定想要的字型
    text 寫入的文字字串
    */
}


/* 建立筆刷 */
$s=intval($textSize/9);
$brush = imagecreate($s,$s);
imagesetbrush($im,$brush);

/* 文字干擾像素 */
/*$l=$codeLength*13;
for($i=0;$i<$l;$i++){
    imagefill($brush,0,0,imagecolorallocate(
        $brush,rand(40,100),rand(40,100),rand(40,100)
    ));
    imagesetpixel(
        $im,
        rand(0,$imageX),
        rand(0,$imageY),
        IMG_COLOR_BRUSHED
    );
}
*/

/*用session来做验证也不错;注册session,名称为checkMsgCode, */
/*其它页面只要包含了该图片 */
/*即可以通过$_SESSION['checkMsgCode']来调用 */
$session->set("gAuthCode",$codeStr);

imagepng($im);
imagedestroy($im);

/*
include_once("lib/session.php");
include_once("lib/CMisc.php");


// Antispam example using a random string
include_once("lib/jpgraph/src/jpgraph.php");

include_once("lib/jpgraph/src/jpgraph_antispam.php");


$session =  new session;
$CMisc = new CMisc;

// Create new anti-spam challenge creator
// Note: Neither '0' (digit) or 'O' (letter) can be used to avoid confusion
$code = "abcdefghjkmnpqrstuvwxyz23456789";
$s=$CMisc->sRandomString("",5);

$spam = new AntiSpam($s);



// Create a random 5 char challenge and return the string generated
//$chars = $spam->Rand(5);

// Stroke random cahllenge
if( $spam->Stroke() === false ) {
    die('Illegal or no data to plot');
}

$session->vSetVar("gAuthCode",$s);
*/
?>