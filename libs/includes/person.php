<?php
require_once(TP_LIB_DIR.'classes/biz/common/util/Pager.php');
require_once(TP_LIB_DIR."classes/biz/member/Person.php");

function getPersonList($params){
	$page = $params['pageno'] > 0 ? $params['pageno'] : 1;
	$count = intval($params['count']);
	
	$i_agent_id = intval($params['aid']);
	
	$o_person = new Person();
	$arr_input = array();
	
	if (!empty($i_agent_id)) {
		$arr_input['agent_id'] = $i_agent_id;
	}
	
	$total_cnt = $o_person->getPersonListCount($arr_input);
	$view_cnt = $count;
	$block_cnt = 10;
	$extra_url = "";
	if(!empty($i_agent_id)){
		$extra_url .= "&aid=".$i_agent_id;
	}
	
	$obj_Pager = new Pager($page, $total_cnt, $tp_tpl_page, $view_cnt, $block_cnt, $extra_url);
	if($total_cnt > 0){
		$a_person_list = $o_person->getPersonList($arr_input,$obj_Pager->getLimitQuery());
	}else {
		$a_person_list = array();
	}
	$a_result = array("pagehtml"=>$obj_Pager->getPagingHtml(),"info"=>$a_person_list);
	
	return $a_result;
}

function addSalesPerson($params){
	
	$o_person = new Person();
	$arr_input = array();
	$arr_input['agent_id'] = addslashes($_POST['agent_id']);
	$arr_input['real_name'] = addslashes($_POST['real_name']);
	$arr_input['job_number'] = addslashes($_POST['job_number']);
	
	//如果有工号，店员账户密码按照工号，用户名为ly+job_number，密码为工号的末五位
	if(!empty($arr_input['job_number'])){
		//乐语集团下的所有员工前缀为"dy"
		$arr_input['username'] = "dy".$arr_input['job_number'];
		$arr_input['password'] = substr($arr_input['job_number'] , -5 , 5);
	}else {
		
	}
	$arr_input['telphone'] = addslashes($_POST['telphone']);
	$arr_input['email'] = addslashes($_POST['email']);
	$arr_input['billing_type'] = intval($_POST['billing_type']);
	$arr_input['percent'] = intval($_POST['percent']);
	$arr_input['status'] = 1;
	$arr_input['reserve1'] = intval($_POST['reserve1']);
	$arr_input['reserve2'] = intval($_POST['reserve2']);
	$arr_input['reserve3'] = addslashes($_POST['reserve3']);
	$arr_input['reserve4'] = addslashes($_POST['reserve4']);
	
	$b_result = $o_person->addPerson($arr_input);
	
	if($b_result){
		$write_log_time = date("Y年m月d日H时i分s秒" , time());
		$write_log_string = "“{$_SESSION['username']}”刚在{$write_log_time}添加了店面id为“{$arr_input['agent_id']}”店员名为“{$arr_input['real_name']}”的数据。";
		writeAdminLog("Person.txt",$write_log_string);
	}
	
	return $b_result;
}

function viewSalesPerson($params){
	
	if($params['id'] > 0){
		$o_person = new Person();
		$arr_input = array();
		$arr_input['id'] = intval($params['id']);
		
		$a_person_info = $o_person->viewPersonById($arr_input);
	}else{
		$a_person_info = array();
	}
	return $a_person_info;
}

function modifySalesPerson($params){
	
	if($params['id'] > 0){
		$o_person = new Person();
		$arr_input = array();
		$arr_input['id'] = intval($params['id']);
		$arr_input['agent_id'] = intval($_POST['agent_id']);
		$arr_input['real_name'] = addslashes($_POST['real_name']);
		$arr_input['job_number'] = addslashes($_POST['job_number']);
		$arr_input['telphone'] = addslashes($_POST['telphone']);
		$arr_input['email'] = addslashes($_POST['email']);
		
		$b_result = $o_person->modifySalesPerson($arr_input);
	}else{
		$b_result = false;
	}
	return $b_result;
}
?>