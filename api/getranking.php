<?php
require_once("../../config.php");
require_once(TP_LIB_DIR."classes/biz/intergral/Intergral.php");
require_once(TP_LIB_DIR."classes/biz/member/Person.php");
require_once(TP_LIB_DIR."classes/biz/member/Member.php");
require_once(TP_LIB_DIR."intergral.php");
require_once("common.func.php");

function getIntergralRankingByUid($arr_input){
	$uid = $arr_input['uid'];
	$is_month = $arr_input['is_month'];
	if($is_month == 1){
		$current_month = date("Y-m", time());
	}
	$count = 10;
	
	$o_intergral = new Intergral();
	$o_person = new Person();
	$o_member = new Member();
	
	//通过店员id获取店员信息
	unset($arr_input);
	$arr_input['id'] = $uid; 
	$arr_salesperson_info = $o_person->viewPersonById($arr_input);
	
	//通过shopid获取顶级渠道id
	unset($arr_input);
	$arr_input['id'] = $arr_salesperson_info['agent_id'];
	$arr_agent_info = $o_member->getMemberAgentInfoById($arr_input);
	$arr_agent_id = explode(",", $arr_agent_info['ppath']);
	$top_agent_id = $arr_agent_id[0];
	
	unset($arr_input);
	$arr_input['top_agent_id'] = $top_agent_id;
	if(empty($current_month)){
		$total_cnt = $o_intergral->getIntergralListCount($arr_input);
		
		if ($total_cnt){
			$a_intergral_list = $o_intergral->getIntergralList($arr_input,"limit 0,{$count}");
			foreach ($a_intergral_list as $key => $row){
				$a_intergral_list[$key]['leveldesc'] = getLevelDesc($row);
			}
		}else{
			$a_intergral_list = array();
		}
	}else{
		$arr_input['month'] = $current_month;
		$total_cnt = $o_intergral->getIntergralMonthListCount($arr_input);
		
		if ($total_cnt){
			$a_intergral_list = $o_intergral->getIntergralMonthList($arr_input,"limit 0,{$count}");
		}else{
			$a_intergral_list = array();
		}
	}
	return $a_intergral_list;
}

if(!empty($_REQUEST['uid'])){

	$uid = intval($_REQUEST['uid']);
	$is_month = intval($_REQUEST['curmonth']);	//0全部，1本月
	unset($arr_input);
	$arr_input['uid'] = $uid;
	$arr_input['is_month'] = $is_month;
	$a_intergral_ranking = getIntergralRankingByUid($arr_input);
	
	echo jsonEncodeChina($a_intergral_ranking);
}
?>