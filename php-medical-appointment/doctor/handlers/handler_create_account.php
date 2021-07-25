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

if(!$objLogin->IsLoggedIn() && (ModulesSettings::Get('doctors', 'allow_registration') == 'yes')){

	include_once('modules/captcha/securimage.php');
	$objImg = new Securimage();
	
	$act 		  = isset($_POST['act']) ? prepare_input($_POST['act']) : '';
	$send_updates = isset($_POST['send_updates']) ? prepare_input($_POST['send_updates']) : '1';
	$first_name   = isset($_POST['first_name']) ? prepare_input($_POST['first_name']) : '';
	$middle_name  = isset($_POST['middle_name']) ? prepare_input($_POST['middle_name']) : '';
	$last_name    = isset($_POST['last_name']) ? prepare_input($_POST['last_name']) : '';
	$gender       = isset($_POST['gender']) ? prepare_input($_POST['gender']) : 'm';
	$title        = isset($_POST['title']) ? prepare_input($_POST['title']) : '';

	$birth_date_year = isset($_POST['birth_date__nc_year']) ? prepare_input($_POST['birth_date__nc_year']) : '';
	$birth_date_month = isset($_POST['birth_date__nc_month']) ? prepare_input($_POST['birth_date__nc_month']) : '';
	$birth_date_day = isset($_POST['birth_date__nc_day']) ? prepare_input($_POST['birth_date__nc_day']) : '';
	$birth_date = $birth_date_year.'-'.$birth_date_month.'-'.$birth_date_day;
	if($birth_date == '--') $birth_date = null;
	
	$b_address   = isset($_POST['b_address']) ? prepare_input($_POST['b_address']) : '';
	$b_address_2 = isset($_POST['b_address_2']) ? prepare_input($_POST['b_address_2']) : '';
	$b_city      = isset($_POST['b_city']) ? prepare_input($_POST['b_city']) : '';
	$b_zipcode   = isset($_POST['b_zipcode']) ? prepare_input($_POST['b_zipcode']) : '';
	$b_country   = isset($_POST['b_country']) ? prepare_input($_POST['b_country']) : '';
	$b_state     = isset($_POST['b_state']) ? prepare_input($_POST['b_state'], false, 'extra') : '';
	$phone       = isset($_POST['phone']) ? prepare_input($_POST['phone']) : '';
	$fax         = isset($_POST['fax']) ? prepare_input($_POST['fax']) : '';
	$work_phone  = isset($_POST['work_phone']) ? prepare_input($_POST['work_phone']) : '';
	$work_mobile_phone = isset($_POST['work_mobile_phone']) ? prepare_input($_POST['work_mobile_phone']) : '';
	$email       = isset($_POST['email']) ? prepare_input($_POST['email']) : '';
	
	$user_name   = isset($_POST['user_name']) ? prepare_input($_POST['user_name']) : '';
	$user_password1 = isset($_POST['user_password1']) ? prepare_input($_POST['user_password1']) : '';
	$user_password2 = isset($_POST['user_password2']) ? prepare_input($_POST['user_password2']) : '';
	$agree       = isset($_POST['agree']) ? prepare_input($_POST['agree']) : '';
	$user_ip     = get_current_ip();			
	$focus_field = '';

	$reg_confirmation = ModulesSettings::Get('doctors', 'reg_confirmation');
	$image_verification_allow = ModulesSettings::Get('doctors', 'image_verification_allow');
	$admin_alert_new_registration = ModulesSettings::Get('doctors', 'admin_alert_new_registration');
	$arr_titles	= array('Mr.'=>'Mr.', 'Ms.'=>'Ms.', 'Mrs.'=>'Mrs.', 'Miss'=>'Miss');
	$arr_degrees = array('BMBS'=>'BMBS', 'MBBS'=>'MBBS', 'MBChB'=>'MBChB', 'MB BCh'=>'MB BCh', 'BMed'=>'BMed', 'MD'=>'MD', 'MDCM'=>'MDCM', 'Dr.MuD'=>'Dr.MuD', 'Dr.Med'=>'Dr.Med', 'Cand.med'=>'Cand.med', 'Med'=>'Med');					
	
	$msg_default = draw_message(_ACCOUNT_CREATE_MSG, false);
	$msg = '';

	$account_created = false;
	
	if($act == 'create'){
		
		$captcha_code= isset($_POST['captcha_code']) ? prepare_input($_POST['captcha_code']) : '';

		if($first_name == ''){
			$msg = draw_important_message(_FIRST_NAME_EMPTY_ALERT, false);
			$focus_field = 'first_name';
		}else if($last_name == ''){
			$msg = draw_important_message(_LAST_NAME_EMPTY_ALERT, false);
			$focus_field = 'last_name';
		}else if(!empty($birth_date) && !check_date($birth_date)){
			$msg = draw_important_message(_BIRTH_DATE_VALID_ALERT, false);
			$focus_field = 'birth_date__nc_month';
		}else if($email == ''){
			$msg = draw_important_message(_EMAIL_EMPTY_ALERT, false);
			$focus_field = 'email';
		}else if($email != '' && !check_email_address($email)){
			$msg = draw_important_message(_EMAIL_VALID_ALERT, false);
			$focus_field = 'email';
		}else if($b_address == ''){
			$msg = draw_important_message(_ADDRESS_EMPTY_ALERT, false);
			$focus_field = 'b_address';
		}else if($b_city == ''){
			$msg = draw_important_message(_CITY_EMPTY_ALERT, false);
			$focus_field = 'b_city';
		}else if($b_zipcode == ''){
			$msg = draw_important_message(_ZIPCODE_EMPTY_ALERT, false);
			$focus_field = 'b_zipcode';
		}else if($b_country == ''){
			$msg = draw_important_message(_COUNTRY_EMPTY_ALERT, false);
			$focus_field = 'b_country';
		}else if($user_name == ''){
			$msg = draw_important_message(_USERNAME_EMPTY_ALERT, false);
			$focus_field = 'frmReg_user_name';
		}else if(($user_name != '') && (strlen($user_name) < 4)){
			$msg = draw_important_message(_USERNAME_LENGTH_ALERT, false);
			$focus_field = 'frmReg_user_name';
		}else if($user_password1 == ''){
			$msg = draw_important_message(_PASSWORD_IS_EMPTY, false);
			$user_password1 = $user_password2 = '';
			$focus_field = 'frmReg_user_password1';
		}else if(($user_password1 != '') && (strlen($user_password1) < 6)){
			$msg = draw_important_message(_PASSWORD_IS_EMPTY, false);
			$user_password1 = $user_password2 = '';
			$focus_field = 'frmReg_user_password1';
		}else if(($user_password1 != '') && ($user_password2 == '')){
			$msg = draw_important_message(_CONF_PASSWORD_IS_EMPTY, false);
			$user_password1 = $user_password2 = '';
			$focus_field = 'frmReg_user_password1';
		}else if(($user_password1 != '') && ($user_password2 != '') && ($user_password1 != $user_password2)){
			$msg = draw_important_message(_CONF_PASSWORD_MATCH, false);
			$user_password1 = $user_password2 = '';
			$focus_field = 'frmReg_user_password1';
		}else if($agree == ''){
			$msg = draw_important_message(_CONFIRM_TERMS_CONDITIONS, false);
		}else if($image_verification_allow == 'yes' && !$objImg->check($captcha_code)){
			$msg = draw_important_message(_WRONG_CODE_ALERT, false);	    
			$focus_field = 'frmReg_captcha_code';
		}

		// deny all operations in demo version
		if(strtolower(SITE_MODE) == 'demo'){
			$msg = draw_important_message(_OPERATION_BLOCKED, false);
		}				

		// check if user IP or email don't blocked
		if($msg == ''){
			if($objLogin->IpAddressBlocked($user_ip)) $msg = draw_important_message(_IP_ADDRESS_BLOCKED, false);
			else if($objLogin->EmailBlocked($email)) $msg = draw_important_message(_EMAIL_BLOCKED, false);			
		}

		// check if user already exists                    
		if($msg == ''){
			$sql = 'SELECT * FROM '.TABLE_DOCTORS.' WHERE user_name = \''.encode_text($user_name).'\'';
			$result = database_query($sql, DATA_AND_ROWS);
			if($result[1] > 0){
				$msg = draw_important_message(_USER_EXISTS_ALERT, false);
			}else{			
				// check if email already exists                    
				$sql = 'SELECT * FROM '.TABLE_DOCTORS.' WHERE email = \''.encode_text($email).'\'';
				$result = database_query($sql, DATA_AND_ROWS);
				if($result[1] > 0){
					$msg = draw_important_message(_USER_EMAIL_EXISTS_ALERT, false);
				}			
			}			
		}
		
		if($msg == ''){			
			if($reg_confirmation == 'by email'){
				$registration_code = strtoupper(get_random_string(19));
				$is_active = '0';
			}else if($reg_confirmation == 'by admin'){
				$registration_code = strtoupper(get_random_string(19));
				$is_active = '0';
			}else{
				$registration_code = '';
				$is_active = '1';
			}
			
			if(!PASSWORDS_ENCRYPTION){
				$user_password = '\''.encode_text($user_password1).'\'';
			}else{
				if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'aes'){					
					$user_password = 'AES_ENCRYPT(\''.encode_text($user_password1).'\', \''.PASSWORDS_ENCRYPT_KEY.'\')';
				}else if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'md5'){
					$user_password = 'MD5(\''.encode_text($user_password1).'\')';
				}
			}
			
			$default_plan_info = MembershipPlans::GetDefaultPlanInfo();
			$default_plan_id = isset($default_plan_info['id']) ? (int)$default_plan_info['id'] : 0;
            $default_plan_images_count = isset($default_plan_info['images_count']) ? (int)$default_plan_info['images_count'] : 0;
            $default_plan_addresses_count = isset($default_plan_info['addresses_count']) ? (int)$default_plan_info['addresses_count'] : 0;
			$default_plan_duration = isset($default_plan_info['duration']) ? (int)$default_plan_info['duration'] : 0;
            $default_plan_show_in_search = isset($default_plan_info['show_in_search']) ? (int)$default_plan_info['show_in_search'] : 0;
            if($default_plan_duration > -1){
                $default_plan_expired = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + $default_plan_duration, date('Y')));                    
            }else{
                $default_plan_expired = null;
            }
            
			// insert new user
			$sql = 'INSERT INTO '.TABLE_DOCTORS.'(
						first_name,
						middle_name,
						last_name,
						birth_date,
						gender,
						title,
						b_address,
						b_address_2,
						b_city,
						b_zipcode,
						b_country,
						b_state,
						phone,
						fax,
						work_phone,
						work_mobile_phone,
						email,
						user_name,
						user_password,
						preferred_language,
						date_created,
						registered_from_ip,
						last_logged_ip,
                        email_notifications,
                        membership_plan_id,
                        membership_images_count,
                        membership_addresses_count,
                        membership_show_in_search,
                        membership_expires,
						is_active,
						is_removed,
						comments,
						registration_code)
					VALUES(
						\''.encode_text($first_name).'\',
						\''.encode_text($middle_name).'\',
						\''.encode_text($last_name).'\',
						'.(!empty($birth_date) ? "'".encode_text($birth_date)."'" : 'NULL').',
						\''.$gender.'\',
						\''.$title.'\',						
						\''.encode_text($b_address).'\',
						\''.encode_text($b_address_2).'\',
						\''.encode_text($b_city).'\',
						\''.encode_text($b_zipcode).'\',
						\''.encode_text($b_country).'\',
						\''.encode_text($b_state).'\',
						\''.encode_text($phone).'\',
						\''.encode_text($fax).'\',
						\''.encode_text($work_phone).'\',
						\''.encode_text($work_mobile_phone).'\',						
						\''.encode_text($email).'\',
						\''.encode_text($user_name).'\',
						'.$user_password.',
						\''.Application::Get('lang').'\',
						\''.date('Y-m-d H:i:s').'\',
						\''.$user_ip.'\',
						\'\',
                        \''.$send_updates.'\',
                        \''.$default_plan_id.'\',
                        \''.$default_plan_images_count.'\',
                        \''.$default_plan_addresses_count.'\',
                        \''.$default_plan_show_in_search.'\',
                        \''.$default_plan_expired.'\',
						'.$is_active.',
						0,
						\'\',
						\''.$registration_code.'\')';
			if(database_void_query($sql) > 0){
		
				////////////////////////////////////////////////////////////
				if($reg_confirmation == 'by email'){
					$email_template = 'new_account_created_confirm_by_email';					
				}else if($reg_confirmation == 'by admin'){
					$email_template = 'new_account_created_confirm_by_admin';
				}else{
					$email_template = 'new_account_created';
				}
				send_email(
					$email,
					$objSettings->GetParameter('admin_email'),
					$email_template,
					array(
						'{FIRST NAME}'   => $first_name,
						'{LAST NAME}'    => $last_name,
						'{USER NAME}'    => $user_name,
						'{USER PASSWORD}' => $user_password1,
						'{WEB SITE}'     => $_SERVER['SERVER_NAME'],
						'{REGISTRATION CODE}' => $registration_code,
						'{BASE URL}'     => APPHP_BASE,
						'{YEAR}' 	     => date('Y'),
						'{ACCOUNT TYPE}' => 'doctor'
					)
				);

				if($admin_alert_new_registration == 'yes'){
					send_email(
						$objSettings->GetParameter('admin_email'),
						$objSettings->GetParameter('admin_email'),
						'new_account_created_notify_admin',
						array(
							'{FIRST NAME}' => $first_name,
							'{LAST NAME}'  => $last_name,
							'{USER NAME}'  => $user_name,
							'{USER EMAIL}' => $email,
							'{WEB SITE}'   => $_SERVER['SERVER_NAME'],
							'{BASE URL}'   => APPHP_BASE,
							'{YEAR}' 	   => date('Y'),
							'{ACCOUNT TYPE}' => 'doctor'
						)
					);
				}
				////////////////////////////////////////////////////////////
				
				if($reg_confirmation == 'by email'){
					$msg = draw_success_message(_ACCOUNT_CREATED_CONF_BY_EMAIL_MSG, false);
					$msg .= '<br />'.draw_message(str_replace('_ACCOUNT_', 'doctor', _ACCOUT_CREATED_CONF_LINK), false);				
				}else if($reg_confirmation == 'by admin'){
					$msg = draw_success_message(_ACCOUNT_CREATED_CONF_BY_ADMIN_MSG, false);
					$msg .= '<br />'.draw_message(str_replace('_ACCOUNT_', 'doctor', _ACCOUT_CREATED_CONF_LINK), false);
				}else{
					$msg = draw_success_message(_ACCOUNT_CREATED_NON_CONFIRM_MSG, false);
					$msg .= '<br />'.draw_message(str_replace('_ACCOUNT_', 'doctor', _ACCOUNT_CREATED_NON_CONFIRM_LINK), false);
				}
				
				$account_created = true;
			
			}else{
				///echo database_error();
				$msg = draw_important_message(_CREATING_ACCOUNT_ERROR, false);
			}                    		
		}		
	}
}

?>