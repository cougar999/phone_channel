<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/goldcoin/GoldCoinExchangeQuery.php');

/*
* GoldCoinExchange class
*
* @package biz.goldcoin.GoldCoinExchange
* @version 1.0.1
* @author jerry<guhao123479@sohu.com>
* @createDate 2012.08.30
* @changeHistory 
*/

class GoldCoinExchange{
	
	public function __construct(){
	}
	
	public function __destruct(){
	}

	public function getGoldCoinExchangeListCount($arr_input){
		try {
			$obj_query = new GoldCoinExchangeQuery();
			$str_query = $obj_query->getQuery("GET_GOLDCOIN_EXCHANGE_LIST_COUNT" , $arr_input);
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getGoldCoinExchangeList($arr_input ,$limit_query = null){
		try {
			$obj_query = new GoldCoinExchangeQuery();
			$str_query = $obj_query->getQuery("GET_GOLDCOIN_EXCHANGE_LIST" , $arr_input);
			
			if ($limit_query != null) {
				$obj_query->setLimitQuery($limit_query);
				$str_query = $obj_query->makeLimitQuery($str_query);
			}
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQueryAssoc($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function modifyGoldCoinExchange($arr_input){
		try{
			$obj_query = new GoldCoinExchangeQuery();
			$str_query = $obj_query->getQuery("MODIFY_GOLDCOIN_EXCHANGE",$arr_input);
			$dbh = DB::get_db_writer();
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	/**
	 * 
	 * this function is get goldcoinexchangeById
	 * @param array $arr_input
	 * 						  [0]		id
	 * @return array $arr_output		
	 */
	public function getGoldCoinExchangeById($arr_input){
		try {
			$obj_query = new GoldCoinExchangeQuery();
			$str_query = $obj_query->getQuery("GET_GOLDCOIN_EXCHANGE_BY_ID",$arr_input);
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQueryAssoc($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	/**
	 * 
	 * this function is modify goldcoinexchange's is_sms
	 * @param array $arr_input
	 * 			string	sms_sign
	 * 			int		is_sms  1:(send sms ok) 
	 * 								or
	 * 							2:(send sms falied)		
	 * @return boolean $result
	 */
	public function setSMSState($arr_input){
		try{
			$obj_query = new GoldCoinExchangeQuery();
			$str_query = $obj_query->getQuery("SET_SMS_STATE",$arr_input);
			
			$dbh = DB::get_db_writer();
			$result = DB::executeQuery($str_query);
			
			return result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function setSMSStateReply($arr_input){
		try{
			$obj_query = new GoldCoinExchangeQuery();
			$str_query = $obj_query->getQuery("SET_SMS_STATE_REPLY",$arr_input);
			
			$dbh = DB::get_db_writer();
			$result = DB::executeQuery($str_query);
			
			return result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
}
?>