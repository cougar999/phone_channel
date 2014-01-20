<?php
function smarty_function_agent($params , &$smarty){
	$ret = $params['ret'];
	$tag = $params['tag'];
	
	switch ($tag){
		case "add":
			if(!empty($ret)){
				return $smarty->assign($ret , addAgent($params));
			}else {
				return addAgent($params);
			}
		break;
		
		case "list":
			if(!empty($ret)){
				return $smarty->assign($ret , getAgentList($params));
			}else {
				return getAgentList($params);
			}
		break;
	}
	return null;
}


?>