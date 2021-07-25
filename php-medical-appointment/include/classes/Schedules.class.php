<?php

/**
 *	Class Schedules
 *  --------------
 *	Description : encapsulates schedules methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 26.01.2012
 *  Usage       : MedicalAppointment
 *
 *	PUBLIC:				  	STATIC:				 	PRIVATE:
 * 	------------------	  	---------------     	---------------
 *	__construct                                     CheckStartFinishDate
 *	__destruct                                      CheckDateOverlapping
 *	GetScheduleInfoByID
 *	BeforeInsertRecord
 *	BeforeUpdateRecord
 *	AfterDeleteRecord
 *      
 **/


class Schedules extends MicroGrid {
	
	protected $debug = false;
	
	// #001 private $arrTranslations = '';		
	private $sqlFieldDateFormat = '';
	private $formTimeblocksActionURL = '';
	
	//==========================================================================
    // Class Constructor
	// 		@param $doctor_id
	//      @param $account_type
	//==========================================================================
	function __construct($doctor_id = 0, $account_type = '')
	{		
		parent::__construct();
		
		$this->params = array();

		global $objSettings;
		
		## for standard fields
		if(isset($_POST['name']))      $this->params['name'] = prepare_input($_POST['name']);
		if(isset($_POST['doctor_id'])) $this->params['doctor_id'] = prepare_input($_POST['doctor_id']);
		if(isset($_POST['date_from'])) $this->params['date_from'] = prepare_input($_POST['date_from']);
		if(isset($_POST['date_to']))   $this->params['date_to'] = prepare_input($_POST['date_to']);

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
	
		//$this->uPrefix 		= 'prefix_';
		
		$this->primaryKey 	= 'id';
		$this->tableName 	= TABLE_SCHEDULES;
		$this->dataSet 		= array();
		$this->error 		= '';
		if($account_type == 'me'){
			$this->formActionURL = 'index.php?doctor=schedules_management';
			$this->formTimeblocksActionURL = 'index.php?doctor=schedules_set_timeblocks';	
		}else{
			$this->formActionURL = 'index.php?admin=schedules_management'.(!empty($doctor_id) ? '&doc_id='.(int)$doctor_id : '');
			$this->formTimeblocksActionURL = 'index.php?admin=schedules_set_timeblocks';	
		}		
		
		$this->actions      = array('add'=>true, 'edit'=>true, 'details'=>true, 'delete'=>true);
		$this->actionIcons  = true;
		$this->allowRefresh = true;
		$this->allowTopButtons = false;
		$this->alertOnDelete = ''; // leave empty to use default alerts

		$this->allowLanguages = false;
		$this->languageId  	= ''; //($this->params['language_id'] != '') ? $this->params['language_id'] : Languages::GetDefaultLang();
		$this->WHERE_CLAUSE = !empty($doctor_id) ? 'WHERE doctor_id = '.(int)$doctor_id : ''; 
		$this->ORDER_CLAUSE = 'ORDER BY '.$this->tableName.'.date_from DESC';
		
		$this->isAlterColorsAllowed = true;

		$this->isPagingAllowed = true;
		$this->pageSize = 20;

		$this->isSortingAllowed = true;

		$this->isExportingAllowed = ($account_type == '') ? true : false;
		$this->arrExportingTypes = array('csv'=>true);
		
		$date_format = get_date_format('view');
		$date_format_settings = get_date_format('view', true);
		$date_format_edit = get_date_format('edit');				
		///$date_format_edit = get_date_format('edit');
		///$datetime_format = get_datetime_format();
		///$currency_format = get_currency_format();

		// prepare doctors array		
		$total_doctors = Doctors::GetAllActive();
		$arr_doctors = array();
		foreach($total_doctors[0] as $key => $val){
			$arr_doctors[$val['id']] = $val['first_name'].' '.$val['middle_name'].' '.$val['last_name'];
		}

		// define filtering fields
		$this->isFilteringAllowed = true;
		$this->arrFilteringFields = array(
			_DOCTOR => array('table'=>TABLE_DOCTORS, 'field'=>'id', 'type'=>'dropdownlist', 'source'=>$arr_doctors, 'sign'=>'=', 'width'=>'160px', 'visible'=>(!empty($doctor_id) ? false : true)),
			_DATE   => array('table'=>TABLE_SCHEDULES, 'field'=>'date_from', 'type'=>'calendar', 'date_format'=>$date_format_settings, 'sign'=>'>=', 'width'=>'80px', 'visible'=>true),
		);

		if($objSettings->GetParameter('date_format') == 'mm/dd/yyyy'){
			$this->sqlFieldDateFormat = '%b %d, %Y';
		}else{
			$this->sqlFieldDateFormat = '%d %b, %Y';
		}
		$this->SetLocale(Application::Get('lc_time_name'));

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

		//---------------------------------------------------------------------- 
		// VIEW MODE
		// format: strip_tags
		// format: nl2br
		// format: 'format'=>'date', 'format_parameter'=>'M d, Y, g:i A'
		// format: 'format'=>'currency', 'format_parameter'=>'european|2' or 'format_parameter'=>'american|4'
		//---------------------------------------------------------------------- 
		$this->VIEW_MODE_SQL = 'SELECT
									'.$this->tableName.'.'.$this->primaryKey.',
									'.$this->tableName.'.name,
									'.$this->tableName.'.doctor_id,
									DATE_FORMAT('.$this->tableName.'.date_from, \''.$this->sqlFieldDateFormat.'\') as date_from,
									DATE_FORMAT('.$this->tableName.'.date_to, \''.$this->sqlFieldDateFormat.'\') as date_to,
									CONCAT('.TABLE_DOCTORS.'.first_name, " ", '.TABLE_DOCTORS.'.middle_name, " ", '.TABLE_DOCTORS.'.last_name) as full_name,
									"[ '._EDIT_WORD.' ]" as link_timeblocks
								FROM '.$this->tableName.' 
									INNER JOIN '.TABLE_DOCTORS.' ON '.$this->tableName.'.doctor_id = '.TABLE_DOCTORS.'.id';		
		// define view mode fields
		$this->arrViewModeFields = array(
			'full_name'  => array('title'=>_DOCTOR, 'type'=>'label', 'align'=>'left', 'width'=>'200px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>(($account_type == 'me') ? false : true), 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>''),
			'name'       => array('title'=>_NAME, 'type'=>'label', 'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>''),
			'date_from'  => array('title'=>_VALID_FROM_DATE, 'type'=>'label', 'align'=>'center', 'width'=>'140px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>''),
			'date_to'    => array('title'=>_VALID_TO_DATE, 'type'=>'label', 'align'=>'center', 'width'=>'140px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>''),
			'link_timeblocks' => array('title'=>_TIME_BLOCKS, 'type'=>'link',  'align'=>'center', 'width'=>'150px', 'sortable'=>false, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>'', 'href'=>$this->formTimeblocksActionURL.'&scid={id}', 'target'=>''),
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
		$this->arrAddModeFields = array();
		if(!empty($doctor_id)){
			$this->arrAddModeFields['doctor_id'] = array('title'=>'', 'type'=>'hidden', 'required'=>true, 'default'=>$doctor_id);
		}else{
			$this->arrAddModeFields['doctor_id'] = array('title'=>_DOCTOR, 'type'=>'enum',  'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'', 'source'=>$arr_doctors, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>'');
		}
		$this->arrAddModeFields['name']      = array('title'=>_NAME, 'type'=>'textbox',  'width'=>'210px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'255', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true);
		$this->arrAddModeFields['date_from'] = array('title'=>_VALID_FROM_DATE, 'type'=>'date', 'width'=>'210px', 'required'=>true, 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true, 'format'=>'date', 'format_parameter'=>$date_format_edit);
		$this->arrAddModeFields['date_to']   = array('title'=>_VALID_TO_DATE, 'type'=>'date', 'width'=>'210px', 'required'=>true, 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true, 'format'=>'date', 'format_parameter'=>$date_format_edit);

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
								'.$this->tableName.'.name,
								'.$this->tableName.'.doctor_id,
								'.$this->tableName.'.date_from,
								'.$this->tableName.'.date_to,
								DATE_FORMAT('.$this->tableName.'.date_from, \''.$this->sqlFieldDateFormat.'\') as mod_date_from,
								DATE_FORMAT('.$this->tableName.'.date_to, \''.$this->sqlFieldDateFormat.'\') as mod_date_to,
								CONCAT('.TABLE_DOCTORS.'.first_name, " ", '.TABLE_DOCTORS.'.middle_name, " ", '.TABLE_DOCTORS.'.last_name) as full_name
							FROM '.$this->tableName.' 
								INNER JOIN '.TABLE_DOCTORS.' ON '.$this->tableName.'.doctor_id = '.TABLE_DOCTORS.'.id
							WHERE '.$this->tableName.'.'.$this->primaryKey.' = _RID_';		
		// define edit mode fields
		$this->arrEditModeFields = array(
			'full_name' => array('title'=>_DOCTOR, 'type'=>'label'),
			'name'      => array('title'=>_NAME, 'type'=>'textbox',  'width'=>'210px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'255', 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true),
			'doctor_id' => array('title'=>'', 'type'=>'hidden', 'required'=>true, 'default'=>''),
			'date_from' => array('title'=>_VALID_FROM_DATE, 'type'=>'date', 'width'=>'210px', 'required'=>true, 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true, 'format'=>'date', 'format_parameter'=>$date_format_edit),
			'date_to'   => array('title'=>_VALID_TO_DATE, 'type'=>'date', 'width'=>'210px', 'required'=>true, 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'unique'=>false, 'visible'=>true, 'format'=>'date', 'format_parameter'=>$date_format_edit),
		);

		//---------------------------------------------------------------------- 
		// DETAILS MODE
		//----------------------------------------------------------------------
		$this->DETAILS_MODE_SQL = $this->EDIT_MODE_SQL;
		$this->arrDetailsModeFields = array(
			'name'      => array('title'=>_NAME, 'type'=>'label'),
			'full_name' => array('title'=>_DOCTOR, 'type'=>'label'),
			'mod_date_from' => array('title'=>_VALID_FROM_DATE, 'type'=>'label'),
			'mod_date_to'  	=> array('title'=>_VALID_TO_DATE, 'type'=>'label'),
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
	
	/**
	 *	Returns View Mode info by ID
	 *		@param $key
	 */
	public function GetScheduleInfoByID($key = '')
	{
		$sql = 'SELECT
					'.$this->tableName.'.*,
					DATE_FORMAT('.$this->tableName.'.date_from, \''.$this->sqlFieldDateFormat.'\') as date_from,
					DATE_FORMAT('.$this->tableName.'.date_to, \''.$this->sqlFieldDateFormat.'\') as date_to
				FROM '.$this->tableName.' 
				WHERE '.$this->primaryKey.'='.(int)$key.'
				LIMIT 0, 1';
		return database_query($sql, DATA_ONLY, FIRST_ROW_ONLY);
	}
	

	//==========================================================================
    // MicroGrid Methods
	//==========================================================================	
	/**
	 *	Before-Insertion record
	 */
	public function BeforeInsertRecord()
	{
		if(!$this->CheckStartFinishDate()) return false;
		if(!$this->CheckDateOverlapping()) return false;		
		return true;
	}

	/**
	 *	Before-updating record
	 */
	public function BeforeUpdateRecord()
	{
		if(!$this->CheckStartFinishDate()) return false;
		if(!$this->CheckDateOverlapping()) return false;		
		return true;
	}
	
	/**
	 *	After-deleting record
	 */
	public function AfterDeleteRecord()
	{
		// clean schedule timeblocks table
		$sql = 'DELETE FROM '.TABLE_SCHEDULE_TIMEBLOCKS.' WHERE schedule_id = '.(int)$this->curRecordId;
		database_void_query($sql);
	}
	

	//==========================================================================
    // Private Methods
	//==========================================================================	
	/**
	 * Check if start date is greater than finish date
	 */
	private function CheckStartFinishDate()
	{
		$date_from = MicroGrid::GetParameter('date_from', false);
		$date_to = MicroGrid::GetParameter('date_to', false);
		
		if($date_from >= $date_to){
			$this->error = _START_FINISH_DATE_ERROR;
			return false;
		}	
		return true;		
	}
	
	/**
	 * Check if there is a date overlapping
	 */
	private function CheckDateOverlapping()
	{
		$rid = MicroGrid::GetParameter('rid');
		$doctor_id = MicroGrid::GetParameter('doctor_id', false);
		$date_from = MicroGrid::GetParameter('date_from', false);
		$date_to = MicroGrid::GetParameter('date_to', false);

		$sql = 'SELECT id FROM '.TABLE_SCHEDULES.'
				WHERE
					id != '.(int)$rid.' AND
					doctor_id = '.(int)$doctor_id.' AND 
					(						
						((\''.$date_from.'\' >= date_from) AND (\''.$date_from.'\' <= date_to)) OR
						((\''.$date_to.'\' >= date_from) AND (\''.$date_to.'\' <= date_to)) OR
						((\''.$date_from.'\' <= date_from) AND (\''.$date_to.'\' >= date_to))						
					) ';	
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			$this->error = ''._DOCTOR_TIME_OVERLAPPING_ALERT;
			return false;
		}
		return true;
	}	
	
}
?>