<?php
require_once(TP_LIB_DIR.'classes/biz/common/util/Pager.php');
require_once(TP_LIB_DIR."classes/biz/member/Member.php");

function loginMember($username,$password){

	$o_member = new Member();

	if(empty($username) || empty($password)){
		return false;
	}

	unset($arr_input);
	$arr_input['username'] = addslashes(htmlspecialchars($_POST['username']));
	$arr_input['password'] = addslashes(htmlspecialchars($_POST['password']));
	$a_login_member_info = $o_member->loginMember($arr_input);		//分库登录查询验证数据
	if(empty($a_login_member_info)){
		return false;
	}
	
	unset($arr_input);
	$arr_input['id'] = $a_login_member_info['agent_id'];
	$arr_input['real_id'] = $a_login_member_info['id'];
	$arr_member_agent = $o_member->getMemberAgentInfo($arr_input);//查询ch_agent是否存在

	//插入在线店员表
	unset($arr_input);
	$arr_input['real_id'] = $a_login_member_info['id'];
	$arr_input['real_name'] = $a_login_member_info['real_name'];
	$arr_input['user_type'] = $a_login_member_info['user_type'];
	
	$agent_i = 1;
	foreach ($arr_member_agent as $agent_id => $agent_name){
		$arr_input['agent_id_'.$agent_i] = $agent_id;
		$arr_input['agent_name_'.$agent_i] = $agent_name;
		$agent_i++;
	}
	$result = $o_member->rememberLogin($arr_input);
	global $_super_mager;
	
	$_SESSION['userid'] = $result;
	$_SESSION['real_id'] = $a_login_member_info['id'];
	$_SESSION['chn_code'] = intval($a_login_member_info['chn_code']);
	$_SESSION['chn_tpl_code'] = intval($a_login_member_info['chn_tpl_code']);
	$_SESSION['username'] = $a_login_member_info['real_name'];
	$_SESSION['_username'] = $a_login_member_info['_real_name'];
	$_SESSION['user_type'] = $a_login_member_info['user_type'];
	$_SESSION['level'] = $a_login_member_info['level'];
	$_SESSION['agentid'] = $arr_input['agent_id_1'];
	$_SESSION['isSup'] = in_array($a_login_member_info['id'], $_super_mager)?1:0;
	
	return $result;
}


function rememberLogin($arr_input){
	$o_member = new Member();
	$result = $o_member->rememberLogin($arr_input);
	return $result;
}


function logoutMember($arr_input = array()){
	$o_member = new Member();
	$arr_input['uid'] = $_SESSION['userid'];
	$arr_input['real_id'] = $_SESSION['real_id'];
	$arr_input['user_type'] = $_SESSION['user_type'];
	$result = $o_member->logoutMember($arr_input);
	if($result){
		session_destroy();
	}
	return $result;
}

function getMemberOnlineList($count){
	$o_member = new Member();
	
	$total_cnt = $o_member->getMemberOnlineListCount();
	
	$view_cnt = $count;
	$block_cnt = 10;
	$extra_url = "";
	
	$obj_Pager = new Pager($page, $total_cnt, $tp_tpl_page, $view_cnt, $block_cnt, $extra_url, null);
	
	$arr_input = array();
	
	if($total_cnt > 0){
		$a_member_list = $o_member->getMemberOnlineList($arr_input,$obj_Pager->getLimitQuery());
	}else {
		$a_member_list = array();
	}
	
	return $a_member_list;
}

function getMemberInfo($arr_input){
	
	$o_member = new Member();
	
	//unset($arr_input);
	//$arr_input['uid'] = intval($userid);
	
	$a_member_info = $o_member->getMemberSessionByUid($arr_input);
	if(empty($a_member_info)){
		session_destroy();
	}
	
	return $a_member_info;
}

function getMemberAgentInfo($params){
	
	if($params['aid'] > 0){
		$o_member = new Member();
		unset($arr_input);
		$arr_input['id'] = intval($params['aid']);
		
		$arr_member_agent_info = $o_member->getMemberAgentInfo($arr_input);
	}else{
		$arr_member_agent_info = array();
	}
	
	return $arr_member_agent_info;
}

function getMemberByAgent($arr_input){
	
	$o_member = new Member();
	
	$a_member = $o_member->getMemberByAgent($arr_input);
	
	return $a_member;
}
?>