<?php

/**
 *	Class Appointments
 *  --------------
 *	Description : encapsulates methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 20.09.2013
 *  Usage       : MedicalAppointment
 *
 *	PUBLIC:				  	STATIC:				 		PRIVATE:
 * 	------------------	  	---------------     		---------------
 *	__construct             GetStaticMessage
 *	__destruct              VerifyAppointment
 *  BeforeUpdateRecord      DrawVerifyAppointment
 *  AfterUpdateRecord       DrawAppointmentDetails 
 *  BeforeDeleteRecord      DrawAppointmentSignIn
 *  ApproveAppointment      DrawAppointmentAssignPatient
 *	CancelAppointment       DoAppointment
 *	                        AwaitingApprovalCount
 *	                        SendAppointmentEmail
 *	                        RemoveExpired
 *	                        SendReminders
 *	                        DrawAppointmentsByDate
 *	                        DrawAppointmentTable (private)
 *      
 **/


class Appointments extends MicroGrid {
	
	protected $debug = false;
	
	private static $static_message = '';
    private static $patientId = '';
    private static $selectedUser = '';
	private $status = '0';
	private $sqlFieldDateFormat = '';
    private $sqlFieldDatetimeFormat = '';
	
	//==========================================================================
    // Class Constructor
	//==========================================================================
	function __construct()
	{		
		parent::__construct();
		
		global $objLogin, $objSettings;
		
		$this->params = array();
		
		## for standard fields
		if(isset($_POST['status']))       $this->params['status'] = prepare_input($_POST['status']);
		if(isset($_POST['doctor_notes'])) $this->params['doctor_notes'] = prepare_input($_POST['doctor_notes']);
		if(isset($_POST['patient_notes'])) $this->params['patient_notes'] = prepare_input($_POST['patient_notes']);
		
		## for checkboxes 
		//$this->params['field4'] = isset($_POST['field4']) ? prepare_input($_POST['field4']) : '0';

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
		
		//$this->params['language_id'] = MicroGrid::GetParameter('language_id');	
		//$this->uPrefix = 'prefix_';		
		
        $allow_deleting = false;
		if($objLogin->IsLoggedInAsPatient()){
			$is_exporting_allowed = false;
			$access_by_usertype = true;
            $access_filter_status = false;
			$show_doctor_column = true;
			$show_patient_column = false;
			$show_country_column = false;
            $allow_deleting = false;
		}else if($objLogin->IsLoggedInAsDoctor()){
			$is_exporting_allowed = false;
			$access_by_usertype = true;
            $access_filter_status = true;
			$show_doctor_column = false;
			$show_patient_column = true;
			$show_country_column = true;
            $allow_deleting = false;
		}else{
			$is_exporting_allowed = true;
			$access_by_usertype = true;
            $access_filter_status = true;
			$show_doctor_column = true;
			$show_patient_column = true;
			$show_country_column = true;
            if($objLogin->GetLoggedType() == 'owner') $allow_deleting = true;
		}        
		
		$change_appt_in_past = true;
		$rid = MicroGrid::GetParameter('rid');
		
		$this->primaryKey 	= 'id';
		$this->tableName 	= TABLE_APPOINTMENTS; 
		$this->dataSet 		= array();
		$this->error 		= '';
		if($objLogin->IsLoggedInAsPatient()){
			$this->formActionURL = 'index.php?patient=my_appointments';		
		}else if($objLogin->IsLoggedInAsDoctor()){
			$this->formActionURL = 'index.php?doctor=appointments';		
		}else{
			$this->formActionURL = 'index.php?admin=mod_appointments_management';		
		}
		$this->actions      = array('add'=>false, 'edit'=>$access_by_usertype, 'details'=>true, 'delete'=>$allow_deleting);
		$this->actionIcons  = true;
		$this->allowRefresh = true;
        $this->allowPrint = true;
		$this->allowTopButtons = true;
		$this->alertOnDelete = ''; // leave empty to use default alerts

		$this->allowLanguages = false;
		//$this->languageId  	= ($this->params['language_id'] != '') ? $this->params['language_id'] : Languages::GetDefaultLang();
		if($objLogin->IsLoggedInAsPatient()){
			$this->WHERE_CLAUSE = 'WHERE '.$this->tableName.'.patient_id = '.(int)$objLogin->GetLoggedID().' AND
										('.$this->tableName.'.status != 2 OR status = 2 AND appointment_date >= \''.date('Y-m-d').'\') ';
			$doctor_notes_readonly = true;
			$patient_notes_readonly = false;
			$reminders_visible = false;
            $createdby_visible = false;
		}else if($objLogin->IsLoggedInAsDoctor()){
			$this->WHERE_CLAUSE = 'WHERE '.$this->tableName.'.doctor_id = '.(int)$objLogin->GetLoggedID().' AND 
			                            ('.$this->tableName.'.status != 2 OR status = 2 AND appointment_date >= \''.date('Y-m-d').'\') ';
			$doctor_notes_readonly = false;
			$patient_notes_readonly = true;
			$reminders_visible = false;
            $createdby_visible = true;
		}else{
			$this->WHERE_CLAUSE = '';
			$doctor_notes_readonly = false;
			$patient_notes_readonly = false;
			$reminders_visible = true;
            $createdby_visible = true;
		}		
		$this->ORDER_CLAUSE = 'ORDER BY '.$this->tableName.'.appointment_date DESC, '.$this->tableName.'.appointment_time DESC'; 
		
		$this->isAlterColorsAllowed = true;

		$this->isPagingAllowed = true;
		$this->pageSize = 30;

		$this->isSortingAllowed = true;

		$this->isExportingAllowed = $is_exporting_allowed;
		$this->arrExportingTypes = array('csv'=>true);
		
		// prepare doctors array		
		$total_doctors = Doctors::GetAllActive();
		$arr_doctors = array();
		foreach($total_doctors[0] as $key => $val){
			$arr_doctors[$val['id']] = $val['first_name'].' '.$val['middle_name'].' '.$val['last_name'];
		}
		// prepare array for whom
		$arr_for_whom = array('0'=>_FOR_ME, '1'=>_SOMEONE_ELSE);
        // prepare array for forst visit
		$arr_first_visit = array('0'=>_NO.' - '._I_EXISTING_PATIENT, '1'=>_YES.' - '._I_NEW_PATIENT);
        // prepare array insurances
        $arr_insurances = array();
        $insurances = Insurances::GetAllActive();
        for($i=0; $i<$insurances[1]; $i++){
            $arr_insurances[$insurances[0][$i]['id']] = $insurances[0][$i]['name'];
        }
        // prepare array visit reasons
        $arr_visit_reasons = array();
        $visit_reasons = VisitReasons::GetAllActive();
        for($i=0; $i<$visit_reasons[1]; $i++){
            $arr_visit_reasons[$visit_reasons[0][$i]['id']] = $visit_reasons[0][$i]['name'];
        }

		$arr_is_sent_vm = array('0'=>'<span style="color:#969696">--</span>', '1'=>'<span style="color:#009600">'._SENT.'</span>');		
		$arr_is_sent = array('0'=>'<span style="color:#969696">'._NO.'</span>', '1'=>'<span style="color:#009600">'._SENT.'</span>');		
        $arr_created_by = array('admin'=>_ADMIN, 'doctor'=>_DOCTOR, 'patient'=>_PATIENT);
        $verifies_title = $objLogin->IsLoggedInAsDoctor() ? _APPROVED : _VERIFIED;
        // prepare statuses array		
		$arr_statuses_view = array('0'=>'<span style="color:#a3a300">'._RESERVED.'</span>',
								   '1'=>'<span style="color:#00a300">'.$verifies_title.'</span>',
							       '2'=>'<span style="color:#939393">'._CANCELED.'</span>');
		
		$arr_statuses_edit = array('0'=>'<span style="color:#a3a300">'._RESERVED.'</span>',
								   '1'=>'<span style="color:#00a300">'.$verifies_title.'</span>',
								   '2'=>'<span style="color:#939393">'._CANCELED.'</span>');
		
		// prepare trigger
		$sql = 'SELECT status, appointment_date FROM '.$this->tableName.' WHERE id = '.(int)$rid;
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			if($result[0]['status'] == '1'){
				$arr_statuses_edit = array('1'=>'<style="color:#00a300">'.$verifies_title.'</span>',
								           '2'=>'<style="color:#939393">'._CANCELED.'</span>');
			}else if($result[0]['status'] == '2'){
				$arr_statuses_edit = array('2'=>'<style="color:#939393">'._CANCELED.'</span>');				
			}else{
				if($objLogin->IsLoggedInAsPatient()){					
					$arr_statuses_edit = array('0'=>'<span style="color:#a3a300">'._RESERVED.'</span>',
											   '2'=>'<style="color:#939393">'._CANCELED.'</span>');
				}				
			}
			if($result[0]['appointment_date'] < date('Y-m-d')){
				$change_appt_in_past = false;
			}
		}
		
		///$this->isAggregateAllowed = false;
		///// define aggregate fields for View Mode
		///$this->arrAggregateFields = array(
		///	'field1' => array('function'=>'SUM'),
		///	'field2' => array('function'=>'AVG'),
		///);

		$date_format = get_date_format('view');
		$date_format_settings = get_date_format('view', true);
		///$date_format_edit = get_date_format('edit');
		///$datetime_format = get_datetime_format();
		$time_format = get_time_format(false);
		if($time_format == 'H:i'){
			$sql_time_format = '%H:%i';
		}else{
			$sql_time_format = '%l:%i %p';
		}
		///$currency_format = get_currency_format();
		$currency_symbol = Application::Get('currency_symbol');

		// define filtering fields
		$this->isFilteringAllowed = true;
		$this->arrFilteringFields = array(
			'#'      => array('table'=>TABLE_APPOINTMENTS, 'field'=>'appointment_number', 'type'=>'text', 'sign'=>'like%', 'width'=>'90px', 'visible'=>$access_by_usertype),
			_STATUS  => array('table'=>TABLE_APPOINTMENTS, 'field'=>'status', 'type'=>'dropdownlist', 'source'=>$arr_statuses_view, 'sign'=>'=', 'width'=>'', 'visible'=>$access_filter_status),
			_DOCTOR  => array('table'=>TABLE_DOCTORS, 'field'=>'id', 'type'=>'dropdownlist', 'source'=>$arr_doctors, 'sign'=>'=', 'width'=>'150px', 'visible'=>$show_doctor_column),
			_PATIENT => array('table'=>TABLE_PATIENTS, 'field'=>'last_name', 'type'=>'text', 'sign'=>'%like%', 'width'=>'90px', 'visible'=>$show_patient_column),
			_DATE    => array('table'=>TABLE_APPOINTMENTS, 'field'=>'appointment_date', 'type'=>'calendar', 'date_format'=>$date_format_settings, 'sign'=>'=', 'width'=>'80px', 'visible'=>true),
		);

		if($objSettings->GetParameter('date_format') == 'mm/dd/yyyy'){
			$this->sqlFieldDateFormat = '%b %d, %Y';
            $this->sqlFieldDatetimeFormat = '%b %d, %Y %H:%i';
		}else{
			$this->sqlFieldDateFormat = '%d %b, %Y';
            $this->sqlFieldDatetimeFormat = '%d %b, %Y %H:%i';
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
									'.$this->tableName.'.appointment_number,
									'.$this->tableName.'.appointment_description,
									'.$this->tableName.'.doctor_id,
									'.$this->tableName.'.patient_id,
									DATE_FORMAT('.$this->tableName.'.appointment_date, \''.$this->sqlFieldDateFormat.'\') as appointment_date,
									DATE_FORMAT('.$this->tableName.'.appointment_time, "'.$sql_time_format.'") as appointment_time,
									'.$this->tableName.'.visit_duration,
									'.$this->tableName.'.visit_price,
									'.$this->tableName.'.doctor_notes,
									'.$this->tableName.'.patient_notes,
									'.$this->tableName.'.status,
									'.$this->tableName.'.status_changed,
									'.$this->tableName.'.p_arrival_reminder_sent,
									'.$this->tableName.'.p_confirm_reminder_sent,
									'.$this->tableName.'.d_confirm_reminder_sent,
									(SELECT
											a.appointment_date
										FROM '.$this->tableName.' a
										WHERE
											a.patient_id = '.$this->tableName.'.patient_id AND 
											a.appointment_number != '.$this->tableName.'.appointment_number AND
											a.appointment_date < \''.date('Y-m-d H:i:s').'\' AND
											a.appointment_date < '.$this->tableName.'.appointment_date AND
											a.status = 1
										ORDER BY a.appointment_date DESC
										LIMIT 0, 1
									) as last_appointment_date,
									CONCAT("<a href=\"javascript:void(\'doctor|view\')\" onclick=\"appAjaxPopupWindow(\'popup.ajax.php\',\'doctor\',\'", '.TABLE_DOCTORS.'.id, "\',\''.Application::Get('token').'\',\''.Application::Get('lang_dir').'\')\">", '.TABLE_DOCTORS.'.first_name, " ", '.TABLE_DOCTORS.'.middle_name, " ", '.TABLE_DOCTORS.'.last_name, "</a><br><span class=gray>", IF(sd.name IS NOT NULL, sd.name, ""), "</span>") as doctor_name,
                                    IF('.$this->tableName.'.patient_id = \'\', "{administrator}", CONCAT("<a href=\"javascript:void(\'patient|view\')\" onclick=\"appAjaxPopupWindow(\'popup.ajax.php\',\'patient\',\'", '.TABLE_PATIENTS.'.id, "\',\''.Application::Get('token').'\',\''.Application::Get('lang_dir').'\')\">", '.(PATIENTS_ENCRYPTION ? 'AES_DECRYPT('.TABLE_PATIENTS.'.first_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : TABLE_PATIENTS.'.first_name').', " ", '.(PATIENTS_ENCRYPTION ? 'AES_DECRYPT('.TABLE_PATIENTS.'.last_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : TABLE_PATIENTS.'.last_name').', "</a>")) as patient_name,
                                    CONCAT("<img src=\"images/appointment.png\" alt=\"icon\" />") link_new_appointment,
                                    IF('.$this->tableName.'.status != 2, CONCAT("<img src=\"images/cancel.png\" alt=\"icon\" />"), "") link_cancel_appointment,
                                    IF('.$this->tableName.'.status = 0, CONCAT("<img src=\"images/approve.png\" alt=\"icon\" />"), "") link_approve_appointment,
									'.TABLE_PATIENTS.'.b_country
								FROM '.$this->tableName.'
									INNER JOIN '.TABLE_DOCTORS.' ON '.$this->tableName.'.doctor_id = '.TABLE_DOCTORS.'.id
									LEFT OUTER JOIN '.TABLE_PATIENTS.' ON '.$this->tableName.'.patient_id = '.TABLE_PATIENTS.'.id
									LEFT OUTER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' sd ON '.$this->tableName.'.doctor_speciality_id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\'
								';		
		// define view mode fields
		$this->arrViewModeFields = array(
			'appointment_date'   => array('title'=>_DATE, 'type'=>'label', 'align'=>'left', 'width'=>'80px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>'', 'maxlength'=>'', 'format'=>'date', 'format_parameter'=>$date_format),
			'appointment_time'   => array('title'=>_TIME, 'type'=>'time', 'align'=>'left', 'width'=>'60px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>'', 'maxlength'=>'', 'format'=>'time', 'format_parameter'=>''),
			'last_appointment_date' => array('title'=>'', 'type'=>'label', 'visible'=>false),
			'doctor_name'        => array('title'=>_DOCTOR, 'type'=>'label', 'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>$show_doctor_column, 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>''),
			//'specialty_name'     => array('title'=>_SPECIALITY, 'type'=>'label', 'align'=>'center', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>''),
			'patient_name'       => array('title'=>_PATIENT, 'type'=>'label', 'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>$show_patient_column, 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>''),
			'b_country'          => array('title'=>_COUNTRY, 'type'=>'label', 'align'=>'center', 'width'=>'', 'height'=>'', 'maxlength'=>'', 'visible'=>$show_country_column),			
			'appointment_number' => array('title'=>_APPOINTMENT_NUMBER, 'type'=>'label', 'align'=>'center', 'width'=>'120px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>''),
			
			'p_arrival_reminder_sent' => array('title'=>'PA', 'header_tooltip'=>_PATIENT_ARRIVAL_REMINDER_EMAIL, 'type'=>'enum',  'align'=>'center', 'width'=>'35px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>$reminders_visible, 'source'=>$arr_is_sent_vm),
			'p_confirm_reminder_sent' => array('title'=>'PC', 'header_tooltip'=>_PATIENT_CONFIRM_REMINDER_EMAIL, 'type'=>'enum',  'align'=>'center', 'width'=>'35px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>$reminders_visible, 'source'=>$arr_is_sent_vm),
			'd_confirm_reminder_sent' => array('title'=>'DC', 'header_tooltip'=>_DOCTOR_ARRIVAL_REMINDER_EMAIL, 'type'=>'enum',  'align'=>'center', 'width'=>'35px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>$reminders_visible, 'source'=>$arr_is_sent_vm),

			'status'             => array('title'=>_STATUS, 'type'=>'enum',  'align'=>'center', 'width'=>'90px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_statuses_view),
			//'link_new_appointment' => array('title'=>'', 'type'=>'label', 'visible'=>false),
			//'visit_duration'     => array('title'=>_VISIT_DURATION, 'type'=>'label', 'align'=>'center', 'width'=>'120px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>'', 'post_html'=>' '._MIN.' '),
		);
		if($objLogin->GetLoggedType() == 'doctor'){
			$this->arrViewModeFields['link_approve_appointment'] = array('title'=>'', 'type'=>'link',  'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>_APPROVE, 'maxlength'=>'', 'href'=>'javascript:approveAppointment({id})', 'target'=>'');
			$this->arrViewModeFields['link_cancel_appointment'] = array('title'=>'', 'type'=>'link',  'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>_CANCEL_APPOINTMENT, 'maxlength'=>'', 'href'=>'javascript:cancelAppointment({id})', 'target'=>'');
        }
		if($objLogin->GetLoggedType() == 'patient'){
			$this->arrViewModeFields['last_appointment_date'] = array('title'=>_LAST_VISIT, 'type'=>'label', 'align'=>'left', 'width'=>'80px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>'', 'maxlength'=>'', 'format'=>'date', 'format_parameter'=>$date_format, 'pre_html'=>'<span class=gray>', 'post_html'=>'</span>');
			$this->arrViewModeFields['doctor_name'] = array('title'=>_DOCTOR, 'type'=>'link',  'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>_CLICK_TO_VIEW, 'maxlength'=>'', 'href'=>'index.php?page=doctors&docid={doctor_id}', 'target'=>'');
			$this->arrViewModeFields['link_new_appointment'] = array('title'=>'', 'type'=>'link',  'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>_NEW_APPOINTMENT, 'maxlength'=>'', 'href'=>'index.php?page=find_doctors&docid={doctor_id}', 'target'=>'');
			$this->arrViewModeFields['link_cancel_appointment'] = array('title'=>'', 'type'=>'link',  'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>_CANCEL_APPOINTMENT, 'maxlength'=>'', 'href'=>'javascript:cancelAppointment({id})', 'target'=>'');
		}


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
										
		);

		//---------------------------------------------------------------------- 
		// EDIT MODE
		// - Validation Type: alpha|numeric|float|alpha_numeric|text|email|ip_address|password|date
		//   Validation Sub-Type: positive (for numeric and float)
		//   Ex.: 'validation_type'=>'numeric', 'validation_type'=>'numeric|positive'
		// - Validation Max Length: 12, 255... Ex.: 'validation_maxlength'=>'255'
		// - Validation Min Length: 4, 6... Ex.: 'validation_minlength'=>'4'
		// - Validation Max Value: 12, 255... Ex.: 'validation_maximum'=>'99.99'
		// - for editable passwords they must be defined directly in SQL : '.$this->tableName.'.user_password,
		//---------------------------------------------------------------------- 
		$this->EDIT_MODE_SQL = 'SELECT
								'.$this->tableName.'.'.$this->primaryKey.',
								'.$this->tableName.'.appointment_number,
								'.$this->tableName.'.appointment_number as mod_appointment_number,
								'.$this->tableName.'.appointment_description,
								'.$this->tableName.'.doctor_id,
								'.$this->tableName.'.doctor_speciality_id,
								'.$this->tableName.'.patient_id,
								'.$this->tableName.'.date_created,
								DATE_FORMAT('.$this->tableName.'.date_created, \''.$this->sqlFieldDatetimeFormat.'\') as mod_date_created,
								DATE_FORMAT('.$this->tableName.'.appointment_date, \''.$this->sqlFieldDateFormat.'\') as mod_appointment_date,
								'.$this->tableName.'.appointment_time,
								DATE_FORMAT('.$this->tableName.'.appointment_time, "'.$sql_time_format.'") as mod_appointment_time,
								'.$this->tableName.'.visit_duration,								
								'.$this->tableName.'.doctor_notes,
								'.$this->tableName.'.patient_notes,
								'.$this->tableName.'.for_whom,
								'.$this->tableName.'.first_visit,
                                '.$this->tableName.'.insurance_id,
                                '.$this->tableName.'.visit_reason_id,
								'.$this->tableName.'.status,
								'.$this->tableName.'.status_changed,
                                '.$this->tableName.'.created_by,
								'.$this->tableName.'.p_arrival_reminder_sent,
								'.$this->tableName.'.p_confirm_reminder_sent,
								'.$this->tableName.'.d_confirm_reminder_sent,
								CONCAT(d.title, d.first_name, " ", d.middle_name, " ", d.last_name) as doctor_name,
								CONCAT(d.work_phone, IF(d.work_phone != "", ", ", ""), d.work_mobile_phone) as doctor_phones,
								d.fax as doctor_fax,
								d.medical_degree as doctor_degree,
								'.(PATIENTS_ENCRYPTION ? 'CONCAT(AES_DECRYPT(p.first_name, "'.PASSWORDS_ENCRYPT_KEY.'"), " ", AES_DECRYPT(p.last_name, "'.PASSWORDS_ENCRYPT_KEY.'"))' : 'CONCAT(p.first_name, " ", p.last_name)').' as patient_name,
								'.(PATIENTS_ENCRYPTION ? 'AES_DECRYPT(p.phone, "'.PASSWORDS_ENCRYPT_KEY.'")' : 'p.phone').' as patient_phone,
								CONCAT("<a href=mailto:", p.email, ">", p.email, "</a>") as patient_email,
                                IF(ds.visit_price IS NOT NULL, ds.visit_price, '.$this->tableName.'.visit_price) as visit_price,
                                sd.name as specialty_name,
								da.address as doctor_address
							FROM '.$this->tableName.'
								INNER JOIN '.TABLE_DOCTORS.' as d ON '.$this->tableName.'.doctor_id = d.id
								LEFT OUTER JOIN '.TABLE_DOCTOR_ADDRESSES.' da ON '.$this->tableName.'.doctor_address_id = da.id
								LEFT OUTER JOIN '.TABLE_PATIENTS.' as p ON '.$this->tableName.'.patient_id = p.id
								LEFT OUTER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' as sd ON '.$this->tableName.'.doctor_speciality_id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\'
                                LEFT OUTER JOIN '.TABLE_DOCTOR_SPECIALITIES.' ds ON '.$this->tableName.'.doctor_id = ds.doctor_id AND '.$this->tableName.'.doctor_speciality_id = ds.speciality_id
							WHERE '.$this->tableName.'.'.$this->primaryKey.' = _RID_';		
		// define edit mode fields
		$this->arrEditModeFields = array(		
			'separator_appointment_info' => array(
				'separator_info' => array('legend'=>_APPOINTMENT_INFO, 'columns'=>'0'),
				'mod_appointment_date'  => array('title'=>_DATE, 'type'=>'label'),
				'mod_appointment_time'  => array('title'=>_TIME, 'type'=>'label', 'format'=>'time'),
				'mod_appointment_number'    => array('title'=>_APPOINTMENT_NUMBER, 'type'=>'label'),
				'appointment_number'    => array('title'=>'', 'type'=>'hidden', 'default'=>''),
				'appointment_description' => array('title'=>_DESCRIPTION, 'type'=>'label'),
				'doctor_address'        => array('title'=>_WHERE, 'type'=>'label'),			
				'for_whom' 			    => array('title'=>_FOR_WHOM, 'type'=>'enum', 'source'=>$arr_for_whom, 'view_type'=>'label'),				
				'first_visit' 			=> array('title'=>_FIRST_VISIT, 'type'=>'enum', 'source'=>$arr_first_visit, 'view_type'=>'label'),				
				'insurance_id' 			=> array('title'=>_INSURANCE, 'type'=>'enum', 'source'=>$arr_insurances, 'view_type'=>'label'),				
				'visit_reason_id' 	    => array('title'=>_VISIT_REASON, 'type'=>'enum', 'source'=>$arr_visit_reasons, 'view_type'=>'label'),				
				'visit_duration' 	    => array('title'=>_VISIT_DURATION, 'type'=>'label', 'post_html'=>' '._MIN),
				'visit_price' 		    => array('title'=>_PRICE, 'type'=>'label', 'pre_html'=>$currency_symbol),
			),
			'separator_doctor_info' => array(
				'separator_info' => array('legend'=>_DOCTOR_INFO, 'columns'=>'0'),
				'doctor_name'    => array('title'=>_DOCTOR, 'type'=>'label'),
				'doctor_degree'  => array('title'=>_DEGREE, 'type'=>'label'),	
				'specialty_name' => array('title'=>_SPECIALITY, 'type'=>'label'),			
				'doctor_phones'  => array('title'=>_PHONES, 'type'=>'label'),
				'doctor_fax'     => array('title'=>_FAX, 'type'=>'label'),
			),
			'separator_patient_info' => array(
				'separator_info' => array('legend'=>_PATIENT_INFO, 'columns'=>'0'),
				'patient_name'   => array('title'=>_NAME, 'type'=>'label'),
				'patient_phone'  => array('title'=>_PHONE, 'type'=>'label'),
				'patient_email'  => array('title'=>_EMAIL, 'type'=>'label'),
			),
		    'separator_other' => array(
				'separator_info' => array('legend'=>_OTHER),
				'mod_date_created' => array('title'=>_DATE_CREATED, 'type'=>'label'),
				'created_by'     => array('title'=>_CREATED_BY, 'type'=>'enum', 'readonly'=>true, 'visible'=>$createdby_visible, 'source'=>$arr_created_by, 'view_type'=>'label'),
				'status'      	 => array('title'=>_STATUS, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>!$change_appt_in_past, 'default'=>'', 'source'=>$arr_statuses_edit, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
				'p_arrival_reminder_sent' => array('title'=>_ARRIVAL_REMINDER_SENT.' <br>('._FOR_PATIENT.')', 'type'=>'enum', 'source'=>$arr_is_sent, 'view_type'=>'label'),
				'p_confirm_reminder_sent' => array('title'=>_CONFIRM_REMINDER_SENT.' <br>('._FOR_PATIENT.')', 'type'=>'enum', 'source'=>$arr_is_sent, 'view_type'=>'label'),
				'd_confirm_reminder_sent' => array('title'=>_CONFIRM_REMINDER_SENT.' <br>('._FOR_DOCTOR.')', 'type'=>'enum', 'source'=>$arr_is_sent, 'view_type'=>'label'),
				'doctor_notes'   => array('title'=>_DOCTOR_NOTES, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'90px', 'editor_type'=>'simple', 'readonly'=>$doctor_notes_readonly, 'default'=>'', 'validation_maxlength'=>'2048', 'validation_type'=>'', 'unique'=>false),
				'patient_notes'  => array('title'=>_PATIENT_NOTES, 'type'=>'textarea', 'width'=>'410px', 'required'=>false, 'height'=>'90px', 'editor_type'=>'simple', 'readonly'=>$patient_notes_readonly, 'default'=>'', 'validation_maxlength'=>'2048', 'validation_type'=>'', 'unique'=>false),
			),			
		);

		//---------------------------------------------------------------------- 
		// DETAILS MODE
		//----------------------------------------------------------------------
		$this->DETAILS_MODE_SQL = $this->EDIT_MODE_SQL;
		$this->arrDetailsModeFields = array(
			'separator_appointment_info' => array(
				'separator_info' => array('legend'=>_APPOINTMENT_INFO, 'columns'=>'0'),
				'mod_appointment_date' => array('title'=>_DATE, 'type'=>'label'),
				'mod_appointment_time' => array('title'=>_TIME, 'type'=>'label', 'format'=>'time'),
				'appointment_number'   => array('title'=>_APPOINTMENT_NUMBER, 'type'=>'label'),
				'appointment_description' => array('title'=>_DESCRIPTION, 'type'=>'label'),
				'doctor_address'    => array('title'=>_WHERE, 'type'=>'label'),			
				'for_whom'          => array('title'=>_FOR_WHOM, 'type'=>'enum', 'source'=>$arr_for_whom),
				'first_visit' 		=> array('title'=>_FIRST_VISIT, 'type'=>'enum', 'source'=>$arr_first_visit),
                'insurance_id' 		=> array('title'=>_INSURANCE, 'type'=>'enum', 'source'=>$arr_insurances),
                'visit_reason_id'   => array('title'=>_VISIT_REASON, 'type'=>'enum', 'source'=>$arr_visit_reasons),				
				'visit_duration'    => array('title'=>_VISIT_DURATION, 'type'=>'label', 'post_html'=>' '._MIN),
				'visit_price'       => array('title'=>_PRICE, 'type'=>'label', 'pre_html'=>$currency_symbol),
			),
			'separator_doctor_info' => array(
				'separator_info' => array('legend'=>_DOCTOR_INFO, 'columns'=>'0'),
				'doctor_name'    => array('title'=>_DOCTOR, 'type'=>'label'),
				'doctor_degree'  => array('title'=>_DEGREE, 'type'=>'label'),	
				'specialty_name' => array('title'=>_SPECIALITY, 'type'=>'label'),
				'doctor_phones'  => array('title'=>_PHONES, 'type'=>'label'),
				'doctor_fax'     => array('title'=>_FAX, 'type'=>'label'),
			),
			'separator_patient_info' => array(
				'separator_info' => array('legend'=>_PATIENT_INFO, 'columns'=>'0'),
				'patient_name'   => array('title'=>_NAME, 'type'=>'label'),
				'patient_phone'  => array('title'=>_PHONE, 'type'=>'label'),
				'patient_email'  => array('title'=>_EMAIL, 'type'=>'label'),
			),
		    'separator_other' => array(
				'separator_info'  => array('legend'=>_OTHER),
				'mod_date_created' => array('title'=>_DATE_CREATED, 'type'=>'label'),
				'created_by'      => array('title'=>_CREATED_BY, 'type'=>'enum', 'visible'=>$createdby_visible, 'source'=>$arr_created_by),
				'status'          => array('title'=>_STATUS, 'type'=>'enum', 'source'=>$arr_statuses_edit),
				'p_arrival_reminder_sent' => array('title'=>_ARRIVAL_REMINDER_SENT.' <br>('._FOR_PATIENT.')', 'type'=>'enum', 'source'=>$arr_is_sent, 'view_type'=>'label'),
				'p_confirm_reminder_sent' => array('title'=>_CONFIRM_REMINDER_SENT.' <br>('._FOR_PATIENT.')', 'type'=>'enum', 'source'=>$arr_is_sent, 'view_type'=>'label'),
				'd_confirm_reminder_sent' => array('title'=>_CONFIRM_REMINDER_SENT.' <br>('._FOR_DOCTOR.')', 'type'=>'enum', 'source'=>$arr_is_sent, 'view_type'=>'label'),
				'doctor_notes'    => array('title'=>_DOCTOR_NOTES, 'type'=>'label'),
				'patient_notes'   => array('title'=>_PATIENT_NOTES, 'type'=>'label'),				
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
	 * Before Update
	 */
	public function BeforeUpdateRecord()
	{
	   	//$this->curRecordId - current record

		$sql = 'SELECT status FROM '.TABLE_APPOINTMENTS.' WHERE id = '.(int)$this->curRecordId;
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			$this->status = (int)$result[0]['status'];
		}
		
	   	return true;
	}
	 	
	/**
	 * After Update
	 */
	public function AfterUpdateRecord()
	{
		// $this->curRecordId - currently updated record
	    // $this->params - current record update info
		global $objSettings, $objLogin;
		
		$current_status = isset($this->params['status']) ? $this->params['status'] : '';
		$appt_number = isset($_POST['appointment_number']) ? prepare_input($_POST['appointment_number']) : '';
		
		$appointment_approved = ($this->status == '0' && $current_status == '1') ? true : false;
		$appointment_canceled = (($this->status == '0' || $this->status == '1') && $current_status == '2') ? true : false;
		
		if($appointment_approved){			
			////////////////////////////////////////////////////////////
			// send email to patient, admin and doctor here
			Appointments::SendAppointmentEmail('appointment_approved_by_administration', $appt_number);
			////////////////////////////////////////////////////////////
		}else if($appointment_canceled){
			////////////////////////////////////////////////////////////
			// send email to patient, admin and doctor here
			Appointments::SendAppointmentEmail('appointment_canceled', $appt_number);
			////////////////////////////////////////////////////////////
		}
	}

	/**
	 * Before Delete
	 */
	public function BeforeDeleteRecord()
	{
	    // $this->curRecordId - current record
		$appointment_info = $this->GetInfoByID($this->curRecordId);
		$date = isset($appointment_info['appointment_date']) ? $appointment_info['appointment_date'] : null;
		$time = isset($appointment_info['appointment_time']) ? $appointment_info['appointment_time'] : '00:00:00';
		
		if($date.' '.$time <= @date('Y-m-d H:i:s')){
			$this->error = _APPOINTMENT_DELETE_IN_PAST_ALERT;
			return false;	
		}
		return true;	
	}
    
    /**
     * Verifies the appointment
     *      @param $rid
     */
    public function ApproveAppointment($rid = 0)
    {
        $sql = 'UPDATE '.TABLE_APPOINTMENTS.' SET status = 1, status_changed = \''.date('Y-m-d H:i:s').'\' WHERE id = '.(int)$rid;
        return database_void_query($sql);
    }

    /**
     * Cancels the appointment
     *      @param $rid
     */
    public function CancelAppointment($rid = 0)
    {
        $sql = 'UPDATE '.TABLE_APPOINTMENTS.' SET status = 2, status_changed = \''.date('Y-m-d H:i:s').'\' WHERE id = '.(int)$rid;
        return database_void_query($sql);
    }


	//==========================================================================
    // Static Methods	
	//==========================================================================	
	/**
	 * Returns static message
	 */
	public static function GetStaticMessage()
	{
		return self::$static_message;
	}    

	/**
	 * Verify Appointment 
	 * 		@param $params
	 */
	public static function VerifyAppointment($params = true)
	{
        global $objLogin;
        $date_format = get_date_format();
		$docid = isset($params['docid']) ? (int)$params['docid'] : '';		
		$schid = isset($params['schid']) ? (int)$params['schid'] : '';
		$daddid = isset($params['daddid']) ? (int)$params['daddid'] : '';
		$date = isset($params['date']) ? $params['date'] : null;
		$start_time = isset($params['start_time']) ? str_replace('-', ':', $params['start_time']) : '00:00:00';
		if(strlen($start_time) == 5) $start_time .= ':00'; /* 20.09.2013 fix for issue with 1st block */		
		$duration = isset($params['duration']) ? (int)$params['duration'] : '';
		$dspecid = isset($params['dspecid']) ? (int)$params['dspecid'] : '';
        $insid = isset($params['insid']) ? (int)$params['insid'] : '';
        $vrid = isset($params['vrid']) ? (int)$params['vrid'] : '';
		$for_whom = isset($params['for_whom']) ? $params['for_whom'] : '';		
		$week_day = (date('w', strtotime($date.' '.$start_time)) + 1);
		
		// check if a doctor is available for this time
		$sql = 'SELECT
					sch.id,
					sch.date_from,
					sch.date_to,
					schtb.time_from,
					schtb.time_to,
					schtb.week_day
				FROM '.TABLE_SCHEDULES.' sch
					INNER JOIN '.TABLE_SCHEDULE_TIMEBLOCKS.' schtb ON sch.id = schtb.schedule_id
					'.(($dspecid != '') ? 'LEFT OUTER JOIN '.TABLE_DOCTOR_SPECIALITIES.' dspec ON sch.doctor_id = dspec.doctor_id AND dspec.speciality_id = '.(int)$dspecid : '').'
					'.(($daddid != '') ? 'LEFT OUTER JOIN '.TABLE_DOCTOR_ADDRESSES.' daddr ON sch.doctor_id = daddr.doctor_id AND daddr.id = '.(int)$daddid : '').'	
				WHERE
					sch.doctor_id = '.(int)$docid.' AND
					sch.id = '.(int)$schid.' AND
					(sch.date_from <= \''.$date.'\' AND \''.$date.'\' <= sch.date_to) AND
					schtb.week_day = '.$week_day.' AND
					(schtb.time_from <= \''.$start_time.'\' AND \''.$start_time.'\' < schtb.time_to) AND
					schtb.time_slots = '.(int)$duration.'';	
		if(database_query($sql, ROWS_ONLY) > 0){
            if($objLogin->IsLoggedInAsPatient() && ModulesSettings::Get('appointments', 'allow_multiple_appointments') != 'yes'){
                $sql = 'SELECT a.*, CONCAT(d.title, d.first_name, " ", d.middle_name, " ", d.last_name) as doctor_name
                        FROM '.TABLE_APPOINTMENTS.' a
                        INNER JOIN '.TABLE_DOCTORS.' d ON d.id = a.doctor_id
                        WHERE a.doctor_id = '.(int)$docid.' AND
                              a.patient_id = '.(int)$objLogin->GetLoggedID().' AND
                              a.appointment_date = \''.$date.'\'';
                $result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
                if($result[1] > 0){                    
                    self::$static_error = str_ireplace(array('_DOCTOR_', '_DATE_'), array('<b>'.$result[0]['doctor_name'].'</b>', '<b>'.date($date_format, strtotime($date)).'</b>'), _ALREADY_HAVE_APPOINTMENT);
                    return false;
                }                
            }
			return true;			
		}else{
            self::$static_error = _WRONG_PARAMETER_PASSED;
            return false;        
        }		
	}

	/**
	 * Draw Appointment Details
	 * 		@param $param
	 * 		@param $draw
	 */
	public static function DrawVerifyAppointment($params = array(), $draw = true)
	{
		global $objLogin;

		$date_format = get_date_format();
		$time_format = get_time_format(false);
		
		$docid = isset($params['docid']) ? $params['docid'] : '';		
		$schid = isset($params['schid']) ? $params['schid'] : '';
		$daddid = isset($params['daddid']) ? $params['daddid'] : '';
		$date = isset($params['date']) ? $params['date'] : '';
		$date_formatted = isset($params['date']) ? date($date_format, strtotime($params['date'])) : '';
		$week_day = get_weekday_local(date('w', strtotime($params['date']))+1);
		$start_time = isset($params['start_time']) ? $params['start_time'] : '';
		$start_time_formatted = isset($params['start_time']) ? date($time_format, strtotime(str_replace('-', ':', $params['start_time']).':00')) : '';
		$duration = isset($params['duration']) ? $params['duration'] : '';
		$dspecid = isset($params['dspecid']) ? $params['dspecid'] : '';
        $insid = isset($params['insid']) ? $params['insid'] : '';
        $vrid = isset($params['vrid']) ? $params['vrid'] : '';
		$for_whom = isset($params['for_whom']) ? $params['for_whom'] : '';
		$first_visit = isset($params['first_visit']) ? $params['first_visit'] : '';
        $patient_id = isset($params['patient_id']) ? $params['patient_id'] : '';
		
        $nl = "\n";
		$output = '';
		
		$doctor_info = Doctors::GetDoctorInfoById($docid);
		
		$output .= '<form action="index.php?page=appointment_completed" method="post">';
		$output .= '<table width="100%" id="tblAppointmentDetails">';
		$output .= draw_token_field(false);
		$output .= draw_hidden_field('task', 'complete_appointment', false);
		$output .= draw_hidden_field('docid', $docid, false);
		$output .= draw_hidden_field('schid', $schid, false);
		$output .= draw_hidden_field('daddid', $daddid, false);
		$output .= draw_hidden_field('date', $date, false);
		$output .= draw_hidden_field('start_time', $start_time, false);
		$output .= draw_hidden_field('duration', $duration, false);
		$output .= draw_hidden_field('dspecid', $dspecid, false);
        $output .= draw_hidden_field('insid', $insid, false);
        $output .= draw_hidden_field('vrid', $vrid, false);
		$output .= draw_hidden_field('for_whom', $for_whom, false);
		$output .= draw_hidden_field('first_visit', $first_visit, false);
        $output .= draw_hidden_field('patient_id', $patient_id, false);

        if($doctor_info[1]){
            
            $first_name = isset($doctor_info[0]['first_name']) ? $doctor_info[0]['first_name'] : '';
            $middle_name = isset($doctor_info[0]['middle_name']) ? $doctor_info[0]['middle_name'] : '';
            $last_name = isset($doctor_info[0]['last_name']) ? $doctor_info[0]['last_name'] : '';
            $title = isset($doctor_info[0]['title']) ? $doctor_info[0]['title'] : '';
            $medical_degree = isset($doctor_info[0]['medical_degree']) ? ' - '.$doctor_info[0]['medical_degree'] : '';
            $doctor_photo_thumb = (isset($doctor_info[0]['doctor_photo_thumb']) && ($doctor_info[0]['doctor_photo_thumb'] != '')) ? $doctor_info[0]['doctor_photo_thumb'] : 'doctor_'.$doctor_info[0]['gender'].'.png';
            $doctor_address_info = DoctorAddresses::GetAddresses($docid, 'all', $daddid);
            $address = isset($doctor_address_info[0][0]['address']) ? $doctor_address_info[0][0]['address'] : '';
            $doctor_spec_info = DoctorSpecialities::GetSpecialities($docid, $dspecid);
            $spec_visit_price = isset($doctor_spec_info[0]['visit_price']) ? $doctor_spec_info[0]['visit_price'] : 0;
    
            $output .= '<tr valign="top">'.$nl;
            $output .= '<td>
                    <div class="doctor_card">
                        <div class="photo"><img width="90px" src="images/doctors/'.$doctor_photo_thumb.'" alt="doctor photo" /></div>
                        <div class="description">
                            <b>'._WHEN.'</b>: '.$date_formatted.' '._AT_TIME.' '.$start_time_formatted.' ('.$week_day.') <br />								
                            <b>'._WITH.'</b>: '.$title.' '.$first_name.' '.$middle_name.' '.$last_name.' '.$medical_degree.'<br>
                            '.(!empty($address) ? '<b>'._WHERE.'</b>: '.$address.'<br />' : '').' 
                            <b>'._DURATION_OF_VISIT.'</b>: '.$duration.' '._MIN.'<br />
                            <b>'._VISIT_PRICE.'</b>: '.Currencies::PriceFormat($spec_visit_price).'
                        </div>';
                        if($objLogin->IsLoggedInAsAdmin()){    
                            if($patient_id != ''){
                                $objPatients = new Patients(); 
                                $result_patient = $objPatients->GetAllPatients(' AND id = '.(int)$patient_id);
                                if($result_patient[1] > 0){
                                    $output .= draw_message(str_replace('_PATIENT_', $result_patient[0][0]['first_name'].' '.$result_patient[0][0]['last_name'], _APPOINTMENT_ASSIGNED_TO_PATIENT_MSG), false);
                                }
                            }else{
                                $output .= draw_message(_APPOINTMENT_ASSIGNED_TO_ADMIN_MSG, false);
                            }
                        }
                    $output .= '</div>';
                    
            $output .= '<div class="appointment_row">'.$nl;        
            $output .= '<b>'._DOCTOR_SPECIALITY.'</b><br>';
            $doc_speciality = Doctors::GetDoctorSpeciality($docid, $dspecid);
            if($doc_speciality[1] > 0){					
                $output .= $doc_speciality[0]['speciality_name'].'<br><br>';
            }						

            $output .= '<b>'._HAVE_VISITED_DOCTOR_BEFORE.'</b><br>';
            $output .= (($first_visit == '0') ? _I_EXISTING_PATIENT : _I_NEW_PATIENT).'<br><br>';
            
            $insurances = Insurances::GetAllActive();
            if($insurances[1] > 0){
                $output .= '<b>'._WILL_YOU_USE_INSURANCE.'</b><br>';
                $insurance_name = _UNKNOWN;
                for($i=0; $i<$insurances[1]; $i++){
                    if($insid == $insurances[0][$i]['id']){
                        $insurance_name = $insurances[0][$i]['name'];
                        break;
                    }                    
                }
                $output .= $insurance_name.'<br><br>';
            }

            $visit_reasons = VisitReasons::GetAllActive();
            if($visit_reasons[1] > 0){
                $output .= '<b>'._VISIT_REASON.'</b><br>';
                $visit_reason_name = _UNKNOWN;
                for($i=0; $i<$visit_reasons[1]; $i++){
                    if($vrid == $visit_reasons[0][$i]['id']){
                        $visit_reason_name = $visit_reasons[0][$i]['name'];
                        break;
                    }                    
                }
                $output .= $visit_reason_name.'<br><br>';
            }

            $output .= '<b>'._WHO_IS_APPOINTMENT_FOR.'</b><br>';
            $output .= (($for_whom == '0') ? _FOR_ME : _SOMEONE_ELSE).'<br>';
            $output .= '</div>';									
            
            $output .= '</td>';									
            $output .= '</tr>';
            $output .= '<tr><td nowrap="nowrap" height="10px"></td></tr>'.$nl;
            $output .= '<tr><td>';
            if($objLogin->IsLoggedInAsPatient()) $output .= '<input class="button" type="button" onclick="javascript:window.history.back();" value="'._BUTTON_BACK.'"> ';
            $output .= '<input class="button" type="submit" value="'._MAKE_APPOINTMENT.'"></td></tr>'.$nl;
            $output .= '<tr><td nowrap="nowrap" height="20px"></td></tr>'.$nl;
        }
		$output .= '</table>'.$nl;
		$output .= '</form>';
		
		if($draw) echo $output;
		else return $output;
	}

	/**
	 * Draw Appointment Details
	 * 		@param $params
	 * 		@param $draw
	 */
	public static function DrawAppointmentDetails($params = array(), $draw = true)
	{
		global $objLogin;		

		$date_format = get_date_format();
		$time_format = get_time_format(false);
		$nl = "\n";
		
		$docid = isset($params['docid']) ? $params['docid'] : '';
		$dspecid = isset($params['dspecid']) ? $params['dspecid'] : '';
        $insid = isset($params['insid']) ? $params['insid'] : '';
        $vrid = isset($params['vrid']) ? $params['vrid'] : '';
		$schid = isset($params['schid']) ? $params['schid'] : '';
		$daddid = isset($params['daddid']) ? $params['daddid'] : '';
		$date = isset($params['date']) ? $params['date'] : '';
		$date_formatted = isset($params['date']) ? date($date_format, strtotime($params['date'])) : '';
		$week_day = get_weekday_local(date('w', strtotime($params['date']))+1);
		$start_time = isset($params['start_time']) ? $params['start_time'] : '';
		$start_time_formatted = isset($params['start_time']) ? date($time_format, strtotime(str_replace('-', ':', $params['start_time']).':00')) : '';
		$duration = isset($params['duration']) ? $params['duration'] : '';
		
		$output = '';
		
		$doctor_info = Doctors::GetDoctorInfoById($docid);
		
		if($objLogin->IsLoggedInAsPatient()){
            $action = 'page=appointment_verify';	
        }else if($objLogin->IsLoggedInAs('owner','mainadmin')){
			$action = 'page=appointment_assign_patient';	
		}else{
			$action = 'page=appointment_signin';	
		}
		
		$output .= '<form action="index.php?'.$action.'" method="post">';
		$output .= '<table width="100%" id="tblAppointmentDetails">';
		$output .= draw_token_field(false);
		$output .= draw_hidden_field('task', 'verify_appointment', false);
		$output .= draw_hidden_field('docid', $docid, false);
		$output .= draw_hidden_field('schid', $schid, false);
		$output .= draw_hidden_field('daddid', $daddid, false);
		$output .= draw_hidden_field('date', $date, false);
		$output .= draw_hidden_field('start_time', $start_time, false);
		$output .= draw_hidden_field('duration', $duration, false);
		//$output .= draw_hidden_field('dspecid', $dspecid, false);

		if($doctor_info[1]){
			$first_name = isset($doctor_info[0]['first_name']) ? $doctor_info[0]['first_name'] : '';
			$middle_name = isset($doctor_info[0]['middle_name']) ? $doctor_info[0]['middle_name'] : '';
			$last_name = isset($doctor_info[0]['last_name']) ? $doctor_info[0]['last_name'] : '';
			$title = isset($doctor_info[0]['title']) ? $doctor_info[0]['title'] : '';
			$medical_degree = isset($doctor_info[0]['medical_degree']) ? ' - '.$doctor_info[0]['medical_degree'] : '';
			$doctor_photo_thumb = (isset($doctor_info[0]['doctor_photo_thumb']) && ($doctor_info[0]['doctor_photo_thumb'] != '')) ? $doctor_info[0]['doctor_photo_thumb'] : 'doctor_'.$doctor_info[0]['gender'].'.png';
			$doctor_address_info = DoctorAddresses::GetAddresses($docid, 'all', $daddid);
			$address = isset($doctor_address_info[0][0]['address']) ? $doctor_address_info[0][0]['address'] : '';
	
			$output .= '<tr valign="top">'.$nl;
			$output .= '<td>
					<div class="doctor_card">
						<div class="photo"><img width="90px" src="images/doctors/'.$doctor_photo_thumb.'" alt="doctor photo" /></div>
						<div class="description">
							<b>'._WHEN.'</b>: '.$date_formatted.' '._AT_TIME.' '.$start_time_formatted.' ('.$week_day.')<br />
							<b>'._WITH.'</b>: '.$title.' '.$first_name.' '.$middle_name.' '.$last_name.' '.$medical_degree.'<br />			
							'.(!empty($address) ? '<b>'._WHERE.'</b>: '.$address.'<br />' : '').' 
							<b>'._DURATION_OF_VISIT.'</b>: '.$duration.' '._MIN.' 
						</div>								
					</div>';				
							
			$output .= '<div class="appointment_row">'.$nl;
            $output .= '<b>'._DOCTOR_SPECIALITY.'</b>:<br>'.$nl;
			$doc_speciality = Doctors::GetDoctorSpecialities($docid);
			$output .= '<select name="dspecid">'.$nl;
			for($i=0; $i<$doc_speciality[1]; $i++){
				$output .= '<option'.(($dspecid == $doc_speciality[0][$i]['speciality_id']) ? ' selected="selected"' : '').' value="'.$doc_speciality[0][$i]['speciality_id'].'">'.$doc_speciality[0][$i]['speciality_name'].'</option>'.$nl;
			}
			$output .= '</select>'.$nl;
            $output .= '</div>'.$nl;
			
            $output .= '<div class="appointment_row">'.$nl;
			$output .= '<b>'._HAVE_VISITED_DOCTOR_BEFORE.'</b><br>'.$nl;
			$output .= '<select name="first_visit">'.$nl;
			$output .= '<option value="1">'._I_NEW_PATIENT.'</option>'.$nl;
			$output .= '<option value="0">'._I_EXISTING_PATIENT.'</option>'.$nl;
			$output .= '</select>'.$nl;
            $output .= '</div>'.$nl;
			
            $insurances = Insurances::GetAllActive();
            if($insurances[1] > 0){
                $output .= '<div class="appointment_row">'.$nl;
                $output .= '<b>'._WILL_YOU_USE_INSURANCE.'</b><br>'.$nl;
                $output .= '<select name="insid">'.$nl;
                for($i=0; $i<$insurances[1]; $i++){
                    $output .= '<option'.(($insid == $insurances[0][$i]['id']) ? ' selected="selected"' : '').' value="'.$insurances[0][$i]['id'].'">'.$insurances[0][$i]['name'].'</option>'.$nl;
                }
                $output .= '</select>'.$nl;
                $output .= '</div>'.$nl;
            }else{
                $output .= '<input type="hidden" name="insid" value="0">'.$nl;
            }

            $visit_reasons = VisitReasons::GetAllActive();
            if($visit_reasons[1] > 0){
                $output .= '<div class="appointment_row">'.$nl;
                $output .= '<b>'._VISIT_REASONS.'</b><br>'.$nl;
                $output .= '<select name="vrid">'.$nl;
                for($i=0; $i<$visit_reasons[1]; $i++){
                    $output .= '<option'.(($vrid == $visit_reasons[0][$i]['id']) ? ' selected="selected"' : '').' value="'.$visit_reasons[0][$i]['id'].'">'.$visit_reasons[0][$i]['name'].'</option>'.$nl;
                }
                $output .= '</select>'.$nl;
                $output .= '</div>'.$nl;
            }else{
                $output .= '<input type="hidden" name="vrid" value="0">'.$nl;
            }
            
            $output .= '<div class="appointment_row">'.$nl;
			$output .= '<b>'._WHO_IS_APPOINTMENT_FOR.'</b><br>'.$nl;
			$output .= '<select name="for_whom">'.$nl;
			$output .= '<option value="0">'._FOR_ME.'</option>'.$nl;
			$output .= '<option value="1">'._SOMEONE_ELSE.'</option>'.$nl;
			$output .= '</select>'.$nl;
            $output .= '</div>'.$nl;

			$output .= '</td>'.$nl;				
			$output .= '</tr>'.$nl;
			$output .= '<tr><td nowrap="nowrap" height="10px"></td></tr>'.$nl;
			$output .= '<tr><td align="'.Application::Get('defined_left').'"><input class="button" type="submit" value="'._BOOK_NOW.'"></td></tr>'.$nl;
            $output .= '<tr><td nowrap="nowrap" height="20px"></td></tr>'.$nl;
		}
		$output .= '</table>'.$nl;
		$output .= '</form>';

		if($draw) echo $output;
		else return $output;
	}
	
	/**
	 * Draw Appointment SignIn
	 * 		@param $draw
	 */
	public static function DrawAppointmentSignIn($draw = true)
	{
		$appointment_details = &$_SESSION[INSTALLATION_KEY.'appointment_details'];
		$appointment_details['docid']	   = isset($_POST['docid']) ? prepare_input($_POST['docid']) : '';
		$appointment_details['dspecid']    = isset($_POST['dspecid']) ? prepare_input($_POST['dspecid']) : '';
        $appointment_details['insid']      = isset($_POST['insid']) ? prepare_input($_POST['insid']) : '';
        $appointment_details['vrid']       = isset($_POST['vrid']) ? prepare_input($_POST['vrid']) : '';
		$appointment_details['for_whom']   = isset($_POST['for_whom']) ? prepare_input($_POST['for_whom']) : '';
		$appointment_details['first_visit']= isset($_POST['first_visit']) ? prepare_input($_POST['first_visit']) : '';
		$appointment_details['schid'] 	   = isset($_POST['schid']) ? prepare_input($_POST['schid']) : '';
		$appointment_details['date'] 	   = isset($_POST['date']) ? prepare_input($_POST['date']) : '';
		$appointment_details['start_time'] = isset($_POST['start_time']) ? prepare_input($_POST['start_time']) : '';
		$appointment_details['duration']   = isset($_POST['duration']) ? prepare_input($_POST['duration']) : '';	
			
		$output = Patients::DrawLoginFormBlock($draw);
        
		if($draw) echo $output;
		else return $output;				        
	}
    
	/**
	 * Draw appointment assign patient form
	 * 		@param $draw
	 */
	public static function DrawAppointmentAssignPatient($draw = true)
	{
        global $objLogin;
        
        $docid	      = isset($_POST['docid']) ? (int)$_POST['docid'] : '';
        $schid 	      = isset($_POST['schid']) ? (int)$_POST['schid'] : '';
        $daddid 	  = isset($_POST['daddid']) ? (int)$_POST['daddid'] : '';
        $date 	      = isset($_POST['date']) ? prepare_input($_POST['date']) : '';
        $start_time   = isset($_POST['start_time']) ? prepare_input($_POST['start_time']) : '';
        $duration     = isset($_POST['duration']) ? (int)$_POST['duration'] : '';
        $dspecid      = isset($_POST['dspecid']) ? (int)$_POST['dspecid'] : '';
        $insid        = isset($_POST['insid']) ? (int)$_POST['insid'] : '';
        $vrid         = isset($_POST['vrid']) ? (int)$_POST['vrid'] : '';
        $for_whom     = isset($_POST['for_whom']) ? prepare_input($_POST['for_whom']) : '';
        $first_visit  = isset($_POST['first_visit']) ? prepare_input($_POST['first_visit']) : '';
        
        $output = '';
        $nl = "\n";
        
		$sub_task = isset($_POST['sub_task']) ? prepare_input($_POST['sub_task']) : '';
		$patient_name = isset($_POST['patient_name']) ? prepare_input($_POST['patient_name']) : '';
		$sel_patient = isset($_POST['sel_patient']) ? prepare_input($_POST['sel_patient']) : '';
        self::$selectedUser = 'admin';
        $show_book_now = false;
        $msg = '';        
        $objPatients = new Patients();
        
        if($sub_task == 'search_patient'){
            if($patient_name == ''){
                $msg = draw_important_message(_EMPTY_PATIENT_NAME_ALERT, false);
            }else{
                $result_patients = $objPatients->GetAllPatients(' AND (last_name like \''.$patient_name.'%\' OR first_name like \''.$patient_name.'%\' OR user_name like \''.$patient_name.'%\') ');
                if($result_patients[1] == 0){
                    $msg = draw_important_message(_NO_PATIENTS_FOUND_ALERT, false);
                }else{
                    self::$selectedUser = 'patient';                    
                    $msg = draw_message(_SELECT_PATIENT_ALERT, false);
                }
            }
        }else if($sub_task == 'apply_patient' && $sel_patient != 'admin'){
            self::$patientId = $sel_patient;
            self::$selectedUser = 'patient';
            $msg = draw_success_message(_APPOINTMENT_ASSIGNED_TO_PATIENT_ALERT, false);
            $show_book_now = true;
            $result_patients = $objPatients->GetAllPatients(' AND id = '.(int)$sel_patient);
        }else{
            self::$patientId = $objLogin->GetLoggedID();
            self::$selectedUser = 'admin';
            $msg = draw_success_message(_APPOINTMENT_ASSIGNED_TO_ADMIN_ALERT, false);
            $show_book_now = true;
        }
        
        if($msg != '') echo $msg.'<br>';        
        
		$output .= '<form id="frmAssignPatient" action="index.php?page=appointment_verify" method="post">';
		$output .= '<table width="100%" id="tblAssignPatient">';
		$output .= draw_token_field(false);
        $output .= draw_hidden_field('sub_task', '', false, 'sub_task');
		$output .= draw_hidden_field('task', 'verify_appointment', false);
		$output .= draw_hidden_field('docid', $docid, false);
		$output .= draw_hidden_field('schid', $schid, false);
		$output .= draw_hidden_field('daddid', $daddid, false);
		$output .= draw_hidden_field('date', $date, false);
		$output .= draw_hidden_field('start_time', $start_time, false);
		$output .= draw_hidden_field('duration', $duration, false);
		$output .= draw_hidden_field('dspecid', $dspecid, false);
        $output .= draw_hidden_field('insid', $insid, false);
        $output .= draw_hidden_field('vrid', $vrid, false);
		$output .= draw_hidden_field('for_whom', $for_whom, false);
		$output .= draw_hidden_field('first_visit', $first_visit, false);
        if($sub_task == 'apply_patient' && self::$patientId != '') $output .= draw_hidden_field('patient_id', self::$patientId, false);
       
        $output .= '<tr>';
        $output .= '<td></td><td>';
        $output .= _CHANGE_PATIENT.': <br>
                    <input type="text" id="patient_name" name="patient_name" value="" size="20" maxlength="40" />
                    <input type="button" class="button" value="'._SEARCH.'" onclick="javascript:appAssignPatient(\'search_patient\')" />';
        $output .= '</td></tr>'.$nl;
        
        $output .= '<tr><td></td><td>- '._OR.' - </td></tr>';
        
        $output .= '<tr>';
        $output .= '<td></td><td>';
        $output .= '<select name="sel_patient" id="sel_patient">';
            if(self::$selectedUser == 'admin'){
                $output .= '<option value="admin">'.$objLogin->GetLoggedFirstName().' '.$objLogin->GetLoggedLastName().' ('.$objLogin->GetLoggedName().')</option>';
            }else{
                if($result_patients[1] > 0){
                    for($i = 0; $i < $result_patients[1]; $i++){
                        $output .= '<option value="'.$result_patients[0][$i]['id'].'">ID:'.$result_patients[0][$i]['id'].' '.$result_patients[0][$i]['first_name'].' '.$result_patients[0][$i]['last_name'].' ('.(($result_patients[0][$i]['user_name'] != '') ? $result_patients[0][$i]['user_name'] : _WITHOUT_ACCOUNT).')'.'</option>';
                    }								
                }else{
                    $output .= '<option value="admin">'.$objLogin->GetLoggedFirstName().' '.$objLogin->GetLoggedLastName().' ('.$objLogin->GetLoggedName().')</option>';
                }
            }
        $output .= '</select> ';
        if(self::$selectedUser == 'patient'){
            $output .= '<input type="button" class="button" value="'._ASSIGN_PATIENT.'" '.($show_book_now ? ' disabled="disabled" style="opacity:0.5"' : '').' onclick="javascript:appAssignPatient(\'apply_patient\')"/> ';
        }
        $output .= '<input type="button" class="button" value="'._SET_ADMIN.'" onclick="javascript:appAssignPatient(\'apply_admin\')"/>';
        $output .= '</td></tr>'.$nl;

        $output .= '<tr><td colspan="2" nowrap="nowrap" height="20px"></td></tr>'.$nl;
        if($show_book_now) $output .= '<tr><td></td><td><input class="button" type="submit" value="'._BOOK_NOW.'"></td></tr>'.$nl;
        $output .= '<tr><td colspan="2" nowrap="nowrap" height="20px"></td></tr>'.$nl;

		$output .= '</table>'.$nl;
		$output .= '</form>';

		if($draw) echo $output;
		else return $output;				        
    }
    
	
	/**
	 * Place appointment into databse
	 * 		@param $params
	 */
	public static function DoAppointment($params = array())
	{
        global $objLogin, $objSettings;
		
        if(SITE_MODE == 'demo'){
           self::$static_error = _OPERATION_BLOCKED;
		   return false;
        }
		
		$docid = isset($params['docid']) ? $params['docid'] : '';		
		$schid = isset($params['schid']) ? $params['schid'] : '';
		$daddid = isset($params['daddid']) ? $params['daddid'] : '';
		$date = isset($params['date']) ? $params['date'] : null;
		$start_time = isset($params['start_time']) ? str_replace('-', ':', $params['start_time']) : '00:00:00';
		$duration = isset($params['duration']) ? $params['duration'] : '';
		$dspecid = isset($params['dspecid']) ? $params['dspecid'] : '';
        $insid = isset($params['insid']) ? $params['insid'] : '';
        $vrid = isset($params['vrid']) ? $params['vrid'] : '';
		$for_whom = isset($params['for_whom']) ? $params['for_whom'] : '';
		$first_visit = isset($params['first_visit']) ? $params['first_visit'] : '';
        $patient_id = isset($params['patient_id']) ? $params['patient_id'] : '';

		$sql = 'SELECT id, appointment_number
				FROM '.TABLE_APPOINTMENTS.'
				WHERE
					doctor_id = '.(int)$docid.' AND
				   (status = 0 OR status = 1) AND
					patient_id = '.(int)$objLogin->GetLoggedID().' AND
					appointment_date = \''.$date.'\' AND
					appointment_time = \''.$start_time.':00\'';
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			self::$static_error = _APPOINTMENT_ALREADY_BOOKED_ALERT;
			return false;
		}else{
			// check if patient has reached the maximum number of allowed 'open' appointments
			$approval_required = ModulesSettings::Get('appointments', 'approval_required');			
			
            // check patient if maximum allowed appointments reached
            if($objLogin->IsLoggedInAsPatient()){
                $max_appointments = ModulesSettings::Get('appointments', 'maximum_allowed_appointments');
                $sql = 'SELECT COUNT(*) as cnt
                        FROM '.TABLE_APPOINTMENTS.'
                        WHERE patient_id = '.(int)$objLogin->GetLoggedID().' AND status = 0';				
                $result = database_query($sql, DATA_ONLY);
                $cnt = isset($result[0]['cnt']) ? (int)$result[0]['cnt'] : 0;
                if($cnt >= $max_appointments){
                    self::$static_error = _MAX_APPOINTMENTS_ERROR;
                    return false;
                }		                
            }
			
			if($approval_required == 'by email'){
				$msg = _APPT_CREATED_CONF_BY_EMAIL_MSG;
				$copy_subject = '';
				$email_template = 'new_appointment_confirm_by_email';					
				$status = '0';
			}else if($approval_required == 'by admin/doctor'){
				$msg = _APPT_CREATED_CONF_BY_ADMIN_MSG;
				$copy_subject = _PATIENT_REQUESTED_APPOINTMENT;
				$email_template = 'new_appointment_confirm_by_admin_doctor';					
				$status = '0';
			}else{
				// automatic
				$msg = _APPOINTMENT_SUCCESS_BOOKED;
				$copy_subject = '';
				$email_template = 'new_appointment_accepted';
				$status = '1';
			}
            
            $doctor_info = Doctors::GetDoctorInfoById($docid);
			$visit_price = isset($doctor_info[0]['default_visit_price']) ? $doctor_info[0]['default_visit_price'] : '0';
            $created_by = ($objLogin->GetLoggedType() == 'owner' || $objLogin->GetLoggedType() == 'admin') ? 'admin' : $objLogin->GetLoggedType();
            if($objLogin->IsLoggedInAsPatient()){
                $patient_id_in_sql = $objLogin->GetLoggedID();
            }else{
                $patient_id_in_sql = ($patient_id != '') ? (int)$patient_id : '0';    
            }
            
			$appt_number = strtoupper(get_random_string(10));
			$sql = 'INSERT INTO '.TABLE_APPOINTMENTS.'(
					id,
					appointment_number,
					appointment_description,
					doctor_id,
					doctor_speciality_id,
					doctor_address_id,
					patient_id,
					date_created,
					appointment_date,
					appointment_time,
					visit_duration,
					visit_price,
					doctor_notes,
					patient_notes,
					for_whom,
					first_visit,
                    insurance_id,
                    visit_reason_id,
					status,
					status_changed,
                    created_by,
					p_arrival_reminder_sent,
					p_confirm_reminder_sent,
					d_confirm_reminder_sent
				)VALUES(
					NULL,
					\''.$appt_number.'\',
					\'Appointment with a doctor\',
					'.(int)$docid.',
					'.(int)$dspecid.',
					'.(int)$daddid.',
					'.(int)$patient_id_in_sql.',
					\''.date('Y-m-d H:i:s').'\',
					\''.$date.'\',
					\''.$start_time.':00\',
					'.(int)$duration.',
					'.(int)$visit_price.',
					\'\',
					\'\',
					'.(int)$for_whom.',
					'.(int)$first_visit.',
                    '.(int)$insid.',
                    '.(int)$vrid.',
					'.(int)$status.',
					NULL,
                    \''.$created_by.'\',
					0,
					0,
					0
				)
			';
			if(database_void_query($sql)){
				self::$static_message = $msg;
				
				////////////////////////////////////////////////////////////
				// send email to patient, admin and doctor here
				Appointments::SendAppointmentEmail($email_template, $appt_number);
				////////////////////////////////////////////////////////////

				return true;	
			}else{
				///echo $sql.'<br>'.database_error();
				self::$static_error = _BOOKING_APPOINTMENT_ERROR;
				return false;
			}
		}		
	}
	
	/**
	 *	Get number of appointments awaiting approval
	 *		@param $doctor_id
	 */
	public static function AwaitingApprovalCount($doctor_id = '')
	{
		$sql = 'SELECT COUNT(*) as cnt FROM '.TABLE_APPOINTMENTS.'
				WHERE
					'.(!empty($doctor_id) ? ' doctor_id = '.(int)$doctor_id.' AND ' : '').'
                    appointment_date > "'.date('Y-m-d').'" AND 
					status = 0';
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			return $result[0]['cnt'];
		}
		return '0';
	}

	/**
	 *	Send appointment email
	 *		@param $type
	 *		@param $appt_number
	 */
	public static function SendAppointmentEmail($type = '', $appt_number = '')
	{
		global $objSettings, $objLogin;
		
		$sender = $objSettings->GetParameter('admin_email');
		$date_format = get_date_format();
		$time_format = get_time_format(false);
		$default_lang = Languages::GetDefaultLang();

        $sql = 'SELECT
					a.*,
					'.(PATIENTS_ENCRYPTION ? 'AES_DECRYPT(p.first_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : 'p.first_name').' as pat_first_name,
					'.(PATIENTS_ENCRYPTION ? 'AES_DECRYPT(p.last_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : 'p.last_name').' as pat_last_name,
					p.email as pat_email,
					p.preferred_language as pat_preferred_language,
					d.email	as doc_email,
					d.medical_degree as doc_medical_degree,
					d.preferred_language as doc_preferred_language,
					d.title	as doc_title,
					d.first_name as doc_first_name,
					d.middle_name as doc_middle_name,
					d.last_name as doc_last_name,
					d.medical_degree as doc_medical_degree,
					sd.name as doc_speciality_name,
                    IF(ds.visit_price IS NOT NULL, ds.visit_price, a.visit_price) as visit_price,
                    IF(id.name IS NOT NULL, id.name, \'\') as insurance_name,
                    IF(vrd.name IS NOT NULL, vrd.name, \'\') as visit_reason_name,
					da.address as doc_address
				FROM '.TABLE_APPOINTMENTS.' a
					INNER JOIN '.TABLE_DOCTORS.' d ON a.doctor_id = d.id
					LEFT OUTER JOIN '.TABLE_PATIENTS.' p ON a.patient_id = p.id
                    LEFT OUTER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' as sd ON a.doctor_speciality_id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\'
					LEFT OUTER JOIN '.TABLE_DOCTOR_ADDRESSES.' da ON a.doctor_address_id = da.id
                    LEFT OUTER JOIN '.TABLE_DOCTOR_SPECIALITIES.' ds ON a.doctor_id = ds.doctor_id AND a.doctor_speciality_id = ds.speciality_id
                    LEFT OUTER JOIN '.TABLE_INSURANCES_DESCRIPTION.' id ON a.insurance_id = id.insurance_id AND id.language_id = \''.Application::Get('lang').'\'
                    LEFT OUTER JOIN '.TABLE_VISIT_REASONS_DESCRIPTION.' vrd ON a.visit_reason_id = vrd.visit_reason_id AND vrd.language_id = \''.Application::Get('lang').'\'
				WHERE a.appointment_number = \''.$appt_number.'\'';
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){			
			
			// get appointment info
			$appt_number = isset($result[0]['appointment_number']) ? $result[0]['appointment_number'] : '';
			$docid = isset($result[0]['doctor_id']) ? $result[0]['doctor_id'] : '0';
			$dspecid = isset($result[0]['doctor_speciality_id']) ? $result[0]['doctor_speciality_id'] : '0';
			$daddid = isset($result[0]['doctor_address_id']) ? $result[0]['doctor_address_id'] : '0';

			// get patient's info
			$patient_fname = isset($result[0]['pat_first_name']) ? $result[0]['pat_first_name'] : '';
			$patient_lname = isset($result[0]['pat_last_name']) ? $result[0]['pat_last_name'] : '';
			$patient_email = isset($result[0]['pat_email']) ? $result[0]['pat_email'] : '';
			$patient_preferred_language = isset($result[0]['pat_preferred_language']) ? $result[0]['pat_preferred_language'] : '';
	
			// get doctor's info
			$doctor_email = isset($result[0]['doc_email']) ? $result[0]['doc_email'] : '';
			$doctor_preferred_language = isset($result[0]['doc_preferred_language']) ? $result[0]['doc_preferred_language'] : $default_lang;

			// prepare appointment details
			$appt_details = self::DrawAppointmentTable($result[0], $date_format, $time_format, false);
			$appt_details_reason = '';
			
			if($type == 'new_appointment_confirm_by_email'){
				$email_template = 'new_appointment_confirm_by_email';
				$copy_subject = _PATIENT_REQUESTED_APPOINTMENT.' ('._EMAIL_CONFIRMATION_REQUIRED.')';
			}else if($type == 'new_appointment_confirm_by_admin_doctor'){
				$email_template = 'new_appointment_confirm_by_admin_doctor';
				$copy_subject = _PATIENT_REQUESTED_APPOINTMENT.' ('._ADMIN_DOCTOR_APPROVAL_REQUIRED.')';
			}else if($type == 'new_appointment_accepted'){
				$email_template = 'new_appointment_accepted';
				$copy_subject = _PATIENT_APPOINTMENT_ACCEPTED;			
			}else if($type == 'appointment_confirmed_by_email'){
				$email_template = 'appointment_confirmed_by_email';
				$copy_subject = _PATIENT_CONFIRMED_APPOINTMENT;
			}else if($type == 'appointment_approved_by_administration'){
				$email_template = 'appointment_approved_by_administration';
				$copy_subject = _APPOINTMENT_APPROVED_BY_ADMINISTRATION;										
			}else if($type == 'appointment_canceled'){
				$email_template = 'appointment_canceled';
				$copy_subject = _APPOINTMENT_CANCELED_BY_ADMINISTRATION;
                $appt_details_reason = _REASON.': ';
                if($objLogin->IsLoggedInAsPatient()){
                    $appt_details_reason .= _CANCELED_BY_PATIENT.'<br><br>';    
                }else{
                    $appt_details_reason .= _CANCELED_BY_ADMINISTRATION.'<br><br>';    
                }
				
			}
            
			// send email to patient 				
			send_email(
				$patient_email,
				$sender,
				$email_template,
				array(
					'{FIRST NAME}' => $patient_fname,
					'{LAST NAME}'  => $patient_lname,
					'{BASE URL}'   => APPHP_BASE,
					'{APPOINTMENT NUMBER}'  => $appt_number,
					'{APPOINTMENT DETAILS}' => $appt_details_reason.$appt_details
				),
				$patient_preferred_language
			);
			
			// send email to admin and/or doctor
            $admin_preferred_language = ($objLogin->IsLoggedInAsAdmin()) ? $objLogin->GetPreferredLang() : $default_lang;
			if(ModulesSettings::Get('appointments', 'send_notification_to_admin') == 'yes'){
				send_email_wo_template($sender, $sender, $copy_subject.' ('._ADMIN_COPY.')', $appt_details, $admin_preferred_language);
			}
			if(ModulesSettings::Get('appointments', 'send_notification_to_doctor') == 'yes'){
				send_email_wo_template($doctor_email, $sender, $copy_subject.' ('._DOCTOR_COPY.')', $appt_details, $doctor_preferred_language);
			}			
        }
	}
	
	/**
	 *	Remove expired appointments
	 */
	public static function RemoveExpired()
	{
		global $objSettings;

		include_once('include/messages.inc.php');
		if(!defined('APPHP_LANG_INCLUDED')) define('APPHP_LANG_INCLUDED', true);

		$cancellation_period = ModulesSettings::Get('appointments', 'cancellation_period');
		$date_format = get_date_format();
		$time_format = get_time_format(false);

        $sql = 'SELECT
					a.id, a.appointment_number, a.patient_id, a.appointment_date, a.appointment_time, a.visit_duration, a.for_whom, a.first_visit,
					'.(PATIENTS_ENCRYPTION ? 'AES_DECRYPT(p.first_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : 'p.first_name').' as pat_first_name,
					'.(PATIENTS_ENCRYPTION ? 'AES_DECRYPT(p.last_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : 'p.last_name').' as pat_last_name,
					p.email as pat_email,
					p.preferred_language as pat_preferred_language,
					d.title	as doc_title, d.first_name as doc_first_name, d.middle_name as doc_middle_name, d.last_name as doc_last_name, d.email as doc_email, d.medical_degree as doc_medical_degree, d.preferred_language as doc_preferred_language,
					sd.name as doc_speciality_name,
                    IF(ds.visit_price IS NOT NULL, ds.visit_price, a.visit_price) as visit_price,
                    IF(id.name IS NOT NULL, id.name, \'\') as insurance_name,
                    IF(vrd.name IS NOT NULL, vrd.name, \'\') as visit_reason_name,
					da.address as doc_address
				FROM '.TABLE_APPOINTMENTS.' a
					INNER JOIN '.TABLE_PATIENTS.' p ON a.patient_id = p.id
					INNER JOIN '.TABLE_DOCTORS.' d ON a.doctor_id = d.id
                    LEFT OUTER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' as sd ON a.doctor_speciality_id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\'
					LEFT OUTER JOIN '.TABLE_DOCTOR_ADDRESSES.' da ON a.doctor_address_id = da.id
                    LEFT OUTER JOIN '.TABLE_DOCTOR_SPECIALITIES.' ds ON a.doctor_id = ds.doctor_id AND a.doctor_speciality_id = ds.speciality_id
                    LEFT OUTER JOIN '.TABLE_INSURANCES_DESCRIPTION.' id ON a.insurance_id = id.insurance_id AND id.language_id = \''.Application::Get('lang').'\'
                    LEFT OUTER JOIN '.TABLE_VISIT_REASONS_DESCRIPTION.' vrd ON a.visit_reason_id = vrd.visit_reason_id AND vrd.language_id = \''.Application::Get('lang').'\'
				WHERE
					a.status = 0 AND
					a.date_created < DATE_SUB(CONCAT(a.appointment_date, " ", a.appointment_time), INTERVAL '.$cancellation_period.' HOUR) AND
					(
						"'.date('Y-m-d H:i:s').'" > DATE_SUB(CONCAT(a.appointment_date, " ", a.appointment_time), INTERVAL '.$cancellation_period.' HOUR) AND 
					    "'.date('Y-m-d H:i:s').'" < CONCAT(a.appointment_date, " ", a.appointment_time)
					)';
		$result = database_query($sql, DATA_AND_ROWS, ALL_ROWS);
		$update_appts_ids = '';
		for($i=0; $i<$result[1]; $i++){

			if($result[0][$i]['pat_email'] != ''){
				$update_appts_ids .= ', '.$result[0][$i]['id']; 
			
				////////////////////////////////////////////////////////
				send_email(
					$result[0][$i]['pat_email'],
					$objSettings->GetParameter('admin_email'),
					'appointment_canceled',
					array(
						'{FIRST NAME}' => $result[0][$i]['pat_first_name'],
						'{LAST NAME}'  => $result[0][$i]['pat_last_name'],
						'{WEB SITE}'   => $_SERVER['SERVER_NAME'],
						'{APPOINTMENT DETAILS}' => _REASON.': '._APPROVAL_TIME_EXPIRED.' - '.$cancellation_period.' '._HOURS.'<br><br>'.
						                           self::DrawAppointmentTable($result[0][$i], $date_format, $time_format, false),
						'{BASE URL}'   => APPHP_BASE,
						'{YEAR}' 	   => date('Y')
					),
					$result[0][$i]['pat_preferred_language']
				);
				////////////////////////////////////////////////////////				
			}
		}
		if(!empty($update_appts_ids)){
			$sql = 'UPDATE '.TABLE_APPOINTMENTS.' SET status = 2, status_changed = \''.date('Y-m-d H:i:s').'\' WHERE id IN (-1'.$update_appts_ids.')';
			database_void_query($sql);		
		}
	}	
	
	/**
	 *	Send appointment reminders
	 */
	public static function SendReminders()
	{
		global $objSettings;
	
		include_once('include/messages.inc.php');
		if(!defined('APPHP_LANG_INCLUDED')) define('APPHP_LANG_INCLUDED', true);

		$approval_required = ModulesSettings::Get('appointments', 'approval_required');
		$cancellation_period = ModulesSettings::Get('appointments', 'cancellation_period');
		
		$patient_arrival_reminder = ModulesSettings::Get('reminder', 'patient_arrival_reminder');
		$patient_confirm_reminder = ModulesSettings::Get('reminder', 'patient_confirm_reminder');		
		$doctor_confirm_reminder = ModulesSettings::Get('reminder', 'doctor_confirm_reminder');
		
		$date_format = get_date_format();
		$time_format = get_time_format(false);

        $sql_main = 'SELECT
					a.id, a.appointment_number, a.patient_id, a.appointment_date, a.appointment_time, a.visit_duration, a.for_whom, a.first_visit,
					'.(PATIENTS_ENCRYPTION ? 'AES_DECRYPT(p.first_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : 'p.first_name').' as pat_first_name,
					'.(PATIENTS_ENCRYPTION ? 'AES_DECRYPT(p.last_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : 'p.last_name').' as pat_last_name,
					p.email as pat_email,
					p.preferred_language as pat_preferred_language,
					d.title	as doc_title, d.first_name as doc_first_name, d.middle_name as doc_middle_name, d.last_name as doc_last_name, d.email as doc_email, d.medical_degree as doc_medical_degree, d.preferred_language as doc_preferred_language,
					sd.name as doc_speciality_name,
                    IF(ds.visit_price IS NOT NULL, ds.visit_price, a.visit_price) as visit_price,                    
                    IF(id.name IS NOT NULL, id.name, \'\') as insurance_name,
                    IF(vrd.name IS NOT NULL, vrd.name, \'\') as visit_reason_name,                    
					da.address as doc_address
				FROM '.TABLE_APPOINTMENTS.' a
					INNER JOIN '.TABLE_PATIENTS.' p ON a.patient_id = p.id
					INNER JOIN '.TABLE_DOCTORS.' d ON a.doctor_id = d.id
                    LEFT OUTER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' as sd ON a.doctor_speciality_id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\'
					LEFT OUTER JOIN '.TABLE_DOCTOR_ADDRESSES.' da ON a.doctor_address_id = da.id
                    LEFT OUTER JOIN '.TABLE_DOCTOR_SPECIALITIES.' ds ON a.doctor_id = ds.doctor_id AND a.doctor_speciality_id = ds.speciality_id
                    LEFT OUTER JOIN '.TABLE_INSURANCES_DESCRIPTION.' id ON a.insurance_id = id.insurance_id AND id.language_id = \''.Application::Get('lang').'\'
                    LEFT OUTER JOIN '.TABLE_VISIT_REASONS_DESCRIPTION.' vrd ON a.visit_reason_id = vrd.visit_reason_id AND vrd.language_id = \''.Application::Get('lang').'\' ';
		
		// 1. ///////////////////////////////////////////////////////////////////////////
		// send arrival reminders to patients
        $sql_where = ' WHERE
					a.status = 1 AND
					a.p_arrival_reminder_sent = 0 AND
					a.date_created < DATE_SUB(CONCAT(a.appointment_date, " ", a.appointment_time), INTERVAL '.$patient_arrival_reminder.' HOUR) AND
					(
						"'.date('Y-m-d H:i:s').'" > DATE_SUB(CONCAT(a.appointment_date, " ", a.appointment_time), INTERVAL '.$patient_arrival_reminder.' HOUR) AND  
					    "'.date('Y-m-d H:i:s').'" < CONCAT(a.appointment_date, " ", a.appointment_time)
					)';
		$result = database_query($sql_main.$sql_where, DATA_AND_ROWS, ALL_ROWS);		
		$update_appts_ids = '';
		for($i=0; $i<$result[1]; $i++){
			if($result[0][$i]['pat_email'] != ''){
				$update_appts_ids .= ', '.$result[0][$i]['id']; 

				////////////////////////////////////////////////////////
				send_email(
					$result[0][$i]['pat_email'],
					$objSettings->GetParameter('admin_email'),
					'arrival_reminder',
					array(
						'{FIRST NAME}' => $result[0][$i]['pat_first_name'],
						'{LAST NAME}'  => $result[0][$i]['pat_last_name'],
						'{WEB SITE}'   => $_SERVER['SERVER_NAME'],
						'{APPOINTMENT DETAILS}' => self::DrawAppointmentTable($result[0][$i], $date_format, $time_format, false),
						'{BASE URL}'   => APPHP_BASE,
						'{YEAR}' 	   => date('Y')
					),
					$result[0][$i]['pat_preferred_language']
				);
				////////////////////////////////////////////////////////				
			}
		}
		if(!empty($update_appts_ids)){
			$sql = 'UPDATE '.TABLE_APPOINTMENTS.' SET p_arrival_reminder_sent = 1 WHERE id IN (-1'.$update_appts_ids.')';
			database_void_query($sql);		
		}

        // 2. ///////////////////////////////////////////////////////////////////////////
		// send approval reminders to patients
		if($approval_required == 'by email'){			
			$sql_where = ' WHERE
						a.status = 0 AND
						a.p_confirm_reminder_sent = 0 AND
						a.date_created < DATE_SUB(CONCAT(a.appointment_date, " ", a.appointment_time), INTERVAL '.$patient_confirm_reminder.' HOUR) AND
						(
							"'.date('Y-m-d H:i:s').'" > DATE_SUB(CONCAT(a.appointment_date, " ", a.appointment_time), INTERVAL '.$patient_confirm_reminder.' HOUR) AND  
							"'.date('Y-m-d H:i:s').'" < CONCAT(a.appointment_date, " ", a.appointment_time)
						)';

			$result = database_query($sql_main.$sql_where, DATA_AND_ROWS, ALL_ROWS);
			$update_appts_ids = '';
			for($i=0; $i<$result[1]; $i++){
				if($result[0][$i]['pat_email'] != ''){
					$update_appts_ids .= ', '.$result[0][$i]['id']; 

					////////////////////////////////////////////////////////
					send_email(
						$result[0][$i]['pat_email'],
						$objSettings->GetParameter('admin_email'),
						'approval_reminder',
						array(
							'{FIRST NAME}' => $result[0][$i]['pat_first_name'],
							'{LAST NAME}'  => $result[0][$i]['pat_last_name'],
							'{WEB SITE}'   => $_SERVER['SERVER_NAME'],
							'{HOURS}'      => $cancellation_period,
							'{BASE URL}'   => APPHP_BASE.'index.php?patient=confirm_appointment&n='.$result[0][$i]['appointment_number'],
							'{APPOINTMENT DETAILS}' => self::DrawAppointmentTable($result[0][$i], $date_format, $time_format, false),
							'{YEAR}' 	   => date('Y')
						),
						$result[0][$i]['pat_preferred_language']
					);
					////////////////////////////////////////////////////////				
				}
			}
			if(!empty($update_appts_ids)){
				$sql = 'UPDATE '.TABLE_APPOINTMENTS.' SET p_confirm_reminder_sent = 1 WHERE id IN (-1'.$update_appts_ids.')';
				database_void_query($sql);		
			}						
		}

        // 3. ///////////////////////////////////////////////////////////////////////////
		// send approval reminders to doctors
		if($approval_required == 'by admin/doctor'){			
			$sql_where = ' WHERE
						a.status = 0 AND
						a.d_confirm_reminder_sent = 0 AND
						a.date_created < DATE_SUB(CONCAT(a.appointment_date, " ", a.appointment_time), INTERVAL '.$doctor_confirm_reminder.' HOUR) AND
						(
							"'.date('Y-m-d H:i:s').'" > DATE_SUB(CONCAT(a.appointment_date, " ", a.appointment_time), INTERVAL '.$doctor_confirm_reminder.' HOUR) AND
							"'.date('Y-m-d H:i:s').'" < CONCAT(a.appointment_date, " ", a.appointment_time)
						)';
			$result = database_query($sql_main.$sql_where, DATA_AND_ROWS, ALL_ROWS);
			$update_appts_ids = '';
			for($i=0; $i<$result[1]; $i++){
				if($result[0][$i]['doc_email'] != ''){
					$update_appts_ids .= ', '.$result[0][$i]['id']; 
	
					////////////////////////////////////////////////////////
					send_email(
						$result[0][$i]['doc_email'],
						$objSettings->GetParameter('admin_email'),
						'approval_reminder',
						array(
							'{FIRST NAME}' => $result[0][$i]['doc_first_name'].' '.$result[0][$i]['doc_middle_name'],
							'{LAST NAME}'  => $result[0][$i]['doc_last_name'],
							'{WEB SITE}'   => $_SERVER['SERVER_NAME'],
							'{HOURS}'      => $cancellation_period,
							'{BASE URL}'   => APPHP_BASE.'index.php?doctor=login',
							'{APPOINTMENT DETAILS}' => self::DrawAppointmentTable($result[0][$i], $date_format, $time_format, false),
							'{YEAR}' 	   => date('Y')
						),
						$result[0][$i]['doc_preferred_language']
					);
					////////////////////////////////////////////////////////				
				}
			}
			if(!empty($update_appts_ids)){
				$sql = 'UPDATE '.TABLE_APPOINTMENTS.' SET d_confirm_reminder_sent = 1 WHERE id IN (-1'.$update_appts_ids.')';
				database_void_query($sql);		
			}			
		}	
	}
	
	/**
	 *	Draw appointments by date
	 *		@param $date
	 *		@param $account_id
	 *		@param $account_type
	 */
	public static function DrawAppointmentsByDate($date = '', $account_id = 0, $account_type = '')
	{
		global $objSettings, $objLogin;
        
        $output = '';
        $verifies_title = $objLogin->IsLoggedInAsDoctor() ? _APPROVED : _VERIFIED;
        $arr_statuses_view = array('0'=>'<span style="color:#a3a300">'._RESERVED.'</span>', '1'=>'<span style="color:#00a300">'.$verifies_title.'</span>', '2'=>'<span style="color:#939393">'._CANCELED.'</span>');
        
        if($account_type != 'doctor' && $account_type != 'patient') return $output;        
		if($objSettings->GetParameter('date_format') == 'mm/dd/yyyy'){
			$sqlFieldDateFormat = '%b %d, %Y';
		}else{
			$sqlFieldDateFormat = '%d %b, %Y';
		}
		$time_format = get_time_format(false);
		if($time_format == 'H:i'){
			$sql_time_format = '%H:%i';
		}else{
			$sql_time_format = '%l:%i %p';
		}
		

		$sql = 'SELECT a.id,
				a.appointment_number,
				a.appointment_description,
				a.doctor_id,
				a.patient_id,
				DATE_FORMAT(a.appointment_date, \''.$sqlFieldDateFormat.'\') as appointment_date,
				DATE_FORMAT(a.appointment_time, "'.$sql_time_format.'") as appointment_time,
				a.visit_duration,
				a.visit_price,
				a.doctor_notes,
				a.patient_notes,
				a.status,
				a.status_changed,
				a.p_arrival_reminder_sent,
				a.p_confirm_reminder_sent,
				a.d_confirm_reminder_sent,
				CONCAT("<a href=\"javascript:void(\'patient|view\')\" onclick=\"appAjaxPopupWindow(\'popup.ajax.php\',\'patient\',\'", '.TABLE_PATIENTS.'.id, "\',\''.Application::Get('token').'\',\''.Application::Get('lang_dir').'\')\">", '.(PATIENTS_ENCRYPTION ? 'AES_DECRYPT('.TABLE_PATIENTS.'.first_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : TABLE_PATIENTS.'.first_name').', " ", '.(PATIENTS_ENCRYPTION ? 'AES_DECRYPT('.TABLE_PATIENTS.'.last_name, "'.PASSWORDS_ENCRYPT_KEY.'")' : TABLE_PATIENTS.'.last_name').', "</a>") as patient_name,
				CONCAT("<a href=\"javascript:void(\'doctor|view\')\" onclick=\"appGoTo(\'page=doctors&docid=", '.TABLE_DOCTORS.'.id, "\')\">", '.TABLE_DOCTORS.'.first_name, " ", '.TABLE_DOCTORS.'.last_name, "</a>") as doctor_name,
				'.TABLE_PATIENTS.'.b_country
			FROM '.TABLE_APPOINTMENTS.' a
				INNER JOIN '.TABLE_DOCTORS.' ON a.doctor_id = '.TABLE_DOCTORS.'.id
				INNER JOIN '.TABLE_PATIENTS.' ON a.patient_id = '.TABLE_PATIENTS.'.id
                LEFT OUTER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' as sd ON a.doctor_speciality_id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\'
            WHERE
                '.($date != '' ? 'a.appointment_date = \''.$date.'\'' : 'a.appointment_date > \''.date('Y-m-d').'\'').' AND
                a.'.$account_type.'_id = '.(int)$account_id;

		$result = database_query($sql, DATA_AND_ROWS, ALL_ROWS);
        $defined_left = Application::Get('defined_left');
        $defined_right = Application::Get('defined_right');
        
        $output .= '<table lass="mgrid_table" width="100%">';        
        if($result[1] > 0){
            $output .= '<tr>';
            $output .= '<th width="100px" align="'.$defined_left.'">'._DATE.'</th>';
            $output .= '<th width="100px" align="'.$defined_left.'">'._TIME.'</th>';
            $output .= '<th align="'.$defined_left.'">'.(($account_type == 'doctor') ? _PATIENT : _DOCTOR).'</th>';
            $output .= '<th width="100px" align="center">'._APPOINTMENT_NUMBER.'</th>';
            $output .= '<th width="100px" align="'.$defined_right.'">'._STATUS.'</th>';
            $output .= '</tr>';
            $output .= '<tr><td nowrap="nowrap" height="1px" colspan="5">'.draw_line('no_margin_line', IMAGE_DIRECTORY, false).'</td></tr>';
        }
		for($i=0; $i<$result[1]; $i++){
			$output .= '<tr>';
            $output .= '<td>'.$result[0][$i]['appointment_date'].'</td>';
			$output .= '<td>'.$result[0][$i]['appointment_time'].'</td>';
            $output .= '<td>'.(($account_type == 'doctor') ? $result[0][$i]['patient_name'] : $result[0][$i]['doctor_name']).'</td>';
            $output .= '<td align="center">'.$result[0][$i]['appointment_number'].'</td>';
            $output .= '<td align="'.$defined_right.'">'.$arr_statuses_view[$result[0][$i]['status']].'</td>';
            $output .= '</tr>';
		}
        if($result[1] > 0){
            $output .= '<tr><td nowrap="nowrap" height="3px" colspan="5">'.draw_line('no_margin_line', IMAGE_DIRECTORY, false).'</td></tr>';
        }else{
            $output .= '<tr><td nowrap="nowrap" height="3px" colspan="5">'.(($account_type == 'doctor') ? _NO_APPOINTMENTS_AVAILABLE: _NO_RECORDS_FOUND).'</td></tr>';
        }
        $output .= '</table>';
		
		echo $output;
	}


	/**
	 *	Send appointment reminders
	 *	    @param $result
	 *	    @param $date_format
	 *	    @param $time_format
	 *		@param $draw
	 */
	private static function DrawAppointmentTable($result, $date_format, $time_format, $draw = true)
	{
		$date_formatted = date($date_format, strtotime($result['appointment_date']));
		$week_day = get_weekday_local(date('w', strtotime($result['appointment_date']))+1);
		$start_time_formatted = date($time_format, strtotime($result['appointment_time']));

		$output  = '<table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #d1d2d3">';
		$output .= '<tr style="background-color:#e1e2e3;font-weight:bold;font-size:13px;"><th align="left" colspan="2">&nbsp;<b>'._APPOINTMENT_DETAILS.'</b></th></tr>';
		$output .= '<tr><td width="27%">'._APPOINTMENT_NUMBER.': </td><td>'.$result['appointment_number'].'</td></tr>';
		$output .= '<tr><td>'._DESCRIPTION.': </td><td>'._APPOINTMENT_WITH_DOCTOR.'</td></tr>';
		$output .= '<tr><td>'._WHEN.': </td><td>'.$date_formatted.' '._AT_TIME.' '.$start_time_formatted.' ('.$week_day.')</td></tr>';
		$output .= '<tr><td>'._WHO.' ('._PATIENT.'): </td><td>'.$result['pat_first_name'].' '. $result['pat_last_name'].'</td></tr>';
		$output .= '<tr><td>'._WITH.' ('._DOCTOR.'): </td><td>'.$result['doc_title'].' '.$result['doc_first_name'].' '.$result['doc_middle_name'].' '.$result['doc_last_name'].' '.$result['doc_medical_degree'].'</td></tr>';
		$output .= '<tr><td>'._DOCTOR_SPECIALITY.': </td><td>'.$result['doc_speciality_name'].'</td></tr>';
		$output .= '<tr><td>'._WHERE.' ('._ADDRESS.'): </td><td>'.$result['doc_address'].'</td></tr>';
		$output .= '<tr><td>'._DURATION_OF_VISIT.': </td><td>'.$result['visit_duration'].' '._MINUTES.'</td></tr>';
		$output .= '<tr><td>'._WHO_IS_APPOINTMENT_FOR.' </td><td>'.(($result['for_whom'] == '0') ? _FOR_ME.' ('.$result['pat_first_name'].' '.$result['pat_last_name'].')' : _SOMEONE_ELSE).'</td></tr>';
		$output .= '<tr><td>'._HAVE_VISITED_DOCTOR_BEFORE.' </td><td>'.(($result['first_visit'] == '0') ? _I_EXISTING_PATIENT : _I_NEW_PATIENT).'</td></tr>';
		$output .= '<tr><td>'._INSURANCE.': </td><td>'.(($result['insurance_name'] != '') ? $result['insurance_name'] : _UNKNOWN).'</td></tr>';
        $output .= '<tr><td>'._VISIT_REASON.': </td><td>'.(($result['visit_reason_name'] != '') ? $result['visit_reason_name'] : _UNKNOWN).'</td></tr>';                    
		$output .= '<tr><td>'._VISIT_PRICE.': </td><td>'.Currencies::PriceFormat($result['visit_price']).'</td></tr>';
		$output .= '</table><br />';
	
		if($draw) echo $output;
		else return $output;
	}
	
}

?>