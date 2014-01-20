<?php
/**
//     * 开始一个事务.
//     */
//    public function begin(){
//        mysql_query('begin');
//    }
//
//    /**
//     * 提交一个事务.
//     */
//    public function commit(){
//        mysql_query('commit');
//    }
//
//    /**
//     * 回滚一个事务.
//     */
//    public function rollback(){
//        mysql_query('rollback');
//    }

require_once(TP_LIB_DIR.'classes/db/DB.php');
//require_once(TP_LIB_DIR.'classes/query/article/ArticleQuery.php');

/*
* Article class
*
* @package biz.article.Article
* @version 1.0
* @author jerry<guhao123479@sohu.com>
* @createDate 2009.03.27
* @changeHistory 
*/

class Score {

	public function __construct(){
	}
	
	public function __destruct(){
	}
	
	/**
	 * @description :this function is add article
	 * @param : array  $arr_input - aid ,category ,title ,content ,author ,state
	 * @return :boolean(true,false)
	 */
	public function addMyArticle($arr_input){
		try {
			$obj_query = new ArticleQuery();
			$str_query = $obj_query->getQuery("ADD_MY_ARTICLE",$arr_input);
			
			$dbh = DB::get_db_writer();
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	/**
	 * @description: this function is get article list count
	 * @param : array $arr_input
	 * @return :$total_cnt
	 * 
	 */
	public function getArticleListCount(){
		try {
			$str_query  = 'select id from tb_u_score_details limit 10';
			$dbh 		= DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return json_encode($arr_output);
			
		}catch (Exception $e){
			echo false;
		}
	}
	
	/**
	 * @description: this function is get article list
	 * @param : array $arr_input ,$limit_query
	 * @return :array $arr_output
	 * 				aid			[0]
	 * 				category	[1]
	 * 				title		[2]
	 * 				author		[3]
	 * 				creat_date	[4]
	 * 				state		[5]
	 * 
	 */
	public function getArticleList($arr_input ,$limit_query = null){
		try {
			$obj_query = new ArticleQuery();
			$str_query = $obj_query->getQuery("GET_ARTICLE_LIST",$arr_input);
			
			if ($limit_query != null) {
				$obj_query->setLimitQuery($limit_query);
				$str_query = $obj_query->makeLimitQuery($str_query);
			}
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo "false";
		}
	}
	
	/**
	 * @description: this function is get title for article list
	 * @param : array $arr_input - category
	 * 				$limit_query
	 * @return :array $arr_output
	 * 					aid		[0]
	 * 					title	[1]
	 */
	public function getArticleTitleList($arr_input ,$limit_query = null){
		try {
			$obj_query = new ArticleQuery();
			$str_query = $obj_query->getQuery("GET_ARTICLE_TITLE_LIST",$arr_input);
			
			if ($limit_query != null) {
				$obj_query->setLimitQuery($limit_query);
				$str_query = $obj_query->makeLimitQuery($str_query);
			}
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo "false";
		}
	}
	
	public function getOtherArticleListCount($arr_input){
		try {
			$obj_query = new ArticleQuery();
			$str_query = $obj_query->getQuery("GET_OTHER_ARTICLE_LIST_COUNT",$arr_input);

			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo false;
		}
	}
	
	public function getOtherArticleList($arr_input , $limit_query = null){
		try {
			$obj_query = new ArticleQuery();
			$str_query = $obj_query->getQuery("GET_OTHER_ARTICLE_LIST",$arr_input);
			
			if ($limit_query != null) {
				$obj_query->setLimitQuery($limit_query);
				$str_query = $obj_query->makeLimitQuery($str_query);
			}
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo "false";
		}
	}
	
	/**
	 * @description: this function is get article list
	 * @param : array $arr_input
	 * @return :array $arr_output
	 * 				category	[0]
	 * 				title		[1]
	 * 				content		[2]
	 * 				author		[3]
	 * 				state		[4]
	 * 
	 */
	public function viewArticle($arr_input){
		try {
			$obj_query = new ArticleQuery();
			$str_query = $obj_query->getQuery("VIEW_ARTICLE",$arr_input);
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0];
			
		}catch (Exception $e){
			echo "false";
		}
	}
	/**
	 * @description: this function is get article list
	 * @param : array $arr_input - category
	 * @return :array $arr_output
	 * 				category	[0]
	 * 				title		[1]
	 * 				content		[2]
	 * 				author		[3]
	 * 				state		[4]
	 * 
	 */
	public function viewHRArticle($arr_input){
		try {
			$obj_query = new ArticleQuery();
			$str_query = $obj_query->getQuery("VIEW_HRARTICLE",$arr_input);
			
			$dbh = DB::get_db_reader();
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo "false";
		}
	}	
	/**
	 * @description : this function is delete article
	 * @param array $arr_input - aid
	 * @return boolean(true,false)
	 */
	public function deleteMyArticle($arr_input){
		$obj_query = new ArticleQuery();
		$str_query = $obj_query->getQuery("DELETE_MY_ARTICLE",$arr_input);
		
		$dbh = DB::get_db_writer();
		$result = DB::executeQuery($str_query);
		
		return $result;
	}
	
	public function addArticleCommentNum($arr_input){
		$obj_query = new ArticleQuery();
		$str_query = $obj_query->getQuery("ADD_ARTICLE_COMMENT_NUM",$arr_input);
		
		$dbh = DB::get_db_writer();
		$result = DB::executeQuery($str_query);
		
		return $result;
	}
	
	public function addArticleReadNum($arr_input){
		$obj_query = new ArticleQuery();
		$str_query = $obj_query->getQuery("ADD_ARTICLE_READ_NUM",$arr_input);
		
		$dbh = DB::get_db_writer();
		$result = DB::executeQuery($str_query);
		
		return $result;
	}
	
	public function addArticleSharingNum($arr_input){
		$obj_query = new ArticleQuery();
		$str_query = $obj_query->getQuery("ADD_ARTICLE_SHARING_NUM",$arr_input);
		
		$dbh = DB::get_db_writer();
		$result = DB::executeQuery($str_query);
		
		return $result;
	}
	
	/**
	 * @description : this function is modify article
	 * @param array $arr_input - aid, category,title,content,author,state
	 * @return boolean(true,false)
	 */
	public function modifyMyArticle($arr_input){
		$obj_query = new ArticleQuery();
		$str_query = $obj_query->getQuery("MODIFY_MY_ARTICLE",$arr_input);
		
		$dbh = DB::get_db_writer();
		$result = DB::executeQuery($str_query);
		
		return $result;
	}
	
	public function modifyAdminArticle($arr_input){
		$obj_query = new ArticleQuery();
		$str_query = $obj_query->getQuery("MODIFY_ARTICLE",$arr_input);
		
		$dbh = DB::get_db_writer();
		$result = DB::executeQuery($str_query);
		
		return $result;
	}
}