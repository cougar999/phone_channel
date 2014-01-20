<?php
include '../config.php';
include TP_LIB_DIR.'/member.php';
$array = getMemberOnlineList(10);
//$result = json_encode($array);
session_start();

			$arr_input['real_id']= $_SESSION['real_id'];
			$arr_input['user_type'] = $_SESSION['user_type'];
			rememberLogin($arr_input);
			
print_r(Array2Json($array));

// 将数组转换成Json格式，中文需要进行URL编码处理
function Array2Json($array) {
    arrayRecursive($array, 'urlencode', true);
    $json = json_encode($array);
    $json = urldecode($json);
    // ext需要不带引号的bool类型
    $json = str_replace("\"false\"","false",$json);
    $json = str_replace("\"true\"","true",$json);
    return $json;
}

function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = $function($value);
        }

        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}
?>
