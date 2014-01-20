<?php
class CommonFunc{
	public static function autoConverEncode($strs,$encode='utf-8'){
		if (is_array ( $strs )) {
			$result = array ();
			foreach ( $strs as $key => $value ) {
				$result [$key] = self::autoConverEncode ( $value,$encode );
			}
		} else {
			$charset = mb_detect_encoding($strs, array('ASCII','UTF-8','GB2312','GBK','BIG5','ISO-8859-1'));
			$result  = strcasecmp($charset,$encode) != 0 ? mb_convert_encoding($strs,$encode,$charset) : $strs;
		}
		return $result;
	}
}
?>