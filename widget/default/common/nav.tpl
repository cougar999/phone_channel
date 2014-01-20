<script type='text/javascript'>
GLOBAL_RT_INFO   =   {
	"soft_upload":{
		"001":"无权限对此进行操作！",
		"002":"解包失败或文件非法！",
		"004":"上传资源失败请重试！",
		"003":"无此软件信息请稍后再传！"
	},
	"mem_del":{
		"002":"不能删除自己账户！",
		"004":"无权限对此进行操作！",
		"001":"参数请求有误！",
		"005":"服务器忙请稍后再试！",
		"006":"删除成功！"
	}
}
STATIC_DOMAIN = '/resources';
$(function(){
	URLOBJ 	 	  = parseURL(document.URL);
	var closeLiId = _cookie.get("closeLiId");
	if(closeLiId != null){
		$("li[class=open_s], li[class=close_s]").each(function(){
			if(closeLiId.indexOf($(this).attr("id").replace("li_", "")) > -1){
	        	$(this).attr("class", "close_s");
	        }
	    	else{
	    		$(this).attr("class", "open_s");
	       	}
	   	});
	}
	$("li[class=open_s] a, li[class=close_s] a").click(function(){
		var closeLiId = "";
		$("li[class=close_s]").each(function(){
			closeLiId +=  $(this).attr("id").replace("li_", "") + ",";
		});
		if(closeLiId != "")
			closeLiId = closeLiId.substring(0, closeLiId.length - 1);
		_cookie.saveCookie("closeLiId", closeLiId, null, "channel.appdear.com");
	});
	//初始化菜单选中状态
	$('#leftFuncMenu li').each(function(k,v){
		if($(v).attr('class') == ''){
			var tmp_href   = $(v).find('a').eq(0).attr('href');
			var tmp_source = URLOBJ.path+URLOBJ.query;
			if(tmp_href == tmp_source){
				$(v).addClass('a');
				return false;
			}
		}
	})
	//初始化文本填充默认值
	try{
		$("input[type='text'],input[type='password'],textarea").defFieldLabels();
	}catch(e){}
})
</script>
<div class="bm">
<ul id="leftFuncMenu" class="nav">
	<li id="li_9" class="open_h"><a href="/"
		onclick="javascript:return changeFunc(this, 9562249);"><em></em>首页概况</a>
	</li>
<!--{if $smarty.session.isSup}-->
	<li id="li_21" class="open_s"><a href="javascript:;" onclick="displayMenu(21, 's');"><em></em>消息推送</a>
		<ul>
			<li id="li_2101" class=""><a href="/admin/pcmessage/add.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span class="">发布消息</span></a>
			</li>
			<!--{if $smarty.session.isSup}-->
			<li id="li_2102" class=""><a href="/admin/pcmessage/index.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span class="">已发布列表</span></a>
			</li>
			<!--{/if}-->
		</ul>
	</li>
<!--{/if}-->
<!--{if $smarty.session.isSup || $smarty.session.is_globadmin}-->
	<li id="li_18" class="open_s"><a href="javascript:;"
		onclick="displayMenu(18, 's');"><em></em>金币兑换</a>
		<ul>
			<li id="li_1801" class=""><a href="/admin/goldcenter/index.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span class="">兑换申请审核</span></a>
			</li>
			<!--{if $smarty.session.isSup}-->
			<li id="li_1802" class=""><a href="/admin/goldcenter/adminuser.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span class="">管理员设置</span></a>
			</li>
			<!--{/if}-->
		</ul>
	</li>
<!--{/if}-->
<!--{if !empty($smarty.session.level) || $smarty.session.isSup}-->
	<li id="li_19" class="open_s"><a href="javascript:;"
		onclick="displayMenu(19, 's');"><em></em>数据统计</a>
		<ul>
			<li id="li_1902" class=""><a href="/statistic_gold.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span class="">收入详情</span></a>
			</li>
			<li id="li_1901" class=""><a href="/statistic_login.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span>登陆详情</span></a>
			</li>
			<li id="li_1903" class=""><a href="/statistic_use.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span
				class="">使用详情</span></a></li>
		</ul>
	</li>
	<li id="li_29" class="open_s"><a href="javascript:;" onclick="displayMenu(29, 's');"><em></em>决策系统</a>
		<ul>
			<li id="li_2902" class=""><a href="/decision_shop.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span class="">店面维度</span></a>
			</li>
			<!--{if $smarty.session.isSup}-->
			<li id="li_2903" class=""><a href="/mag_info.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span class="<!--{if $smarty.now|date_format:'%Y-%m-%d' < '2012-11-25'}-->new<!--{/if}-->">资料维度</span></a>
			</li>
			<!--{/if}-->
		</ul>
	</li>
<!--{/if}-->
<!--{if !empty($smarty.session.chn_tpl_code)}-->
	<li id="li_20" class="open_s"><a href="javascript:;"
		onclick="displayMenu(20, 's');"><em></em>软件管理</a>
		<ul>
			<li id="li_2002" class=""><a href="/soft_upload.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span class="">软件上传</span></a>
			</li>
			<li id="li_2001" class=""><a href="/soft_release.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span>软件发布</span></a>
			</li>
		</ul>
	</li>
<!--{/if}-->
	<li id="li_11" class="open_s"><a href="javascript:;"
		onclick="displayMenu(11, 's');"><em></em>资料分享</a>
		<ul>
			<li id="li_1102" class=""><a href="/article/index.html?category=1"
				onclick="javascript:return changeFunc(this, 9562249);"><span>业内资讯</span></a>
			</li>
			<li id="li_1101" class=""><a href="/article/index.html?category=2"
				onclick="javascript:return changeFunc(this, 9562249);"><span>资料学堂</span></a>
			</li>
			<li id="li_1103" class=""><a href="/article/index.html?category=3"
				onclick="javascript:return changeFunc(this, 9562249);"><span>店长分享</span></a>
			</li>
		</ul>
	</li>
	<li id="li_13" class="open_s"><a href="javascript:;"
		onclick="displayMenu(13, 's');"><em></em>权限系统</a>
		<ul>
			<li id="li_1301" class=""><a href="/mag_user.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span>账号管理</span></a>
			</li>
		</ul>
	</li>
	<!--{if 0}-->
	<li id="li_12" class="open_s"><a href="javascript:;"
		onclick="displayMenu(12, 's');"><em></em>店员激励</a>
		<ul>
			<li id="li_1205" class=""><a href="/intergral/index.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span
				class="new0">店员中心</span></a></li>
			<li id="li_1201" class=""><a href="/intergral/ranking.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span>积分排名</span></a>
			</li>
			<li id="li_1202" class=""><a href="/intergral/detailed.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span>积分明细</span></a>
			</li>
			<li id="li_1203" class=""><a href="/intergral/conver.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span>积分商城</span></a>
			</li>
			<li id="li_1206" class=""><a href="/intergral/intergral.html"
				onclick="javascript:return changeFunc(this, 9562249);"><span>积分规则</span></a>
			</li>
		</ul>
	</li>
	<!--{/if}-->
</ul>
</div>