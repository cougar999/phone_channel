<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

/**
* CommentQuery class
*
* @package query.comment.Commentquery
* @version 1.0
* @author jerry <guhao123479@sohu.com>
* @createDate 2011.11.23
* @changeHistory 
*/
class CommentQuery  extends Query {

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
			
			case "ADD_COMMENT":
			
			$str_query .= "insert into";
			$str_query .= " tb_comment";
			$str_query .= " set";
			$str_query .= " create_date='{$this->currenttime()}'";
			$str_query .= ", content='{$arr_input['content']}'";
			$str_query .= ", authorid={$arr_input['authorid']}";
			$str_query .= ", author_name='{$arr_input['author_name']}'";
			$str_query .= ", state={$arr_input['state']}";
				
			break;
			
			case "ADD_COMMENT_ARTICLE":
			
			$str_query .= "insert into";
			$str_query .= " tb_comment_article";
			$str_query .= " set";
			$str_query .= " cid={$arr_input['cid']}";
			$str_query .= ", aid={$arr_input['aid']}";
				
			break;
			
			case "GET_COMMENT_BY_AID_LIST_COUNT":
				
			$str_query .= " select";
			$str_query .= " count(*)";
			$str_query .= " from";
			$str_query .= " tb_comment_article";
			$str_query .= " where";
			$str_query .= " aid={$arr_input['aid']}";
				
			break;
			
			case "GET_COMMENT_BY_AID_LIST":
				
			$str_query .= " select";
			$str_query .= " aid";
			$str_query .= ", cid";
			$str_query .= " from";
			$str_query .= " tb_comment_article";
			$str_query .= " where";
			$str_query .= " aid={$arr_input['aid']}";
					
			break;
			
			case "GET_COMMENT_LIST":
				
			$str_query .= " select";
			$str_query .= " *";
			$str_query .= " from";
			$str_query .= " tb_comment";
			$str_query .= " where";
			if (is_array($arr_input['cid'])) {
				$str_cid = join("," , $arr_input['cid']);
				$str_query .= " cid in ({$str_cid})";
			}else{
				$str_query .= " cid={$arr_input['aid']}";
			}
			$str_query .=" order by create_date desc";
					
			break;
			
			case "DELETE_COMMENT":
				
			$str_query .= "delete";
			$str_query .= " form";
			$str_query .= " tb_article";	
			$str_query .= " set";
			$str_query .= " where cid = {$arr_input['cid']}";
					
			break;
			
		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}
}//class