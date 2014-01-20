(function($) {
    $.fn.tableSorterPager = function(options) {
        return this.each(function() {
            // 默认配置
            var defaults = {
                size: 20, // 每页显示记录的条数
                offset: 0, // 显示的页数
                page: 0, // 当前页
                totalRows: 0, // 总记录数
                totalPages: 0, // 总页数
                container: null, // 分页显示的地方(HTML元素的ID值)
                formerTrs:[], // 旧行顺序
                isCompare:0, // 对比标识
                trs:[], // 表格数据行的对象数组
                orders:[], // 排序列元素对象
                sortArr:[], // 排序列缓存
                cssNext: 'next', // 下一页的样式
                cssPrev: 'prev', // 上一页的样式
                cssFirst: 'first', // 第一页的样式
                cssLast: 'last', // 最后一页的样式
                cssPageDisplay: 'current', // 当前页样式
                cssPageSize: 'pagesize', // 暂时未启用
                cssTable: 'tablesorter', // 数据表格样式
                cssHeader: 'header', // 表格头部列的样式
                cssHeaderSortUp: 'headerSortUp', // 升序列样式
                cssHeaderSortDown: 'headerSortDown', // 降序列样式
                sortColumnClass: 'order' // 排序列标识类
            };
            // 配置信息
            var opts = $.extend({},defaults, options);
            var __target = $(this);
            __target.toggleClass(opts.cssTable);
            // 生成分页
            function multi() {
                // 开始页
                var start = Math.max(opts.page - Math.floor(opts.offset / 2), 1);
                // 结束页
                var end = Math.min(start + opts.offset, opts.totalPages);
                // 链接
                var as = [];
                // 如果只有一页，则不显示分页
                if(1 == opts.totalPages) {
                    return false;
                }
                // 判断是否需要第一页、上一页的按钮
                if(1 < start) {
                    var f = createHref(1, '&laquo;');
                    $(f).css('class', opts.cssFirst)
                    as.push(f);
                    var p = createHref(opts.page - 1, '&lt;');
                    $(p).css('class', opts.cssPrev);
                    as.push(p);
                }
                // 输出每页信息
                for(var i = start;  i <= end; i ++) {
                    as.push(createHref(i));
                }
                // 判断是否需要最后一页、下一页的按钮
                if(opts.totalPages > end) {
                    var n = createHref(opts.page + 1, '&gt;');
                    $(n).css('class', opts.cssNext);
                    as.push(n);
                    var l = createHref(opts.totalPages, '&raquo;');
                    $(l).css('class', opts.cssLast);
                    as.push(l);
                }
                // 如果指定了分页显示位置
                if(!$('#' + opts.container).get(0)) {
                    __target.after('<div id="' + opts.container + '"></div>');
                }
                $('#' + opts.container).empty().append(as);
            };
            // 生成一个分页链接
            function createHref(page, html) {
                var a = document.createElement('a');
                html = 'undefined' == typeof(html) ? page : html;
                $(a).attr('href', 'javascript:;');
                $(a).html(html);
                if(page == opts.page) {
                    $(a).css('cursor', 'default');
                    $(a).addClass(opts.cssPageDisplay);
                } else {
                    $(a).bind('click', function() {
                        showPage(page);
                    });
                }
                return a;
            };
            // 处理当前页
            function showPage(page) {
                opts.page = page;
                multi();
                showData();
            };
            // 快速排序算法
            function quickSort(arr, ascDesc) {
                if (arr.length <= 1) {
                    return arr;
                }
                var pivotIndex = Math.floor(arr.length / 2);
                var pivot = arr.splice(pivotIndex, 1)[0];
                var left = [];
                var right = [];
                for (var i = 0; i < arr.length; i++){
                    if('asc' == ascDesc) {
                        if (arr[i][1] < pivot[1]) {
                            left.push(arr[i]);
                        } else {
                            right.push(arr[i]);
                        }
                    } else {
                        if (arr[i][1] < pivot[1]) {
                            right.push(arr[i]);
                        } else {
                            left.push(arr[i]);
                        }
                    }
                }
                return quickSort(left, ascDesc).concat([pivot], quickSort(right, ascDesc));
            };
            // 改变头部排序列的样式
            function clearHeaderCss() {
                $(opts.orders).each(function() {
                    $(this).removeClass(opts.cssHeaderSortUp);
                    $(this).removeClass(opts.cssHeaderSortDown);
                    $(this).removeAttr('ascdesc');
                });
            };
            // 排序的 click 事件
            function sortClick(event) {
                var ascDesc = $(this).attr('ascdesc');
                clearHeaderCss();
                if('undefined' == typeof(ascDesc) || 'asc' == ascDesc) {
                    ascDesc = 'desc';
                    $(this).addClass(opts.cssHeaderSortDown);
                } else {
                    ascDesc = 'asc';
                    $(this).addClass(opts.cssHeaderSortUp);
                }
                // 设置排序标识
                $(this).attr('ascdesc', ascDesc);
                // 如果没有需要排序的行
                var trsLen = opts.formerTrs.length;
                if(0 == trsLen) {
                    return true;
                }
                // 获取排序列数组
                var sortArr = [];
                useIdx = false;
                if('undefined' == typeof(opts.sortArr[event.data.index])) {
                    useIdx = $(this).attr('useidx');
                    for(var i = 0; i < trsLen; i ++) {
                        if (0 < opts.isCompareRealTime && event.data.index > 1) {
                            i ++;
                        }
                        if (useIdx) {
                            v = i;
                        } else {
                            if (0 < opts.isCompareRealTime && event.data.index > 1) {
                                var objThTd = $(opts.formerTrs[i]).find('td, th').get(event.data.index - 1);
                            } else {
                                var objThTd = $(opts.formerTrs[i]).find('td, th').get(event.data.index);
                            }
                            var numRe = /^[+\-]?\d+(.\d+)?/ig;
                            var v = $(objThTd).text();
                            if (v.match(/^\-/i)) {
                                var re = /[\:\%\s\,\n\r]/ig;
                            } else {
                                var re = /[\-\:\%\s\,\n\r]/ig;
                            }
                            v = v.replace(re, '');
                            if(v.match(numRe)) {
                                v = parseFloat(v);
                            }
                            if('' == v) {
                                v = 0;
                            } else if ('-' == v) {
                                v = -99999;
                            }
                        }
                        sortArr.push([i, v]);
                        // 如果是对比，则隔一行
                        if(0 < opts.isCompare) {
                            i ++;
                        }
                        if (0 < opts.isCompareRealTime && event.data.index == 1) {
                            i ++;
                        }
                    }
                    // 排序
//                    quickSort(sortArr, 0, sortArr.length -1);
                    sortArr.quickSort();
                    opts.sortArr[event.data.index] = sortArr;
                } else {
                    sortArr = opts.sortArr[event.data.index];
                }
                opts.trs = [];
                // 临时序号
                var iTmp = 0;
                for(var i = 0; i < sortArr.length; i ++) {
                    iTmp = sortArr[i][0];
                    if('desc' == ascDesc) {
                        if(0 < opts.isCompareRealTime && event.data.index == 1) {
                            opts.trs.unshift(opts.formerTrs[iTmp + 2]);
                        } 
                        if(0 < opts.isCompare) {
                            opts.trs.unshift(opts.formerTrs[iTmp + 1]);
                            
                        }
                        opts.trs.unshift(opts.formerTrs[iTmp]);
                        if(0 < opts.isCompareRealTime && event.data.index > 1) {
                            opts.trs.unshift(opts.formerTrs[iTmp - 1]);
                        } 
                    } else {
                        if(0 < opts.isCompareRealTime && event.data.index > 1) {
                            opts.trs.push(opts.formerTrs[iTmp - 1]);
                        }
                        opts.trs.push(opts.formerTrs[iTmp]);
                        if(0 < opts.isCompare) {
                            opts.trs.push(opts.formerTrs[iTmp + 1]);
                        }
                        if(0 < opts.isCompareRealTime && event.data.index == 1) {
                            opts.trs.push(opts.formerTrs[iTmp + 2]);
                        } 
                    }
                }
                // 显示当前页
                showData();
                try {
                    if (typeof(eval(opts.callBack)) == 'function') {
                        opts.callBack();
                    }
                } catch (e) {
                    // 不做处理
                }
            };
            function showData() {
                // 开始序号
                var start = (opts.page - 1) * opts.size;
                // 结束序号
                var end = start + opts.size;
                end = end > opts.trs.length ? opts.trs.length : end;
                // 清楚所有表格行
                __target.find('tr').each(function() {
                    // 表格的头、尾忽略
                    if($(this).parent().is('thead, tfoot')) {
                        return true;
                    }
                    $(this).remove();
                });
                // 所有 TR
                for(var i = start; i < end; i ++) {
                    var td_th = $(opts.trs[i]).find('td, th');
                    var orderNum = i;
                    if(1 == opts.isCompare && 0 == opts.isCompareRealTime) {
                        orderNum = orderNum / 2;
                    } else if (0 < opts.isCompareRealTime){
                        orderNum = orderNum / 3;
                    }
                    // 如果是对比，则隔一行
                    if(0 == opts.isCompare || (0 == opts.isCompareRealTime && 0 == i % 2) ||(1 == opts.isCompareRealTime && 0 == i % 3)) {
                        if(td_th.get(1)) {
                            td_th.get(0).innerHTML = orderNum + 1;
                        }
                    }
                    __target.append(opts.trs[i]);
                }
            };
            // 初始化排序表格
            function initEvent() {
                // 表格头部 TR 对象
                var theadTr = __target.find('thead').find('tr');
                // 统计所有表格的列数
                var trCount = 0;
                __target.find('tr').each(function() {
                    // 表格的头、尾忽略
                    if($(this).parent().is('thead, tfoot')) {
                        return true;
                    }
                    opts.formerTrs.push($(this).get(0));
                    trCount ++;
                });
                opts.trs = opts.formerTrs;
                // 数据总行数
                opts.totalRows = trCount;
                // 总页数
                opts.totalPages = Math.ceil(trCount / opts.size);
                // 显示的页数
                if(1 > opts.offset) {
                    opts.offset = 9;
                }
                // 当前页数
                if(1 > opts.page) {
                    opts.page = 1;
                }
                // 显示当前页的内容
                showPage(opts.page);
                // 是否是第一列
                var isFirst = true;
                // 过滤
                theadTr.children().filter(function(index) {
                    if($(this).hasClass(opts.sortColumnClass)) {
                        // 如果定义了无效TR数量，则index相减
                        if (opts.invalidTrCount) {
                            index -= opts.invalidTrCount;
                        }
                        $(this).contents().wrapAll('<span></span>');
                        var orderObj = $(this).children();
                        $(orderObj).attr('useidx', $(this).attr('useidx'));
                        orderObj.css('cursor', 'pointer');
                        orderObj.toggleClass(opts.cssHeader);
                        // 如果开始排序列未定义则第一列
                        if ((opts.startColumn && index == opts.startColumn) || (!opts.startColumn && isFirst)) {
                            isFirst = false;
                            if (opts.ascdesc == 'asc') {
                                orderObj.addClass(opts.cssHeaderSortUp).attr('ascdesc', 'asc');
                            } else {
                                orderObj.addClass(opts.cssHeaderSortDown).attr('ascdesc', 'desc');
                            }
                        }
                        orderObj.bind('click', {index:index}, sortClick);
                        opts.orders.push(orderObj.get(0));
                    }
                });
            };
            // 对必要的数据清空
            opts.formerTrs.length = 0;
            opts.trs.length = 0;
            opts.orders.length = 0;
            // 排序记录，优化速度，这里做一个缓存
            opts.sortArr.length = 0;
            // 清除分页内容
            $('#' + opts.container).empty();
            // 初始化排序表格
            initEvent();
        });
    };
})(jQuery);
