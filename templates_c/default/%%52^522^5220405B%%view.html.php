<?php /* Smarty version 2.6.26, created on 2014-01-20 22:34:38
         compiled from admin/pcmessage/view.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'pcmessage', 'admin/pcmessage/view.html', 53, false),)), $this); ?>
<link rel="stylesheet" type="text/css" href="/resources/css<?php echo $this->_config[0]['vars']['version']; ?>
/form.css" />
<style>
#overflowDiv_fex td{text-align:left;}
.windows {margin-left:30px;font-size:12px;background:#fbfeff url(http://pc2.d-fone.com/resources/img40/windowbg.jpg) repeat-x left top;width:328px;height:236px;border:1px solid #3275b4;border-top-left-radius:5px;border-top-right-radius:5px;}
.wd_tit {height:30px; line-height:28px;padding:0 10px;}
.wd_tit h1 {color:#fff; font-size:12px; font-weight:bold; font-family:Simsun;margin:0;padding:0;float:left;}
.wd_tit .closeWindow {float:right;width:51px;height:22px;background:url(http://pc2.d-fone.com/resources/img40/window_spirit.png) no-repeat -1px -223px;text-indent:-1000px;overflow:hidden;}
.wd_tit .closeWindow a {display:block;height:22px;}
.wd_text {overflow:hidden;work-break:normal; white-space:normal;padding:18px 18px 0 72px;width:238px;height:133px;color:#4e4f4f;font-size:12px;line-height:180%;background:url(http://pc2.d-fone.com/resources/img40/window_spirit.png) no-repeat 10px 8px;}
.wd_text a {color:#2188fc;text-decoration:underline;font-weight:bold;line-height:23px;}
.wd_text a:hover {color:#f00;}
.wd_service {padding:8px 12px;margin-top:15px;}
.wd_service .callme {color:#ff6633;background:url(http://pc2.d-fone.com/resources/img40/window_spirit.png) no-repeat 87px -324px;padding-right:18px;margin-top:4px;float:left;}
.wd_service .qqonline {width:78px;height:24px;float:left;margin-left:20px;}
.wd_service .qqoffline {width:78px;height:24px;float:left;margin-left:20px;}
#town{display:none;}
</style>
<div class="bm_h cl">
	<div class="nav_dh">
		<label for="web"><a href="/">信息管理平台</a></label>
		<label for="web">&rsaquo;</label>
		<label for="web"><a href="#">修改推送消息</a></label>
		<label style="float:right;margin-right:10px;"><a id="exportFile" href="javascript:void(0);" class="export" title="导出数据">导出数据</a></label>
	</div>
</div>
<div class="bm">
 	<div class="bm_t">
		<h3>
			<div class="controlsearch vm">
			<input type="text" id="searchUrl" name="searchUrl" value="" class="px xg1" size="40" />
			<input type="hidden" id="searchUrlEncode" name="searchUrlEncode" value=""/>
			&nbsp;
			<button id="searchUrlBtn" class="pnc pn">
			<span>查询</span>
			</button>
			<button class="pn" id="searchUrlBackBtn" style='display:none;'>
			<span>返回</span>
			</button>
			</div>
		</h3>
	</div>
	<div class="bm_t">
		<h3>
		发布消息
		<span id='tableHeadDate' class='start_and_end_date'></span>
		</h3>
	</div>
	<div class="controlbar cl"  id="tab_use_info">
		<ul class="datebar cl">
			<li><a href="javascript:void(0);">编辑推送消息</a></li>
		</ul>
	</div>
	<?php echo smarty_function_pcmessage(array('tag' => 'view','id' => $_GET['id'],'ret' => 'item'), $this);?>

    <div id='load_div_cnt'>
		<div id='overflowDiv_fex' style='width:100%;float:left;'>
			<div class="box">
				<div id="con_add_1">
					<table>
						<tr>
							<th colspan="2">第一步：编辑要发布的消息内容和样式</th>
						</tr>
						<tr>
							<th><font color="red">*</font>标题：</th>
							<td><input type="text" style="width:400px;" class="measure-input" name="title" value="<?php echo $this->_tpl_vars['item']['title']; ?>
"></td>
						</tr>
						<tr>
							<th><font color="red">*</font>内容：</th>
							<td>
								<div class="windows">
									<div class="wd_tit">
										<h1><?php echo $this->_tpl_vars['item']['title']; ?>
</h1>
										<div class="closeWindow"><a href="##">关闭</a></div>
									</div>
									<div class="wd_text">
										<?php echo $this->_tpl_vars['item']['mess']; ?>

									</div>
									<div class="wd_service">
										<span class="callme">有问题？来找我</span>
										<div class="qqonline"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=105047908&site=qq&menu=yes" class="openlink"><img border="0" src="http://wpa.qq.com/pa?p=2:105047908:41 &r=0.17891979683190584" alt="有问题？来找我" title="有问题？来找我"></a></div>
										<div class="qqoffline"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=458435777&site=qq&menu=yes" class="openlink"><img border="0" src="http://wpa.qq.com/pa?p=2:458435777:41 &r=0.17891979683190584" alt="有问题？来找我" title="有问题？来找我"></a></div>
									</div>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="con_add_2">
					<table>
						<tr>
							<th colspan="2">第二步：设定消息的推送范围</th>
						</tr>
						<tr>
							<th><font color="red">*</font>用户类型：</th>
							<td>
								<div class="f_line" id="for_type">
									<input type="radio" name="for_type" value="1" <?php if ($this->_tpl_vars['item']['for_type'] == 1): ?>checked<?php endif; ?> id="alluser">全部用户
									<input type="radio" name="for_type" value="2" <?php if ($this->_tpl_vars['item']['for_type'] == 2): ?>checked<?php endif; ?> id="someuser">部分用户
									<input type="radio" name="for_type" value="3" <?php if ($this->_tpl_vars['item']['for_type'] == 3): ?>checked<?php endif; ?> id="otheruser">指定用户
								</div>
								<div id="alluser_content" class="f_line">
								</div>
								<div id="someuser_content" class="f_line1">
									<div class="f_line1">设定登录状态：
										<select name="isanony">
											<option value="" <?php if ($this->_tpl_vars['item']['isanony'] == ''): ?>selected<?php endif; ?>>所有用户</option>
											<option value="0" <?php if ($this->_tpl_vars['item']['isanony'] == 0): ?>selected<?php endif; ?>>登录用户</option>
											<option value="1" <?php if ($this->_tpl_vars['item']['isanony'] == 1): ?>selected<?php endif; ?>>匿名用户</option>
										</select>
									</div>
									<div class="f_line1">
										<div class="f_line1">设定账号体系：
											<select id="agent1"><option value="">所有体系</option></select>
											<select id="agent2"><option value="">所有分公司</option></select>
											<a href="javascript:void(0);" id="add_agent">加入设定列表</a>
											<div id="add_agent_content">
												<ul class="szlist clear">
												<?php $_from = $this->_tpl_vars['item']['link_channel']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['link_channel']):
?>
													<li rel="<?php echo $this->_tpl_vars['link_channel']['channel1']; ?>
,<?php echo $this->_tpl_vars['link_channel']['channel2']; ?>
">
														<p class="tagFun">
															<a class="tag"><?php echo $this->_tpl_vars['link_channel']['channel1name']; ?>
 > <?php echo $this->_tpl_vars['link_channel']['channel2name']; ?>
</a>
															<a class="delTag" href="javascript:void(0);">×</a>
														</p>
														<span class="tag"><?php echo $this->_tpl_vars['link_channel']['channel1name']; ?>
 > <?php echo $this->_tpl_vars['link_channel']['channel2name']; ?>
</span>
														<input type="hidden" name="msg_channel[]" value="<?php echo $this->_tpl_vars['link_channel']['channel1']; ?>
,<?php echo $this->_tpl_vars['link_channel']['channel2']; ?>
">
													</li>
												<?php endforeach; endif; unset($_from); ?>
												</ul>
											</div>
										</div>
									</div>
									<div class="f_line1">
										<div class="f_line1">设定地理地域：
											<span id="city_select_show"></span>
											<a href="javascript:void(0);" id="add_area">加入设定列表</a>
											<div id="add_area_content">
												<ul class="szlist clear">
												<?php $_from = $this->_tpl_vars['item']['link_area']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['link_area']):
?>
													<li rel="<?php echo $this->_tpl_vars['link_area']['province']; ?>
,<?php echo $this->_tpl_vars['link_area']['city']; ?>
">
														<p class="tagFun">
														<a class="tag"><?php echo $this->_tpl_vars['link_area']['province']; ?>
 > <?php echo $this->_tpl_vars['link_area']['city']; ?>
</a>
														<a class="delTag" href="javascript:void(0);">×</a>
														</p>
														<span class="tag"><?php echo $this->_tpl_vars['link_area']['province']; ?>
 > <?php echo $this->_tpl_vars['link_area']['city']; ?>
</span>
														<input type="hidden" name="msg_area[]" value="<?php echo $this->_tpl_vars['link_area']['province']; ?>
,<?php echo $this->_tpl_vars['link_area']['city']; ?>
">
													</li>
												<?php endforeach; endif; unset($_from); ?>
												</ul>
											</div>
										</div>
									</div>
									<div class="f_line1">
										<div class="f_line1">设置客户端版本：
											<select id="pcversion">
												<option value="">所有版本</option>
												<option value="2.600">v2.6</option>
												<option value="2.700">v2.7</option>
												<option value="2.800">v2.8</option>
												<option value="2.900">v2.9</option>
											</select>
											<a href="javascript:void(0);" id="add_pcversion">加入设定列表</a>
											<div id="add_pcversion_content">
												<ul class="szlist clear">
												<?php $_from = $this->_tpl_vars['item']['for_version']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['for_version']):
?>
													<li rel="<?php echo $this->_tpl_vars['key']; ?>
">
														<p class="tagFun">
															<a class="tag"><?php echo $this->_tpl_vars['for_version']; ?>
</a>
															<a class="delTag" href="javascript:void(0);">×</a>
														</p>
														<span class="tag"><?php echo $this->_tpl_vars['for_version']; ?>
</span>
														<input type="hidden" name="msg_version[]" value="<?php echo $this->_tpl_vars['key']; ?>
">
													</li>
												<?php endforeach; endif; unset($_from); ?>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<div id="otheruser_content" class="f_line">
									添加用户：<textarea style="width:100%;height:100px;"><?php echo $this->_tpl_vars['item']['for_udids']; ?>
</textarea>
									<br/>按英文输入法逗号“,”分割
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div id="con_add_3">
					<table>
						<tr>
							<th colspan="2">第三步：设定消息的有效时间</th>
						</tr>
						<tr>
							<th><font color="red">*</font>该消息的有效时间为：</th>
							<td>
								<select name="etime">
									<option value="当天有效" <?php if ($this->_tpl_vars['item']['etime'] == '当天有效'): ?>selected<?php endif; ?>>当天有效</option>
									<option value="本周有效" <?php if ($this->_tpl_vars['item']['etime'] == '本周有效'): ?>selected<?php endif; ?>>本周有效</option>
									<option value="本月有效" <?php if ($this->_tpl_vars['item']['etime'] == '本月有效'): ?>selected<?php endif; ?>>本月有效</option>
									<option value="永久有效" <?php if ($this->_tpl_vars['item']['etime'] == '永久有效'): ?>selected<?php endif; ?>>永久有效</option>
								</select>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
    </div>
</div>
<script type="text/javascript" src="/resources/js/jquery.provincesCity.js"></script>
<script type="text/javascript" src="/resources/js/provincesdata.js" charset="utf-8"></script>
<script type="text/javascript" src="/resources/ckeditor/ckeditor.js"></script>
<script language="javascript">
var count_channel = 0;
var obj_agent1_info = '';
var obj_agent2_info = new Array();
var count_area = 0;

function setTab(name,cursel,n){
	for(i=1;i<=n;i++){
		var menu=document.getElementById(name+i);
		var con=document.getElementById("con_"+name+"_"+i);
		menu.className=i==cursel?"a":"";
		con.style.display=i==cursel?"block":"none";
	}
}
$(function(){
	$("#city_select_show").ProvinceCity((URLOBJ.params.province || ''),'','');
	var html_content = '<select id="agent1"><option value="">所有体系</option></select><select id="agent2"><option value="">所有分公司</option></select>';
	$('#form_agent').append(html_content);
	$.getJSON('/api/api_member.php'+'?callback=?','act=show&type=1',function(data){
		for(var i = 0; i < data.length; i++){
			var agent_id = data[i].id;
			var agent_name = data[i].text;
			var html_option = '<option value="' + agent_id + '">' + agent_name + '</option>';
			$('#agent1').append(html_option);
		}
		$('#agent1').change(function(){
			var agent_id = $(this).val();
			var html2_option = '<option value="">所有分公司</option>';
			if(agent_id && !obj_agent2_info[agent_id]){
				$.getJSON('/api/api_member.php'+'?callback=?','act=show&type=1&nid='+ agent_id,function(data2){
					obj_agent2_info[agent_id] = data2;
					for(var j = 0; j < data2.length;j++){
						var agent2_id = data2[j].id;
						var agent2_name = data2[j].text;
						html2_option += '<option value="' + agent2_id + '">' + agent2_name + '</option>';
					}
					$('#agent2').html(html2_option);
				});
			}else if(agent_id){
				for(var j = 0; j < obj_agent2_info[agent_id].length;j++){
					var agent2_id = obj_agent2_info[agent_id][j].id;
					var agent2_name = obj_agent2_info[agent_id][j].text;
					html2_option += '<option value="' + agent2_id + '">' + agent2_name + '</option>';
				}
			}
			$('#agent2').html(html2_option);
		});
	});
	$('#add_agent').click(function(){
		var agent1 = $('#agent1').val();
		if(!agent1){
			alert('全部体系不需要添加！');return false;
		}
		var agent1_value = $('#agent1 option:selected').text();
		var agent2 = $('#agent2').val();
		var agent2_value = $('#agent2 option:selected').text();
		var html_content = '<li rel="' + agent1 + ',' + agent2 + '">'
						 + '<p class="tagFun">'
						 + '<a class="tag">' + agent1_value + ' > ' + agent2_value + '</a>'
						 + '<a class="delTag" href="javascript:void(0);">×</a>'
						 + '</p>'
						 + '<span class="tag">' + agent1_value + ' > ' + agent2_value + '</span>'
						 + '<input type="hidden" name="msg_channel[]" value="' + agent1 + ',' + agent2 + '">'
						 + '</li>';
		$('#add_agent_content ul').append(html_content);
		$('.delTag').click(function(){
			$(this).closest('li').remove();
		});
		$('ul.szlist li').mouseover(function(){
			$(this).addClass('hover');
			$(this).children('.tagFun').show();
		}).mouseout(function(){
			$(this).removeClass('hover');
			$(this).children('.tagFun').hide();
		});
	});
	
	$('#add_area').click(function(){
		var province = $('#province').val();
		if(!province || (province == 0)){
			alert('全部地域不需要添加！');
			return false;
		}
		var province_value = $('#province option:selected').text();
		var city = $('#city').val();
		var city_value = $('#city option:selected').text();
		var html_content = '<li rel="' + province + ',' + city + '">'
						 + '<p class="tagFun">'
						 + '<a class="tag">' + province_value + ' > ' + city_value + '</a>'
						 + '<a class="delTag" href="javascript:void(0);">×</a>'
						 + '</p>'
						 + '<span class="tag">' + province_value + ' > ' + city_value + '</span>'
						 + '<input type="hidden" name="msg_area[]" value="' + province + ',' + city + '">'
						 + '</li>';
		$('#add_area_content ul').append(html_content);
		$('.delTag').click(function(){
			$(this).closest('li').remove();
		});
		$('ul.szlist li').mouseover(function(){
			$(this).addClass('hover');
			$(this).children('.tagFun').show();
		}).mouseout(function(){
			$(this).removeClass('hover');
			$(this).children('.tagFun').hide();
		});
	});
	
	$('#add_pcversion').click(function(){
		var version = $('#pcversion').val();
		if(!version){
			alert('全部版本不需要添加！');
			return false;
		}
		var version_value = $('#pcversion option:selected').text();
		var is_repeat = false;
		$('#add_pcversion_content ul li').each(function(){
			if($(this).attr('rel') == ''){
				alert('已全部添加，若要添加请删除全部！');
				is_repeat = true;
				return false;
			}else if(($(this).attr('rel') == version) || (version == '')){
				alert('不能重复添加::'+version_value);
				is_repeat = true;
				return false;
			}
		});
		if(!is_repeat){
			var html_content = '<li rel="' + version + '">'
							 + '<p class="tagFun">'
							 + '<a class="tag">' + version_value + '</a>'
							 + '<a class="delTag" href="javascript:void(0);">×</a>'
							 + '</p>'
							 + '<span class="tag">' + version_value + '</span>'
							  + '<input type="hidden" name="msg_version[]" value="' + version + '">'
							 + '</li>';
			$('#add_pcversion_content ul').append(html_content);
			$('.delTag').click(function(){
				$(this).closest('li').remove();
			});
			$('ul.szlist li').mouseover(function(){
				$(this).addClass('hover');
				$(this).children('.tagFun').show();
			}).mouseout(function(){
				$(this).removeClass('hover');
				$(this).children('.tagFun').hide();
			});
		}
	});
	
	$("#for_type input[type=radio]").change(function(){
		$("#for_type input[type=radio]").each(function(){
			$("#"+$(this).attr("id")+"_content").hide();
		});
		$("#"+$(this).attr("id")+"_content").show();
	});
	$("#for_type input[type=radio]:checked").each(function(){
		$("#for_type input[type=radio]").each(function(){
			$("#"+$(this).attr("id")+"_content").hide();
		});
		$("#"+$(this).attr("id")+"_content").show();
	});
	
	$('.delTag').click(function(){
		$(this).closest('li').remove();
	});
	$('ul.szlist li').mouseover(function(){
		$(this).addClass('hover');
		$(this).children('.tagFun').show();
	}).mouseout(function(){
		$(this).removeClass('hover');
		$(this).children('.tagFun').hide();
	});
})
</script>