<?php
require_once("../../config.php");
require_once(TP_LIB_DIR."classes/biz/intergral/Intergral.php");
require_once(TP_LIB_DIR."classes/biz/member/Person.php");
require_once(TP_LIB_DIR."intergral.php");
require_once("common.func.php");



function getIntergralInfoByUid($uid){
	$o_intergral = new Intergral();
	
	unset($arr_input);
	$arr_input['salesperson_id'] = $uid;
	
	$a_intergral_info = $o_intergral->getIntergralInfo($arr_input);
	
	$a_result_info = array();
	if(!empty($a_intergral_info)){
		$a_result_info['seller_id'] = $a_intergral_info[0]['salesperson_id'];
		$a_result_info['real_name'] = $a_intergral_info[0]['salesperson_name'];
		$a_result_info['score'] = $a_intergral_info[0]['score'];
		$a_result_info['level'] = $a_intergral_info[0]['level'];
		$a_result_info['leveldesc'] = getLevelDesc($a_intergral_info[0]);
	}else{
		$o_person = new Person();
		unset($arr_input);
		$arr_input['id'] = $uid;
		$arr_person_info = $o_person->viewPersonById($arr_input);
		
		$a_result_info['seller_id'] = $uid;
		$a_result_info['real_name'] = $arr_person_info['real_name'];
		$a_result_info['score'] = 0;
		$a_result_info['level'] = 1;
		$a_result_info['leveldesc'] = getLevelDesc($a_result_info);
	}
	
	return $a_result_info;
}

if(!empty($_REQUEST['uid'])){
	$uid = $_REQUEST['uid'];
	
	$a_intergral_info = getIntergralInfoByUid($uid);
	
	echo jsonEncodeChina($a_intergral_info);
	//echo json_encode($a_intergral_info);
}
?>