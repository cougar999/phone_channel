<?php
/**
* Query class
*
* @package biz.common.query
* @version 1.0
* @author jerry <guhao123479@sohu.com>
* @createDate 2009.03.22
* @changeHistory 
*/

abstract class Query {

	private $debug_flag='N'; //N -> No, F -> File, W->Web
	private $limit_query;
	private $current_time=0;

	public function __construct(){}
	public function __destruct(){}

	/**
	* This function gets getDebugFlag
	*	@param : 
	*	@return : boolean(if true log write)
	*/
	public function getDebugFlag() {
		return $this->debug_flag;
	}
	public function setDebugFlag($debug_flag) {
		$this->debug_flag=$debug_flag;
	}

	/*public function debugQuery($str_query) {

		if ($this->getDebugFlag() == "F") {
			$instance = CoreLogManager::getInstance();
			@$instance->writeLog("[".__CLASS__."][".$_SERVER['REMOTE_ADDR']."] :: ".$str_query."\n", '/query_debug/', __CLASS__, 'H');
		} elseif ($this->getDebugFlag() == "W") {
			echo $str_query;
			echo "<hr>";
		}
	}*/

	/**
	* This function is a abstract declare about getQuery($query_key, $arr_input=null)
	* @param : string $query_key - key to obtain a completed query from QueryClass
	* @param : array $arr_input - input_data to use when you execute a query clause (null value is allowed)
	*/
	abstract protected function getQuery($query_key, $arr_input=null) ;
	
	/**
	* This function gets DbNum
	*	@param : int $uid - uid
	*	@return : int $db_num - database number 
	*/
	final public function getDbNum($uid) {
		
		if ($uid <= 0) {
			throw new CoreDbException('Query::getDbNum(): uid must be greater than zero');
		}
		
		$db_num  = CoreDbLocator::getDbNum($uid);		
		return $db_num;
	}
	
	public function getLimit($int_start, $int_end = null){

		$str_query = " LIMIT ".$int_start;
		if ($int_end != null) {
			$str_query .= " ,".$int_end;
		}
		return $str_query;
	}

	/**
	* This function setLimitQuery() - set Limit Query
	* @param :  limit query string
	* @return : void
	*/	
	function setLimitQuery($limit_query) {
		$this->limit_query=$limit_query;
	}
	/**
	* This function setLimitQuery() - set Limit Query
	* @param :  limit query string
	* @return : void
	*/		
	function getLimitQuery() {
		return $this->limit_query;
	}

	function makeLimitQuery($str_query) {
		$str_query=$str_query." ".$this->getLimitQuery();
		return $str_query;
	}

	/**
	* This function return datetime string, like "2007-02-07 17:36:01", instead of NOW() and SYSDATE() in SQL
	* @return : datetime string
	*/
	protected function currentTime(){
		if (!$this->current_time) {
//			$this->current_time = gmdate('Y-m-d H:i:s', (time() + (8 * 3600)));
			$this->current_time = time();
		}
		return $this->current_time;
	}

	/**
	* This function return inverse datetime string, like "-20070207173601", for inverse-index
	* @return : inverse datetime string
	*/
	protected function inverseCurrentTime() {
		return '-'.date('YmdHis', strtotime($this->currentTime()));
	}
	
	function lastInsertID() {
		//$str_query = "select LAST_INSERT_ID()";
		//$str_query = mysql_insert_id();
		//return $str_query;
		return false;
	}
}//class
?>