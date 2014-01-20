<?php
session_start();

require_once("../config.php");
require_once(TP_LIB_DIR."admin.php");
require_once(TP_LIB_DIR."statistic.php");

$uid = $_SESSION['real_id'];
$tag = $_POST['tag'];
$data['result'] = false;

switch ($tag){
	case "list":
		$list = getAppBlackList();
		echo json_encode($list);
	break;
	
	case "del":
		$sid = $_POST['sid'];
		if ($sid) {
			unset($arr_input);
			$arr_input['sid'] = $sid;
			$s_result = deleteAppBlackList($arr_input);
			if($s_result){
				$data['result'] = true;
			}
		}
	break;
	
	case "add":
		$arr_input['sid'] = $_POST['sid'];
		$arr_input['appid'] = $_POST['appid'];
		$arr_input['appname'] = $_POST['appname'];
		
		$s_result = AddAppBlackList($arr_input);
		
		if($s_result){
			$data['result'] = true;
		}
	break;
}

?>