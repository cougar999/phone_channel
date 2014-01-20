<?php
function smarty_function_menu($params, &$smarty)
{
	$index = $params['index'];
	$code = $params['code'];
	$items = $params['items'] ? $params['items'] : array();
	$ret = $params['ret'];
	$key = $params['key'];
	$menu = array();
	foreach ($items as $item) {
		$menu[$item[$index]] = $item;
	}
	if (!empty($ret)) {
		if (!empty($key)) {
			return $menu[$code][$key];
		} else {
			$smarty->assign($ret, $menu[$code]);
		}
	} else {
		return $menu;
	}
	return null;
}
