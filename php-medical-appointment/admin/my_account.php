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
	
	$objAdmin           = new Admins($objLogin->GetLoggedID());
	$submit_type 	    = isset($_POST['submit_type']) ? prepare_input($_POST['submit_type']) : '';
	$preferred_language = isset($_POST['preferred_language']) ? prepare_input($_POST['preferred_language']) : '';
	$admin_email 	 	= isset($_POST['admin_email']) ? prepare_input($_POST['admin_email']) : '';
	$password_one 	 	= isset($_POST['password_one']) ? prepare_input($_POST['password_one']) : '';
	$password_two 	 	= isset($_POST['password_two']) ? prepare_input($_POST['password_two']) : '';
	$first_name 	 	= isset($_POST['first_name']) ? prepare_input($_POST['first_name']) : '';
	$last_name 	 	    = isset($_POST['last_name']) ? prepare_input($_POST['last_name']) : '';
	$msg                = '';
	$error_field        = '';

	// change password
	if($submit_type == '1'){
		$msg = $objAdmin->ChangeLang($preferred_language);
	}else if($submit_type == '2'){
		$msg = $objAdmin->SavePersonalInfo($admin_email, $first_name, $last_name, $error_field);
	}else if($submit_type == '3'){
		$msg = $objAdmin->ChangePassword($password_one, $password_two, $error_field);
	}

	draw_title_bar(prepare_breadcrumbs(array(_ACCOUNTS=>'',_MY_ACCOUNT=>'')));	

	if($msg == '') draw_message(_ALERT_REQUIRED_FILEDS);
	else echo $msg;

	draw_content_start();
	
	// 'clinicowner'=>_CLINIC_OWNER
	$arr_account_types = array('owner'=>_OWNER, 'admin'=>_ADMIN, 'mainadmin'=>_MAIN_ADMIN);	

?>

	<?php draw_sub_title_bar(_GENERAL_INFO); ?>
	<form action="index.php?admin=my_account" method="post">
	<?php draw_hidden_field('submit_type', '1'); ?>
	<?php draw_token_field(); ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="2" class="main_text">
	<tr>
		<td>&nbsp;<?php echo _ACCOUNT_TYPE;?>:</td>
		<td><?php echo $arr_account_types[$objAdmin->GetParameter('account_type')];?></td>
	</tr>
	<tr>
		<td width="150px">&nbsp;<?php echo _PREFERRED_LANGUAGE;?> <span class="required">*</span>:</td>
		<td>
		<?php
			// display language
			$total_languages = Languages::GetAllActive(); 
			draw_languages_box('preferred_language', $total_languages[0], 'abbreviation', 'lang_name', $objAdmin->GetParameter('preferred_language')); 
		?>
		</td>
	</tr>
	<tr>
		<td width="150px">&nbsp;<?php echo _USERNAME;?>:</td>
		<td><?php echo $objAdmin->account_name;?></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>	
	<tr>
		<td style="padding-left:0px;" colspan="2"><input class="form_button" type="submit" name="submit" value="<?php echo _BUTTON_CHANGE; ?>"></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>	
	</table>
	</form>
	
	<?php draw_sub_title_bar(_PERSONAL_INFORMATION); ?>
	<form action="index.php?admin=my_account" method="post">
	<?php draw_hidden_field('submit_type', '2'); ?>
	<?php draw_token_field(); ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="2" class="main_text">
	<tr>
		<td width="150px">&nbsp;<?php echo _FIRST_NAME;?> <span class="required">*</span>:</td>
		<td><input class="form_text" id="first_name" name="first_name" type="text" size="25" maxlength="32" value="<?php echo $objAdmin->GetParameter('first_name'); ?>"></td>
	</tr>
	<tr>
		<td width="150px">&nbsp;<?php echo _LAST_NAME;?> <span class="required">*</span>:</td>
		<td><input class="form_text" id="last_name" name="last_name" type="text" size="25" maxlength="32" value="<?php echo $objAdmin->GetParameter('last_name'); ?>"></td>
	</tr>
	<tr>
		<td width="150px">&nbsp;<?php echo _EMAIL_ADDRESS;?> <span class="required">*</span>:</td>
		<td><input class="form_text" id="admin_email" name="admin_email" type="text" size="25" maxlength="70" value="<?php echo $objAdmin->GetParameter('email'); ?>"></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td style="padding-left:0px;" colspan="2"><input class="form_button" type="submit" name="submit" value="<?php echo _BUTTON_CHANGE; ?>"></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>	
	</table>	
	</form>

	<?php draw_sub_title_bar(_CHANGE_YOUR_PASSWORD); ?>
	<form action="index.php?admin=my_account" method="post">
	<?php draw_hidden_field('submit_type', '3'); ?>
	<?php draw_token_field(); ?>
	<table width="100%" border="0" cellspacing="0" cellpadding="2" class="main_text">
	<tr>
		<td width="150px">&nbsp;<?php echo _PASSWORD;?> <span class="required">*</span>:</td>
		<td colspan="2" width="405px"><input class="form_text" id="password_one" name="password_one" type="password" size="25" maxlength="15"></td>
	</tr>
	<tr>
		<td>&nbsp;<?php echo _RETYPE_PASSWORD;?> <span class="required">*</span>:</td>
		<td colspan="2"><input class="form_text" id="password_two" name="password_two" type="password" size="25" maxlength="15"></td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
		<td colspan="2" style="padding-left:0px;" colspan="2"><input class="form_button" type="submit" name="submit" value="<?php echo _BUTTON_CHANGE_PASSWORD ?>"></td>
		<td></td>
	</tr>
	</table>
	</form>

<?php
	if($error_field != '') echo '<script type="text/javascript">appSetFocus(\''.$error_field.'\');</script>';

	draw_content_end();	
}else{
	draw_title_bar(_ADMIN);
	draw_important_message(_NOT_AUTHORIZED);
}
?>