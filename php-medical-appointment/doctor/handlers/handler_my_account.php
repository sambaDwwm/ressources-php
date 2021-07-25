<?php
/**
* @project ApPHP Medical Appointment
* @copyright (c) 2012 - 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

// *** Make sure the file isn't accessed directly
defined('APPHP_EXEC') or die('Restricted Access');
//--------------------------------------------------------------------------

if(!$objLogin->IsLoggedInAsDoctor()){
	$objSession->SetMessage('notice', str_replace('_ACCOUNT_', 'doctor', _MUST_BE_LOGGED));
    redirect_to('index.php?doctor=login');
}else{
	
	$task = isset($_POST['task']) ? prepare_input($_POST['task']) : '';
	$password_one = isset($_POST['password_one']) ? prepare_input($_POST['password_one']) : '';
	$password_two = isset($_POST['password_two']) ? prepare_input($_POST['password_two']) : '';
	$msg = '';
	
	$objDoctors = new Doctors('me');	
	
	if($task == 'change_password'){
		$msg = Doctors::ChangePassword($password_one, $password_two);		
	}

}
