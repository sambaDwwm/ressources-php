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

	$submit = isset($_POST['submit']) ? prepare_input($_POST['submit']) : '';
    $user_password = isset($_POST['user_password']) ? prepare_input($_POST['user_password']) : '';
	$account_deleted = false;
    $focus_field = '';
	$msg = '';
	
	if($submit == 'remove'){
		if(strtolower(SITE_MODE) == 'demo'){
			$msg = draw_important_message(_OPERATION_BLOCKED, false);
        }else if($user_password == ''){
            $msg = draw_important_message(_CONF_PASSWORD_IS_EMPTY, false);
            $focus_field = 'user_password';        
        }else if(!$objLogin->CheckAccountPassword('doctor', $user_password)){
            $msg = draw_important_message(_PASSWORD_DO_NOT_MATCH, false);
            $focus_field = 'user_password';
		}else{
			if($objLogin->RemoveAccount()){
				$msg = draw_success_message(_ACCOUNT_WAS_DELETED, false);
				$account_deleted = true;
		
				////////////////////////////////////////////////////////////////
				send_email(
					$objLogin->GetLoggedEmail(),
					$objSettings->GetParameter('admin_email'),
					'account_deleted_by_user',
					array(
						'{USER NAME}'  => $objLogin->GetLoggedName(),
					),
					$objLogin->GetPreferredLang()
				);
				////////////////////////////////////////////////////////////
				
				$objSession->EndSession();
			}else{
				$msg = draw_important_message(_DELETING_ACCOUNT_ERROR, false);
			}			
		}
	}
   
	draw_title_bar(prepare_breadcrumbs(array(_MY_ACCOUNT=>'',_REMOVE_ACCOUNT=>'')));
	
?>
	<form action="index.php" method="post" id="frmLogout" style="display:inline;margin-top:0px;padding-top:0px;">
		<?php draw_hidden_field('submit_logout', 'logout'); ?>
		<?php draw_token_field(); ?>
	</form>
		
	<form action="index.php?doctor=remove_account" method="post" name="frmProfile">
		<?php draw_hidden_field('submit', 'remove'); ?>
		<?php draw_token_field(); ?>
		<?php        
			echo $msg;
			if($account_deleted){
				echo '<script type="text/javascript">setTimeout(function(){appFormSubmit("frmLogout")}, 5000);</script>';
			}else{                            
				draw_message(_REMOVE_ACCOUNT_WARNING);                                                
			}        
		?>
		<?php if(!$account_deleted){ ?>
		<table align="center" border="0" cellspacing="1" cellpadding="2" width="96%">
		<tr><td colspan="3">&nbsp;</td></tr>            
		<tr>
			<td align="left" colspan="2">
				<?php echo _REMOVE_ACCOUNT_PASSWORD_CONFIRM; ?>:<br>
                <input type="password" id="user_password" name="user_password" size="28" maxlength="20" value="" autocomplete="off" />
			</td>
		</tr>
        <tr><td colspan="3">&nbsp;</td></tr>            
		<tr>
			<td align="left" colspan="2">
				<input type="submit" class="form_button" name="btnSubmitPD" id="btnSubmitPD" value="<?php echo _REMOVE; ?>" />
				&nbsp;
				<a href="javascript:appGoTo('doctor=my_account');"><?php echo _BUTTON_CANCEL; ?></a>
			</td>
		</tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		</table>
		<?php } ?>
	</form>

	<script type="text/javascript">
        appSetFocus("<?php echo $focus_field;?>");
	</script>   

<?php
}else if($objLogin->IsLoggedIn()){
    draw_title_bar(_DOCTORS);
    draw_important_message(_NOT_AUTHORIZED);
}else{
	draw_title_bar(_DOCTORS);
	draw_important_message(str_replace('_ACCOUNT_', 'doctor', _MUST_BE_LOGGED));
}

?>