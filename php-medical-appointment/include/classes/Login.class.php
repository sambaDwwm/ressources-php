<?php

/**
 *	Class Login 
 *  -------------- 
 *  Description : encapsulates login methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 02.10.2012
 *  Usage       : MedicalAppointment
 *	
 *	PUBLIC:				  	STATIC:				 	  PRIVATE:
 * 	------------------	  	---------------     	  ---------------
 *	__construct				SetMembershipInfo         DoLogin	
 *	__destruct              		    			  UpdateAccountInfo
 *	RemoveAccount        						      GetAccountInformation
 *	IsWrongLogin            						  SetSessionVariables
 *	IsIpAddressBlocked      						  GetUniqueUrl
 *	IsEmailBlocked          						  PrepareLink  
 *	DoLogout                						  Encrypt
 *	IsLoggedIn                                        Decrypt 
 *	GetLastLoginTime
 *	IsLoggedInAs
 *	IsLoggedInAsAdmin
 *	IsLoggedInAsPatient
 *	IsLoggedInAsDoctor
 *	GetLoggedType
 *	GetLoggedEmail
 *	UpdateLoggedEmail
 *	GetLoggedName
 *	GetLoggedFirstName
 *	GetLoggedLastName
 *	UpdateLoggedFirstName
 *	UpdateLoggedLastName
 *	GetLoggedID
 *	GetPreferredLang
 *	SetPreferredLang
 *  GetActiveMenuCount
 *	DrawLoginLinks
 *	IpAddressBlocked
 *	EmailBlocked
 *	GetLoginError
 *	HasPrivileges
 *	CheckAccountPassword
 *	GetMembershipInfo
 *	
 **/

class Login {

	private $wrongLogin;
	private $ipAddressBlocked;
	private $emailBlocked;
	private $activeMenuCount;
	private $accountType;
	private $loginError;

	private $cookieName;
	private $cookieTime;
	private $passwordKey = 'phpma_customer_area';
	
	//==========================================================================
    // Class Constructor
	//==========================================================================
	function __construct()
	{
		$this->ipAddressBlocked = false;
		$this->emailBlocked = false;
		$this->loginError = '';
		
		$this->cookieName = 'site_auth'.INSTALLATION_KEY;
		$this->cookieTime = (3600 * 24 * 14); // 14 days

		$submit_login  = isset($_POST['submit_login']) ? prepare_input($_POST['submit_login']) : '';
		$submit_logout = isset($_POST['submit_logout']) ? prepare_input($_POST['submit_logout']) : '';
		$user_name     = isset($_POST['user_name']) ? prepare_input($_POST['user_name'], true) : '';
		$password      = isset($_POST['password']) ? prepare_input($_POST['password'], true) : '';
		$this->accountType = isset($_POST['type']) ? prepare_input($_POST['type']) : 'patient';
		$remember_me   = isset($_POST['remember_me']) ? prepare_input($_POST['remember_me']) : '';
		
		$this->wrongLogin = false;		
		if(!$this->IsLoggedIn()){
			if($submit_login == 'login'){
				if(empty($user_name) || empty($password)){
					if(isset($_POST['user_name']) && empty($user_name)){
						$this->loginError = '_USERNAME_EMPTY_ALERT';						
					}else if(isset($_POST['password']) && empty($password)){
						$this->loginError = '_WRONG_LOGIN';
					}
					$this->wrongLogin = true;							
				}else{
					$this->DoLogin($user_name, $password, $remember_me);
				}
			}else{
				if(isset($_COOKIE[$this->cookieName])){
					parse_str($_COOKIE[$this->cookieName]);
					if(!empty($type) && !empty($usr) && !empty($hash)){
						$this->accountType = $type;
						$user_name = $usr;
						$password = $this->Decrypt($hash, $this->passwordKey);					
						$this->DoLogin($user_name, $password, '2');
					}
				}				
			}
		}else if($submit_logout == 'logout'){
			$this->DoLogout();
		}
		$this->activeMenuCount = 0;
	}
	
	//==========================================================================
    // Class Destructor
	//==========================================================================
    function __destruct()
	{
		// echo 'this object has been destroyed';
    }

	/**
	 * 	Do login
	 * 		@param $user_name - system name of user
	 * 		@param $password - password of user
	 * 		@param $remember_me
	 * 		@param $do_redirect - prepare redirect or not
	 */
	private function DoLogin($user_name, $password, $remember_me = '', $do_redirect = true)
	{
		global $objSession;
		
		$ip_address = get_current_ip();

		if($account_information = $this->GetAccountInformation($user_name, $password)){

			if($account_information['is_active'] == '0'){
				if(!empty($account_information['registration_code']) != ''){
					$this->loginError = '_REGISTRATION_NOT_COMPLETED';	
				}else{
					$this->loginError = '_WRONG_LOGIN';
				}				
				$this->wrongLogin = true;
				return false;
			}

			ob_start();
			$this->SetSessionVariables($account_information);

			if($this->IsLoggedInAsPatient(false) || $this->IsLoggedInAsDoctor(false)){
				if($this->IpAddressBlocked($ip_address)){
					$this->DoLogout();
					$this->ipAddressBlocked = true;
					$do_redirect = false;
				}else if($this->EmailBlocked($this->GetLoggedEmail(false))){
					$this->DoLogout();
					$this->emailBlocked = true;
					$do_redirect = false;					
				}
			}

			$this->UpdateAccountInfo($account_information);
			if($do_redirect){
				$objSession->SetFingerInfo();
				
				if($remember_me == '2'){
					// ignore and do nothing - allow cookies to expire in $this->cookieTime sec.
				}else if($remember_me == '1'){
					$password_hash = $this->Encrypt($password, $this->passwordKey);
					setcookie($this->cookieName, 'type='.$this->accountType.'&usr='.$user_name.'&hash='.$password_hash, time() + $this->cookieTime);
				}else{
					setcookie($this->cookieName, '', time() - 3600);
				}

				$redirect_page = 'index.php';
				if($this->IsLoggedInAsPatient() || $this->IsLoggedInAsDoctor()){
					$redirect_page  = (Session::Get('last_visited') != '') ? Session::Get('last_visited') : 'index.php?'.$this->GetLoggedType().'=home';
					$redirect_page .= (preg_match('/\?/', $redirect_page) ? '&' : '?').'lang='.$this->GetPreferredLang();
				}				 
                redirect_to($redirect_page);
				ob_end_flush();
				exit;
			}
		}else{
			$this->loginError = '_WRONG_LOGIN';			
			$this->wrongLogin = true;
		}
	}
	
	/**
	 * 	Checks if login was wrong
	 */
	public function IsWrongLogin()
	{
		return ($this->wrongLogin == true) ? true : false;	
	}

	/**
	 * 	Checks if IP address was blocked
	 */
	public function IsIpAddressBlocked()
	{
		return ($this->ipAddressBlocked == true) ? true : false;	
	}

	/**
	 * 	Checks if IP address was blocked
	 */
	public function IsEmailBlocked()
	{
		return ($this->emailBlocked == true) ? true : false;	
	}

	/**
	 * 	Destroys the session and returns to the default page
	 */
	public function DoLogout()
	{
		global $objSession;
		
		$redirect = ($this->IsLoggedInAsAdmin()) ? 'index.php?admin=login' : '';
		$objSession->EndSession();
		setcookie($this->cookieName, '', time() - 3600);
		
		if($redirect != ''){
			redirect_to($redirect);
		}
	}

	/**
	 * 	Checks IP address
	 * 		@param $ip_address
	 */
	public function IpAddressBlocked($ip_address)
	{
		$sql = 'SELECT ban_item
				FROM '.TABLE_BANLIST.' 
				WHERE ban_item = \''.$ip_address.'\' AND ban_item_type = \'IP\'';
		return database_query($sql, ROWS_ONLY);		
	}

	/**
	 * 	Checks email address
	 * 		@param $email
	 */
	public function EmailBlocked($email)
	{
		$sql = 'SELECT ban_item
				FROM '.TABLE_BANLIST.'
				WHERE ban_item = \''.$email.'\' AND ban_item_type = \'Email\'';
		return database_query($sql, ROWS_ONLY);		
	}

	/**
	 * 	Checks account password
	 * 		@param $account_type 
	 * 		@param $password
	 */
	public function CheckAccountPassword($account_type, $password)
	{
        if(!$this->IsLoggedIn()) return false;
        $this->accountType = (in_array($account_type, array('doctor', 'patient'))) ? $account_type : '';
        $result = $this->GetAccountInformation($this->GetLoggedName(), $password);
        return !empty($result) ? true : false;
    }

	/**
	 * 	Sets membership info
	 */
	public static function SetMembershipInfo($account_id = 0)
	{
		if(!$account_id) $account_id = Session::Get('session_account_id');
		$result = Doctors::GetMembershipInfo($account_id);
		$preferences_info = array();
		foreach($result as $key => $val){
			$preferences_info[$key] = $val;
		}
		Session::Set('session_membership_preferences', $preferences_info);
    }    
    
	/**
	 * 	Returns membership info
	 * 		@param $param - 'plan_name', 'images_count', 'show_on_search' or 'expires'
	 */
	public function GetMembershipInfo($param)
	{
        if(!$this->IsLoggedInAsDoctor()) return false;
        $membership_info = Session::Get('session_membership_preferences');
        return isset($membership_info[$param]) ? $membership_info[$param] : '';
    }
    
	/**
	 * 	Gets the account information
	 * 		@param $user_name - system name of user
	 * 		@param $password - password of user
	 */
	private function GetAccountInformation($user_name, $password)
	{
		if(PASSWORDS_ENCRYPTION){			
			if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'aes'){
				$password = 'AES_ENCRYPT(\''.$password.'\', \''.PASSWORDS_ENCRYPT_KEY.'\')';
			}else if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'md5'){
				$password = 'MD5(\''.$password.'\')';
			}	
		}else{
			$password = '\''.$password.'\'';
		}
        
		if($this->accountType == 'admin'){
			$sql = 'SELECT '.TABLE_ACCOUNTS.'.*, user_name AS account_name
					FROM '.TABLE_ACCOUNTS.'
					WHERE  user_name = \''.$user_name.'\' AND 
						   password = '.$password;			
		}else if($this->accountType == 'doctor'){
			$sql = 'SELECT '.TABLE_DOCTORS.'.*, user_name AS account_name
					FROM '.TABLE_DOCTORS.'
					WHERE user_name = \''.$user_name.'\' AND 
						  user_password = '.$password.'';			
		}else{
			$first_name = (PATIENTS_ENCRYPTION ? 'AES_DECRYPT(first_name, "'.PASSWORDS_ENCRYPT_KEY.'") as first_name' : 'first_name');
			$last_name = (PATIENTS_ENCRYPTION ? 'AES_DECRYPT(last_name, "'.PASSWORDS_ENCRYPT_KEY.'") as last_name' : 'last_name');
			$sql = 'SELECT '.TABLE_PATIENTS.'.*,
						'.$first_name.',
						'.$last_name.',
						user_name AS account_name
					FROM '.TABLE_PATIENTS.'
					WHERE user_name = \''.$user_name.'\' AND 
						  user_password = '.$password.' AND
						  is_removed = 0';
		}
		return database_query($sql, DATA_ONLY, FIRST_ROW_ONLY);
	}

	/**
	 * 	Checks to see if the user is logged in
	 */
	public function IsLoggedIn()
	{
		global $objSession;
		
		$logged = Session::Get('session_account_logged');
		$id = Session::Get('session_account_id');
	    if($logged == str_replace(array('modules/tinymce/plugins/imageupload/', 'ajax/', 'modules/ratings/lib/'), '', $this->GetUniqueUrl()).$id){
            if(!$objSession->AnalyseFingerInfo()){
				return false;
			}
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 	Returns last login time
	 */
	public function GetLastLoginTime()
	{
		$last_login = Session::Get('session_last_login');

		if(!empty($last_login)){
			return $last_login;
		}else{
			return '--';
		}
	}

	/**
	 * 	Checks to see if the user is logged in as a specific account type
	 * 		@return true if the user is logged in as specified account type, false otherwise
	 */
	public function IsLoggedInAs()
	{
		if(!$this->IsLoggedIn()) return false;

		$account_type = Session::Get('session_account_type');

		$types = func_get_args();
		foreach($types as $type){
			$type_parts = explode(',', $type);
			foreach($type_parts as $type_part){
				if($account_type == $type_part) return true;	
			}			
		}
		return false;
	}

	/**
	 * 	Checks to see if the user is logged in as a specific account type
	 * 		@return true if the user is logged in as specified account type, false otherwise
	 */
	public function IsLoggedInAsAdmin()
	{
		if(!$this->IsLoggedIn()) return false;
		$account_type = Session::Get('session_account_type');
		if($account_type == 'owner' || $account_type == 'mainadmin' || $account_type == 'admin') return true;
		return false;
	}

	/**
	 * 	Checks to see if the user is logged in as a specific account type
	 * 		@param $check
	 * 		@return true if the user is logged in as specified account type, false otherwise
	 */
	public function IsLoggedInAsPatient($check = true)
	{
		if(!$this->IsLoggedIn() && $check) return false;
		$account_type = Session::Get('session_account_type');
		if($account_type == 'patient') return true;
		return false;
	}
	
	/**
	 * 	Checks to see if the user is logged in as a specific account type
	 * 		@param $check
	 * 		@return true if the user is logged in as specified account type, false otherwise
	 */
	public function IsLoggedInAsDoctor($check = true)
	{
		if(!$this->IsLoggedIn() && $check) return false;
		$account_type = Session::Get('session_account_type');
		if($account_type == 'doctor') return true;
		return false;
	}

	/**
	 * 	Returns the type of logged user 
	 */
	public function GetLoggedType()
	{
		if(!$this->IsLoggedIn()) return false;
		return Session::Get('session_account_type');
	}

	/**
	 * 	Returns the email of logged user 
	 */
	public function GetLoggedEmail($check = true)
	{
		if(!$this->IsLoggedIn() && $check) return false;
		return Session::Get('session_user_email');
	}

	/**
	 * 	Sets the email of logged user 
	 */
	public function UpdateLoggedEmail($new_email)
	{
		if(!$this->IsLoggedIn()) return false;
		Session::Set('session_user_email', $new_email);
	}

	/**
	 * 	Returns the name of logged user 
	 */
	public function GetLoggedName()
	{
		return Session::Get('session_user_name');
	}
	
	/**
	 * 	Returns the first name of logged user 
	 */
	public function GetLoggedFirstName()
	{
		return Session::Get('session_user_first_name');
	}

	/**
	 * 	Returns the last name of logged user 
	 */
	public function GetLoggedLastName()
	{
		return Session::Get('session_user_last_name');
	}

	/**
	 * 	Update first name of logged user 
	 */
	public function UpdateLoggedFirstName($first_name)
	{
		return Session::Set('session_user_first_name', $first_name);
	}

	/**
	 * 	Update last name of logged user 
	 */
	public function UpdateLoggedLastName($last_name)
	{
		return Session::Set('session_user_last_name', $last_name);
	}
	
	/**
	 * 	Returns ID of logged user
	 */
	public function GetLoggedID()
	{
		return Session::Get('session_account_id');
	}
	
	/**
	 * 	Returns preferred language
	 */
	public function GetPreferredLang()
	{
		return Session::Get('session_preferred_language');
	}	

	/**
	 * 	Sets preferred language
	 * 		@param $lang
	 */
	public function SetPreferredLang($lang)
	{
		Session::Set('session_preferred_language', $lang);
	}	

	/**
	 * 	Sets the session variables and performs the login
	 * 		@param $account_information - array
	 */
	private function SetSessionVariables($account_information)
	{
		Session::Set('session_account_id', $account_information['id']);
		Session::Set('session_account_logged', (($account_information['id']) ? $this->GetUniqueUrl().$account_information['id'] : false));			
		Session::Set('session_user_name', $account_information['user_name']);
		Session::Set('session_user_first_name', $account_information['first_name']);
		Session::Set('session_user_last_name', $account_information['last_name']);		
		Session::Set('session_user_email', $account_information['email']);
		if($this->accountType == 'admin'){
			$account_type = $account_information['account_type'];
		}else if($this->accountType == 'doctor'){
			$account_type = 'doctor';
		}else{
			$account_type = 'patient';
		}
		Session::Set('session_account_type', $account_type);
		Session::Set('session_last_login', $account_information['date_lastlogin']);

		// prepare doctor membership plan preferences
        self::SetMembershipInfo($account_information['id']);

		// check if predefined lang still exists, if not set default language		
		if(isset($account_information['preferred_language']) && Languages::LanguageActive($account_information['preferred_language'])){
			$preferred_language = $account_information['preferred_language'];
		}else{
			$preferred_language = Languages::GetDefaultLang();
		}
		Session::Set('session_preferred_language', $preferred_language);
		
		// prepare role privileges
		$result = Roles::GetPrivileges(Session::Get('session_account_type'));
		$privileges_info = array();
		for($i = 0; $i < $result[1]; $i++){
			$privileges_info[$result[0][$i]['code']] = ($result[0][$i]['is_active'] == '1') ? true : false;
		}		
		Session::Set('session_user_privileges', $privileges_info);
		
		// clean some session variables
		Session::Set('preview', '');
	}
	
	/**
	 *  Get unique URL 
	 */
	private function GetUniqueUrl()
	{
		$port = '';
		$http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != '80'){
			if(!strpos($http_host, ':')){
				$port = ':'.$_SERVER['SERVER_PORT'];
			}
		}	
		$folder = isset($_SERVER['SCRIPT_NAME']) ? substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')+1) : '';	
		return $http_host.$port.$folder;
	}

	/**
	 * 	Returns count of active menus
	 * 		@return number on menus
	 */
	public function GetActiveMenuCount()
	{
		return $this->activeMenuCount;
	}	
	
	/**
	 * 	Updates Account Info	
	 * 		@param $account_information - array
	 */
	private function UpdateAccountInfo($account_information)
	{
		if($this->accountType == 'admin'){
			$sql = 'UPDATE '.TABLE_ACCOUNTS.'
					SET date_lastlogin = \''.@date('Y-m-d H:i:s').'\'
					WHERE id = '.(int)$account_information['id'];
		}else if($this->accountType == 'doctor'){
			$sql = 'UPDATE '.TABLE_DOCTORS.'
					SET date_lastlogin = \''.@date('Y-m-d H:i:s').'\',
						last_logged_ip = \''.get_current_ip().'\'
					WHERE id = '.(int)$account_information['id'];						
		}else{
			$sql = 'UPDATE '.TABLE_PATIENTS.'
					SET date_lastlogin = \''.@date('Y-m-d H:i:s').'\',
						last_logged_ip = \''.get_current_ip().'\'
					WHERE id = '.(int)$account_information['id'];			
		}
		return database_void_query($sql);
	}	

	/**
	 * 	Removes user account
	 */	
	public function RemoveAccount()
	{
		if($this->GetLoggedType() == 'doctor'){
			$table = TABLE_DOCTORS;
		}else{
			$table = TABLE_PATIENTS;
		}		
		$sql = 'UPDATE '.$table.'
				SET is_removed = 1, is_active = 0, comments = CONCAT(comments, "\r\n'.@date('Y-m-d H:i:s').' - account was removed by user.") 
				WHERE id = '.(int)$this->GetLoggedID();
		return (database_void_query($sql) > 0 ? true : false);
	}

	/**
	 * 	Get Login Error
	 */	
	public function GetLoginError()
	{
		return defined($this->loginError) ? constant($this->loginError) : '';
	}

	/**
	 * Check if user has privilege
	 * 		@param $code
	 */
	public function HasPrivileges($code = '')
	{
		$privileges_info = Session::Get('session_user_privileges');		
		return (isset($privileges_info[$code]) && $privileges_info[$code] == true) ? true : false;
	}	

	/**
	 * 	Draws the login links and logout form
	 */
	public function DrawLoginLinks()
	{
		if(Application::Get('preview') == 'yes') return '';
		
		$menu_index = '0';
		$text_align = (Application::Get('lang_dir') == 'ltr') ? 'text-align: left;' : 'text-align: right; padding-right:15px;';
		
		// ---------------------------------------------------------------------
		// MAIN ADMIN LINKS
		if($this->IsLoggedInAsAdmin()){
			draw_block_top(_MENUS.': [ <a id="lnk_all_open" href="javascript:void(0);" onclick="javascript:toggle_menus(1)">'._OPEN.'</a> | <a id="lnk_all_close" href="javascript:void(0);" onclick="javascript:toggle_menus(0)">'._CLOSE.'</a> ]');			
			draw_block_bottom();

			draw_block_top(_GENERAL, $menu_index++, 'maximized');        
				echo '<ul>';
				echo '<li>'.$this->PrepareLink('home', _HOME).'</li>';
				if($this->IsLoggedInAs('owner','mainadmin')) echo '<li>'.$this->PrepareLink('settings', _SETTINGS).'</li>';				
				echo '<li>'.$this->PrepareLink('ban_list', _BAN_LIST).'</li>';
				if($this->IsLoggedInAs('owner','mainadmin')) echo '<li>'.$this->PrepareLink('countries_management', _COUNTRIES, '', '', array('states_management')).'</li>';
				echo '<li>'.prepare_permanent_link('index.php?preview=yes', _PREVIEW.' <img src="images/external_link.gif" alt="external link" />').'</li>';
			echo '</ul>';
			draw_block_bottom();

			draw_block_top(_ACCOUNTS_MANAGEMENT, $menu_index++);
				echo '<div class="menu_category">';
				echo '<ul>';
				echo '<li>'.$this->PrepareLink('my_account', _MY_ACCOUNT).'</li>';
				if(Modules::IsModuleInstalled('patients') && $this->IsLoggedInAs('owner','mainadmin')) echo '<li>'.$this->PrepareLink('statistics', _STATISTICS).'</li>';
				if($this->IsLoggedInAs('owner')) echo '<li>'.$this->PrepareLink('roles_management', _ROLES_AND_PRIVILEGES, '', '', array('role_privileges_management')).'</li>';
				echo '</ul>';
				if($this->IsLoggedInAs('owner','mainadmin')){
					echo '<label>'._ADMINS_MANAGEMENT.'</label>';
					echo '<ul>';
					echo '<li>'.$this->PrepareLink('admins_management', _ADMINS).'</li>';
					echo '</ul>';
				}				
				if(Modules::IsModuleInstalled('patients') && $this->IsLoggedInAs('owner','mainadmin')){
					echo '<label>'._PATIENTS_MANAGEMENT.'</label>';
					echo '<ul>';
					echo '<li>'.$this->PrepareLink('mod_patients_groups', _PATIENT_GROUPS).'</li>';
					echo '<li>'.$this->PrepareLink('mod_patients_management', _PATIENTS).'</li>';
					echo '</ul>';
				}
				if($this->IsLoggedInAs('owner','mainadmin')){
					echo '<label>'._DOCTORS_MANAGEMENT.'</label>';
					echo '<ul>';			
					echo '<li>'.$this->PrepareLink('mod_doctors_settings', _DOCTORS_SETTINGS).'</li>';
					echo '<li>'.$this->PrepareLink('doctors_management', _DOCTORS, '', '', array('doctors_specialities','doctors_addresses','doctors_upload_images')).'</li>';
					echo '</ul>';
				}				
				echo '</div>';
			draw_block_bottom();

			draw_block_top(_CLINIC_MANAGEMENT, $menu_index++); 
				echo '<div class="menu_category">';
				echo '<ul>';
				echo '<li>'.$this->PrepareLink('clinic_info', _CLINIC_INFO).'</li>';
				if($this->IsLoggedInAs('owner','mainadmin')) echo '<li>'.$this->PrepareLink('mod_doctors_integration', _INTEGRATION).'</li>';
				if($this->IsLoggedInAs('owner','mainadmin')) echo '<li>'.$this->PrepareLink('specialities_management', _SPECIALITIES_MANAGEMENT).'</li>';
				if($this->IsLoggedInAs('owner','mainadmin')) echo '<li>'.$this->PrepareLink('schedules_management', _SCHEDULES, '', '', array('schedules_set_timeblocks')).'</li>';
				if($this->IsLoggedInAs('owner','mainadmin')) echo '<li>'.$this->PrepareLink('timeoff_management', _TIMEOFF).'</li>';
				echo '</ul>';			
				echo '</div>';
			draw_block_bottom();

			if($this->IsLoggedInAs('owner','mainadmin')){
				draw_block_top(_APPOINTMENTS, $menu_index++);
					echo '<div class="menu_category">';
					echo '<ul>';
					echo '<label>'._SETTINGS.'</label>';
					echo '<ul>';
					echo '<li>'.$this->PrepareLink('mod_appointments_settings', _APPOINTMENTS_SETTINGS).'</li>';
                    echo '<li>'.$this->PrepareLink('mod_appointments_insurances', _INSURANCES).'</li>';
                    echo '<li>'.$this->PrepareLink('mod_appointments_visit_reasons', _VISIT_REASONS).'</li>';
					echo '</ul>';
					echo '<label>'._MANAGEMENT.'</label>';
					echo '<ul>';
					echo '<li>'.$this->PrepareLink('mod_appointments_create', _CREATE_APPOINTMENT).'</li>';
					echo '<li>'.$this->PrepareLink('mod_appointments_management', _APPOINTMENTS_MANAGEMENT).'</li>';
					echo '<li>'.$this->PrepareLink('mod_appointments_statistics', _STATISTICS).'</li>';
					echo '</ul>';
					echo '</div>';
				draw_block_bottom();
			}

            if($this->IsLoggedInAs('owner','mainadmin')){
            draw_block_top(_PAYMENTS, $menu_index++);
                echo '<ul>';
                echo '<li>'.$this->PrepareLink('mod_appointments_currencies', _CURRENCIES).'</li>';
                if(Modules::IsModuleInstalled('payments') && ModulesSettings::Get('payments', 'is_active') == 'yes'){
                    echo '<li>'.$this->PrepareLink('mod_payments_membership_plans', _DOCTOR_MEMBERSHIP_PLANS).'</li>';
                    echo '<li>'.$this->PrepareLink('mod_payments_orders', _ORDERS).'</li>';
                    echo '<li>'.$this->PrepareLink('mod_payments_statistics', _STATISTICS).'</li>';
                }			
                echo '</ul>';
            }
            draw_block_bottom();				
			

			if($this->HasPrivileges('add_menus') || $this->HasPrivileges('edit_menus') || $this->HasPrivileges('add_pages') || $this->HasPrivileges('edit_pages')){					
				draw_block_top(_MENUS_AND_PAGES, $menu_index++);
					echo '<div class="menu_category">';
					if($this->HasPrivileges('add_menus') || $this->HasPrivileges('edit_menus')){
						echo '<label>'._MENU_MANAGEMENT.'</label>';
						echo '<ul>';			
						if($this->HasPrivileges('add_menus')) echo '<li>'.$this->PrepareLink('menus_add', _ADD_NEW_MENU).'</li>';
						echo '<li>'.$this->PrepareLink('menus', _EDIT_MENUS, '', '', array('menus_edit')).'</li>';
						echo '</ul>';
					}
	
					if($this->HasPrivileges('add_pages') || $this->HasPrivileges('edit_pages')){
						echo '<label>'._PAGE_MANAGEMENT.'</label>';
						echo '<ul>';			
						if($this->HasPrivileges('add_pages')) echo '<li>'.$this->PrepareLink('pages_add', _PAGE_ADD_NEW).'</li>';
						if($this->HasPrivileges('edit_pages')) echo '<li>'.$this->PrepareLink('pages_edit', _PAGE_EDIT_HOME, 'type=home').'</li>';
						echo '<li>'.$this->PrepareLink('pages', _PAGE_EDIT_PAGES, 'type=general').'</li>';
						if($this->HasPrivileges('edit_pages')) echo '<li>'.$this->PrepareLink('pages', _PAGE_EDIT_SYS_PAGES, 'type=system').'</li>';				
						if($this->HasPrivileges('edit_pages')) echo '<li>'.$this->PrepareLink('pages_trash', _TRASH).'</li>';				
						echo '</ul>';						
					}
					echo '</div>';
				draw_block_bottom();
			}

			draw_block_top(_LANGUAGES_SETTINGS, $menu_index++);
				echo '<ul>';			
				if ($this->IsLoggedInAs('owner','mainadmin')) echo '<li>'.$this->PrepareLink('languages', _LANGUAGES, '', '', array('languages_add','languages_edit')).'</li>';
				echo '<li>'.$this->PrepareLink('vocabulary', _VOCABULARY, 'filter_by=A').'</li>';
				echo '</ul>';
			draw_block_bottom();

			if($this->IsLoggedInAs('owner','mainadmin')){
				draw_block_top(_MASS_MAIL_AND_TEMPLATES, $menu_index++);
					echo '<ul>';			
					if($this->IsLoggedInAs('owner','mainadmin')) echo '<li>'.$this->PrepareLink('email_templates', _EMAIL_TEMPLATES).'</li>';
					if($this->IsLoggedInAs('owner','mainadmin')) echo '<li>'.$this->PrepareLink('mass_mail', _MASS_MAIL).'</li>';
					echo '</ul>';
				draw_block_bottom();
			}

			// MODULES
			$sql = 'SELECT * FROM '.TABLE_MODULES.' WHERE is_installed = 1 AND is_system = 0 ORDER BY priority_order ASC';
			$modules = database_query($sql, DATA_AND_ROWS, ALL_ROWS);
			
			$modules_output = '';
			for($i=0; $i < $modules[1]; $i++){
				$output = '';
				if($modules[0][$i]['settings_access_by'] == '' || ($modules[0][$i]['settings_access_by'] != '' && $this->IsLoggedInAs($modules[0][$i]['settings_access_by']))){
					if($modules[0][$i]['settings_const'] != '') $output .= '<li>'.$this->PrepareLink($modules[0][$i]['settings_page'], constant($modules[0][$i]['settings_const'])).'</li>';
				}
				if($modules[0][$i]['management_access_by'] == '' || ($modules[0][$i]['management_access_by'] != '' && $this->IsLoggedInAs($modules[0][$i]['management_access_by']))){
					$management_pages = explode(',', $modules[0][$i]['management_page']);
					$management_consts = explode(',', $modules[0][$i]['management_const']);
					$management_pages_total = count($management_pages);
					for($j=0; $j < $management_pages_total; $j++){
						if(isset($management_pages[$j]) && isset($management_consts[$j]) && $management_consts[$j] != ''){
							$output .= '<li>'.$this->PrepareLink($management_pages[$j], constant($management_consts[$j])).'</li>';
						}
					}							
				}
				if($output){
					$modules_output .= '<label>'.constant($modules[0][$i]['name_const']).'</label>';
					$modules_output .= '<ul>'.$output.'</ul>';										
				}
			}
			if(!empty($modules_output)){
				draw_block_top(_MODULES, $menu_index++);
					if($this->IsLoggedInAs('owner','mainadmin')){
						echo '<ul>';			
						echo '<li>'.$this->PrepareLink('modules', _MODULES_MANAGEMENT).'</li>';				
						echo '</ul>';
					}					
					echo '<div class="menu_category">'.$modules_output.'</div>';
				draw_block_bottom();	
			}			
		}
	
		// ---------------------------------------------------------------------
		// PATIENT LINKS
		if($this->IsLoggedInAsPatient()){
			echo '<div id="column-left-wrapper">';
			draw_block_top(_MY_ACCOUNT);
				echo '<ul>';
				echo '<li>'.$this->PrepareLink('home', _DASHBOARD).'</li>';
				echo '<li>'.$this->PrepareLink('my_account', _EDIT_MY_ACCOUNT).'</li>';
				echo '<li>'.$this->PrepareLink('my_appointments', _MY_APPOINTMENTS).'</li>';
				echo '</ul>';
			draw_block_bottom();
			echo '</div>';
		}				

		// ---------------------------------------------------------------------
		// DOCTOR LINKS
		if($this->IsLoggedInAsDoctor()){
			echo '<div id="column-left-wrapper">';
			draw_block_top(_MY_ACCOUNT);
                echo '<label>'._GENERAL.'</label>';
                echo '<ul>';
				echo '<li>'.$this->PrepareLink('home', _DASHBOARD).'</li>';
                echo '</ul>';

                echo '<label>'._PROFILE_DETAILS.'</label>';
                echo '<ul>';
				echo '<li>'.$this->PrepareLink('my_account', _EDIT_MY_ACCOUNT).'</li>';
				echo '<li>'.$this->PrepareLink('my_specialities', _MY_SPECIALITIES).'</li>';
				echo '<li>'.prepare_permanent_link('index.php?page=doctors&docid='.$this->GetLoggedID(), _VIEW_PROFILE).'</li>';
                echo '</ul>';
                
                echo '<label>'._MEMBERSHIP_INFO.'</label>';
                echo '<ul>';
				if(Modules::IsModuleInstalled('payments') && ModulesSettings::Get('payments', 'is_active') == 'yes'){
                    echo '<li>'.$this->PrepareLink('membership_plans', _MEMBERSHIP_PLANS).'</li>';
                    echo '<li>'.$this->PrepareLink('my_orders', _MY_ORDERS).'</li>';
                }
                echo '<li>'.$this->PrepareLink('my_images_upload', _MY_IMAGES).'</li>';
				echo '<li>'.$this->PrepareLink('my_addresses', _MY_ADDRESSES).'</li>';
				echo '</ul>';

                echo '<label>'._APPOINTMENTS_MANAGEMENT.'</label>';
                echo '<ul>';
				echo '<li>'.$this->PrepareLink('appointments', _APPOINTMENTS).'</li>';
				echo '<li>'.$this->PrepareLink('schedules_management', _SCHEDULES).'</li>';
				echo '<li>'.$this->PrepareLink('timeoff_management', _TIMEOFF).'</li>';
				echo '</ul>';                
			draw_block_bottom();
			echo '</div>';
		}				

		// Logout
		if($this->IsLoggedIn()){
			echo '<div id="column-left-wrapper">';
			draw_block_top_empty();
			echo '<form action="index.php" method="post">
       			  '.draw_hidden_field('submit_logout', 'logout', false).'
				  '.draw_token_field(false).'
				  &nbsp;&nbsp;<input class="form_button" type="submit" name="btnLogout" value="'._BUTTON_LOGOUT.'" />&nbsp;&nbsp;
				  </form>';
			draw_block_bottom();
			echo '</div>';
            echo '<br />';
		}		
		
		$this->activeMenuCount = $menu_index;
	}

	/**
	 * Prepare admin panel link
	 * 		@param $href
	 * 		@param $link
	 * 		@param $params
	 * 		@param $class
	 * 		@param $href_array
	 */
	private function PrepareLink($href, $link, $params='', $class='', $href_array=array())
	{
		$output = '';
		$css_class = (($class != '') ? $class : '');
		if($this->GetLoggedType() == 'patient'){
			$logged_as = 'patient';	
		}else if($this->GetLoggedType() == 'doctor'){
			$logged_as = 'doctor';	
		}else{
			$logged_as = 'admin';
		}		
		
		if(Application::Get($logged_as) == $href || in_array(Application::Get($logged_as), $href_array)){
			$is_active = true;
			if(!empty($params)){
				$params_parts = explode('=', $params);
				$f_param  = (isset($params_parts[0]) && isset($_GET[$params_parts[0]])) ? $_GET[$params_parts[0]] : '';
				$s_param = isset($params_parts[1]) ? $params_parts[1] : '';
				if($f_param != $s_param) $is_active = false; 
			}
		}else{
			$is_active = false;
		}
		
		if(!empty($css_class)){
			$css_class = ($is_active ? $css_class.' active' : '');	
		}else{
			$css_class = ($is_active ? 'active' : '');	
		}
	
		$output = prepare_permanent_link('index.php?'.$logged_as.'='.$href.((!empty($params)) ? '&'.$params : $params), $link, '', $css_class);
		return $output;
	}

	/**
	 * Encrypt
	 * 		@param $value
	 * 		@param $secret_key
	 */
	private function Encrypt($value, $secret_key)
	{
		if(version_compare(phpversion(), '7.0.0', '<')){
			$return = trim(strtr(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $secret_key, $value, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))), '+/=', '-_,'));
		}else{
			// Generate an initialization vector
			$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
			// Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector
			$encrypted = openssl_encrypt($value, 'aes-256-cbc', $secret_key, 0, $iv);
			// The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (self::$_separator)
			$return = base64_encode($encrypted . '::' . base64_encode($iv));
		}
		
		return $return;
	}

	/**
	 * Decrypt
	 * 		@param $value
	 * 		@param $secret_key
	 */
	private function Decrypt($value, $secret_key)
	{
		if(version_compare(phpversion(), '7.0.0', '<')){
			$return = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $secret_key, base64_decode(strtr($value, '-_,', '+/=')), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
		}else{
			// To decrypt, split the encrypted data from our IV - our unique separator used was '::'
			list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($value), 2), 2, '');
			// Validate IV value
			$iv = base64_decode($iv);
			if ( strlen($iv) == 16) {
				$return = openssl_decrypt($encrypted_data, 'aes-256-cbc', $secret_key, 0, $iv);
			} else {
				$return = '';
			}
		}
		
		return $return;
	}

}
