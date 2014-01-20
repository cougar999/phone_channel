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
class ArticleQuery  extends Query {

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
			
			case "ADD_MY_ARTICLE":
			
			$str_query .= "insert into";
			$str_query .= " tb_article";
			$str_query .= " set";
			$str_query .= " create_date='{$this->currenttime()}'";
			$str_query .= ", category={$arr_input['category']}";
			$str_query .= ", title='{$arr_input['title']}'";
			$str_query .= ", description='{$arr_input['description']}'";
			$str_query .= ", content='{$arr_input['content']}'";
			$str_query .= ", authorid='{$arr_input['authorid']}'";
			$str_query .= ", state={$arr_input['state']}";
			$str_query .= ", agent_id={$arr_input['agent_id']}";
				
			break;
			
			case "GET_ARTICLE_LIST_COUNT":
				
			$str_query .= " select";
			$str_query .= " count(*)";
			$str_query .= " from";
			$str_query .= " tb_article";
				
			break;
			
			case "GET_ARTICLE_LIST":
				
			$str_query .= " select";
			$str_query .= " aid";
			$str_query .= ", category";
			$str_query .= ", title";
			$str_query .= ", description";
			$str_query .= ", author";
			$str_query .= ", create_date";
			$str_query .= ", state";
			$str_query .= " from";
			$str_query .= " tb_article";
			$str_query .= " order by create_date desc";
					
			break;
			
			case "GET_OTHER_ARTICLE_LIST_COUNT":
				
				$str_query .= " select";
				$str_query .= " count(*)";
				$str_query .= " from";
				$str_query .= " tb_article";
				$str_query .= " where";
				$str_query .= " state = 1";
				if (array_key_exists('category', $arr_input)) {
					$str_query .= " and category = {$arr_input['category']}";
				}
				if (array_key_exists('authorid', $arr_input)) {
					$str_query .= " and authorid = {$arr_input['authorid']}";
				}
				$str_query .= " and state = 1";
				if (array_key_exists('agent_id', $arr_input) && !empty($arr_input['agent_id'])) {
					$str_query .= " and agent_id = {$arr_input['agent_id']}";
				}
			break;
			
			case "GET_OTHER_ARTICLE_LIST":
				
				$str_query .= " select";
				$str_query .= " aid";			
				$str_query .= ", title";
				$str_query .= ", category";
				$str_query .= ", authorid";
				$str_query .= ", description";
				$str_query .= ", state";
				$str_query .= ", create_date";
				$str_query .= ", read_num";
				$str_query .= ", comment_num";
				$str_query .= ", sharing_num";
				$str_query .= " from";
				$str_query .= " tb_article";
				$str_query .= " where";
				$str_query .= " state = 1";
				if (array_key_exists('category', $arr_input)) {
					$str_query .= " and category = {$arr_input['category']}";
				}
				if (array_key_exists('authorid', $arr_input)) {
				$str_query .= " and authorid = {$arr_input['authorid']}";
				}
				if (array_key_exists('agent_id', $arr_input) && !empty($arr_input['agent_id'])) {
					$str_query .= " and agent_id = {$arr_input['agent_id']}";
				}
				$str_query .= " order by create_date desc";
					
			break;
			
			case "MODIFY_MY_ARTICLE":
			
			$str_query .= "update";	
			$str_query .= " tb_article";	
			$str_query .= " set";	
			$str_query .= " update_date='{$this->currenttime()}'";
			if (array_key_exists('category', $arr_input)) {
				$str_query .= ", category={$arr_input['category']}";
			}
			if (array_key_exists('title', $arr_input)) {
				$str_query .= ", title='{$arr_input['title']}'";
			}
			if (array_key_exists('description', $arr_input)) {
				$str_query .= ", description='{$arr_input['description']}'";
			}
			if (array_key_exists('content', $arr_input)) {
				$str_query .= ", content='{$arr_input['content']}'";
			}
			if (array_key_exists('state', $arr_input)) {
				$str_query .= ", state={$arr_input['state']}";
			}
			$str_query .= " where aid = {$arr_input['aid']}";
			$str_query .= " and authorid = {$arr_input['authorid']}";
			
			break;
			
			case "MODIFY_ARTICLE":
				
			$str_query .= "update";	
			$str_query .= " tb_article";	
			$str_query .= " set";	
			$str_query .= " update_date='{$this->currenttime()}'";
			if (array_key_exists('category', $arr_input)) {
				$str_query .= ", category={$arr_input['category']}";
			}
			if (array_key_exists('title', $arr_input)) {
				$str_query .= ", title='{$arr_input['title']}'";
			}
			if (array_key_exists('description', $arr_input)) {
				$str_query .= ", description='{$arr_input['description']}'";
			}
			if (array_key_exists('content', $arr_input)) {
				$str_query .= ", content='{$arr_input['content']}'";
			}
			if (array_key_exists('state', $arr_input)) {
				$str_query .= ", state={$arr_input['state']}";
			}
			$str_query .= " where aid = {$arr_input['aid']}";
				
			break;
			
			case "VIEW_ARTICLE":
			
			$str_query .= "select";
			$str_query .= " aid";
			$str_query .= ", title";
			$str_query .= ", category";
			$str_query .= ", authorid";
			$str_query .= ", description";
			$str_query .= ", content";
			$str_query .= ", create_date";
			$str_query .= ", read_num";
			$str_query .= ", comment_num";
			$str_query .= ", sharing_num";
			$str_query .= " from";
			$str_query .= " tb_article";
			$str_query .= " where";
			$str_query .= " aid = {$arr_input['aid']}";
			$str_query .= " and state = 1";
				
			break;
			
			case "DELETE_MY_ARTICLE":
				
			$str_query .= "update";
			$str_query .= " tb_article";	
			$str_query .= " set";	
			$str_query .= " state= 0";
			$str_query .= " where aid = {$arr_input['aid']}";
					
			break;
			
			case "ADD_ARTICLE_COMMENT_NUM":
				
			$str_query .= "update";
			$str_query .= " tb_article";	
			$str_query .= " set";	
			$str_query .= " comment_num = comment_num+1";
			$str_query .= " where aid = {$arr_input['aid']}";
					
			break;
			
			case "ADD_ARTICLE_READ_NUM":
				
			$str_query .= "update";
			$str_query .= " tb_article";	
			$str_query .= " set";	
			$str_query .= " read_num = read_num+1";
			$str_query .= " where aid = {$arr_input['aid']}";
					
			break;
			
			case "ADD_ARTICLE_SHARING_NUM":
			
			$str_query .= "update";
			$str_query .= " tb_article";	
			$str_query .= " set";	
			$str_query .= " sharing_num = sharing_num+1";
			$str_query .= " where aid = {$arr_input['aid']}";
					
			break;	
			
		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}
}//class