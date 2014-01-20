<?php
function smarty_modifier_sizetext($string) {
	if ($string) {
		if ($string < 1024) {
			$string = $string;
			$string = number_format($string, 0, '.', '') . ' ';
		} else if ($string >= 1024 && $string < 1024 * 1024) {
			$string = $string / 1024;
			$string = number_format($string, 2, '.', '') . 'K';
		} else if ($string >= 1024 * 1024 && $string < 1024 * 1024 * 1024) {
			$string = $string / 1024 / 1024;
			$string = number_format($string, 2, '.', '') . 'M';
		} else if ($string >=1024 * 1024 * 1024) {
			$string = $string / 1024 / 1024 / 1024;
			$string = number_format($string, 2, '.', '') . 'G';
		}
	}
	return $string;
}
?>