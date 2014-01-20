<?php
function smarty_modifier_sizebook($string) {
	if ($string) {
		if ($string < 10000) {
			$string = $string;
			$string = number_format($string, 0, '.', '') . ' ';
		} else if ($string >= 10000) {
			$string = $string / 10000;
			$string = number_format($string, 1, '.', '') . '万';
		} 
	}
	return $string;
}
?>