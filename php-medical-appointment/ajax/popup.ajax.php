<?php
/**
* @project ApPHP Medical Appointment
* @copyright (c) 2012 - 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

define('APPHP_EXEC', 'access allowed');
define('APPHP_CONNECT', 'direct');
require_once('../include/base.inc.php');
require_once('../include/connection.php');

$param = isset($_POST['param']) ? prepare_input($_POST['param']) : '';
$id = isset($_POST['id']) ? prepare_input($_POST['id']) : '';
$check_key = isset($_POST['check_key']) ? prepare_input($_POST['check_key']) : '';
$token = isset($_POST['token']) ? prepare_input($_POST['token']) : '';
$session_token = isset($_SESSION[INSTALLATION_KEY]['token']) ? prepare_input($_SESSION[INSTALLATION_KEY]['token']) : '';
$arr = array();

if($objLogin->IsLoggedIn() && $check_key == 'apphpma' && ($token == $session_token) && !empty($param) && !empty($id)){
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // Date in the past
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
	header('Pragma: no-cache'); // HTTP/1.0
	header('Content-Type: application/json');
	
	$arr[] = '"status": "1"';

	if($param == 'doctor' && ($objLogin->IsLoggedInAsAdmin() || $objLogin->IsLoggedInAsPatient())){		
		$result = Doctors::DrawDoctorInfo((int)$id, 'long', 'demo', false, false);
		$arr[] = '"content": '.json_encode(utf8_encode($result));
	}else if($param == 'patient' && ($objLogin->IsLoggedInAsAdmin() || $objLogin->IsLoggedInAsDoctor())){
		ob_start();
		$objPatients = new Patients();
		$objPatients->DrawDetailsMode((int)$id, array('back'=>false));		
		// save the contents of output buffer to the string
		$result = ob_get_contents(); 
		ob_end_clean();		
		
		$arr[] = '"content": '.json_encode(utf8_encode($result));
	}
	
	echo '{';
	echo implode(',', $arr);
	echo '}';
}else{
	// wrong parameters passed!
	$arr[] = '"status": "0"';
	echo '{';
	echo implode(',', $arr);
	echo '}';
}    

?>