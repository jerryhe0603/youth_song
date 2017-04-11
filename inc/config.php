<?php

defined('PATH_ROOT')|| define('PATH_ROOT', realpath(dirname(__FILE__) . '/..'));
ini_set('memory_limit','128M');

$sFileName = dirname(dirname(__FILE__))."/env.php";
if (file_exists($sFileName)){
	include_once($sFileName);
} else {
	die("File Not Exist env.php. See config.php");
	//vCreateEnvPhp($sFileName);
	//include_once($sFileName);
}

include_once(dirname(__FILE__)."/config/config_".ENVIRONMENT.".php");


function vCreateEnvPhp($sFileName=''){
	if (!$sFileName) return FALSE;
	$fp = fopen($sFileName, 'w');
	fwrite($fp, '<?php define("ENVIRONMENT", "development"); ?>');
	fclose($fp);
}

?>