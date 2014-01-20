<?php
session_start();
error_reporting(0);
require_once('../config.php');


$type = trim($_REQUEST['type']);
$act  = trim($_REQUEST['act']);
$page = $pra['page'] = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <  1) ? 1 : intval($_REQUEST['page']);
$pagesize = $pra['pagesize'] = 20;

$IntergralModel = new IntergralModel();

switch ($type){
	case 'monthorder':
		$pra['act']   = $act;
		$pra['type']  = 'month';
		$pra['month'] = date('Y-m');
		$pra['top_agent_id'] = $_SESSION['agentid'];
		$data = $IntergralModel->getIntergralInfo($pra);
		break;
	case 'allorder':
		$pra['act']   = $act;
		$pra['type']  = 'all';
		$pra['top_agent_id'] = $_SESSION['agentid'];
		$data = $IntergralModel->getIntergralInfo($pra);
		break;
	case 'a1':
		$pra['act']   = $act;
		$pra['type']  = 'a1';
		$pra['top_agent_id'] = $_SESSION['agentid'];
		$data = $IntergralModel->getIntergralInfo($pra);
		break;
	case 'a2':
		$pra['act']   = $act;
		$pra['type']  = $type;
		$pra['top_agent_id'] = $_SESSION['agentid'];
		$data = $IntergralModel->getIntergralInfo($pra);
		break;
	case 'a3':
		$pra['act']   = $act;
		$pra['type']  = $type;
		$pra['top_agent_id'] = $_SESSION['agentid'];
		$data = $IntergralModel->getIntergralInfo($pra);
		break;
	case 'a4':
		$pra['act']   = $act;
		$pra['type']  = $type;
		$pra['top_agent_id'] = $_SESSION['agentid'];
		$data = $IntergralModel->getIntergralInfo($pra);
		break;
	case 'a5':
		$pra['act']   = $act;
		$pra['type']  = $type;
		$pra['top_agent_id'] = $_SESSION['agentid'];
		$data = $IntergralModel->getIntergralInfo($pra);
		break;
	case 'a6':
		$pra['act']   = $act;
		$pra['type']  = $type;
		$pra['top_agent_id'] = $_SESSION['agentid'];
		$data = $IntergralModel->getIntergralInfo($pra);
		break;
	default:break;
}
echo json_encode(array('code'=>$code,'data'=>$data));