function doSelector(frmId, obj, submit, preChk) {
    var tips = '';
    var maxTips = 2;
    var limit = parseInt(CHECKBOX_LIMIT[preChk + '[]']);

    if (obj) {
        var val = $(obj).val();

        // 检查是否被选中
        idx = $.inArray(val, SELECT_ITEMS[frmId]);
        if (idx > -1) {
            SELECT_ITEMS[frmId].splice(idx, 1);
        }

        if ($(obj).attr('checked') == true) {
            SELECT_ITEMS[frmId].unshift(val);
        } else {
            $(obj).next('label').removeClass('a');

            if (SELECT_ITEMS[frmId].length < 1) {
                SELECT_ITEMS[frmId].unshift(val);
                return true;
            }
        }
    }

    var len = SELECT_ITEMS[frmId].length;
    if (!isNaN(limit) && len > limit) {
        deletor  = SELECT_ITEMS[frmId].splice(limit, len - limit);

        $.each(deletor, function(idx, id){
            $('#' + preChk + '_' + id).attr('checked', false);
            $('#' + preChk + '_' + id).next('label').removeClass('a');
        });
    }

    var deli = '';
    var flag = 0;
    $.each(SELECT_ITEMS[frmId], function(idx, id) {
        $('#' + preChk + '_' + id).attr('checked', true);
        $('#' + preChk + '_' + id).next('label').addClass('a');
        if (flag++ > maxTips) {
            tips += ',...';
            return false;
        }

        tips +=  deli + ITEM_TIPS[id];
        deli = ',';
    });

    $('#' + frmId + '_tip').text(tips);
    if ("undefined" == typeof(submit) || submit == true) {
        $('#' + frmId).submit();
    }
}

function toggleAllItem(id, frmId, name) {
    var min = 3;
    var flag = 0;
    var checked = $('#' + id).attr('checked');

    SELECT_ITEMS[frmId] = [];

    $('#' + id).next('label').toggleClass('a');

    $('#' + frmId).find(':checkbox').each(function() {
        if(name + '[]' == $(this).attr('name')) {
            var tmpChk = checked;
            if (checked == false && flag++ < min) {
                tmpChk = true;
            }
            $(this).attr('checked', tmpChk);
            $(this).next('label').toggleClass('a', tmpChk);

            if (tmpChk == true) {
                SELECT_ITEMS[frmId].push($(this).val());
            }
        }
     });

    doSelector(frmId, null, false, false, name);
}
