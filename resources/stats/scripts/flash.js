/*
 TOWF Project By BOSS Web Group.
 */
var ad_pay = {};
ad_pay.show = function(data){
    ad_pay = new Flash(data);
    ad_pay.create();
}
var Flash = function(data){
    this.mId = data.id || "flash";
    this.mWidth = data.width || 400;
    this.mHeight = data.height || 300;
    this.showNum = data.num;
    this.mLinks = data.links || [];
    this.mImages = data.images || [];
    this.mFunc = data.callback;
    this.mSlide = data.slide;
    this.mAuto = data.auto;
    this.innerId = "flash_inner_div";
    this.perOffset = data.width;
    this.miniOffset = 20;
    this.count = this.mLinks.length || 0;
    this.indexId = 1;
    this.flag = true;
    this.stop = false;
    this.linkTarget = data.linkTarget || "_blank";
    this.interval = data.interval || 6000;
}
Flash.prototype = {
    create: function(data){
        var parentDiv = document.getElementById(this.mId);
        parentDiv.style.overflow = "hidden";
        parentDiv.style.width = this.mWidth * this.showNum + "px";
        parentDiv.style.height = this.mHeight + "px";
        parentDiv.style.position = "relative";
        var L = this.count;
        var arr = [];
        var _style = "height:" + this.mHeight + "px;width:" + (this.mWidth * L) + "px;position:relative;left:0px;padding:0px;";
        arr.push("<div id=\"" + this.innerId + "\" style=\"" + _style + "\">")
        for (var i = 0; i < L; i++) 
        {
            if (this.mLinks[i] != ''){
                arr.push("<a style='float:left' href='" + this.mLinks[i] + "' target='" + this.linkTarget + "'><img src='" + this.mImages[i] + "' border='0' width='" + this.mWidth + " height='" + this.mHeight + "' /></a>");
            }
            else{
                arr.push("<div style='float:left'><img src='" + this.mImages[i] + "' border='0' width='" + this.mWidth + " height='" + this.mHeight + "' /></div>");
            }
        }
        arr.push("</div>");
        parentDiv.innerHTML = arr.join("\n");
        this.move(1);
        var direc = 1;
        if (this.mAuto) 
        {
            var _self = this;
            var loop = setInterval(function(){
                if (_self.indexId == 1) 
                    direc = 1;
                else if (_self.indexId == _self.count) 
                    direc = -1;
                direc == 1 ? _self.left() : _self.right();
            }, this.interval);
        }
    },
    move: function(index){
        if (index < 1) 
            index = this.count - this.showNum + 1;
        else if (index > this.count - this.showNum + 1) 
            index = 1;
        _self = this;
        if (_self.flag == false || this.stop == true) 
            return false;
        _self.flag = false
        var div = document.getElementById(this.innerId);
        var curentPos = parseInt(div.style.left);
        var endPost = curentPos + (this.indexId - index) * this.perOffset;
        var _flag = (this.indexId - index) < 0 ? -1 : 1;
        var speed1 = this.miniOffset * _flag;
        var speed2 = parseInt(this.miniOffset / 2) * _flag;
        var speed3 = 2 * _flag;
        this.indexId = index;
        this.mFunc(this.indexId);
        var loop = setInterval(function(){
            var left = parseInt(div.style.left);
            if (Math.abs(left - endPost) < 5 || (!_self.mSlide)) 
            {
                div.style.left = endPost + "px";
                clearInterval(loop);
                _self.flag = true;
            } else if (Math.abs(left - endPost) < 20) 
            {
                div.style.left = left + speed3 + "px";
            } else if (Math.abs(left - endPost) < 60) 
            {
                div.style.left = left + speed2 + "px";
            } else 
            {
                div.style.left = left + speed1 + "px";
            }
        }, 1);
    },
    left: function(){
        this.move(this.indexId + 1);
    },
    right: function(){
        this.move(this.indexId - 1);
    }
}

