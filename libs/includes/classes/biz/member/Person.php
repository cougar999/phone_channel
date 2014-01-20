<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/member/PersonQuery.php');

class Person{
	public function __construct(){
		$this->dbkey = "member";
	}
	
	public function __destruct(){
	}
	
	public function getPersonListCount($arr_input){
		try{
			$obj_query = new PersonQuery();
			$str_query = $obj_query->getQuery("GET_PERSON_LIST_COUNT" , $arr_input);
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getPersonList($arr_input , $limit_query = null){
		try{
			$obj_query = new PersonQuery();
			$str_query = $obj_query->getQuery("GET_PERSON_LIST" , $arr_input);
			
			if ($limit_query != null) {
				$obj_query->setLimitQuery($limit_query);
				$str_query = $obj_query->makeLimitQuery($str_query);
			}
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function addSalesPerson($arr_input){
		try {
			$obj_query = new PersonQuery();
			$str_query = $obj_query->getQuery("ADD_PERSON" , $arr_input);
			
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function modifySalesPerson($arr_input){
		try {
			$obj_query = new PersonQuery();
			$str_query = $obj_query->getQuery("MODIFY_PERSON" , $arr_input);
			
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function checkAddPerson($arr_input){
		try {
			$obj_query = new PersonQuery();
			$str_query = $obj_query->getQuery("CHECK_ADD_PERSON" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function viewPersonById($arr_input){
		try {
			$obj_query = new PersonQuery();
			$str_query = $obj_query->getQuery("VIEW_PERSON_INFO" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function viewPersonByRealName($arr_input){
		try {
			$obj_query = new PersonQuery();
			$str_query = $obj_query->getQuery("VIEW_PERSON_INFO" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function addPerson($arr_input){
		try {
			$result = $this->checkAddPerson($arr_input);
			if($result){
				return ;
			}else{
				$b_result = $this->addSalesPerson($arr_input);
				if(empty($arr_input['job_number'])){
					unset($arr_input_update);
					$arr_input_update['id'] = DB::getInsertId();
					$arr_input_update['username'] = $arr_input_update['id'];
					$arr_input_update['password'] = $arr_input_update['id'];
					
					$b_result = $this->modifyAddPersonInfo($arr_input_update);
				}
			}
			return $b_result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function modifyAddPersonInfo($arr_input){
		try {
			$obj_query = new PersonQuery();
			$str_query = $obj_query->getQuery("MODIFY_ADD_PERSON_INFO" , $arr_input);
			
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	private function selectInsertID(){
		try {
			$obj_query = new PersonQuery();
			$str_query = $obj_query->lastInsertID();
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			$e->getMessage();
		}
	}
}

?>