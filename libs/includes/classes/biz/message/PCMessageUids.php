<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/message/PCMessageUidsQuery.php');

/*
* PCMessage class
*
* @package biz.message.PCMessageUids
* @version 1.0.1
* @author jerry<guhao123479@sohu.com>
* @createDate 2012.10.23
* @changeHistory 
*/

class PCMessageUids{
	
	public function __construct(){
		$this->dbkey = "stat";
	}
	
	public function __destruct(){
	}
	
	public function getPCMessageUidsListCount($arr_input){
		try {
			$obj_query = new PCMessageUidsQuery();
			$str_query = $obj_query->getQuery("GET_PCMESSAGE_UIDS_LIST_COUNT" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getPCMessageUidsList($arr_input){
		try {
			$obj_query = new PCMessageUidsQuery();
			$str_query = $obj_query->getQuery("GET_PCMESSAGE_UIDS_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQueryAssoc($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
}
?>