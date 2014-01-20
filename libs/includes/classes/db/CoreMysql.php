<?php

class CoreMysql{
	
	var $hostname;
	var $username;
	var $password;
	var $charset = 'utf8';
	var $database;
	private $db;
	
	public function db_connect($dbname){
		include(TP_APP_DIR."/configs/database.php");

		if (is_array($db_config)&& !empty($dbname))
		{
			foreach ($db_config[$dbname] as $key => $val)
			{
				$this->$key = $val;
			}
		}
		$db = @mysql_connect($this->hostname,$this->username,$this->password);
		@mysql_unbuffered_query("SET NAMES '".$this->charset."'");
		if (!$db)
		{
		   die('<b>数据库连接失败！</b>');
		   exit;
		}
		return $db;
	}
	
	public function db_close(){
		return @mysql_close();
	}
	
	public function db_select(){
		return mysql_select_db($this->database);
	}
	
	public function db_query($str_query){
		return mysql_query($str_query);
	}
	
	public function db_fetch_array($result){
		return mysql_fetch_array($result);
	}
	public function db_fetch_assoc($result){
		return mysql_fetch_assoc($result);
	}
	public function db_error(){
		return mysql_error();
	}
	
	public function db_insert_id(){
		return mysql_insert_id();
	}
	
}
?>