<?php
date_default_timezone_set('PRC');
require_once("../config.php");
require_once(TP_LIB_DIR."feed.php");
function autoConverEncode($strs,$encode='utf-8'){
	if (is_array ( $strs )) {
		$result = array ();
		foreach ( $strs as $key => $value ) {
			$result [$key] = autoConverEncode ( $value,$encode );
		}
	} else {
		$charset = mb_detect_encoding($strs, array('ASCII','UTF-8','GB2312','GBK','BIG5','ISO-8859-1'));
		$result  = strcasecmp($charset,$encode) != 0 ? mb_convert_encoding($strs,$encode,$charset) : $strs;
	}
	return $result;
}
function checkPhone($mobilephone){
	return preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$/",$mobilephone);
}
$type 			   = trim($_REQUEST['type']);
$flag			   = trim($_REQUEST['flag']);

if ($type == 'sfid'){
	if (isset($_REQUEST['sid']) && !empty($_REQUEST['sid']) && isset($_REQUEST['sname']) && !empty($_REQUEST['sname']) && isset($_REQUEST['mobile']) && !empty($_REQUEST['mobile']) && isset($_REQUEST['issub'])) {
		$data['sid'] = intval($_REQUEST['sid']);
		$data['sname'] = $_REQUEST['sname'];
		$data['mobile'] = $_REQUEST['mobile'];
		$data['issub'] = $_REQUEST['issub'];
		$detail = ap_core_feed("AP_INT_SEND_SOFT_MESSAGE" , $data);
		echo json_encode($detail);
		
	}else{
		echo 'failed';
	}
}else{
	$cnf = array(
		'wap' => 'http://m.appdear.com/wap/',
		'ard' => 'http://www.appdear.com/a.apk',
		'syb' => 'http://www.appdear.com/s.sisx',
	);
	!checkPhone($mobilephone = $_REQUEST['mobile']) && die(json_encode(array('code'=>'004')));
	!!empty($cnf[$flag]) && die(json_encode(array('code'=>'002')));
	$url 	= 'http://sms.api.aipimobile.com/aipimobile_sms_war/jsp/smsSender.jsp';
	$req 	= parse_url($url);
	$client = new HttpClient($req['host'], $req['port'] ? $req['port'] : 80);
//	$client->setDebug(true);

	$text 	= '地址：'.$cnf[$flag].' 安卓软件、游戏，尽在爱皮下载 ';
	$client->get($req['path'], array('phone' => $mobilephone, 'msgContent' => iconv('utf-8','gbk//IGNORE',$text) . ' ' . date('Y-m-d h:i')));
	$rs = ($client->getStatus()=='200'&&trim($client->getContent())=='') ? '006' : '003';
	echo json_encode(array('code'=>$rs));
}
?>