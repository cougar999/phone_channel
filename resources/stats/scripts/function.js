function showFunction(funName){
    var functions = [
    {"title": "实时数据分析",
     "content":[
            {"name":"",
             "desc":"每十分钟内访问网站的浏览量（PV）和独立访客（UV），帮助您实时了解网站的动态流量。通常运用到网站的线上、线下活动之中，监测网站的流量变化，从而跟踪活动效果"
           }],
     "url": "/features/period?sId=2938105"
    },
	{"title": "网站分析",
     "content":[
            {"name":"",
             "desc":"提供全面准确的PV、UV、VV、IP等统计，包括站点的平均在线时长、跳出率等分析功能"
           }],
	 "url": "/site/index?sId=2938105"
    },
	{"title": "来源分析",
     "content":[
            {"name":"全部来源",
             "desc":"帮您了解访客来到网站的各种渠道，更合理地规划和推广网站"
           },
			{"name":"搜索引擎",
             "desc":"统计搜索引擎为您的网站带来的流量分布情况，使您了解哪些搜索引擎更有效，能帮助您更合理地优化搜索引擎推广"
           },
			{"name":"外部链接",
             "desc":"帮助您了解用户分别通过哪些外部链接访问到网站"
           },
			{"name":"关键词",
             "desc":"帮助您分析各大搜索引擎的关键词为网站带来的流量情况，为网站SEO优化提供依据"
           }],
	 "url": "/source/all?sId=2938105"
    },
	{"title": "路径分析",
     "content":[
            {"name":"",
             "desc":"网站首页为路径起点，观察访客在网站中的浏览足迹及访问下层页面的流失、留存情况。帮助您了解访客的访问行为，从而更合理地规划您的网站布局"
           }],
     "url": "/features/spreadPath?sId=2938105"
    },
	{"title": "会员与游客",
     "content":[
            {"name":"",
             "desc":"帮助您区分游客与会员的比重，让您清楚了解：浏览量（PV）、独立访客（UV）等各项指标趋势和规律"
           }],
     "url": "/features/guestMember?sId=2938105"
    },
	{"title": "热门版块/主题",
     "content":[
            {"name":"",
             "desc":"帮助您区分会员与游客分别关注哪些版块和主题"
           },
			{"name":"",
             "desc":"帮助您了解每个版块与主题的浏览量、访问量和跳出率等，从而衡量版块质量与话题方向"
           }],
     "url": "/features/forum?sId=2938105"
    },
	{"title": "会员参与度",
     "content":[
            {"name":"",
             "desc":"精确衡量会员在论坛中的活跃程度，区分不同发帖量级的会员参与情况，帮助站长更清晰地掌握网站活动效果"
           }],
     "url": "/visitor/engagement?sId=2938105"
    },
	{"title": "QQ互联分析",
     "content":[
            {"name":"",
             "desc":"通过区分统计QQ互联与普通用户的登陆、注册、绑定人数，帮助站长了解QQ互联每日的使用情况"
           }],
     "url": "/cloudplatform/QQConnect?sId=2938105"
    },
	{"title": "纵横搜索分析",
     "content":[
            {"name":"",
             "desc":"清晰展示用户每日的站内纵横搜索量与历史趋势，综合衡量纵横搜索使用情况"
           }],
     "url": "/cloudplatform/zonghengIndex?sId=2938105"
    },
	{"title": "广告效果分析",
     "content":[
            {"name":"",
             "desc":"展示所有站内广告的曝光量、访问次数、点击量与点击率等信息，帮助站长清晰把握站内广告脉搏"
           }],
     "url": "/ad/report?sId=2938105"
    },
	{"title": "网站监控中心",
     "content":[
            {"name":"",
             "desc":"为站长提供免费、及时、实用的网站监测工具，以帮助站长了解网站不可用状态及网站异常情况，并提出可供站长参考执行的具体优化建议"
           }],
     "url": "/availability/monitor?sId=2938105"
    },
	{"title": "网站/页面测速",
     "content":[
            {"name":"",
             "desc":"通过统计网站加载的平均时间，以地图形式展示网站的访问速度，使网站打开速度一览无余，方便站长对症下药、合理优化"
           }],
     "url": "/speed/area?sId=2938105"
    },
	{"title": "数据订阅",
     "content":[
            {"name":"",
             "desc":"为您量身定制的网站数据订阅功能，精选每日网站关键指标与最新数据“送货上门”，彻底解决您“忘看数据”的困扰，让TA与您靠得更近"
           }],
     "url": "/summary/index?sId=2938105"
    },
	{"title": "账号授权",
     "content":[
            {"name":"",
             "desc":"为您提供添加授权帐号功能，让多个帐号享受同等数据服务"
           }],
     "url": "/summary/index?sId=2938105"
    },
	{"title": "页面热区图",
     "content":[
            {"name":"",
             "desc":"首创热区自动识别功能，鼠标仅需在页面任意区域悬停0.5秒即可启动识别当前热区并展示点击量、占比等信息；同时在mini窗口中还可查询此热区的地域、来源、搜索词、浏览器等详细信息，让您轻松掌握该热区的访客行为及属性特征"
           }],
     "url": "/heatmap/listHeatMapConf?sId=2938105"
    }

    ]

    var tips = '           <div class="tips_msg">';
    for(i = 0; i < functions.length; i++){
        if (functions[i].title != funName){
            continue;
        }
        
        tips += '<h3>' + functions[i].title + '</h3>';
        tips += '<div class="tips_close"><span><a href="#self" onclick="hideFunctionPanel();" class="close">&times;</a></span><i class="s_b"></i></div>';
        tips += '<div class="msg_cont">';

        for(j = 0; j < functions[i].content.length; j++){
            var item = functions[i].content[j];
            if (item.name != ''){
                tips += '<dl>';
                tips += '<dt>' + item.name + '</dt>';
                tips += '<dd>' + item.desc + '</dd>';
                tips += "</dl>";
            }
            else{
                tips += '<p>' + item.desc + '</p>';
            }
        } 
        tips += '</div>';
        tips += '<div class="msg_link"><a onclick="javascript:sendHotClick(\'ta.function.demo.' + functions[i].title + '\');" href="' + functions[i].url + '" target="_blank">查看演示</a></div>';
    }
    tips += '</div>';

    $('#funTips').html(tips);
    showFunctionPanel();
}

function showFunctionPanel(){
    var top = document.body.scrollTop || document.documentElement.scrollTop;
    var winHeight = $(window).height();// || window.innerHeight;
    var divTop = top + winHeight/2 - $('#funTips').height()/2 - 80;
    $('#funTips').css('top', divTop + 'px');
    $('#funTips').show();
}

function hideFunctionPanel(){
    $('#funTips').hide();
}

$("#divFunction a").click(function(){
	var title = $(this).html().replace(/(^\s*)(\s*$)/g, ''); 
    sendHotClick("ta.function.info." + title);
	showFunction(title);
})
