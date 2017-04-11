<?php

error_reporting(E_ALL & ~(E_NOTICE|E_STRICT|E_DEPRECATED));
ini_set('display_errors', true);

/**
 * SMARTY ³]©w
 */
define('PATH_SMARTY_TPL', PATH_ROOT.'/tpl');


# iMarketing 銀河數位行銷
define('_LOCAL_HOST', 'localhost'); // localhost
define('_LOCAL_DB',   'youth_song');
define('_LOCAL_USER', 'robot');
define('_LOCAL_PASS', 'robot');

//Default
define('_MYSQL_HOST', 'localhost'); // localhost
define('_MYSQL_DB',   'youth_song');
define('_MYSQL_USER', 'robot');
define('_MYSQL_PASS', 'robot');



/**
 * queue db *Required*
 */

define('_USER_HOST',	'localhost');
define('_USER_DB',		'youth_song');
define('_USER_USER',	'robot');
define('_USER_PASS',	'robot');

/**
 * TW 的db位置
 */
define('_TW_HOST',	'localhost');
define('_TW_DB',	'youth_song');
define('_TW_USER',	'robot');
define('_TW_PASS',	'robot');
/**
 * CN的db位置
 */
define('_CN_HOST',	'localhost');
define('_CN_DB',	'youth_song');
define('_CN_USER',	'robot');
define('_CN_PASS',	'robot');


//php self path
defined('PHP_SELF_PATH')
|| define('PHP_SELF_PATH', dirname($_SERVER['PHP_SELF']));
//®Ú¥Ø¿ý
defined('PATH_ROOT')
|| define('PATH_ROOT', realpath(dirname(__FILE__) . '/..'));
/**
* ©w¸q¤À­¶ªº¸ê®Æ§Ç¸¹
*/
define("PAGE_INPUT_TYPE_NO", 142); 
defined('_LANG_NEXT_PAGE')||define('_LANG_NEXT_PAGE', '¤U¤@­¶');
defined('_LANG_LAST_PAGE')||define('_LANG_LAST_PAGE', '³Ì«á¤@­¶');


/**
* session db config *Required*
*/
defined('_SESSION_HOST')||define('_SESSION_HOST',	'localhost');
defined('_SESSION_DB')||define('_SESSION_DB',	'youth_song');
defined('_SESSION_USER')||define('_SESSION_USER',	'robot');
defined('_SESSION_PASS')||define('_SESSION_PASS',	'robot');

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
* layout »y¨¥
*/
define('_LANG', 'tw');

// ¤½¥q¤l¨t²Îºô§}(«È¤áÃö«Y¨t²Î)
define('COMPANY_SERVER',	'csscompany.lab.net');

# ¬O§_¬°¥¿¦¡¾÷,¨M©w¤å®×¸ê®Æ®w³]©w­n¨ú¥¿¦¡/´ú¸Õ³]©w
define('IS_RELEASE',false);

//要導的網站位置TW AND CN
define ("_WEB_INDEX", "youth_index.php?");//導到正常的網頁
define ("_WEB_ADDRESS_TW", "http://youth-song.lab.net/youth_index.php?");//TW的網站位置
define ("_WEB_ADDRESS_CN", "http://youth-song.lab.net/youth_index.php?");//CN的網站位置
define ("_WEB_INDEX_SCR_TW", "http://youth-song.lab.net/");//發送確認信的mail裡使用者點擊的連結位置判斷國別 
define ("_WEB_INDEX_SCR_CN", "http://youth-song.lab.net/");//發送確認信的mail裡使用者點擊的連結位置判斷國別 

//mail
define ("_Web_Master_Email", "jerry.he@iwant-in.net");//寄件人信箱
define ("_Web_Master_Email_Name", "青春頌官方信箱");//寄件人名稱

?>