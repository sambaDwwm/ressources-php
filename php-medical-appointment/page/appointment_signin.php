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

if(Modules::IsModuleInstalled('appointments') && ModulesSettings::Get('appointments', 'is_active') == 'yes'){

	if(!$objLogin->IsLoggedIn()){
		draw_title_bar(_SIGN_IN);
		draw_appointment_bar(2);	

		draw_message(_APPOINTMENT_SIGNIN_ALERT);
		Appointments::DrawAppointmentSignIn();
	}else {
		draw_title_bar(_APPOINTMENTS);
		draw_important_message(_ONLY_PATIENTS_ACCESS);		
	}		
}else{
	draw_title_bar(_APPOINTMENTS);
	draw_important_message(_NOT_AUTHORIZED);
}
	
?>