/**
 * js 分页
 * @param row
 * 			count		每页数据量
 * 			totalcount  总共数据量
 * 			pageno      当前分页号
 * @param string		绑定js页面跳转所需的按钮classname前缀
 * @returns {String}
 */
function getListPagingJS(row, string){
	var count = row.count;
	var totalcount = row.totalcount;
	var pageno = row.pageno;
	
	var totalpage= Math.ceil(totalcount / count);
	
	var html = '<div id="wgt-paging" class="wgt-paging"><div class="paging">';
	if(totalcount > count){
		html += '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td nowrap="nowrap" class="tc" id="pagenum" style="padding:20px 20px;">';
		html += '<div class="lf">共 ' + totalpage + ' 页';
		if(pageno > 1){
			html += '<a href="" class="firstpage ' + string + 'firstpage"><span>第一页</span></a>';
			html += '<a href="" class="prevpage ' + string + 'prevpage"><span>上一页</span></a>';
		}else{
			html += '<a href="javascript:return false;" disabled="disabled" class="firstpage"><span>第一页</span></a>';
			html += '<a href="javascript:return false;" disabled="disabled" class="prevpage"><span>上一页</span></a>';
		}
		html += '</div>';
		html += '<div class="pagenums">';
		var pages = Math.ceil(totalcount/count);
		var size = new Array();
		var k = 0;
		if (pageno < 5) {
			for (var __i = 1; __i < 11; __i++) {
				if (__i <= pages) {
					size[k] = __i;
					k++;
				}
			}
		} else if (pageno > (pages - 5)) {
			for (var __i = pages - 9; __i <= pages; __i++) {
				if (__i > 0) {
					size[k] = __i;
					k++;
				}
			}	
		} else {
			for (var __i = pageno - 4; __i < pageno + 6; __i++) {
				size[k] = __i;
				k++;
			}	
		}
		$.each(size,function(__i,__pageno){
			if(__pageno == pageno){
				html += '<a href="javascript:return false;" class="active" disabled="disabled"><span>' + __pageno + '</span></a>';
			}else{
				html += '<a href="" class="' + string + 'pageno"><span>' + __pageno + '</span></a>';
			}
		});
		html += '</div>';
		html += '<div class="lf">';
		if(pageno >= pages){
			html += '<a href="javascript:return false;" disabled="disabled" class="nextpage"><span>下一页</span></a>';
			html += '<a href="javascript:return false;" disabled="disabled" class="lastpage"><span>最末页</span></a>';
		}else{
			html += '<a href="" class="nextpage ' + string + 'nextpage"><span>下一页</span></a>';
			html += '<a href="" class="lastpage ' + string + 'lastpage"><span>最末页</span></a>';
		}
		html += '</div>';
		html += '</td></tr></table>';
	}
	html += '</div></div>';
	return html;
}