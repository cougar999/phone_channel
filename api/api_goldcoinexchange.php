<?php
error_reporting(0);
require_once('../config.php');
require_once(TP_LIB_DIR.'classes/biz/goldcoin/GoldCoinExchange.php');
require_once(TP_LIB_DIR.'classes/biz/common/util/Pager.php');
require_once(TP_LIB_DIR."classes/biz/member/Person.php");
require_once(TP_LIB_DIR."classes/biz/member/Member.php");
require_once(TP_LIB_DIR.'post.php');
require_once(TP_LIB_DIR.'feeding.php');

function isAdminGoldExchange($real_id,$_super_mager){
	$admingoldcache_file = TP_DATA_DIR.'data.txt';
	if(in_array($real_id,$_super_mager)){
			return true;
	}elseif(file_exists($admingoldcache_file)){
		$cache = file_get_contents($admingoldcache_file);
		$arr_adminuser = unserialize($cache);
		if(array_key_exists($real_id,$arr_adminuser)){
			return true;
		}else {
			return false;
		}
	}
	return false;
}

$type = trim($_REQUEST['type']);
$act  = trim($_REQUEST['act']);

$obj_GoldCoinExchange = new GoldCoinExchange();
$o_person = new Person();
$o_member = new Member();

unset($arr_input);
unset($arr_output);

switch ($type){
	case "confirm_payment":
		$arr_input['id'] = join(',' , $_POST['ids']);
		$arr_input['admin_id'] = intval($_REQUEST['admin_id']);
		if(!isAdminGoldExchange($arr_input['admin_id'],$_super_mager)){
			$arr_output['result'] = '权限不足';
			break;
		}
		$arr_input['status'] = 4;
		$result = $obj_GoldCoinExchange->modifyGoldCoinExchange($arr_input);
		if($result){
			$arr_output['result_desc'] = '完成确认付款流程！';
			$arr_output['result'] = 'success';
		}else{
			$arr_output['result'] = 'failed';
		}
		break;
	
	case "lists":
		$arr_input['status'] = intval($_POST['status']);
		if(array_key_exists('ex_type', $_POST)){
			$arr_input['ex_type'] = intval($_POST['ex_type']);
		}
		$total_count = $obj_GoldCoinExchange->getGoldCoinExchangeListCount($arr_input);
		
		$pageno = intval($_REQUEST['pageno']);
		$count = intval($_REQUEST['count']);
		$obj_pager = new Pager($pageno,$total_count,'',$count);
		
		if($total_count > 0){
			$arr_list = $obj_GoldCoinExchange->getGoldCoinExchangeList($arr_input,$obj_pager->getLimitQuery());
			for ($i = 0;$i < count($arr_list);$i++){
				$arr_real_id[] = $arr_list[$i]['sales_id'];
			}
			$arr_real_id = array_unique($arr_real_id);
			unset($arr_hash_sales_info);
			foreach ($arr_real_id as $real_id){
				unset($arr_pinput);
				$arr_pinput['id'] = $real_id;
				$arr_person_info = $o_person->viewPersonById($arr_pinput);
				unset($arr_agent_input);
				$arr_agent_input['id'] = $arr_person_info['agent_id'];
				$arr_member_agent = $o_member->getMemberAgentInfo($arr_agent_input);
				$arr_hash_sales_info[$real_id]['real_name'] = $arr_person_info['real_name'];
				$arr_hash_sales_info[$real_id]['agent_info'] = join(' - ', $arr_member_agent);
			}
			for ($i = 0;$i < count($arr_list);$i++){
				$real_id = $arr_list[$i]['sales_id'];
				$arr_list[$i]['sales_name'] = $arr_hash_sales_info[$real_id]['real_name'];
				$arr_list[$i]['agent_info'] = $arr_hash_sales_info[$real_id]['agent_info'];
			}
		}else{
			$arr_list = array();
		}
		$arr_output['totalcount'] = $total_count;
		$arr_output['pageno'] = $pageno;
		$arr_output['count'] = $count;
		$arr_output['items'] = $arr_list;
		
		break;
	case "list":
		$arr_input['status'] = 1;
		if(array_key_exists('ex_type', $_POST)){
			$arr_input['ex_type'] = intval($_POST['ex_type']);
		}
		$total_count = $obj_GoldCoinExchange->getGoldCoinExchangeListCount($arr_input);
		
		$pageno = intval($_REQUEST['pageno']);
		$count = intval($_REQUEST['count']);
		$obj_pager = new Pager($pageno,$total_count,'',$count);
		
		if($total_count > 0){
			$arr_list = $obj_GoldCoinExchange->getGoldCoinExchangeList($arr_input,$obj_pager->getLimitQuery());
			for ($i = 0;$i < count($arr_list);$i++){
				$arr_real_id[] = $arr_list[$i]['sales_id'];
			}
			$arr_real_id = array_unique($arr_real_id);
			unset($arr_hash_sales_info);
			foreach ($arr_real_id as $real_id){
				unset($arr_pinput);
				$arr_pinput['id'] = $real_id;
				$arr_person_info = $o_person->viewPersonById($arr_pinput);
				unset($arr_agent_input);
				$arr_agent_input['id'] = $arr_person_info['agent_id'];
				if(empty($arr_agent_input['id'])){
					$arr_hash_sales_info[$real_id]['real_name'] = '“该用户已删除”';
					$arr_hash_sales_info[$real_id]['agent_info'] = '由于用户数据清理，该用户已删除，无信息！';
				}else{
					$arr_member_agent = $o_member->getMemberAgentInfo($arr_agent_input);
					$arr_hash_sales_info[$real_id]['real_name'] = $arr_person_info['real_name'];
					$arr_hash_sales_info[$real_id]['agent_info'] = join(' - ', $arr_member_agent);
				}
			}
			for ($i = 0;$i < count($arr_list);$i++){
				$real_id = $arr_list[$i]['sales_id'];
				$arr_list[$i]['sales_name'] = $arr_hash_sales_info[$real_id]['real_name'];
				$arr_list[$i]['agent_info'] = $arr_hash_sales_info[$real_id]['agent_info'];
			}
		}else{
			$arr_list = array();
		}
		$arr_output['totalcount'] = $total_count;
		$arr_output['pageno'] = $pageno;
		$arr_output['count'] = $count;
		$arr_output['items'] = $arr_list;
		
		break;
	case "send_sms":
		$arr_input['id'] = intval($_REQUEST['id']);
		$arr_input['admin_id'] = intval($_REQUEST['admin_id']);
		if(!isAdminGoldExchange($arr_input['admin_id'],$_super_mager)){
			$arr_output['result'] = '权限不足';
			break;
		}
		$result_desc = '';
		/**send sms***********begin*************/
		
		$data_sms = array();
					
		unset($arr_input_by_id);
		$arr_input_by_id['id'] = intval($_REQUEST['id']);
		
		unset($arr_goldcoin_info);
		$arr_goldcoin_info = $obj_GoldCoinExchange->getGoldCoinExchangeById($arr_input_by_id);
		
		$int_ex_type	 =	 $arr_goldcoin_info['ex_type'];
		$str_telphone	 =	 $arr_goldcoin_info['telphone'];
		$int_goldcoin	 =	 $arr_goldcoin_info['goldcoin'];
		
		if(!empty($int_ex_type) && $int_ex_type != 0 && !empty($str_telphone)){
			$data_sms['mobile'] = $str_telphone;
			//$str_create_time = date("Y年m月d日 H:i",strtotime($arr_goldcoin_info['create_time']));
			switch ($int_ex_type){
				case 1:
					$str_payment_mobnumber = $arr_goldcoin_info['payment_mobnumber'];
					$data_sms['msg'] = '爱皮下载' . $int_goldcoin . '金币兑换已审核。话费充值手机号：' . $str_payment_mobnumber . '，确认无误请回复 Y 以便充值。详询4008855060';
					break;
				case 2:
					$str_bank_card_no = $arr_goldcoin_info['bank_card_no'];
					$str_sms_bank_card_weihao = substr($str_bank_card_no, strlen($str_bank_card_no)-4, 4);
					$str_account_name = $arr_goldcoin_info['account_name'];
					$str_bank_name = $arr_goldcoin_info['bank_name'];
					$str_sms_bank_name = preg_replace('/(.*)银行(.*)/','\1银行',$str_bank_name);
					$data_sms['msg'] = '爱皮下载' . $int_goldcoin . '金币兑换已审核。' . $str_sms_bank_name . '卡尾号：' . $str_sms_bank_card_weihao . '，开户人：' . $str_account_name . '，确认无误请回复 Y 以便付款。详询4008855060';
					break;
			}
			//$data_sms['msg'] = urlencode($data_sms['msg']);
			$data_sms['bcode'] = SMS_GOLDCOIN_STATE_BCODE;
			$sms_sign = ap_core_feeding("AP_INT_SMSCATD",$data_sms);
		}
		/**send sms***********end*************/
		if(!empty($sms_sign)){
			$arr_input['status'] = 2;
			$arr_input['sms_sign'] = $sms_sign;
			$result_desc = '发送成功！';
			$result = $obj_GoldCoinExchange->modifyGoldCoinExchange($arr_input);
		}else{
			$result_desc = '短信提醒发送失败！请联系技术人员！';
		}
		if($result){
			$arr_output['result_desc'] = $result_desc;
			$arr_output['result'] = 'success';
		}else{
			$arr_output['result'] = 'failed';
		}
		break;
	case "pass":
		$arr_input['id'] = intval($_REQUEST['id']);
		$arr_input['admin_id'] = intval($_REQUEST['admin_id']);
		if(!isAdminGoldExchange($arr_input['admin_id'],$_super_mager)){
			$arr_output['result'] = '权限不足';
			break;
		}
		$result_desc = $arr_input['description'] = '';
		$data['salesid'] = intval($_REQUEST['salesid']);
		$data['count'] = intval($_REQUEST['count']);
		$data['operatetime'] = addslashes(($_REQUEST['operatetime']));
					
		$detail = ap_core_post("AP_INT_CASH" , $data);
		if(isset($detail['result']) && ('000000' == $detail['result']['resultcode'])){
			$int_jsp_result = $detail['isok'];
			switch ($int_jsp_result){
				case 51:
					$arr_input['status'] = 2;
					//*send sms***********begin*************
					
					$data_sms = array();
								
					unset($arr_input_by_id);
					$arr_input_by_id['id'] = intval($_REQUEST['id']);
					
					unset($arr_goldcoin_info);
					$arr_goldcoin_info = $obj_GoldCoinExchange->getGoldCoinExchangeById($arr_input_by_id);
					
					$int_ex_type	 =	 $arr_goldcoin_info['ex_type'];
					$str_telphone	 =	 $arr_goldcoin_info['telphone'];
					$int_goldcoin	 =	 $arr_goldcoin_info['goldcoin'];
					
					if(!empty($int_ex_type) && $int_ex_type != 0 && !empty($str_telphone)){
						$data_sms['mobile'] = $str_telphone;
						//$str_create_time = date("Y年m月d日 H:i",strtotime($arr_goldcoin_info['create_time']));
						switch ($int_ex_type){
							case 1:
								$str_payment_mobnumber = $arr_goldcoin_info['payment_mobnumber'];
								$data_sms['msg'] = '爱皮下载' . $int_goldcoin . '金币兑换已审核。话费充值手机号：' . $str_payment_mobnumber . '，确认无误请回复 Y 以便充值。详询4008855060';
								break;
							case 2:
								$str_bank_card_no = $arr_goldcoin_info['bank_card_no'];
								$str_sms_bank_card_weihao = substr($str_bank_card_no, strlen($str_bank_card_no)-4, 4);
								$str_account_name = $arr_goldcoin_info['account_name'];
								$str_bank_name = $arr_goldcoin_info['bank_name'];
								$str_sms_bank_name = preg_replace('/(.*)银行(.*)/','\1银行',$str_bank_name);
								$data_sms['msg'] = '爱皮下载' . $int_goldcoin . '金币兑换已审核。' . $str_sms_bank_name . '卡尾号：' . $str_sms_bank_card_weihao . '，开户人：' . $str_account_name . '，确认无误请回复 Y 以便付款。详询4008855060';
								break;
						}
						//$data_sms['msg'] = urlencode($data_sms['msg']);
						$data_sms['bcode'] = SMS_GOLDCOIN_STATE_BCODE;
						$sms_sign = ap_core_feeding("AP_INT_SMSCATD",$data_sms);
					}
					//*send sms***********end*************
					if(!empty($sms_sign)){
						$arr_input['sms_sign'] = $sms_sign;
						$result_desc = '完成兑换流程！';
					}else{
						$result_desc = '完成兑换流程！但短信提醒发送失败！请联系技术人员！';
					}
					break;
				case 52:
					$result_desc = $arr_input['description'] = '该店员金币余额不足！';
					$arr_input['status'] = 3;
					break;
				case 53:
					$result_desc = $arr_input['description'] = '店员不存在！';
					$arr_input['status'] = 3;
					break;
				case 54:
					$result_desc = $arr_input['description'] = '金币兑换出错！';
					$arr_input['status'] = 3;
					break;
			}
			$result = $obj_GoldCoinExchange->modifyGoldCoinExchange($arr_input);
		
		}
		if($result){
			$arr_output['result_desc'] = $result_desc;
			$arr_output['result'] = 'success';
		}else{
			$arr_output['result'] = 'failed';
		}
		break;
	case "refuse":
		$arr_input['id'] = intval($_REQUEST['id']);
		$arr_input['admin_id'] = intval($_REQUEST['admin_id']);
		if(!isAdminGoldExchange($arr_input['admin_id'],$_super_mager)){
			$arr_output['result'] = '权限不足';
			break;
		}
		$arr_input['status'] = 3;
		$arr_input['description'] = addslashes($_REQUEST['description']);
		$result = $obj_GoldCoinExchange->modifyGoldCoinExchange($arr_input);
		if($result){
			$arr_output['result'] = 'success';
		}else{
			$arr_output['result'] = 'failed';
		}
		break;
}
echo json_encode($arr_output);
?>