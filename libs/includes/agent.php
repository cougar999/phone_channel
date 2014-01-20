<?php
require_once(TP_LIB_DIR.'classes/biz/common/util/Pager.php');
require_once(TP_LIB_DIR."classes/biz/member/Member.php");
require_once(TP_LIB_DIR."classes/biz/member/Agent.php");

function getAgentList($params){
	$page = $params['pageno'] > 0 ? $params['pageno'] : 1;
	$count = intval($params['count']);
	
	$i_level = intval($params['level']);
	$i_pid = intval($params['pid']);
	
	$o_agent = new Agent();
	$arr_input = array();
	if(!empty($i_level)) {
		$arr_input['level'] = $i_level;
	}
	if (!empty($i_pid)) {
		$arr_input['pid'] = $i_pid;
	}
	
	$total_cnt = $o_agent->getAgentListCount($arr_input);
	$view_cnt = $count;
	$block_cnt = 10;
	$extra_url = "";
	if(!empty($i_level)){
		$extra_url .= "level=".$i_level;
	}
	if(!empty($i_pid)){
		$extra_url .= "&pid=".$i_pid;
	}
	
	$obj_Pager = new Pager($page, $total_cnt, $tp_tpl_page, $view_cnt, $block_cnt, $extra_url);
	if($total_cnt > 0){
		$a_agent_list = $o_agent->getAgentList($arr_input,$obj_Pager->getLimitQuery());
	}else {
		$a_agent_list = array();
	}
	$a_result = array("pagehtml"=>$obj_Pager->getPagingHtml(),"info"=>$a_agent_list);
	
	return $a_result;
}

function addAgent($params){
	$o_agent = new Agent();
	$arr_input = array();
	$username = addslashes($_POST['username']);
	$password = addslashes($_POST['password']);
	$name = addslashes($_POST['name']);
	$pid = intval($_POST['pid']);
	if (!empty($pid)) {
		$o_member = new Member();
		unset($arr_input_member_info);
		$arr_input_member_info['id'] = $pid;
		$arr_member_agent = $o_member->getMemberAgentInfo($arr_input_member_info);
		
		$arr_member_agent_info = $o_member->getMemberAgentInfoById($arr_input_member_info);
		$int_plevel = $arr_member_agent_info['level'];
		
		$arr_hash_ppath = array();
		foreach ($arr_member_agent as $agent_id => $agent_name){
			$pname = $agent_name;
			$arr_hash_ppath[] = $agent_id;
		}
		
		$level = $int_plevel + 1;
		$ppath = join("," , $arr_hash_ppath);
	}else{
		$pid = 0;
		$pname = '';
		$level = 1;
		$ppath = '0';
	}
	
	
	$adjust_type = intval($_POST['adjust_type']);
	$percent = intval($_POST['percent']);
	$increment = intval($_POST['increment']);
	$type = intval($_POST['type']);
	$billing_type = intval($_POST['billing_type']);
	$acc_balance = intval($_POST['acc_balance']);
	$reserve1 = intval($_POST['reserve1']);
	$reserve2 = intval($_POST['reserve2']);
	$reserve3 = addslashes($_POST['reserve3']);
	$reserve4 = addslashes($_POST['reserve4']);

	if(!empty($username)){
		$arr_input['username'] = $username;
		$arr_input['password'] = $password;
	}
	$arr_input['name'] = $name;
	$arr_input['pid'] = $pid;
	$arr_input['pname'] = $pname;
	$arr_input['ppath'] = $ppath;
	$arr_input['level'] = $level;
	$arr_input['adjust_type'] = $adjust_type;
	$arr_input['percent'] = $percent;
	$arr_input['increment'] = $increment;
	$arr_input['type'] = $type;
	$arr_input['status'] = 1;
	$arr_input['billing_type'] = $billing_type;
	$arr_input['acc_balance'] = $acc_balance;
	$arr_input['reserve1'] = $reserve1;
	$arr_input['reserve2'] = $reserve2;
	$arr_input['reserve3'] = $reserve3;
	$arr_input['reserve4'] = $reserve4;
	
	//insert ch_agent_info data
	$contact_name = addslashes($_POST['contact_name']);
	$contact_tel = addslashes($_POST['contact_tel']);
	$contact_email = addslashes($_POST['contact_email']);
	$company_type = intval($_POST['company_type']);
	$company_province = addslashes($_POST['company_province']);
	$company_city = addslashes($_POST['company_city']);
	$company_address = addslashes($_POST['company_address']);
	$company_website = addslashes($_POST['company_website']);
	$telphone = addslashes($_POST['telphone']);
	$legal_person = addslashes($_POST['legal_person']);
	$license = addslashes($_POST['license']);
	$license_url = addslashes($_POST['license_url']);
	$bank_name = addslashes($_POST['bank_name']);
	$bank_address = addslashes($_POST['bank_address']);
	$bank_tel = addslashes($_POST['bank_tel']);
	$swift = addslashes($_POST['swift']);
	$bank_user_name = addslashes($_POST['bank_user_name']);
	$bank_account = addslashes($_POST['bank_account']);
	$download_area = intval($_POST['download_area']);
	$download_person = intval($_POST['download_person']);
	$download_computer = intval($_POST['download_computer']);
	$download_net = addslashes($_POST['download_net']);
	
	$arr_input['contact_name'] = $contact_name;
	$arr_input['contact_tel'] = $contact_tel;
	$arr_input['contact_email'] = $contact_email;
	$arr_input['company_type'] = $company_type;
	$arr_input['company_province'] = $company_province;
	$arr_input['company_city'] = $company_city;
	$arr_input['company_address'] = $company_address;
	$arr_input['company_website'] = $company_website;
	$arr_input['telphone'] = $telphone;
	$arr_input['legal_person'] = $legal_person;
	$arr_input['license'] = $license;
	$arr_input['license_url'] = $license_url;
	$arr_input['bank_name'] = $bank_name;
	$arr_input['bank_address'] = $bank_address;
	$arr_input['bank_tel'] = $bank_tel;
	$arr_input['swift'] = $swift;
	$arr_input['bank_user_name'] = $bank_user_name;
	$arr_input['bank_account'] = $bank_account;
	$arr_input['download_area'] = $download_area;
	$arr_input['download_person'] = $download_person;
	$arr_input['download_computer'] = $download_computer;
	$arr_input['download_net'] = $download_net;

	$b_result = $o_agent->addAgent($arr_input);
	
	if($b_result){
		$write_log_time = date("Y年m月d日H时i分s秒" , time());
		$write_log_string = "“{$_SESSION['username']}”刚在{$write_log_time}添加了{$level}级渠道名为“{$name}”的数据。";
		writeAdminLog("Agent.txt",$write_log_string);
	}
	
	return $b_result;
}

?>