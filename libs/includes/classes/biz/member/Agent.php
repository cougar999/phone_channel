<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/member/AgentQuery.php');

class Agent{
	public function __construct(){
		$this->dbkey = "member";
	}
	
	public function __destruct(){
	}
	
	public function getAgentListCount($arr_input){
		try{
			$obj_query = new AgentQuery();
			$str_query = $obj_query->getQuery("GET_AGENT_LIST_COUNT" , $arr_input);
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getAgentList($arr_input , $limit_query = null){
		try{
			$obj_query = new AgentQuery();
			$str_query = $obj_query->getQuery("GET_AGENT_LIST" , $arr_input);
			
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
	
	public function getAgentListSelect($arr_input){
		try{
			$obj_query = new AgentQuery();
			$str_query = $obj_query->getQuery("GET_AGENT_LIST_SELECT" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQueryAssoc($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function checkAddAgent($arr_input){
		try {
			$obj_query = new AgentQuery();
			$str_query = $obj_query->getQuery("CHECK_ADD_AGENT" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function addFirstAgent($arr_input){
		try {
			$obj_query = new AgentQuery();
			$str_query = $obj_query->getQuery("ADD_AGENT" , $arr_input);
			
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function modifyAddAgent($arr_input){
		try {
			$obj_query = new AgentQuery();
			$str_query = $obj_query->getQuery("MODIFY_ADD_AGENT" , $arr_input);
			
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function addAgentInfoByAgentID($arr_input){
		try{
			$obj_query = new AgentQuery();
			$str_query = $obj_query->getQuery("ADD_AGENT_INFO_BY_AGENTID" , $arr_input);
			
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function addAgent($arr_input){
		try {
			$result = $this->checkAddAgent($arr_input);
			if($result){
				return ;
			}else{
				$b_result = $this->addFirstAgent($arr_input);
				
				unset($arr_input_update);
				$arr_input_update['id'] = DB::getInsertId();
				if(empty($arr_input['username']) && $b_result){
					$arr_input_update['username'] = 'ly'.$arr_input_update['id'];
					$arr_input_update['password'] = $arr_input_update['id'];
					
					$b_result = $this->modifyAddAgent($arr_input_update);
					
					$arr_input['agent_id'] = $arr_input_update['id'];
					$arr_input['agent_name'] = $arr_input['name'];
					$b_result = $this->addAgentInfoByAgentID($arr_input);
				}
			}
			return $b_result;
			
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