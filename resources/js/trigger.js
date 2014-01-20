// JavaScript Document
$(document).ready(function(){
  $(function() {
  	$( "#dateselfirst").datepicker();
  	$( "#dateselsecond").datepicker();
  	$( "#ad_dateselfirst").datepicker();
  	$( "#ad_dateselsecond").datepicker();
  });
  function MM_jumpMenu(targ,selObj,restore){ //v3.0
    eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
    if (restore) selObj.selectedIndex=0;
  }
});

function getPersonSelect(aid,uid){
	$('#ad_frm').find('#button').attr('disabled',true);
	$.post("/api/api_person.php", {aid:aid}, function(data){
		$('#ad_frm').find('#button').attr('disabled',false);
		if(data.result){
			var str_html = '';
			if($(".person_select").length == 0){
				str_html += "<select class=\"person_select\" name=\"person_select\">";
				str_html += "<option value=\"\">-选择店员-</option>";
				for(x in data.info){
					str_html += "<option value=\"" + data.info[x].id + "\"";
					if(uid == data.info[x].id){
						str_html += "selected";
					}
					str_html += ">" + data.info[x].real_name + "</option>";
				}
				str_html += "</select>";
				$("#channel_agent_select").append(str_html);
			}else{
				str_html += "<option value=\"\">-选择店员-</option>";
				for(x in data.info){
					str_html += "<option value=\"" + data.info[x].id + "\">" + data.info[x].real_name + "</option>";
				}
				$(".person_select").html(str_html);
			}
			$(".person_select").change(function(){
				$(this).blur();
				var uid = $(this).val();
				if(uid != ''){
					$("#ad_search_select_value").val(uid);
					$("#ad_search_user_type_value").val(1);
				}else{
					if($(".agent_select3").val() != ''){
						$("#ad_search_select_value").val($(".agent_select3").val());
					}else{
						$("#ad_search_select_value").val('');
					}
					$("#ad_search_user_type_value").val(2);
				}
			});
		}
	},"json");
}
function getAgentSelect(pid,cid){
	$('#ad_frm').find('#button').attr('disabled',true);
	$.post("/api/api_agent.php", {pid:pid}, function(data){
		$('#ad_frm').find('#button').attr('disabled',false);
		if(data.result){
			var str_html = '';
			if($(".agent_select" + data.info[0].level).length == 0){
				str_html += "<select class=\"agent_select" + data.info[0].level + "\" name=\"agent_select" + data.info[0].level + "\">";
				str_html += "<option value=\"\">-选择子渠道-</option>";
				for(x in data.info){
					str_html += "<option value=\"" + data.info[x].id + "\"";
					if(cid == data.info[x].id){
						str_html += "selected";
					}
					str_html += ">" + data.info[x].name + "</option>";
				}
				str_html += "</select>";
				$("#channel_agent_select").append(str_html);
			}else{
				str_html += "<option value=\"\">-选择子渠道-</option>";
				for(x in data.info){
					str_html += "<option value=\"" + data.info[x].id + "\">" + data.info[x].name + "</option>";
				}
				$(".agent_select" + data.info[0].level).html(str_html);
			}
			$(".agent_select" + data.info[0].level).change(function(){
				$(this).blur();
				var pid = $(this).val();
				if(pid != ''){
					$("#ad_search_select_value").val(pid);
				}else{
					$("#ad_search_select_value").val($(".agent_select2").val());	
				}
				$("#ad_search_user_type_value").val(2);
				if(data.info[0].level < 3){
					var next_select_classname = parseInt(data.info[0].level)+1;
					if(pid == ''){
						$(".agent_select"+next_select_classname).remove();
						$(".person_select").remove();
					}
					_level_flag = 2;
					getAgentSelect(pid);
				}else{
					if(pid == ''){
						$(".person_select").remove();
					}
					_level_flag = pid == '' ? 2 : 1;
					getPersonSelect(pid);
				}
			});
		}else{
			$(".agent_select3").remove();
			$(".person_select").remove();
		}
		
	},"json");
}