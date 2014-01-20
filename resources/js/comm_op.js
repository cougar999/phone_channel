Date.prototype.format = function(format) //author: meizz
{
  var o = {
    "M+" : this.getMonth()+1, //month
    "d+" : this.getDate(),    //day
    "h+" : this.getHours(),   //hour
    "m+" : this.getMinutes(), //minute
    "s+" : this.getSeconds(), //second
    "q+" : Math.floor((this.getMonth()+3)/3),  //quarter
    "S" : this.getMilliseconds() //millisecond
  }
  if(/(y+)/.test(format)) format=format.replace(RegExp.$1,
    (this.getFullYear()+"").substr(4 - RegExp.$1.length));
  for(var k in o)if(new RegExp("("+ k +")").test(format))
    format = format.replace(RegExp.$1,
      RegExp.$1.length==1 ? o[k] :
        ("00"+ o[k]).substr((""+ o[k]).length));
  return format;
} 
function GetDateStr(AddDayCount,curday){
    var dd = parseDate(curday) || new Date();
    dd.setDate(dd.getDate()+AddDayCount);//获取AddDayCount天后的日期
    var y = dd.getFullYear();
    var m = dd.getMonth()+1;//获取当前月份的日期
    var d = dd.getDate();
    if(m<10){
        m = "0" + m;
    }
    if(d<10){
       d = "0" + d;
    } 
    return y+"-"+m+"-"+d;
}
function getLastDay(str)
{     
	var results = str.match(/^ *(\d{4})-(\d{1,2})-(\d{1,2}) *$/);
	var year = results[1];
	var month= results[2];
	 var new_year = year;    //取当前的年份     
	 var new_month = month++;//取下一个月的第一天，方便计算（最后一天不固定）     
	 if(month>12)            //如果当前大于12月，则年份转到下一年     
	 {     
	  new_month -=12;        //月份减     
	  new_year++;            //年份增     
	 }     
	 var new_date = new Date(new_year,new_month,1);                //取当年当月中的第一天     
	 return results[1]+'-'+results[2]+'-'+(new Date(new_date.getTime()-1000*60*60*24)).getDate();//获取当月最后一天日期     
}  
function parseDate(str){  
  if(typeof str == 'string'){  
    var results = str.match(/^ *(\d{4})-(\d{1,2})-(\d{1,2}) *$/);  
    if(results && results.length>3)  
    	return new Date(parseInt(results[1],10),parseInt(results[2],10) -1,parseInt(results[3],10));   
    results = str.match(/^ *(\d{4})-(\d{1,2})-(\d{1,2}) +(\d{1,2}):(\d{1,2}):(\d{1,2}) *$/);  
    if(results && results.length>6)  
      return new Date(parseInt(results[1],10),parseInt(results[2],10) -1,parseInt(results[3],10),parseInt(results[4],10),parseInt(results[5],10),parseInt(results[6],10));   
    results = str.match(/^ *(\d{4})-(\d{1,2})-(\d{1,2}) +(\d{1,2}):(\d{1,2}):(\d{1,2})\.(\d{1,9}) *$/);  
    if(results && results.length>7)  
      return new Date(parseInt(results[1],10),parseInt(results[2],10) -1,parseInt(results[3],10),parseInt(results[4],10),parseInt(results[5],10),parseInt(results[6],10),parseInt(results[7],10));   
  }  
  return null;  
}
function compareDate(DateOne,DateTwo)
{

var OneMonth = DateOne.substring(5,DateOne.lastIndexOf ("-"));
var OneDay = DateOne.substring(DateOne.length,DateOne.lastIndexOf ("-")+1);
var OneYear = DateOne.substring(0,DateOne.indexOf ("-"));

var TwoMonth = DateTwo.substring(5,DateTwo.lastIndexOf ("-"));
var TwoDay = DateTwo.substring(DateTwo.length,DateTwo.lastIndexOf ("-")+1);
var TwoYear = DateTwo.substring(0,DateTwo.indexOf ("-"));

if (Date.parse(OneMonth+"/"+OneDay+"/"+OneYear) >
Date.parse(TwoMonth+"/"+TwoDay+"/"+TwoYear))
{
return true;
}
else
{
return false;
}

}
function formatval(obj){
	var tmp = [];
	$.each(obj,function(k,v){
		tmp.push({name:k,value:v});
	});
	return tmp;
}
function addval(obj,add){
	if(!obj) return add;
	var obj = clone(obj);
	$.each(add,function(k,v){
		if(getval(obj,v.name) != null) obj = setval(obj,v.name,v.value);
		else obj[obj.length] = v;
	});
	return obj;
	//obj = obj.concat(add);
}
function setval(obj,name,val){
	$.each(obj,function(k,v){
		if(v.name == name) v.value = val;
	});
	return obj;
}
function getval(obj,item){
	var obj = obj || [{name:'type',value:'sketch'}];
	var val = null;
	$.each(obj,function(k,v){
		if(v.name == item){
			val = v.value;
			return false;
		}
	});
	return val;
}
function clone(obj) {
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
    objClone.toString = obj.toString;
    objClone.valueOf = obj.valueOf;
    return objClone;
}