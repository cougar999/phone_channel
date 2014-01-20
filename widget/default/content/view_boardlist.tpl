<!--{menu items=$arr_menulist.list.items index='code' code=$code ret='cur_info'}-->
<!--{menu items=$arr_menulist.list.items index='code' code=$code1 ret='menu_info'}-->
<!--{feed int=AP_INT_BOARD_LIST cid=$menu_info.sectionid order=0 pageno=1 count=10 ret='boardlist'}-->
<!--{feed int=AP_INT_BOARD_SOFTLIST bid=$boardlist.page.items[0].bid bsid=0 order=0 pageno=1 count=10 ret='boardsoftlist'}-->
<div class="boardlist cmn_transparent" id='dt_yy'>
	<h3>应用下载排行</h3>
	<ul>
	<!--{foreach from=$boardsoftlist.page.items item=list key=key}-->
		<li	<!--{if $key == 0}-->class="hover"<!--{/if}-->>
			<dt	<!--{if $key != 0}-->class="no"<!--{/if}-->>
				<div class="p_fl bsoft_icon">
					<a href="<!--{routePath type=$assign.st_info.route.sxqy sid=$list.softid}-->" target="_blank">
						<img title="<!--{$list.name}-->" <!--{if $key==0}-->src<!--{else}-->original<!--{/if}-->="<!--{$list.imgurl|imgsizepath:48x48}-->">
					</a>
				</div>
				<div class="p_fr bsoftinfo">
					<p class="position"><a href="<!--{routePath type=$assign.st_info.route.sxqy sid=$list.softid}-->" class="bsoft_title" title="<!--{$list.name}-->" target="_blank"><!--{$list.name}--></a><a href="<!--{$list.downloadurl}-->&code1=<!--{$code}-->&code1_id=<!--{$cur_info.sectionid}-->&code2=<!--{$code1}-->&code2_id=<!--{$menu_info.sectionid}-->&from=website" class="down"></a></p>
					<p>
                    	<span class="p_fl bsoft_class">
                    		<!--{feed int=AP_INT_SOFT_INFO softid=$list.softid order=0 pageno=1 count=1 ret='softinfo'}-->
                        	<!--{$softinfo.catalog}-->
                        </span>
                        <span class="p_fr bsoft_size"><!--{$list.size|sizetext}--></span>
                    </p>
				</div>
			</dt>
			<dd	<!--{if $key == 0}-->class="no"<!--{/if}-->>
				<span class="p_fl 	<!--{if $key > 2}-->board_icon<!--{else}-->board_top<!--{/if}-->"><!--{$key+1}--></span>
                <a href="#" class="p_fl" style="width:120px;padding-left:3px;line-height:35px;height:35px;overflow:hidden;" title="<!--{$list.name}-->"><!--{$list.name}--></a>
                <span class="p_fr"><!--{$list.size|sizetext}--></span>
			</dd>
		</li>
	<!--{/foreach}-->
	</ul>
</div>
<!--{menu items=$arr_menulist.list.items index='code' code=$code2 ret='menu_info'}-->
<!--{feed int=AP_INT_BOARD_LIST cid=$menu_info.sectionid order=0 pageno=1 count=10 ret='boardlist'}-->
<!--{feed int=AP_INT_BOARD_SOFTLIST bid=$boardlist.page.items[0].bid bsid=0 order=0 pageno=1 count=10 ret='boardsoftlist'}-->
<div class="boardlist cmn_transparent" id='dt_gm'>
	<h3>游戏下载排行</h3>
	<ul>
		<!--{foreach from=$boardsoftlist.page.items item=list key=key}-->
		<li	<!--{if $key == 0}-->class="hover"<!--{/if}-->>
			<dt	<!--{if $key != 0}-->class="no"<!--{/if}-->>
				<div class="p_fl bsoft_icon">
					<a href="<!--{routePath type=$assign.st_info.route.sxqy sid=$list.softid}-->" target="_blank">
						<img title="<!--{$list.name}-->" <!--{if $key==0}-->src<!--{else}-->original<!--{/if}-->="<!--{$list.imgurl|imgsizepath:48x48}-->">
					</a>
				</div>
				<div class="p_fr bsoftinfo">
					<p class="position"><a href="<!--{routePath type=$assign.st_info.route.sxqy sid=$list.softid}-->" class="bsoft_title" title="<!--{$list.name}-->" target="_blank"><!--{$list.name}--></a><a href="<!--{$list.downloadurl}-->&code1=<!--{$code}-->&code1_id=<!--{$cur_info.sectionid}-->&code2=<!--{$code2}-->&code2_id=<!--{$menu_info.sectionid}-->&from=website" class="down"></a></p>
					<p>
                    	<span class="p_fl bsoft_class">
                        	<!--{feed int=AP_INT_SOFT_INFO softid=$list.softid order=0 pageno=1 count=1 ret='softinfo'}-->
                        	<!--{$softinfo.catalog}-->
                        </span>
                        <span class="p_fr bsoft_size"><!--{$list.size|sizetext}--></span>
                    </p>
				</div>
			</dt>
			<dd	<!--{if $key == 0}-->class="no"<!--{/if}-->>
				<span class="p_fl 	<!--{if $key > 2}-->board_icon<!--{else}-->board_top<!--{/if}-->"><!--{$key+1}--></span>
                <a href="#" class="p_fl" style="width:120px;padding-left:3px;line-height:35px;height:35px;overflow:hidden;" title="<!--{$list.name}-->"><!--{$list.name}--></a>
                <span class="p_fr"><!--{$list.size|sizetext}--></span>
			</dd>
		</li>
	<!--{/foreach}-->
	</ul>
</div>
<script type="text/javascript">
function setStu(obj){
	var obj = obj || $('#dt_gm');
	$(obj).find('li').bind("mouseenter" , function(){
		if($(this).attr('class') == 'hover') return ;
		else{
			$(obj).find('li').each(function(k,v){
				$(v).removeClass("hover");
				$(v).find('dt').addClass("no");
				$(v).find('dd').removeClass("no");
			});
			$(this).addClass("hover");
			$(this).find('dt').removeClass("no");
			$(this).find('dd').addClass("no");
		}
		$(this).find('dt').find('img').trigger("sporty");
	}).bind("mouseleave" ,function(){
		
	});
}
setStu($('#dt_yy'));
setStu($('#dt_gm'));
$("#dt_gm img").lazyload({ 
    placeholder : "/resources/images/grey.gif",
    effect : "fadeIn",
    event : "sporty",
    onerr:function(self,settings){ self.attr('src','/resources/images/errpic.jpg');settings.loadImg.on && self.LoadImage_min(settings.loadImg.on,settings.loadImg.width,settings.loadImg.height,settings.loadImg.loader);},
    loadImg:{on:true,width:48,height:48,loader:'/resources/images/loader.gif'}
});
$("#dt_yy img").lazyload({ 
    placeholder : "/resources/images/grey.gif",
    effect : "fadeIn",
    event : "sporty",
    onerr:function(self,settings){ self.attr('src','/resources/images/errpic.jpg');settings.loadImg.on && self.LoadImage_min(settings.loadImg.on,settings.loadImg.width,settings.loadImg.height,settings.loadImg.loader);},
    loadImg:{on:true,width:48,height:48,loader:'/resources/images/loader.gif'}
});
</script>