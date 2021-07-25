<?php
/**
* @project ApPHP Medical Appointment
* @copyright (c) 2012 - 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

//--------------------------------------------------------------------------
// *** remote file inclusion, check for strange characters in $_GET keys
// *** all keys with "/", "\", ":" or "%-0-0" are blocked, so it becomes virtually impossible
// *** to inject other pages or websites
foreach($_GET as $get_key => $get_value){
    if(is_string($get_value) && (preg_match("/\//", $get_value) || preg_match("/\[\\\]/", $get_value) || preg_match("/:/", $get_value) || preg_match("/%00/", $get_value))){
        if(isset($_GET[$get_key])) unset($_GET[$get_key]);
        die("A hacking attempt has been detected. For security reasons, we're blocking any code execution.");
    }
}

// *** check token for POST requests
if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
	$token_post = isset($_POST['token']) ? $_POST['token'] : 'post';
	$token_session = isset($_SESSION[INSTALLATION_KEY]['token']) ? $_SESSION[INSTALLATION_KEY]['token'] : 'session';

	if($token_session != $token_post){

		unset($_POST['submition_type']); // for settings page
										 //     vocabulary
										 //     backup
        unset($_POST['submit_type']);    // for Admin my_account page
		unset($_REQUEST['mg_action']);   // for MicroGrid pages
		unset($_POST['task']);           // for room prices,
										 //	    room availability 
										 //     mass_mail/newsletter
										 //     client/confirm_registration
										 //     client/my_account page
		unset($_POST['act']);            // for menus
										 // 	pages
										 // 	languages
										 //     vocabulary
										 //     client/create_account
										 //     client/password_forgotten
        //unset($_POST['tabid']);        // for Tabs operations
	    //unset($_POST['submit_login']); // for login page
	}
}

