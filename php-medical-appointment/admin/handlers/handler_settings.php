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

	$submition_type = isset($_POST['submition_type']) ? prepare_input($_POST['submition_type']) : '';	
	$site_template  = isset($_POST['site_template']) ? prepare_input($_POST['site_template']) : '';
	$cron_type      = isset($_POST['cron_type']) ? prepare_input($_POST['cron_type']) : $objSettings->GetParameter('cron_type');
	$http_host 	    = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : _UNKNOWN;
	$language_id    = isset($_POST['sel_language_id']) ? prepare_input($_POST['sel_language_id']) : Languages::GetDefaultLang();
	$msg            = '';
	$focus_on_field = '';

	$objSiteDescription->LoadData($language_id);
    $objSocialNetworks = new SocialNetworks();
	
	$params = array();
	$params['ssl_mode'] 	   = isset($_POST['ssl_mode']) ? prepare_input($_POST['ssl_mode']) : $objSettings->GetParameter('ssl_mode');
	$params['seo_urls'] 	   = isset($_POST['seo_urls']) ? prepare_input($_POST['seo_urls']) : $objSettings->GetParameter('seo_urls');
	$params['rss_feed']        = isset($_POST['rss_feed']) ? prepare_input($_POST['rss_feed']) : $objSettings->GetParameter('rss_feed');
	$params['rss_feed_type']   = isset($_POST['rss_feed_type']) ? prepare_input($_POST['rss_feed_type']) : $objSettings->GetParameter('rss_feed_type');
	$params['is_offline']      = isset($_POST['is_offline']) ? prepare_input($_POST['is_offline']) : $objSettings->GetParameter('is_offline');
	$params['offline_message'] = isset($_POST['offline_message']) ? prepare_input($_POST['offline_message']) : $objSettings->GetParameter('offline_message');
	$params['caching_allowed'] = isset($_POST['caching_allowed']) ? prepare_input($_POST['caching_allowed']) : $objSettings->GetParameter('caching_allowed');
	$params['cache_lifetime']  = isset($_POST['cache_lifetime']) ? prepare_input($_POST['cache_lifetime']) : $objSettings->GetParameter('cache_lifetime');
	$params['wysiwyg_type']    = isset($_POST['wysiwyg_type']) ? prepare_input($_POST['wysiwyg_type']) : $objSettings->GetParameter('wysiwyg_type');

	$params_visual = array();
	$params_visual['header_text'] = isset($_POST['header_text']) ? strip_tags(prepare_input($_POST['header_text'], false, 'medium'), '<b><u><i>') : $objSiteDescription->GetParameter('header_text');
	$params_visual['slogan_text'] = isset($_POST['slogan_text']) ? prepare_input($_POST['slogan_text']) : $objSiteDescription->GetParameter('slogan_text');
	$params_visual['footer_text'] = isset($_POST['footer_text']) ? prepare_input($_POST['footer_text'], false, 'medium') : $objSiteDescription->GetParameter('footer_text');

	$params_visual_meta = array();
	$params_visual_meta['tag_title'] 	 = isset($_POST['tag_title']) ? prepare_input($_POST['tag_title']) : $objSiteDescription->GetParameter('tag_title');
	$params_visual_meta['tag_description'] = isset($_POST['tag_description']) ? prepare_input($_POST['tag_description']) : $objSiteDescription->GetParameter('tag_description');
	$params_visual_meta['tag_keywords']  = isset($_POST['tag_description']) ? prepare_input($_POST['tag_keywords']) : $objSiteDescription->GetParameter('tag_keywords');
	$apply_to_all_pages                  = isset($_POST['apply_to_all_pages']) ? prepare_input($_POST['apply_to_all_pages']) : '';

	$params_datetime = array();
	$params_datetime['date_format']  = isset($_POST['date_format']) ? prepare_input($_POST['date_format']) : $objSettings->GetParameter('date_format');
	$params_datetime['time_format']  = isset($_POST['time_format']) ? prepare_input($_POST['time_format']) : $objSettings->GetParameter('time_format');
	$params_datetime['time_zone']    = isset($_POST['time_zone']) ? prepare_input($_POST['time_zone']) : $objSettings->GetParameter('time_zone');
    $params_datetime['price_format'] = isset($_POST['price_format']) ? prepare_input($_POST['price_format']) : $objSettings->GetParameter('price_format');
    $params_datetime['week_start_day'] = isset($_POST['week_start_day']) ? prepare_input($_POST['week_start_day']) : $objSettings->GetParameter('week_start_day');

	$params_email = array();
	$params_email['mailer'] 	   = isset($_POST['mailer']) ? prepare_input($_POST['mailer']) : $objSettings->GetParameter('mailer');
	$params_email['admin_email']   = isset($_POST['admin_email']) ? prepare_input($_POST['admin_email']) : $objSettings->GetParameter('admin_email');
	$params_email['mailer_type']   = isset($_POST['mailer_type']) ? prepare_input($_POST['mailer_type']) : $objSettings->GetParameter('mailer_type');
	$params_email['mailer_wysiwyg_type'] = isset($_POST['mailer_wysiwyg_type']) ? prepare_input($_POST['mailer_wysiwyg_type']) : $objSettings->GetParameter('mailer_wysiwyg_type');
    $params_email['smtp_auth']     = isset($_POST['smtp_auth']) ? prepare_input($_POST['smtp_auth']) : $objSettings->GetParameter('smtp_auth');
    $params_email['smtp_secure']   = isset($_POST['smtp_secure']) ? prepare_input($_POST['smtp_secure']) : $objSettings->GetParameter('smtp_secure');
	$params_email['smtp_host']     = isset($_POST['smtp_host']) ? prepare_input($_POST['smtp_host']) : $objSettings->GetParameter('smtp_host');
	$params_email['smtp_port']     = isset($_POST['smtp_port']) ? prepare_input($_POST['smtp_port']) : $objSettings->GetParameter('smtp_port');
	$params_email['smtp_username'] = isset($_POST['smtp_username']) ? prepare_input($_POST['smtp_username']) : $objSettings->GetParameter('smtp_username');
	$params_email['smtp_password'] = isset($_POST['smtp_password']) ? prepare_input($_POST['smtp_password']) : '';
    
    $params_social = array();
    $social_networks = $objSocialNetworks->GetAllData();
    foreach($social_networks as $key => $val){
        $params_social['link_'.$key] = isset($_POST['link_'.$key]) ? prepare_input($_POST['link_'.$key], false, 'medium') : $objSocialNetworks->GetParameter($key);
    }

	$params_cron = array();
	$params_cron['cron_type']             = isset($_POST['cron_type']) ? prepare_input($_POST['cron_type']) : $objSettings->GetParameter('cron_type');
	$params_cron['cron_run_period']       = isset($_POST['cron_run_period']) ? prepare_input($_POST['cron_run_period']) : $objSettings->GetParameter('cron_run_period');
	$params_cron['cron_run_period_value'] = isset($_POST['cron_run_period_value']) ? prepare_input($_POST['cron_run_period_value']) : $objSettings->GetParameter('cron_run_period_value');

	// SAVE CHANGES			
	if($submition_type == 'general'){
		if(strlen($params['offline_message']) > 255){
			$msg_text = str_replace('_FIELD_', '<b>'._OFFLINE_MESSAGE.'</b>', _FIELD_LENGTH_ALERT);
			$msg_text = str_replace('_LENGTH_', '255', $msg_text);
			$msg = draw_important_message($msg_text, false);
			$focus_on_field = 'offline_message';
		}else{			
			if($objSettings->UpdateFields($params) == true){
				$msg = draw_success_message(_SETTINGS_SAVED, false);
			}else $msg = draw_important_message($objSettings->error, false);
		}
	}else if($submition_type == 'clean_cache'){
		if(strtolower(SITE_MODE) == 'demo'){
			$msg = draw_important_message(_OPERATION_BLOCKED, false);
		}else{
			delete_cache();
			$msg = draw_success_message(_DELETING_OPERATION_COMPLETED, false);
		}
	}else if($submition_type == 'visual_settings'){
		if($params_visual['header_text'] == ''){
			$msg = draw_important_message(_HEADER_IS_EMPTY, false);
			$focus_on_field = 'header_text';
		}else if(strlen($params_visual['header_text']) > 255){
			$msg_text = str_replace('_FIELD_', '<b>'._HDR_HEADER_TEXT.'</b>', _FIELD_LENGTH_ALERT);
			$msg_text = str_replace('_LENGTH_', '255', $msg_text);
			$msg = draw_important_message($msg_text, false);
			$focus_on_field = 'header_text';
		}else if(strlen($params_visual['slogan_text']) > 512){
			$msg_text = str_replace('_FIELD_', '<b>'._HDR_SLOGAN_TEXT.'</b>', _FIELD_LENGTH_ALERT);
			$msg_text = str_replace('_LENGTH_', '512', $msg_text);
			$msg = draw_important_message($msg_text, false);
			$focus_on_field = 'slogan_text';
		//}else if($params_visual['footer_text'] == ''){
		//	$msg = draw_important_message(_FOOTER_IS_EMPTY, false);
		//	$focus_on_field = 'footer_text';
		}else if(strlen($params_visual['footer_text']) > 512){
			$msg_text = str_replace('_FIELD_', '<b>'._HDR_FOOTER_TEXT.'</b>', _FIELD_LENGTH_ALERT);
			$msg_text = str_replace('_LENGTH_', '512', $msg_text);
			$msg = draw_important_message($msg_text, false);
			$focus_on_field = 'footer_text';
		}else{			
			if($objSiteDescription->UpdateFields($params_visual, $language_id) == true){
				$msg = draw_success_message(_SETTINGS_SAVED, false);
			}else $msg = draw_important_message($objSiteDescription->error, false);
		}		
	}else if($submition_type == 'meta_tags'){
		if($params_visual_meta['tag_title'] == ''){
			$msg = draw_important_message(_TAG_TITLE_IS_EMPTY, false);
			$params_visual_meta['tag_title'] = $objSiteDescription->GetParameter('tag_title');
			$focus_on_field = 'tag_title';
		}else if(strlen($params_visual_meta['tag_title']) > 255){
			$msg_text = str_replace('_FIELD_', '<b>TITLE</b>', _FIELD_LENGTH_ALERT);
			$msg_text = str_replace('_LENGTH_', '255', $msg_text);
			$msg = draw_important_message($msg_text, false);
			$focus_on_field = 'tag_title';
		}else if(strlen($params_visual_meta['tag_keywords']) > 512){
			$msg_text = str_replace('_FIELD_', '<b>KEYWORDS</b>', _FIELD_LENGTH_ALERT);
			$msg_text = str_replace('_LENGTH_', '512', $msg_text);
			$msg = draw_important_message($msg_text, false);
			$focus_on_field = 'tag_keywords';
		}else if(strlen($params_visual_meta['tag_description']) > 512){
			$msg_text = str_replace('_FIELD_', '<b>DESCRIPTION</b>', _FIELD_LENGTH_ALERT);
			$msg_text = str_replace('_LENGTH_', '512', $msg_text);
			$msg = draw_important_message($msg_text, false);
			$focus_on_field = 'tag_description';
		}else{
			if($objSiteDescription->UpdateFields($params_visual_meta, $language_id) == true){
				if($apply_to_all_pages == '1') Pages::UpdateMetaTags($params_visual_meta, $language_id);
				$msg = draw_success_message(_SETTINGS_SAVED, false);	
			}else $msg = draw_important_message($objSiteDescription->error, false);			
		}
	}else if($submition_type == 'date_time'){
		if($objSettings->UpdateFields($params_datetime) == true){
			$msg = draw_success_message(_SETTINGS_SAVED, false);
		}else $msg = draw_important_message($objSettings->error, false);
	}else if($submition_type == 'test_smtp_connection'){
		if($params_email['admin_email'] == ''){
			$msg = draw_important_message(_ADMIN_EMAIL_IS_EMPTY, false);
			$focus_on_field = 'admin_email';
		}else if(!check_email_address($params_email['admin_email'])){
			$msg = draw_important_message(_ADMIN_EMAIL_WRONG, false);
			$focus_on_field = 'admin_email';
		}else{			
			if(strtolower(SITE_MODE) == 'demo'){
				$msg = draw_important_message(_OPERATION_BLOCKED, false);
			}else{
				if($params_email['mailer'] == 'smtp'){
					$mail = new PHPMailer();
					$mail->IsSMTP(); // telling the class to use SMTP
					$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
															   // 1 = errors and messages
															   // 2 = messages only
                                         
					$mail->SMTPAuth   = ($params_email['smtp_auth'] == '1') ? true : false; // enable SMTP authentication                                          
					$mail->SMTPSecure = (in_array($params_email['smtp_secure'], array('ssl', 'tls'))) ? $params_email['smtp_secure'] : '';  // sets the prefix to the server
					$mail->Host       = $params_email['smtp_host'];  
					$mail->Port       = $params_email['smtp_port'];  
					$mail->Username   = $params_email['smtp_username']; 
                    $mail->Password   = ($params_email['smtp_password'] != '') ? $params_email['smtp_password'] : $objSettings->GetParameter('smtp_password');
			
					$mail->SetFrom($params_email['admin_email']);    
					$mail->AddAddress($params_email['admin_email']); 
			
					$mail->Subject    = 'This is Test Email!';
					$mail->AltBody    = strip_tags('Hello Admin! This is a test email that you just have sent to check your connection parameters.');
					$mail->MsgHTML('Hello! This is test email.');
					if(!$mail->Send()){
						$msg = draw_important_message($mail->ErrorInfo, false);				
					}else{
						$msg = draw_success_message(_EMAIL_SUCCESSFULLY_SENT, false);
					}					
				}else{
					$mail = new Email($params_email['admin_email'], $params_email['admin_email'], 'This is Test Email!'); 				
					$mail->textOnly = false;
					$mail->content = 'Hello! This is test email.';						
					$result = $mail->Send();
					if(!$result){
						if(version_compare(PHP_VERSION, '5.2.0', '>=')){	
							$err = error_get_last();
							if(!empty($err)){
								$msg = draw_important_message((isset($err['message']) ? $err['message'] : ''), false);	
							}
						}else{
							$msg = draw_important_message('PHPMail Error: an error occurred while sending email.', false);
						}
					}else{
						$msg = draw_success_message(_EMAIL_SUCCESSFULLY_SENT, false);
					}					
				}
			}
		}
	}else if($submition_type == 'email'){
		if($params_email['admin_email'] == ''){
			$msg = draw_important_message(_ADMIN_EMAIL_IS_EMPTY, false);
			$focus_on_field = 'admin_email';
		}else if(!check_email_address($params_email['admin_email'])){
			$msg = draw_important_message(_ADMIN_EMAIL_WRONG, false);
			$focus_on_field = 'admin_email';
		}
		
		if($msg == '' && $params_email['mailer'] == 'smtp'){
			if($params_email['smtp_host'] != '' || $params_email['smtp_port'] != '' || $params_email['smtp_username'] != '' || $params_email['smtp_password'] != ''){
				if($params_email['smtp_host'] == ''){
					$msg = draw_important_message(str_replace('_FIELD_', '<b>SMTP Host</b>', _FIELD_CANNOT_BE_EMPTY), false);
					$focus_on_field = 'smtp_host';
				}else if($params_email['smtp_port'] == ''){
					$msg = draw_important_message(str_replace('_FIELD_', '<b>SMTP Port</b>', _FIELD_CANNOT_BE_EMPTY), false);
					$focus_on_field = 'smtp_port';
				}else if($params_email['smtp_username'] == ''){
					$msg = draw_important_message(str_replace('_FIELD_', '<b>SMTP Username</b>', _FIELD_CANNOT_BE_EMPTY), false);
					$focus_on_field = 'smtp_username';
				}else if($params_email['smtp_password'] == ''){
					$msg = draw_important_message(str_replace('_FIELD_', '<b>SMTP Password</b>', _FIELD_CANNOT_BE_EMPTY), false);
					$focus_on_field = 'smtp_password';
				}				
			}			
		}
		
		if($msg == ''){
			if($objSettings->UpdateFields($params_email) == true){
				$msg = draw_success_message(_SETTINGS_SAVED, false);
			}else $msg = draw_important_message($objSettings->error, false);								
		}
	}else if($submition_type == 'templates'){
		if(!empty($site_template)){
			if($objSettings->SetTemplate($site_template) == true){
				delete_cache();
				$msg = draw_success_message(_SETTINGS_SAVED, false);	
			}else $msg = draw_important_message($objSettings->error, false);									
		}else $msg = draw_important_message(_TEMPLATE_IS_EMPTY, false);
    }else if($submition_type == 'social_networks'){
		if($objSocialNetworks->UpdateFields($params_social) == true){
			$msg = draw_success_message(_SETTINGS_SAVED, false);
		}else $msg = draw_important_message($objSocialNetworks->error, false);        
	}else if($submition_type == 'site_info'){		
		$params_ranks = array();
		$params_ranks['alexa_rank'] = number_format((float)$objSettings->CheckAlexaRank($http_host));
		$params_ranks['google_rank'] = (int)$objSettings->CheckGoogleRank($http_host);		
		if($objSettings->UpdateFields($params_ranks) == true) $msg = draw_success_message(_CHANGES_WERE_SAVED, false);	
		else $msg = draw_important_message($objSettings->error, false);					
	}else if($submition_type == 'cron_settings'){
		if($objSettings->UpdateFields($params_cron) == true) $msg = draw_success_message(_CHANGES_WERE_SAVED, false);	
		else $msg = draw_important_message($objSettings->error, false);
	}
	
	$template = $objSettings->GetTemplate();	
	if(strtolower(SITE_MODE) != 'demo' && $submition_type == 'general' || $submition_type == 'visual_settings' || $submition_type == 'meta_tags'){
		$objSiteDescription->LoadData();
		RSSFeed::UpdateFeeds();	
	}	

} 
?>