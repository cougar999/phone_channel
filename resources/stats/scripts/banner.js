
var bannerList = [
{'id': 'banner3',
    'href': '/notice/trendsDetails/?nId=12&ADTAG=TA.INDEX.BANNER.REALTIME',
    'hottag': 'ta.index.banner.realtime',
    'target': '_blank'
},
{'id': 'banner2',
    'href': '/heatmap/introduce?ADTAG=TA.INDEX.BANNER.LINK',
    'hottag': 'ta.index.banner.heatmap',
    'target': '_self'
},
{'id': 'banner1',
    'href': '',
    'hottag': '',
    'target': ''
}
] 

var strBanner = '';
for(i = 0; i < bannerList.length; i++){
    var banner = bannerList[i];
    var strTarget = '';
    var strDisplay = '';
    if (banner.target != ''){
        strTarget = ' target="' + banner.target + '" ';
    }
    if (i != 0){
        strDisplay = ' style="display:none" ';
    }
    strBanner += '            <div class="' + banner.id + '" href="' + banner.href + '" hottag="' + banner.hottag + '" ' + strTarget + strDisplay + ' ></div>';
}
$('#banner_list').html(strBanner);

var t = 0, cur = n = 0, count;
var timerList = [];
$(document).ready(function(){   
    count=$("#banner_list div").length;
    showBanner(0);
    $("#banner_dot i").click(function() {
        n = $(this).index(); 
        showBanner(n, false);
        removeAllTimer();
        addTimer();
    });
    addTimer();
});

function addTimer(){
    t = setInterval("move()", 6000);
    timerList.push(t);
}

function removeAllTimer(){
    for(var i = 0; i < timerList.length; i++){
        clearInterval(timerList[i]);
    }
    timerList.length = 0;
}

function move(){
    n++;
    n = validIndex(n);
    showBanner(n, true);
}

function validIndex(index){
    if (index >= count){
        index = 0;
    }
    if (index < 0){
        index = count - 1;
    }

    return index;
}

function showBanner(index, animate){
    var pre = cur;
    cur = index;

    var curBanner = $('#banner_list div').eq(cur);
    var preBanner = $('#banner_list div').eq(pre);
    var bannerLink = $('#bannerLink');
    var curDot = $('#banner_dot i').eq(cur);

    if (curBanner.attr('href')){
        bannerLink.attr('href', curBanner.attr('href'));
        bannerLink.show();
        bannerLink.attr('hottag', curBanner.attr('hottag'));
        bannerLink.attr('target', curBanner.attr('target'));
    }else{
        bannerLink.hide();
    }

    if (animate){
        preBanner.animate({opacity:'hide'}, 1000, null, function(){
            curDot.addClass("on");
            curDot.siblings().removeClass("on");
            curBanner.animate({opacity: 'show'}, 1000);
        });           
    }else{
        preBanner.hide();
        curBanner.show();
        curDot.addClass("on");
        curDot.siblings().removeClass("on");
    } 
}

