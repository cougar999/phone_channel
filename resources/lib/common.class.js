String.prototype.getBytesLength = function() { 
	return this.replace(/[^\x00-\xff]/gi, "--").length; 
};
var ZwzCommonOp = {
	roundFun:function(numberRound,roundDigit)
	{
	    if(numberRound>=0){
	         var tempNumber = parseInt((numberRound*Math.pow(10,roundDigit)+0.5))/Math.pow(10,roundDigit);  
	         return tempNumber;
	    }else{
			numberRound1 =- numberRound;
			var tempNumber = parseInt((numberRound1*Math.pow(10,roundDigit)+0.5))/Math.pow(10,roundDigit);
			return -tempNumber;
	    }
	},
	char_escape_html:function(str){
		return $('<div></div>').text(str).html();
	},
	char_cutstr_:function(str,len,ext){
		var ext = ext || '..';
		if(len>=str.length) return str;
		else{
			return str.slice(0,len)+ext;
		}
	},
	char_cutstr:function(str,len,ext){
		//废弃
		var str_length = 0;
		var str_len = 0;
		var ext     = ext || '..';//...
		str_cut = new String();
		str_len = str.length;
		for (var i = 0; i < str_len; i++) {
			a = str.charAt(i);
			str_length++;
			if (escape(a).length > 4) {
				str_length++
			}
			str_cut = str_cut.concat(a);
			if (str_length >= len) {
				str_cut = str_cut.concat(ext);
				return str_cut
			}
		}
		if (str_length < len) {
			return str
		}
	},
	format_fmoney:function(s, n){
		n = n > 0 && n <= 20 ? n : 2;
		s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";
		var l = s.split(".")[0].split("").reverse(),
		r = s.split(".")[1];
		t = "";
		for(i = 0; i < l.length; i ++ )
		{
		  t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");
		}
		return t.split("").reverse().join("") + "." + r;
	},
	format_fileSize:function(value){
		if(null==value||value==''){
			return "0 Bytes";
		}
		var unitArr = new Array("Bytes","KB","MB","GB","TB","PB","EB","ZB","YB");
		var index=0;

		var srcsize = parseFloat(value);
		var size    = this.roundFun(srcsize/Math.pow(1024,(index=Math.floor(Math.log(srcsize)/Math.log(1024)))),2);
		return size+unitArr[index];
	},
	loading_layerClose:function(className){
	    var divClass = 'div.flashChartLoading';
	    if('undefined' != typeof(className)) {
	        divClass = className;
	    }
	    $(divClass).each(function() {
	        $(this).remove();
	    });
	},
	loading_layerOpen:function(id) {
	    var id_class = '.flashChart, .overflowDiv';
	    if('undefined' != typeof(id)) {
	        id_class = '#' + id;
	    }
	    var isIe6 = false;
	    var top_extra = 0; 
	    var browser_diff = 0;
	    $(id_class).each(function() {
			var _self = this;
	        if($(_self).hasClass("overflowDiv")) {
	            top_extra = 30;
	            if($.browser.msie) {
	                if ($.browser.version < 7) {
	                    isIe6 = true;
	                    browser_diff = top_extra / 2; 
	                } else {
	                    browser_diff = top_extra;
	                }
	            }
	        };
	        var pos = $(_self).offset();
	        var fDiv = document.createElement('div');
	        var jDiv = $(fDiv);
	        jDiv.css('position', 'absolute');
	        jDiv.css('left', pos.left);
	        jDiv.css('top', pos.top + top_extra);
	        jDiv.css('width', _self.offsetWidth);
	        jDiv.css('height', _self.offsetHeight - browser_diff);
	        jDiv.css('line-height', _self.offsetHeight - browser_diff + 'px');
			jDiv.css({"background":"none repeat scroll 0 0 #FFFFFF","opacity":"0.4","text-align":"center"});
	        jDiv.addClass('flashChartLoading');
	        jDiv.html('<img src="' + STATIC_DOMAIN + '/stats/images/loading.gif" />数据加载中...');
	        $(_self).parent().append(jDiv);
			$('.flashChartLoading img').css({"margin-right":"6px"});
			$('.flashChartLoading *').css({"vertical-align":"middle"});
	    });
	},
	clone:function(obj,flag){
		var flag = flag || 1;
		var objClone;
	    if (obj.constructor == Object) {
	        objClone = new obj.constructor();
	    } else {
	        objClone = new obj.constructor(obj.valueOf());
	    }
	    for (var key in obj) {
	        if (objClone[key] != obj[key]) {
	            if (typeof(obj[key]) == 'object') {
	                objClone[key] = clone(obj[key]);
	            } else {
	                objClone[key] = obj[key];
	            }
	        }
	    }
		if(1 == flag){
			objClone.toString = obj.toString;
			objClone.valueOf = obj.valueOf;
		}
	    return objClone;
	},
	isEmpty:function(data){
		try{
			return $.isArray(data)?!data.length:$.isEmptyObject(data);
		}catch(e){
			return true;
		}
	},
	obj_char:{
		autoAddEllipsis:function(pStr, pLen ,pExt) {
			var pStr = (pStr || '').toString();
			var pExt = pExt || '..';
			var _ret = this.cutString(pStr, (pStr.getBytesLength()>pLen?pLen-pExt.getBytesLength():pLen));
			var _cutFlag = _ret.cutflag;
			var _cutStringn = _ret.cutstring;
			if ("1" == _cutFlag) {
				return _cutStringn + pExt
			} else {
				return _cutStringn
			}
		},
		cutString:function(pStr, pLen) {
			var _strLen = pStr.length;
			var _tmpCode;
			var _cutString;
			var _cutFlag = "1";
			var _lenCount = 0;
			var _ret = false;
			if (_strLen <= pLen / 2) {
				_cutString = pStr;
				_ret = true
			}
			if (!_ret) {
				for (var i = 0; i < _strLen; i++) {
					if (this.isFull(pStr.charAt(i))) {
						_lenCount += 2
					} else {
						_lenCount += 1
					}
					if (_lenCount > pLen) {
						_cutString = pStr.substring(0, i);
						_ret = true;
						break
					} else if (_lenCount == pLen) {
						_cutString = pStr.substring(0, i + 1);
						_ret = true;
						break
					}
				}
			}
			if (!_ret) {
				_cutString = pStr;
				_ret = true
			}
			if (_cutString.length == _strLen) {
				_cutFlag = "0"
			}
			return {
				"cutstring": _cutString,
				"cutflag": _cutFlag
			}
		},
		isFull:function(pChar){
			if ((pChar.charCodeAt(0) > 128)) {
				return true
			} else {
				return false
			}
		}
	},
	obj_url:{
		// public method for url encoding
		encode : function (string) {
			return escape(this._utf8_encode(string));
		},
		// public method for url decoding
		decode : function (string) {
			return this._utf8_decode(unescape(string));
		},
		// private method for UTF-8 encoding
		_utf8_encode : function (string) {
			string = string.replace(/\r\n/g,"\n");
			var utftext = "";
	 
			for (var n = 0; n < string.length; n++) {
				var c = string.charCodeAt(n);
				if (c < 128) {
					utftext += String.fromCharCode(c);
				}
				else if((c > 127) && (c < 2048)) {
					utftext += String.fromCharCode((c >> 6) | 192);
					utftext += String.fromCharCode((c & 63) | 128);
				}
				else {
					utftext += String.fromCharCode((c >> 12) | 224);
					utftext += String.fromCharCode(((c >> 6) & 63) | 128);
					utftext += String.fromCharCode((c & 63) | 128);
				}
			}
			return utftext;
		},
		// private method for UTF-8 decoding
		_utf8_decode : function (utftext) {
			var string = "";
			var i = 0;
			var c = c1 = c2 = 0;
	 
			while ( i < utftext.length ) {
				c = utftext.charCodeAt(i);
				if (c < 128) {
					string += String.fromCharCode(c);
					i++;
				}
				else if((c > 191) && (c < 224)) {
					c2 = utftext.charCodeAt(i+1);
					string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
					i += 2;
				}
				else {
					c2 = utftext.charCodeAt(i+1);
					c3 = utftext.charCodeAt(i+2);
					string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
					i += 3;
				}
			}
			return string;
		},
		//parseURL
		parseURL:function(url) {
			var url= url || document.URL;
			var a  = document.createElement('a');
			a.href = url;
			return {
				source: url,
				protocol: a.protocol.replace(':', ''),
				host: a.hostname,
				domain:(function(){
					domains = a.hostname;
					var redomain 	  = '';
					var domainArray   = new Array("com", "net", "org", "gov", "edu");
					var domains_array = domains.split('.');
					var domain_count  = domains_array.length - 1;
					var flag 		  = false;
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
				})(),
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
	},
	file_obj:{
		GetExtName:function(pathfilename){
			var reg = /(\\+)/g;
			var pfn = pathfilename.replace(reg, "#");
			var arrpfn = pfn.split("#");
			var fn = arrpfn[arrpfn.length - 1];
			var arrfn = fn.split(".");
			return arrfn[arrfn.length - 1];
		}
	}
};
(function($) {
    $.fn.defFieldLabels = function(options) {
        var base = this;
        var opts = $.extend({},$.fn.defFieldLabels.defaults, options);
		var bcss = ".plg_lb_val{\
						position:relative;\
						display:inline-block\
					}\
					.plg_lb_cls{\
						position:absolute;\
						left:0px;top:0px;\
						color:#999999;\
						font-weight:normal;\
						cursor:text !important;\
						font-size:12px;\
					}";
        function bindInputChange(obj_v, obj_l) {
            '' == $.trim($(obj_v).val()) ? $(obj_l).show() : $(obj_l).hide();
            return false
        }
        if (this.selector) $("head").append("<style type='text/css'>"+bcss+"</style>");
        return this.each(function(i, obj) {
			var _self_     = this;
            var plg_defval = $(this).attr('plg_defval');
            var plg_defcls = eval('(' + ($(this).attr('plg_defcls') || "{}") + ')');
            if (!plg_defval) return;
            var obj_p = $("<span class='plg_lb_val'></span>");
            $(obj_p).appendTo($(this).parent()).insertBefore($(this));
            $(this).appendTo($(obj_p));
            var obj_l = $("<label class='plg_lb_cls' for='" + ($(this).attr('id') || '') + "'>" + plg_defval + "</label>");
            $(obj_l).appendTo($(obj_p));
            $(obj_l).css($.extend({},
            {
                "left": "7px",
                "line-height": $(this).parent().height() + 'px'
            },
            plg_defcls));
            $(this).bind('propertychange',
            function(e) {
                bindInputChange($(this), $(obj_l))
            }).bind('input',
            function(e) {
                bindInputChange($(this), $(obj_l))
            }).bind('keyup',
            function(e) {
                8 == e.keyCode && $.browser.msie && ($.browser.version == "9.0") && (bindInputChange($(this), $(obj_l)))
            });
			$(obj_l).click(function(e){
				$(_self_).focus();
			})
			'' != $.trim($(_self_).val()) && $(obj_l).hide();
        })
    };
    $.fn.defFieldLabels.defaults = {}
})(jQuery);