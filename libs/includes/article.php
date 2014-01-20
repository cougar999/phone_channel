<?php
require_once(TP_LIB_DIR.'classes/biz/common/util/Pager.php');
require_once(TP_LIB_DIR."classes/biz/article/Article.php");

function getOtherArticleList($params){
	
	$page = $params['pageno'] > 0 ? $params['pageno'] : 1;
	$count = $params['count'];
	
	$category = intval($params['category']);
	$authorid = intval($params['authorid']);
	$agentid = intval($params['agentid']);
	
	$o_arcitle = new Article();
	$arr_input = array();
	
	if (!empty($agentid)) {
		$arr_input['agent_id'] = $agentid;
	}
	if (!empty($category)) {
		$arr_input['category'] = $category;
		switch ($category){
			case 1:
			case 2:unset($arr_input['agent_id']);break;
		}
	}
	if (!empty($authorid)) {
		$arr_input['authorid'] = $authorid;
	}
	
	$total_cnt = $o_arcitle->getOtherArticleListCount($arr_input);
	$view_cnt = $count;
	$block_cnt = 10;
	$extra_url = "category=".$category;
	
	$obj_Pager = new Pager($page, $total_cnt, $tp_tpl_page, $view_cnt, $block_cnt, $extra_url);
	if($total_cnt > 0){
		$a_article_list = $o_arcitle->getOtherArticleList($arr_input,$obj_Pager->getLimitQuery());
	}else {
		$a_article_list = array();
	}
	$a_result = array("pagehtml"=>$obj_Pager->getPagingHtml(),"info"=>$a_article_list);
	
	return $a_result;
}

function addMyShare($params){
	
	$o_arcitle = new Article();
	unset($arr_input);
	$title = trim(addslashes($_POST['title']));
	$description = trim(addslashes($_POST['description']));
	$content = trim(addslashes($_POST['content']));

	if(!empty($title)&&!empty($description)&&!empty($content)){
		$arr_input['title'] = addslashes($_POST['title']);
		$arr_input['description'] = addslashes($_POST['description']);
		$arr_input['category'] = 3;
		$arr_input['state'] = 1;											//正常状态
		$arr_input['content'] = addslashes($_POST['content']);
		$arr_input['agent_id'] = intval($_SESSION['agentid']);
		$arr_input['authorid'] = intval($_SESSION['userid']);
		 
		$b_result = $o_arcitle->addMyArticle($arr_input);
	}else {
		$b_result = false;
	}
	
	return $b_result;
}

function addArticle($params){
	
	$o_arcitle = new Article();
	unset($arr_input);

	if(!empty($_POST['title'])&&!empty($_POST['description'])&&!empty($_POST['content'])){
		$arr_input['title'] = addslashes($_POST['title']);
		$arr_input['description'] = addslashes($_POST['description']);
		$arr_input['category'] = intval($_POST['category']);
		$arr_input['state'] = 1;											//正常状态
		$arr_input['content'] = addslashes($_POST['content']);
		$arr_input['authorid'] = intval($_SESSION['userid']);
		$arr_input['agent_id'] = intval($_SESSION['agentid']);
		$b_result = $o_arcitle->addMyArticle($arr_input);
	}else {
		$b_result = false;
	}
	
	return $b_result;
}

function viewArticle($params){
	
	$o_arcitle = new Article();
	unset($arr_input);
	$arr_input['aid'] = intval($params['aid']);
	$arr_article_view = $o_arcitle->viewArticle($arr_input);
	if(empty($arr_article_view)){
		$arr_article_view['content'] = 'nothing';
	}
	
	return $arr_article_view;
}

function modifyArticle($params){
	
	$o_arcitle = new Article();
	unset($arr_input);
			
	$arr_input['aid'] = intval($params['aid']);
	$arr_input['title'] = addslashes($_POST['title']);
	$arr_input['description'] = addslashes($_POST['description']);
	$arr_input['category'] = intval($_POST['category']);
	$arr_input['state'] = 1;											//正常状态
	$arr_input['content'] = addslashes($_POST['content']);
	
	$b_result = $o_arcitle->modifyAdminArticle($arr_input);
	
	return $b_result;
}

function modifyMyArticle($params){
	
	$o_arcitle = new Article();
	unset($arr_input);

	if(!empty($params['authorid']) && ($params['authorid'] == $_SESSION['userid'])){
		$arr_input['aid'] = intval($params['aid']);
		$arr_input['authorid'] = $_SESSION['userid'];
		$arr_input['title'] = addslashes($_POST['title']);
		$arr_input['description'] = addslashes($_POST['description']);
		$arr_input['category'] = 3;											//店长分享
		$arr_input['state'] = 1;											//正常状态
		$arr_input['content'] = addslashes($_POST['content']);
		
		$b_result = $o_arcitle->modifyMyArticle($arr_input);
	}else {
		$b_result = false;
	}
	
	return $b_result;
}

function addCommentNum($params){
	$aid = intval($params['aid']);
	$o_arcitle = new Article();
	unset($arr_input);
	if($aid > 0){
		$arr_input['aid'] = intval($params['aid']);
		$b_result = $o_arcitle->addArticleCommentNum($arr_input);
	}else {
		$b_result = false;
	}
	
	return $b_result;
}

function addReadNum($params){
	$aid = intval($params['aid']);
	$o_arcitle = new Article();
	unset($arr_input);
	if($aid > 0){
		$arr_input['aid'] = intval($params['aid']);
		$b_result = $o_arcitle->addArticleReadNum($arr_input);
	}else {
		$b_result = false;
	}
	
	return $b_result;
}

function addSharingNum($params){
	$aid = intval($params['aid']);
	$o_arcitle = new Article();
	unset($arr_input);
	if($aid > 0){
		$arr_input['aid'] = intval($params['aid']);
		$b_result = $o_arcitle->addArticleSharingNum($arr_input);
	}else {
		$b_result = false;
	}
	
	return $b_result;
}

function deleteArticle($params){
	$aid = intval($params['aid']);
	$o_arcitle = new Article();
	unset($arr_input);
	if($aid > 0){
		$arr_input['aid'] = intval($params['aid']);
		$b_result = $o_arcitle->deleteMyArticle($arr_input);
	}else {
		$b_result = false;
	}
	
	return $b_result;
}
?>