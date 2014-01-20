<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/communication/CommunicationQuery.php');

class Communication{
	var $items; 
	public function __construct(){
		$this->Query  =  new CommunicationQuery();
	}
	
	public function __destruct(){}
	
	public function communicat($userid){
		$str_query = $this->Query->getQuery("GET_COMMUNICATION_NOTE",$userid);
		$dbh = DB::get_db_reader();
		$arr_output = DB::selectQuery($str_query);
	    return $arr_output;
	}
	
	public function addNote($arr_input){
		try {
			$obj_query = new CommunicationQuery();
			$str_query = $obj_query->getQuery("ADD_NOTE", $arr_input);
			
			$dbh = DB::get_db_writer();
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getCommunicationListCount($arr_input){
		try{
			$obj_query = new CommunicationQuery();
			$str_query = $obj_query->getQuery("GET_COMMUNICATION_LIST_COUNT" , $arr_input);
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getCommunicationList($arr_input , $limit_query = null){
		try{
			$obj_query = new CommunicationQuery();
			$str_query = $obj_query->getQuery("GET_COMMUNICATION_LIST" , $arr_input);
			
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
	
	/*
	 * 发信息
	 */
	public function submit_message($attr){

	$date = array_merge($attr,array("create_date" => time()));

	//$obj_query = new CommunicationQuery();
	$str_query = $this->Query->getQuery("ADD_MESSAGE",$date);
	$dbh = DB::get_db_reader();
	$arr_output = DB::executeQuery($str_query);
    return $arr_output;
	}
	
	private function selectInsertID(){
		try {
			$obj_query = new CommunicationQuery();
			$str_query = $obj_query->lastInsertID();
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function createRecord($arr_input){
		try {
			$arr_hash_column = $arr_hash_value = array();
			foreach ($arr_input as $key=>$value){
				$arr_hash_column[] = $key;
				$arr_hash_value[] = "'".$value."'";
			}
			$k = join("," , $arr_hash_column);
			$v = join("," , $arr_hash_value);
			
			$str_query="INSERT INTO tb_record (".$k.") VALUES (".$v.")";
			$dbh = DB::get_db_writer();
			return $arr_output = DB::executeQuery($str_query);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	/*留言*/
	public function getRecord($uid){
		try {
			$str_query="select * from tb_record where nid='$uid' order by rid DESC limit 0,5";
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			return $arr_output;
			
		}catch (Exception $e){
			$e->getMessage();
		}
	}
}


?>