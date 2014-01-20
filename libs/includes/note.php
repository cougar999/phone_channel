<?php
require_once(TP_LIB_DIR.'classes/biz/common/util/Pager.php');
require_once(TP_LIB_DIR."classes/biz/notice/Notice.php");

function getNoticeList($params){
	$page = $params['pageno'] > 0 ? $params['pageno'] : 1;
	$count = $params['count'];
	
	$o_notice = new Notice();
	unset($arr_input);
	
	$total_cnt = $o_notice->getNoticeListCount($arr_input);
	if ($params['act'] == 'cut') return array('cut'=>$total_cnt);
	$view_cnt = $count;
	$block_cnt = 10;
	$extra_url = "";
	
	$obj_Pager = new Pager($page, $total_cnt, $tp_tpl_page, $view_cnt, $block_cnt, $extra_url, null);
	if($total_cnt > 0){
		$a_notie_list = $o_notice->getNoticeList($arr_input,$obj_Pager->getLimitQuery());
	}else {
		$a_notie_list = array();
	}
	
	$a_result = array("pagehtml"=>$obj_Pager->getPagingHtml(),"info"=>$a_notie_list);
	
	return $a_result;
}

function addNotice($params){
	if(!empty($_SESSION['userid']) && !empty($_POST['content'])){
		$o_notice = new Notice();
		
		unset($arr_input);
		$arr_input['uid'] = intval($_SESSION['userid']);
		$arr_input['content'] = addslashes($_POST['content']);
		$arr_input['type'] = 1;							//1,表示通告类型为所有人
		
		$b_result = $o_notice->addNotice($arr_input);
	}
	
	return $b_result;
}

function deleteNotice($params){
	$o_notice = new Notice();
	unset($arr_input);
	
	$arr_input['cnid'] = $params['cnid'];
	
	$b_result = $o_notice->deleteNotice($arr_input);
	
	return $b_result;
}
?>