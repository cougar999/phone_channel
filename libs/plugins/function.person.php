<?php
function smarty_function_person($params , &$smarty){
	$tag = $params['tag'];
	$ret = $params['ret'];
	
	switch ($tag){
		case "list":
			if(!empty($tag)){
				return $smarty->assign($ret , getPersonList($params));
			}else {
				return getPersonList($params);
			}
			
		break;
		
		case "add":
			if(!empty($tag)){
				return $smarty->assign($ret , addSalesPerson($params));
			}else {
				return addSalesPerson($params);
			}
			
		break;
		
		case "view":
			if(!empty($tag)){
				return $smarty->assign($ret , viewSalesPerson($params));
			}else {
				return viewSalesPerson($params);
			}
			
		break;
		
		case "modify":
			if(!empty($tag)){
				return $smarty->assign($ret , modifySalesPerson($params));
			}else {
				return modifySalesPerson($params);
			}
		break;
			
	}
	
	return null;
}

?>