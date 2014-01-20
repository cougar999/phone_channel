<?php
class Statitic {
	private $_dbr;
	public function __construct($dbKey = 'stat'){
		DB::get_db_reader($dbKey);
	}
	
	public function __destruct(){
	}
	
	public function getChnDownList($pra){
		try {
			$str_query = "
			select 
			if(channel_id={$pra['curid']},0,1) as flag,
			channel_id,
			sum(total_download) as collect_toal,
			sum(soft_free_download),sum(soft_pay_download),COALESCE(sum(soft_pay_download),0)+COALESCE(sum(soft_free_download),0) as soft_total,
			sum(music_free_download),sum(music_pay_download),COALESCE(sum(music_free_download),0)+COALESCE(sum(music_pay_download),0) as music_total,
			sum(ebook_free_download),sum(ebook_pay_download),COALESCE(sum(ebook_free_download),0)+COALESCE(sum(ebook_pay_download),0) as ebook_total,
			sum(local_music_free_download),sum(local_music_pay_download),COALESCE(sum(local_music_free_download),0)+COALESCE(sum(local_music_pay_download),0) as local_music_total,
			sum(local_video_free_download),sum(local_video_pay_download),COALESCE(sum(local_video_free_download),0)+COALESCE(sum(local_video_pay_download),0) as local_video_total
			from stat_day_channel_download
			where 
			channel_id in({$pra['chnid']})
			and download_day >= '{$pra['start_time']}' and download_day <= '{$pra['end_time']}'
			group by channel_id
			order by flag desc
			";
			return DB::selectQuery($str_query);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	public function getChnCardDownList($pra){
		try {
			$str_query = "
			select 
			if(channel_id={$pra['curid']},0,1) as flag,
			channel_id,
			sum(quarter_card_count),sum(quarter_card_total),
			sum(half_year_card_count),sum(half_year_card_total),
			sum(year_card_count),sum(year_card_total),
			sum(two_year_card_count),sum(two_year_card_total),
			sum(total_count),sum(total_price)
			from stat_day_channel_card_new
			where 
			channel_id in({$pra['chnid']})
			and active_day >= '{$pra['start_time']}' and active_day <= '{$pra['end_time']}'
			group by channel_id
			order by flag desc
			";
			return DB::selectQuery($str_query);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	public function getChnIncomeList($pra){
		try {
			$str_query = "
			select 
			if(channel_id={$pra['curid']},0,1) as flag,
			channel_id,
			sum(soft_servicefee),
			sum(music_servicefee),
			sum(ebook_servicefee),
			sum(local_music_servicefee),
			sum(local_video_servicefee),
			sum(servicefee_income_total)
			from stat_day_channel_income
			where 
			channel_id in({$pra['chnid']})
			and sell_day >= '{$pra['start_time']}' and sell_day <= '{$pra['end_time']}'
			group by channel_id
			order by flag desc
			";
			return DB::selectQuery($str_query);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	public function getPerDownList($pra){
		try {
			$str_query = "
			select 
			if(seller_id={$pra['curid']},0,1) as flag,
			seller_id as channel_id,
			sum(total_download) as collect_toal,
			sum(soft_free_download),sum(soft_pay_download),COALESCE(sum(soft_pay_download),0)+COALESCE(sum(soft_free_download),0) as soft_total,
			sum(music_free_download),sum(music_pay_download),COALESCE(sum(music_free_download),0)+COALESCE(sum(music_pay_download),0) as music_total,
			sum(ebook_free_download),sum(ebook_pay_download),COALESCE(sum(ebook_free_download),0)+COALESCE(sum(ebook_pay_download),0) as ebook_total,
			sum(local_music_free_download),sum(local_music_pay_download),COALESCE(sum(local_music_free_download),0)+COALESCE(sum(local_music_pay_download),0) as local_music_total,
			sum(local_video_free_download),sum(local_video_pay_download),COALESCE(sum(local_video_free_download),0)+COALESCE(sum(local_video_pay_download),0) as local_video_total
			from stat_day_seller_download
			where 
			".(!!empty($pra['shopid'])?"seller_id in({$pra['chnid']})":"shop_id in({$pra['shopid']})")."
			and download_day >= '{$pra['start_time']}' and download_day <= '{$pra['end_time']}'
			".(!!empty($pra['shopid'])?"group by shop_id":"group by seller_id")."
			order by flag desc
			";
			return DB::selectQuery($str_query);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	public function getPerCardDownList($pra){
		try {
			$str_query = "
			select 
			if(seller_id={$pra['curid']},0,1) as flag,
			seller_id as channel_id,
			sum(quarter_card_count),sum(quarter_card_total),
			sum(half_year_card_count),sum(half_year_card_total),
			sum(year_card_count),sum(year_card_total),
			sum(two_year_card_count),sum(two_year_card_total),
			sum(total_count),sum(total_price)
			from stat_day_seller_card_new
			where 
			".(!!empty($pra['shopid'])?"seller_id in({$pra['chnid']})":"shop_id in({$pra['shopid']})")."
			and active_day >= '{$pra['start_time']}' and active_day <= '{$pra['end_time']}'
			".(!!empty($pra['shopid'])?"group by shop_id":"group by seller_id")."
			order by flag desc
			";
			return DB::selectQuery($str_query);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	public function getPerIncomeList($pra){
		try {
			$str_query = "
			select 
			if(seller_id={$pra['curid']},0,1) as flag,
			seller_id as channel_id,
			sum(soft_servicefee),
			sum(music_servicefee),
			sum(ebook_servicefee),
			sum(local_music_servicefee),
			sum(local_video_servicefee),
			sum(servicefee_income_total)
			from stat_day_seller_income
			where 
			".(!!empty($pra['shopid'])?"seller_id in({$pra['chnid']})":"shop_id in({$pra['shopid']})")."
			and sell_day >= '{$pra['start_time']}' and sell_day <= '{$pra['end_time']}'
			".(!!empty($pra['shopid'])?"group by shop_id":"group by seller_id")."
			order by flag desc
			";
			return DB::selectQuery($str_query);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
}