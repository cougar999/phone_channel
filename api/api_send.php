<?php
error_reporting(0);
date_default_timezone_set('PRC');
require_once("../config.php");
require_once(TP_LIB_DIR."smtp.class.php");
require_once(TP_LIB_DIR."regExp.php");
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
$cnf_mail = array(
	'server' => 'mail.appdear.com',
	'port'   => '25',
	'user'   => 'smtp_web@appdear.com',
	'pwd'    => 'F3K2W4m4',
	'type'   => 'html',
	'auth'   => 'aipi'
);
$type = trim($_REQUEST['type']);

if ($type == 'email'){
	$email    = trim($_REQUEST['email']);
	$ottype   = trim($_REQUEST['ottype']);
	$otinfo   = trim($_REQUEST['otinfo']);
	$ot_cnf   = array(
		'phone'=>'手机号为',
		'qq'=>'QQ号为',
	);
	$_toemail = 'luxuntao@appdear.com';
	$em_max_len = 76;
//	$_toemail = '358751844@qq.com';
	
	if (!!empty($email)) $code = '001';
	elseif (!empty($email) && !regExp::isEmail($email)) $code = '002';
	elseif (strlen($email) > $em_max_len) $code = '010';
	elseif (!empty($otinfo) && $ottype=='phone' && !regExp::isPhone($otinfo)) $code = '003';
	elseif (!empty($otinfo) && $ottype=='qq' && !regExp::isQQ($otinfo)) $code = '007';
	else{
		$qf_title = '申请爱皮下载零售店版测试账号';
		$qf_body  = "地区：{$_REQUEST['province']}&nbsp;&nbsp;{$_REQUEST['city']}&nbsp;&nbsp;{$_REQUEST['town']}<BR>邮箱地址：{$email}".(empty($otinfo)?"":"<BR>{$ot_cnf[$ottype]}：{$otinfo}");
		$smtp 	  = new smtp($cnf_mail['server'],$cnf_mail['port'],true,$cnf_mail['user'],$cnf_mail['pwd']);
		//$smtp->debug = true;
		$qf_title = autoConverEncode($qf_title,'gbk');
		$qf_body  = autoConverEncode($qf_body,'gbk');
		$code     = $smtp->sendmail($_toemail, $cnf_mail['user'], $qf_title, $qf_body, $cnf_mail['type']) ? '006' : '004';
	}
	echo json_encode(array('code'=>$code));
}