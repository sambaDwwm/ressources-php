<?php

/**
 *	Class MembershipPlans
 *  --------------
 *	Description : encapsulates membership plans methods and properties
 *	Written by  : ApPHP
 *	Version     : 1.0.0
 *  Updated	    : 08.03.2014
 *  Usage       : MedicalAppointment
 *
 *	PUBLIC:				  	STATIC:				 	 	PRIVATE:
 * 	------------------	  	---------------     	 	---------------
 *	__construct             GetDefaultPlanInfo  	    ValidateTranslationFields
 *	__destruct              GetAllActive
 *	BeforeInsertRecord      DrawPlans
 *	AfterInsertRecord       GetPlanInfo
 *	BeforeUpdateRecord      DrawPrepayment
 *	AfterUpdateRecord       ReDrawPrepayment
 *  AfterDeleteRecord       DoOrder
 *	                        PlaceOrder
 *	                        RemoveExpired
 *	                        PrepareDurationsArray (private)
 *	
 *      
 **/


class MembershipPlans extends MicroGrid {
	
	protected $debug = false;
	
	// 001
	private $arrTranslations = '';
	private $currency_format = '';
	public static $message = '';

	
	//==========================================================================
    // Class Constructor
	//==========================================================================
	function __construct()
	{		
		parent::__construct();
		
		global $objLogin;
		
		$this->params = array();		
		if(isset($_POST['price'])) $this->params['price'] = prepare_input($_POST['price']);
		if(isset($_POST['images_count'])) $this->params['images_count'] = prepare_input($_POST['images_count']);
		if(isset($_POST['addresses_count'])) $this->params['addresses_count'] = prepare_input($_POST['addresses_count']);        
		if(isset($_POST['duration'])) $this->params['duration'] = prepare_input($_POST['duration']);
		if(isset($_POST['show_in_search'])) $this->params['show_in_search'] = prepare_input($_POST['show_in_search']);

		// for checkboxes 
		if(isset($_POST['is_default'])) $this->params['is_default'] = (int)$_POST['is_default']; else $this->params['is_default'] = '0';
		$this->params['is_active'] = isset($_POST['is_active']) ? prepare_input($_POST['is_active']) : '0';	

		$this->params['language_id'] = MicroGrid::GetParameter('language_id');
	
		//$this->uPrefix 		= 'prefix_';
		
		$this->primaryKey 	= 'id';
		$this->tableName 	= TABLE_MEMBERSHIP_PLANS;
		$this->dataSet 		= array();
		$this->error 		= '';
		$this->formActionURL = 'index.php?admin=mod_payments_membership_plans';
		$this->actions      = array('add'=>false, 'edit'=>true, 'details'=>true, 'delete'=>false);
		$this->actionIcons  = true;
		$this->allowRefresh = true;
		$this->allowTopButtons = true;
		$this->alertOnDelete = ''; // leave empty to use default alerts

		$this->allowLanguages = false;
		$this->languageId  	= $objLogin->GetPreferredLang();
		$this->WHERE_CLAUSE = ''; // WHERE .... / 'WHERE language_id = \''.$this->languageId.'\'';				
		$this->ORDER_CLAUSE = ''; // ORDER BY '.$this->tableName.'.date_created DESC
		
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
		
		///$this->isAggregateAllowed = false;
		///// define aggregate fields for View Mode
		///$this->arrAggregateFields = array(
		///	'field1' => array('function'=>'SUM'),
		///	'field2' => array('function'=>'AVG'),
		///);
		
		$arr_is_active = array('0'=>'<span class=no>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');
		$arr_is_default = array('0'=>'<span class=gray>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');
        $arr_show_search = array('0'=>'<span class=gray>'._NO.'</span>', '1'=>'<span class=yes>'._YES.'</span>');

		///$date_format = get_date_format('view');
		///$date_format_edit = get_date_format('edit');
		///$datetime_format = get_datetime_format();
		$this->currency_format = get_currency_format();
		$pre_currency_symbol = ((Application::Get('currency_symbol_place') == 'before') ? Application::Get('currency_symbol') : '');
		$post_currency_symbol = ((Application::Get('currency_symbol_place') == 'after') ? Application::Get('currency_symbol') : '');
        
		$arr_durations = self::PrepareDurationsArray();
		$arr_images = array('0', '1', '2', '3', '4', '5');
		$arr_addresses = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10');

		///////////////////////////////////////////////////////////////////////////////
		// 002. prepare translation fields array
		$this->arrTranslations = $this->PrepareTranslateFields(
			array('name', 'description')
		);
		///////////////////////////////////////////////////////////////////////////////			

		///////////////////////////////////////////////////////////////////////////////			
		// 003. prepare translations array for add/edit/detail modes
		$sql_translation_description = $this->PrepareTranslateSql(
			TABLE_MEMBERSHIP_PLANS_DESCRIPTION,
			'membership_plan_id',
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
									mp.'.$this->primaryKey.',
									mp.price,
									mp.duration,
                                    mp.show_in_search,
                                    mp.images_count,
                                    mp.addresses_count,
									mp.is_default,
									mp.is_active,
									apd.name
								FROM '.$this->tableName.' mp
									LEFT OUTER JOIN '.TABLE_MEMBERSHIP_PLANS_DESCRIPTION.' apd ON mp.id = apd.membership_plan_id AND apd.language_id = \''.$this->languageId.'\'';		
		// define view mode fields
		$this->arrViewModeFields = array(
			'name'  	     => array('title'=>_NAME, 'type'=>'label', 'align'=>'left', 'width'=>'', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'', 'format_parameter'=>''),
			'duration'       => array('title'=>_DURATION, 'type'=>'enum', 'align'=>'center', 'width'=>'100px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'source'=>$arr_durations),
			'price' 	     => array('title'=>_PRICE, 'type'=>'label', 'align'=>'center', 'width'=>'90px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>'', 'tooltip'=>'', 'maxlength'=>'', 'format'=>'currency', 'format_parameter'=>$this->currency_format.'|2', 'pre_html'=>$pre_currency_symbol, 'post_html'=>$post_currency_symbol),
			'images_count'   => array('title'=>_IMAGES, 'type'=>'enum',  'align'=>'center', 'width'=>'70px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_images),
			'addresses_count'=> array('title'=>_ADDRESSES, 'type'=>'enum',  'align'=>'center', 'width'=>'70px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_addresses),
			'show_in_search' => array('title'=>_SHOW_IN_SEARCH, 'type'=>'enum',  'align'=>'center', 'width'=>'85px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_show_search),
			'is_default'     => array('title'=>_DEFAULT, 'type'=>'enum',  'align'=>'center', 'width'=>'85px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_is_default),
			'is_active'      => array('title'=>_ACTIVE, 'type'=>'enum',  'align'=>'center', 'width'=>'95px', 'sortable'=>true, 'nowrap'=>'', 'visible'=>true, 'source'=>$arr_is_active),
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
									'.$this->primaryKey.',									
									price,
									'.$sql_translation_description.'
									duration,
                                    show_in_search,
									is_default,
									is_active,
									images_count,
                                    addresses_count
								FROM '.$this->tableName.' 
								WHERE '.$this->primaryKey.' = _RID_';			

		$rid = MicroGrid::GetParameter('rid');
		$sql = 'SELECT is_default FROM '.TABLE_MEMBERSHIP_PLANS.' WHERE id = '.(int)$rid;
		$readonly = false;
		if($result = database_query($sql, DATA_ONLY, FIRST_ROW_ONLY)){
			$readonly = (isset($result['is_default']) && $result['is_default'] == '1') ? true : false;
		}
							
		// define edit mode fields
		$this->arrEditModeFields = array(
			'separator_general'  =>array(
				'separator_info' => array('legend'=>_GENERAL_INFO),
				'price'          => array('title'=>_PRICE, 'type'=>'textbox',  'width'=>'90px', 'required'=>true, 'readonly'=>false, 'maxlength'=>'10', 'default'=>'0', 'validation_type'=>'float|positive', 'unique'=>false, 'visible'=>true, 'pre_html'=>$pre_currency_symbol, 'post_html'=>$post_currency_symbol),
				'duration'       => array('title'=>_DURATION, 'type'=>'enum',  'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'', 'source'=>$arr_durations, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
				'show_in_search' => array('title'=>_SHOW_IN_SEARCH, 'type'=>'enum',  'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'', 'source'=>$arr_show_search, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
				'images_count'   => array('title'=>_IMAGES_COUNT.' ('._GALLERY.')', 'type'=>'enum',  'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'', 'source'=>$arr_images, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
				'addresses_count'=> array('title'=>_ADDRESSES_COUNT, 'type'=>'enum',  'width'=>'', 'required'=>true, 'readonly'=>false, 'default'=>'', 'source'=>$arr_addresses, 'default_option'=>'', 'unique'=>false, 'javascript_event'=>''),
				'is_default'     => array('title'=>_DEFAULT, 'type'=>'checkbox', 'readonly'=>$readonly, 'default'=>'0', 'true_value'=>'1', 'false_value'=>'0'),
				'is_active'      => array('title'=>_ACTIVE, 'type'=>'checkbox', 'readonly'=>$readonly, 'default'=>'1', 'true_value'=>'1', 'false_value'=>'0'),		
			)
		);

		//---------------------------------------------------------------------- 
		// DETAILS MODE
		//----------------------------------------------------------------------
		$this->DETAILS_MODE_SQL = $this->EDIT_MODE_SQL;
		$this->arrDetailsModeFields = array(
			'separator_general'  =>array(
				'separator_info' => array('legend'=>_GENERAL_INFO),
				'price'          => array('title'=>_PRICE, 'type'=>'label', 'pre_html'=>$pre_currency_symbol, 'post_html'=>$post_currency_symbol),
				'duration'       => array('title'=>_DURATION, 'type'=>'enum', 'source'=>$arr_durations),
				'show_in_search' => array('title'=>_SHOW_IN_SEARCH, 'type'=>'enum', 'source'=>$arr_show_search),
				'images_count'   => array('title'=>_IMAGES_COUNT.' ('._GALLERY.')', 'type'=>'label'),
				'addresses_count'=> array('title'=>_ADDRESSES_COUNT, 'type'=>'label'),
				'is_default' 	 => array('title'=>_DEFAULT, 'type'=>'enum', 'source'=>$arr_is_default),			
				'is_active'      => array('title'=>_ACTIVE, 'type'=>'enum', 'source'=>$arr_is_active),
			)
		);

		///////////////////////////////////////////////////////////////////////////////
		// 004. add translation fields to all modes
		$this->AddTranslateToModes(
			$this->arrTranslations,
			array(
				'name'        => array('title'=>_NAME, 'type'=>'textbox', 'width'=>'310px', 'required'=>true, 'maxlength'=>'125', 'readonly'=>false),
				'description' => array('title'=>_DESCRIPTION, 'type'=>'textarea', 'width'=>'410px', 'height'=>'90px', 'required'=>false, 'validation_maxlength'=>'512', 'readonly'=>false)
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


	//==========================================================================
    // MicroGrid Methods
	//==========================================================================	
	////////////////////////////////////////////////////////////////////
	// BEFORE/AFTER METHODS
	///////////////////////////////////////////////////////////////////
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
			}else if(strlen($val['name']) > 125){
				$this->error = str_replace('_FIELD_', '<b>'._NAME.'</b>', _FIELD_LENGTH_EXCEEDED);
				$this->error = str_replace('_LENGTH_', 125, $this->error);
				$this->errorField = 'name_'.$key;
				return false;
			}else if(strlen($val['description']) > 512){
				$this->error = str_replace('_FIELD_', '<b>'._DESCRIPTION.'</b>', _FIELD_LENGTH_EXCEEDED);
				$this->error = str_replace('_LENGTH_', 512, $this->error);
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
		// set all other plans to be a not default plans
		$is_default = MicroGrid::GetParameter('is_default', false);
		if($is_default == '1'){
			$sql = 'UPDATE '.TABLE_MEMBERSHIP_PLANS.' SET is_default = \'0\' WHERE id != '.(int)$this->lastInsertId;
			database_void_query($sql);
		}

		$sql = 'INSERT INTO '.TABLE_MEMBERSHIP_PLANS_DESCRIPTION.'(id, membership_plan_id, language_id, name, description) VALUES ';
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
		// set default  = 0 for other languages
		if(self::GetParameter('is_default', false) == '1'){
			$sql = 'UPDATE '.TABLE_MEMBERSHIP_PLANS.'
					SET is_active = IF(id = '.(int)$this->curRecordId.', 1, is_active),
						is_default = IF(id = '.(int)$this->curRecordId.', 1, 0)';
			database_void_query($sql);					
		}			

		foreach($this->arrTranslations as $key => $val){
			$sql = 'UPDATE '.TABLE_MEMBERSHIP_PLANS_DESCRIPTION.'
					SET name = \''.encode_text(prepare_input($val['name'])).'\',
						description = \''.encode_text(prepare_input($val['description'])).'\'
					WHERE membership_plan_id = '.$this->curRecordId.' AND language_id = \''.$key.'\'';
			database_void_query($sql);
		}
	}	
	
	/**
	 * After-Deleting 
	 */
	public function AfterDeleteRecord()
	{
		$sql = 'DELETE FROM '.TABLE_MEMBERSHIP_PLANS_DESCRIPTION.' WHERE membership_plan_id = '.$this->curRecordId;
		if(database_void_query($sql)){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * Returns info for default plan
	 */
	public static function GetDefaultPlanInfo()
	{
		$sql = 'SELECT *
		        FROM '.TABLE_MEMBERSHIP_PLANS.'
				WHERE is_default = 1';

		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		return ($result[1] == 1) ? $result[0] : false;
	}
	
	/**
	 * Returns all active plans
	 */
	public static function GetAllActive()
	{
		$sql = 'SELECT
					mp.*,
					apd.name as plan_name,
					apd.description as plan_description
				FROM '.TABLE_MEMBERSHIP_PLANS.' mp
					LEFT OUTER JOIN '.TABLE_MEMBERSHIP_PLANS_DESCRIPTION.' apd ON mp.id = apd.membership_plan_id AND apd.language_id = \''.Application::Get('lang').'\'
				ORDER BY mp.id ASC';
					
		$result = database_query($sql, DATA_AND_ROWS, ALL_ROWS);
		return $result;		
	}

	/**
	 * Returns plan info
	 * 		@param $plan_id
	 */
	public static function GetPlanInfo($plan_id = 0)
	{
		$sql = 'SELECT
					mp.*,
					apd.name as plan_name,
					apd.description as plan_description
				FROM '.TABLE_MEMBERSHIP_PLANS.' mp
					LEFT OUTER JOIN '.TABLE_MEMBERSHIP_PLANS_DESCRIPTION.' apd ON mp.id = apd.membership_plan_id AND apd.language_id = \''.Application::Get('lang').'\'
				WHERE mp.id = '.(int)$plan_id;
					
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		return ($result[1] == 1) ? $result[0] : false;
	}

	/**
	 * Draws all active plans
	 * 		@param $draw
	 */
	public static function DrawPlans($draw = true)
	{
        global $objLogin;
		$output = '';
		$arr_durations = self::PrepareDurationsArray();

		$default_payment_system = isset($_GET['payment_type']) ? $_GET['payment_type'] : ModulesSettings::Get('payments', 'default_payment_system');
		$payment_type_online    = ModulesSettings::Get('payments', 'payment_method_online');
		$payment_type_paypal    = ModulesSettings::Get('payments', 'payment_method_paypal');
		$payment_type_2co       = ModulesSettings::Get('payments', 'payment_method_2co');
		$payment_type_authorize = ModulesSettings::Get('payments', 'payment_method_authorize');
		$payment_type_cnt	    = ($payment_type_online === 'yes')+($payment_type_paypal === 'yes')+($payment_type_2co === 'yes')+($payment_type_authorize === 'yes');
		$exclude_free_plans     = true; //($default_payment_system != 'online') ? true : false;
        $current_plan_id        = $objLogin->GetMembershipInfo('plan_id');
        $current_plan_name      = $objLogin->GetMembershipInfo('plan_name');

		$sql = 'SELECT mp.*,
                    apd.name,
                    apd.description
				FROM '.TABLE_MEMBERSHIP_PLANS.' mp
					LEFT OUTER JOIN '.TABLE_MEMBERSHIP_PLANS_DESCRIPTION.' apd ON mp.id = apd.membership_plan_id AND apd.language_id = \''.Application::Get('lang').'\'
				WHERE 1=1                    
				ORDER BY mp.id ASC';
       			
		$result = database_query($sql, DATA_AND_ROWS, ALL_ROWS);
		if($result[1] > 0){
			$output .= '<form name="frmMembershipForm" id="frmMembershipForm" action="index.php?doctor=membership_prepayment" method="post">';
			$output .= draw_hidden_field('task', 'do_order', false);
			$output .= draw_token_field(false);
			
			$output .= '<div class="membership_plans_container">';			
			$output .= '<table>';
			$output .= '<tr><td>'._CURRENCY.':</td><td>'.Currencies::GetCurrenciesDDL(false, true).'</td></tr>';			

			if($payment_type_cnt >= 1){
				$output .= '<tr><td>'._PAYMENT_TYPE.': </td><td>
				<select name="payment_type" id="payment_type">';
					if($payment_type_online == 'yes') $output .= '<option value="online" '.(($default_payment_system == 'online') ? 'selected="selected"' : '').'>'._ONLINE_ORDER.'</option>';	
					if($payment_type_paypal == 'yes') $output .= '<option value="paypal" '.(($default_payment_system == 'paypal') ? 'selected="selected"' : '').'>'._PAYPAL.'</option>';	
					if($payment_type_2co == 'yes') $output .= '<option value="2co" '.(($default_payment_system == '2co') ? 'selected="selected"' : '').'>2CO</option>';	
					if($payment_type_authorize == 'yes') $output .= '<option value="authorize" '.(($default_payment_system == 'authorize') ? 'selected="selected"' : '').'>Authorize.Net</option>';	
				$output .= '</select></td></tr>';
			}else{
				$output .= '<tr><td colspan="2">';
				$output .= draw_important_message(_NO_PAYMENT_METHODS_ALERT, false);
				$output .= '</td></tr>';
			}
			$output .= '</table>';

			$output .= '<div class="plans_wrapper">';
			$output .= '<h2>'._SELECT_PLAN.'</h2>';
            $active_ind = '-1';
            $allow_submission = true;
            
            if($current_plan_id == 4){
                $output .= '<p>'.str_ireplace('_PLAN_NAME_', '<b>'.$current_plan_name.'</b>', _NO_AVAILABLE_PLANS_NOTICE).'</p>';
                $allow_submission = false;
            }else{
                if(Doctors::HasOpenOnlineOrder($objLogin->GetLoggedID())){
                    $allow_submission = false;
                    $output .= '<p>'._OPEN_ORDER_NOTICE.'</p>';
                }else{
                    $output .= '<p>'.str_ireplace('_PLAN_NAME_', '<b>'.$current_plan_name.'</b>', _CHOOSE_AVAILABLE_PLANS_NOTICE).'</p>';
                }
            }
			
			for($i=0; $i<$result[1]; $i++){				
				if(($result[0][$i]['price'] != 0 && ($result[0][$i]['id'] > $current_plan_id)) && $active_ind == '-1') $active_ind = $i;				
				$duration = isset($arr_durations[$result[0][$i]['duration']]) ? $arr_durations[$result[0][$i]['duration']] : '';
                $show_in_search = isset($arr_durations[$result[0][$i]['show_in_search']]) ? (bool)$result[0][$i]['show_in_search'] : false;
				$no_text = '<span class=no>'._NO.'</span>';
				$yes_text = '<span class=yes>'._YES.'</span>';
                
                if($result[0][$i]['price'] != 0 && ($result[0][$i]['id'] > $current_plan_id)){
                    $css_class = ($i == $active_ind) ? 'active' : '';
                }else if($result[0][$i]['price'] == 0){
                    $css_class = 'disabled';
                }else{
                    $css_class = 'disabled';
                }
                if(!$allow_submission) $css_class = 'disabled';  
    
				$output .= '<div class="item '.$css_class.'" id="item_'.$i.'">
					<h3>'.$result[0][$i]['name'].'</h3>
					<div class="item_text" '.($css_class != 'disabled' ? ' title="'._CLICK_TO_SELECT.'"' : '').'>
						<label for="plan_'.$result[0][$i]['id'].'">
						'._DURATION.': <b>'.$duration.'</b><br />
						'._SHOW_IN_SEARCH.': <b>'.($show_in_search ? _YES : _NO).'</b><br />
						'._IMAGES_COUNT.': <b>'.$result[0][$i]['images_count'].'</b><br />
                        '._ADDRESSES_COUNT.': <b>'.$result[0][$i]['addresses_count'].'</b><br />
						'._PRICE.': <b>'.Currencies::PriceFormat($result[0][$i]['price'] * Application::Get('currency_rate')).'</b><br />
						<div class="item_description">'.$result[0][$i]['description'].'</div>
						</label>						
					</div>
					<div class="item_radio">';
					if($result[0][$i]['price'] != 0 && ($result[0][$i]['id'] > $current_plan_id)){
						$output .= '<input '.(($i == $active_ind) ? 'checked="checked"' : '').' type="radio" name="plan_id" id="plan_'.$result[0][$i]['id'].'" value="'.$result[0][$i]['id'].'" onclick="appSelectBlock(\''.$i.'\');">';
					}
					$output .= '</div>
				</div>';
			}
			$output .= '</div>';
			if($payment_type_cnt >= 1 && $allow_submission) $output .= '<div class="plan_button"><input type="submit" class="form_button" name="btnSubmit" value="'._SUBMIT.'" /></div>';
            
            $output .= '</div>';            
			$output .= '</form>';
		}else{
			$output .= _NO_RECORDS_FOUND;
		}
		
		if($draw) echo $output;
		else $output;
	}
	

	/**
	 * Draw prepayment info
	 * 		@param $draw
	 */
	public static function ReDrawPrepayment($draw = true)
	{
		global $objLogin;
		
		// get order number
		$sql = 'SELECT id, membership_plan_id, currency, payment_type, order_number FROM '.TABLE_ORDERS.' WHERE doctor_id = '.(int)$objLogin->GetLoggedID().' AND `status` = 0 ORDER BY id DESC';
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			self::DrawPrepayment($result[0]['membership_plan_id'], 'online', Application::Get('currency'));			
		}else{
			draw_important_message(_WRONG_PARAMETER_PASSED);
		}
	}		
		

	/**
	 * Draw prepayment info
	 * 		@param $draw
	 */
	public static function DrawPrepayment($plan_id = '', $payment_type = '', $currency = '', $draw = true)
	{		
		global $objSettings, $objLogin;
		
		$plan_id = (empty($plan_id)) ? MicroGrid::GetParameter('plan_id', false) : $plan_id;
		$payment_type = (empty($payment_type)) ? MicroGrid::GetParameter('payment_type', false) : $payment_type;
		$currency = (empty($currency)) ? MicroGrid::GetParameter('currency', false) : $currency;
		$output = '';

		// retrieve module parameters
		$paypal_email        = ModulesSettings::Get('payments', 'paypal_email');
		$collect_credit_card = ModulesSettings::Get('payments', 'online_collect_credit_card');
		$two_checkout_vendor = ModulesSettings::Get('payments', 'two_checkout_vendor');
		$authorize_login_id  = ModulesSettings::Get('payments', 'authorize_login_id');
		$authorize_transaction_key = ModulesSettings::Get('payments', 'authorize_transaction_key');
		$mode                = ModulesSettings::Get('payments', 'mode');
		$vat_value           = ModulesSettings::Get('payments', 'vat_value');

		// retrieve credit card info
		$cc_type = isset($_REQUEST['cc_type']) ? prepare_input($_REQUEST['cc_type']) : '';
		$cc_holder_name  = isset($_POST['cc_holder_name']) ? prepare_input($_POST['cc_holder_name']) : '';
		$cc_number = isset($_POST['cc_number']) ? prepare_input($_POST['cc_number']) : "";
		$cc_expires_month = isset($_POST['cc_expires_month']) ? prepare_input($_POST['cc_expires_month']) : "1";
		$cc_expires_year = isset($_POST['cc_expires_year']) ? prepare_input($_POST['cc_expires_year']) : date("Y");
		$cc_cvv_code = isset($_POST['cc_cvv_code']) ? prepare_input($_POST['cc_cvv_code']) : "";

		// prepare datetime format
		$field_date_format = get_datetime_format();
		$currency_format = get_currency_format();		  
		$arr_durations = self::PrepareDurationsArray();

		// prepare clients info 
		$sql='SELECT * FROM '.TABLE_DOCTORS.' WHERE id = '.(int)$objLogin->GetLoggedID();
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		$doctor_info = array();
		$doctor_info['first_name'] = isset($result[0]['first_name']) ? $result[0]['first_name'] : '';
		$doctor_info['last_name'] = isset($result[0]['last_name']) ? $result[0]['last_name'] : '';
		$doctor_info['address1'] = isset($result[0]['b_address']) ? $result[0]['b_address'] : '';
		$doctor_info['address2'] = isset($result[0]['b_address2']) ? $result[0]['b_address2'] : '';
		$doctor_info['city'] = isset($result[0]['b_city']) ? $result[0]['b_city'] : '';
		$doctor_info['state'] = isset($result[0]['b_state']) ? $result[0]['b_state'] : '';
		$doctor_info['zip'] = isset($result[0]['b_zipcode']) ? $result[0]['b_zipcode'] : '';
		$doctor_info['country'] = isset($result[0]['b_country']) ? $result[0]['b_country'] : '';
		$doctor_info['email'] = isset($result[0]['email']) ? $result[0]['email'] : '';
		$doctor_info['company'] = isset($result[0]['company']) ? $result[0]['company'] : '';
        $doctor_info['phone'] = isset($result[0]['phone']) ? $result[0]['phone'] : '';
		$doctor_info['fax'] = isset($result[0]['fax']) ? $result[0]['fax'] : '';

		if($cc_holder_name == ''){
			if($objLogin->IsLoggedIn()){
				$cc_holder_name = $objLogin->GetLoggedFirstName().' '.$objLogin->GetLoggedLastName();
			}else{
				$cc_holder_name = $doctor_info['first_name'].' '.$doctor_info['last_name'];
			}
		}		
		
		// get order number
		$sql = 'SELECT id, order_number FROM '.TABLE_ORDERS.' WHERE doctor_id = '.(int)$objLogin->GetLoggedID().' AND `status` = 0 ORDER BY id DESC';
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){					
			$order_number = $result[0]['order_number'];
		}else{
			$order_number = strtoupper(get_random_string(10));
		}

		$additional_info = '';

		$cart_total_wo_vat = 0;
		$vat_cost = 0;
		$cart_total = 0;
		
		$sql = 'SELECT
					mp.id,
                    mp.images_count,
                    mp.addresses_count,
					mp.price,
					mp.duration,
                    mp.show_in_search,
					mp.is_default,
					apd.name,
					apd.description
				FROM '.TABLE_MEMBERSHIP_PLANS.' mp
					LEFT OUTER JOIN '.TABLE_MEMBERSHIP_PLANS_DESCRIPTION.' apd ON mp.id = apd.membership_plan_id AND apd.language_id = \''.Application::Get('lang').'\'
				WHERE mp.id = '.(int)$plan_id;
					
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);

		$fisrt_part = '<table border="0" width="97%" align="center">
			<tr><td colspan="3"><h4>'._ORDER_DESCRIPTION.'</h4></td></tr>
			<tr><td width="20%">'._ORDER_DATE.' </td><td width="2%"> : </td><td> '.format_datetime(date('Y-m-d H:i:s'), $field_date_format).'</td></tr>';
			if($result[1] > 0){
				if($result[0]['price'] == 0){
					$payment_type = 'online';
					$collect_credit_card = 'no';
				}
				$cart_total_wo_vat = ($result[0]['price'] * Application::Get('currency_rate'));
				$vat_cost = ($cart_total_wo_vat * ($vat_value / 100));
				$cart_total = $cart_total_wo_vat + $vat_cost;
				
				$duration = isset($arr_durations[$result[0]['duration']]) ? $arr_durations[$result[0]['duration']] : '';
				$show_in_search = isset($result[0]['show_in_search']) ? (bool)$result[0]['show_in_search'] : false;
                $images_count = isset($result[0]['images_count']) ? (int)$result[0]['images_count'] : 0;
                $addresses_count = isset($result[0]['addresses_count']) ? (int)$result[0]['addresses_count'] : 0;
				
				$fisrt_part .= '<tr><td>'._MEMBERSHIP_PLANS.' </td><td width="2%"> : </td><td> '.$result[0]['name'].'</td></tr>';
				$fisrt_part .= '<tr><td>'._DESCRIPTION.' </td><td width="2%"> : </td><td> '.$result[0]['description'].'</td></tr>';
				$fisrt_part .= '<tr><td>'._DURATION.' </td><td width="2%"> : </td><td> '.$duration.'</td></tr>';
				$fisrt_part .= '<tr><td>'._SHOW_IN_SEARCH.' </td><td width="2%"> : </td><td> '.($show_in_search ? _YES : _NO).'</td></tr>';
                $fisrt_part .= '<tr><td>'._IMAGES_COUNT.' </td><td width="2%"> : </td><td> '.$images_count.'</td></tr>';
                $fisrt_part .= '<tr><td>'._ADDRESSES_COUNT.' </td><td width="2%"> : </td><td> '.$addresses_count.'</td></tr>';
				$fisrt_part .= '<tr><td>'._PRICE.' </td><td width="2%"> : </td><td> '.Currencies::PriceFormat($cart_total_wo_vat, '', '', $currency_format).'</td></tr>';
			}			
	
		$pp_params = array(
			'api_login'       => '',
			'transaction_key' => '',
			'order_number'    => $order_number,			
			
			'address1'      => $doctor_info['address1'],
			'address2'      => $doctor_info['address2'],
			'city'          => $doctor_info['city'],
			'zip'           => $doctor_info['zip'],
			'country'       => $doctor_info['country'],
			'state'         => $doctor_info['state'],
			'first_name'    => $doctor_info['first_name'],
			'last_name'     => $doctor_info['last_name'],
			'email'         => $doctor_info['email'],
			'company'       => $doctor_info['company'],
            'phone'         => $doctor_info['phone'],
			'fax'           => $doctor_info['fax'],
			
			'notify'        => '',
			'return'        => 'index.php?page=payment_return',
			'cancel_return' => 'index.php?page=payment_cancel',
						
			'paypal_form_type'   	   => '',
			'paypal_form_fields' 	   => '',
			'paypal_form_fields_count' => '',
			
			'collect_credit_card' => $collect_credit_card,
			'cc_type'             => '',
			'cc_holder_name'      => '',
			'cc_number'           => '',
			'cc_cvv_code'         => '',
			'cc_expires_month'    => '',
			'cc_expires_year'     => '',
			
			'currency_code'      => Application::Get('currency_code'),
			'additional_info'    => $additional_info,
			'discount_value'     => '',
			'extras_param'       => '',
			'extras_sub_total'   => '',
			'vat_cost'           => $vat_cost,
			'cart_total'         => number_format((float)$cart_total, (int)Application::Get('currency_decimals'), '.', ','),
			'is_prepayment'      => false,
			'pre_payment_type'   => '',
			'pre_payment_value'  => 0,
			
		);
			
		$fisrt_part .= '
			<tr><td colspan="3" nowrap="nowrap" height="10px"></td></tr>
			<tr><td colspan="3"><h4>'._TOTAL.'</h4></td></tr>
			<tr><td>'._SUBTOTAL.' </td><td> : </td><td> '.Currencies::PriceFormat($cart_total_wo_vat, '', '', $currency_format).'</td></tr>';
			$fisrt_part .= '<tr><td>'._VAT.' ('.$vat_value.'%) </td><td> : </td><td> '.Currencies::PriceFormat($vat_cost, '', '', $currency_format).'</td></tr>';
			$fisrt_part .= '<tr><td>'._PAYMENT_SUM.' </td><td> : </td><td> <b>'.Currencies::PriceFormat($cart_total, '', '', $currency_format).'</b></td></tr>';
			$fisrt_part .= '<tr><td colspan="3" nowrap="nowrap" height="0px"></td></tr>';
			$fisrt_part .= '<tr><td colspan="3">';
			//if($additional_info != ''){
			//	$fisrt_part .= '<tr><td colspan="3" nowrap height="10px"></td></tr>';
			//	$fisrt_part .= '<tr><td colspan="3"><h4>'._ADDITIONAL_INFO.'</h4>'.$additional_info.'</td></tr>';							
			//}
		
		
		$second_part = '
			</td></tr>
		</table><br />';


		if($payment_type == 'online'){

			$output .= $fisrt_part;
				$pp_params['credit_card_required'] = $collect_credit_card;
				$pp_params['cc_type']             = $cc_type;
				$pp_params['cc_holder_name']      = $cc_holder_name;
				$pp_params['cc_number']           = $cc_number;
				$pp_params['cc_cvv_code']         = $cc_cvv_code;
				$pp_params['cc_expires_month']    = $cc_expires_month;
				$pp_params['cc_expires_year']     = $cc_expires_year;
				$output .= PaymentIPN::DrawPaymentForm('online', $pp_params, (($mode == 'TEST MODE') ? 'test' : 'real'), false);
			$output .= $second_part;			
	
		}else if($payment_type == 'paypal'){							
		
			$output .= $fisrt_part;
				$pp_params['api_login']                = $paypal_email;
				$pp_params['notify']        		   = 'index.php?page=payment_notify_paypal';
				$pp_params['paypal_form_type']   	   = 'single';
				$pp_params['paypal_form_fields'] 	   = '';
				$pp_params['paypal_form_fields_count'] = '';						
				$output .= PaymentIPN::DrawPaymentForm('paypal', $pp_params, (($mode == 'TEST MODE') ? 'test' : 'real'), false);
			$output .= $second_part;		
		
		}else if($payment_type == '2co'){				

			$output .= $fisrt_part;
				$pp_params['api_login'] = $two_checkout_vendor;			
				$pp_params['notify']    = 'index.php?page=payment_notify_2co';
				$output .= PaymentIPN::DrawPaymentForm('2co', $pp_params, (($mode == 'TEST MODE') ? 'test' : 'real'), false);
			$output .= $second_part;

		}else if($payment_type == 'authorize'){

			$output .= $fisrt_part;
				$pp_params['api_login'] 	  = $authorize_login_id;
				$pp_params['transaction_key'] = $authorize_transaction_key;
				$pp_params['notify']    	  = 'index.php?page=payment_notify_autorize_net';
				// authorize.net accepts only USD, so we need to convert the sum into USD
				$pp_params['cart_total']      = number_format((($pp_params['cart_total'] * Application::Get('currency_rate'))), '2', '.', ',');												
				$output .= PaymentIPN::DrawPaymentForm('authorize.net', $pp_params, (($mode == 'TEST MODE') ? 'test' : 'real'), false);
			$output .= $second_part;
		}
	
		if($draw) echo $output;
		else $output;
	}
	
	/**
	 * Do (prepare) order
	 * 		@param $payment_type
	 */
	public static function DoOrder($payment_type = '')
	{
		//global $objSettings;
		global $objLogin;
	
        if(SITE_MODE == 'demo'){
           self::$message = _OPERATION_BLOCKED;
		   return false;
        }

		// check if doctor has reached the maximum number of allowed 'open' orders
		$max_orders = ModulesSettings::Get('payments', 'maximum_allowed_orders');
		$sql = 'SELECT COUNT(*) as cnt
				FROM '.TABLE_ORDERS.'
				WHERE doctor_id = '.(int)$objLogin->GetLoggedID().' AND
				     (status = 0 OR status = 1)';				
		$result = database_query($sql, DATA_ONLY);
		$cnt = isset($result[0]['cnt']) ? (int)$result[0]['cnt'] : 0;
		if($cnt >= $max_orders){
			self::$message = _MAX_ORDERS_ERROR;
			return false;
		}		

		$return = false;
		$currency = MicroGrid::GetParameter('currency', false);
		$plan_id = MicroGrid::GetParameter('plan_id', false);
		$payment_type = MicroGrid::GetParameter('payment_type', false);		
		$additionalInfo = '';
		$payed_by = 0;
		$order_price = 0;
		$vat_percent = ModulesSettings::Get('payments', 'vat_value');
		$vat_cost = 0;
		$total_price = 0;

		// add order to database
		if(in_array($payment_type, array('online', 'paypal', '2co', 'authorize'))){			
			if($payment_type == 'paypal'){
				$payed_by = '1';
				$status = '0';									
			}else if($payment_type == '2co'){
				$payed_by = '2';
				$status = '0';
			}else if($payment_type == 'authorize'){
				$payed_by = '3';
				$status = '0';				
			}else{
				$payed_by = '0';
				$status = '0';
			}
			
			$sql = 'SELECT mp.id, mp.price, mp.duration, mp.show_in_search												
					FROM '.TABLE_MEMBERSHIP_PLANS.' mp
					WHERE mp.id = '.(int)$plan_id;						
			$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);

			if($result[1] > 0){
				$order_price = ($result[0]['price'] * Application::Get('currency_rate'));
				$vat_cost = ($order_price * ($vat_percent / 100));
				$total_price = $order_price + $vat_cost;
				
				/////////////////////////////////////////////////////////////////
				$sql = 'SELECT id, order_number FROM '.TABLE_ORDERS.' WHERE doctor_id = '.(int)$objLogin->GetLoggedID().' AND `status` = 0 ORDER BY id DESC';
				$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
				if($result[1] > 0){					
					$sql_start = 'UPDATE '.TABLE_ORDERS.' SET ';
					$order_number = $result[0]['order_number'];
					$sql_end = ' WHERE order_number = \''.$order_number.'\'';
				}else{					
					$sql_start = 'INSERT INTO '.TABLE_ORDERS.' SET ';
					$order_number = strtoupper(get_random_string(10));
					$sql_end = '';
				}
				
				$sql_middle = 'order_number = \''.$order_number.'\',
							order_description = \'Doctor Membership Plan\',
							order_price = '.number_format((float)$order_price, (int)Application::Get('currency_decimals')).',
							vat_percent = '.$vat_percent.',
							vat_fee = '.number_format((float)$vat_cost, (int)Application::Get('currency_decimals')).',
							total_price = '.number_format((float)$total_price, (int)Application::Get('currency_decimals')).',
							currency = \''.$currency.'\',
							membership_plan_id = '.$plan_id.',
							doctor_id = '.(int)$objLogin->GetLoggedID().',
							transaction_number = \'\',
							created_date = \''.date('Y-m-d H:i:s').'\',
							payment_date = NULL,
							payment_type = '.$payed_by.',
							payment_method = 0,
							coupon_number = \'\',
							discount_campaign_id = 0,
							additional_info = \''.$additionalInfo.'\',
							cc_type = \'\',
							cc_holder_name = \'\',
							cc_number = \'\', 
							cc_expires_month = \'\', 
							cc_expires_year = \'\', 
							cc_cvv_code = \'\',
							`status` = '.(int)$status.',
							`status_changed` = NULL,
							email_sent = 0';
							
				$sql = $sql_start.$sql_middle.$sql_end;
	
				if(database_void_query($sql)){					
					$return = true;
				}else{
					self::$message = _ORDER_PEPARING_ERROR;
					$return = false;
				}
			}else{
				self::$message = _ORDER_PEPARING_ERROR;
				$return = false;
			}
		}else{
			self::$message = _ORDER_PEPARING_ERROR;
			$return = false;
		}
		
		if(SITE_MODE == 'development' && !empty(self::$message)) self::$message .= '<br>'.$sql.'<br>'.database_error();		

		return $return;
	}

	/**
	 * Place order
	 * 		@param $order_number
	 * 		@param $cc_params
	 */
	public static function PlaceOrder($order_number, $cc_params = array())
	{
		global $objLogin;
		
        if(SITE_MODE == 'demo'){
           self::$message = draw_important_message(_OPERATION_BLOCKED, false);
		   return false;
        }
		
		$sql='SELECT id, order_number
			  FROM '.TABLE_ORDERS.'
			  WHERE
			        order_number = \''.$order_number.'\' AND
					doctor_id = '.(int)$objLogin->GetLoggedID().' AND
			        `status` = 0
			  ORDER BY id DESC';				
		$result = database_query($sql, DATA_AND_ROWS, FIRST_ROW_ONLY);
		if($result[1] > 0){
			$sql = 'UPDATE '.TABLE_ORDERS.'
					SET
						created_date = \''.date('Y-m-d H:i:s').'\',
						status_changed = \''.date('Y-m-d H:i:s').'\',
						cc_type = \''.$cc_params['cc_type'].'\',
						cc_holder_name = \''.$cc_params['cc_holder_name'].'\',
						cc_number = AES_ENCRYPT(\''.$cc_params['cc_number'].'\', \''.PASSWORDS_ENCRYPT_KEY.'\'),
						cc_expires_month = \''.$cc_params['cc_expires_month'].'\',
						cc_expires_year = \''.$cc_params['cc_expires_year'].'\',
						cc_cvv_code = \''.$cc_params['cc_cvv_code'].'\',
						status = \'1\'
					WHERE order_number = \''.$order_number.'\'';
			database_void_query($sql);
			if(Orders::SendOrderEmail($order_number, 'accepted', $objLogin->GetLoggedID())){
			    // OK	
			}else{
				//$this->message = draw_success_message(_ORDER_SEND_MAIL_ERROR, false);					
			}			
			return true;
		}else{
			self::$message = _ORDER_ERROR;
			return false;			
		}
		
	}
    
	/**
	 *	Remove expired membership plans
	 */
	public static function RemoveExpired()
	{
        $where_clause = "(membership_plan_id = 4 AND membership_expires IS NOT NULL OR membership_plan_id != 4 AND '".date('Y-m-d H:i:s')."' > membership_expires)";
        //$sql = "SELECT *
        //        FROM ".TABLE_DOCTORS."
        //        WHERE
        //            (".$where_clause.") AND 
        //            TIMESTAMPDIFF(DAY, membership_expires, '".date('Y-m-d H:i:s')."') > 10";                
        //$result = database_query($sql, DATA_AND_ROWS);
               
        Doctors::UpdateMembershipInfo(array(), $where_clause);
    }
	
	/**
	 * Prepare array of ducrations;
	 */
	private static function PrepareDurationsArray()
	{
		$array = array('1'=>'1 '._DAY, '2'=>'2 '._DAYS, '3'=>'3 '._DAYS, '4'=>'4 '._DAYS, '5'=>'5 '._DAYS, '6'=>'6 '._DAYS, '7'=>'7 '._DAYS, '8'=>'8 '._DAYS, '9'=>'9 '._DAYS, '10'=>'10 '._DAYS, '14'=>'14 '._DAYS, '21'=>'21 '._DAYS, '28'=>'28 '._DAYS, '30'=>'1 '._MONTH, '45'=>'1.5 '._MONTHS, '60'=>'2 '._MONTHS, '90'=>'3 '._MONTHS, '120'=>'4 '._MONTHS, '180'=>'6 '._MONTHS, '240'=>'8 '._MONTHS, '270'=>'9 '._MONTHS, '365'=>'1 '._YEAR, '720'=>'2 '._YEARS, '1095'=>'3 '._YEARS, '1440'=>'4 '._YEARS, '1825'=>'5 '._YEARS, '-1'=>_UNLIMITED);
		return $array;
	}

}
?>