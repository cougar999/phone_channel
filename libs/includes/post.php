<?php
require_once('HttpClient.class.php');

function ap_core_post($int, $data=array(), $debug=false) {
	$postlog = 'log/post.log';
	$errlog = 'log/err.log';
	if (is_array($int)) {
		$feed = constant($params['int']);
		$data = $params;
	} elseif (is_string($int)) {
		$feed = constant($int);
		$data = $data;
	}
	

	$req = parse_url($feed);
	$client = new HttpClient($req['host'], $req['port'] ? $req['port'] : 80);
	$client->setDebug($debug);
	
	$headers = $_COOKIE['headers'];
	$headers = json_decode($headers, true);
	if (!is_null($headers) ) {
		$headers = $client->headers = array(
			'Version' => $headers['FirstPhoneInfo']['Version'],
			'User-Agent' => $headers['FirstPhoneInfo']['User-Agent'],
			'Platform' => $headers['FirstPhoneInfo']['Platform'],
			'Deviceid' => $headers['FirstPhoneInfo']['Deviceid'],
			'Shopid' => $headers['FirstPhoneInfo']['Shopid'],
			'Authid' => $headers['FirstPhoneInfo']['Authid'],
			'Termid' => $headers['FirstPhoneInfo']['hansetid'],
			'Phonetype' => $headers['FirstPhoneInfo']['phonetype'],
			'Phoneno' => $headers['FirstPhoneInfo']['phoneno'],
			'Termcode' => $headers['FirstPhoneInfo']['imei'] != '' ? $headers['FirstPhoneInfo']['imei'] : 'test-term-code',
			'Trackid' => $headers['FirstPhoneInfo']['Trackid'],
			'Salesid' => $headers['FirstPhoneInfo']['Salesid'],
			'Timestamp' => time()
		);
	} else {
		$headers = $client->headers = array(
			'Version' => '1',
			'User-Agent' => 'Aipi-pc-retail',
			'Platform' => '1',
			'Deviceid' => 'test-device-id',
			'Shopid' => 'test-shop-id',
			'Authid' => 'test-auth-id',
			'Termid' => '-1',
			'Termcode' => 'test-term-code',
			'Trackid' => 'test-track-id',
			'Salesid' => '888',
			'Timestamp' => time()
		);
	}
	
	$client->post($req[path], $data);
	$status = $client->getStatus();
	$cache = constant('TP_CAH_DIR') . md5($req[path] . '?' . http_build_query($data));
		
	if ($status != '200') {
		if ($feedfp = fopen($postlog, 'a')) {
			$log = sprintf("%s %s %s %s %s %s\nrequest:%s\nresponse:%s\n\n", 
				date('Y-m-d H:i:s u'),
				$_SERVER['REMOTE_ADDR'],  
				$status, 
				($req['host'] . ' ' . ($req['port'] ? $req['port'] : 80)) . ' ' . $req[path], 
				$client->getError(), 
				$cache,
				var_export($headers, true) . var_export($data, true), 
				$client->getContent());
			fwrite($feedfp, $log);
			fclose($feedfp);
		}
	} else {
		$content = $client->getContent();
		if ($cachefp = fopen($cache, 'w')) {
			fwrite($cachefp, $content);
			fclose($cachefp);
		}
	}
	if (!empty($content)) {
		$json = json_decode($content, true);
		if ($debug) { var_dump($json); }
		if ($json['result']['resultcode'] != '000000' || (isset($json['result']['isok']) && $json['result']['isok'] != 1)) {
			if ($errfp = fopen($errlog, 'a')) {
				$log = sprintf("%s %s %s %s %s %s\nrequest:%s\nresponse:%s\n\n", 
					date('Y-m-d H:i:s u'), 
					$_SERVER['REMOTE_ADDR'],  
					$client->getStatus(), 
					($req['host'] . ' ' . ($req['port'] ? $req['port'] : 80)) . ' ' . $req[path], 
					$client->getError(), 
					$cache,
					var_export($headers, true) . var_export($data, true), 
					$json == null ? $content : var_export($json, true));
				fwrite($errfp, $log);
				fclose($errfp);
			}
		}
		return $json;
	}	
}
