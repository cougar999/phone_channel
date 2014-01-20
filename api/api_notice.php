<?php
session_start();

require_once("../config.php");
require_once(TP_LIB_DIR."admin.php");
require_once(TP_LIB_DIR."note.php");

$uid = $_SESSION['real_id'];
$tag = $_POST['tag'];
$data['result'] = false;
switch ($tag){
	case "del":
		$b_admin_group_result = checkAdminGroup($uid);
		$cnid = intval($_POST['cnid']);
		if ($cnid && $b_admin_group_result) {
			unset($arr_input);
			$arr_input['cnid'] = $cnid;
			$b_result = deleteNotice($arr_input);
			
			if($b_result){
				$data['result'] = true;
			}
		}
	break;
	case 'list':
		$act = trim($_REQUEST['act']);
		$params['pageno'] = $_REQUEST['pageno'];
		$params['count']  = intval($_REQUEST['count']);
		$params['act']    = $act;
		$data= getNoticeList($params);
		break;
}
echo json_encode($data);


?>