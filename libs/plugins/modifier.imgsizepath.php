<?php
function smarty_modifier_imgsizepath($string, $size="48x48" ,$type=1)
{
	if (stripos($string, '_sf_') === false){
		$ext 	 = ".".($type==1?"png":end(explode('.', $string)));
		$string .= strtolower("_sf_".$size.$ext);
	}
	return $string;
}
?>