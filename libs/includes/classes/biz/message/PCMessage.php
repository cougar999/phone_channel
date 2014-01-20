<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/message/PCMessageQuery.php');

/*
* PCMessage class
*
* @package biz.message.PCMessage
* @version 1.0.1
* @author jerry<guhao123479@sohu.com>
* @createDate 2012.09.17
* @changeHistory 
*/

class PCMessage{
	
	public function __construct(){
		$this->dbkey = "member";
	}
	
	public function __destruct(){
	}
	
	public function checkMessHtml($arr_input){
		try {
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("CHECK_MESS_HTML" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}

	public function getPCMessageValidListCount($arr_input){
		try {
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("GET_PCMESSAGE_VALID_LIST_COUNT" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getPCMessageValidList($arr_input ,$limit_query = null){
		try {
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("GET_PCMESSAGE_VALID_LIST" , $arr_input);
			
			if ($limit_query != null) {
				$obj_query->setLimitQuery($limit_query);
				$str_query = $obj_query->makeLimitQuery($str_query);
			}
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQueryAssoc($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getPCMessageOverListCount($arr_input){
		try {
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("GET_PCMESSAGE_OVER_LIST_COUNT" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			return $arr_output[0][0];
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getPCMessageOverList($arr_input ,$limit_query = null){
		try {
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("GET_PCMESSAGE_OVER_LIST" , $arr_input);
			
			if ($limit_query != null) {
				$obj_query->setLimitQuery($limit_query);
				$str_query = $obj_query->makeLimitQuery($str_query);
			}
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQueryAssoc($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function addPCMessage($arr_input){
		try{
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("ADD_PCMESSAGE",$arr_input);
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function modifyPCMessage($arr_input){
		try{
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("MODIFY_PCMESSAGE",$arr_input);
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function delPCMessage($arr_input){
		try{
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("DELETE_PCMESSAGE",$arr_input);
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function viewPCMessage($arr_input){
		try{
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("VIEW_PCMESSAGE" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQueryAssoc($str_query);
			
			return $arr_output[0];
			
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function addLinkMsgChannel($arr_input){
		try{
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("ADD_LINK_MSG_CHANNEL",$arr_input);
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getLinkMsgChannelList($arr_input){
		try {
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("GET_LINK_MSG_CHANNEL_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQueryAssoc($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function delLinkMsgChannel($arr_input){
		try{
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("DELETE_LINK_MSG_CHANNEL",$arr_input);
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function addLinkMsgArea($arr_input){
		try{
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("ADD_LINK_MSG_AREA",$arr_input);
			$dbh = DB::get_db_writer($this->dbkey);
			$result = DB::executeQuery($str_query);
			
			return $result;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getLinkMsgAreaList($arr_input){
		try {
			$obj_query = new PCMessageQuery();
			$str_query = $obj_query->getQuery("GET_LINK_MSG_AREA_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQueryAssoc($str_query);
			
			return $arr_output;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function create_html($html_content,$url){
		mkdirs($url);
		$string = file_get_contents(TP_TPL_DIR.'pcmessage_demo.html');
		$patterns = array('/<style type="text\/css">[\s\S]*?<\/style>/','/<div class="wd_tit">[\s\S]*?<div class="wd_text">/');
		$replace = array('','<div class="wd_text">');
		$html_content = preg_replace($patterns , $replace , $html_content);
		$patterns = '/<a href="(.*?)">(.*?)<\/a>/';
		$replace = '<a href="\1" class="openlink" target="_blank">\2</a>';
		$html_content = preg_replace($patterns , $replace , $html_content);
		$html_content = str_replace('{page_html}',$html_content,$string);
		file_put_contents($url,$html_content);
	}
}
?>