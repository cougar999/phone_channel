<?php
function mkdirs($path, $mode = 0777){ 
	$dirs = explode('/',$path); 
	$pos = strrpos($path, "."); 
	if ($pos === false) { 
		$subamount=0; 
	} 
	else { 
		$subamount=1; 
	} 
	
	for ($c=0;$c < count($dirs) - $subamount; $c++) { 
		$thispath=""; 
		for ($cc=0; $cc <= $c; $cc++) { 
			$thispath.=$dirs[$cc].'/'; 
		} 
		if (!file_exists($thispath)) { 
			mkdir($thispath,$mode); 
		} 
	} 
}
?>