<?php
require_once(TP_LIB_DIR.'classes/db/DB.php');
require_once(TP_LIB_DIR.'classes/query/intergral/IntergralRuleQuery.php');

/*
* IntergralRule class
*
* @package biz.intergral.IntergralRule
* @version 1.0.1
* @author jerry<guhao123479@sohu.com>
* @createDate 2012.01.14
* @changeHistory 
*/

class IntergralRule{
	
	public function __construct(){
		$this->dbkey = "stat";
	}
	
	public function __destruct(){
	}

	/**
	 * @description :每天登录+10
	 * @param : array  $arr_input
	 * seller_id					//店员id
	 * login_day					//今天时间
	 * @return :int intergral
	 */
	public function everyDayLogin($arr_input){
		try {
			$obj_query = new IntergralRuleQuery();
			$str_query = $obj_query->getQuery("GET_EVERYDAY_LOGIN" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			//取出登录总次数乘以10
			$intergral = $arr_output[0]['login_day_sum']*10;
			
			return $intergral;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	/**
	 * @description :连续三天登录+10
	 * @param : array  $arr_input - 
	 * @return :boolean(true,false)
	 */
	public function threeConsecutiveDayLogin($arr_input){
		try {
			$obj_query = new IntergralRuleQuery();
			$str_query = $obj_query->getQuery("GET_EVERYDAY_LOGIN_LIST" , $arr_input);
			
			$dbh = DB::get_db_reader($this->dbkey);
			$arr_output = DB::selectQuery($str_query);
			
			foreach ($arr_output as $row){
				$str_day = $row['login_day'];
			}
			for($i = 0;$i < count($arr_output);$i++){
				$str_current_day = $arr_output[$i]['login_day'];
				$str_next_day = $arr_out[$i+1]['login_day'];
				
			}
			//取出登录总次数乘以10
			$intergral = $arr_output[0]['login_day_sum']*10;
			
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	/**
	 * @description :连接手机+5
	 * @param : array  $arr_input - 
	 * @return :boolean(true,false)
	 */
	public function contentPhone($arr_input){
		
	}
	
	/**
	 * @description :每天成功下载并安装10个以上资源 +10
	 * @param : array  $arr_input - 
	 * @return :boolean(true,false)
	 */
	public function downloadInstall($arr_input){
		
	}
	
	/**
	 * @description :本店上传每个视频+5
	 * @param : array  $arr_input - 
	 * @return :boolean(true,false)
	 */
	public function zoneUploadVideo($arr_input){
		
	}
	
	/**
	 * @description :本店上传每首音乐+2
	 * @param : array  $arr_input - 
	 * @return :boolean(true,false)
	 */
	public function zoneUploadMuisc($arr_input){
		
	}
	
	/**
	 * @description :激活每个下载卡(季卡+20、半年卡+30、年卡+50、两年卡+100)
	 * @param : array  $arr_input - 
	 * @return :boolean(true,false)
	 */
	public function activeCard($arr_input){
		
	}
	
	/**
	 * @description :手机快速注册一个会员
	 * @param : array  $arr_input - 
	 * @return :boolean(true,false)
	 */
	public function fastRegisterMemberByPhone(){
		
	}
}
?>