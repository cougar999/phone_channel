<?php
session_start();
date_default_timezone_set('PRC');
define('_INIT_FROM_INDEX_', true);

require_once("./config.php");
require_once(TP_DATA_DIR."site.php");
require_once(TP_LIB_DIR."common.function.php");
require_once(TP_LIB_DIR."template.php");
require_once(TP_LIB_DIR."feed.php");

require_once(TP_LIB_DIR."article.php");
require_once(TP_LIB_DIR."comment.php");
require_once(TP_LIB_DIR."member.php");
require_once(TP_LIB_DIR."statistic.php");
require_once(TP_LIB_DIR."note.php");
require_once(TP_LIB_DIR."admin.php");
require_once(TP_LIB_DIR."agent.php");
require_once(TP_LIB_DIR."person.php");
require_once(TP_LIB_DIR."intergral.php");
require_once(TP_LIB_DIR."writeLog.php");


$i = 1;
$site_info = $arr_site[$i];

$tp_tpl_appname = $site_info;
$tp_tpl_layout = isset($tp_tpl_layout) ? $tp_tpl_layout : "layout.html";
$tp_tpl_assign = array('tp' => $tp, 'ap_int' => $ap_int,'st_info'=>$site_info);
$tp_tpl_assign = array('article_category' => $arr_article_category);
$tp_tpl_instance = tp_tpl_engine($tp_tpl_appname);

$tp_tpl_page = isset($tp_tpl_page) ? $tp_tpl_page : ($_GET['tp_tpl_page'] != '' ? $_GET['tp_tpl_page'] : 'index.html');
$tp_tpl_page_group = explode("/", $tp_tpl_page);
$tp_tpl_page_plugin = (strpos($tp_tpl_page_group[0], ".") ? substr($tp_tpl_page_group[0], 0, strpos($tp_tpl_page_group[0], ".")) : $tp_tpl_page_group[0]) . ".php";

//判断使用
$tp_tpl_layout = stripos(@file_get_contents(TP_TPL_DIR.$site_info['template'].'/'.$tp_tpl_page), '</html>') === false ? 'layout_page.html' : $tp_tpl_layout; 

if (file_exists($tp_tpl_page_plugin) && $tp_tpl_page_plugin != 'index.php') { require($tp_tpl_page_plugin); }
$tp_tpl_assign['tp_tpl_page'] = $tp_tpl_page;
$tp_tpl_assign['tp_tpl_page_group'] = $tp_tpl_page_group;
$tp_tpl_assign['tp_tpl_page_plugin'] = $tp_tpl_page_plugin;
$tp_tpl_assign['tp_tpl_layout'] = $tp_tpl_layout;

if(empty($_SESSION['userid']) && $tp_tpl_page != 'login.html'){
	header('Location: /login.html?gt='.urlencode(GetCurlUrl()));
	exit;
}elseif(!empty($_SESSION['userid']) && $tp_tpl_page == 'login.html'){
	header('Location: /index.html');
	exit;
}

if (file_exists(TP_APP_DIR."/{$tp_tpl_page}")) {require_once TP_APP_DIR."/{$tp_tpl_page}";exit;}
if (!file_exists(TP_TPL_DIR."/{$site_info['template']}/{$tp_tpl_page}")) throw new CHttpException(404);

// 获取金币中心管理员权限
$admingoldcache_file = TP_DATA_DIR.'data.txt';
if(file_exists($admingoldcache_file) || ($_SESSION['is_globadmin'] == 1)){
	$cache = file_get_contents($admingoldcache_file);
	$arr_adminuser = unserialize($cache);
	if(array_key_exists($_SESSION['real_id'],$arr_adminuser)){
		$_SESSION['is_globadmin'] = 1;
	}
}

$tp_tpl_assign['user_type'] = $_SESSION['user_type'];
$tp_tpl_assign['userid'] = $_SESSION['userid'];
$tp_tpl_assign['username'] = empty($_SESSION['_username'])?$_SESSION['username']:$_SESSION['_username'];
$tp_tpl_assign['level'] = $_SESSION['level'];

DB::close_all();
//var_dump($_SESSION);exit;
tp_tpl_display($tp_tpl_instance, $tp_tpl_page, $tp_tpl_layout, $tp_tpl_assign, $tp_tpl_page_plugin);
?>