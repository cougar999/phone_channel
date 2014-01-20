<?php
/**
 * Template
 * Template Engine
 */
function tp_tpl_engine($site_info='') {
	require_once(TP_LIB_DIR . 'Smarty-2.6.26/libs/Smarty.class.php');
	$instance = new Smarty();
	// set smarty variable
	$instance->left_delimiter	= "<!--{";
	$instance->right_delimiter	= "}-->";
	$instance->debugging		= false;
	$instance->template_dir		= array_merge(array(TP_TPL_DIR.$site_info['template']),array(TP_WGT_DIR.$site_info['template']));
	$instance->config_dir		= TP_APP_DIR. "/configs/";
	!!file_exists($instance->config_dir.'smarty.conf') && $instance->config_load('smarty.conf');
	$instance->compile_dir		= TP_APP_DIR . "/templates_c/{$site_info['template']}";
	mkdirs($instance->compile_dir);
	$instance->plugins_dir		= array(TP_LIB_DIR ."Smarty-2.6.22/libs/plugins/", TP_LIB_DIR."Smarty-Plugins/", TP_PlG_DIR);
	return $instance;
}

/**
 * Template
 * Fetch Template
 */
function tp_tpl_fetch($instance=null, $content, $template='', $assign=array()) {
	if ($instance == null) $instance = tp_tpl_engine();
	
	$instance->assign('assign', $assign);
	
	$instance->assign('tp_tpl_instance', $instance);
	$instance->assign('tp_tpl_page', $page);
	$instance->assign('tp_tpl_layout', $tp_tpl_layout);
	
	// transform template
	if ($content != '') {
		$content = $instance->fetch($content, null, null, false);
	}
	if ($template != '') {
		$instance->assign('screen', $content);		
		$content = $instance->fetch($template, null, null, false);
	}
	return $content;
}

/**
 * Template
 * Display Screen
 */
function tp_tpl_display($instance=null, $page, $layout='', $assign=null, $pageplugin=null) {
	if ($instance == null) $instance = tp_tpl_engine();
	
	$instance->assign('assign', $assign);
	
	$instance->assign('tp_tpl_instance', $instance);
	$instance->assign('tp_tpl_page', $page);
	$instance->assign('tp_tpl_layout', $layout);
	$instance->assign('tp_tpl_page_plugin', $pageplugin);
	
	if (is_array($page)) {
		if ($page['layout'] != '') {
			$content = $instance->fetch($page['template'], null, null, false);
			$instance->assign('content', $content);
			$instance->display($page['layout'], null, null, false);
		} else {
			$instance->display($page['template'], null, null, false);
		}
	} else {
		if ($layout != '') {
			$page = $instance->fetch($page, null, null, false);
			$instance->assign('page', $page);
			$instance->display($layout, null, null, false);
		} else {
			$instance->display($page, null, null, false);
		}
	}
}
?>