<?php
$cache_dir = dirname(dirname(__FILE__)).'/temp/cache/';
$cache_key = md5($_SERVER['PHP_SELF'].http_build_query($_GET));
$cache_expire = 3600;

if(version_compare(phpversion(), "5.2.0", "<")) {
	if (!function_exists("pathinfo_filename")) {
		function pathinfo_filename($path) {
			$temp = pathinfo($path);
			if($temp['extension'])
			$temp['filename'] = substr($temp['basename'],0 ,strlen($temp['basename'])-strlen($temp['extension'])-1);
			return $temp;
		}
	}
} else {
	if (!function_exists("pathinfo_filename")) {
		function pathinfo_filename($path) {
			return pathinfo($path);
		}
	}
}

// client cache
$headers = apache_request_headers();
if (isset($headers['If-Modified-Since']) && (time() - strtotime($headers['If-Modified-Since'])) <= $cache_expire) {
	header('HTTP/1.1 304 Not Modified');
    header('Connection: close');
    exit();
}

// server cache
foreach (glob($cache_dir.$cache_key.'.*') as $filename) {
    $pathinfo_filename = pathinfo_filename($filename);
	$extension = strtolower($pathinfo_filename["extension"]);
	ap_core_header($extension, filesize($filename));
	echo file_get_contents($filename);
	exit;
}

// no cache
ob_start();
register_shutdown_function('ap_core_shutdown');
// end cache

// cache function
// create cache
function ap_core_cache($buffer) {
	global $cache_dir, $cache_key, $cache_expire;
	if ($buffer == '') { return false; } 
	$extension = '';
	$header = headers_list();
	foreach ($header as $item) {
		if (stripos($item, "Content-type:") !== false) {
			$extension = '.' . strtolower(substr($item, stripos($item, '/') + 1));
			break;
		}
	}
	if ($fp = fopen($cache_dir.$cache_key.$extension, 'w')) {
		fwrite($fp, $buffer);
		fclose($fp);
	}
}

// cache event
function ap_core_shutdown() {
	global $cache_dir, $cache_key, $cache_expire;
	$extension = '';
	$header = headers_list();
	// fix Content-Disposition filename, remove ETag
	foreach ($header as $item) {
		if (stripos($item, 'Content-Disposition:') !== false) {
			$extension = '.' . strtolower(substr(substr($item, 0, strlen($item) - 1), strripos($item, '.') + 1));
			header_remove('Content-Disposition');
			header('Content-Disposition: inline; filename="'.($cache_key.$extension).'"');	
			break;
		}
	}
	header_remove('ETag');
	ob_end_flush();
	$buffer = ob_get_contents();
	ap_core_cache($buffer);
}

// cache header
function ap_core_header($extension, $fiesize = '') {
	global $cache_dir, $cache_key, $cache_expire;
	$time = time(); $expire = $cache_expire;
	switch($extension){
		case "gif": $ctype="image/gif"; break;
		case "png": $ctype="image/png"; break;
		case "jpg":
		case "jpeg": $ctype="image/jpeg"; break;
		case "bmp": $ctype="image/bmp"; break;
		default: $ctype="text/html";
	}
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $time) . ' GMT');
	header('Cache-Control: max-age=' . $expire . ',must-revalidate');
	header('Expires: '.gmdate('D, d M Y H:i:s', $time + $expire).' GMT');
	if ($fiesize != '') {
		header('Content-Length: ' . $fiesize);
	}
	header('Content-type: ' . $ctype);
	if ($extension != 'html') {
		header('Content-Disposition: inline; filename="'.($cache_key.$extension).'"');
	}	
}

?>
