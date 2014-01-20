<?php
class ChAgent {
	private $_dbr;
	public function __construct($dbKey = 'member'){
		session_start();
		DB::get_db_reader($dbKey);
	}
	
	public function __destruct(){
	
	}
	
	public function getMagInfo($pra){
		$pra['page'] 	  = (empty($pra['page']) || intval($pra['page']) <  1) ? 1 : intval($pra['page']);
		$pra['pagesize']  = (!intval($pra['pagesize'])?20:intval($pra['pagesize']));
		$pra['sortname']  = empty($pra['sortname'])?"name":$pra['sortname'];
		$pra['sortorder'] = empty($pra['sortorder'])?"desc":$pra['sortorder'];
		$data = array(
			'Total'=>0,
			'Rows'=>array()
		);
		$agent   = intval($pra['agent_id']);
		//权限验证
		if(!$_SESSION['isSup']&&empty($_SESSION['level'])) return $data;
		if(!empty($agent)){
		
		}

		$str_query = "SELECT {str}
					FROM channel.ch_agent a
					LEFT JOIN 
					(SELECT GROUP_CONCAT(real_name) real_name, GROUP_CONCAT(telphone) telphone,agent_id
					FROM channel.ch_salesperson
					GROUP BY agent_id) b ON a.id = b.agent_id
					LEFT JOIN channel.ch_agent_info c ON a.id = c.agent_id
					WHERE 1
					";
		!empty($pra['level']) && $str_query.=" and a.level = '{$pra['level']}'";		
		$str_query .= " and a.".(3==$_SESSION['level']?'id':'pid')." = '".(!$agent?($_SESSION['isSup']?0:$_SESSION['real_id']):$agent)."'";
		!empty($pra['q']) && $str_query.=" and (name like '%{$pra['q']}%' or a.username like '%{$pra['q']}%')";
		if ($pra['_act']  == 'cut'){
			$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(a.id) as cut', $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			return $data;
		}
		$tmp_str_q = 'a.id,a.level,a.name,b.*,c.company_address,c.sp_count_total,c.sp_count_open,c.sp_expand_er,c.sp_expand_ing,c.shop_prop,c.contact_name,c.contact_tel';
		$str_query = str_ireplace('{str}', $tmp_str_q, $str_query);
		$str_query.= " order by {$pra['sortname']} {$pra['sortorder']}";
		$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
		$data['Rows']  = DB::selectQueryAssoc($str_query);
		return $data;
	}
	
	public function delMemInfo($pra){
		extract($pra);
		$data  = array();
		$code  = '';
		$id    = intval($id);
		$table = intval($table);
		if(!$id) $code = '001';
		elseif(!$_SESSION['isSup']) $code = '004';
		elseif(($table&&intval($_SESSION['level'])&&$id==$_SESSION['real_id'])||(!$table&&!intval($_SESSION['level'])&&$id==$_SESSION['real_id'])) $code = '002';
		if(!in_array($code,array('004','001','002'))){
			$o_log  = new ChLog();
			if($table){
				$sql  = "delete a.* from channel.ch_agent_info a,channel.ch_agent b
						where a.agent_id = b.id and (find_in_set('{$id}',b.ppath) or b.id = {$id});
						";
				$stu1 = DB::executeQuery($sql);
				if($stu1){
					$_pra_  = array(
						'log_sql'=>$sql,
						'log_remarks'=>'删除店面信息'
					);
					$o_log->addLog($_pra_);
				}
				$sql  = "delete a.* from channel.ch_salesperson a,channel.ch_agent b
						where a.agent_id = b.id and (find_in_set('{$id}',b.ppath) or b.id = {$id});
						";
				$stu2 = DB::executeQuery($sql);
				if($stu2){
					$_pra_  = array(
						'log_sql'=>$sql,
						'log_remarks'=>'删除店员账户'
					);
					$o_log->addLog($_pra_);
				}
				$sql  = "delete a.* from channel.ch_agent a
						where find_in_set('{$id}',a.ppath) or a.id = {$id};";
				$stu3 = DB::executeQuery($sql);
				if($stu3){
					$_pra_  = array(
						'log_sql'=>$sql,
						'log_remarks'=>'删除店面账户'
					);
					$o_log->addLog($_pra_);
				}
				$code = $stu1&&$stu2&&$stu3?'006':'005';
			}else{
				$sql = "delete a.* from channel.ch_salesperson a
						where a.id = {$id};";
				if(DB::executeQuery($sql)){
					$code 	= '006';
					$_pra_  = array(
						'log_sql'=>$sql,
						'log_remarks'=>"删除店员账户[ID={$id}]"
					);
					$o_log->addLog($_pra_);
				}else $code = '005';
			}
		}
		return array('data'=>$data,'code'=>$code);
	}
	
	public function getFeedBack($pra){
		$pra['page'] 	 = (empty($pra['page']) || intval($pra['page']) <  1) ? 1 : intval($pra['page']);
		$pra['pagesize'] = (!intval($pra['pagesize'])?2:intval($pra['pagesize']));
		$str_query = "select {str}
					from channel.ch_service_permutation
					where csp_type = 1
					";
		!empty($pra['shop_id']) && $str_query.=" and csp_agent_id = '{$pra['shop_id']}'";
		if ($pra['_act']  == 'cut'){
			$_tmp      	   = DB::selectQueryAssoc(str_ireplace('{str}', 'count(*) as cut', $str_query));
			$data['Total'] = $_tmp[0]['cut'];
			return $data;
		}
		$tmp_str_q = 'id,csp_des,create_time';
		$str_query = str_ireplace('{str}', $tmp_str_q, $str_query);
		$str_query.= " order by create_time desc";
		$str_query.= " limit ".(($pra['page']-1)*$pra['pagesize']).",{$pra['pagesize']}";
		$data['Rows']  = DB::selectQueryAssoc($str_query);
		return $data;
	}
	
	public function getChAgent($pra){
		try {
			$ids = implode(",", (array)$pra['ids']);
			$str_query = "
			select ".implode(',', (array)$pra['sel'])."
			from ch_agent
			where id in({$ids})
			"; 
			return DB::selectQueryAssoc($str_query);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function authAccess($pra,$type=''){
		//权限验证
		if(!$_SESSION['real_id']) return false;
		if($_SESSION['isSup']) return true;
		switch($type){
			case 'getMemInfo':
				if('0' == $pra['type']){
					if(empty($_SESSION['level'])) return false;
					$sql = "select concat(ppath,concat(',',id)) ppath from channel.ch_agent where id = {$pra['id']}";
				}else{
					if(empty($_SESSION['level'])&&$_SESSION['real_id']!=$pra['id']) return false;
					$sql = "select concat(b.ppath,concat(',',a.agent_id)) ppath
							from channel.ch_salesperson a,channel.ch_agent b
							where a.agent_id = b.id and a.id = {$pra['id']}";
				}
				$data    = (array)DB::selectQueryAssoc($sql);
				if(empty($data[0]['ppath'])) return false;
				else{
					$_tmp_dat = explode(',',$data[0]['ppath'].",{$pra['id']}");
					return in_array($_SESSION['real_id'],$_tmp_dat);
				}
			break;
			default:break;
		}
	}
	
	public function getMatchInfo($pra){
		try {
			if(!$this->authAccess($pra,'getMemInfo')) return array();
			if ($pra['type'] == 0){
				$str_query = "SELECT a.username,a.password,a.name,a.status,a.level,b.*
							FROM channel.ch_agent a
							LEFT JOIN channel.ch_agent_info b on a.id = b.agent_id
							where a.id = {$pra['id']}";
			}else{
				$str_query = "SELECT username, password,real_name,job_number,telphone,email,qq,status
							FROM channel.ch_salesperson
							WHERE id = {$pra['id']}";
			}
			return DB::selectQueryAssoc($str_query);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function cntModel($pra){
		try {
			$config   = HTMLPurifier_Config::createDefault();
			$config   -> set('HTML.SafeEmbed', 'true');
			$purifier = new HTMLPurifier($config);
			foreach(array('feedback_des','sp_expand_ing','sp_expand_er','contact_name','name','username') as $item){
				$pra[$item] = $purifier->purify($pra[$item]);
			}
			$pra['contact_tel'] = RegVerification::isPhone($pra['contact_tel'])||RegVerification::isTel($pra['contact_tel'])?$pra['contact_tel']:'';
			$pra['id']     = intval($pra['id']);
			$pra['table']  = intval($pra['table']);
			$pra['action'] = trim($pra['action']);
			$_rs           = '';
			$o_log  	   = new ChLog();
			
			if ($pra['action']=='modify'||$pra['action']=='add'){
				if(!$this->authAccess(array('id'=>$pra['id'],'type'=>($pra['table']&&$pra['action']=='add'?0:$pra['table'])),'getMemInfo')) return array('code'=>'004');
			
				$cd      = $this->getAllInfo(array('ckpwdid'=>$pra['id'],'username'=>$pra['username'],'type'=>$pra['table']));
				if (!empty($cd)) return array('code'=>'002');
				$_status = isset($pra['status'])?$pra['status']:1;
				
				if (0 == $pra['table']){
					if ($pra['action']=='add'){
						if($pra['id'] == 0) $_rs[0] = array('pid'=>'0','pname'=>'','ppath'=>'0','level'=>1);
						else{
							$sql = "select id as pid,name as pname,concat(ppath,concat(',',id)) as ppath,level+1 as level from channel.ch_agent where id = {$pra['id']}";
							$_rs = DB::selectQueryAssoc($sql);
						}
						if (3 < $_rs[0]['level']) return array('code'=>'001');
					}
					$sql  = $pra['action']=='modify'?'update channel.ch_agent set ':'insert into channel.ch_agent set ';
					$sql .= "
							username = '{$pra['username']}',
							password = '{$pra['password']}',
							name     = '{$pra['name']}',
							type     = '".intval($pra['type'])."',
							acc_balance= '0',
							status   = '{$_status}',
							update_time= NOW()
							";
					if ($pra['action']=='add'){
						$sql .= ",
								pname 	    = '{$_rs[0]['pname']}',
								ppath       = '".preg_replace('/^(0,)?(.*)$/is','\\2',$_rs[0]['ppath'])."',
								pid         = '{$_rs[0]['pid']}',
								level       = '{$_rs[0]['level']}',
								create_time = NOW()
								";
					}else $sql .= "where id = {$pra['id']}";
					$stu = DB::executeQuery($sql);
					$agent_id = $pra['action']=='add'?DB::getInsertId():$pra['id'];
					if($stu){
						//插入日志
						$o_log->addLog(array('log_sql'=>$sql,'log_remarks'=>($pra['action']=='add'?'添加':'修改').'店面[ID='.$agent_id.']'));
					}
					$sql      = $pra['action']=='modify'?'update channel.ch_agent_info set ':'insert into channel.ch_agent_info set ';
					$sql .= "
							contact_qq = '{$pra['contact_qq']}',
							shop_prop = '{$pra['shop_prop']}',
							feedback_des = '{$pra['feedback_des']}',
							open_date = '".(empty($pra['open_date'])?date('Y-m-d H:i:s'):$pra['open_date'])."',
							feedback_date = '".(empty($pra['feedback_date'])?date('Y-m-d H:i:s'):$pra['feedback_date'])."',
							company_province = '{$pra['province']}',
							company_city = '{$pra['city']}',
							telphone     = '{$pra['telphone']}',
							download_person     = '".intval($pra['download_person'])."',
							download_net     = '{$pra['download_net']}',
							download_computer     = '".intval($pra['download_computer'])."',
							download_area     = '".intval($pra['download_area'])."',
							contact_tel     = '{$pra['contact_tel']}',
							contact_name     = '{$pra['contact_name']}',
							contact_email     = '{$pra['contact_email']}',
							company_website     = '{$pra['company_website']}',
							company_type     = '".intval($pra['company_type'])."',
							company_address     = '{$pra['company_address']}',
							sp_count_total     = '".intval($pra['sp_count_total'])."',
							sp_count_open     = '".intval($pra['sp_count_open'])."',
							sp_expand_er     = '{$pra['sp_expand_er']}',
							sp_expand_ing     = '{$pra['sp_expand_ing']}',
							update_time=NOW()
							";
					if ($pra['action']=='add'){
						$sql .= ",
								agent_id 	     = '{$agent_id}',
								agent_name       = '{$pra['name']}',
								create_time = NOW()
								";
					}else $sql .= "where agent_id = {$agent_id}";
					$stu2       = DB::executeQuery($sql);
					if($stu2){
						//插入日志
						$o_log->addLog(array('log_sql'=>$sql,'log_remarks'=>($pra['action']=='add'?'添加':'修改').'店面信息'));
					}
					//插入反馈信息
					if(!empty($pra['feedback_des'])){
						$sql = "insert into channel.ch_service_permutation set 
								csp_type = 1,
								csp_agent_id = {$agent_id},
								csp_oper_id = {$_SESSION['real_id']},
								csp_des = '{$pra['feedback_des']}',
								create_time = NOW(),
								update_time = NOW()
								";
						$stu7 = DB::executeQuery($sql);
					}
					$self_data  = array(
						'level'=>empty($_rs[0])?0:$_rs[0]['level'],
						'text'=>$pra['name'],
						'id'=>$agent_id,
						'pid' => empty($_rs[0])?0:$_rs[0]['pid'],
						'status' => $_status,
						'children'=>array(),
						'isexpand'=>false
					);
					//更新冗余字段--渠道
					if ($pra['action']=='modify' && $stu){
						$_sql  = "update channel.ch_agent_info set agent_name = '{$pra['name']}' where agent_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update channel.ch_agent set pname = '{$pra['name']}' where pid = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update statistic.stat_pc_day_use_channel_summary set channel_name = '{$pra['name']}' where channel_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update statistic.stat_pc_day_use_shop_summary set channel_name = '{$pra['name']}' where channel_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update statistic.stat_pc_day_use_shop_summary set shop_name = '{$pra['name']}' where shop_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update statistic.stat_pc_day_channel_login set channel_name = '{$pra['name']}' where channel_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update statistic.stat_pc_day_shop_login set channel_name = '{$pra['name']}' where channel_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update statistic.stat_pc_day_shop_login set shop_name = '{$pra['name']}' where shop_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update statistic.stat_pc_day_use_phone_type set channel_name = '{$pra['name']}' where channel_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update statistic.stat_pc_day_use_phone_type set shop_name = '{$pra['name']}' where shop_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update statistic.stat_pc_day_use_soft set channel_name = '{$pra['name']}' where channel_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update statistic.stat_pc_day_use_soft set shop_name = '{$pra['name']}' where shop_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
					}
					return array('code'=>($stu&&$stu2?'006':'003'),'data'=>$self_data);
				}else{
					$sql  = $pra['action']=='modify'?'update channel.ch_salesperson set ':'insert into channel.ch_salesperson set ';
					$sql .= "
							username 	= '{$pra['username']}',
							password 	= '{$pra['password']}',
							real_name	= '{$pra['real_name']}',
							job_number  = '{$pra['job_number']}',
							telphone    = '{$pra['telphone']}',
							email       = '{$pra['email']}',
							qq          = '".intval($pra['qq'])."',
							billing_type= '0',
							status      = '{$_status}',
							update_time = NOW()
							";
					if ($pra['action']=='add'){
						$sql .= ",
								agent_id 	= '{$pra['id']}',
								create_time = NOW()
								";
					}else $sql .= "where id = {$pra['id']}";
					$stu        = DB::executeQuery($sql);
					$self_data  = array(
						'level'=>0,
						'text'=>$pra['real_name'],
						'id'=>$pra['action']=='add'?DB::getInsertId():$pra['id'],
						'pid' => $pra['id'],
						'status' => $_status,
						'isexpand'=>false,
						'children'=>''
					);
					if($stu){
						//插入日志
						$o_log->addLog(array('log_sql'=>$sql,'log_remarks'=>($pra['action']=='add'?'添加':'修改').'店员[ID='.$self_data['id'].']'));
					}
					//更新冗余字段--店员
					if ($pra['action']=='modify' && $stu){
						$_sql  = "update channel2_portal.tb_u_score set salesperson_name = '{$pra['real_name']}' where salesperson_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update channel2_portal.tb_u_score_details set salesperson_name = '{$pra['real_name']}' where salesperson_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update channel2_portal.tb_u_score_month set salesperson_name = '{$pra['real_name']}' where salesperson_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update channel2_portal.tb_user_session set real_name = '{$pra['real_name']}' where real_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
						$_sql  = "update channel2_portal.tb_u_soce_convert set salesperson_name = '{$pra['real_name']}' where salesperson_id = {$pra['id']};";
						$stu3  = DB::executeQuery($_sql);
					}
					return array('code'=>($stu?'006':'003'),'data'=>$self_data);
				}
			}
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getSaleInfo($pra){
	try {
			$pra['id']     = (array)$pra['id'];
			$pra['ext']    = (array)$pra['ext'];
			
			$sql_sp = "select a.id,a.username,a.real_name,'0' as level,a.status,b.score,b.usable_score
					from channel.ch_salesperson a,channel2_portal.tb_u_score b
					where a.id = b.salesperson_id and a.id in(".implode(',', $pra['id']).")";
			return DB::selectQueryAssoc($sql_sp);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getChnInfo($pra){
		try {
			$pra['id']     = (array)$pra['id'];
			$pra['ext']    = (array)$pra['ext'];
			
			$sql_cn = "select id,username,password,name,level,status
					from channel.ch_agent 
					where id in(".implode(',', $pra['id']).")";
			$sql_sp = "select id,username,password,real_name as name,'0' as level,status
					from channel.ch_salesperson
					where id in(".implode(',', $pra['id']).")";
			return DB::selectQueryAssoc($pra['type']?$sql_sp:$sql_cn);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getMemInfo($pra){
		try {
			$exp_id   = intval($pra['exp_id']);
			$tmp_data = $tmp_cc = $tmp = array();
			
			if (empty($exp_id)){
				$tmp_cc = self::getInfo(array('nid'=>0));
			}else $tmp_cc = array(array('id'=>$exp_id));
			
			foreach ($tmp_cc as $item){
				$exp_id = $item['id'];
				$sql    = "
						select * from (
						(select if(level=2,concat(ppath,concat(',',id)),ppath) as od_ppath,name,level,id as od_id,username,password,id as rl_id,level as _level,status from channel.ch_agent
						where find_in_set('{$exp_id}',ppath) or id = {$exp_id}
						)
						union all
						(select if(b.level=2,concat(b.ppath,concat(',',b.id)),b.ppath) as ppath,a.real_name as name,4 as level,agent_id as id,a.username,a.password,a.id as rl_id,b.`level` as _level,a.status from channel.ch_salesperson a ,channel.ch_agent b 
						where a.agent_id = b.id and (find_in_set('{$exp_id}',b.ppath) or a.agent_id = {$exp_id})
						)
						) a
						order by a.od_ppath,od_id,level
						";
				$tmp 	  = DB::selectQueryAssoc($sql);
				$tmp_data = array_merge($tmp_data,$tmp);
			}
			return $tmp_data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function checkPerm($pra){
		try {
			$pra['id']     = intval($pra['id']);
			$pra['table']  = intval($pra['table']);
			
			if ($_SESSION['isSup'] || ($_SESSION['level']<3&&$pra['id']==$_SESSION['real_id']) || ($pra['id']==$_SESSION['real_id']&&$pra['action']=='modify')) return '006';
			if (empty($_SESSION['level']) || $_SESSION['level'] > 2) return '004';
			
			$c = $this->getAllInfo(array('fid'=>$_SESSION['real_id'],'self_id'=>$pra['id']));
			return empty($c[0])?'004':'006';
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function execSql($sql=''){
		if (empty($sql)) return '';
		return DB::selectQueryAssoc($sql);
	}
	
	public function getInfo($pra){
		$icon_ok   = '/resources/lib/ligerUI/skins/icons/right.gif';
		$icon_fail = '/resources/lib/ligerUI/skins/icons/busy.gif';//,if(status=1,'{$icon_ok}','{$icon_fail}') as icon
		try {
			$str_query = "select a.*
						 from (
							  (select id,pid,name as text,level,username,status,create_time
							  from channel.ch_agent".($_SESSION['isSup']?" where id <> 132":"").") 
							  union
							  (SELECT id,agent_id AS pid,real_name AS text,0 as level,username,status,create_time
							  FROM channel.ch_salesperson)
						 ) a
						 where pid = {$pra['nid']}".($pra['nid']==0?" and level=1":"")."
						 order by level,text desc,create_time desc,username desc";
			$data      = DB::selectQueryAssoc($str_query);
//			$str_query = "select id,pid,name as text,level,username,status
//						  from channel.ch_agent 
//						  where pid = {$pra['nid']}
//						  order by create_time desc,username desc
//						  ";
//			$data      = DB::selectQueryAssoc($str_query);
//			if (empty($data)){
//				$str_query = "SELECT id,agent_id AS pid,real_name AS text,0 as level,username,status
//							  FROM channel.ch_salesperson 
//							  where agent_id = {$pra['nid']}
//							  order by create_time desc,username desc
//							  ";
//				$data      = DB::selectQueryAssoc($str_query);
//			}
			return $data;
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	/**
	 * 获取渠道/子公司/店面信息
	 * @param int $pid
	 * @param int $level
	 */
	function getAgentInfo($pid=0, $level=1){
		try {
			if ($level == -1){//子公司、店面
				//$sql = "select count(*) as agent_sum from channel.ch_agent where pid=$pid 
							//union select username from channel.ch_agent where pid=$pid";
				$sql = "select cast(username as signed) as username from channel.ch_agent where pid=$pid";
			}
			else {//渠道
				$sql = "select count(*) as agent_sum from channel.ch_agent where pid=$pid and level=$level";
			}
			return DB::selectQueryAssoc($sql);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	/**
	 * 检测用户名是否已经存在
	 * @param string $new_clerk
	 */
	function checkAgentOrClerkHad($new_clerk){
		try {
			$sql_clerk = "select username from channel.ch_salesperson where username='$new_clerk'";
			$info_clerk = DB::selectQueryAssoc($sql_clerk);
			$sql_agent = "select username from channel.ch_agent where username='$new_clerk'";
			$info_agent = DB::selectQueryAssoc($sql_agent);
			return array($info_clerk,$info_agent);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	/**
	 * 获取子级信息
	 * @param int $pid
	 * @param int $level
	 */
	function getSubChannel($pid,$level){
		try {
			$level += 1;
			$sql = "select id,username,pid,level from channel.ch_agent where pid='$pid' and level=$level";
			return DB::selectQueryAssoc($sql);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
	
	public function getAllInfo($pra){
		try {
			$str_query = "select a.*
						from (
						select id as self_id,username,name,id as agent_id,pid,ppath,'0' as _type,level,status
						from channel.ch_agent".($_SESSION['isSup']?" where id <> 132":"")."
						union all
						select a.id as self_id,a.username,a.real_name as name,a.agent_id,b.pid,concat(b.ppath,concat(',',a.agent_id)) as ppath,'1' as _type,0 as level,a.status
						from channel.ch_salesperson a left join channel.ch_agent b on a.agent_id = b.id
						) a
						where 1=1";
			!empty($pra['username']) && $str_query.=" and a.username = '{$pra['username']}'";
			!empty($pra['username']) && !empty($pra['ckpwdid']) && $str_query.=" and a.self_id != '{$pra['ckpwdid']}'";
			!empty($pra['fid']) && $str_query.=" and find_in_set('{$pra['fid']}',ppath)";
			!empty($pra['self_id']) && $str_query.=" and self_id = {$pra['self_id']}";
			!!isset($pra['table']) && $str_query.=" and _type = {$pra['table']}";
			!!isset($pra['type']) && $str_query.=" and _type = {$pra['type']}";
			!empty($pra['kw']) && $str_query.=" and (name like '%{$pra['kw']}%' or username like '%{$pra['kw']}%')";
			!empty($pra['create']) && $str_query.=" and username like '{$pra['create']}%' order by username desc";
			!empty($pra['limit']) && $str_query.=" limit {$pra['limit']}";
			
			return DB::selectQueryAssoc($str_query);
		}catch (Exception $e){
			$e->getMessage();
		}
	}
}