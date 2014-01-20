<?php
require_once(TP_LIB_DIR.'classes/biz/common/query/Query.php');

/**
* StatisticQuery class
*
* @package query.statistic.StatisticQuery
* @version 1.0
* @author jerry <guhao123479@sohu.com>
* @createDate 2011.11.23
* @changeHistory 
*/
class StatisticQuery  extends Query {

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
			
			case "GET_SEARCH_DAY_SELLER_INCOME_LIST":
			
			$str_query .= "select";
			$str_query .= " sell_day";
			$str_query .= " , income_total";
			$str_query .= " , content_income_total";
			$str_query .= " , servicefee_income_total";
			$str_query .= " , soft_income";
			$str_query .= " , game_income";
			$str_query .= " , music_income";
			$str_query .= " , ebook_income";
			$str_query .= " , local_music_income";
			$str_query .= " , local_video_income";
			$str_query .= " , soft_servicefee";
			$str_query .= " , game_servicefee";
			$str_query .= " , music_servicefee";
			$str_query .= " , ebook_servicefee";
			$str_query .= " , local_music_servicefee";
			$str_query .= " , local_video_servicefee";
			$str_query .= " from";
			$str_query .= " stat_day_seller_income";
			$str_query .= " where";
			$str_query .= " seller_id = {$arr_input['seller_id']}";
			$str_query .= " and sell_day >= '{$arr_input['start_time']}'";
			$str_query .= " and sell_day <= '{$arr_input['end_time']}'";

			break;

			case "GET_DAY_SELLER_INCOME_LIST":

			$str_query .= "select";
			$str_query .= " sell_day";
			$str_query .= " , income_total";
			$str_query .= " , content_income_total";
			$str_query .= " , servicefee_income_total";
			$str_query .= " , soft_income";
			$str_query .= " , game_income";
			$str_query .= " , music_income";
			$str_query .= " , ebook_income";
			$str_query .= " , local_music_income";
			$str_query .= " , local_video_income";
			$str_query .= " , soft_servicefee";
			$str_query .= " , game_servicefee";
			$str_query .= " , music_servicefee";
			$str_query .= " , ebook_servicefee";
			$str_query .= " , local_music_servicefee";
			$str_query .= " , local_video_servicefee";
			$str_query .= " from";
			$str_query .= " stat_day_seller_income";
			$str_query .= " where";
			$str_query .= " seller_id = {$arr_input['seller_id']}";
			$str_query .= " and sell_day = '{$arr_input['sell_day']}'";

			break;

			case "GET_MONTH_SELLER_INCOME_LIST":
			
			$str_query .= "select";
			$str_query .= " sell_month";
			$str_query .= " , income_total";
			$str_query .= " , content_income_total";
			$str_query .= " , servicefee_income_total";
			$str_query .= " , soft_income";
			$str_query .= " , game_income";
			$str_query .= " , music_income";
			$str_query .= " , ebook_income";
			$str_query .= " , local_music_income";
			$str_query .= " , local_video_income";
			$str_query .= " , soft_servicefee";
			$str_query .= " , game_servicefee";
			$str_query .= " , music_servicefee";
			$str_query .= " , ebook_servicefee";
			$str_query .= " , local_music_servicefee";
			$str_query .= " , local_video_servicefee";
			$str_query .= " from";
			$str_query .= " stat_month_seller_income";
			$str_query .= " where";
			$str_query .= " seller_id = {$arr_input['seller_id']}";
			$str_query .= " and sell_month = '{$arr_input['sell_month']}'";

			break;

			case "GET_SEARCH_DAY_SELLER_DOWNLOAD_LIST":

			$str_query .= "select";
			$str_query .= " download_day";
			$str_query .= " , total_download";
			$str_query .= " , free_download";
			$str_query .= " , pay_download";
			$str_query .= " , soft_free_download";
			$str_query .= " , soft_pay_download";
			$str_query .= " , game_free_download";
			$str_query .= " , game_pay_download";
			$str_query .= " , music_free_download";
			$str_query .= " , music_pay_download";
			$str_query .= " , ebook_free_download";
			$str_query .= " , ebook_pay_download";
			$str_query .= " , local_music_free_download";
			$str_query .= " , local_music_pay_download";
			$str_query .= " , local_video_free_download";
			$str_query .= " , local_video_pay_download";
			$str_query .= " from";
			$str_query .= " stat_day_seller_download";
			$str_query .= " where";
			$str_query .= " seller_id = {$arr_input['seller_id']}";
			$str_query .= " and download_day >= '{$arr_input['start_time']}'";
			$str_query .= " and download_day <= '{$arr_input['end_time']}'";

			break;

			case "GET_MONTH_SELLER_DOWNLOAD_LIST":

			$str_query .= "select";
			$str_query .= " download_month";
			$str_query .= " , total_download";
			$str_query .= " , free_download";
			$str_query .= " , pay_download";
			$str_query .= " , soft_free_download";
			$str_query .= " , soft_pay_download";
			$str_query .= " , game_free_download";
			$str_query .= " , game_pay_download";
			$str_query .= " , music_free_download";
			$str_query .= " , music_pay_download";
			$str_query .= " , ebook_free_download";
			$str_query .= " , ebook_pay_download";
			$str_query .= " , local_music_free_download";
			$str_query .= " , local_music_pay_download";
			$str_query .= " , local_video_free_download";
			$str_query .= " , local_video_pay_download";
			$str_query .= " from";
			$str_query .= " stat_month_seller_download";
			$str_query .= " where";
			$str_query .= " seller_id = {$arr_input['seller_id']}";
			$str_query .= " and download_month = '{$arr_input['download_month']}'";

			break;

			case "GET_DAY_SELLER_DOWNLOAD_LIST":
				
			$str_query .= "select";
			$str_query .= " download_day";
			$str_query .= " , total_download";
			$str_query .= " , free_download";
			$str_query .= " , pay_download";
			$str_query .= " , soft_free_download";
			$str_query .= " , soft_pay_download";
			$str_query .= " , game_free_download";
			$str_query .= " , game_pay_download";
			$str_query .= " , music_free_download";
			$str_query .= " , music_pay_download";
			$str_query .= " , ebook_free_download";
			$str_query .= " , ebook_pay_download";
			$str_query .= " , local_music_free_download";
			$str_query .= " , local_music_pay_download";
			$str_query .= " , local_video_free_download";
			$str_query .= " , local_video_pay_download";
			$str_query .= " from";
			$str_query .= " stat_day_seller_download";
			$str_query .= " where";
			$str_query .= " seller_id = {$arr_input['seller_id']}";
			$str_query .= " and download_day = '{$arr_input['download_day']}'";

			break;

			case "GET_SEARCH_DAY_SELLER_CARD_LIST":

			$str_query .= "select";
			$str_query .= " active_day";
			$str_query .= " , total_price";
			$str_query .= " , total_count";
			$str_query .= " , quarter_card_total";
			$str_query .= " , quarter_card_count";
			$str_query .= " , half_year_card_total";
			$str_query .= " , half_year_card_count";
			$str_query .= " , year_card_total";
			$str_query .= " , year_card_count";
			$str_query .= " , two_year_card_total";
			$str_query .= " , two_year_card_count";
			$str_query .= " from";
			$str_query .= " stat_day_seller_card_new";
			$str_query .= " where";
			$str_query .= " seller_id = {$arr_input['seller_id']}";
			$str_query .= " and active_day >= '{$arr_input['start_time']}'";
			$str_query .= " and active_day <= '{$arr_input['end_time']}'";

			break;

			case "GET_MONTH_SELLER_CARD_LIST":

			$str_query .= "select";
			$str_query .= " sell_month";
			$str_query .= " , total_price";
			$str_query .= " , total_count";
			$str_query .= " , quarter_card_total";
			$str_query .= " , quarter_card_count";
			$str_query .= " , half_year_card_total";
			$str_query .= " , half_year_card_count";
			$str_query .= " , year_card_total";
			$str_query .= " , year_card_count";
			$str_query .= " , two_year_card_total";
			$str_query .= " , two_year_card_count";
			$str_query .= " from";
			$str_query .= " stat_month_seller_card_new";
			$str_query .= " where";
			$str_query .= " seller_id = {$arr_input['seller_id']}";
			$str_query .= " and sell_month = '{$arr_input['sell_month']}'";

			break;

			case "GET_DAY_SELLER_CARD_LIST":

			$str_query .= "select";
			$str_query .= " active_day";
			$str_query .= " , total_price";
			$str_query .= " , total_count";
			$str_query .= " , quarter_card_total";
			$str_query .= " , quarter_card_count";
			$str_query .= " , half_year_card_total";
			$str_query .= " , half_year_card_count";
			$str_query .= " , year_card_total";
			$str_query .= " , year_card_count";
			$str_query .= " , two_year_card_total";
			$str_query .= " , two_year_card_count";
			$str_query .= " from";
			$str_query .= " stat_day_seller_card_new";
			$str_query .= " where";
			$str_query .= " seller_id = {$arr_input['seller_id']}";
			$str_query .= " and active_day = '{$arr_input['active_day']}'";

			break;
			
			case "GET_SEARCH_DAY_CHANNEL_INCOME_LIST":
			
			$str_query .= "select";
			$str_query .= " sell_day";
			$str_query .= " , income_total";
			$str_query .= " , content_income_total";
			$str_query .= " , servicefee_income_total";
			$str_query .= " , soft_income";
			$str_query .= " , game_income";
			$str_query .= " , music_income";
			$str_query .= " , ebook_income";
			$str_query .= " , local_music_income";
			$str_query .= " , local_video_income";
			$str_query .= " , soft_servicefee";
			$str_query .= " , game_servicefee";
			$str_query .= " , music_servicefee";
			$str_query .= " , ebook_servicefee";
			$str_query .= " , local_music_servicefee";
			$str_query .= " , local_video_servicefee";
			$str_query .= " from";
			$str_query .= " stat_day_channel_income";
			$str_query .= " where";
			$str_query .= " channel_id = {$arr_input['channel_id']}";
			$str_query .= " and sell_day >= '{$arr_input['start_time']}'";
			$str_query .= " and sell_day <= '{$arr_input['end_time']}'";

			break;

			case "GET_DAY_CHANNEL_INCOME_LIST":

			$str_query .= "select";
			$str_query .= " sell_day";
			$str_query .= " , income_total";
			$str_query .= " , content_income_total";
			$str_query .= " , servicefee_income_total";
			$str_query .= " , soft_income";
			$str_query .= " , game_income";
			$str_query .= " , music_income";
			$str_query .= " , ebook_income";
			$str_query .= " , local_music_income";
			$str_query .= " , local_video_income";
			$str_query .= " , soft_servicefee";
			$str_query .= " , game_servicefee";
			$str_query .= " , music_servicefee";
			$str_query .= " , ebook_servicefee";
			$str_query .= " , local_music_servicefee";
			$str_query .= " , local_video_servicefee";
			$str_query .= " from";
			$str_query .= " stat_day_channel_income";
			$str_query .= " where";
			$str_query .= " channel_id = {$arr_input['channel_id']}";
			$str_query .= " and sell_day = '{$arr_input['sell_day']}'";

			break;

			case "GET_MONTH_CHANNEL_INCOME_LIST":
			
			$str_query .= "select";
			$str_query .= " sell_month";
			$str_query .= " , income_total";
			$str_query .= " , content_income_total";
			$str_query .= " , servicefee_income_total";
			$str_query .= " , soft_income";
			$str_query .= " , game_income";
			$str_query .= " , music_income";
			$str_query .= " , ebook_income";
			$str_query .= " , local_music_income";
			$str_query .= " , local_video_income";
			$str_query .= " , soft_servicefee";
			$str_query .= " , game_servicefee";
			$str_query .= " , music_servicefee";
			$str_query .= " , ebook_servicefee";
			$str_query .= " , local_music_servicefee";
			$str_query .= " , local_video_servicefee";
			$str_query .= " from";
			$str_query .= " stat_month_channel_income";
			$str_query .= " where";
			$str_query .= " channel_id = {$arr_input['channel_id']}";
			$str_query .= " and sell_month = '{$arr_input['sell_month']}'";

			break;

			case "GET_SEARCH_DAY_CHANNEL_DOWNLOAD_LIST":

			$str_query .= "select";
			$str_query .= " download_day";
			$str_query .= " , total_download";
			$str_query .= " , free_download";
			$str_query .= " , pay_download";
			$str_query .= " , soft_free_download";
			$str_query .= " , soft_pay_download";
			$str_query .= " , game_free_download";
			$str_query .= " , game_pay_download";
			$str_query .= " , music_free_download";
			$str_query .= " , music_pay_download";
			$str_query .= " , ebook_free_download";
			$str_query .= " , ebook_pay_download";
			$str_query .= " , local_music_free_download";
			$str_query .= " , local_music_pay_download";
			$str_query .= " , local_video_free_download";
			$str_query .= " , local_video_pay_download";
			$str_query .= " from";
			$str_query .= " stat_day_channel_download";
			$str_query .= " where";
			$str_query .= " channel_id = {$arr_input['channel_id']}";
			$str_query .= " and download_day >= '{$arr_input['start_time']}'";
			$str_query .= " and download_day <= '{$arr_input['end_time']}'";

			break;

			case "GET_MONTH_CHANNEL_DOWNLOAD_LIST":

			$str_query .= "select";
			$str_query .= " download_month";
			$str_query .= " , total_download";
			$str_query .= " , free_download";
			$str_query .= " , pay_download";
			$str_query .= " , soft_free_download";
			$str_query .= " , soft_pay_download";
			$str_query .= " , game_free_download";
			$str_query .= " , game_pay_download";
			$str_query .= " , music_free_download";
			$str_query .= " , music_pay_download";
			$str_query .= " , ebook_free_download";
			$str_query .= " , ebook_pay_download";
			$str_query .= " , local_music_free_download";
			$str_query .= " , local_music_pay_download";
			$str_query .= " , local_video_free_download";
			$str_query .= " , local_video_pay_download";
			$str_query .= " from";
			$str_query .= " stat_month_channel_download";
			$str_query .= " where";
			$str_query .= " channel_id = {$arr_input['channel_id']}";
			$str_query .= " and download_month = '{$arr_input['download_month']}'";

			break;

			case "GET_DAY_CHANNEL_DOWNLOAD_LIST":
				
			$str_query .= "select";
			$str_query .= " download_day";
			$str_query .= " , total_download";
			$str_query .= " , free_download";
			$str_query .= " , pay_download";
			$str_query .= " , soft_free_download";
			$str_query .= " , soft_pay_download";
			$str_query .= " , game_free_download";
			$str_query .= " , game_pay_download";
			$str_query .= " , music_free_download";
			$str_query .= " , music_pay_download";
			$str_query .= " , ebook_free_download";
			$str_query .= " , ebook_pay_download";
			$str_query .= " , local_music_free_download";
			$str_query .= " , local_music_pay_download";
			$str_query .= " , local_video_free_download";
			$str_query .= " , local_video_pay_download";
			$str_query .= " from";
			$str_query .= " stat_day_channel_download";
			$str_query .= " where";
			$str_query .= " channel_id = {$arr_input['channel_id']}";
			$str_query .= " and download_day = '{$arr_input['download_day']}'";

			break;

			case "GET_SEARCH_DAY_CHANNEL_CARD_LIST":

			$str_query .= "select";
			$str_query .= " active_day";
			$str_query .= " , total_price";
			$str_query .= " , total_count";
			$str_query .= " , quarter_card_total";
			$str_query .= " , quarter_card_count";
			$str_query .= " , half_year_card_total";
			$str_query .= " , half_year_card_count";
			$str_query .= " , year_card_total";
			$str_query .= " , year_card_count";
			$str_query .= " , two_year_card_total";
			$str_query .= " , two_year_card_count";
			$str_query .= " from";
			$str_query .= " stat_day_channel_card_new";
			$str_query .= " where";
			$str_query .= " channel_id = {$arr_input['channel_id']}";
			$str_query .= " and active_day >= '{$arr_input['start_time']}'";
			$str_query .= " and active_day <= '{$arr_input['end_time']}'";

			break;

			case "GET_MONTH_CHANNEL_CARD_LIST":

			$str_query .= "select";
			$str_query .= " sell_month";
			$str_query .= " , total_price";
			$str_query .= " , total_count";
			$str_query .= " , quarter_card_total";
			$str_query .= " , quarter_card_count";
			$str_query .= " , half_year_card_total";
			$str_query .= " , half_year_card_count";
			$str_query .= " , year_card_total";
			$str_query .= " , year_card_count";
			$str_query .= " , two_year_card_total";
			$str_query .= " , two_year_card_count";
			$str_query .= " from";
			$str_query .= " stat_month_channel_card_new";
			$str_query .= " where";
			$str_query .= " channel_id = {$arr_input['channel_id']}";
			$str_query .= " and sell_month = '{$arr_input['sell_month']}'";

			break;

			case "GET_DAY_CHANNEL_CARD_LIST":

			$str_query .= "select";
			$str_query .= " active_day";
			$str_query .= " , total_price";
			$str_query .= " , total_count";
			$str_query .= " , quarter_card_total";
			$str_query .= " , quarter_card_count";
			$str_query .= " , half_year_card_total";
			$str_query .= " , half_year_card_count";
			$str_query .= " , year_card_total";
			$str_query .= " , year_card_count";
			$str_query .= " , two_year_card_total";
			$str_query .= " , two_year_card_count";
			$str_query .= " from";
			$str_query .= " stat_day_channel_card_new";
			$str_query .= " where";
			$str_query .= " channel_id = {$arr_input['channel_id']}";
			$str_query .= " and active_day = '{$arr_input['active_day']}'";

			break;
			
			case "GET_APP_BLACK_LIST":

			$str_query .= "select";
			$str_query .= " *";
			$str_query .= " from";
			$str_query .= " dev_presoft";
			$str_query .= " where";
			$str_query .= " state > 0";

			break;
			
			case "DELETE_APP_BLACK_LIST":
				
			$str_query .= "update";
			$str_query .= " dev_presoft";	
			$str_query .= " set";	
			$str_query .= " state= 0";
			$str_query .= " where sid = '{$arr_input['sid']}'";
			break;
			
			case "ADD_APP_BLACK_LIST":
				
			$str_query .= "insert into";
			$str_query .= " dev_presoft";
			$str_query .= " set";
			$str_query .= " sid='{$arr_input['sid']}'";
			$str_query .= ", sname='{$arr_input['appname']}'";
			$str_query .= ", appid='{$arr_input['appid']}'";
			$str_query .= ", state=1";
			break;
			

		}//switch
//		parent::debugQuery($str_query);
		return $str_query;
	}
}//class