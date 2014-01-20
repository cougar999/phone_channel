<?php
class statLoginModel{
	private $_dbr;
	public function __construct(){
		session_start();
		DB::get_db_reader('stat');
	}
	
	public function __destruct(){
	}
	
	public function getStatShopLoginInfo_($pra){
		try {
			$pra['sortname']  = empty($pra['sortname'])?"shop_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
			$pra['name']      = trim($pra['name']);
			
			$channel_id = intval($pra['channel_id']);
			$lg_id      = intval($pra['lgid']);
			
			$str_query = "select {str}
						from stat_pc_day_shop_login
						where is_login in(0,1) and ".(empty($channel_id)?"":"channel_id = {$channel_id} and").(empty($lg_id)?"":"shop_id={$lg_id} and")." stat_date = '".$pra['date']."'
						";
			!empty($pra['name']) && $str_query.=" and shop_name like '%{$pra['name']}%'";
			
			$_tmp      = DB::selectQueryAssoc(str_ireplace('{str}', 'count(shop_id) as cut', $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			$str_query = str_ireplace('{str}', 'shop_id,shop_name,is_login', $str_query);
			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			foreach ($data['Rows'] as $k=>$v){
				$data['Rows'][$k]['OrderID'] = $k+1;
				$data['Rows'][$k]['is_login'] = $data['Rows'][$k]['is_login']?"是":"<font color=red>否</font>";
			}
			$str_query = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatShopLoginInfo($pra){
		try {
			$pra['sortname']  = empty($pra['sortname'])?"shop_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
			$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
			$pra['name']      = trim($pra['name']);
			$pra['dotype']    = empty($pra['dotype'])?'lag':trim($pra['dotype']);
			
			$channel_id = intval($pra['channel_id']);
			$lg_id      = intval($pra['lgid']);
			
			$str_query = "select {str}
						from stat_pc_day_shop_login
						where is_login in(0,1) and ".(empty($channel_id)?"":"channel_id = {$channel_id} and").(empty($lg_id)?"":"shop_id={$lg_id} and")." stat_date >= '".$pra['date']."' and stat_date <= '{$pra['end_date']}'
						";
			!empty($pra['name']) && $str_query.=" and shop_name like '%{$pra['name']}%'";
			
			if ($pra['_act']  == 'cut'){
				$_tmp      = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct shop_id) as cut', $str_query));
				$data['Total'] = $_tmp[0]['cut'];
				return $data;
			}
			
			for($i=strtotime($pra['date']);$i<=strtotime($pra['end_date']);$i+=86400){
				$cur_day = date("d",$i);
				$cur_mth = date("Y-m-d",$i);
				$_str   .= "sum(if(stat_date='{$cur_mth}',is_login,0)) as d_".date('m',$i).$cur_day."_y,";
			}
			
			if ($pra['dotype'] == 'db'){
				$str_query = str_ireplace('{str}', $_str.'shop_id,shop_name', $str_query);
				$str_query.= " group by shop_id";
				$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
				$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
				$data['Rows']  = DB::selectQueryAssoc($str_query);
			}else{
				$_tmp_do_data  = $_tmp_rt_data = array();
				$str_query 	   = str_ireplace('{str}', 'stat_date,is_login,shop_id,shop_name', $str_query);
				$_tmp_do_data  = DB::selectQueryAssoc($str_query);
				foreach ($_tmp_do_data as $item){
					$_tmp_rt_data[$item['shop_id']]['shop_name'] = iconv('utf-8', 'gbk//ignore', $item['shop_name']);
					$_tmp_rt_data[$item['shop_id']]['shop_id']   = $item['shop_id'];
					$_tmp_rt_data[$item['shop_id']]["d_".date('m',strtotime($item['stat_date'])).date('d',strtotime($item['stat_date']))."_y"] = $item['is_login'];
				}
				$_tmp_rt_data = sortArr($_tmp_rt_data,$pra['sortname'],strtoupper("SORT_".$pra['sortorder']));
				foreach ($_tmp_rt_data as $k=>$v){
					$_tmp_rt_data[$k]['shop_name'] = iconv('gbk', 'utf-8//ignore', $v['shop_name']);
				}
				$_tmp_rt_data = array_splice($_tmp_rt_data,($pra['page']-1)*$pra['pagesize'],$pra['pagesize']);
				$data['Rows'] = array_values($_tmp_rt_data);
			}
			
			$str_query = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatLoginInfo($pra){
		try {
			$pra['sortname']  = empty($pra['sortname'])?"channel_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
			$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
			$pra['name']      = trim($pra['name']);
			$pra['dotype']    = empty($pra['dotype'])?'lag':trim($pra['dotype']);
			
			if ($pra['page']==1){
//				$str_query = "select sum(if(stat_type=1,stat_count,0)) as login_ytc,sum(if(stat_type=2,stat_count,0)) as login_ntc
//							from stat_pc_day_channel_login
//							where stat_type in(1,2) and top_agent_id = {$pra['top_agent_id']}
//							and stat_date = '".$pra['date']."'";
//				!empty($pra['name']) && $str_query.=" and channel_name like '%{$pra['name']}%'";
//				
//				$_tmp      = DB::selectQueryAssoc($str_query);
//				$data['login_ytc'] = $_tmp[0]['login_ytc'];
//				$data['login_ntc'] = $_tmp[0]['login_ntc'];
			}
			$str_query = "select {str}
						from stat_pc_day_channel_login
						where stat_type in(1,2) and top_agent_id = {$pra['top_agent_id']} and stat_date >= '".$pra['date']."' and stat_date <= '{$pra['end_date']}'
						";
			!empty($pra['name']) && $str_query.=" and channel_name like '%{$pra['name']}%'";
			
			if ($pra['_act']  == 'cut'){
				$_tmp          = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct channel_id) as cut', $str_query));
				$data['Total'] = intval($_tmp[0]['cut']);
				return $data;
			}
			$__tmp_day	   = array();
			
			for($i=strtotime($pra['date']);$i<=strtotime($pra['end_date']);$i+=86400){
				$cur_day = date("d",$i);
				$cur_mth = date("Y-m-d",$i);
				$_str   .= "sum(if(stat_date='{$cur_mth}' and stat_type=1,stat_count,0)) as d_".date('m',$i).$cur_day."_y,";
				$_str   .= "sum(if(stat_date='{$cur_mth}' and stat_type=2,stat_count,0)) as d_".date('m',$i).$cur_day."_n,";
				$__tmp_day[] = "d_".date('m',$i).$cur_day."_y";
				$__tmp_day[] = "d_".date('m',$i).$cur_day."_n";
			}
			
			if ($pra['dotype'] == 'db'){
				$str_query = str_ireplace('{str}', $_str.'channel_name,channel_id', $str_query);
				$str_query.= " group by channel_id";
				$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
				$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
				$data['Rows']  = DB::selectQueryAssoc($str_query);
			}else{
				$_tmp_do_data  = $_tmp_rt_data = array();
				$str_query 	   = str_ireplace('{str}', 'stat_type,stat_date,stat_count,channel_name,channel_id', $str_query);
				$_tmp_do_data  = DB::selectQueryAssoc($str_query);
				foreach ($_tmp_do_data as $item){
					$_tmp_rt_data[$item['channel_id']]['channel_name'] = iconv('utf-8', 'gbk//ignore', $item['channel_name']);
					$_tmp_rt_data[$item['channel_id']]['channel_id']   = $item['channel_id'];
					$_tmp_rt_data[$item['channel_id']]["d_".date('m',strtotime($item['stat_date'])).date('d',strtotime($item['stat_date'])).($item['stat_type']==1?"_y":"_n")] += $item['stat_count'];
				}
				$_tmp_rt_data = sortArr($_tmp_rt_data,$pra['sortname'],strtoupper("SORT_".$pra['sortorder']));
				foreach ($_tmp_rt_data as $k=>$v){
					$_tmp_rt_data[$k]['channel_name'] = iconv('gbk', 'utf-8//ignore', $v['channel_name']);
				}
				$_tmp_rt_data = array_splice($_tmp_rt_data,($pra['page']-1)*$pra['pagesize'],$pra['pagesize']);
				$data['Rows'] = array_values($_tmp_rt_data);
			}
			
			if (!$pra['total'] && !!$data['Rows']){
				$tmp_total     = array();
				foreach ($data['Rows'] as $k=>$v){
					foreach ($__tmp_day as $item){
						$tmp_total[$item] += intval($v[$item]);
					}
				}
				$tmp_total['channel_name'] = '汇总';
				$data['Rows'][]= $tmp_total;
			}
			$str_query = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatLoginAllData($pra){
		try {
			$pra['sortname']  = empty($pra['sortname'])?"channel_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
			$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
			$pra['name']      = trim($pra['name']);
			$pra['dotype']    = empty($pra['dotype'])?'lag':trim($pra['dotype']);
			
			$str_query = "select {str}
						from statistic.stat_pc_day_channel_login a,channel.ch_agent b
						where a.top_agent_id = b.id and a.stat_type in(1,2) and a.stat_date >= '".$pra['date']."' and a.stat_date <= '{$pra['end_date']}'
						";
			!empty($pra['name']) && $str_query.=" and b.name like '%{$pra['name']}%'";
			$_SESSION['isSup'] && $str_query.=" and a.top_agent_id <> 132";
			
			if ($pra['_act']  == 'cut'){
				$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct a.top_agent_id) as cut', $str_query));
				$data['Total'] = $_tmp[0]['cut'];
				return $data;
			}
			$__tmp_day     = array();
			
			for($i=strtotime($pra['date']);$i<=strtotime($pra['end_date']);$i+=86400){
				$cur_day = date("d",$i);
				$cur_mth = date("Y-m-d",$i);
				$_str   .= "sum(if(a.stat_date='{$cur_mth}' and a.stat_type=1,a.stat_count,0)) as d_".date('m',$i).$cur_day."_y,";
				$_str   .= "sum(if(a.stat_date='{$cur_mth}' and a.stat_type=2,a.stat_count,0)) as d_".date('m',$i).$cur_day."_n,";
				$__tmp_day[] = "d_".date('m',$i).$cur_day."_y";
				$__tmp_day[] = "d_".date('m',$i).$cur_day."_n";
			}
			
			if ($pra['dotype'] == 'db'){
				$str_query = str_ireplace('{str}', $_str.'b.name,a.top_agent_id', $str_query);
				$str_query.= " group by a.top_agent_id";
				$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
				$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
				$data['Rows']  = DB::selectQueryAssoc($str_query);
			}else{
				$_tmp_do_data  = $_tmp_rt_data = array();
				$str_query 	   = str_ireplace('{str}', 'a.stat_type,a.stat_date,a.stat_count,b.name,a.top_agent_id', $str_query);
				$_tmp_do_data  = DB::selectQueryAssoc($str_query);
				foreach ($_tmp_do_data as $item){
					$_tmp_rt_data[$item['top_agent_id']]['name'] = iconv('utf-8', 'gbk//ignore', $item['name']);
					$_tmp_rt_data[$item['top_agent_id']]['top_agent_id'] = $item['top_agent_id'];
					$_tmp_rt_data[$item['top_agent_id']]["d_".date('m',strtotime($item['stat_date'])).date('d',strtotime($item['stat_date'])).($item['stat_type']==1?"_y":"_n")] += $item['stat_count'];
				}
				$_tmp_rt_data = sortArr($_tmp_rt_data,$pra['sortname'],strtoupper("SORT_".$pra['sortorder']));
				foreach ($_tmp_rt_data as $k=>$v){
					$_tmp_rt_data[$k]['name'] = iconv('gbk', 'utf-8//ignore', $v['name']);
				}
				$_tmp_rt_data = array_splice($_tmp_rt_data,($pra['page']-1)*$pra['pagesize'],$pra['pagesize']);
				$data['Rows'] = array_values($_tmp_rt_data);
			}
		
			if (!$pra['total'] && !!$data['Rows']){
				$tmp_total     = array();
				foreach ($data['Rows'] as $k=>$v){
					foreach ($__tmp_day as $item){
						$tmp_total[$item] += intval($v[$item]);
					}
				}
				$tmp_total['name'] = '汇总';
				$data['Rows'][]= $tmp_total;
			}
			$str_query = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatLoginInfo_($pra){
		try {
			$pra['sortname']  = empty($pra['sortname'])?"channel_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
			$pra['name']      = trim($pra['name']);
			
			if ($pra['page']==1){
				$str_query = "select sum(if(stat_type=1,stat_count,0)) as login_ytc,sum(if(stat_type=2,stat_count,0)) as login_ntc
							from stat_pc_day_channel_login
							where stat_type in(1,2) and top_agent_id = {$pra['top_agent_id']}
							and stat_date = '".$pra['date']."'";
				!empty($pra['name']) && $str_query.=" and channel_name like '%{$pra['name']}%'";
				
				$_tmp      = DB::selectQueryAssoc($str_query);
				$data['login_ytc'] = $_tmp[0]['login_ytc'];
				$data['login_ntc'] = $_tmp[0]['login_ntc'];
			}
			$str_query = "select {str}
						from stat_pc_day_channel_login
						where stat_type in(1,2) and top_agent_id = {$pra['top_agent_id']} and stat_date = '".$pra['date']."'
						";
			!empty($pra['name']) && $str_query.=" and channel_name like '%{$pra['name']}%'";
			
			$_tmp      = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct channel_id) as cut', $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			$str_query = str_ireplace('{str}', 'channel_name,channel_id,sum(if(stat_type=1,stat_count,0)) as login_y,sum(if(stat_type=2,stat_count,0)) as login_n', $str_query);
			$str_query.= " group by channel_id";
			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			$str_query = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatUseSketchInfo($pra){
		try {
			$pra['sortname']  = empty($pra['sortname'])?"channel_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
			$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
			$pra['name']      = trim($pra['name']);
			$pra['channel_id']= intval($pra['channel_id']);
			
			$str_query = "select {str}
						from statistic.stat_pc_day_use_channel_summary a left join statistic.stat_pc_day_channel_login b 
						on (a.channel_id = b.channel_id and a.stat_date = b.stat_date)
						where a.top_agent_id = '{$pra['top_agent_id']}' and b.stat_type = 1 and a.stat_date >= '{$pra['date']}' and a.stat_date <= '{$pra['end_date']}'";
			!empty($pra['name']) && $str_query.=" and a.channel_name like '%{$pra['name']}%'";
			!empty($pra['channel_id']) && $str_query.=" and a.channel_id = {$pra['channel_id']}";
			
			$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct a.channel_id) as cut', $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			$tmp_str_q     = 'a.channel_id,a.channel_name,b.stat_count as login_count,a.top_agent_id,
							sum(if(a.stat_type=1,a.stat_count,0)) as phone_connect,
							sum(if(a.stat_type=2,a.stat_count,0)) as iso_phone_connect,
							sum(if(a.stat_type=3,a.stat_count,0)) as apk_phone_connect,
							sum(if(a.stat_type=4,a.stat_count,0)) as iso_soft_dl,
							sum(if(a.stat_type=5,a.stat_count,0)) as apk_soft_dl,
							sum(if(a.stat_type=6,a.stat_count,0)) as iso_card_act,
							sum(if(a.stat_type=7,a.stat_count,0)) as dl_card_act';
			$str_query = str_ireplace('{str}', $tmp_str_q, $str_query);
			$str_query.= " group by a.channel_id";
			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			$tmp_total     = array();
			foreach ($data['Rows'] as $k=>$v){
				$sql = "select sum(stat_count) as login_count 
						from statistic.stat_pc_day_channel_login 
						where channel_id in({$v['channel_id']}) and stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}' and stat_type = 1";
				$_rs = DB::selectQueryAssoc($sql);
				
				$data['Rows'][$k]['login_count'] = $_rs[0]['login_count'];
				$tmp_total['login_count'] +=intval( $_rs[0]['login_count']);
				$tmp_total['phone_connect'] +=intval( $v['phone_connect']);
				$tmp_total['iso_phone_connect'] +=intval( $v['iso_phone_connect']);
				$tmp_total['apk_phone_connect'] +=intval( $v['apk_phone_connect']);
				$tmp_total['iso_soft_dl'] +=intval( $v['iso_soft_dl']);
				$tmp_total['apk_soft_dl'] +=intval( $v['apk_soft_dl']);
				$tmp_total['iso_card_act'] +=intval( $v['iso_card_act']);
				$tmp_total['dl_card_act'] +=intval( $v['dl_card_act']);
			}
			$tmp_total['channel_name'] = '汇总';
			$data['Rows'][]= $tmp_total;
			$str_query 	   = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatUseSketchAllData($pra){
		try {
			$pra['sortname']  = empty($pra['sortname'])?"name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
			$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
			$pra['name']      = trim($pra['name']);
			
			$str_query = "select {str}
						from statistic.stat_pc_day_use_channel_summary a left join statistic.stat_pc_day_channel_login b 
						on (a.channel_id = b.channel_id and a.stat_date = b.stat_date)
						left join channel.ch_agent c on a.top_agent_id = c.id
						where b.stat_type = 1 and a.stat_date >= '{$pra['date']}' and a.stat_date <= '{$pra['end_date']}'";
			!empty($pra['name']) && $str_query.=" and c.name like '%{$pra['name']}%'";
			$_SESSION['isSup'] && $str_query.=" and a.top_agent_id <> 132";
			
			$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct a.top_agent_id) as cut', $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			$tmp_str_q     = 'a.top_agent_id,c.name,b.stat_count as login_count,
							sum(if(a.stat_type=1,a.stat_count,0)) as phone_connect,
							sum(if(a.stat_type=2,a.stat_count,0)) as iso_phone_connect,
							sum(if(a.stat_type=3,a.stat_count,0)) as apk_phone_connect,
							sum(if(a.stat_type=4,a.stat_count,0)) as iso_soft_dl,
							sum(if(a.stat_type=5,a.stat_count,0)) as apk_soft_dl,
							sum(if(a.stat_type=6,a.stat_count,0)) as iso_card_act,
							sum(if(a.stat_type=7,a.stat_count,0)) as dl_card_act';
			$str_query = str_ireplace('{str}', $tmp_str_q, $str_query);
			$str_query.= " group by a.top_agent_id";
			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			$tmp_total     = array();
			foreach ($data['Rows'] as $k=>$v){
				$sql = "select sum(stat_count) as login_count 
						from statistic.stat_pc_day_channel_login 
						where channel_id in(select channel_id from statistic.stat_pc_day_use_channel_summary where top_agent_id = {$v['top_agent_id']} and stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}') and stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}' and stat_type = 1";
				$_rs = DB::selectQueryAssoc($sql);
				
				$data['Rows'][$k]['login_count'] = $_rs[0]['login_count'];
				$tmp_total['login_count'] +=intval( $_rs[0]['login_count']);
				$tmp_total['phone_connect'] +=intval( $v['phone_connect']);
				$tmp_total['iso_phone_connect'] +=intval( $v['iso_phone_connect']);
				$tmp_total['apk_phone_connect'] +=intval( $v['apk_phone_connect']);
				$tmp_total['iso_soft_dl'] +=intval( $v['iso_soft_dl']);
				$tmp_total['apk_soft_dl'] +=intval( $v['apk_soft_dl']);
				$tmp_total['iso_card_act'] +=intval( $v['iso_card_act']);
				$tmp_total['dl_card_act'] +=intval( $v['dl_card_act']);
			}
			$tmp_total['name'] = '汇总';
			$data['Rows'][]= $tmp_total;
			$str_query 	   = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatUseSketChnInfo($pra){
		try {
			$pra['sortname']  = empty($pra['sortname'])?"channel_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
			$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
			$pra['name']      = trim($pra['name']);
			$pra['channel_id']= intval($pra['channel_id']);
			$pra['shop_id']   = intval($pra['shop_id']);
			$pra['total']   = intval($pra['total']);
			
			$str_query = "select {str}
						from statistic.stat_pc_day_use_channel_summary
						where top_agent_id = '{$pra['top_agent_id']}' and stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}'";
			!empty($pra['name']) && $str_query.=" and (channel_name like '%{$pra['name']}%')";
			!empty($pra['channel_id']) && $str_query.=" and channel_id = {$pra['channel_id']}";
			!empty($pra['shop_id']) && $str_query.=" and shop_id = {$pra['shop_id']}";
			
			if ($pra['_act'] == 'cut'){
				$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct channel_id) as cut', $str_query));
				$data['Total'] = $_tmp[0]['cut'];
				return $data;
			}
			
			$tmp_str_q     = 'channel_id,channel_name,
							sum(if(stat_type=1,stat_count,0)) phone_connect,
							sum(if(stat_type=4,stat_count,0)) iso_soft_dl,
							sum(if(stat_type=5,stat_count,0)) apk_soft_dl,
							sum(if(stat_type=6,stat_count,0)) iso_card_act,
							sum(if(stat_type=7,stat_count,0)) dl_card_act';
			$str_query = str_ireplace('{str}', $tmp_str_q, $str_query);
			$str_query.= " group by channel_id";
			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			$tmp_total     = array();
			if (!$pra['total'] && !!$data['Rows']){
				foreach ($data['Rows'] as $k=>$v){
					$tmp_total['phone_connect'] += intval($v['phone_connect']);
					$tmp_total['iso_soft_dl'] += intval($v['iso_soft_dl']);
					$tmp_total['apk_soft_dl'] += intval($v['apk_soft_dl']);
					$tmp_total['iso_card_act'] += intval($v['iso_card_act']);
					$tmp_total['dl_card_act'] += intval($v['dl_card_act']);
				}
				$tmp_total['shop_name'] = '汇总';
				$data['Rows'][]= $tmp_total;
			}
			$str_query 	   = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatUseSketChnAllDataIndex($pra){
		$str_query = "select
					a.stat_date,
					sum(if(a.stat_type=2,a.stat_count,0)) as ios_phone_connect,
					sum(if(a.stat_type=3,a.stat_count,0)) as apk_phone_connect,
					sum(if(a.stat_type=4,a.stat_count,0)) as ios_soft_dl,
					sum(if(a.stat_type=5,a.stat_count,0)) as apk_soft_dl,
					sum(if(a.stat_type=6,a.stat_count,0)) as iso_card_act,
					sum(if(a.stat_type=7,a.stat_count,0)) as dl_card_act
					from statistic.stat_pc_day_use_shop_summary a
					where 1
					";
		if ($_SESSION['isSup']) $str_query.=" and a.top_agent_id <> 132";
		else{
			$_SESSION['level']==1 && $str_query.=" and a.top_agent_id = {$_SESSION['real_id']}";
			$_SESSION['level']==2 && $str_query.=" and a.channel_id = {$_SESSION['real_id']}";
			$_SESSION['level']==3 && $str_query.=" and a.shop_id = {$_SESSION['real_id']}";
		}
		$str_query.= " group by a.stat_date order by a.stat_date desc limit 2";
		
		return DB::selectQueryAssoc($str_query);
	}
	
	public function getStatLoginSketChnAllDataIndex($pra){
		$str_query = "select
					b.pid,
					a.stat_date,
					sum(if(a.is_login=1,1,0)) as login_y,
					sum(if(a.is_login=0,1,0)) as login_n,
					sum(1) as login_t
					from stat_pc_day_shop_login a,channel.ch_agent b 
					where a.channel_id = b.id and a.is_login in(0,1)
					";
		if ($_SESSION['isSup']) $str_query.=" and b.pid <> 132";
		else{
			$_SESSION['level']==1 && $str_query.=" and b.pid = {$_SESSION['real_id']}";
			$_SESSION['level']==2 && $str_query.=" and a.channel_id = {$_SESSION['real_id']}";
			$_SESSION['level']==3 && $str_query.=" and a.shop_id = {$_SESSION['real_id']}";
		}
		$str_query.= " group by a.stat_date order by a.stat_date desc limit 2";
		
		return DB::selectQueryAssoc($str_query);
	}
	
	public function getStatGoldSketChnAllDataIndex($pra){
		global $__CF__;
		$cf_gole = array(
			'1'  => array('table'=>'statistic.stat_pc_day_top_agent_goldcoin'),
			'01' => array('table'=>'statistic.stat_pc_day_top_agent_goldcoin'),
			'02' => array('table'=>'statistic.stat_pc_day_channel_goldcoin'),
			'03' => array('table'=>'statistic.stat_pc_day_shop_goldcoin')
		);
		$str_query = "select
					stat_date,
					sum(if(mission_type in(1,3,4),total_coins,0))/{$__CF__['gold']['conver']} gold_total,
					sum(if(mission_type=1,total_coins,0))/{$__CF__['gold']['conver']} gold_login,
					sum(if(mission_type=2,total_coins,0))/{$__CF__['gold']['conver']} gold_connect,
					sum(if(mission_type=3,total_coins,0))/{$__CF__['gold']['conver']} gold_install,
					sum(if(mission_type=4,total_coins,0))/{$__CF__['gold']['conver']} gold_copy
					from {$cf_gole[$_SESSION['isSup'].$_SESSION['level']]['table']}
					where 1
					";
		if ($_SESSION['isSup']) {}//$str_query.=" and top_agent_id <> 132";
		else{
			$_SESSION['level']==1 && $str_query.=" and top_agent_id = {$_SESSION['real_id']}";
			$_SESSION['level']==2 && $str_query.=" and channel_id = {$_SESSION['real_id']}";
			$_SESSION['level']==3 && $str_query.=" and shop_id = {$_SESSION['real_id']}";
		}
		$str_query.= " group by stat_date order by stat_date desc limit 2";
		return DB::selectQueryAssoc($str_query);
	}
	
	public function getStatDecisionShopDataInfo($pra){
		$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
		$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
		$pra['condition'] = empty($pra['condition'])?'usage_rate':$pra['condition'];
		$pra['shop_id']   = intval($pra['shop_id']);
		
		$sql = "
				select {$pra['condition']},stat_date
				from statistic.stat_pc_day_shop_analysis
				where stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}' and shop_id = {$pra['shop_id']}
				group by stat_date
				order by stat_date
		";
		return DB::selectQuery($sql);//Assoc
	}
	
	public function getStatDecisionShopData($pra){
		//权限设定
		if(!$_SESSION['isSup']&&empty($_SESSION['level'])) return array('Total'=>0,'Rows'=>array('average'=>array(),'average'=>array()));
		if((!$pra['do_id']||'null'==$pra['do_id']) && !$_SESSION['isSup']) $pra['do_id'] = $_SESSION['real_id'];
		
		$pra['sortname']  = empty($pra['sortname'])?"shop_name":$pra['sortname'];
		$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
		$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
		$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
		$pra['name']      = trim($pra['name']);
		$pra['do_id']     = intval($pra['do_id']);
		$pra['total']     = intval($pra['total']);
		$day_diff         = (strtotime($pra['end_date'])-strtotime($pra['date']))/3600/24+1;
		/* if(!empty($pra['selId']) && empty($pra['do_id'])){
			$_tmp = explode(',',$pra['selId']);
			$pra['do_id'] = end($_tmp);
		} */
		$str_query = "select
					{str}
					from statistic.stat_pc_day_shop_analysis
					where stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}'
					";
		!empty($pra['name']) && $str_query.=" and shop_name like '%{$pra['name']}%'";
		!empty($pra['province']) && $str_query.=" and province = '{$pra['province']}'";
		!empty($pra['do_id']) && $str_query.=" and (top_agent_id={$pra['do_id']} or channel_id={$pra['do_id']} or shop_id={$pra['do_id']})";

		if ($pra['_act']  == 'cut'){
			$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', "count(distinct shop_id) as cut", $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			return $data;
		}
		$tmp_str_q = "
					shop_name,shop_id,
					sum(daily_sales) daily_sales,
					sum(match_android) match_android,
					sum(match_ios) match_ios,
					".(1==$day_diff?'usage_rate':'sum(matchs)/sum(daily_sales)')." usage_rate,
					sum(phone_android) phone_android,
					".(1==$day_diff?'conversion_rate_android':'sum(phone_android)/sum(match_android)')." conversion_rate_android,
					sum(phone_ios) phone_ios,
					".(1==$day_diff?'conversion_rate_ios':'sum(phone_ios)/sum(match_ios)')." conversion_rate_ios,
					sum(soft_android) soft_android,
					sum(soft_ios) soft_ios,
					sum(phone_one_key_install) phone_one_key_install,
					".(1==$day_diff?'usage_rate_one_key_install':'sum(usage_rate_one_key_install)/sum(phones)')." usage_rate_one_key_install,
					sum(biz_soft_android) biz_soft_android,
					sum(biz_soft_active) biz_soft_active,
					".(1==$day_diff?'arup_ios':'sum(soft_ios)/sum(phone_ios)')." arup_ios,
					".(1==$day_diff?'arup_android':'sum(soft_android)/sum(phone_android)')." arup_android,
					".(1==$day_diff?'arup_android_biz':'sum(biz_soft_android)/sum(phone_android)')." arup_android_biz,
					".(1==$day_diff?'single_cost':'sum(goldgoins)/sum(phones)')." single_cost,
					last_contact last_contact,
					is_notice is_notice
					";
		$str_query = str_ireplace('{str}', $tmp_str_q, $str_query);
		$str_query.= " group by shop_id";
		$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
		$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
		$data['Rows']['analysis']  = DB::selectQueryAssoc($str_query);
		//获取平均值
		$sql = "
				select 
				".(1==$day_diff?'usage_rate':'sum(matchs)/sum(daily_sales)')." usage_rate,
				".(1==$day_diff?'arup_android':'sum(soft_android)/sum(phone_android)')." arup_android,
				".(1==$day_diff?'arup_ios':'sum(soft_ios)/sum(phone_ios)')." arup_ios,
				".(1==$day_diff?'arup_android_biz':'sum(biz_soft_android)/sum(phone_android)')." arup_android_biz,
				".(1==$day_diff?'conversion_a_rate':'sum(phone_android)/sum(matchs_android)')." conversion_a_rate,
				".(1==$day_diff?'conversion_i_rate':'sum(phone_ios)/sum(matchs_ios)')." conversion_i_rate,
				".(1==$day_diff?'single_cost':'sum(goldcoins)/sum(phones)')." single_cost,
				".(1==$day_diff?'usage_rate_one_key_install':'sum(phone_one_key_install)/sum(phones)')." usage_rate_one_key_install
				from statistic.stat_pc_day_daily_average
				where stat_date>='{$pra['date']}' and stat_date<='{$pra['end_date']}'
				";
		$data['Rows']['average']  = DB::selectQueryAssoc($sql);
		$tmp_total = array();
		if (!$pra['total'] && !!$data['Rows']['analysis']){
			/*
			foreach ($data['Rows'] as $k=>$v){
				$tmp_total['gold_login'] += floatval($v['gold_login']);
				$tmp_total['gold_connect'] += floatval($v['gold_connect']);
				$tmp_total['gold_install'] += floatval($v['gold_install']);
				$tmp_total['gold_copy'] += floatval($v['gold_copy']);
				$tmp_total['gold_total'] += floatval($v['gold_total']);
			}
			$tmp_total['name'] = '汇总';
			$data['Rows'][]= $tmp_total;
			*/
		}
		$str_query 	   = '';
		return !empty($str_query)?DB::selectQuery($str_query):$data;
	}
	
	public function getStatGoldAllData($pra){
		global $__CF__;
		$cf_gole = array(
			'1'  => array('table'=>'statistic.stat_pc_day_top_agent_goldcoin','name'=>'top_agent_name','id'=>'top_agent_id'),
			'01' => array('table'=>'statistic.stat_pc_day_channel_goldcoin','name'=>'channel_name','id'=>'channel_id','c_id'=>'top_agent_id'),
			'02' => array('table'=>'statistic.stat_pc_day_shop_goldcoin','name'=>'shop_name','id'=>'shop_id','c_id'=>'channel_id'),
			'03' => array('table'=>'statistic.stat_pc_day_shop_goldcoin','name'=>'shop_name','id'=>'shop_id','c_id'=>'shop_id')
		);
		$pra['sortname']  = empty($pra['sortname'])?"name":$pra['sortname'];
		$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
		$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
		$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
		$pra['name']      = trim($pra['name']);
		$pra['do_sp']     = intval($pra['do_sp']);
		$pra['do_level']  = trim($pra['do_level']);
		$pra['do_id']     = intval($pra['do_id']);
		$pra['total']     = intval($pra['total']);
		
		$str_query = "select
					{str}
					from {$cf_gole[$pra['do_sp'].$pra['do_level']]['table']}
					where stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}'
					";
		!empty($pra['name']) && $str_query.=" and {$cf_gole[$pra['do_sp'].$pra['do_level']]['name']} like '%{$pra['name']}%'";
		isset($cf_gole[$pra['do_sp'].$pra['do_level']]['c_id']) && $str_query.=" and {$cf_gole[$pra['do_sp'].$pra['do_level']]['c_id']} = {$pra['do_id']}";

		if ($pra['_act']  == 'cut'){
			$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', "count(distinct {$cf_gole[$pra['do_sp'].$pra['do_level']]['id']}) as cut", $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			return $data;
		}
		$tmp_str_q = "
					{$cf_gole[$pra['do_sp'].$pra['do_level']]['id']} do_id,
					{$cf_gole[$pra['do_sp'].$pra['do_level']]['name']} name,
					agent_level,
					sum(if(mission_type in(1,3,4),total_coins,0))/{$__CF__['gold']['conver']} gold_total,
					sum(if(mission_type=1,total_coins,0))/{$__CF__['gold']['conver']} gold_login,
					sum(if(mission_type=2,total_coins,0))/{$__CF__['gold']['conver']} gold_connect,
					sum(if(mission_type=3,total_coins,0))/{$__CF__['gold']['conver']} gold_install,
					sum(if(mission_type=4,total_coins,0))/{$__CF__['gold']['conver']} gold_copy";
		$str_query = str_ireplace('{str}', $tmp_str_q, $str_query);
		$str_query.= " group by {$cf_gole[$pra['do_sp'].$pra['do_level']]['id']}";
		$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
		$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
		
		$data['Rows']  = DB::selectQueryAssoc($str_query);
		$tmp_total     = array();
		if (!$pra['total'] && !!$data['Rows']){
			foreach ($data['Rows'] as $k=>$v){
				$tmp_total['gold_login'] += floatval($v['gold_login']);
				$tmp_total['gold_connect'] += floatval($v['gold_connect']);
				$tmp_total['gold_install'] += floatval($v['gold_install']);
				$tmp_total['gold_copy'] += floatval($v['gold_copy']);
				$tmp_total['gold_total'] += floatval($v['gold_total']);
			}
			$tmp_total['name'] = '汇总';
			$data['Rows'][]= $tmp_total;
		}
		$str_query 	   = '';
		return !empty($str_query)?DB::selectQuery($str_query):$data;
	}
	
	public function getStatGoldDetailData($pra){
		$pra['sortname']  = empty($pra['sortname'])?"operation_time":$pra['sortname'];
		$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
		$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
		$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
		$pra['name']      = trim($pra['name']);
		$pra['stu']       = trim($pra['stu']);
		
		$str_query = "select
					{str}
					from statistic.stat_pc_day_goldcoin_detail
					where stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}'
					";
		!empty($pra['top_agent_id']) && $str_query .= " and top_agent_id = {$pra['top_agent_id']}";
		!empty($pra['channel_id']) && $str_query .= " and channel_id = {$pra['channel_id']}";
		!empty($pra['shop_id']) && $str_query .= " and shop_id = {$pra['shop_id']}";
		$pra['stu']!='' && $str_query .= " and mission_status in(".($pra['stu']==0?'0':'1,2,3').")";

		if ($pra['_act']  == 'cut'){
			$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', "count(id) as cut", $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			return $data;
		}
		$tmp_str_q = "
					top_agent_name,
					channel_name,
					shop_name,
					seller_real_name,
					mission_status,
					mission_status is_valid_status,
					goldcoin,
					operation_time,
					mission_desc,
					mission_status mission_completion_status,
					remarks
					";
		$str_query = str_ireplace('{str}', $tmp_str_q, $str_query);
		$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
		$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
		
		$data['Rows']  = DB::selectQueryAssoc($str_query);
		$str_query 	   = '';
		return !empty($str_query)?DB::selectQuery($str_query):$data;
	}
	
	public function getStatUseSketChnAllData($pra){
		try {
			$pra['sortname']  = empty($pra['sortname'])?"name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
			$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
			$pra['name']      = trim($pra['name']);
			$pra['channel_id']= intval($pra['channel_id']);
			$pra['shop_id']   = intval($pra['shop_id']);
			$pra['total']   = intval($pra['total']);
			
			$str_query = "select {str}
						from statistic.stat_pc_day_use_channel_summary a,channel.ch_agent b
						where a.top_agent_id = b.id and a.stat_date >= '{$pra['date']}' and a.stat_date <= '{$pra['end_date']}'";
			!empty($pra['name']) && $str_query.=" and b.name like '%{$pra['name']}%'";
			$_SESSION['isSup'] && $str_query.=" and a.top_agent_id <> 132";
//			!empty($pra['channel_id']) && $str_query.=" and channel_id = {$pra['channel_id']}";
//			!empty($pra['shop_id']) && $str_query.=" and shop_id = {$pra['shop_id']}";
			
			if ($pra['_act'] == 'cut'){
				$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct a.top_agent_id) as cut', $str_query));
				$data['Total'] = $_tmp[0]['cut'];
				return $data;
			}
			
			$tmp_str_q     = 'a.top_agent_id,b.name,
							sum(if(a.stat_type=1,a.stat_count,0)) as phone_connect,
							sum(if(a.stat_type=4,a.stat_count,0)) as iso_soft_dl,
							sum(if(a.stat_type=5,a.stat_count,0)) as apk_soft_dl,
							sum(if(a.stat_type=6,a.stat_count,0)) as iso_card_act,
							sum(if(a.stat_type=7,a.stat_count,0)) as dl_card_act';
			$str_query = str_ireplace('{str}', $tmp_str_q, $str_query);
			$str_query.= " group by a.top_agent_id";
			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			$tmp_total     = array();
			if (!$pra['total'] && !!$data['Rows']){
				foreach ($data['Rows'] as $k=>$v){
					$tmp_total['phone_connect'] += intval($v['phone_connect']);
					$tmp_total['iso_soft_dl'] += intval($v['iso_soft_dl']);
					$tmp_total['apk_soft_dl'] += intval($v['apk_soft_dl']);
					$tmp_total['iso_card_act'] += intval($v['iso_card_act']);
					$tmp_total['dl_card_act'] += intval($v['dl_card_act']);
				}
				$tmp_total['shop_name'] = '汇总';
				$data['Rows'][]= $tmp_total;
			}
			$str_query 	   = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatUseSketShopInfo($pra){
		try {
			$pra['sortname']  = empty($pra['sortname'])?"channel_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
			$pra['end_date']  = empty($pra['end_date'])?date('Y-m-d',strtotime("-1 day")):$pra['end_date'];
			$pra['name']      = trim($pra['name']);
			$pra['channel_id']= intval($pra['channel_id']);
			$pra['shop_id']   = intval($pra['shop_id']);
			$pra['total']   = intval($pra['total']);
			
			$str_query = "select {str}
						from statistic.stat_pc_day_use_shop_summary
						where stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}'";
			!empty($pra['name']) && $str_query.=" and (shop_name like '%{$pra['name']}%' or channel_name like '%{$pra['name']}%')";
			!empty($pra['channel_id']) && $str_query.=" and channel_id = {$pra['channel_id']}";
			!empty($pra['shop_id']) && $str_query.=" and shop_id = {$pra['shop_id']}";
			
			if ($pra['_act'] == 'cut'){
				$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct shop_id) as cut', $str_query));
				$data['Total'] = $_tmp[0]['cut'];
				return $data;
			}
			
			$tmp_str_q     = 'shop_id,shop_name,channel_name,
							sum(if(stat_type=1,stat_count,0)) phone_connect,
							sum(if(stat_type=4,stat_count,0)) iso_soft_dl,
							sum(if(stat_type=5,stat_count,0)) apk_soft_dl,
							sum(if(stat_type=6,stat_count,0)) iso_card_act,
							sum(if(stat_type=7,stat_count,0)) dl_card_act';
			$str_query = str_ireplace('{str}', $tmp_str_q, $str_query);
			$str_query.= " group by shop_id";
			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			$tmp_total     = array();
			if (!$pra['total'] && !!$data['Rows']){
				foreach ($data['Rows'] as $k=>$v){
					$tmp_total['phone_connect'] += intval($v['phone_connect']);
					$tmp_total['iso_soft_dl'] += intval($v['iso_soft_dl']);
					$tmp_total['apk_soft_dl'] += intval($v['apk_soft_dl']);
					$tmp_total['iso_card_act'] += intval($v['iso_card_act']);
					$tmp_total['dl_card_act'] += intval($v['dl_card_act']);
				}
				$tmp_total['shop_name'] = '汇总';
				$data['Rows'][]= $tmp_total;
			}
			$str_query 	   = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatUseSketchInfo_($pra){
		try {
			$pra['sortname']  = empty($pra['sortname'])?"channel_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['date']      = empty($pra['date'])?date('Y-m-d',strtotime("-1 day")):$pra['date'];
			$pra['name']      = trim($pra['name']);
			
			$str_query = "select {str}
						from stat_pc_day_channel_login
						where stat_type in(1,2) and top_agent_id = {$pra['top_agent_id']} and stat_date = '{$pra['date']}' and stat_type = 1
						";
			!empty($pra['name']) && $str_query.=" and channel_name like '%{$pra['name']}%'";
			
			$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct channel_id) as cut', $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			$str_query = str_ireplace('{str}', 'channel_name,channel_id,sum(stat_count) as login', $str_query);
			$str_query.= " group by channel_id";
//			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
//			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$_data     = DB::selectQueryAssoc($str_query);
			
			foreach ($_data as $k=>$v) $pxord[$k] = $v[$pra['sortname']];
			array_multisort($pxord,SORT_REGULAR,strtolower($pra['sortorder'])=='asc'?SORT_ASC:SORT_DESC,$_data);
			
			$_data		  = array_slice($_data,($pra['page']-1)*$pra['pagesize'],$pra['pagesize']);
			$data['Rows'] = $_data;
			$str_query 	  = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatDetailLoginInfo($pra){
		try {
			$pra['s_day']     = empty($pra['s_day'])?date('Y-m-01',strtotime("-1 day")):$pra['s_day'];
			$pra['e_day']     = empty($pra['e_day'])?date('Y-m-t',strtotime("-1 day")):$pra['e_day'];
			$pra['sortname']  = empty($pra['sortname'])?"channel_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['name']      = trim($pra['name']);
			
			$str_query = "select {str}
						from stat_pc_day_channel_login
						where stat_type in(1,2) and top_agent_id = {$pra['top_agent_id']} and stat_date >= '".$pra['s_day']."' and stat_date <= '{$pra['e_day']}' and stat_type = 1
						";
			!empty($pra['name']) && $str_query.=" and channel_name like '%{$pra['name']}%'";
			
			$_tmp      = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct channel_id) as cut', $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			
			for($i=strtotime($pra['s_day']);$i<=strtotime($pra['e_day']);$i+=86400){
				$cur_day = date("d",$i);
				$cur_mth = date("Y-m-d",$i);
				$_str   .= "sum(if(stat_date='".$cur_mth."',stat_count,0)) as d_".date('m',$i).$cur_day.",";
			}
			
			$str_query = str_ireplace('{str}', $_str.'channel_name,channel_id', $str_query);
			$str_query.= " group by channel_id";
			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			$str_query = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatDetailLoginAllData($pra){
		try {
			$pra['s_day']     = empty($pra['s_day'])?date('Y-m-01',strtotime("-1 day")):$pra['s_day'];
			$pra['e_day']     = empty($pra['e_day'])?date('Y-m-t',strtotime("-1 day")):$pra['e_day'];
			$pra['sortname']  = empty($pra['sortname'])?"channel_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['name']      = trim($pra['name']);
			
			$str_query = "select {str}
						from stat_pc_day_channel_login a left join channel.ch_agent b on a.top_agent_id = b.id
						where a.stat_type in(1,2) and a.stat_date >= '".$pra['s_day']."' and a.stat_date <= '{$pra['e_day']}' and a.stat_type = 1
						";
			!empty($pra['name']) && $str_query.=" and b.name like '%{$pra['name']}%'";
			$_SESSION['isSup'] && $str_query.=" and a.top_agent_id <> 132";
			
			$_tmp      = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct a.top_agent_id) as cut', $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			
			for($i=strtotime($pra['s_day']);$i<=strtotime($pra['e_day']);$i+=86400){
				$cur_day = date("d",$i);
				$cur_mth = date("Y-m-d",$i);
				$_str   .= "sum(if(a.stat_date='".$cur_mth."',a.stat_count,0)) as d_".date('m',$i).$cur_day.",";
			}
			
			$str_query = str_ireplace('{str}', $_str.'b.name,a.top_agent_id', $str_query);
			$str_query.= " group by a.top_agent_id";
			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			$str_query = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatDetailShopLoginInfo($pra){
		try {
			$pra['s_day']     = empty($pra['s_day'])?date('Y-m-01',strtotime("-1 day")):$pra['s_day'];
			$pra['e_day']     = empty($pra['e_day'])?date('Y-m-t',strtotime("-1 day")):$pra['e_day'];
			$pra['sortname']  = empty($pra['sortname'])?"shop_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['name']      = trim($pra['name']);
			
			$channel_id = intval($pra['channel_id']);
			$lg_id      = intval($pra['lgid']);
			
			$str_query = "select {str}
						from stat_pc_day_shop_login
						where is_login in(0,1) and".(empty($channel_id)?"":" channel_id = {$channel_id} and").(empty($lg_id)?"":" shop_id={$lg_id} and")." stat_date >= '".$pra['s_day']."' and stat_date <= '{$pra['e_day']}'
						";
			!empty($pra['name']) && $str_query.=" and shop_name like '%{$pra['name']}%'";
			
			$_tmp      = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct shop_id) as cut', $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			
			for($i=strtotime($pra['s_day']);$i<=strtotime($pra['e_day']);$i+=86400){
				$cur_day = date("d",$i);
				$cur_mth = date("Y-m-d",$i);
				$_str   .= "sum(if(stat_date='".$cur_mth."',is_login,0)) as d_".date('m',$i).$cur_day.",";
				$_tmp_arr[] = "d_".date('m',$i).$cur_day;
			}
			
			$str_query = str_ireplace('{str}', $_str.'shop_id,shop_name,is_login', $str_query);
			$str_query.= " group by shop_id order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			foreach ($data['Rows'] as $k=>$v){
				$data['Rows'][$k]['OrderID'] = $k+1;
				foreach ($_tmp_arr as $item){
					$data['Rows'][$k][$item] = $data['Rows'][$k][$item]?"是":"<font color=red>否</font>";
				}
			}
			$str_query = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatPhoneTypeSketchInfo($pra){
		try {
			$pra['date']      = empty($pra['date'])?date('Y-m-01',strtotime("-1 day")):$pra['date'];
			$pra['end_date']  = empty($pra['end_date'])?date('Y-m-t',strtotime("-1 day")):$pra['end_date'];
			$pra['sortname']  = empty($pra['sortname'])?"phone_type_name":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['name']      = trim($pra['name']);
			$pra['channel_id']= intval($pra['channel_id']);
			$pra['shop_id']   = intval($pra['shop_id']);
			
			$str_query = "select {str}
						from stat_pc_day_use_phone_type
						where stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}'
						";
			!$pra['isSup'] && $str_query.=" and top_agent_id = {$pra['top_agent_id']}";
			!empty($pra['name']) && $str_query.=" and phone_type_name like '%{$pra['name']}%'";
			!empty($pra['channel_id']) && $str_query.=" and channel_id = {$pra['channel_id']}";
			!empty($pra['shop_id']) && $str_query.=" and shop_id = {$pra['shop_id']}";
			
			if ($pra['_act'] == 'cut'){
				$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct phone_type_name) as cut', $str_query));
				$data['Total'] = $_tmp[0]['cut'];
				return $data;
			}
			
			$str_query = str_ireplace('{str}', 'count(id) as connect_count,phone_type_id,phone_type_name,phone_os', $str_query);
			$str_query.= " group by phone_type_name order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			$phone_os_arr  = array(
				'8' => 'IOS',
				'3' => 'android',
				'4' => 'symbian',
				'5' => 'MTK'
			);
			foreach ($data['Rows'] as $k=>$v){
				$data['Rows'][$k]['phone_os'] = $phone_os_arr[$data['Rows'][$k]['phone_os'][0]];
			}
			$str_query = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatPhoneImeiSketchInfo($pra){
		try {
			$pra['date']      = empty($pra['date'])?date('Y-m-01',strtotime("-1 day")):$pra['date'];
			$pra['end_date']  = empty($pra['end_date'])?date('Y-m-t',strtotime("-1 day")):$pra['end_date'];
			$pra['sortname']  = empty($pra['sortname'])?"download_count":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['phone_type_name'] = trim($pra['phone_type_name']);
			$pra['channel_id'] = intval($pra['channel_id']);
			$pra['shop_id'] = intval($pra['shop_id']);
			$pra['name']      = trim($pra['name']);
			
			
			$str_query = "select {str}
						from stat_pc_day_use_phone_type
						where stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}'
						";
			!$pra['isSup'] && $str_query.=" and top_agent_id = {$pra['top_agent_id']}";
			!empty($pra['phone_type_name']) && $str_query.=" and phone_type_name = '{$pra['phone_type_name']}'";
			!empty($pra['channel_id']) && $str_query.=" and channel_id = '{$pra['channel_id']}'";
			!empty($pra['shop_id']) && $str_query.=" and shop_id = '{$pra['shop_id']}'";
			!empty($pra['name']) && $str_query.=" and (channel_name like '%{$pra['name']}%' or shop_name like '%{$pra['name']}%')";
			
			if ($pra['_act'] == 'cut'){
				$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct shop_id) as cut', $str_query));
				$data['Total'] = $_tmp[0]['cut'];
				return $data;
			}
			
			$str_query = str_ireplace('{str}', 'count(id) connect_count,phone_type_name,shop_name,channel_name,sum(download_count) download_count', $str_query);
			$str_query.= " group by shop_id";
			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			$str_query = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getStatUseSoftInfo($pra){
		try {
			$pra['date']      = empty($pra['date'])?date('Y-m-01',strtotime("-1 day")):$pra['date'];
			$pra['end_date']  = empty($pra['end_date'])?date('Y-m-t',strtotime("-1 day")):$pra['end_date'];
			$pra['sortname']  = empty($pra['sortname'])?"download_times":$pra['sortname'];
			$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
			$pra['channel_id']= intval($pra['channel_id']);
			$pra['shop_id']   = intval($pra['shop_id']);
			$pra['name']      = trim($pra['name']);
			$pra['soft_type'] = intval($pra['soft_type']);
			
			$str_query = "select {str}
						from stat_pc_day_use_soft
						where stat_date >= '{$pra['date']}' and stat_date <= '{$pra['end_date']}' and soft_type = '{$pra['soft_type']}'
						";
			!$pra['isSup'] && $str_query.=" and top_agent_id = {$pra['top_agent_id']}";
			!empty($pra['channel_id']) && $str_query.=" and channel_id = '{$pra['channel_id']}'";
			!empty($pra['shop_id']) && $str_query.=" and shop_id = '{$pra['shop_id']}'";
			!empty($pra['name']) && $str_query.=" and soft_name like '%{$pra['name']}%'";
			
			if ($pra['_act'] == 'cut'){
				$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(distinct soft_id) as cut', $str_query));
				$data['Total'] = $_tmp[0]['cut'];
				return $data;
			}
			
			$str_query = str_ireplace('{str}', 'soft_name,sum(download_times) as download_times', $str_query);
			$str_query.= " group by soft_id";
			$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
			$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			$data['Rows']  = DB::selectQueryAssoc($str_query);
			$str_query = '';
			return !empty($str_query)?DB::selectQuery($str_query):$data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
}