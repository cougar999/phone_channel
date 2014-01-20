<?php
function smarty_function_params($params, &$smarty) {
	$query_string = $_SERVER['QUERY_STRING'];
	$query_array = array();
	parse_str($query_string, $query_array);
	unset($query_array['tp_tpl_page']);
	foreach ($params as $key => $value) {
		if ($value == null) {
			unset($query_array[$key]);
		} else {
			$query_array[$key] = $value;
		}
	}
	return http_build_query($query_array);
}
?>