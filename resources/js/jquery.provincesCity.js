/**
 * jQuery :  城市联动插件
 * @author   XiaoDong <cssrain@gmail.com>
 *			 http://www.cssrain.cn
 * @example  $("#test").ProvinceCity();
 * @params   暂无
 */
$.fn.ProvinceCity = function(df1,df2,df3,df4){
	var _self = this;
	var v1 = typeof(df1) == 'undefined' ? '' : df1;
	var v2 = typeof(df2) == 'undefined' ? '' : df2;
	var v3 = typeof(df3) == 'undefined' ? '' : df3;
	var df4 = df4 || 0;
	var df_tmp_cf = ["请选择","全部"];
	function appendOptionTo($o,k,v,d){
		var $opt=$("<option>").text(k).val(v);
		if(v==d){$opt.attr("selected", "selected")}
		$opt.appendTo($o);
	}
	//定义3个默认值
	_self.data("province",[df_tmp_cf[df4], ""]);
	_self.data("city1",[df_tmp_cf[df4], ""]);
	_self.data("city2",[df_tmp_cf[df4], ""]);
	//插入3个空的下拉框
	_self.append("<select name='province' id='province'></select>");
	_self.append("<select name='city' id='city'></select>");
	_self.append("<select name='town' id='town'></select>");
	//分别获取3个下拉框
	var $sel1 = _self.find("select").eq(0);
	var $sel2 = _self.find("select").eq(1);
	var $sel3 = _self.find("select").eq(2);
	//默认省级下拉
	if(_self.data("province")){
		$sel1.append("<option value='"+_self.data("province")[1]+"'>"+_self.data("province")[0]+"</option>");
	}
	$.each( GP , function(index,data){
		appendOptionTo($sel1,data,data,v1);
		//$sel1.append("<option value='"+data+"'>"+data+"</option>");
	});
	//默认的1级城市下拉
	if(_self.data("city1")){
		$sel2.append("<option value='"+_self.data("city1")[1]+"'>"+_self.data("city1")[0]+"</option>");
	}
	//默认的2级城市下拉
	if(_self.data("city2")){
		$sel3.append("<option value='"+_self.data("city2")[1]+"'>"+_self.data("city2")[0]+"</option>");
	}
	//省级联动 控制
	var index1 = "" ;
	$sel1.change(function(){
		//清空其它2个下拉框
		$sel2[0].options.length=0;
		$sel3[0].options.length=0;
		index1 = this.selectedIndex;
		if(index1==0){	//当选择的为 “请选择” 时
			if(_self.data("city1")){
				$sel2.append("<option value='"+_self.data("city1")[1]+"'>"+_self.data("city1")[0]+"</option>");
			}
			if(_self.data("city2")){
				$sel3.append("<option value='"+_self.data("city2")[1]+"'>"+_self.data("city2")[0]+"</option>");
			}
		}else{
			$sel2.append("<option value='"+_self.data("city1")[1]+"'>"+_self.data("city1")[0]+"</option>");
			$.each( GT[index1-1] , function(index,data){
				appendOptionTo($sel2,data,data,v2);
				//$sel2.append("<option value='"+data+"'>"+data+"</option>");
			});
			$sel3.append("<option value='"+_self.data("city2")[1]+"'>"+_self.data("city2")[0]+"</option>");
//			$.each( GC[index1-1][0] , function(index,data){
//				$sel3.append("<option value='"+data+"'>"+data+"</option>");
//			})
		}
	}).change();
	//1级城市联动 控制
	var index2 = "" ;
	$sel2.change(function(){
		$sel3[0].options.length=0;
		index2 = this.selectedIndex-1;
	    if(index2=="-1"){$sel3.append("<option value='"+_self.data("city2")[1]+"'>"+_self.data("city2")[0]+"</option>");}else{
		$sel3.append("<option value='"+_self.data("city2")[1]+"'>"+_self.data("city2")[0]+"</option>");
		$.each( GC[index1-1][index2] , function(index,data){
			appendOptionTo($sel3,data,data,v3);
			//$sel3.append("<option value='"+data+"'>"+data+"</option>");
		})}
	});
	$sel2.val() && $sel2.change();
	return _self;
};