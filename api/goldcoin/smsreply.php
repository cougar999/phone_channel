<?php
error_reporting(0);
require_once('../../config.php');
require_once(TP_LIB_DIR."common.function.php");
require_once(TP_LIB_DIR.'classes/biz/goldcoin/GoldCoinExchange.php');
require_once(TP_LIB_DIR."writeLog.php");

$obj_GoldCoinExchange = new GoldCoinExchange();

$msg = strtoupper(trim($_REQUEST['msg']));
$telphone = $_REQUEST['mobile'];
if($msg == 'Y'){
	unset($arr_input);
	$arr_input['telphone'] = addslashes($telphone);
	
	$result = $obj_GoldCoinExchange->setSMSStateReply($arr_input);
}

//写日志 格式: 日期 <|> IP <|> telphone:XXXXX <|> message:XXXX
$file = TP_APP_DIR."/log/goldcoin/".date("Y")."/".date("m")."/".date("d")."/"."reply.log";
$log_string = date('Y-m-d H:i:s u')." <|> ". $_SERVER['REMOTE_ADDR'] . " <|> telphone:" . $telphone . " <|> message:" . $_REQUEST['msg'];
writeLog($file,$log_string);
?>