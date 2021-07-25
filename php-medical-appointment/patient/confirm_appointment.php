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
if(ModulesSettings::Get('appointments', 'approval_required') == 'by email'){
	
	draw_title_bar(_APPOINTMENT_CONFIRMATION);
	
	echo $msg;
	
	echo '<div class="pages_contents">';
	if(!$confirmed){
		echo '<br />
		<form action="index.php?patient=confirm_appointment" method="post" name="frmConfirmCode" id="frmConfirmCode">
			'.draw_hidden_field('task', 'post_submission', false).'
			'.draw_token_field(false).'

			'._ENTER_CONFIRMATION_CODE.':
			<input type="text" name="n" id="n" value="" size="27" maxlength="25" /><br /><br />
			<input class="form_button" type="submit" name="btnSubmit" id="btnSubmit" value="Submit">
		</form>
		<script type="text/javascript">appSetFocus(\'n\')</script>';
	}
	echo '</div>';

}else{
	draw_title_bar(_APPOINTMENT_CONFIRMATION);
    draw_important_message(_NOT_AUTHORIZED);
}

?>