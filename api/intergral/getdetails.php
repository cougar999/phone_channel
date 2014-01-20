<?php
require_once("../../config.php");
require_once("common.func.php");
require_once(TP_LIB_DIR."classes/biz/intergral/Intergral.php");
require_once(TP_LIB_DIR."intergral.php");

function getDetailsList($params){
	$count = $params['count'];
	$pageno = intval($params['pageno']) >= 1 ? intval($params['pageno']) : 1;
	
	$o_intergral = new Intergral();
	unset($arr_input);
	$arr_input['salesperson_id'] = $params['uid'];
	
	$total_cnt = $o_intergral->getIntergralDetailsListCount($arr_input);
	$block_cnt = $count;
	
	$limit_query = " limit ".$block_cnt*($pageno-1)." , $block_cnt";
	
	if ($total_cnt){
		$a_intergral_list = $o_intergral->getIntergralDetailsList($arr_input,$limit_query);
		
		foreach ($a_intergral_list as $key => $row){
			$a_intergral_list[$key]['description'] = getIntergralDetailsDesc($row);
		}
	}else{
		$a_intergral_list = array();
	}
	$a_result['pageno'] = $pageno;
	$a_result['pagenum'] = $count;
	$a_result['totalcount'] = $total_cnt;
	$a_result['items'] = $a_intergral_list;
	
	return $a_result;
}

if(!empty($_REQUEST['count']) && !empty($_REQUEST['uid'])){
	unset($arr_input);
	$arr_input['count'] = $_REQUEST['count'];
	$arr_input['pageno'] = $_REQUEST['pageno'];
	$arr_input['uid'] = $_REQUEST['uid'];
	
	$a_details_list = getDetailsList($arr_input);
	
	echo jsonEncodeChina($a_details_list);
}
?>