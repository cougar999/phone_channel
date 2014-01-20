<?php

function smarty_function_admin($params, &$smarty) {
	$ret = $params['ret'];
	$tag = $params['tag'];
	
	switch ($tag){
		case "checkadmingorup":
			$uid = $_SESSION['real_id'];
			$user_type = $_SESSION['user_type'];
			if($user_type == 1){
				if(!empty($ret)){
					return $smarty->assign($ret , checkAdminGroup($uid));
				}else {
					return checkAdminGroup($uid);
				}
			}
		break;
	}
	return null;
}