<?php

/**
 *	Class DoctorAddresses 
 *  --------------
 *	Description : encapsulates doctor addresses methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 06.10.2012
 *  Usage       : MedicalAppointment
 *
 *	PUBLIC:				  	STATIC:				 	PRIVATE:
 * 	------------------	  	---------------     	---------------
 *	__construct             GetAddresses            UpdateCoordinates
 *	__destruct              GetDefaultAddress
 *	BeforeInsertRecord
 *	AfterInsertRecord
 *	AfterUpdateRecord
 *	AfterDeleteRecord
 *	
 **/


class DoctorAddresses extends MicroGrid {
	
	protected $debug = false;
	
	// #001 private $arrTranslations = '';
	//-----------------------------------------
	private $doctorId = '';
	
	//==========================================================================
    // Class Constructor
	// 		@param $doctor_id
	//      @param $account_type
	//==========================================================================
	function __construct($doctor_id = 0, $account_type = '')
	{		
		parent::__construct();
		
		global $objLogin;
        $this->doctorId = (int)$doctor_id;
		
		$this->params = array();		
		if(isset($_POST['doctor_id'])) $this->params['doctor_id'] = prepare_input($_POST['doctor_id']);
		if(isset($_POST['address'])) $this->params['address'] = prepare_input($_POST['address']);
		if(isset($_POST['latitude'])) $this->params['latitude'] = prepare_input($_POST['latitude']);
		if(isset($_POST['longitude'])) $this->params['longitude'] = prepare_input($_POST['longitude']);
		if(isset($_POST['access_level'])) $this->params['access_level'] = prepare_input($_POST['access_level']);
		if(isset($_POST['priority_order']))   $this->params['priority_order'] = prepare_input($_POST['priority_order']);
		## for checkboxes 
		if(isset($_POST['is_default'])) $this->params['is_default'] = (int)$_POST['is_default']; else $this->params['is_default'] = '0';
		if(isset($_POST['is_active']))  $this->params['is_active'] = (int)$_POST['is_active']; else $this->params['is_active'] = '0';
		
		//$this->params['language_id'] = MicroGrid::GetParameter('language_id');
	
		//$this->uPrefix 		= 'prefix_';
		
		$this->primaryKey 	= 'id';
		$this->tableName 	= TABLE_DOCTOR_ADDRESSES;
		$this->dataSet 		= array();
		$this->error 		= '';
		if($account_type == 'me'){
			$this->formActionURL = 'index.php?doctor=my_addresses';	
		}else{
			$this->formActionURL = 'index.php?admin=doctors_addresses&doc_id='.(int)$this->doctorId;	
		}		

		$action_allowed = true;
        // old - block adding addresses for doctors
        // ($objLogin->IsLoggedInAs('owner')) ? true : false;
		$this->actions = array(
			'add'=>$action_allowed,
			'edit'=>$action_allowed,
			'details'=>$action_allowed,
			'delete'=>$action_allowed
		);
		
		$this->actionIcons  = true;
		$this->allowRefresh = true;
		$this->allowTopButtons = false;
		$this->alertOnDelete = ''; // leave empty to use default alerts

		$this->allowLanguages = false;
		$this->languageId  	= '';
		$this->WHERE_CLAUSE = 'WHERE doctor_id = '.(int)$this->doctorId;
		$this->GROUP_BY_CLAUSE = ''; // GROUP BY '.$this->tableName.'.order_number
		$this->ORDER_CLAUSE = 'ORDER BY priority_order ASC';
		
		$this->isAlterColorsAllowed = true;

		$this->isPagingAllowed = true;
		$this->pageSize = 20;

		$this->isSortingAllowed = true;

		$this->isExportingAllowed = false;
		$this->arrExportingTypes = array('csv'=>false);
		
		$this->isFilteringAllowed = false;
		// define filtering fields
		$this->arrFilteringFields = array(
			// 'Caption_1'  => array('table'=>'', 'field'=>'', 'type'=>'text', 'sign'=>'=|>=|<=|like%|%like|%like%', 'width'=>'80px', 'visible'=>true),
			// 'Caption_2'  => array('table'=>'', 'field'=>'', 'type'=>'dropdownlist', 'source'=>array(), 'sign'=>'=|>=|<=|like%|%like|%like%', 'width'=>'130px', 'visible'=>true),
			// 'Caption_3'  => array('table'=>'', 'field'=>'', 'type'=>'calendar', 'date_format'=>'dd/mm/yyyy|mm/dd/yyyy|yyyy/mm/dd', 'sign'=>'=|>=|<=|like%|%like|%like%', 'width'=>'80px', 'visible'=>true),
		);

		///$this->isAggregateAllowed = false;
		///// define aggregate fields for View Mode
		///$this->arrAggregateFields = array(
		///	'field1' => array('function'=>'SUM', 'align'=>'center', 'aggregate_by'=>'', 'decimal_place'=>2),
		///	'field2' => array('function'=>'AVG', 'align'=>'center', 'aggregate_by'=>'', 'decimal_place'=>2),
		///);

		///$date_format = get_date_format('view');
		///$date_format_settings = get_date_format('view', true); /* to get pure settings format */
		///$date_format_edit = get_date_format('edit');
		///$datetime_format = get_datetime_format();
		///$time_format = get_time_format(); /* by default 1st param - shows seconds */
		///$currency_format = get_currency_format();

		// prepare languages array		
		/// $total_languages = Languages::GetAllActive();
		/// $arr_languages      = array();
		/// foreach($total_languages[0] as $key => $val){
		/// 	$arr_languages[$val['abbreviation']] = $val['lang_name'];
		/// }

		$arr_is_active = array('0'=>'<span class=no>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');
		$arr_is_default = array('0'=>'<span class=gray>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');
		$arr_access_levels = array('public'=>_PUBLIC, 'registered'=>_REGISTERED);

		///////////////////////////////////////////////////////////////////////////////
		// #002. prepare translation fields array
		/// $this->arrTranslations = $this->PrepareTranslateFields(
		///	array('field1', 'field2')
		/// );
		///////////////////////////////////////////////////////////////////////////////			

		///////////////////////////////////////////////////////////////////////////////			
		// #003. prepare translations array for add/edit/detail modes
		/// REMEMBER! to add '.$sql_translation_description.' in EDIT_MODE_SQL
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
		$this->VIEW_MODE_SQL = 'SELECT '.$this->primaryKey.',
									doctor_id,
									address,
									latitude,
									longitude,
									priority_order,
									access_level,
									is_default,
									is_active
								FROM '.$this->tableName;		
		// define view mode fields
		$this->arrViewModeFields = array(
			//'doctor_id'  => array('title'=>'', 'type'=>'hidden', 'required'=>true, 'readonly'=>false, 'default'=>$this->doctorId),
			'address'        => array('title'=>_ADDRESS, 'type'=>'label', 'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'tooltip'=>'', 'maxlength'=>'60', 'format'=>'', 'format_parameter'=>''),
			'latitude'       => array('title'=>_LATITUDE, 'type'=>'label', 'align'=>'center', 'width'=>'80px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>(($account_type == 'me') ? false : true), 'tooltip'=>'', 'maxlength'=>'11', 'format'=>'', 'format_parameter'=>''),
			'longitude'      => array('title'=>_LONGITUDE, 'type'=>'label', 'align'=>'center', 'width'=>'80px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>(($account_type == 'me') ? false : true), 'tooltip'=>'', 'maxlength'=>'11', 'format'=>'', 'format_parameter'=>''),
			'access_level'   => array('title'=>_ACCESS, 'type'=>'enum',  'align'=>'center', 'width'=>'110px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_access_levels),
			'priority_order' => array('title'=>_ORDER, 'type'=>'label', 'align'=>'center', 'width'=>'80px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>'', 'movable'=>true),
			'is_default'     => array('title'=>_DEFAULT, 'type'=>'enum',  'align'=>'center', 'width'=>'90px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_is_default),
			'is_active'      => array('title'=>_ACTIVE, 'type'=>'enum',  'align'=>'center', 'width'=>'90px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_is_active),
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
			'address'        => array('title'=>_ADDRESS, 'type'=>'textarea', 'width'=>'310px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'255', 'validation_maxlength'=>'255', 'default'=>'', 'height'=>'90px', 'editor_type'=>'simple', 'validation_type'=>'', 'unique'=>false),
			'latitude'       => array('title'=>_LATITUDE, 'type'=>'textbox', 'required'=>false, 'width'=>'120px', 'readonly'=>false, 'maxlength'=>'10', 'default'=>'0', 'validation_type'=>'float', 'unique'=>false, 'visible'=>true),			
			'longitude'      => array('title'=>_LONGITUDE, 'type'=>'textbox', 'required'=>false, 'width'=>'120px', 'readonly'=>false, 'maxlength'=>'10', 'default'=>'0', 'validation_type'=>'float', 'unique'=>false, 'visible'=>true),			
			'priority_order' => array('title'=>_ORDER, 'type'=>'textbox', 'required'=>true, 'width'=>'40px', 'readonly'=>false, 'maxlength'=>'2', 'default'=>'0', 'validation_type'=>'numeric|positive', 'unique'=>false, 'visible'=>true),			
			'access_level'   => array('title'=>_ACCESS, 'type'=>'enum',     'width'=>'',      'required'=>true, 'readonly'=>false, 'default'=>'public', 'source'=>$arr_access_levels, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>'', 'view_type'=>'dropdownlist', 'multi_select'=>false),
			'is_default'     => array('title'=>_DEFAULT, 'type'=>'checkbox', 'readonly'=>false, 'default'=>'0', 'true_value'=>'1', 'false_value'=>'0'),
			'is_active'      => array('title'=>_ACTIVE, 'type'=>'checkbox', 'readonly'=>false, 'default'=>'1', 'true_value'=>'1', 'false_value'=>'0'),

			'doctor_id'      => array('title'=>'', 'type'=>'hidden', 'required'=>true, 'readonly'=>false, 'default'=>$this->doctorId),										
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
								'.$this->tableName.'.address,
								'.$this->tableName.'.latitude,
								'.$this->tableName.'.longitude,
								'.$this->tableName.'.priority_order,
								'.$this->tableName.'.access_level,
								'.$this->tableName.'.is_default,
								'.$this->tableName.'.is_active
							FROM '.$this->tableName.'
							WHERE '.$this->tableName.'.'.$this->primaryKey.' = _RID_';		
		// define edit mode fields
		$this->arrEditModeFields = array(
			'address'        => array('title'=>_ADDRESS, 'type'=>'textarea', 'width'=>'310px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'255', 'validation_maxlength'=>'255', 'default'=>'', 'height'=>'90px', 'editor_type'=>'simple', 'validation_type'=>'', 'unique'=>false),
			'latitude'       => array('title'=>_LATITUDE, 'type'=>'textbox', 'required'=>false, 'width'=>'120px', 'readonly'=>false, 'maxlength'=>'10', 'default'=>'0', 'validation_type'=>'float', 'unique'=>false, 'visible'=>true),			
			'longitude'      => array('title'=>_LONGITUDE, 'type'=>'textbox', 'required'=>false, 'width'=>'120px', 'readonly'=>false, 'maxlength'=>'10', 'default'=>'0', 'validation_type'=>'float', 'unique'=>false, 'visible'=>true),			
			'priority_order' => array('title'=>_ORDER, 'type'=>'textbox', 'required'=>true, 'width'=>'40px', 'readonly'=>false, 'maxlength'=>'2', 'default'=>'0', 'validation_type'=>'numeric|positive', 'unique'=>false, 'visible'=>true),
			'access_level'   => array('title'=>_ACCESS, 'type'=>'enum',     'width'=>'',      'required'=>true, 'readonly'=>false, 'default'=>'', 'source'=>$arr_access_levels, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>'', 'view_type'=>'dropdownlist', 'multi_select'=>false),
			'is_default'     => array('title'=>_DEFAULT, 'type'=>'checkbox', 'readonly'=>false, 'default'=>'0', 'true_value'=>'1', 'false_value'=>'0'),
			'is_active'      => array('title'=>_ACTIVE, 'type'=>'checkbox', 'readonly'=>false, 'default'=>'1', 'true_value'=>'1', 'false_value'=>'0'),
		);

		//---------------------------------------------------------------------- 
		// DETAILS MODE
		//----------------------------------------------------------------------
		$this->DETAILS_MODE_SQL = $this->EDIT_MODE_SQL;
		$this->arrDetailsModeFields = array(
			'address'    => array('title'=>_ADDRESS, 'type'=>'label', 'visible'=>true),
			'latitude'       => array('title'=>_LATITUDE, 'type'=>'label', 'visible'=>true),
			'longitude'      => array('title'=>_LONGITUDE, 'type'=>'label', 'visible'=>true),
			'priority_order' => array('title'=>_ORDER, 'type'=>'label'),
			'access_level'   => array('title'=>_ACCESS, 'type'=>'enum', 'source'=>$arr_access_levels),			
			'is_default' => array('title'=>_DEFAULT, 'type'=>'enum', 'source'=>$arr_is_default),
			'is_active'  => array('title'=>_ACTIVE, 'type'=>'enum', 'source'=>$arr_is_active),
		);

		///////////////////////////////////////////////////////////////////////////////
		// #004. add translation fields to all modes
		/// $this->AddTranslateToModes(
		/// $this->arrTranslations,
		/// array('name'        => array('title'=>_NAME, 'type'=>'textbox', 'width'=>'410px', 'required'=>true, 'maxlength'=>'', 'readonly'=>false),
		/// 	  'description' => array('title'=>_DESCRIPTION, 'type'=>'textarea', 'width'=>'410px', 'height'=>'90px', 'required'=>false, 'maxlength'=>'', 'maxlength'=>'512', 'validation_maxlength'=>'512', 'readonly'=>false)
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
	public function BeforeInsertRecord()
	{
		// check if we reached the maximum allowed addresses
		$arr_added_addresses = self::GetAddresses($this->doctorId);

		// get maximum allowed addresses for this doctor
        $doctor_membership_info = Doctors::GetMembershipInfo($this->doctorId);
        $maximum_addresses = isset($doctor_membership_info['addresses_count']) ? (int)$doctor_membership_info['addresses_count'] : 0;

        if($arr_added_addresses[1] >= $maximum_addresses){
			$this->error = _DOCTOR_MAX_ADDRESSES_ALERT;
			return false;
		}
		return true;
	}

    /**
	 * After-Insertion Record
	 */
	public function AfterInsertRecord()
	{
		$is_default = MicroGrid::GetParameter('is_default', false);
		$latitude = MicroGrid::GetParameter('latitude', false);
		$longitude = MicroGrid::GetParameter('longitude', false);
		$address = MicroGrid::GetParameter('address', false);
		
		if(empty($latitude) && empty($longitude) && !empty($address)){
			$this->UpdateCoordinates($this->lastInsertId, $address, $latitude, $longitude);
		}
		
		if($is_default == '1'){
			$sql = 'UPDATE '.TABLE_DOCTOR_ADDRESSES.'
					SET is_default = 0
				    WHERE doctor_id = '.(int)$this->doctorId.' AND id != '.(int)$this->lastInsertId;
			database_void_query($sql);
			return true;
		}
	}

	/**
	 * After-Updating Record
	 */
	public function AfterUpdateRecord()
	{
		$latitude = MicroGrid::GetParameter('latitude', false);
		$longitude = MicroGrid::GetParameter('longitude', false);
		$address = MicroGrid::GetParameter('address', false);
		
		if(empty($latitude) && empty($longitude) && !empty($address)){
			$this->UpdateCoordinates($this->curRecordId, $address, $latitude, $longitude);
		}

		$sql = 'SELECT id, is_active FROM '.TABLE_DOCTOR_ADDRESSES.' WHERE doctor_id = '.(int)$this->doctorId;
		if($result = database_query($sql, DATA_AND_ROWS, ALL_ROWS)){
			if($result[1] == 1){
				// make last address always be default
				$sql = 'UPDATE '.TABLE_DOCTOR_ADDRESSES.'
						SET is_default = 1
						WHERE doctor_id = '.(int)$this->doctorId.' AND id = '.(int)$result[0][0]['id'];
				database_void_query($sql);
			}else{
				// save all other addresses to be not default
				$rid = MicroGrid::GetParameter('rid');
				$is_default = MicroGrid::GetParameter('is_default', false);
				if($is_default == '1'){
					$sql = 'UPDATE '.TABLE_DOCTOR_ADDRESSES.'
							SET is_active = 1
							WHERE doctor_id = '.(int)$this->doctorId.' AND id = '.(int)$rid;
					database_void_query($sql);
					
					$sql = 'UPDATE '.TABLE_DOCTOR_ADDRESSES.'
							SET is_default = 0
							WHERE doctor_id = '.(int)$this->doctorId.' AND id != '.(int)$rid;
					database_void_query($sql);
				}
			}
		}
	    return true;	
	}	

	/**
	 * After-Updating Record
	 */
	public function AfterDeleteRecord()
	{
		$sql = 'SELECT id, is_active FROM '.TABLE_DOCTOR_ADDRESSES.' WHERE doctor_id = '.(int)$this->doctorId;
		if($result = database_query($sql, DATA_AND_ROWS, ALL_ROWS)){
			if($result[1] == 1){
				// make last address always be default
				$sql = 'UPDATE '.TABLE_DOCTOR_ADDRESSES.'
						SET is_default = 1
						WHERE doctor_id = '.(int)$this->doctorId.' AND id = '.(int)$result[0][0]['id'];
				database_void_query($sql);
			}
		}
	    return true;	
	}	
	
	/**
	 * Returns all addresses of a specific doctor
	 * 		@param $doctor_id
	 * 		@param $access_level
	 * 		@param $address_id
	 */
	public static function GetAddresses($doctor_id = '', $access_level = 'all', $address_id = '')
	{
		global $objLogin;
		$output = array();
		
		$sql = 'SELECT id, address, latitude, longitude
				FROM '.TABLE_DOCTOR_ADDRESSES.'
				WHERE
					doctor_id = '.(int)$doctor_id.' AND 
					'.(!empty($address_id) ? ' id = '.(int)$address_id.' AND ' : '').'
					'.($access_level == 'public' ? ' access_level = \'public\' AND ' : '').'
					is_active = 1					
				ORDER BY
					is_default DESC,
					priority_order ASC';
		if($result = database_query($sql, DATA_AND_ROWS, ALL_ROWS)){
			$output = $result;
		}
		
		return $output;		
	}
	
	/**
	 * Returns all addresses of a specific doctor
	 * 		@param $doctor_id
	 */
	public static function GetDefaultAddress($doctor_id = '')
	{
		$output = '';
		
		$sql = 'SELECT id, address, latitude, longitude
				FROM '.TABLE_DOCTOR_ADDRESSES.'
				WHERE doctor_id = '.(int)$doctor_id.' AND is_active = 1
				ORDER BY is_default DESC, priority_order ASC';
			
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);			
		if($result[1] > 0){
			$output = $result[0];
		}
		
		return $output;		
	}
	
	/**
	 * Updates doctor address coordinates
	 */
	private function UpdateCoordinates($id = 0, $address='', $longitude = 0, $latitude = 0)
	{
		global $objLogin;
		
		$key = ModulesSettings::Get('google_maps', 'api_key'); /* your API key */
		if(!empty($key)){
			$address = urlencode($address);

            $url = 'http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false&key='.$key; /*.$key*/
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response_a = json_decode($response);
            $latitude = $response_a->results[0]->geometry->location->lat;
            $longitude = $response_a->results[0]->geometry->location->lng;
			
			if(!empty($latitude) || !empty($longitude)){
				$sql = 'UPDATE '.TABLE_DOCTOR_ADDRESSES.'
						SET latitude = \''.$latitude.'\', longitude = \''.$longitude.'\' 
						WHERE
							'.(!$objLogin->IsLoggedIn() ? 'access_level = \'public\' AND ' : '').'
							id = '.(int)$id;
				database_void_query($sql);
				return true;
			}else{
				self::$static_error = _COORDINATES_UPDATE_ERROR;
                self::$static_error .= ($response_a->error_message != '') ? '<br>Google error description: '.$response_a->error_message : '';
				return false;
			}			
		}else{
			self::$static_error = _GOOGLE_MAPS_API_NOT_SET;
			return false;
		}
	}
	
}

?>