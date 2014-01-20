<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/notice/NoticeQuery.php');

/*
* Notice class
*
* @package biz.notice.Notice
* @version 1.0
* @author jerry<guhao123479@sohu.com>
* @createDate 2011.11.28
* @changeHistory 
*/

class Notice {

	public function __construct(){
	}
	
	public function __destruct(){
	}
	
	public function getNoticeListCount($arr_input = array()){
		try {
			$obj_query = new NoticeQuery();
			$str_query = $obj_query->getQuery("GET_NOTICE_LIST_COUNT",$arr_input);
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getNoticeList($arr_input = array(),$limit_query){
		try {
			$obj_query = new NoticeQuery();
			$str_query = $obj_query->getQuery("GET_NOTICE_LIST",$arr_input);
			
			if ($limit_query != null) {
				$obj_query->setLimitQuery($limit_query);
				$str_query = $obj_query->makeLimitQuery($str_query);
			}
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function addNotice($arr_input){
		try {
			$obj_query = new NoticeQuery();
			$str_query = $obj_query->getQuery("ADD_NOTICE", $arr_input);
			$dbh = DB::get_db_writer();
			
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function deleteNotice($arr_input){
		try {
			$obj_query = new NoticeQuery();
			$str_query = $obj_query->getQuery("DELETE_NOTICE", $arr_input);
			$dbh = DB::get_db_writer();
			
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
}
?>