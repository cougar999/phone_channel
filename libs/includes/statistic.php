<?php
require_once(TP_LIB_DIR.'classes/biz/common/util/Pager.php');
require_once(TP_LIB_DIR."classes/biz/statistic/Statistic.php");

function getYesterDaySellerIncomeList($seller_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['sell_day'] = date("Y-m-d",strtotime("last day"));
	
	$a_day_income_info = $o_stat->getDaySellerIncomeList($arr_input);
	
	$a_day_income_info['page_menu_date'] = $arr_input['sell_day'];
	
	return $a_day_income_info;
	
}

function getToDaySellerIncomeList($seller_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['sell_day'] = date("Y-m-d",time());
	
	$a_day_income_info = $o_stat->getDaySellerIncomeList($arr_input);
	
	$a_day_income_info['page_menu_date'] = $arr_input['sell_day'];
	
	return $a_day_income_info;
	
}

function getThisMonthSellerIncomeList($seller_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['sell_month'] = date("Y-m",time());
	
	$a_month_income_info = $o_stat->getMonthSellerIncomeList($arr_input);
	
	$a_month_income_info['page_menu_date'] = $arr_input['sell_month'];
	
	return $a_month_income_info;
}

function getLastMonthSellerIncomeList($seller_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['sell_month'] = date("Y-m",strtotime("last month"));
	
	$a_month_income_info = $o_stat->getMonthSellerIncomeList($arr_input);
	
	$a_month_income_info['page_menu_date'] = $arr_input['sell_month'];
	
	return $a_month_income_info;
}

function getSearchSellerIncomeList($seller_id){
	$o_stat = new Statistic();
	
	$start_time = addslashes(htmlspecialchars($_POST['start_time']));
	$end_time = addslashes(htmlspecialchars($_POST['end_time']));
	
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['start_time'] = $start_time;
	$arr_input['end_time'] = $end_time;
	
	$a_day_income_list = $o_stat->getSearchSellerIncomeList($arr_input);
	$a_seller_income_info = array();
	
	//搜索返回值不为空，结果计算
	if(!empty($a_day_income_list)){
		foreach ($a_day_income_list as $row){
			$a_seller_income_info['income_total'] += $row['income_total'];
			$a_seller_income_info['content_income_total'] += $row['content_income_total'];
			$a_seller_income_info['servicefee_income_total'] += $row['servicefee_income_total'];
			$a_seller_income_info['soft_income'] += $row['soft_income'];
			$a_seller_income_info['game_income'] += $row['game_income'];
			$a_seller_income_info['music_income'] += $row['music_income'];
			$a_seller_income_info['ebook_income'] += $row['ebook_income'];
			$a_seller_income_info['local_music_income'] += $row['local_music_income'];
			$a_seller_income_info['local_video_income'] += $row['local_video_income'];
			$a_seller_income_info['soft_servicefee'] += $row['soft_servicefee'];
			$a_seller_income_info['game_servicefee'] += $row['game_servicefee'];
			$a_seller_income_info['music_servicefee'] += $row['music_servicefee'];
			$a_seller_income_info['ebook_servicefee'] += $row['ebook_servicefee'];
			$a_seller_income_info['local_music_servicefee'] += $row['local_music_servicefee'];
			$a_seller_income_info['local_video_servicefee'] += $row['local_video_servicefee'];
		}
	}
	
	$a_seller_income_info['page_menu_date'] = $arr_input['start_time']." 至 ".$arr_input['end_time'];
	$a_seller_income_info['start_time'] = $arr_input['start_time'];
	$a_seller_income_info['end_time'] = $arr_input['end_time'];
	
	return $a_seller_income_info;
}

/***********************download***************************************/
function getYesterDaySellerDownloadList($seller_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['download_day'] = date("Y-m-d",strtotime("last day"));
	
	$a_day_download_info = $o_stat->getDaySellerDownloadList($arr_input);
	
	$a_day_download_info['page_menu_date'] = $arr_input['download_day'];
	
	return $a_day_download_info;
	
}

function getToDaySellerDownloadList($seller_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['download_day'] = date("Y-m-d",time());
	
	$a_day_download_info = $o_stat->getDaySellerDownloadList($arr_input);
	
	$a_day_download_info['page_menu_date'] = $arr_input['download_day'];
	
	return $a_day_download_info;
	
}

function getThisMonthSellerDownloadList($seller_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['download_month'] = date("Y-m",time());
	
	$a_month_download_info = $o_stat->getMonthSellerDownloadList($arr_input);
	
	$a_month_download_info['page_menu_date'] = $arr_input['download_month'];
	
	return $a_month_download_info;
}

function getLastMonthSellerDownloadList($seller_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['download_month'] = date("Y-m",strtotime("last month"));
	
	$a_month_download_info = $o_stat->getMonthSellerDownloadList($arr_input);
	
	$a_month_download_info['page_menu_date'] = $arr_input['download_month'];
	
	return $a_month_download_info;
}

function getSearchSellerDownloadList($seller_id){
	$o_stat = new Statistic();
	
	$start_time = addslashes(htmlspecialchars($_POST['start_time']));
	$end_time = addslashes(htmlspecialchars($_POST['end_time']));
	
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['start_time'] = $start_time;
	$arr_input['end_time'] = $end_time;
	
	$a_day_download_list = $o_stat->getSearchSellerDownloadList($arr_input);
	$a_seller_download_info = array();
	
	//搜索返回值不为空，结果计算
	if(!empty($a_day_download_list)){
		foreach ($a_day_download_list as $row){
			$a_seller_download_info['total_download'] += $row['total_download'];
			$a_seller_download_info['free_download'] += $row['free_download'];
			$a_seller_download_info['pay_download'] += $row['pay_download'];
			$a_seller_download_info['soft_free_download'] += $row['soft_free_download'];
			$a_seller_download_info['soft_pay_download'] += $row['soft_pay_download'];
			$a_seller_download_info['game_free_download'] += $row['game_free_download'];
			$a_seller_download_info['game_pay_download'] += $row['game_pay_download'];
			$a_seller_download_info['music_free_download'] += $row['music_free_download'];
			$a_seller_download_info['music_pay_download'] += $row['music_pay_download'];
			$a_seller_download_info['ebook_free_download'] += $row['ebook_free_download'];
			$a_seller_download_info['ebook_pay_download'] += $row['ebook_pay_download'];
			$a_seller_download_info['local_music_free_download'] += $row['local_music_free_download'];
			$a_seller_download_info['local_music_pay_download'] += $row['local_music_pay_download'];
			$a_seller_download_info['local_video_free_download'] += $row['local_video_free_download'];
			$a_seller_download_info['local_video_pay_download'] += $row['local_video_pay_download'];
		}
	}
	
	$a_seller_download_info['page_menu_date'] = $arr_input['start_time']." 至 ".$arr_input['end_time'];
	$a_seller_download_info['start_time'] = $arr_input['start_time'];
	$a_seller_download_info['end_time'] = $arr_input['end_time'];
	
	return $a_seller_download_info;
}
/***********************card***************************************/
function getYesterDaySellerCardList($seller_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['active_day'] = date("Y-m-d",strtotime("last day"));
	
	$a_day_card_info = $o_stat->getDaySellerCardList($arr_input);
	
	$a_day_card_info['page_menu_date'] = $arr_input['active_day'];
	
	return $a_day_card_info;
	
}

function getToDaySellerCardList($seller_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['active_day'] = date("Y-m-d",time());
	
	$a_day_card_info = $o_stat->getDaySellerCardList($arr_input);
	
	$a_day_card_info['page_menu_date'] = $arr_input['active_day'];
	
	return $a_day_card_info;
	
}

function getThisMonthSellerCardList($seller_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['sell_month'] = date("Y-m",time());
	
	$a_month_card_info = $o_stat->getMonthSellerCardList($arr_input);
	
	$a_month_card_info['page_menu_date'] = $arr_input['sell_month'];
	
	return $a_month_card_info;
}

function getLastMonthSellerCardList($seller_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['sell_month'] = date("Y-m",strtotime("last month"));
	
	$a_month_card_info = $o_stat->getMonthSellerCardList($arr_input);
	
	$a_month_card_info['page_menu_date'] = $arr_input['sell_month'];
	
	return $a_month_card_info;
}

function getSearchSellerCardList($seller_id){
	$o_stat = new Statistic();
	
	$start_time = addslashes(htmlspecialchars($_POST['start_time']));
	$end_time = addslashes(htmlspecialchars($_POST['end_time']));
	
	unset($arr_input);
	$arr_input['seller_id'] = intval($seller_id);
	$arr_input['start_time'] = $start_time;
	$arr_input['end_time'] = $end_time;
	
	$a_day_card_list = $o_stat->getSearchSellerCardList($arr_input);
	$a_seller_card_info = array();
	
	//搜索返回值不为空，结果计算
	if(!empty($a_day_card_list)){
		foreach ($a_day_card_list as $row){
			$a_seller_card_info['total_price'] += $row['total_price'];
			$a_seller_card_info['total_count'] += $row['total_count'];
			$a_seller_card_info['quarter_card_total'] += $row['quarter_card_total'];
			$a_seller_card_info['quarter_card_count'] += $row['quarter_card_count'];
			$a_seller_card_info['half_year_card_total'] += $row['half_year_card_total'];
			$a_seller_card_info['half_year_card_count'] += $row['half_year_card_count'];
			$a_seller_card_info['year_card_total'] += $row['year_card_total'];
			$a_seller_card_info['year_card_count'] += $row['year_card_count'];
			$a_seller_card_info['two_year_card_total'] += $row['two_year_card_total'];
			$a_seller_card_info['two_year_card_count'] += $row['two_year_card_count'];
		}
	}
	
	$a_seller_card_info['page_menu_date'] = $arr_input['start_time']." 至 ".$arr_input['end_time'];
	$a_seller_card_info['start_time'] = $arr_input['start_time'];
	$a_seller_card_info['end_time'] = $arr_input['end_time'];
	
	return $a_seller_card_info;
}

function getYesterDayChannelIncomeList($channel_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['sell_day'] = date("Y-m-d",strtotime("last day"));
	
	$a_day_income_info = $o_stat->getDayChannelIncomeList($arr_input);
	
	$a_day_income_info['page_menu_date'] = $arr_input['sell_day'];
	
	return $a_day_income_info;
	
}

function getToDayChannelIncomeList($channel_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['sell_day'] = date("Y-m-d",time());
	
	$a_day_income_info = $o_stat->getDayChannelIncomeList($arr_input);
	
	$a_day_income_info['page_menu_date'] = $arr_input['sell_day'];
	
	return $a_day_income_info;
	
}

function getThisMonthChannelIncomeList($channel_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['sell_month'] = date("Y-m",time());
	
	$a_month_income_info = $o_stat->getMonthChannelIncomeList($arr_input);
	
	$a_month_income_info['page_menu_date'] = $arr_input['sell_month'];
	
	return $a_month_income_info;
}

function getLastMonthChannelIncomeList($channel_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['sell_month'] = date("Y-m",strtotime("last month"));
	
	$a_month_income_info = $o_stat->getMonthChannelIncomeList($arr_input);
	
	$a_month_income_info['page_menu_date'] = $arr_input['sell_month'];
	
	return $a_month_income_info;
}

function getSearchChannelIncomeList($channel_id){
	$o_stat = new Statistic();
	
	$start_time = addslashes(htmlspecialchars($_POST['start_time']));
	$end_time = addslashes(htmlspecialchars($_POST['end_time']));
	
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['start_time'] = $start_time;
	$arr_input['end_time'] = $end_time;
	
	$a_day_income_list = $o_stat->getSearchChannelIncomeList($arr_input);
	$a_seller_income_info = array();
	
	//搜索返回值不为空，结果计算
	if(!empty($a_day_income_list)){
		foreach ($a_day_income_list as $row){
			$a_seller_income_info['income_total'] += $row['income_total'];
			$a_seller_income_info['content_income_total'] += $row['content_income_total'];
			$a_seller_income_info['servicefee_income_total'] += $row['servicefee_income_total'];
			$a_seller_income_info['soft_income'] += $row['soft_income'];
			$a_seller_income_info['game_income'] += $row['game_income'];
			$a_seller_income_info['music_income'] += $row['music_income'];
			$a_seller_income_info['ebook_income'] += $row['ebook_income'];
			$a_seller_income_info['local_music_income'] += $row['local_music_income'];
			$a_seller_income_info['local_video_income'] += $row['local_video_income'];
			$a_seller_income_info['soft_servicefee'] += $row['soft_servicefee'];
			$a_seller_income_info['game_servicefee'] += $row['game_servicefee'];
			$a_seller_income_info['music_servicefee'] += $row['music_servicefee'];
			$a_seller_income_info['ebook_servicefee'] += $row['ebook_servicefee'];
			$a_seller_income_info['local_music_servicefee'] += $row['local_music_servicefee'];
			$a_seller_income_info['local_video_servicefee'] += $row['local_video_servicefee'];
		}
	}
	
	$a_seller_income_info['page_menu_date'] = $arr_input['start_time']." 至 ".$arr_input['end_time'];
	$a_seller_income_info['start_time'] = $arr_input['start_time'];
	$a_seller_income_info['end_time'] = $arr_input['end_time'];
	
	return $a_seller_income_info;
}

/***********************download***************************************/
function getYesterDayChannelDownloadList($channel_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['download_day'] = date("Y-m-d",strtotime("last day"));
	
	$a_day_download_info = $o_stat->getDayChannelDownloadList($arr_input);
	
	$a_day_download_info['page_menu_date'] = $arr_input['download_day'];
	
	return $a_day_download_info;
	
}

function getToDayChannelDownloadList($channel_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['download_day'] = date("Y-m-d",time());
	
	$a_day_download_info = $o_stat->getDayChannelDownloadList($arr_input);
	
	$a_day_download_info['page_menu_date'] = $arr_input['download_day'];
	
	return $a_day_download_info;
	
}

function getThisMonthChannelDownloadList($channel_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['download_month'] = date("Y-m",time());
	
	$a_month_download_info = $o_stat->getMonthChannelDownloadList($arr_input);
	
	$a_month_download_info['page_menu_date'] = $arr_input['download_month'];
	
	return $a_month_download_info;
}

function getLastMonthChannelDownloadList($channel_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['download_month'] = date("Y-m",strtotime("last month"));
	
	$a_month_download_info = $o_stat->getMonthChannelDownloadList($arr_input);
	
	$a_month_download_info['page_menu_date'] = $arr_input['download_month'];
	
	return $a_month_download_info;
}

function getSearchChannelDownloadList($channel_id){
	$o_stat = new Statistic();
	
	$start_time = addslashes(htmlspecialchars($_POST['start_time']));
	$end_time = addslashes(htmlspecialchars($_POST['end_time']));
	
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['start_time'] = $start_time;
	$arr_input['end_time'] = $end_time;
	
	$a_day_download_list = $o_stat->getSearchChannelDownloadList($arr_input);
	$a_seller_download_info = array();
	
	//搜索返回值不为空，结果计算
	if(!empty($a_day_download_list)){
		foreach ($a_day_download_list as $row){
			$a_seller_download_info['total_download'] += $row['total_download'];
			$a_seller_download_info['free_download'] += $row['free_download'];
			$a_seller_download_info['pay_download'] += $row['pay_download'];
			$a_seller_download_info['soft_free_download'] += $row['soft_free_download'];
			$a_seller_download_info['soft_pay_download'] += $row['soft_pay_download'];
			$a_seller_download_info['game_free_download'] += $row['game_free_download'];
			$a_seller_download_info['game_pay_download'] += $row['game_pay_download'];
			$a_seller_download_info['music_free_download'] += $row['music_free_download'];
			$a_seller_download_info['music_pay_download'] += $row['music_pay_download'];
			$a_seller_download_info['ebook_free_download'] += $row['ebook_free_download'];
			$a_seller_download_info['ebook_pay_download'] += $row['ebook_pay_download'];
			$a_seller_download_info['local_music_free_download'] += $row['local_music_free_download'];
			$a_seller_download_info['local_music_pay_download'] += $row['local_music_pay_download'];
			$a_seller_download_info['local_video_free_download'] += $row['local_video_free_download'];
			$a_seller_download_info['local_video_pay_download'] += $row['local_video_pay_download'];
		}
	}
	
	$a_seller_download_info['page_menu_date'] = $arr_input['start_time']." 至 ".$arr_input['end_time'];
	$a_seller_download_info['start_time'] = $arr_input['start_time'];
	$a_seller_download_info['end_time'] = $arr_input['end_time'];
	
	return $a_seller_download_info;
}
/***********************card***************************************/
function getYesterDayChannelCardList($channel_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['active_day'] = date("Y-m-d",strtotime("last day"));
	
	$a_day_card_info = $o_stat->getDayChannelCardList($arr_input);
	
	$a_day_card_info['page_menu_date'] = $arr_input['active_day'];
	
	return $a_day_card_info;
	
}

function getToDayChannelCardList($channel_id){
	$o_stat = new Statistic();
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['active_day'] = date("Y-m-d",time());
	
	$a_day_card_info = $o_stat->getDayChannelCardList($arr_input);
	
	$a_day_card_info['page_menu_date'] = $arr_input['active_day'];
	
	return $a_day_card_info;
	
}

function getThisMonthChannelCardList($channel_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['sell_month'] = date("Y-m",time());
	
	$a_month_card_info = $o_stat->getMonthChannelCardList($arr_input);
	
	$a_month_card_info['page_menu_date'] = $arr_input['sell_month'];
	
	return $a_month_card_info;
}

function getLastMonthChannelCardList($channel_id){
	$o_stat = new Statistic();
	
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['sell_month'] = date("Y-m",strtotime("last month"));
	
	$a_month_card_info = $o_stat->getMonthChannelCardList($arr_input);
	
	$a_month_card_info['page_menu_date'] = $arr_input['sell_month'];
	
	return $a_month_card_info;
}

function getSearchChannelCardList($channel_id){
	$o_stat = new Statistic();
	
	$start_time = addslashes(htmlspecialchars($_POST['start_time']));
	$end_time = addslashes(htmlspecialchars($_POST['end_time']));
	
	unset($arr_input);
	$arr_input['channel_id'] = intval($channel_id);
	$arr_input['start_time'] = $start_time;
	$arr_input['end_time'] = $end_time;
	
	$a_day_card_list = $o_stat->getSearchChannelCardList($arr_input);
	$a_seller_card_info = array();
	
	//搜索返回值不为空，结果计算
	if(!empty($a_day_card_list)){
		foreach ($a_day_card_list as $row){
			$a_seller_card_info['total_price'] += $row['total_price'];
			$a_seller_card_info['total_count'] += $row['total_count'];
			$a_seller_card_info['quarter_card_total'] += $row['quarter_card_total'];
			$a_seller_card_info['quarter_card_count'] += $row['quarter_card_count'];
			$a_seller_card_info['half_year_card_total'] += $row['half_year_card_total'];
			$a_seller_card_info['half_year_card_count'] += $row['half_year_card_count'];
			$a_seller_card_info['year_card_total'] += $row['year_card_total'];
			$a_seller_card_info['year_card_count'] += $row['year_card_count'];
			$a_seller_card_info['two_year_card_total'] += $row['two_year_card_total'];
			$a_seller_card_info['two_year_card_count'] += $row['two_year_card_count'];
		}
	}
	
	$a_seller_card_info['page_menu_date'] = $arr_input['start_time']." 至 ".$arr_input['end_time'];
	$a_seller_card_info['start_time'] = $arr_input['start_time'];
	$a_seller_card_info['end_time'] = $arr_input['end_time'];
	
	return $a_seller_card_info;
}

function getAppBlackList($arr_input = null){
	$o_stat = new Statistic();
	
	unset($arr_input);
	//$arr_input['appid'] = $appid;
	
	$app_black_list = $o_stat->getAppBlackList($arr_input);
	return $app_black_list;
}

function deleteAppBlackList($arr_input){
	
	$sid = $arr_input['sid'];
	$o_stat = new Statistic();
	unset($arr_input);
	if($sid){
		$arr_input['sid'] = $sid;
		$s_result = $o_stat->deleteAppBlacklist($arr_input);
	}else {
		$s_result = false;
	}
	echo $s_result;
}

function AddAppBlackList($arr_input){
	$o_stat = new Statistic();
	
	if($arr_input){
		$s_result = $o_stat->AddAppBlackList($arr_input);
	}else {
		$s_result = false;
	}
	echo $s_result;
}

?>