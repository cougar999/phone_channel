<?php /* Smarty version 2.6.26, created on 2014-01-20 22:20:48
         compiled from layout_page.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'config_load', 'layout_page.html', 1, false),array('function', 'admin', 'layout_page.html', 14, false),)), $this); ?>
<?php echo smarty_function_config_load(array('file' => "smarty.conf"), $this);?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="迪信通手机零售信息管理平台推出的智能手机一站式解决方案。包括android版及Wap网站。提供android软件的下载安装和手机的管理，可轻松备份通讯录和短信，云端数据存储保障安全。" />
<meta name="keywords" content="迪信通手机零售信息管理平台,android软件,安卓游戏,手机管理客户端,苹果软件,安卓软件下载,手机联系人备份，手机资料备份,手机数据恢复,免费游戏下载" />
<meta name="copyright" content=" © 2013 - 2018 迪信通手机零售信息管理平台" />
<meta name="generator" content="dphone Analytics" />
<title>迪信通手机零售信息管理平台 手机零售信息管理平台-智能手机一站解决方案·安卓软件游戏下载·android手机管理·手机备份、还原工具</title>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/header_static.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head>
<body>
<?php echo smarty_function_admin(array('tag' => 'checkadmingorup','ret' => 'b_admin_group_result'), $this);?>

<div id="appendParent"></div>
<div class="wrap">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="main mt_2">
<table class="mainframe">
	<tr>
		<td class="content">
			<?php echo $this->_tpl_vars['page']; ?>

		</td>
	</tr>
</table>
</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "common/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
</body>
</html>