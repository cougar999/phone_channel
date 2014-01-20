<?php
$_u = trim($_REQUEST['u']);
$_t = intval($_REQUEST['t']);
if ($_t == 0){
	$rounder = new RoundedCorner($_u, 15);  
	$rounder->round_it();
}else{
	$img_file = $_u;
	$corner = TP_APP_DIR.'/api/common/rounded_corner.png';
	$dst_img = '';
	$resizeimage = new ResizeImage($img_file, $dst_img, $corner, "0", "0", "1", "15", "0");
}