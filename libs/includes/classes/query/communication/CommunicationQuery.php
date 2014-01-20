<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

/**
* ArticleQuery class
*
* @package query.article.Articlequery
* @version 1.0
* @author jerry <guhao123479@sohu.com>
* @createDate 2009.04.06
* @changeHistory 
*/

class CommunicationQuery extends Query {

	function __construct(){}
	function __destruct(){}
	
	/**
	* This function return a completed query matched with sql_key
	* @param : string $query_key - key to obtain a completed query from QueryClass
	* @param : array $arr_input - input_data to use when you execute a query clause (null value is allowed)
	* @return : string $str_query - a completed query matched with sql_key
	*/
	final function getQuery($query_key, $arr_input=null){
		$str_query = '';
		
		switch ($query_key) {
			
			case "ADD_NOTE":

				$str_query .= "insert into";
				$str_query .= " tb_note";
				$str_query .= " set";
				$str_query .= " create_date = '{$this->currenttime()}'";
				$str_query .= ", content = '{$arr_input['content']}'";
				$str_query .= ", uid = {$arr_input['uid']}";
				$str_query .= ", real_name = '{$arr_input['real_name']}'";
				$str_query .= ", type = 1";
				$str_query .= ", status = 1";
				$str_query .= ", agent_id = '{$arr_input['agent_id']}'";
				
			break;
			
			case "GET_COMMUNICATION_LIST_COUNT":

				$str_query .= "select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " tb_note";
				$str_query .= " where";
				$str_query .= " status = 1";
				$str_query .= " and";
				$str_query .= " agent_id = {$arr_input['agent_id']}";
				
			break;
			
			case "GET_COMMUNICATION_LIST":
				
				$str_query .= "select";
				$str_query .= " nid";
				$str_query .= " , uid";
				$str_query .= " , real_name";
				$str_query .= " , content";
				$str_query .= " , create_date";
				$str_query .= " from";
				$str_query .= " tb_note";
				$str_query .= " where";
				$str_query .= " status = 1";
				$str_query .= " and agent_id = {$arr_input['agent_id']}";
				$str_query .= " order by create_date desc";
				
			break;
				
			case "GET_COMMUNICATION_NOTE";
			    //$str_query="select * from td_note where Nid in(select send_uid from td_send_note where send_uid='' order by Nid desc limit 0,10) order by Rid desc limit 0,10";
				$str_query="select * from tb_note where Nid in(select t.nid from (select * from tb_send_note where send_uid='".$arr_input."' order by Nid desc limit 0,10)as t) order by nid desc limit 0,10";
			break;
			
			case "GET_COMMUNICATION_RECORD";
			
				$str_query="select * from tb_record where Nid in(select t.send_uid from (select * from tb_send_note where send_uid='".$arr_input."' order by Nid desc limit 0,10)as t) order by Rid desc limit 0,10";
			break;
			
			case "ADD_MESSAGE";
			    //$str_query = "INSERT INTO Persons (content,uid,create_date,type,Status) VALUES ('Wilson', 'Champs-Elysees')";
			foreach ($arr_input as $key=>$value){
			$k.=$key;
			$v.="'".$value."'";
				if(end($arr_input)!=$value){
					$k.=',';
					$v.=',';
				}
			
			}
			$str_query="INSERT INTO tb_note (".$k.") VALUES (".$v.")";
			break;
			
			case "SEND_RELATIONS";
			foreach ($arr_input as $key=>$value){
			$k.=$key;
			$v.="'".$value."'";
				if(end($arr_input)!=$value){
					$k.=',';
					$v.=',';
				}
			
			}
			$str_query="INSERT INTO tb_send_note (".$k.") VALUES (".$v.")";
			break;
		}
		
		return $str_query;
	}

}//class
?>