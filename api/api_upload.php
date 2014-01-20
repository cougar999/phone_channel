<?php
error_reporting(0);
!defined('_INIT_FROM_INDEX_') && include_once '../config.php';

$p 		   = new ApkParser();
$_flag_ext = true;

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	try {
		$res 	  = $p->open($tempFile);
	}catch(Exception $e){
		$_flag_ext = false;
	}
	if ($_flag_ext){
		
	}
}