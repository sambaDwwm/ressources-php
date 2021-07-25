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

	draw_title_bar(prepare_breadcrumbs(array(_DOCTORS=>'', _CREATING_NEW_ACCOUNT=>'')));
?>
<?php if($account_created){ ?>

	<div class='pages_contents'>
		<?php echo (($msg == '') ? $msg_default : $msg); ?>
	</div>

<?php }else{ ?>

	<script type="text/javascript"> 
	function btnSubmitPD_OnClick(){
		frmReg = document.getElementById("frmRegistration");
		
		if(frmReg.first_name.value == "")          { alert("<?php echo _FIRST_NAME_EMPTY_ALERT; ?>");  frmReg.first_name.focus(); return false;
		}else if(frmReg.last_name.value == "")     { alert("<?php echo _LAST_NAME_EMPTY_ALERT; ?>"); frmReg.last_name.focus(); return false;        
		}else if(frmReg.email.value == "")         { alert("<?php echo _EMAIL_EMPTY_ALERT; ?>"); frmReg.email.focus(); return false;        
		}else if(!appIsEmail(frmReg.email.value))  { alert("<?php echo _EMAIL_VALID_ALERT; ?>"); frmReg.email.focus(); return false;        
		}else if(frmReg.b_address.value == "")     { alert("<?php echo _ADDRESS_EMPTY_ALERT; ?>"); frmReg.b_address.focus(); return false;        
		}else if(frmReg.b_city.value == "")	       { alert("<?php echo _CITY_EMPTY_ALERT; ?>"); frmReg.b_city.focus(); return false;        
		}else if(frmReg.b_zipcode.value == "")     { alert("<?php echo _ZIPCODE_EMPTY_ALERT; ?>"); frmReg.b_zipcode.focus(); return false;        
		}else if(frmReg.b_country.value == "")     { alert("<?php echo _COUNTRY_EMPTY_ALERT; ?>"); frmReg.b_country.focus(); return false;        
		}else if(frmReg.user_name.value == "")     { alert("<?php echo _USERNAME_EMPTY_ALERT; ?>"); frmReg.user_name.focus(); return false;        
		}else if(frmReg.user_password1.value == ""){ alert("<?php echo _PASSWORD_IS_EMPTY; ?>"); frmReg.user_password1.focus(); return false;        
		}else if(frmReg.user_password2.value == ""){ alert("<?php echo _CONF_PASSWORD_IS_EMPTY; ?>"); frmReg.user_password2.focus(); return false;        
		}else if(frmReg.user_password1.value != frmReg.user_password2.value){ alert("<?php echo _CONF_PASSWORD_MATCH; ?>"); frmReg.user_password2.focus(); return false;        		
		<?php if($image_verification_allow == 'yes'){ ?> }else if(frmReg.captcha_code.value == "")  { alert("<?php echo _IMAGE_VERIFY_EMPTY; ?>"); frmReg.captcha_code.focus(); return false; <?php } ?>
		}else if(!frmReg.agree.checked)            { alert("<?php echo _CONFIRM_TERMS_CONDITIONS; ?>"); return false; }
		return true;
	}
	</script>

	<?php echo $msg; ?>        
	<div class='pages_contents'>
		
		<p style='padding-left:3px;'>
			<?php echo _ALERT_REQUIRED_FILEDS; ?>
		</p>		
				
		<form action="index.php?doctor=create_account" method="post" name="frmRegistration" id="frmRegistration">
			<?php draw_hidden_field("act", "create"); ?>
			<?php draw_token_field(); ?>
			
			<table cellspacing="2" cellpadding="2" width="100%">
			<tbody>			
			<tr><td colspan="3"><b><?php echo _PERSONAL_DETAILS;?></b><hr size="1" noshade="noshade" /></td></tr>	
			<tr>
				<td width="38%" align="<?php echo Application::Get('defined_right');?>"><?php echo _FIRST_NAME;?></td>
				<td><span class="mandatory_star">*</span></td>
				<td nowrap="nowrap"><input type="text" id="first_name" name="first_name" size="32" maxlength="32" value="<?php echo decode_text($first_name);?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<td width="38%" align="<?php echo Application::Get('defined_right');?>"><?php echo _MIDDLE_NAME;?></td>
				<td></td>
				<td nowrap="nowrap"><input type="text" id="middle_name" name="middle_name" size="22" maxlength="32" value="<?php echo decode_text($middle_name);?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _LAST_NAME;?></td>
				<td><span class="mandatory_star">*</span></td>
				<td nowrap="nowrap"><input type="text" id="last_name" name="last_name" size="32" maxlength="32" value="<?php echo decode_text($last_name);?>" autocomplete="off"  /></td>
			</tr>
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _BIRTH_DATE;?></td>
				<td>&nbsp;</td>
				<td nowrap="nowrap">
					<?php draw_date_select_field('birth_date', $birth_date, '90', '0'); ?>
				</td>
			</tr>
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _GENDER;?></td>
				<td>&nbsp;</td>
				<td nowrap="nowrap">
					<?php draw_select_box('gender', array('f'=>_FEMALE, 'm'=>_MALE), $gender); ?>
				</td>
			</tr>
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _TITLE;?></td>
				<td>&nbsp;</td>
				<td nowrap="nowrap">
					<?php draw_select_box('title', $arr_titles, $title); ?>
				</td>
			</tr>

			<tr><td height="20" colspan="3"><b><?php echo _CONTACT_INFORMATION;?></b><hr size="1" noshade="noshade" /></td></tr>
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _WORK_PHONE;?></td>
				<td></td>
				<td nowrap="nowrap"><input type="text" id="work_phone" name="work_phone" size="32" maxlength="32" value="<?php echo decode_text($work_phone);?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _MOBILE_WORK_PHONE;?></td>
				<td></td>
				<td nowrap="nowrap"><input type="text" id="work_mobile_phone" name="work_mobile_phone" size="32" maxlength="32" value="<?php echo decode_text($work_mobile_phone);?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _PHONE;?></td>
				<td></td>
				<td nowrap="nowrap"><input type="text" id="phone" name="phone" size="32" maxlength="32" value="<?php echo decode_text($phone);?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _FAX;?></td>
				<td></td>
				<td nowrap="nowrap"><input type="text" id="fax" name="fax" size="32" maxlength="32" value="<?php echo decode_text($fax);?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _EMAIL_ADDRESS;?></td>
				<td><span class="mandatory_star">*</span></td>
				<td nowrap="nowrap">				 
					<?php echo _ENTER_EMAIL_ADDRESS;?>
					<br />
					<input type="text" id="email" name="email" size="32" maxlength="70" value="<?php echo decode_text($email);?>" autocomplete="off" />
				</td>
			</tr>

			<tr><td colspan="3"><b><?php echo _BILLING_ADDRESS;?></b><hr size="1" noshade="noshade" /></td></tr>		    
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _ADDRESS;?></td>
				<td><span class="required">*</span></td>
				<td nowrap="nowrap"><input type="text" id="b_address" name="b_address" size="32" maxlength="64" value="<?php echo decode_text($b_address);?>" autocomplete="off" /></td>
			</tr>	
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _ADDRESS_2;?></td>
				<td>&nbsp;</td>
				<td nowrap="nowrap"><input type="text" id="b_address_2" name="b_address_2" size="32" maxlength="64" value="<?php echo decode_text($b_address_2);?>" autocomplete="off" /></td>
			</tr>	
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _CITY;?></td>
				<td><span class="required">*</span></td>
				<td nowrap="nowrap"><input type="text" id="b_city" name="b_city" size="32" maxlength="64" value="<?php echo decode_text($b_city);?>" autocomplete="off" /></td>
			</tr>	
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _ZIP_CODE;?></td>
				<td><span class="required">*</span></td>
				<td nowrap="nowrap"><input type="text" id="b_zipcode" name="b_zipcode" size="32" maxlength="32" value="<?php echo decode_text($b_zipcode);?>" autocomplete="off" /></td>
			</tr>
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _COUNTRY;?></td>
				<td><span class="required">*</span></td>
				<td nowrap="nowrap">				
					<?php
                        Countries::DrawAllCountries('b_country', $b_country, true, "appChangeCountry(this.value,'b_state','','".Application::Get('token')."')");
					?>
				</td>
			</tr>	
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _STATE_PROVINCE;?></td>
				<td></td>
				<td nowrap="nowrap"><input type="text" id="b_state" name="b_state" size="32" maxlength="64" value="<?php echo decode_text($b_state);?>" autocomplete="off" /></td>
			</tr>					

			<tr><td colspan="3"><b><?php echo _ACCOUNT_DETAILS;?></b><hr size="1" noshade="noshade" /></td></tr>
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _USERNAME;?></td>
				<td class="mandatory_star">*</td>
				<td nowrap="nowrap"><input type="text" id="frmReg_user_name" name="user_name" size="32" maxlength="32" value="<?php echo decode_text($user_name);?>" autocomplete="off" /></td>
			</tr>		    
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _PASSWORD;?></td>
				<td><span class="mandatory_star">*</span></td>
				<td nowrap="nowrap"><input type="password" id="frmReg_user_password1" name="user_password1" size="32" maxlength="20" value="<?php echo decode_text($user_password1);?>" autocomplete="off" /></td>
			</tr>		    
			<tr>
				<td align="<?php echo Application::Get('defined_right');?>"><?php echo _CONFIRM_PASSWORD;?></td>
				<td class="mandatory_star">*</td>
				<td nowrap="nowrap"><input type="password" id="frmReg_user_password2" name="user_password2" size="32" maxlength="20" value="<?php echo decode_text($user_password2);?>" autocomplete="off" /></td>
			</tr>

			<?php if($image_verification_allow == 'yes'){?>
			<tr><td height="20" colspan="3"><b><?php echo _IMAGE_VERIFICATION; ?></b><hr size="1" noshade="noshade" /></td></tr>
			<tr valign="top">
			<td align="<?php echo Application::Get('defined_left');?>">
				<?php echo _TYPE_CHARS; ?> 			    
			</td>
			<td></td>
			<td>
				<table>
				<tr>
					<td><img id="captcha_image" src="modules/captcha/securimage_show.php?sid=<?php echo md5(uniqid(time())); ?>"></td>				
					<td>
						<a href="modules/captcha/securimage_play.php"><img style="padding:0px; margin:0px; border:0px;" id="captcha_image_play" src="modules/captcha/images/audio_icon.gif" title="<?php echo _PLAY; ?>" alt="<?php echo _PLAY; ?>" /></a><br />
						<img style="cursor:pointer;padding:0px;margin:0px;border:0px;" id="captcha_image_reload" src="modules/captcha/images/refresh.gif" style="cursor:pointer;" onclick="document.getElementById('captcha_image').src = 'modules/captcha/securimage_show.php?sid=' + Math.random(); appSetFocus('frmReg_captcha_code'); return false;" title="<?php echo _REFRESH; ?>" alt="<?php echo _REFRESH; ?>" />				
					</td>				
				</tr>
				<tr>
					<td colspan="2">
						<input type="text" id="frmReg_captcha_code" name="captcha_code" style="width:148px;" maxlength="20" value="" autocomplete="off" />
					</td>
				</tr>
				</table>			    			    
			</td>
			</tr>
			<?php } ?>

			<tr><td colspan="3" nowrap height="7px"></td></tr>
			<tr>
				<td colspan="3" align="<?php echo Application::Get('defined_left');?>">
				<table>					
				<tr valign="top">
					<td align="<?php echo Application::Get('defined_right');?>"><input type="checkbox" name="send_updates" id="send_updates" <?php echo (($send_updates == '1') ? 'checked="checked"' : '');?> value="1"></td>
					<td>&nbsp;</td>
					<td><?php echo _NOTIFICATION_MSG; ?></td>
				</tr>					
				<tr><td colspan="3" nowrap="nowrap" height="5px"></td></tr>
				<tr valign="middle">
					<td align="<?php echo Application::Get('defined_right');?>"><input type="checkbox" name="agree" id="agree" value="1" <?php echo ($agree == '1') ? 'checked="checked"' : ''; ?>></td>
					<td>&nbsp;</td>
					<td>
						<?php							
							$temp_page = new Pages('terms_and_conditions', true);
                            $page_text = $temp_page->DrawText(false);
                            if(!empty($page_text)){
                                echo '<a href="javascript:appPopupWindow(\'terms_and_conditions.html\',\'div_terms_and_conditions\')">'._AGREE_CONF_TEXT.'</a>';
                                echo '<div id="div_terms_and_conditions" style="display:none;">';
                                echo  $page_text;
                                echo '</div>';								
                            }else{
                                echo _AGREE_CONF_TEXT;
                            }
                        ?>
					</td>					
				</tr>					
				</table>
				</td>
			</tr>

			<tr>
				<td colspan="3" align="center">
				<br /><br />
				<input type="submit" class="form_button" name="btnSubmitPD" id="btnSubmitPD" value="<?php echo _SUBMIT; ?>" onclick="return btnSubmitPD_OnClick()">
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			
			<tr>
			<td colspan="3" align="<?php echo Application::Get('defined_left');?>">
				<p><?php echo _CREATE_ACCOUNT_NOTE; ?></p>
			</td>
			</tr>
			
			</table>
		</form>
	</div>	

	<script type="text/javascript">
		appSetFocus('<?php echo $focus_field; ?>');
        appChangeCountry(jQuery("#b_country").val(),'b_state','<?php echo decode_text($b_state);?>','<?php echo Application::Get('token');?>');        
	</script>	
<?php
	}	
}else{
	draw_title_bar(_DOCTORS);
	draw_important_message(_NOT_AUTHORIZED);
}
?>