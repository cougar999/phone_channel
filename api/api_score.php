<?php
session_start();

require_once("../config.php");
require_once(TP_LIB_DIR."admin.php");
require_once(TP_LIB_DIR."classes/biz/model/score.php");

$model_score = new Score();
$uid = $_SESSION['real_id'];
$tag = $_REQUEST['tag'];
$data['result'] = false;
switch ($tag){
	case "del":
		$arr = $model_score->getArticleListCount();
		print_r($arr);exit;
		$b_admin_group_result = checkAdminGroup($uid);
		$aid = intval($_REQUEST['aid']);
		if ($aid && $b_admin_group_result) {
			unset($arr_input);
			$arr_input['aid'] = $aid;
			$b_result = deleteArticle($arr_input);
			
			if($b_result){
				$data['result'] = true;
			}
		}
	break;
	
	case "addSharingNum":
		$aid = intval($_REQUEST['aid']);
		if ($aid > 0) {
			unset($arr_input);
			$arr_input['aid'] = $aid;
			$b_result = addSharingNum($arr_input);
			
			if($b_result){
				$data['result'] = true;
			}
		}
	break;
}
echo json_encode($data);


?>