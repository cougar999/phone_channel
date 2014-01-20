<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

/**
* IntergralRuleQuery class
*
* @package query.intergral.IntergralRuleQuery
* @version 1.0.1
* @author jerry <guhao123479@sohu.com>
* @createDate 2012.01.14
* @changeHistory 
*/
class IntergralRuleQuery  extends Query {

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
			
			case "GET_EVERYDAY_LOGIN":
			
			$str_query .= "select";
			$str_query .= " count(distinct(login_day)) as login_day_sum";
			$str_query .= " ,seller_id";
			$str_query .= " from";
			$str_query .= " stat_day_login_seller";
			$str_query .= " where";
			$str_query .= " seller_id = {$arr_input['seller_id']}";
			$str_query .= " and login_success_times > 0";
			$str_query .= " and login_day <= '{$arr_input['login_day']}'";

			break;
			
			case "GET_EVERYDAY_LOGIN_LIST":
			
			$str_query .= "select";
			$str_query .= " distinct(login_day) as str_login_day";
			$str_query .= " ,seller_id";
			$str_query .= " from";
			$str_query .= " stat_day_login_seller";
			$str_query .= " where";
			$str_query .= " seller_id = {$arr_input['seller_id']}";
			$str_query .= " and login_success_times > 0";
			$str_query .= " and login_day <= '{$arr_input['login_day']}'";

			break;

			

		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}
}//class