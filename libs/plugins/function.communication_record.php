<?php
require_once(TP_LIB_DIR."classes/biz/communication/Communication.php");

function smarty_function_communication_record($params,&$smarty){
	$nid = $params['nid'];
	$o_communication = new Communication();
	$date=$o_communication->getRecord($nid);
	return  $smarty->assign($params['ret'], $date); 
	
}
