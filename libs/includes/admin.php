<?php
function checkAdminGroup($uid){
	global $_super_mager;
	//临时管理员用户数组
	$a_admin_group = array(
					array('userid'=>980,'real_name'=>'系统平台周飞'),
					array('userid'=>1009,'real_name'=>'产品部卢训涛'),
					array('userid'=>1255,'real_name'=>'顾浩'),
					);
	$b_result = false;
	foreach ($a_admin_group as $row){
		if($row['userid'] == $uid){
			$b_result = true;
		}
	}
	$b_result = in_array($uid, $_super_mager) ? true : $b_result;
	return $b_result;
}

?>