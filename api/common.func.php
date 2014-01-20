<?php
function autoConverEncode($strs,$encode='utf-8'){
	if (is_array ( $strs )) {
		$result = array ();
		foreach ( $strs as $key => $value ) {
			$result [$key] = autoConverEncode ( $value,$encode );
		}
	} else {
		//echo $strs;exit;
		$charset = mb_detect_encoding($strs, array('ASCII','UTF-8','GB2312','GBK','BIG5','ISO-8859-1'));
		$result  = strcasecmp($charset,$encode) != 0 ? mb_convert_encoding($strs,$encode,$charset) : $strs;
	}
	return $result;
}

function urlArr($strs){
	if (is_array ( $strs )) {
		foreach ( $strs as $key => $value ) {
			$result [$key] = urlArr ( $value );
		}
	} else {
		$result  = rawurlencode(str_replace("\"","@a",$strs));
	}
	return $result;
}

function jsonEncodeChina($data){
	$data = urlArr($data);
	echo autoConverEncode(rawurldecode(json_encode($data)),'UTF-8');
}