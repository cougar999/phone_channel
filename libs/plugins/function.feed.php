<?php
function smarty_function_feed($params, &$smarty)
{
	$int = $params['int'];
	$ret = $params['ret'];
	if (!empty($ret)) {
		$smarty->assign($ret, ap_core_feed($int, $params, empty($params[debug]) ? false : $params[debug]));
	} else {
		return ap_core_feed($int, $params, empty($params[debug]) ? false : $params[debug]);
	}
	return null;
}
