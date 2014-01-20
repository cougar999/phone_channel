<!--{php}-->
date_default_timezone_set('PRC');
require_once(TP_LIB_DIR.'classes/biz/message/PCMessage.php');

$obj_pcmessage = new PCMessage();

$title		 = urldecode($_POST['title']);
$mess		 = urldecode($_POST['mess']);
$mess_type	 = intval($_POST['mess_type']);									//1、消息体，取mess字段；2、html文件，取mess_type字段
$mess_html	 = urldecode($_POST['mess_html']);
if(1 == $mess_type){
	$url = date("y",time())."/".date("m",time())."/".date("d",time())."/".date("His").rand(0,99).".html";
	$html_url = TP_HTML_DIR."pcmessage/".$url;
	$mess_html = AP_PC_MESSAGE_DOMAIN.$url;
}
$for_type	 = intval($_POST['for_type']);
if($obj_pcmessage->checkMessHtml($mess_html)){
	echo "<center>消息html文件地址重复,<font color=\"red\">3</font>秒后自动登录!</center>";
	echo "<script language=\"javascript\">setTimeout(function(){window.location.history(-1);},3000);</script>";
	exit;
}

$arr_msg_channel = array();
$arr_msg_area = array();

switch($for_type){
	case 2:
		$isanony	 = intval($_POST['isanony']);
		$for_version = urldecode($_POST['for_version']);
		if(!empty($_POST['msg_channel']) && is_array($_POST['msg_channel'])) $arr_msg_channel = array_unique($_POST['msg_channel']);
		if(!empty($_POST['msg_area']) && is_array($_POST['msg_area'])) $arr_msg_area = array_unique($_POST['msg_area']);
		$arr_for_version = array_unique(empty($_POST['msg_version']) ? array() : $_POST['msg_version']);
		$for_version = urldecode(join(",",$arr_for_version));
		break;
	case 3:
		$for_udids	 = urldecode(join(",",$_POST['for_udids']));
		break;
}

$valid_class		 = urldecode($_POST['valid_class']);
$day  = date('d'); 
$mon  = date('m'); 
$year = date('Y'); 
$today  = date('N');
$create_time = date("Y-m-d H:i:s");
switch ($valid_class){
	case "1":
		$valid_type = 1;
		$start_time = $create_time;
		$end_time = date("Y-m-d H:i:s", mktime(23,59,59, $mon, $day, $year));
		break;
	case "2":
		$valid_type = 1;
		$start_time = $create_time;
		$end_time = date('Y-m-d H:i:s', mktime(23,59,59, $mon, $day-$today+7, $year));
		break;
	case "3":
		$valid_type = 1;
		$start_time = $create_time; 
		$end_time = date('Y-m-d H:i:s', mktime(23,59,59, $mon+1, 0, $year)); 
		break;
	case "4":
		$valid_type = 2;
		$start_time = $create_time; 
		break;
}

$status		 = intval($_POST['status']);
unset($arr_input);
$arr_input['title']			 = $title;
$arr_input['mess']			 = $mess;
$arr_input['mess_html']		 = $mess_html;
$arr_input['mess_type']		 = $mess_type;
$arr_input['valid_type']	 = $valid_type;
$arr_input['for_type']		 = $for_type;
$arr_input['for_version']	 = $for_version;
$arr_input['for_udids']		 = $for_udids;

$arr_input['valid_class']	 = $valid_class;
$arr_input['start_time']	 = $start_time;
$arr_input['end_time']		 = $end_time;
$arr_input['isanony']		 = $isanony;
$arr_input['create_time']	 = $create_time;
$result_add = $obj_pcmessage->addPCMessage($arr_input);
$msg_id = mysql_insert_id();
switch($for_type){
	case 2:
		foreach ($arr_msg_channel as $row){
			unset($arr_input);
			$arr_input['messageid'] = $msg_id;
			$arr_hash_channel = explode(",",$row);
			$arr_input['channel1'] = intval($arr_hash_channel[0]);
			$arr_input['channel2'] = intval($arr_hash_channel[1]);
			$arr_input['status'] = 1;
			$arr_input['create_time'] = $create_time;
			$result = $obj_pcmessage->addLinkMsgChannel($arr_input);
		}
		
		foreach ($arr_msg_area as $row){
			unset($arr_input);
			$arr_input['messageid'] = $msg_id;
			$arr_hash_area = explode(",",$row);
			$arr_input['province'] = urldecode($arr_hash_area[0]);
			$arr_input['city'] = urldecode($arr_hash_area[1]);
			$arr_input['status'] = 1;
			$arr_input['create_time'] = $create_time;
			$result = $obj_pcmessage->addLinkMsgArea($arr_input);
		}
	break;
}
if($result_add){
	if(1 == $mess_type){
		$mess = stripslashes($_POST['mess']);
		$obj_pcmessage->create_html($mess,$html_url);
		$str_sh = exec("/bin/sh /data/scripts/rsync_html_198.sh");
		if('ok' == $str_sh){
			echo "<center>消息发布成功,<font color=\"red\">3</font>秒后跳转到消息列表!</center>";
			echo "<script language=\"javascript\">setTimeout(function(){window.location.replace('index.html');},3000);</script>";
		}else{
			echo "<center>消息发布成功但同步文件失败,<font color=\"red\">3</font>秒后跳转到消息列表!</center>";
			echo "<script language=\"javascript\">setTimeout(function(){window.location.replace('index.html');},3000);</script>";
		}
	}else{
		echo "<center>消息发布成功,<font color=\"red\">3</font>秒后跳转到消息列表!</center>";
		echo "<script language=\"javascript\">setTimeout(function(){window.location.replace('index.html');},3000);</script>";
	}
}
<!--{/php}-->