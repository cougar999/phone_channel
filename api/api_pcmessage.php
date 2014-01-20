<?php
error_reporting(0);
require_once('../config.php');
require_once(TP_LIB_DIR.'classes/biz/message/PCMessage.php');
require_once(TP_LIB_DIR.'classes/biz/common/util/Pager.php');

$obj_pcmessage = new PCMessage();

$type = $_REQUEST['type'];

unset($arr_input);
$arr_input['current_time'] = date("y-m-d H:i:s");
switch ($type){
	case "checkmesshtml":
		$mess_html = urldecode($_REQUEST['mess_html']);
		unset($arr_input);
		echo $arr_input['mess_html'] = $mess_html;
		if($obj_pcmessage->checkMessHtml($arr_input)){
			$arr_output['result'] = 'failed';
		}else{
			$arr_output['result'] = 'success';
		}
	case "validlists":
		$total_count = $obj_pcmessage->getPCMessageValidListCount($arr_input);

		$pageno = intval($_REQUEST['pageno']);
		$count = intval($_REQUEST['count']);
		$obj_pager = new Pager($pageno,$total_count,'',$count);
		
		if($total_count > 0){
			$arr_list = $obj_pcmessage->getPCMessageValidList($arr_input,$obj_pager->getLimitQuery());
			for ($i = 0; $i < count($arr_list);$i++){
				$str_hash_start_time = $arr_list[$i]['start_time'];
				$str_hash_end_time = $arr_list[$i]['end_time'];
				switch ($arr_list[$i]['valid_type']){
					case 2:$arr_list[$i]['etime'] = '永久有效';break;
					case 1:
						switch ($arr_list[$i]['valid_class']){
							case 1:$arr_list[$i]['etime'] = '当日有效';break;
							case 2:$arr_list[$i]['etime'] = '本周有效';break;
							case 3:$arr_list[$i]['etime'] = '本月有效';break;
						}
						break;
				}
			}
		}else{
			$arr_list = array();
		}
		$arr_output['totalcount'] = $total_count;
		$arr_output['pageno'] = $pageno;
		$arr_output['count'] = $count;
		$arr_output['items'] = $arr_list;
		break;
	case "overlists":
		$total_count = $obj_pcmessage->getPCMessageOverListCount($arr_input);

		$pageno = intval($_REQUEST['pageno']);
		$count = intval($_REQUEST['count']);
		$obj_pager = new Pager($pageno,$total_count,'',$count);
		
		if($total_count > 0){
			$arr_list = $obj_pcmessage->getPCMessageOverList($arr_input,$obj_pager->getLimitQuery());
			for ($i = 0; $i < count($arr_list);$i++){
				$str_hash_start_time = $arr_list[$i]['start_time'];
				$str_hash_end_time = $arr_list[$i]['end_time'];
				switch ($arr_list[$i]['valid_type']){
					case 2:$arr_list[$i]['etime'] = '永久有效';break;
					case 1:
						switch ($arr_list[$i]['valid_class']){
							case 1:$arr_list[$i]['etime'] = '当日有效';break;
							case 2:$arr_list[$i]['etime'] = '本周有效';break;
							case 3:$arr_list[$i]['etime'] = '本月有效';break;
						}
						break;
				}
			}
		}else{
			$arr_list = array();
		}
		$arr_output['totalcount'] = $total_count;
		$arr_output['pageno'] = $pageno;
		$arr_output['count'] = $count;
		$arr_output['items'] = $arr_list;
		break;
	case "deletemsg":
		$id = intval($_REQUEST['id']);
		unset($arr_input);
		$arr_input['id'] = $id;
		$result = $obj_pcmessage->delPCMessage($arr_input);
		if($result){
			$arr_output['result'] = 'success';
		}else{
			$arr_output['result'] = 'failed';
		}
		break;
}
echo json_encode($arr_output);
?>