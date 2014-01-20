<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

/**
* MemberQuery class
*
* @package query.member.Memberquery
* @version 1.0
* @author jerry <guhao123479@sohu.com>
* @createDate 2011.11.24
* @changeHistory 
*/
class MemberQuery  extends Query {

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
			
			case "GET_LOGIN_MEMBER":
			
			$str_query .= "select";
			$str_query .= " id";
			$str_query .= " , agent_id";
			$str_query .= " , real_name";
			$str_query .= " from";
			$str_query .= " ch_salesperson";
			$str_query .= " where";
			$str_query .= " username = '{$arr_input['username']}'";
			$str_query .= " and password = '{$arr_input['password']}'";
			$str_query .= " and status = 1";
			
			break;
			
			case "GET_MEMBER_BY_AGENT":
			
			$str_query .= "select";
			$str_query .= " id";
			$str_query .= " , real_name";
			$str_query .= " from";
			$str_query .= " ch_salesperson";
			$str_query .= " where";
			$str_query .= " agent_id = '{$arr_input['agent_id']}'";

			break;
			
			case "GET_LOGIN_CHANNEL_MEMBER":
				
			$str_query .= "select";
			$str_query .= " id";
			$str_query .= " , name";
			$str_query .= " , pid";
			$str_query .= " , level";
			$str_query .= " , chn_code";
			$str_query .= " , chn_tpl_code";
			$str_query .= " from";
			$str_query .= " ch_agent";
			$str_query .= " where";
			$str_query .= " username = '{$arr_input['username']}'";
			$str_query .= " and password = '{$arr_input['password']}'";
			$str_query .= " and status = 1";
				
			break;
			
			case "GET_MEMBER_AGENT_INFO":
			
			$str_query .= "select";
			$str_query .= " id";
			$str_query .= " , name";
			$str_query .= " , ppath";
			$str_query .= " , level";
			$str_query .= " from";
			$str_query .= " ch_agent";
			$str_query .= " where";
			$str_query .= " id = {$arr_input['id']}";
			
			break;
			
			case "MODIFY_MEMBER_SESSION":
			
			$str_query .= "update";	
			$str_query .= " tb_user_session";	
			$str_query .= " set";	
			$str_query .= " last_ol_time='{$this->currenttime()}'";
			if (array_key_exists('logout_status', $arr_input)) {
				$str_query .= ", logout_status={$arr_input['logout_status']}";
			}
			if (array_key_exists('real_name', $arr_input)) {
				$str_query .= ", real_name='{$arr_input['real_name']}'";
			}
			if (array_key_exists('agent_id_1', $arr_input)) {
				$str_query .= ", agent_id_1=".intval($arr_input['agent_id_1']);
			}
			if (array_key_exists('agent_name_1', $arr_input)) {
				$str_query .= ", agent_name_1='{$arr_input['agent_name_1']}'";
			}
			if (array_key_exists('agent_id_2', $arr_input)) {
				$str_query .= ", agent_id_2=".intval($arr_input['agent_id_2']);
			}
			if (array_key_exists('agent_name_2', $arr_input)) {
				$str_query .= ", agent_name_2='{$arr_input['agent_name_2']}'";
			}
			if (array_key_exists('agent_id_3', $arr_input)) {
				$str_query .= ", agent_id_3=".intval($arr_input['agent_id_3']);
			}
			if (array_key_exists('agent_name_3', $arr_input)) {
				$str_query .= ", agent_name_3='{$arr_input['agent_name_3']}'";
			}
			$str_query .= " where uid = {$arr_input['uid']}";
			$str_query .= " and ";
			$str_query .= "user_type = {$arr_input['user_type']}";
			
			break;
			
			case "ADD_MEMBER_SESSION":
			
			$str_query .= "insert into";
			$str_query .= " tb_user_session";
			$str_query .= " set";
			$str_query .= " last_ol_time='{$this->currenttime()}'";
			$str_query .= ", real_id={$arr_input['real_id']}";
			$str_query .= ", user_type={$arr_input['user_type']}";
			$str_query .= ", real_name='{$arr_input['real_name']}'";
			$str_query .= ", agent_id_1='".intval($arr_input['agent_id_1'])."'";
			$str_query .= ", agent_name_1='{$arr_input['agent_name_1']}'";
			$str_query .= ", agent_id_2='".intval($arr_input['agent_id_2'])."'";
			$str_query .= ", agent_name_2='{$arr_input['agent_name_2']}'";
			$str_query .= ", agent_id_3='".intval($arr_input['agent_id_3'])."'";
			$str_query .= ", agent_name_3='{$arr_input['agent_name_3']}'";
				
			break;	
			
			case "GET_MEMBER_SESSION":
				
			$str_query .= "select";
			$str_query .= " last_ol_time";
			$str_query .= " , agent_id_1";
			$str_query .= " , agent_name_1";
			$str_query .= " , agent_id_2";
			$str_query .= " , agent_name_2";
			$str_query .= " , agent_id_3";
			$str_query .= " , agent_name_3";
			$str_query .= " from";
			$str_query .= " tb_user_session";
			$str_query .= " where";
			$str_query .= " real_id = {$arr_input['real_id']}";
			$str_query .= " and ";
			$str_query .= "user_type = {$arr_input['user_type']}";
				
			break;
			
			case "CHECK_LOGIN":
			$str_query .= "select";
			$str_query .= " uid";
			$str_query .= " from";
			$str_query .= " tb_user_session";
			$str_query .= " where";
			$str_query .= " real_id = {$arr_input['real_id']}";
			$str_query .= " and ";
			$str_query .= " user_type = {$arr_input['user_type']}";
				
			break;
			
			case "GET_MEMBER_ONLINE_LIST_COUNT":
				
			$str_query .= "select";
			$str_query .= " count(*)";
			$str_query .= " from";
			$str_query .= " tb_user_session";
			$str_query .= " where";
			$str_query .= " logout_status = 0";
			$str_query .= " and ({$this->currentTime()} - last_ol_time) < 5*60";
			
			break;
			
			case "GET_MEMBER_ONLINE_LIST":
			
			$str_query .= "select";
			$str_query .= " uid";
			$str_query .= " ,real_name";
			$str_query .= " ,agent_id_1";
			$str_query .= " ,agent_name_1";
			$str_query .= " ,agent_id_2";
			$str_query .= " ,agent_name_2";
			$str_query .= " ,agent_id_3";
			$str_query .= " ,agent_name_3";
			$str_query .= " from";
			$str_query .= " tb_user_session";
			$str_query .= " where";
			$str_query .= " logout_status = 0";
			$str_query .= " and ({$this->currentTime()} - last_ol_time) < 5*60";
			
			break;
			
		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}
}//class