<?php
session_start();

require_once("../config.php");
require_once("../libs/includes/template.php");
require_once("../libs/includes/feed.php");
require_once("../libs/includes/article.php");
require_once("../libs/includes/comment.php");
require_once("../libs/includes/member.php");
require_once("../libs/includes/statistic.php");
require_once(TP_LIB_DIR."classes/query/communication/CommunicationQuery.php");
require_once(TP_LIB_DIR."classes/biz/communication/Communication.php");

date_default_timezone_set('PRC');

$uid = $_SESSION['userid'];
$name = $_SESSION['username'];
$nid = $_POST['nid'];
$content = $_POST['content'];

$o_communication = new Communication();
if ($uid && $name && $nid && $content) {
	$result = $o_communication->createRecord(array('nid'=>$nid, 'uid'=>$uid, 'name'=>$name, 'content'=>$content, 'create_date'=>time(), 'status'=>1));

	if ($result) {
		
		$date=$o_communication->getRecord($nid);
		foreach($date as $key => $value) {
			echo '<li><div class="msgCnt"><div><strong class="pleft">' . $value['name'] . '&nbsp;说:</strong><span class="pright">'.date('Y-m-d H:i', $value['create_date']).'</span></div><div class="clear"></div><div class="content">'.$value['content'].'</div></div></li>';
		}
	}
}


?>