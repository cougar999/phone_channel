<?php
session_start();
error_reporting(0);
!defined('_INIT_FROM_INDEX_') && include_once '../config.php';


$_REQUEST['type'] = trim($_REQUEST['type']);
$_REQUEST['page'] = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <  1) ? 1 : intval($_REQUEST['page']);
$_REQUEST['pagesize'] = (!$_REQUEST['pagesize']?10:$_REQUEST['pagesize']);

$statLoginModel = new statLoginModel();

function format_tmp_data($data){
	$_data_ = array();
	foreach((array)$data as $item){
		if(!$item['level']) continue;
		$_data_[$item['id']] = array(
			'name'=>$item['text']
		);
	}
	return $_data_;
}

switch ($_REQUEST['type']){
	case 'flash_decision_shop':
		$data = array();
		$pra  = $_REQUEST;
		$data = $statLoginModel->getStatDecisionShopDataInfo($pra);
		break;
	case 'get_member_info':
		$data        = $_data = array();
		$pra 		 = $_REQUEST;
		$memberModel = new ChAgent();
		$id			 = intval($pra['id']);
		if(!$id && !$_SESSION['isSup']) $id = $_SESSION['real_id'];
		$_data 	     = $memberModel->getInfo(array('nid'=>$id));
		$data        = format_tmp_data($_data);
		break;
	case 'decision_shop':
		$pra = $_REQUEST;
		$data= $statLoginModel->getStatDecisionShopData($pra);
		break;
	case 'get_gold_detail':
		$pra = $_REQUEST;
		echo json_encode($statLoginModel->getStatGoldDetailData($pra));exit;
		break;
	case 'get_gold_info':
		$pra = $_REQUEST;
		echo json_encode($statLoginModel->getStatGoldAllData($pra));exit;
		break;
	case 'sketch_ad':
		$pra = $_REQUEST;
		echo json_encode($statLoginModel->getStatLoginAllData($pra));exit;
		break;
	case 'sketch':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = empty($pra['top_agent_id'])?$_SESSION['agentid']:$pra['top_agent_id'];//$_SESSION['real_id']
		echo json_encode($statLoginModel->getStatLoginInfo($pra));exit;
		break;
	case 'detail':
		$pra = $_REQUEST;
		echo json_encode($statLoginModel->getStatShopLoginInfo($pra));exit;
		break;
	case 'detail_dt':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = empty($pra['top_agent_id'])?$_SESSION['agentid']:$pra['top_agent_id'];
		echo json_encode($statLoginModel->getStatDetailLoginInfo($pra));exit;
		break;
	case 'detail_dt_ad':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = empty($pra['top_agent_id'])?$_SESSION['agentid']:$pra['top_agent_id'];
		echo json_encode($statLoginModel->getStatDetailLoginAllData($pra));exit;
		break;
	case 'detail_dtinfo':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = $_SESSION['agentid'];
		echo json_encode($statLoginModel->getStatDetailShopLoginInfo($pra));exit;
		break;
	case 'use_sketch_ad':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = $_SESSION['agentid'];
		echo json_encode($statLoginModel->getStatUseSketchAllData($pra));exit;
		break;
	case 'use_sketch':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = empty($pra['top_agent_id'])?$_SESSION['agentid']:$pra['top_agent_id'];
		echo json_encode($statLoginModel->getStatUseSketchInfo($pra));exit;
		break;
	case 'use_sketch_shop':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = empty($pra['top_agent_id'])?$_SESSION['agentid']:$pra['top_agent_id'];
		echo json_encode($statLoginModel->getStatUseSketShopInfo($pra));exit;
		break;
	case 'use_sketch_channel':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = empty($pra['top_agent_id'])?$_SESSION['agentid']:$pra['top_agent_id'];
		echo json_encode($statLoginModel->getStatUseSketChnInfo($pra));exit;
		break;
	case 'use_sketch_channel_ad':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = empty($pra['top_agent_id'])?$_SESSION['agentid']:$pra['top_agent_id'];
		echo json_encode($statLoginModel->getStatUseSketChnAllData($pra));exit;
		break;
	case 'phone_type_sketch':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = $_SESSION['agentid'];
		$pra['isSup'] = $_SESSION['isSup'];
		echo json_encode($statLoginModel->getStatPhoneTypeSketchInfo($pra));exit;
		break;
	case 'phone_imei_sketch':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = $_SESSION['agentid'];
		$pra['isSup'] = $_SESSION['isSup'];
		echo json_encode($statLoginModel->getStatPhoneImeiSketchInfo($pra));exit;
		break;
	case 'use_soft_detail':
		$pra = $_REQUEST;
		$pra['top_agent_id'] = $_SESSION['agentid'];
		$pra['isSup'] = $_SESSION['isSup'];
		echo json_encode($statLoginModel->getStatUseSoftInfo($pra));exit;
		break;
	default:break;
}
print_r((isset($_GET['callback'])?$_GET['callback']."(":"").json_encode($data).(isset($_GET['callback'])?")":""));