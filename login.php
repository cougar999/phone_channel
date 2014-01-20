<?php
$username = $_POST['username'];
$password = $_POST['password'];

if ($username != '' && $password != '') {
	$result = loginMember($username, $password);
	if ($result) {
		$_gt = empty($_GET['gt'])?"/":$_GET['gt'];
		header("location: {$_gt}");
		exit;
	} else {
		$tp_tpl_assign['login'] = "用户和密码不匹配，请重新输入后再试";
	}
}

?>