<?php
session_start();

require_once("../config.php");
require_once(TP_LIB_DIR."classes/biz/member/Person.php");

function getPersonList($params){
	$i_agent_id = intval($params['aid']);
	
	$o_person = new Person();
	$arr_input = array();
	
	if (!empty($i_agent_id)) {
		$arr_input['agent_id'] = $i_agent_id;
	}
	
	$total_cnt = $o_person->getPersonListCount($arr_input);
	
	if($total_cnt > 0){
		$a_person_list = $o_person->getPersonList($arr_input);
	}else {
		$a_person_list = array();
	}
	$a_result = $a_person_list;
	
	return $a_result;
}
$aid = $_REQUEST['aid'];

unset($data);
$data['result'] = false;
if($aid > 0){
	unset($arr_input);
	$arr_input['aid'] = $aid;
	
	$a_person_list = getPersonList($arr_input);
	if(!empty($a_person_list)){
		$data['result'] = true;
		$data['info'] = $a_person_list;
	}
}
echo json_encode($data);
?>