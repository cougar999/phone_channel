<?php
require_once(TP_LIB_DIR.'classes/db/CoreDb.php');

class DB{
	public function __construct(){
	}
	public function __destruct(){}
	
	/**
	 * @description:get database for writer
	 * @param :null
	 * @return :null
	 */
	public function get_db_writer($dbname = 'default'){
		$obj_CoreDb =new CoreDb($dbname);
		return $obj_CoreDb->db_select();
	}
	
	/**
	 * @description:get database for reader
	 * @param :null
	 * @return :null
	 */
	public function get_db_reader($dbname = 'default'){
		$obj_CoreDb =new CoreDb($dbname);
		return $obj_CoreDb->db_select();
	}
	
	/**
	 * Enter description here... close all databases
	 * @param :null
	 * @return :close database function
	 */
	public function close_all(){
		return CoreDb::db_close();
	}
	
	public function selectQuery($str_query){
		$i = 0;
		$result = CoreDb::db_query($str_query);
		if($result){
			while($row = CoreDb::db_fetch_array($result)){
				$arr_output[$i] = $row;
				$i++;
			}
			return $arr_output;
		}else {
			echo CoreDb::db_error() . "[$str_query]";
			return false;
		}
	}
	
	public function selectQueryAssoc($str_query){
		$i = 0;
		$result = CoreDb::db_query($str_query);
		if($result){
			while($row = CoreDb::db_fetch_assoc($result)){
				$arr_output[$i] = $row;
				$i++;
			}
			return $arr_output;
		}else {
			echo CoreDb::db_error() . "[$str_query]";
			return false;
		}
	}
	
	public function executeQuery($str_query){
		$result = CoreDb::db_query($str_query);
		if(!$result){
			echo CoreDb::db_error();
			return false;
		}else {
			return true;
		}
	}
	
	public function getInsertId() {
		$result = CoreDb::db_insert_id();
		if(!$result){
			echo CoreDb::db_error();
			return false;
		}else {
			return $result;
		}
	}
}
?>
