/**
* jQuery ligerUI 1.1.9
* 
* http://ligerui.com
*  
* Author daomi 2012 [ gd_star@163.com ] 
* 
*/(function(a){function c(c){for(var d in c)a("<img />").attr("src",b.DialogImagePath+c[d])}var b=a.ligerui;a(".l-dialog-btn").live("mouseover",function(){a(this).addClass("l-dialog-btn-over")}).live("mouseout",function(){a(this).removeClass("l-dialog-btn-over")}),a(".l-dialog-tc .l-dialog-close").live("mouseover",function(){a(this).addClass("l-dialog-close-over")}).live("mouseout",function(){a(this).removeClass("l-dialog-close-over")}),a.ligerDialog=function(){return b.run.call(null,"ligerDialog",arguments,{isStatic:!0})},a.ligerui.DialogImagePath="../../lib/ligerUI/skins/Aqua/images/win/",a.ligerDefaults.Dialog={cls:null,id:null,buttons:null,isDrag:!0,width:280,height:null,content:"",target:null,url:null,load:!1,onLoaded:null,type:"none",left:null,top:null,modal:!0,name:null,isResize:!1,allowClose:!0,opener:null,timeParmName:null,closeWhenEnter:null,isHidden:!0,show:!0,title:"提示",showMax:!1,showToggle:!1,showMin:!1,slide:a.browser.msie?!1:!0,fixedType:null,showType:null},a.ligerDefaults.DialogString={titleMessage:"提示",ok:"确定",yes:"是",no:"否",cancel:"取消",waittingMessage:"正在等待中,请稍候..."},a.ligerMethos.Dialog=a.ligerMethos.Dialog||{},b.controls.Dialog=function(a){b.controls.Dialog.base.constructor.call(this,null,a)},b.controls.Dialog.ligerExtend(b.core.Win,{__getType:function(){return"Dialog"},__idPrev:function(){return"Dialog"},_extendMethods:function(){return a.ligerMethos.Dialog},_render:function(){var c=this,d=this.options;c.set(d,!0);var e=a('<div class="l-dialog"><table class="l-dialog-table" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td class="l-dialog-tl"></td><td class="l-dialog-tc"><div class="l-dialog-tc-inner"><div class="l-dialog-icon"></div><div class="l-dialog-title"></div><div class="l-dialog-winbtns"><div class="l-dialog-winbtn l-dialog-close"></div></div></div></td><td class="l-dialog-tr"></td></tr><tr><td class="l-dialog-cl"></td><td class="l-dialog-cc"><div class="l-dialog-body"><div class="l-dialog-image"></div> <div class="l-dialog-content"></div><div class="l-dialog-buttons"><div class="l-dialog-buttons-inner"></div></td><td class="l-dialog-cr"></td></tr><tr><td class="l-dialog-bl"></td><td class="l-dialog-bc"></td><td class="l-dialog-br"></td></tr></tbody></table></div>');a("body").append(e),c.dialog=e,c.element=e[0],c.dialog.body=a(".l-dialog-body:first",c.dialog),c.dialog.header=a(".l-dialog-tc-inner:first",c.dialog),c.dialog.winbtns=a(".l-dialog-winbtns:first",c.dialog.header),c.dialog.buttons=a(".l-dialog-buttons:first",c.dialog),c.dialog.content=a(".l-dialog-content:first",c.dialog),c.set(d,!1),d.allowClose==!1&&a(".l-dialog-close",c.dialog).remove();if(d.target||d.url||d.type=="none")d.type=null,c.dialog.addClass("l-dialog-win");d.cls&&c.dialog.addClass(d.cls),d.id&&c.dialog.attr("id",d.id),c.mask(),d.isDrag&&c._applyDrag(),d.isResize&&c._applyResize(),d.type?c._setImage():(a(".l-dialog-image",c.dialog).remove(),c.dialog.content.addClass("l-dialog-content-noimage")),d.show||(c.unmask(),c.dialog.hide());if(d.target)c.dialog.content.prepend(d.target),a(d.target).show();else if(d.url){d.timeParmName&&(d.url+=d.url.indexOf("?")==-1?"?":"&",d.url+=d.timeParmName+"="+(new Date).getTime());if(d.load)c.dialog.body.load(d.url,function(){c._saveStatus(),c.trigger("loaded")});else{c.jiframe=a("<iframe frameborder='0'></iframe>");var f=d.name?d.name:"ligerwindow"+(new Date).getTime();c.jiframe.attr("name",f),c.jiframe.attr("id",f),c.dialog.content.prepend(c.jiframe),c.dialog.content.addClass("l-dialog-content-nopadding"),setTimeout(function(){c.jiframe.attr("src",d.url),c.frame=window.frames[c.jiframe.attr("name")]},0)}}d.opener&&(c.dialog.opener=d.opener),d.buttons?a(d.buttons).each(function(b,d){var e=a('<div class="l-dialog-btn"><div class="l-dialog-btn-l"></div><div class="l-dialog-btn-r"></div><div class="l-dialog-btn-inner"></div></div>');a(".l-dialog-btn-inner",e).html(d.text),a(".l-dialog-buttons-inner",c.dialog.buttons).prepend(e),d.width&&e.width(d.width),d.onclick&&e.click(function(){d.onclick(d,c,b)})}):c.dialog.buttons.remove(),a(".l-dialog-buttons-inner",c.dialog.buttons).append("<div class='l-clear'></div>"),a(".l-dialog-title",c.dialog).bind("selectstart",function(){return!1}),c.dialog.click(function(){b.win.setFront(c)}),a(".l-dialog-tc .l-dialog-close",c.dialog).click(function(){d.isHidden?c.hide():c.close()});if(!d.fixedType){var g=0,h=0,i=d.width||c.dialog.width();d.slide==!0&&(d.slide="fast"),d.left!=null?g=d.left:d.left=g=.5*(a(window).width()-i),d.top!=null?h=d.top:d.top=h=.5*(a(window).height()-c.dialog.height())+a(window).scrollTop()-10,g<0&&(d.left=g=0),h<0&&(d.top=h=0),c.dialog.css({left:g,top:h})}c.show(),a("body").bind("keydown.dialog",function(a){var b=a.which;b==13?c.enter():b==27&&c.esc()}),c._updateBtnsWidth(),c._saveStatus(),c._onReisze()},_borderX:12,_borderY:32,doMax:function(c){var d=this,e=this.options,f=a(window).width(),g=a(window).height(),h=0,i=0;b.win.taskbar&&(g-=b.win.taskbar.outerHeight(),b.win.top&&(i+=b.win.taskbar.outerHeight())),c?(d.dialog.body.animate({width:f-d._borderX},e.slide),d.dialog.animate({left:h,top:i},e.slide),d.dialog.content.animate({height:g-d._borderY-d.dialog.buttons.outerHeight()},e.slide,function(){d._onReisze()})):(d.set({width:f,height:g,left:h,top:i}),d._onReisze())},max:function(){var b=this,c=this.options;b.winmax&&(b.winmax.addClass("l-dialog-recover"),b.doMax(c.slide),b.wintoggle&&(b.wintoggle.hasClass("l-dialog-extend")?b.wintoggle.addClass("l-dialog-toggle-disabled l-dialog-extend-disabled"):b.wintoggle.addClass("l-dialog-toggle-disabled l-dialog-collapse-disabled")),b.resizable&&b.resizable.set({disabled:!0}),b.draggable&&b.draggable.set({disabled:!0}),b.maximum=!0,a(window).bind("resize.dialogmax",function(){b.doMax(!1)}))},recover:function(){var b=this,c=this.options;b.winmax&&(b.winmax.removeClass("l-dialog-recover"),c.slide?(b.dialog.body.animate({width:b._width-b._borderX},c.slide),b.dialog.animate({left:b._left,top:b._top},c.slide),b.dialog.content.animate({height:b._height-b._borderY-b.dialog.buttons.outerHeight()},c.slide,function(){b._onReisze()})):(b.set({width:b._width,height:b._height,left:b._left,top:b._top}),b._onReisze()),b.wintoggle&&b.wintoggle.removeClass("l-dialog-toggle-disabled l-dialog-extend-disabled l-dialog-collapse-disabled"),a(window).unbind("resize.dialogmax")),this.resizable&&this.resizable.set({disabled:!1}),b.draggable&&b.draggable.set({disabled:!1}),b.maximum=!1},min:function(){var a=this,c=this.options,d=b.win.getTask(this);c.slide?(a.dialog.body.animate({width:1},c.slide),d.y=d.offset().top+d.height(),d.x=d.offset().left+d.width()/2,a.dialog.animate({left:d.x,top:d.y},c.slide,function(){a.dialog.hide()})):a.dialog.hide(),a.unmask(),a.minimize=!0,a.actived=!1},active:function(){var c=this,d=this.options;if(c.minimize){var e=c._width,f=c._height,g=c._left,h=c._top;c.maximum&&(e=a(window).width(),f=a(window).height(),g=h=0,b.win.taskbar&&(f-=b.win.taskbar.outerHeight(),b.win.top&&(h+=b.win.taskbar.outerHeight()))),d.slide?(c.dialog.body.animate({width:e-c._borderX},d.slide),c.dialog.animate({left:g,top:h},d.slide)):c.set({width:e,height:f,left:g,top:h})}c.actived=!0,c.minimize=!1,b.win.setFront(c),c.show()},toggle:function(){var a=this,b=this.options;!a.wintoggle||(a.wintoggle.hasClass("l-dialog-extend")?a.extend():a.collapse())},collapse:function(){var a=this,b=this.options;!a.wintoggle||(b.slide?a.dialog.content.animate({height:1},b.slide):a.dialog.content.height(1),this.resizable&&this.resizable.set({disabled:!0}))},extend:function(){var a=this,b=this.options;if(!!a.wintoggle){var c=a._height-a._borderY-a.dialog.buttons.outerHeight();b.slide?a.dialog.content.animate({height:c},b.slide):a.dialog.content.height(c),this.resizable&&this.resizable.set({disabled:!1})}},_updateBtnsWidth:function(){var b=this,c=a(">div",b.dialog.winbtns).length;b.dialog.winbtns.width(22*c)},_setLeft:function(a){!this.dialog||a!=null&&this.dialog.css({left:a})},_setTop:function(a){!this.dialog||a!=null&&this.dialog.css({top:a})},_setWidth:function(a){!this.dialog||a>=this._borderX&&this.dialog.body.width(a-this._borderX)},_setHeight:function(a){var b=this,c=this.options;if(!!this.dialog&&a>=this._borderY){var d=a-this._borderY-b.dialog.buttons.outerHeight();b.dialog.content.height(d)}},_setShowMax:function(b){var c=this,d=this.options;b?c.winmax||(c.winmax=a('<div class="l-dialog-winbtn l-dialog-max"></div>').appendTo(c.dialog.winbtns).hover(function(){a(this).hasClass("l-dialog-recover")?a(this).addClass("l-dialog-recover-over"):a(this).addClass("l-dialog-max-over")},function(){a(this).removeClass("l-dialog-max-over l-dialog-recover-over")}).click(function(){a(this).hasClass("l-dialog-recover")?c.recover():c.max()})):c.winmax&&(c.winmax.remove(),c.winmax=null),c._updateBtnsWidth()},_setShowMin:function(c){var d=this,e=this.options;c?d.winmin||(d.winmin=a('<div class="l-dialog-winbtn l-dialog-min"></div>').appendTo(d.dialog.winbtns).hover(function(){a(this).addClass("l-dialog-min-over")},function(){a(this).removeClass("l-dialog-min-over")}).click(function(){d.min()}),b.win.addTask(d)):d.winmin&&(d.winmin.remove(),d.winmin=null),d._updateBtnsWidth()},_setShowToggle:function(b){var c=this,d=this.options;b?c.wintoggle||(c.wintoggle=a('<div class="l-dialog-winbtn l-dialog-collapse"></div>').appendTo(c.dialog.winbtns).hover(function(){a(this).hasClass("l-dialog-toggle-disabled")||(a(this).hasClass("l-dialog-extend")?a(this).addClass("l-dialog-extend-over"):a(this).addClass("l-dialog-collapse-over"))},function(){a(this).removeClass("l-dialog-extend-over l-dialog-collapse-over")}).click(function(){if(!a(this).hasClass("l-dialog-toggle-disabled"))if(c.wintoggle.hasClass("l-dialog-extend")){if(c.trigger("extend")==!1)return;c.wintoggle.removeClass("l-dialog-extend"),c.extend(),c.trigger("extended")}else{if(c.trigger("collapse")==!1)return;c.wintoggle.addClass("l-dialog-extend"),c.collapse(),c.trigger("collapseed")}})):c.wintoggle&&(c.wintoggle.remove(),c.wintoggle=null)},enter:function(){var a=this,b=this.options,c;if(b.closeWhenEnter!=undefined)c=b.closeWhenEnter;else if(b.type=="warn"||b.type=="error"||b.type=="success"||b.type=="question")c=!0;c&&a.close()},esc:function(){},_removeDialog:function(){var a=this,b=this.options;b.showType&&b.fixedType?a.dialog.animate({bottom:-1*b.height},function(){a.dialog.remove()}):a.dialog.remove()},close:function(){var c=this,d=this.options;b.win.removeTask(this),c.unmask(),c._removeDialog(),a("body").unbind("keydown.dialog")},_getVisible:function(){return this.dialog.is(":visible")},_setUrl:function(a){var b=this,c=this.options;c.url=a,c.load?b.dialog.body.html("").load(c.url,function(){b.trigger("loaded")}):b.jiframe&&b.jiframe.attr("src",c.url)},_setContent:function(a){this.dialog.content.html(a)},_setTitle:function(b){var c=this,d=this.options;b&&a(".l-dialog-title",c.dialog).html(b)},_hideDialog:function(){var a=this,b=this.options;b.showType&&b.fixedType?a.dialog.animate({bottom:-1*b.height},function(){a.dialog.hide()}):a.dialog.hide()},hidden:function(){var a=this;b.win.removeTask(a),a.dialog.hide(),a.unmask()},show:function(){var b=this,c=this.options;b.mask(),c.fixedType?c.showType?(b.dialog.css({bottom:-1*c.height}).addClass("l-dialog-fixed"),b.dialog.show().animate({bottom:0})):b.dialog.show().css({bottom:0}):b.dialog.show(),a.ligerui.win.setFront.ligerDefer(a.ligerui.win,100,[b])},setUrl:function(a){this._setUrl(a)},_saveStatus:function(){var a=this;a._width=a.dialog.body.width(),a._height=a.dialog.body.height();var b=0,c=0;isNaN(parseInt(a.dialog.css("top")))||(b=parseInt(a.dialog.css("top"))),isNaN(parseInt(a.dialog.css("left")))||(c=parseInt(a.dialog.css("left"))),a._top=b,a._left=c},_applyDrag:function(){var c=this,d=this.options;a.fn.ligerDrag&&(c.draggable=c.dialog.ligerDrag({handler:".l-dialog-title",animate:!1,onStartDrag:function(){b.win.setFront(c)},onStopDrag:function(){if(d.target){var e=b.find(a.ligerui.controls.DateEditor),f=b.find(a.ligerui.controls.ComboBox);a(a.merge(e,f)).each(function(){this.updateSelectBoxPosition&&this.updateSelectBoxPosition()})}c._saveStatus()}}))},_onReisze:function(){var b=this,c=this.options;if(c.target){var d=a(c.target).liger();d||(d=a(c.target).find(":first").liger());if(!d)return;var e=b.dialog.content.height(),f=b.dialog.content.width();d.trigger("resize",[{width:f,height:e}])}},_applyResize:function(){var b=this,c=this.options;a.fn.ligerResizable&&(b.resizable=b.dialog.ligerResizable({onStopResize:function(a,c){var d=0,e=0;isNaN(parseInt(b.dialog.css("top")))||(d=parseInt(b.dialog.css("top"))),isNaN(parseInt(b.dialog.css("left")))||(e=parseInt(b.dialog.css("left"))),a.diffLeft&&b.set({left:e+a.diffLeft}),a.diffTop&&b.set({top:d+a.diffTop}),a.newWidth&&(b.set({width:a.newWidth}),b.dialog.body.css({width:a.newWidth-b._borderX})),a.newHeight&&b.set({height:a.newHeight}),b._onReisze(),b._saveStatus();return!1},animate:!1}))},_setImage:function(){var b=this,c=this.options;c.type&&(c.type=="success"||c.type=="donne"||c.type=="ok"?(a(".l-dialog-image",b.dialog).addClass("l-dialog-image-donne").show(),b.dialog.content.css({paddingLeft:64,paddingBottom:30})):c.type=="error"?(a(".l-dialog-image",b.dialog).addClass("l-dialog-image-error").show(),b.dialog.content.css({paddingLeft:64,paddingBottom:30})):c.type=="warn"?(a(".l-dialog-image",b.dialog).addClass("l-dialog-image-warn").show(),b.dialog.content.css({paddingLeft:64,paddingBottom:30})):c.type=="question"&&(a(".l-dialog-image",b.dialog).addClass("l-dialog-image-question").show(),b.dialog.content.css({paddingLeft:64,paddingBottom:40})))}}),b.controls.Dialog.prototype.hide=b.controls.Dialog.prototype.hidden,a.ligerDialog.open=function(b){return a.ligerDialog(b)},a.ligerDialog.close=function(){var a=b.find(b.controls.Dialog.prototype.__getType());for(var c in a){var d=a[c];d.destroy.ligerDefer(d,5)}b.win.unmask()},a.ligerDialog.show=function(c){var d=b.find(b.controls.Dialog.prototype.__getType());if(d.length)for(var e in d){d[e].show();return}return a.ligerDialog(c)},a.ligerDialog.hide=function(){var a=b.find(b.controls.Dialog.prototype.__getType());for(var c in a){var d=a[c];d.hide()}},a.ligerDialog.tip=function(b){b=a.extend({showType:"slide",width:240,modal:!1,height:100},b||{}),a.extend(b,{fixedType:"se",type:"none",isDrag:!1,isResize:!1,showMax:!1,showToggle:!1,showMin:!1});return a.ligerDialog.open(b)},a.ligerDialog.alert=function(b,c,d,e){b=b||"",typeof c=="function"?(e=c,d=null):typeof d=="function"&&(e=d);var f=function(a,b,c){b.close(),e&&e(a,b,c)};p={content:b,buttons:[{text:a.ligerDefaults.DialogString.ok,onclick:f}]},typeof c=="string"&&c!=""&&(p.title=c),typeof d=="string"&&d!=""&&(p.type=d),a.extend(p,{showMax:!1,showToggle:!1,showMin:!1});return a.ligerDialog(p)},a.ligerDialog.confirm=function(b,c,d){typeof c=="function"&&(d=c,type=null);var e=function(a,b){b.close(),d&&d(a.type=="ok")};p={type:"question",content:b,buttons:[{text:a.ligerDefaults.DialogString.yes,onclick:e,type:"ok"},{text:a.ligerDefaults.DialogString.no,onclick:e,type:"no"}]},typeof c=="string"&&c!=""&&(p.title=c),a.extend(p,{showMax:!1,showToggle:!1,showMin:!1});return a.ligerDialog(p)},a.ligerDialog.warning=function(b,c,d){typeof c=="function"&&(d=c,type=null);var e=function(a,b){b.close(),d&&d(a.type)};p={type:"question",content:b,buttons:[{text:a.ligerDefaults.DialogString.yes,onclick:e,type:"yes"},{text:a.ligerDefaults.DialogString.no,onclick:e,type:"no"},{text:a.ligerDefaults.DialogString.cancel,onclick:e,type:"cancel"}]},typeof c=="string"&&c!=""&&(p.title=c),a.extend(p,{showMax:!1,showToggle:!1,showMin:!1});return a.ligerDialog(p)},a.ligerDialog.waitting=function(b){b=b||a.ligerDefaults.Dialog.waittingMessage;return a.ligerDialog.open({cls:"l-dialog-waittingdialog",type:"none",content:'<div style="padding:4px">'+b+"</div>",allowClose:!1})},a.ligerDialog.closeWaitting=function(){var a=b.find(b.controls.Dialog);for(var c in a){var d=a[c];d.dialog.hasClass("l-dialog-waittingdialog")&&d.close()}},a.ligerDialog.success=function(b,c,d){return a.ligerDialog.alert(b,c,"success",d)},a.ligerDialog.error=function(b,c,d){return a.ligerDialog.alert(b,c,"error",d)},a.ligerDialog.warn=function(b,c,d){return a.ligerDialog.alert(b,c,"warn",d)},a.ligerDialog.question=function(b,c){return a.ligerDialog.alert(b,c,"question")},a.ligerDialog.prompt=function(b,c,d,e){var f=a('<input type="text" class="l-dialog-inputtext"/>');typeof d=="function"&&(e=d),typeof c=="function"?e=c:typeof c=="boolean"&&(d=c),typeof d=="boolean"&&d&&(f=a('<textarea class="l-dialog-textarea"></textarea>')),(typeof c=="string"||typeof c=="int")&&f.val(c);var g=function(a,b,c){b.close(),e&&e(a.type=="yes",f.val())};p={title:b,target:f,width:320,buttons:[{text:a.ligerDefaults.DialogString.ok,onclick:g,type:"yes"},{text:a.ligerDefaults.DialogString.cancel,onclick:g,type:"cancel"}]};return a.ligerDialog(p)}})(jQuery)