<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

/**
* PCMessageQuery class
*
* @package query.message.PCMessageQuery
* @version 1.0.1
* @author jerry <guhao123479@sohu.com>
* @createDate 2012.08.30
* @changeHistory 
*/
class PCMessageQuery  extends Query {

	function __construct(){}
	function __destruct(){}
	
	/**
	* This function return a completed query matched with sql_key
	* @param : string $query_key - key to obtain a completed query from QueryClass
	* @param : array $arr_input - input_data to use when you execute a query clause (null value is allowed)
	* @return : string $str_query - a completed query matched with sql_key
	*/
	final function getQuery($query_key, $arr_input=null){
		$str_query = '';
		
		switch ($query_key) {
			
			case "CHECK_MESS_HTML":
				
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " ch_message";
				$str_query .= " where";
				$str_query .= " mess_html = '{$arr_input['mess_html']}'";
				
			break;
			
			case "GET_PCMESSAGE_VALID_LIST_COUNT":
			
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " ch_message";
				$str_query .= " where";
				$str_query .= " status = 1";
				$str_query .= " and ( end_time > '{$arr_input['current_time']}'";
				$str_query .= " or valid_type = 2 )";
			break;
			
			case "GET_PCMESSAGE_VALID_LIST":
			
				$str_query .= "select";
				$str_query .= " id";
				$str_query .= " ,title";
				$str_query .= " ,valid_type";
				$str_query .= " ,for_type";
				$str_query .= " ,for_version";
				$str_query .= " ,valid_class";
				$str_query .= " ,start_time";
				$str_query .= " ,end_time";
				$str_query .= " ,isanony";
				$str_query .= " ,create_time";
				$str_query .= " from";
				$str_query .= " ch_message";
				$str_query .= " where";
				$str_query .= " status = 1";
				$str_query .= " and (end_time > '{$arr_input['current_time']}'";
				$str_query .= " or valid_type = 2 )";
				$str_query .= " order by create_time desc";

			break;
			
			case "GET_PCMESSAGE_OVER_LIST_COUNT":
			
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " ch_message";
				$str_query .= " where";
				$str_query .= " status = 1";
				$str_query .= " and valid_type = 1";
				$str_query .= " and end_time <= '{$arr_input['current_time']}'";
			break;
			
			case "GET_PCMESSAGE_OVER_LIST":
			
				$str_query .= "select";
				$str_query .= " id";
				$str_query .= " ,title";
				$str_query .= " ,valid_type";
				$str_query .= " ,for_type";
				$str_query .= " ,for_version";
				$str_query .= " ,valid_class";
				$str_query .= " ,start_time";
				$str_query .= " ,end_time";
				$str_query .= " ,isanony";
				$str_query .= " ,create_time";
				$str_query .= " from";
				$str_query .= " ch_message";
				$str_query .= " where";
				$str_query .= " status = 1";
				$str_query .= " and valid_type = 1";
				$str_query .= " and end_time <= '{$arr_input['current_time']}'";
				$str_query .= " order by create_time desc";

			break;
			
			case "ADD_PCMESSAGE":
				
				$str_query .= "insert into";
				$str_query .= " ch_message";
				$str_query .= " set";
				$str_query .= " title='{$arr_input['title']}'";
				$str_query .= ", mess='{$arr_input['mess']}'";
				$str_query .= ", mess_html='{$arr_input['mess_html']}'";
				$str_query .= ", mess_type={$arr_input['mess_type']}";
				$str_query .= ", valid_type={$arr_input['valid_type']}";
				$str_query .= ", valid_class={$arr_input['valid_class']}";
				$str_query .= ", for_type={$arr_input['for_type']}";
				switch ($arr_input['for_type']){
					case 2:
						$str_query .= ", for_version='{$arr_input['for_version']}'";
						$str_query .= ", isanony={$arr_input['isanony']}";
						break;
					case 3:
						$str_query .= ", for_udids='{$arr_input['for_udids']}'";
						break;
				}
				$str_query .= ", start_time='{$arr_input['start_time']}'";
				if (1 == $arr_input['valid_type']) {
					$str_query .= ", end_time='{$arr_input['end_time']}'";
				}
				$str_query .= ", status=1";
				$str_query .= ", create_time='{$arr_input['create_time']}'";
			break;
			
			case "MODIFY_PCMESSAGE":
				
				$str_query .= "update";
				$str_query .= " ch_message";
				$str_query .= " set";
				$str_query .= " title='{$arr_input['title']}'";
				$str_query .= ", mess='{$arr_input['mess']}'";
				$str_query .= ", mess_html='{$arr_input['mess_html']}'";
				$str_query .= ", mess_type={$arr_input['mess_type']}";
				$str_query .= ", valid_type={$arr_input['valid_type']}";
				$str_query .= ", for_type={$arr_input['for_type']}";
				switch ($arr_input['for_type']){
					case 2:
						$str_query .= ", for_version='{$arr_input['for_version']}'";
						$str_query .= ", isanony={$arr_input['isanony']}";
						break;
					case 3:
						$str_query .= ", for_udids='{$arr_input['for_udids']}'";
						break;
				}
				$str_query .= ", start_time='{$arr_input['start_time']}'";
				$str_query .= ", end_time='{$arr_input['end_time']}'";
				$str_query .= ", update_time = '{$arr_input['update_time']}'";
				$str_query .= " where";
				$str_query .= " id = {$arr_input['id']}";
				
			break;
			
			case "DELETE_PCMESSAGE":
				
				$str_query .= "update";
				$str_query .= " ch_message";
				$str_query .= " set";
				$str_query .= " status = 0";
				$str_query .= " where";
				$str_query .= " id = {$arr_input['id']}";
				
			break;
			
			case "VIEW_PCMESSAGE":
				
				$str_query .= "select";
				$str_query .= " id";
				$str_query .= " ,title";
				$str_query .= " ,mess";
				$str_query .= " ,mess_html";
				$str_query .= " ,mess_type";
				$str_query .= " ,valid_type";
				$str_query .= " ,for_type";
				$str_query .= " ,for_version";
				$str_query .= " ,for_udids";
				$str_query .= " ,valid_class";
				$str_query .= " ,start_time";
				$str_query .= " ,end_time";
				$str_query .= " ,isanony";
				$str_query .= " from";
				$str_query .= " ch_message";
				$str_query .= " where";
				$str_query .= " id = {$arr_input['id']}";
				
			break;
			
			case "ADD_LINK_MSG_CHANNEL":
				
				$str_query .= "insert into";
				$str_query .= " ch_link_msg_channel";
				$str_query .= " set";
				$str_query .= " messageid = {$arr_input['messageid']}";
				$str_query .= ", channel1 = {$arr_input['channel1']}";
				$str_query .= ", channel2 = {$arr_input['channel2']}";
				$str_query .= ", status = 1";
				$str_query .= ", create_time = '{$arr_input['create_time']}'";
				
			break;
			
			case "GET_LINK_MSG_CHANNEL_LIST":
				
				$str_query .= "select";
				$str_query .= " id";
				$str_query .= " ,messageid";
				$str_query .= " ,channel1";
				$str_query .= " ,channel2";
				$str_query .= " from";
				$str_query .= " ch_link_msg_channel";
				$str_query .= " where";
				$str_query .= " status = 1";
				$str_query .= " and messageid = {$arr_input['messageid']}";
				$str_query .= " order by id asc";
				
			break;
			
			case "DELETE_LINK_MSG_CHANNEL":
				
				$str_query .= "update";
				$str_query .= " ch_link_msg_channel";
				$str_query .= " set";
				$str_query .= " status = 0";
				$str_query .= " where";
				$str_query .= " id in ({$arr_input['id']})";
				
			break;
			
			case "ADD_LINK_MSG_AREA":
				
				$str_query .= "insert into";
				$str_query .= " ch_link_msg_area";
				$str_query .= " set";
				$str_query .= " messageid = {$arr_input['messageid']}";
				$str_query .= ", province = '{$arr_input['province']}'";
				$str_query .= ", city = '{$arr_input['city']}'";
				$str_query .= ", status = 1";
				$str_query .= ", create_time = '{$arr_input['create_time']}'";
				
			break;
			
			case "GET_LINK_MSG_AREA_LIST":
				
				$str_query .= "select";
				$str_query .= " id";
				$str_query .= " ,messageid";
				$str_query .= " ,province";
				$str_query .= " ,city";
				$str_query .= " from";
				$str_query .= " ch_link_msg_area";
				$str_query .= " where";
				$str_query .= " status = 1";
				$str_query .= " and messageid = {$arr_input['messageid']}";
				$str_query .= " order by id asc";
				
			break;

		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}
}//class