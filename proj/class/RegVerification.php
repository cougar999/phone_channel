<?php
class RegVerification{
    public static function isPhone($str){
        return preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$str);
    }
    public static function isEmail($str){
        return preg_match("/\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$str);
    }
    public static function isQQ($str){
        return preg_match('/^[1-9]{1}[\d]{4,10}$/',$str);
    }
    public static function isTel($str){
    	// 验证电话号码匹配五种形式
		// 86-027-12345678-1
		// 0755-88848773
		// 0755-88860888-0129
		// 86-027-12345678
		// 88848773
        $pattern = "/(^(86)\-(\d{3,4})\-(\d{7,8})\-(\d{1,4})$)"
				."|(^(\d{3,4})\-(\d{7,8})$)"
				."|(^(\d{3,4})\-(\d{7,8})\-(\d{1,4})$)"
				."|(^(86)\-(\d{3,4})\-(\d{7,8})$)"
				."|(^(\d{7,8})$)/";
		return preg_match($pattern, $str);
    }
}
?>