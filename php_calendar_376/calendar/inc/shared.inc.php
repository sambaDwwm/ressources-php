<?php
/**
* @project ApPHP Calendar
* @copyright (c) 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

// security check #1 (with refferer)
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	if(!empty($_SERVER['HTTP_REFERER'])){
		if(!preg_match('/'.$_SERVER['HTTP_HOST'].'/', $_SERVER['HTTP_REFERER'])){
			@header('HTTP/1.1 403 Forbidden');
			exit('Access forbidden');
		}
	}	
}

// security check #2 (check token for POST requests)
if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
	$token_post = isset($_POST['cal_token']) ? $_POST['cal_token'] : '';
	$token_session = isset($_SESSION['cal_token']) ? $_SESSION['cal_token'] : '';
	if(!empty($token_post) && !empty($token_session) && $token_post != $token_session){
		unset($_REQUEST['hid_event_action']);
		unset($_POST['hid_event_action']);
		unset($_REQUEST['hid_event_id']);
		unset($_POST['hid_event_id']);
		//unset($_REQUEST['hid_action']);
		//unset($_POST['hid_view_type']);
		//unset($_REQUEST['hid_previous_action']);
		//unset($_POST['hid_previous_action']);		
	}	
}

