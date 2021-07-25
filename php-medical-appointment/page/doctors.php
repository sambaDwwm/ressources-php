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

$doctor_id = isset($_REQUEST['docid']) ? (int)$_REQUEST['docid'] : '';

draw_title_bar(_DOCTOR_INFO);

draw_content_start();
if(empty($doctor_id)){
    draw_important_message(_WRONG_PARAMETER_PASSED);
}else{
    Doctors::DrawDoctorInfo($doctor_id);    
}
draw_content_end();	
	
?>