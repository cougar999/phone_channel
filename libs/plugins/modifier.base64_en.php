<?php
/**
 * Smarty plugin
 * 自定义函数，对数据进行编码，如php之base64_encode
 */
function smarty_modifier_base64_en($string)
{
    $string = base64_encode($string);
    
    return $string;
    
}
?>