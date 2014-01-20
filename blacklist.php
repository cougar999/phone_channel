<?php
session_start();
date_default_timezone_set('PRC');

require_once("./config.php");
require_once(TP_DATA_DIR."site.php");
require_once(TP_LIB_DIR."common.function.php");
require_once(TP_LIB_DIR."template.php");
require_once(TP_LIB_DIR."feed.php");

// 文件名称
require_once(TP_LIB_DIR."statistic.php");




if ($tp_tpl_page == 'blacklist/index.html') {
	$data = 1;
	$tp_tpl_assign['blacklist'] = $data;
}elseif ($tp_tpl_page == '/'){
	// 读取数据文件内容输入
	
}