<?php
function smarty_modifier_datestyle($string,$type=1)
{
	if (empty($string)){
		return date('Y-m-d',strtotime("-{$type} days"));
	}
	return str_ireplace('00:00', '', getDateStyle($string));
}
?>