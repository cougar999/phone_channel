<?php
/**
* 使用google api生成二维码
* http://n.appdear.com/api/common/charQr.php?u=cc&s=70x70
*/

// cht参数：qr为二维码
$data['cht'] = 'qr';
// chs参数：生成二维码的尺寸，width*height
$data['chs'] = !!empty($_REQUEST['s'])?'100x100':$_REQUEST['s'];
// chl参数：生成二维码包含的信息，不能混合数据类型（这个不影响PHP），必须经过urlencode编码，大小超过2kb请使用POST提交
$data['chl'] = $_REQUEST['u'];

// choe参数：可选参数，默认UTF-8
// chld参数：可选参数，纠错等级，L-(默认)可以识别已损失7%的数据；M-可以识别已损失15%的数据；Q-可以识别已损失25%的数据；H-可以识别已损失30%的数据。margin 是指生成的二维码离边框的距离。
$data['chld'] = !!empty($_REQUEST['l'])?'L|0':$_REQUEST['l']; // 这里margin=2
$query = http_build_query($data);

// 输出二维码图片
echo file_get_contents("http://chart.apis.google.com/chart?".$query);
?>