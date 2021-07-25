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

if($objLogin->IsLoggedInAs('owner','mainadmin') && Modules::IsModuleInstalled('appointments')){	

	draw_title_bar(
		prepare_breadcrumbs(array(_APPOINTMENTS=>'',_MANAGEMENT=>'',_CREATE_APPOINTMENT=>''))
	);

	draw_content_start();	
	Doctors::DrawAppointmentsBlock();
	draw_content_end();	
	
}else{
	draw_title_bar(_ADMIN);
	draw_important_message(_NOT_AUTHORIZED);
}

?>