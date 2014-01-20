(function($){$.fn.lazyload=function(options){var settings={loadImg:{on:false,width:0,height:0,loader:''},threshold:0,failurelimit:0,event:"scroll",effect:"show",onerr:function(self,settings){},container:window};if(options){$.extend(settings,options);}
var elements=this;if("scroll"==settings.event){$(settings.container).bind("scroll",function(event){var counter=0;elements.each(function(){if($.abovethetop(this,settings)||$.leftofbegin(this,settings)){}else if(!$.belowthefold(this,settings)&&!$.rightoffold(this,settings)){$(this).trigger("appear");}else{if(counter++>settings.failurelimit){return false;}}});var temp=$.grep(elements,function(element){return!element.loaded;});elements=$(temp);});}
this.each(function(){var self=this;if(undefined==$(self).attr("original")){return ;}
if("scroll"!=settings.event||undefined==$(self).attr("src")||settings.placeholder==$(self).attr("src")||($.abovethetop(self,settings)||$.leftofbegin(self,settings)||$.belowthefold(self,settings)||$.rightoffold(self,settings))){if(settings.placeholder){$(self).attr("src",settings.placeholder);}else{$(self).removeAttr("src");}
self.loaded=false;}else{$(self).attr("src",$(self).attr("original"));settings.loadImg.on&&$(self).LoadImage_min(settings.loadImg.on,settings.loadImg.width,settings.loadImg.height,settings.loadImg.loader);self.loaded=true;}
$(self).one("appear",function(){if(!this.loaded){$("<img />").bind('error',function(){settings.onerr($(self),settings);}).bind("load",function(){$(self).hide().attr("src",$(self).attr("original"))[settings.effect](settings.effectspeed);settings.loadImg.on&&$(self).LoadImage_min(settings.loadImg.on,settings.loadImg.width,settings.loadImg.height,settings.loadImg.loader);self.loaded=true;}).attr("src",$(self).attr("original"));};});if("scroll"!=settings.event){$(self).bind(settings.event,function(event){if(!self.loaded){$(self).trigger("appear");}});}});$(settings.container).trigger(settings.event);return this;};$.belowthefold=function(element,settings){if(settings.container===undefined||settings.container===window){var fold=$(window).height()+$(window).scrollTop();}else{var fold=$(settings.container).offset().top+$(settings.container).height();}
return fold<=$(element).offset().top-settings.threshold;};$.rightoffold=function(element,settings){if(settings.container===undefined||settings.container===window){var fold=$(window).width()+$(window).scrollLeft();}else{var fold=$(settings.container).offset().left+$(settings.container).width();}
return fold<=$(element).offset().left-settings.threshold;};$.abovethetop=function(element,settings){if(settings.container===undefined||settings.container===window){var fold=$(window).scrollTop();}else{var fold=$(settings.container).offset().top;}
return fold>=$(element).offset().top+settings.threshold+$(element).height();};$.leftofbegin=function(element,settings){if(settings.container===undefined||settings.container===window){var fold=$(window).scrollLeft();}else{var fold=$(settings.container).offset().left;}
return fold>=$(element).offset().left+settings.threshold+$(element).width();};$.extend($.expr[':'],{"below-the-fold":"$.belowthefold(a, {threshold : 0, container: window})","above-the-fold":"!$.belowthefold(a, {threshold : 0, container: window})","right-of-fold":"$.rightoffold(a, {threshold : 0, container: window})","left-of-fold":"!$.rightoffold(a, {threshold : 0, container: window})"});})(jQuery);jQuery.fn.LoadImage_min=function(scaling,width,height,loadpic){if(loadpic==null)loadpic="load3.gif";return this.each(function(){var t=$(this);var src=$(this).attr("src")
var img=new Image();img.src=src;var autoScaling=function(){if(scaling){if(img.width>0&&img.height>0){if(img.width/img.height>=width/height){if(img.width>width){t.width(width);t.height(Math.ceil((img.height*width)/img.width));}else{t.width(img.width);t.height(img.height);}}
else{if(img.height>height){t.height(height);t.width(Math.ceil((img.width*height)/img.height));}else{t.width(img.width);t.height(img.height);}}}}
t.css({marginLeft:(width-t.width())*0.5,marginTop:(height-t.height())*0.5,marginRight:(width-t.width())*0.5,marginBottom:(height-t.height())*0.5});}
if(img.complete){autoScaling();return;}
$(this).attr("src","");var loading=$("<div style='display:inline-block;background:url("+loadpic+") center no-repeat;width:"+width+"px;height:"+height+"px;' alt='图片加载中' title='图片加载中'></div>");t.hide();t.after(loading);$(img).load(function(){autoScaling();loading.remove();t.attr("src",this.src);t.show();});});}