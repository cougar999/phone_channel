<?php
require_once(TP_LIB_DIR.'classes/biz/common/util/Pager.php');
require_once(TP_LIB_DIR."classes/biz/comment/Comment.php");

function getCommentByAidList($params){
	
	$page = $params['pageno'] > 0 ? $params['pageno'] : 1;
	$count = $params['count'];
	$aid = intval($params['aid']);
	
	if($aid > 0){
		$o_comment = new Comment();
		unset($arr_input);
		$arr_input['aid'] = $aid;
		$total_cnt = $o_comment->getCommentByAidListCount($arr_input);
		$view_cnt = $count;
		$block_cnt = 10;
		$extra_url = "";
		
		$obj_Pager = new Pager($page, $total_cnt, $tp_tpl_page, $view_cnt, $block_cnt, $extra_url, null);
		
		if($total_cnt > 0){
			$a_comment_list = $o_comment->getCommentByaidList($arr_input,$obj_Pager->getLimitQuery());
		}else {
			$a_comment_list = array();
		}
		return $a_comment_list;
	}else {
		return array('msg'=>'This page has noting comment!');
	}
}

function addCommentByAid($params){
	
	$o_comment = new Comment();
	
	unset($arr_input);
	$arr_input['aid'] = $params['aid'];
	$arr_input['authorid'] = $params['authorid'];
	$arr_input['author_name'] = $params['author_name'];
	$arr_input['content'] = $_POST['content'];
	$arr_input['state'] = 1;											//“1”表示状态为正常的评论
	
	$b_result = $o_comment->addComment($arr_input);
	if($b_result){
		$o_article = new Article();
		unset($arr_input);
		$arr_input['aid'] = $params['aid'];
		
		$b_result = $o_article->addArticleCommentNum($arr_input);
	}
	
	return $b_result;
}

?>