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

if($objLogin->IsLoggedInAsPatient()){
	
	$task = isset($_GET['task']) ? prepare_input($_GET['task']) : '';
    $dashboard_state = Session::Get('patient_dashboard_state');
	$actions_msg = array();
	
    if($task == 'close_dashboard'){
        $dashboard_state = 'hidden';
        Session::Set('patient_dashboard_state', 'hidden');
	}else if($task == 'open_dashboard'){
		$dashboard_state = '';
		Session::Set('patient_dashboard_state', '');
    }

	draw_title_bar(prepare_breadcrumbs(array(_GENERAL=>'',_ACCOUNT_PANEL=>'')));
    
?>
	<div style="padding:5px 0;">
	<?php
        if($dashboard_state == ''){
            $msg = '<div id="divDashboardMessages">';
            $msg .= '<img id="divDashboardMessagesImg" src="images/close.png" alt="close" title="'._HIDE.'" onclick="javascript:appGoTo(\'patient=home\',\'&task=close_dashboard\')" />';
            
            $msg .= '<br>'; 
            //$msg = '<div style="padding:9px;min-height:250px">';
            $welcome_text = _WELCOME_PATIENT_TEXT;
            $welcome_text = str_replace('_FIRST_NAME_', $objLogin->GetLoggedFirstName(), $welcome_text);
            $welcome_text = str_replace('_LAST_NAME_', $objLogin->GetLoggedLastName(), $welcome_text);
            $welcome_text = str_replace('_TODAY_', _TODAY.': <b>'.format_datetime(@date('Y-m-d H:i:s'), '', '', true).'</b>', $welcome_text);
            $welcome_text = str_replace('_LAST_LOGIN_', _LAST_LOGIN.': <b>'.format_datetime($objLogin->GetLastLoginTime(), '', _NEVER, true).'</b>', $welcome_text);
            $msg .= $welcome_text;
            $msg .= '<br>'; 
            $msg .= '</div>';		
            draw_message($msg);		
        }else{
            echo '<div id="divDashboardRequired"><a href="javascript:void(0);" onclick="javascript:appGoTo(\'patient=home\',\'&task=open_dashboard\')">'._SHOW_DASHBOARD.'</a></div>';
            echo '<div style="clear:both"></div>';
        }
	?>
    
    <br>
	<div style="padding:10px;">
        <a style="float:<?php echo Application::Get('defined_right'); ?>" href="javascript:void('print')" onclick="javascript:window.print();"><img src="images/printer.png" alt="print" /> <?php echo _PRINT; ?></a>
        <h3><?php echo _UPCOMING_APPOINTMENTS; ?></h3>
        <?php Appointments::DrawAppointmentsByDate('', $objLogin->GetLoggedID(), 'patient'); ?>		
	</div>
    
    </div>
<?php
}else if($objLogin->IsLoggedIn()){
    draw_title_bar(_PATIENTS);
    draw_important_message(_NOT_AUTHORIZED);
}else{
    draw_title_bar(_PATIENTS);
    draw_important_message(str_replace('_ACCOUNT_', 'patient', _MUST_BE_LOGGED));
}
?>