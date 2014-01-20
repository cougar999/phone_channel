<?php
define('TP_APP_DIR', dirname(__FILE__));
define('WEB_PROJ_PHYDIR', dirname(__FILE__).'/proj/');
define('TP_TPL_DIR', TP_APP_DIR.'/templates/');
define('TP_LIB_DIR', TP_APP_DIR.'/libs/includes/');
define('TP_PlG_DIR', TP_APP_DIR.'/libs/plugins/');
define('TP_WGT_DIR', TP_APP_DIR.'/widget/');
define('TP_CAH_DIR', TP_APP_DIR.'/cache/');
define('TP_DATA_DIR', TP_APP_DIR.'/data/');
define('TP_HTML_DIR', TP_APP_DIR.'/html/');

define('TP_WEB_URL', '/');
define('TP_RES_URL', '/');

foreach($_REQUEST as $_k=>$_v)
{
	if( strlen($_k)>0 && eregi('^(cfg_|GLOBALS)',$_k) )
	{
		exit('Request var not allow!');
	}
}
foreach(Array('_GET','_POST','_COOKIE','_REQUEST') as $_request)
{
	_RunMagicQuotes($$_request);
}
function _RunMagicQuotes(&$svar)
{
	if(!get_magic_quotes_gpc())
	{
		if( is_array($svar) )
		{
			foreach($svar as $_k => $_v) $svar[$_k] = _RunMagicQuotes($_v);
		}
		else
		{
			$svar = addslashes($svar);
		}
	}
	return $svar;
}

require_once WEB_PROJ_PHYDIR.'zwz.inc.php';
$url = new CUrlManager();
$req = new CHttpRequest();
$config = array(
	'urlManager'=>array(
		'urlFormat'=>'path',
		'showScriptName'=>false,
		'rules'=>array(
//			'http://<user:(api)>.pzi.com/<_c:\w+>/<_a:\w+>' => '<user>/<_c>/<_a>',
//			'show_<id:\d+>' => array('show','urlSuffix'=>'.html','caseSensitive'=>false),
			'apis/<dd:\w+><aa:\.?><rt_type:.*?>' => 'api/<dd>.php',
			'show_<sid:\d+>.html' => 'show.html',
			'apis/<cc:.*?>'=>'api/common/<cc>.php'
		)
	)
);
$url->configure($config['urlManager']);
$url->init();
ZwzInc::import('system.class.*');
ZwzInc::import('lib.classes.biz.model.*');
ZwzInc::import('lib.classes.db.*');

ZwzInc::import('system.func.common#func*',true);
$_GET['tp_tpl_page'] = $url->parseUrl($req);

$tp = array();
$tp['TP_WEB_URL'] = TP_WEB_URL;
$tp['TP_RES_URL'] = TP_RES_URL;
/*地址配置*/
define('HTTPCLIENT_TIMEOUT', 60);
define('AP_INT_DOMAIN', 'http://op.appdear.com');//http://172.16.17.117:8080//http://172.16.16.74:10022
define('AP_INT_DOMAIN2', 'http://118.145.22.12:9002');
define('AP_INT_DOMAIN3', 'http://sms.appdear.com');
define('AP_PC_MESSAGE_DOMAIN', '/html/pcmessage/');

define('AP_INT_channelValidation', AP_INT_DOMAIN . '/oper/pages/channel/channelValidation.jsp');
define('AP_INT_channleImport', AP_INT_DOMAIN . '/oper/pages/channel/channleImport.jsp');
define('AP_INT_channlePublish', AP_INT_DOMAIN . '/oper/pages/channel/channlePublish.jsp');
define('AP_INT_channlePublishAdjustment', AP_INT_DOMAIN . '/oper/pages/channel/channlePublishAdjustment.jsp');
define('AP_INT_channleSoft', AP_INT_DOMAIN . '/oper/pages/channel/channleSoft.jsp');
define('AP_INT_channelSoftAdjutment', AP_INT_DOMAIN . '/oper/pages/channel/channelSoftAdjutment.jsp');
define('AP_INT_channelSoftDetail', AP_INT_DOMAIN . '/oper/pages/channel/channelSoftDetail.jsp');

define('SMS_GOLDCOIN_STATE_BCODE', 10101);
define('AP_INT_SMSCATD', AP_INT_DOMAIN3 . '/aipimobile_sms_war/sms/smscatd.jsp');
/*地址配置*/

define('AP_INT_DOMAIN_CONTENT', 'http://118.145.22.12:9001/publish');

define('AP_INT_CASH', AP_INT_DOMAIN2 . '/pcr/pc/cashnew.jsp'); 								//3.6.126	金币兑换接口（cash）

$arr_article_category = array(1=>'业内资讯',2=>'资料学堂',3=>'店长分享');

$ap_int = array();
$ap_int['AP_INT_DOMAIN'] = AP_INT_DOMAIN;
$ap_int['AP_INT_DOMAIN_CONTENT'] = AP_INT_DOMAIN_CONTENT;

define('EVERY_LOGIN_DAY' , 1);									//每天登录
define('CONNECT_PHONE_SUCCESS' , 2);							//成功连接一部手机
define('INSTALL_APP_SUCCESS' , 3);								//成功安装一个应用
define('TWO_PHONE_BACKUP' , 4);									//双击复制成功一次
define('QUICKLY_REGISTER' , 5);									//手机快速注册一个会员
define('CLOUD_BACKUP' , 6);										//云备份成功一次
define('ACTIVATION_CARD_PHONE' , 7);							//下载卡联机激活一次
define('ACTIVATION_CARD_NOPHONE' , 8);							//下载卡非联机激活一次
define('EXDOWNLOAD_INSTALL_APP_SUCCESS' , 9);					//精品下载页面成功安装一个应用
define('ACTIVATION_CARD_PHONE_IOS' , 10);						//苹果下载卡激活一次

define('EVERY_LOGIN_DAY_VALUE' , 5);							//每天登录积分+5
define('CONNECT_PHONE_SUCCESS_VALUE' , 5);						//成功连接一部手机积分+5
define('INSTALL_APP_SUCCESS_VALUE' , 2);						//成功安装一个应用积分+2
define('TWO_PHONE_BACKUP_VALUE' , 20);							//双击复制成功一次积分+20
define('QUICKLY_REGISTER_VALUE' , 10);							//手机快速注册一个会员积分+10
define('CLOUD_BACKUP_VALUE' , 10);								//云备份成功一次积分+10
define('ACTIVATION_CARD_PHONE_VALUE' , 20);						//下载卡联机激活一次积分+40
define('ACTIVATION_CARD_NOPHONE_VALUE' , 10);					//下载卡非联机激活一次积分+20
define('EXDOWNLOAD_INSTALL_APP_SUCCESS_VALUE' , 4);				//精品下载页面内成功安装下载一个应用积分+4
define('ACTIVATION_CARD_PHONE_IOS_VALUE' , 50);					//苹果下载卡激活一次积分+50

define('EVERY_LOGIN_DAY_DESC' , "成功登录获得$1点积分。");										//每天登录积分+5
define('CONNECT_PHONE_SUCCESS_DESC' , "成功连接$2部手机获得$1点积分。");							//成功连接一部手机积分+5
define('INSTALL_APP_SUCCESS_DESC' , "成功为IMEI码为($3)的手机安装$2个应用获得$1点积分。");			//成功安装一个应用积分+2
define('TWO_PHONE_BACKUP_DESC' , "成功为IMEI码为($3)的手机双机复制成功一次获得$1点积分。");		//双击复制成功一次积分+20
define('QUICKLY_REGISTER_DESC' , "成功为手机号为($4)的手机快速注册一个会员获得$1点积分。");		//手机快速注册一个会员积分+10
define('CLOUD_BACKUP_DESC' , "云备份成功一次获得$1点积分。");										//云备份成功一次积分+10
define('ACTIVATION_CARD_PHONE_DESC' , "下载卡联机(IMEI码为$3)激活一次获得$1点积分。");				//下载卡联机激活一次积分+40
define('ACTIVATION_CARD_NOPHONE_DESC' , "下载卡非联机激活一次获得$1点积分。");					//下载卡非联机激活一次积分+20
define('EXDOWNLOAD_INSTALL_APP_SUCCESS_DESC' , "成功为IMEI码为($3)的手机安装$2个应用获得$1点积分。（月末双倍积分-精品下载页下载奖励）");					//精品下载页面成功安装一个应用积分+4
define('ACTIVATION_CARD_PHONE_IOS_DESC' , "苹果下载卡联机(IMEI码为$3)激活一次获得$1点积分");					//苹果下载卡激活一次积分+50
$_super_mager = array(5487,1009,980,979,945,978,954,944,978,1278,964,1010,17195);
$__CF__       = array(
	'gold' => array('conver'=>100)
);

function isSup($real_id,$_super_mager){
	if(!in_array($real_id,$_super_mager)){
			return false;
	}
	return true;
}
?>
