(function($){
	//扩展这个方法到jquery;
	$.fn.extend({   
		//将可选择的变量传递给方法
		slideimg: function(options) {  
			
			//参数和默认值
			var defaults = {
			   tol_class:'.last',       //获得点击左按钮的class
			   tor_class:'.next',      //获得点击右按钮的class
			   slide_class:'.slideimglist',    //获得滑动层的class
			   center_class:'.hdactive',         //获得中间点的class
			   imgshow_num:'5',      //最多可见图片的张数；
			   cen_dot_bar:'true',   //当cen_dot_bar为'true'时中间显示，否则关闭；
			   gostart_on:'true',   //自动运行开关
			   dotimg_src:'/resources/images/yuan_blue.gif',   //中间点src
			   centerbar_class:'.center_dot',              //中间点所在层的class
			   runtime:'2000',      //自动运行时间
			   hdCallBack:function(){}//滑动事件回调
			}; 
			
			var options = $.extend(defaults, options); 
			var count = 1;
			var checkname;
			var thisidv;
			
			//遍历匹配元素的集合
			return this.each(function() {
			var o =options;
            
            //获得最外层的ID; 
			var thisid = $(this).attr("id");
			//转化：去除中间点的class的'.'
			var cenclass =o.center_class.substr('1');
			//获得中间点击点处的class
            var centerBar = $($(this).find(o.centerbar_class),$(this));
			var slidebar = $($(this).find(o.slide_class),$(this));
			//最多滑动的次数（Math.ceil()方法向上取整）
			o.maxnum = Math.ceil(slidebar.find('.dl_line').size()/o.imgshow_num);

			//设置可见层的宽
			o.slide_wid = slidebar.parent().width();
			//设置滑动层的宽；

			slidebar.css("width",o.slide_wid*o.maxnum+"px");
			slidebar.css("position","relative");

			//添加中间处的点击点
			
			if(o.cen_dot_bar == 'true'){
				for( var i=1;i<=o.maxnum;i++){
					if(i == 1){
						$(centerBar).append(
							"<span" + ' ' + "name="+i+ " " +"class='active "+cenclass+' '+cenclass+i +"'>"+"</span>"
						);
					}else{
						$(centerBar).append(
							"<span" + ' ' + "name="+i+ " " +"class='node "+cenclass+' '+cenclass+i +"'>"+"</span>"
						);
					}
				}
			};
			
			var goleftbtn = $($(this).find(o.tol_class),$(this));
			var gorightbtn = $($(this).find(o.tor_class),$(this));
			var clickCenter = $($(this).find(o.center_class),$(this));
            
			//点击向左按钮时；
			$(goleftbtn).click(function(){
				if(count > 1){
				  count--;
				  com_cen_src();
				}else{
				  count = o.maxnum;
				  com_cen_src();
				}
			});
			 
			//点击向右按钮时；
			$(gorightbtn).click(function(){
				if( count < o.maxnum){
				  count++;
				  com_cen_src();	  
				}else{
				  slidebar.animate({left:'0'},{queue:false});
				  count = 1;
				  com_cen_src();
				}
			});
            
			//点击中间点时
			$(clickCenter).click(function(){
				   checkname = $(this).attr("name");
				   count = checkname;
				   com_cen_src();
			});
			
			//调置中间点击处的透明度
			$(o.center_class).css({"cursor":"pointer"});
			$(o.tol_class +","+o.tor_class).css("opacity","0.3");
			$(o.tol_class +","+o.tor_class).hover(function(){
				$(this).css("opacity","1");
				//go_start();
				},function(){
				$(this).css("opacity","0.8");
				//go_stop();
			});
			
			
			//自动运行
			function slideshow3(){
				if( count < o.maxnum){
					  count++;
					  com_cen_src();	  
					}else{
					  slidebar.animate({left:'0'},{queue:false});
					  count = 1;
					  com_cen_src();
					}
				};
	
				function com_cen_src(){
					slidebar.animate({left:'-' + (o.slide_wid-230)*(count-1)},{queue:false});
				    $("#"+ thisid +" "+o.center_class).addClass("node");
					$("#"+ thisid +" "+o.center_class + count).removeClass("node").addClass("active");
					o.hdCallBack(o,count);
				}
					
				if( o.gostart_on == 'true'){
				   go_start();
				};
				
				function go_start(){
				   thisidv = setInterval(slideshow3,o.runtime);
				};
				function go_stop(){
					clearInterval(thisidv);
				};
			
		    });  
		}  
	});  
})(jQuery);