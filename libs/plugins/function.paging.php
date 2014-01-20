<?php
function smarty_function_paging($params, &$smarty)
{
	$ret = $params['ret'];
	$page = $params['page'];
	
	$no = $params['no'] ? $params['no'] : 1;
	$count = $page['count'] ? $page['count'] : ($page['pagenum'] ? $page['pagenum'] : 10);
	$pages = (int) ($page['totalcount'] / $count) + ($page['totalcount'] % $count ? 1 : 0);
	$page['first'] = 1;
	$page['prev'] = $no - 1;
	$page['next'] = $no + 1;
	$page['last'] = $pages;
	$page['no'] = $no;
	
	if ($no < 5) {
		for ($i = 1; $i < 11; $i++) {
			if ($i <= $pages) {
				$page['size'][] = $i;
			}
		}	
	} elseif ($no > ($pages - 5)) {
		for ($i = $pages - 9; $i <= $pages; $i++) {
			if ($i > 0) {
				$page['size'][] = $i;
			}
		}	
	} else {
		for ($i = $no - 4; $i < $no + 6; $i++) {
			$page['size'][] = $i;
		}	
	}
		
	if ($page['prev'] <  $page['first']) { $page['prev'] = -1; }
	if ($page['next'] >  $page['last']) { $page['next'] = -1; }
	if ($page['first'] == $no) { $page['first'] = -1; }
	if ($page['last'] == $no || $page['last'] == 0) { $page['last'] = -1; }
	
	$smarty->assign($ret, $page);
}
