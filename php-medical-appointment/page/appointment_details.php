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

$prm = isset($_GET['prm']) ? base64_decode($_GET['prm']) : '';
$get = array();
parse_str($prm, $get);

$params = array();
$params['docid']	  = isset($get['docid']) ? (int)$get['docid'] : '';
$params['dspecid']	  = isset($get['dspecid']) ? (int)$get['dspecid'] : '';
$params['insid']	  = isset($get['insid']) ? (int)$get['insid'] : '';
$params['vrid']	      = isset($get['vrid']) ? (int)$get['vrid'] : '';
$params['schid'] 	  = isset($get['schid']) ? (int)$get['schid'] : '';
$params['daddid'] 	  = isset($get['daddid']) ? (int)$get['daddid'] : '';
$params['date'] 	  = isset($get['date']) ? prepare_input($get['date']) : '';
$params['start_time'] = isset($get['start_time']) ? prepare_input($get['start_time']) : '';
$params['duration']   = isset($get['duration']) ? (int)$get['duration'] : '';
$access_level         = ModulesSettings::Get('appointments', 'schedules_access_level');

if(Modules::IsModuleInstalled('appointments') && ModulesSettings::Get('appointments', 'is_active') == 'yes'){
	draw_title_bar(_APPOINTMENT_DETAILS);
    if($access_level == 'public' || ($access_level == 'registered' && $objLogin->IsLoggedIn())){
        draw_appointment_bar(1);    
        draw_content_start();	
        if(Appointments::VerifyAppointment($params)){
            Appointments::DrawAppointmentDetails($params);	
        }else{
            draw_important_message(Appointments::GetStaticError());
        }
        draw_content_end();
    }else{
        draw_important_message(str_replace('_ACCOUNT_', 'patient', _MUST_BE_LOGGED));  
    }
}else{
	draw_title_bar(_APPOINTMENTS);
	draw_important_message(_NOT_AUTHORIZED);
}
	
?>