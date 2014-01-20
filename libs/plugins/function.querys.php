<?php
function smarty_function_querys($params, &$smarty) {
	$query_string = $_SERVER['QUERY_STRING'];
	$query_array = array();
	parse_str($query_string, $query_array);
	foreach ($params as $key => $value) {
		$query_array[$key] = $value;
	}
	return http_build_query($query_array);
}
?>