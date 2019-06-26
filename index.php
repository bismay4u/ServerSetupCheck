<?php
/*
 * This file contains the source for verifing the server enviroment
 *
 * Author: Bismay Kumar Mohapatra bismay4u@gmail.com
 * Version: 1.0
 */

if (!defined('PHP_VERSION_ID')) {
		$version = explode('.', PHP_VERSION);

		define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

if(PHP_VERSION_ID < 50600) {
	sysCheckPrint("PHP version 5.6 or above is required to run Logiks 4.0.","Please upgrade to continue.");
}

if(isset($_REQUEST['mode'])) {
        switch(strtolower($_REQUEST['mode'])) {
                case "phpinfo":
                        phpinfo();
                        exit();
                        break;
        }
}

//Check some important extensions.
$checkExtensions=array(
		"cURL PHP Extension is required"=>"func:curl_init",
		"MCrypt PHP Extension is required"=>"library:mcrypt",
		"Mbstring PHP Extension is required"=>"library:mbstring",
		"OpenSSL PHP Extension is required"=>"library:openssl",
		"ZipArchive PHP Library is required"=>"class:ZipArchive",
		"GD PHP Library is required"=>"library:gd",
		"Memcached Library is required"=>"class:Memcached",
		"MySQLi Library is required"=>"func:mysqli_connect",
	);
$errorMsg=[];
$noProceed=false;

foreach ($checkExtensions as $msg=>$extension) {
	$extension=explode(":",$extension);
	switch($extension[0]) {
		case "library":
			if(!extension_loaded($extension[1])) {
				$errorMsg[]=$msg;
				$noProceed=true;
			}
			break;
		case "class":
			if(!class_exists($extension[1])) {
				$errorMsg[]=$msg;
				$noProceed=true;
			}
			break;
		case "func":
			if(!function_exists($extension[1])) {
				$errorMsg[]=$msg;
				$noProceed=true;
			}
			break;
	}
}
if($noProceed) {
	sysCheckPrint("Important extension missing in PHP, please install them before continuing.",$errorMsg);
} else {
	sysCheckPrint("All done","Server is in great shape");
}
if(php_sapi_name() == 'cli' ) {
	echo "\nInstallation is all done. Visit the site on browser to continue.\n\n";
	exit();
}

function sysCheckPrint($msg1,$msg2) {
	$version = "".floor(PHP_VERSION_ID / 10000).".".floor((PHP_VERSION_ID % 10000)/100).".".(PHP_VERSION_ID % 100);
	$path = __DIR__;
	$isCLI = (php_sapi_name() == 'cli' );
	if($isCLI) {
		echo "\n";
		echo $msg1."\n";
		if(is_array($msg2)) {
			foreach($msg2 as $m) {
				echo "\t+ ".$m."\n";
			}
		} else {
			echo "\t".$msg2."\n";
		}
		echo "\n";
	} else {
		echo "<h1 align=center style='color:#BF2E11'>{$msg1}</h1>";
		if(is_array($msg2)) {
			echo "<hr>";
			foreach($msg2 as $m) {
				echo "<p align=center style='color:#444;'>{$m}</p>";
			}
		} else {
			echo "<h3 align=center style='color:#444;'>{$msg2}</h3>";
		}
	}
	echo "\n<hr><div align=center>Found PHP : {$version} @ {$path}</div>";
	echo "\n<div align=center><a href='?mode=phpinfo'>PHPInfo</a></div>";
	exit();
}

?>
