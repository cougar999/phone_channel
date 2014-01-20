<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/comment/CommentQuery.php');

class Comment{
	public function __construct(){
	}
	
	public function __destruct(){
	}
	
	
	
	public function addComment($arr_input){
		try {
			$obj_query = new CommentQuery();
			$str_query = $obj_query->getQuery("ADD_COMMENT",$arr_input);
			
			$dbh = DB::get_db_writer();
			$result = DB::executeQuery($str_query);
			if ($result) {
				$aid = $arr_input['aid'];
				unset($arr_input);
				//$arr_input['cid'] = $this->selectInsertID();
				$arr_input['cid'] = DB::getInsertId();
				$arr_input['aid'] = $aid;
				
				return $this->addCommentArticle($arr_input);
			}else{
				return false;
			}
			
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	private function addCommentArticle($arr_input){
		try {
			$obj_query = new CommentQuery();
			$str_query = $obj_query->getQuery("ADD_COMMENT_ARTICLE",$arr_input);
			$dbh = DB::get_db_writer();
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getCommentByAidListCount($arr_input){
		try {
			$obj_query = new CommentQuery();
			$str_query = $obj_query->getQuery("GET_COMMENT_BY_AID_LIST_COUNT",$arr_input);
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getCommentByAidList($arr_input){
		try {
			$obj_query = new CommentQuery();
			$str_query = $obj_query->getQuery("GET_COMMENT_BY_AID_LIST",$arr_input);
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			$arr_cid_hash = array();
			foreach ($arr_output as $row){
				$arr_cid_hash[] = $row['cid'];
			}
			unset($arr_input);
			$arr_input['cid'] = $arr_cid_hash;
			$str_query = $obj_query->getQuery("GET_COMMENT_LIST",$arr_input);
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function deleteComment($arr_input){}
	
	private function selectInsertID(){
		try {
			$obj_query = new CommentQuery();
			$str_query = $obj_query->lastInsertID();
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			$e->getMessage();
		}
	}
}
?>