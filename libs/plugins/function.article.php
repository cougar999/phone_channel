<?php

function smarty_function_article($params, &$smarty) {
	$ret = $params['ret'];
	$tag = $params['tag'];
	
	switch ($tag){
		//列表
		case "lists":
			$a_article_list = getOtherArticleList($params);
			
			if (!empty($ret)) {
				return $smarty->assign($ret , $a_article_list);
			}else {
				return $a_article_list;
			}
			break;
			
		//添加店长分享
		
		case "addmyshare":

			$b_result = addMyShare($params);
			
			if (!empty($ret)) {
				return $smarty->assign($ret , $b_result);
			}else {
				return $b_result;
			}
			
			break;
			
		//管理添加文章
		
		case "adminadd":
			
			$b_result = addArticle($params);
			
			if (!empty($ret)) {
				return $smarty->assign($ret , $b_result);
			}else {
				return $b_result;
			}
			
			break;
			
		//查询文章
		case "view":
			
			$arr_article_view = viewArticle($params);
			
			if (!empty($ret)) {
				return $smarty->assign($ret , $arr_article_view);
			}else {
				return $arr_article_view;
			}
			
			break;
		
		//修改我的分享
		case "modfiymyshare":
				
			$b_result = modifyMyArticle($params);
			
			if (!empty($ret)) {
				return $smarty->assign($ret , $b_result);
			}else {
				return $b_result;
			}
			
			break;
			
		//管理修改文章
		case "adminmodfiy":
				
			$b_result = modifyArticle($params);
			
			if (!empty($ret)) {
				return $smarty->assign($ret , $b_result);
			}else {
				return $b_result;
			}
			
			break;
		
		//删除我的分享
		case "deletemyshare":
			
			unset($arr_input);
			$arr_input['aid'] = intval($params['aid']);
			
			$b_result = $o_arcitle->deleteMyArticle($arr_input);
			
			if (!empty($ret)) {
				return $smarty->assign($ret , $b_result);
			}else {
				return $b_result;
			}
			
			break;
		
		//增加文章阅读统计
		case "readnum":
			
			addReadNum($params);
			
			break;
	}
	
	return null;
}
?>