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

	$task = isset($_GET['task']) ? prepare_input($_GET['task']) : '';
	$alert_state = Session::Get('doctor_alert_state');
    $dashboard_state = Session::Get('doctor_dashboard_state');
	$actions_msg = array();
	
	if($task == 'close_alert'){
	    $alert_state = 'hidden';
		Session::Set('doctor_alert_state', 'hidden');
	}else if($task == 'open_alert'){
		$alert_state = '';
		Session::Set('doctor_alert_state', '');
	}else if($task == 'close_dashboard'){
        $dashboard_state = 'hidden';
        Session::Set('doctor_dashboard_state', 'hidden');
	}else if($task == 'open_dashboard'){
		$dashboard_state = '';
		Session::Set('doctor_dashboard_state', '');
    }
	
	$appointments_count = Appointments::AwaitingApprovalCount($objLogin->GetLoggedID());
	if($appointments_count > 0){
		$appointments_msg = str_replace('_COUNT_', $appointments_count, _APPOINTMENTS_AWAITING_APPROVAL_ALERT);
		$appointments_msg = str_replace('_HREF_', 'index.php?doctor=appointments', $appointments_msg);
		$actions_msg[] = $appointments_msg;
	}

	$specialities_count = DoctorSpecialities::SpecialitiesCount($objLogin->GetLoggedID());
    if(!$specialities_count){
        $actions_msg[] = str_replace('_HREF_', 'index.php?doctor=my_specialities', _DOCTOR_MISSING_SPECIALITIES_ALERT);
    }
    
	draw_title_bar(prepare_breadcrumbs(array(_DOCTOR=>'',_ACCOUNT_PANEL=>'')));
    
	if(count($actions_msg) > 0){        
		if($alert_state == ''){
			$msg = '<div id="divAlertMessages">
				<img src="images/close.png" alt="close" style="cursor:pointer;float:'.Application::Get('defined_right').';" title="'._HIDE.'" onclick="javascript:appGoTo(\'doctor=home\',\'&task=close_alert\')" />
				<img src="images/action_required.png" alt="action" style="margin-bottom:-3px;" />&nbsp;&nbsp;<b>'._ACTION_REQUIRED.'</b>: 
				<ul style="margin-top:7px;margin-bottom:7px;">';
				foreach($actions_msg as $single_msg){
					$msg .= '<li>'.$single_msg.'</li>';
				}
			$msg .= '</ul></div>';
			draw_important_message($msg, true, false);        
		}else{
			echo '<div id="divAlertRequired"><a href="javascript:void(0);" onclick="javascript:appGoTo(\'doctor=home\',\'&task=open_alert\')">'._SHOW_ALERT_WINDOW.'</a></div>';
            echo '<div style="clear:both"></div>';
		}
    }
    
?>
	<div style="padding:5px 0;margin-bottom:10px;">
	<?php	
        if($dashboard_state == ''){
            $msg = '<div id="divDashboardMessages">';
            $msg .= '<img id="divDashboardMessagesImg" src="images/close.png" alt="close" title="'._HIDE.'" onclick="javascript:appGoTo(\'doctor=home\',\'&task=close_dashboard\')" />';
            
            $msg .= '<br>'; 
            $welcome_text = _WELCOME_DOCTOR_TEXT;
            $welcome_text = str_replace('_FIRST_NAME_', $objLogin->GetLoggedFirstName(), $welcome_text);
            $welcome_text = str_replace('_LAST_NAME_', $objLogin->GetLoggedLastName(), $welcome_text);
            $welcome_text = str_replace('_TODAY_', _TODAY.': <b>'.format_datetime(@date('Y-m-d H:i:s'), '', '', true).'</b>', $welcome_text);
            $welcome_text = str_replace('_LAST_LOGIN_', _LAST_LOGIN.': <b>'.format_datetime($objLogin->GetLastLoginTime(), '', _NEVER, true).'</b>', $welcome_text);
            $msg .= $welcome_text;
            $msg .= '<br>'; 

            Login::SetMembershipInfo();
            $doctor_images = DoctorImages::GetImagesForDoctor($objLogin->GetLoggedID());
            $doctor_addresses = DoctorAddresses::GetAddresses($objLogin->GetLoggedID());			
            
            $msg .= '<p><b>'._MEMBERSHIP_INFO.'</b>:</p>'; 
            $msg .= '<p><b>&#8226;</b> '._NAME.': <b>'.(($objLogin->GetMembershipInfo('plan_name') != '') ? $objLogin->GetMembershipInfo('plan_name') : '--').'</b></p>'; 
            $msg .= '<p><b>&#8226;</b> '._MEMBERSHIP_EXPIRES.': <b>'.(($objLogin->GetMembershipInfo('plan_name') != '') ? format_date($objLogin->GetMembershipInfo('expires'), '', _NEVER, true) : '--').'</b></p>';          
            
            $msg .= '<p><b>&#8226;</b> '._IMAGES_COUNT.': <b>'.$doctor_images[1].' / '.$objLogin->GetMembershipInfo('images_count').'</b></p>'; 
            if($objLogin->GetMembershipInfo('images_count') > 0 && $doctor_images[1] == 0) $msg .= '<p class="membership-alert">'.str_replace('_HREF_', 'index.php?doctor=my_images_upload', _DOCTOR_ADD_IMAGES_ALERT).'</p>';
            
            $msg .= '<p><b>&#8226;</b> '._ADDRESSES_COUNT.': <b>'.$doctor_addresses[1].' / '.$objLogin->GetMembershipInfo('addresses_count').'</b></p>'; 
            if($objLogin->GetMembershipInfo('addresses_count') > 0 && $doctor_addresses[1] == 0) $msg .= '<p class="membership-alert">'.str_replace('_HREF_', 'index.php?doctor=my_addresses', _DOCTOR_ADD_ADDRESSES_ALERT).'</p>';
            
            $msg .= '<p><b>&#8226;</b> '._SHOW_IN_SEARCH.': <b>'.($objLogin->GetMembershipInfo('show_in_search') == '1' ? _YES : _NO).'</b></p>';            
            if(Modules::IsModuleInstalled('payments') && ModulesSettings::Get('payments', 'is_active') == 'yes'){
                if(!$objLogin->GetMembershipInfo('show_in_search')) $msg .= '<p class="membership-alert">'.str_replace('_HREF_', 'index.php?doctor=membership_plans', _DOCTOR_UPGRADE_MEMBERSHIP_ALERT).'</p>';
            }
            $msg .= '<br>';
            
            $msg .= '</div>';		
            draw_message($msg);		
        }else{
            echo '<div id="divDashboardRequired"><a href="javascript:void(0);" onclick="javascript:appGoTo(\'doctor=home\',\'&task=open_dashboard\')">'._SHOW_DASHBOARD.'</a></div>';
            echo '<div style="clear:both"></div>';
        }
	?>

    <br>
	<div style="padding:10px;">
        <a style="float:<?php echo Application::Get('defined_right'); ?>" href="javascript:void('print')" onclick="javascript:window.print();"><img src="images/printer.png" alt="print" /> <?php echo _PRINT; ?></a>
        <h3><?php echo _TODAY_APPOINTMENTS; ?></h3>
        <?php Appointments::DrawAppointmentsByDate(date('Y-m-d'), $objLogin->GetLoggedID(), 'doctor'); ?>		
	</div>
    
    </div>
<?php
}else if($objLogin->IsLoggedIn()){
    draw_title_bar(prepare_breadcrumbs(array(_DOCTOR=>'')));
    draw_important_message(_NOT_AUTHORIZED);
}else{
    draw_title_bar(prepare_breadcrumbs(array(_DOCTOR=>'')));
    draw_message(str_replace('_ACCOUNT_', 'doctor', _MUST_BE_LOGGED));
}
?>