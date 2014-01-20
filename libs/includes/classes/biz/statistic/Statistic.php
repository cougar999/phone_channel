<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/statistic/StatisticQuery.php');

/*
* Statistic class
*
* @package biz.statistic.Statistic
* @version 1.0
* @author jerry<guhao123479@sohu.com>
* @createDate 2011.11.25
* @changeHistory 
*/

class Statistic {

	public function __construct(){
		$this->dbkey = "stat";
	}
	
	public function __destruct(){
	}
	
	public function getDaySellerIncomeList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_DAY_SELLER_INCOME_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMonthSellerIncomeList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_MONTH_SELLER_INCOME_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getSearchSellerIncomeList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_SEARCH_DAY_SELLER_INCOME_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getDaySellerDownloadList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_DAY_SELLER_DOWNLOAD_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMonthSellerDownloadList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_MONTH_SELLER_DOWNLOAD_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getSearchSellerDownloadList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_SEARCH_DAY_SELLER_DOWNLOAD_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getDaySellerCardList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_DAY_SELLER_CARD_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMonthSellerCardList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_MONTH_SELLER_CARD_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getSearchSellerCardList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_SEARCH_DAY_SELLER_CARD_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	
	public function getDayChannelIncomeList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_DAY_CHANNEL_INCOME_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMonthChannelIncomeList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_MONTH_CHANNEL_INCOME_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getSearchChannelIncomeList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_SEARCH_DAY_CHANNEL_INCOME_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getDayChannelDownloadList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_DAY_CHANNEL_DOWNLOAD_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMonthChannelDownloadList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_MONTH_CHANNEL_DOWNLOAD_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getSearchChannelDownloadList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_SEARCH_DAY_CHANNEL_DOWNLOAD_LIST" , $arr_input);
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getDayChannelCardList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_DAY_CHANNEL_CARD_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMonthChannelCardList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_MONTH_CHANNEL_CARD_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getSearchChannelCardList($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_SEARCH_DAY_CHANNEL_CARD_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getAppBlackList($arr_input = null){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("GET_APP_BLACK_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	public function deleteAppBlacklist($arr_input){
		try {
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("DELETE_APP_BLACK_LIST",  $arr_input);
			$dbh = DB::get_db_writer($this->dbkey);
			$arr_output = DB::executeQuery($str_query);
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function AddAppBlacklist($arr_input){
		try {
			
			$obj_query = new StatisticQuery();
			$str_query = $obj_query->getQuery("ADD_APP_BLACK_LIST",  $arr_input);
			$dbh = DB::get_db_writer($this->dbkey);
			$arr_output = DB::executeQuery($str_query);
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	
}

?>