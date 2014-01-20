<?php
error_reporting(0);
require_once('../../config.php');
require_once(TP_LIB_DIR.'classes/biz/goldcoin/GoldCoinExchange.php');

$obj_GoldCoinExchange = new GoldCoinExchange();

unset($arr_input);
	
$int_state = $_REQUEST['state'];
if(1 == $int_state){
	$arr_input['is_sms'] = 1;
}else{
	$arr_input['is_sms'] = 2;
}
$arr_input['sms_sign'] = addslashes($_REQUEST['linkid']);

$result = $obj_GoldCoinExchange->setSMSState($arr_input);
?>