<?php
// 文件名称
$cache_file = TP_DATA_DIR.'data.txt';

if ($tp_tpl_page == 'admin/goldcenter/adminuser_proc.php') {
	if(isSup($_SESSION['real_id'],$_super_mager)){//判断权限
		if(file_exists($cache_file)){
			$cache = file_get_contents($cache_file);
			$data = unserialize($cache);
		}
		$id = intval($_POST['real_id']);
		$proc_type = addslashes($_POST['proc_type']);
		switch($proc_type){
			case "add":
				$name = addslashes($_POST['real_name']);
				$data[$id] = $name;
			break;
			case "del":
				unset($data[$id]);
			break;
		}
		//print_r($data);exit;
		// 创建数据文件写入信息
		$cache_handle = fopen($cache_file,'w+');
		$result = fwrite($cache_handle,serialize($data));
		fclose($cache_handle);
	}
	header('location: /admin/goldcenter/adminuser.html');exit;
	
}elseif ($tp_tpl_page == 'admin/goldcenter/adminuser.html'){
	// 读取数据文件内容输入
	if(file_exists($cache_file)){
		$cache = file_get_contents($cache_file);
		$data = unserialize($cache);
	}
	$tp_tpl_assign['adminuser_list'] = $data;
}