<?php /* Smarty version 2.6.26, created on 2014-01-20 22:20:48
         compiled from index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'statistic', 'index.html', 17, false),array('modifier', 'datestyle', 'index.html', 30, false),array('modifier', 'number_format', 'index.html', 31, false),array('modifier', 'string_format', 'index.html', 35, false),)), $this); ?>
<div class="bm_h cl">
	<div class="nav_dh">
		<label for="web"><a href="/">信息管理平台</a></label>
		<label for="web">&rsaquo;</label>
		<label for="web"><a href="/">首页概况</a></label>
	</div>
</div>
<div class="bm">
        <div class="notice notice_t mbm" id="tipsDiv" style='display:none;'>
        <em><a href="javascript:;" onclick="javascript:cancelTip(901, 'tipsDiv');">我知道了!</a></em>
        <p>网站基本概况，方便您了解和掌握网站的全局情况。</p>
    </div>
<?php if (empty ( $_SESSION['level'] ) && ! $_SESSION['isSup']): ?>
<p style="text-align:center;line-height:200px">暂无数据</p>
<?php else: ?>

<?php echo smarty_function_statistic(array('tag' => 'get_shop_login','type' => 0,'ret' => 'a_statistic_login_list','act' => 'index','count' => 12), $this);?>

            
    <div class="box" style="width:45%;float: left;clear: none;margin-right:5%;">
    	<h3> <em class="mtn"><a href="/statistic_login.html?date=<?php echo $this->_tpl_vars['a_statistic_login_list'][1]['stat_date']; ?>
&end_date=<?php echo $this->_tpl_vars['a_statistic_login_list'][0]['stat_date']; ?>
">查看详情&nbsp;&nbsp; </a></em> &nbsp;&nbsp;店面登录概况</h3>
        <table>
        <tr>
            <th></th>
                        <th>登录店面数</th>
                        <th>未登录店面数</th>
                        <th>总店面数</th>
                        <th>登陆比例</th>
                    </tr>
        <tr>
            <td class="hl"><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_login_list'][0]['stat_date'])) ? $this->_run_mod_handler('datestyle', true, $_tmp, '1') : smarty_modifier_datestyle($_tmp, '1')); ?>
</td>
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_login_list'][0]['login_y'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_login_list'][0]['login_n'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_login_list'][0]['login_t'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                        <td><?php if ($this->_tpl_vars['a_statistic_login_list'][0]['login_t'] && $this->_tpl_vars['a_statistic_login_list'][0]['login_t'] != 0): ?>
                        	<?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_login_list'][0]['login_y']*100/$this->_tpl_vars['a_statistic_login_list'][0]['login_t'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>
%<?php else: ?>0%<?php endif; ?></td>
                    </tr>
        <tr>
            <td class="hl"><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_login_list'][1]['stat_date'])) ? $this->_run_mod_handler('datestyle', true, $_tmp, '2') : smarty_modifier_datestyle($_tmp, '2')); ?>
</td>
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_login_list'][1]['login_y'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_login_list'][1]['login_n'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_login_list'][1]['login_t'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                        <td><?php if ($this->_tpl_vars['a_statistic_login_list'][1]['login_t'] && $this->_tpl_vars['a_statistic_login_list'][1]['login_t'] != 0): ?>
                        	<?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_login_list'][1]['login_y']*100/$this->_tpl_vars['a_statistic_login_list'][1]['login_t'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%.2f') : smarty_modifier_string_format($_tmp, '%.2f')); ?>
%<?php else: ?>0%<?php endif; ?></td>
                    </tr>
        </table>
    </div>
<?php echo smarty_function_statistic(array('tag' => 'get_shop_use','type' => 0,'ret' => 'a_statistic_list','act' => 'index','count' => 12), $this);?>

            
    <div class="box"  style="width:45%;float: left;clear: none;">
    	<h3><em class="mtn"><a href="/statistic_use.html?date=<?php echo $this->_tpl_vars['a_statistic_list'][1]['stat_date']; ?>
&end_date=<?php echo $this->_tpl_vars['a_statistic_list'][0]['stat_date']; ?>
">查看详情&nbsp;&nbsp;</a></em>&nbsp;&nbsp;店面使用概况</h3>
        <table>
        <tr>
            <th></th>
                        <!-- <th>连接苹果设备</th> -->
                        <th>连接安卓设备</th>
                        <!-- <th>下载苹果软件</th> -->
                        <th>下载安卓软件</th>
                        <!-- <th>苹果卡激活</th> -->
                        <!-- <th>下载卡激活</th> -->
                    </tr>
        <tr>
            <td class="hl"><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][0]['stat_date'])) ? $this->_run_mod_handler('datestyle', true, $_tmp, '1') : smarty_modifier_datestyle($_tmp, '1')); ?>
</td>
                       <!--  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][0]['ios_phone_connect'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td> -->
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][0]['apk_phone_connect'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                       <!--  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][0]['ios_soft_dl'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td> -->
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][0]['apk_soft_dl'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                       <!--  <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][0]['iso_card_act'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][0]['dl_card_act'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td> -->
                    </tr>
        <tr>
            <td class="hl"><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][1]['stat_date'])) ? $this->_run_mod_handler('datestyle', true, $_tmp, '2') : smarty_modifier_datestyle($_tmp, '2')); ?>
</td>
                        <!-- <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][1]['ios_phone_connect'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td> -->
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][1]['apk_phone_connect'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                        <!-- <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][1]['ios_soft_dl'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td> -->
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][1]['apk_soft_dl'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                        <!-- <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][1]['iso_card_act'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td>
                        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['a_statistic_list'][1]['dl_card_act'])) ? $this->_run_mod_handler('number_format', true, $_tmp) : number_format($_tmp)); ?>
</td> -->
                    </tr>
        </table>
    </div>
<?php endif; ?>
</div>