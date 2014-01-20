<?php
function smarty_function_pcmessage($params , &$smarty){
	$ret = $params['ret'];
	$tag = $params['tag'];
	$arr_form_version_list = array(
		"2.600" => 'V2.6',
		"2.700" => 'V2.7',
		"2.800" => 'V2.8',
		"2.900" => 'V2.9',
	);
	switch ($tag){
		case "view":
			require_once(TP_LIB_DIR.'classes/biz/message/PCMessage.php');
			require_once(TP_LIB_DIR.'classes/biz/member/Agent.php');
			
			$obj_pcmessage = new PCMessage();
			$obj_agent = new Agent();
			
			unset($arr_input);
			$arr_input['id'] = intval($params['id']);
			$arr_info = $obj_pcmessage->viewPCMessage($arr_input);
			
			$arr_link_msg_channel = array();
			$arr_link_msg_area = array();
			$arr_for_version = array();
			unset($arr_input);
			$arr_input['messageid'] = intval($params['id']);
			$arr_link_msg_channel_hash = $obj_pcmessage->getLinkMsgChannelList($arr_input);
			$arr_link_msg_area = $obj_pcmessage->getLinkMsgAreaList($arr_input);
			
			//start获取渠道名
			if(!empty($arr_link_msg_channel_hash)){
				foreach ($arr_link_msg_channel_hash as $row){
					if($row['channel1'])	$arr_hash_channelid[] = $row['channel1'];
					if($row['channel2'])	$arr_hash_channelid[] = $row['channel2'];
				}
				if(!empty($arr_hash_channelid) && is_array($arr_hash_channelid)){
					$str_hash_channelid = join(',',$arr_hash_channelid);
					unset($arr_input);
					$arr_input['id'] = $str_hash_channelid;
					$arr_channel_info_list = $obj_agent->getAgentListSelect($arr_input);
					$arr_hash_channel_info = array();
					foreach ($arr_channel_info_list as $arr_channel_info){
						$arr_hash_channel_info[$arr_channel_info['id']] = $arr_channel_info['name'];
					}
					foreach ($arr_link_msg_channel_hash as $key => $row){
						if($row['channel1']){
							$arr_link_msg_channel_hash[$key]['channel1name'] = $arr_hash_channel_info[$row['channel1']];
						}else{
							$arr_link_msg_channel_hash[$key]['channel1name'] = '所有体系';
						}
						if($row['channel2']){
							$arr_link_msg_channel_hash[$key]['channel2name'] = $arr_hash_channel_info[$row['channel2']];
						}else{
							$arr_link_msg_channel_hash[$key]['channel2name'] = '所有分公司';
						}
					}
					$arr_link_msg_channel = $arr_link_msg_channel_hash;
				}
			}
			//end获取渠道名
			
			$arr_for_version_hash = explode(",",$arr_info['for_version']);
			foreach ($arr_for_version_hash as $value){
				$arr_for_version[$value] = $arr_form_version_list[$value];
			}
			unset($arr_output);
			$arr_output = $arr_info;
			$arr_output['link_channel'] = $arr_link_msg_channel;
			$arr_output['link_area'] = $arr_link_msg_area;
			$arr_output['for_version'] = $arr_for_version;
			switch ($arr_info['valid_type']){
				case 2:$arr_output['etime'] = '永久有效';break;
				case 1:
					switch ($arr_info['valid_class']){
						case 1:$arr_output['etime'] = '当日有效';break;
						case 2:$arr_output['etime'] = '本周有效';break;
						case 3:$arr_output['etime'] = '本月有效';break;
					}
				break;
			}
			if(!empty($ret)){
				return $smarty->assign($ret , $arr_output);
			}else {
				return $arr_output;
			}
		break;
	}
	return null;
}


?>