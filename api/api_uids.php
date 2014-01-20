<?php
session_start();
error_reporting(0);
set_time_limit(0);
!defined('_INIT_FROM_INDEX_') && include_once '../config.php';
require_once(TP_LIB_DIR.'classes/biz/member/Person.php');
require_once(TP_LIB_DIR.'classes/biz/message/PCMessageUids.php');

$obj_person = new Person();
$obj_pcmessageUids = new PCMessageUids();

$real_name = $_REQUEST['real_name'];
unset($arr_input);
$arr_input['real_name'] = $real_name;
$arr_person_info = $obj_person->viewPersonByRealName($arr_input);
$int_shop_id = $arr_person_info['agent_id'];

if(!empty($int_shop_id)){
	unset($arr_input);
	$arr_input['shop_id'] = $int_shop_id;
	$total_count = $obj_pcmessageUids->getPCMessageUidsListCount($arr_input);
	if($total_count > 0){
		$arr_uids = $obj_pcmessageUids->getPCMessageUidsList($arr_input);
		foreach ($arr_uids as $row){
			$arr_uids_hash[] = $row['uids'];
		}
		$str_uids = join(',', $arr_uids_hash);
	}else{
		$str_uids = '';
	}
}else{
	$str_uids = '';
}
$arr_output['data'] = $str_uids;
echo json_encode($arr_output);