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

	if($objLogin->IsLoggedInAs('owner','mainadmin')){	
		draw_title_bar(_ASSIGN_PATIENT);
		draw_appointment_bar(2);	

        draw_content_start();
		Appointments::DrawAppointmentAssignPatient();
        draw_content_end();

	}else {
		draw_title_bar(_APPOINTMENTS);
		draw_important_message(_NOT_AUTHORIZED);
	}		
}else{
	draw_title_bar(_APPOINTMENTS);
	draw_important_message(_NOT_AUTHORIZED);
}
	
?>