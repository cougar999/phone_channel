function showNews(){
    var news = [
    {"date":"2012-06-14",
        "title":"TA与JiaThis携手提供更全面的社会化...", 
        "desc":"TA与JiaThis携手提供更全面的社会化数据分析", 
        "url":"http://ta.qq.com/notice/trendsDetails/?nId=29"},
    {"date":"2012-04-20",
        "title":"流量波动分析测试版上线", 
        "desc":"流量波动分析测试版上线", 
        "url":"http://ta.qq.com/notice/trendsDetails/?nId=13"},
    {"date":"2012-01-06",
        "title":"TA重大技术架构升级，实时计算秒级...", 
        "desc":"TA重大技术架构升级，实时计算秒级刷新", 
        "url":"http://ta.qq.com/notice/trendsDetails/?nId=12"},
    { "date":"2011-12-29", 
        "title":"网站可用性监控正式全量开放", 
        "desc":"网站可用性监控正式全量开放", 
        "url":"http://ta.qq.com/notice/trendsDetails/?nId=10"}, 
    {"date":"2011-12-12",
        "title":"移动互联网统计上线",
        "desc":"移动互联网统计上线",
        "url":"http://ta.com/notice/trendsDetails/?nId=9"}
    ]

    for(i = 0; i< news.length; i++){
            document.write('<li><i class="">•</i><a href="' + news[i].url + '" target="_blank" title="' + news[i].desc + '">' + news[i].title + '</a><span>' + news[i].date + '</span></li>');
        }
}

showNews();

