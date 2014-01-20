<?php
error_reporting(0);
session_start();
require_once("../config.php");
require_once(TP_LIB_DIR."admin.php");
ZwzInc::import('lib.classes.biz.model.*');

$model_statitic = new Statitic();


$tag 			= trim($_REQUEST['tag']);
$type 			= intval($_REQUEST['type']);
$arr_data		= $arr_ids = array();
$code 			= '006';
$user_type		= !empty($_POST['user_type']) ? $_POST['user_type'] : $_SESSION['user_type'];
$select			= trim($_REQUEST['_tmp_sel']);

$arr_tmp  		= explode(':', $select);
empty($arr_tmp[0]) && $arr_tmp[0] = $_SESSION['real_id'];

$_arr_tmp[$user_type][]  = $arr_tmp[0];
$_arr_tmp[$arr_tmp[2]][] = $arr_tmp[1];
if (!empty($_arr_tmp[2])){
	$ids_chn = implode(',', array_filter($_arr_tmp[2]));
}
if (!empty($_arr_tmp[1])){
	$ids_per = implode(',', array_filter($_arr_tmp[1]));
}

switch ($tag){
	case "download":
		switch ($type){
			case '4':
			if (!empty($ids_chn)){
				$pra = array(
					'curid' => $arr_tmp[0],
					'chnid' => $ids_chn,
					'start_time' => $_REQUEST['start_time'],
					'end_time' 	 => $_REQUEST['end_time']
				);
				$arr_data_chn = $model_statitic->getChnDownList($pra);
			}
			if (!empty($ids_per)){
				$pra = array(
					'curid' => $arr_tmp[0],
					'chnid' => $ids_per,
					'shopid' 	 => intval($ids_chn),
					'start_time' => $_REQUEST['start_time'],
					'end_time' 	 => $_REQUEST['end_time']
				);
				$arr_data_per = $model_statitic->getPerDownList($pra);
				if (!empty($pra['shopid']) && !empty($arr_data_per)){
					foreach ($arr_data_per as $item){
						if (!in_array($item['channel_id'], array_filter($_arr_tmp[1]))){
							$arr_ids[] = $item['channel_id'];
						}
					}
					if (0 < count($arr_ids)){
						$model_chagent  = new ChAgent();
						$arr_rs_ids 	= $model_chagent->getChAgent(array('ids'=>$arr_ids,'sel'=>array('name','id')));
						foreach ($arr_rs_ids as $k=>$v){
							$arr_rs_ids[$v['id']] = $v['name'];
							unset($arr_rs_ids[$k]);
						}
					}
				}
			}
			$_arr_data = array_merge((array)$arr_data_per,(array)$arr_data_chn);
			if (!empty($_arr_data)){
				foreach ($_arr_data as $k=>$v){
					foreach ($v as $kk=>$vv)  $_arr_data[$k][$kk]=intval($vv);
				}
			}
			$_tmp 			     = end($_arr_data);
			$arr_data['collect'] = array('汇总',$_tmp['soft_total'],$_tmp['music_total'],$_tmp['ebook_total'],$_tmp['local_music_total'],$_tmp['local_video_total'],$_tmp['collect_toal']);
			break;
			default:break;
		}
		break;
	case 'card':
		switch ($type){
			case '4':
			if (!empty($ids_chn)){
				$pra = array(
					'curid' => $arr_tmp[0],
					'chnid' => $ids_chn,
					'start_time' => $_REQUEST['start_time'],
					'end_time' 	 => $_REQUEST['end_time']
				);
				$arr_data_chn = $model_statitic->getChnCardDownList($pra);
			}
			if (!empty($ids_per)){
				$pra = array(
					'curid' => $arr_tmp[0],
					'chnid' => $ids_per,
					'shopid' 	 => intval($ids_chn),
					'start_time' => $_REQUEST['start_time'],
					'end_time' 	 => $_REQUEST['end_time']
				);
				$arr_data_per = $model_statitic->getPerCardDownList($pra);
				if (!empty($pra['shopid']) && !empty($arr_data_per)){
					foreach ($arr_data_per as $item){
						if (!in_array($item['channel_id'], array_filter($_arr_tmp[1]))){
							$arr_ids[] = $item['channel_id'];
						}
					}
					if (0 < count($arr_ids)){
						$model_chagent  = new ChAgent();
						$arr_rs_ids 	= $model_chagent->getChAgent(array('ids'=>$arr_ids,'sel'=>array('name','id')));
						foreach ((array)$arr_rs_ids as $k=>$v){
							$arr_rs_ids[$v['id']] = $v['name'];
							unset($arr_rs_ids[$k]);
						}
					}
				}
			}
			$_arr_data = array_merge((array)$arr_data_per,(array)$arr_data_chn);
			if (!empty($_arr_data)){
				foreach ($_arr_data as $k=>$v){
					foreach ($v as $kk=>$vv)  $_arr_data[$k][$kk]=intval($vv);
				}
			}
			$_tmp 			     = end($_arr_data);
			$arr_data['collect'] = array('汇总',$_tmp['3'],$_tmp['5'],$_tmp['7'],$_tmp['9'],$_tmp['11']);
			break;
			default:break;
		}
		break;
	case 'index':
		if ($type == 4){
			if (!empty($ids_chn)){
				$pra = array(
					'curid' => $arr_tmp[0],
					'chnid' => $ids_chn,
					'start_time' => $_REQUEST['start_time'],
					'end_time' 	 => $_REQUEST['end_time']
				);
				$arr_data_chn = $model_statitic->getChnIncomeList($pra);
			}
			if (!empty($ids_per)){
				$pra = array(
					'curid' => $arr_tmp[0],
					'chnid' => $ids_per,
					'shopid' 	 => intval($ids_chn),
					'start_time' => $_REQUEST['start_time'],
					'end_time' 	 => $_REQUEST['end_time']
				);
				$arr_data_per = $model_statitic->getPerIncomeList($pra);
				if (!empty($pra['shopid']) && !empty($arr_data_per)){
					foreach ($arr_data_per as $item){
						if (!in_array($item['channel_id'], array_filter($_arr_tmp[1]))){
							$arr_ids[] = $item['channel_id'];
						}
					}
					if (0 < count($arr_ids)){
						$model_chagent  = new ChAgent();
						$arr_rs_ids 	= $model_chagent->getChAgent(array('ids'=>$arr_ids,'sel'=>array('name','id')));
						foreach ($arr_rs_ids as $k=>$v){
							$arr_rs_ids[$v['id']] = $v['name'];
							unset($arr_rs_ids[$k]);
						}
					}
				}
			}
			$_arr_data = array_merge((array)$arr_data_per,(array)$arr_data_chn);
			if (!empty($_arr_data)){
				foreach ($_arr_data as $k=>$v){
					foreach ($v as $kk=>$vv)  $_arr_data[$k][$kk]=intval($vv);
				}
			}
			$_tmp 			     = end($_arr_data);
			$arr_data['collect'] = array('汇总',$_tmp['2'],$_tmp['3'],$_tmp['4'],$_tmp['5'],$_tmp['6'],$_tmp['7']);
		}
		break;
	default:break;
}

$arr_data['detail']  = $_arr_data;
$arr_data['agent']   = (array)$arr_rs_ids;
echo json_encode(array('code'=>$code,'data'=>$arr_data));