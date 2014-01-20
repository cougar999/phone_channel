<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

/**
* IntergralQuery class
*
* @package query.intergral.IntergralQuery
* @version 1.0.1
* @author jerry <guhao123479@sohu.com>
* @createDate 2012.02.17
* @changeHistory 
*/
class IntergralQuery  extends Query {

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
			
			case "GET_INTERGRAL_LIST_COUNT":
			
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " tb_u_score";
				$str_query .= " where";
				$str_query .= " top_agent_id = {$arr_input['top_agent_id']}";
				if(array_key_exists("salesperson_id",$arr_input) &&!empty($arr_input['salesperson_id'])){
					$str_query .= " and salesperson_id in ({$arr_input['salesperson_id']})";
				}
			break;
			
			case "GET_INTERGRAL_LIST":
			
				$str_query .= "select";
				$str_query .= " salesperson_id";
				$str_query .= " ,salesperson_name";
				$str_query .= " ,score";
				$str_query .= " ,level";
				$str_query .= " from";
				$str_query .= " tb_u_score";
				$str_query .= " where";
				$str_query .= " top_agent_id = {$arr_input['top_agent_id']}";
				if(array_key_exists("salesperson_id",$arr_input) &&!empty($arr_input['salesperson_id'])){
					$str_query .= " and salesperson_id in ({$arr_input['salesperson_id']})";
				}
				$str_query .= " order by score desc";

			break;
			
			case "GET_INTERGRAL_MONTH_LIST_COUNT":
				
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " tb_u_score_month";
				$str_query .= " where";
				$str_query .= " top_agent_id = {$arr_input['top_agent_id']}";
				$str_query .= " and month = '{$arr_input['month']}'";
				if(array_key_exists("salesperson_id",$arr_input) &&!empty($arr_input['salesperson_id'])){
					$str_query .= " and salesperson_id in ({$arr_input['salesperson_id']})";
				}
			break;
				
			case "GET_INTERGRAL_MONTH_LIST":
			
				$str_query .= "select";
				$str_query .= " salesperson_id";
				$str_query .= " ,salesperson_name";
				$str_query .= " ,score";
				$str_query .= " from";
				$str_query .= " tb_u_score_month";
				$str_query .= " where";
				$str_query .= " top_agent_id = {$arr_input['top_agent_id']}";
				$str_query .= " and month = '{$arr_input['month']}'";
				if(array_key_exists("salesperson_id",$arr_input) &&!empty($arr_input['salesperson_id'])){
					$str_query .= " and salesperson_id in ({$arr_input['salesperson_id']})";
				}
				$str_query .= " order by score desc";

			break;
			
			case "GET_INTERGRAL_INFO":
			
				$str_query .= "select";
				$str_query .= " salesperson_id";
				$str_query .= " ,salesperson_name";
				$str_query .= " ,score";
				$str_query .= " ,usable_score";
				$str_query .= " ,level";
				$str_query .= " from";
				$str_query .= " tb_u_score";
				$str_query .= " where";
				$str_query .= " salesperson_id = {$arr_input['salesperson_id']}";

			break;

			case "GET_INTERGRAL_DETAILS_LIST_COUNT":
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " tb_u_score_details";
				$str_query .= " where";
				$str_query .= " salesperson_id = {$arr_input['salesperson_id']}";
			break;
			
			case "GET_INTERGRAL_DETAILS_LIST":
				
				$str_query .= "select";
				$str_query .= " salesperson_id";
				$str_query .= " ,salesperson_name";
				$str_query .= " ,type";
				$str_query .= " ,op_times";
				$str_query .= " ,imei";
				$str_query .= " ,active_day";
				$str_query .= " ,phone_no";
				$str_query .= " ,reserve3";
				$str_query .= " ,op_score";
				$str_query .= " from";
				$str_query .= " tb_u_score_details";
				$str_query .= " where";
				$str_query .= " salesperson_id = {$arr_input['salesperson_id']}";
				$str_query .= " order by active_day desc";
				
			break;

		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}
}//class