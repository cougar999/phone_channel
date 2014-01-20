<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/intergral/IntergralQuery.php');

/*
* IntergralRule class
*
* @package biz.intergral.Intergral
* @version 1.0.1
* @author jerry<guhao123479@sohu.com>
* @createDate 2012.02.17
* @changeHistory 
*/

class Intergral{
	
	public function __construct(){
	}
	
	public function __destruct(){
	}

	public function getIntergralListCount($arr_input){
		try {
			$obj_query = new IntergralQuery();
			$str_query = $obj_query->getQuery("GET_INTERGRAL_LIST_COUNT" , $arr_input);
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getIntergralList($arr_input ,$limit_query = null){
		try {
			$obj_query = new IntergralQuery();
			$str_query = $obj_query->getQuery("GET_INTERGRAL_LIST" , $arr_input);
			
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
	
public function getIntergralMonthListCount($arr_input){
		try {
			$obj_query = new IntergralQuery();
			$str_query = $obj_query->getQuery("GET_INTERGRAL_MONTH_LIST_COUNT" , $arr_input);
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getIntergralMonthList($arr_input ,$limit_query = null){
		try {
			$obj_query = new IntergralQuery();
			$str_query = $obj_query->getQuery("GET_INTERGRAL_MONTH_LIST" , $arr_input);
			
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
	
	public function getIntergralInfo($arr_input){
		try {
			$obj_query = new IntergralQuery();
			$str_query = $obj_query->getQuery("GET_INTERGRAL_INFO" , $arr_input);
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getIntergralDetailsListCount($arr_input){
		try {
			$obj_query = new IntergralQuery();
			$str_query = $obj_query->getQuery("GET_INTERGRAL_DETAILS_LIST_COUNT" , $arr_input);
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getIntergralDetailsList($arr_input ,$limit_query = null){
		try {
			$obj_query = new IntergralQuery();
			$str_query = $obj_query->getQuery("GET_INTERGRAL_DETAILS_LIST" , $arr_input);
			
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
	
}
?>