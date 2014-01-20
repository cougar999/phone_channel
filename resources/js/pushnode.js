$(function(){
	$("#pushok").bind("click",function(){
		var sid = $("input[name='sid']").val();
		var sname = $("input[name='sname']").val();
		var issub = $("input[name='issub']").filter(':checked').val();
		if(!issub){issub = 0;}
		var mphone = $.trim($("input[name='phoneno']").val());
		var reg0 = /^13\d{9}$/;
        var reg1 = /^15\d{9}$/;
        var reg2 = /^18\d{9}$/;
        var reg3 = /^0\d{10,11}$/;
        var my = false;
        if (reg0.test(mphone)) my = true;
        if (reg1.test(mphone)) my = true;
        if (reg2.test(mphone)) my = true;
        if (reg3.test(mphone)) my = true;
        if (!my) {
        	alert("手机号码的格式不正确!");
        }else{
        	if($('.msg').length == 0){
        		$(".shownotebg").append("<div class='msg'><img src=\"/resources/images/loading_black.gif\"></div>");
        	}else{
        		$(".msg").html("<img src=\"/resources/images/loading_black.gif\">");
        	}
    		innerWidth = $('body').innerWidth();
			innerHeight = $('body').innerHeight();
			winHeight = $(window).height();
			winWidth = $(window).width();
			$(".shownotebg").css({'display':'block','width':innerWidth,'height':innerHeight});
			$(".msg").css({'display':'block','top':winHeight/2+"px",'left':winWidth/2+"px"});
        	//return false;
        	$.post("/api/api_pushtophone.php",{sid:sid,sname:sname,mobile:mphone,issub:issub},function(data){
        		if (data && data.result && data.isok && data.isok == 1) {
        			$(".msg").html('发送成功！<button onclick="shownoteclose();">关闭</button>');
        		}else{
        			$(".msg").html('发送失败，<button onclick="shownoteclose();">请重试！</button>');
        		}
        	},'json');
        }

		return false;
	});
});
function shownoteclose(){
	$('.shownotebg').css('display','none');
	$('.msg').css('display','none');
}