<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/member/MemberQuery.php');

class Member{
	public function __construct(){
		$this->dbkey = "member";
	}
	
	public function __destruct(){
	}
	
	public function getLoginChannelMember($arr_input){
		try{
			$obj_query = new MemberQuery();
			$str_query = $obj_query->getQuery("GET_LOGIN_CHANNEL_MEMBER" , $arr_input);
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			$arr_channel_info = array();
			if(!empty($arr_output)) {
				$arr_channel_info['id'] = $arr_output[0]['id'];
				$arr_channel_info['real_name'] = $arr_output[0]['name'];
				$arr_channel_info['agent_id'] = $arr_output[0]['pid'];	
				$arr_channel_info['level'] = $arr_output[0]['level'];	
				$arr_channel_info['chn_code'] = $arr_output[0]['chn_code'];	
				$arr_channel_info['chn_tpl_code'] = $arr_output[0]['chn_tpl_code'];	
			}
			
			return $arr_channel_info;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getLoginMember($arr_input){//查询ch_salesperson
		try{
			$obj_query = new MemberQuery();
			$str_query = $obj_query->getQuery("GET_LOGIN_MEMBER" , $arr_input);
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMemberByAgent($arr_input){//查询ch_salesperson
		try{
			$obj_query = new MemberQuery();
			$str_query = $obj_query->getQuery("GET_MEMBER_BY_AGENT" , $arr_input);
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getCnInfo($arr_input){
		$str_query  = "select id,name,pid,level,chn_code,chn_tpl_code from channel.ch_agent
						where level in(1,2) and id = {$arr_input['agent_id']}";
		$dbh        = DB::get_db_reader($this->dbkey);
		$arr_output = DB::selectQueryAssoc($str_query);
		
		return $arr_output;
	}
	
	public function loginMember($arr_input){
		try{
			$arr_output = $this->getLoginMember($arr_input);//查询ch_salesperson
			if(!empty($arr_output)){
				//判断是否是1，2级下直接店员
				$_tmp_out = $this->getCnInfo($arr_output);
				if (!empty($_tmp_out)){
					$arr_channel_info['id'] = $_tmp_out[0]['id'];
					$arr_channel_info['real_name'] = $_tmp_out[0]['name'];
					$arr_channel_info['_real_name'] = $arr_output['real_name'];
					$arr_channel_info['agent_id'] = $_tmp_out[0]['pid'];	
					$arr_channel_info['level'] = $_tmp_out[0]['level'];	
					$arr_channel_info['chn_code'] = $_tmp_out[0]['chn_code'];	
					$arr_channel_info['chn_tpl_code'] = $_tmp_out[0]['chn_tpl_code'];	
					
					$arr_channel_info['user_type'] = '2';
					if (empty($arr_channel_info['agent_id']) && (1 == $arr_channel_info['level'])) {
						$arr_channel_info['agent_id'] = $arr_channel_info['id'];
					}else if(empty($arr_channel_info['agent_id']) && (1 != $arr_channel_info['level'])){
						return ;
					}
					return $arr_channel_info;
				}
				/*判断是否是1，2级下直接店员END*/
				$arr_output['user_type'] = '1';
				if (empty($arr_output['agent_id'])) {
					return ;
				}
			}else {
				unset($arr_output);
				$arr_output = $this->getLoginChannelMember($arr_input);//查询ch_agent数据库
				if(!empty($arr_output)){
					$arr_output['user_type'] = '2';
					if (empty($arr_output['agent_id']) && (1 == $arr_output['level'])) {
						$arr_output['agent_id'] = $arr_output['id'];
					}else if(empty($arr_output['agent_id']) && (1 != $arr_output['level'])){
						return ;
					}
				}
			}
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function logoutMember($arr_input){
		try{
			$obj_query = new MemberQuery();
			$arr_input['logout_status'] = 1;
			$str_query = $obj_query->getQuery("MODIFY_MEMBER_SESSION",$arr_input);
			$dbh = DB::get_db_writer();
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMemberAgentInfo($arr_input){
		try{
			$arr_agent_info = $this->getMemberAgentInfoById($arr_input);
			$arr_agent_name = array();
			if(!empty($arr_agent_info)){
				$agent_name_hash = $arr_agent_info['name'];
				$agent_id_hash = $arr_agent_info['id'];
				
				$arr_agent_name = array();
				
				if($arr_agent_info['ppath']){
					$arr_agent = explode("," , $arr_agent_info['ppath']);
					
					foreach ($arr_agent as $id){
						unset($arr_input);
						$arr_input['id'] = $id;
						$arr_agent_info = $this->getMemberAgentInfoById($arr_input);
						$arr_agent_name[$id] = $arr_agent_info['name'];
					}
				}
				$arr_agent_name[$agent_id_hash] = $agent_name_hash;
			}
			return $arr_agent_name;
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMemberAgentInfoById($arr_input){//查询ch_agent是否存在
		try{
			$obj_query = new MemberQuery();
			$str_query = $obj_query->getQuery("GET_MEMBER_AGENT_INFO" , $arr_input);
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function modifyMemberSession($arr_input){
		try{
			$obj_query = new MemberQuery();
			$str_query = $obj_query->getQuery("MODIFY_MEMBER_SESSION",$arr_input);
			$dbh = DB::get_db_writer();
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function rememberLogin($arr_input){
		try{
			$result = $this->checkLogin($arr_input);//查询tb_user_session是否存在
			$b_result = false;
			if ($result['uid']) {			//检查有
				$arr_input['logout_status'] = 0;
				$arr_input['uid']=$result['uid'];
				$b_result = $this->modifyMemberSession($arr_input);				//修改表内对应用户的信息
			}else{
				$b_result = $this->addMemberSession($arr_input);				//添加一条新的在线用户信息
				$result = $this->checkLogin($arr_input);						//重新获取用户新生成的信息
			}
			return $result['uid'];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMemberSessionByUid($arr_input){
		try{
			$obj_query = new MemberQuery();
			$str_query = $obj_query->getQuery("GET_MEMBER_SESSION",$arr_input);
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function addMemberSession($arr_input){
		try{
			$obj_query = new MemberQuery();
			$str_query = $obj_query->getQuery("ADD_MEMBER_SESSION",$arr_input);
			$dbh = DB::get_db_writer();
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function checkLogin($arr_input){
		try{
			$obj_query = new MemberQuery();
			$str_query = $obj_query->getQuery("CHECK_LOGIN",$arr_input);
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			$result = $arr_output[0];
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMemberOnlineListCount(){
		try {
			$obj_query = new MemberQuery();
			$str_query = $obj_query->getQuery("GET_MEMBER_ONLINE_LIST_COUNT",$arr_input);
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getMemberOnlineList($arr_input,$limit_query = null){
		try {
			$obj_query = new MemberQuery();
			$str_query = $obj_query->getQuery("GET_MEMBER_ONLINE_LIST",$arr_input);
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
}
?>