<?php
require_once(TP_LIB_DIR."classes/biz/announcement/Announcement.php");
function smarty_function_announcement($params,&$smarty){
		$o_communication = new Announcement();
		
		if(!empty($_SESSION['userid'])){
			$arr=$o_communication->cement($_SESSION['userid']);
		}
		if (!empty($params['cnid'])){
			$o_communication->announcement_relation($params['cnid']);
		}
		return  $smarty->assign("arrdb",$arr); 
}
?>