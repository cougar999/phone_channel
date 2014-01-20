<?php
require_once(TP_LIB_DIR.'classes/biz/common/util/Pager.php');
require_once(TP_LIB_DIR."classes/biz/intergral/Intergral.php");
ZwzInc::import('lib.classes.biz.model.*');

function getIntergralDetailsList($params){
	$count = $params['count'];
	$pageno = $params['pageno'];
	
	$o_intergral = new Intergral();
	unset($arr_input);
	$arr_input['salesperson_id'] = $_SESSION['real_id'];
	
	$total_cnt = $o_intergral->getIntergralDetailsListCount($arr_input);
	$view_cnt = $count;
	$block_cnt = 10;
	$extra_url = "";
	
	$obj_Pager = new Pager($pageno, $total_cnt, $tp_tpl_page, $view_cnt, $block_cnt, $extra_url);
	
	if ($total_cnt){
		$a_intergral_list = $o_intergral->getIntergralDetailsList($arr_input,$obj_Pager->getLimitQuery());
		foreach ($a_intergral_list as $key => $row){
			$a_intergral_list[$key]['description'] = getIntergralDetailsDesc($row);
		}
	}else{
		$a_intergral_list = array();
	}
	$a_result = array("pagehtml"=>$obj_Pager->getPagingHtml(),"info"=>$a_intergral_list);
	
	return $a_result;
}

function getIntergralRanking($params){
	$is_month = $params['is_month'];
	$count = $is_month?20:10;
	
	if($is_month == 1){
		$currmonth = date("Y-m",time());
	}
	
	$o_intergral = new Intergral();
	unset($arr_input);
	$arr_input['top_agent_id'] = $_SESSION['agentid'];
	
	if(empty($currmonth)){
		$total_cnt = $o_intergral->getIntergralListCount($arr_input);
		$view_cnt = $count;
		$block_cnt = 10;
		$extra_url = "";
		
		$obj_Pager = new Pager($page, $total_cnt, $tp_tpl_page, $view_cnt, $block_cnt, $extra_url);
		
		if ($total_cnt){
			$a_intergral_list = $o_intergral->getIntergralList($arr_input,$obj_Pager->getLimitQuery());
		}else{
			$a_intergral_list = array();
		}
	}else{
		$arr_input['month'] = $currmonth;
		$total_cnt = $o_intergral->getIntergralMonthListCount($arr_input);
		$view_cnt = $count;
		$block_cnt = 10;
		$extra_url = "";
		
		$obj_Pager = new Pager($page, $total_cnt, $tp_tpl_page, $view_cnt, $block_cnt, $extra_url);
		
		if ($total_cnt){
			$a_intergral_list = $o_intergral->getIntergralMonthList($arr_input,$obj_Pager->getLimitQuery());
		}else{
			$a_intergral_list = array();
		}
	}
	return $a_intergral_list;
}

function getIntergralInfo($uid){
	$o_intergral = new Intergral();
	
	unset($arr_input);
	$arr_input['top_agent_id'] = $_SESSION['agentid'];
	$arr_input['salesperson_id'] = $_SESSION['real_id'];
	
	$a_intergral_info = $o_intergral->getIntergralInfo($arr_input);
	return $a_intergral_info[0];
}

function getIntergralDetailsDesc($array){
	$_new_match_desc = array(
		'1' => array(
			'score' => 5,
			'desc'  => '成功登录获得{$op_score}点积分。'
		),
		'2' => array(
			'score' => 5,
			'desc'  => '成功连接{$op_times}部手机获得{$op_score}点积分。'
		),
		'3' => array(
			'score' => 2,
			'desc'  => '成功为IMEI码为({$imei})的手机安装{$op_times}个应用获得{$op_score}点积分。'
		),
		'4' => array(
			'score' => 20,
			'desc'  => '成功为IMEI码为({$imei})的手机双机复制成功一次获得{$op_score}点积分。'
		),
		'5' => array(
			'score' => 10,
			'desc'  => '成功为手机号为({$phone_no})的手机快速注册一个会员获得{$op_score}点积分。'
		),
		'6' => array(
			'score' => 10,
			'desc'  => '云备份成功一次获得{$op_score}点积分。'
		),
		'7' => array(
			'score' => 20,
			'desc'  => '下载卡联机(IMEI码为{$imei})激活一次获得{$op_score}点积分。'
		),
		'8' => array(
			'score' => 10,
			'desc'  => '下载卡非联机激活一次获得{$op_score}点积分。'
		),
		'10' => array(
			'score' => 50,
			'desc'  => '苹果下载卡联机(IMEI码为{$imei})激活一次获得{$op_score}点积分'
		),
		'11' => array(
			'score' => 3,
			'desc'  => '成功为IMEI码为({$imei})的手机安装{$op_times}个应用获得{$op_score}点积分。（畅游金榜下载页下载）'
		),
		'12' => array(
			'score' => 2,
			'desc'  => '成功为IMEI码为({$imei})的手机安装{$op_times}个应用获得{$op_score}点积分。（新品推荐下载页下载）'
		),
		'14' => array(
			'score' => 2,
			'desc'  => '成功为IMEI码为({$imei})的手机更新{$op_times}个应用获得{$op_score}点积分。'
		)
	);
	extract($array);
	if ($op_score > 0){
		$imei			=	substr($imei,0,4)."****".substr($imei,-4);
		if (empty($_new_match_desc[$type])) return $reserve3;
		else{
			$_optimes    = intval($op_times)==0?1:intval($op_times);
			eval('$_desc = "'.$_new_match_desc[$type]['desc'].'";');
			$bd          = $op_score/$_optimes;
			if ($bd/$_new_match_desc[$type]['score'] > 1){
				$_desc .= $bd%$_new_match_desc[$type]['score']==0?"（".($bd/$_new_match_desc[$type]['score'])."倍积分活动）":"（赠送".($bd-$_new_match_desc[$type]['score'])."积分活动）";
			}
			return $_desc;
		}
	}
	$type			=	$array['type'];
	$op_times		=	$array['op_times'];
	$imei			=	$array['imei'];
	$imei			=	substr($imei,0,4)."****".substr($imei,-4);
	$phone_no		=	$array['phone_no'];
	$phone_no		=	substr($phone_no,0,3)."****".substr($phone_no,-4);
	$other_desc		=	$array['reserve3'];
	$is_event		=	false;
	if(($array['active_day'] >= '2012-03-30') && ($array['active_day'] <= '2012-03-31')){
		$is_event = true;
		$event_desc = '（月末双倍积分活动）';
	}elseif (($array['active_day'] >= '2012-04-26') && ($array['active_day'] <= '2012-04-28')){
		$is_event_3 = true;
		$event_desc = '（月末三倍积分活动）';
	}elseif (($array['active_day'] >= '2012-06-01') && ($array['active_day'] <= '2012-06-30')){
		
	}
	
	
	switch($type){
		case "1":
			$detailsdesc	=	EVERY_LOGIN_DAY_DESC;
			$value			=	EVERY_LOGIN_DAY_VALUE;
			if($is_event)	{$value *= 2;$detailsdesc .= $event_desc;}
			if ($is_event_3) {$value *= 3;$detailsdesc .= $event_desc;}
			$search			=	array('$1',);
			$replace		=	array($value);
		break;
		case "2":
			$detailsdesc	=	CONNECT_PHONE_SUCCESS_DESC;
			$value			=	CONNECT_PHONE_SUCCESS_VALUE;
			if($is_event)	{$value *= 2;$detailsdesc .= $event_desc;}
			$search			=	array('$1','$2');
			$replace		=	array($value*$op_times,$op_times);
		break;
		case "3":
			$detailsdesc	=	INSTALL_APP_SUCCESS_DESC;
			$value			=	INSTALL_APP_SUCCESS_VALUE;
			$score			=	($value*$op_times) > 200 ? 200 : ($value*$op_times);
			if($is_event){
				$score			=	($value*2*$op_times) > 400 ? 400 : ($value*2*$op_times);
				$detailsdesc .= $event_desc;
			}
			if($is_event_3){
				$score		  =	($value*3*$op_times) > 200 ? 200 : ($value*3*$op_times);
				$detailsdesc .= $event_desc;
			}
			$search			=	array('$1','$2','$3');
			$replace		=	array($score,$op_times,$imei);
			break;
		case "4":
			$detailsdesc	=	TWO_PHONE_BACKUP_DESC;
			$value			=	TWO_PHONE_BACKUP_VALUE;
			if($is_event)	{$value *= 2;$detailsdesc .= $event_desc;}
			$search			=	array('$1',"$3");
			$replace		=	array($value,$imei);
			break;
		case "5":
			$detailsdesc	=	QUICKLY_REGISTER_DESC;
			$value			=	QUICKLY_REGISTER_VALUE;
			if($is_event)	{$value *= 2;$detailsdesc .= $event_desc;}
			if($is_event_3)	{$value *= 3;$detailsdesc .= $event_desc;}
			$search			=	array('$1','$4');
			$replace		=	array($value,$phone_no);
		break;
		case "6":
			$detailsdesc	=	CLOUD_BACKUP_DESC;
			$value			=	CLOUD_BACKUP_VALUE;
			if($is_event)	{$value *= 2;$detailsdesc .= $event_desc;}
			$search			=	array('$1');
			$replace		=	array($value);
		break;
		case "7":
			$detailsdesc 	=   ACTIVATION_CARD_PHONE_DESC;
			$value			=	ACTIVATION_CARD_PHONE_VALUE;
			if($is_event)	{$value *= 2;$detailsdesc .= $event_desc;}
			$search			=	array('$1','$3');
			$replace		=	array($value,$imei);
		break;
		case "8":
			$detailsdesc	= 	ACTIVATION_CARD_NOPHONE_DESC;
			$value			=	ACTIVATION_CARD_NOPHONE_VALUE;
			if($is_event)	{$value *= 2;$detailsdesc .= $event_desc;}
			$search			=	array('$1');
			$replace		=	array($value);
		break;
		case "9":
			$detailsdesc	=	EXDOWNLOAD_INSTALL_APP_SUCCESS_DESC;
			$value			=	INSTALL_APP_SUCCESS_VALUE;
			$score			=	($value*$op_times) > 200 ? 200 : ($value*$op_times);
			if($is_event){
				$score			=	($value*2*$op_times) > 400 ? 400 : ($value*2*$op_times);
			}
			$search			=	array('$1','$2','$3');
			$replace		=	array($score,$op_times,$imei);
		break;
		case "10":
			$detailsdesc 	=   ACTIVATION_CARD_PHONE_IOS_DESC;
			$value			=	ACTIVATION_CARD_PHONE_IOS_VALUE;
			if($is_event)	{$value *= 2;$detailsdesc .= $event_desc;}
			$search			=	array('$1','$3');
			$replace		=	array($value,$imei);
		break;
		case "11":
			$detailsdesc	=	CYJBDOWNLOAD_INSTALL_APP_SUCCESS_DESC;
			$value			=	CYJBDOWNLOAD_INSTALL_APP_SUCCESS_VALUE;
			$score			=	($value*$op_times) > 100 ? 100 : ($value*$op_times);
			$search			=	array('$1','$2','$3');
			$replace		=	array($score,$op_times,$imei);
		break;
		case "12":
			$detailsdesc	=	XPTJDOWNLOAD_INSTALL_APP_SUCCESS_DESC;
			$value			=	XPTJDOWNLOAD_INSTALL_APP_SUCCESS_VALUE;
			$score			=	($value*$op_times) > 100 ? 100 : ($value*$op_times);
			$search			=	array('$1','$2','$3');
			$replace		=	array($score,$op_times,$imei);
		break;
		case "100":
			//$detailsdesc	= 	$other_desc."<font color=\"red\">(系统操作)</font>";
			$detailsdesc	= 	$other_desc."（系统操作）";
			$search			=	array();
			$replace		=	array();
		break;
		case "20":
			//$detailsdesc	= 	$other_desc."<font color=\"red\">(系统操作)</font>";
			$detailsdesc	= 	$other_desc."（系统操作）";
			$search			=	array();
			$replace		=	array();
		break;
	}
	$desc			=	str_replace($search,$replace,$detailsdesc);
	return $desc;
}

function getLevelDesc($params){
	$level = $params['level'];
	switch ($level){
		case "1":$desc = "铁牌店员";break;
		case "2":$desc = "铜牌店员";break;
		case "3":$desc = "银牌店员";break;
		case "4":$desc = "金牌店员";break;
		case "5":$desc = "白金店员";break;
	}
	
	return $desc;
}

function getActivity($params){
	$acti = new IntergralModel();
	return $acti->getIntergralInfo($params);
}
?>