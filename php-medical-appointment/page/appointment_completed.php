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
	
	draw_title_bar(_COMPLETED);
	draw_appointment_bar(4);

	echo $objSession->GetMessage('notice');
	//redirect_to('index.php?patient=home', 15000);

}else{
	draw_title_bar(_APPOINTMENTS);
	draw_important_message(_NOT_AUTHORIZED);
}
	
?>