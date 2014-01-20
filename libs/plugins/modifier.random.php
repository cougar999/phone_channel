<?php
function smarty_modifier_random($arr,$len,$curid=0) {
	$len  = intval($len);
	$curid= intval($curid);
	$_arr = array();
	while (count($_arr) <= $len-1){
		$index = rand(0,count($arr)-1);
		$arr[$index] != $curid && $_arr[]= $arr[$index];
		unset($arr[$index]);
		if (empty($arr)) break;
		$arr = array_values($arr);
	}
	return $_arr;
}
?>