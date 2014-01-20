/*
* jQuery JCShare plugin 0.1
*
* Copyright (c) 2010 James.Ying
*
*/
;(function ($) {

$.fn.extend({
    share: function(options){
        options = $.extend({}, $.Share.defaults,options);

        return new $.Share(this, options);
    }
});

$.Share = function(input,options){
    var title = options.title ? options.title : document.title;
    var content = options.content ? options.content : document.title;
    var url = options.url ? options.url : document.URL;
    $.each(options.sharePlace, function(name, tag){
        input.find(tag).each(function(){
            var href = eval("options.replace." + name + "(this, title, content, url);");
            $(this).attr("target", options.target);
            $(this).attr("href", href);
        });
    });
};

$.Share.defaults = {
    share: ".share",
    sharePlace: {
        kaixin: ".share_kaixin",
        sina: ".share_sina",
        renren: ".share_renren",
        email: ".share_email",
        douban: ".share_douban",
        qq: ".share_qq",
        google: ".share_google",
        gmail: ".share_gmail",
        twitter: ".share_twitter"
    },
    title: "",
    content: "",
    url: "",
    target: "_blank",
    replace : {
        kaixin : function(div, title, content, url){
            return "http://www.kaixin001.com/repaste/share.php?rtitle=" + encodeURIComponent(title) + "&rurl=" + encodeURIComponent(url) + "&rcontent=" + encodeURIComponent(content);
        },
        sina : function(div, title, content, url){
            return "http://v.t.sina.com.cn/share/share.php?appkey=2000229224&url="+encodeURIComponent(url)+"&title="+ encodeURIComponent(title);
        }, 
        douban : function(div, title, content, url){
            return "http://www.douban.com/recommend/?url=" + encodeURIComponent(url) + "&title=" + encodeURIComponent(title);
        },
        renren : function(div, title, content, url){
            return "http://share.xiaonei.com/share/buttonshare.do?link=" + encodeURIComponent(url) +"&title=" + encodeURIComponent(title);
        },
        gmail : function(div, title, content, url){
        	return "https://mail.google.com/mail/?ui=2&view=cm&fs=1&tf=1&su=" + encodeURIComponent(title) +"&body=" + encodeURIComponent(url) + "-" + encodeURIComponent(title) + "&zx=19col4emn0ksj&shva=1";
        },
        qq : function(div, title, content, url){
            return "http://v.t.qq.com/share/share.php?title=" + encodeURI(title) + "&url=" + encodeURIComponent(url);
        }
    }
};
})(jQuery);