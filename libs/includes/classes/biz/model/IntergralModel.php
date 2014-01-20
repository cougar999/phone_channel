<?php
function getScoreCase($type,$time,$op_times=1){
	$val = 0;
	$op_times = !intval($op_times)?1:intval($op_times);
	//1,3,5
	//if(a.type=1,5,if(a.type=2,5*op_times,if(a.type=3,2*op_times,if(a.type=4,20,if(a.type=5,10,if(a.type=6,10,if(a.type=7,40,if(a.type=8,20,if(a.type=9,4*op_times,if(a.type=10,50,if(type=20,1000,0))))))))))) as _score
	switch ($type){
		case 1:
			if ($time>='2012-04-26'&&$time<='2012-04-28') $val = 5*3;
			else $val = 5;
			break;
		case 2:
			$val = 5*$op_times;
			break;
		case 3:
			if ($time>='2012-04-26'&&$time<='2012-04-28') $val = 2*$op_times*3;
			else $val = 2*$op_times;
			break;
		case 4:
			$val = 20;
			break;
		case 5:
			if ($time>='2012-04-26'&&$time<='2012-04-28') $val = 10*3;
			else $val = 10;
			break;
		case 6:
			$val = 10;
			break;
		case 7:
			$val = 20;
			break;
		case 8:
			$val = 10;
			break;
		case 9:
			$val = 4*$op_times;
			break;
		case 10:
			$val = 50;
			break;
		case 20:
			$val = 1000;
			break;
		default:break;
	}
	return $val;
}
class IntergralModel{
	private $_dbr;
	public function __construct(){
		DB::get_db_reader();
	}
	
	public function __destruct(){
	}
	
	public function getIntergralInfo_6($pra){
		$_moth_str = '2012-06';
		$month_s   = '2012-06-01';
		$month_e   = '2012-06-30';
		try {
			if ($pra['type']=='all'){
				$str_query = "select 
							{str}
							from tb_u_score_details a left join channel.ch_salesperson b on a.salesperson_id=b.id
							left join channel.ch_agent c on b.agent_id = c.id
							where find_in_set('{$pra['top_agent_id']}',c.ppath) and a.active_day like '{$_moth_str}%' and a.op_score > 0";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(distinct salesperson_id) as cut', $str_query):
							str_ireplace('{str}', 'a.salesperson_id,a.salesperson_name,sum(a.op_score) as score,
													sum(if(a.type=11,a.op_times,0)) as cyjb_count,
													sum(if(a.type in(7,10),1,0)) as card_count', $str_query).
							" group by a.salesperson_id order by score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='alls'){
				$str_query = "select {str}
							from tb_u_score
							where top_agent_id = {$pra['top_agent_id']}";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(salesperson_id) as cut', $str_query):str_ireplace('{str}', 'score,salesperson_id,salesperson_name,level', $str_query)." order by score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a3'){
				$sql = "select b.company_province
						from channel.ch_salesperson a left join channel.ch_agent_info b on a.agent_id = b.agent_id 
						where a.id = {$pra['session']['real_id']}";
				$rs  = DB::selectQueryAssoc($sql);
				$str_query = "select 
							{str}
							from tb_u_score_details a left join channel.ch_salesperson b on a.salesperson_id=b.id
							left join channel.ch_agent c on b.agent_id = c.id
							left join channel.ch_agent_info d on c.id = d.agent_id
							where find_in_set('{$pra['top_agent_id']}',c.ppath) and a.active_day like '{$_moth_str}%' and a.op_score > 0";
				
							!empty($rs) && $str_query.=" and d.company_province = '{$rs[0]['company_province']}'";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(distinct salesperson_id) as cut', $str_query):
							str_ireplace('{str}', 'salesperson_id,salesperson_name,sum(a.op_score) as score,
													if(a.salesperson_id='.$pra['session']['real_id'].',1,0) as _flag,
													sum(if(a.type=11,a.op_times,0)) as cyjb_count,
													sum(if(a.type in(7,10),1,0)) as card_count', $str_query).
							" group by a.salesperson_id order by score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a4'){
				$str_query = "select 
							{str}
							from tb_u_score_details a left join channel.ch_salesperson b on a.salesperson_id=b.id
							left join channel.ch_agent c on b.agent_id = c.id
							where find_in_set('{$pra['top_agent_id']}',c.ppath) and a.active_day like '{$_moth_str}%'";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(distinct salesperson_id) as cut', $str_query):
							str_ireplace('{str}', 'a.salesperson_id,
													a.salesperson_name,
													0 as con_imei,
													if(a.salesperson_id='.$pra['session']['real_id'].',1,0) as _flag,
													sum(if(a.type=1,1,0)) as login_count,
													sum(if(a.type=2,op_times,0)) as con_count', $str_query).
							" group by a.salesperson_id order by _flag desc,login_count desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
				if ($pra['act'] == 'info'){
					$arr_rs    = DB::selectQueryAssoc($str_query);
					foreach ($arr_rs as $k=>$v){
						$tmp_seller[] = $v['salesperson_id'];
					}
					foreach ($arr_rs as $item){
						$_arr_rs[$item['salesperson_id']] = $item;
					}
					$sql       = "select 
								a.salesperson_id,
								a.salesperson_name,
								count(distinct a.imei) as con_imei
								from tb_u_score_details a
								where a.type in(3,9,11,12) and a.salesperson_id in(".implode(',', $tmp_seller).") and a.active_day like '{$_moth_str}%'
								group by a.salesperson_id
								";	
					$_tmp_rs   = DB::selectQueryAssoc($sql);
					foreach ($_tmp_rs as $item){
						$_arr_rs[$item['salesperson_id']]['con_imei'] = $item['con_imei'];
					}
					unset($arr_rs);
					foreach ($_arr_rs as $k=>$v){
						$arr_rs[] = $v;
					}
					$str_query = '';
				}
			}elseif ($pra['type']=='a5'){
				$str_query = "select 
							{str}
							from tb_u_score_details a left join channel.ch_salesperson b on a.salesperson_id=b.id
							left join channel.ch_agent c on b.agent_id = c.id
							where find_in_set('{$pra['top_agent_id']}',c.ppath) and a.active_day like '{$_moth_str}%'";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(distinct salesperson_id) as cut', $str_query):
							str_ireplace('{str}', 'a.salesperson_id,a.salesperson_name,
													sum(if(a.type=4,1,0)) as copy_count', $str_query).
							" group by a.salesperson_id order by copy_count desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a6'){
				$arr_tmp   = array();
				$arr_rs    = '';
				$max_len   = 20;
				$max_score = 1000;//1000
//				$str_query = "select a.active_day,b.create_time,a.salesperson_id,a.salesperson_name,if(a.type=1,5,if(a.type=2,5*op_times,if(a.type=3,2*op_times,if(a.type=4,20,if(a.type=5,10,if(a.type=6,10,if(a.type=7,20,if(a.type=8,10,if(a.type=9,4*op_times,if(a.type=10,50,if(type=20,1000,0))))))))))) as _score
				$str_query = "select a.active_day,b.create_time,a.salesperson_id,a.salesperson_name,a.type,a.op_times,a.op_score
							from tb_u_score_details a 
							left join channel.ch_salesperson b on a.salesperson_id = b.id
							where a.op_score > 0 and b.create_time >= '".$month_s."' and b.create_time <= '".$month_e."' and a.active_day>='".$month_s."' and a.active_day <= '".$month_e."'
							and b.agent_id in(select id from channel.ch_agent where ppath = {$pra['top_agent_id']} or find_in_set('{$pra['top_agent_id']},',ppath))
							order by a.active_day asc";//order by a.active_day asc,_score desc";
				$st = mysql_query($str_query);
				while ($rs = mysql_fetch_array($st,MYSQL_ASSOC)){
					$arr_tmp[$rs['salesperson_id']]['_score'] += intval($rs['op_score']);
					if ($arr_tmp[$rs['salesperson_id']]['_score']>=$max_score && !isset($arr_rs[$rs['salesperson_id']])){
						$rs['score'] = $arr_tmp[$rs['salesperson_id']]['_score'];
						$_active_day = $rs['active_day'] == date('Y-m-d',strtotime($rs['active_day']))?strtotime($rs['active_day'])+24*60*60:strtotime($rs['active_day']);
						$_create_time= strtotime($rs['create_time']);
						$rs['ys'] = $rs['score'] = $_active_day-$_create_time;
						$arr_rs[$rs['salesperson_id']] = $rs;
					}
//					if(count($arr_rs) > $max_len) break;
				}
				usort($arr_rs, "sortself");
				$arr_rs    = array_slice($arr_rs,0,$max_len);
				foreach ($arr_rs as $k=>$v){
					$arr_rs[$k]['score'] = filter_time($arr_rs[$k]['score']);
				}
				$str_query = '';
			}
		}catch (Exception $e){
			$e->getMessage();
		}
		return !empty($str_query)?DB::selectQueryAssoc($str_query):$arr_rs;
	}
	
	public function getIntergralInfo_1207($pra){
		$_moth_str = '2012-07';
		$month_s   = '2012-07-01';
		$month_e   = '2012-07-31';
		try {
			if ($pra['type']=='all'){
				$str_query = "select 
							{str}
							from tb_u_score_details a left join channel.ch_salesperson b on a.salesperson_id=b.id
							left join channel.ch_agent c on b.agent_id = c.id
							where find_in_set('{$pra['top_agent_id']}',c.ppath) and a.active_day like '{$_moth_str}%' and a.op_score > 0";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(distinct salesperson_id) as cut', $str_query):
							str_ireplace('{str}', 'a.salesperson_id,a.salesperson_name,c.pid,sum(a.op_score) as score,
													sum(if(a.type=11,a.op_times,0)) as cyjb_count,
													sum(if(a.type in(7,10),1,0)) as card_count', $str_query).
							" group by a.salesperson_id order by score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
				if ($pra['act'] == 'info'){
					$arr_rs     = DB::selectQueryAssoc($str_query);
					foreach ($arr_rs as $k=>$v){
						$_tmp_cid[] = $v['pid'];
					}
					$sql 		  = "select imei_count,channel_id from statistic.stat_pc_month_channel_imeis where stat_date = '".date('Y-m-d',strtotime("-1 days"))."' and channel_id in(".implode(',', $_tmp_cid).")";
					$_tmp_cid_arr = DB::selectQueryAssoc($sql);
					foreach ((array)$_tmp_cid_arr as $item){
						$_tmp_arr[$item['channel_id']] = $item['imei_count'];
					}
					//数据重组
					foreach ($arr_rs as $k=>$v){
						$con_cut = intval($_tmp_arr[$v['pid']]);
						$con_lvl = setLevel($v['pid']);
						$arr_rs[$k]['con_cut'] = $con_cut;
						$arr_rs[$k]['con_lvl'] = $con_lvl;
						$arr_rs[$k]['flag']    = $v['score']>=8000&&$v['cyjb_count']>=200&&$v['card_count']>=10?$con_lvl:'F';
					}
					foreach($arr_rs as $key=>$val) {
					        $scoreGup[$key] = $val['score'];
					        $flagGup[$key]  = $val['flag'];
					}
					array_multisort($flagGup, SORT_ASC, $scoreGup, SORT_DESC, $arr_rs);
					$str_query = '';
				}
			}elseif ($pra['type']=='alls'){
				$str_query = "select {str}
							from tb_u_score
							where top_agent_id = {$pra['top_agent_id']}";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(salesperson_id) as cut', $str_query):str_ireplace('{str}', 'score,salesperson_id,salesperson_name,level', $str_query)." order by score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a3'){
				//获取省级信息
				$str_query = "select b.pid,b.pname,b.ppath,c.imei_count
							from channel.ch_salesperson a,channel.ch_agent b,statistic.stat_pc_month_channel_imeis c
							where a.agent_id = b.id and b.pid = c.channel_id and a.id = {$pra['session']['real_id']} and c.stat_date = '".date('Y-m-d',strtotime("-1 days"))."'
							order by c.imei_count desc";
				$rs        = DB::selectQueryAssoc($str_query);
				$str_query = "select 
							{str}
							from tb_u_score_details a 
							left join channel.ch_salesperson b on a.salesperson_id=b.id
							left join channel.ch_agent c on b.agent_id = c.id
							where find_in_set('{$rs[0]['pid']}',c.ppath) and a.active_day like '{$_moth_str}%' and a.op_score > 0";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(distinct salesperson_id) as cut', $str_query):
							str_ireplace('{str}', 'a.salesperson_id,a.salesperson_name,c.pid,sum(a.op_score) as score,
													sum(if(a.type=11,a.op_times,0)) as cyjb_count,
													sum(if(a.type in(7,10),1,0)) as card_count', $str_query).
							" group by a.salesperson_id order by score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
				if ($pra['act'] == 'info'){
					$arr_rs['data']    = DB::selectQueryAssoc($str_query);
					$arr_rs['lvl']     = setLevel($rs[0]['pid']);
					$str_query		   = '';
				}
			}elseif ($pra['type']=='a4'){
				$str_query = "select 
							{str}
							from tb_u_score_details a left join channel.ch_salesperson b on a.salesperson_id=b.id
							left join channel.ch_agent c on b.agent_id = c.id
							where find_in_set('{$pra['top_agent_id']}',c.ppath) and a.active_day like '{$_moth_str}%'";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(distinct salesperson_id) as cut', $str_query):
							str_ireplace('{str}', 'a.salesperson_id,
													a.salesperson_name,
													0 as con_imei,
													if(a.salesperson_id='.$pra['session']['real_id'].',1,0) as _flag,
													sum(if(a.type=1,1,0)) as login_count,
													sum(if(a.type in(3,9,11,12),op_times,0)) as down_count,
													sum(if(a.type=2,op_times,0)) as con_count', $str_query).
							" group by a.salesperson_id order by _flag desc,login_count desc,salesperson_name desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a5'){
				$str_query = "select 
							{str}
							from tb_u_score_details a left join channel.ch_salesperson b on a.salesperson_id=b.id
							left join channel.ch_agent c on b.agent_id = c.id
							where find_in_set('{$pra['top_agent_id']}',c.ppath) and a.active_day like '{$_moth_str}%' and a.type = 4";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(distinct salesperson_id) as cut', $str_query):
							str_ireplace('{str}', 'a.salesperson_id,a.salesperson_name,
													sum(if(a.type=4,1,0)) as copy_count', $str_query).
							" group by a.salesperson_id order by copy_count desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a10'){
				$str_query = "select 
							{str}
							from tb_u_score_details a left join channel.ch_salesperson b on a.salesperson_id=b.id
							left join channel.ch_agent c on b.agent_id = c.id
							where find_in_set('{$pra['top_agent_id']}',c.ppath) and a.active_day like '{$_moth_str}%' and a.type = 14";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(distinct salesperson_id) as cut', $str_query):
							str_ireplace('{str}', 'a.salesperson_id,a.salesperson_name,
													sum(if(a.type=14,op_times,0)) as update_count', $str_query).
							" group by a.salesperson_id order by update_count desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a6'){
				$arr_tmp   = $cf_tmp_arr_imei = array();
				$arr_rs    = '';
				$max_len   = 20;
				$max_score = 1000;//1000
				$str_query = "select salesperson_id,salesperson_name from channel2_portal.tb_u_score a
							where a.score = (
							select score from channel2_portal.tb_u_score_month where month = '{$_moth_str}' and salesperson_id = a.salesperson_id
							) and a.score >= {$max_score} and a.top_agent_id = '{$pra['top_agent_id']}' and a.status = 1";
//				$str_query = "SELECT a.salesperson_id,a.salesperson_name
//							FROM channel2_portal.tb_u_score_month a,channel.ch_salesperson b
//							WHERE a.top_agent_id = 8317 and a.`status` = 1 and a.salesperson_id = b.id AND a.month = '2012-07' AND a.score>=1000 AND b.create_time LIKE '2012-07%'";
				$rs_fir    = DB::selectQueryAssoc($str_query);
				if (!empty($rs_fir)){
					foreach ($rs_fir as $item){
						$seller_id[] 							   = $item['salesperson_id'];
						$arr_tmp[$item['salesperson_id']]['_name'] = $item['salesperson_name'];
					}
					$_tmp_str_sellerid = implode(',', $seller_id);
					$sql_query = "select * from (
								(select seller_id,date_format(active_time,'%Y-%m-%d %H:%i:%s') as active_time,if(card_type in (9,10,11),10,7) as _type,'' as tmp from statistic.stat_pc_detail_active_card where active_type = 2 and seller_id > 0 and seller_id regexp '^[0-9]+$' and active_status = 1 and card_type in (9,10,11,4,5,6,7) and active_time like '2012-07%' and seller_id in({$_tmp_str_sellerid}) order by active_time)
								union all
								(select a.* from (select seller_id,date_format(op_time,'%Y-%m-%d %H:%i:%s') as op_time,'1' as _type,'' as tmp from statistic.stat_pc_detail_login where op_time like '2012-07%' and op_status = 1 and seller_id > 0 and seller_id in({$_tmp_str_sellerid}) order by op_time) a group by a.seller_id,date_format(a.op_time,'%Y-%m-%d'))
								union all
								(select a.seller_id,a.match_time,a._type,'' as tmp from (select seller_id,date_format(match_time,'%Y-%m-%d %H:%i:%s') as match_time,'2' as _type,imei from statistic.stat_pc_detail_match_success where match_time like '2012-07%' and seller_id > 0 and seller_id regexp '^[0-9]+$' and seller_id in({$_tmp_str_sellerid}) order by match_time) a group by a.seller_id,a.imei)
								union all
								(select a.seller_id,a.op_time,a._type,'' as tmp from (select seller_id,date_format(op_time,'%Y-%m-%d %H:%i:%s') as op_time,'4' as _type,src_imei from statistic.stat_pc_detail_phone_copy where op_time like '2012-07%' and success_count > 0 and seller_id regexp '^[0-9]+$' and seller_id > 0 and op_status = 1 and seller_id in({$_tmp_str_sellerid}) order by op_time) a group by a.seller_id,date_format(a.op_time,'%Y-%m-%d'),a.src_imei)
								union all
								(select seller_id,date_format(op_time,'%Y-%m-%d %H:%i:%s') as op_time,if(path like '%update=1%',14,if(path like '%code3=200008%',11,if(op_time>='2012-07-21' and op_time<='2012-07-23' and (path like '%code3=1331%' or path like '%code3=1332%' or path like '%code3=200005%' or path like '%code3=200006%'),13,3))) as _type,imei as tmp from statistic.stat_pc_detail_download_install where content_type = 1 and op_type = 2 and error_code = 1 and seller_id regexp '^[0-9]+$' and seller_id > 0 and op_time like '2012-07%' and seller_id in({$_tmp_str_sellerid}) order by op_time)
								) a 
								order by a.active_time";
					$st 	   = mysql_query($sql_query);
					while ($rs = mysql_fetch_array($st,MYSQL_ASSOC)){
						//处理type为积分
						$rs['op_score'] = _get_type_score($rs['_type'],$rs['active_time'],$rs['tmp']);
						//echo $rs['_type'].'****'.$rs['active_time'].'****'.$rs['tmp'].'****'.$rs['op_score']."\r\n";
						$arr_tmp[$rs['seller_id']]['_score'] += intval($rs['op_score']);
						!isset($arr_tmp[$rs['seller_id']]['start_time']) && $arr_tmp[$rs['seller_id']]['start_time'] = $rs['active_time'];
						
						if ($arr_tmp[$rs['seller_id']]['_score']>=$max_score && !isset($arr_rs[$rs['seller_id']])){
							$_active_day = strtotime($rs['active_time']);
							$_create_time= strtotime($arr_tmp[$rs['seller_id']]['start_time']);
							$rs['start_time'] = $arr_tmp[$rs['seller_id']]['start_time'];
							$rs['ys'] = $_active_day-$_create_time;
							$arr_rs[$rs['seller_id']] = $rs;
						}
	//					if(count($arr_rs) > $max_len) break;
					}
					//print_r($arr_tmp);exit;
					usort($arr_rs, "sortself");
					$arr_rs    = array_slice($arr_rs,0,$max_len);
					foreach ($arr_rs as $k=>$v){
						$arr_rs[$k]['ys'] 				= filter_time($arr_rs[$k]['ys']);
						$arr_rs[$k]['salesperson_name'] = $arr_tmp[$v['seller_id']]['_name'];
					}
				}
				$str_query = '';
			}
		}catch (Exception $e){
			$e->getMessage();
		}
		return !empty($str_query)?DB::selectQueryAssoc($str_query):$arr_rs;
	}
	
	public function getIntergralInfo($pra){
		$month_s = '2012-05-01';//date('Y-m-01')
		$month_e = '2012-05-31';//date('Y-m-t')
		$_month  = '2012-05';//date('Y-m')
		$pra['month'] = '2012-05';
		try {
			if ($pra['type']=='a1'){
				$str_query = "select {str}
							from (SELECT sum(op_times*2) as score,salesperson_id,salesperson_name,char_length(imei) as ln,TYPE
							FROM tb_u_score_details
							left join channel.ch_salesperson as b on b.id = tb_u_score_details.salesperson_id
							WHERE active_day>='".$month_s."' and active_day <= '".$month_e."' and type=3 and char_length(imei)=40
							and b.agent_id in(select id from channel.ch_agent where ppath = {$pra['top_agent_id']} or find_in_set('{$pra['top_agent_id']},',ppath))
							group by salesperson_id
							) _tp";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(_tp.salesperson_id) as cut', $str_query):str_ireplace('{str}', '_tp.*', $str_query)." order by _tp.score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a2'){
				$str_query = "select {str}
							from (SELECT sum(op_times*2) as score,salesperson_id,salesperson_name,char_length(imei) as ln,TYPE
							FROM tb_u_score_details
							left join channel.ch_salesperson as b on b.id = tb_u_score_details.salesperson_id
							WHERE active_day>='".$month_s."' and active_day <= '".$month_e."' and type=3 and char_length(imei)!=40
							and b.agent_id in(select id from channel.ch_agent where ppath = {$pra['top_agent_id']} or find_in_set('{$pra['top_agent_id']},',ppath))
							group by salesperson_id
							) _tp";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(_tp.salesperson_id) as cut', $str_query):str_ireplace('{str}', '_tp.*', $str_query)." order by _tp.score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a3'){
				$str_query = "select {str}
							from (SELECT sum(if(type=7,20,if(type=8,10,50))) as score,salesperson_id,salesperson_name
							FROM tb_u_score_details
							left join channel.ch_salesperson as b on b.id = tb_u_score_details.salesperson_id
							WHERE active_day>='".$month_s."' and active_day <= '".$month_e."' and type in(7,8,10)
							and b.agent_id in(select id from channel.ch_agent where ppath = {$pra['top_agent_id']} or find_in_set('{$pra['top_agent_id']},',ppath))
							group by salesperson_id
							) _tp";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(_tp.salesperson_id) as cut', $str_query):str_ireplace('{str}', '_tp.*', $str_query)." order by _tp.score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a4'){
				$str_query = "select {str}
							from (select sum(score) as score,agent_id as salesperson_id,agent_name as salesperson_name
							from (
							select b.agent_id,a.salesperson_id,a.score,c.agent_name
							from tb_u_score_month a 
							left join channel.ch_salesperson b on a.salesperson_id = b.id
							left join channel.ch_agent_info c on b.agent_id = c.agent_id
							where a.top_agent_id = {$pra['top_agent_id']} and a.status = 1 and month='".$_month."'
							) tmp
							group by tmp.agent_id
							) _tp";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(_tp.salesperson_id) as cut', $str_query):str_ireplace('{str}', '_tp.*', $str_query)." order by _tp.score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a5'){
				$str_query = "select {str}
							from (select score,salesperson_id,salesperson_name
							from tb_u_score_month
							where status=1 and month='".$_month."' and top_agent_id = {$pra['top_agent_id']}
							) _tp";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(_tp.salesperson_id) as cut', $str_query):str_ireplace('{str}', '_tp.*', $str_query)." order by _tp.score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a6'){
				$arr_tmp   = array();
				$arr_rs    = '';
				$max_len   = 20;
				$max_score = 1000;//1000
//				$str_query = "select a.active_day,b.create_time,a.salesperson_id,a.salesperson_name,if(a.type=1,5,if(a.type=2,5*op_times,if(a.type=3,2*op_times,if(a.type=4,20,if(a.type=5,10,if(a.type=6,10,if(a.type=7,20,if(a.type=8,10,if(a.type=9,4*op_times,if(a.type=10,50,if(type=20,1000,0))))))))))) as _score
				$str_query = "select a.active_day,b.create_time,a.salesperson_id,a.salesperson_name,a.type,a.op_times
							from tb_u_score_details a 
							left join channel.ch_salesperson b on a.salesperson_id = b.id
							where b.create_time >= '".$month_s."' and b.create_time <= '".$month_e."' and a.active_day>='".$month_s."' and a.active_day <= '".$month_e."'
							and b.agent_id in(select id from channel.ch_agent where ppath = {$pra['top_agent_id']} or find_in_set('{$pra['top_agent_id']},',ppath))
							order by a.active_day asc";//order by a.active_day asc,_score desc";
				$st = mysql_query($str_query);
				while ($rs = mysql_fetch_array($st,MYSQL_ASSOC)){
//					$arr_tmp[$rs['salesperson_id']]['_score'] += intval($rs['_score']);
					$arr_tmp[$rs['salesperson_id']]['_score'] += getScoreCase($rs['type'],$rs['active_day'],$rs['op_times']);
					if ($arr_tmp[$rs['salesperson_id']]['_score']>=$max_score && !isset($arr_rs[$rs['salesperson_id']])){
						$rs['score'] = $arr_tmp[$rs['salesperson_id']]['_score'];
						$_active_day = $rs['active_day'] == date('Y-m-d',strtotime($rs['active_day']))?strtotime($rs['active_day'])+24*60*60:strtotime($rs['active_day']);
						$_create_time= strtotime($rs['create_time']);
						$rs['ys'] = $rs['score'] = $_active_day-$_create_time;
						$arr_rs[$rs['salesperson_id']] = $rs;
					}
//					if(count($arr_rs) > $max_len) break;
				}
				usort($arr_rs, "sortself");
				$arr_rs    = array_slice($arr_rs,0,$max_len);
				foreach ($arr_rs as $k=>$v){
					$arr_rs[$k]['score'] = filter_time($arr_rs[$k]['score']);
				}
				$str_query = '';
			}elseif ($pra['type']=='month'){
				$str_query = "select {str}
							from tb_u_score_month
							where status=1 and month='".$pra['month']."' and top_agent_id = {$pra['top_agent_id']}";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(salesperson_id) as cut', $str_query):str_ireplace('{str}', 'score,salesperson_id,salesperson_name', $str_query)." order by score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='all'){
				$str_query = "select {str}
							from tb_u_score
							where top_agent_id = {$pra['top_agent_id']}";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(salesperson_id) as cut', $str_query):str_ireplace('{str}', 'score,salesperson_id,salesperson_name,level', $str_query)." order by score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}
			return !empty($str_query)?DB::selectQuery($str_query):$arr_rs;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
}
function setLevel($id){
	$tmp_first = $tmp_sec = $tmp_thi = $tmp_four = array();
	$date= date('Y-m-d',strtotime("-1 days"));
	$sql = "select a.channel_id as id,a.channel_name,a.imei_count,stat_date from statistic.stat_pc_month_channel_imeis a,channel.ch_agent b
			where a.top_agent_id = b.pid and b.id = {$id} and a.stat_date = '{$date}'
			order by a.imei_count desc";
	
	$data= DB::selectQueryAssoc($sql);
	foreach ((array)$data as $k=>$v){
		if(count($tmp_first) < 1 && $v['imei_count'] > 4000){
			$tmp_first[] = $v['imei_count'];
			if ($id == $v['id']) return 'A';
			else continue;
		}
		if(count($tmp_sec) < 2 && $v['imei_count'] > 3000){
			$tmp_sec[] = $v['imei_count'];
			if ($id == $v['id']) return 'B';
			else continue;
		}
		if(count($tmp_thi) < 3 && $v['imei_count'] > 1500){
			$tmp_thi[] = $v['imei_count'];
			if ($id == $v['id']) return 'C';
			else continue;
		}
		$tmp_four[] = $v['imei_count'];
		if ($id == $v['id']) return 'D';
	}
}
function sortself($a,$b){
	if ($a == $b) return 0;
  	return ($a['ys'] > $b['ys']) ? 1 : -1;
}
function filter_time($diff){
	$str = '';
	if ($diff>=3600*24){
		$str .= floor($diff/3600/24).'天';
		$diff = $diff-(floor($diff/3600/24)*24*3600);
	}
	if ($diff>=3600){
		$str .= floor($diff/3600).'小时';
		$diff = $diff-(floor($diff/3600)*3600);
	}
	if ($diff>=60){
		$str .= floor($diff/60).'分';
		$diff = $diff-(floor($diff/60)*60);
	}
	if ($diff>=0){
		$str .= $diff.'秒';
	}
	return $str;
}
function _get_type_score($type,$time,$imei=''){
	global $cf_tmp_arr_imei;
	
	if (in_array($imei, $cf_tmp_arr_imei)) return 0;
	$cf_max_score = 100;
	$cf_score_app = array(
		'3' => 2,
		'11' => 3,
		'14' => 2,
		'13' => 2
	);
	if ($type == 7) return 20;
	elseif ($type == 10) return 50;
	elseif ($type == 1) return 5;
	elseif ($type == 2) return 5;
	elseif ($type == 4) return 20;
	elseif (in_array($type,array(3,11,14,13))){
		$time = date('Y-m-d',strtotime($time));
		if ($time >= '2012-07-11' && $time <= '2012-07-13'){
			$cf_score_app[11] = 4;
		}
		if ($time >= '2012-07-21' && $time <= '2012-07-23'){
			$cf_score_app[11] = 4;
			$cf_score_app[13] = 4;
		}
		$_rt_score = $cf_score_app[$type];
		$sql = "select sum(op_score) as score from channel2_portal.tb_u_score_details where type in(3,11,13,14) and active_day < '{$time}' and imei = '{$imei}'";
		$_rs = DB::selectQueryAssoc($sql);
		if (intval($_rs[0]['score']) >= $cf_max_score){
			$cf_tmp_arr_imei[] = $imei;
			return 0;
		}else return $cf_max_score-intval($_rs[0]['score'])>=$_rt_score?$_rt_score:$cf_max_score-intval($_rs[0]['score']);
	}
}