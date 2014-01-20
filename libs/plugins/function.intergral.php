<?php
function smarty_function_intergral($params , &$smarty){
	$ret = $params['ret'];
	$tag = $params['tag'];
	
	switch ($tag){
		case "info":
			if(!empty($ret)){
				return $smarty->assign($ret , getIntergralInfo($params));
			}else {
				return getIntergralInfo($params);
			}
		break;
		
		case "ranking":
			if(!empty($ret)){
				return $smarty->assign($ret , getIntergralRanking($params));
			}else {
				return getIntergralRanking($params);
			}
		break;
		
		case "details":
			if(!empty($ret)){
				return $smarty->assign($ret , getIntergralDetailsList($params));
			}else {
				return getIntergralDetailsList($params);
			}
		break;
		
		case "getleveldesc":
			if(!empty($ret)){
				return $smarty->assign($ret , getLevelDesc($params));
			}else {
				return getLevelDesc($params);
			}
		break;
		
		case "activity":
			if(!empty($ret)){
				return $smarty->assign($ret , getActivity($params));
			}else {
				return getLevelDesc($params);
			}
		break;
	}
	return null;
}


?>