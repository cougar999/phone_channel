<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

/**
* PCMessageUidsQuery class
*
* @package query.message.PCMessageQueryUids
* @version 1.0.1
* @author jerry <guhao123479@sohu.com>
* @createDate 2012.10.23
* @changeHistory 
*/
class PCMessageUidsQuery  extends Query {

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
			
			case "GET_PCMESSAGE_UIDS_LIST_COUNT":
			
				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " stat_pc_uids";
				$str_query .= " where";
				$str_query .= " u_status = 1";
				$str_query .= " and shop_id = {$arr_input['shop_id']}";
			break;
			
			case "GET_PCMESSAGE_UIDS_LIST":
			
				$str_query .= "select";
				$str_query .= " uids";
				$str_query .= " from";
				$str_query .= " stat_pc_uids";
				$str_query .= " where";
				$str_query .= " u_status = 1";
				$str_query .= " and shop_id = {$arr_input['shop_id']}";

			break;
			

		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}
}//class