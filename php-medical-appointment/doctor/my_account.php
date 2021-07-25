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

	draw_title_bar(prepare_breadcrumbs(array(_MY_ACCOUNT=>'',_EDIT_MY_ACCOUNT=>'')));

	$action 	= MicroGrid::GetParameter('action');
	$rid    	= $objLogin->GetLoggedID();
	$email      = MicroGrid::GetParameter('email', false);
	$first_name = MicroGrid::GetParameter('first_name', false);
	$last_name  = MicroGrid::GetParameter('last_name', false);
	
	if($action=='update'){
		if($objDoctors->UpdateRecord($rid)){
			if(!empty($email)) $objLogin->UpdateLoggedEmail($email);
			$objLogin->UpdateLoggedFirstName(encode_text($first_name));
			$objLogin->UpdateLoggedLastName(encode_text($last_name));
			$msg = draw_success_message(_UPDATING_OPERATION_COMPLETED, false);
		}else{
			$msg = draw_important_message($objDoctors->error, false);
		}		
	}
	
	echo $msg.'<br>';
	
	$objDoctors->DrawEditMode($rid, array('reset'=>true, 'cancel'=>false));
?>

	<hr size="1" noshade="noshade" />
	<?php draw_sub_title_bar(_CHANGE_YOUR_PASSWORD); ?>
	<form action="index.php?doctor=my_account" method="post">
	<?php draw_hidden_field('task', 'change_password'); ?>
	<?php draw_token_field(); ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="2" class="main_text">
	<tr>
		<td width="150px">&nbsp;<?php echo _PASSWORD;?> <span class="required">*</span>:</td>
		<td width="405px"><input class="form_text" name="password_one" type="password" size="25" maxlength="15"></td>
	</tr>
	<tr>
		<td>&nbsp;<?php echo _RETYPE_PASSWORD;?> <span class="required">*</span>:</td>
		<td colspan="2"><input class="form_text" name="password_two" type="password" size="25" maxlength="15"></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
		<td colspan="2" style="padding-left:0px;" colspan="2"><input class="form_button" type="submit" name="submit" value="<?php echo _BUTTON_CHANGE_PASSWORD; ?>"></td>
		<td></td>
	</tr>
	</table>
	</form>
	<br />
	
	<hr size="1" noshade="noshade" />
	<table cellspacing="1" cellpadding="2" width="100%">
	<tr>
		<td colspan="3" align="right">
			<input type="button" class="form_button" name="btnRemoveAccount" id="btnRemoveAccount" value="<?php echo _REMOVE_ACCOUNT; ?>" onclick="javascript:appGoTo('doctor=remove_account');" />
		</td>
	</tr>		
	</table>
	<br>

<?php
}else{
	draw_title_bar(_DOCTORS);
	draw_message(str_replace('_ACCOUNT_', 'doctor', _MUST_BE_LOGGED));
}
?>