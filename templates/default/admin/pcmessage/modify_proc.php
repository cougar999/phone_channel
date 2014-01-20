<!--{php}-->
date_default_timezone_set('PRC');
require_once(TP_LIB_DIR.'classes/biz/message/PCMessage.php');

$obj_pcmessage = new PCMessage();

$messageid	 = intval($_POST['messageid']);
$title		 = urldecode($_POST['title']);
$mess		 = urldecode($_POST['mess']);
$mess_type	 = 2;									//1、消息体，取mess字段；2、html文件，取mess_type字段

$for_type	 = intval($_POST['for_type']);
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
		$for_udids	 = urldecode($_POST['for_udids']);
		break;
}

$etime		 = urldecode($_POST['etime']);
$day  = date('d'); 
$mon  = date('m'); 
$year = date('Y'); 
$today  = date('N');
switch ($etime){
	case "当天有效":
		$valid_type = 1;
		$start_time = date("Y-m-d");
		$end_time = date("Y-m-d", strtotime("+1 day"));
		break;
	case "本周有效":
		$valid_type = 1;
		$start_time = date('Y-m-d H:i:s', mktime(0,0,0, $mon, $day-$today+1, $year));
		$end_time = date('Y-m-d H:i:s', mktime(23,59,59, $mon, $day-$today+7, $year));
		break;
	case "本月有效":
		$valid_type = 1;
		$start_time = date('Y-m-d H:i:s', mktime(0,0,0, $mon, 1, $year)); 
		$end_time = date('Y-m-d H:i:s', mktime(23,59,59, $mon+1, 0, $year)); 
		break;
	case "永久有效":
		$valid_type = 2;
		break;
}

$status		 = intval($_POST['status']);
$create_time = date("Y-m-d H:i:s");

unset($arr_input);
$arr_input['id']			 = $messageid;
$arr_input['title']			 = $title;
$arr_input['mess']			 = $mess;
$arr_input['mess_html']		 = $mess_html;
$arr_input['mess_type']		 = $mess_type;
$arr_input['valid_type']	 = $valid_type;
$arr_input['for_type']		 = $for_type;
$arr_input['for_version']	 = $for_version;
$arr_input['for_udids']		 = $for_udids;

$arr_input['start_time']	 = $start_time;
$arr_input['end_time']		 = $end_time;
$arr_input['isanony']		 = $isanony;
$arr_input['update_time']	 = $create_time;
//$result = $obj_pcmessage->modifyPCMessage($arr_input);
switch($for_type){
	case 2:
		unset($arr_input);
		$arr_input['messageid'] = intval($messageid);
		$arr_link_msg_channel_hash = $obj_pcmessage->getLinkMsgChannelList($arr_input);
		$arr_link_msg_area = $obj_pcmessage->getLinkMsgAreaList($arr_input);
		if(!empty($arr_link_msg_channel_hash) && is_array($arr_link_msg_channel_hash)){
			for($i = 0;$i < count($arr_link_msg_channel_hash);$i++){
				$id = $arr_link_msg_channel_hash[$i]['id'];
				$channel1 = $arr_link_msg_channel_hash[$i]['channel1'];
				$channel2 = $arr_link_msg_channel_hash[$i]['channel2'];
				$arr_msg_channel_hash[$i] = $channel1.",".$channel2;
				if(is_array($arr_msg_channel) && !in_array($arr_msg_channel_hash[$i],$arr_msg_channel)){
					$arr_hash_del_channelid[] = $id;
				}elseif(!is_array($arr_msg_channel)){
					$arr_hash_del_channelid[] = $id;
				}
			}
			if(!empty($arr_hash_del_channelid) && is_array($arr_hash_del_channelid)){
				$str_hash_del_channelid = join("," , $arr_hash_del_channelid);
				unset($arr_input);
				$arr_input['id'] = $str_hash_del_channelid;
				$obj_pcmessage->delLinkMsgChannel($arr_input);
			}
			for($i = 0;$i < count($arr_msg_channel);$i++){
				if(!in_array($arr_msg_channel[$i],$arr_msg_channel_hash)){
					unset($arr_input);
					$arr_input['messageid'] = $messageid;
					$arr_hash_channel = explode(",",$arr_msg_channel[$i]);
					$arr_input['channel1'] = intval($arr_hash_channel[0]);
					$arr_input['channel2'] = intval($arr_hash_channel[1]);
					$arr_input['status'] = 1;
					$arr_input['create_time'] = $create_time;
					$obj_pcmessage->addLinkMsgChannel($id);
				}
			}
		}else{
			for($i = 0;$i < count($arr_msg_channel);$i++){
				unset($arr_input);
				$arr_input['messageid'] = $messageid;
				$arr_hash_channel = explode(",",$arr_msg_channel[$i]);
				$arr_input['channel1'] = intval($arr_hash_channel[0]);
				$arr_input['channel2'] = intval($arr_hash_channel[1]);
				$arr_input['status'] = 1;
				$arr_input['create_time'] = $create_time;
				$result = $obj_pcmessage->addLinkMsgChannel($arr_input);
			}
		}
		
	break;
}
<!--{/php}-->