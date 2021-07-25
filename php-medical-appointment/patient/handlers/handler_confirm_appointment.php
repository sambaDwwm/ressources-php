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

if(ModulesSettings::Get('appointments', 'approval_required') == 'by email'){
    
	$appt_number = isset($_REQUEST['n']) ? prepare_input($_REQUEST['n']) : '';
	$task  = isset($_POST['task']) ? prepare_input($_POST['task']) : '';
    $msg = '';
	$confirmed = false;

    if($appt_number != ''){
        $sql = 'SELECT * FROM '.TABLE_APPOINTMENTS.'
                WHERE appointment_number = \''.$appt_number.'\' AND status = 0';
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);		
        if($result[1] > 0){            
			$sql = 'UPDATE '.TABLE_APPOINTMENTS.'
					SET status = 1 
					WHERE appointment_number = \''.$appt_number.'\' AND status = 0';
			database_void_query($sql);
			$msg = draw_success_message(_APPT_CONFIRMED_SUCCESS_MSG, false);
			$confirmed = true;

			////////////////////////////////////////////////////////////
			// send email to patient, admin and doctor here
			Appointments::SendAppointmentEmail('appointment_confirmed_by_email', $appt_number);
			////////////////////////////////////////////////////////////
			
			$msg .= redirect_to('index.php?'.($objLogin->IsLoggedInAsPatient() ? 'patient=my_appointments' : 'patient=login'), 15000, '', false);
        }else{
            if(strlen($appt_number) == 10){
				$confirmed = true;
                $msg = draw_message(_APPT_CONFIRMED_ALREADY_MSG, false);                        
            }else{
				$msg = draw_important_message(_WRONG_APPOINTMENT_CODE, false);
            }		
        }
    }else{
		if($task == 'post_submission') $msg = draw_important_message(str_replace('_FIELD_', _CONFIRMATION_CODE, _FIELD_CANNOT_BE_EMPTY), false);                
    }    
}

?>