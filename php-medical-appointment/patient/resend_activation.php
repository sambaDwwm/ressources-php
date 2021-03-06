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

$act 		    = isset($_POST['act']) ? prepare_input($_POST['act']) : '';
$password_sent 	= (bool)Session::Get('patient_email_resent');
$email 			= isset($_POST['email']) ? prepare_input($_POST['email']) : '';
$msg 			= '';

if($act == 'resend'){
    if(!$password_sent){
		if(Patients::Reactivate($email)){
			$msg = draw_success_message(str_replace('_EMAIL_', $email, _ACTIVATION_EMAIL_WAS_SENT), false);
			Session::Set('patient_email_resent', true);
		}else{
			$msg = draw_important_message(Patients::GetStaticError(), false);					
		}
	}else{
		$msg = draw_message(_ACTIVATION_EMAIL_ALREADY_SENT, false);
	}
}


// Check if patient is logged in
if(!$objLogin->IsLoggedIn() && ModulesSettings::Get('patients', 'allow_registration') == 'yes'){		

    // Draw title bar
    draw_title_bar(prepare_breadcrumbs(array(_PATIENTS=>'',_RESEND_ACTIVATION_EMAIL=>'')));

	echo $msg;
?>
	<div class="pages_contents">
	<form action="index.php?patient=resend_activation" method="post">
		<?php draw_hidden_field('act', 'resend'); ?>
		<?php draw_hidden_field('type', 'patient'); ?>
		<?php draw_token_field(); ?>
				
		<table class="loginForm" width="96%" border="0">
		<tr>
			<td colspan="2">
				<?php echo '<p>'._RESEND_ACTIVATION_EMAIL_MSG.'</p>'; ?>
			</td>
		</tr>
		<tr>
			<td width="15%" nowrap='nowrap'><?php echo _EMAIL_ADDRESS;?>:</td>
			<td width="85%"><input class="form_text" type="text" name="email" id="resend_email" size="22" maxlength="70" autocomplete="off" /></td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>			
		<tr>
			<td colspan="2">
				<input class="form_button" type="submit" name="btnSend" value="<?php echo _SEND;?>">
			</td>
		</tr>
		<tr><td colspan='2' nowrap='nowrap' height='5px'></td></tr>		
		<tr>
			<td colspan='2'>
				<?php
					if(ModulesSettings::Get('patients', 'allow_login') == 'yes'){
						echo prepare_permanent_link('index.php?patient=login', _PATIENT_LOGIN).'<br />';
					}				
					if(ModulesSettings::Get('patients', 'allow_registration') == 'yes'){
						echo prepare_permanent_link('index.php?patient=create_account', _CREATE_ACCOUNT);
					}
				?>
			</td>
		</tr>
		<tr><td colspan='2' nowrap='nowrap' height='5px'></td></tr>		
		</table>
	</form>
	</div>
	<script type="text/javascript">
	appSetFocus('resend_email');
	</script>	
<?php
	//draw_content_end();	
}else if($objLogin->IsLoggedInAsPatient()){
    draw_title_bar(_PATIENTS);
	draw_important_message(_ALREADY_LOGGED);
}else{
    draw_title_bar(_PATIENTS);
	draw_important_message(_NOT_AUTHORIZED);
}
?>