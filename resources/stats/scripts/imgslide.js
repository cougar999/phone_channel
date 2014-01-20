/**
 * @author firefu
 */
/**
<div class="t_pre"><a href="#">上一页</a></div>
<div class="t_next"><a href="#">下一页</a></div>
<div class="change">
	<a href="#"><img src="http://imgcache.qq.com/bossweb/ta/pic/company_logo/c_logo1.jpg"></a>
	<a href="#"><img src="http://imgcache.qq.com/bossweb/ta/pic/company_logo/c_logo2.jpg"></a>
	<a href="#"><img src="http://imgcache.qq.com/bossweb/ta/pic/company_logo/c_logo1.jpg"></a>
	<a href="#"><img src="http://imgcache.qq.com/bossweb/ta/pic/company_logo/c_logo2.jpg"></a>
	<a href="#"><img src="http://imgcache.qq.com/bossweb/ta/pic/company_logo/c_logo1.jpg"></a>
	<a href="#"><img src="http://imgcache.qq.com/bossweb/ta/pic/company_logo/c_logo2.jpg"></a>
	<a href="#"><img src="http://imgcache.qq.com/bossweb/ta/pic/company_logo/c_logo1.jpg"></a>
	<a href="#"><img src="http://imgcache.qq.com/bossweb/ta/pic/company_logo/c_logo2.jpg"></a>
	<a href="#"><img src="http://imgcache.qq.com/bossweb/ta/pic/company_logo/c_logo1.jpg"></a>
	<a href="#"><img src="http://imgcache.qq.com/bossweb/ta/pic/company_logo/c_logo2.jpg"></a>
</div>	
*/

function ImgSlide(options){	
    var defualt ={
        pageSize:1,
        slices:[],
        count:10,
        step:50,
        interval:5,
        autoDirect:false,
        autoSlide:false,
        container:'',
        aHrefs:[],
        aClick:false,
        aClasses:[],
        isNoImg:false,
        imgSrcs:[],
        imgSize:{width:0,height:0},
        prev:'',
        next:'',
        enable:true,
        sliceWinArr:[],
        linkPattern: /\{(\d+)-(\d+)\}/i
     };
	this['options'] = this.extend(defualt, options);
	this.init();		
};
ImgSlide.$ = function(i){
	return document.getElementById(i);
};
ImgSlide.prototype = {
	extend : function(){
		var args = arguments;
		var result = {};
		for(var i = 0; i < args.length; i++){
			var obj = args[i];
			for(var name in obj){
				result[name] = obj[name];
			}
		}
		return result;
	},	
	init : function(){
		var opts = this.options;
		var _container = this.getContainer();
		
		if(!opts.enable){
			return;
		}
		
		if(opts.prev != '' && opts.next != ''){
			this.prevObj = ImgSlide.$(opts.prev);
			this.nextObj = ImgSlide.$(opts.next);
			var _this = this;
			if(document.addEventListener){
				this.prevObj.addEventListener('click', function(){
					_this.prev();
				});
				this.nextObj.addEventListener('click', function(){				
					_this.next();
				});
			}else if(document.attachEvent){
				this.prevObj.attachEvent('onclick', function(){
					_this.prev();
				});
				this.nextObj.attachEvent('onclick', function(){
					_this.next()
				});
			}else{
				this.prevObj.onclick = function(){
					_this.prev();
				};
				this.nextObj.onclick = function(){
					_this.next()
				};
			}
		}
		this.container = _container;
		
		var _hrefs = [];
		var _srcs = [];
		if('string' == typeof opts.aHrefs ){
			var baseHref = opts.aHrefs;
			this.createLinkByPattern(baseHref, _hrefs);
		}else{
			_hrefs = opts.aHrefs;
		}
		
        if(!opts.isNoImg){
            if('string' == typeof opts.imgSrcs){
                var baseSrc = opts.imgSrcs;
                this.createLinkByPattern(baseSrc, _srcs, true);
            }else{
                _srcs = opts.imgSrcs;
            }		
            if(!opts.enable){
                return;
            }	
        }
        if(opts.aClasses){
            if('string' == typeof opts.aClasses){
                var baseClass = opts.aClasses;
                var _classes = [];
                this.createLinkByPattern(baseClass, _classes, true);
            }else{
                _srcs = opts.imgSrcs;
            }		
            if(!opts.enable){
                return;
            }	

        }
		
		var left = 0;
        var len = Math.max(_srcs.length, _hrefs.length, _classes.length);
		for(var i = 0; i < len; i++){
			var a = this.createImg(opts.isNoImg ? false : srcs[i], _hrefs[i] ? _hrefs[i] : _hrefs[_hrefs.length], opts.size, _classes ? _classes[i] : false);	
			opts.slices[opts.slices.length] = a;
			a.style.position = 'absolute';			
			a.style.float = 'none';	
			if(i < opts.pageSize){
				_container.appendChild(a);
				a.style.left = left + 'px';
				left += a.scrollWidth || a.clientWidth;
			}
		}
		this.containerWidth = left;
		this.current = {leftSlideIndex:0, rightSlideIndex:opts.pageSize - 1};
		this.direct = 0;
		this.slideQueue = [];
		var  winArr = opts.slices.slice(0, opts.pageSize);
		this.sliceWinArr = [];
		for(var i = 0; i < winArr.length; i++){
			this.sliceWinArr[this.sliceWinArr.length] = {img: winArr[i], index: i};
		}
				
	},
	createLinkByPattern:function(baseLink, result, error){
		var pattern = this.options.linkPattern || /\{(\d+)-(\d+)\}/i;		
		var arr = pattern.exec(baseLink)
		if(arr){
			var  start = parseInt(arr[1]);
			var end = parseInt(arr[2]);
			if(start >= end){
				var temp = start;
				start = end;
				end = temp;
			}
			for(var i = start; i <= end; i++){
				result[result.length] = baseLink.replace(pattern, i);
			}
		}else if(error){
			this.options.enable = false;
			this.options.error = 'link address format error'; 
		}else{
            result[result.length] =  baseLink;
        }
	},		
	getContainer : function(){
		var _container = !this.options.container || this.options.container == '' || ImgSlide.$(this.options.container);
		if (!_container) {
			this.options.enable = false;
			this.options.error = 'container not find'; 
			return null
		}
		return _container;
	},	
	prev : function(index){	
		if(this.direct != 0){
			this.slideQueue[this.slideQueue.length] = -1;
			return;
		}	
		var _slices = this.options.slices;		
		var _current = index ? this.current : this.getEntrancePosition(-1);
		var inObj = _slices[_current.leftSlideIndex];
		this.container.insertBefore(inObj, this.container.firstChild);		
		var inObjWidth = inObj.scrollWidth || inObj.clientWidth || this.options.imgWidth;
		inObj.style.left = -1 * inObjWidth + 'px';
		this.sliceWinArr.unshift({
			img: inObj,
			index: _current.leftSlideIndex
		});
		this.direct = -1;
		var _this = this;
		this.move(inObjWidth, function(){
			var outObj = _this.sliceWinArr.pop().img;
			outObj.parentNode.removeChild(outObj);
		});
        return true;
	},
	next : function(index){
		if(this.direct != 0){
			this.slideQueue[this.slideQueue.length] = 1;
			return;
		}
		var _slices = this.options.slices;		
		var _current = index ? this.current : this.getEntrancePosition(1);
		var inObj = _slices[_current.rightSlideIndex];
		this.container.appendChild(inObj);
		var inObjWidth = inObj.scrollWidth || inObj.clientWidth || this.options.imgWidth;
		inObj.style.left =this.containerWidth + 'px';
		this.sliceWinArr.push({
			img: inObj,
			index: _current.rightSlideIndex
		});
		this.direct = 1;
		var _this = this;
		this.move(inObjWidth, function(){
			var outObj = _this.sliceWinArr.shift().img;
			outObj.parentNode.removeChild(outObj);			
		});
        return true;
	},
	stop : function(){
		
	},
	start : function(){
		
	},
	move : function(objWidth, callback){
		var opts = this.options;		
		var speed = parseInt(objWidth /(opts.interval * 50)) || 10;
		var increaseWidth = 0;
		var flag = true;
		var increase = -1 * this.direct * speed;
		var _this = this;
		var loop = setInterval(function(){			
			increaseWidth += increase;
			if(Math.abs(increaseWidth) >= objWidth){
				increase = (objWidth - Math.abs(increaseWidth - increase)) * -1 * _this.direct ;
				flag = false;
			}
			for (var i = _this.sliceWinArr.length - 1; i >= 0; i--) {
				var obj = _this.sliceWinArr[i].img;
				var objLeft = parseInt(obj.style.left);
				obj.style.left = objLeft + increase + 'px';
			}
			
			if(!flag){
				_this.moveEnd();
				clearInterval(loop);
				callback();								
			}
		}, 1);		
	},
	moveEnd : function(){
		this.direct = 0;
		if(this.slideQueue.length > 0){
			var _direct = this.slideQueue.shift();
			_direct > 0 && this.next();
			_direct < 0 && this.prev();
		}	
	},
	autoSlide : function(callBack){
		var _this = this;
		var loop = setTimeout(function(){
            var _current = _this.current;
			if(_this.options.autoDirect == 'prev'){
                _this.prev();
            }else if(_this.options.autoDirect == 'next'){
                _this.next();
            }else if(_this.options.autoDirect == 'auto, next'){
				if(_current.rightSlideIndex == (_this.options.count - 1)){
                    _this.prev();
                    _this.options.autoDirect = 'auto, prev';
                }else{
                    _this.next();
                }
            }else if(_this.options.autoDirect == 'auto, prev'){
				if(_current.leftSlideIndex == 0){
                    _this.next();
                    _this.options.autoDirect = 'auto, next';
                }else{
                    _this.prev();
                }
            }else if(_this.options.autoDirect){
				if(_current.leftSlideIndex == 0){
                    _this.next();
                    _this.options.autoDirect = 'auto, next';
                }else if(_current.rightSlideIndex == (_this.options.count - 1)){
                    _this.prev();
                    _this.options.autoDirect = 'auto, prev';
                }
			}	
			setTimeout(arguments.callee, _this.options.autoInterval * 1000);		
		}, _this.options.autoInterval * 1000);		
	},
	slideIndex : function(index){
		var _slices = this.options.slices;
		if(index < 0 || index >= _slices.length){
			return;
		}
		var obj = _slices[index];
		if(index < 0 || index >= this.options.count)return;
		var middle = parseInt(this.opts.count / 2);
		if(index >= middle){
			this.current.rightSlideIndex = this.current.leftSlideIndex = index;
			this.next(index);
		}else{
			this.current.rightSlideIndex = this.current.leftSlideIndex = index;
			this.prev(index);
		}
		
	},
	getEntrancePosition : function(direct){
		var current = this.current;
		var opts = this.options;
		if(direct > 0){			
			current.leftSlideIndex = current.leftSlideIndex == (opts.count - 1) ? 0 : current.leftSlideIndex + 1;	
			current.rightSlideIndex = current.rightSlideIndex == (opts.count - 1) ? 0 : current.rightSlideIndex + 1;			
		}else if(direct < 0){			
			current.leftSlideIndex = current.leftSlideIndex == 0 ? opts.count - 1 : current.leftSlideIndex -1;
			current.rightSlideIndex = current.rightSlideIndex == 0 ? opts.count - 1 : current.rightSlideIndex -1;
		}
		
		return current;
	},
	createImg : function(src, href, imgSize, aClass){
		var opts = this.options;
		var a = document.createElement('a');
		var _href = href || '#self';
        if(src){
            var img = document.createElement('img');
            var _src = src || '#self';
            img.setAttribute('src', _src);
            imgSize && imgSise.width && img.setAttribute('width', imgSise.width + 'px');
            imgSize && imgSize.height && img.setAttribute('height', imgSize.height + 'px');
            a.appendChild(img);
            opts.imgClass && img.setAttribute('class', opts.imgClass);
        }
		if(aClass) a.className = aClass;
		a.setAttribute('href', _href);
        a.setAttribute('hidefocus', true);
        if(typeof opts.aClick == 'function'){
            document.attachEvent && a.attachEvent('onclick', opts.aClick);
            document.addEventListener && a.addEventListener('click', opts.aClick);
        }
		
		return a;
	}	
}


