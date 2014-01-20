<?php
function smarty_function_statistic($params , &$smarty){
	$ret = $params['ret'];
	$tag = $params['tag'];
	$act = trim($params['act']);
	$type = intval($params['type']);
	
	if(!empty($_POST['user_type'])){
		$user_type = $_POST['user_type'];
	}else{
		$user_type = $_SESSION['user_type'];
	}
	
	if ($act == 'index'){
		switch ($tag){
			case 'get_shop_use':
				$statLoginModel = new statLoginModel();
				$arr_output     = $statLoginModel->getStatUseSketChnAllDataIndex(array());
				if(!empty($ret)){
					return $smarty->assign($ret , $arr_output);
				}else{
					return $arr_output;
				}
				break;
			case 'get_shop_login':
				$statLoginModel = new statLoginModel();
				$arr_output     = $statLoginModel->getStatLoginSketChnAllDataIndex(array());
				if(!empty($ret)){
					return $smarty->assign($ret , $arr_output);
				}else{
					return $arr_output;
				}
				break;
			case 'get_shop_gold':
				$statLoginModel = new statLoginModel();
				$arr_output     = $statLoginModel->getStatGoldSketChnAllDataIndex(array());
				if(!empty($ret)){
					return $smarty->assign($ret , $arr_output);
				}else{
					return $arr_output;
				}
				break;
		}
	}
	
	if ($user_type > 1) {
		switch ($tag){
			case "incomeseller":
				if(!empty($_POST['select'])){
					$seller_id = $_POST['select'];
				}else{
					$seller_id = $_SESSION['real_id'];
				}
				$arr_output = array();
				
				switch ($type){
					case 1:
						$arr_output = getYesterDayChannelIncomeList($seller_id);
					break;
					case 2:
						$arr_output = getThisMonthChannelIncomeList($seller_id);
					break;
					case 3:
						$arr_output = getLastMonthChannelIncomeList($seller_id);
					break;
					case 4:
						$arr_output = getSearchChannelIncomeList($seller_id);
					break;
					default:$arr_output = getToDayChannelIncomeList($seller_id);
				}
				
				if(!empty($ret)){
					return $smarty->assign($ret , $arr_output);
				}else{
					return $arr_output;
				}
				
			break;
			
			case "downloadseller":
				if(!empty($_POST['select'])){
					$seller_id = $_POST['select'];
				}else{
					$seller_id = $_SESSION['real_id'];
				}
				$arr_output = array();
				switch ($type){
					case 1:
						$arr_output = getYesterDayChannelDownloadList($seller_id);
					break;
					case 2:
						$arr_output = getThisMonthChannelDownloadList($seller_id);
					break;
					case 3:
						$arr_output = getLastMonthChannelDownloadList($seller_id);
					break;
					case 4:
						$arr_output = getSearchChannelDownloadList($seller_id);
					break;
					default:$arr_output = getToDayChannelDownloadList($seller_id);
				}
				
				if(!empty($ret)){
					return $smarty->assign($ret , $arr_output);
				}else{
					return $arr_output;
				}
				
			break;
			
			case "cardseller":
				if(!empty($_POST['select'])){
					$seller_id = $_POST['select'];
				}else{
					$seller_id = $_SESSION['real_id'];
				}
				$arr_output = array();
				
				switch ($type){
					case 1:
						$arr_output = getYesterDayChannelCardList($seller_id);
					break;
					case 2:
						$arr_output = getThisMonthChannelCardList($seller_id);
					break;
					case 3:
						$arr_output = getLastMonthChannelCardList($seller_id);
					break;
					case 4:
						$arr_output = getSearchChannelCardList($seller_id);
					break;
					default:$arr_output = getToDayChannelCardList($seller_id);
				}
				
				if(!empty($ret)){
					return $smarty->assign($ret , $arr_output);
				}else{
					return $arr_output;
				}
				
			break;
		}
	} else {
		switch ($tag){
			case "incomeseller":
				if(!empty($_POST['select'])){
					$seller_id = $_POST['select'];
				}else{
					$seller_id = $_SESSION['real_id'];
				}
				$arr_output = array();
				
				switch ($type){
					case 1:
						$arr_output = getYesterDaySellerIncomeList($seller_id);
					break;
					case 2:
						$arr_output = getThisMonthSellerIncomeList($seller_id);
					break;
					case 3:
						$arr_output = getLastMonthSellerIncomeList($seller_id);
					break;
					case 4:
						$arr_output = getSearchSellerIncomeList($seller_id);
					break;
					default:$arr_output = getToDaySellerIncomeList($seller_id);
				}
				
				if(!empty($ret)){
					return $smarty->assign($ret , $arr_output);
				}else{
					return $arr_output;
				}
				
			break;
			
			case "downloadseller":
				if(!empty($_POST['select'])){
					$seller_id = $_POST['select'];
				}else{
					$seller_id = $_SESSION['real_id'];
				}
				$arr_output = array();
				
				switch ($type){
					case 1:
						$arr_output = getYesterDaySellerDownloadList($seller_id);
					break;
					case 2:
						$arr_output = getThisMonthSellerDownloadList($seller_id);
					break;
					case 3:
						$arr_output = getLastMonthSellerDownloadList($seller_id);
					break;
					case 4:
						$arr_output = getSearchSellerDownloadList($seller_id);
					break;
					default:$arr_output = getToDaySellerDownloadList($seller_id);
				}
				
				if(!empty($ret)){
					return $smarty->assign($ret , $arr_output);
				}else{
					return $arr_output;
				}
				
			break;
			
			case "cardseller":
				if(!empty($_POST['select'])){
					$seller_id = $_POST['select'];
				}else{
					$seller_id = $_SESSION['real_id'];
				}
				$arr_output = array();
				
				switch ($type){
					case 1:
						$arr_output = getYesterDaySellerCardList($seller_id);
					break;
					case 2:
						$arr_output = getThisMonthSellerCardList($seller_id);
					break;
					case 3:
						$arr_output = getLastMonthSellerCardList($seller_id);
					break;
					case 4:
						$arr_output = getSearchSellerCardList($seller_id);
					break;
					default:$arr_output = getToDaySellerCardList($seller_id);
				}
				
				if(!empty($ret)){
					return $smarty->assign($ret , $arr_output);
				}else{
					return $arr_output;
				}
				
			break;
		}
	}
	return ;
}
?>