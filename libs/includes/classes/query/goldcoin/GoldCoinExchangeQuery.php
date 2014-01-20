<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

/**
* IntergralQuery class
*
* @package query.goldcoin.GoldCoinExchangeQuery
* @version 1.0.1
* @author jerry <guhao123479@sohu.com>
* @createDate 2012.08.30
* @changeHistory 
*/
class GoldCoinExchangeQuery  extends Query {

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
			
			case "GET_GOLDCOIN_EXCHANGE_LIST_COUNT":
			
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " ch_mission_goldcoin_exchange";
				$str_query .= " where 1 = 1";
				if(array_key_exists('status', $arr_input)){
					$str_query .= " and status = {$arr_input['status']}";
				}
				if(array_key_exists('ex_type', $arr_input)){
					$str_query .= " and ex_type = {$arr_input['ex_type']}";
				}
			break;
			
			case "GET_GOLDCOIN_EXCHANGE_LIST":
			
				$str_query .= "select";
				$str_query .= " id";
				$str_query .= " ,sales_id";
				$str_query .= " ,telphone";
				$str_query .= " ,goldcoin";
				$str_query .= " ,status";
				$str_query .= " ,description";
				$str_query .= " ,create_time";
				$str_query .= " ,update_time";
				$str_query .= " ,ex_type";
				$str_query .= " ,payment_mobnumber";
				$str_query .= " ,bank_card_no";
				$str_query .= " ,account_name";
				$str_query .= " ,bank_name";
				$str_query .= " ,identity_card";
				$str_query .= " ,is_sms";
				$str_query .= " from";
				$str_query .= " ch_mission_goldcoin_exchange";
				$str_query .= " where 1 = 1";
				if(array_key_exists('status', $arr_input)){
					$str_query .= " and status = {$arr_input['status']}";
				}
				if(array_key_exists('ex_type', $arr_input)){
					$str_query .= " and ex_type = {$arr_input['ex_type']}";
				}
				$str_query .= " order by create_time desc";

			break;
			
			case "MODIFY_GOLDCOIN_EXCHANGE":
				
				$str_query .= "update";
				$str_query .= " ch_mission_goldcoin_exchange";
				$str_query .= " set";
				$str_query .= " status = {$arr_input['status']}";
				if(array_key_exists('sms_sign', $arr_input)){
					$str_query .= " ,sms_sign = '{$arr_input['sms_sign']}'";
				}
				if(array_key_exists('is_sms', $arr_input)){
					$str_query .= " ,is_sms = {$arr_input['is_sms']}";
				}
				if(array_key_exists('description', $arr_input)){
					$str_query .= " ,description = '{$arr_input['description']}'";
				}
				$str_query .= " ,admin_id = '{$arr_input['admin_id']}'";
				$str_query .= " where";
				$str_query .= " id in ({$arr_input['id']})";
				
			break;
			
			case "GET_GOLDCOIN_EXCHANGE_BY_ID":

				$str_query .= "select";
				$str_query .= " telphone,goldcoin,create_time,ex_type,payment_mobnumber,bank_card_no,account_name,bank_name,identity_card";
				$str_query .= " from";
				$str_query .= " ch_mission_goldcoin_exchange";
				$str_query .= " where";
				$str_query .= " id = {$arr_input['id']}";
				
			break;
			
			case "SET_SMS_STATE":
				
				$str_query .= "update";
				$str_query .= " ch_mission_goldcoin_exchange";
				$str_query .= " set";
				$str_query .= " is_sms = {$arr_input['is_sms']}";
				$str_query .= " where";
				$str_query .= " sms_sign = '{$arr_input['sms_sign']}'";
				
			break;
			
			case "SET_SMS_STATE_REPLY":
				
				$str_query .= "update";
				$str_query .= " ch_mission_goldcoin_exchange";
				$str_query .= " set";
				$str_query .= " is_sms = 3";
				$str_query .= " where";
				$str_query .= " status = 2";
				$str_query .= " and is_sms = 1";
				$str_query .= " and telphone = '{$arr_input['telphone']}'";
				$str_query .= " order by create_time asc";
				$str_query .= " limit 1";
				
			break;

		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}
}//class