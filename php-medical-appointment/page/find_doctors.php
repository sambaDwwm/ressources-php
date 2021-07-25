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

$doctor_speciality = isset($_POST['doctor_speciality']) ? prepare_input($_POST['doctor_speciality']) : '';
$doctor_name       = isset($_POST['doctor_name']) ? prepare_input($_POST['doctor_name']) : '';
$doctor_location   = isset($_POST['doctor_location']) ? prepare_input($_POST['doctor_location']) : '';
$doctor_id         = isset($_REQUEST['docid']) ? (int)$_REQUEST['docid'] : '';
$search_params     = array('speciality' => $doctor_speciality, 'name' => $doctor_name, 'location' => $doctor_location, 'doctor_id' => $doctor_id);
$access_level      = ModulesSettings::Get('appointments', 'schedules_access_level');
$speciality_name   = Specialities::GetSpecialityName($doctor_speciality);

draw_title_bar(
    prepare_breadcrumbs(array(
        _SEARCH_RESULTS=>'',
        $speciality_name=>''
    ))
);

draw_content_start();
if($access_level == 'public' || ($access_level == 'registered' && $objLogin->IsLoggedIn())){
    if(empty($doctor_speciality) && empty($doctor_name) && empty($doctor_location) && empty($doctor_id)){
        draw_important_message(_NO_SEARCH_CRITERIA_SELECTED);
        if($objLogin->IsLoggedInAs('owner','mainadmin')) Doctors::DrawDoctorFindForm();
        echo '<br>';
        Specialities::DrawAllAsLinks();				
    }else{
        if($objLogin->IsLoggedInAs('owner','mainadmin')) Doctors::DrawDoctorFindForm(array('doctor_speciality'=>$doctor_speciality));
        Doctors::DrawSearchResult(Doctors::SearchFor($search_params), $search_params);    
    }    
}else{
    draw_important_message(str_replace('_ACCOUNT_', 'patient', _MUST_BE_LOGGED));  
}
draw_content_end();	
	
?>