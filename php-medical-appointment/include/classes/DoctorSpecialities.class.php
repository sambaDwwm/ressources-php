<?php

/**
 *	Class DoctorSpecialities
 *  --------------
 *	Description : encapsulates doctor specialities methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 28.01.2014
 *  Usage       : MedicalAppointment
 *
 *	PUBLIC				  	STATIC				 		PRIVATE
 * 	------------------	  	---------------     		---------------
 *	__construct             GetSpecialities
 *	__destruct              SpecialitiesCount 
 *	AfterInsertRecord
 *	AfterUpdateRecord
 *	AfterDeleteRecord
 *	
 **/


class DoctorSpecialities extends MicroGrid {
	
	protected $debug = false;
	
	//-----------------------------------------
	private $doctorID = '';

	//==========================================================================
    // Class Constructor
	//		@param $doctor_id
	//      @param $account_type
	//==========================================================================
	function __construct($doctor_id = 0, $account_type = '')
	{		
		parent::__construct();
		
		global $objLogin; 
		
		$this->params = array();
		
		## for standard fields
		if(isset($_POST['speciality_id'])) $this->params['speciality_id'] = prepare_input($_POST['speciality_id']);
		if(isset($_POST['doctor_id'])) $this->params['doctor_id'] = prepare_input($_POST['doctor_id']);
		if(isset($_POST['priority_order'])) $this->params['priority_order'] = prepare_input($_POST['priority_order']);
		if(isset($_POST['visit_price'])) $this->params['visit_price'] = prepare_input($_POST['visit_price']);
		
		## for checkboxes 
		if(isset($_POST['is_default'])) $this->params['is_default'] = (int)$_POST['is_default']; else $this->params['is_default'] = '0';

		$this->doctorID = (int)$doctor_id;
		
		//$this->params['language_id'] = MicroGrid::GetParameter('language_id');	
		//$this->uPrefix 		= 'prefix_';
		
		$this->primaryKey 	= 'id';
		$this->tableName 	= TABLE_DOCTOR_SPECIALITIES;
		$this->dataSet 		= array();
		$this->error 		= '';
		if($account_type == 'me'){
			$this->formActionURL = 'index.php?doctor=my_specialities';	
		}else{
			$this->formActionURL = 'index.php?admin=doctors_specialities&doc_id='.(int)$doctor_id;
		}
		
        $this->actions = array();
        if($objLogin->IsLoggedInAs('owner','mainadmin')){
            $this->actions = array(
                'add'=>true,
                'edit'=>(($doctor_id > 0) ? true : false),
                'details'=>true,
                'delete'=>true
            );            
        }else if($objLogin->IsLoggedInAsDoctor()){
            $this->actions = array(
                'add'=>true,
                'edit'=>true,
                'details'=>true,
                'delete'=>true
            );
        }
        
		$this->actionIcons  = true;
		$this->allowRefresh = true;
		$this->allowTopButtons = false;
		$this->alertOnDelete = ''; // leave empty to use default alerts

		$this->allowLanguages = false;
		$this->languageId  	=  $objLogin->GetPreferredLang();
		$this->WHERE_CLAUSE = ''; // WHERE .... / 'WHERE language_id = ''.$this->languageId.''';				
        $this->ORDER_CLAUSE = 'ORDER BY ds.priority_order ASC';
		
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

		///$date_format = get_date_format('view');
		///$date_format_edit = get_date_format('edit');				
		///$currency_format = get_currency_format();
		$pre_currency_symbol = ((Application::Get('currency_symbol_place') == 'before') ? Application::Get('currency_symbol') : '');
		$post_currency_symbol = ((Application::Get('currency_symbol_place') == 'after') ? Application::Get('currency_symbol') : '');
        $doctor_info = Doctors::GetDoctorInfoById($this->doctorID);
        $default_visit_price = isset($doctor_info[0]['default_visit_price']) ? $doctor_info[0]['default_visit_price'] : 0;

		// prepare specialities array
		$total_specialities = Specialities::GetAllActive('s.id NOT IN (SELECT speciality_id FROM '.$this->tableName.' WHERE doctor_id = '.(int)$doctor_id.')');
		$arr_specialities = array();
		foreach($total_specialities[0] as $key => $val){
		 	$arr_specialities[$val['id']] = $val['name'];
		}

		$arr_is_default = array('0'=>'<span class=gray>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');
		
		//---------------------------------------------------------------------- 
		// VIEW MODE
		// format: strip_tags
		// format: nl2br
		// format: 'format'=>'date', 'format_parameter'=>'M d, Y, g:i A''
		// format: 'format'=>'currency', 'format_parameter'=>'european|2' or 'format_parameter'=>'american|4'
		//---------------------------------------------------------------------- 
		$this->VIEW_MODE_SQL = 'SELECT
									ds.'.$this->primaryKey.',
                                    ds.visit_price,
                                    ds.priority_order,
									ds.is_default,
									sd.name,
									sd.description
								FROM '.$this->tableName.' ds
									INNER JOIN '.TABLE_DOCTORS.' d ON ds.doctor_id = d.id
									INNER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' sd ON ds.speciality_id = sd.speciality_id AND sd.language_id = \''.$this->languageId.'\'
								WHERE
									ds.doctor_id = '.$doctor_id;		
		// define view mode fields
		$this->arrViewModeFields = array(
			'name'           => array('title'=>_NAME, 'type'=>'label', 'align'=>'left', 'width'=>'25%', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'40', 'format'=>'', 'format_parameter'=>''),
			'description'    => array('title'=>_DESCRIPTION, 'type'=>'label', 'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'100', 'format'=>'', 'format_parameter'=>'', 'visible'=>($objLogin->IsLoggedInAsAdmin() ? true : false)),
			'visit_price'    => array('title'=>_VISIT_PRICE, 'type'=>'label', 'align'=>'center', 'width'=>'90px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'100', 'format'=>'', 'format_parameter'=>'', 'pre_html'=>$pre_currency_symbol, 'post_html'=>$post_currency_symbol),
			'priority_order' => array('title'=>_ORDER, 'type'=>'label', 'align'=>'center', 'width'=>'90px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>'', 'movable'=>true),
			'is_default'     => array('title'=>_DEFAULT, 'type'=>'enum', 'align'=>'center', 'width'=>'90px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_is_default),
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
			'speciality_id' => array('title'=>_SPECIALITY, 'type'=>'enum', 'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'', 'source'=>$arr_specialities, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),			
			'doctor_id'     => array('title'=>'', 'type'=>'hidden', 'required'=>true, 'readonly'=>false, 'default'=>$doctor_id),										
			'visit_price'   => array('title'=>_VISIT_PRICE, 'type'=>'textbox',  'width'=>'85px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'10', 'default'=>$default_visit_price, 'validation_type'=>'float|positive', 'unique'=>false, 'visible'=>true, 'pre_html'=>$pre_currency_symbol, 'post_html'=>$post_currency_symbol),
			'priority_order'=> array('title'=>_ORDER, 'type'=>'textbox', 'width'=>'50px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'2', 'default'=>'0', 'validation_type'=>'numeric|positive', 'unique'=>false, 'visible'=>true),
			'is_default'    => array('title'=>_DEFAULT, 'type'=>'checkbox', 'readonly'=>false, 'default'=>'0', 'true_value'=>'1', 'false_value'=>'0'),
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
								ds.'.$this->primaryKey.',
								ds.doctor_id,
                                ds.visit_price,
                                ds.priority_order,
								ds.is_default,
								ds.speciality_id,
								sd.name as speciality_name,
								sd.description as speciality_description
							FROM '.$this->tableName.' ds
								INNER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' sd ON ds.speciality_id = sd.speciality_id AND sd.language_id = \''.$this->languageId.'\'
							WHERE ds.'.$this->primaryKey.' = _RID_';		
		// define edit mode fields
		$this->arrEditModeFields = array(
			'speciality_name'=> array('title'=>_SPECIALITY, 'type'=>'label'),
			'speciality_description' => array('title'=>_DESCRIPTION, 'type'=>'label'),
			'visit_price'    => array('title'=>_VISIT_PRICE, 'type'=>'textbox',  'width'=>'85px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'10', 'default'=>'0', 'validation_type'=>'float|positive', 'unique'=>false, 'visible'=>true, 'pre_html'=>$pre_currency_symbol.' ', 'post_html'=>$post_currency_symbol),
			'priority_order' => array('title'=>_ORDER, 'type'=>'textbox',  'width'=>'50px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'2', 'default'=>'0', 'validation_type'=>'numeric|positive', 'unique'=>false, 'visible'=>true),
			'is_default'     => array('title'=>_DEFAULT, 'type'=>'checkbox', 'readonly'=>false, 'default'=>'0', 'true_value'=>'1', 'false_value'=>'0'),
			'doctor_id'      => array('title'=>'', 'type'=>'hidden', 'required'=>true, 'readonly'=>false, 'default'=>$doctor_id),										
		);

		//---------------------------------------------------------------------- 
		// DETAILS MODE
		//----------------------------------------------------------------------
		$this->DETAILS_MODE_SQL = $this->EDIT_MODE_SQL;
		$this->arrDetailsModeFields = array(
			'speciality_name'        => array('title'=>_SPECIALITY, 'type'=>'label'),
			'speciality_description' => array('title'=>_DESCRIPTION, 'type'=>'label'),
			'visit_price'            => array('title'=>_VISIT_PRICE, 'type'=>'label', 'pre_html'=>$pre_currency_symbol, 'post_html'=>$post_currency_symbol),
			'priority_order'         => array('title'=>_ORDER, 'type'=>'label'),
			'is_default'             => array('title'=>_DEFAULT, 'type'=>'enum', 'source'=>$arr_is_default),
		);
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
	 * After-Insertion Record
	 */
	public function AfterInsertRecord()
	{
		$is_default = MicroGrid::GetParameter('is_default', false);
		
		if($is_default == '1'){
			$sql = 'UPDATE '.TABLE_DOCTOR_SPECIALITIES.'
					SET is_default = 0
				    WHERE doctor_id = '.(int)$this->doctorID.' AND id != '.(int)$this->lastInsertId;
			database_void_query($sql);
			return true;
		}
	}
	
	/**
	 * After-Updating Record
	 */
	public function AfterUpdateRecord()
	{
		$sql = 'SELECT id, is_default FROM '.TABLE_DOCTOR_SPECIALITIES.' WHERE doctor_id = '.(int)$this->doctorID;
		if($result = database_query($sql, DATA_AND_ROWS, ALL_ROWS)){
			if($result[1] == 1){
				// make last specialities always be default
				$sql = 'UPDATE '.TABLE_DOCTOR_SPECIALITIES.'
						SET is_default = 1
						WHERE doctor_id = '.(int)$this->doctorID;
				database_void_query($sql);
			}else{
				// save all other specialities to be not default
				$rid = MicroGrid::GetParameter('rid');
				$is_default = MicroGrid::GetParameter('is_default', false);
				if($is_default == '1'){
					$sql = 'UPDATE '.TABLE_DOCTOR_SPECIALITIES.'
							SET is_default = 0
							WHERE doctor_id = '.(int)$this->doctorID.' AND id != '.(int)$rid;
					database_void_query($sql);
				}
			}
		}
	    return true;			
	}	

    /**
	 * After-Deleting Record
	 */
	public function AfterDeleteRecord()
	{
		$sql = 'SELECT id, is_default FROM '.TABLE_DOCTOR_SPECIALITIES.' WHERE doctor_id = '.(int)$this->doctorID;
		if($result = database_query($sql, DATA_AND_ROWS, ALL_ROWS)){
			if($result[1] == 1){
				// make last specialities always be default
				$sql = 'UPDATE '.TABLE_DOCTOR_SPECIALITIES.'
						SET is_default = 1
						WHERE doctor_id = '.(int)$this->doctorID;
				database_void_query($sql);
			}
		}
	    return true;			
	}

	/**
	 * Returns all specialities of a specific doctor
	 * 		@param $doctor_id
	 * 		@param $speciality_id
	 */
	public static function GetSpecialities($doctor_id, $speciality_id = '')
	{
		$output = array();		
		$sql = 'SELECT
					ds.id,
                    ds.visit_price,
					sd.name,
					sd.description
				FROM '.TABLE_DOCTOR_SPECIALITIES.' ds
					INNER JOIN '.TABLE_DOCTORS.' d ON ds.doctor_id = d.id
					INNER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' sd ON ds.speciality_id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\' 
				WHERE ds.doctor_id = '.(int)$doctor_id.'
                '.(($speciality_id != '') ? ' AND ds.speciality_id = '.(int)$speciality_id : '').'
                ORDER BY ds.priority_order ASC';		

		if($result = database_query($sql, DATA_AND_ROWS, (($speciality_id != '') ? FIRST_ROW_ONLY : ALL_ROWS))){
			$output = $result;
		}
		
		return $output;		
	}

	/**
	 * Returns a count of specialities for given doctor
	 * 		@param $doctor_id
	 */
	public static function SpecialitiesCount($doctor_id)
	{
		$output = array();		
		$sql = 'SELECT COUNT(*) as cnt
				FROM '.TABLE_DOCTOR_SPECIALITIES.' ds
					INNER JOIN '.TABLE_DOCTORS.' d ON ds.doctor_id = d.id
				WHERE ds.doctor_id = '.(int)$doctor_id;		
        $result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if(isset($result[0]['cnt'])){
			return $result[0]['cnt'];
		}
		
		return 0;
	}
	
}

?>