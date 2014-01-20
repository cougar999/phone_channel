<?php
require_once(TP_LIB_DIR."classes/biz/communication/Communication.php");

function smarty_function_communication($params,&$smarty){
	$ret = $params['ret'];
	$tag = $params['tag'];
	
	$o_communication = new Communication();
	
	switch ($tag){
		case "add":
			
			$uid = intval($_SESSION['userid']);
			$agent_id = intval($_SESSION['agentid']);
			$real_name = $_SESSION['username'];
			
			if(trim($_POST['content']) && !empty($uid) && !empty($real_name)){
				unset($arr_input);
				$arr_input['uid'] = $uid;
				$arr_input['content'] = addslashes(htmlspecialchars($_POST['content']));
				$arr_input['real_name'] = $real_name;
				$arr_input['agent_id'] = $agent_id;
				
				$result = $o_communication->addNote($arr_input);
				
				if (!empty($ret)) {
					return $smarty->assign($ret , $result);
				}else {
					return $result;
				}
			}else {
				return false;
			}
		break;
		
		case "list":
			$count = intval($params['count']);
			$page = intval($params['pageno']);
			$arr_input['agent_id'] = $_SESSION['agentid'];
			$total_cnt = $o_communication->getCommunicationListCount($arr_input);
			$view_cnt = $count;
			$block_cnt = 10;
			$extra_url = "category=".$category;
			
			$obj_Pager = new Pager($page, $total_cnt, $tp_tpl_page, $view_cnt, $block_cnt, $extra_url);
			if($total_cnt > 0){
				$a_communication_list = $o_communication->getCommunicationList($arr_input,$obj_Pager->getLimitQuery());
			}else {
				$a_communication_list = array();
			}
			
			if (!empty($ret)) {
				return $smarty->assign($ret,$a_communication_list);
			}else {
				return $a_communication_list;
			}
		break;
	}
	return null;
}
