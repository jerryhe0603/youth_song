<?php

//error_reporting(E_ALL );
error_reporting(E_ALL & ~(E_NOTICE|E_STRICT|E_DEPRECATED));
ini_set('display_errors', true);

/**
 * SMARTY 設定
 */
define('PATH_SMARTY_TPL',PATH_ROOT.'/tpl');

# iMarketing 銀河數位行銷
define('_LOCAL_HOST', 'localhost'); // localhost
define('_LOCAL_DB',   'youth_song');
define('_LOCAL_USER', 'robot');
define('_LOCAL_PASS', 'up6ck6UP^CK^');

//Default
define('_MYSQL_HOST', 'localhost'); // localhost
define('_MYSQL_DB',   'youth_song');
define('_MYSQL_USER', 'robot');
define('_MYSQL_PASS', 'up6ck6UP^CK^');

/**
 * queue db *Required*
 */

define('_USER_HOST',	'localhost');
define('_USER_DB',		'youth_song');
define('_USER_USER',	'robot');
define('_USER_PASS',	'up6ck6UP^CK^');

//php self path
defined('PHP_SELF_PATH')
|| define('PHP_SELF_PATH', dirname($_SERVER['PHP_SELF']));
//根目錄
defined('PATH_ROOT')
|| define('PATH_ROOT', realpath(dirname(__FILE__) . '/..'));
/**
 * 定義分頁的資料序號
 */
define("PAGE_INPUT_TYPE_NO", 142); 
defined('_LANG_NEXT_PAGE')||define('_LANG_NEXT_PAGE', '下一頁');
defined('_LANG_LAST_PAGE')||define('_LANG_LAST_PAGE', '最後一頁');
defined('_LANG_FIRST_PAGE')||define('_LANG_FIRST_PAGE', '第一頁');
defined('_LANG_PREV_PAGE')||define('_LANG_PREV_PAGE', '上一頁');


/**
 * session db config *Required*
 */
defined('_SESSION_HOST')||define('_SESSION_HOST',	'localhost');
defined('_SESSION_DB')||define('_SESSION_DB',	'youth_song');
defined('_SESSION_USER')||define('_SESSION_USER',	'robot');
defined('_SESSION_PASS')||define('_SESSION_PASS',	'up6ck6UP^CK^');

/**
 * date.timezone
 */
date_default_timezone_set("Asia/Taipei");
/**
 * php script exec unlimit in time
 */
set_time_limit( 0 );

/**
 * SMTP AUTH
 */
define('_ICB_SMTP_ACCOUNT', 	'icb@iwant-in.net');
define('_ICB_SMTP_PASSWORD', 	'xfh62yz7');

/**
 * ProgressBar Style
 */
define('PAGING_NUM', 			10);


/**
 * layout 語言
 */
define('_LANG', 'tw');

define('COMPANY_SERVER','localhost/youth_song');

# 是否為正式機,決定文案資料庫設定要取正式/測試設定
define('IS_RELEASE',false);

define ("_SYS_MAIL_FROM", "icd@iwant-in.net"); //發信mail address
define ("_SYS_MAIL_TO", "icd@iwant-in.net"); //郵件接收者define ("_SYS_SMTP_HOST", "authsmtp.vmail.seed.net.tw");//mail 主機名稱或ip
define ("_SYS_SMTP_HOST", "172.16.1.89");//mail 主機名稱或ip
#define ("_SYS_SMTP_HOST", "61.220.205.82");//mail 主機名稱或ip
#define ("_SYS_SMTP_HOST", "zimbra.iwant-in.net");//mail 主機名稱或ip
define ("_SYS_SMTP_PORT", "25");//mail 主機port
#define ("_SYS_SMTP_USER", "jerome.hung@iwant-in.net");//smtp 帳號
#define ("_SYS_SMTP_PASS", "jerome");//smtp 密碼
define ("_SYS_SMTP_USER", "bill.yeh@iwant-in.net");//smtp 帳號
define ("_SYS_SMTP_PASS", "123456");//smtp 密碼
define ("_SYS_SMTP_SENDER", "no-reply@iwant-in.net");
define ("_SYS_SMTP_ALTBODY", "To view the message, please use an HTML compatible email viewer!");
define ("_SYS_SMTPUSER", "ourbeat2017@gmail.com");
define ("_SYS_SMTPPASS", "24680abc");

//要導的網站位置TW AND CN
define ("_WEB_INDEX", "youth_index.php?");//導到正常的網頁
define ("_WEB_ADDRESS_TW", "http://www.youth-song.com/youth_index.php?");//TW的網站位置
define ("_WEB_ADDRESS_CN", "http://gb.youth-song.com/youth_index.php?");//CN的網站位置
define ("_WEB_INDEX_SCR_TW", "http://www.youth-song.com/");//發送確認信的mail裡使用者點擊的連結位置判斷國別 
define ("_WEB_INDEX_SCR_CN", "http://gb.youth-song.com/");//發送確認信的mail裡使用者點擊的連結位置判斷國別 

//mail
define ("_Web_Master_Email", "jerry.he@iwant-in.net");//寄件人信箱
define ("_Web_Master_Email_Name", "青春頌官方信箱");//寄件人名稱

?>