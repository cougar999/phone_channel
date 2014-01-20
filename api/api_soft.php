<?php
set_time_limit(0);
error_reporting(0);
!defined('_INIT_FROM_INDEX_') && include_once '../config.php';
include_once TP_LIB_DIR.'/feed.php';

$apk_obj   = new ApkParser();
$rt_data   = array('code'=>'','data'=>array());
$act       = trim($_REQUEST['act']);
$_REQUEST['page'] 	  = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <  1) ? 1 : intval($_REQUEST['page']);
$_REQUEST['pagesize'] = (!$_REQUEST['pagesize']?200:$_REQUEST['pagesize']);
$_f_debug  			  = TP_DATA_DIR.'debug.txt';
$tomailer    = array(
	'liuxin@appdear.com',
	'zhoufei@appdear.com',
	'zhengziliang@appdear.com',
	'liuyu@appdear.com',
	'luxuntao@appdear.com'
);
$tomailer 	 = empty($_REQUEST['email'])?$tomailer:explode(',', $_REQUEST['email']);

switch ($act){
	case 'upload':
		$_session_            = json_decode(stripcslashes($_REQUEST['_se_str']),true);
		if (!empty($_FILES)) {
			$_flag_ext = true;
			$tempFile  = $_FILES['Filedata']['tmp_name'];
			$fileParts = pathinfo($_FILES['Filedata']['name']);
			$ext	   = $fileParts['extension'];
			$chn_id    = $_REQUEST['chn_id'];
			$chn_code  = $_REQUEST['chn_code'];
			$pathExt   = array(
				'apk' => array(
					'path' => 'android',
					'os'   => '3'
				)
			);
			try {
				$apk_obj			-> open($tempFile);
				$_app_id 			= $apk_obj->getPackage();
				$_app_name  	    = $apk_obj->getAppName();
				$_app_version_name  = $apk_obj->getVersionName();
				$_app_version_code  = $apk_obj->getVersionCode();
				//调用接口
				$_feed_post = array(
					'appId'		  => $_app_id,
					'channelId'   => $chn_id,
					'channelCode' => $chn_code,
					'osType'	  => $pathExt[$ext]['os']
				);
				$__rt = ap_core_feed('AP_INT_channelValidation',$_feed_post,false,1);
				if(3 == $__rt['resultcode']){
					$des_dir   = '/no_soft/'.$pathExt[$ext]['path'].'/'.date('Ymd');
					$filename  = randName(0).'_chn_'.$chn_id;
					$filePath  = $des_dir.'/'.$filename.'.'.$ext;
					@common_mkdirs(TP_DATA_DIR.$des_dir);
					if(@move_uploaded_file($tempFile,TP_DATA_DIR.$filePath)){
						
					}
					try {
						//无此软件情况
						$mail = new PHPMailer(true);
						$mail->IsSMTP();
						$mail->SMTPAuth   = true;                  // enable SMTP authentication
						$mail->Port       = 25;                    // set the SMTP server port
						$mail->Host       = "mail.appdear.com"; // SMTP server
						$mail->Username   = "smtp_web@appdear.com";     // SMTP server username
						$mail->Password   = "F3K2W4m4";            // SMTP server password
						$mail->From       = "smtp_web@appdear.com";
						$mail->FromName   = "爱皮科技";
						$mail->CharSet="utf-8";
						foreach ($tomailer as $item){
							$mail->AddAddress($item);
						}
						$tmp_url        = "http://n.channel.appdear.com/data{$filePath}";
						$mail->Subject  = "渠道软件上传无此软件信息";
						$mail->Body     = "用户名称：<b>{$_session_['username']}</b>。<BR>用户ID：{$_session_['real_id']}。<BR>时间：".date('Y-m-d H:i:s')."
											<BR><BR>软件下载地址 ：<a href='{$tmp_url}'>{$tmp_url}</a>
										   ";
//						$mail->AddAttachment(TP_DATA_DIR.$filePath);
						
						$mail->IsHTML(true);
						$mail->Send();
					}catch (phpmailerException $e) {
//						echo $e->errorMessage();
					}
					$rt_data['code'] = '003';
					$_flag_ext 		 = false;
				}
			}catch(Exception $e){
				$rt_data['code'] = '002';
				$_flag_ext 		 = false;
			}
			if ($_flag_ext){
				$des_dir   = '/tmp_soft/'.$pathExt[$ext]['path'].'/'.date('Ymd');
				$filename  = randName(0).'_chn_'.$chn_id;
				$filePath  = $des_dir.'/'.$filename.'.'.$ext;
				@common_mkdirs(TP_DATA_DIR.$des_dir);
				//上传
				if(@move_uploaded_file($tempFile,TP_DATA_DIR.$filePath)){
					$rt_data['code']   = '006';
					$rt_data['data']['ds'] = array(
						'appId'		  => $_app_id,
						'versionCode' => $_app_version_code,
						'versionName' => $_app_version_name,
						'url'		  => $filePath,
						'operType'    => $__rt['resultcode'],
						'_id' 		  => $filename,
						'osType'	  => $pathExt[$ext]['os']
					);
					$rt_data['data']['id'] = $filename;
					$rt_data['data']['tg'] = $__rt['softcontent'][0];
				}else $rt_data['code'] = '004';
			}
		}
		break;
	case 'submit':
		$_rt_data 	  = array();
		$_chnId   	  = $_SESSION['real_id'];
		$_sectionCode = $_SESSION['chn_tpl_code'];
		$_chnCode	  = $_SESSION['chn_code'];
		foreach ($_POST['appId'] as $k=>$v){
			$_id 	  = $_POST['_id'][$k];
			//资源文件从临时文件夹移动 
			$_new_url = str_ireplace('/tmp_soft/', '/soft/', $_POST['url'][$k]);
			@common_mkdirs(dirname(TP_DATA_DIR.$_new_url));
			if(rename(TP_DATA_DIR.$_POST['url'][$k], TP_DATA_DIR.$_new_url)){
				$_feed_post = array(
					'appId' 	  => trim($_POST['appId'][$k]),
					'size' 		  => intval($_POST['size'][$k]),
					'versionCode' => trim($_POST['versionCode'][$k]),
					'versionName' => trim($_POST['versionName'][$k]),
					'url' 		  => $_new_url,
					'osType'	  => trim($_POST['osType'][$k]),
					'channelId'   => $_chnId,
					'channelCode' => $_chnCode,
					'sectionCode' => $_sectionCode,
					'operType'    => intval($_POST['operType'][$k])
				);
				$__rt = ap_core_feed('AP_INT_channleImport',array('batchPara'=>json_encode($_feed_post)),false,1);
//file_put_contents($_f_debug, json_encode($__rt)."\n",FILE_APPEND);
				1    != $__rt['resultcode'] && rename(TP_DATA_DIR.$_new_url,TP_DATA_DIR.$_POST['url'][$k]);//回滚资源文件
				$_rt_data[$_id]['code']   = $__rt['resultcode'];
			}else $_rt_data[$_id]['code'] = '002';
		}
		$rt_data['data'] = $_rt_data;
		break;
	case 'getInfo':
		$status     = intval($_REQUEST['status']);
		$_feed_post = array(
			'channelCode' => intval($_SESSION['chn_code']),
			'sectionCode' => intval($_SESSION['chn_tpl_code']),
			'pageIndex'   => $_REQUEST['page'],
			'pageSize'    => $_REQUEST['pagesize'],
			'status' 	  => $status
		);
		$__rt = ap_core_feed('AP_INT_channlePublish',$_feed_post,false,1);
		$rt_data['data'] = $__rt['list'];
		$rt_data['code'] = $__rt['resultcode'];
		break;
	case 'adjustment':
		$_se_chntpl_code = intval($_SESSION['chn_tpl_code']);
		$type 			 = trim($_REQUEST['type']);
		$tmp  			 = array();
		switch ($type){
			case 'px':
				$tmp = explode('|',$_REQUEST['nodeid']);
				foreach ($tmp as $item){
					$_feed_post[] = array(
						'nodeId' => $item
					);
				}
				break;
			case 'sj':
				$_feed_post[] = array('nodeId'=>intval($_REQUEST['nodeid']),status=>1,'contentid'=>intval($_REQUEST['contentid']));
				break;
			case 'xj':
				$_feed_post[] = array('nodeId'=>intval($_REQUEST['nodeid']),status=>0,'contentid'=>intval($_REQUEST['contentid']));
				break;
			case 'del':
				$_feed_post[] = array('nodeId'=>intval($_REQUEST['nodeid']),status=>2,'contentid'=>intval($_REQUEST['contentid']));
				break;
		}
		$_tmp_post       = array(
			'count' => count($_feed_post),
			'items' => $_feed_post,
			'channelCode' => intval($_SESSION['chn_code']),
			'sectionCode' => $_se_chntpl_code
		);
		$__rt 			 = ap_core_feed('AP_INT_channlePublishAdjustment',array('batchPara'=>json_encode($_tmp_post)),false,1);
		$rt_data['code'] = $__rt['resultcode'];
		break;
}
echo json_encode($rt_data);