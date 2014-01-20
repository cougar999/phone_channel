/********************************************************************
使用方式如下
先引入js文件：
<script src="dateRange.js"></script>
在头部加入下面CSS：
.dateRangeCalendar {position:absolute;border:1px solid red;}
.dateRangePicker {float:left;}
.dateRangeOptions {float:left;}
.dateRangeOptions input.dateRangeInput {width:80px;text-align:center;}
.dateRangeDateTable {margin:0 0 0 5px;padding:0px;float:left;}
.dateRangeDateTable td {border-right:1px solid #AAA;border-bottom:1px solid #AAA;text-align:right;cursor:pointer;}
.dateRangeDateTable td.dateRangeGray {color:#BBB;cursor:default;}
.dateRangeDateTable td.dateRangeToday {color:#F90;font-weight:bold;}
.dateRangeSelected {background-color:#FF0000;}
.dateRangeCompare {background-color:#00FF00;}
.dateRangeCoincide {background-color:#FFFF00;}
.dateRangeNextMonth {padding:2px 5px;}
.dateRangePreMonth {padding:2px 5px;}

调用js进行实例化
<script>
// 有时间对比的情况
// date 为输入框的ID值
var dateRange = new pickerDateRange('date', {
    startDate : '2010-12-10', // startDate 开始时间
    endDate : '2011-01-12', // endDate 结束时间
    startCompareDate : '2010-11-20', // startCompareDate 比较开始时间
    endCompareDate : '2011-01-02', // endCompareDate 比较结束时间
    needCompare : true, // needCompare 比较开关;true 启用比较;false 不进行比较
    target : 'asdfasdf', // target 日期选择框摆放的位置, 一般是在 form 中
    isSingleDay : false // 强制只能选择一天
});

// 没有时间对比的情况
// date 为输入框的ID值
var dateRange = new pickerDateRange('date', {
    startDate : '2010-12-10', // startDate 开始时间
    endDate : '2011-01-12', // endDate 结束时间
    target : 'asdfasdf' // target 日期选择框摆放的位置, 一般是在 form 中
});
</script>
*************************************************************************/

function pickerDateRange(inputId, options) {
    // 默认参数
    var defaults = {
		aToday : 'aToday', //今天
		aYesterday : 'aYesterday', //昨天
		aRecent7Days : 'aRecent7Days', //最近7天
		aRecent30Days : 'aRecent30Days', //最近30天
        startDate : '', // 开始日期
        endDate : '', // 结束日期
        startCompareDate : '', // 对比开始日期
        endCompareDate : '', // 对比结束日期
        success : function(obj) {return true;},
        startDateId : 'startDate', // 开始日期输入框ID
        startCompareDateId : 'startCompareDate', // 对比开始日期输入框ID
        endDateId : 'endDate', // 结束日期输入框ID
        endCompareDateId : 'endCompareDate', // 对比结束日期输入框ID
        target : '', // 日期选择框的目标，一般为 <form> 的ID值
        needCompare : false, // 是否需要进行日期对比
		suffix : '',
        compareCheckboxId : 'needCompare', // 比较选择框
        calendars : 3, // 展示的月份数
        dayRangeMax : 0, // 日期最大范围(以天计算)
        monthRangeMax : 12, // 日期最大范围(以月计算)
        dateTable : 'dateRangeDateTable', // 日期表格的CSS类
        selectCss : 'dateRangeSelected', // 时间选择的样式
        compareCss : 'dateRangeCompare', // 比较时间选择的样式
        coincideCss : 'dateRangeCoincide', // 重合部分的样式
        disableGray : 'dateRangeGray', // 非当前月的日期样式
        isToday : 'dateRangeToday', // 今天日期的样式
        joinLineId : 'joinLine',
        isSingleDay : false,
        defaultText : ' 至 ',
        singleCompare : false,
        stopToday : true,
        rangeTableId : 'range_table',
        isTodayValid : false,
		noCalendar : false, //日期输入框是否展示
        exportFileId : 'exportFile'
    };
    var __method = this;
	//昨天,今天,最近7天,最近30天
	this.periodObj = {
		aToday : 0,
		aYesterday : 1,
		aRecent7Days : 6,
		aRecent30Days : 29
	};
	//展示粒度对象
	this.rangeObj = {
		cbRangeHour : 'range_hour',
		cbRangeDay : 'range_day',
		cbRangeWeek : 'range_week',
		cbRangeMonth : 'range_month'
	};
	
    this.inputId = inputId;
	this.inputCompareId = inputId + 'Compare';
    // 配置参数
    this.mOpts = $.extend({}, defaults, options);
    // 记录初始默认时间
    this.startDefDate = '';
    // 是否有分时统计
    this.isHourStats = (undefined != $('#' + this.rangeObj.cbRangeHour).val());
    // 随机ID后缀
    var suffix = '' == this.mOpts.suffix ? (new Date()).getTime() : this.mOpts.suffix;
    // 日期选择框DIV的ID
    this.calendarId = 'calendar_' + suffix;
    // 日期列表DIV的ID
    this.dateListId = 'dateRangePicker_' + suffix;
    // 日期比较层
    this.dateRangeCompareDiv = 'dateRangeCompareDiv_' + suffix;
	//日期选择层
	this.dateRangeDiv = 'dateRangeDiv_' + suffix;
    // 日期对比选择控制的checkbox
    this.compareCheckBoxDiv = 'dateRangeCompareCheckBoxDiv_' + suffix;
    // 时间选择的确认按钮
    this.submitBtn = 'submit_' + suffix;
    // 日期选择框关闭按钮
    this.closeBtn = 'closeBtn_' + suffix;
    // 上一个月的按钮
    this.preMonth = 'dateRangePreMonth_' + suffix;
    // 下一个月的按钮
    this.nextMonth = 'dateRangeNextMonth_' + suffix;
    // 表单中开始、结束、开始对比、结束对比时间
    this.startDateId = this.mOpts.startDateId + '_' + suffix;
    this.endDateId = this.mOpts.endDateId + '_' + suffix;
    this.compareCheckboxId = this.mOpts.compareCheckboxId + '_' + suffix;
    this.startCompareDateId = this.mOpts.startCompareDateId + '_' + suffix;
    this.endCompareDateId = this.mOpts.endCompareDateId + '_' + suffix;

    // 初始化日期选择HTML
    var wrapper = [
        '<div id="' + this.calendarId + '" class="dateRangeCalendar">',
            '<table class="dateRangePicker"><tr id="' + this.dateListId + '"></tr></table>',
            '<div class="dateRangeOptions">',
                '<div class="dateRangeInput" id="' + this.dateRangeDiv + '" >',
                    '<input type="text" class="dateRangeInput" name="' + this.startDateId + '" id="' + this.startDateId + '" value="' + this.mOpts.startDate + '" readonly />',
                    '<span id="' + this.mOpts.joinLineId + '"> - </span>',
                    '<input type="text" class="dateRangeInput" name="' + this.endDateId + '" id="' + this.endDateId + '" value="' + this.mOpts.endDate + '" readonly /><br />',
                '</div>',
                '<div class="dateRangeInput" id="' + this.dateRangeCompareDiv + '">',
                    '<input type="text" class="dateRangeInput" name="' + this.startCompareDateId + '" id="' + this.startCompareDateId + '" value="' + this.mOpts.startCompareDate + '" readonly />',
                    '<span class="' + this.mOpts.joinLineId + '"> - </span>',
                    '<input type="text" class="dateRangeInput" name="' + this.endCompareDateId + '" id="' + this.endCompareDateId + '" value="' + this.mOpts.endCompareDate + '" readonly />',
                '</div>',
                '<div>',
                    '<input type="button" name="' + this.submitBtn + '" id="' + this.submitBtn + '" value="确定" />',
                    '&nbsp;<a id="' + this.closeBtn + '" href="javascript:;">关闭</a>',
                '</div>',
            '</div>',
        '</div>'
    ];
	//checkbox对比选择框的使用 added by johnnyzheng
	var checkBoxWrapper = [
		'<label class="contrast" for ="' + this.compareCheckboxId + '">',
            '<input type="checkbox" class="pc" name="' + this.compareCheckboxId + '" id="' + this.compareCheckboxId + '" value="1"/>对比',
        '</label>',
		'<input type="text" name="dateCompare" id="dateCompare" value="" class="px date"/>'
	];
	//把checkbox放到页面的相应位置,放置到inputid后面 added by johnnyzheng
	$(checkBoxWrapper.join('')).insertAfter($('#' + this.inputId));
	//根据传入参数决定是否展示日期输入框
	if(this.mOpts.noCalendar){
		$('#' + this.inputId).css('display', 'none');
	}
	
    // 把时间选择框放到页面中
    $(0 < $('#appendParent').length ? '#appendParent' : document.body).append(wrapper.join(''));
    $('#' + this.calendarId).css('z-index', 9999);
    // 初始化目标地址的元素
    if(1 > $('#' + this.mOpts.startDateId).length) {
        $('#' + this.mOpts.target).append('<input type="hidden" id="' + this.mOpts.startDateId + '" name="' + this.mOpts.startDateId + '" value="' + this.mOpts.startDate + '" />');
    } else {
        $('#' + this.mOpts.startDateId).val(this.mOpts.startDate);
    }
    if(1 > $('#' + this.mOpts.endDateId).length) {
        $('#' + this.mOpts.target).append('<input type="hidden" id="' + this.mOpts.endDateId + '" name="' + this.mOpts.endDateId + '" value="' + this.mOpts.endDate + '" />');
    } else {
        $('#' + this.mOpts.endDateId).val(this.mOpts.endDate);
    }
    if(1 > $('#' + this.mOpts.compareCheckboxId).length) {
        $('#' + this.mOpts.target).append('<input type="checkbox" id="' + this.mOpts.compareCheckboxId + '" name="' + this.mOpts.compareCheckboxId + '" value="1" style="display:none;" />');
    }
    // 如果不需要比较日期，则需要隐藏比较部分的内容
    if(false == this.mOpts.needCompare) {
        $('#' + this.compareCheckBoxDiv).css('display', 'none');
        $('#' + this.dateRangeCompareDiv).css('display', 'none');
        $('#' + this.compareCheckboxId).attr('disabled', true);
        $('#' + this.startCompareDateId).attr('disabled', true);
        $('#' + this.endCompareDateId).attr('disabled', true);
		//隐藏对比的checkbox
		$('#' + this.compareCheckboxId).parent().css('display','none');
    } else {
        if(1 > $('#' + this.mOpts.startCompareDateId).length) {
            $('#' + this.mOpts.target).append('<input type="hidden" id="' + this.mOpts.startCompareDateId + '" name="' + this.mOpts.startCompareDateId + '" value="' + this.mOpts.startCompareDate + '" />');
        } else {
            $('#' + this.mOpts.startCompareDateId).val(this.mOpts.startCompareDate);
        }
        if(1 > $('#' + this.mOpts.endCompareDateId).length) {
            $('#' + this.mOpts.target).append('<input type="hidden" id="' + this.mOpts.endCompareDateId + '" name="' + this.mOpts.endCompareDateId + '" value="' + this.mOpts.endCompareDate + '" />');
        } else {
            $('#' + this.mOpts.endCompareDateId).val(this.mOpts.endCompareDate);
        }
        if('' == this.mOpts.startCompareDate || '' == this.mOpts.endCompareDate) {
            $('#' + this.compareCheckboxId).attr('checked', false);
            $('#' + this.mOpts.compareCheckboxId).attr('checked', false);
        } else {
            $('#' + this.compareCheckboxId).attr('checked', true);
            $('#' + this.mOpts.compareCheckboxId).attr('checked', true);
        }
		
    }

    // 输入框焦点定在第一个输入框
    this.dateInput = this.startDateId;
    // 为新的输入框加背景色
    this.changeInput(this.dateInput);

    // 开始时间 input 的 click 事件
    $('#' + this.startDateId).bind('click', function() {
        // 如果用户在选择基准结束时间时，换到对比时间了，则
        if(__method.endCompareDateId == __method.dateInput) {
            $('#' + __method.startCompareDateId).val(__method.startDefDate);
        }
        __method.startDefDate = '';
        __method.removeCSS(1);
        //__method.addCSS(1);
        __method.changeInput(__method.startDateId);
        return false;
    });
    $('#' + this.calendarId).bind('click', function(event) {
        //event.preventDefault();
        // 防止冒泡
        event.stopPropagation();
    });
    // 开始比较时间 input 的 click 事件
    $('#' + this.startCompareDateId).bind('click', function() {
        // 如果用户在选择基准结束时间时，换到对比时间了，则
        if(__method.endDateId == __method.dateInput) {
            $('#' + __method.startDateId).val(__method.startDefDate);
        }
        __method.startDefDate = '';
        __method.removeCSS(0);
        //__method.addCSS(0);
        __method.changeInput(__method.startCompareDateId);
        return false;
    });
    // 时间选择确认并关闭 button 的 click 事件
    $('#' + this.submitBtn).bind('click', function() {
        __method.close(1);
        __method.mOpts.success(__method);
        return false;
    });
    // 日期选择关闭按钮的 click 事件
    $('#' + this.closeBtn).bind('click', function() {
        __method.close();
        return false;
    });
    // 为输入框添加click事件
    $('#' + this.inputId).bind('click', function() {
        __method.init();
        __method.show(false, __method);
        return false;
    });
	   // 为输入框添加click事件
    $('#' + this.inputCompareId).bind('click', function() {
        __method.init(true);
        __method.show(true, __method);
        return false;
    });
	
	//判断是否是实时数据,如果是将时间默认填充进去 added by johnnyzheng 12-06
	if(this.mOpts.singleCompare){
		$('#' + this.compareCheckboxId).attr('checked',true);
		$('#' + this.mOpts.compareCheckboxId).attr('checked',true);
		$('#' + __method.startDateId).val(__method.mOpts.startDate);
		$('#' + __method.endDateId).val(__method.mOpts.startDate);
		$('#' + __method.startCompareDateId).val(__method.mOpts.startCompareDate);
		$('#' + __method.endCompareDateId).val(__method.mOpts.startCompareDate);
	}

    // 时间对比
    $('#' + this.dateRangeCompareDiv).css('display', $('#' + this.compareCheckboxId).attr('checked') ? '' : 'none');
    $('#' + this.compareCheckboxId).bind('click', function() {
		$('#' + __method.inputCompareId).css('display', this.checked ? '' : 'none');
        // 隐藏对比时间选择
        $('#' + __method.dateRangeCompareDiv).css('display', this.checked ? '' : 'none');
        // 把两个对比时间框置为不可用
        $('#' + __method.startCompareDateId).css('disabled', this.checked ? false : true);
        $('#' + __method.endCompareDateId).css('disabled', this.checked ? false : true);
        // 修改表单的 checkbox 状态
        $('#' + __method.mOpts.compareCheckboxId).attr('checked', $('#' + __method.compareCheckboxId).attr('checked'));
        // 修改表单的值
        $('#' + __method.mOpts.compareCheckboxId).val($('#' + __method.compareCheckboxId).attr('checked')?1:0);
        // 初始化选框背景
        if($('#' + __method.compareCheckboxId).attr('checked')) {
            sDate = __method.str2date($('#' + __method.startDateId).val());
            sTime = sDate.getTime();
            eDate = __method.str2date($('#' + __method.endDateId).val());
			eTime = eDate.getTime();
            scDate = $('#' + __method.startCompareDateId).val();
            ecDate = $('#' + __method.endCompareDateId).val();
            if('' == scDate || '' == ecDate) {
                ecDate = __method.str2date(__method.date2ymd(sDate).join('-'));
                ecDate.setDate(ecDate.getDate() - 1);
				scDate = __method.str2date(__method.date2ymd(sDate).join('-'));
                scDate.setDate(scDate.getDate() - ((eTime - sTime) / 86400000) - 1);
				//这里要和STATS_START_TIME的时间进行对比，如果默认填充的对比时间在这个时间之前 added by johnnyzheng
				if(ecDate.getTime() < STATS_START_TIME * 1000){
					scDate = sDate;
					ecDate = eDate;
				}
				if(ecDate.getTime() >= STATS_START_TIME * 1000 && scDate.getTime() < STATS_START_TIME * 1000){
					scDate.setTime(STATS_START_TIME * 1000)
					scDate = __method.str2date(__method.date2ymd(scDate).join('-'));
					ecDate.setDate(scDate.getDate() + ((eTime - sTime) / 86400000) - 1);
				}
                $('#' + __method.startCompareDateId).val(__method.formatDate(__method.date2ymd(scDate).join('-')));
                $('#' + __method.endCompareDateId).val(__method.formatDate(__method.date2ymd(ecDate).join('-')));
            }

            __method.changeExportClass(1);
            __method.addCSS(1);
            // 输入框焦点切换到比较开始时间
            __method.changeInput(__method.startCompareDateId);

        } else {
            __method.changeExportClass(0);
            __method.removeCSS(1);
            // 输入框焦点切换到开始时间
            __method.changeInput(__method.startDateId);
        }
		//用户点击默认自动提交 added by johnnyzheng 12-08
		__method.close();
		__method.mOpts.success(__method);
    });
    // 让用户手动关闭或提交日历
    $(document).bind('click', function() {
        __method.close();
    });

    // 初始化开始
    this.init();
    // 关闭日期选择框，并把结果反显到输入框
    this.close();
};

// 初始化
pickerDateRange.prototype.init = function(isCompare) {
    var __method = this;
    var minDate, maxDate;
	var isNeedCompare = typeof(isCompare) != 'undefined'? isCompare && $("#" + __method.compareCheckboxId).attr('checked') : $("#" + __method.compareCheckboxId).attr('checked');
    // 清空日期列表的内容
    $("#" + this.dateListId).empty();

    // 如果开始日期为空，则取当天的日期为开始日期
    var endDate = '' == this.mOpts.endDate ? (new Date()) : this.str2date(this.mOpts.endDate);
    // 日历结束时间
    this.calendar_endDate = new Date(endDate.getFullYear(), endDate.getMonth() + 1, 0);

    // 计算并显示以 endDate 为结尾的最近几个月的日期列表
    for(var i = 0; i < this.mOpts.calendars; i ++) {
        var td = document.createElement('td');
        $(td).append(this.fillDate(endDate.getFullYear(), endDate.getMonth(), i));
        $(td).css('vertical-align', 'top');
        if(0 == i) {
            $("#" + this.dateListId).append(td);
        } else {
            var firstTd = $("#" + this.dateListId).find('td').get(0);
            $(firstTd).before(td);
        }
        endDate.setMonth(endDate.getMonth() - 1, 1);
    }

    // 上一个月
    $('#' + this.preMonth).bind('click', function() {
        __method.calendar_endDate.setMonth(__method.calendar_endDate.getMonth() - 1, 1);
        __method.mOpts.endDate = __method.date2ymd(__method.calendar_endDate).join('-');
        __method.init();
		//如果是单月选择的时候，要控制input输入框 added by johnnyzheng 2011-12-19
		if(1 == __method.mOpts.calendars){
			if('' == $('#' + __method.startDateId).val()){
				__method.changeInput(__method.startDateId);
			}
			else{
				__method.changeInput(__method.endDateId);
			}
		}
        return false;
    });
    // 下一个月
    $('#' + this.nextMonth).bind('click', function() {
        __method.calendar_endDate.setMonth(__method.calendar_endDate.getMonth() + 1, 1);
        __method.mOpts.endDate = __method.date2ymd(__method.calendar_endDate).join('-');
		__method.init();
		//如果是单月选择的时候，要控制input输入框 added by johnnyzheng 2011-12-19
		if(1 == __method.mOpts.calendars){
			if('' == $('#' + __method.startDateId).val()){
				__method.changeInput(__method.startDateId);
			}
			else{
				__method.changeInput(__method.endDateId);
			}
		}
        return false;
    });

    // 日历开始时间
    this.calendar_startDate = new Date(endDate.getFullYear(), endDate.getMonth() + 1, 1);

    // 初始化时间选区背景
    if(this.endDateId != this.dateInput && this.endCompareDateId != this.dateInput) {
         (isNeedCompare && typeof(isCompare) !='undefined') ? this.addCSS(1) : this.addCSS(0);
    }
	
	if(isNeedCompare && typeof(isCompare) !='undefined'){
		__method.addCSS(1);
		__method.removeCSS(0);
	}
	else{
		__method.addCSS(0);
		__method.removeCSS(1);
	}
	// 隐藏对比日期框
	$('#' + __method.inputCompareId).css('display', isNeedCompare ? '' : 'none');
	
	//昨天，今天，最近7天，最近30天绑定逻辑 added by johnnyzheng
	for(var property in __method.periodObj){
		if($('#'+__method.mOpts[property])){
			$('#' + __method.mOpts[property]).unbind('click');
			$('#' + __method.mOpts[property]).bind('click' , function(){
				//处理点击样式
				$(this).parent().nextAll().removeClass('a');
				$(this).parent().prevAll().removeClass('a');
				$(this).parent().addClass('a');
				//拼接提交时间串
				var timeObj = __method.getSpecialPeriod(__method.periodObj[$(this).attr('id')]);
				$('#' + __method.startDateId).val(__method.formatDate(timeObj.otherday));
				$('#' + __method.endDateId).val(__method.formatDate(timeObj.today));
				$('#' + __method.mOpts.startDateId).val($('#' + __method.startDateId).val());
				$('#' + __method.mOpts.endDateId).val($('#' + __method.endDateId).val());
				$('#' + __method.inputCompareId).css('display','none');
				$('#' + __method.compareCheckboxId).attr('checked', false);
				$('#' + __method.mOpts.compareCheckboxId).attr('checked', false);
                __method.close();
				//于此同时清空对比时间框的时间
				$('#' + __method.startCompareDateId).val('');
				$('#' + __method.endCompareDateId).val('');
				$('#' + __method.mOpts.startCompareDateId).val('');
				$('#' + __method.mOpts.endCompareDateId).val('');
				//点击提交
				__method.mOpts.success(__method);
				//统计快捷菜单的点击量
				path = document.location.pathname;
				tag = 'ta' + path.replace(/\//g ,'.') + '.' + $(this).attr('id');
				//pgvSendClick({hottag : tag});
			}); 
		}
	}
};

//控制粒度的选择的正确性 vinsonliu
pickerDateRange.prototype.ctrlDateRange = function(step){

    var ctrlMap = [];
    var chkId = '';
    var firstId = this.rangeObj.cbRangeDay;

    // 有分时选项且选择一天
    if (this.isHourStats) {
        var hourId = this.rangeObj.cbRangeHour
        // 选择一天只能按小时显示
        if (step == 0) {
            chkId = hourId;
            $('#' + hourId).attr('checked', true);
            $('#' + hourId).next('label').toggleClass('a', false);
            $('#' + hourId).next('label').removeClass('disabled');
        } else {
            $('#' + hourId).attr('checked', false);
            $('#' + hourId).attr('disabled', true);
            $('#' + hourId).next('label').addClass('disabled');
        }
    }
	
    ctrlMap[1] = this.rangeObj.cbRangeDay;
    ctrlMap[7] = this.rangeObj.cbRangeWeek;
    ctrlMap[30] = this.rangeObj.cbRangeMonth;
    step = Math.abs(step);

    $.each(ctrlMap, function(idx, id) {
        if (id) {
            // 超出可选范围
            if (step < idx) {
                $('#' + id).attr('disabled', true);
                $('#' + id).attr('checked', false);
                $('#' + id).next('label').removeClass('a');
                $('#' + id).next('label').addClass('disabled');
            } else {
                $('#' + id).attr('disabled', '');
                $('#' + id).next('label').removeClass('disabled');
            }

            if (!chkId && $('#' + id).attr('checked') == true) {
                chkId = id;
            }
        }
    });

    // 没有选中粒度就选第一个
    if (chkId) {
        $('#' + chkId).attr('disabled', '');
    } else {
        chkId = firstId;
        $('#' + firstId).attr('disabled', '');
        $('#' + firstId).attr('checked', true);
        $('#' + firstId).next('label').addClass('a');
        $('#' + firstId).next('label').removeClass('disabled');
    }

    $('#' + this.mOpts.rangeTableId).val($('#' + chkId).val());
}

//计算今天，昨天，最近7天，最近30天返回的时间范围  added by johnnyzheng
pickerDateRange.prototype.getSpecialPeriod = function(period){
	var __method = this;
	var date = new Date();
	//如果今天不可用，则从昨天向前推 added by johnnyzheng 12-07
	(__method.mOpts.isTodayValid && ('' != __method.mOpts.isTodayValid) || 2 > period)? '' : date.setTime(date.getTime() - ( 1 * 24 * 60 * 60 * 1000));
	var timeStamp = ((date.getTime()- ( period * 24 * 60 * 60 * 1000)) < (STATS_START_TIME * 1000)) ? (STATS_START_TIME * 1000) : (date.getTime()- ( period * 24 * 60 * 60 * 1000)) ;
	var todayStr = date.getFullYear() + '-' + (date.getMonth()+ 1 ) + '-' + date.getDate();
	date.setTime(timeStamp);
	var otherdayStr = date.getFullYear() + '-' + (date.getMonth()+ 1 ) + '-' + date.getDate();
	if(period == __method.periodObj.aYesterday){
		todayStr = otherdayStr;
	}
	return {today: todayStr , otherday : otherdayStr};
}

// 去掉之前选择的样式
pickerDateRange.prototype.removeCSS = function(isCompare, specialClass) {
    // 初始化对比时间重合部分的样式类
    if('undefined' == typeof(specialClass)) {
        specialClass = this.mOpts.coincideCss;
    }
    // 是否移除对比部分的样式:0 日期选择;1 对比日期选择
    if('undefined' == typeof(isCompare)) {
        isCompare = 0;
    }

    // 整个日期列表的开始日期
    var bDate = new Date(this.calendar_startDate.getFullYear(), this.calendar_startDate.getMonth(), this.calendar_startDate.getDate());
    var cla = '';
    // 从开始日期循环到结束日期
    for(var d = new Date(bDate); d.getTime() <= this.calendar_endDate.getTime(); d.setDate(d.getDate() + 1)) {
            if(0 == isCompare) {
                // 移除日期样式
                cla = this.mOpts.selectCss;
            } else {
                // 移除对比日期样式
                cla = this.mOpts.compareCss;
                // 如果该日期是重合部分，则直接改成日期样式
                // if($('#' + this.date2ymd(d).join('-')).hasClass(specialClass)) {
                    // $('#' + this.date2ymd(d).join('-')).attr('class', this.mOpts.selectCss);
                    // continue;
                // }
            }
        // 移除指定样式
        $('#' + this.date2ymd(d).join('-')).removeClass(cla);
    }
};

// 为选中的日期加上样式：1=比较时间；0=时间范围
pickerDateRange.prototype.addCSS = function(isCompare, specialClass) {
    // 初始化对比时间重合部分的样式类
    if('undefined' == typeof(specialClass)) {
        specialClass = this.mOpts.coincideCss;
    }
    // 是否移除对比部分的样式:0 日期选择;1 对比日期选择
    if('undefined' == typeof(isCompare)) {
        isCompare = 0;
    }

    // 获取4个日期
    var startDate = this.str2date($('#' + this.startDateId).val());
    var endDate = this.str2date($('#' + this.endDateId).val());
    var startCompareDate = this.str2date($('#' + this.startCompareDateId).val());
    var endCompareDate = this.str2date($('#' + this.endCompareDateId).val());

    // 循环开始日期
    var sDate = 0 == isCompare ? startDate : startCompareDate;
    // 循环结束日期
    var eDate = 0 == isCompare ? endDate : endCompareDate;
    var cla = '';
    for(var d = new Date(sDate); d.getTime() <= eDate.getTime(); d.setDate(d.getDate() + 1)) {
            if(0 == isCompare) {
                // 添加日期样式
                cla = this.mOpts.selectCss;
            } else {
                // 添加对比日期样式
                cla = this.mOpts.compareCss;
                // 如果该日期是重合部分，则直接改成重合日期样式
                //if(d.getTime() >= startDate.getTime() && d.getTime() <= endDate.getTime()) {
                //    cla = specialClass;
                //}
            }
        // 移除指定样式
        $('#' + this.date2ymd(d).join('-')).attr('class', cla);
    }
};

// 判断开始、结束日期是否处在允许的范围内
pickerDateRange.prototype.checkDateRange = function(startYmd, endYmd) {
    var sDate = this.str2date(startYmd);
    var eDate = this.str2date(endYmd);
    var sTime = sDate.getTime();
    var eTime = eDate.getTime();
    var minEDate, maxEDate;
    //if (this.mOpts.singleCompare) {
    //    if (sTime == eTime) {
    //        alert('不能用同一天的数据进行对比');
    //        return false;
    //    }
    //}
    // 如果开始时间大于结束时间
    if(eTime >= sTime) {
        // 判断是否超过最大日期外
        maxEDate = this.str2date(startYmd);
        maxEDate.setMonth(maxEDate.getMonth() + this.mOpts.monthRangeMax);
        maxEDate.setDate(maxEDate.getDate() + this.mOpts.dayRangeMax - 1);
        if(maxEDate.getTime() < eTime) {
            alert('结束日期不能大于：' + this.date2ymd(maxEDate).join('-'));
            return false;
        }
    } else {
        // 判断是否超过最大日期外
        maxEDate = this.str2date(startYmd);
        maxEDate.setMonth(maxEDate.getMonth() - this.mOpts.monthRangeMax);
        maxEDate.setDate(maxEDate.getDate() - this.mOpts.dayRangeMax + 1);
        if(maxEDate.getTime() > eTime) {
            alert('开始日期不能小于：' + this.date2ymd(maxEDate).join('-'));
            return false;
        }
    }
    return true;
}

// 选择日期
pickerDateRange.prototype.selectDate = function(ymd) {
    // 格式化日期
    var ymdFormat = this.formatDate(ymd);

    // start <-> end 切换
    if(this.startDateId == this.dateInput) {
        // 移除样式
        this.removeCSS(0);
		this.removeCSS(1);
        // 为当前点加样式
        $('#' + ymd).attr('class', this.mOpts.selectCss);
        // 获取开始时间的初始值
        this.startDefDate = $('#' + this.dateInput).val();
        // 更改对应输入框的值
        $('#' + this.dateInput).val(ymdFormat);
        // 切换输入框焦点,如果是实时数据那么选择一天的数据
        if (true == this.mOpts.singleCompare || true == this.mOpts.isSingleDay) {
            this.dateInput = this.startDateId;
			$('#' + this.endDateId).val(ymdFormat);
        } else {
            this.dateInput = this.endDateId;
        }
		
    } else if(this.endDateId == this.dateInput) {
        // 如果开始时间未选
        if('' == $('#' + this.startDateId).val()) {
            this.dateInput = this.startDateId;
            this.selectDate(ymd);
            return false;
        }
        // 判断用户选择的时间范围
        if(false == this.checkDateRange($('#' + this.startDateId).val(), ymd)) {
            return false;
        }
        // 如果结束时间小于开始时间
        if(-1 == this.compareStrDate(ymd, $('#' + this.startDateId).val())) {
            // 更改对应输入框的值(结束时间)
            $('#' + this.dateInput).val($('#' + this.startDateId).val());
            // 更改对应输入框的值(开始时间)
            $('#' + this.startDateId).val(ymdFormat);
            ymdFormat = $('#' + this.dateInput).val();
        }
        // 更改对应输入框的值
        $('#' + this.dateInput).val(ymdFormat);
        // 切换输入框焦点
        this.dateInput = this.startDateId;
		this.removeCSS(0);
        this.addCSS(0);
		//this.addCSS(0, this.mOpts.coincideCss);
        this.startDefDate = '';
    } else if(this.startCompareDateId == this.dateInput) {
        // 移除样式
        this.removeCSS(1);
		this.removeCSS(0);
        // 为当前点加样式
        $('#' + ymd).attr('class', this.mOpts.compareCss);
        // 获取开始时间的初始值
        this.startDefDate = $('#' + this.dateInput).val();
        // 更改对应输入框的值
        $('#' + this.dateInput).val(ymdFormat);
        // 切换输入框焦点
        this.dateInput = this.endCompareDateId;
		if(true == this.mOpts.singleCompare){
			this.dateInput = this.startCompareDateId;
			$('#' + this.endCompareDateId).val(ymdFormat);
		}
		
    } else if(this.endCompareDateId == this.dateInput) {
        // 如果开始时间未选
        if('' == $('#' + this.startCompareDateId).val()) {
            this.dateInput = this.startCompareDateId;
            this.selectDate(ymd);
            return false;
        }
        // 判断用户选择的时间范围
        if(false == this.checkDateRange($('#' + this.startCompareDateId).val(), ymd)) {
            return false;
        }
        // 如果结束时间小于开始时间
        if(-1 == this.compareStrDate(ymd, $('#' + this.startCompareDateId).val())) {
            // 更改对应输入框的值(结束时间)
            $('#' + this.dateInput).val($('#' + this.startCompareDateId).val());
            // 更改对应输入框的值(开始时间)
            $('#' + this.startCompareDateId).val(ymdFormat);
            ymdFormat = $('#' + this.dateInput).val();
        }
        // 更改对应输入框的值
        $('#' + this.dateInput).val(ymdFormat);
        // 切换输入框焦点
        this.dateInput = this.startCompareDateId;
        //this.addCSS(1, this.mOpts.coincideCss);
		this.removeCSS(1);
		this.addCSS(1);
        this.startDefDate = '';
    }
    // 切换到下一个输入框
    this.changeInput(this.dateInput);
};

// 显示日期选择框
pickerDateRange.prototype.show = function(isCompare, __method) {
	$('#' + __method.dateRangeDiv).css('display', isCompare ? 'none' : '');
	$('#' + __method.dateRangeCompareDiv).css('display', isCompare ? '' : 'none');
    var pos = isCompare ?  $('#' + this.inputCompareId).offset() : $('#' + this.inputId).offset();
	var offsetHeight = isCompare ? $('#' + this.inputCompareId).attr('offsetHeight') : $('#' + this.inputId).attr('offsetHeight')
    var clientWidth = parseInt($(document.body).attr('clientWidth'));
    var left = pos.left;
    $("#" + this.calendarId).css('display', 'block');
    if (true == this.mOpts.singleCompare || true == this.mOpts.isSingleDay) {
        $('#' + this.endDateId).css('display', 'none');
		$('#' + this.endCompareDateId).css('display','none');
        $('#' + this.mOpts.joinLineId).css('display', 'none');
		$('.' + this.mOpts.joinLineId).css('display', 'none');
    }
    // 如果和输入框左对齐时超出了宽度范围，则右对齐
    if(0 < clientWidth && $("#" + this.calendarId).attr('offsetWidth') + pos.left > clientWidth) {
        left = pos.left + $('#' + this.inputId).attr('offsetWidth') - $("#" + this.calendarId).attr('offsetWidth') + ((/msie/i.test(navigator.userAgent) && !(/opera/i.test(navigator.userAgent)))? 5 : 0) ;
    }
    $("#" + this.calendarId).css('left', left  + 'px');
    $("#" + this.calendarId).css('top', pos.top + offsetHeight- 1 + 'px');

	//第一次显示的时候，一定要初始化输入框
	isCompare ? this.changeInput(this.startCompareDateId) : this.changeInput(this.startDateId);
    return false;
};

// 关闭日期选择框
pickerDateRange.prototype.close = function(btnSubmit) {
    // 把开始、结束时间显示到输入框 (PS:如果选择的今日，昨日，则只填入一个日期)
    $('#' + this.inputId).val($('#' + this.startDateId).val() + ($('#' + this.startDateId).val() == $('#' + this.endDateId).val() ? '' : this.mOpts.defaultText + $('#' + this.endDateId).val()));
	//判断当前天是否可选，来决定从后往前推修改日期是从哪一点开始
	var nDateTime = ((this.mOpts.isTodayValid && '' != this.mOpts.isTodayValid)) ? new Date().getTime() : new Date().getTime() - (1 * 24 * 60 * 60 * 1000);
    var bDateTime = this.str2date($('#' + this.startDateId).val()).getTime();
    var eDateTime = this.str2date($('#' + this.endDateId).val()).getTime();
	//	//在js侧就做好日期校准，以前面的日期选择的跨度为准，如果后面的跨度超过了当前可用时间，则以当前可用时间向前推 added by johnnyzheng 11-29
	if('' !=  $('#' + this.startCompareDateId).val() && '' != $('#' + this.endCompareDateId).val()){
		var bcDateTime = this.str2date($('#' + this.startCompareDateId).val()).getTime();
		var ecDateTime = this.str2date($('#' + this.endCompareDateId).val()).getTime();
		var _ecDateTime = bcDateTime + eDateTime - bDateTime;
		if(_ecDateTime > nDateTime){
		//如果计算得到的时间超过了当前可用时间，那么就和服务器端保持一致，将当前可用的天数向前推日期选择器的跨度 added by johnnyzheng 11-29
			_ecDateTime = nDateTime;
			$('#' + this.startCompareDateId).val(this.formatDate(this.date2ymd(new Date(_ecDateTime + bDateTime - eDateTime)).join('-')));
		}
		$('#' + this.endCompareDateId).val(this.formatDate(this.date2ymd(new Date(_ecDateTime)).join('-')));
	}
	
	//把对比时间填入输入框 (PS:如果选择今日，昨日，则只填入一个日期)
	$('#' + this.inputCompareId).val($('#' + this.startCompareDateId).val() + ($('#' + this.startCompareDateId).val() == $('#' + this.endCompareDateId).val() ? '' : this.mOpts.defaultText + $('#' + this.endCompareDateId).val()));

    // 计算相隔天数
    var step = (bDateTime - eDateTime) / 86400000;
	this.ctrlDateRange(step);

	// 隐藏日期选择框
    $("#" + this.calendarId).css('display', 'none');
    // 更改目标元素值
    $('#' + this.mOpts.startDateId).val($('#' + this.startDateId).val());
    $('#' + this.mOpts.endDateId).val($('#' + this.endDateId).val());
	$('#' + this.mOpts.startCompareDateId).val($('#' + this.startCompareDateId).val());
    $('#' + this.mOpts.endCompareDateId).val($('#' + this.endCompareDateId).val());
	//如果是实时数据只允许单个天数的选择，则将对比的日期赋到常规选择的enddate
	// if(this.mOpts.singleCompare){
		// $('#' + this.mOpts.endDateId).val($('#' + this.endCompareDateId).val());
	// }
	//点击确定按钮进行查询后将取消所有的今天 昨天 最近7天的快捷链接 added by johnnyzheng 11-29
	if(btnSubmit){
		for(var property in this.periodObj){
			if($('#' + this.mOpts[property])){
				$('#' + this.mOpts[property]).parent().removeClass('a');
			}
		}
	}
    return false;
};

// 日期填充
pickerDateRange.prototype.fillDate = function(year, month, index) {
    var __method = this;

    // 当月第一天
    var firstDayOfMonth = new Date(year, month, 1);
    var dateBegin = new Date(year, month, 1);
    var w = dateBegin.getDay();
    // 计算应该开始的日期
    dateBegin.setDate(1 - w);

    // 当月最后一天
    var lastDayOfMonth = new Date(year, month + 1, 0);
    var dateEnd = new Date(year, month + 1, 0);
    w = dateEnd.getDay();
    // 计算应该结束的日期
    dateEnd.setDate(dateEnd.getDate() + 6 - w);

    var today = new Date();
    var dToday = today.getDate();
    var mToday = today.getMonth();
    var yToday = today.getFullYear();

    var table = document.createElement('table');
    table.className = this.mOpts.dateTable;

    tr = document.createElement('tr');
    td = document.createElement('td');
    // 如果是最后一个月的日期，则加上下一个月的链接
    if(0 == index) {
        $(td).append('<a href="javascript:void(0);" id="' + this.nextMonth + '" class="dateRangeNextMonth"><span>next</span></a>');
    }
    // 如果是第一个月的日期，则加上上一个月的链接
    if(index + 1 == this.mOpts.calendars) {
        $(td).append('<a href="javascript:void(0);" id="' + this.preMonth + '" class="dateRangePreMonth"><span>pre</span></a>');
    }
    $(td).append(year + '年' + (month + 1) + '月');
    $(td).attr('colSpan', 7);
    $(td).css('text-align', 'center');
    $(tr).append(td);
    $(table).append(tr);

    var days = ['日', '一', '二', '三', '四', '五', '六'];
    tr = document.createElement('tr');
    for(var i = 0; i < 7; i ++) {
        td = document.createElement('td');
        $(td).append(days[i]);
        $(tr).append(td);
    }
    $(table).append(tr);

    // 当前月的所有日期(包括空白位置填充的日期)
    var tdClass = '', deviation = 0, ymd = '';
    for(var d = dateBegin; d.getTime() <= dateEnd.getTime(); d.setDate(d.getDate() + 1)) {
        if(d.getTime() < firstDayOfMonth.getTime()) { // 当前月之前的日期
            tdClass = this.mOpts.disableGray;
            deviation = '-1';
        } else if(d.getTime() > lastDayOfMonth.getTime()) { // 当前月之后的日期
            tdClass = this.mOpts.disableGray;
            deviation = '1';
        } else if((this.mOpts.stopToday == true && d.getTime() > today.getTime()) || d.getTime() < STATS_START_TIME * 1000) { // 当前时间之后的日期，或者开启统计之前的日期
            tdClass = this.mOpts.disableGray;
            deviation = '2';
        } else { // 当前月日期
            deviation = '0';
            if(d.getDate() == dToday && d.getMonth() == mToday && d.getFullYear() == yToday) {
                if (this.mOpts.isTodayValid) {
                    tdClass = this.mOpts.isToday;
                } else {
                    tdClass = this.mOpts.disableGray;
                    deviation = '2';
                }
            } else {
                tdClass = '';
            }
        }

        // 如果是周日
        if(0 == d.getDay()) {
            tr = document.createElement('tr');
        }

        td = document.createElement('td');
        td.innerHTML = d.getDate();
        if('' != tdClass) {
            $(td).attr('class', tdClass);
        }

        // 只有当前月可以点击
        if(0 == deviation) {
            ymd = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
            $(td).attr('id', ymd);
            (function(ymd) {
                $(td).bind("click", ymd, function() {
                    __method.selectDate(ymd);
                    return false;
                });
            })(ymd);
        }

        $(tr).append(td);

        // 如果是周六
        if(6 == d.getDay()) {
            $(table).append(tr);
        }
    }

    return table;
};

// 把时间字串转成时间格式
pickerDateRange.prototype.str2date = function(str) {
    var ar = str.split('-');

    // 返回日期格式
    return new Date(ar[0], ar[1] - 1, ar[2]);
};

// 比较两个时间字串的大小:1 大于; 0 等于; -1 小于
pickerDateRange.prototype.compareStrDate = function(b, e) {
    var bDate = this.str2date(b);
    var eDate = this.str2date(e);

    // 1 大于; 0 等于; -1 小于
    if(bDate.getTime() > eDate.getTime()) {
        return 1;
    } else if(bDate.getTime() == eDate.getTime()) {
        return 0;
    } else {
        return -1;
    }
};

// 把时间格式转成ID
pickerDateRange.prototype.date2ymd = function(d) {
    return [d.getFullYear(), (d.getMonth() + 1), d.getDate()];
};

// 切换焦点到当前输入框
pickerDateRange.prototype.changeInput = function(ipt) {
    // 强制修改为开始输入框
    if (true == this.mOpts.isSingleDay) {
        ipt = this.startDateId;
    }
    // 所有4个输入框
    var allInputs = [this.startDateId, this.startCompareDateId, this.endDateId, this.endCompareDateId];

    // 如果 ipt 是日期输入框，则为日期样式，否则为对比日期样式
    var cla = '';
    if(ipt == this.startDateId || ipt == this.endDateId) {
        cla = this.mOpts.selectCss;
    } else {
        cla = this.mOpts.compareCss;
    }
    if(ipt == this.endDateId && this.mOpts.singleCompare) {
        cla = this.mOpts.compareCss;
    }

    // 移除所有输入框的附加样式
    for(var i in allInputs) {
        $('#' + allInputs[i]).removeClass(this.mOpts.selectCss);
        $('#' + allInputs[i]).removeClass(this.mOpts.compareCss);
    }

    // 为指定输入框添加样式
    $('#' + ipt).addClass(cla);
    // 把输入焦点移到指定输入框
    this.dateInput = ipt;
};

// 日期格式化，加前导零
pickerDateRange.prototype.formatDate = function(ymd) {
    return ymd.replace(/(\d{4})\-(\d{1,2})\-(\d{1,2})/g, function(ymdFormatDate, y, m, d){
        if(m < 10){
            m = '0' + m;
        }
        if(d < 10){
            d = '0' + d;
        }
        return y + '-' + m + '-' + d;
    });
}

// 数据下载样式控制
pickerDateRange.prototype.changeExportClass = function(small) {
    if (small) {
        $('#' + this.mOpts.exportFileId).removeClass('export');
        $('#' + this.mOpts.exportFileId).addClass('export_small');
    } else {
        $('#' + this.mOpts.exportFileId).removeClass('export_small');
        $('#' + this.mOpts.exportFileId).addClass('export');
    }
}
