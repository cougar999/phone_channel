<?php /* Smarty version 2.6.26, created on 2014-01-20 22:20:56
         compiled from admin/pcmessage/add.html */ ?>
<link rel="stylesheet" type="text/css" href="/resources/css<?php echo $this->_config[0]['vars']['version']; ?>
/form.css" />
<style>
#overflowDiv_fex td{text-align:left;}
#mess_html{display:none;}
.windows {float:left;margin-left:30px;font-size:12px;background:#fbfeff url(http://pcr5.appdear.com/resources/img40/windowbg.jpg) repeat-x left top;width:328px;height:236px;border:1px solid #3275b4;border-top-left-radius:5px;border-top-right-radius:5px;}
.wd_tit {height:30px; line-height:28px;padding:0 10px;}
.wd_tit h1 {color:#fff; font-size:12px; font-weight:bold; font-family:Simsun;margin:0;padding:0;float:left;width:150px;overflow:hidden}
.wd_tit .closeWindow {float:right;width:51px;height:22px;background:url(http://pcr5.appdear.com/resources/img40/window_spirit.png) no-repeat -1px -223px;text-indent:-1000px;overflow:hidden;}
.wd_tit .closeWindow a {display:block;height:22px;}
.wd_text {overflow:hidden;work-break:normal; white-space:normal;padding:18px 18px 0 72px;width:238px;height:133px;color:#4e4f4f;font-size:12px;line-height:180%;background:url(http://pcr5.appdear.com/resources/img40/window_spirit.png) no-repeat 10px 8px;}
.wd_text a {color:#2188fc;text-decoration:underline;font-weight:bold;line-height:23px;}
.wd_text a:hover {color:#f00;}
.wd_service {padding:8px 12px;margin-top:15px;}
.wd_service .callme {color:#ff6633;background:url(http://pcr5.appdear.com/resources/img40/window_spirit.png) no-repeat 87px -324px;padding-right:18px;margin-top:4px;float:left;}
.wd_service .qqonline {width:78px;height:24px;float:left;margin-left:20px;}
.wd_service .qqoffline {width:78px;height:24px;float:left;margin-left:20px;}
p{padding:0;marign:0;}
.mess_content{width:500px;height:200px;}
#town{display:none;}
#titleCheck{color: #333333;font-family: Georgia;font-size: 24px;}
</style>
<div class="bm_h cl">
	<div class="nav_dh">
		<label for="web"><a href="/">信息管理平台</a></label>
		<label for="web">&rsaquo;</label>
		<label for="web"><a href="#">发布推送消息</a></label>
	</div>
</div>
<div class="bm">
	<div class="bm_t">
		<h3>
		发布消息
		<span id='tableHeadDate' class='start_and_end_date'></span>
		</h3>
	</div>
	<div class="controlbar cl"  id="tab_use_info">
		<ul class="datebar cl">
			<li><a href="javascript:void(0);">编辑推送消息</a></li>
			<!--<li class='a' id="add1" onclick="setTab('add',1,3)"><a href="javascript:void(0);">编辑消息内容</a></li>
			<li id="add2" onclick="setTab('add',2,3)"><a href="javascript:void(0);">消息推送范围</a></li>
			<li id="add3" onclick="setTab('add',3,3)"><a href="javascript:void(0);">有效时间</a></li>-->
		</ul>
	</div>
	<form action="./add_proc.php" method="post"  onsubmit="return checkForm();">
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
							<td>
								<input type="text" style="width:400px;" class="measure-input" name="title" id="title" onkeyup="checkWord(18,event,'titleCheck','title_prompt');">
								<span id="title_prompt">还可以输入</span><span id="titleCheck">18</span>字
								</td>
						</tr>
						<tr>
							<th><font color="red">*</font>消息类型：</th>
							<td id="mess_type">
								<input type="radio" name="mess_type" value="1" checked>消息体
								<input type="radio" name="mess_type" value="2">html文件
							</td>
						</tr>
						<tr>
							<th><font color="red">*</font>内容：</th>
							<td id="mess_content">
								<h1>预览：</h1>
								<div class="windows">
									<div class="wd_tit">
										<h1>【输入消息头信息...】</h1>
										<div class="closeWindow"><a href="##">关闭</a></div>
									</div>
									<div class="wd_text">
										【这里是输入消息内容...】
									</div>
									<div class="wd_service">
										<span class="callme">有问题？来找我</span>
										<div class="qqonline"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=105047908&site=qq&menu=yes" class="openlink"><img border="0" src="http://wpa.qq.com/pa?p=2:105047908:41 &r=0.17891979683190584" alt="有问题？来找我" title="有问题？来找我"></a></div>
										<div class="qqoffline"><a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=458435777&site=qq&menu=yes" class="openlink"><img border="0" src="http://wpa.qq.com/pa?p=2:458435777:41 &r=0.17891979683190584" alt="有问题？来找我" title="有问题？来找我"></a></div>
									</div>
								</div>
								<div style="float:left;padding:10px 20px;">
									<h1>消息内容编辑：(可直接编写html源代码)</h1>
									<textarea class="mess_content" name="mess"></textarea>
								</div>
								
							</td>
							<td id="mess_html"><input type="text" style="width:400px;" class="input-text" name="mess_html"></td>
						</tr>
						<!-- <tr>
							<th>输入生成的文件名：</th>
							<td><input type="text" style="width:300px;" class="input-text">默认按日期命名，可更改</td>
						</tr> -->
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
									<input type="radio" name="for_type" value="1" checked id="alluser">全部用户
									<input type="radio" name="for_type" value="2" id="someuser">部分用户
									<input type="radio" name="for_type" value="3" id="otheruser">指定用户
								</div>
								<div id="alluser_content" class="f_line">
								</div>
								<div id="someuser_content" class="f_line1">
									<div class="f_line1">设定登录状态：
										<select name="isanony">
											<option value="" selected>所有用户</option>
											<option value="0">登录用户</option>
											<option value="1">匿名用户</option>
										</select>
									</div>
									<div class="f_line1">
										<div class="f_line1">设定账号体系：
											<select id="agent1"><option value="">所有体系</option></select>
											<select id="agent2"><option value="">所有分公司</option></select>
											<a href="javascript:void(0);" id="add_agent">加入设定列表</a>
											<div id="add_agent_content">
												<ul class="szlist clear"></ul>
											</div>
										</div>
									</div>
									<div class="f_line1">
										<div class="f_line1">设定地理地域：
											<span id="city_select_show"></span>
											<a href="javascript:void(0);" id="add_area">加入设定列表</a>
											<div id="add_area_content">
												<ul class="szlist clear"></ul>
											</div>
										</div>
									</div>
									<div class="f_line1">
										<div class="f_line1">设置客户端版本：
											<select id="pcversion">
												<option value="">所有版本</option>
												<option value="2.600">V2.6</option>
												<option value="2.700">V2.7</option>
												<option value="2.800">V2.8</option>
												<option value="2.900">V2.9</option>
											</select>
											<a href="javascript:void(0);" id="add_pcversion">加入设定列表</a>
											<div id="add_pcversion_content">
												<ul class="szlist clear"></ul>
											</div>
										</div>
									</div>
								</div>
								<div id="otheruser_content" class="f_line">
									<div class="f_line1">
										<div class="f_line1">添加用户：
											<input type="text" id="udids">
											<a href="javascript:void(0);" id="add_udids">加入设定列表</a>
											<div id="add_udids_content">
												<ul class="szlist clear"></ul>
											</div>
										</div>
									</div>
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
								<select name="valid_class">
									<option value="1">当天有效</option>
									<option value="2">本周有效</option>
									<option value="3">本月有效</option>
									<option value="4">永久有效</option>
								</select>
							</td>
						</tr>
					</table>
				</div>
				<div>
					<table>
						<tr>
							<td><input type="submit" id="pcmsg_submit"></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
    </div>
    </form>
</div>
<script type="text/javascript" src="/resources/js/jquery.provincesCity.js"></script>
<script type="text/javascript" src="/resources/js/provincesdata.js" charset="utf-8"></script>
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
		if(!province){
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
	
	$("#mess_type input[type=radio]").change(function(){
		$("#mess_type input[type=radio]:checked").each(function(){
			switch($(this).val()){
				case "1":
					$('#mess_html').hide();
					$('#mess_content').show();
				break;
				case "2":
					$('#mess_content').hide();
					$('#mess_html').show();
				break;
			}
		});
	});
	$("input[name=mess_html]").focusout(function(){
		var mess_html = $(this).val();
		if(mess_html == ''){
			alert('该字段不能为空！');
			return false;
		}
		$.post('/api/api_pcmessage.php',{mess_html:mess_html,type:"checkmesshtml"},function(data){
			if(data.result == 'success'){
			}else{
				alert('该url重复，请重新填写！');
				$(this).val('');
			}
		},'json');
	});
	$("#title").keyup(function(){
		var value = $(this).val();
	   	$(".wd_tit h1").html(value);
	});
	$('.mess_content').keyup(function(){
		$(".wd_text").html($(this).val());
	});
	
	$('#add_udids').click(function(){
		var real_name = $('#udids').val();
		if(!real_name){
			alert('不能为空！');
			return false;
		}
		$('#add_udids_content ul li').each(function(i){
			if(real_name == $(this).attr('title')){
				alert('不能重复添加!');
				return false;
			}else{
				getUdids(real_name);
			}
		});
		if($('#add_udids_content ul li').length <= 0){
			getUdids(real_name);
		}
	});
})
function getUdids(real_name){
	$.post('/api/api_uids.php',{real_name : real_name},function(data){
		if(data.data){
			var html_content = '<li rel="' + data.data + '" title="' + real_name + '">'
							 + '<p class="tagFun">'
							 + '<a class="tag">' + real_name + '</a>'
							 + '<a class="delTag" href="javascript:void(0);">×</a>'
							 + '</p>'
							 + '<span class="tag">' + real_name + '</span>'
							 + '<input type="hidden" name="for_udids[]" value="' + data.data + '">'
							 + '</li>';
			$('#add_udids_content ul').append(html_content);
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
		}else{
			alert("该用户没有收集到UDID，暂不能收到通知！");
			return false;
		}
	},'json');
}

function checkWord(len,evt,wordCheckNumID,wordPromptNumID){ 
	if(evt==null) 
	evt = window.event; 
	var src = evt.srcElement? evt.srcElement : evt.target; 
	var str = src.value.trim();
	myLen =0;myLenC = 2;
	i=0;
	for(;(i<str.length)&&(myLen<=len*2);i++){
		if(str.charCodeAt(i)>0&&str.charCodeAt(i)<128)
		 	myLen++;
		else
		   	myLen+=2;
	}
	for(;(i<str.length)&&(myLen>len*2);i++){
	 	if(str.charCodeAt(i)>0&&str.charCodeAt(i)<128){
			myLen++;
			myLenC++;
	 	}else{
		  	myLen+=2;
		  	myLenC+=2;
	 	}
	}
	if(myLen>len*2){
		document.getElementById(wordPromptNumID).innerHTML = "超出";
		document.getElementById(wordCheckNumID).innerHTML = Math.floor((myLenC)/2);
		document.getElementById(wordCheckNumID).style.color = "red";
	}else{
		document.getElementById(wordPromptNumID).innerHTML = "还可以输入";
		document.getElementById(wordCheckNumID).style.color = "#333333";
	  	document.getElementById(wordCheckNumID).innerHTML = Math.floor((len*2-myLen)/2);
	}
}

function checkForm(){
	var title_value = $('#title').val().trim();
	var content_value = $('.mess_content').val().trim();
	var mess_type = $("#mess_type input[type=radio]:checked").val();
	var tLen = 0;i=0;
	if((1 == mess_type) && (content_value.length <= 0)){
		alert('消息内容不能为空！');
		return false;
	}else if(2 == mess_type){
		var mess_html = $('input[name=mess_html]').val();
		if(mess_html.length <= 0){
			alert('外链url不能为空！');
			return false;
		}
	}
	for(;i<title_value.length;i++){
		if(title_value.charCodeAt(i)>0&&title_value.charCodeAt(i)<128)
			tLen++;
		else
			tLen += 2;
	}
	if(36 < tLen){
		alert('标题超出字数！');
		return false;
	}else if(0 >= tLen){
		alert('标题不能为空!');
		return false;
	}else{
		return true;
	}
}

</script>