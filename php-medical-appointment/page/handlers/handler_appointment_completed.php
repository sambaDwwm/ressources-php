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

if(($objLogin->IsLoggedInAsPatient() || $objLogin->IsLoggedInAs('owner','mainadmin')) &&
   Modules::IsModuleInstalled('appointments') &&
   ModulesSettings::Get('appointments', 'is_active') == 'yes'){

	$task 					= isset($_POST['task']) ? prepare_input($_POST['task']) : '';

	$params = array();
	$params['docid']		= isset($_POST['docid']) ? (int)$_POST['docid'] : '';
	$params['schid'] 		= isset($_POST['schid']) ? (int)$_POST['schid'] : '';
	$params['daddid'] 	    = isset($_POST['daddid']) ? (int)$_POST['daddid'] : '';
	$params['date'] 		= isset($_POST['date']) ? prepare_input($_POST['date']) : '';
	$params['start_time'] 	= isset($_POST['start_time']) ? prepare_input($_POST['start_time']) : '';
	$params['duration'] 	= isset($_POST['duration']) ? (int)$_POST['duration'] : '';
	$params['dspecid']      = isset($_POST['dspecid']) ? (int)$_POST['dspecid'] : '';
    $params['insid']        = isset($_POST['insid']) ? (int)$_POST['insid'] : '';
    $params['vrid']         = isset($_POST['vrid']) ? (int)$_POST['vrid'] : '';
	$params['for_whom']     = isset($_POST['for_whom']) ? prepare_input($_POST['for_whom']) : '';
	$params['first_visit']  = isset($_POST['first_visit']) ? prepare_input($_POST['first_visit']) : '';
    $params['patient_id']   = isset($_POST['patient_id']) ? prepare_input($_POST['patient_id']) : '';

	if($task == 'complete_appointment' && Appointments::VerifyAppointment($params)){
		if(Appointments::DoAppointment($params)){
			$msg = draw_success_message(Appointments::GetStaticMessage(), false);
		}else{
			$msg = draw_important_message(Appointments::GetStaticError(), false);
		}		
	}else{
		$msg = draw_important_message(Appointments::GetStaticError(), false);
	}
	$objSession->SetMessage('notice', $msg);    
	
}
	
?>