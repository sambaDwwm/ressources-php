<?php

/**
 *	Class Doctors
 *  --------------
 *	Description : encapsulates doctors methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 26.01.2012
 *  Usage       : MedicalAppointment
 *
 *	PUBLIC:				  	STATIC:				 	PRIVATE:
 * 	------------------	  	---------------     	---------------
 *	__construct             GetAllActive
 *	__destruct              GetDoctorInfoBySchedule
 *	BeforeAddRecord         GetDoctorInfoById
 *	AfterAddRecord          DrawAppointmentsBlock
 *	AfterInsertRecord       SearchFor
 *	BeforeEditRecord        DrawSearchResult
 *	AfterEditRecord         GetMembershipInfo
 *	BeforeUpdateRecord      DrawDoctorInfo
 *	AfterUpdateRecord       GetDoctorSpecialities
 *	AfterDeleteRecord		GetDoctorSpeciality
 *	                        Reactivate
 *	                        SendPassword
 *	                        ChangePassword
 *	                        AwaitingAprovalCount
 *	                        DoctorHasSchedule
 *	                        DrawDoctorFindForm
 *	                        DrawDoctorsInfo
 *	                        SetMembershipInfoForDoctor
 *	                        UpdateMembershipInfo
 *	                        HasOpenOnlineOrder
 *	                        FindNextAppointment (private)
 *      
 **/


class Doctors extends MicroGrid {
	
	protected $debug = false;
	
	// #001 private $arrTranslations = '';
	private $email_notifications;
	private $sqlFieldDatetimeFormat = '';
	private $sqlFieldDateFormat = '';
	private $reg_confirmation;
	private $allow_changing_password = '';
	
	//==========================================================================
    // Class Constructor
	// 		@param $account_type
	//==========================================================================
	function __construct($account_type = '')
	{		
		parent::__construct();

		global $objSettings;

		$this->params = array();
		
		## for standard fields
		if(isset($_POST['first_name']))  $this->params['first_name'] = prepare_input($_POST['first_name']);
		if(isset($_POST['middle_name'])) $this->params['middle_name'] = prepare_input($_POST['middle_name']);
		if(isset($_POST['last_name']))   $this->params['last_name'] = prepare_input($_POST['last_name']);
		if(isset($_POST['gender']))      $this->params['gender'] = prepare_input($_POST['gender']);
		if(isset($_POST['birth_date']))  $this->params['birth_date'] = prepare_input($_POST['birth_date']);
		if(isset($_POST['title']))       $this->params['title'] = prepare_input($_POST['title']);

		if(isset($_POST['b_address']))   $this->params['b_address']   = prepare_input($_POST['b_address']);
		if(isset($_POST['b_address_2'])) $this->params['b_address_2'] = prepare_input($_POST['b_address_2']);
		if(isset($_POST['b_city']))   	 $this->params['b_city']      = prepare_input($_POST['b_city']);
		if(isset($_POST['b_state']))   	 $this->params['b_state']     = prepare_input($_POST['b_state']);
		if(isset($_POST['b_country']))	 $this->params['b_country']   = prepare_input($_POST['b_country']);
		if(isset($_POST['b_zipcode']))	 $this->params['b_zipcode']   = prepare_input($_POST['b_zipcode']);

		if(isset($_POST['email'])) 		       $this->params['email'] = prepare_input($_POST['email']);
		if(isset($_POST['work_phone']))        $this->params['work_phone'] = prepare_input($_POST['work_phone']);
		if(isset($_POST['work_mobile_phone'])) $this->params['work_mobile_phone'] = prepare_input($_POST['work_mobile_phone']);
		if(isset($_POST['fax'])) 		       $this->params['fax'] = prepare_input($_POST['fax']);
		if(isset($_POST['user_name']))         $this->params['user_name'] = prepare_input($_POST['user_name']);
		if(isset($_POST['user_password']))     $this->params['user_password'] = prepare_input($_POST['user_password']);
		if(isset($_POST['preferred_language'])) $this->params['preferred_language'] = prepare_input($_POST['preferred_language']);
		if(isset($_POST['registered_from_ip'])) $this->params['registered_from_ip'] = prepare_input($_POST['registered_from_ip']);
		if(isset($_POST['last_logged_ip'])) 	$this->params['last_logged_ip'] 	= prepare_input($_POST['last_logged_ip']);
		if(isset($_POST['email_notifications']))$this->params['email_notifications']= prepare_input($_POST['email_notifications']); else $this->params['email_notifications'] = '0';
		if(isset($_POST['notification_status_changed'])) $this->params['notification_status_changed'] = prepare_input($_POST['notification_status_changed']);
		if(isset($_POST['date_created']))  		$this->params['date_created'] = prepare_input($_POST['date_created']);
		if(isset($_POST['date_lastlogin']))  	$this->params['date_lastlogin'] = prepare_input($_POST['date_lastlogin']);
        
		if(isset($_POST['membership_plan_id'])) $this->params['membership_plan_id'] = prepare_input($_POST['membership_plan_id']);
		if(isset($_POST['membership_images_count'])) $this->params['membership_images_count'] = prepare_input($_POST['membership_images_count']);
		if(isset($_POST['membership_addresses_count'])) $this->params['membership_addresses_count'] = prepare_input($_POST['membership_addresses_count']);
		if(isset($_POST['membership_show_in_search'])) $this->params['membership_show_in_search'] = prepare_input($_POST['membership_show_in_search']);
		if(isset($_POST['membership_expires'])) $this->params['membership_expires'] = prepare_input($_POST['membership_expires']);
        		
		if(isset($_POST['medical_degree'])) $this->params['medical_degree'] = prepare_input($_POST['medical_degree']);
		if(isset($_POST['additional_degree'])) $this->params['additional_degree'] = prepare_input($_POST['additional_degree']);
        if(isset($_POST['license_number'])) $this->params['license_number'] = prepare_input($_POST['license_number']);
		if(isset($_POST['education']))   $this->params['education'] = prepare_input($_POST['education']);
		if(isset($_POST['experience_years']))   $this->params['experience_years'] = prepare_input($_POST['experience_years']);
		if(isset($_POST['residency_training']))   $this->params['residency_training'] = prepare_input($_POST['residency_training']);
		if(isset($_POST['hospital_affiliations']))   $this->params['hospital_affiliations'] = prepare_input($_POST['hospital_affiliations']);
		if(isset($_POST['board_certifications']))    $this->params['board_certifications'] = prepare_input($_POST['board_certifications']);
		if(isset($_POST['awards_and_publications'])) $this->params['awards_and_publications'] = prepare_input($_POST['awards_and_publications']);
		if(isset($_POST['languages_spoken'])) $this->params['languages_spoken'] = prepare_input($_POST['languages_spoken']);
		if(isset($_POST['insurances_accepted'])) $this->params['insurances_accepted'] = prepare_input($_POST['insurances_accepted']);
		if(isset($_POST['default_visit_price'])) $this->params['default_visit_price'] = prepare_input($_POST['default_visit_price']);
		if(isset($_POST['default_visit_duration'])) $this->params['default_visit_duration'] = prepare_input($_POST['default_visit_duration']);
		if(isset($_POST['comments'])) 			$this->params['comments'] = prepare_input($_POST['comments']);
		if(isset($_POST['registration_code'])) 	$this->params['registration_code'] = prepare_input($_POST['registration_code']);
		
		## for checkboxes 
		if($account_type == '') $this->params['is_active'] = isset($_POST['is_active']) ? prepare_input($_POST['is_active']) : '0';
		if($account_type == '') $this->params['is_removed'] = isset($_POST['is_removed']) ? prepare_input($_POST['is_removed']) : '0';
		
		$this->allow_changing_password = ModulesSettings::Get('doctors', 'password_changing_by_admin');
		$this->reg_confirmation = ModulesSettings::Get('doctors', 'reg_confirmation');
		$this->email_notifications = '';
        $watermark = (ModulesSettings::Get('doctors', 'watermark') == 'yes') ? true : false;
        $watermark_text = ModulesSettings::Get('doctors', 'watermark_text');

		## for images (not necessary)
		//if(isset($_POST['icon'])){
		//	$this->params['icon'] = prepare_input($_POST['icon']);
		//}else if(isset($_FILES['icon']['name']) && $_FILES['icon']['name'] != ''){
		//	// nothing 			
		//}else if (self::GetParameter('action') == 'create'){
		//	$this->params['icon'] = '';
		//}

		## for files:
		// define nothing

		$this->params['language_id'] = '';//MicroGrid::GetParameter('language_id');
	
		//$this->uPrefix 		= 'prefix_';
		
		$this->primaryKey 	= 'id';
		$this->tableName 	= TABLE_DOCTORS;
		$this->dataSet 		= array();
		$this->error 		= '';

		if($account_type == 'me'){
			$this->formActionURL = 'index.php?doctor=my_account';
			$add_mode = false;
			$gender_readonly = true;
			$is_active_visible = false;
			$is_removed_visible = false;
            $membership_option_readonly = true;
		}else{
			$this->formActionURL = 'index.php?admin=doctors_management';
			$add_mode = true;
			$gender_readonly = false;
			$is_active_visible = true;
			$is_removed_visible = true;
            $membership_option_readonly = false;
		}		
		
		$this->actions      = array('add'=>$add_mode, 'edit'=>true, 'details'=>true, 'delete'=>true);
		$this->actionIcons  = true;
		$this->allowRefresh = true;
		$this->allowTopButtons = true;
		$this->alertOnDelete = ''; // leave empty to use default alerts

		$this->allowLanguages = false;
		$this->languageId  	= ''; //($this->params['language_id'] != '') ? $this->params['language_id'] : Languages::GetDefaultLang();
		$this->WHERE_CLAUSE = ''; // WHERE .... / 'WHERE language_id = \''.$this->languageId.'\'';				
		$this->ORDER_CLAUSE = 'ORDER BY '.$this->tableName.'.date_created DESC';
        $this->GROUP_BY_CLAUSE = 'GROUP BY '.$this->tableName.'.id';
		
		$this->isAlterColorsAllowed = true;

		$this->isPagingAllowed = true;
		$this->pageSize = 20;

		$this->isSortingAllowed = true;

		$this->isExportingAllowed = true;
		$this->arrExportingTypes = array('csv'=>true);
		
		$arr_genders = array('f'=>_FEMALE, 'm'=>_MALE);
		$arr_activity = array('0'=>'<span class=no>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');
        $arr_show_in_search = array('0'=>_NO, '1'=>_YES);
		$arr_email_notifications = array('0'=>'<span class=no>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');
		$arr_removed = array('0'=>'<span class=no>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');
		$arr_titles	= array('Mr.'=>'Mr.', 'Ms.'=>'Ms.', 'Mrs.'=>'Mrs.', 'Miss'=>'Miss');
		$arr_degrees = array('BMBS'=>'BMBS', 'MBBS'=>'MBBS', 'BDS'=>'BDS', 'MBChB'=>'MBChB', 'MB BCh'=>'MB BCh', 'BMed'=>'BMed', 'MD'=>'MD', 'MDCM'=>'MDCM', 'Dr.MuD'=>'Dr.MuD', 'Dr.Med'=>'Dr.Med', 'Cand.med'=>'Cand.med', 'Med'=>'Med');
        $arr_experience = array('0' => '0 - '._NO);
        for($i=1; $i<=60; $i++){
            $arr_experience[$i] = $i;    
        }        
        // prepare specialities
        $arr_specialities = array();
		$specialities = Specialities::GetAllActive();
        for($i=0; $i<$specialities[1]; $i++){
            $arr_specialities[$specialities[0][$i]['id']] = $specialities[0][$i]['name'];
        }
        // prepare countries
		$total_countries = Countries::GetAllCountries('priority_order DESC, name ASC');
		$arr_countries = array();
		foreach($total_countries[0] as $key => $val){
			$arr_countries[$val['abbrv']] = $val['name'];
		}
        // prepare membership plans
        $arr_membership_plans = array();
        $membership_plans = MembershipPlans::GetAllActive();
        for($i=0; $i<$membership_plans[1]; $i++){
            $arr_membership_plans[$membership_plans[0][$i]['id']] = $membership_plans[0][$i]['plan_name'];
        }        
		$arr_images = array('0', '1', '2', '3', '4', '5');
		$arr_addresses = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10');

        $arr_slots = get_time_slots();
		$doctor_ip = get_current_ip();
		$datetime_format = get_datetime_format();		

		// define filtering fields
		$this->isFilteringAllowed = true;		
		$this->arrFilteringFields = array(
			_NAME   => array('table'=>TABLE_DOCTORS, 'field'=>'last_name', 'type'=>'text', 'sign'=>'like%', 'width'=>'110px', 'visible'=>true),
			///_SPECIALITY => array('table'=>TABLE_DOCTOR_SPECIALITIES, 'field'=>'speciality_id', 'type'=>'dropdownlist', 'source'=>$arr_specialities, 'sign'=>'=', 'width'=>'170px', 'visible'=>true),
			_DEGREE => array('table'=>TABLE_DOCTORS, 'field'=>'medical_degree', 'type'=>'dropdownlist', 'source'=>$arr_degrees, 'sign'=>'=', 'width'=>'100px', 'visible'=>true),
		);

		$date_format = get_date_format('view');
		$date_format_edit = get_date_format('edit');
		///$datetime_format = get_datetime_format();
		
        //zzz333 Session::Set('currency_code', '');
		$pre_currency_symbol = ((Application::Get('currency_symbol_place') == 'before') ? Application::Get('currency_symbol') : '');
		$post_currency_symbol = ((Application::Get('currency_symbol_place') == 'after') ? Application::Get('currency_symbol') : '');

		// prepare languages array		
		$total_languages = Languages::GetAllActive();
		$arr_languages = array();
		foreach($total_languages[0] as $key => $val){
			$arr_languages[$val['abbreviation']] = $val['lang_name'];
		}
		
		///////////////////////////////////////////////////////////////////////////////
		// #002. prepare translation fields array
		/// $this->arrTranslations = $this->PrepareTranslateFields(
		///	array('field1', 'field2')
		/// );
		///////////////////////////////////////////////////////////////////////////////			

		///////////////////////////////////////////////////////////////////////////////			
		// #003. prepare translations array for add/edit/detail modes
		/// $sql_translation_description = $this->PrepareTranslateSql(
		///	TABLE_XXX_DESCRIPTION,
		///	'gallery_album_id',
		///	array('field1', 'field2')
		/// );
		///////////////////////////////////////////////////////////////////////////////			

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
		// format: strip_tags
		// format: nl2br
		// format: 'format'=>'date', 'format_parameter'=>'M d, Y, g:i A'
		// format: 'format'=>'currency', 'format_parameter'=>'european|2' or 'format_parameter'=>'american|4'
		//---------------------------------------------------------------------- 
		$this->VIEW_MODE_SQL = 'SELECT '.$this->tableName.'.'.$this->primaryKey.',
									first_name,
									middle_name,
									last_name,
									CONCAT(first_name, " ", middle_name, " ", last_name, IF(medical_degree != "", CONCAT(" (", medical_degree, ")"), "")) as full_name,
									gender,
									birth_date,
									email,
									title,
									medical_degree,
									education,
									residency_training,
									hospital_affiliations,
									board_certifications,
									awards_and_publications,
									languages_spoken,
									insurances_accepted,
									IF(doctor_photo <> "", doctor_photo, IF(gender = "f", "doctor_f.png", "doctor_m.png")) as doctor_photo,
									doctor_photo_thumb,
									work_phone,
									work_mobile_phone,
									CONCAT(work_phone, "<br>", work_mobile_phone) as mod_phones,
									default_visit_price,
									default_visit_duration,
									is_active,
									"[ '._SCHEDULES.' ]" as link_schedules,
									"[ '._TIMEOFF.' ]" as link_timeoffs,
									"[ '._ADDRESSES.' ]" as link_addresses,
									CONCAT("[ '._SPECIALITIES.' ]", " (", (SELECT COUNT(*) FROM '.TABLE_DOCTOR_SPECIALITIES.' ds WHERE ds.doctor_id = '.$this->tableName.'.id), ")") as link_specialities,
									CONCAT("[ '._IMAGES.' ]", " (", (SELECT COUNT(*) as cnt FROM '.TABLE_DOCTOR_IMAGES.' di WHERE di.doctor_id = '.$this->tableName.'.'.$this->primaryKey.') , ")") as link_upload_images
								FROM '.$this->tableName.'';
                                ///INNER JOIN '.TABLE_DOCTOR_SPECIALITIES.' ON '.$this->tableName.'.id = '.TABLE_DOCTOR_SPECIALITIES.'.doctor_id 
                                    
		// define view mode fields
		$this->arrViewModeFields = array(
			'doctor_photo'       => array('title'=>_PHOTO, 'type'=>'image', 'align'=>'center', 'width'=>'44px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'image_width'=>'38px', 'image_height'=>'32px', 'target'=>'images/doctors/', 'no_image'=>'no_image.png'),
			'full_name'          => array('title'=>_NAME, 'type'=>'label', 'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>''),			
			'mod_phones'         => array('title'=>_PHONES, 'type'=>'label', 'align'=>'center', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>''),			
			//'medical_degree'     => array('title'=>_DEGREE, 'type'=>'enum',  'align'=>'center', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'source'=>$arr_degrees),
			'is_active'          => array('title'=>_ACTIVE, 'type'=>'enum',  'align'=>'center', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'source'=>$arr_activity),
			'link_specialities'  => array('title'=>'', 'type'=>'link',  'align'=>'center', 'width'=>'110px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>'', 'href'=>'index.php?admin=doctors_specialities&doc_id={id}', 'target'=>''),
			'link_schedules'     => array('title'=>'', 'type'=>'link',  'align'=>'center', 'width'=>'90px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>'', 'href'=>'index.php?admin=schedules_management&doc_id={id}', 'target'=>''),
			'link_timeoffs'      => array('title'=>'', 'type'=>'link',  'align'=>'center', 'width'=>'75px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>'', 'href'=>'index.php?admin=timeoff_management&doc_id={id}', 'target'=>''),
			'link_addresses'     => array('title'=>'', 'type'=>'link',  'align'=>'center', 'width'=>'80px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>'', 'href'=>'index.php?admin=doctors_addresses&doc_id={id}', 'target'=>''),
			'link_upload_images' => array('title'=>'', 'type'=>'link',  'align'=>'center', 'width'=>'80px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>'', 'href'=>'index.php?admin=doctors_upload_images&docid={id}', 'target'=>''),
			'id'                 => array('title'=>'ID', 'type'=>'label', 'align'=>'center', 'width'=>'40px'),
		);
		
		//---------------------------------------------------------------------- 
		// ADD MODE
		// - Validation Type: alpha|numeric|float|alpha_numeric|text|email|ip_address|password|date
		// 	 Validation Sub-Type: positive (for numeric and float)
		//   Ex.: 'validation_type'=>'numeric', 'validation_type'=>'numeric|positive'
		// - Validation Max Length: 12, 255... Ex.: 'validation_maxlength'=>'255'
		// - Validation Min Length: 4, 6... Ex.: 'validation_minlength'=>'4'
		// - Validation Max Value: 12, 255... Ex.: 'validation_maximum'=>'99.99'
		//---------------------------------------------------------------------- 
		// define add mode fields
		$this->arrAddModeFields = array(		    
			'separator_personal_info' => array(
				'separator_info' => array('legend'=>_PERSONAL_INFORMATION),
				'first_name'   => array('title'=>_FIRST_NAME, 'type'=>'textbox',  'width'=>'210px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'70', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true),
				'middle_name'  => array('title'=>_MIDDLE_NAME, 'type'=>'textbox',  'width'=>'110px', 'required'=>false, 'readonly'=>false, 'maxlength'=>'70', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true),
				'last_name'    => array('title'=>_LAST_NAME, 'type'=>'textbox',  'width'=>'210px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'70', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true),
				'gender'       => array('title'=>_GENDER, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'m', 'source'=>$arr_genders, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
				'birth_date'   => array('title'=>_BIRTH_DATE, 'type'=>'date', 'required'=>true, 'readonly'=>false, 'unique'=>false, 'visible'=>true, 'default'=>'', 'validation_type'=>'date', 'format'=>'date', 'format_parameter'=>$date_format_edit, 'min_year'=>'90', 'max_year'=>'1'),
				'title'        => array('title'=>_TITLE, 'type'=>'enum', 'width'=>'', 'required'=>false, 'readonly'=>false, 'default'=>'', 'source'=>$arr_titles, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
			),
			'separator_profile_info' => array(
				'separator_info' => array('legend'=>_PROFILE_DETAILS),
				'doctor_photo' => array('title'=>_PHOTO, 'type'=>'image', 'width'=>'210px', 'required'=>false, 'readonly'=>false, 'target'=>'images/doctors/', 'no_image'=>'no_image.png', 'random_name'=>true, 'overwrite_image'=>false, 'unique'=>false, 'thumbnail_create'=>true, 'thumbnail_field'=>'doctor_photo_thumb', 'thumbnail_width'=>'90px', 'thumbnail_height'=>'90px', 'file_maxsize'=>'500k', 'watermark'=>$watermark, 'watermark_text'=>$watermark_text),
				'work_phone'   => array('title'=>_WORK_PHONE, 'type'=>'textbox', 'width'=>'210px', 'required'=>false, 'readonly'=>false, 'maxlength'=>'32', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true),
				'work_mobile_phone' => array('title'=>_MOBILE_WORK_PHONE, 'type'=>'textbox',  'width'=>'210px', 'required'=>false, 'readonly'=>false, 'maxlength'=>'32', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true),
				'default_visit_price' => array('title'=>_DEFAULT_PRICE_PER_VISIT, 'type'=>'textbox',  'width'=>'85px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'10', 'default'=>'0', 'validation_type'=>'float|positive', 'unique'=>false, 'visible'=>true, 'pre_html'=>$pre_currency_symbol.' ', 'post_html'=>$post_currency_symbol),
				'default_visit_duration' => array('title'=>_DEFAULT_VISIT_DURATION, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'15', 'source'=>$arr_slots, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),				
				'comments'			 => array('title'=>_COMMENTS, 'type'=>'textarea', 'width'=>'420px', 'height'=>'70px', 'required'=>false, 'readonly'=>false, 'validation_type'=>'text', 'validation_maxlength'=>'2048'),
			),		
		    'separator_account_info'   =>array(
				'separator_info' => array('legend'=>_ACCOUNT_DETAILS),
				'user_name' 	     => array('title'=>_USERNAME,   'type'=>'textbox',  'width'=>'210px', 'maxlength'=>'32', 'required'=>true, 'validation_type'=>'text', 'validation_minlength'=>'4', 'readonly'=>false, 'unique'=>true, 'username_generator'=>true),
				'user_password'      => array('title'=>_PASSWORD,   'type'=>'password', 'width'=>'210px', 'maxlength'=>'20', 'required'=>true, 'validation_type'=>'password', 'cryptography'=>PASSWORDS_ENCRYPTION, 'cryptography_type'=>PASSWORDS_ENCRYPTION_TYPE, 'aes_password'=>PASSWORDS_ENCRYPT_KEY, 'password_generator'=>true),
				'preferred_language' => array('title'=>_PREFERRED_LANGUAGE, 'type'=>'enum', 'required'=>true, 'readonly'=>false, 'width'=>'120px', 'default'=>Application::Get('lang'), 'source'=>$arr_languages),
				'date_created'		 => array('title'=>_DATE_CREATED,	'type'=>'hidden', 'width'=>'210px', 'required'=>true, 'default'=>date('Y-m-d H:i:s')),
				'registered_from_ip' => array('title'=>_REGISTERED_FROM_IP, 'type'=>'hidden', 'width'=>'210px', 'required'=>true, 'default'=>$doctor_ip),
				'last_logged_ip'	 => array('title'=>_LAST_LOGGED_IP,	  'type'=>'hidden', 'width'=>'210px', 'required'=>false, 'default'=>''),
				'email_notifications'=> array('title'=>_EMAIL_NOTIFICATION,	'type'=>'checkbox', 'true_value'=>'1', 'false_value'=>'0'),
				'is_active'    => array('title'=>_ACTIVE, 'type'=>'checkbox', 'readonly'=>false, 'default'=>'1', 'true_value'=>'1', 'false_value'=>'0', 'unique'=>false),
				'is_removed'   => array('title'=>_REMOVED,	'type'=>'hidden', 'width'=>'210px', 'required'=>true, 'default'=>'0'),
				'registration_code'	 => array('title'=>_REGISTRATION_CODE, 'type'=>'hidden', 'width'=>'210px', 'required'=>false, 'default'=>''),
			),
		    'separator_billing_address'   =>array(
				'separator_info' => array('legend'=>_BILLING_ADDRESS),
				'b_address' 	=> array('title'=>_ADDRESS,	 'type'=>'textbox', 'width'=>'210px', 'required'=>true, 'maxlength'=>'64', 'validation_type'=>'text'),
				'b_address_2' 	=> array('title'=>_ADDRESS_2,'type'=>'textbox', 'width'=>'210px', 'required'=>false, 'maxlength'=>'64', 'validation_type'=>'text'),
				'b_city' 		=> array('title'=>_CITY,	 'type'=>'textbox', 'width'=>'210px', 'required'=>true, 'maxlength'=>'64', 'validation_type'=>'text'),
				'b_zipcode' 	=> array('title'=>_ZIP_CODE, 'type'=>'textbox', 'width'=>'210px', 'required'=>true, 'maxlength'=>'32', 'validation_type'=>'text'),
				'b_country' 	=> array('title'=>_COUNTRY,	 'type'=>'enum',     'width'=>'', 'source'=>$arr_countries, 'required'=>true, 'javascript_event'=>'onchange="appChangeCountry(this.value,\'b_state\',\'\',\''.Application::Get('token').'\')"'),
				'b_state' 		=> array('title'=>_STATE_PROVINCE, 'type'=>'textbox', 'width'=>'210px', 'required'=>false, 'maxlength'=>'64', 'validation_type'=>'text'),
			),
		    'separator_contact_information'   =>array(
				'separator_info' => array('legend'=>_CONTACT_INFORMATION),
				'phone' 		=> array('title'=>_PHONE, 'type'=>'textbox', 'width'=>'210px', 'required'=>false, 'maxlength'=>'32', 'validation_type'=>'text'),
				'fax' 		    => array('title'=>_FAX,	'type'=>'textbox', 'width'=>'210px', 'required'=>false, 'maxlength'=>'32', 'validation_type'=>'text'),
				'email' 		=> array('title'=>_EMAIL_ADDRESS, 'type'=>'textbox', 'width'=>'230px', 'required'=>false, 'maxlength'=>'70', 'validation_type'=>'email', 'unique'=>true, 'autocomplete'=>'off'),
			),
		    'separator_membership_info' =>array(
				'separator_info'        => array('legend'=>_MEMBERSHIP_INFO),
                'membership_plan_id'    => array('title'=>_MEMBERSHIP_PLANS, 'type'=>'enum', 'required'=>true, 'readonly'=>false, 'width'=>'120px', 'default'=>'', 'default_option'=>'', 'source'=>$arr_membership_plans, 'javascript_event'=>'onchange="appChangePlan(\''.$this->tableName.'\')"'),
                'membership_images_count' => array('title'=>_IMAGES_COUNT, 'type'=>'textbox',  'width'=>'60px', 'required'=>true, 'readonly'=>true, 'maxlength'=>'', 'default'=>'', 'validation_type'=>'numeric', 'unique'=>false, 'visible'=>true),
                'membership_addresses_count' => array('title'=>_ADDRESSES_COUNT, 'type'=>'textbox',  'width'=>'60px', 'required'=>true, 'readonly'=>true, 'maxlength'=>'', 'default'=>'', 'validation_type'=>'numeric', 'unique'=>false, 'visible'=>true),
                'membership_show_in_search' => array('title'=>_SHOW_IN_SEARCH, 'type'=>'hidden',  'required'=>true, 'readonly'=>false,  'default'=>''),
                'membership_show_in_search_ddl' => array('title'=>_SHOW_IN_SEARCH, 'type'=>'enum', 'required'=>true, 'readonly'=>true, 'width'=>'120px', 'default'=>'', 'view_type'=>'label', 'source'=>$arr_show_in_search),
                'membership_expires'    => array('title'=>_MEMBERSHIP_EXPIRES, 'type'=>'textbox',  'width'=>'115px', 'required'=>true, 'readonly'=>true, 'maxlength'=>'', 'default'=>'', 'validation_type'=>'date', 'unique'=>false, 'visible'=>true),
            ),
			'separator_professional_info' => array(
				'separator_info'        => array('legend'=>_PROFESSIONAL_INFORMATION),
				'medical_degree'        => array('title'=>_DEGREE, 'type'=>'enum', 'width'=>'', 'required'=>false, 'readonly'=>false, 'default'=>'', 'source'=>$arr_degrees, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
                'additional_degree'     => array('title'=>_ADDITIONAL_DEGREE, 'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'50', 'required'=>false, 'validation_type'=>'text'),
                'license_number'        => array('title'=>_LICENSE_NUMBER, 'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'30', 'required'=>false, 'validation_type'=>'text'),
				'education'             => array('title'=>_EDUCATION, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),
				'experience_years'      => array('title'=>_EXPERIENCE, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'0', 'source'=>$arr_experience, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>'', 'post_html'=>' '._YEARS),
				'residency_training'    => array('title'=>_RESIDENCY_TRAINING, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),
				'hospital_affiliations' => array('title'=>_HOSPITAL_AFFILIATIONS, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),
				'board_certifications'  => array('title'=>_BOARD_CERTIFICATIONS, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),				
				'awards_and_publications' => array('title'=>_AWARDS_AND_PUBLICATIONS, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),				
				'languages_spoken'      => array('title'=>_LANGUAGES_SPOKEN, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'52px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'125', 'validation_maxlength'=>'125', 'unique'=>false),				
				'insurances_accepted'   => array('title'=>_INSURANCES_ACCEPTED, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),				
			),
		);

		//---------------------------------------------------------------------- 
		// EDIT MODE
		// - Validation Type: alpha|numeric|float|alpha_numeric|text|email|ip_address|password|date
		//   Validation Sub-Type: positive (for numeric and float)
		//   Ex.: 'validation_type'=>'numeric', 'validation_type'=>'numeric|positive'
		// - Validation Max Length: 12, 255... Ex.: 'validation_maxlength'=>'255'
		// - Validation Min Length: 4, 6... Ex.: 'validation_minlength'=>'4'
		// - Validation Max Value: 12, 255... Ex.: 'validation_maximum'=>'99.99'
		//---------------------------------------------------------------------- 
		$this->EDIT_MODE_SQL = 'SELECT
								'.$this->tableName.'.'.$this->primaryKey.',
								'.$this->tableName.'.*,
								'.$this->tableName.'.user_password,
								IF(doctor_photo <> "", doctor_photo, IF(gender = "f", "doctor_f.png", "doctor_m.png")) as mod_doctor_photo,
                                IF('.$this->tableName.'.registered_from_ip = "", "<span class=gray>- unknown -</span>", registered_from_ip) as registered_from_ip,
                                IF('.$this->tableName.'.last_logged_ip = "", "<span class=gray>- unknown -</span>", last_logged_ip) as last_logged_ip,
								IF('.$this->tableName.'.membership_expires IS NULL, "<span class=gray>- never -</span>", DATE_FORMAT('.$this->tableName.'.membership_expires, \''.$this->sqlFieldDateFormat.'\')) as mod_membership_expires,
								IF('.$this->tableName.'.date_created IS NULL, "<span class=gray>- unknown -</span>", DATE_FORMAT('.$this->tableName.'.date_created, \''.$this->sqlFieldDatetimeFormat.'\')) as date_created,
								IF('.$this->tableName.'.date_lastlogin IS NULL, "-<span class=gray> never -</span>", DATE_FORMAT('.$this->tableName.'.date_lastlogin, \''.$this->sqlFieldDatetimeFormat.'\')) as date_lastlogin,
								IF('.$this->tableName.'.notification_status_changed IS NULL, "<span class=gray>- never -</span>", DATE_FORMAT('.$this->tableName.'.notification_status_changed, \''.$this->sqlFieldDatetimeFormat.'\')) as notification_status_changed,
								DATE_FORMAT('.$this->tableName.'.birth_date, \''.$this->sqlFieldDateFormat.'\') as mod_birth_date,
                                mpd.name as membership_plan_name,
                                IF(st.name IS NOT NULL, st.name, '.$this->tableName.'.b_state) as state_name
							FROM '.$this->tableName.'
                                LEFT OUTER JOIN '.TABLE_MEMBERSHIP_PLANS_DESCRIPTION.' mpd ON mpd.membership_plan_id = '.$this->tableName.'.membership_plan_id AND mpd.language_id = \''.Application::Get('lang').'\'
                                LEFT OUTER JOIN '.TABLE_COUNTRIES.' c ON '.$this->tableName.'.b_country = c.abbrv AND c.is_active = 1
                                LEFT OUTER JOIN '.TABLE_STATES.' st ON '.$this->tableName.'.b_state = st.abbrv AND st.country_id = c.id AND st.is_active = 1
							WHERE '.$this->tableName.'.'.$this->primaryKey.' = _RID_';		
		// define edit mode fields
		$this->arrEditModeFields = array(
			'separator_personal_info'   =>array(
				'separator_info' => array('legend'=>_PERSONAL_INFORMATION),
				'first_name'   => array('title'=>_FIRST_NAME, 'type'=>'textbox',  'width'=>'210px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'70', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true),
				'middle_name'  => array('title'=>_MIDDLE_NAME, 'type'=>'textbox',  'width'=>'110px', 'required'=>false, 'readonly'=>false, 'maxlength'=>'70', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true),
				'last_name'    => array('title'=>_LAST_NAME, 'type'=>'textbox',  'width'=>'210px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'70', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true),
				'gender'       => array('title'=>_GENDER, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>$gender_readonly, 'default'=>'m', 'source'=>$arr_genders, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
				'birth_date'   => array('title'=>_BIRTH_DATE, 'type'=>'date', 'required'=>true, 'readonly'=>false, 'unique'=>false, 'visible'=>true, 'default'=>'', 'validation_type'=>'date', 'format'=>'date', 'format_parameter'=>$date_format_edit, 'min_year'=>'90', 'max_year'=>'1'),
				'title'        => array('title'=>_TITLE, 'type'=>'enum', 'width'=>'', 'required'=>false, 'readonly'=>false, 'default'=>'', 'source'=>$arr_titles, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
			),
			'separator_profile_info' => array(
				'separator_info' => array('legend'=>_PROFILE_DETAILS),
				'doctor_photo' => array('title'=>_PHOTO, 'type'=>'image',    'width'=>'210px', 'required'=>false, 'readonly'=>false, 'target'=>'images/doctors/', 'no_image'=>'no_image.png', 'random_name'=>true, 'overwrite_image'=>false, 'unique'=>false, 'thumbnail_create'=>true, 'thumbnail_field'=>'doctor_photo_thumb', 'thumbnail_width'=>'90px', 'thumbnail_height'=>'90px', 'file_maxsize'=>'500k', 'watermark'=>$watermark, 'watermark_text'=>$watermark_text),
				'work_phone'   => array('title'=>_WORK_PHONE, 'type'=>'textbox',  'width'=>'210px', 'required'=>false, 'readonly'=>false, 'maxlength'=>'32', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true),
				'work_mobile_phone' => array('title'=>_MOBILE_WORK_PHONE, 'type'=>'textbox',  'width'=>'210px', 'required'=>false, 'readonly'=>false, 'maxlength'=>'32', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true),
				'default_visit_price' => array('title'=>_DEFAULT_PRICE_PER_VISIT, 'type'=>'textbox',  'width'=>'85px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'10', 'default'=>'0', 'validation_type'=>'float|positive', 'unique'=>false, 'visible'=>true, 'pre_html'=>$pre_currency_symbol.' ', 'post_html'=>$post_currency_symbol),
				'default_visit_duration' => array('title'=>_DEFAULT_VISIT_DURATION, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'15', 'source'=>$arr_slots, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
				'comments'			=> array('title'=>_COMMENTS,	  'type'=>'textarea', 'width'=>'420px', 'height'=>'70px', 'required'=>false, 'readonly'=>false, 'validation_type'=>'text', 'validation_maxlength'=>'2048'),
			),			
		    'separator_account_info'   =>array(
				'separator_info' => array('legend'=>_ACCOUNT_DETAILS),
				'user_name' 	     => array('title'=>_USERNAME, 'type'=>'label'),
				'user_password'      => array('title'=>_PASSWORD, 'type'=>'password', 'width'=>'210px', 'maxlength'=>'20', 'required'=>true, 'validation_type'=>'password', 'cryptography'=>PASSWORDS_ENCRYPTION, 'cryptography_type'=>PASSWORDS_ENCRYPTION_TYPE, 'aes_password'=>PASSWORDS_ENCRYPT_KEY, 'visible'=>(($this->allow_changing_password == 'yes' && $account_type == '') ? true : false)),
				'preferred_language' => array('title'=>_PREFERRED_LANGUAGE, 'type'=>'enum', 'required'=>true, 'readonly'=>false, 'width'=>'120px', 'source'=>$arr_languages),
				'date_created'	     => array('title'=>_DATE_CREATED, 'type'=>'label'),
				'registered_from_ip' => array('title'=>_REGISTERED_FROM_IP, 'type'=>'label'),
				'date_lastlogin'     => array('title'=>_LAST_LOGIN, 'type'=>'label'),
				'last_logged_ip'	 => array('title'=>_LAST_LOGGED_IP,	 'type'=>'label'),
				'email_notifications'=> array('title'=>_EMAIL_NOTIFICATION,	'type'=>'checkbox', 'true_value'=>'1', 'false_value'=>'0'),
				'notification_status_changed' => array('title'=>_NOTIFICATION_STATUS_CHANGED, 'type'=>'label'),
				'is_active'          => array('title'=>_ACTIVE, 'type'=>'checkbox', 'readonly'=>false, 'default'=>'1', 'true_value'=>'1', 'false_value'=>'0', 'unique'=>false, 'visible'=>$is_active_visible),
				'is_removed'         => array('title'=>_REMOVED,	'type'=>'checkbox', 'true_value'=>'1', 'false_value'=>'0', 'visible'=>$is_removed_visible),
				'registration_code'	 => array('title'=>_REGISTRATION_CODE, 'type'=>'hidden', 'width'=>'210px', 'required'=>false, 'default'=>''),
			),
		    'separator_billing_address'   =>array(
				'separator_info' => array('legend'=>_BILLING_ADDRESS),
				'b_address' 	=> array('title'=>_ADDRESS,	 'type'=>'textbox', 'width'=>'210px', 'required'=>true, 'maxlength'=>'64', 'validation_type'=>'text'),
				'b_address_2' 	=> array('title'=>_ADDRESS_2,'type'=>'textbox', 'width'=>'210px', 'required'=>false, 'maxlength'=>'64', 'validation_type'=>'text'),
				'b_city' 		=> array('title'=>_CITY,	 'type'=>'textbox', 'width'=>'210px', 'required'=>true, 'maxlength'=>'64', 'validation_type'=>'text'),
				'b_zipcode' 	=> array('title'=>_ZIP_CODE, 'type'=>'textbox', 'width'=>'210px', 'required'=>true, 'maxlength'=>'32', 'validation_type'=>'text'),
				'b_country' 	=> array('title'=>_COUNTRY,	 'type'=>'enum',     'width'=>'', 'source'=>$arr_countries, 'required'=>true, 'javascript_event'=>'onchange="appChangeCountry(this.value,\'b_state\',\'\',\''.Application::Get('token').'\')"'),
				'b_state' 		=> array('title'=>_STATE_PROVINCE, 'type'=>'textbox', 'width'=>'210px', 'required'=>false, 'maxlength'=>'64', 'validation_type'=>'text'),
			),
		    'separator_contact_information'   =>array(
				'separator_info' => array('legend'=>_CONTACT_INFORMATION),
				'phone' 		=> array('title'=>_PHONE, 'type'=>'textbox', 'width'=>'210px', 'required'=>false, 'maxlength'=>'32', 'validation_type'=>'text'),
				'fax' 		    => array('title'=>_FAX,	'type'=>'textbox', 'width'=>'210px', 'required'=>false, 'maxlength'=>'32', 'validation_type'=>'text'),
				'email' 		=> array('title'=>_EMAIL_ADDRESS, 'type'=>'textbox', 'width'=>'230px', 'required'=>false, 'maxlength'=>'70', 'validation_type'=>'email', 'unique'=>true, 'autocomplete'=>'off'),
			),
		    'separator_membership_info' =>array(
				'separator_info'        => array('legend'=>_MEMBERSHIP_INFO),
                'membership_plan_id'    => array('title'=>_MEMBERSHIP_PLANS, 'type'=>'enum', 'required'=>true, 'readonly'=>$membership_option_readonly, 'width'=>'120px', 'default'=>'', 'default_option'=>'', 'source'=>$arr_membership_plans),
                'membership_images_count' => array('title'=>_IMAGES_COUNT, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>$membership_option_readonly, 'default'=>'0', 'source'=>$arr_images, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
                'membership_addresses_count' => array('title'=>_ADDRESSES_COUNT, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>$membership_option_readonly, 'default'=>'0', 'source'=>$arr_addresses, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
                'membership_show_in_search' => array('title'=>_SHOW_IN_SEARCH, 'type'=>'enum',  'width'=>'', 'required'=>true, 'readonly'=>$membership_option_readonly, 'default'=>'0', 'source'=>$arr_show_in_search, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
                'membership_expires'    => array('title'=>_MEMBERSHIP_EXPIRES, 'type'=>'date', 'required'=>true, 'readonly'=>$membership_option_readonly, 'unique'=>false, 'visible'=>true, 'default'=>'', 'validation_type'=>'date', 'format'=>'date', 'format_parameter'=>$date_format_edit, 'min_year'=>'1', 'max_year'=>'10'),
            ),
			'separator_professional_info'   =>array(
				'separator_info'        => array('legend'=>_PROFESSIONAL_INFORMATION),
				'medical_degree'        => array('title'=>_DEGREE, 'type'=>'enum', 'width'=>'', 'required'=>false, 'readonly'=>false, 'default'=>'', 'source'=>$arr_degrees, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
                'additional_degree'     => array('title'=>_ADDITIONAL_DEGREE, 'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'50', 'required'=>false, 'validation_type'=>'text'),
                'license_number'        => array('title'=>_LICENSE_NUMBER, 'type'=>'textbox', 'width'=>'210px', 'maxlength'=>'30', 'required'=>false, 'validation_type'=>'text'),
				'education'             => array('title'=>_EDUCATION, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),
				'experience_years'      => array('title'=>_EXPERIENCE, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'', 'source'=>$arr_experience, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>'', 'post_html'=>' '._YEARS),
				'residency_training'    => array('title'=>_RESIDENCY_TRAINING, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),
				'hospital_affiliations' => array('title'=>_HOSPITAL_AFFILIATIONS, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),
				'board_certifications'  => array('title'=>_BOARD_CERTIFICATIONS, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),
				'awards_and_publications' => array('title'=>_AWARDS_AND_PUBLICATIONS, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),				
				'languages_spoken'      => array('title'=>_LANGUAGES_SPOKEN, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'52px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'125', 'validation_maxlength'=>'125', 'unique'=>false),				
				'insurances_accepted'   => array('title'=>_INSURANCES_ACCEPTED, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'70px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'unique'=>false),				
			),
		);

		//---------------------------------------------------------------------- 
		// DETAILS MODE
		//----------------------------------------------------------------------
		$this->DETAILS_MODE_SQL = $this->EDIT_MODE_SQL;
		$this->arrDetailsModeFields = array(
			'separator_personal_info'   =>array(
				'separator_info' => array('legend'=>_PERSONAL_INFORMATION),
				'first_name'   => array('title'=>_FIRST_NAME, 'type'=>'label'),
				'middle_name'  => array('title'=>_MIDDLE_NAME, 'type'=>'label'),
				'last_name'    => array('title'=>_LAST_NAME, 'type'=>'label'),
				'gender'       => array('title'=>_GENDER, 'type'=>'enum', 'source'=>$arr_genders),
				'mod_birth_date'   => array('title'=>_BIRTH_DATE, 'type'=>'label'),
				'title'        => array('title'=>_TITLE, 'type'=>'enum', 'source'=>$arr_titles),
			),
			'separator_profile_info' => array(
				'separator_info'       => array('legend'=>_PROFILE_DETAILS),
				'mod_doctor_photo'     => array('title'=>_PHOTO, 'type'=>'image', 'target'=>'images/doctors/', 'no_image'=>'no_image.png', 'image_width'=>'90px', 'image_height'=>'90px'),
				'work_phone'           => array('title'=>_WORK_PHONE, 'type'=>'label'),
				'work_mobile_phone'    => array('title'=>_MOBILE_WORK_PHONE, 'type'=>'label'),
				'fax' 		           => array('title'=>_FAX, 'type'=>'label'),
				'email' 		       => array('title'=>_EMAIL_ADDRESS, 'type'=>'label'),
				'default_visit_price'  => array('title'=>_DEFAULT_PRICE_PER_VISIT, 'type'=>'label', 'pre_html'=>$pre_currency_symbol, 'post_html'=>$post_currency_symbol),
				'default_visit_duration' => array('title'=>_DEFAULT_VISIT_DURATION, 'type'=>'enum', 'source'=>$arr_slots),
				'comments'			   => array('title'=>_COMMENTS,     'type'=>'label'),
			),			
		    'separator_account_info'   =>array(
				'separator_info'       => array('legend'=>_ACCOUNT_DETAILS),
				'user_name' 	       => array('title'=>_USERNAME, 'type'=>'label'),
				'preferred_language'   => array('title'=>_PREFERRED_LANGUAGE, 'type'=>'enum', 'source'=>$arr_languages),				
				'date_created'	       => array('title'=>_DATE_CREATED, 'type'=>'label'),
				'registered_from_ip'   => array('title'=>_REGISTERED_FROM_IP, 'type'=>'label'),
				'date_lastlogin'=> array('title'=>_LAST_LOGIN,	 'type'=>'label'),
				'last_logged_ip'	   => array('title'=>_LAST_LOGGED_IP,	 'type'=>'label'),
				'email_notifications'  => array('title'=>_EMAIL_NOTIFICATION, 'type'=>'enum', 'source'=>$arr_email_notifications),
				'notification_status_changed' => array('title'=>_NOTIFICATION_STATUS_CHANGED, 'type'=>'label'),
				'is_active'            => array('title'=>_ACTIVE, 'type'=>'enum', 'source'=>$arr_activity),
				'is_removed'           => array('title'=>_REMOVED, 'type'=>'enum', 'source'=>$arr_removed),
			),
		    'separator_billing_address'   =>array(
				'separator_info' => array('legend'=>_BILLING_ADDRESS),
				'b_address' 	=> array('title'=>_ADDRESS,	 'type'=>'label'),
				'b_address_2' 	=> array('title'=>_ADDRESS_2,'type'=>'label'),
				'b_city' 		=> array('title'=>_CITY,	 'type'=>'label'),
				'b_zipcode' 	=> array('title'=>_ZIP_CODE, 'type'=>'label'),
				'b_country' 	=> array('title'=>_COUNTRY,	 'type'=>'enum', 'source'=>$arr_countries),
				'state_name'    => array('title'=>_STATE,	 'type'=>'label'),
			),
		    'separator_contact_information'   =>array(
				'separator_info' => array('legend'=>_CONTACT_INFORMATION),
				'phone' 		=> array('title'=>_PHONE,	 'type'=>'label'),
				'fax' 		    => array('title'=>_FAX,	'type'=>'label'),
				'email' 		=> array('title'=>_EMAIL_ADDRESS, 'type'=>'label'),
			),
		    'separator_membership_info' =>array(
				'separator_info'        => array('legend'=>_MEMBERSHIP_INFO),
                'membership_plan_name'  => array('title'=>_MEMBERSHIP_PLANS, 'type'=>'label'),
                'membership_images_count' => array('title'=>_IMAGES_COUNT, 'type'=>'label'),
                'membership_addresses_count' => array('title'=>_ADDRESSES_COUNT, 'type'=>'label'),
                'membership_show_in_search' => array('title'=>_SHOW_IN_SEARCH, 'type'=>'enum', 'width'=>'120px', 'source'=>$arr_show_in_search),
                'mod_membership_expires'    => array('title'=>_MEMBERSHIP_EXPIRES, 'type'=>'label'),
            ),
			'separator_professional_info'   =>array(
				'separator_info'        => array('legend'=>_PROFESSIONAL_INFORMATION),
				'medical_degree'        => array('title'=>_DEGREE, 'type'=>'enum', 'source'=>$arr_degrees),
                'additional_degree'     => array('title'=>_ADDITIONAL_DEGREE, 'type'=>'label'),
                'license_number'        => array('title'=>_LICENSE_NUMBER, 'type'=>'label'),
				'education'             => array('title'=>_EDUCATION, 'type'=>'label'),
				'experience_years'      => array('title'=>_EXPERIENCE, 'type'=>'enum', 'source'=>$arr_experience, 'post_html'=>' '._YEARS),
				'residency_training'    => array('title'=>_RESIDENCY_TRAINING, 'type'=>'label'),
				'hospital_affiliations' => array('title'=>_HOSPITAL_AFFILIATIONS, 'type'=>'label'),
				'board_certifications'  => array('title'=>_BOARD_CERTIFICATIONS, 'type'=>'label'),
				'awards_and_publications' => array('title'=>_AWARDS_AND_PUBLICATIONS, 'type'=>'label'),
				'languages_spoken'      => array('title'=>_LANGUAGES_SPOKEN, 'type'=>'label'),
				'insurances_accepted'   => array('title'=>_INSURANCES_ACCEPTED, 'type'=>'label'),
			),
		);

		///////////////////////////////////////////////////////////////////////////////
		// #004. add translation fields to all modes
		/// $this->AddTranslateToModes(
		/// $this->arrTranslations,
		/// array('name'        => array('title'=>_NAME, 'type'=>'textbox', 'width'=>'410px', 'required'=>true, 'maxlength'=>'', 'readonly'=>false),
		/// 	  'description' => array('title'=>_DESCRIPTION, 'type'=>'textarea', 'width'=>'410px', 'height'=>'90px', 'required'=>false, 'readonly'=>false)
		/// )
		/// );
		///////////////////////////////////////////////////////////////////////////////			

	}
	
	//==========================================================================
    // Class Destructor
	//==========================================================================
    function __destruct()
	{
		// echo 'this object has been destroyed';
    }


	//==========================================================================
    // MicroGrid Methods
	//==========================================================================	
	/**
	 * Before Add record
	 */
    public function BeforeAddRecord()
    { 
        $plan_id = self::GetParameter('membership_plan_id', false);
        if($plan_id == ''){
			$default_plan_info = MembershipPlans::GetDefaultPlanInfo();
        }else{
			$default_plan_info = MembershipPlans::GetPlanInfo($plan_id);            
        }
        
        $membership_plan_id = isset($default_plan_info['id']) ? (int)$default_plan_info['id'] : 0;
        $membership_images_count = isset($default_plan_info['images_count']) ? (int)$default_plan_info['images_count'] : 0;
        $membership_addresses_count = isset($default_plan_info['addresses_count']) ? (int)$default_plan_info['addresses_count'] : 0;
        $default_plan_duration = isset($default_plan_info['duration']) ? (int)$default_plan_info['duration'] : 0;
        $membership_show_in_search = isset($default_plan_info['show_in_search']) ? (int)$default_plan_info['show_in_search'] : 0;
        if($default_plan_duration > -1){
            $membership_expires = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + $default_plan_duration, date('Y')));                    
        }else{
            $membership_expires = null;
        }

        if($plan_id == ''){
            $this->arrAddModeFields['separator_membership_info']['membership_plan_id']['default'] = $membership_plan_id;
            $this->arrAddModeFields['separator_membership_info']['membership_images_count']['default'] = $membership_images_count;
            $this->arrAddModeFields['separator_membership_info']['membership_addresses_count']['default'] = $membership_addresses_count;
            $this->arrAddModeFields['separator_membership_info']['membership_show_in_search']['default'] = $membership_show_in_search;
            $this->arrAddModeFields['separator_membership_info']['membership_show_in_search_ddl']['default'] = $membership_show_in_search;
            $this->arrAddModeFields['separator_membership_info']['membership_expires']['default'] = $membership_expires;
        }else{
            $this->params['membership_plan_id'] = $membership_plan_id;
            $this->params['membership_images_count'] = "$membership_images_count";
            $this->params['membership_addresses_count'] = "$membership_addresses_count";
            $this->params['membership_show_in_search'] = "$membership_show_in_search";
            $this->arrAddModeFields['separator_membership_info']['membership_show_in_search']['default'] = "$membership_show_in_search";
            $this->params['membership_show_in_search_ddl'] = $membership_show_in_search;
            $this->params['membership_expires'] = $membership_expires;        
        }
    }

	/**
	 * After Add Record
	 */
	public function AfterAddRecord()
	{ 
		echo '<script type="text/javascript">appChangeCountry(jQuery("#b_country").val(), "b_state", "'.self::GetParameter('b_state', false).'", "'.Application::Get('token').'");</script>';
	}
	
    /**
	 * After-Insert operation
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
					'{FIRST NAME}'   => $this->params['first_name'],
					'{LAST NAME}'    => $this->params['last_name'],
					'{USER NAME}'    => $this->params['user_name'],
					'{USER PASSWORD}' => $this->params['user_password'],
					'{WEB SITE}'     => $_SERVER['SERVER_NAME'],
					'{BASE URL}'     => APPHP_BASE,
					'{YEAR}' 	     => date('Y'),
					'{ACCOUNT TYPE}' => 'doctor'
				),
				$this->params['preferred_language']
			);		
		}
		////////////////////////////////////////////////////////////
	}

	/**
	 * Before Edit Record
	 */
	public function BeforeEditRecord()
	{
		$registration_code = isset($this->result[0][0]['registration_code']) ? $this->result[0][0]['registration_code'] : '';
		$is_active         = isset($this->result[0][0]['is_active']) ? $this->result[0][0]['is_active'] : '';
		$reactivation_html = '';
		
		if($registration_code != '' && !$is_active && $this->reg_confirmation == 'by email'){
			$reactivation_html = ' &nbsp;<a href="javascript:void(\'email|reactivate\')" onclick="javascript:if(confirm(\''._PERFORM_OPERATION_COMMON_ALERT.'\'))__mgDoPostBack(\''.TABLE_DOCTORS.'\', \'reactivate\');">[ '._REACTIVATION_EMAIL.' ]</a>';
		}
		$this->arrEditModeFields['separator_contact_information']['email']['post_html'] = $reactivation_html;
		return true;
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
	 * Before-Update operation
	 */
	public function BeforeUpdateRecord()
	{
		$sql = 'SELECT email_notifications, user_password FROM '.$this->tableName.' WHERE '.$this->primaryKey.' = '.(int)$this->curRecordId;
		$result = database_query($sql, DATA_ONLY, FIRST_ROW_ONLY);
		if(isset($result['email_notifications'])) $this->email_notifications = $result['email_notifications'];
		if(isset($result['user_password'])) $this->user_password = $result['user_password'];
		return true;
	}

	/**
	 * After-Update operation
	 */
	public function AfterUpdateRecord()
	{
		global $objSettings;
		
		$registration_code = self::GetParameter('registration_code', false);
		$is_active         = self::GetParameter('is_active', false);
		$removed_update_clause = (self::GetParameter('is_removed', false) == '1') ? 'is_active = 0' : '';
		$confirm_update_clause = '';
		
		$sql = 'SELECT user_name, user_password, preferred_language FROM '.$this->tableName.' WHERE '.$this->primaryKey.' = '.$this->curRecordId;
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
	 *	After-deleting record
	 */
	public function AfterDeleteRecord()
	{
		// clean schedule timeblocks table		
		$sql = 'DELETE FROM '.TABLE_SCHEDULE_TIMEBLOCKS.'
				WHERE schedule_id IN (SELECT id FROM '.TABLE_SCHEDULES.' WHERE doctor_id = '.(int)$this->curRecordId.')';
		database_void_query($sql);
		
		// clean schedules		
		$sql = 'DELETE FROM '.TABLE_SCHEDULES.' WHERE doctor_id = '.(int)$this->curRecordId;
		database_void_query($sql);

		// clean specialities		
		$sql = 'DELETE FROM '.TABLE_DOCTOR_SPECIALITIES.' WHERE doctor_id = '.(int)$this->curRecordId;
		database_void_query($sql);

		// clean addresses
		$sql = 'DELETE FROM '.TABLE_DOCTOR_ADDRESSES.' WHERE doctor_id = '.(int)$this->curRecordId;
		database_void_query($sql);

		// clean timeoffs
		$sql = 'DELETE FROM '.TABLE_TIMEOFFS.' WHERE doctor_id = '.(int)$this->curRecordId;
		database_void_query($sql);
	}
	

	//==========================================================================
    // Static Methods
	//==========================================================================
	/**
	 *	Returns all array of all active languages
	 *		@param $where_clause
	 *		@param $limit_clause
	 */
	public static function GetAllActive($where_clause = '', $limit_clause = '')
	{		
		$sql = 'SELECT * FROM '.TABLE_DOCTORS.'
				WHERE is_active = 1 '.(($where_clause != '') ? ' AND '.$where_clause : '').'
				ORDER BY id ASC';
        if(!empty($limit_clause)) $sql .= ' '.$limit_clause;
		return database_query($sql, DATA_AND_ROWS);
	}
	
	/**
	 *	Returns doctor's info by schedule
	 *		@param $schedule_id
	 */
	public static function GetDoctorInfoBySchedule($schedule_id = '')
	{		
		$sql = 'SELECT d.*
				FROM '.TABLE_DOCTORS.' d
					INNER JOIN '.TABLE_SCHEDULES.' sch ON d.id = sch.doctor_id
				WHERE sch.id = '.(int)$schedule_id;				
		return database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
	}	

	/**
	 *	Returns doctor's info by ID
	 *		@param $doctor_id
	 */
	public static function GetDoctorInfoById($doctor_id = '')
	{		
		$sql = 'SELECT *, CONCAT(first_name, " ", middle_name, " ", last_name) as full_name
                FROM '.TABLE_DOCTORS.'
                WHERE id = '.(int)$doctor_id;				
		return database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
	}	
	
	/**
	 *	Draw appointments block on homepage
	 *		@param $params
	 */
	public static function DrawAppointmentsBlock($params = array())
	{
		$doctor_speciality = isset($_POST['doctor_speciality']) ? prepare_input($_POST['doctor_speciality']) : '';
		$doctor_name = isset($_POST['doctor_name']) ? prepare_input($_POST['doctor_name']) : '';
		$doctor_location = isset($_POST['doctor_location']) ? prepare_input($_POST['doctor_location']) : '';
        
        $action_url = isset($params['action_url']) ? prepare_input($params['action_url'], false, 'low') : '';
        $target = isset($params['target']) ? prepare_input($params['target']) : '';
        $draw = isset($params['draw']) ? (bool)$params['draw'] : true;
		
		$allow_search_by_name = ModulesSettings::Get('doctors', 'allow_search_by_name');
		$allow_search_by_location = ModulesSettings::Get('doctors', 'allow_search_by_location');
		
		$output = '<form action="'.$action_url.'index.php?page=find_doctors" id="frmFindDoctors" name="frmFindDoctors" method="post"'.(!empty($target) ? ' target="'.$target.'"' : '').'>
			'.draw_hidden_field('p', '1', false, 'page_number').'
			'.draw_hidden_field('start_day', date('Y-m-d'), false, 'start_day').'
            '.draw_hidden_field('docid', '', false, 'docid').'
			'.draw_token_field(false);
		
		$output .= '<table class="tblSideBlock" cellspacing="0">';	
			$total_specialities = Specialities::GetAllActive();
			if($total_specialities[1] == 1){
				$output .= '<tr><td>';
				$output .= '<input type="hidden" name="doctor_speciality" id="doctor_speciality" value="'.$total_specialities[0][0]['id'].'" />';
			}else{
				$output .= '<tr valign="middle"><td>'._FIND_DOCTOR_BY_SPECIALITY.'</td></tr>';
				$output .= '<tr><td>';
				$output .= '<select name="doctor_speciality" id="doctor_speciality">';
				$output .= '<option value="">-- '._SELECT.' --</option>';											
				foreach($total_specialities[0] as $key => $val){
					$selected = (($val['id'] == $doctor_speciality) ? ' selected="selected"' : '');
					$output .= '<option value="'.$val['id'].'"'.$selected.'>'.$val['name'].'</option>';
				}					
				$output .= '</select>';
			}
			$output .= '</td></tr>';
			
			if($allow_search_by_name == 'yes'){
				$output .= '<tr><td></td></tr>
				<tr valign="middle"><td>'._FIND_DOCTOR_BY_NAME.'</td></tr>
				<tr><td><input type="text" name="doctor_name" value="'.$doctor_name.'" maxlength="64"></td></tr>';				
			}
			
			if($allow_search_by_location == 'yes'){
				$output .= '<tr><td></td></tr>
				<tr valign="middle"><td>'._FIND_DOCTOR_BY_LOCATION.'</td></tr>
				<tr><td><input type="text" name="doctor_location" value="'.$doctor_location.'" maxlength="64"></td></tr>';				
			}

			$output .= '<tr><td nowrap height="3px"></td></tr>
			<tr><td><input class="button" type="button" onclick="appFormSubmit(\'frmFindDoctors\')" value="'._FIND_DOCTORS.'" /></td></tr>
			</table>
		</form>';
		
		if($draw) echo $output;
		else return $output;
	}
	
	/**
	 *	Draw appointments block on homepage
	 *		@param $search_params
	 */
	public static function SearchFor($search_params)
	{
		$result = array();
		$total_doctors = self::GetDoctorsByParam($search_params);
        
		foreach($total_doctors[0] as $key => $val){			
			$result[$val['doctor_id']] = array(
				'first_name'         => $val['first_name'],
				'middle_name'        => $val['middle_name'],
				'last_name'          => $val['last_name'],
				'title'              => $val['title'],
				'gender'             => $val['gender'],
				'medical_degree'     => $val['medical_degree'],
				'doctor_photo'       => $val['doctor_photo'],
				'doctor_photo_thumb' => $val['doctor_photo_thumb'],
				'speciality_id'      => $val['speciality_id'],
				'speciality_name'    => $val['speciality_name'],
				'address_id'         => $val['address_id'],
				'address'            => $val['address'],
                'membership_addresses_count' => $val['membership_addresses_count'],
                'membership_images_count' => $val['membership_images_count']
			);
		}
		
		return $result;		
	}

	/**
	 *	Draw appointments block on homepage
	 *      @param $result
	 *      @param $search_params
	 *		@param $draw
	 */
	public static function DrawSearchResult($result, $search_params, $draw = true)
	{
		$output = '';
		$start_day = isset($_POST['start_day']) ? prepare_input($_POST['start_day']) : date('Y-m-d');
		$is_active = ModulesSettings::Get('appointments', 'is_active');

		$search_page_size = (int)ModulesSettings::Get('appointments', 'page_size');
		$doctors_total = count($result);
		
		// -------- pagination		
		$current_page = isset($_REQUEST['p']) ? (int)$_REQUEST['p'] : '1';
		$total_pages = (int)($doctors_total / $search_page_size);		
		if($current_page > ($total_pages+1)) $current_page = 1;
		if(($doctors_total % $search_page_size) != 0) $total_pages++;
		if(!is_numeric($current_page) || (int)$current_page <= 0) $current_page = 1;
		// --------
		
		if(empty($result)){			
			if($search_params['speciality'] != '' && empty($search_params['name']) && empty($search_params['location'])){
				$output = draw_important_message(_NO_DOCTORS_FOUND_IN_SPEC, false);	
			}else{
				$output = draw_important_message(_NO_DOCTORS_FOUND, false);
			}			
		}else{
			// prepare array for next 7 days
			$current_day = strtotime($start_day);
			$arr_dates = array();
			for($i=0; $i<7; $i++){
				$current_date = ($i == 0) ? $current_day : strtotime('+'.$i.' day', $current_day); 					
				$arr_dates[] = array(
					'date'=>date('Y-m-d', $current_date),
					'date_view'=>format_date(date('Y-m-d', $current_date), '', '', true),
					'week_day_num'=>(date('w', $current_date)+1),
					'week_day'=>get_weekday_local(date('w', $current_date)+1)
				);				
			}
			$prev_week_day = date('Y-m-d', strtotime('-6 day', $current_day)); 					
			$next_week_day = date('Y-m-d', strtotime('+6 day', $current_day));
			
			$but_prev = (Application::Get('lang_dir') == 'ltr') ? 'but_prev' : 'but_next';
			$but_next = (Application::Get('lang_dir') == 'ltr') ? 'but_next' : 'but_prev';
			$docid_param = isset($search_params['doctor_id']) ? '&docid='.(int)$search_params['doctor_id'] : '';
            
			$output .= '<table class="doctors_result" cellspacing="0" cellpadding="0">';
			$output .= '
				<tr valign="top">
					<td class="th_left">'._DOCTORS.'</td>';
					if($is_active == 'yes'){
						$output .= '<td class="th_right">';
							$output .= '<table cellspacing="0" cellpadding="0"><tr>';
                            $output .= '<td class="go_prev">';
                                if($prev_week_day >= date('Y-m-d')){
                                    $output .= '<a href="javascript:void(\'week|previous\');" title="'._PREVIOUS.'" onclick="appFormSubmit(\'frmFindDoctors\',\'start_day='.$prev_week_day.'&page_number='.$current_page.$docid_param.'\')"><img src="templates/'.Application::Get('template').'/images/'.$but_prev.'_active.png"></a>';
                                }else{
                                    $output .= '<img src="templates/'.Application::Get('template').'/images/'.$but_prev.'.png">';
                                }								
                            $output .= '</td>';						
							$days_count = 0;
							foreach($arr_dates as $dkey => $dval){
								$output .= '<td class="th_week_day'.(($days_count++ % 2 == 0) ? ' wd_colored' : ' wd_white').'">'.$dval['week_day'].'<br>'.$dval['date_view'].'</td>';
							}
							$output .= '<td class="go_next"><a href="javascript:void(\'week|next\');" title="'._NEXT.'" onclick="appFormSubmit(\'frmFindDoctors\',\'start_day='.$next_week_day.'&page_number='.$current_page.$docid_param.'\')"><img src="templates/'.Application::Get('template').'/images/'.$but_next.'_active.png"></a></td>';						
							$output .= '</tr></table>';
						$output .= '</td>';
					}
				$output .= '</tr>';

			$doctors_count = 0;
			foreach($result as $key => $val){
				$doctors_count++;
				
				if($doctors_count <= ($search_page_size * ($current_page - 1))){
					continue;
				}else if($doctors_count > ($search_page_size * ($current_page - 1)) + $search_page_size){
					break;
				}

				if($search_params['speciality'] != ''){
					$speciality_id = isset($val['speciality_id']) ? (int)$val['speciality_id'] : '';			
					$speciality_name = isset($val['speciality_name']) ? $val['speciality_name'] : '';
				}else{
					$doc_speciality_info = self::GetDoctorSpecialities($key);
					$speciality_id = isset($doc_speciality_info[0][0]['speciality_id']) ? (int)$doc_speciality_info[0][0]['speciality_id'] : '';			
					$speciality_name = isset($doc_speciality_info[0][0]['speciality_name']) ? $doc_speciality_info[0][0]['speciality_name'] : '';
				}
				
                ///$doctor_def_address = '';
                $doctor_def_address_id = '0';
                if($val['membership_addresses_count'] > 0){
                    if(!empty($val['address'])){
                        ///$doctor_def_address = $val['address'];
                        $doctor_def_address_id = (int)$val['address_id'];
                    }else{                        
                        // draws 1st non-default address
                        $doctor_default_address = DoctorAddresses::GetDefaultAddress($key);
                        ///$doctor_def_address = isset($doctor_default_address['address']) ? $doctor_default_address['address'] : '';
                        $doctor_def_address_id = isset($doctor_default_address['id']) ? (int)$doctor_default_address['id'] : '0';
                    } 
                }
				
				$photo = ($val['doctor_photo_thumb'] != '') ? $val['doctor_photo_thumb'] : 'doctor_'.$val['gender'].'.png';
				$doc_name = $val['title'].' '.$val['first_name'].' '.$val['middle_name'].' '.$val['last_name'];
				$output .= '
					<tr valign="top">
						<td colspan="3">
							<div class="doctor_profile">
                                <img class="doctor_small_photo" src="images/doctors/'.$photo.'"><br>
								'.prepare_link('doctors', 'docid', (int)$key, $doc_name, $doc_name).' '.$val['medical_degree'].'<br>
								'.$speciality_name.'                                
							</div>
						';
                        /// '.$doctor_def_address.'
					if($is_active == 'yes'){
                            $non_empty_days = 0;
                            $output_inner = '<table cellspacing="0" cellpadding="0"><tr>';
                            $output_inner .= '<td class="go_prev"></td>';
							$days_count = 0;
                            $first_day = '';
							foreach($arr_dates as $dkey => $dval){
                                if($first_day == '') $first_day = $dval['date'];
								$time_slots = ScheduleTimeblocks::GetTimeSlotsForDay($key, $dval['date'], $dval['week_day_num']);
								$slots = 0;
								$output_inner .= '<td class="td_week_day'.(($days_count++ % 2 == 0) ? ' wd_colored' : ' wd_white').'">';
								foreach($time_slots as $ts_key => $ts_val){
									$doctor_address_id = (!empty($ts_val['doctor_address_id']) ? $ts_val['doctor_address_id'] : $doctor_def_address_id);
									$param = base64_encode('docid='.$key.'&dspecid='.$speciality_id.'&schid='.$ts_val['schedule_id'].'&daddid='.$doctor_address_id.'&date='.$dval['date'].'&start_time='.$ts_val['time'].'&duration='.$ts_val['duration']);
									$output_inner .= prepare_permanent_link('index.php?page=appointment_details&prm='.$param, $ts_val['time_view']).'<br>'."\n";
									if($slots == 5){
										$output_inner .= '<div class="hidden_slots'.$key.'" style="display:none;">';
									}
									$slots++;                                    
								}
                                if($slots > 0) $non_empty_days++;
								if($slots > 6){
									$output_inner .= '</div>';
									$output_inner .= '<a class="more_links'.$key.'" href="javascript:void(0)" onclick="appShowElement(\'.hidden_slots'.$key.'\');appHideElement(\'.more_links'.$key.'\');">'._MORE.'...</a>';
								}
								$output_inner .= '</td>';								
							}
							$output_inner .= '<td class="go_next"></td>';						
							$output_inner .= '</tr></table>';
                            
                            $output .= ($non_empty_days == 0) ? self::FindNextAppointment($key, $first_day) : $output_inner;
						//$output .= '</td>';
					}
					$output .= '</tr>';
				$output .= '<tr valign="top"><td colspan="3"><hr></td></tr>';
			}
			$output .= '</table>';
			
			$output .= '<div class="paging">';
			for($page_ind = 1; $page_ind <= $total_pages; $page_ind++){
				$output .= '<a class="paging_link" href="javascript:void(\'page|'.$page_ind.'\');" onclick="javascript:appFormSubmit(\'frmFindDoctors\',\'page_number='.$page_ind.$docid_param.'\')">'.(($page_ind == $current_page) ? '<b>['.$page_ind.']</b>' : $page_ind).'</a> ';
			}
			$output .= '</div>'; 
		}

		if($draw) echo $output;
		else return $output;
	}

	/**
	 *	Get membership info
	 *      @param $doctor_id
	 */
	public static function GetMembershipInfo($doctor_id)
	{
        $result = array('plan_id'=>'', 'plan_name'=>'', 'images_count'=>0, 'addresses_count'=>0, 'expires'=>'');        
        $sql = 'SELECT
                    d.id,
                    d.membership_plan_id,
                    d.membership_images_count,
                    d.membership_addresses_count,
                    d.membership_show_in_search,
                    d.membership_expires,
                    mpd.name
                FROM '.TABLE_DOCTORS.' as d
                    LEFT OUTER JOIN '.TABLE_MEMBERSHIP_PLANS_DESCRIPTION.' mpd ON mpd.membership_plan_id = d.membership_plan_id AND mpd.language_id = \''.Application::Get('lang').'\'
                WHERE d.id = '.(int)$doctor_id;
		$sql_result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
        if($sql_result[1] > 0){
            $result['plan_id'] = $sql_result[0]['membership_plan_id'];
            $result['plan_name'] = $sql_result[0]['name'];
            $result['images_count'] = $sql_result[0]['membership_images_count'];
            $result['addresses_count'] = $sql_result[0]['membership_addresses_count'];
            $result['show_in_search'] = $sql_result[0]['membership_show_in_search'];
            $result['expires'] = $sql_result[0]['membership_expires'];
        }
        
        return $result;
    }    
    
	/**
	 *	Draw appointments block on homepage
	 *      @param $doctor_id
	 *      @param $info_type
	 *      @param $mode
	 *      @param $result
	 *		@param $draw
	 */
	public static function DrawDoctorInfo($doctor_id, $info_type = 'long', $mode = '', $result = array(), $draw = true)
	{
		global $objLogin;		
		$output = '';		
		
        if(Modules::IsModuleInstalled('ratings') == 'yes'){
            if($objLogin->IsLoggedInAsAdmin() || ($objLogin->IsLoggedInAsDoctor() && $doctor_id == $objLogin->GetLoggedID())) $mode = 'demo';
            if($info_type == 'long' || ($info_type == 'short' && !defined('RATING_INCLUDED'))){
                $output .= '<link href="modules/ratings/css/'.(($mode == 'demo') ? 'ratings.demo.css' : 'ratings.css').'" rel="stylesheet" type="text/css" />';
                if(Application::Get('lang_dir') == 'rtl') $output .= '<link href="modules/ratings/css/ratings-rtl.css" rel="stylesheet" type="text/css" />';
                $ratings_lang = (file_exists('modules/ratings/langs/'.Application::Get('lang').'.js')) ? Application::Get('lang') : 'en';
                $output .= '<script src="modules/ratings/langs/'.$ratings_lang.'.js" type="text/javascript"></script>';                
                $output .= '<script src="modules/ratings/js/'.(($mode == 'demo') ? 'ratings.demo.js' : 'ratings.js').'" type="text/javascript"></script>';
                define('RATING_INCLUDED', true);                
            } 
        }

        if(!empty($result) && is_array($result)){
            $result_row = $result;
            $allowed = ($result_row['membership_show_in_search'] == 1) ? true : false;    
        }else{
            $result = self::GetAllActive('id = '.(int)$doctor_id);
            $result_row = isset($result[0][0]) ? $result[0][0] : array();            
            if($objLogin->IsLoggedInAsDoctor()){
                $allowed = ($result[1] > 0) ? true : false;
            }else if($objLogin->IsLoggedInAsAdmin()){
                $allowed = true;
            }else{
                $allowed = ($result[1] > 0) ? true : false;
                ///$allowed = ($result[1] > 0 && $result[0][0]['membership_show_in_search'] == 1) ? true : false;
            }            
        }
        
		if($allowed){			
			$api_key = ModulesSettings::Get('google_maps', 'api_key'); /* your API key */
			$map_height = (int)ModulesSettings::Get('google_maps', 'map_height');
			$map_width = (int)ModulesSettings::Get('google_maps', 'map_width'); 
			
			$photo = ($result_row['doctor_photo'] != '') ? $result_row['doctor_photo'] : 'doctor_'.$result_row['gender'].'.png';
			$doctor_name = $result_row['first_name'].' '.$result_row['middle_name'].' '.$result_row['last_name'];
			$title = ($result_row['title'] != '') ? $result_row['title'] : '';			
			$medical_degree = ($result_row['medical_degree'] != '') ? ' - '.$result_row['medical_degree'] : '';
			$additional_degree = ($result_row['additional_degree'] != '') ? ' ('.$result_row['additional_degree'].')' : '';
			$license_number = ($result_row['license_number'] != '') ? ' <b>'._LICENSE_NUMBER.'</b>: <br>'.$result_row['license_number'].'<br><br>' : '';
			$education = ($result_row['education'] != '') ? ' <b>'._EDUCATION.'</b>: <br>'.$result_row['education'].'<br><br>' : '';
			$experience = ($result_row['experience_years'] != '') ? ' <b>'._EXPERIENCE.'</b>: <br>'.$result_row['experience_years'].' '.(($result_row['experience_years'] > 1) ? _YEARS : _YEAR).'<br><br>' : '';
			$residency_training = ($result_row['residency_training'] != '') ? ' <b>'._RESIDENCY_TRAINING.'</b>: <br>'.$result_row['residency_training'].'<br><br>' : '';
			$hospital_affiliations = ($result_row['hospital_affiliations'] != '') ? ' <b>'._HOSPITAL_AFFILIATIONS.'</b>: <br>'.$result_row['hospital_affiliations'].'<br><br>' : '';
			$board_certifications = ($result_row['board_certifications'] != '') ? ' <b>'._BOARD_CERTIFICATIONS.'</b>: <br>'.$result_row['board_certifications'].'<br><br>' : '';
			$awards_and_publications = ($result_row['awards_and_publications'] != '') ? ' <b>'._AWARDS_AND_PUBLICATIONS.'</b>: <br>'.$result_row['awards_and_publications'].'<br><br>' : '';
			$languages_spoken = ($result_row['languages_spoken'] != '') ? ' <b>'._LANGUAGES_SPOKEN.'</b>: <br>'.$result_row['languages_spoken'].'<br><br>' : '';
			$insurances_accepted = ($result_row['insurances_accepted'] != '') ? ' <b>'._INSURANCES_ACCEPTED.'</b>: <br>'.$result_row['insurances_accepted'].'<br><br>' : '';
			$default_visit_price = ($result_row['default_visit_price'] != '') ? ' <b>'._DEFAULT_PRICE_PER_VISIT.'</b>: <br>'.Currencies::PriceFormat($result_row['default_visit_price']).'<br><br>' : '';
            $membership_images_count = ($result_row['membership_images_count'] != '') ? $result_row['membership_images_count'] : 0;
            $membership_addresses_count = ($result_row['membership_addresses_count'] != '') ? $result_row['membership_addresses_count'] : 0;

			if($info_type != 'short') $output .= '<div class="doctor_info">';			
			$output .= '<table class="tblDoctorInfo">';
            $output .= '<tr>';
            
            // draw doctor photo & gallery images
            $output .= '<td rowspan="2" width="140px">';
            if($photo != '') $output .= '<img class="doctor_photo" src="images/doctors/'.$photo.'" alt="icon" />';
            if($info_type != 'short'){
                $result_images = DoctorImages::GetImagesForDoctor($doctor_id);
                for($i=0; ($i < $result_images[1] && $i < $membership_images_count); $i++){
                    if($result_images[0][$i]['item_file'] != ''){
                        if($mode == '') $output .= '<a rel="lyteshow_'.$doctor_id.'" href="images/doctors/'.$result_images[0][$i]['item_file'].'" title="'.decode_text($result_images[0][$i]['image_title']).'">';
                        $output .= '<img class="doctor_thumb" src="images/doctors/'.$result_images[0][$i]['item_file_thumb'].'" alt="icon" />';
                        if($mode == '') $output .= '</a>';                        
                    }                    
                }                
            } 
            $output .= '</td>';
            
            // draw doctor name and titles
            $output .= '<td colspan="3" valign="top">';
                $output .= '<h3>';
                $doctor_full_name = $title.$doctor_name.$medical_degree.$additional_degree;
                $output .= ($info_type == 'short') ? prepare_link('doctors', 'docid', (int)$doctor_id, $doctor_full_name, $doctor_full_name) : $doctor_full_name;
                $output .= '</h3>';
            $output .= '</td>';
            $output .= '</tr>';

            $inner_output = '<td valign="top">';
            $result = DoctorSpecialities::GetSpecialities($doctor_id);
            if($result[1] > 0){
                $inner_output .= '<b>'._SPECIALITIES.'</b>:<br>';
                $inner_output .= '<ul>';
                for($i=0; $i < $result[1]; $i++){
                    $inner_output .= '<li>'.$result[0][$i]['name'].'</li>';	
                }
                $inner_output .= '</ul>';

                $result_addresses = DoctorAddresses::GetAddresses($doctor_id, ($objLogin->IsLoggedIn() ? 'all' : 'public'));			
                if($result_addresses[1] > 0 && $membership_addresses_count > 0){
                    $inner_output .= '<b>'._ADDRESSES.'</b>:<br>';
                    $inner_output .= '<ul>';
                    $markers = '';
                    $markers_count = 0;
                    for($i=0; ($i < $result_addresses[1] && $i < $membership_addresses_count); $i++){
                        $inner_output .= '<li>'.$result_addresses[0][$i]['address'].'</li>';
                        if(!empty($result_addresses[0][$i]['latitude']) || !empty($result_addresses[0][$i]['latitude'])){
                            $markers .= '&markers=color:blue%7Clabel:'.chr(65+($markers_count++)).'%7C'.$result_addresses[0][$i]['latitude'].','.$result_addresses[0][$i]['longitude'].'';									
                        }
                    }
                    $inner_output .= '</ul>';                        
                }
            }
            $inner_output .= '</td>';
            
            if(Modules::IsModuleInstalled('ratings') == 'yes'){					
                $inner_output .= '<td width="150px" align="center" valign="top">';
                $inner_output .= '<b>'._PATIENTS_RATING.': </b> <div class="ratings_stars" id="rt_doctor_'.$doctor_id.'"></div>';
                $inner_output .= '</td>';
            }

            $output .= '<tr>';
            $output .= $inner_output;
            
            if($info_type == 'short'){
                $output .= '</tr>';
                $output .= '</table>';                
            }else{
                $output .= '</tr>';                
                $output .= '</table>';                
                $output .= $license_number.'
                '.$education.'
                '.$experience.'
                '.$residency_training.'
                '.$hospital_affiliations.'
                '.$board_certifications.'
                '.$awards_and_publications.'
                '.$languages_spoken.'
                '.$insurances_accepted.'
                '.$default_visit_price;

                $markers = '';
                $markers_count = 0;
                $output .= '<table class="tblDoctorMap"><tr valign="top"><td>';
                if($result_addresses[1] > 0 && $membership_addresses_count > 0){    
                    $output .= '<b>'._ADDRESSES.'</b>:<br>';
                    $output .= '<ul>';
                    for($i=0; ($i < $result_addresses[1] && $i < $membership_addresses_count); $i++){
                        $output .= '<li>'.$result_addresses[0][$i]['address'].'</li>';
                        if(!empty($result_addresses[0][$i]['latitude']) || !empty($result_addresses[0][$i]['latitude'])){
                            $markers .= '&markers=color:blue%7Clabel:'.chr(65+($markers_count++)).'%7C'.$result_addresses[0][$i]['latitude'].','.$result_addresses[0][$i]['longitude'].'';									
                        }
                    }
                    $output .= '</ul>';
                    $output .= '<br>';
                }
                $output .= '</td><td>';
                if($markers_count) $output .= '<img src="https://maps.googleapis.com/maps/api/staticmap?size='.$map_width.'x'.$map_height.'&maptype=roadmap&sensor=false'.$markers.'" alt="Doctor Addresses">';
                $output .= '</td></tr></table>';
            }
            if($info_type != 'short') $output .= '</div>';                		
        }else{
			$output .= _DOCTOR_PROFILE_INACTIVE;					
		}
	
		if($draw) echo $output;
		else return $output;
	}

	/**
	 *	Get doctor specialities
	 *		@param $doctor_id
	 */
	public static function GetDoctorSpecialities($doctor_id = 0)
	{
		$sql = 'SELECT
					d.id as doctor_id,
					sd.id as speciality_id,
					sd.name as speciality_name
				FROM '.TABLE_DOCTOR_SPECIALITIES.' ds
					INNER JOIN '.TABLE_DOCTORS.' d ON ds.doctor_id = d.id
                    INNER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' sd ON ds.speciality_id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\' 
				WHERE ds.doctor_id = '.(int)$doctor_id.'
				ORDER BY ds.priority_order ASC';
		return database_query($sql, DATA_AND_ROWS);
	}

	/**
	 *	Get doctor speciality
	 *		@param $doctor_id
	 *		@param $speciality_id
	 */
	public static function GetDoctorSpeciality($doctor_id = 0, $speciality_id = 0)
	{
		$sql = 'SELECT
					sd.id as speciality_id,
					sd.name as speciality_name
				FROM '.TABLE_DOCTOR_SPECIALITIES.' ds
					INNER JOIN '.TABLE_DOCTORS.' d ON ds.doctor_id = d.id
                    INNER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' sd ON ds.speciality_id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\' 
				WHERE
					ds.doctor_id = '.(int)$doctor_id.' AND
					ds.speciality_id = '.(int)$speciality_id;
		return database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
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

		if(!empty($email)){
			if(check_email_address($email)){
				$sql = 'SELECT id, first_name, last_name, user_name, registration_code, preferred_language, is_active ';
				if(!PASSWORDS_ENCRYPTION){
					$sql .= ', user_password ';
				}else{
					if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'aes'){
						$sql .= ', AES_DECRYPT(user_password, \''.PASSWORDS_ENCRYPT_KEY.'\') as user_password ';
					}else if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'md5'){
						$sql .= ', \'\' as user_password ';
					}				
				}
				$sql .= 'FROM '.TABLE_DOCTORS.' WHERE email=\''.$email.'\'';
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
								$sql = 'UPDATE '.TABLE_DOCTORS.' SET user_password = \''.md5($user_password).'\' WHERE id = '.$temp['id'];
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
			if(check_email_address($email)){   
				if(!PASSWORDS_ENCRYPTION){
					$sql = 'SELECT id, first_name, last_name, user_name, user_password, preferred_language FROM '.TABLE_DOCTORS.' WHERE email=\''.$email.'\' AND is_active = 1';
				}else{
					if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'aes'){
						$sql = 'SELECT id, first_name, last_name, user_name, AES_DECRYPT(user_password, \''.PASSWORDS_ENCRYPT_KEY.'\') as user_password, preferred_language FROM '.TABLE_DOCTORS.' WHERE email=\''.$email.'\' AND is_active = 1';
					}else if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'md5'){
						$sql = 'SELECT id, first_name, last_name, user_name, \'\' as user_password, preferred_language  FROM '.TABLE_DOCTORS.' WHERE email=\''.$email.'\' AND is_active = 1';
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
							$sql = 'UPDATE '.TABLE_DOCTORS.' SET user_password = \''.md5($user_password).'\' WHERE id = '.$temp['id'];
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
	 * Change Password
	 *		@param $password
	 *		@param $confirmation - confirm password
	 */
	public static function ChangePassword($password, $confirmation)
	{
		global $objLogin;
		
		$msg = '';
		
		// deny all operations in demo version
		if(strtolower(SITE_MODE) == 'demo'){
			$msg = draw_important_message(_OPERATION_BLOCKED, false);
			return $msg;
		}
				
		if(!empty($password) && !empty($confirmation) && strlen($password) >= 6) {
			if($password == $confirmation){
				if(!PASSWORDS_ENCRYPTION){
					$sql = 'UPDATE '.TABLE_DOCTORS.' SET user_password = '.quote_text(encode_text($password)).' WHERE id = '.(int)$objLogin->GetLoggedID();
				}else{
					if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'aes'){
						$sql = 'UPDATE '.TABLE_DOCTORS.' SET user_password = AES_ENCRYPT('.quote_text($password).', '.quote_text(PASSWORDS_ENCRYPT_KEY).') WHERE id = '.(int)$objLogin->GetLoggedID();
					}else if(strtolower(PASSWORDS_ENCRYPTION_TYPE) == 'md5'){
						$sql = 'UPDATE '.TABLE_DOCTORS.' SET user_password = '.quote_text(md5($password)).' WHERE id = '.(int)$objLogin->GetLoggedID();
					}else{
						$sql = 'UPDATE '.TABLE_DOCTORS.' SET user_password = AES_ENCRYPT('.quote_text($password).', '.quote_text(PASSWORDS_ENCRYPT_KEY).') WHERE id = '.(int)$objLogin->GetLoggedID();
					}
				}
				if(database_void_query($sql)){
					$msg .= draw_success_message(_PASSWORD_CHANGED, false);
				}else{
					$msg .= draw_important_message(_PASSWORD_NOT_CHANGED, false);
				}								
			}else $msg .= draw_important_message(_PASSWORD_DO_NOT_MATCH, false);
		}else $msg .= draw_important_message(_PASSWORD_IS_EMPTY, false);

		return $msg;		
	}
	
	/**
	 * Get number of doctors awaiting aproval
	 */
	public static function AwaitingAprovalCount()
	{
		$sql = 'SELECT COUNT(*) as cnt FROM '.TABLE_DOCTORS.' WHERE is_active = 0 AND registration_code != \'\'';
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			return $result[0]['cnt'];
		}
		return '0';
	}
	
	/**
	 * Checks if doctor has appropriate schedule
	 * 		@param $doctor_id
	 * 		@param $scid
	 */
	public static function DoctorHasSchedule($doctor_id, $scid)
	{
		$sql = 'SELECT COUNT(*) as cnt
				FROM '.TABLE_SCHEDULES.'
				WHERE
					id = '.(int)$scid.' AND 
					doctor_id = '.(int)$doctor_id;
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			return $result[0]['cnt'];
		}
		return '0';
	}
	
	/**
	 * Draws doctor search HTML form
	 * 		@param $params
	 * 		@param $draw
	 */
	public static function DrawDoctorFindForm($params = array())
	{
		$doctor_speciality = isset($params['doctor_speciality']) ? $params['doctor_speciality'] : '';
		$draw = isset($params['draw']) ? (bool)$params['draw'] : true;
		
		$output = '<form action="index.php?page=find_doctors" id="frmFindDoctors" name="frmFindDoctors" method="post">
			'.draw_hidden_field('p', '1', false, 'page_number').'
			'.draw_hidden_field('start_day', date('Y-m-d'), false, 'start_day').'
            '.draw_hidden_field('docid', '', false, 'docid').'
			'.draw_hidden_field('doctor_speciality', $doctor_speciality, false, 'doctor_speciality').'
			'.draw_token_field(false);
		$output .= '</form>';
		
		if($draw) echo $output;
		else return $output;
	}
    
	/**
	 * Draws doctors info
	 * 		@param $draw
	 */
	public static function DrawDoctorsInfo($draw = true)
	{        
        $output = '';

        //// -------- pagination
        $current_page = isset($_GET['p']) ? abs((int)$_GET['p']) : '1';
        $total_doctors = 0;
        $page_size = 10;
		$sql = 'SELECT COUNT(*) as cnt FROM '.TABLE_DOCTORS.' WHERE membership_show_in_search = 1 && is_active = 1';
		$doctors = database_query($sql, DATA_ONLY, FIRST_ROW_ONLY);		
        
        $total_doctors = isset($doctors['cnt']) ? (int)$doctors['cnt'] : 0;
        $total_pages = (int)($total_doctors / $page_size);
        
        if($current_page > ($total_pages+1)) $current_page = 1;
        if(($total_doctors % $page_size) != 0) $total_pages++;
        if(!is_numeric($current_page) || (int)$current_page <= 0) $current_page = 1;
        if($total_pages == 1 && $current_page > 1) $current_page = 1;
        
        $start_row = ($current_page - 1) * $page_size;
        //// --------
        $doctors = self::GetAllActive('membership_show_in_search = 1', 'LIMIT '.$start_row.', '.$page_size);
        
        if($doctors[1] > 0){
            for($i=0; $i<$doctors[1]; $i++){
                $doctor_result = self::DrawDoctorInfo($doctors[0][$i]['id'], 'short', 'demo', $doctors[0][$i], false);
                $output .= ($doctor_result != '') ? $doctor_result.draw_line('no_margin_line', IMAGE_DIRECTORY, false) : '';          
            }
            $url = 'index.php?page='.Application::Get('page');
            $url .= ((Application::Get('page_id') != '') ? '&pid='.Application::Get('page_id') : '');
            $url .= ((Application::Get('system_page') != '') ? '&system_page='.Application::Get('system_page') : '');
            $output .= '<div class="pagging">'.pagination_get_links($total_pages, $url).'</div>';
        }else{
            $output .= _WRONG_PARAMETER_PASSED;
        }
        
		if($draw) echo $output;
		else return $output;
    }
    
	/**
	 * Sets membership info for doctor
	 * 		@param $doctor_id
	 * 		@param $membership_plan_id
	 * 		@param $operation
	 */
	public static function SetMembershipInfoForDoctor($doctor_id = 0, $membership_plan_id = 0, $operation = '')
	{
        if($operation == '+'){
            $membership_plan = MembershipPlans::GetPlanInfo($membership_plan_id);
            if(!empty($membership_plan)){
                $membership_plan['expires'] = (($membership_plan['duration'] > -1) ? date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + $membership_plan['duration'], date('Y'))) : null);
                self::UpdateMembershipInfo($membership_plan, 'id = '.(int)$doctor_id);
            }            
        }else{
            self::UpdateMembershipInfo(array(), 'id = '.(int)$doctor_id);           
        }
    }
    
	/**
	 * Updates membership info for doctor
	 * 		@param $params
	 * 		@param $where_clause
	 */
	public static function UpdateMembershipInfo($params = array(), $where_clause = '')
	{
        $plan_id = isset($params['id']) ? $params['id'] : 0;
        $images_count = isset($params['images_count']) ? $params['images_count'] : 0;
        $addresses_count = isset($params['addresses_count']) ? $params['addresses_count'] : 0;
        $show_in_search = isset($params['show_in_search']) ? $params['show_in_search'] : 0;
        $membership_expires = isset($params['expires']) ? $params['expires'] : null;
        
        $sql = "UPDATE ".TABLE_DOCTORS."
                SET membership_plan_id = ".(int)$plan_id.",
                    membership_images_count = ".(int)$images_count.",
                    membership_addresses_count = ".(int)$addresses_count.",
                    membership_show_in_search = ".(int)$show_in_search.",
                    membership_expires = ".(!empty($membership_expires) ? "'".$membership_expires."'" : 'NULL')."
                WHERE 1=1 ".(($where_clause != '') ? ' AND '.$where_clause : '');
        return database_void_query($sql);        
    }
    
	/**
	 * Checks if a doctor has open (unpaid) online order
	 * 		@param $doctor_id
	 */
	public static function HasOpenOnlineOrder($doctor_id = 0)
	{
		$sql = 'SELECT
                    o.id
                FROM '.TABLE_DOCTORS.' d
                    INNER JOIN '.TABLE_ORDERS.' o ON d.id = o.doctor_id  
                WHERE
                    o.doctor_id = '.(int)$doctor_id.' AND 
                    o.payment_type = 0 AND
                    o.status = 1 ';
		$orders = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
        return ($orders[1] > 0) ? true : false;        
    }
    

	/**
	 *	Get doctors by seciality
	 *		@param $params
	 */
	public static function GetDoctorsByParam($params)
	{
		$speciality = isset($params['speciality']) ? $params['speciality'] : '';
		$name = isset($params['name']) ? trim($params['name']) : '';
		$name_parts = explode(' ', $name);
		$name_part1 = isset($name_parts[0]) ? $name_parts[0] : '';
		$name_part2 = isset($name_parts[1]) ? $name_parts[1] : '';
		$location = isset($params['location']) ? $params['location'] : '';
        $doctor_id = isset($params['doctor_id']) ? $params['doctor_id'] : '';
		
		$sql = 'SELECT
					d.id as doctor_id,
					d.first_name,
					d.middle_name,
					d.last_name,
					d.gender,
					d.title,
					d.medical_degree,
					d.doctor_photo,
					d.doctor_photo_thumb,
                    d.membership_plan_id,
                    d.membership_images_count,
                    d.membership_addresses_count,
                    d.membership_show_in_search,
					sd.id as speciality_id,					
					sd.name as speciality_name
					'.(!empty($location) ? ', da.address' : ', "" as address').'
					'.(!empty($location) ? ', da.id as address_id' : ', "" as address_id').'
				FROM '.TABLE_DOCTORS.' d
					INNER JOIN '.TABLE_DOCTOR_SPECIALITIES.' ds ON d.id = ds.doctor_id
					INNER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' sd ON ds.speciality_id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\'
					'.(!empty($location) ? ' INNER JOIN '.TABLE_DOCTOR_ADDRESSES.' da ON d.id = da.doctor_id' : '').'
				WHERE
                    d.membership_show_in_search = 1 AND
                    d.is_active = 1
					'.(($doctor_id != '') ? ' AND d.id = '.(int)$doctor_id : '').'
				    '.(!empty($speciality)  ? ' AND ds.speciality_id = '.(int)$speciality : '').'
					'.(!empty($name) ? ' AND (
						d.last_name LIKE \'%'.$name_part1.'%\'
						'.($name_part2 != '' ? ' OR d.last_name LIKE \'%'.$name_part2.'%\'' : '').' 
						OR d.first_name LIKE \'%'.$name_part1.'%\'
						'.($name_part2 != '' ? ' OR d.first_name LIKE \'%'.$name_part2.'%\'' : '').' 
					) ' : '').'
					'.(!empty($location)  ? ' AND da.address LIKE \'%'.$location.'%\'' : ''); 
                ///'.(!empty($location)  ? ' AND d.membership_addresses_count < 0' : '').'
					
		return database_query($sql, DATA_AND_ROWS);					
	}
	
	/**
	 * Find next appointment for given doctor
	 * 		@param $doc_id
	 * 		@param $date_from
	 */
	private static function FindNextAppointment($doc_id, $date_from = '')
	{        
        $msg = '';
        $date_from = ($date_from != '') ? $date_from : date('Y-m-d');
        $arr_weekdays_en = array('1'=>'Sunday','2'=>'Monday','3'=>'Tuesday','4'=>'Wednesday','5'=>'Thursday','6'=>'Friday','7'=>'Saturday');
		$arr_weekdays_loc = array('1'=>_SUNDAY, '2'=>_MONDAY, '3'=>_TUESDAY, '4'=>_WEDNESDAY, '5'=>_THURSDAY, '6'=>_FRIDAY, '7'=>_SATURDAY);
        
        // check if there are schedules at all
		$sql = 'SELECT * FROM '.TABLE_SCHEDULES.' WHERE date_to >= \''.$date_from.'\' AND doctor_id = '.(int)$doc_id.' ORDER BY date_from ASC LIMIT 0, 1';
        $result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
        if($result[1] > 0){
            // check if there is schedule in the nearest 2 months
            $datediff = round(time_diff($result[0]['date_from'], $date_from) / 86400);
            if($datediff < 60){
                $sql = 'SELECT * FROM '.TABLE_SCHEDULE_TIMEBLOCKS.' WHERE schedule_id = '.(int)$result[0]['id'].' ORDER BY week_day ASC LIMIT 0, 1';
                $result_day = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
                if($result_day[1] > 0){
                    $next_date = date('Y-m-d', strtotime('next '.$arr_weekdays_en[(int)$result_day[0]['week_day']], strtotime($result[0]['date_from'])));
                    $msg = draw_message(_NEXT_APPOINTMENT_AT.' '.format_date($next_date, '', '', true).' ('.$arr_weekdays_loc[(int)$result_day[0]['week_day']].')', false);   
                }else{
                    $msg = draw_message(_NEXT_APPOINTMENT_AT.' '.$result[0]['date_from'], false);   
                }
            }else{
                $msg = draw_message(_NEXT_APPT_IN_2_MONTHS, false);
            }
        }else{
            $msg = draw_message(_NO_APPOINTMENTS_AVAILABLE, false);
        }
        
        return $msg;
    }
		
}

?>