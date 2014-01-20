function parseURL(url) {
    var a  = document.createElement('a');
    a.href = url;
    return {
        source: url,
        protocol: a.protocol.replace(':', ''),
        host: a.hostname,
        port: a.port,
        query: a.search,
        params: (function() {
            var ret = {},
            seg = a.search.replace(/^\?/, '').split('&'),
            len = seg.length,
            i = 0,
            s;
            for (; i < len; i++) {
                if (!seg[i]) {
                    continue;
                }
                s = seg[i].split('=');
                ret[s[0]] = decodeURI(s[1]);
            }
            return ret;
        })(),
        file: (a.pathname.match(/\/([^\/?#]+)$/i) || [, ''])[1],
        hash: a.hash.replace('#', ''),
        path: a.pathname.replace(/^([^\/])/, '/$1'),
        relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [, ''])[1],
        segments: a.pathname.replace(/^\//, '').split('/')
    };
}
var UtilCp = {
    set_cp_tip: function(type, isShow) {
        var isShow = isShow || 0;
        var _self = this;
        if (isShow == '-1') return;
        $.getJSON('http://www.' + _self.getRealDomain() + '/api/user/UserPopedomCp?callback=?', {
            act: 'set_cp_tip',
            type: type,
            r: Math.random(),
            isShow: isShow
        },
        function(data) {
            if (data.code == 'B0001') _self.dz_msgwin('您尚未登陆', 1000);
            else if (data.code == 'SCT006') {
                _self.dz_msgwin(data.data.s, 2000, data.data.t);
            }
        })
    },
    getString: function(str, len, ext) {
        var length = len || 6;
        var ext = ext || '';
        if (str == null) {
            return 0;
        }
        var l = str.length;
        var blen = 0;
        var i = 0;
        for (i = 0; i < l; i++) {
            if ((str.charCodeAt(i) & 65280) != 0) {
                blen++;
                if (blen > length) break;
            }
            blen++;
            if (blen > length) break;
        }
        var rStr = str.substring(0, i);
        if (i < l) {
            rStr += ext;
        }
        return rStr;
    },
    Homepage: function() {
        if (document.all) {
            document.body.style.behavior = 'url(#default#homepage)';
            document.body.setHomePage(window.location.href);
        } else if (window.sidebar) {
            if (window.netscape) {
                try {
                    netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                } catch(e) {
                    alert("该操作被浏览器拒绝，如果想启用该功能，请在地址栏内输入 about:config,然后将项 signed.applets.codebase_principal_support 值该为true");
                }
            }
            try {
                var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
                prefs.setCharPref('browser.startup.homepage', window.location.href);
            } catch(e) {}
        }
    },
    Favorite: function(sURL, sTitle) {
        var sURL = sURL || window.location.href;
        var sTitle = sTitle || document.title;
        try {
            window.external.AddFavorite(sURL, sTitle);
        } catch(e) {
            try {
                window.sidebar.addPanel(sTitle, sURL, "");
            } catch(e) {
                alert("加入收藏失败,请手动添加.");
            }
        }
    },
    showMsg: function(id, msg, height) {
        var height = height || ($('#' + id).height()) / 2;
        var msg = msg || '';
        var h = '';
        h += '<B>' + msg + '</B>';
        $('#' + id).html('<div style="font-size:18px;margin-top:' + (height) + 'px;text-align:center;">' + h + '</div>');
    },
    loadingHtml: function(id, src, width, height, tip) {
        var height = height || $('#' + id).height();
        var width = width || $('#' + id).width();
        var tip = tip || '数据加载中';
        var src = src || 'http://www.' + this.getRealDomain() + '/images/loader.gif';
        $('#' + id).html('<div style="width:' + (width == 0 ? '100%': width + 'px') + ';height:' + (height == 0 ? '100%': height + 'px') + ';background:url(' + src + ') center no-repeat;" alt="' + tip + '" title="' + tip + '"></div>');
    },
    getRealDomain: function(domains) {
        domains = domains || window.location.host;
        var redomain = '';
        var domainArray = new Array("com", "net", "org", "gov", "edu");
        var domains_array = domains.split('.');
        var domain_count = domains_array.length - 1;
        var flag = false;
        if (domains_array[domain_count] == 'cn') {
            for (i = 0; i < domainArray.length; i++) {
                if (domains_array[domain_count - 1] == domainArray[i]) {
                    flag = true;
                    break;
                }
            }
            if (flag == true) {
                redomain = domains_array[domain_count - 2] + "." + domains_array[domain_count - 1] + "." + domains_array[domain_count];
            } else {
                redomain = domains_array[domain_count - 1] + "." + domains_array[domain_count];
            }
        } else {
            redomain = domains_array[domain_count - 1] + "." + domains_array[domain_count];
        }
        return redomain;
    },
    URLParamRequest: function(strName) {
        var strHref = document.location.toString();
        var intPos = strHref.indexOf("?");
        var strRight = strHref.substr(intPos + 1);
        if (strHref.indexOf("&") < 0) {
            strRight += "&";
        }
        var arrTmp = strRight.split("&");
        for (var i = 0; i < arrTmp.length; i++) {
            var dIntPos = arrTmp[i].indexOf("=");
            var paraName = arrTmp[i].substr(0, dIntPos);
            var paraData = arrTmp[i].substr(dIntPos + 1);
            if (paraName.toUpperCase() == strName.toUpperCase()) {
                return decodeURIComponent(paraData);
            }
        }
        return "";
    },
    selChe: function(name) {
        var flag = false;
        $("[name='" + name + "\[\]']").each(function() {
            if ($(this).attr("checked")) {
                flag = true;
                return;
            }
        });
        return flag;
    },
    selAll: function(name) {
        $("[name='" + name + "\[\]']").attr("checked", 'true');
    },
    selRev: function(name) {
        $("[name='" + name + "\[\]']").each(function() {
            if ($(this).attr("checked")) {
                $(this).removeAttr("checked");
            } else {
                $(this).attr("checked", 'true');
            }
        });
    },
    getLocalTime:function(nS) {  
        return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ').replace(/年|月/g, "-").replace(/日/g, " ");
    },
    dz_msgwin: function(s, t, _t) {
        var _self = this;
        var _t = _t || '&nbsp;';
        var msgWinObj = document.getElementById('msgwin');
        if (!msgWinObj) {
            var msgWinObj = document.createElement("div");
            msgWinObj.id = 'msgwin';
            msgWinObj.style.display = 'none';
            msgWinObj.style.position = 'absolute';
            msgWinObj.style.zIndex = '100000';
            document.body.appendChild(msgWinObj);
        }
        msgWinObj.innerHTML = "<div class='popupmenu_layer'>" + "<p>" + _t + "</p><p class='btn_line'>" + s + "</p></div>";
        msgWinObj.style.display = '';
        msgWinObj.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=0)';
        msgWinObj.style.opacity = 0;
        var sTop = document.documentElement && document.documentElement.scrollTop ? document.documentElement.scrollTop: document.body.scrollTop;
        pbegin = sTop + (document.documentElement.clientHeight / 2);
        pend = sTop + (document.documentElement.clientHeight / 5);
        setTimeout(function() {
            UtilCp.dz_showmsgwin(pbegin, pend, 0, t)
        },
        10);
        msgWinObj.style.left = ((document.documentElement.clientWidth - msgWinObj.clientWidth) / 2) + 'px';
        msgWinObj.style.top = pbegin + 'px';
    },
    dz_showmsgwin: function(b, e, a, t) {
        var _self = this;
        step = (b - e) / 10;
        var msgWinObj = document.getElementById('msgwin');
        newp = (parseInt(msgWinObj.style.top) - step);
        if (newp > e) {
            msgWinObj.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + a + ')';
            msgWinObj.style.opacity = a / 100;
            msgWinObj.style.top = newp + 'px';
            setTimeout(function() {
                UtilCp.dz_showmsgwin(b, e, a += 10, t)
            },
            10);
        } else {
            msgWinObj.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=100)';
            msgWinObj.style.opacity = 1;
            setTimeout('UtilCp.dz_displayOpacity(\'msgwin\', 100)', t);
        }
    },
    dz_displayOpacity: function(id, n) {
        var _self = this;
        if (!document.getElementById(id)) {
            return;
        }
        if (n >= 0) {
            n -= 10;
            document.getElementById(id).style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + n + ')';
            document.getElementById(id).style.opacity = n / 100;
            setTimeout('UtilCp.dz_displayOpacity(\'' + id + '\',' + n + ')', 50);
        } else {
            document.getElementById(id).style.display = 'none';
            document.getElementById(id).style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=100)';
            document.getElementById(id).style.opacity = 1;
        }
    }
}
ERR_INFO   =   {
	"sms":{
		"004":"号码格式不正确！",
		"006":"发送成功！",
		"002":"请求类型有误！",
		"003":"服务器忙请稍后再试！"
	}
}
function setWaitTime(time){
	var count = time || 30;
	$('#push_nsfid').prop("disabled",true);
	countdown = setInterval(function(){
		$(".tip_time").html(count + " 秒后可再推送");
		if (count == 0) {
			window.clearInterval(countdown);
			$(".tip_time").html("");
			$('#push_nsfid').prop("disabled",false);
		}
		count--;
	}, 1000);
}