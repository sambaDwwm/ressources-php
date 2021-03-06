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

if($objLogin->IsLoggedInAsAdmin()){

	$task = isset($_GET['task']) ? prepare_input($_GET['task']) : '';
	$alert_state = Session::Get('alert_state');
    $dashboard_state = Session::Get('dashboard_state');
	
	if($task == 'close_alert'){
	    $alert_state = 'hidden';
		Session::Set('alert_state', 'hidden');
	}else if($task == 'open_alert'){
		$alert_state = '';
		Session::Set('alert_state', '');
	}else if($task == 'close_dashboard'){
        $dashboard_state = 'hidden';
        Session::Set('dashboard_state', 'hidden');
	}else if($task == 'open_dashboard'){
		$dashboard_state = '';
		Session::Set('dashboard_state', '');
    }
    
	draw_title_bar(prepare_breadcrumbs(array(_GENERAL=>'',_HOME=>'')));
	
    // draw important messages 
	// ---------------------------------------------------------------------
    $actions_msg = array();
    if($objLogin->IsLoggedInAs('owner', 'mainadmin') && (file_exists('install.php') || file_exists('install/'))){
    	$actions_msg[] = '<span class="darkred">'._INSTALL_PHP_EXISTS.'</span>';
    }

	if(SITE_MODE == 'development') $actions_msg[] = '<span class="darkred">'._SITE_DEVELOPMENT_MODE_ALERT.'</span>';
    
    if($objLogin->IsLoggedInAs('owner', 'mainadmin')){
		$arr_folders = array('images/upload/', 'images/flags/', 'images/banners/', 'images/gallery/', 'images/doctors/', 'tmp/backup/', 'tmp/export/', 'tmp/cache/', 'feeds/');
		$arr_folders_not_writable = '';
		foreach($arr_folders as $folder){
			if(!is_writable($folder)){
				if($arr_folders_not_writable != '') $arr_folders_not_writable .= ', ';
				$arr_folders_not_writable .= $folder;
			}
		}
		if($arr_folders_not_writable != ''){
			$actions_msg[] = _NO_WRITE_ACCESS_ALERT.' <b>'.$arr_folders_not_writable.'</b>';
		}    	
    }

	$admin_email = $objSettings->GetParameter('admin_email');    
    if($objLogin->IsLoggedInAs('owner', 'mainadmin') && ($admin_email == '' || preg_match('/yourdomain/i', $admin_email))){
        $actions_msg[] = _DEFAULT_EMAIL_ALERT;
    }
	
	$own_email = $objLogin->GetLoggedEmail();    
	if($own_email == '' || preg_match('/yourdomain/i', $own_email)){
		$actions_msg[] = _DEFAULT_OWN_EMAIL_ALERT;
	}
    
	if($objLogin->IsLoggedInAs('owner', 'mainadmin') && Modules::IsModuleInstalled('contact_us')){
		$admin_email_to = ModulesSettings::Get('contact_us', 'email');
        if($admin_email_to == '' || preg_match('/yourdomain/i', $admin_email_to)){
            $actions_msg[] = _CONTACTUS_DEFAULT_EMAIL_ALERT;
        }
    }    
	
	if(Modules::IsModuleInstalled('comments')){
		$comments_allow	= ModulesSettings::Get('comments', 'comments_allow');
		$comments_count = Comments::AwaitingModerationCount();
		if($comments_allow == 'yes' && $comments_count > 0){
			$actions_msg[] = str_replace('_COUNT_', $comments_count, _COMMENTS_AWAITING_MODERATION_ALERT);			
		}
	}

	$appointments_count = Appointments::AwaitingApprovalCount();
	if($appointments_count > 0){
		$appointments_msg = str_replace('_COUNT_', $appointments_count, _APPOINTMENTS_AWAITING_APPROVAL_ALERT);
		$appointments_msg = str_replace('_HREF_', 'index.php?admin=mod_appointments_management', $appointments_msg);
		$actions_msg[] = $appointments_msg;
	}

	if(ModulesSettings::Get('patients', 'reg_confirmation') == 'by admin'){
		$patients_count = Patients::AwaitingAprovalCount();
		if($patients_count > 0){
			$actions_msg[] = str_replace('_COUNT_', $patients_count, _PATIENTS_AWAITING_MODERATION_ALERT);			
		}
	}	

	if(ModulesSettings::Get('doctors', 'reg_confirmation') == 'by admin'){
		$doctors_count = Doctors::AwaitingAprovalCount();
		if($doctors_count > 0){
			$actions_msg[] = str_replace('_COUNT_', $doctors_count, _DOCTORS_AWAITING_MODERATION_ALERT);			
		}
	}	
	
	if(count($actions_msg) > 0){        
		if($alert_state == ''){
			$msg = '<div id="divAlertMessages">
				<img src="images/close.png" alt="close" style="cursor:pointer;float:'.Application::Get('defined_right').';margin-right:-3px;" title="'._HIDE.'" onclick="javascript:appGoTo(\'admin=home\',\'&task=close_alert\')" />
				<img src="images/action_required.png" alt="action" style="margin-bottom:-3px;" />&nbsp;&nbsp;<b>'._ACTION_REQUIRED.'</b>: 
				<ul>';
				foreach($actions_msg as $single_msg){
					$msg .= '<li>'.$single_msg.'</li>';
				}
			$msg .= '</ul></div>';
			draw_important_message($msg, true, false);        
		}else{
			echo '<div id="divAlertRequired"><a href="javascript:void(0);" onclick="javascript:appGoTo(\'admin=home\',\'&task=open_alert\')">'._SHOW_ALERT_WINDOW.'</a></div>';
		}
    }

	if($dashboard_state == ''){
        // draw welcome message
        $msg = '<div id="divDashboardMessages">
        <img src="images/close.png" alt="close" title="'._HIDE.'" onclick="javascript:appGoTo(\'admin=home\',\'&task=close_dashboard\')" />
    
        <div class="site_version">'._VERSION.': '.CURRENT_VERSION.'</div>
        <p>'._TODAY.': <b>'.format_datetime(date('Y-m-d H:i:s'), '', '', true).'</b></p>
        <p>'._LAST_LOGIN.': <b>'.format_datetime($objLogin->GetLastLoginTime(), '', _NEVER, true).'</b></p>';
    
        $msg .= _HOME_PAGE_WELCOME_TEXT.'
        </div>';        
    }else{
        $msg = '';
		echo '<div id="divDashboardRequired"><a href="javascript:void(0);" onclick="javascript:appGoTo(\'admin=home\',\'&task=open_dashboard\')">'._SHOW_DASHBOARD.'</a></div>';        
    }
    
    
    draw_message($msg, true, false);

    // draw dashboard modules
	$objModules = new Modules();
	echo '<div style="padding:2px 2px 40px 2px;">';
	$objModules->DrawModulesOnDashboard();
	echo '<div style="clear:both;"></div>';
	echo '</div>';

    echo '<div style="text-align:right;padding:20px 18px 0 0;vertical-align:bottom;">'.$objSiteDescription->DrawFooter(false).'</div>';

}else{
	draw_title_bar(_ADMIN);
    draw_important_message(_NOT_AUTHORIZED);
}
?>