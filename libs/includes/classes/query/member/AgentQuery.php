<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

/**
* AgentQuery class
*
* @package query.member.AgentQuery
* @version 1.0
* @author jerry <guhao123479@sohu.com>
* @createDate 2011.12.05
* @changeHistory 
*/
class AgentQuery  extends Query {

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
			
			case "ADD_AGENT":
				
				$str_query .= "insert into";
				$str_query .= " ch_agent";
				$str_query .= " set";
				$str_query .= " create_time= NOW()";
				$str_query .= ", username='{$arr_input['username']}'";
				$str_query .= ", password='{$arr_input['password']}'";
				$str_query .= ", name='{$arr_input['name']}'";
				$str_query .= ", pid={$arr_input['pid']}";
				$str_query .= ", pname='{$arr_input['pname']}'";
				$str_query .= ", ppath='{$arr_input['ppath']}'";
				$str_query .= ", level={$arr_input['level']}";
				$str_query .= ", adjust_type={$arr_input['adjust_type']}";
				$str_query .= ", percent={$arr_input['percent']}";
				$str_query .= ", increment={$arr_input['increment']}";
				$str_query .= ", type={$arr_input['type']}";
				$str_query .= ", status={$arr_input['status']}";
				$str_query .= ", billing_type={$arr_input['billing_type']}";
				$str_query .= ", acc_balance={$arr_input['acc_balance']}";
				$str_query .= ", update_time= NOW()";
				$str_query .= ", reserve1={$arr_input['reserve1']}";
				$str_query .= ", reserve2={$arr_input['reserve2']}";
				$str_query .= ", reserve3='{$arr_input['reserve3']}'";
				$str_query .= ", reserve4='{$arr_input['reserve4']}'";

			break;
			
			case "MODIFY_ADD_AGENT":
				
				$str_query .="update";
				$str_query .=" ch_agent";
				$str_query .=" set";
				$str_query .=" username = '{$arr_input['username']}'";
				$str_query .=" ,password = '{$arr_input['password']}'";
				$str_query .=" where id = '{$arr_input['id']}'";
				
			break;
			
			case "ADD_AGENT_INFO_BY_AGENTID":
				
				$str_query .= "insert into";
				$str_query .= " ch_agent_info";
				$str_query .= " set";
				$str_query .= " create_time= NOW()";
				$str_query .= ", agent_id={$arr_input['agent_id']}";
				$str_query .= ", agent_name='{$arr_input['agent_name']}'";
				$str_query .= ", contact_name='{$arr_input['contact_name']}'";
				$str_query .= ", contact_tel='{$arr_input['contact_tel']}'";
				$str_query .= ", contact_email='{$arr_input['contact_email']}'";
				$str_query .= ", company_type={$arr_input['company_type']}";
				$str_query .= ", company_province='{$arr_input['company_province']}'";
				$str_query .= ", company_city='{$arr_input['company_city']}'";
				$str_query .= ", company_address='{$arr_input['company_address']}'";
				$str_query .= ", company_website='{$arr_input['company_website']}'";
				$str_query .= ", telphone='{$arr_input['telphone']}'";
				$str_query .= ", legal_person='{$arr_input['legal_person']}'";
				$str_query .= ", license='{$arr_input['license']}'";
				$str_query .= ", license_url='{$arr_input['license_url']}'";
				$str_query .= ", bank_name='{$arr_input['bank_name']}'";
				$str_query .= ", bank_address='{$arr_input['bank_address']}'";
				$str_query .= ", bank_tel='{$arr_input['bank_tel']}'";
				$str_query .= ", swift='{$arr_input['swift']}'";
				$str_query .= ", bank_user_name='{$arr_input['bank_user_name']}'";
				$str_query .= ", bank_account='{$arr_input['bank_account']}'";
				$str_query .= ", download_area={$arr_input['download_area']}";
				$str_query .= ", download_person={$arr_input['download_person']}";
				$str_query .= ", download_computer={$arr_input['download_computer']}";
				$str_query .= ", download_net='{$arr_input['download_net']}'";
				$str_query .= ", update_time=NOW()";
				$str_query .= ", reserve1={$arr_input['reserve1']}";
				$str_query .= ", reserve2={$arr_input['reserve2']}";
				$str_query .= ", reserve3='{$arr_input['reserve3']}'";
				$str_query .= ", reserve4='{$arr_input['reserve4']}'";
				
			break;
			
			case "CHECK_ADD_AGENT":
				
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " ch_agent";
				$str_query .= " where";
				$str_query .= " pid = {$arr_input['pid']}";
				$str_query .= " and name = '{$arr_input['name']}'";
				
			break;
			
			case "GET_AGENT_LIST_COUNT":
				
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " ch_agent";
				$str_query .= " where";
				$str_query .= " 1 = 1";
				if(!empty($arr_input['status']) && array_key_exists('status',$arr_input)){
					$str_query .= " and status = {$arr_input['status']}";
				}
				if(!empty($arr_input['level']) && array_key_exists('level',$arr_input)){
					$str_query .= " and level = {$arr_input['level']}";
				}
				if(!empty($arr_input['pid']) && array_key_exists('pid',$arr_input)){
					$str_query .= " and pid = {$arr_input['pid']}";
				}
				
			break;
			
			case "GET_AGENT_LIST":
				
				$str_query .= "select";
				$str_query .= " id";
				$str_query .= " ,name";
				$str_query .= " ,level";
				$str_query .= " ,status";
				$str_query .= " from";
				$str_query .= " ch_agent";
				$str_query .= " where";
				$str_query .= " 1 = 1";
				if(!empty($arr_input['status']) && array_key_exists('status',$arr_input)){
					$str_query .= " and status = {$arr_input['status']}";
				}
				if(!empty($arr_input['level']) && array_key_exists('level',$arr_input)){
					$str_query .= " and level = {$arr_input['level']}";
				}
				if(!empty($arr_input['pid']) && array_key_exists('pid',$arr_input)){
					$str_query .= " and pid = {$arr_input['pid']}";
				}
				$str_query .= " order by  status desc , update_time asc ";
				
			break;
			
			case "GET_AGENT_LIST_SELECT":
				
				$str_query .= "select";
				$str_query .= " id";
				$str_query .= " ,name";
				$str_query .= " from";
				$str_query .= " ch_agent";
				$str_query .= " where";
				$str_query .= " status = 1";
				$str_query .= " and id in ({$arr_input['id']})";
				
			break;
			
		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}
}//class