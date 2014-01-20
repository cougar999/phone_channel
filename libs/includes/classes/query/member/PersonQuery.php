<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

/**
* PersonQuery class
*
* @package query.member.PersonQuery
* @version 1.0
* @author jerry <guhao123479@sohu.com>
* @createDate 2011.12.07
* @changeHistory 
*/
class PersonQuery  extends Query {

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
			
			case "ADD_PERSON":
				$str_query .= "insert into";
				$str_query .= " ch_salesperson";
				$str_query .= " set";
				$str_query .= " create_time= NOW()";
				$str_query .= ", uuid='{$arr_input['uuid']}'";
				$str_query .= ", agent_id={$arr_input['agent_id']}";
				$str_query .= ", username='{$arr_input['username']}'";
				$str_query .= ", password='{$arr_input['password']}'";
				$str_query .= ", real_name='{$arr_input['real_name']}'";
				$str_query .= ", job_number='{$arr_input['job_number']}'";
				$str_query .= ", telphone='{$arr_input['telphone']}'";
				$str_query .= ", email='{$arr_input['email']}'";
				$str_query .= ", billing_type={$arr_input['billing_type']}";
				$str_query .= ", percent={$arr_input['percent']}";
				$str_query .= ", status={$arr_input['status']}";
				$str_query .= ", update_time= NOW()";
				$str_query .= ", reserve1={$arr_input['reserve1']}";
				$str_query .= ", reserve2={$arr_input['reserve2']}";
				$str_query .= ", reserve3='{$arr_input['reserve3']}'";
				$str_query .= ", reserve4='{$arr_input['reserve4']}'";				
				
			break;
			
			case "VIEW_PERSON_INFO":
			
				$str_query .= "select";
				$str_query .= "  id";
				$str_query .= ", uuid";
				$str_query .= ", agent_id";
				$str_query .= ", username";
				$str_query .= ", password";
				$str_query .= ", real_name";
				$str_query .= ", job_number";
				$str_query .= ", telphone";
				$str_query .= ", email";
				$str_query .= ", billing_type";
				$str_query .= ", percent";
				$str_query .= ", status";
				$str_query .= ", create_time";
				$str_query .= ", update_time";
				$str_query .= ", reserve1";
				$str_query .= ", reserve2";
				$str_query .= ", reserve3";
				$str_query .= ", reserve4";
				$str_query .= " from";
				$str_query .= " ch_salesperson";
				$str_query .= " where 1=1 ";
				if (array_key_exists('id', $arr_input)) {
					$str_query .= "and id={$arr_input['id']}";
				}
				if (array_key_exists('real_name', $arr_input)) {
					$str_query .= "and real_name='{$arr_input['real_name']}'";
				}

			break;
			
			case "MODIFY_PERSON":
				
				$str_query .= "update";
				$str_query .= " ch_salesperson";
				$str_query .= " set";
				$str_query .= " update_time= NOW()";
				if (array_key_exists('uuid', $arr_input)) {
					$str_query .= ", uuid='{$arr_input['uuid']}'";
				}
				if (array_key_exists('agent_id', $arr_input)) {
					$str_query .= ", agent_id={$arr_input['agent_id']}";
				}
				if (array_key_exists('username', $arr_input)) {
					$str_query .= ", username='{$arr_input['username']}'";
				}
				if (array_key_exists('password', $arr_input)) {
					$str_query .= ", password='{$arr_input['password']}'";
				}
				if (array_key_exists('real_name', $arr_input)) {
					$str_query .= ", real_name='{$arr_input['real_name']}'";
				}
				if (array_key_exists('job_number', $arr_input)) {
					$str_query .= ", job_number='{$arr_input['job_number']}'";
				}
				if (array_key_exists('telphone', $arr_input)) {
					$str_query .= ", telphone='{$arr_input['telphone']}'";
				}
				if (array_key_exists('email', $arr_input)) {
					$str_query .= ", email='{$arr_input['email']}'";
				}
				if (array_key_exists('billing_type', $arr_input)) {
					$str_query .= ", billing_type={$arr_input['billing_type']}";
				}
				if (array_key_exists('percent', $arr_input)) {
					$str_query .= ", percent={$arr_input['percent']}";
				}
				if (array_key_exists('status', $arr_input)) {
					$str_query .= ", status={$arr_input['status']}";
				}
				if (array_key_exists('reserve1', $arr_input)) {
					$str_query .= ", reserve1={$arr_input['reserve1']}";
				}
				if (array_key_exists('reserve2', $arr_input)) {
					$str_query .= ", reserve2={$arr_input['reserve2']}";
				}
				if (array_key_exists('reserve3', $arr_input)) {
					$str_query .= ", reserve3='{$arr_input['reserve3']}'";
				}
				if (array_key_exists('reserve4', $arr_input)) {
					$str_query .= ", reserve4='{$arr_input['reserve4']}'";
				}
				$str_query .= " where id = {$arr_input['id']}";

			break;
			
			case "MODIFY_ADD_PERSON_INFO":
				
				$str_query .="update";
				$str_query .=" ch_salesperson";
				$str_query .=" set";
				$str_query .=" username = '{$arr_input['username']}'";
				$str_query .=" ,password = '{$arr_input['password']}'";
				$str_query .=" where id = '{$arr_input['id']}'";
				
			break;
			
			case "CHECK_ADD_PERSON":
				
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " ch_salesperson";
				$str_query .= " where";
				$str_query .= " status = 1";
				if(!empty($arr_input['job_number']) && array_key_exists('job_number' , $arr_input)){
					$str_query .= " and job_number = '{$arr_input['job_number']}'";
				}else{
					$str_query .= " and agent_id = {$arr_input['agent_id']}";
					$str_query .= " and real_name = '{$arr_input['real_name']}'";
				}
				
			break;
			
			case "GET_PERSON_LIST_COUNT":
				
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " ch_salesperson";
				$str_query .= " where";
				$str_query .= " 1 = 1";
				if(!empty($arr_input['agent_id']) && array_key_exists('agent_id',$arr_input)){
					$str_query .= " and agent_id = {$arr_input['agent_id']}";
				}
				
			break;
			
			case "GET_PERSON_LIST":
				
				$str_query .= "select";
				$str_query .= " id";
				$str_query .= " ,real_name";
				$str_query .= " ,job_number";
				$str_query .= " ,status";
				$str_query .= " from";
				$str_query .= " ch_salesperson";
				$str_query .= " where";
				$str_query .= " 1 = 1";
				if(!empty($arr_input['agent_id']) && array_key_exists('agent_id',$arr_input)){
					$str_query .= " and agent_id = {$arr_input['agent_id']}";
				}
				$str_query .= " order by real_name desc";
				
			break;
			
		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}
}//class