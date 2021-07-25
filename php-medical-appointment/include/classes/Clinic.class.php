<?php

/**
 *	Class Clinic 
 *  --------------
 *  Description : encapsulates clinic methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 16.05.2010
 *  Usage       : MedicalAppointment
 *
 *	PUBLIC:				  	STATIC:				 		PRIVATE:
 * 	------------------	  	---------------     		---------------
 *  __construct				DrawLocalTime
 *  __destruct              GetClinicInfo
 *                          GetClinicFullInfo
 *                          DrawAboutUs
 *	
 **/


class Clinic extends MicroGrid {
	
	protected $debug = false;
	
	//==========================================================================
    // Class Constructor
	//==========================================================================
	function __construct()
	{		
		global $objLogin;
		
		$this->params = array();
		
		## for standard fields
		if(isset($_POST['name']))    $this->params['name'] = prepare_input($_POST['name']);
		if(isset($_POST['address'])) $this->params['address'] = prepare_input($_POST['address']);
		if(isset($_POST['phone']))   $this->params['phone'] = prepare_input($_POST['phone']);
		if(isset($_POST['fax']))   	 $this->params['fax'] = prepare_input($_POST['fax']);
		if(isset($_POST['description'])) $this->params['description'] = prepare_input($_POST['description'], false, 'medium');
		if(isset($_POST['map_code'])) $this->params['map_code'] = prepare_input($_POST['map_code'], false, 'low');
		if(isset($_POST['time_zone']))   $this->params['time_zone'] = prepare_input($_POST['time_zone']);
		
		## for checkboxes 
		//if(isset($_POST['parameter4']))   $this->params['parameter4'] = $_POST['parameter4']; else $this->params['parameter4'] = '0';

		## for images
		//if(isset($_POST['icon'])){
		//	$this->params['icon'] = $_POST['icon'];
		//}else if(isset($_FILES['icon']['name']) && $_FILES['icon']['name'] != ''){
		//	// nothing 			
		//}else if (self::GetParameter('action') == 'create'){
		//	$this->params['icon'] = '';
		//}

		/// $this->params['language_id'] 	  = MicroGrid::GetParameter('language_id');
		
		$this->uPrefix 		= 'cln_';
		
		$this->primaryKey 	= 'id';
		$this->tableName 	= TABLE_CLINIC;
		$this->dataSet 		= array();
		$this->error 		= '';
		$this->formActionURL = 'index.php?admin=clinic_info';
		$this->actions      = array('add'=>false, 'edit'=>true, 'details'=>false, 'delete'=>false);
		$this->actionIcons  = false;
		$this->allowRefresh = true;

		$this->allowLanguages = false;
		///$this->languageId  	= ($this->params['language_id'] != '') ? $this->params['language_id'] : Languages::GetDefaultLang();
		$this->WHERE_CLAUSE = ''; // WHERE .... / 'WHERE language_id = \''.$this->languageId.'\'';				
		$this->ORDER_CLAUSE = ''; // ORDER BY '.$this->tableName.'.date_created DESC
		
		$this->isAlterColorsAllowed = true;

		$this->isPagingAllowed = true;
		$this->pageSize = 20;

		$this->isSortingAllowed = true;

		$this->isFilteringAllowed = false;
		// define filtering fields
		$this->arrFilteringFields = array(
			// 'Caption_1'  => array('table'=>'', 'field'=>'', 'type'=>'text', 'sign'=>'=|like%|%like|%like%', 'width'=>'80px'),
			// 'Caption_2'  => array('table'=>'', 'field'=>'', 'type'=>'dropdownlist', 'source'=>array(), 'sign'=>'=|like%|%like|%like%', 'width'=>'130px'),
		);

		if($objLogin->IsLoggedInAsAdmin()) $arr_time_zones = get_timezones_array();
		else $arr_time_zones = array();

		//---------------------------------------------------------------------- 
		// VIEW MODE
		//---------------------------------------------------------------------- 
		$this->VIEW_MODE_SQL = 'SELECT '.$this->primaryKey.',
									parameter1,
									parameter2,
									parameter3
								FROM '.$this->tableName;		
		// define view mode fields
		$this->arrViewModeFields = array(

		);
		
		//---------------------------------------------------------------------- 
		// ADD MODE
		// Validation Type: alpha|numeric|float|alpha_numeric|text|email
		// Validation Sub-Type: positive (for numeric and float)
		// Ex.: 'validation_type'=>'numeric', 'validation_type'=>'numeric|positive'
		//---------------------------------------------------------------------- 
		// define add mode fields
		$this->arrAddModeFields = array(		    

		);

		//---------------------------------------------------------------------- 
		// EDIT MODE
		// Validation Type: alpha|numeric|float|alpha_numeric|text|email
		// Validation Sub-Type: positive (for numeric and float)
		// Ex.: 'validation_type'=>'numeric', 'validation_type'=>'numeric|positive'
		//---------------------------------------------------------------------- 
		$this->EDIT_MODE_SQL = 'SELECT
								'.$this->tableName.'.'.$this->primaryKey.',
								'.$this->tableName.'.phone,
								'.$this->tableName.'.fax,
								'.$this->tableName.'.time_zone,
								'.$this->tableName.'.map_code,
								'.TABLE_CLINIC_DESCRIPTION.'.name,
								'.TABLE_CLINIC_DESCRIPTION.'.address,
								'.TABLE_CLINIC_DESCRIPTION.'.description								
							FROM '.$this->tableName.'
								INNER JOIN '.TABLE_CLINIC_DESCRIPTION.' ON '.$this->tableName.'.id = '.TABLE_CLINIC_DESCRIPTION.'.clinic_id
							WHERE '.$this->tableName.'.'.$this->primaryKey.' = _RID_';		
							
							///'.$this->tableName.'.'.$this->primaryKey.',							
		// define edit mode fields
		$this->arrEditModeFields = array(
			'phone'  		=> array('title'=>_PHONE, 'type'=>'textbox',  'required'=>false, 'width'=>'170px', 'readonly'=>false, 'maxlength'=>'32', 'default'=>'', 'validation_type'=>'text'),
			'fax'  		   	=> array('title'=>_FAX, 'type'=>'textbox',  'required'=>false, 'width'=>'170px', 'readonly'=>false, 'maxlength'=>'32', 'default'=>'', 'validation_type'=>'text'),
			'time_zone'     => array('title'=>_TIME_ZONE, 'type'=>'enum',  'required'=>true, 'width'=>'480px', 'readonly'=>false, 'source'=>$arr_time_zones),
			'map_code'      => array('title'=>_MAP_CODE, 'type'=>'textarea', 'required'=>false, 'width'=>'480px', 'height'=>'100px', 'editor_type'=>'simple', 'readonly'=>false, 'default'=>'', 'validation_type'=>'', 'validation_maxlength'=>'1024', 'unique'=>false),
		
		);

		//---------------------------------------------------------------------- 
		// DETAILS MODE
		//----------------------------------------------------------------------
		$this->DETAILS_MODE_SQL = $this->EDIT_MODE_SQL;
		$this->arrDetailsModeFields = array(

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
	 * Draws About Us block
	 * 		@param $draw
	 */
	public static function DrawAboutUs($draw = true)
	{		
		$lang = Application::Get('lang');		
		$output = '';
		
		$sql = 'SELECT
					h.phone,
					h.fax,
					h.map_code,	
					hd.name,									
					hd.address,
					hd.description 
				FROM '.TABLE_CLINIC.' h
					INNER JOIN '.TABLE_CLINIC_DESCRIPTION.' hd ON h.id = hd.clinic_id
					INNER JOIN '.TABLE_LANGUAGES.' l ON hd.language_id = l.abbreviation
				WHERE hd.language_id = \''.$lang.'\'';
				
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			$output .= '<h3>'.$result[0]['name'].'</h3>';
			$output .= $result[0]['description'].'<br />';		
			$output .= '<b>'._ADDRESS.'</b>: '.$result[0]['address'].'<br /><br />';
			$output .= '<b>'._PHONE.'</b>: '.$result[0]['phone'].'<br /><b>'._FAX.'</b>: '.$result[0]['fax'].'<br /><br />';
			if($result[0]['map_code']) $output .= '<b>'._OUR_LOCATION.'</b>:<br /> '.$result[0]['map_code'];
		}

		if($draw) echo $output;
		else return $output;
	}

	/**
	 * Draws Local Time block
	 * 		@param $draw
	 */
	public static function DrawLocalTime($draw = true)
	{
		global $objSettings;
		
		// set timezone
		//----------------------------------------------------------------------
		$clinicInfo = Clinic::GetClinicInfo();
		$time_offset_clinic = (isset($clinicInfo['time_zone'])) ? $clinicInfo['time_zone'] : 0;
		$time_offset_site = $objSettings->GetParameter('time_zone');
		$time_zome_diff = $time_offset_clinic - $time_offset_site;
		$time_with_offset = time() + $time_zome_diff * 3600;

		if(Application::Get('lang') != 'en'){
			$output1 = strftime(str_replace('%B', get_month_local(@strftime('%m', $time_with_offset)), '%d %B, %Y'), $time_with_offset);
			$output2 = strftime(str_replace('%A', get_weekday_local(@strftime('%w', $time_with_offset)+1), '%A %H:%M'), $time_with_offset);			
		}else{
			$output1 = @date('dS \of F Y', $time_with_offset);
			$output2 = @date('l g:i A', $time_with_offset);
		}
		$output = $output1.'<br />'.$output2;

		if($draw) echo $output;
		else return $output;
	}

	/**
	 * Returns Clinic info
	 */
	public static function GetClinicInfo()
	{
		$output = array();
		$sql = 'SELECT * FROM '.TABLE_CLINIC;		
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			$output = $result[0];
		}
		return $output;
	}

	/**
	 * Returns clinic_id full info
	 */
	public static function GetClinicFullInfo()
	{
		$output = array();
		$sql = 'SELECT
					h.*,
					hd.name,
					hd.address,
					hd.description
				FROM '.TABLE_CLINIC.' as h
					INNER JOIN '.TABLE_CLINIC_DESCRIPTION.' hd ON h.id = hd.clinic_id';
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			$output = $result[0];
		}
		return $output;
	}	

}
?>