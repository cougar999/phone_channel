<?php
function writeAdminLog($filename , $string){
	$file_dir = TP_APP_DIR."/log/admin/";
	if(!is_dir($file_dir)){
		mkdirs($file_dir);
	}
	file_put_contents($file_dir.$filename,$string."\n",FILE_APPEND);
	
	return true;
}

function writeLog($file , $string){
	if(!is_dir($file)){
		mkdirs($file);
	}
	file_put_contents($file,$string."\n",FILE_APPEND);
	
	return true;
}
?>