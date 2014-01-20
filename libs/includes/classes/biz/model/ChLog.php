<?php
class ChLog {
	private $_dbr;
	public function __construct($dbKey = 'member'){
		session_start();
		DB::get_db_reader($dbKey);
	}
	
	public function __destruct(){
	
	}
	
	public function __get($name)
	{
		
	}
	
	public function __set($name,$parameters)
	{
		
	}
	
	public function addLog($pra){
		$sql = 'insert into channel.ch_log_app set ';
		$sql.= "log_uid = '{$_SESSION['real_id']}',
				log_level = '".intval($_SESSION['level'])."',
				log_ip = '".get_real_ip()."',
				log_time  = now()";
		foreach($pra as $k=>$v){
			$sql .= ",{$k}='".addslashes($v)."'";
		}
		DB::executeQuery($sql);
		return (int)DB::getInsertId();
	}
}