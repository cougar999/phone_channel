<?php
session_start();
error_reporting(0);
set_time_limit(0);
!defined('_INIT_FROM_INDEX_') && include_once '../config.php';
define(AGENT_NAME, '010');

//$_REQUEST['type'] = trim($_REQUEST['type']);
//$_REQUEST['page'] = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <  1) ? 1 : intval($_REQUEST['page']);
//$_REQUEST['pagesize'] = (!$_REQUEST['pagesize']?10:$_REQUEST['pagesize']);

$memberModel = new ChAgent();
$PHPExcel    = new PHPExcel();
$data  		 = array();
$pra         = $_REQUEST;

switch ($_REQUEST['act']){
	case 'get_mag_info':
		$data = $memberModel->getMagInfo($pra);
		break;
	case 'mem_del':
		$data = $memberModel->delMemInfo($pra);
		break;
	case 'get_feedback':
		$data = array();
		$data = $memberModel->getFeedBack($pra);
		break;
	case 'sales_excel':
		$_file_name = $_FILES["{$_GET['n']}"]["name"];
		$_file_name = trim(preg_replace("/[^\x{4e00}-\x{9fa5}]+/u",'',$_file_name));
		$path_info = pathinfo($_FILES["{$_GET['n']}"]["name"]);
		$day_diff  = date( 't ');
		$ext = $path_info['extension'];
		$tp  = array("xlsx","xls");
		$add = intval($_GET['add']);
		$_agent = intval($_GET['agent']);
		$_agent = !$_agent?8317:$_agent;
		//检查上传文件是否在允许上传的类型
		if(!in_array($ext,$tp)) $data['code'] = '001';
		/*文件名传参
		$do_agent_id = intval($pra['agentId']);
		if(!!empty($do_agent_id)){
			preg_match("/^(.*)(?:_)([\d]+)$/is",$path_info['filename'],$tmp_info);
			$do_agent_id = intval($tmp_info[2]);
		}
		if(!!empty($do_agent_id)) $data['code'] = '002';
		*/
		if(!$_SESSION['isSup']) $data['code'] = '004';
		if(!in_array($data['code'],array('001','002','004'))){
			$data['code']  = '006';
			$filePath 	   = $_FILES["{$_GET['n']}"]["tmp_name"];
			$PHPReader     = new PHPExcel_Reader_Excel2007();
			if(!$PHPReader -> canRead($filePath)){
				$PHPReader = new PHPExcel_Reader_Excel5();
				if(!$PHPReader->canRead($filePath)){
					echo 'no Excel';
					return ;
				}
			}
			$index 		  = 1;
			$PHPExcel 	  = $PHPReader->load($filePath);
			$currentSheet = $PHPExcel->getSheet($index);
			$allColumn    = $currentSheet->getHighestColumn();
			$allRow 	  = $currentSheet->getHighestRow(); 
			for($currentRow = 1;$currentRow<=$allRow;$currentRow++){
				$shop_name  = trim($currentSheet->getCell("A".$currentRow)->getValue());
				$shop_sale  = intval($currentSheet->getCell("B".$currentRow)->getValue());
				$shop_name  = explode(' ',$shop_name);
				$shop_name  = $shop_name[0];
				$sql = "select id,name from channel.ch_agent where pname = '{$_file_name}' and name = '{$shop_name}' and find_in_set('{$_agent}',ppath)";
				$tmp = $memberModel->execSql($sql);
				if(!empty($tmp) && 1==count($tmp)){
					$shop_id = intval($tmp[0]['id']);
					$sql     = "select id from channel.ch_service_permutation
								where csp_agent_id = {$shop_id} and date_format(create_time,'%Y-%m-%d') = '".date('Y-m-d')."'";
					$_tmp_   = $memberModel->execSql($sql);
					$sql     = empty($_tmp_)?'insert into':'update';
					$sql    .= " channel.ch_service_permutation set 
								csp_type = 2,
								csp_agent_id = {$shop_id},
								csp_oper_id = {$_SESSION['real_id']},
								csp_num = ".(!empty($_tmp_)&&$add?'csp_num+':'').(ceil($shop_sale/$day_diff)).",
								update_time = now()
								";
					$sql    .= empty($_tmp_)?',create_time = now()':" where id = {$_tmp_[0]['id']}";
					$memberModel->execSql($sql);
				}else{
					$data['fail'][] = array(
						'name' => $shop_name,
						'cnt' => $tmp
					);
				}
			}
		}
		break;
	case 'rd_excel':
		$pra['id'] = intval($pra['id']);
		$rt_arr    = array();
		$do_arr    = array();
		
		$ext = explode('.', $_FILES["{$_GET['n']}"]["name"]);
		$ext = end($ext);
		$tp  = array("xlsx","xls");
		//检查上传文件是否在允许上传的类型
		if(!in_array($ext,$tp)) $data['code'] = '001';
		
		$filePath = $_FILES["{$_GET['n']}"]["tmp_name"];
		$PHPReader     = new PHPExcel_Reader_Excel2007();
		if(!$PHPReader -> canRead($filePath)){
			$PHPReader = new PHPExcel_Reader_Excel5();
			if(!$PHPReader->canRead($filePath)){
				echo 'no Excel';
				return ;
			}
		}
		$index 		  = 0;
		$PHPExcel 	  = $PHPReader->load($filePath);
		$currentSheet = $PHPExcel->getSheet($index);
		$allColumn    = $currentSheet->getHighestColumn();
		$allRow 	  = $currentSheet->getHighestRow(); 
		for($currentRow = 2;$currentRow<=$allRow;$currentRow++){
			$shop_name    = trim($currentSheet->getCell("A".$currentRow)->getValue());
			$shoper_name  = trim($currentSheet->getCell("B".$currentRow)->getValue());
			$shoper_uname = trim($currentSheet->getCell("C".$currentRow)->getValue());
			$shoper_pwd   = trim($currentSheet->getCell("D".$currentRow)->getValue());
			if (empty($shop_name) || empty($shoper_name)) continue;
			$do_arr[md5($shop_name)]['name'] = $shop_name;
			$do_arr[md5($shop_name)]['username'] = $shoper_uname;
			$do_arr[md5($shop_name)]['password'] = $shoper_pwd;
			$do_arr[md5($shop_name)]['er'][] = array(
				'name'=>$shoper_name,
				'username'=>$shoper_uname,
				'password'=>$shoper_pwd
			);
		}
		foreach ($do_arr as $item){
			$agent_id = 0;
			
			$sql = "select id,name,level,status from channel.ch_agent
					where pid = {$pra['id']} and name = '{$item['name']}' limit 1";
			$tmp = $memberModel->execSql($sql);
			if (empty($tmp[0])){
				if (empty($item['username'])){
					$__tmp        = getCreate(array('pid'=>$pra['id'],'type'=>0));
					$_username    = $__tmp['u_new'];
				}else $_username  = $item['username']."_sp";
				$_password = empty($item['password'])?salt():$item['password'];
				
				$cnt = array(
					'action' => 'add',
					'id'=>$pra['id'],
					'name'=>$item['name'],
					'username'=>$_username,
					'password'=>$_password,
					'table'=>'0'
				);
				$data = $memberModel->cntModel($cnt);
				if ($data['code'] == '006'){
					$agent_id         		  = $data['data']['id'];
					$data['data']['isexpand'] = true;
					$data['data']['pid']      = $pra['id'];
					$rt_arr['succ'][] 		  = $data['data'];
				}else $rt_arr['fail'][] = $cnt;
			}else{
				$agent_id = $tmp[0]['id'];
				$self_data  = array(
					'level'=>$tmp[0]['level'],
					'text'=>$tmp[0]['name'],
					'id'=>$tmp[0]['id'],
					'status' => $tmp[0]['status'],
					'children'=>array(),
					'isexpand'=>true
				);
				$rt_arr['succ'][] = $self_data;
			}
			if (empty($agent_id)) continue;
			foreach ($item['er'] as $k=>$v){
				if (empty($v['username'])){
					$__tmp        = getCreate(array('pid'=>$pra['id'],'type'=>0));
					$_username    = $__tmp['u_new'];
				}else $_username  = $v['username'];
				$_password = empty($v['password'])?salt():$v['password'];
				
				$cnt = array(
					'action' => 'add',
					'id'=>$agent_id,
					'real_name'=>$v['name'],
					'username'=>$_username,
					'password'=>$_password,
					'table'=>'1'
				);
				$data = $memberModel->cntModel($cnt);
				if ($data['code'] == '006'){
					$data['data']['isexpand'] = true;
					$data['data']['pid']      = $agent_id;
					$rt_arr['succ'][]   	  = $data['data'];
				}else $rt_arr['fail'][] = $cnt;
			}
		}
		$data = $rt_arr;
		break;
	case 'perm':
		$pra['action'] = trim($pra['action']);
		$pra['id']     = intval($pra['id']);
		$pra['table']  = intval($pra['table']);
		
		$data['code']  = $memberModel->checkPerm($pra);
		if ('006' == $data['code']){
			$data = $memberModel->cntModel($pra);
		}
		break;
	case 'ckpwd':
		$pra['username'] = $pra['uname'];
		$data = $memberModel->getAllInfo($pra);
		break;
	case 'getinfo':
		$data = $memberModel->getMatchInfo($pra);
		$data = $data[0];
		break;
	case 'getsaleinfo':
		$pra['id'] = $_SESSION['real_id'];
		$data = $memberModel->getSaleInfo($pra);
		$data = $data[0];
		break;
	case 'conver_cz':
		die('stop');
		$pra['id']     = $_SESSION['real_id'];
		$pra['cz_val'] = abs(intval($pra['cz_val']));
		$_scoreScale   = 200;
		$_time         = date('Y-m-d H:i:s');
		if (empty($pra['id']) || empty($pra['cz_val'])) $data['code'] = '004';
		else{
			$data = $memberModel->getSaleInfo($pra);
			if ($data[0]['usable_score'] >= ($conver_val_ = $pra['cz_val']*$_scoreScale)){
				//封装事务后放入事务中
				$sql = "update channel2_portal.tb_u_score set usable_score = usable_score - {$conver_val_} where salesperson_id = {$pra['id']}";
				$stu1=$memberModel->execSql($sql);
				$_reserve3 = "兑换{$pra['cz_val']}元充值卡消耗{$conver_val_}积分。[充值卡]";
				$sql = "insert into channel2_portal.tb_u_score_details set 
						salesperson_id = {$pra['id']},
						salesperson_name = '{$data[0]['real_name']}',
						op_score = '-{$conver_val_}',
						type = '100',
						active_day = '{$_time}',
						create_time = '{$_time}',
						update_time = '{$_time}',
						reserve3 = '{$_reserve3}'
						";
				$stu2=$memberModel->execSql($sql);
				$sql = "insert into channel2_portal.tb_u_soce_convert set 
						salesperson_id = {$pra['id']},
						salesperson_name = '{$data[0]['real_name']}',
						type = '{$pra['cz_val']}',
						create_time = '{$_time}'
						";
				$stu3=$memberModel->execSql($sql);
				$data['code'] = '006';
			}else $data['code'] = '005';
		}
		break;
	case 'create':
		$data = getCreate($pra);
		break;
	case 'sug':
		$pra['kw'] = trim($pra['kw']);
		if (!empty($pra['kw'])){
			$pra['fid']   = empty($_SESSION['isSup'])?$_SESSION['real_id']:0;
			$pra['limit'] = 10;
			$_data 		  = $memberModel->getAllInfo($pra);
			foreach ($_data as $item){
				$data[] = $item['name'];
			}
		}
		break;
	case 'show':
		$pra['nid'] = intval($pra['nid']);
		$data 	    = $memberModel->getInfo($pra);
		//处理children
		foreach ($data as $k=>$v){
			if ($pra['type'] == 1){
				if ($v['status'] == 0 || !$v['level']) unset($data[$k]);
				else{
					unset($data[$k]['pid']);
					unset($data[$k]['level']);
					unset($data[$k]['username']);
					unset($data[$k]['create_time']);
					unset($data[$k]['status']);
				}
			}else{
				$data[$k]['children'] = empty($v['level'])?'':array();
				$data[$k]['isexpand'] = false;
			}
		}
		if ($pra['type'] == 1) $data = array_values($data);
		break;
	case 'init':
		$q 		 = trim($pra['q']);
		$data[0] = array(
			'id'=>(empty($_SESSION['level'])&&!!$_SESSION['isSup']?0:$_SESSION['real_id']),
			'pid'=>null,
			'level'=>(empty($_SESSION['level'])&&!!$_SESSION['isSup']?-1:$_SESSION['level']),
			'children'=>(empty($_SESSION['level'])&&!!$_SESSION['isSup']?array():(empty($_SESSION['level'])?'':array())),
			'isexpand'=>true,
//			'icon' => '/resources/lib/ligerUI/skins/icons/right.gif',
			'text'=>(empty($_SESSION['level'])&&!!$_SESSION['isSup']?'全部体系账户':$_SESSION['username'])
		);
		if (!empty($_SESSION['level']) || $_SESSION['isSup']){
			foreach ($memberModel->getInfo(array('nid'=>$data[0]['id'])) as $item){
				$item['children'] = empty($item['level'])?'':array();
				$item['isexpand'] = false;
				$data[] = $item;
			}
		}
		if (!empty($q)){
			$_tmp_isat_pid = array();
			$_tmp_height   = array();
			$_pra['fid']   = empty($_SESSION['isSup'])?$_SESSION['real_id']:0;
			$_pra['kw']    = $q;
//			$_pra['limit'] = 10;
			$_data 		   = $memberModel->getAllInfo($_pra);
			if ($pra['type'] == 'new_cut'){
				echo json_encode(array('cut'=>count($_data)));exit;
			}
			if($pra['type'] == 'new_info'){
				$_pg_size = 10;
				$_data    = array_splice($_data,$pra['page']*$_pg_size,$_pg_size);
				$_cf_rf   = empty($_REQUEST['rf'])?'user':$_REQUEST['rf'];
//				if($_cf_rf == 'gold') {print_r($_data);exit;}
				foreach ($_data as $item){
					$_tmp_flag  = false;
					$_tmp_arr   = explode(',', '0,'.$item['ppath']);
					$_tmp_sf_id = array();
					$_tmp_df_dc = array();
					$_tmp_df_level = array();
					foreach ($_tmp_arr as $t_item){
						if ($_tmp_flag){
							$_tmp_sf_id[] = $t_item;
							$_tmp_cc      = $memberModel->getChnInfo(array('id'=>$t_item));
							$_tmp_df_dc[] = $_tmp_cc[0]['name'];
							$_tmp_df_level[] = $_tmp_cc[0]['level'];
						}
						if (!$_tmp_flag && $t_item == $_pra['fid']) $_tmp_flag = true;
					}
					$rs_data[] = array(
						'find_id' => implode(',', $_tmp_sf_id),
						'find_dc' => implode('>>', $_tmp_df_dc),
						'find_level' => implode(',', $_tmp_df_level),
						'self_id' => $_pra['fid'],
						'self_obj' => $item
					);
				}
				echo json_encode(array('data'=>$rs_data));exit;
			}
			$_tmp_isat_pid[] = $_pra['fid'];
			foreach ($_data as $item){
				$_tmp_flag= false;
				$_tmp_arr = explode(',', '0,'.$item['ppath']);
				//echo strrchr($item['ppath'],$_pra['fid']);exit;
				foreach ($_tmp_arr as $t_item){
					if(!$_tmp_flag && $t_item != $_pra['fid']) continue;
					if (!in_array($t_item,$_tmp_isat_pid)){
						$_tmp_data 	     = $memberModel->getInfo(array('nid'=>$t_item));
						foreach ($_tmp_data as $_t_item){
							$data[] = $_t_item;
						}
						$_tmp_isat_pid[] = $t_item;
					}
					$_tmp_flag = true;
				}
				$_tmp_height[] = $item['self_id'];
			}
			//产生高亮及其它初始化
			foreach ($data as $k=>$v){
//				$data[$k]['text']     = false === stripos($data[$k]['text'], $q) ? $data[$k]['text'] : more_hightlight(array($q),$data[$k]['text']);
				$data[$k]['text']     = in_array($data[$k]['id'], $_tmp_height)?"<font color=red>{$data[$k]['text']}</font>":$data[$k]['text'];
				$data[$k]['children'] = empty($v['level'])?'':array();
				$data[$k]['isexpand'] = in_array($v['id'], $_tmp_isat_pid)?true:false;
			}
		}
		break;
	default:break;
}
/**
 * 检测用户名是否已经存在
 * @param string $new_username
 */
function checkAgentOrClerkHad($new_username){
	global $memberModel;
	$return = array();
	$res = $memberModel->checkAgentOrClerkHad($new_username);
	if (empty($res[0]) && empty($res[1])){//无重复用户名
		$return[] = $new_username;
	}
	else {//有重复用户名，在此基础上加1
		$return[] = checkAgentOrClerkHad(strval($new_username+1));
	}
	return $return;
}
function more_hightlight($keyArr,$cnt){
	/*
	 * 若出现乱码问题请使用strtr时将字符转为utf-8
	 */
	if(empty($keyArr)) return $cnt;
	$temp = array();
	foreach (array_unique($keyArr) as $k=>$v){
		 $temp[$keyArr[$k]] = "<font color=red>{$keyArr[$k]}</font>";
	}
	return str_ireplace("</font><font color=red>","",strtr($cnt,$temp));
}
function getCreate($pra){
	global $memberModel;
	$pra['id']   = intval($pra['pid']);
	$_data 	     = $memberModel->getMatchInfo($pra);
	
	if ($pra['id'] == 0){//渠道
		$agent_sum = $memberModel->getAgentInfo();//统计现有渠道总数
		if (empty($_data)){
			$new_agent = $agent_sum[0]['agent_sum'].AGENT_NAME.($agent_sum[0]['agent_sum']+1);
			$res = checkAgentOrClerkHad($new_agent);//判断是否已存在该用户名
			if ($new_agent == $res){
				$data['u_new'] = $new_agent;
			}
			else {
				$data['u_new'] = $res;
			}
		}
	}
	else if ($pra['id'] > 0) {//子公司、店面、店员
		if (preg_match('/([^\d]+)(\d+)([^\d]*)$/is', $_data[0]['username'])){
			preg_match('/([^\d]+)(\d+)([^\d]*)$/is', $_data[0]['username'],$tmp);
			empty($tmp[1]) && $tmp = array('tmp00000','tmp','00000');
			if (!empty($tmp[1])){
				$ga_pra['create'] = $tmp[1];
				$_data 		  	  = $memberModel->getAllInfo($ga_pra);
				preg_match('/([^\d]+)(\d+)([^\d]*)$/is', empty($_data[0]['username'])?$tmp[0]:$_data[0]['username'],$tmp);
				$new 			  = $tmp[1].sprintf("%0".strlen($tmp[2])."d",$tmp[2]+1);
				$data['u_new'] 	  = $new;
			}else $data['u_new']  = $tmp[0];
		}else {
			if ($_data[0]['level'] == 3){//添加店员
				$new_clerk = $_data[0]['username']+1;
				$res = checkAgentOrClerkHad($new_clerk);//判断是否已存在该用户名
				if ($new_clerk == $res){
					$data['u_new'] = $new_clerk;
				}
				else {
					$data['u_new'] = $res;
				}
			}
			else {//添加子公司、店面
				$new_agent = $_data[0]['username'];
				$res = checkAgentOrClerkHad($new_agent);//判断是否已存在该用户名
				if ($new_agent == $res){
					$data['u_new'] = $new_agent;
				}
				else {
					$data['u_new'] = $res;
				}
			}
		}
	}
	
//	$pra['id']   = intval($pra['pid']);
//	$_data 	     = $memberModel->getMatchInfo($pra);
//	preg_match('/([^\d]+)(\d+)$/is', $_data[0]['username'],$tmp);
//	
//	empty($tmp[1]) && $tmp = array('tmp00000','tmp','00000');
//	if (!empty($tmp[1])){
//		$ga_pra['create'] = $tmp[1];
//		$_data 		  	  = $memberModel->getAllInfo($ga_pra);
//		preg_match('/([^\d]+)(\d+)$/is', empty($_data[0]['username'])?$tmp[0]:$_data[0]['username'],$tmp);
//		$new 			  = $tmp[1].sprintf("%0".strlen($tmp[2])."d",$tmp[2]+1);
//		$data['u_new'] 	  = $new;
//	}else $data['u_new']  = $tmp[0];
	return $data;
}
function salt(){
    $str = "0123456789";
    $s='';
    $len = strlen($str)-1;
    for($i=0 ; $i<6; $i++){
    	$s .=  $str[rand(0,$len)];
    }
    return  $s;
}
$data = $data==null?array():$data;
print_r((isset($_GET['callback'])?$_GET['callback']."(":"").json_encode($data).(isset($_GET['callback'])?")":""));