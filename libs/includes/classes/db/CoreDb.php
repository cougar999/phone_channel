<?php
require_once(TP_LIB_DIR.'classes/db/CoreMysql.php');

class CoreDb extends CoreMysql{
	var $dbname;
	
	public function __construct($dbname){
		CoreMysql::db_connect($dbname);
	}
	public function __destruct(){
//		CoreMysql::db_close();
	}
}
?>