<?php
error_reporting(0);
require_once('../../config.php');
require_once(TP_LIB_DIR.'classes/biz/goldcoin/GoldCoinExchange.php');

$type = $_REQUEST['type'];
$obj_GoldCoinExchange = new GoldCoinExchange();

unset($arr_input);
unset($arr_output);
$arr_input['sms_sign'] = addslashes($_REQUEST['linkid']);
switch ($type){
	case "ok":
		$arr_input['is_sms'] = 1;
		$result = $obj_GoldCoinExchange->modifySms($arr_input);
	break;
	
	case "reply":
		$arr_input['is_sms'] = 2;
		//$arr_input['telphone'] = addslashes($_REQUEST['telphone']);
		$result = $obj_GoldCoinExchange->modifySms($arr_input);
	break;
}
if($result){
	$arr_output['result'] = 'true';
}else{
	$arr_output['result'] = 'false';
}
echo json_encode($arr_output);
?>