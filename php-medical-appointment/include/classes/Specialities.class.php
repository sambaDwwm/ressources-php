<?php

/**
 *	Class Specialities
 *  --------------
 *	Description : encapsulates specialities methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 05.02.2012
 *  Usage       : MedicalAppointment
 *
 *	PUBLIC:				  	STATIC:				 	PRIVATE:
 * 	------------------	  	---------------     	---------------
 *	__construct             GetAllActive            ValidateTranslationFields
 *	__destruct              DrawAllAsLinks
 *	BeforeInsertRecord      GetSpecialityName
 *	AfterInsertRecord 
 *	BeforeUpdateRecord
 *	AfterUpdateRecord
 *	AfterDeleteRecord
 *	
 **/


class Specialities extends MicroGrid {
	
	protected $debug = false;
	
	private $arrTranslations = '';		
	
	//==========================================================================
    // Class Constructor
	//==========================================================================
	function __construct()
	{		
		parent::__construct();
        
        global $objLogin;
		
		$this->params = array();
		
		## for standard fields
		if(isset($_POST['is_active']))  $this->params['is_active'] = prepare_input($_POST['is_active']); else $this->params['is_active'] = '0';
		
		## for checkboxes 
		//$this->params['field4'] = isset($_POST['field4']) ? prepare_input($_POST['field4']) : '0';


		$this->params['language_id'] = MicroGrid::GetParameter('language_id');
	
		//$this->uPrefix 		= 'prefix_';
		
		$this->primaryKey 	= 'id';
		$this->tableName 	= TABLE_SPECIALITIES; 
		$this->dataSet 		= array();
		$this->error 		= '';
		$this->formActionURL = 'index.php?admin=specialities_management';
		$this->actions      = array('add'=>true, 'edit'=>true, 'details'=>true, 'delete'=>true);
		$this->actionIcons  = true;
		$this->allowRefresh = true;
		$this->allowTopButtons = true;
		$this->alertOnDelete = ''; // leave empty to use default alerts

		$this->allowLanguages = false;
		$this->languageId  	= $objLogin->GetPreferredLang();
		$this->WHERE_CLAUSE = ''; // WHERE .... / 'WHERE language_id = \''.$this->languageId.'\'';				
		$this->ORDER_CLAUSE = 'ORDER BY sd.name ASC';
		
		$this->isAlterColorsAllowed = true;

		$this->isPagingAllowed = true;
		$this->pageSize = 50;

		$this->isSortingAllowed = true;

		$this->isExportingAllowed = false;
		$this->arrExportingTypes = array('csv'=>false);
		
		$this->isFilteringAllowed = true;
		// define filtering fields
		$this->arrFilteringFields = array(
			_NAME  => array('table'=>$this->tableName, 'field'=>'name', 'type'=>'text', 'sign'=>'%like%', 'width'=>'110px', 'visible'=>true),
			// 'Caption_2'  => array('table'=>'', 'field'=>'', 'type'=>'dropdownlist', 'source'=>array(), 'sign'=>'=|like%|%like|%like%', 'width'=>'130px', 'visible'=>true),
		);

		///$date_format = get_date_format('view');
		///$date_format_edit = get_date_format('edit');
		///$datetime_format = get_datetime_format();
		///$currency_format = get_currency_format();

		// prepare arrays
		$arr_is_active = array('0'=>'<span class=no>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');

		///////////////////////////////////////////////////////////////////////////////
		// prepare translation fields array
		$this->arrTranslations = $this->PrepareTranslateFields(
            array('name', 'description')
		);
		///////////////////////////////////////////////////////////////////////////////			

		///////////////////////////////////////////////////////////////////////////////			
		// prepare translations array for add/edit/detail modes
		$sql_translation_description = $this->PrepareTranslateSql(
            TABLE_SPECIALITIES_DESCRIPTION,
            'speciality_id',
            array('name', 'description')
		);
		///////////////////////////////////////////////////////////////////////////////			

		//---------------------------------------------------------------------- 
		// VIEW MODE
		// format: strip_tags
		// format: nl2br
		// format: 'format'=>'date', 'format_parameter'=>'M d, Y, g:i A'
		// format: 'format'=>'currency', 'format_parameter'=>'european|2' or 'format_parameter'=>'american|4'
		//---------------------------------------------------------------------- 
		$this->VIEW_MODE_SQL = 'SELECT
                                    s.'.$this->primaryKey.',
                                    s.is_active,
									sd.name,
									sd.description
								FROM '.$this->tableName.' s
									LEFT OUTER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' sd ON s.id = sd.speciality_id AND sd.language_id = \''.$this->languageId.'\'';		
		// define view mode fields
		$this->arrViewModeFields = array(
			'name'        => array('title'=>_NAME, 'type'=>'label', 'align'=>'left', 'width'=>'290px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'45', 'format'=>'', 'format_parameter'=>''),
			'description' => array('title'=>_DESCRIPTION, 'type'=>'label', 'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'80', 'format'=>'', 'format_parameter'=>''),
			'is_active'   => array('title'=>_ACTIVE, 'type'=>'enum',  'align'=>'center', 'width'=>'90px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_is_active),
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
			'is_active'   => array('title'=>_ACTIVE, 'type'=>'checkbox', 'readonly'=>false, 'true_value'=>'1', 'false_value'=>'0', 'default'=>'1'),
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
                                '.$sql_translation_description.'
                                '.$this->tableName.'.is_active
							FROM '.$this->tableName.'
							WHERE '.$this->tableName.'.'.$this->primaryKey.' = _RID_';		
		// define edit mode fields
		$this->arrEditModeFields = array(
			'is_active'   => array('title'=>_ACTIVE,  'type'=>'checkbox', 'readonly'=>false, 'true_value'=>'1', 'false_value'=>'0'),
		);

		//---------------------------------------------------------------------- 
		// DETAILS MODE
		//----------------------------------------------------------------------
		$this->DETAILS_MODE_SQL = $this->EDIT_MODE_SQL;
		$this->arrDetailsModeFields = array(
            'is_active'      => array('title'=>_ACTIVE, 'type'=>'enum', 'source'=>$arr_is_active),
		);

		///////////////////////////////////////////////////////////////////////////////
		// add translation fields to all modes
		$this->AddTranslateToModes(
            $this->arrTranslations,
            array('name'        => array('title'=>_NAME, 'type'=>'textbox', 'width'=>'410px', 'required'=>true, 'maxlength'=>'70', 'validation_maxlength'=>'255', 'readonly'=>false),
                  'description' => array('title'=>_DESCRIPTION, 'type'=>'textarea', 'width'=>'410px', 'height'=>'90px', 'required'=>false, 'maxlength'=>'', 'maxlength'=>'255', 'validation_maxlength'=>'255', 'readonly'=>false)
            )
		);
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
	 * Validate translation fields
	 */
	private function ValidateTranslationFields()	
	{
		// check for required fields		
		foreach($this->arrTranslations as $key => $val){			
			if($val['name'] == ''){
				$this->error = str_replace('_FIELD_', '<b>'._NAME.'</b>', _FIELD_CANNOT_BE_EMPTY);
				$this->errorField = 'name_'.$key;
				return false;
			}else if(strlen($val['name']) > 70){
				$this->error = str_replace('_FIELD_', '<b>'._NAME.'</b>', _FIELD_LENGTH_EXCEEDED);
				$this->error = str_replace('_LENGTH_', 70, $this->error);
				$this->errorField = 'name_'.$key;
				return false;
			}else if(strlen($val['description']) > 255){
				$this->error = str_replace('_FIELD_', '<b>'._DESCRIPTION.'</b>', _FIELD_LENGTH_EXCEEDED);
				$this->error = str_replace('_LENGTH_', 255, $this->error);
				$this->errorField = 'description_'.$key;
				return false;
			}			
		}		
		return true;		
	}

	/**
	 * Before-Insertion
	 */
	public function BeforeInsertRecord()
	{
		return $this->ValidateTranslationFields();
	}
    
	/**
	 * After-Insertion - add banner descriptions to description table
	 */
	public function AfterInsertRecord()
	{
		$sql = 'INSERT INTO '.TABLE_SPECIALITIES_DESCRIPTION.'(id, speciality_id, language_id, name, description) VALUES ';
		$count = 0;
		foreach($this->arrTranslations as $key => $val){
			if($count > 0) $sql .= ',';
			$sql .= '(NULL, '.$this->lastInsertId.', \''.$key.'\', \''.encode_text(prepare_input($val['name'])).'\', \''.encode_text(prepare_input($val['description'])).'\')';
			$count++;
		}
		if(database_void_query($sql)){
			return true;
		}else{
			return false;
		}
	}	

	/**
	 * Before-Updating operations
	 */
	public function BeforeUpdateRecord()
	{
		return $this->ValidateTranslationFields();
	}

	/**
	 * After-Updating
	 */
	public function AfterUpdateRecord()
	{
		foreach($this->arrTranslations as $key => $val){
			$sql = 'UPDATE '.TABLE_SPECIALITIES_DESCRIPTION.'
					SET name = \''.encode_text(prepare_input($val['name'])).'\',
						description = \''.encode_text(prepare_input($val['description'])).'\'
					WHERE speciality_id = '.$this->curRecordId.' AND language_id = \''.$key.'\'';
			database_void_query($sql);
		}
	}	

	/**
	 * After-Deleting 
	 */
	public function AfterDeleteRecord()
	{
		$sql = 'DELETE FROM '.TABLE_SPECIALITIES_DESCRIPTION.' WHERE speciality_id = '.$this->curRecordId;
		if(database_void_query($sql)){
			return true;
		}else{
			return false;
		}
	}
    
	/**
	 *	Returns all array of all active specialities
	 *		@param $where_clause
	 */
	public static function GetAllActive($where_clause = '')
	{		
		$sql = 'SELECT
					s.id,
                    sd.name,
                    sd.description 
				FROM '.TABLE_SPECIALITIES.' s
                    LEFT OUTER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' sd ON s.id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\'
				'.(($where_clause != '') ? 'WHERE '.$where_clause : '').'
				ORDER BY name ASC';			
		
		return database_query($sql, DATA_AND_ROWS);
	}
	
	/**
	 *	Draw all specialities as links
	 *		@param $draw
	 */
	public static function DrawAllAsLinks($draw = true)
	{
		$output = '';
		
		$result = self::GetAllActive();		
		$output .= '<h2>'._FIND_DOCTOR_BY_SPECIALITY.'</h2>';
		$output .= '<table id="tblDocSpecialities"><tr>';
		for($i=0; $i<$result[1]; $i++){
			if($i % 3 == 0) $output .= '</tr><tr>';
			$output .= '<td><a href="javascript:void(\'speciality|select\');" onclick="javascript:appFormSubmit(\'frmFindDoctors\',\'doctor_speciality='.$result[0][$i]['id'].'\')">'.$result[0][$i]['name'].'</a></td>';					
		}
		$output .= '</tr></table>';
		
		if($draw) echo $output;
		else return $output;
		
	}
	
	/**
	 *	Returns specialiy name by ID
	 *		@param $speciality_id
	 */
	public static function GetSpecialityName($speciality_id = 0)
	{		
		$sql = 'SELECT
					s.id,
                    sd.name,
                    sd.description 
				FROM '.TABLE_SPECIALITIES.' s
                    LEFT OUTER JOIN '.TABLE_SPECIALITIES_DESCRIPTION.' sd ON s.id = sd.speciality_id AND sd.language_id = \''.Application::Get('lang').'\'
				WHERE s.id = '.(int)$speciality_id;					
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
        return isset($result[0]['name']) ? $result[0]['name'] : '';            
	}	

}
?>