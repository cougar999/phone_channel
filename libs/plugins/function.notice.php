<?php
function smarty_function_notice($params ,&$smarty){
	$ret = $params['ret'];
	$tag = $params['tag'];
	
	switch ($tag){
		case "list":
		
			if(!empty($ret)){
				return $smarty->assign($ret , getNoticeList($params));
			}else{
				return getNoticeList($params);
			}
		
		break;
		
		//管理添加公告通知
		
		case "adminadd":
			
			$b_result = addNotice($params);
			
			if (!empty($ret)) {
				return $smarty->assign($ret , $b_result);
			}else {
				return $b_result;
			}
			
			break;
			
		//管理删除公告通知
		case "admindelete":
				
			$b_result = deleteNotice($params);
			
			if (!empty($ret)) {
				return $smarty->assign($ret , $b_result);
			}else {
				return $b_result;
			}
			
			break;
	}
	
	return null;
}

?>