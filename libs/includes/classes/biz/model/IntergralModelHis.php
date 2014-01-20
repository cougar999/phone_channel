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
class IntergralModelHis{
	private $_dbr;
	public function __construct(){
		DB::get_db_reader();
	}
	
	public function __destruct(){
	}
	
	public function getIntergralInfo($pra){
		$active_day_start = date('Y-m-01',strtotime($pra['selDate']));
		$active_day_end   = date('Y-m-t',strtotime($pra['selDate']));
		$active_day_cur   = date('Y-m',strtotime($pra['selDate']));
		try {
			if ($pra['type']=='a1'){
				$str_query = "select {str}
							from (SELECT sum(op_times*2) as score,salesperson_id,salesperson_name,char_length(imei) as ln,TYPE
							FROM tb_u_score_details
							left join channel.ch_salesperson as b on b.id = tb_u_score_details.salesperson_id
							WHERE active_day>='".$active_day_start."' and active_day <= '".$active_day_end."' and type=3 and char_length(imei)=40
							and b.agent_id in(select id from channel.ch_agent where ppath = {$pra['top_agent_id']} or find_in_set('{$pra['top_agent_id']},',ppath))
							group by salesperson_id
							) _tp";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(_tp.salesperson_id) as cut', $str_query):str_ireplace('{str}', '_tp.*', $str_query)." order by _tp.score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a2'){
				$str_query = "select {str}
							from (SELECT sum(op_times*2) as score,salesperson_id,salesperson_name,char_length(imei) as ln,TYPE
							FROM tb_u_score_details
							left join channel.ch_salesperson as b on b.id = tb_u_score_details.salesperson_id
							WHERE active_day>='".$active_day_start."' and active_day <= '".$active_day_end."' and type=3 and char_length(imei)!=40
							and b.agent_id in(select id from channel.ch_agent where ppath = {$pra['top_agent_id']} or find_in_set('{$pra['top_agent_id']},',ppath))
							group by salesperson_id
							) _tp";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(_tp.salesperson_id) as cut', $str_query):str_ireplace('{str}', '_tp.*', $str_query)." order by _tp.score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a3'){
				$str_query = "select {str}
							from (SELECT sum(if(type=7,20,if(type=8,10,50))) as score,salesperson_id,salesperson_name
							FROM tb_u_score_details
							left join channel.ch_salesperson as b on b.id = tb_u_score_details.salesperson_id
							WHERE active_day>='".$active_day_start."' and active_day <= '".$active_day_end."' and type in(7,8,10)
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
							where a.top_agent_id = {$pra['top_agent_id']} and a.status = 1 and month='".$active_day_cur."'
							) tmp
							group by tmp.agent_id
							) _tp";
				$str_query = $pra['act'] == 'cut'?str_ireplace('{str}', 'count(_tp.salesperson_id) as cut', $str_query):str_ireplace('{str}', '_tp.*', $str_query)." order by _tp.score desc limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
			}elseif ($pra['type']=='a5'){
				$str_query = "select {str}
							from (select score,salesperson_id,salesperson_name
							from tb_u_score_month
							where status=1 and month='".$active_day_cur."' and top_agent_id = {$pra['top_agent_id']}
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
							where b.create_time >= '".$active_day_start."' and b.create_time <= '".$active_day_end."' and a.active_day>='".$active_day_start."' and a.active_day <= '".$active_day_end."'
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
							where status=1 and month='".$active_day_cur."' and top_agent_id = {$pra['top_agent_id']}";
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
function sortself($a,$b){
	if ($a == $b) return 0;
  	return ($a['ys'] > $b['ys']) ? 1 : -1;
}
function filter_time($diff){
	return (date('d',$diff)-1)."天".intval(date('H',$diff))."小时".intval(date('i',$diff))."分";
}