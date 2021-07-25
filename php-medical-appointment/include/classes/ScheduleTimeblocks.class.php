<?php

/**
 *	Class ScheduleTimeblocks
 *  --------------
 *	Description : encapsulates schedule timeblocks methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 20.01.2012
 *  Usage       : MedicalAppointment
 *
 *	PUBLIC:				  	STATIC:				 	PRIVATE:
 * 	------------------	  	---------------     	---------------
 *	__construct             GetTimeSlotsForDay      DrawScheduleInfo
 *	__destruct                                      CheckStartFinishTime 	
 *	BeforeViewRecords                               CheckTimeOverlapping
 *	BeforeAddRecord
 *	BeforeEditRecord
 *	BeforeDetailsRecord
 *	BeforeInsertRecord
 *	BeforeUpdateRecord
 *	
 **/


class ScheduleTimeblocks extends MicroGrid {
	
	protected $debug = false;
	
	// #001 private $arrTranslations = '';
	private $schedule_id = '';
	
	//==========================================================================
    // Class Constructor
	// 		@param $schedule_id
	//      @param $account_type
	//==========================================================================
	function __construct($schedule_id = 0, $account_type = '')
	{		
		parent::__construct();
        
        global $objSettings;

		$this->schedule_id = $schedule_id;
		
		$this->params = array();		
		if(isset($_POST['week_day']))  $this->params['week_day'] = prepare_input($_POST['week_day']);
		if(isset($_POST['time_from'])) $this->params['time_from'] = prepare_input($_POST['time_from']);
		if(isset($_POST['time_to']))   $this->params['time_to'] = prepare_input($_POST['time_to']);
		if(isset($_POST['time_slots'])) $this->params['time_slots'] = prepare_input($_POST['time_slots']);
		if(isset($_POST['schedule_id']))  $this->params['schedule_id'] = prepare_input($_POST['schedule_id']);
		if(isset($_POST['doctor_address_id'])) $this->params['doctor_address_id'] = (int)$_POST['doctor_address_id'];
		
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
		$this->tableName 	= TABLE_SCHEDULE_TIMEBLOCKS;
		$this->dataSet 		= array();
		$this->error 		= '';
		if($account_type == 'me'){
			$this->formActionURL = 'index.php?doctor=schedules_set_timeblocks&scid='.(int)$this->schedule_id;
		}else{
			$this->formActionURL = 'index.php?admin=schedules_set_timeblocks&scid='.(int)$this->schedule_id;
		}
		$this->actions      = array('add'=>true, 'edit'=>true, 'details'=>true, 'delete'=>true);
		$this->actionIcons  = true;
		$this->allowRefresh = true;
		$this->allowTopButtons = false;
		$this->alertOnDelete = ''; // leave empty to use default alerts

		$this->allowLanguages = false;
		$this->languageId  	= ''; //($this->params['language_id'] != '') ? $this->params['language_id'] : Languages::GetDefaultLang();
		$this->WHERE_CLAUSE = 'WHERE schedule_id = '.(int)$this->schedule_id; 
		$this->ORDER_CLAUSE = 'ORDER BY week_day ASC, time_from ASC'; 
		
		$this->isAlterColorsAllowed = true;

		$this->isPagingAllowed = true;
		$this->pageSize = 20;

		$this->isSortingAllowed = true;

		$this->isExportingAllowed = false;
		$this->arrExportingTypes = array('csv'=>false);
		
		$this->isFilteringAllowed = false;
		// define filtering fields
		$this->arrFilteringFields = array(
			// 'Caption_1'  => array('table'=>'', 'field'=>'', 'type'=>'text', 'sign'=>'=|like%|%like|%like%', 'width'=>'80px', 'visible'=>true),
			// 'Caption_2'  => array('table'=>'', 'field'=>'', 'type'=>'dropdownlist', 'source'=>array(), 'sign'=>'=|like%|%like|%like%', 'width'=>'130px', 'visible'=>true),
		);
		
		$doctor_info = Doctors::GetDoctorInfoBySchedule($this->schedule_id);		
		$default_timeslot = isset($doctor_info[0]['default_visit_duration']) ? $doctor_info[0]['default_visit_duration'] : '15';
		$doctor_id = isset($doctor_info[0]['id']) ? $doctor_info[0]['id'] : '0';

		///$this->isAggregateAllowed = false;
		///// define aggregate fields for View Mode
		///$this->arrAggregateFields = array(
		///	'field1' => array('function'=>'SUM'),
		///	'field2' => array('function'=>'AVG'),
		///);

		///$date_format = get_date_format('view');
		///$date_format_edit = get_date_format('edit');
		///$datetime_format = get_datetime_format();
		$time_format = get_time_format(false);
		$time_format_settings = get_time_format(false, true);
		$sql_time_format = ($time_format_settings == 'am/pm') ? '%l:%i %p' : '%H:%i';
		///$currency_format = get_currency_format();

		// prepare weekdays array		
		$arr_weekdays_temp = array('1'=>_SUNDAY, '2'=>_MONDAY, '3'=>_TUESDAY, '4'=>_WEDNESDAY, '5'=>_THURSDAY, '6'=>_FRIDAY, '7'=>_SATURDAY);
        $week_start_day = ($objSettings->GetParameter('week_start_day') != '') ? $objSettings->GetParameter('week_start_day') - 1 : 1;
        for($i=$week_start_day; $i < $week_start_day + 7; $i++){
            $ind_modulo = ($i % 7) + 1;
            $arr_weekdays[$ind_modulo] = $arr_weekdays_temp[$ind_modulo];
        }
        
		$arr_slots = get_time_slots();

		// prepare addresses array		
		$total_addresses = DoctorAddresses::GetAddresses($doctor_id, 'all');
		$arr_addresses = array();
		foreach($total_addresses[0] as $key => $val){
			$arr_addresses[$val['id']] = $val['address'];
		}

		//---------------------------------------------------------------------- 
		// VIEW MODE
		// format: strip_tags
		// format: nl2br
		// format: 'format'=>'date', 'format_parameter'=>'M d, Y, g:i A'
		// format: 'format'=>'currency', 'format_parameter'=>'european|2' or 'format_parameter'=>'american|4'
		//---------------------------------------------------------------------- 
		$this->VIEW_MODE_SQL = 'SELECT stb.'.$this->primaryKey.',
									stb.schedule_id,
									stb.doctor_address_id,
									stb.week_day,
									DATE_FORMAT(stb.time_from, "'.$sql_time_format.'") as mod_time_from,
									DATE_FORMAT(stb.time_to, "'.$sql_time_format.'") as mod_time_to,
									stb.time_slots,
									da.address
								FROM '.$this->tableName.' stb
									LEFT OUTER JOIN '.TABLE_DOCTOR_ADDRESSES.' da ON stb.doctor_address_id = da.id 
								';		
		// define view mode fields
		$this->arrViewModeFields = array(
			'week_day'      => array('title'=>_WEEK_DAY, 'type'=>'enum',  'align'=>'left', 'width'=>'120px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_weekdays),
			'address'       => array('title'=>_ADDRESS, 'type'=>'label', 'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'50', 'format'=>'', 'format_parameter'=>''),
			'mod_time_from' => array('title'=>_FROM_TIME, 'type'=>'label', 'align'=>'center', 'width'=>'120px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>''),
			'mod_time_to'   => array('title'=>_TO_TIME, 'type'=>'label', 'align'=>'center', 'width'=>'120px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>''),
			'time_slots'    => array('title'=>_TIME_SLOTS, 'type'=>'enum', 'align'=>'center', 'width'=>'160px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_slots),
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
			'doctor_address_id' => array('title'=>_ADDRESS, 'type'=>'enum', 'width'=>'', 'required'=>false, 'readonly'=>false, 'default'=>'', 'source'=>$arr_addresses, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
			'week_day'   => array('title'=>_WEEK_DAY, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'', 'source'=>$arr_weekdays, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
			'time_from'  => array('title'=>_FROM_TIME, 'type'=>'time', 'required'=>true, 'readonly'=>false, 'unique'=>false, 'visible'=>true, 'default'=>'00:00:00', 'validation_type'=>'', 'format'=>'', 'format_parameter'=>$time_format_settings, 'show_seconds'=>false, 'minutes_step'=>'5'),
			'time_to'    => array('title'=>_TO_TIME, 'type'=>'time', 'required'=>true, 'readonly'=>false, 'unique'=>false, 'visible'=>true, 'default'=>'00:00:00', 'validation_type'=>'', 'format'=>'', 'format_parameter'=>$time_format_settings, 'show_seconds'=>false, 'minutes_step'=>'5'),
			'time_slots' => array('title'=>_TIME_SLOTS, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>$default_timeslot, 'source'=>$arr_slots, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
			'schedule_id' => array('title'=>'', 'type'=>'hidden', 'required'=>true, 'readonly'=>false, 'default'=>$this->schedule_id),
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
								'.$this->tableName.'.schedule_id,
								'.$this->tableName.'.doctor_address_id,
								'.$this->tableName.'.week_day,
								'.$this->tableName.'.time_from,
								'.$this->tableName.'.time_to,
								'.$this->tableName.'.time_slots
							FROM '.$this->tableName.'
							WHERE '.$this->tableName.'.'.$this->primaryKey.' = _RID_';		
		// define edit mode fields
		$this->arrEditModeFields = array(
			'doctor_address_id' => array('title'=>_ADDRESS, 'type'=>'enum', 'width'=>'', 'required'=>false, 'readonly'=>false, 'default'=>'', 'source'=>$arr_addresses, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
			'week_day'   => array('title'=>_WEEK_DAY, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'', 'source'=>$arr_weekdays, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
			'time_from'  => array('title'=>_FROM_TIME, 'type'=>'time', 'required'=>true, 'readonly'=>false, 'unique'=>false, 'visible'=>true, 'default'=>'00:00:00', 'validation_type'=>'', 'format'=>'', 'format_parameter'=>$time_format_settings, 'show_seconds'=>false, 'minutes_step'=>'5'),
			'time_to'    => array('title'=>_TO_TIME, 'type'=>'time', 'required'=>true, 'readonly'=>false, 'unique'=>false, 'visible'=>true, 'default'=>'00:00:00', 'validation_type'=>'', 'format'=>'', 'format_parameter'=>$time_format_settings, 'show_seconds'=>false, 'minutes_step'=>'5'),
			'time_slots' => array('title'=>_TIME_SLOTS, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>$default_timeslot, 'source'=>$arr_slots, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
			'schedule_id' => array('title'=>'', 'type'=>'hidden', 'required'=>true, 'readonly'=>false, 'default'=>$this->schedule_id),
		);

		//---------------------------------------------------------------------- 
		// DETAILS MODE
		//----------------------------------------------------------------------
		$this->DETAILS_MODE_SQL = $this->EDIT_MODE_SQL;
		$this->arrDetailsModeFields = array(
			//'schedule_id' => array('title'=>'', 'type'=>'label'),
			'doctor_address_id' => array('title'=>_ADDRESS, 'type'=>'enum', 'source'=>$arr_addresses),
			'week_day'      => array('title'=>_WEEK_DAY, 'type'=>'enum', 'source'=>$arr_weekdays),
			'time_from' => array('title'=>_FROM_TIME, 'type'=>'time', 'format'=>'date', 'format_parameter'=>$time_format),  
			'time_to' => array('title'=>_TO_TIME, 'type'=>'time', 'format'=>'date', 'format_parameter'=>$time_format),  
			'time_slots'    => array('title'=>_TIME_SLOTS, 'type'=>'enum', 'source'=>$arr_slots),
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
	 * Before drawing View Mode
	 */
	public function BeforeViewRecords()
	{
		$this->DrawScheduleInfo();		
	}

	/**
	 * Before drawing Add Mode
	 */
	public function BeforeAddRecord()
	{
		$this->DrawScheduleInfo();		
	}

	/**
	 * Before drawing Edit Mode
	 */
	public function BeforeEditRecord()
	{
		$this->DrawScheduleInfo();
		return true;
	}
	
	/**
	 * Before drawing Details Mode
	 */
	public function BeforeDetailsRecord()
	{
		$this->DrawScheduleInfo();		
	}
	
	/**
	 *	Before-Insertion record
	 */
	public function BeforeInsertRecord()
	{
		if(!$this->CheckStartFinishTime()) return false;
		if(!$this->CheckTimeOverlapping()) return false;		
		return true;
	}

	/**
	 *	Before-updating record
	 */
	public function BeforeUpdateRecord()
	{
		if(!$this->CheckStartFinishTime()) return false;
		if(!$this->CheckTimeOverlapping()) return false;		
		return true;
	}

	//==========================================================================
    // Static Methods
	//==========================================================================	
	/**
	 * Returns a list of slots for certain doctor for a specific day
	 */
	public static function GetTimeSlotsForDay($doctor_id, $date, $week_day_num)
	{
		$result = array();
		$minimum_time_slots = ModulesSettings::Get('appointments', 'delay_slots');
		$today = date('Y-m-d');
		$time_format = get_time_format(false);
		
		// prepare real timeslots
		$sql = 'SELECT
					'.TABLE_SCHEDULES.'.id,
					'.TABLE_SCHEDULES.'.name,
					'.TABLE_SCHEDULES.'.date_from,
					'.TABLE_SCHEDULES.'.date_to,
					'.TABLE_SCHEDULE_TIMEBLOCKS.'.doctor_address_id,
					'.TABLE_SCHEDULE_TIMEBLOCKS.'.week_day,
					'.TABLE_SCHEDULE_TIMEBLOCKS.'.time_from,
					'.TABLE_SCHEDULE_TIMEBLOCKS.'.time_to,
					'.TABLE_SCHEDULE_TIMEBLOCKS.'.time_slots 
				FROM '.TABLE_SCHEDULES.'
					INNER JOIN '.TABLE_SCHEDULE_TIMEBLOCKS.' ON '.TABLE_SCHEDULES.'.id = '.TABLE_SCHEDULE_TIMEBLOCKS.'.schedule_id 
				WHERE
					('.TABLE_SCHEDULES.'.date_from <= \''.$date.'\' AND \''.$date.'\' <= '.TABLE_SCHEDULES.'.date_to) AND
					'.TABLE_SCHEDULES.'.doctor_id = '.(int)$doctor_id.' AND
					'.TABLE_SCHEDULE_TIMEBLOCKS.'.week_day = '.(int)$week_day_num.'
				ORDER BY '.TABLE_SCHEDULES.'.id ASC, '.TABLE_SCHEDULE_TIMEBLOCKS.'.time_from ASC';
		$res = database_query($sql, DATA_AND_ROWS, ALL_ROWS);
		for($i=0; $i < $res[1]; $i++){
			$time_slot   = (int)$res[0][$i]['time_slots'];			
			$schedile_id = $res[0][$i]['id'];
			$doctor_address_id = $res[0][$i]['doctor_address_id'];
			$start_time  = strtotime($res[0][$i]['time_from']);
			
			if($date == $today){
				$actual_time = date('H:i:s', strtotime('+'.($time_slot*$minimum_time_slots).' minute'));				
			}else{
				$actual_time = $res[0][$i]['time_from'];
			}
			
			$current_time = $res[0][$i]['time_from'];
			$end_time     = $res[0][$i]['time_to'];
			$counter = 0;
			while($current_time < $end_time){
				$current_time_shift = strtotime('+'.($counter * $time_slot).' minute', $start_time);					
				$current_time = date('H:i:s', $current_time_shift);
				$current_time_1 = date('H-i', $current_time_shift);
				$current_time_2 = date($time_format, $current_time_shift);

				$counter++;
				if($counter > 300) break;

				if($current_time < $actual_time){
					continue;
				}else if($current_time < $end_time){
					$result[] = array('date'=>$date, 'schedule_id'=>$schedile_id, 'doctor_address_id'=>$doctor_address_id, 'time_real'=>$current_time, 'time'=>$current_time_1, 'time_view'=>$current_time_2, 'duration'=>$time_slot);
				}				
			}
		}			
		$result_total = count($result);
		
		// subtruct booked timeslots
		$sql = 'SELECT id, doctor_id, patient_id, appointment_date, appointment_time, visit_duration 
				FROM '.TABLE_APPOINTMENTS.'
				WHERE appointment_date = "'.$date.'" AND						
					  doctor_id = '.(int)$doctor_id.' AND
					  (status = 0 OR status = 1)';
		// exclude canceled
		$res = database_query($sql, DATA_AND_ROWS, ALL_ROWS);
		for($i=0; $i < $res[1]; $i++){
			for($j=0; $j < $result_total; $j++){
				$time_real = isset($result[$j]['time_real']) ? $result[$j]['time_real'] : false;
				if($time_real == $res[0][$i]['appointment_time']){
					unset($result[$j]);
					break;
				}
			}
		}

		//echo '<pre>';
		//print_r($result);
		//echo '</pre>';

		// subtruct timeoff timeslots		
		$sql = 'SELECT id, doctor_id, date_from, time_from, date_to, time_to
				FROM '.TABLE_TIMEOFFS.'
				WHERE
					(
					  ("'.$date.'" > date_from AND "'.$date.'" < date_to) OR
					  ("'.$date.'" = date_from AND "'.$date.'" <= date_to) OR 
					  ("'.$date.'" >= date_from AND "'.$date.'" = date_to)
					) AND
					doctor_id = '.(int)$doctor_id;
		$res = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($res[1] > 0){
			if($date == $res[0]['date_from'] && $date == $res[0]['date_to']){
				// this day doctor works partially
				for($j=0; $j < $result_total; $j++){
					$time_real = isset($result[$j]['time_real']) ? $result[$j]['time_real'] : false;
					if($time_real >= $res[0]['time_from'] && $time_real < $res[0]['time_to']){
						unset($result[$j]);
					}
				}				
			}else if($date == $res[0]['date_to']){
				// this day doctor works partially
				for($j=0; $j < $result_total; $j++){
					$time_real = isset($result[$j]['time_real']) ? $result[$j]['time_real'] : false;
					if($time_real < $res[0]['time_to']){
						unset($result[$j]);
					}
				}
			}else if($date == $res[0]['date_from']){
				// this day doctor works partially
				for($j=0; $j < $result_total; $j++){
					$time_real = isset($result[$j]['time_real']) ? $result[$j]['time_real'] : false;
					if($time_real >= $res[0]['time_from']){						
						unset($result[$j]);
					}
				}
			}else{
				// this day doctor doesn't work at all
				$result = array();
			}
		}

		return $result;		
	}

	//==========================================================================
    // Private Methods
	//==========================================================================	
	/**
	 * Check if start date is greater than finish date
	 */
	private function CheckStartFinishTime()
	{
		$time_from = MicroGrid::GetParameter('time_from', false);
		$time_to = MicroGrid::GetParameter('time_to', false);
		$time_slots = MicroGrid::GetParameter('time_slots', false);
		
		if($time_from == $time_to){
			$this->error = _START_FINISH_TIME_ERROR;
			return false;
		}else if(strtotime('+'.(int)$time_slots.' minute', strtotime($time_from)) > strtotime($time_to)){
			$this->error = _START_FINISH_TIME_ERROR;
			return false;
		}	
		return true;		
	}
	
	/**
	 * Check if there is a time overlapping
	 */
	private function CheckTimeOverlapping()
	{
		$rid = MicroGrid::GetParameter('rid');
		$schedule_id = MicroGrid::GetParameter('schedule_id', false);
		$week_day  = MicroGrid::GetParameter('week_day', false);
		$time_from = MicroGrid::GetParameter('time_from', false);
		$time_to   = MicroGrid::GetParameter('time_to', false);

		$sql = 'SELECT id FROM '.TABLE_SCHEDULE_TIMEBLOCKS.'
				WHERE
					id != '.(int)$rid.' AND
					schedule_id = '.(int)$schedule_id.' AND
					week_day = '.(int)$week_day.' AND 
					(						
						((\''.$time_from.'\' >= time_from) AND (\''.$time_from.'\' < time_to)) OR
						((\''.$time_to.'\' > time_from) AND (\''.$time_to.'\' <= time_to)) OR
						((\''.$time_from.'\' <= time_from) AND (\''.$time_to.'\' >= time_to))						
					) ';	
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			$this->error = _SCHEDULE_TIME_OVERLAPPING_ALERT;
			return false;
		}
		return true;
	}	
	
	/**
	 * Prepare schedule info
	 */
	private function DrawScheduleInfo($draw = true)
	{
		$objSchedule = new Schedules();
		$schedule_info = $objSchedule->GetScheduleInfoByID($this->schedule_id);
		
		$objDoctor = new Doctors();
		$doctor_info = $objDoctor->GetInfoByID((int)$schedule_info['doctor_id']);
		
		$output  = _DOCTOR.': <b>'.$doctor_info['first_name'].' '.$doctor_info['middle_name'].' '.$doctor_info['last_name'].'</b><br />';
		$output .= _SCHEDULE.': <b>'.$schedule_info['name'].' ('.$schedule_info['date_from'].' - '.$schedule_info['date_to'].')</b><br />';		
		$output .= '<br />';
		
		if($draw) echo $output;
		else return $output;
	}
}
?>