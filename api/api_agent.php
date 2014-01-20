<?php
session_start();
require_once("../config.php");
require_once(TP_LIB_DIR."classes/biz/member/Agent.php");

function getAgentList($params){
	$i_level = intval($params['level']);
	$i_pid = intval($params['pid']);
	
	$o_agent = new Agent();
	$arr_input = array();
	$arr_input['status'] = 1;
	if(!empty($i_level)) {
		$arr_input['level'] = $i_level;
	}
	if (!empty($i_pid)) {
		$arr_input['pid'] = $i_pid;
	}
	
	$total_cnt = $o_agent->getAgentListCount($arr_input);
	
	if($total_cnt > 0){
		$a_agent_list = $o_agent->getAgentList($arr_input);
	}else {
		$a_agent_list = array();
	}
	$a_result = $a_agent_list;
	
	return $a_result;
}
$pid = $_REQUEST['pid'];

unset($data);
$data['result'] = false;
if($pid > 0){
	unset($arr_input);
	$arr_input['pid'] = $pid;
	
	$a_agent_list = getAgentList($arr_input);
	if(!empty($a_agent_list)){
		$data['result'] = true;
		$data['info'] = $a_agent_list;
	}
}
echo json_encode($data);
?>