<?php
session_start();

require_once("../config.php");
require_once(TP_LIB_DIR."admin.php");
require_once(TP_LIB_DIR."agent.php");

$tag = $_POST['tag'];
$data['result'] = false;

switch ($tag){
	case "list":
		$level = intval($_POST['level']);
		$pid = intval($_POST['pid']);
		$count = intval($_POST['count']);
		$pageno = intval($_POST['pageno']);
		
		unset($arr_input);
		$arr_input['level'] = $level;
		$arr_input['pid'] = $pid;
		$arr_input['count'] = $count;
		$arr_input['pageno'] = $pageno;
		
		$a_agent_list = getAgentList($arr_input);
		$data['info'] = $a_agent_list['info'];
		$data['result'] = true;
				
	break;
}

echo json_encode($data);
?>