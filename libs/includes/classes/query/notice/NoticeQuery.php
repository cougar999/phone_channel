<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

class NoticeQuery extends Query {

	function __construct(){}
	function __destruct(){}
	

	final function getQuery($query_key, $arr_input=null){
		$str_query = '';
		
		switch ($query_key){
			case "GET_NOTICE_LIST_COUNT":
			
			$str_query .= "select";
			$str_query .= " count(*)";
			$str_query .= " from";
			$str_query .= " tb_common_note";
			$str_query .= " where";
			$str_query .= " type = 1";
			
			break;
			
			case "ADD_NOTICE":
			
			$str_query .= "insert into";
			$str_query .= " tb_common_note";
			$str_query .= " set";
			$str_query .= " create_date = '{$this->currenttime()}'";
			$str_query .= " , uid = {$arr_input['uid']}";
			$str_query .= " , content = '{$arr_input['content']}'";
			$str_query .= " , type = {$arr_input['type']}";
			
			break;
			
			case "GET_NOTICE_LIST":
			
			$str_query .= "select";
			$str_query .= " cnid";
			$str_query .= " ,content";
			$str_query .= " ,create_date";
			$str_query .= " from";
			$str_query .= " tb_common_note";
			$str_query .= " where";
			$str_query .= " type = 1";
			$str_query .= " order by create_date desc";
			
			break;
			
			case "DELETE_NOTICE":
			
			$str_query .= "delete";
			$str_query .= " from";
			$str_query .= " tb_common_note";
			$str_query .= " where";
			$str_query .= " cnid = {$arr_input['cnid']}";
				
			break;
			
		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}

}
?>
