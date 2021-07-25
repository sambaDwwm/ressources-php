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

if(!$objLogin->IsLoggedInAsPatient()){	

	$objSession->SetMessage('notice', str_replace('_ACCOUNT_', 'patient', _MUST_BE_LOGGED));
    redirect_to('index.php?patient=login');

}else if($objLogin->IsLoggedInAsPatient() && Modules::IsModuleInstalled('appointments')){	

	$action = MicroGrid::GetParameter('action');
	$rid    = MicroGrid::GetParameter('rid');
	$mode   = 'view';
	$msg    = '';
	
	$objAppointments = new Appointments();

    // check if rid is valid
	$appointment_info = $objAppointments->GetInfoByID($rid);
    if(!empty($rid) && $appointment_info['patient_id'] != $objLogin->GetLoggedID()){
        $action = '';
        $msg = draw_important_message(_WRONG_PARAMETER_PASSED, false);
    }
	
	if($action=='add'){		
		$mode = 'add';
	}else if($action=='create'){
		$mode = 'view';
	}else if($action=='edit'){
		$mode = 'edit';
	}else if($action=='update'){
		if($objAppointments->UpdateRecord($rid)){
			$msg = draw_success_message(_UPDATING_OPERATION_COMPLETED, false);
			$mode = 'view';
		}else{
			$msg = draw_important_message($objAppointments->error, false);
			$mode = 'edit';
		}		
	}else if($action=='delete'){
		$mode = 'view';
	}else if($action=='details'){		
		$mode = 'details';		
	}else if($action=='cancel_add'){		
		$mode = 'view';		
	}else if($action=='cancel_edit'){				
		$mode = 'view';
	}else if($action=='cancel_appointment'){
		if($objAppointments->CancelAppointment($rid)){
			$msg = draw_success_message(_CANCEL_OPERATION_COMPLETED, false);
		}else{
			$msg = draw_important_message($objAppointments->error, false);
		}
        $mode = 'view';
    }else{
        $action = '';
    }

}

?>