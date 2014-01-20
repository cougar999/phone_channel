<?php
function get_real_ip(){
	$unknown = 'unknown';
	if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if (false !== strpos($ip, ','))
		 $ip = reset(explode(',', $ip));
	return $ip;
}
function  setupSize( $fileSize ) {    
     $size   =   sprintf ( " %u " ,   $fileSize );
     $sizename   =   array ( "  Bytes " ,   "  KB " ,   "  MB " ,   "  GB " ,   "  TB " ,   "  PB " ,   "  EB " ,   "  ZB " ,   "  YB " );    
     return   round ( $size / pow ( 1024 ,  ( $i   =   floor ( log ( $size ,   1024 )))) ,  3 )  .   $sizename [ $i ];
} 
function randName($type = 0) {
	return !!$type ? md5(uniqid(rand(),true).microtime(true).rand(100000, 999999 )) : substr(md5(uniqid(rand(),true).microtime(true).rand(100000, 999999 )),8,16);
}
function common_mkdirs($dir) {
	if (! is_dir ( $dir )) {
		if (! common_mkdirs ( dirname ( $dir ) )) {
			return false;
		}
		if (! mkdir ( $dir, 0777 )) {
			return false;
		}else chmod($dir,0777);
	}
	return true;
}
/**
 * 将日期格式根据以下规律修改为不同显示样式
 * 小于1分钟 则显示多少秒前
 * 小于1小时，显示多少分钟前
 * 一天内，显示多少小时前
 * 3天内，显示前天22:23或昨天:12:23。
 * 超过3天，则显示完整日期。
 * @static
 * @param  $sorce_date 数据源日期 unix时间戳
 * @return void
 */
function getDateStyle($sorce_date){
	if (empty($sorce_date)) return '';
	$sorce_date = is_numeric($sorce_date)?$sorce_date:strtotime($sorce_date);
    $nowTime 	= time();  //获取今天时间戳

    //echo '数据源时间戳：'.$sorce_date . ' = '. date('Y-m-d H:i:s',$sorce_date);
    //echo "\n 当前时间戳:". date('Y-m-d H:i:s',$nowTime)."\n";

    $timeHtml = ''; //返回文字格式
    $temp_time = 0;
    switch($sorce_date){

        //一分钟
        case ($sorce_date+60)>=$nowTime:
            $temp_time = $nowTime-$sorce_date;
            $timeHtml = $temp_time ."秒前";
            break;

        //小时
        case ($sorce_date+3600)>=$nowTime:
            $temp_time = date('i',$nowTime-$sorce_date);
            $timeHtml = $temp_time ."分钟前";
            break;

        //天
        case ($sorce_date+3600*24)>=$nowTime:
            $temp_time = date('H',$nowTime)-date('H',$sorce_date);
            $timeHtml = $temp_time .'小时前';
            break;

        //昨天
        case ($sorce_date+3600*24*2)>=$nowTime:
            $temp_time = date('H:i',$sorce_date);
            $timeHtml = '昨天'.$temp_time ;
            break;

        //前天
        case ($sorce_date+3600*24*3)>=$nowTime:
            $temp_time  = date('H:i',$sorce_date);
            $timeHtml = '前天'.$temp_time ;
            break;

        //3天前
        case ($sorce_date+3600*24*4)>=$nowTime:
            $timeHtml = '3天前';
            break;

        default:
            $timeHtml = date('Y-m-d',$sorce_date);
            break;

    }
    return $timeHtml;

}
/**
 * @package     BugFree
 * @version     $Id: FunctionsMain.inc.php,v 1.32 2005/09/24 11:38:37 wwccss Exp $
 *
 *
 * Sort an two-dimension array by some level two items use array_multisort() function.
 *
 * sortArr($Array,"Key1","SORT_ASC","SORT_RETULAR","Key2"……)
 * @author                      Chunsheng Wang <wwccss@263.net>
 * @param  array   $ArrayData   the array to sort.
 * @param  string  $KeyName1    the first item to sort by.
 * @param  string  $SortOrder1  the order to sort by("SORT_ASC"|"SORT_DESC")
 * @param  string  $SortType1   the sort type("SORT_REGULAR"|"SORT_NUMERIC"|"SORT_STRING")
 * @return array                sorted array.
 */
function sortArr($ArrayData,$KeyName1,$SortOrder1 = "SORT_ASC",$SortType1 = "SORT_REGULAR")
{
    if(!is_array($ArrayData)) return $ArrayData;
    
    // Get args number.
    $ArgCount = func_num_args();
    // Get keys to sort by and put them to SortRule array.
    for($I = 1;$I < $ArgCount;$I ++)
    {
        $Arg = func_get_arg($I);
        if(!eregi("SORT",$Arg))
        {
            $KeyNameList[] = $Arg;
            $SortRule[]    = '$'.$Arg;
        }
        else $SortRule[]   = $Arg;
    }
    // Get the values according to the keys and put them to array.
    foreach($ArrayData AS $Key => $Info)
    {
        foreach($KeyNameList AS $KeyName) ${$KeyName}[$Key] = strtolower($Info[$KeyName]);
    }
    
    // Create the eval string and eval it.
    $EvalString = 'array_multisort('.join(",",$SortRule).',$ArrayData);';
    eval ($EvalString);
    return $ArrayData;
}
function GetCurlUrl() {
	if (! empty ( $_SERVER ['REQUEST_URI'] )) {
		$scriptName = $_SERVER ['REQUEST_URI'];
		$nowurl = $scriptName;
	} else {
		$scriptName = $_SERVER ['PHP_SELF'];
		if (empty ( $_SERVER ['QUERY_STRING'] )) {
			$nowurl = $scriptName;
		} else {
			$nowurl = $scriptName . "?" . $_SERVER ['QUERY_STRING'];
		}
	}
	return "http://" . $_SERVER ['HTTP_HOST'] . $nowurl;
}
function getUrlRoot($url){
	#添加头部和尾巴
	$url = $url . "/";
	#判断域名
	preg_match("/((\w*):\/\/)?\w*\.?([\w|-]*\.(com.cn|net.cn|gov.cn|org.cn|com|net|cn|org|asia|tel|mobi|me|tv|biz|cc|name|info))\//", $url, $ohurl);
	#判断IP
	if($ohurl[3] == ''){
        preg_match("/((\d+\.){3}\d+)\//", $url, $ohip);
        return $ohip[1];
	}
	return $ohurl[3];
}
function setFromUrl($default){
	return !empty($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=GetCurlUrl() && getUrlRoot($_SERVER['HTTP_REFERER'])==getUrlRoot(GetCurlUrl()) ? $_SERVER['HTTP_REFERER'] : $default;
}