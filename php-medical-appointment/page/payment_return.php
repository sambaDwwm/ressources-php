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

if(Modules::IsModuleInstalled('payments') && ModulesSettings::Get('payments', 'is_active') == 'yes'){

	draw_title_bar(
		prepare_breadcrumbs(array(_MY_ACCOUNT=>'',_MEMBERSHIP_PLANS=>'',_ORDER_COMPLETED=>''))
	);
	
	draw_content_start();
		draw_success_message(_ORDER_WAS_COMPLETED_MSG);
	draw_content_end();		
}else{
    draw_important_message(_NOT_AUTHORIZED);
}
?>