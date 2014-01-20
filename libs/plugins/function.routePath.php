<?php
function smarty_function_routePath($params, &$smarty)
{
	global $url;
	$type = $params['type'];
	unset($params['type']);
	return $url->createUrl($type,$params);
}
