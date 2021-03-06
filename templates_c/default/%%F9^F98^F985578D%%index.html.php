<?php /* Smarty version 2.6.26, created on 2014-01-20 22:21:00
         compiled from admin/pcmessage/index.html */ ?>
<link rel="stylesheet" type="text/css" href="/resources/css<?php echo $this->_config[0]['vars']['version']; ?>
/form.css" />
<style>
#overflowDiv_fex table{table-layout:fixed;}
.box table tr td a{padding:10px;}
</style>
<div class="bm_h cl">
	<div class="nav_dh">
		<label for="web"><a href="/">信息管理平台</a></label>
		<label for="web">&rsaquo;</label>
		<label for="web"><a href="#">已发布消息列表</a></label>
		<!-- <label style="float:right;margin-right:10px;"><a id="exportFile" href="javascript:void(0);" class="export" title="导出数据">导出数据</a></label> -->
	</div>
</div>
<div class="bm">
 	<!-- <div class="bm_t">
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
	</div> -->
	<div class="bm_t">
		<h3>
		已发布消息列表
		<span id='tableHeadDate' class='start_and_end_date'></span>
		</h3>
	</div>
	<div class="controlbar cl"  id="tab_use_info">
		<ul class="datebar cl">
			<li class='a'><a id="validlists" href="javascript:void(0);">有效消息</a></li>
			<li><a id="overlists" href="javascript:void(0);">过期消息</a></li>
		</ul>
	</div>
	<div id='load_div_cnt'>
		<div id='overflowDiv_fex' style='width:100%;float:left;'>
			<div class="box"></div>
		</div>
    </div>
</div>
<script language="javascript">
var arr_for_type = {
	1:"全部用户",
	2:"部分用户",
	3:"指定用户",
};
function getPCMessageList(row){
	$('.box').html('<center><img src="/resources/images/loader.gif"></center>');
	$.post('/api/api_pcmessage.php',row,function(data){
		var html_content = '';
		if(data.totalcount > 0){
			html_content += '<table>'
						+ '<colgroup><col width="5%"><col width="30%"><col width="10%"><col width="10%"><col width="10%"><col width="20%"></colgroup>'
	            		+ '<thead>'
	            		+ '<tr id="dataTheadTr_fex">'
	            		+ '<th>序号</th>'
	            		+ '<th>消息标题</th>'
	            		+ '<th>发布时间</th>'
	            		+ '<th>有效期</th>'
	            		+ '<th>可见用户</th>'
				        + '<th>操作</th>'
				        + '</tr>'
				        + '</thead>'
				        + '<tbody>';
			for(i = 0;i < data.items.length;i++){
				var item = data.items[i];
				//var date1 = new Date(Date.parse(item.start_time.replace("-", "/")));
				//var date2 = new Date(Date.parse(item.end_time.replace("-", "/")));
				var valid_time = item.etime;
				html_content += '<tr>'
					        + '<td>' + parseInt(i+1) + '</td>'
					        + '<td title="' + item.title + '">' + item.title + '</td>'
					        + '<td>' + item.create_time + '</td>'
					        + '<td title="' + item.start_time + '至' + item.end_time + '">' + valid_time + '</td>'
					        + '<td>' + arr_for_type[item.for_type] + '</td>';
				html_content += '<td><div class="modify">'
				//			+ '<a href="modify.html?id=' + item.id + '" class="modify">编辑</a>'
				        	+ '<a href="view.html?id=' + item.id + '" class="view">查看</a>'
				        	+ '<a href="javascript:void(0);" rel="' + item.id + '" class="delmsg">删除</a></div>';
				html_content += '</td></tr>';
			}
			html_content += '</tbody></table>';
			var row_paging = {
					count : row.count,
					pageno : row.pageno,
					totalcount : data.totalcount
				};
			html_content += getListPagingJS(row_paging,'pcmessage');
		}else{
			html_content += '<div style="padding:20px;font-size:18px;"><h1>暂无记录...</h1></div>';
		}
		$('.box').html(html_content);
		$('.pcmessagepageno').click(function(){
			row.pageno = parseInt($(this).find('span').html());
			getPCMessageList(row);
			return false;
		});
		$('.pcmessagefirstpage').click(function(){
			row.pageno = 1;
			getPCMessageList(row);
			return false;
		});
		$('.pcmessageprevpage').click(function(){
			row.pageno = row.pageno - 1;
			getPCMessageList(row);
			return false;
		});
		$('.pcmessagenextpage').click(function(){
			row.pageno = row.pageno + 1;
			getPCMessageList(row);
			return false;
		});
		$('.pcmessagelastpage').click(function(){
			row.pageno = Math.ceil(data.totalcount/row.count);
			getPCMessageList(row);
			return false;
		});
	},"json");
}
$(function(){
	getPCMessageList({pageno:1,count:20,type:'validlists'});
	
	$('#tab_use_info li').live('click',function(){
		$('#tab_use_info li').removeClass('a');
		$(this).addClass('a');
		if($(this).find('a').attr('id') == 'validlists'){
			getPCMessageList({pageno:1,count:20,type:'validlists'});
		}else if($(this).find('a').attr('id') == 'overlists'){
			getPCMessageList({pageno:1,count:20,type:'overlists'});
		}
	});
	$('.delmsg').live('click',function(){
		if(confirm('确定是否删除？')){
			var id = $(this).attr("rel");
			var row = {
				id : id,
				type : 'deletemsg'
			};
			var obj_this = $(this);
			$(this).after('<img src="/resources/images/loader.gif"><font color="red">正在删除...请稍等！</font>');
			$.post("/api/api_pcmessage.php",row,function(data){
				if(data.result == 'success'){
					obj_this.closest('tr').remove();
				}
			},"json");
		}
	});
});
</script>