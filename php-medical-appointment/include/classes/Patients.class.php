<?php

/**
 *	Class Patients 
 *  -------------- 
 *  Description : encapsulates patients methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 02.03.2011
 *  Usage       : MedicalAppointment
 *	
 *	PUBLIC				  	STATIC				 	PRIVATE
 * 	------------------	  	---------------     	---------------
 *	__construct			  	SendPassword                                  
 *	__destruct            	Reactivate                          
 *	BeforeEditRecord        DrawLoginFormBlock
 *	AfterEditRecord         GetPatientInfo
 *	BeforeUpdateRecord      AwaitingAprovalCount
 *	AfterUpdateRecord       
 *	AfterAddRecord
 *	AfterInsertRecord
 *	GetAllPatients
 *	
 **/

class Patients extends MicroGrid {
	
	protected $debug = false;
	
	//---------------------------
	private $email_notifications;
	private $patient_password;
	private $allow_adding_by_admin;
	private $allow_changing_password;
	private $reg_confirmation;
	private $sqlFieldDatetimeFormat = '';
	private $sqlFieldDateFormat = '';
	
	//==========================================================================
    // Class Constructor
	//==========================================================================
	function __construct()
	{
		parent::__construct();
		
		global $objSettings;
		
		$this->params = array();		
		if(isset($_POST['group_id']))   $this->params['group_id']    = (int)prepare_input($_POST['group_id']);
		if(isset($_POST['first_name'])) $this->params['first_name']  = prepare_input($_POST['first_name']);
		if(isset($_POST['last_name']))	$this->params['last_name']   = prepare_input($_POST['last_name']);
		if(isset($_POST['gender']))      $this->params['gender'] = prepare_input($_POST['gender']);
		if(isset($_POST['birth_date']) && ($_POST['birth_date'] != ''))  $this->params['birth_date'] = prepare_input($_POST['birth_date']); else $this->params['birth_date'] = null;	
		if(isset($_POST['company']))   	$this->params['company']     = prepare_input($_POST['company']);
		if(isset($_POST['b_address']))  $this->params['b_address']   = prepare_input($_POST['b_address']);
		if(isset($_POST['b_address_2']))$this->params['b_address_2'] = prepare_input($_POST['b_address_2']);
		if(isset($_POST['b_city']))   	$this->params['b_city']      = prepare_input($_POST['b_city']);
		if(isset($_POST['b_state']))   	$this->params['b_state']     = prepare_input($_POST['b_state']);
		if(isset($_POST['b_country']))	$this->params['b_country']   = prepare_input($_POST['b_country']);
		if(isset($_POST['b_zipcode']))	$this->params['b_zipcode']   = prepare_input($_POST['b_zipcode']);
		if(isset($_POST['phone'])) 		$this->params['phone'] 		 = prepare_input($_POST['phone']);
		if(isset($_POST['fax'])) 		$this->params['fax'] 		 = prepare_input($_POST['fax']);
		if(isset($_POST['email'])) 		$this->params['email'] 		 = prepare_input($_POST['email']);
		if(isset($_POST['url'])) 		$this->params['url'] 		 = prepare_input($_POST['url']);
		if(isset($_POST['user_name']))  $this->params['user_name']   = prepare_input($_POST['user_name']);
		if(isset($_POST['user_password']))  	$this->params['user_password']  = prepare_input($_POST['user_password']);
		if(isset($_POST['preferred_language'])) $this->params['preferred_language'] = prepare_input($_POST['preferred_language']);
		if(isset($_POST['date_created']))  		$this->params['date_created']   = prepare_input($_POST['date_created']);
		if(isset($_POST['date_lastlogin']))  	$this->params['date_lastlogin'] = prepare_input($_POST['date_lastlogin']);
		if(isset($_POST['registered_from_ip'])) $this->params['registered_from_ip'] = prepare_input($_POST['registered_from_ip']);
		if(isset($_POST['last_logged_ip'])) 	$this->params['last_logged_ip'] 	= prepare_input($_POST['last_logged_ip']);
		if(isset($_POST['email_notifications'])) 		 $this->params['email_notifications'] 		  = prepare_input($_POST['email_notifications']); else $this->params['email_notifications'] = '0';
		if(isset($_POST['notification_status_changed'])) $this->params['notification_status_changed'] = prepare_input($_POST['notification_status_changed']);
		if(isset($_POST['is_active']))  		$this->params['is_active']  = prepare_input($_POST['is_active']); else $this->params['is_active'] = '0';
		if(isset($_POST['is_removed'])) 		$this->params['is_removed'] = prepare_input($_POST['is_removed']); else $this->params['is_removed'] = '0';
		if(isset($_POST['comments'])) 			$this->params['comments'] 		 	= prepare_input($_POST['comments']);
		if(isset($_POST['registration_code'])) 	$this->params['registration_code'] 	= prepare_input($_POST['registration_code']);
		$rid = MicroGrid::GetParameter('rid');

		$this->email_notifications = '';
		$this->user_password = '';

		$this->allow_adding_by_admin = ModulesSettings::Get('patients', 'allow_adding_by_admin');		
		$this->allow_changing_password = ModulesSettings::Get('patients', 'password_changing_by_admin');
		$this->reg_confirmation = ModulesSettings::Get('patients', 'reg_confirmation');
		
		$allow_adding = ($this->allow_adding_by_admin == 'yes') ? true : false;
		
		$this->primaryKey 	= 'id';
		$this->tableName 	= TABLE_PATIENTS;
		$this->dataSet 		= array();
		$this->error 		= '';
		///$this->languageId  	= (isset($_REQUEST['language_id']) && $_REQUEST['language_id'] != '') ? $_REQUEST['language_id'] : Languages::GetDefaultLang();
		$this->formActionURL = 'index.php?admin=mod_patients_management';
		$this->actions      = array('add'=>$allow_adding, 'edit'=>true, 'details'=>true, 'delete'=>true);
		$this->actionIcons  = true;
		$this->allowRefresh = true;
		$this->allowTopButtons = true;

		$this->allowLanguages = false;
		$this->WHERE_CLAUSE = '';		
		$this->ORDER_CLAUSE = 'ORDER BY id DESC';

		$this->isAlterColorsAllowed = true;

		$this->isPagingAllowed = true;
		$this->pageSize = 20;

		$this->isSortingAllowed = true;

		$this->isExportingAllowed = false;
		$this->arrExportingTypes = array('csv'=>false);

		$arr_genders = array('f'=>_FEMALE, 'm'=>_MALE);
		
		$total_countries = Countries::GetAllCountries('priority_order DESC, name ASC');
		$arr_countries = array();
		foreach($total_countries[0] as $key => $val){
			$arr_countries[$val['abbrv']] = $val['name'];
		}

		$total_groups = PatientGroups::GetAllGroups();
		$arr_groups = array();
		foreach($total_groups[0] as $key => $val){
			$arr_groups[$val['id']] = $val['name'];
		}

		// prepare languages array		
		$total_languages = Languages::GetAllActive();
		$arr_languages = array();
		foreach($total_languages[0] as $key => $val){
			$arr_languages[$val['abbreviation']] = $val['lang_name'];
		}

		$this->isFilteringAllowed = true;
		// define filtering fields
		$this->arrFilteringFields = array(
			_FIRST_NAME => array('table'=>'p', 'field'=>'first_name',  'type'=>'text', 'sign'=>'like%', 'width'=>'80px', 'cryptography'=>PATIENTS_ENCRYPTION, 'cryptography_type'=>'AES', 'aes_password'=>PASSWORDS_ENCRYPT_KEY),
			_LAST_NAME  => array('table'=>'p', 'field'=>'last_name',  'type'=>'text', 'sign'=>'like%', 'width'=>'80px', 'cryptography'=>PATIENTS_ENCRYPTION, 'cryptography_type'=>'AES', 'aes_password'=>PASSWORDS_ENCRYPT_KEY),
			_EMAIL      => array('table'=>'p', 'field'=>'email',  'type'=>'text', 'sign'=>'like%', 'width'=>'90px'),
			_ACTIVE     => array('table'=>'p', 'field'=>'is_active', 'type'=>'dropdownlist', 'source'=>array('0'=>_NO, '1'=>_YES), 'sign'=>'=', 'width'=>'85px'),
			_GROUP      => array('table'=>'p', 'field'=>'group_id', 'type'=>'dropdownlist', 'source'=>$arr_groups, 'sign'=>'=', 'width'=>'85px'),
		);

		$patient_ip = get_current_ip();
		$datetime_format = get_datetime_format();		
		$date_format_view = get_date_format('view');
		$date_format_edit = get_date_format('edit');
		$arr_activity = array('0'=>'<span class=no>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');
		$arr_removed = array('0'=>'<span class=no>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');
		$arr_email_notifications = array('0'=>'<span class=no>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');
		
		if($objSettings->GetParameter('date_format') == 'mm/dd/yyyy'){
			$this->sqlFieldDatetimeFormat = '%b %d, %Y %H:%i';
			$this->sqlFieldDateFormat = '%b %d, %Y';
		}else{
			$this->sqlFieldDatetimeFormat = '%d %b, %Y %H:%i';
			$this->sqlFieldDateFormat = '%d %b, %Y';
		}
		$this->SetLocale(Application::Get('lc_time_name'));

		//---------------------------------------------------------------------- 
		// VIEW MODE
		//---------------------------------------------------------------------- 
		$this->VIEW_MODE_SQL = 'SELECT
									p.'.$this->primaryKey.',
		                            p.*,
									'.(PATIENTS_ENCRYPTION ? 'CONCAT(AES_DECRYPT(p.first_name, "'.PASSWORDS_ENCRYPT_KEY.'"), " ", AES_DECRYPT(p.last_name, "'.PASSWORDS_ENCRYPT_KEY.'"))' : 'CONCAT(p.first_name, " ", p.last_name)').' as full_name,
									c.name as country_name,
									pg.name as group_name
								FROM '.$this->tableName.' p
									LEFT OUTER JOIN '.TABLE_COUNTRIES.' c ON p.b_country = c.abbrv AND c.is_active = 1
									LEFT OUTER JOIN '.TABLE_PATIENT_GROUPS.' pg ON p.group_id = pg.id ';		
		// define view mode fields
		$this->arrViewModeFields = array(
			'full_name'    => array('title'=>_NAME, 'type'=>'label', 'align'=>'left', 'width'=>'', 'maxlength'=>'25'),
			'user_name'    => array('title'=>_USERNAME, 'type'=>'label', 'align'=>'left', 'width'=>'', 'maxlength'=>'18'),
			'email' 	   => array('title'=>_EMAIL_ADDRESS, 'type'=>'link', 'href'=>'mailto:{email}', 'align'=>'left', 'width'=>'', 'maxlength'=>'40'),
			'country_name' => array('title'=>_COUNTRY, 'type'=>'label', 'align'=>'left', 'width'=>''),
			'is_active'    => array('title'=>_ACTIVE, 'type'=>'enum',  'align'=>'center', 'width'=>'90px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'source'=>$arr_activity),			
			'group_name'   => array('title'=>_GROUP, 'type'=>'label', 'align'=>'left', 'width'=>'90px'),
			'id'           => array('title'=>'ID', 'type'=>'label', 'align'=>'center', 'width'=>'50px'),
		);
		
		//---------------------------------------------------------------------- 
		// ADD MODE
		//---------------------------------------------------------------------- 
		// define add mode fields
		$this->arrAddModeFields = array(
		    'separator_1'   =>array(
				'separator_info' => array('legend'=>_PERSONAL_DETAILS),
				'first_name'  	=> array('title'=>_FIRST_NAME, 'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'32', 'required'=>true, 'validation_type'=>'text', 'cryptography'=>PATIENTS_ENCRYPTION, 'cryptography_type'=>'AES', 'aes_password'=>PASSWORDS_ENCRYPT_KEY),
				'last_name'    	=> array('title'=>_LAST_NAME,  'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'32', 'required'=>true, 'validation_type'=>'text', 'cryptography'=>PATIENTS_ENCRYPTION, 'cryptography_type'=>'AES', 'aes_password'=>PASSWORDS_ENCRYPT_KEY),
				'birth_date'    => array('title'=>_BIRTH_DATE, 'type'=>'date',    'width'=>'210px', 'required'=>false, 'readonly'=>false, 'default'=>'', 'validation_type'=>'date', 'unique'=>false, 'visible'=>true, 'min_year'=>'90', 'max_year'=>'0', 'format'=>'date', 'format_parameter'=>$date_format_edit),
				'gender'        => array('title'=>_GENDER, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'m', 'source'=>$arr_genders, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
				'url' 			=> array('title'=>_URL,		   'type'=>'textbox', 'width'=>'270px', 'maxlength'=>'255', 'required'=>false, 'validation_type'=>'text'),
			),
		    'separator_2'   =>array(
				'separator_info' => array('legend'=>_BILLING_ADDRESS),
				'company' 		=> array('title'=>_COMPANY,	   'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'128', 'required'=>false, 'validation_type'=>'text'),
				'b_address' 	=> array('title'=>_ADDRESS,	  'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'64', 'required'=>true, 'validation_type'=>'text'),
				'b_address_2' 	=> array('title'=>_ADDRESS_2, 'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'64', 'required'=>false, 'validation_type'=>'text'),
				'b_city' 		=> array('title'=>_CITY,	  'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'64', 'required'=>true, 'validation_type'=>'text'),
				'b_zipcode' 	=> array('title'=>_ZIP_CODE,  'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'32', 'required'=>false, 'validation_type'=>'text'),
				'b_country' 	=> array('title'=>_COUNTRY,	  'type'=>'enum', 'width'=>'210px', 'source'=>$arr_countries, 'required'=>true, 'javascript_event'=>'onchange="appChangeCountry(this.value,\'b_state\',\'\',\''.Application::Get('token').'\')"'),
				'b_state' 		=> array('title'=>_STATE_PROVINCE, 'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'64', 'required'=>false, 'validation_type'=>'text'),
			),
		    'separator_3'   =>array(
				'separator_info' => array('legend'=>_CONTACT_INFORMATION),
				'phone' 		=> array('title'=>_PHONE,	      'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'32', 'required'=>false, 'validation_type'=>'text', 'cryptography'=>PATIENTS_ENCRYPTION, 'cryptography_type'=>'AES', 'aes_password'=>PASSWORDS_ENCRYPT_KEY),
				'fax' 		    => array('title'=>_FAX,	          'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'32', 'required'=>false, 'validation_type'=>'text'),
				'email' 		=> array('title'=>_EMAIL_ADDRESS, 'type'=>'textbox', 'width'=>'230px', 'maxlength'=>'70', 'required'=>false, 'validation_type'=>'email', 'unique'=>true, 'autocomplete'=>'off'),
			),
		    'separator_4'   =>array(
				'separator_info' => array('legend'=>_ACCOUNT_DETAILS),
				'user_name' 	 => array('title'=>_USERNAME,   'type'=>'textbox',  'width'=>'210px', 'maxlength'=>'32', 'required'=>true, 'validation_type'=>'text', 'validation_minlength'=>'4', 'readonly'=>false, 'unique'=>true, 'username_generator'=>true),
				'user_password'  => array('title'=>_PASSWORD,   'type'=>'password', 'width'=>'210px', 'maxlength'=>'20', 'required'=>true, 'validation_type'=>'password', 'cryptography'=>PASSWORDS_ENCRYPTION, 'cryptography_type'=>PASSWORDS_ENCRYPTION_TYPE, 'aes_password'=>PASSWORDS_ENCRYPT_KEY, 'password_generator'=>true),
				'group_id'       => array('title'=>_PATIENT_GROUP, 'type'=>'enum',     'width'=>'', 'required'=>false, 'readonly'=>false, 'source'=>$arr_groups),
				'preferred_language' => array('title'=>_PREFERRED_LANGUAGE, 'type'=>'enum', 'required'=>true, 'readonly'=>false, 'width'=>'120px', 'default'=>Application::Get('lang'), 'source'=>$arr_languages),
			),
		    'separator_5'   =>array(
				'separator_info' => array('legend'=>_OTHER),
				'date_created'		   => array('title'=>_DATE_CREATED,	'type'=>'hidden', 'width'=>'210px', 'required'=>true, 'default'=>date('Y-m-d H:i:s')),
				'registered_from_ip'   => array('title'=>_REGISTERED_FROM_IP, 'type'=>'hidden', 'width'=>'210px', 'required'=>true, 'default'=>$patient_ip),
				'last_logged_ip'	   => array('title'=>_LAST_LOGGED_IP,	  'type'=>'hidden', 'width'=>'210px', 'required'=>false, 'default'=>''),
				'email_notifications'  => array('title'=>_EMAIL_NOTIFICATION,	'type'=>'checkbox', 'true_value'=>'1', 'false_value'=>'0'),
				'is_active'			=> array('title'=>_ACTIVE,		  'type'=>'checkbox', 'readonly'=>false, 'default'=>'1', 'true_value'=>'1', 'false_value'=>'0', 'unique'=>false),
				'is_removed'		=> array('title'=>_REMOVED,		  'type'=>'hidden', 'width'=>'210px', 'required'=>true, 'default'=>'0'),
				'comments'			=> array('title'=>_COMMENTS,	  'type'=>'textarea', 'width'=>'420px', 'height'=>'70px', 'required'=>false, 'readonly'=>false, 'validation_type'=>'text', 'validation_maxlength'=>'2048'),
				'registration_code'	=> array('title'=>_REGISTRATION_CODE, 'type'=>'hidden', 'width'=>'210px', 'required'=>false, 'default'=>''),
			),
		);

		//---------------------------------------------------------------------- 
		// EDIT MODE
		// * password field must be written directly in SQL!!!
		//---------------------------------------------------------------------- 
		$this->EDIT_MODE_SQL = 'SELECT
									'.$this->tableName.'.'.$this->primaryKey.',
		                            '.$this->tableName.'.*,
									'.$this->tableName.'.first_name,
									'.$this->tableName.'.last_name,
									'.$this->tableName.'.phone,
									'.$this->tableName.'.user_password,
                                    IF('.$this->tableName.'.registered_from_ip = "", "<span class=gray>- unknown -</span>", registered_from_ip) as registered_from_ip,
                                    IF('.$this->tableName.'.last_logged_ip = "", "<span class=gray>- unknown -</span>", last_logged_ip) as last_logged_ip,
									IF('.$this->tableName.'.date_created IS NULL, "<span class=gray>- unknown -</span>", DATE_FORMAT('.$this->tableName.'.date_created, \''.$this->sqlFieldDatetimeFormat.'\')) as date_created,
									IF('.$this->tableName.'.date_lastlogin IS NULL, "<span class=gray>- never -</span>", DATE_FORMAT('.$this->tableName.'.date_lastlogin, \''.$this->sqlFieldDatetimeFormat.'\')) as date_lastlogin,
									IF('.$this->tableName.'.notification_status_changed IS NULL, "<span class=gray>- never -</span>", DATE_FORMAT('.$this->tableName.'.notification_status_changed, \''.$this->sqlFieldDatetimeFormat.'\')) as notification_status_changed
								FROM '.$this->tableName.'
								WHERE '.$this->tableName.'.'.$this->primaryKey.' = _RID_';
								
		// define edit mode fields
		$this->arrEditModeFields = array(
		    'separator_1'   =>array(
				'separator_info' => array('legend'=>_PERSONAL_DETAILS),
				'first_name'  	=> array('title'=>_FIRST_NAME, 'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'32', 'required'=>true, 'validation_type'=>'text', 'cryptography'=>PATIENTS_ENCRYPTION, 'cryptography_type'=>'AES', 'aes_password'=>PASSWORDS_ENCRYPT_KEY),
				'last_name'    	=> array('title'=>_LAST_NAME,  'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'32', 'required'=>true, 'validation_type'=>'text', 'cryptography'=>PATIENTS_ENCRYPTION, 'cryptography_type'=>'AES', 'aes_password'=>PASSWORDS_ENCRYPT_KEY),
				'birth_date'    => array('title'=>_BIRTH_DATE, 'type'=>'date',    'width'=>'210px', 'required'=>false, 'readonly'=>false, 'default'=>'', 'validation_type'=>'date', 'unique'=>false, 'visible'=>true, 'min_year'=>'90', 'max_year'=>'0', 'format'=>'date', 'format_parameter'=>$date_format_edit),
				'gender'        => array('title'=>_GENDER,     'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'m', 'source'=>$arr_genders, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
				'url' 			=> array('title'=>_URL,		   'type'=>'textbox', 'width'=>'270px', 'maxlength'=>'255', 'required'=>false, 'validation_type'=>'text'),
			),
		    'separator_2'   =>array(
				'separator_info' => array('legend'=>_BILLING_ADDRESS),
				'company' 		=> array('title'=>_COMPANY,	'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'128', 'required'=>false, 'validation_type'=>'text'),
				'b_address' 	=> array('title'=>_ADDRESS,	   'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'64', 'required'=>true, 'validation_type'=>'text'),
				'b_address_2' 	=> array('title'=>_ADDRESS_2,  'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'64', 'required'=>false, 'validation_type'=>'text'),
				'b_city' 		=> array('title'=>_CITY,	   'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'64', 'required'=>true, 'validation_type'=>'text'),
				'b_zipcode' 	=> array('title'=>_ZIP_CODE,   'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'32', 'required'=>false, 'validation_type'=>'text'),
				'b_country' 	=> array('title'=>_COUNTRY,	   'type'=>'enum', 'width'=>'210px', 'source'=>$arr_countries, 'required'=>true, 'javascript_event'=>'onchange="appChangeCountry(this.value,\'b_state\',\'\',\''.Application::Get('token').'\')"'),
				'b_state' 		=> array('title'=>_STATE_PROVINCE, 'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'64', 'required'=>false, 'validation_type'=>'text'),
			),
		    'separator_3'   =>array(
				'separator_info' => array('legend'=>_CONTACT_INFORMATION),
				'phone' 		=> array('title'=>_PHONE,	      'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'32', 'required'=>false, 'validation_type'=>'text', 'cryptography'=>PATIENTS_ENCRYPTION, 'cryptography_type'=>'AES', 'aes_password'=>PASSWORDS_ENCRYPT_KEY),
				'fax' 		    => array('title'=>_FAX,	          'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'32', 'required'=>false, 'validation_type'=>'text'),
				'email' 		=> array('title'=>_EMAIL_ADDRESS, 'type'=>'textbox', 'width'=>'230px', 'maxlength'=>'70', 'required'=>true, 'readonly'=>false, 'validation_type'=>'email', 'unique'=>true, 'autocomplete'=>'off'),
			),
		    'separator_4'   =>array(
				'separator_info' => array('legend'=>_ACCOUNT_DETAILS),
				'user_name' 	 => array('title'=>_USERNAME, 'type'=>'label'),
				'user_password'  => array('title'=>_PASSWORD, 'type'=>'password', 'width'=>'210px', 'maxlength'=>'20', 'required'=>true, 'validation_type'=>'password', 'cryptography'=>PASSWORDS_ENCRYPTION, 'cryptography_type'=>PASSWORDS_ENCRYPTION_TYPE, 'aes_password'=>PASSWORDS_ENCRYPT_KEY, 'visible'=>(($this->allow_changing_password == 'yes') ? true : false)),
				'group_id'       => array('title'=>_PATIENT_GROUP, 'type'=>'enum', 'required'=>false, 'readonly'=>false, 'width'=>'', 'source'=>$arr_groups),
				'preferred_language' => array('title'=>_PREFERRED_LANGUAGE, 'type'=>'enum', 'required'=>true, 'readonly'=>false, 'width'=>'120px', 'source'=>$arr_languages),
			),
		    'separator_5'   =>array(
				'separator_info'  => array('legend'=>_OTHER),
				'date_created'	  => array('title'=>_DATE_CREATED, 'type'=>'label'),
				'registered_from_ip'   => array('title'=>_REGISTERED_FROM_IP, 'type'=>'label'),
				'date_lastlogin'  => array('title'=>_LAST_LOGIN, 'type'=>'label'),
				'last_logged_ip'	   => array('title'=>_LAST_LOGGED_IP,	 'type'=>'label'),
				'email_notifications'  => array('title'=>_EMAIL_NOTIFICATION,	'type'=>'checkbox', 'true_value'=>'1', 'false_value'=>'0'),
				'notification_status_changed' => array('title'=>_NOTIFICATION_STATUS_CHANGED, 'type'=>'label'),
				'is_active'			=> array('title'=>_ACTIVE,		  'type'=>'checkbox', 'true_value'=>'1', 'false_value'=>'0'),
				'is_removed'		=> array('title'=>_REMOVED,		  'type'=>'checkbox', 'true_value'=>'1', 'false_value'=>'0'),
				'comments'			=> array('title'=>_COMMENTS,	  'type'=>'textarea', 'width'=>'420px', 'height'=>'70px', 'required'=>false, 'readonly'=>false, 'validation_type'=>'text', 'validation_maxlength'=>'2048'),
				'registration_code'	=> array('title'=>_REGISTRATION_CODE, 'type'=>'hidden', 'width'=>'210px', 'required'=>false, 'default'=>''),
			),
		);

		//---------------------------------------------------------------------- 
		// DETAILS MODE
		//----------------------------------------------------------------------
		$this->DETAILS_MODE_SQL = 'SELECT
									'.$this->tableName.'.'.$this->primaryKey.',
		                            '.$this->tableName.'.*,
									'.$this->tableName.'.first_name,
									'.$this->tableName.'.last_name,
									'.$this->tableName.'.phone,									
                                    IF('.$this->tableName.'.registered_from_ip = "", "<span class=gray>- unknown -</span>", registered_from_ip) as registered_from_ip,
                                    IF('.$this->tableName.'.last_logged_ip = "", "<span class=gray>- unknown -</span>", last_logged_ip) as last_logged_ip,
									IF('.$this->tableName.'.date_created IS NULL, "<span class=gray>- unknown -</span>", DATE_FORMAT('.$this->tableName.'.date_created, \''.$this->sqlFieldDatetimeFormat.'\')) as date_created,
									IF('.$this->tableName.'.date_lastlogin IS NULL, "<span class=gray>- never -</span>", DATE_FORMAT('.$this->tableName.'.date_lastlogin, \''.$this->sqlFieldDatetimeFormat.'\')) as date_lastlogin,
									DATE_FORMAT('.$this->tableName.'.birth_date, \''.$this->sqlFieldDateFormat.'\') as birth_date,
									IF('.$this->tableName.'.notification_status_changed IS NULL, "<span class=gray>- never -</span>", DATE_FORMAT('.$this->tableName.'.notification_status_changed, \''.$this->sqlFieldDatetimeFormat.'\')) as notification_status_changed,
									c.name as country_name,
									pg.name as group_name,
                                    IF(st.name IS NOT NULL, st.name, '.$this->tableName.'.b_state) as state_name
								FROM '.$this->tableName.' 
									LEFT OUTER JOIN '.TABLE_COUNTRIES.' c ON '.$this->tableName.'.b_country = c.abbrv AND c.is_active = 1
									LEFT OUTER JOIN '.TABLE_PATIENT_GROUPS.' pg ON '.$this->tableName.'.group_id = pg.id
                                    LEFT OUTER JOIN '.TABLE_STATES.' st ON '.$this->tableName.'.b_state = st.abbrv AND st.country_id = c.id AND st.is_active = 1
								WHERE '.$this->tableName.'.'.$this->primaryKey.' = _RID_';
		$this->arrDetailsModeFields = array(			
		    'separator_1'   =>array(
				'separator_info' => array('legend'=>_PERSONAL_DETAILS),
				'first_name'  	=> array('title'=>_FIRST_NAME, 'type'=>'label', 'cryptography'=>PATIENTS_ENCRYPTION, 'cryptography_type'=>'AES', 'aes_password'=>PASSWORDS_ENCRYPT_KEY),
				'last_name'    	=> array('title'=>_LAST_NAME,  'type'=>'label', 'cryptography'=>PATIENTS_ENCRYPTION, 'cryptography_type'=>'AES', 'aes_password'=>PASSWORDS_ENCRYPT_KEY),
				'birth_date'    => array('title'=>_BIRTH_DATE,  'type'=>'label'),
				'gender'        => array('title'=>_GENDER, 'type'=>'enum', 'source'=>$arr_genders),
				'url' 			=> array('title'=>_URL,		 'type'=>'label'),
			),
		    'separator_2'   =>array(
				'separator_info' => array('legend'=>_BILLING_ADDRESS),
				'company' 		=> array('title'=>_COMPANY,	 'type'=>'label'),
				'b_address' 	=> array('title'=>_ADDRESS,	 'type'=>'label'),
				'b_address_2' 	=> array('title'=>_ADDRESS_2, 'type'=>'label'),
				'b_city' 		=> array('title'=>_CITY,	 'type'=>'label'),
				'b_zipcode' 	=> array('title'=>_ZIP_CODE, 'type'=>'label'),
				'country_name' 	=> array('title'=>_COUNTRY,	 'type'=>'label'),
				'state_name'    => array('title'=>_STATE,	 'type'=>'label'),
			),
		    'separator_3'   =>array(
				'separator_info' => array('legend'=>_CONTACT_INFORMATION),
				'phone' 		=> array('title'=>_PHONE, 'type'=>'label', 'cryptography'=>PATIENTS_ENCRYPTION, 'cryptography_type'=>'AES', 'aes_password'=>PASSWORDS_ENCRYPT_KEY),
				'fax' 		    => array('title'=>_FAX,	'type'=>'label'),
				'email' 		=> array('title'=>_EMAIL_ADDRESS, 'type'=>'label'),
			),
		    'separator_4'   =>array(
				'separator_info' => array('legend'=>_ACCOUNT_DETAILS),
				'user_name' 	=> array('title'=>_USERNAME, 'type'=>'label'),
				'group_name'    => array('title'=>_PATIENT_GROUP, 'type'=>'label'),
				'preferred_language' => array('title'=>_PREFERRED_LANGUAGE, 'type'=>'enum', 'source'=>$arr_languages),				
			),
		    'separator_5'   =>array(
				'separator_info' => array('legend'=>_OTHER),
				'date_created'	 => array('title'=>_DATE_CREATED, 'type'=>'label'),
				'registered_from_ip'   => array('title'=>_REGISTERED_FROM_IP, 'type'=>'label'),
				'date_lastlogin' => array('title'=>_LAST_LOGIN,	 'type'=>'label'),
				'last_logged_ip'	   => array('title'=>_LAST_LOGGED_IP,	 'type'=>'label'),
				'email_notifications'  => array('title'=>_EMAIL_NOTIFICATION, 'type'=>'enum', 'source'=>$arr_email_notifications),
				'notification_status_changed' => array('title'=>_NOTIFICATION_STATUS_CHANGED, 'type'=>'label'),
				'is_active'            => array('title'=>_ACTIVE, 'type'=>'enum', 'source'=>$arr_activity),
				'is_removed'           => array('title'=>_REMOVED, 'type'=>'enum', 'source'=>$arr_removed),
				'comments'			   => array('title'=>_COMMENTS,     'type'=>'label'),
			),
		);

	}
	
	//==========================================================================
    // Class Destructor
	//==========================================================================
    function __destruct()
	{
		// echo 'this object has been destroyed';
    }


	/**
	 * Send forgotten password
	 *		@param $email
	 */
	public static function SendPassword($email)
	{		
		global $objSettings;
		
		// deny all operations in demo version
		if(strtolower(SITE_MODE) == 'demo'){
			self::$static_error = _OPERATION_BLOCKED;
			return false;
		}
				
		if(!empty($email)) {
			
			$first_name = (PATIENTS_ENCRYPTION ? 'AES_DECRYPT(first_name, "'.PASSWORDS_ENCRYPT_KEY.'") as first_name' : 'first_name');
			$last_name = (PATIENTS_ENCRYPTION ? 'AES_DECRYPT(last_name, "'.PASSWORDS_ENCRYPT_KEY.'") as last_name' : 'last_name');
		
			if(check_email_address($email)){   
				if(!PASSWORDS_ENCRYPTION){
					$sql = 'SELECT id, '.$first_name.', '.$last_name.', user_name, user_password, preferred_language FROM '.TABLE_PATIENTS.' WHERE email=\''.$email.'\' AND is_active = 1';
				}else{
					if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'aes'){
						$sql = 'SELECT id, '.$first_name.', '.$last_name.', user_name, AES_DECRYPT(user_password, \''.PASSWORDS_ENCRYPT_KEY.'\') as user_password, preferred_language FROM '.TABLE_PATIENTS.' WHERE email=\''.$email.'\' AND is_active = 1';
					}else if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'md5'){
						$sql = 'SELECT id, '.$first_name.', '.$last_name.', user_name, \'\' as user_password, preferred_language  FROM '.TABLE_PATIENTS.' WHERE email=\''.$email.'\' AND is_active = 1';
					}				
				}
				
				$temp = database_query($sql, DATA_ONLY, FIRST_ROW_ONLY);
				if(is_array($temp) && count($temp) > 0){
					$sender = $objSettings->GetParameter('admin_email');
					$recipiant = $email;
	
					if(!PASSWORDS_ENCRYPTION){
						$user_password = $temp['user_password'];
					}else{
						if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'aes'){
							$user_password = $temp['user_password'];
						}else if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'md5'){
							$user_password = get_random_string(8);
							$sql = 'UPDATE '.TABLE_PATIENTS.' SET user_password = \''.md5($user_password).'\' WHERE id = '.$temp['id'];
							database_void_query($sql);
						}				
					}
					////////////////////////////////////////////////////////////
					send_email(
						$recipiant,
						$sender,
						'password_forgotten',
						array(
							'{FIRST NAME}' => $temp['first_name'],
							'{LAST NAME}'  => $temp['last_name'],
							'{USER NAME}'  => $temp['user_name'],
							'{USER PASSWORD}' => $user_password,
							'{WEB SITE}'   => $_SERVER['SERVER_NAME'],
							'{BASE URL}'   => APPHP_BASE,
							'{YEAR}' 	   => date('Y')
						),
						$temp['preferred_language']
					);
					////////////////////////////////////////////////////////////
					return true;					
				}else{
					self::$static_error = _EMAIL_NOT_EXISTS;
					return false;
				}				
			}else{
				self::$static_error = _EMAIL_IS_WRONG;
				return false;								
			}
		}else{
			self::$static_error = _EMAIL_EMPTY_ALERT;
			return false;
		}
		return true;
	}	

	/**
	 * After Add Record
	 */
	public function AfterAddRecord()
	{ 
		echo '<script type="text/javascript">appChangeCountry(jQuery("#b_country").val(), "b_state", "'.self::GetParameter('b_state', false).'", "'.Application::Get('token').'");</script>';
	}

	/**
	 * After Edit Record
	 */
	public function AfterEditRecord()
	{
		$state_value = (self::GetParameter('b_state', false) != '') ? self::GetParameter('b_state', false) : $this->result[0][0]['b_state'];
		echo '<script type="text/javascript">appChangeCountry(jQuery("#b_country").val(), "b_state", "'.$state_value.'", "'.Application::Get('token').'");</script>';
	}

	/**
	 * After-Addition operation
	 */
	public function AfterInsertRecord()
	{
		global $objSettings, $objSiteDescription;

		////////////////////////////////////////////////////////////
		if(!empty($this->params['email'])){
			send_email(
				$this->params['email'],
				$objSettings->GetParameter('admin_email'),
				'new_account_created_by_admin',
				array(
					'{FIRST NAME}' => $this->params['first_name'],
					'{LAST NAME}'  => $this->params['last_name'],
					'{USER NAME}'  => $this->params['user_name'],
					'{USER PASSWORD}' => $this->params['user_password'],
					'{WEB SITE}'   => $_SERVER['SERVER_NAME'],
					'{BASE URL}'   => APPHP_BASE,
					'{YEAR}' 	   => date('Y'),
					'{ACCOUNT TYPE}' => 'patient'
				),
				$this->params['preferred_language']
			);
		}
		////////////////////////////////////////////////////////////
	}

	/**
	 * Before-Editing operation
	 */
	public function BeforeEditRecord()
	{
		$registration_code = isset($this->result[0][0]['registration_code']) ? $this->result[0][0]['registration_code'] : '';
		$is_active         = isset($this->result[0][0]['is_active']) ? $this->result[0][0]['is_active'] : '';
		$reactivation_html = '';
		
		if($registration_code != '' && !$is_active && $this->reg_confirmation == 'by email'){
				$reactivation_html = ' &nbsp;<a href="javascript:void(\'email|reactivate\')" onclick="javascript:if(confirm(\''._PERFORM_OPERATION_COMMON_ALERT.'\'))__mgDoPostBack(\''.TABLE_PATIENTS.'\',\'reactivate\');">[ '._REACTIVATION_EMAIL.' ]</a>';
		}
		$this->arrEditModeFields['separator_3']['email']['post_html'] = $reactivation_html;
		return true;
	}

	/**
	 * Before-Updating operation
	 */
	public function BeforeUpdateRecord()
	{
		$sql = 'SELECT email_notifications, user_password FROM '.$this->tableName.' WHERE '.$this->primaryKey.' = '.$this->curRecordId;
		$result = database_query($sql, DATA_ONLY, FIRST_ROW_ONLY);
        if(isset($result['email_notifications'])) $this->email_notifications = $result['email_notifications'];
		if(isset($result['user_password'])) $this->user_password = $result['user_password'];
		return true;
	}

	/**
	 * After-Updating operation
	 */
	public function AfterUpdateRecord()
	{
		global $objSettings;
		
		$registration_code = self::GetParameter('registration_code', false);
		$is_active         = self::GetParameter('is_active', false);
		$removed_update_clause = ((self::GetParameter('is_removed', false) == '1') ? ', is_active = 0' : '');
		$confirm_update_clause = '';
		
		$sql = 'SELECT user_name, user_password, preferred_language FROM '.$this->tableName.' WHERE '.$this->primaryKey.' = '.(int)$this->curRecordId;
		$result = database_query($sql, DATA_ONLY, FIRST_ROW_ONLY);
		$preferred_language = isset($result['preferred_language']) ? $result['preferred_language'] : '';
		$user_password = isset($result['user_password']) ? $result['user_password'] : '';

		if(!empty($registration_code) && $is_active && $this->reg_confirmation == 'by admin'){
			$confirm_update_clause = ', registration_code=\'\'';	
			////////////////////////////////////////////////////////////
			send_email(
				self::GetParameter('email', false),
				$objSettings->GetParameter('admin_email'),
				'registration_approved_by_admin',
				array(
					'{FIRST NAME}' => self::GetParameter('first_name', false),
					'{LAST NAME}'  => self::GetParameter('last_name', false),
					'{USER NAME}'  => self::GetParameter('user_name', false),
					'{WEB SITE}'   => $_SERVER['SERVER_NAME'],
					'{BASE URL}'   => APPHP_BASE,
					'{YEAR}' 	   => date('Y')
				),
				$preferred_language
			);
			////////////////////////////////////////////////////////////
		}		
		
		$sql = 'UPDATE '.$this->tableName.' 
				SET notification_status_changed = IF(email_notifications <> \''.$this->email_notifications.'\', \''.date('Y-m-d H:i:s').'\', notification_status_changed)
				    '.$removed_update_clause.'
					'.$confirm_update_clause.'
				WHERE '.$this->primaryKey.' = '.$this->curRecordId;		
		database_void_query($sql);

        // send email, if password was changed
		if($user_password != $this->user_password){
			////////////////////////////////////////////////////////////
			send_email(
				self::GetParameter('email', false),
				$objSettings->GetParameter('admin_email'),
				'password_changed_by_admin',
				array(
					'{FIRST NAME}'    => self::GetParameter('first_name', false),
					'{LAST NAME}'     => self::GetParameter('last_name', false),
					'{USER NAME}'     => $result['user_name'],
					'{USER PASSWORD}' => self::GetParameter('user_password', false),
					'{WEB SITE}'      => $_SERVER['SERVER_NAME']
				),
				$preferred_language
			);
			////////////////////////////////////////////////////////////			
		}

		return true;
	}

	/**
	 *	Returns DataSet array
	 *	    @param $where_clause
	 *		@param $params
	 */
	public function GetAllPatients($where_clause = '', $params = array())
	{
        $order_clause = isset($params['order_clause']) ? $params['order_clause'] : '';
        $limit_clause = isset($params['limit_clause']) ? $params['limit_clause'] : '';

		$first_name = (PATIENTS_ENCRYPTION ? 'AES_DECRYPT(first_name, "'.PASSWORDS_ENCRYPT_KEY.'") as first_name' : 'first_name');
		$last_name = (PATIENTS_ENCRYPTION ? 'AES_DECRYPT(last_name, "'.PASSWORDS_ENCRYPT_KEY.'") as last_name' : 'last_name');

		$first_name_in_where = (PATIENTS_ENCRYPTION ? 'AES_DECRYPT(first_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : 'first_name');
		$last_name_in_where = (PATIENTS_ENCRYPTION ? 'AES_DECRYPT(last_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : 'last_name');
        $where_clause = str_ireplace(array('first_name', 'last_name'), array($first_name_in_where, $last_name_in_where), $where_clause);

		$sql = 'SELECT *, '.$first_name.', '.$last_name.' FROM '.$this->tableName.' WHERE is_active = 1 '.$where_clause.' '.$order_clause.' '.$limit_clause;
        
		if($this->debug) $this->arrSQLs['select_get_all'] = $sql;					
		return database_query($sql, DATA_AND_ROWS, ALL_ROWS);
	}

	/**
	 * Send activation email
	 *		@param $email
	 */
	public static function Reactivate($email)
	{		
		global $objSettings;
		
		// deny all operations in demo version
		if(strtolower(SITE_MODE) == 'demo'){
			self::$static_error = _OPERATION_BLOCKED;
			return false;
		}
		
		if(!empty($email)) {
			if(check_email_address($email)){

				$first_name = (PATIENTS_ENCRYPTION ? 'AES_DECRYPT(first_name, "'.PASSWORDS_ENCRYPT_KEY.'") as first_name' : 'first_name');
				$last_name = (PATIENTS_ENCRYPTION ? 'AES_DECRYPT(last_name, "'.PASSWORDS_ENCRYPT_KEY.'") as last_name' : 'last_name');

				$sql = 'SELECT id, '.$first_name.', '.$last_name.', user_name, registration_code, preferred_language, is_active ';
				if(!PASSWORDS_ENCRYPTION){
					$sql .= ', user_password ';
				}else{
					if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'aes'){
						$sql .= ', AES_DECRYPT(user_password, \''.PASSWORDS_ENCRYPT_KEY.'\') as user_password ';
					}else if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'md5'){
						$sql .= ', \'\' as user_password ';
					}				
				}
				$sql .= 'FROM '.TABLE_PATIENTS.' WHERE email=\''.$email.'\'';
				$temp = database_query($sql, DATA_ONLY, FIRST_ROW_ONLY);
				if(is_array($temp) && count($temp) > 0){
					if($temp['registration_code'] != '' && $temp['is_active'] == '0'){
						////////////////////////////////////////////////////////		
						if(!PASSWORDS_ENCRYPTION){
							$user_password = $temp['user_password'];
						}else{
							if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'aes'){
								$user_password = $temp['user_password'];
							}else if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'md5'){
								$user_password = get_random_string(8);
								$sql = 'UPDATE '.TABLE_PATIENTS.' SET user_password = \''.md5($user_password).'\' WHERE id = '.$temp['id'];
								database_void_query($sql);
							}				
						}

						send_email(
							$email,
							$objSettings->GetParameter('admin_email'),
							'new_account_created_confirm_by_email',
							array(
								'{FIRST NAME}' => $temp['first_name'],
								'{LAST NAME}'  => $temp['last_name'],
								'{USER NAME}'  => $temp['user_name'],
								'{USER PASSWORD}' => $user_password,
								'{REGISTRATION CODE}' => $temp['registration_code'],
								'{WEB SITE}'   => $_SERVER['SERVER_NAME'],
								'{BASE URL}'   => APPHP_BASE,
								'{YEAR}' 	   => date('Y')
							),
							$temp['preferred_language']
						);
						////////////////////////////////////////////////////////
						return true;					
					}else{
						self::$static_error = _EMAILS_SENT_ERROR;
						return false;						
					}
				}else{
					self::$static_error = _EMAIL_NOT_EXISTS;
					return false;
				}				
			}else{
				self::$static_error = _EMAIL_IS_WRONG;
				return false;								
			}
		}else{
			self::$static_error = _EMAIL_EMPTY_ALERT;
			return false;
		}
		return true;
	}
	
	/**
	 * Draws login form on Front-End
	 * 		@param $draw
	 */
	public static function DrawLoginFormBlock($draw = true)
	{
		global $objLogin;		

		$username = '';
		$password = '';
		
		$output = draw_block_top(_AUTHENTICATION, '', 'maximized', false);
		$output .= '<form class="patient_login" action="index.php?patient=login" method="post">
			'.draw_hidden_field('submit_login', 'login', false).'
			'.draw_hidden_field('type', 'patient', false).'
			'.draw_token_field(false).'
			
			<table class="tblSideBlock" border="0" cellspacing="1" cellpadding="1">
			<tr><td>'._USERNAME.':</td></tr>
			<tr><td><input type="text" name="user_name" id="user_name" maxlength="32" autocomplete="off" value="'.$username.'" /></td></tr>
			<tr><td>'._PASSWORD.':</td></tr>
			<tr><td><input type="password" name="password" id="password" maxlength="20" autocomplete="off" value="'.$password.'" /></td></tr>
			<tr><td nowrap height="1px"></td></tr>
			<tr><td>';

		$output .= '<input class="form_button" type="submit" name="submit" value="'._BUTTON_LOGIN.'" />';
		if(ModulesSettings::Get('patients', 'remember_me_allow') == 'yes'){
			$output .= '<input type="checkbox" class="form_checkbox" name="remember_me" id="chk_remember_me" value="1" /> <label for="chk_remember_me">'._REMEMBER_ME.'</label><br>';
		}				

		$output .= '</td></tr>
			<tr><td></td></tr>';
			if(ModulesSettings::Get('patients', 'allow_registration') == 'yes') $output .= '<tr><td>'.prepare_permanent_link('index.php?patient=create_account', _CREATE_ACCOUNT, '', 'form_link').'</td></tr>';
			if(ModulesSettings::Get('patients', 'allow_reset_passwords') == 'yes') $output .= '<tr><td>'.prepare_permanent_link('index.php?patient=password_forgotten', _FORGOT_PASSWORD, '', 'form_link').'</td></tr>';
            $output .= '</table>
		</form>';
		$output .= draw_block_bottom(false);
		
		if($draw) echo $output;
		else return $output;				
	}	

	/**
	 * Returns info about patient
	 * 		@param $patient_id
	 */
	public static function GetPatientInfo($patient_id = 0)
	{
		$sql = 'SELECT *
				'.(PATIENTS_ENCRYPTION ? ', AES_DECRYPT(first_name, "'.PASSWORDS_ENCRYPT_KEY.'") as first_name' : '').'
				'.(PATIENTS_ENCRYPTION ? ', AES_DECRYPT(last_name, "'.PASSWORDS_ENCRYPT_KEY.'") as last_name' : '').'				
				FROM '.TABLE_PATIENTS.'
				WHERE id = '.(int)$patient_id;
		return database_query($sql, DATA_ONLY, FIRST_ROW_ONLY);		
	}

	/**
	 * Get number of patients awaiting aproval
	 */
	public static function AwaitingAprovalCount()
	{
		$sql = 'SELECT COUNT(*) as cnt FROM '.TABLE_PATIENTS.' WHERE is_active = 0 AND registration_code != \'\'';
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			return $result[0]['cnt'];
		}
		return '0';
	}


}
?>