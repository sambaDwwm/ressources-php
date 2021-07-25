<?php

/**
 *	Class Clinic Description
 *  -------------- 
 *  Description : encapsulates clinic description methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 22.11.2012
 *  Usage       : MedicalAppointment
 *
 *	PUBLIC:				  	STATIC:				 		PRIVATE:
 * 	------------------	  	---------------     		---------------
 *  __construct				
 *  __destruct              
 *	
 **/


class ClinicDescription extends MicroGrid {
	
	protected $debug = false;
	
	//==========================================================================
    // Class Constructor
	//==========================================================================
	function __construct()
	{		
		$room_id 	= isset($_GET['room_id']) ? (int)$_GET['room_id'] : '0';
		
		$this->params = array();		
		if(isset($_POST['name'])) $this->params['name'] = prepare_input($_POST['name']);
		if(isset($_POST['address'])) $this->params['address'] = prepare_input($_POST['address']);
		if(isset($_POST['description'])) $this->params['description'] = prepare_input($_POST['description'], false, 'medium');

		//$default_lang = Languages::GetDefaultLang();
		//$default_currency = Currencies::GetDefaultCurrency();	
		
		
		// for checkboxes
		/// if(isset($_POST['parameter4']))   $this->params['parameter4'] = $_POST['parameter4']; else $this->params['parameter4'] = '0';
		
		//$this->params['language_id'] 	  = MicroGrid::GetParameter('language_id');
	
		$this->primaryKey 	= 'id';
		$this->tableName 	= TABLE_CLINIC_DESCRIPTION;
		$this->dataSet 		= array();
		$this->error 		= '';
		$this->formActionURL = 'index.php?admin=clinic_info';
		$this->actions      = array('add'=>false, 'edit'=>true, 'details'=>true, 'delete'=>false);
		$this->actionIcons  = true;
		
		$this->allowLanguages = false;
		$this->languageId  	= ''; //($this->params['language_id'] != '') ? $this->params['language_id'] : Languages::GetDefaultLang();
		$this->WHERE_CLAUSE = 'WHERE '.$this->tableName.'.clinic_id = \'1\'';
		$this->ORDER_CLAUSE = 'ORDER BY '.$this->tableName.'.id ASC';
		
		$this->isAlterColorsAllowed = true;
        
		$this->isPagingAllowed = false;
		$this->pageSize = 100;
        
		$this->isSortingAllowed = true;
        
		$this->isFilteringAllowed = false;
		// define filtering fields
		// $this->arrFilteringFields = array(
		//	'parameter1' => array('title'=>'',  'type'=>'text', 'sign'=>'=|like%|%like|%like%', 'width'=>'80px'),
		//	'parameter2'  => array('title'=>'',  'type'=>'text', 'sign'=>'=|like%|%like|%like%', 'width'=>'80px'),
		// );
		
		// prepare languages array
		//$total_languages = Languages::GetAllActive();
		//$arr_languages      = array();
		//foreach($total_languages[0] as $key => $val){
		//	$arr_languages[$val['abbreviation']] = $val['lang_name'];
		//}
		

		//---------------------------------------------------------------------- 
		// VIEW MODE
		//---------------------------------------------------------------------- 
		$this->VIEW_MODE_SQL = 'SELECT '.$this->tableName.'.'.$this->primaryKey.',
									'.$this->tableName.'.clinic_id,
									'.$this->tableName.'.language_id,
									'.$this->tableName.'.name,									
									'.$this->tableName.'.address,
									'.$this->tableName.'.description, 
									'.TABLE_LANGUAGES.'.lang_name  
								FROM '.$this->tableName.'
									INNER JOIN '.TABLE_CLINIC.' ON '.$this->tableName.'.clinic_id = '.TABLE_CLINIC.'.id
									INNER JOIN '.TABLE_LANGUAGES.' ON '.$this->tableName.'.language_id = '.TABLE_LANGUAGES.'.abbreviation AND '.TABLE_LANGUAGES.'.is_active = 1
								';

		// define view mode fields
		$this->arrViewModeFields = array(
			'name'  	   	=> array('title'=>_NAME, 'type'=>'label', 'align'=>'left', 'width'=>'140px', 'maxlength'=>''),
			'address' 		=> array('title'=>_ADDRESS, 'type'=>'label', 'align'=>'left', 'width'=>'', 'maxlength'=>'90', 'formate'=>'strip_tags'),
			'lang_name'     => array('title'=>_LANGUAGE, 'type'=>'label', 'align'=>'center', 'width'=>'120px', 'maxlength'=>''),

		);
		
		//---------------------------------------------------------------------- 
		// ADD MODE
		//---------------------------------------------------------------------- 
		// define add mode fields
		$this->arrAddModeFields = array(
		
		);

		//---------------------------------------------------------------------- 
		// EDIT MODE
		//---------------------------------------------------------------------- 
		$this->EDIT_MODE_SQL = 'SELECT '.$this->tableName.'.'.$this->primaryKey.',
									'.$this->tableName.'.clinic_id,
									'.$this->tableName.'.language_id,
									'.$this->tableName.'.name,									
									'.$this->tableName.'.address,
									'.$this->tableName.'.description, 
									'.TABLE_LANGUAGES.'.lang_name  
								FROM '.$this->tableName.'
									INNER JOIN '.TABLE_CLINIC.' ON '.$this->tableName.'.clinic_id = '.TABLE_CLINIC.'.id
									INNER JOIN '.TABLE_LANGUAGES.' ON '.$this->tableName.'.language_id = '.TABLE_LANGUAGES.'.abbreviation AND '.TABLE_LANGUAGES.'.is_active = 1
								WHERE '.$this->tableName.'.'.$this->primaryKey.' = _RID_';		

		// define edit mode fields
		$this->arrEditModeFields = array(

			'lang_name'     => array('title'=>_LANGUAGE, 'type'=>'label'),
			'name' 	   		=> array('title'=>_NAME, 'type'=>'textbox',  'width'=>'270px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'125', 'default'=>'', 'validation_type'=>'text'),
			'address' 		=> array('title'=>_ADDRESS, 'type'=>'textarea', 'width'=>'480px', 'height'=>'55px', 'required'=>false, 'readonly'=>false, 'default'=>'', 'validation_type'=>'text', 'validation_maxlength'=>'225'),
			'description' 	=> array('title'=>_DESCRIPTION, 'type'=>'textarea', 'width'=>'460px', 'height'=>'150px', 'editor_type'=>'wysiwyg', 'required'=>false, 'readonly'=>false, 'default'=>'', 'validation_type'=>'text', 'validation_maxlength'=>'2048'),
			
		);

		//---------------------------------------------------------------------- 
		// DETAILS MODE
		//----------------------------------------------------------------------
		$this->DETAILS_MODE_SQL = $this->EDIT_MODE_SQL;
		$this->arrDetailsModeFields = array(

			'lang_name'   => array('title'=>_LANGUAGE, 'type'=>'label'),
			'name'  	  => array('title'=>_NAME, 'type'=>'label'),
			'address'  	  => array('title'=>_ADDRESS, 'type'=>'label'),
			'description' => array('title'=>_DESCRIPTION, 'type'=>'label', 'format'=>'strip_tags'),

		);
	}
	
	//==========================================================================
    // Class Destructor
	//==========================================================================
    function __destruct()
	{
		// echo 'this object has been destroyed';
    }
	
}
?>