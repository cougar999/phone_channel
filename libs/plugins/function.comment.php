<?php

function smarty_function_comment($params, &$smarty) {
	$ret = $params['ret'];
	$tag = $params['tag'];

	switch ($tag){
		case "add":
			
			$b_result = addCommentByAid($params);
			
			if (!empty($ret)) {
				return $smarty->assign($ret , $b_result);
			}else {
				return $b_result;
			}
			
			break;
		case "lists":
			
			$a_comment_list = getCommentByAidList($params);
			
			if (!empty($ret)) {
				return $smarty->assign($ret , $a_comment_list);
			}else {
				return $a_comment_list;
			}
			break;
	}
}