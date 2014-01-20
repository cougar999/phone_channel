<?php
function smarty_function_member($params, &$smarty) {

	$ret = $params['ret'];
	$tag = $params['tag'];

	switch ($tag){
		case "login":
			$username = addslashes(htmlspecialchars($_POST['username']));
			$password = addslashes(htmlspecialchars($_POST['password']));

			if (!empty($ret)) {
				return $smarty->assign($ret , loginMember($username,$password));
			}else{
				return loginMember($params);
			}
		break;

		case "info":
			$arr_input['real_id']= $_SESSION['real_id'];
			$arr_input['user_type'] = $_SESSION['user_type'];
			if (!empty($ret)) {
				return $smarty->assign($ret , getMemberInfo($arr_input));
			}else{
				return getMemberInfo($arr_input);
			}
		break;

		case "logout":
			$arr_input['real_id']= $_SESSION['real_id'];
			$arr_input['user_type'] = $_SESSION['user_type'];
			
			if (!empty($ret)) {
				return $smarty->assign($ret , logoutMember($arr_input));
			}else{
				return logoutMember($arr_input);
			}
			
			break;

		case "online":

			$count = $params['count'];

			if (!empty($ret)) {
				return $smarty->assign($ret , getMemberOnlineList($count));
			}else{
				return getMemberOnlineList($count);
			}

			break;

		case "rememberlogin":
			$arr_input['real_id']= $_SESSION['real_id'];
			$arr_input['user_type'] = $_SESSION['user_type'];
			if(!empty($_SESSION['userid'])){
				rememberLogin($arr_input);
			}
			
			break;
			
		case "getmemberagentinfo":
			
			if (!empty($ret)) {
				return $smarty->assign($ret , getMemberAgentInfo($params));
			}else{
				return getMemberAgentInfo($params);
			}
			
		break;
		
		case "member":
			if (!empty($ret)) {
				return $smarty->assign($ret , getMemberByAgent(array('agent_id' =>$_SESSION['real_id'])));
			}else{
				return getMemberByAgent($_SESSION['agent_id']);
			}
			
			break;
	}
}