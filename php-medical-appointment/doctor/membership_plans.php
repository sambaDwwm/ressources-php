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

if($objLogin->IsLoggedInAsDoctor()){
    if(Modules::IsModuleInstalled('payments') && ModulesSettings::Get('payments', 'is_active') == 'yes'){
		draw_title_bar(prepare_breadcrumbs(array(_MY_ACCOUNT=>'',_MEMBERSHIP_PLANS=>'')));	
		Login::SetMembershipInfo();
        MembershipPlans::DrawPlans();
	}else{
		draw_title_bar(_DOCTORS); 
		draw_important_message(_NOT_AUTHORIZED);
	}	
}else{
	draw_title_bar(_DOCTORS);
	draw_message(str_replace('_ACCOUNT_', 'doctor', _MUST_BE_LOGGED));
}

?>