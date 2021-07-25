<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP Calendar version 3.7.6                                               #
##  Developed by:  ApPhp <info@apphp.com>                                      #
##  Last modified: 12.01.2021 12:24                                            #
##  License:       GNU LGPL v.3                                                #
##  Site:          https://www.apphp.com/php-calendar/                         #
##  Copyright:     ApPHP Calendar (c) 2009-2014. All rights reserved.          #
##                                                                             #
##  Additional modules (embedded):                                             #
##  -- overLIB v4.21 (JS library)           http://www.bosrup.com/web/overlib/ #
##  -- Google AJAX Libraries API                  http://code.google.com/apis/ #
##  -- jQuery (JavaScript Library)                           http://jquery.com #
##  -- jQuery UI (JavaScript Library)                      http://jqueryui.com #
##  -- Crystal Project Icons (icons set)               http://www.everaldo.com #
##  -- TinyEditor (WYSIWYG editor)                    http://www.scriptiny.com #
##                                                                             #
################################################################################
// Non-documented: (for developers)
// ---------------
// disableEarlierHours  - disable hours before selected time in dropdown box in Add New Event popup
// hideWeekEmptySlots   - hides empty slots in Weekly View
// isEventPartsAllowed  - true|false(default), used to allow working with event parts (slots)
// participantDataSaveType - 'session|cookie', specifies how to save participant data with session variables or cookie
// separator 		    - used to separate events for GROUP_CONCAT() function in SELECT SQL
// viewChangerType      - 'tabs|dropdownlist(default)', specifies a type of View Changer
// isAnchorAllowed      - true|false(default), used to allow working with anchor
// direction            - 'rtl|ltr(default)', used to define calendar layout direction
// dateFormat           - 'yyyy/mm/dd', 'dd/mm/yyyy' or 'mm/dd/yyyy'(default), used to define calendar date format
// removeCategoryEvents - true|false(default), used to force deletion of category events when category is removing
// eventsLinkTarget     - specifies target for event links: _new, _blank, _parent, _self etc.
// allowEventsWithSameName - true|false(default), whether to allow creating events with the same name
// highlightSelectedDay - true|false(default), used to highlight selected day
// showEmptyTimeSlots   - true|false(default), used to show/hide empty time slots
// saturdayColor        - true|false(default), used to allow color for Saturday

class Calendar{
	
	
	// PUBLIC						PRIVATE							Auxilary (PRIVATE)			STATIC                   DEPRECATED    
	// --------                     ---------						--------                    ---------	             ----------
	// __construct  				SetDefaultParameters			SetLanguage					Version					 SetCategoriesSettings	
	// __destruct                   GetCurrentParameters			StartCaching				GetDefaultTimeZone	     SetParticipantsSettings
	// Show                         DrawCssStyle					FinishCaching				GetColorsByName				
	// SetCalendarDimensions        DrawJsFunctions					GetEventsListForHour		GetColorNameByValue			
	// SetCaption                   IncludeModules					GetEventsListForWeekDay
	// SetWeekStartedDay			InitializeJsFunctions           GetEventCountForDay
	// SetWeekDayNameLength         DrawMessages                    GetEventsCountForMonth
	// ShowWeekNumberOfYear         DrawListView                    GetEventsListForMonth
	// SetTimeZone                  DrawYear                        GetMonthlyDayEvents
	// GetCurrentTimeZone           DrawMonth                       IsYear
	// SetDefaultView               DrawMonthSmall                  IsMonth
	// SetEventsDisplayType         DrawWeek 						IsDay
	// SetSundayColor               DrawDay							ConvertToDecimal
	// SetTimeFormat                DrawTypesChanger                GetFormattedMicrotime 
	// SetEventsOperations          DrawDateJumper                  PrepareOption
	// SetCategoriesOperations      DrawTodayJumper                 PrepareText
	// SetCssStyle                  DrawEventsAddForm               PrepareFormatedText
	// SetParticipantID             DrawEventsEditForm              PrepareFormatedTextTooltips
	// SetCachingParameters         DrawEventsDetailsForm           PrepareFormatedTextOverlib
	// SetInterfaceLang             DrawEventsStatistics            PrepareMinutesHoures
	// ResetParticipantsOperations  DrawEventsParticipantsManagement GetDayForMonth 
	// SetCalendarViews    			DrawAddEventForm                IsActionType
    // SetAllowedHours              DrawEditEventForm               GetParameter
	// Debug                        ParseHour                       RemoveDuplications
	// SetJsFunctions               ParseMinutes                    PrepareWhereClauseFromToTime
	// BlockEventsDeletingBefore    HandleEventsOperations          PrepareWhereClauseEventTime
	// SetDisabledDays              DrawCategoriesAddForm           PrepareWhereClauseCategory
	// SetPostBackMethod            DrawCategoriesDetailsForm       PrepareWhereClauseParticipant
	// SetEventsMultipleOccurrences DrawCategoriesEditForm          PrepareWhereClauseLocation
	// SaveHttpRequestVars          DrawColors                      GetRandomString 
	// SetTimeSlot                  DrawCategoriesDDL               ResetCategoriesOperations   
	// ShowTime                     DrawEventsDDL                   ResetLocationsOperations
	// SetMonthlySmallLinks         DrawExportFormatsDDL            CheckRepeatOnWeekDayInMonth 
	// SetParticipantsOperations    DrawDateTime                    IsDisabledDay
	// AllowCategories				DrawDate
	// AllowParticipants            DatabaseVoidQuery
	// AllowWYSIWYG       			DatabaseVoidQuery
	// SetAdminEmail                RemoveBadChars
	// SetNotificationTime          DrawDeleteEventsByRange
	// SendNotifications		    DrawEventsManagement
	// Email                        DrawParticipantsAddForm
	// SetJsFunctions               DrawParticipantsEditForm
	// EditingEventsInPast          DrawParticipantsDetailsForm
	// DeletingEventsInPast         DrawParticipantsManagement
	// SetCalendarActions           DrawMonthlyDayCell
    // SetExportTypes               DrawMonthDoubled
	// AllowLocations               DrawMonthlyDoubledDayCell
	// SetLocationsOperations       DrawEventsExporting
    // SetCategoryID                DrawShowInFilter
	//                              DrawEventsCategoriesManagement
    //                              DrawEventsLocationsManagement
    //                              DrawLocationsAddForm
    //                              DrawLocationsEditForm
    //                              DrawLocationsDetailsForm
    //                              DrawLocationsDDL
    //                              GetOSName
	//                              InitWYSIWYG
	//                              Lang
	//                              FileGetContents
	//                              SubString
	//                              InsertEventsOccurrences
	//                              InsertEventsOccurrencesRepeatedly
	//                              DrawTime
	//                              DisplayDebugInfo
	//                              DrawRepeatTimeDDL
	//                              DrawRepeatEveryDDL
	//                              DrawRepeatOnWeekdayDDL
	//                              DrawRepeatOnWeekdayNumDDL
    //                              DrawEventTooltips
    //                              DrawErrors
    //                              DrawWarnings
    //                              Message
    //                              ReplaceHolders
    //                              DrawEventsListForDayPrepare
    //                              DrawEventsListForDay
    //                              FillTimeSlots
	//                              DrawEventsOccurrencesManagement
    //
	
	//--- PUBLIC DATA MEMBERS --------------------------------------------------
	public $error;
	
	//--- PROTECTED DATA MEMBERS -----------------------------------------------
	protected $weekDayNameLength;
	
	//--- PRIVATE DATA MEMBERS -------------------------------------------------
	private $arrWeekDays;
	private $arrMonths;
	private $arrViewTypes;
	private $defaultView;
	private $eventsDisplayType;
	private $defaultAction;
	private $showControlPanel;
	private $calendarType;
    private $arrEventTooltips;
	
	private $arrParameters;
	private $arrToday;
	private $prevYear;
	private $nextYear;
	private $prevMonth;
	private $nextMonth;
	private $currWeek;
	private $prevWeek;
	private $nextWeek;
	private $prevDay;
	private $nextDay;
	
	private $isDrawNavigation;
	private $isWeekNumberOfYear;
	
	private $crLt;	
	private $caption;		
	private $calWidth;		
	private $calHeight;
	private $celHeight;
	private $weekColWidth;
	private $sundayColor;

	private $timezone;
	private $timeFormat;
	private $timeFormatSQL;
	private $isShowTime;	
	
	private $langName;
	private $lang;
	
	private $arrEventsOperations;
	private $isParticipantsAllowed;
	private $arrParticipantsOperations;
	private $isCategoriesAllowed;
	private $arrCatOperations;    
	private $isLocationsAllowed;
	private $arrLocOperations;
	private $arrCalendarOperations;
	private $postBackMethod;
	private $actionLink;

    private $token;
	private $isDemo;
	private $isDebug;
	private $allowDeletingEventsInPast;
    private $allowEditingEventsInPast;
	private $allowEventsMultipleOccurrences;
	private $canNotDeleteBefore;
	private $arrMessages;
	private $arrErrors;
    private $arrWarnings;
    private $arrSQLs;
    private $startTime;
	private $endTime;
	
	private $addEventFormType;
	
	private $monthlyDayEvents;
	
	private $isCachingAllowed;
	private $cacheLifetime;
	private $cacheDir;
	private $maxCacheFiles;
	
	private $isWYSIWYG;
	private $exportDir;
    private $exportTypes;
	
	private $participantID;
    private $categoryID;
	private $calDir;

	private $fromHour;
	private $toHour;
	
	private $httpRequestVars;
	
	private $arrInitJsFunction;
	
	private $arrTemp;

	static private $version = '3.7.5';
	
	private $alertBeforeHours = '1';
	private $adminEmail;
	private $uPrefix = 'cal_';
    private $viewOnly;
    
	// -- non-documented ----------
	private $disableEarlierHours = 'false';
	private $hideWeekEmptySlots = false;
	private $isEventPartsAllowed = false;
	private $dataSaveType = 'session';
    private $useStoredProcedures = false; /* deprecated */
	private $separator = '$$';
    private $viewChangerType = 'dropdownlist'; /* dropdownlist|tabs */
    private $isAnchorAllowed = false;
    private $direction = 'ltr'; /* ltr|rtl */
    private $dateFormat = 'mm/dd/yyyy'; /* 'dd/mm/yyyy', 'mm/dd/yyyy' or 'yyyy/mm/dd' */
    private $removeCategoryEvents = false;
    private $eventsLinkTarget = ''; /* _new, _blank, _parent, _self etc. */
    private $allowEventsWithSameName = false;
    private $highlightSelectedDay = false;
    private $showEmptyTimeSlots = true;
    private $saturdayColor = false; 
	
		
	//--------------------------------------------------------------------------
    // CLASS CONSTRUCTOR
    //  @param $calendar_type
    //  @param $prefix
    //  @param $view_only
	//--------------------------------------------------------------------------
	function __construct($calendar_type = 'normal', $prefix = '', $view_only = false)
	{
		if(defined('CALENDAR_DIR')) $this->calDir = CALENDAR_DIR;
		else $this->calDir = '';
        
        if(!empty($prefix)) $this->uPrefix = preg_replace('/[^a-zA-Z0-9_-]+/', '', $prefix);
        $this->viewOnly = ($view_only == true) ? true : false;
		
        $this->arrEventTooltips = array();
		$this->calendarType  = ($calendar_type == 'small') ? 'small' : 'normal';
		$this->defaultView   = 'monthly';
		$this->defaultAction = 'view';
		$this->showControlPanel = true;
		$this->eventsDisplayType = array('daily'=>'inline', 'weekly'=>'inline', 'monthly'=>'inline');
	
		// possible values 1|2|...7
		$this->weekStartedDay = 1;
		$this->weekDisabledDays = array('1'=>false, '2'=>false, '3'=>false, '4'=>false, '5'=>false, '6'=>false, '7'=>false);
		
		$this->weekDayNameLength = 'short'; // short|long

		$this->langName = 'en';
		
		$this->timezone = self::GetDefaultTimeZone();
		@date_default_timezone_set($this->timezone); 		
		$this->timeFormat = '24';
		$this->timeFormatSQL = '%H:%i';
		$this->timeSlot = '60';
		$this->isShowTime = true;

		$this->arrEventsOperations = array();
		$this->arrEventsOperations['add']     = true;
		$this->arrEventsOperations['edit']    = true;
		$this->arrEventsOperations['details'] = true;
		$this->arrEventsOperations['delete']  = true;
		$this->arrEventsOperations['delete_by_range']  = true;
		$this->arrEventsOperations['manage'] = true;

		$this->isParticipantsAllowed = false;
		$this->arrParticipantsOperations = array();
		$this->arrParticipantsOperations['add']     = true;
		$this->arrParticipantsOperations['edit']    = true;
		$this->arrParticipantsOperations['details'] = true;
		$this->arrParticipantsOperations['delete']  = true;
		$this->arrParticipantsOperations['manage'] = true;
        $this->arrParticipantsOperations['assign_to_events'] = true;
		
		$this->isCategoriesAllowed = false;
		$this->arrCatOperations = array();
		$this->arrCatOperations['add']     = true;
		$this->arrCatOperations['edit']    = true;
		$this->arrCatOperations['details'] = true;
		$this->arrCatOperations['delete']  = true;
		$this->arrCatOperations['manage']  = true;
		$this->arrCatOperations['allow_colors'] = true;
        $this->arrCatOperations['show_filter'] = true;

		$this->isLocationsAllowed = false;
		$this->arrLocOperations = array();
		$this->arrLocOperations['add']     = false;
		$this->arrLocOperations['edit']    = false;
		$this->arrLocOperations['details'] = false;
		$this->arrLocOperations['delete']  = false;
		$this->arrLocOperations['manage']  = false;
        $this->arrLocOperations['show_filter'] = false;

		$this->arrCalendarOperations = array();
		$this->arrCalendarOperations['statistics'] = false;
		$this->arrCalendarOperations['exporting'] = false;
		$this->arrCalendarOperations['printing'] = false;		
		
		$this->arrWeekDays  = array();
		$this->arrMonths    = array();

		$this->arrViewTypes = array();
		$this->arrViewTypes['daily']     = array('name'=>'Daily', 'enabled'=>true);
		$this->arrViewTypes['weekly']    = array('name'=>'Weekly', 'enabled'=>true);
		$this->arrViewTypes['monthly']   = array('name'=>'monthly', 'enabled'=>true);
		$this->arrViewTypes['monthly_small'] = array('name'=>'Monthly Small', 'enabled'=>false);
		$this->arrViewTypes['monthly_double'] = array('name'=>'Monthly 2x', 'enabled'=>true);
		$this->arrViewTypes['yearly']  	 = array('name'=>'Yearly', 'enabled'=>true);
		$this->arrViewTypes['list_view'] = array('name'=>'List View', 'enabled'=>true);		
		
		$this->arrParameters = array();
		$this->SetDefaultParameters();

		$this->arrToday  = array();
		$this->prevYear  = array();
		$this->nextYear  = array();
		$this->prevMonth = array();
		$this->nextMonth = array();
		$this->currWeek  = array();
		$this->prevWeek  = array();
		$this->nextWeek  = array();
		$this->prevDay   = array();
		$this->nextDay   = array();
		
		$this->isDrawNavigation = true;
		$this->isWeekNumberOfYear = true;
		
		$this->crLt = "\n";
		$this->caption = '';
		$this->calWidth = '800px';
		$this->calHeight = '470px';
		$this->celHeight = number_format(((int)$this->calHeight)/6, '0').'px';
		$this->weekColWidth = number_format(((int)$this->calWidth)/8, '0').'px';
		$this->sundayColor = true;
        $this->saturdayColor = false; 

		$this->postBackMethod = 'post';
		$this->actionLink = '';
		
		$this->cssStyle = 'blue';
		$this->participantID = '0';
        $this->categoryID = '';
		
		$this->isCachingAllowed = true;
		$this->cacheLifetime = 5; // in minutes
		$this->cacheDir = $this->calDir.'tmp/cache/';
		$this->maxCacheFiles = 100;
		
		$this->isWYSIWYG = false;
		$this->exportDir = $this->calDir.'tmp/export/';
        $this->exportTypes = array(
            'csv'=>array('name'=>'CSV', 'enabled'=>true),
            'xml'=>array('name'=>'XML', 'enabled'=>true),
            'ics'=>array('name'=>'iCal', 'enabled'=>true)
        );

		$this->isDemo = false;
		$this->isDebug = false;
        $this->allowDeletingEventsInPast = true;
		$this->allowEditingEventsInPast = true;
		$this->canNotDeleteBefore = '';
		$this->allowEventsMultipleOccurrences = true;
		$this->arrMessages = array();
		$this->arrErrors = array();
        $this->arrWarnings = array();
		$this->arrSQLs = array();
	
		$this->fromHour = 0;
		$this->toHour = 24;

		$this->arrTemp = array();
		
		$this->httpRequestVars = array();
		
		$this->arrInitJsFunction = array();		

		if($this->calendarType == 'small'){
			$this->defaultAction = 'view';
			$this->showControlPanel = false;
			$this->defaultView = 'monthly_small';
			$this->arrViewTypes['monthly_small']['enabled'] = true;
			$this->arrParameters['view_type'] = 'monthly_small';
		}
		
		$this->alertBeforeHours = '24';
		$this->adminEmail = '';        

        if(!isset($_SESSION)) $this->arrWarnings[] = 'You have to turn on session feature for a correct work of the component.';

        $this->token = md5(uniqid(rand(), true));
        $_SESSION['cal_token'] = $this->token;        
	}
	
	//--------------------------------------------------------------------------
    // CLASS DESTRUCTOR
	//--------------------------------------------------------------------------
    function __destruct()
	{
		// echo 'this object has been destroyed';
    }

	
	//==========================================================================
    // PUBLIC DATA FUNCTIONS
	//==========================================================================			
	/**
	 * Show Calendar 
	*/	
	public function Show()
	{
        // start calculating running time of the script
        $this->startTime = 0;
        $this->endTime = 0;
        if($this->isDebug){
			error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
            $this->startTime = $this->GetFormattedMicrotime();
        }        

		$this->SetLanguage();
		
		$this->ResetCategoriesOperations();
        $this->ResetLocationsOperations();
		$this->ResetParticipantsOperations();
						
		//ob_start();
		$event_action = $this->GetParameter('hid_event_action');
		$event_id     = $this->GetParameter('hid_event_id');
		$category_id  = $this->GetParameter('hid_category_id');
        $location_id  = $this->GetParameter('hid_location_id');
		$participant_id      = $this->GetParameter('hid_participant_id');
		$jump_year 	  = $this->GetParameter('jump_year'); 
		$jump_month	  = $this->GetParameter('jump_month'); 
		$jump_day 	  = $this->GetParameter('jump_day'); 
		$view_type 	  = $this->GetParameter('view_type');
		
		if(!$this->viewOnly) $this->HandleEventsOperations();
		$this->GetCurrentParameters();
		$this->DrawCssStyle();
		$this->DrawJsFunctions();
		$this->IncludeModules();

		// prepare stored http request variables
		$http_query_string = '';
        foreach($this->httpRequestVars as $key){
			if(isset($_REQUEST[$key])){
				$http_query_string .= ((!$http_query_string) ? '?' : '&').$key.'='.$this->RemoveBadChars($_REQUEST[$key]);	
			}
		}
        if($this->isAnchorAllowed) echo '<a name="'.$this->uPrefix.'top"></a>'.$this->crLt;
		if(!$this->viewOnly){
            echo '<form name="frmCalendar" id="frmCalendar" action="'.$this->arrParameters['current_file'].$http_query_string.'" method="'.$this->postBackMethod.'">'.$this->crLt;
            echo '<input type="hidden" name="cal_token" value="'.$this->token.'" />'.$this->crLt;
            echo '<input type="hidden" id="hid_event_action" name="hid_event_action" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_event_id" name="hid_event_id" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_action" name="hid_action" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_view_type" name="hid_view_type" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_year" name="hid_year" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_month" name="hid_month" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_day" name="hid_day" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_previous_action" name="hid_previous_action" value="'.$event_action.'" />'.$this->crLt;
            echo '<input type="hidden" id="hid_page" name="hid_page" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_chart_type" name="hid_chart_type" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_category_id" name="hid_category_id" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_location_id" name="hid_location_id" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_participant_id" name="hid_participant_id" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_event_participant_id" name="hid_event_participant_id" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_sort_by" name="hid_sort_by" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_sort_direction" name="hid_sort_direction" value="" />'.$this->crLt;
            echo '<input type="hidden" id="hid_selected_category" name="hid_selected_category" value="'.$this->arrParameters['selected_category'].'" />'.$this->crLt;
            echo '<input type="hidden" id="hid_selected_location" name="hid_selected_location" value="'.$this->arrParameters['selected_location'].'" />'.$this->crLt;
            echo '<input type="hidden" id="hid_operation_randomize_code" name="hid_operation_randomize_code" value="'.$this->GetRandomString(10).'" />'.$this->crLt;
        }
        
        $arr_allowed_actions = array(
            'events_add', 'events_edit', 'events_details', 'events_delete', 'events_update', 'events_management', 'events_participants_management', 'events_delete_by_range', 'events_participants_assign', 'events_participants_delete', 'events_show_occurrences',
            'categories_add', 'categories_update', 'categories_details', 'categories_delete', 'categories_edit', 'categories_management',
            'locations_add', 'locations_update', 'locations_details', 'locations_delete', 'locations_edit', 'locations_management',
            'participants_add', 'participants_update', 'participants_details', 'participants_delete', 'participants_edit', 'participants_management'
        );
		if(in_array($event_action, $arr_allowed_actions)){
			echo '<input type="hidden" id="view_type" name="view_type" value="'.$view_type.'" />'.$this->crLt;
			echo '<input type="hidden" id="jump_year" name="jump_year" value="'.$jump_year.'" />'.$this->crLt;
			echo '<input type="hidden" id="jump_month" name="jump_month" value="'.$jump_month.'" />'.$this->crLt;
			echo '<input type="hidden" id="jump_day" name="jump_day" value="'.$jump_day.'" />'.$this->crLt;			
		}
		
		echo '<div id="calendar_wrapper" style="width:'.$this->calWidth.';">'.$this->crLt;		
		
		// draw calendar header
		if($this->defaultView != 'monthly_small'){
			echo '<table id="calendar_header">'.$this->crLt;
			if(!empty($this->caption)) echo '<tr><th class="caption" colspan="2">'.$this->caption.'</th></tr>';
			echo '<tr>';
			echo '<th class="caption_left">'.$this->DrawTodayJumper(false).'</th>';
            echo '<th class="types_changer" nowrap="nowrap">';
            $style = ($this->direction == 'rtl') ? 'width:145px;margin:0 0 0 5px;' : 'width:145px;margin:0 5px 0 0;';
            if($event_action != 'events_exporting' && $event_action != 'events_exporting_execute'){
                if($this->arrCatOperations['show_filter']) echo $this->DrawCategoriesDDL($this->arrParameters['selected_category'], 'onchange="javascript:phpCalendar.categoryChange(this.value,\''.$this->arrParameters['view_type'].'\');"', $style, 'sel_category_name_change', true);
            }            
            if($event_action != 'events_exporting' && $event_action != 'events_exporting_execute'){
                if($this->arrLocOperations['show_filter']) echo $this->DrawLocationsDDL($this->arrParameters['selected_location'], 'onchange="javascript:phpCalendar.locationChange(this.value,\''.$this->arrParameters['view_type'].'\');"', $style, 'sel_location_name_change', true);
            }            
            echo $this->DrawTypesChanger(false);
            echo '</th>';
			echo '</tr>'.$this->crLt;
            echo '</table>'.$this->crLt;
            
            echo '<table id="calendar_header">'.$this->crLt;	
			if($this->showControlPanel){
				echo '<tr>';			
				echo '<th class="caption_left" valign="bottom">
					<table cellpadding="0" cellspacing="1"><tr>';
					$tabs_count = 0;
					$tabs_additional_count = 0;
					if($this->arrEventsOperations['manage']){					
						echo '<td><img class="cal_icons" src="'.$this->calDir.'style/'.$this->cssStyle.'/images/manage_events.png" border="0" alt="events" /></td>';
						echo '<td>&nbsp;<a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_management\')" '.(($this->IsActionType($event_action) == 'event') ? 'class="tab_active"' : '').'>'.$this->Lang('events').'</a></td>';
						$tabs_count++;
					}
					if($this->arrCatOperations['manage']){
						if($tabs_count++ > 0) echo '<td>&nbsp;|&nbsp;</td>';
						echo '<td><img class="cal_icons" src="'.$this->calDir.'style/'.$this->cssStyle.'/images/manage_categories.png" border="0" alt="categories" /></td>';
						echo '<td>&nbsp;<a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'categories_management\')" '.(($this->IsActionType($event_action) == 'category') ? 'class="tab_active"' : '').'>'.$this->Lang('categories').'</a></td>';
					}
					if($this->arrLocOperations['manage']){
						if($tabs_count++ > 0) echo '<td>&nbsp;|&nbsp;</td>';
						echo '<td><img class="cal_icons" src="'.$this->calDir.'style/'.$this->cssStyle.'/images/manage_locations.png" border="0" alt="locations" /></td>';
						echo '<td>&nbsp;<a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'locations_management\')" '.(($this->IsActionType($event_action) == 'location') ? 'class="tab_active"' : '').'>'.$this->Lang('locations').'</a></td>';
					}
					if($this->arrParticipantsOperations['manage']){
						if($tabs_count++ > 0) echo '<td>&nbsp;|&nbsp;</td>';
						echo '<td><img class="cal_icons" src="'.$this->calDir.'style/'.$this->cssStyle.'/images/manage_participants.png" border="0" alt="participants" /></td>';
						echo '<td>&nbsp;<a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'participants_management\')" '.(($this->IsActionType($event_action) == 'participant') ? 'class="tab_active"' : '').'>'.$this->Lang('participants').'</a></td>';
					}
					if($this->arrCalendarOperations['statistics']){
						if($tabs_count++ > 0) echo '<td>&nbsp;|&nbsp;</td>';
						echo '<td><img class="cal_icons" src="'.$this->calDir.'style/'.$this->cssStyle.'/images/events_statistics.png" border="0" alt="statistics" /></td>';
						echo '<td>&nbsp;<a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_statistics\')" '.(($this->IsActionType($event_action) == 'statistics') ? 'class="tab_active"' : '').'>'.$this->Lang('statistics').'</a></td>';
					}
				echo '  </tr></table></th>';
				echo '<th class="caption_right" valign="bottom">';
					if($this->arrCalendarOperations['exporting']){
                        echo '<img class="cal_export" src="'.$this->calDir.'style/'.$this->cssStyle.'/images/events_exporting.png" border="0" alt="export" /><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_exporting\')" '.(($this->IsActionType($event_action) == 'exporting') ? 'class="tab_active"' : '').' title="'.$this->Lang('click_to_export').'">'.$this->Lang('export').'</a>';
						$tabs_additional_count++;
					}
					if($this->arrCalendarOperations['printing']){
						if($tabs_additional_count > 0) echo '&nbsp;|&nbsp;';
						echo '<img class="cal_print" src="'.$this->calDir.'style/'.$this->cssStyle.'/images/print.png" border="0" alt="print" />';
                        echo '<a href="javascript:void(\'print\');" onclick="javascript:window.print()" title="'.$this->Lang('click_to_print').'">'.$this->Lang('print').'</a>';
                        
					}
				echo '</th>';
				echo '</tr>'.$this->crLt;			
			}		
			echo $this->DrawMessages(false);		
			echo '</table>';
		}

		switch($event_action)
		{
			// Events actions
			case 'events_add':
				if($this->arrEventsOperations['add']) $this->DrawEventsAddForm();
				else echo '<span class="cal_msg_error">'.$this->Lang('msg_this_operation_blocked').'</span>';
				break;			
			case 'events_edit':
				if($this->arrEventsOperations['edit']) $this->DrawEventsEditForm($event_id);
				else echo '<span class="cal_msg_error">'.$this->Lang('msg_this_operation_blocked').'</span>';
				break;			
			case 'events_details':
				if($this->arrEventsOperations['details']) $this->DrawEventsDetailsForm($event_id);
				else echo '<span class="cal_msg_error">'.$this->Lang('msg_this_operation_blocked').'</span>';
				break;			
			case 'events_management':
			case 'events_delete':
			case 'events_update':
			case 'events_insert':
			case 'events_delete_by_range':
				if($this->arrEventsOperations['manage']) $this->DrawEventsManagement();
				break;
			case 'events_by_range':
				if($this->arrEventsOperations['manage']) $this->DrawDeleteEventsByRange();
				break;
			case 'events_participants_management':
			case 'events_participants_assign':
			case 'events_participants_delete':
				if($this->arrEventsOperations['manage']) $this->DrawEventsParticipantsManagement($event_id);
				break;			
			case 'events_show_occurrences':
				if($this->arrEventsOperations['manage']) $this->DrawEventsOccurrencesManagement($event_id);
				break;			
				break;			

            // Categories actions
			case 'categories_add':
				if($this->arrCatOperations['add']) $this->DrawCategoriesAddForm();
				break;						
			case 'categories_edit':
				if($this->arrCatOperations['manage']) $this->DrawCategoriesEditForm($category_id);
				break;			
			case 'categories_details':
				if($this->arrCatOperations['manage']) $this->DrawCategoriesDetailsForm($category_id);
				break;			
			case 'categories_management':
			case 'categories_delete':
			case 'categories_update':
			case 'categories_insert':
				if($this->arrCatOperations['manage']) $this->DrawEventsCategoriesManagement();
				break;

            // Locations actions
			case 'locations_add':
				if($this->arrLocOperations['add']) $this->DrawLocationsAddForm();
				break;						
			case 'locations_edit':
				if($this->arrLocOperations['manage']) $this->DrawLocationsEditForm($location_id);
				break;			
			case 'locations_details':
				if($this->arrLocOperations['manage']) $this->DrawLocationsDetailsForm($location_id);
				break;			
			case 'locations_management':
			case 'locations_delete':
			case 'locations_update':
			case 'locations_insert':
				if($this->arrLocOperations['manage']) $this->DrawEventsLocationsManagement();
				break;

            // Participants actions
			case 'participants_add':
				if($this->arrParticipantsOperations['add']) $this->DrawParticipantsAddForm();
				break;						
			case 'participants_edit':
				if($this->arrParticipantsOperations['manage'] && $this->arrParticipantsOperations['edit']) $this->DrawParticipantsEditForm($participant_id);
				break;			
			case 'participants_details':
				if($this->arrParticipantsOperations['manage'] && $this->arrParticipantsOperations['details']) $this->DrawParticipantsDetailsForm($participant_id);
				break;			
			case 'participants_management':
			case 'participants_delete':
			case 'participants_update':
			case 'participants_insert':
				if($this->arrParticipantsOperations['manage']) $this->DrawParticipantsManagement();
				break;

			// Events statistics
			case 'events_statistics':
				if($this->arrEventsOperations['details']) $this->DrawEventsStatistics();
				else echo '<span class="cal_msg_error">'.$this->Lang('msg_this_operation_blocked').'</span>';
				break;

			// Events export
			case 'events_exporting':
			case 'events_exporting_execute':
				if($this->arrEventsOperations['details']) $this->DrawEventsExporting();
				else echo '<span class="cal_msg_error">'.$this->Lang('msg_this_operation_blocked').'</span>';
				break;
			
			default:
				switch($this->arrParameters['view_type'])
				{			
					case 'daily':
						$this->DrawDay();
						break;
					case 'weekly':
						$this->DrawWeek();
						break;
					case 'yearly':
						$this->DrawYear();
						break;			
					case 'list_view':
						$this->DrawListView();
						break;			
					case 'monthly_small':					
						$this->DrawMonthSmall();
						break;			
					case 'monthly_double':					
						$this->DrawMonthDoubled();
						break;			
					default:
					case 'monthly':
						if($this->arrViewTypes['monthly']['enabled']) $this->DrawMonth();
						break;
				}			
			break;
		}
		
		echo '</div>'.$this->crLt;
        if(!$this->viewOnly) echo '</form>'.$this->crLt;
		$this->InitializeJsFunctions();
		
		if($this->isDebug){
			$this->endTime = $this->GetFormattedMicrotime();			
            // check stored procedures feature
            if($this->useStoredProcedures){
                $sql = 'SELECT * FROM `information_schema`.`ROUTINES` WHERE `ROUTINE_NAME` = \'apphp_text_encode_overlib\' OR `ROUTINE_NAME` = \'apphp_text_encode\'';
                $result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Checks whether stored procedures are installed');
                if($result[1] != 2) $this->arrErrors[] = 'Stored procedures feataure is \'Turned On\', but \'apphp_text_encode\' and \'apphp_text_encode_overlib\' are not installed! Please re-install them.<br />';
            }
			$this->DisplayDebugInfo();
		}		

        // set cookies for calendar		
		if($this->dataSaveType == 'cookie' && isset($_COOKIE)){
			$operation_randomize_code = $this->GetParameter('hid_operation_randomize_code');
			echo $this->crLt.'<script type="text/javascript">phpCalendar.setCookie(\''.$this->uPrefix.'operation_randomize_code\',\''.$operation_randomize_code.'\');</script>';			
		}
        echo $this->crLt.'<!-- This code was generated by ApPHP Calendar v'.self::$version.' (https://www.apphp.com) -->'.$this->crLt;

		//ob_end_flush();		
	}	
	
	/**
	 * Set calendar dimensions
	 * @param $width
	 * @param $height
	*/	
	public function SetCalendarDimensions($width = '', $height = '')
	{
		$this->calWidth = ($width != '') ? $width : '800px';
		$this->calHeight = ($height != '') ? $height : '470px';
		$this->celHeight = number_format(((int)$this->calHeight)/6, '0').'px';
	}

	/**
	 * Set caption for calendar
	 * @param $caption_text
	*/	
	public function SetCaption($caption_text = '')
	{
		$this->caption = $caption_text;	
	}
	
	/**
	 * Set week started day
	 * @param $started_day - started day of week 1...7
	*/	
	public function SetWeekStartedDay($started_day = '1')
	{
		if(is_numeric($started_day) && (int)$started_day >= 1 && (int)$started_day <= 7){
			$this->weekStartedDay = (int)$started_day;				
		}
	}

	/**
	 * Set week disabled days
	 * @param $disabled_days - disabled days of week 1,2,...,7
	*/	
	public function SetDisabledDays()
	{
		$disabled_days = func_get_args();
		foreach($disabled_days as $day){
			if(is_numeric($day) && (int)$day >= 1 && (int)$day <= 7){			
				$this->weekDisabledDays[$day] = true;
			}			
		}
	}

	/**
	 * Set week day name length 
	 * @param $length_name - 'short'|'long'
	*/	
	public function SetWeekDayNameLength($length_name = 'short')
	{
		if(strtolower($length_name) == 'long'){
			$this->weekDayNameLength = 'long';
		}
	}
	
	/**
	 * Show week number of year
	 * @param $show - true|false
	*/	
	public function ShowWeekNumberOfYear($show = true)
	{
		if($show === true || strtolower($show) == 'true'){
			$this->isWeekNumberOfYear = true;
		}else{
			$this->isWeekNumberOfYear = false;
		}
	}
	
	/**
	 * Show time in Daily and Weekly views
	 * @param $show - true|false
	*/	
	public function ShowTime($show = true)
	{
		if($show === true || strtolower($show) == 'true'){
			$this->isShowTime = true;
		}else{
			$this->isShowTime = false;
		}		
	}

	/**
	 * Set timezone
	 * @param $timezone
	*/	
	public function SetTimeZone($timezone = '')
	{
		if($timezone != ''){
			if($this->isDebug){				
				if(function_exists('timezone_identifiers_list') && !in_array($timezone, timezone_identifiers_list())){
					$this->arrErrors[] = str_ireplace('_TIME_ZONE_', $timezone, $this->Lang('msg_timezone_invalid')).'<br />';	
				}
			}
			$this->timezone = $timezone;
			@date_default_timezone_set($this->timezone); 
		}
	}

	/**
	 * Get current timezone 
	*/	
	public function GetCurrentTimeZone()
	{
		return $this->timezone;
	}
	
	/**
	 * Set displaying type of events
	 * @param $events_display_type - array
	*/	
	public function SetEventsDisplayType($events_display_type = array())
	{
		$this->eventsDisplayType['daily'] = isset($events_display_type['daily']) ? $events_display_type['daily'] : 'inline';
		$this->eventsDisplayType['weekly'] = isset($events_display_type['weekly']) ? $events_display_type['weekly'] : 'inline';
		$this->eventsDisplayType['monthly'] = isset($events_display_type['monthly']) ? $events_display_type['monthly'] : 'inline';
	}

	/**
	 * Set calendar Views
	 * @param $views - array
	*/	
	public function SetCalendarViews($views = array())
	{
		$this->arrViewTypes['daily']['enabled']     = (isset($views['daily']) && ($views['daily'] === true || strtolower($views['daily']) == 'true')) ? true : false;
		$this->arrViewTypes['weekly']['enabled']    = (isset($views['weekly']) && ($views['weekly'] === true || strtolower($views['weekly']) == 'true')) ? true : false;
		$this->arrViewTypes['monthly']['enabled']   = (isset($views['monthly']) && ($views['monthly'] === true || strtolower($views['monthly']) == 'true')) ? true : false;
		$this->arrViewTypes['monthly_small']['enabled'] = (isset($views['monthly_small']) && ($views['monthly_small'] === true || strtolower($views['monthly_small']) == 'true')) ? true : false;
		$this->arrViewTypes['monthly_double']['enabled'] = (isset($views['monthly_double']) && ($views['monthly_double'] === true || strtolower($views['monthly_double']) == 'true')) ? true : false;
		$this->arrViewTypes['yearly']['enabled']    = (isset($views['yearly']) && ($views['yearly'] === true || strtolower($views['yearly']) == 'true')) ? true : false;
		$this->arrViewTypes['list_view']['enabled'] = (isset($views['list_view']) && ($views['list_view'] === true || strtolower($views['list_view']) == 'true')) ? true : false;

        // check if default view is enabled
		if(!$this->arrViewTypes[$this->defaultView]['enabled']){
			$this->arrErrors['default_view'] = str_ireplace('_DEFAULT_VIEW_', $this->defaultView, $this->Lang('msg_view_type_invalid')).'<br />';	
		}        
	}
	
	/**
	 * Set allowed hours
	 * @param $hour_from - started hour
	 * @param $hour_to - ended hour
	*/	
	public function SetAllowedHours($from_hour, $to_hour)
	{
		$this->fromHour = (!is_numeric($from_hour) || $from_hour < 0) ? 0 : (int)$from_hour;
		$this->toHour = (!is_numeric($to_hour) || $to_hour > 24) ? 24 : (int)$to_hour;
		if(($this->fromHour > $this->toHour) && $this->isDebug){
			$this->arrErrors[] = $this->Lang('error_from_to_hour').'<br />';
		}
	}	

	/**
	 * Set default calendar view
	 * @param $default_view
	*/	
	public function SetDefaultView($default_view = 'monthly')
	{
		if(isset($this->arrViewTypes[$default_view]['enabled']) && $this->arrViewTypes[$default_view]['enabled'] == true){
			$this->defaultView = $default_view;
            unset($this->arrErrors['default_view']);
		}else{
			$this->arrErrors['default_view'] = str_ireplace('_DEFAULT_VIEW_', $default_view, $this->Lang('msg_view_type_invalid')).'<br />';	
        }
	}
	
	/**
	 * Set action link for monthly small view type
	 * @param $link
	*/	
	public function SetMonthlySmallLinks($link)
	{
		$this->actionLink = $link;
	}
	
	/**
	 * Set Sunday color
	 * @param $color
	*/	
	public function SetSundayColor($color = false)
	{
		$this->sundayColor = ($color == true || $color === 'true') ? true : false;
	}
	
	/**
	 * Set time format
	 * @param $time_format
	*/	
	public function SetTimeFormat($time_format = '24')
	{
		$this->timeFormat = (strtoupper($time_format) == 'AM/PM') ? 'AM/PM' : '24';
		if($this->timeFormat == '24'){
			$this->timeFormatSQL = '%H:%i';
		}else{
			$this->timeFormatSQL = '%h:%i %p';
		}
	}	

	/**
	 * Set time slot
	 * @param $time_slot
	*/	
	public function SetTimeSlot($time_slot = '60')
	{
		$time_slot = (int)$time_slot;
        if($time_slot == '10') $this->timeSlot = '10';	
		else if($time_slot == '15') $this->timeSlot = '15';	
		else if($time_slot == '30') $this->timeSlot = '30';	
		else if($time_slot == '45') $this->timeSlot = '45';
        else if($time_slot == '60') $this->timeSlot = '60';
        else if($time_slot == '120') $this->timeSlot = '120';	
		else $this->timeSlot = '60';	
	}	

	/**
	 * Set (allow) using calendar categories
	 * @param $allow
	*/	
	public function AllowCategories($allow = false)
	{
		$this->isCategoriesAllowed = ($allow === true || strtolower($allow) == 'true') ? true : false;
	}

	/**
	 * Set (allow) using calendar locations
	 * @param $allow
	*/	
	public function AllowLocations($allow = false)
	{
		$this->isLocationsAllowed = ($allow === true || strtolower($allow) == 'true') ? true : false;
	}
    
	/**
	 * Set calendar categories operations (old version - deprecated)
	 * @param $operations
	*/	
	public function SetCategoriesSettings($operations = array())
	{
        $this->SetCategoriesOperations($operations);
        $this->arrWarnings[] = 'The <b>SetCategoriesSettings()</b> method has been deprecated from v3.4.0 and replaced with <b>SetCategoriesOperations()</b>. Relying on this method is highly discouraged.';
    }

	/**
	 * Set calendar categories operations
	 * @param $operations
	*/	
	public function SetCategoriesOperations($operations = array())
	{
		$this->arrCatOperations['add']     = (isset($operations['add']) && ($operations['add'] === true || strtolower($operations['add']) === 'true')) ? true : false;
		$this->arrCatOperations['edit']    = (isset($operations['edit']) && ($operations['edit'] === true || strtolower($operations['edit']) === 'true')) ? true : false;
		$this->arrCatOperations['details'] = (isset($operations['details']) && ($operations['details'] === true || strtolower($operations['details']) === 'true')) ? true : false;
		$this->arrCatOperations['delete']  = (isset($operations['delete']) && ($operations['delete'] === true || strtolower($operations['delete']) === 'true')) ? true : false;
		$this->arrCatOperations['manage']  = (isset($operations['manage']) && ($operations['manage'] === true || strtolower($operations['manage']) === 'true')) ? true : false;
		$this->arrCatOperations['allow_colors'] = (isset($operations['allow_colors']) && ($operations['allow_colors'] === true || strtolower($operations['allow_colors']) === 'true')) ? true : false;
        $this->arrCatOperations['show_filter'] = (isset($operations['show_filter']) && ($operations['show_filter'] === true || strtolower($operations['show_filter']) === 'true')) ? true : false;
	}

	/**
	 * Set calendar locations operations
	 * @param $operations
	*/	
	public function SetLocationsOperations($operations = array())
	{
		$this->arrLocOperations['add']     = (isset($operations['add']) && ($operations['add'] === true || strtolower($operations['add']) === 'true')) ? true : false;
		$this->arrLocOperations['edit']    = (isset($operations['edit']) && ($operations['edit'] === true || strtolower($operations['edit']) === 'true')) ? true : false;
		$this->arrLocOperations['details'] = (isset($operations['details']) && ($operations['details'] === true || strtolower($operations['details']) === 'true')) ? true : false;
		$this->arrLocOperations['delete']  = (isset($operations['delete']) && ($operations['delete'] === true || strtolower($operations['delete']) === 'true')) ? true : false;
		$this->arrLocOperations['manage']  = (isset($operations['manage']) && ($operations['manage'] === true || strtolower($operations['manage']) === 'true')) ? true : false;
        $this->arrLocOperations['show_filter'] = (isset($operations['show_filter']) && ($operations['show_filter'] === true || strtolower($operations['show_filter']) === 'true')) ? true : false;
	}

	/**
	 * Set (allow) calendar events operations
	 * @param $operations
	*/	
	public function SetEventsOperations($operations = array())
	{
		$this->arrEventsOperations['add']      = (isset($operations['add']) && ($operations['add'] === true || strtolower($operations['add']) == 'true')) ? true : false;
		$this->arrEventsOperations['edit']     = (isset($operations['edit']) && ($operations['edit'] === true || strtolower($operations['edit']) == 'true')) ? true : false;
		$this->arrEventsOperations['details']  = (isset($operations['details']) && ($operations['details'] === true || strtolower($operations['details']) == 'true')) ? true : false;
		$this->arrEventsOperations['delete']   = (isset($operations['delete']) && ($operations['delete'] === true || strtolower($operations['delete']) == 'true')) ? true : false;
		$this->arrEventsOperations['delete_by_range'] = (isset($operations['delete_by_range']) && ($operations['delete_by_range'] === true || strtolower($operations['delete_by_range']) == 'true')) ? true : false;
		$this->arrEventsOperations['manage']   = (isset($operations['manage']) && ($operations['manage'] === true || strtolower($operations['manage']) == 'true')) ? true : false;
	}
	
	/**
	 * Set (allow) calendar participants operations
	 * @param $allow
	*/	
	public function AllowParticipants($allow = false)
	{
		$this->isParticipantsAllowed = ($allow === true || strtolower($allow) == 'true') ? true : false;        
	}

	/**
	 * Set calendar participants operations (old version - deprecated)
	 * @param $operations
	*/	
	public function SetParticipantsSettings($operations = array())
	{
        $this->SetParticipantsOperations($operations);
        $this->arrWarnings[] = 'The <b>SetParticipantsSettings()</b> method has been deprecated from v3.4.0 and replaced with <b>SetParticipantsOperations()</b>. Relying on this feature is highly discouraged.';
    }
    
	/**
	 * Set calendar participants operations
	 * @param $operations
	*/	
	public function SetParticipantsOperations($operations = array())
	{
		$this->arrParticipantsOperations['add']     = (isset($operations['add']) && ($operations['add'] === true || strtolower($operations['add']) == 'true')) ? true : false;
		$this->arrParticipantsOperations['edit']    = (isset($operations['edit']) && ($operations['edit'] === true || strtolower($operations['edit']) == 'true')) ? true : false;
		$this->arrParticipantsOperations['details'] = (isset($operations['details']) && ($operations['details'] === true || strtolower($operations['details']) == 'true')) ? true : false;
		$this->arrParticipantsOperations['delete']  = (isset($operations['delete']) && ($operations['delete'] === true || strtolower($operations['delete']) == 'true')) ? true : false;
		$this->arrParticipantsOperations['manage']  = (isset($operations['manage']) && ($operations['manage'] === true || strtolower($operations['manage']) == 'true')) ? true : false;
        $this->arrParticipantsOperations['assign_to_events']  = (isset($operations['assign_to_events']) && ($operations['assign_to_events'] === true || strtolower($operations['assign_to_events']) == 'true')) ? true : false;
	}

	/**
	 * Reset calendar participants operations
	*/	
	public function ResetParticipantsOperations()
	{
		if($this->isParticipantsAllowed == false) $this->SetParticipantsOperations(array());
	}	

	/**
	 * Set (allow) calendar operations
	 * @param $operations
	*/	
	public function SetCalendarActions($operations = array())
	{
		$this->arrCalendarOperations['statistics'] = (isset($operations['statistics']) && ($operations['statistics'] === true || strtolower($operations['statistics']) == 'true')) ? true : false;
		$this->arrCalendarOperations['exporting'] = (isset($operations['exporting']) && ($operations['exporting'] === true || strtolower($operations['exporting']) == 'true')) ? true : false;
		$this->arrCalendarOperations['printing'] = (isset($operations['printing']) && ($operations['printing'] === true || strtolower($operations['printing']) == 'true')) ? true : false;
	}

	/**
	 * Set (allow) export types
	 * @param $types
	*/	
	public function SetExportTypes($types = array())
	{
		$this->exportTypes['csv']['enabled'] = (isset($types['csv']) && ($types['csv'] === true || strtolower($types['csv']) == 'true')) ? true : false;
        $this->exportTypes['xml']['enabled'] = (isset($types['xml']) && ($types['xml'] === true || strtolower($types['xml']) == 'true')) ? true : false;
        $this->exportTypes['ics']['enabled'] = (isset($types['ics']) && ($types['ics'] === true || strtolower($types['ics']) == 'true')) ? true : false;
    }
    
	/**
	 * Set form PostBack method
	 * @param $postback_method
	*/	
	public function SetPostBackMethod($postback_method = 'post')
	{
		if(strtolower($postback_method) == 'get') $this->postBackMethod = 'get';
		else $this->postBackMethod = 'post';
	}

	/**
	 * Set CSS style
	 * @param $style
	*/	
	public function SetCssStyle($style = 'blue')
	{		
		if(empty($style) || strtolower($style) == 'blue') $this->cssStyle = 'blue';
		else if(strtolower($style) == 'green') $this->cssStyle = 'green';
		else if(strtolower($style) == 'brown') $this->cssStyle = 'brown';		
		else $this->cssStyle = $style;
	}	
	
	/**
	 * Allow using of WYSIWYG editor
	 * @param $allowed
	*/	
	public function AllowWYSIWYG($allowed = false)
	{		
		if(strtolower($allowed) == 'true' || $allowed === true) $this->isWYSIWYG = true;
		else $this->isWYSIWYG = false;
	}	

	/**
	 * Set Caching Parameters
	 * @param $allowed
	 * @param $lifetime
	*/	
	public function SetCachingParameters($allowed, $lifetime = '5')
	{		
		if(strtolower($allowed) == 'true' || $allowed === true) $this->isCachingAllowed = true;
		else $this->isCachingAllowed = false;
		// timeout in minutes
		if(is_numeric($lifetime) && $lifetime < 24*60){			
			$this->cacheLifetime = $lifetime;
		}else{
			$this->cacheLifetime = 5; 
		}
	}	
	
	/**
	 * Set interface language 
	 * @param $lang
	*/	
	public function SetInterfaceLang($lang = 'en')
	{
		if($lang != '' && strlen($lang) == '2') $this->langName = $lang;
        if($lang == 'he' || $lang == 'ar' || $lang =='ja') $this->direction = 'rtl';
		$this->SetLanguage();		
	}
	
	/**
	 * Set participant ID
	 * @param $participant_id
	*/	
	public function SetParticipantID($participant_id = '0')
	{
		if($participant_id != '' && is_numeric($participant_id)) $this->participantID = (int)$participant_id;
	}

	/**
	 * Set category ID
	 * @param $category_id
	*/	
	public function SetCategoryID($category_id = '0')
	{
		if($category_id != '' && is_numeric($category_id)) $this->categoryID = (int)$category_id;
	}

	/**
	 * Set debug mode
	 * @param $mode
	*/	
	public function Debug($mode = false)
	{
		if($mode == true || strtolower($mode) === 'true') $this->isDebug = true;
	}
	
	/**
	 * Set allow deleting events in the past
	 * @param $mode
	*/	
	public function DeletingEventsInPast($mode = false)
	{
		if($mode == false || strtolower($mode) === 'false') $this->allowDeletingEventsInPast = false;
	}

	/**
	 * Set allow editing events in the past
	 * @param $mode
	*/	
	public function EditingEventsInPast($mode = false)
	{
		if($mode == false || strtolower($mode) === 'false') $this->allowEditingEventsInPast = false;
	}
	
	/**
	 * Block deleting events before defined period of time
	 * @param $hours
	*/	
	public function BlockEventsDeletingBefore($hours = '')
	{
		if($hours != '' || is_numeric($hours)) $this->canNotDeleteBefore = intval($hours);
	}
	
	/**
	 * Set allow multiple occurrences for events
	 * @param $mode
	*/	
	public function SetEventsMultipleOccurrences($mode = true)
	{
		if($mode == false || strtolower($mode) === 'false') $this->allowEventsMultipleOccurrences = false;
	}

	/**
	 * Set admin email
	 * @param $email
	*/	
	public function SetAdminEmail($email)
	{
		$this->adminEmail = $email;
	}
	
	/**
	 * Set notification pending time
	 * @param $hours
	*/	
	public function SetNotificationTime($hours)
	{
		$this->alertBeforeHours = (int)$hours;
	}
		
	/**
	 * Send email notifications to participants
	*/	
	public function SendNotifications()
	{
		$notifications_sent = 0;
		
		$sql = 'SELECT
					'.CALENDAR_TABLE.'.id,
					'.CALENDAR_TABLE.'.event_date,
					'.CALENDAR_TABLE.'.event_time,
					'.CALENDAR_TABLE.'.slot,
					DATE_FORMAT(CONCAT('.CALENDAR_TABLE.'.event_date, " ", '.CALENDAR_TABLE.'.event_time), "%b %d, %Y %H:%i") as event_date_time_mod,					
					'.EVENTS_TABLE.'.id as event_id,
					'.EVENTS_TABLE.'.name,
                    '.EVENTS_TABLE.'.url,
					'.EVENTS_TABLE.'.description,
                    '.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.color' : '""').' as color,
                    '.(($this->isLocationsAllowed) ? EVENTS_LOCATIONS_TABLE.'.name' : '""').' as location_name,
					(SELECT CONCAT(c1.event_date,c1.event_time) FROM '.CALENDAR_TABLE.' c1 WHERE c1.slot = 1 AND c1.unique_key = '.CALENDAR_TABLE.'.unique_key LIMIT 0, 1) as priority
				FROM '.CALENDAR_TABLE.'
                    INNER JOIN '.EVENTS_TABLE.' ON '.CALENDAR_TABLE.'.event_id = '.EVENTS_TABLE.'.id
                    '.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
                    '.(($this->isLocationsAllowed) ? 'LEFT OUTER JOIN '.EVENTS_LOCATIONS_TABLE.' ON '.EVENTS_TABLE.'.location_id = '.EVENTS_LOCATIONS_TABLE.'.id ' : '').'
				WHERE
				    '.CALENDAR_TABLE.'.slot = 1 AND
					'.CALENDAR_TABLE.'.notification_sent = 0 AND 
					TIMESTAMPDIFF(HOUR, CONCAT('.CALENDAR_TABLE.'.event_date, " ", '.CALENDAR_TABLE.'.event_time), "'.date('Y-m-d H:i:s').'") BETWEEN 0 AND '.$this->alertBeforeHours.'
				GROUP BY '.EVENTS_TABLE.'.id
                ORDER BY priority ASC';
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve list of events for specific day - Cron');
        $content = $this->FileGetContents('templates/emails/email_alert.tpl');			
		for($i=0; $i<$result[1]; $i++){            
			$sql = 'SELECT
						p.id, p.first_name, p.last_name, p.email
					FROM '.PARTICIPANTS_TABLE.' p
						INNER JOIN '.EVENTS_PARTICIPANTS_TABLE.' ep ON p.id = ep.participant_id
					WHERE ep.event_id = '.(int)$result[0][$i]['event_id'];
		    $result2 = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve list of participants, assigned for specific event - Cron');
            for($j=0; $j<$result2[1]; $j++){
                $subject = 'Notification for '.$result[0][$i]['name'];
                
				$content_new = str_ireplace('{FIRST NAME}', $result2[0][$j]['first_name'], $content);
				$content_new = str_ireplace('{LAST NAME}', $result2[0][$j]['last_name'], $content_new);
                $content_new = str_ireplace('{EVENT NAME}', $result[0][$i]['name'], $content_new);
                $content_new = str_ireplace('{EVENT URL}', (($result[0][$i]['url'] != '') ? 'Link: '.$result[0][$i]['url'].'<br><br>' : ''), $content_new);
                $content_new = str_ireplace('{EVENT DATE}', $result[0][$i]['event_date_time_mod'], $content_new);
                $content_new = str_ireplace('{EVENT LOCATION}', (!empty($result[0][$i]['location_name']) ? 'In '.$result[0][$i]['location_name'].'<br>' : ''), $content_new);
                
				$this->Email($result2[0][$j]['email'], $subject, $content_new);
				$notifications_sent++;
			}
			
			$sql = 'UPDATE '.CALENDAR_TABLE.' SET notification_sent = 1 WHERE id = '.(int)$result[0][$i]['id'];
			$this->DatabaseVoidQuery($sql);
		}

		if($this->isDebug){
			if(!$notifications_sent) echo '<u><b>There were no email notifications sent!</b></u><br /><br />';
			$this->DisplayDebugInfo();
		}		    		
	}

	/**
	 * Send email
	 * @param $to
	 * @param $subject
	 * @param $content
	*/	
	public function Email($to, $subject, $content)
	{
		$ec_newline = "\r\n";
		// to send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0'.$ec_newline.
					'Content-type: text/html; charset=iso-8859-1'.$ec_newline;
		
		// additional headers
		$headers .= 'From: '.$this->adminEmail.$ec_newline.
					'Reply-To: '.$this->adminEmail.$ec_newline.
					'X-Mailer: PHP/'.phpversion();

		if($this->adminEmail != ''){
			if($this->isDebug) echo 'From: '.$this->adminEmail.' To: '.$to.' Subject: '.$subject.'<br>';
			return @mail($to, $subject, $content, $headers);
		}		
	}
	
	/**
	 * Set javascript functions
	*/	
	public function SetJsFunctions()
	{
		$this->DrawJsFunctions();
	}
	
	//==========================================================================
    // STATIC
	//==========================================================================		
	/**
	 * Return current version
	*/	
	static function Version()
	{
		return self::$version;
	}
	
	/**
	 * Return default time zone
	*/	
	static function GetDefaultTimeZone()
	{
		return @date_default_timezone_get();  
	}

	
	//==========================================================================
    // PRIVATE DATA FUNCTIONS
	//==========================================================================		
	/**
	 * Set default parameters
	*/	
	function SetDefaultParameters()
	{
		$this->arrParameters['year']  = date('Y');
		$this->arrParameters['month'] = date('m');
		$this->arrParameters['month_full_name'] = date('F');
		$this->arrParameters['day']   = date('d');
		$this->arrParameters['view_type'] = $this->defaultView;
		$this->arrParameters['action'] = 'display';
		$this->arrParameters['page'] = '1';
		$this->arrParameters['chart_type'] = 'columnchart';
		$this->arrParameters['event_action'] = '';
		$this->arrParameters['selected_category'] = '';
        $this->arrParameters['selected_location'] = '';
		$this->arrToday = getdate();

		// get current file
		$this->arrParameters['current_file'] = $_SERVER['SCRIPT_NAME'];
		$parts = explode('/', $this->arrParameters['current_file']);
		$this->arrParameters['current_file'] = $parts[count($parts) - 1];		
	}

	/**
	 * Get current parameters - read them from URL
	*/	
	function GetCurrentParameters()
	{
		$year 		     = (isset($_REQUEST['hid_year']) && $this->IsYear($_REQUEST['hid_year'])) ? $this->RemoveBadChars($_REQUEST['hid_year']) : date('Y');
		$month 		     = (isset($_REQUEST['hid_month']) && $this->IsMonth($_REQUEST['hid_month'])) ? $this->RemoveBadChars($_REQUEST['hid_month']) : date('m');
		$day 		     = (isset($_REQUEST['hid_day']) && $this->IsDay($_REQUEST['hid_day'])) ? $this->RemoveBadChars($_REQUEST['hid_day']) : date('d');
		$last_day_in_month = $this->GetDayForMonth($year,$month,$day);
		$view_type 	     = (isset($_REQUEST['hid_view_type']) && array_key_exists($_REQUEST['hid_view_type'], $this->arrViewTypes)) ? $this->RemoveBadChars($_REQUEST['hid_view_type']) : $this->defaultView;
		$previous_action = (isset($_REQUEST['hid_previous_action']) ? $this->RemoveBadChars($_REQUEST['hid_previous_action']) : '');
		$page 	    	 = $this->GetParameter('hid_page', 1);
		$chart_type 	 = $this->GetParameter('hid_chart_type', 'columnchart'); 
		$event_action 	 = $this->GetParameter('hid_event_action');
		$selected_category = $this->GetParameter('hid_selected_category');
        $selected_location = $this->GetParameter('hid_selected_location');

		if($day > $last_day_in_month) $day = $last_day_in_month;
		$cur_date = getdate(mktime(0,0,0,$month,$day,$year));	
		
		$this->arrParameters['year']  = $cur_date['year'];
		$this->arrParameters['month'] = $this->ConvertToDecimal($cur_date['mon']);
		$this->arrParameters['month_full_name'] = $this->Lang('months', $cur_date['mon']); //$cur_date['month'];
		$this->arrParameters['day']   	  = $day;
		$this->arrParameters['wday']      = $cur_date['wday'];
		$this->arrParameters['weekday']   = $this->Lang(strtolower($cur_date['weekday']));
		$this->arrParameters['view_type'] = ($this->calendarType == 'small') ? 'monthly_small' : $view_type;
		$this->arrParameters['action'] = 'view';
		$this->arrParameters['previous_action'] = $previous_action;
		$this->arrParameters['page'] = $page;
		$this->arrParameters['chart_type'] = $chart_type;
		$this->arrParameters['event_action'] = $event_action;
		$this->arrParameters['selected_category'] = ($this->categoryID != '') ? $this->categoryID : $selected_category;
        $this->arrParameters['selected_location'] = $selected_location;

		// find starting day for current day
		if($view_type == 'weekly'){
			$sign = 1;
			for($i=0; $i<7; $i++){
				$week_day = date('w', mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters['day']+($sign*$i)-($this->weekStartedDay-1),$this->arrParameters['year']));
				if(($i == 0) && ($week_day != '0')) $sign = -1;
				if($week_day == '0'){
					$parts = explode('-', date('d-n-Y', mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters['day']+($sign*$i),$this->arrParameters['year'])));
					$this->currWeek['year']  = $parts[2];
					$this->currWeek['month'] = $this->ConvertToDecimal($parts[1]);
					$this->currWeek['month_full_name'] = $this->Lang('months', $parts[1]); 
					$this->currWeek['day']   = $parts[0];					
					break;
				}					
			}				
		}else{
			$this->currWeek['year']  = $this->arrParameters['year'];
			$this->currWeek['month'] = $this->arrParameters['month'];
			$this->currWeek['month_full_name'] = $this->arrParameters['month_full_name']; 
			$this->currWeek['day']   = $this->arrParameters['day'];
		}
		
		$this->arrToday = getdate();		

		$this->prevYear = getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters['day'],$this->arrParameters['year']-1));
		$this->prevYear['month'] = $this->Lang('months', $this->prevYear['mon']);
		$this->prevYear['weekday'] = $this->Lang(strtolower($this->prevYear['weekday']));
		$this->nextYear = getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters['day'],$this->arrParameters['year']+1));
		$this->nextYear['month'] = $this->Lang('months', $this->nextYear['mon']);
		$this->nextYear['weekday'] = $this->Lang(strtolower($this->nextYear['weekday']));

		$this->prevMonth = getdate(mktime(0,0,0,$this->arrParameters['month']-1,$this->GetDayForMonth($this->arrParameters['year'],$this->arrParameters['month']-1,$this->arrParameters['day']),$this->arrParameters['year']));
		$this->prevMonth['month'] = $this->Lang('months', $this->prevMonth['mon']);
		$this->prevMonth['weekday'] = $this->Lang(strtolower($this->prevMonth['weekday']));
		$this->nextMonth = getdate(mktime(0,0,0,$this->arrParameters['month']+1,$this->GetDayForMonth($this->arrParameters['year'],$this->arrParameters['month']+1,$this->arrParameters['day']),$this->arrParameters['year']));
		$this->nextMonth['month'] = $this->Lang('months', $this->nextMonth['mon']);
		$this->nextMonth['weekday'] = $this->Lang(strtolower($this->nextMonth['weekday']));
		
		$this->prevWeek = getdate(mktime(0,0,0,$this->currWeek['month'],$this->currWeek['day']-7,$this->currWeek['year']));
		$this->prevWeek['month'] = $this->Lang('months', $this->prevWeek['mon']);
		$this->prevWeek['weekday'] = $this->Lang(strtolower($this->prevWeek['weekday']));
		$this->nextWeek = getdate(mktime(0,0,0,$this->currWeek['month'],$this->currWeek['day']+7,$this->currWeek['year']));
		$this->nextWeek['month'] = $this->Lang('months', $this->nextWeek['mon']);
		$this->nextWeek['weekday'] = $this->Lang(strtolower($this->nextWeek['weekday']));

        $this->prevDay = getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters['day']-1,$this->arrParameters['year']));
		$this->prevDay['month'] = $this->Lang('months', $this->prevDay['mon']);
		$this->prevDay['weekday'] = $this->Lang(strtolower($this->prevDay['weekday']));
		$this->nextDay = getdate(mktime(0,0,0,$this->arrParameters['month'],$this->arrParameters['day']+1,$this->arrParameters['year']));
		$this->nextDay['month'] = $this->Lang('months', $this->nextDay['mon']);
		$this->nextDay['weekday'] = $this->Lang(strtolower($this->nextDay['weekday']));
	}

	/**
	 * Handle events - proccess events: insert, edit or delete
	*/	
	private function HandleEventsOperations()
	{
		$event_action 		= $this->GetParameter('hid_event_action');
		$event_id 		    = $this->GetParameter('hid_event_id');
		$sel_event 			= $this->GetParameter('sel_event'); 
		$event_name 		= strip_tags($this->GetParameter('event_name'));
        $event_url 		    = strip_tags($this->GetParameter('event_url'));
		$sel_event_name		= $this->GetParameter('sel_event_name'); 
							$sel_event_name_array = explode('#', $sel_event_name);
							if(isset($sel_event_name_array[0])) $sel_event_name = $sel_event_name_array[0];
							
		$event_description 	= $this->GetParameter('event_description');  
		$event_insertion_type = $this->GetParameter('event_insertion_type');
		$sel_category_id	= $this->GetParameter('sel_category_name'); 
							$sel_category_id_array = explode('#', $sel_category_id);
							if(isset($sel_category_id_array[0])) $sel_category_id = $sel_category_id_array[0];
        $sel_export_format	= $this->GetParameter('sel_export_format');                             

		$event_insertion_subtype = $this->GetParameter('event_insertion_subtype');
		$event_from_time_hour  	= $this->GetParameter('event_from_time_hour');
		$event_from_date_day   	= $this->GetParameter('event_from_date_day');
		$event_from_date_month 	= $this->GetParameter('event_from_date_month');
		$event_from_date_year  	= $this->GetParameter('event_from_date_year');
		$event_from_date  	   	= $event_from_date_year.'-'.$event_from_date_month.'-'.$event_from_date_day;
		$event_to_time_hour  	= $this->GetParameter('event_to_time_hour');
		$event_to_date_day   	= $this->GetParameter('event_to_date_day');
		$event_to_date_month 	= $this->GetParameter('event_to_date_month');
		$event_to_date_year  	= $this->GetParameter('event_to_date_year');
		$event_to_date  	   	= $event_to_date_year.'-'.$event_to_date_month.'-'.$event_to_date_day;
		$event_week_days        = array('0'=>$this->GetParameter('repeat_sun'),
										'1'=>$this->GetParameter('repeat_mon'),
										'2'=>$this->GetParameter('repeat_tue'),
										'3'=>$this->GetParameter('repeat_wed'),
										'4'=>$this->GetParameter('repeat_thu'),
										'5'=>$this->GetParameter('repeat_fri'),
										'6'=>$this->GetParameter('repeat_sat'));
		$event_repeat_type 		= $this->GetParameter('event_repeat_type');
		$event_repeat_every  	= $this->GetParameter('event_repeat_every');

		$event_from_hour 	= $this->GetParameter('event_from_hour');
		$event_from_day 	= $this->GetParameter('event_from_day');
		$event_from_month 	= $this->GetParameter('event_from_month');
		$event_from_year 	= $this->GetParameter('event_from_year');
		$start_date         = $event_from_year.'-'.$event_from_month.'-'.$event_from_day.' '.$event_from_hour;
		
		$event_to_hour 		= $this->GetParameter('event_to_hour');
		$event_to_day 		= $this->GetParameter('event_to_day'); 
		$event_to_month 	= $this->GetParameter('event_to_month'); 
		$event_to_year 		= $this->GetParameter('event_to_year'); 
		$finish_date_trim   = $event_to_year.$event_to_month.$event_to_day.$event_to_hour;
		$finish_date        = $event_to_year.'-'.$event_to_month.'-'.$event_to_day.' '.$event_to_hour;
		
		$category_id        = $this->GetParameter('hid_category_id'); 
		$category_name 		= $this->GetParameter('category_name'); 
		$category_description = $this->GetParameter('category_description'); 
		$category_color     = $this->GetParameter('category_color'); 
		$category_duration  = $this->GetParameter('category_duration');
        $show_in_filter     = $this->GetParameter('show_in_filter');

        $location_id        = $this->GetParameter('hid_location_id'); 
		$location_name 		= $this->GetParameter('location_name'); 
		$location_description = $this->GetParameter('location_description');
        $sel_location_id	= $this->GetParameter('sel_location_name'); 

		$participant_id  	= $this->GetParameter('hid_participant_id');
		$first_name			= $this->GetParameter('first_name');
		$last_name 			= $this->GetParameter('last_name');
		$email  			= $this->GetParameter('email');
		
		// edit single event
		$event_unique_key  		= $this->GetParameter('event_unique_key');
		$event_from_edit_year 	= $this->GetParameter('event_from_edit_year');
		$event_from_edit_month 	= $this->GetParameter('event_from_edit_month');
		$event_from_edit_day 	= $this->GetParameter('event_from_edit_day');
		$event_from_edit_hour 	= $this->GetParameter('event_from_edit_hour');
		$event_to_edit_year 	= $this->GetParameter('event_to_edit_year');
		$event_to_edit_month 	= $this->GetParameter('event_to_edit_month');
		$event_to_edit_day 		= $this->GetParameter('event_to_edit_day');
		$event_to_edit_hour 	= $this->GetParameter('event_to_edit_hour');
		
		$operation_randomize_code = $this->GetParameter('hid_operation_randomize_code');
		
		$start_edit_date        = $event_from_edit_year.'-'.$event_from_edit_month.'-'.$event_from_edit_day.' '.$event_from_edit_hour;
		$finish_edit_date       = $event_to_edit_year.'-'.$event_to_edit_month.'-'.$event_to_edit_day.' '.$event_to_edit_hour;
		$event_name_edit		= strip_tags($this->GetParameter('event_name_edit'));
        $event_url_edit         = strip_tags($this->GetParameter('event_url_edit'));		
		$event_description_edit = $this->GetParameter('event_description_edit');		
		$sql = '';
		$arr_actions = array('events_statistics', 'events_management', 'events_participants_management',
		                     'events_add',        'events_edit',       'categories_management',
							 'categories_add',    'categories_edit',   'events_show_occurrences',
							 'events_by_range',   'events_details',    'categories_details',
							 'participants_management',  'participants_details',     'participants_edit',
                             'locations_add',     'locations_edit',    'locations_management', 
							 'participants_add',   'events_exporting');
		
		if($this->isDemo && $event_action != '' && !in_array($event_action, $arr_actions)){ 
			$this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked_demo'));
			return false;
		}
		
		if($event_action == 'add'){
			// insert single event
			$insert_id = false;
			if($sel_event == 'new'){
                if(!$this->allowEventsWithSameName){
                    $sql = 'SELECT COUNT(*) as cnt FROM '.EVENTS_TABLE.' WHERE name = \''.$this->PrepareText($event_name).'\' '.$this->PrepareWhereClauseParticipant().' AND category_id = '.(int)$sel_category_id;
                    $event_exists = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Check if event exists');                
                    if($event_exists['cnt'] > 0){
                        $this->arrMessages[] = $this->Message('error', $this->Lang('error_event_exists'));
                        return false;
                    }                    
                }
                $sql = 'INSERT INTO '.EVENTS_TABLE.' (id, participant_id, name, url, description, category_id, location_id) VALUES (NULL, '.(int)$this->participantID.', \''.$this->PrepareText($event_name).'\', \''.$this->PrepareText($event_url).'\', \''.$this->PrepareText($event_description).'\', \''.(int)$sel_category_id.'\', \''.(int)$sel_location_id.'\')';
                $insert_id = $this->DatabaseVoidQuery($sql, 'Insert new event');	
			}else if($sel_event == 'current'){
				$insert_id = $sel_event_name;
			}
			if($insert_id != false){
				$result = $this->InsertEventsOccurrences($insert_id, $start_date, $finish_date, $event_from_hour, '', $event_from_day, $event_from_month, $event_from_year);
				if($result) $this->arrMessages[] = $this->Message('success', $this->Lang('success_new_event_was_added'));
				$this->DeleteCache();
			}else{
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_inserting_new_events'));
			}
		}else if($event_action == 'update_event'){
			// update single event occurrences
			$insert_id = false;
			
			//in check [#002 24.08.2010] added GROUP BY '.CALENDAR_TABLE.'.event_date to prevent SQL error: Mixing of GROUP columns (MIN....
			$sql = 'SELECT
						MIN(CONCAT('.CALENDAR_TABLE.'.event_date, " ",'.CALENDAR_TABLE.'.event_time)) as start_datetime,
						MAX(CONCAT('.CALENDAR_TABLE.'.event_date, " ",'.CALENDAR_TABLE.'.event_time)) as finish_datetime,
						'.CALENDAR_TABLE.'.event_id,
						'.EVENTS_TABLE.'.category_id,
                        '.EVENTS_TABLE.'.name,
                        '.EVENTS_TABLE.'.url,
						'.EVENTS_TABLE.'.description
					FROM '.CALENDAR_TABLE.'
						INNER JOIN '.EVENTS_TABLE.' ON '.CALENDAR_TABLE.'.event_id = '.EVENTS_TABLE.'.id
					WHERE unique_key = \''.$this->PrepareText($event_unique_key).'\'
					GROUP BY '.CALENDAR_TABLE.'.event_date,
						'.CALENDAR_TABLE.'.event_id,
						'.EVENTS_TABLE.'.category_id,
						'.EVENTS_TABLE.'.name,
						'.EVENTS_TABLE.'.url,
						'.EVENTS_TABLE.'.description';
					
			$res = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve event ID by unique key');                
			if(isset($res['event_id']) && $res['event_id'] > 0){
				$res_finish_datetime = date('Y-m-d H:i:s', (strtotime($res['finish_datetime']) + $this->timeSlot*60));
				$result = true;
				if(($start_edit_date.':00' == $res['start_datetime']) && ($finish_edit_date.':00' <= $res_finish_datetime)){
					// delete occurrences of event if start datetime the same and finish datetime less or equal
					$sql = 'DELETE FROM '.CALENDAR_TABLE.' WHERE unique_key = \''.$event_unique_key.'\' AND CONCAT(event_date, " ",event_time) >= \''.$finish_edit_date.':00\'';
					$this->DatabaseVoidQuery($sql, 'Delete event occurrences from calendar');
				}else{					
					$result = $this->InsertEventsOccurrences($res['event_id'], $start_edit_date, $finish_edit_date, $event_from_edit_hour, '', $event_from_edit_day, $event_from_edit_month, $event_from_edit_year, true, true, $event_unique_key);
				}
				// update event info
				if($res['name'] != $event_name_edit || $res['url'] != $event_url_edit || $res['description'] != $event_description_edit || $res['category_id'] != $sel_category_id){
                    $sql = 'UPDATE '.EVENTS_TABLE.' SET ';
                    if($this->isCategoriesAllowed) $sql .= 'category_id = \''.(int)$sel_category_id.'\', ';
                    $sql .= 'name = \''.$this->PrepareText($event_name_edit).'\', url = \''.$this->PrepareText($event_url_edit).'\', description = \''.$this->PrepareText($event_description_edit).'\' WHERE id = '.(int)$res['event_id'];
					$this->DatabaseVoidQuery($sql, 'Update event info');
				}
				if($result) $this->arrMessages[] = $this->Message('success', $this->Lang('success_event_was_updated'));
				$this->DeleteCache();
			}else{
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_updating_event'));
			}			
		}else if($event_action == 'delete'){
			$can_delete = true;
			// check if event can be deleted
			$sql = 'SELECT
                        '.CALENDAR_TABLE.'.event_date,
                        '.CALENDAR_TABLE.'.event_time,
						'.EVENTS_TABLE.'.name,
						TIME_FORMAT(TIMEDIFF(CONCAT('.CALENDAR_TABLE.'.event_date, " ", '.CALENDAR_TABLE.'.event_time), \''.date('Y-m-d H:i:s').'\'), "%H") as time_diff
					FROM '.CALENDAR_TABLE.'
						INNER JOIN '.EVENTS_TABLE.' ON '.CALENDAR_TABLE.'.event_id = '.EVENTS_TABLE.'.id
					WHERE '.CALENDAR_TABLE.'.id = '.(int)$event_id.'
					ORDER BY time_diff ASC
					LIMIT 0, 1';					
			$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Calculate time difference to know if event may be deleted');
			$event_name = (isset($result['name'])) ? $result['name'] : '';
            if(!$this->allowDeletingEventsInPast && ($result['event_date'].' '.$result['event_time'] < date('Y-m-d H:i:s'))){
                $this->arrMessages[] = $this->Message('error', $this->Lang('msg_deleting_event_in_past'));		
                $can_delete = false;
            }else if($this->canNotDeleteBefore != '' && isset($result['time_diff']) && $result['time_diff'] < $this->canNotDeleteBefore){
				$can_delete = false;
				if($result['time_diff'] < 0){
					$this->arrMessages[] = $this->Message('error', str_ireplace('_HOURS_', $this->canNotDeleteBefore, $this->Lang('error_deleting_event_past')));							
				}else{
					$this->arrMessages[] = $this->Message('error', str_ireplace('_HOURS_', $this->canNotDeleteBefore, $this->Lang('error_deleting_event_hours')));							
				}
			}else{						
				$can_delete = true;
			}
			// delete single event
			if($can_delete){				
				$sql = 'SELECT unique_key FROM '.CALENDAR_TABLE.' WHERE id = '.(int)$event_id.' LIMIT 0, 1';
				$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve event\'s unique key');
				$event_unique_key = isset($result['unique_key']) ? $result['unique_key'] : '';
				
				if($this->isEventPartsAllowed){
					// delete single occurrence of event
					$sql = 'DELETE FROM '.CALENDAR_TABLE.' WHERE id = '.(int)$event_id;
					
					// update splitted slots
					$sql_ = 'SELECT id FROM '.CALENDAR_TABLE.' WHERE unique_key = \''.$event_unique_key.'\' AND id > '.(int)$event_id.' ORDER BY id ASC LIMIT 0, 1';	
					$result = $this->DatabaseQuery($sql_, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve event occurrence id to be updated');
					$update_id = isset($result['id']) ? $result['id'] : '';					
					if($update_id){
						$sql_ = 'UPDATE '.CALENDAR_TABLE.' SET slot = 1 WHERE id = '.(int)$update_id;
						$this->DatabaseVoidQuery($sql_, 'Update splitted slot');	
						$sql_ = 'UPDATE '.CALENDAR_TABLE.' SET unique_key = \''.$this->GetRandomString(10).'\' WHERE id >= '.(int)$update_id.' AND unique_key = \''.$event_unique_key.'\'';
						$this->DatabaseVoidQuery($sql_, 'Update splitted slot');						
					}					
				}else{
					// delete occurrences of event
					$sql = 'DELETE FROM '.CALENDAR_TABLE.' WHERE unique_key = \''.$event_unique_key.'\'';
				}
				if(!$this->DatabaseVoidQuery($sql, 'Delete event occurrences from calendar')){
					$this->arrMessages[] = $this->Message('error', $this->Lang('error_deleting_event'));
				}else{
					$this->arrMessages[] = $this->Message('success', str_ireplace('_EVENT_NAME_', $event_name, $this->Lang('success_event_was_deleted')));
					$this->DeleteCache();
				}				
			}
		}else if($event_action == 'events_insert'){
			
			if(!$this->CheckF5CaseValidation($operation_randomize_code)){
				$this->arrMessages[] = $this->Message('error', $this->Lang('F5_issue'));
				return false;
			}
			
			// insert new event
			$check_passed = true;
			if($event_insertion_type == 2 && $event_insertion_subtype == 'repeat'){
				// add occurrences repeatedly  - force insertion
				// do nothing
			}else{
                if(!$this->allowEventsWithSameName){
                    $sql = 'SELECT COUNT(*) as cnt FROM '.EVENTS_TABLE.' WHERE name = \''.$this->PrepareText($event_name).'\' '.$this->PrepareWhereClauseParticipant().' ';
                    if($this->isCategoriesAllowed) $sql .= ' AND category_id = '.(int)$sel_category_id;
                    if($this->isLocationsAllowed) $sql .= ' AND location_id = '.(int)$sel_location_id;
                    $event_exists = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Check if event exists');
                    if(!empty($event_exists['cnt'])){
                        $this->arrMessages[] = $this->Message('error', $this->Lang('error_event_exists'));
                        $check_passed = false;
                    }
                }
			} 
			if($check_passed){
				$sql = 'INSERT INTO '.EVENTS_TABLE.' (id, participant_id, name, url, description, category_id, location_id) VALUES (NULL, '.(int)$this->participantID.', \''.$this->PrepareText($event_name).'\', \''.$this->PrepareText($event_url).'\', \''.$this->PrepareText($event_description).'\', '.(int)$sel_category_id.', '.(int)$sel_location_id.')';
				$insert_id = $this->DatabaseVoidQuery($sql, 'Insert new event');	
				if($insert_id != false){
					// if add event occurrences selected
					$result = true;
					if($event_insertion_type == 2){
						if($event_insertion_subtype == 'one_time') $result = $this->InsertEventsOccurrences($insert_id, $start_date, $finish_date, $event_from_hour, $event_to_hour, $event_from_day, $event_from_month, $event_from_year, false);
						else if($event_insertion_subtype == 'repeat') $result = $this->InsertEventsOccurrencesRepeatedly($insert_id, $event_from_date, $event_to_date, $event_from_time_hour, $event_to_time_hour, $event_week_days, $event_repeat_type, $event_repeat_every);
					}					
					if($result){
						$this->arrMessages[] = $this->Message('success', $this->Lang('success_new_event_was_added'));
						if($this->dataSaveType == 'cookie' && isset($_COOKIE)){
							// set cookie - code placed at the bottom
							// $_COOKIE['operation_randomize_code'] = $operation_randomize_code;
						}else if($this->dataSaveType == 'session' && isset($_SESSION)){
							$_SESSION[$this->uPrefix.'operation_randomize_code'] = $operation_randomize_code;							
						}
					}
				}else{
					$this->arrMessages[] = $this->Message('error', $this->Lang('error_inserting_new_events'));
				}
			}
		}else if($event_action == 'events_update'){
			// update events
			$sql = 'UPDATE '.EVENTS_TABLE.' SET name = \''.$this->PrepareText($event_name).'\', url = \''.$this->PrepareText($event_url).'\', description = \''.$this->PrepareText($event_description).'\', category_id = '.(int)$sel_category_id.', location_id = '.(int)$sel_location_id.' WHERE id = '.(int)$event_id;
			if($this->DatabaseVoidQuery($sql, 'Update existing event')){
				$this->arrMessages[] = $this->Message('success', $this->Lang('success_event_was_updated'));
			}else{
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_updating_event'));
			}
		}else if($event_action == 'events_delete_by_range'){
			$sql  = 'DELETE FROM '.CALENDAR_TABLE.' WHERE ';
			if($sel_category_id != '' || $sel_location_id != '' || $this->participantID){
				$sql .= CALENDAR_TABLE.'.event_id IN (SELECT id FROM '.EVENTS_TABLE.' WHERE 1=1 '.$this->PrepareWhereClauseParticipant().' '.$this->PrepareWhereClauseCategory($sel_category_id).' '.$this->PrepareWhereClauseLocation($sel_location_id).') AND ';
			}
			$sql .= '(event_date >= \''.$event_from_year.'-'.$event_from_month.'-'.$event_from_day.'\') AND
					 (event_date <= \''.$event_to_year.'-'.$event_to_month.'-'.$event_to_day.'\') ';

			if($this->DatabaseVoidQuery($sql, 'Delete events occurrences from calendar by date range')){
				$this->arrMessages[] = $this->Message('success', $this->Lang('success_events_were_deleted'));
			}else{
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_no_event_found'));
			}			
		}else if($event_action == 'events_delete'){
			// delete event from Events table
			$sql = 'SELECT name FROM '.EVENTS_TABLE.' WHERE id = '.(int)$event_id;
			$event_name = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Check if event exists');
			if(isset($event_name['name'])){
				$can_delete = true;
                if(!$this->allowEventsMultipleOccurrences){
					$sql_1 = 'SELECT
								'.CALENDAR_TABLE.'.event_date, 
								'.CALENDAR_TABLE.'.event_time,								
								TIME_FORMAT(TIMEDIFF(CONCAT('.CALENDAR_TABLE.'.event_date, " ", '.CALENDAR_TABLE.'.event_time), \''.date('Y-m-d H:i:s').'\'), \'%H\') as time_diff
							FROM '.EVENTS_TABLE.'
								LEFT OUTER JOIN '.CALENDAR_TABLE.' ON '.EVENTS_TABLE.'.id = '.CALENDAR_TABLE.'.event_id 
							WHERE							    
								'.EVENTS_TABLE.'.id = '.(int)$event_id.'
							ORDER BY time_diff ASC
							LIMIT 0, 1';
					$result = $this->DatabaseQuery($sql_1, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Calculate time difference to know if event may be deleted');
					if($this->canNotDeleteBefore != '' && isset($result['time_diff']) && $result['time_diff'] < $this->canNotDeleteBefore){
						$can_delete = false;
						if($result['time_diff'] < 0){
							$this->arrMessages[] = $this->Message('error', str_ireplace('_HOURS_', $this->canNotDeleteBefore, $this->Lang('error_deleting_event_past')));							
						}else{
							$this->arrMessages[] = $this->Message('error', str_ireplace('_HOURS_', $this->canNotDeleteBefore, $this->Lang('error_deleting_event_hours')));							
						}						
					}else{						
						$can_delete = true;
					}
				}
				if($can_delete){
					$sql_1 = 'DELETE FROM '.EVENTS_TABLE.' WHERE id = '.(int)$event_id;			
					$sql_2 = 'DELETE FROM '.CALENDAR_TABLE.' WHERE event_id = '.(int)$event_id;
					if(!$this->DatabaseVoidQuery($sql_1, 'Delete event')){
						$this->arrMessages[] = $this->Message('error', $this->Lang('error_deleting_event'));
					}else{
						$this->DatabaseVoidQuery($sql_2, 'Delete event occurrences from calendar');
						if($this->arrParticipantsOperations['delete']){
							$sql = 'DELETE FROM '.EVENTS_PARTICIPANTS_TABLE.' WHERE event_id = '.(int)$event_id;
							$this->DatabaseVoidQuery($sql, 'Delete assignments of participants for this events');
						}
						$this->arrMessages[] = $this->Message('success', str_ireplace('_EVENT_NAME_', $event_name['name'], $this->Lang('success_event_was_deleted')));
						$this->DeleteCache();									
					}
				}
			}else{
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_deleting_event'));
			}
        }else if($event_action == 'events_participants_assign'){
			if(!$this->isParticipantsAllowed) { $this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked')); return false; }
            $assigned_participant_id 	= $this->GetParameter('assigned_participant_id');
    
            $sql = 'SELECT id FROM '.EVENTS_PARTICIPANTS_TABLE.' WHERE event_id = '.(int)$event_id.' AND participant_id = '.(int)$assigned_participant_id;
            $result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Check if participant already assigned to specific event');
			if($result[1] > 0){
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_participant_already_assigned'));
			}else{
                $sql = 'INSERT INTO '.EVENTS_PARTICIPANTS_TABLE.' (id, event_id, participant_id) VALUES (NULL, '.(int)$event_id.', '.(int)$assigned_participant_id.') ';
                if($this->DatabaseVoidQuery($sql, 'Assign participant to specific event')){
                    $this->arrMessages[] = $this->Message('success', $this->Lang('success_participant_assigned'));
                }else{
                    $this->arrMessages[] = $this->Message('error', $this->Lang('error_assigning_participant'));
                }
            }            
        }else if($event_action == 'events_participants_delete'){
			if(!$this->isParticipantsAllowed) { $this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked')); return false; }

			$event_participant_id = $this->GetParameter('hid_event_participant_id');
			$sql = 'DELETE FROM '.EVENTS_PARTICIPANTS_TABLE.' WHERE id = '.(int)$event_participant_id;
			if($this->DatabaseVoidQuery($sql, 'Unassign participant from specific event')){
				$this->arrMessages[] = $this->Message('success', $this->Lang('success_participant_unassigned'));
			}else{
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_unassigning_participant'));
			}
        }else if($event_action == 'categories_insert'){
			if(!$this->isCategoriesAllowed) { $this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked')); return false; }

			// insert new category
			$sql = 'SELECT COUNT(*) as cnt FROM '.EVENTS_CATEGORIES_TABLE.' WHERE name = \''.$this->PrepareText($category_name).'\'';
			$category_exists = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Check if event category already exists');
			if($category_exists['cnt'] > 0){
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_category_exists'));
			}else{
				$sql = 'INSERT INTO '.EVENTS_CATEGORIES_TABLE.' (id, name, description, color, duration, show_in_filter) VALUES (NULL, \''.$this->PrepareText($category_name).'\', \''.$this->PrepareText($category_description).'\', \''.$category_color.'\', \''.$category_duration.'\', '.(int)$show_in_filter.')';
				$insert_id = $this->DatabaseVoidQuery($sql, 'Insert new category for events');	
				if($insert_id != false){
					$this->arrMessages[] = $this->Message('success', $this->Lang('success_new_category_added'));
				}else{
					$this->arrMessages[] = $this->Message('error', $this->Lang('error_inserting_new_category'));
				}				
			}			
		}else if($event_action == 'categories_update'){
			if(!$this->isCategoriesAllowed) { $this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked')); return false; }

			// update category
			$sql = 'UPDATE '.EVENTS_CATEGORIES_TABLE.' SET name = \''.$this->PrepareText($category_name).'\', description = \''.$this->PrepareText($category_description).'\', color = \''.$category_color.'\', duration = \''.$category_duration.'\', show_in_filter = '.(int)$show_in_filter.' WHERE id = '.(int)$category_id;
			if($this->DatabaseVoidQuery($sql, 'Update existing event category')){
				$this->arrMessages[] = $this->Message('success', $this->Lang('success_category_was_updated'));
			}else{
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_updating_category'));
			}			
		}else if($event_action == 'categories_delete'){
			if(!$this->isCategoriesAllowed) { $this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked')); return false; }

			// delete category
			$sql = 'DELETE FROM '.EVENTS_CATEGORIES_TABLE.' WHERE id = '.(int)$category_id;
			if(!$this->DatabaseVoidQuery($sql, 'Delete category')){                
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_deleting_category'));
			}else{								
                $this->arrMessages[] = $this->Message('success', $this->Lang('success_category_was_deleted'));
                if($this->removeCategoryEvents){
                    $sql = 'DELETE FROM '.CALENDAR_TABLE.' WHERE event_id IN (SELECT evnt.id FROM '.EVENTS_TABLE.' evnt WHERE evnt.id = '.CALENDAR_TABLE.'.event_id AND evnt.category_id = '.(int)$category_id.')';
                    $this->DatabaseVoidQuery($sql, 'Delete category events occurrences');
                    ///$this->arrMessages[] = $this->Message('success', $this->Lang('success_category_events_were_deleted'));
                    $sql = 'DELETE FROM '.EVENTS_TABLE.' WHERE category_id = '.(int)$category_id;			
                    $this->DatabaseVoidQuery($sql, 'Delete category events');
                }                
				$this->DeleteCache();									
			}
        }else if($event_action == 'locations_insert'){
			if(!$this->isLocationsAllowed) { $this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked')); return false; }

			// insert new location
			$sql = 'SELECT COUNT(*) as cnt FROM '.EVENTS_LOCATIONS_TABLE.' WHERE name = \''.$this->PrepareText($location_name).'\'';
			$location_exists = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Check if event location already exists');
			if($location_exists['cnt'] > 0){
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_location_exists'));
			}else{
				$sql = 'INSERT INTO '.EVENTS_LOCATIONS_TABLE.' (id, name, description, show_in_filter) VALUES (NULL, \''.$this->PrepareText($location_name).'\', \''.$this->PrepareText($location_description).'\', '.(int)$show_in_filter.')';
				$insert_id = $this->DatabaseVoidQuery($sql, 'Insert new location for events');	
				if($insert_id != false){
					$this->arrMessages[] = $this->Message('success', $this->Lang('success_new_location_added'));
				}else{
					$this->arrMessages[] = $this->Message('error', $this->Lang('error_inserting_new_location'));
				}				
			}			
		}else if($event_action == 'locations_update'){
			if(!$this->isLocationsAllowed) { $this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked')); return false; }

			// update location
			$sql = 'UPDATE '.EVENTS_LOCATIONS_TABLE.' SET name = \''.$this->PrepareText($location_name).'\', description = \''.$this->PrepareText($location_description).'\', show_in_filter = '.(int)$show_in_filter.' WHERE id = '.(int)$location_id;
			if($this->DatabaseVoidQuery($sql, 'Update existing event location')){
				$this->arrMessages[] = $this->Message('success', $this->Lang('success_location_was_updated'));
			}else{
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_updating_location'));
			}			
		}else if($event_action == 'locations_delete'){
			if(!$this->isLocationsAllowed) { $this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked')); return false; }

			// delete category
			$sql = 'DELETE FROM '.EVENTS_LOCATIONS_TABLE.' WHERE id = '.(int)$location_id;
			if(!$this->DatabaseVoidQuery($sql, 'Delete location')){                
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_deleting_location'));
			}else{								
                $this->arrMessages[] = $this->Message('success', $this->Lang('success_location_was_deleted'));
				$this->DeleteCache();									
			}
		}else if($event_action == 'participants_insert'){
			if(!$this->isParticipantsAllowed) { $this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked')); return false; }

            $sql = 'SELECT COUNT(*) as cnt FROM '.PARTICIPANTS_TABLE.' WHERE email != \'\' AND email = \''.$this->PrepareText($email).'\'';
            $participant_exists = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Check if participant exists');                
            if($participant_exists['cnt'] > 0){
                $this->arrMessages[] = $this->Message('error', $this->Lang('error_participant_exists'));
            }else{
                // add participants
                $sql = 'INSERT INTO '.PARTICIPANTS_TABLE.'(first_name, last_name, email) VALUES (\''.$this->PrepareText($first_name).'\', \''.$this->PrepareText($last_name).'\', \''.$this->PrepareText($email).'\')';
                if($this->DatabaseVoidQuery($sql, 'Insert new participant')){
                    $this->arrMessages[] = $this->Message('success', $this->Lang('success_participant_was_added'));
                }else{
                    $this->arrMessages[] = $this->Message('error', $this->Lang('error_inserting_participant'));
                }                
            }            
		}else if($event_action == 'participants_update'){
			if(!$this->isParticipantsAllowed) { $this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked')); return false; }

            $sql = 'SELECT COUNT(*) as cnt FROM '.PARTICIPANTS_TABLE.' WHERE email != \'\' AND email = \''.$this->PrepareText($email).'\' AND id != '.(int)$participant_id;
            $participant_exists = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Check if participant exists');                
            if($participant_exists['cnt'] > 0){
                $this->arrMessages[] = $this->Message('error', $this->Lang('error_participant_exists'));
            }else{
                // update participants
                $sql = 'UPDATE '.PARTICIPANTS_TABLE.' SET first_name = \''.$this->PrepareText($first_name).'\', last_name = \''.$this->PrepareText($last_name).'\', email = \''.$this->PrepareText($email).'\' WHERE id = '.(int)$participant_id;
                if($this->DatabaseVoidQuery($sql, 'Update existing participant')){
                    $this->arrMessages[] = $this->Message('success', $this->Lang('success_participant_was_updated'));
                }else{
                    $this->arrMessages[] = $this->Message('error', $this->Lang('error_updating_participant'));
                }
            }
		}else if($event_action == 'participants_delete'){
			if(!$this->isParticipantsAllowed) { $this->arrMessages[] = $this->Message('error', $this->Lang('msg_this_operation_blocked')); return false; }

			// delete participant
			$sql = 'DELETE FROM '.PARTICIPANTS_TABLE.' WHERE id = '.(int)$participant_id;
			if($this->arrParticipantsOperations['delete'] && $this->DatabaseVoidQuery($sql, 'Delete participant')){
				$sql = 'DELETE FROM '.EVENTS_PARTICIPANTS_TABLE.' WHERE participant_id = '.(int)$participant_id;
				$this->DatabaseVoidQuery($sql, 'Delete participant assignments to events');
				$this->arrMessages[] = $this->Message('success', $this->Lang('success_participant_was_deleted'));
				$this->DeleteCache();									
			}else{				
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_deleting_participant'));
			}
		}else if($event_action == 'events_exporting_execute'){
            
            $category_name_change = ($this->GetParameter('sel_category_name_change') != '') ? $this->GetParameter('sel_category_name_change') : '';
            $location_name_change = ($this->GetParameter('sel_location_name_change') != '') ? $this->GetParameter('sel_location_name_change') : '';

			$sql = 'SELECT
					'.EVENTS_TABLE.'.id,
					'.EVENTS_TABLE.'.category_id,
                    '.EVENTS_TABLE.'.location_id,
					'.EVENTS_TABLE.'.name,
                    '.EVENTS_TABLE.'.url,
					'.EVENTS_TABLE.'.description,
                    '.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.name' : '""').' as category_name,
                    '.(($this->isLocationsAllowed) ? EVENTS_LOCATIONS_TABLE.'.name' : '""').' as location_name,
                    '.CALENDAR_TABLE.'.event_date,
                    '.CALENDAR_TABLE.'.event_time,
                    MIN('.CALENDAR_TABLE.'.event_time) as min_event_time,
                    COUNT('.CALENDAR_TABLE.'.unique_key) as slots_count
				FROM '.CALENDAR_TABLE.'
					LEFT OUTER JOIN '.EVENTS_TABLE.' ON '.CALENDAR_TABLE.'.event_id = '.EVENTS_TABLE.'.id
                    '.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
                    '.(($this->isLocationsAllowed) ? 'LEFT OUTER JOIN '.EVENTS_LOCATIONS_TABLE.' ON '.EVENTS_TABLE.'.location_id = '.EVENTS_LOCATIONS_TABLE.'.id ' : '').'
				WHERE 1=1 AND                    
					('.CALENDAR_TABLE.'.event_date >= \''.$event_from_year.'-'.$event_from_month.'-'.$event_from_day.'\') AND
					('.CALENDAR_TABLE.'.event_date <= \''.$event_to_year.'-'.$event_to_month.'-'.$event_to_day.'\')
					'.$this->PrepareWhereClauseParticipant().'
                    '.$this->PrepareWhereClauseCategory($category_name_change).'
                    '.$this->PrepareWhereClauseLocation($location_name_change).'
                GROUP BY '.CALENDAR_TABLE.'.unique_key
				ORDER BY '.CALENDAR_TABLE.'.event_date ASC
                LIMIT 0, 1000';

            $result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve all events for exporting');
			if($result[1] > 0){
                $fe = fopen($this->exportDir.'export.'.$sel_export_format, 'w+');
                if($fe){  
                    if($sel_export_format == 'csv' && $this->exportTypes['csv']['enabled']){
                        // write BOM to the opened file
                        if(fwrite($fe, "\xEF\xBB\xBF") == FALSE) $this->arrMessages[] = $this->Lang('file_writing_error').' (export.csv)';
                        $fputcsv_row = array();
                        $fputcsv_row[] = $this->Lang('event_name');
                        $fputcsv_row[] = $this->Lang('event_url');
                        $fputcsv_row[] = $this->Lang('event_description');
                        if($this->isCategoriesAllowed) $fputcsv_row[] = $this->Lang('category');
                        if($this->isLocationsAllowed) $fputcsv_row[] = $this->Lang('location');
                        $fputcsv_row[] = $this->Lang('start_time');
                        $fputcsv_row[] = $this->Lang('finish_time');
                        fputcsv($fe, $fputcsv_row);
                        foreach($result[0] as $key => $val){
                            $start_datetime = date('Y-m-d H:i:s', strtotime($val['event_date'].' '.$val['min_event_time']));
                            $finish_datetime = date('Y-m-d H:i:s', strtotime($val['event_date'].' '.$val['min_event_time']) + ($this->timeSlot*$val['slots_count']*60));
                            $fputcsv_row = array();
                            $fputcsv_row[] = strip_tags($val['name']);
                            $fputcsv_row[] = strip_tags($val['url']);
                            $fputcsv_row[] = strip_tags($val['description']);
                            if($this->isCategoriesAllowed) $fputcsv_row[] = $val['category_name'];
                            if($this->isLocationsAllowed) $fputcsv_row[] = $val['location_name'];
                            $fputcsv_row[] = $start_datetime;
                            $fputcsv_row[] = $finish_datetime;
                            fputcsv($fe, $fputcsv_row);
                        }
                    }else if($sel_export_format == 'xml' && $this->exportTypes['xml']['enabled']){
                        $content  = '<?xml version="1.0" encoding="UTF-8" ?>';
                        $content .= '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:openSearch="http://a9.com/-/spec/opensearchrss/1.0/">';
                        foreach($result[0] as $key => $val){
                            $start_datetime = date('Ymd\THis\Z', strtotime($val['event_date'].' '.$val['min_event_time']));
                            $finish_datetime = date('Ymd\THis\Z', strtotime($val['event_date'].' '.$val['min_event_time']) + ($this->timeSlot*$val['slots_count']*60));
                            $content .= '<entry>';
                            //$content .= '<id></id>';
                            //$content .= '<published>'.$val['event_date'].'T'.$val['min_event_time'].'.000Z</published>';
                            //$content .= '<updated>'.$val['event_date'].'T'.$val['min_event_time'].'.000Z</updated>';
                            $content .= '<title>'.strip_tags($val['name']).'</title>';
                            $content .= '<url>'.strip_tags($val['url']).'</url>';
                            $content .= '<content>'.strip_tags($val['description']).'</content>';
                            if($this->isCategoriesAllowed) $content .= '<category>'.$val['category_name'].'</category>';
                            if($this->isLocationsAllowed) $content .= '<location>'.$val['location_name'].'</location>';
                            $content .= '<startTime>'.$start_datetime.'</startTime>';
                            $content .= '<finishTime>'.$finish_datetime.'</finishTime>';
                            //$content .= '<link rel="alternate" type="text/html" href="#" title="alternate"/>';
                            //$content .= '<link rel="self" type="application/atom+xml" href="#"/>';
                            //$content .= '<author><name></name><email></email></author>';
                            $content .= '</entry>';
                        }
                        $content .= '</feed>';
                        // write content to the opened file
                        if(fwrite($fe, $content) == FALSE) $this->arrMessages[] = $this->Lang('file_writing_error').' (export.xml)';
                    }else if($sel_export_format == 'ics' && $this->exportTypes['ics']['enabled']){
                        $content  = 'BEGIN:VCALENDAR'.$this->crLt;
                        $content .= 'PRODID:-//ApPHP Calendar '.self::$version.'//EN'.$this->crLt;
                        $content .= 'VERSION:2.0'.$this->crLt;
                        $content .= 'CALSCALE:GREGORIAN'.$this->crLt;
                        $content .= 'METHOD:PUBLISH'.$this->crLt;
                        $content .= 'X-WR-CALNAME:'.$this->crLt;
                        $content .= 'X-WR-TIMEZONE:'.$this->timezone.$this->crLt;
                        foreach($result[0] as $key => $val){
                            $start_datetime = date('Ymd\THis\Z', strtotime($val['event_date'].' '.$val['min_event_time']));
                            $finish_datetime = date('Ymd\THis\Z', strtotime($val['event_date'].' '.$val['min_event_time']) + ($this->timeSlot*$val['slots_count']*60));
                            $content .= 'BEGIN:VEVENT'.$this->crLt;
                            $content .= 'DTSTART:'.$start_datetime.''.$this->crLt;
                            $content .= 'DTEND:'.$finish_datetime.''.$this->crLt;
                            $content .= 'DTSTAMP:'.$this->crLt;
                            $content .= 'UID:'.$this->crLt;
                            $content .= 'CREATED:'.$this->crLt;
                            $content .= 'DESCRIPTION:'.strip_tags($val['description']).''.$this->crLt;
                            $content .= 'LAST-MODIFIED:'.$this->crLt;
                            $content .= 'LOCATION:'.(($this->isLocationsAllowed) ? $val['location_name'] : '').$this->crLt;
                            $content .= 'SEQUENCE:0'.$this->crLt;
                            $content .= 'STATUS:CONFIRMED'.$this->crLt;
                            $content .= 'SUMMARY:'.strip_tags($val['name']).''.$this->crLt;
                            $content .= 'URL:'.strip_tags($val['url']).''.$this->crLt;
                            $content .= 'TRANSP:OPAQUE'.$this->crLt;
                            $content .= 'END:VEVENT'.$this->crLt;
                        }
                        $content .= 'END:VCALENDAR';                        
                        // write content to the opened file
                        if(fwrite($fe, $content) == FALSE) $this->arrMessages[] = $this->Lang('file_writing_error').' (export.ics)';
                    }
                    $this->arrMessages[] = $this->Message('success', str_ireplace('_HREF_', $this->calDir.'download/export.php?cal_token='.$this->token.'&cal_format='.$sel_export_format, $this->Lang('success_export_events')));
                }else{
                    $this->arrMessages[] = $this->Message('error', $this->Lang('file_opening_error').(($this->isDebug) ? ' ['.$this->exportDir.'export.'.$sel_export_format.']' : ''));
                }
                fclose($fe);                    
			}else{
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_no_event_found'));
			}
		}
	}	

	/**
	 * Draw CSS style
	*/	
	private function DrawCssStyle()
	{
		echo '<link href="'.$this->calDir.'style/'.$this->cssStyle.'/style.css" rel="stylesheet" type="text/css" />'.$this->crLt;
        if($this->direction == 'rtl') echo '<link href="'.$this->calDir.'style/'.$this->cssStyle.'/style-rtl.css" rel="stylesheet" type="text/css" />'.$this->crLt;
		echo '<!--[if IE]><link href="'.$this->calDir.'style/'.$this->cssStyle.'/style-ie.css" rel="stylesheet" type="text/css" /><![endif]-->'.$this->crLt;
	}

	/**
	 * Draw javascript functions
	*/	
	private function DrawJsFunctions()
	{
		$event_action = $this->GetParameter('hid_event_action');

		echo '<script type="text/javascript">'.$this->crLt;
		if(isset($this->arrToday['mday']) && isset($this->arrToday['mon']) && isset($this->arrToday['year'])){
			echo 'GL_jump_day   = "'.$this->arrToday['mday'].'";'.$this->crLt;
			echo 'GL_jump_month = "'.$this->ConvertToDecimal($this->arrToday['mon']).'";'.$this->crLt;
			echo 'GL_jump_year  = "'.$this->arrToday['year'].'";'.$this->crLt;
			echo 'GL_today_year = "'.$this->arrToday['year'].'";'.$this->crLt;
			echo 'GL_today_mon  = "'.$this->ConvertToDecimal($this->arrToday['mon']).'";'.$this->crLt;
			echo 'GL_today_mday = "'.$this->arrToday['mday'].'";'.$this->crLt;
		}
		echo 'GL_view_type = "'.$this->defaultView.'";'.$this->crLt;
		echo 'GL_installation_key = "'.INSTALLATION_KEY.'";'.$this->crLt;
		echo 'GL_cal_dir = "'.$this->calDir.'";'.$this->crLt;
        echo 'GL_event_tooltips = new Array();'.$this->crLt;		
        if($this->direction == 'rtl' && $this->arrParameters['view_type'] != 'monthly_double') $this->arrInitJsFunction[] = 'var ol_hpos=LEFT;';
        $this->arrInitJsFunction[] = 'phpCalendar = new CalendarClass();';
        $this->arrInitJsFunction[] = 'phpCalendar.setDirection("'.$this->direction.'");';
		
		if($this->cssStyle == 'blue') echo 'ol_bgcolor = "#336699";'.$this->crLt;
		else if($this->cssStyle == 'brown') echo 'ol_bgcolor = "#863030";'.$this->crLt;
		else if($this->cssStyle == 'green') echo 'ol_bgcolor = "#008282";'.$this->crLt;
		else 'ol_bgcolor = "#82806b";'.$this->crLt;
        	
		echo '</script>'.$this->crLt;
        $lang_name = (@file_exists($this->calDir.'langs/js/'.$this->langName.'.js')) ? $this->langName : 'en';
        echo '<script type="text/javascript" src="'.$this->calDir.'langs/js/'.$lang_name.'.js"></script>'.$this->crLt;    
        echo '<script type="text/javascript" src="'.$this->calDir.'js/calendar.class.js"></script>'.$this->crLt;
		///echo '<script type="text/javascript" src="'.$this->calDir.'js/jquery-1.11.3.min.js"></script>'.$this->crLt;
		echo '<script>window.jQuery || document.write(\'<script type="text/javascript" src="'.$this->calDir.'js/jquery-1.11.3.min.js">\x3C/script>\')</script>'.$this->crLt;
		echo '<script type="text/javascript" src="'.$this->calDir.'js/jquery-ui-1.9.2.custom.min.js"></script>'.$this->crLt;
		if($event_action != 'events_edit'){
			$this->arrInitJsFunction[] = 'jQuery(function(){jQuery("#divAddEvent").draggable();jQuery("#divEditEvent").draggable();});';
			$this->arrInitJsFunction[] = 'jQuery(document).click(function(e){ click_x = e.pageX; click_y = e.pageY; });';
		}

		if($this->arrCalendarOperations['statistics'] && $this->arrParameters['event_action'] == 'events_statistics'){
            echo '<script type="text/javascript" src="//www.google.com/jsapi"></script>'.$this->crLt;
			echo '<script type="text/javascript">function drawVisualization(){
					// Create and populate the data table.
					var data = new google.visualization.DataTable();
					data.addColumn("string", "'.$this->Lang('event_name').'");
					data.addColumn("number", "'.$this->Lang('occurrences').'");';
			
					$category_id = $this->GetParameter('sel_category_name_change');
					$sql = 'SELECT
								'.EVENTS_TABLE.'.id,
								'.EVENTS_TABLE.'.category_id,
								'.EVENTS_TABLE.'.name,
								'.EVENTS_TABLE.'.description,
								COUNT('.CALENDAR_TABLE.'.id) as cnt
							FROM '.EVENTS_TABLE.'
								LEFT OUTER JOIN '.CALENDAR_TABLE.' ON '.CALENDAR_TABLE.'.event_id = '.EVENTS_TABLE.'.id
							WHERE 1=1
								'.$this->PrepareWhereClauseCategory().'
                                '.$this->PrepareWhereClauseLocation().'
								'.$this->PrepareWhereClauseParticipant().'
							GROUP BY '.EVENTS_TABLE.'.id 							
							ORDER BY cnt DESC
							LIMIT 0, 10';
							
					$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve events occurrences for statistics');
					$content = $this->FileGetContents('templates/events_management_row.tpl');
			
					echo 'data.addRows('.$result[1].');'.$this->crLt;
					$events_count = '0';
					foreach($result[0] as $key => $val){
						echo 'data.setCell('.$events_count.', 0, "'.addslashes($val['name']).' ('.$val['cnt'].')");'.$this->crLt;
						echo 'data.setCell('.$events_count.', 1, '.$val['cnt'].');'.$this->crLt;
						$events_count++;
					}
				 
					// Create and draw the visualization
					if($this->arrParameters['chart_type'] == 'barchart'){
						echo 'new google.visualization.BarChart(document.getElementById("div_visualization")).draw(data, {is3D: true, min:0, title:"'.$this->Lang('top_10_events').'"});'.$this->crLt; 
					}else if($this->arrParameters['chart_type'] == "piechart"){
						echo 'new google.visualization.PieChart(document.getElementById("div_visualization")).draw(data, {is3D: true, min:0, title:"'.$this->Lang('top_10_events').'"});'.$this->crLt;
					}else if($this->arrParameters['chart_type'] == "areachart"){
						echo 'new google.visualization.AreaChart(document.getElementById("div_visualization")).draw(data, {is3D: true, min:0, title:"'.$this->Lang('top_10_events').'"});'.$this->crLt;
					}else{ // columnchart
						echo 'new google.visualization.ColumnChart(document.getElementById("div_visualization")).draw(data, {is3D: true, min:0, title:"'.$this->Lang('top_10_events').'"});'.$this->crLt;
					}			   
				   
				echo ' }	   
			  </script>'.$this->crLt;

			$this->arrInitJsFunction[] = 'google.load("visualization", "1", {packages: ["'.$this->arrParameters['chart_type'].'"]});';
			$this->arrInitJsFunction[] = 'google.setOnLoadCallback(drawVisualization);';
		}
	}
	
	/**
	 * Include Modules
	*/	
	private function IncludeModules()
	{
		echo '<script type="text/javascript" src="'.$this->calDir.'modules/overlib/overlib.js"></script>'.$this->crLt;					
	}

	/**
	 * Initialize javascript functions
	*/	
	private function InitializeJsFunctions()
	{
		if(count($this->arrInitJsFunction) > 0 || $this->isAnchorAllowed){
			echo '<script type="text/javascript">'.$this->crLt;
			foreach($this->arrInitJsFunction as $key => $val){
				echo $val.$this->crLt;
            }
            if($this->isAnchorAllowed) echo 'phpCalendar.calSkipToAnchor(\''.$this->uPrefix.'\');'.$this->crLt;
			echo '</script>'.$this->crLt;			
		}
	}	
	
	/**
	 * Draw system messages
	 * @param $draw
	*/	
	private function DrawMessages($draw = true)
	{		
		$output = '';
		if(count($this->arrMessages) > 0){
			echo '<tr>';
			foreach($this->arrMessages as $key){
				$output .= '<th colspan="3"><center>'.$key.'</center></th>'.$this->crLt;
			}
			echo '</tr>';
		}
		if($draw) echo $output;
		else return $output;		
	}
	
	/**
	 * Draw system errors
	 * @param $draw
	*/	
	private function DrawErrors($draw = true)
	{
		$output = '';
		if(count($this->arrErrors) > 0){
			foreach($this->arrErrors as $key){
				$output .= '<span>- '.$key.'</span><br />'.$this->crLt;
			}
		}
		if($draw) echo $output;
		else return $output;		
	}

	/**
	 * Draw system warnings
	 * @param $draw
	*/	
	private function DrawWarnings($draw = true)
	{
		$output = '';
		if(count($this->arrWarnings) > 0){
			foreach($this->arrWarnings as $key){
				$output .= '<span>- '.$key.'</span><br />'.$this->crLt;
			}
		}
		if($draw) echo $output;
		else return $output;		
	}
    
	/**
	 * Draw replace holders
	 * @param &$content 
	 * @param $holder_key
	 * @param $holder_val
	 * @param $allow
	 * @param $empty_val
	*/	
	private function ReplaceHolders(&$content, $holder_key = '', $holder_val = '', $allow = true, $empty_val = '')
	{        
        if($allow){
            if(empty($holder_val) && !empty($empty_val)) $holder_val = '<span class="cal_gray">- '.$empty_val.' -</span>';
            $content = str_ireplace($holder_key, $holder_val, $content);
        }else{
            $content = str_ireplace($holder_key, '', $content);
        }				
	}
    
	/**
	 * Draw system errors
	 * @param $draw
	*/	
	private function DrawSQLs($draw = true)
	{		
		$output = '';
		if(count($this->arrSQLs) > 0){
			foreach($this->arrSQLs as $key){
				$output .= '<span>'.$key.'</span><br />'.$this->crLt;
			}
		}
		if($draw) echo $output;
		else return $output;		
	}

	/**
	 * Draw yearly calendar
	*/	
	private function DrawYear()
	{
        $output = '';
        
		// start caching
		$cachefile = $this->cacheDir.md5('yearly-'.$this->arrParameters['selected_category'].'-'.$this->arrParameters['selected_location'].'-'.$this->arrParameters['year']).'.cch';		
		if($this->StartCaching($cachefile)) return true;

		$this->celHeight = '20px';
		$output .= '<table class="year_container">'.$this->crLt;
		$output .= '<tr>'.$this->crLt;
			$output .= '<th class="th_navbar"  colspan="3">';
				$output .= '<table class="table_navbar">'.$this->crLt;
				$output .= '<tr>';
				$output .= '<th class="tr_navbar_left" valign="middle"><span>'.$this->DrawDateJumper(false, false, false).'</span></th>'.$this->crLt;
				$output .= '<th class="tr_navbar">'.$this->arrParameters['year'].'</th>'.$this->crLt;
				$output .= '<th class="tr_navbar_right"><span>				
					  <a href="javascript:phpCalendar.doPostBack(\'view\',\'yearly\',\''.$this->prevYear['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\')">'.$this->prevYear['year'].'</a> |
					  <a href="javascript:phpCalendar.doPostBack(\'view\',\'yearly\',\''.$this->nextYear['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\')">'.$this->nextYear['year'].'</a>
					  </span></th>'.$this->crLt;
				$output .= '</tr>'.$this->crLt;
				$output .= '</table>'.$this->crLt;
			$output .= '</th>'.$this->crLt;
		$output .= '</tr>'.$this->crLt;

		$output .= '<tr>';
		for($i = 1; $i <= 12; $i++){
			$output .= '<td align="center" valign="top">';
			if($this->arrViewTypes['monthly']['enabled']){
				$output .= '<a href="javascript:phpCalendar.doPostBack(\'view\',\'monthly\',\''.$this->arrParameters['year'].'\',\''.$this->ConvertToDecimal($i).'\',\''.$this->arrParameters['day'].'\')">'.$this->arrMonths[$i].'</a>';				
			}else{
				$output .= '<b>'.$this->arrMonths[$i].'</b>';
			}			
			$output .= $this->DrawMonthSmall($this->arrParameters['year'], $this->ConvertToDecimal($i), false);
			$output .= '</td>';
			if(($i != 1) && ($i % 3 == 0)) $output .= '</tr><tr>';
		}
		$output .= '</tr>';
		$output .= '<tr><td nowrap="nowrap" colspan="3" height="10px"></td></tr>';
		$output .= '<tr>
				<td nowrap="nowrap" colspan="3">
					<div class="legend_text"> '.$this->Lang('events').': </div>
					<div class="legend_f_block">1</div>
					<div class="legend_block e0"></div>
					<div class="legend_block e1"></div>
					<div class="legend_block e2"></div>
					<div class="legend_block e3"></div>
					<div class="legend_block e4"></div>
					<div class="legend_block e5"></div>
					<div class="legend_block e6"></div>
					<div class="legend_block e7"></div>
					<div class="legend_block e8"></div>
					<div class="legend_block e9"></div>
					<div class="legend_block e10"></div>
					<div class="legend_l_block">10+</div>
				</td>
			  </tr>';
		$output .= '<tr><td nowrap="nowrap" colspan="3" height="5px"></td></tr>';	  
		$output .= '</table>';
        
        echo $output;
		
		// finish caching
		$this->FinishCaching($cachefile);			
	}

	/**
	 * Draw list view calendar
	*/	
	private function DrawListView()
	{
        $output = '';
        
		// start caching
		$cachefile = $this->cacheDir.md5('listview-'.$this->arrParameters['selected_category'].'-'.$this->arrParameters['selected_location'].'-'.$this->arrParameters['year'].'-'.$this->arrParameters['month']).'.cch';
		if($this->StartCaching($cachefile)) return true;
	
		// today, first day and last day in month
		$firstDay = getdate(mktime(0,0,0,$this->arrParameters['month'],1,$this->arrParameters['year']));
		$lastDay  = getdate(mktime(0,0,0,$this->arrParameters['month']+1,0,$this->arrParameters['year']));
		$actday   = 0;
		
		$this->arrTemp = $this->GetEventsListForMonth($this->arrParameters['year'], $this->arrParameters['month'], true);
		//$output .= '<pre>';
		//print_r($this->arrTemp);
		//$output .= '</pre>';
		
		// Create a table with the necessary header informations
		$output .= '<table class="month">'.$this->crLt;
		$output .= '<tr>'.$this->crLt;
            $output .= '<th class="th_navbar" colspan="2">'.$this->crLt;
				$output .= '<table class="table_navbar">'.$this->crLt;
				$output .= '<tr>';
				$output .= '<th class="tr_navbar_left">'.$this->DrawDateJumper(false).'</th>'.$this->crLt;
				$output .= '<th class="tr_navbar">';
				$output .= ' <a class="a_prev" href="javascript:phpCalendar.doPostBack(\'view\',\'list_view\',\''.$this->prevMonth['year'].'\',\''.$this->ConvertToDecimal($this->prevMonth['mon']).'\',\''.$this->ConvertToDecimal($this->prevMonth['mday']).'\')" title="'.$this->Lang('previous').'">&laquo;&laquo;</a> ';
				$output .= $this->arrParameters['month_full_name'].' - '.$this->arrParameters['year'];
				$output .= ' <a class="a_next" href="javascript:phpCalendar.doPostBack(\'view\',\'list_view\',\''.$this->nextMonth['year'].'\',\''.$this->ConvertToDecimal($this->nextMonth['mon']).'\',\''.$this->ConvertToDecimal($this->nextMonth['mday']).'\')" title="'.$this->Lang('next').'">&raquo;&raquo;</a> ';
				$output .= '</th>'.$this->crLt;
				$output .= '<th class="tr_navbar_right">
					  <a href="javascript:phpCalendar.doPostBack(\'view\',\'list_view\',\''.$this->prevYear['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\')">'.$this->prevYear['year'].'</a> |
					  <a href="javascript:phpCalendar.doPostBack(\'view\',\'list_view\',\''.$this->nextYear['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\')">'.$this->nextYear['year'].'</a>
					  </th>'.$this->crLt;
				$output .= '</tr>'.$this->crLt;
				$output .= '</table>'.$this->crLt;
			$output .= '</th>'.$this->crLt;
		$output .= '</tr>'.$this->crLt;
		
		$displayed_count = 0;
		for($i = 0; $i <= 31; $i++){
			$monthly_day_events = $this->GetMonthlyDayEvents($i);
			if($monthly_day_events != ''){                
                $wday = date('w', mktime(0, 0, 0, $this->arrParameters['month'], $this->ConvertToDecimal($i), $this->arrParameters['year']));                
				if($displayed_count > 0) $output .= '<tr><td></td><td><div class="lv_separator"></div></td></tr>'.$this->crLt;
				$act_day_css = (($this->arrToday['mday'] == $this->ConvertToDecimal($i)) && ($this->arrToday['mon'] == $this->arrParameters['month'])) ? ' class="td_actday"' : '';
				$output .= '<tr'.$act_day_css.'>'.$this->crLt;
				$output .= '<td '.(!$displayed_count ? 'width="15%" ' : '').'class="lv_lcolumn">';
				if(($this->arrViewTypes['daily']['enabled'])) $output .= '<a href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->ConvertToDecimal($i).'\')">';
                if($this->dateFormat == 'mm/dd/yyyy'){                    
                    $output .= $this->arrWeekDays[$wday]['short'].', '.$this->arrParameters['month_full_name'].' '.$this->ConvertToDecimal($i);
                }else if($this->dateFormat == 'yyyy/mm/dd'){
                    $output .= $this->arrWeekDays[$wday]['short'].', '.$this->arrParameters['month_full_name'].' '.$this->ConvertToDecimal($i);
                }else{
                    $output .= $this->arrWeekDays[$wday]['short'].', '.$this->ConvertToDecimal($i).' '.$this->arrParameters['month_full_name'];
                }
				if(($this->arrViewTypes['daily']['enabled'])) $output .= '</a>';
				$output .= '</td>'.$this->crLt;
				$output .= '<td '.(!$displayed_count ? 'width="85%" ' : '').'class="lv_rcolumn">';
				$output .= $monthly_day_events;
				$output .= '</td>'.$this->crLt;
				$output .= '</tr>'.$this->crLt;
				$displayed_count++;
			}
		}
        if(!$displayed_count){
            $output .= '<tr><td colspan="2" height="20px" align="center"><span class="cal_msg_error">'.$this->Lang('error_no_event_found').'</span></td></tr>';		
        }
		$output .= '<tr><td colspan="2" height="10px" nowrap="nowrap"></td></tr>';		
		$output .= '</table>'.$this->crLt;
        
        $output .= $this->DrawEventTooltips(false);
        
        echo $output;

		// finish caching
		$this->FinishCaching($cachefile);			
	}

	/**
	 * Draw monthly calendar
	 * @param $year
	 * @param $month
	 * @param $view_type
	 * @param $navigation_bar
	 * @param $draw
	*/	
	private function DrawMonth($year = '', $month = '', $view_type = 'monthly', $navigation_bar = true, $draw = true)
	{
        $output = '';
        
		$month = ($month == '') ? $this->arrParameters['month'] : $this->ConvertToDecimal($month);
		$year = ($year == '') ? $this->arrParameters['year'] : $year;
		
		// start caching
		$cachefile = $this->cacheDir.md5($view_type.'-'.$this->arrParameters['selected_category'].'-'.$this->arrParameters['selected_location'].'-'.$year.'-'.$month.'-'.$this->arrParameters['day']).'.cch';
		if($this->StartCaching($cachefile)) return true;

		// today, first day and last day in month
		$firstDay = getdate(mktime(0,0,0,$month,1,$year));
		$lastDay  = getdate(mktime(0,0,0,$month+1,0,$year));
		$actday   = 0;
		$this->arrTemp = $this->GetEventsListForMonth($year, $month);

		#echo '<pre>';
		#print_r($this->arrTemp);
		#echo '</pre>';
	
		// Create a table with the necessary header informations
		$output .= '<table class="month">'.$this->crLt;
		if($navigation_bar){
			$output .= '<tr>';
                $output .= '<th class="th_navbar" colspan="'.(($this->isWeekNumberOfYear) ? '8' : '7').'">';
					$output .= '<table class="table_navbar">'.$this->crLt;
					$output .= '<tr>';
					$output .= '<th class="tr_navbar_left"><span>'.$this->DrawDateJumper(false).'</span></th>'.$this->crLt;
					$output .= '<th class="tr_navbar">';
					$output .= '<a class="a_prev" href="javascript:phpCalendar.doPostBack(\'view\',\''.$view_type.'\',\''.$this->prevMonth['year'].'\',\''.$this->ConvertToDecimal($this->prevMonth['mon']).'\',\''.$this->ConvertToDecimal($this->prevMonth['mday']).'\')" title="'.$this->Lang('previous').'">&laquo;&laquo;</a>';
					$output .= ' '.$this->arrParameters['month_full_name'].' - '.$year.' ';
					$output .= '<a class="a_next" href="javascript:phpCalendar.doPostBack(\'view\',\''.$view_type.'\',\''.$this->nextMonth['year'].'\',\''.$this->ConvertToDecimal($this->nextMonth['mon']).'\',\''.$this->ConvertToDecimal($this->nextMonth['mday']).'\')" title="'.$this->Lang('next').'">&raquo;&raquo;</a>';
					$output .= '</th>'.$this->crLt;
					$output .= '<th class="tr_navbar_right"><span>
						  <a href="javascript:phpCalendar.doPostBack(\'view\',\''.$view_type.'\',\''.$this->prevYear['year'].'\',\''.$month.'\',\''.$this->arrParameters['day'].'\')">'.$this->prevYear['year'].'</a> |
						  <a href="javascript:phpCalendar.doPostBack(\'view\',\''.$view_type.'\',\''.$this->nextYear['year'].'\',\''.$month.'\',\''.$this->arrParameters['day'].'\')">'.$this->nextYear['year'].'</a>
                          </span></th>'.$this->crLt;
					$output .= '</tr>'.$this->crLt;
					$output .= '</table>'.$this->crLt;
				$output .= '</th>'.$this->crLt;
			$output .= '</tr>'.$this->crLt;
		}
		
        $output .= '<tr class="tr_days">'.$this->crLt;
			if($this->isWeekNumberOfYear) $output .= '<td class="th_wn"></td>'.$this->crLt;
			for($i = $this->weekStartedDay-1; $i < $this->weekStartedDay+6; $i++){
				$output .= '<td class="th">'.$this->arrWeekDays[($i % 7)][$this->weekDayNameLength].'</td>'.$this->crLt;
			}
		$output .= '</tr>'.$this->crLt;		
		
		// Display the first calendar row with correct positioning
		if($firstDay['wday'] == 0) $firstDay['wday'] = 7;
		$max_empty_days = $firstDay['wday']-($this->weekStartedDay-1);		
		if($max_empty_days < 7){
			$output .= '<tr class="tr" style="height:'.$this->celHeight.';">'.$this->crLt;
			if($this->isWeekNumberOfYear){
				$parts = explode('-', (date('Y-m-d', mktime(0,0,0,$month,1-$max_empty_days,$year))));
				$week_number = date('W', mktime(0,0,0,$month,2-$max_empty_days,$year));
				$output .= '<td class="td_wn">';
				if($this->arrViewTypes['weekly']['enabled']){
					$output .= '<a class="mw_link" href="javascript:phpCalendar.doPostBack(\'view\',\'weekly\',\''.$parts[0].'\',\''.$parts[1].'\',\''.$parts[2].'\')" title="'.$this->Lang('click_view_week').'">'.$week_number.'</a>';
				}else{
					$output .= $week_number;
				}
				$output .= '</td>'.$this->crLt;
			}
			for($i = 1; $i <= $max_empty_days; $i++){
				$empty_day = (date('d', mktime(0,0,0,$month,$i-$max_empty_days,$year)));
				$output .= '<td class="td_empty">'.$empty_day.'</td>'.$this->crLt;
			}			
			for($i = $max_empty_days+1; $i <= 7; $i++){
				$actday++;
				if(($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $month)) {
					$class = ' class="td_actday"';			
				}else if($this->highlightSelectedDay && $actday == $this->arrParameters['day']){
					$class = ' class="td_selday"';
				}else if($this->arrWeekDays[(($i+($this->weekStartedDay - 2)) % 7)]['short'] == 'Sun') {
					$class = ($this->sundayColor) ? ' class="td_sunday"' : ' class="td"';
				}else if($this->arrWeekDays[(($i+($this->weekStartedDay - 2)) % 7)]['short'] == 'Sat') {
					$class = ($this->saturdayColor) ? ' class="td_saturday"' : ' class="td"';
				}else{
					$class = ' class="td"';
				} 
				$output .= '<td'.$class.'>';
				$events_count = (is_array($this->arrTemp[$this->ConvertToDecimal($actday)])) ? count($this->RemoveDuplications($this->arrTemp[$this->ConvertToDecimal($actday)])) : 0;				
				if($this->arrParameters['view_type'] == 'monthly'){
					$events_count_text = ($events_count > 0) ? ' ('.(($events_count > 1) ? $events_count.' '.$this->Lang('events_lc') : $events_count.' '.$this->Lang('event_lc')).')' : '';
					$output .= (($this->arrViewTypes['daily']['enabled']) ? '<a class="md_link" href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$year.'\',\''.$month.'\',\''.$this->ConvertToDecimal($actday).'\')">'.$actday.' '.$events_count_text.'</a>' : $actday.' '.$events_count_text).'<br />';
					$output .= $this->DrawMonthlyDayCell($events_count, $actday, $month, $year, false);
				}else{
					$events_count_text = ($events_count > 0) ? '('.$events_count.')' : '';
					$output .= (($this->arrViewTypes['daily']['enabled']) ? '<a class="md_link" href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$year.'\',\''.$month.'\',\''.$this->ConvertToDecimal($actday).'\')">'.$actday.'</a>' : $actday).'<br />';
					$output .= $this->DrawMonthlyDoubledDayCell($events_count, $actday, $month, $year, false);
				}
				$output .= '</td>'.$this->crLt;
			}
			$output .= '</tr>'.$this->crLt;
		}
		
		// Get how many complete weeks are in the actual month
		$fullWeeks = floor(($lastDay['mday']-$actday)/7);
		
		for($i=0; $i<$fullWeeks; $i++){
			$output .= '<tr class="tr" style="height:'.$this->celHeight.'">'.$this->crLt;
			if($this->isWeekNumberOfYear){
				$parts = explode('-', (date('Y-m-d', mktime(0,0,0,$month,$actday+1,$year))));
				$week_number = date('W', mktime(0,0,0,$month,$actday+7,$year));
				$output .= '<td class="td_wn">';
				if($this->arrViewTypes['weekly']['enabled']){
					$output .= '<a class="mw_link" href="javascript:phpCalendar.doPostBack(\'view\',\'weekly\',\''.$parts[0].'\',\''.$parts[1].'\',\''.$parts[2].'\')" title="'.$this->Lang('click_view_week').'">'.$week_number.'</a>';
				}else{
					$output .= $week_number;
				}				
				$output .= '</td>'.$this->crLt;
			}
			for($j=0; $j<7; $j++){
				$actday++;
				if(($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $month)){
					$class = ' class="td_actday"';
				}else if($this->highlightSelectedDay && $actday == $this->arrParameters['day']){				
					$class = ' class="td_selday"';				
				}else if($this->arrWeekDays[(($j+($this->weekStartedDay - 1)) % 7)]['short'] == 'Sun'){
					$class = ($this->sundayColor) ? ' class="td_sunday"' : ' class="td"';
				}else if($this->arrWeekDays[(($j+($this->weekStartedDay - 1)) % 7)]['short'] == 'Sat'){
					$class = ($this->saturdayColor) ? ' class="td_saturday"' : ' class="td"';
				}else{
					$class = ' class="td"';
				}
				$output .= '<td'.$class.'>';
				$events_count = (is_array($this->arrTemp[$this->ConvertToDecimal($actday)])) ? count($this->RemoveDuplications($this->arrTemp[$this->ConvertToDecimal($actday)])) : 0;				
				if($this->arrParameters['view_type'] == 'monthly'){
					$events_count_text = ($events_count > 0) ? ' ('.(($events_count > 1) ? $events_count.' '.$this->Lang('events_lc') : $events_count.' '.$this->Lang('event_lc')).')' : '';
					$output .= (($this->arrViewTypes['daily']['enabled']) ? '<a class="md_link" href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$year.'\',\''.$month.'\',\''.$this->ConvertToDecimal($actday).'\')">'.$actday.' '.$events_count_text.'</a>' : $actday.' '.$events_count_text).'<br />';
					$output .= $this->DrawMonthlyDayCell($events_count, $actday, $month, $year, false);
				}else{
					$events_count_text = ($events_count > 0) ? '('.$events_count.')' : '';
					$output .= (($this->arrViewTypes['daily']['enabled']) ? '<a class="md_link" href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$year.'\',\''.$month.'\',\''.$this->ConvertToDecimal($actday).'\')">'.$actday.'</a>' : $actday).'<br />';
					$output .= $this->DrawMonthlyDoubledDayCell($events_count, $actday, $month, $year, false);
				}
				$output .= '</td>'.$this->crLt;
			}
			$output .= '</tr>'.$this->crLt;
		}
		
		// Now display the rest of the month
		if($actday < $lastDay['mday']){
			$output .= '<tr class="tr" style="height:'.$this->celHeight.'">'.$this->crLt;
			if($this->isWeekNumberOfYear){
				$parts = explode('-', (date('Y-m-d', mktime(0,0,0,$month,$actday+1,$year))));
				$week_number = date('W', mktime(0,0,0,$month,$actday+7,$year));
				$output .= '<td class="td_wn">';
				if($this->arrViewTypes['weekly']['enabled']){
					$output .= '<a class="mw_link" href="javascript:phpCalendar.doPostBack(\'view\',\'weekly\',\''.$parts[0].'\',\''.$parts[1].'\',\''.$parts[2].'\')" title="'.$this->Lang('click_view_week').'">'.$week_number.'</a>';
				}else{
					$output .= $week_number;
				}
				$output .= '</td>'.$this->crLt;
			}

			for($i=0; $i<7; $i++){
				$actday++;
				if(($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $month)){
					$class = ' class="td_actday"';
				}else if($this->highlightSelectedDay && $actday == $this->arrParameters['day']){
					$class = ' class="td_selday"';
				}else if($this->arrWeekDays[(($i+($this->weekStartedDay - 1)) % 7)]['short'] == 'Sun'){					
					$class = ($this->sundayColor) ? ' class="td_sunday"' : ' class="td"';				
				}else if($this->arrWeekDays[(($i+($this->weekStartedDay - 1)) % 7)]['short'] == 'Sat'){					
					$class = ($this->saturdayColor) ? ' class="td_saturday"' : ' class="td"';				
				}else{
					$class = ' class="td"';
				}				
				if($actday <= $lastDay['mday']){
					$output .= '<td'.$class.'>';
					$events_count = (is_array($this->arrTemp[$this->ConvertToDecimal($actday)])) ? count($this->RemoveDuplications($this->arrTemp[$this->ConvertToDecimal($actday)])) : 0;				
					if($this->arrParameters['view_type'] == 'monthly'){
						$events_count_text = ($events_count > 0) ? ' ('.(($events_count > 1) ? $events_count.' '.$this->Lang('events_lc') : $events_count.' '.$this->Lang('event_lc')).')' : '';
						$output .= (($this->arrViewTypes['daily']['enabled']) ? '<a class="md_link" href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$year.'\',\''.$month.'\',\''.$this->ConvertToDecimal($actday).'\')">'.$actday.' '.$events_count_text.'</a>' : $actday.' '.$events_count_text).'<br />';
						$output .= $this->DrawMonthlyDayCell($events_count, $actday, $month, $year, false);
					}else{
						$events_count_text = ($events_count > 0) ? '('.$events_count.')' : '';
						$output .= (($this->arrViewTypes['daily']['enabled']) ? '<a class="md_link" href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$year.'\',\''.$month.'\',\''.$this->ConvertToDecimal($actday).'\')">'.$actday.'</a>' : $actday).'<br />';
						$output .= $this->DrawMonthlyDoubledDayCell($events_count, $actday, $month, $year, false);
					}
				}else{
					$empty_day = (date('d', mktime(0,0,0,$month,$actday,$year)));
					$output .= '<td class="td_empty">'.(int)$empty_day.'</td>'.$this->crLt;
				}
			}					
			$output .= '</tr>'.$this->crLt;
		}		
		$output .= '</table>'.$this->crLt;
        
        $output .= $this->DrawEventTooltips(false);
        
        if($draw) echo $output;
        else return $output; 

		// finish caching
		$this->FinishCaching($cachefile);			
	}

	/**
	 * Draw small monthly doubled calendar
	 * @param $draw
	*/	
	private function DrawMonthDoubled($draw = true)
    {
        $output = '';
        
		// today, first day and last day in month
		$firstMonth  = getdate(mktime(0,0,0,$this->arrParameters['month'],1,$this->arrParameters['year']));
		$secondMonth = getdate(mktime(0,0,0,$this->arrParameters['month']+1,1,$this->arrParameters['year']));
		
		#echo '<pre>';
		#print_r($firstMonth);
		#print_r($secondMonth);
		#echo '</pre>';
		
		// Create a table with the necessary header informations
		$output .= '<table class="double_month">'.$this->crLt;
		$output .= '<tr>';
			$output .= '<th colspan="'.(($this->isWeekNumberOfYear) ? '8' : '7').'">';
				$output .= '<table class="table_navbar" cellspacing="0">'.$this->crLt;
				$output .= '<tr>';
				$output .= '<th class="tr_navbar_left"><span>'.$this->DrawDateJumper(false).'</span></th>'.$this->crLt;
				$output .= '<th class="tr_navbar" colspan="2">';
				$output .= '<a class="a_prev" href="javascript:phpCalendar.doPostBack(\'view\',\'monthly_double\',\''.$this->prevMonth['year'].'\',\''.$this->ConvertToDecimal($this->prevMonth['mon']).'\',\''.$this->ConvertToDecimal($this->prevMonth['mday']).'\')" title="'.$this->Lang('previous').'">&laquo;&laquo;</a>';
                if($firstMonth['year'] == $secondMonth['year']){
                    $output .= ' '.$this->arrMonths[$firstMonth['mon']].' - '.$this->arrMonths[$secondMonth['mon']].', '.$secondMonth['year'].' ';
                }else{
                    $output .= ' '.$this->arrMonths[$firstMonth['mon']].', '.$firstMonth['year'].' - '.$this->arrMonths[$secondMonth['mon']].', '.$secondMonth['year'].' ';                                    
                }
				$output .= '<a class="a_next" href="javascript:phpCalendar.doPostBack(\'view\',\'monthly_double\',\''.$this->nextMonth['year'].'\',\''.$this->ConvertToDecimal($this->nextMonth['mon']).'\',\''.$this->ConvertToDecimal($this->nextMonth['mday']).'\')" title="'.$this->Lang('next').'">&raquo;&raquo;</a>';
				$output .= '</th>'.$this->crLt;
				$output .= '<th class="tr_navbar_right"><span>				
					  <a href="javascript:phpCalendar.doPostBack(\'view\',\'monthly_double\',\''.$this->prevYear['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\')">'.$this->prevYear['year'].'</a> |
					  <a href="javascript:phpCalendar.doPostBack(\'view\',\'monthly_double\',\''.$this->nextYear['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\')">'.$this->nextYear['year'].'</a>
					  </span></th>'.$this->crLt;
				$output .= '</tr>'.$this->crLt;
				$output .= '<tr>';
				$output .= '<th class="tr_navbar" colspan="2">';                
                if($this->arrViewTypes['monthly']['enabled']) $output .= '<a href="javascript:phpCalendar.doPostBack(\'view\',\'monthly\',\''.$firstMonth['year'].'\',\''.$this->ConvertToDecimal($firstMonth['mon']).'\',\''.$this->arrParameters['day'].'\')">'.$this->arrMonths[$firstMonth['mon']].'</a>';
                else $output .= $this->arrMonths[$firstMonth['mon']];
                $output .= '</th>';
                $output .= '<th class="tr_navbar" colspan="2">';
                if($this->arrViewTypes['monthly']['enabled']) $output .= '<a href="javascript:phpCalendar.doPostBack(\'view\',\'monthly\',\''.$secondMonth['year'].'\',\''.$this->ConvertToDecimal($secondMonth['mon']).'\',\''.$this->arrParameters['day'].'\')">'.$this->arrMonths[$secondMonth['mon']].'</a>';
                else $output .= $this->arrMonths[$secondMonth['mon']];                
                $output .= '</th>';
				$output .= '</tr>'.$this->crLt;
				$output .= '</table>'.$this->crLt;
			$output .= '</th>'.$this->crLt;
		$output .= '</tr>'.$this->crLt;

		$this->weekDayNameLength = 'short';
		$this->celHeight = number_format(((int)$this->calHeight)/12, '0').'px';
		$output .= '<tr valign="top">'.$this->crLt;
			$output .= '<td colspan="4" class="cal_dleft">'.$this->crLt;
			$output .= $this->DrawMonth($firstMonth['year'], $firstMonth['mon'], 'monthly_double', false, false);
			$output .= '</td>'.$this->crLt;		
			$output .= '<td colspan="4" class="cal_dright">'.$this->crLt;
			$output .= $this->DrawMonth($secondMonth['year'], $secondMonth['mon'], 'monthly_double', false, false);
			$output .= '</td>'.$this->crLt;
		$output .= '</tr>'.$this->crLt;				
		$output .= '</table>'.$this->crLt;
        
        if($draw) echo $output;
        else return $output; 
	}	

	/**
	 * Draw small monthly calendar
	 * @param $year
	 * @param $month
	 * @param $draw
	*/	
	private function DrawMonthSmall($year = '', $month = '', $draw = true)
	{
		if($month == '') $month = $this->arrParameters['month'];
		if($year == '') $year = $this->arrParameters['year'];
		$week_rows = 0;
		$actday = 0;
        $output = '';
		
		// today, first day and last day in month
		$firstDay = getdate(mktime(0,0,0,$month,1,$year));
		$lastDay  = getdate(mktime(0,0,0,$month+1,0,$year));

		// get array of events for month
		$arrEventsList = $this->GetEventsListForMonth($year, $month);
		//echo '<pre>';
		//print_r($arrEventsList);
		//echo '</pre>';
		if($this->arrParameters['view_type'] == 'monthly_small'){		
			$output .= '<table class="table_navbar">'.$this->crLt;
			$output .= '<tr>';
			$output .= '<th class="tr_navbar">';
			$output .= ' <a href="javascript:phpCalendar.doPostBack(\'view\',\'monthly\',\''.$this->prevMonth['year'].'\',\''.$this->ConvertToDecimal($this->prevMonth['mon']).'\',\''.$this->ConvertToDecimal($this->prevMonth['mday']).'\')">&laquo;&laquo;</a> ';
			$output .= $this->arrParameters['month_full_name'].' - '.$this->arrParameters['year'];
			$output .= ' <a href="javascript:phpCalendar.doPostBack(\'view\',\'monthly\',\''.$this->nextMonth['year'].'\',\''.$this->ConvertToDecimal($this->nextMonth['mon']).'\',\''.$this->ConvertToDecimal($this->nextMonth['mday']).'\')">&raquo;&raquo;</a> ';
			$output .= '</th>'.$this->crLt;
			$output .= '</tr>'.$this->crLt;
			$output .= '</table>'.$this->crLt;
		}

		// create a table with the necessary header informations
		$output .= '<table class="month_small">'.$this->crLt;
		$output .= '<tr class="tr_small_days">'.$this->crLt;
			if($this->isWeekNumberOfYear) $output .= '<td class="th_small_wn"></td>'.$this->crLt;
			for($i = $this->weekStartedDay-1; $i < $this->weekStartedDay+6; $i++){
				$output .= '<td class="th_small">'.$this->arrWeekDays[($i % 7)]['short'].'</td>'.$this->crLt;		
			}
		$output .= '</tr>'.$this->crLt;
		
		// display the first calendar row with correct positioning
		if ($firstDay['wday'] == 0) $firstDay['wday'] = 7;
		$max_empty_days = $firstDay['wday']-($this->weekStartedDay-1);		
		if($max_empty_days < 7){
			$output .= '<tr class="tr_small" style="height:'.$this->celHeight.'">'.$this->crLt;
			if($this->isWeekNumberOfYear){
				$parts = explode('-', (date('Y-m-d', mktime(0,0,0,$month,1-$max_empty_days,$year))));
				$week_number = date('W', mktime(0,0,0,$month,2-$max_empty_days,$year));
				$output .= '<td class="td_small_wn">';
				if($this->arrViewTypes['weekly']['enabled']){
					$output .= '<a href="javascript:phpCalendar.doPostBackSmall(\'view\',\'weekly\',\''.$parts[0].'\',\''.$parts[1].'\',\''.$parts[2].'\',\''.$this->actionLink.'\')" title="'.$this->Lang('click_view_week').'">'.$week_number.'</a>';
				}else{
					$output .= $week_number;
				}
				$output .= '</td>'.$this->crLt;
			}
			for($i = 1; $i <= $max_empty_days; $i++){
				$output .= '<td class="td_small_empty">&nbsp;</td>'.$this->crLt;
			}			
			for($i = $max_empty_days+1; $i <= 7; $i++){
				$actday++;
				$output .= $this->DrawMonthSmallCell($arrEventsList, $year, $month, $actday, false);
			}
			$output .= '</tr>'.$this->crLt;
			$week_rows++;
		}
		
		// get how many complete weeks are in the actual month
		$fullWeeks = floor(($lastDay['mday']-$actday)/7);
		
		for($i=0; $i<$fullWeeks; $i++){
			$output .= '<tr class="tr_small" style="height:'.$this->celHeight.'">'.$this->crLt;
			if($this->isWeekNumberOfYear){
				$parts = explode('-', (date('Y-m-d', mktime(0,0,0,$month,$actday+1,$year))));
				$week_number = date('W', mktime(0,0,0,$month,$actday+7,$year));
				$output .= '<td class="td_small_wn">';
				if($this->arrViewTypes['weekly']['enabled']){
					$output .= '<a href="javascript:phpCalendar.doPostBackSmall(\'view\',\'weekly\',\''.$parts[0].'\',\''.$parts[1].'\',\''.$parts[2].'\',\''.$this->actionLink.'\')" title="'.$this->Lang('click_view_week').'">'.$week_number.'</a>';
				}else{
					$output .= $week_number;
				}				
				$output .= '</td>'.$this->crLt;
			}
			for ($j=0;$j<7;$j++){
				$actday++;
				$output .= $this->DrawMonthSmallCell($arrEventsList, $year, $month, $actday, false);
			}
			$output .= '</tr>'.$this->crLt;
			$week_rows++;			
		}
		
		// now display the rest of the month
		if ($actday < $lastDay['mday']){
			$output .= '<tr class="tr_small" style="height:'.$this->celHeight.'">'.$this->crLt;
			if($this->isWeekNumberOfYear){
				$parts = explode('-', (date('Y-m-d', mktime(0,0,0,$month,$actday+1,$year))));
				$week_number = date('W', mktime(0,0,0,$month,$actday+7,$year));
				$output .= '<td class="td_small_wn">';
				if($this->arrViewTypes['weekly']['enabled']){
					$output .= '<a href="javascript:phpCalendar.doPostBackSmall(\'view\',\'weekly\',\''.$parts[0].'\',\''.$parts[1].'\',\''.$parts[2].'\',\''.$this->actionLink.'\')" title="'.$this->Lang('click_view_week').'">'.$week_number.'</a>';
				}else{
					$output .= $week_number;
				}
				$output .= '</td>'.$this->crLt;
			}
			for ($i=0; $i<7;$i++){
				$actday++;
				if($actday <= $lastDay['mday']){
					$output .= $this->DrawMonthSmallCell($arrEventsList, $year, $month, $actday, false);
				}else{
					$output .= '<td class="td_small_empty">&nbsp;</td>'.$this->crLt;
				}
			}					
			$output .= '</tr>'.$this->crLt;
			$week_rows++;
		}
		
		// complete last line
		if($week_rows < 5){
			$output .= '<tr class="tr_small" style="height:'.$this->celHeight.'">'.$this->crLt;
			if($this->isWeekNumberOfYear) $output .= '<td class="td_small_wn"></td>'.$this->crLt;
			for ($i=0; $i<7;$i++){
				$output .= '<td class="td_small_empty">&nbsp;</td>'.$this->crLt;
			}					
			$output .= '</tr>'.$this->crLt;
			$week_rows++;			
		}
		
		$output .= '</table>'.$this->crLt;
        
        if($draw) echo $output;
        else return $output; 
	}
	
	/**
	 * Draw small month cell
	 * @param &$arrEventsList
	 * @param $year
	 * @param $month
	 * @param $actday
	 * @param $draw
	*/	
	private function DrawMonthSmallCell(&$arrEventsList, $year, $month, $actday, $draw = true)
	{
        $output = '';
		$arrEvents = $this->RemoveDuplications($arrEventsList[$this->ConvertToDecimal($actday)]);			
		$events_count = (is_array($arrEventsList[$this->ConvertToDecimal($actday)])) ? count($arrEvents) : 0;
		if($events_count > 0){
			$events_count_text = ($events_count > 0) ? ' ('.(($events_count > 1) ? $events_count.' '.$this->Lang('events_lc') : $events_count.' '.$this->Lang('event_lc')).')' : '';
			$class = ' class="td_small_full '.(($events_count < 10) ? 'e'.$events_count : 'e10').'"';
            $output .= '<td'.$class.' title="'.$events_count_text.'">';
            if($this->arrViewTypes['daily']['enabled']){
                $output .= '<a href="javascript:phpCalendar.doPostBackSmall(\'view\',\'daily\',\''.$year.'\',\''.$month.'\',\''.$this->ConvertToDecimal($actday).'\',\''.$this->actionLink.'\');">'.$actday.'</a>';                
            }else{
                $output .= $actday;
            }
            $output .= '</td>'.$this->crLt;	
		}else{
			if(($actday == $this->arrToday['mday']) && ($this->arrToday['mon'] == $month) && ($this->arrToday['year'] == $year)){
				$class = ' class="td_small_actday"';
			}else{
				$class = ' class="td_small"';
			}
			$output .= '<td'.$class.'>'.$actday.'</td>'.$this->crLt;	
		}
        if($draw) echo $output;
        else return $output;
	}

	/**
	 * Draw weekly calendar
	*/	
	private function DrawWeek()
	{
        $output = '';
        
		// start caching
		$cachefile = $this->cacheDir.md5('weekly-'.$this->arrParameters['selected_category'].'-'.$this->arrParameters['selected_location'].'-'.$this->arrParameters['year'].'-'.$this->arrParameters['month'].'-'.$this->arrParameters['day']).'.cch';
		if($this->StartCaching($cachefile)) return true;

		// today, first day and last day in month
		$firstDay = getdate(mktime(0,0,0,$this->currWeek['month'],1,$this->currWeek['year']));
		$lastDay  = getdate(mktime(0,0,0,$this->currWeek['month']+1,0,$this->currWeek['year']));
		
		// Create a table with the necessary header informations
		$output .= '<table class="week">'.$this->crLt;
		$output .= '<tr>'.$this->crLt;
		$output .= '<th colspan="7">'.$this->crLt;
			$output .= '<table class="week_navigation" width="100%" border="0">'.$this->crLt;
			$output .= '<tr>';
			$output .= '<th class="tr_navbar_left"><span>'.$this->DrawDateJumper(false).'</span></th>';
			$output .= '<th class="tr_navbar">';
            // draw Month Year - Month Year line
			if($this->currWeek['year'] != $this->nextWeek['year']){
				$output .= $this->prevWeek['month'].' '.$this->currWeek['year'].' - '.$this->nextWeek['month'].' '.$this->nextWeek['year'];
			}else{
				$month = (int)$this->currWeek['month'];
				$output .= $this->arrMonths[$month].(($month != $this->nextWeek['mon']) ? ' - '.$this->nextWeek['month'] : '').' '.$this->currWeek['year'];
			}
			$output .= '</th>'.$this->crLt;
            if($this->dateFormat == 'dd/mm/yyyy'){
                $week_prev = $this->ConvertToDecimal($this->prevWeek['mday']).$this->Lang('th').' '.$this->prevWeek['month'];
                $week_next = $this->ConvertToDecimal($this->nextWeek['mday']).$this->Lang('th').' '.$this->nextWeek['month'];
            }else{
                $week_prev = $this->prevWeek['month'].' '.$this->ConvertToDecimal($this->prevWeek['mday']).$this->Lang('th');
                $week_next = $this->nextWeek['month'].' '.$this->ConvertToDecimal($this->nextWeek['mday']).$this->Lang('th');            
            }
			$output .= '<th class="tr_navbar_right"><span>				
				  <a href="javascript:phpCalendar.doPostBack(\'view\',\'weekly\',\''.$this->prevWeek['year'].'\',\''.$this->ConvertToDecimal($this->prevWeek['mon']).'\',\''.$this->ConvertToDecimal($this->prevWeek['mday']).'\')">'.$week_prev.'</a> |
				  <a href="javascript:phpCalendar.doPostBack(\'view\',\'weekly\',\''.$this->nextWeek['year'].'\',\''.$this->ConvertToDecimal($this->nextWeek['mon']).'\',\''.$this->ConvertToDecimal($this->nextWeek['mday']).'\')">'.$week_next.'</a>
				  </span></th>'.$this->crLt;
			$output .= '</tr>'.$this->crLt;
			$output .= '</table>'.$this->crLt;			  
		$output .= '</th>'.$this->crLt;
		$output .= '</tr>'.$this->crLt;
		$output .= '<tr class="tr_days">'.$this->crLt;
			for($i = $this->weekStartedDay-1; $i < $this->weekStartedDay+6; $i++){
				$week_day = date('w', mktime(0,0,0,$this->currWeek['month'],$this->currWeek['day']+$i-($this->weekStartedDay-1),$this->currWeek['year']));
				$output .= '<td class="th">'.$this->arrWeekDays[($week_day % 7)][$this->weekDayNameLength].'</td>';						
			}
		$output .= '</tr>'.$this->crLt;
		
		// Display the first calendar row with correct positioning
		$output .= '<tr>'.$this->crLt;
		if($firstDay['wday'] == 0) $firstDay['wday'] = 7;
		$actday = 0;
		for($i = 0; $i <= 6; $i++){
			$parts = explode('-', date('d-n-Y', mktime(0,0,0,$this->currWeek['month'],$this->currWeek['day']+$i,$this->currWeek['year'])));
			$actday = $parts[0];
			$actmon = $parts[1];
			$actyear = $parts[2];
			$week_day = date('w', mktime(0,0,0,$this->currWeek['month'],$this->currWeek['day']+$i-($this->weekStartedDay-1),$this->currWeek['year']));
			
			if(((int)$actday == (int)$this->arrToday['mday'])){
				$class = ' class="td_actday_w"';
			}else if($this->arrWeekDays[(($week_day+($this->weekStartedDay - 1)) % 7)]['short'] == 'Sun'){					
				$class = ($this->sundayColor) ? ' class="td_sunday_w"' : ' class="td_w"';				
			}else if($this->arrWeekDays[(($week_day+($this->weekStartedDay - 1)) % 7)]['short'] == 'Sat'){					
				$class = ($this->saturdayColor) ? ' class="td_saturday_w"' : ' class="td_w"';				
			}else{				
				$class = ' class="td_w"';
			}
			$output .= '<td'.$class.'>'.$this->crLt;
			
				// prepare events for this day of week
				$sql = 'SELECT
							'.CALENDAR_TABLE.'.id,
                            '.CALENDAR_TABLE.'.event_id,
							'.CALENDAR_TABLE.'.event_date,
							'.CALENDAR_TABLE.'.event_time,
							'.CALENDAR_TABLE.'.slot,
							DATE_FORMAT('.CALENDAR_TABLE.'.event_time, "%H:%i") as event_time_formatted,
							'.EVENTS_TABLE.'.name,
							'.EVENTS_TABLE.'.description,
                            '.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.color' : '""').' as color
						FROM '.CALENDAR_TABLE.'						
							INNER JOIN '.EVENTS_TABLE.' ON '.CALENDAR_TABLE.'.event_id = '.EVENTS_TABLE.'.id
                            '.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
						WHERE
							'.CALENDAR_TABLE.'.event_date = \''.$this->currWeek['year'].'-'.$this->ConvertToDecimal($actmon).'-'.$actday.'\'
							'.$this->PrepareWhereClauseParticipant().'
							'.$this->PrepareWhereClauseCategory().'
                            '.$this->PrepareWhereClauseLocation().'
							'.$this->PrepareWhereClauseEventTime().'
						ORDER BY '.CALENDAR_TABLE.'.id ASC';
						
				$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve list of events for specific day of week');
				
				// prepare time blocks array
				$arrEvents = array();

                if(in_array($this->timeSlot, array('10', '15', '30', '45'))){
                    $arrEvents = $this->FillTimeSlots($this->timeSlot);
				}else{
                    // 60 or 120
                    $increment = ($this->timeSlot == '120') ? 2 : 1;
					for($i_hour=$this->fromHour; $i_hour<$this->toHour; $i_hour += $increment){
						$ind = $this->ConvertToDecimal($i_hour).':00';
						$arrEvents[$ind] = array();
					}			                    
				}
				
				foreach($result[0] as $key => $val){
					$arrEvents[$val['event_time_formatted']][] = array('id'=>$val['id'], 'event_id'=>$val['event_id'], 'name'=>$val['name'], 'slot'=>$val['slot'], 'description'=>$val['description'], 'color'=>$val['color']);
				}
		
				///echo '<pre>';
				///print_r($arrEvents);
				///echo '</pre>';				
			
				$output .= '<table width="100%" border="0" cellpadding="0" celspacing="0">'.$this->crLt;
				$output .= '<tr><td class="td_header" colspan="2">';
                if($this->dateFormat == 'mm/dd/yyyy'){
                    $date_text = $this->arrMonths[$actmon].' '.$actday;
                }else if($this->dateFormat == 'yyyy/mm/dd'){
                    $date_text = $this->arrMonths[$actmon].' '.$actday;
                }else{
                    $date_text = $actday.' '.$this->arrMonths[$actmon];    
                }					
				if($this->arrViewTypes['daily']['enabled']){
					$output .= '<a href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$this->currWeek['year'].'\',\''.$this->ConvertToDecimal($actmon).'\',\''.$actday.'\')">'.$date_text.'</a>';
				}else{
                    $output .= $date_text;
				}
				$output .= '</td></tr>'.$this->crLt;
				$today = date('YmdHi');
				
				if(!$this->IsDisabledDay($week_day+1)){
					foreach($arrEvents as $key_hour => $val_arr){
						$key_hour_parts = explode(':', $key_hour);
						$hour_part = isset($key_hour_parts[0]) ? $key_hour_parts[0] : '00';
						$minute_part = isset($key_hour_parts[1]) ? $key_hour_parts[1] : '00';
						if(($hour_part >= $this->fromHour) && ($hour_part < $this->toHour)){
							$output .= '<tr>'; 
							$output .= '<td align="'.(($this->direction == 'rtl') ? 'right' : 'left').'" valign="top" width="'.(($this->timeFormat == '24') ? '45px' : '64px').'">';
							$current_date = $actyear.$this->ConvertToDecimal($actmon).$actday.str_replace(':', '', $key_hour);
							$events_list = $this->GetEventsListForWeekDay($arrEvents[$key_hour], $key_hour);
							$allow_add_event = false;
							if($this->arrEventsOperations['add']){
								if(!$this->allowEventsMultipleOccurrences){
									if(!$events_list) $allow_add_event = true;
								}else{
									if($this->allowEditingEventsInPast || (!$this->allowEditingEventsInPast && ($current_date > $today))){
										$allow_add_event = true;
									}
								}
							}
							if($this->hideWeekEmptySlots){
								if($events_list != '') $output .= $this->ConvertToHour($hour_part, $minute_part, true).' ';									
							}else{
                                if($this->showEmptyTimeSlots || $events_list != ''){
                                    $output .= (($this->isShowTime) ? $this->ConvertToHour($hour_part, $minute_part, true).' ' : '');	
                                    if($allow_add_event) $output .= '<a href="javascript:phpCalendar.callAddEventForm(\'divAddEvent\',\''.$this->currWeek['year'].'\',\''.$this->ConvertToDecimal($actmon).'\',\''.$actday.'\',\''.$key_hour.'\','.$this->disableEarlierHours.',\'week\');" title="'.$this->Lang('add_new_event').'">+</a><br />';
                                }else{
                                    $output .= '&nbsp;';
                                }
							}
							$output .= $events_list;
							$output .= '</td>';
							$output .= '</tr>'.$this->crLt;						
						}
					}					
				}
				$output .= '</table>'.$this->crLt;
			$output .= '</td>'.$this->crLt;
		}
		$output .= '</tr>'.$this->crLt;
		$output .= '</table>'.$this->crLt;		

		$output .= $this->DrawAddEventForm(false);
		$output .= $this->DrawEditEventForm(false);
        $output .= $this->DrawEventTooltips(false);

        echo $output;        

		// finish caching
		$this->FinishCaching($cachefile);			
	}

	/**
	 * Draw daily calendar
	*/	
	private function DrawDay()
	{
        $output = '';
        
		// start caching
		$cachefile = $this->cacheDir.md5('daily-'.$this->arrParameters['selected_category'].'-'.$this->arrParameters['selected_location'].'-'.$this->arrParameters['year'].'-'.$this->arrParameters['month'].'-'.$this->arrParameters['day']).'.cch';
		if($this->StartCaching($cachefile)) return true;
		
		// [#004 05.04.2011]
		$sql = 'SELECT					
					'.CALENDAR_TABLE.'.id,
                    '.CALENDAR_TABLE.'.event_id,
					'.CALENDAR_TABLE.'.event_date,
					'.CALENDAR_TABLE.'.event_time,
					'.CALENDAR_TABLE.'.slot,
					DATE_FORMAT('.CALENDAR_TABLE.'.event_time, "%H:%i") as event_time_formatted,
                    '.EVENTS_TABLE.'.category_id,
					'.EVENTS_TABLE.'.name,
					'.EVENTS_TABLE.'.description,
                    '.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.color' : '""').' as color,
					(SELECT CONCAT(c1.event_date,c1.event_time) FROM '.CALENDAR_TABLE.' c1 WHERE c1.slot = 1 AND c1.unique_key = '.CALENDAR_TABLE.'.unique_key LIMIT 0, 1) as priority
				FROM '.CALENDAR_TABLE.'
					INNER JOIN '.EVENTS_TABLE.' ON '.CALENDAR_TABLE.'.event_id = '.EVENTS_TABLE.'.id
					'.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
				WHERE
					'.CALENDAR_TABLE.'.event_date = \''.$this->arrParameters['year'].'-'.$this->arrParameters['month'].'-'.$this->arrParameters['day'].'\'
					'.$this->PrepareWhereClauseParticipant().'
					'.$this->PrepareWhereClauseCategory().'
                    '.$this->PrepareWhereClauseLocation().'
					'.$this->PrepareWhereClauseEventTime().'					
				ORDER BY priority ASC';
				
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve list of events for specific day');
		
		// prepare time blocks array
		$arrEvents = array();

        if(in_array($this->timeSlot, array('10', '15', '30', '45'))){
            $arrEvents = $this->FillTimeSlots($this->timeSlot);
        }else{
            // 60 or 120
            $increment = ($this->timeSlot == '120') ? 2 : 1;
            for($i_hour=$this->fromHour; $i_hour<$this->toHour; $i_hour += $increment){
                $ind = $this->ConvertToDecimal($i_hour).':00';
                $arrEvents[$ind] = array();
            }
        }
		
		foreach($result[0] as $key => $val){
			$arrEvents[$val['event_time_formatted']][] = array('id'=>$val['id'], 'event_id'=>$val['event_id'], 'category_id'=>$val['category_id'], 'name'=>$val['name'], 'slot'=>$val['slot'], 'description'=>$this->PrepareFormatedText($val['description']), 'color'=>$val['color']);
		}

		// Create a table with the necessary header informations
		$output .= '<table class="day_navigation" width="100%" border="0">'.$this->crLt;
		$output .= '<tr>';
		$output .= '<th class="tr_navbar_left"><span>'.$this->DrawDateJumper(false).'</span></th>'.$this->crLt;
		$output .= '<th class="tr_navbar">';
		$output .= '<a class="a_prev" href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$this->prevDay['year'].'\',\''.$this->ConvertToDecimal($this->prevDay['mon']).'\',\''.$this->ConvertToDecimal($this->prevDay['mday']).'\')" title="'.$this->Lang('previous').'">&laquo;&laquo;</a>';
        $output .= ' '.$this->arrParameters['weekday'].' - ';
        if($this->dateFormat == 'mm/dd/yyyy'){
            $output .= $this->arrParameters['month_full_name'].' '.$this->arrParameters['day'].', '.$this->arrParameters['year'].' ';
        }else if($this->dateFormat == 'yyyy/mm/dd'){
            $output .= $this->arrParameters['year'].', '.$this->arrParameters['month_full_name'].' '.$this->arrParameters['day'].' ';
        }else{
            $output .= $this->arrParameters['day'].' '.$this->arrParameters['month_full_name'].', '.$this->arrParameters['year'].' ';
        }
		$output .= '<a class="a_next" href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$this->nextDay['year'].'\',\''.$this->ConvertToDecimal($this->nextDay['mon']).'\',\''.$this->ConvertToDecimal($this->nextDay['mday']).'\')" title="'.$this->Lang('next').'">&raquo;&raquo;</a>';
		$output .= '</th>'.$this->crLt;
        if($this->dateFormat == 'dd/mm/yyyy'){
			$week_prev = $this->ConvertToDecimal($this->prevWeek['mday']).$this->Lang('th').' '.$this->prevWeek['month'];
			$week_next = $this->ConvertToDecimal($this->nextWeek['mday']).$this->Lang('th').' '.$this->nextWeek['month'];
        }else{
			$week_prev = $this->prevWeek['month'].' '.$this->ConvertToDecimal($this->prevWeek['mday']).$this->Lang('th');
			$week_next = $this->nextWeek['month'].' '.$this->ConvertToDecimal($this->nextWeek['mday']).$this->Lang('th');            
        }
		$output .= '<th class="tr_navbar_right" colspan="2"><span>
			  <a href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$this->prevWeek['year'].'\',\''.$this->ConvertToDecimal($this->prevWeek['mon']).'\',\''.$this->ConvertToDecimal($this->prevWeek['mday']).'\')">'.$week_prev.'</a> |
			  <a href="javascript:phpCalendar.doPostBack(\'view\',\'daily\',\''.$this->nextWeek['year'].'\',\''.$this->ConvertToDecimal($this->nextWeek['mon']).'\',\''.$this->ConvertToDecimal($this->nextWeek['mday']).'\')">'.$week_next.'</a>
			  </span></th>'.$this->crLt;
		$output .= '</tr>'.$this->crLt;
		$output .= '</table>'.$this->crLt;

		$output .= '<table class="day" cellpadding="0" celspacing="0">'.$this->crLt;
		$today = date('YmdHi');
		$count = 0;
        $arrHelp = $this->DrawEventsListForDayPrepare($arrEvents);
       
        $actslot_set = false;
		foreach($arrEvents as $key_hour => $val_arr){
			$key_hour_parts = explode(':', $key_hour);
			$hour_part = isset($key_hour_parts[0]) ? $key_hour_parts[0] : '00';
			$minute_part = isset($key_hour_parts[1]) ? $key_hour_parts[1] : '00';
			if(($hour_part >= $this->fromHour) && ($hour_part < $this->toHour)){                
                $td_acthour_d = ' class="td_d_h"';
                $td_d = ' class="td_d"';
                // find actual time slot 
				if($this->arrParameters['day'] == $this->arrToday['mday'] && !$actslot_set){
                    $current_time = $this->ConvertToDecimal($this->arrToday['hours']).":".$this->ConvertToDecimal($this->arrToday['minutes']);
                    $slot_time = $hour_part.':'.$minute_part;
                    if((strtotime($slot_time) + $this->timeSlot * 60) > strtotime($current_time)){
                        $actslot_set = true;
                        $td_acthour_d = ' class="td_acthour_d_h"';
                        $td_d = ' class="td_acthour_d"';
                    }
				}
				$output .= '<tr>';		
				$output .= (($this->isShowTime) ? '<td'.$td_acthour_d.'>'.$this->ConvertToHour($hour_part, $minute_part, true).'</td>' : '');
				$output .= '<td'.$td_d.'>';
				if(!$this->IsDisabledDay($this->arrParameters['wday']+1)){
					$current_date = $this->arrParameters['year'].$this->arrParameters['month'].$this->arrParameters['day'].str_replace(':', '', $key_hour);
					$events_list = $this->GetEventsListForHour($arrEvents[$key_hour]);
					$allow_add_event = false;
					if($this->arrEventsOperations['add']){
						if(!$this->allowEventsMultipleOccurrences){
							if(!$events_list) $allow_add_event = true;
						}else{
							if($this->allowEditingEventsInPast || (!$this->allowEditingEventsInPast && ($current_date > $today))){
								$allow_add_event = true;
							}
						}
					}
					$output .= ($allow_add_event) ? ' <a href="javascript:phpCalendar.callAddEventForm(\'divAddEvent\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\''.$key_hour.'\','.$this->disableEarlierHours.',\'day\');" title="'.$this->Lang('add_new_event').'">+</a> ' : '&nbsp;';
					if($this->eventsDisplayType['daily'] == 'block'){
                        $output .= $this->DrawEventsListForDay($arrHelp, $key_hour);
					}else{
						$output .= $events_list;	
					}					
				}
				$output .= '</td>';
				$output .= '</tr>'.$this->crLt;
				$count++;
			}			
		}
		$output .= '</table>'.$this->crLt;

		$output .= $this->DrawAddEventForm(false);
		$output .= $this->DrawEditEventForm(false);
        $output .= $this->DrawEventTooltips(false);
        
        echo $output;
        
		// finish caching
		$this->FinishCaching($cachefile);			
	}


	////////////////////////////////////////////////////////////////////////////
	// EVENTS

	/**
	 * Draw single event adding form
	 * @param $draw
	*/	
	private function DrawAddEventForm($draw = true)
	{
		$content = $this->FileGetContents('templates/add_event_form.tpl');
        if($content !== false){
			$content = str_ireplace('{h:class_move}', ' cal_move', $content);
			$content = str_ireplace('{h:ddl_from}', $this->DrawDateTime('from', $this->arrParameters['year'], $this->arrParameters['month'], $this->arrParameters['day'], $this->arrToday['hours'], false), $content);
			$content = str_ireplace('{h:ddl_to}', $this->DrawDateTime('to', $this->arrParameters['year'], $this->arrParameters['month'], $this->arrParameters['day'], $this->arrToday['hours'], false), $content);
			$content = str_ireplace('{h:lan_event_name}', $this->Lang('event_name'), $content);
            $content = str_ireplace('{h:lan_event_url}', $this->Lang('event_url'), $content);
			$content = str_ireplace('{h:lan_event_description}', $this->Lang('event_description'), $content);
			$content = str_ireplace('{h:lan_from}', $this->Lang('from'), $content);
			$content = str_ireplace('{h:lan_to}', $this->Lang('to'), $content); 
			$content = str_ireplace('{h:lan_add_event}', $this->Lang('add_event'), $content);
			$content = str_ireplace('{h:lan_close}', $this->Lang('close'), $content);
			$content = str_ireplace('{h:lan_add_new_event}', $this->Lang('add_new_event'), $content);
            $content = str_ireplace('{h:lan_select_existing_event}', $this->Lang('select_existing_event'), $content);
            $ddl_display = ($this->isCategoriesAllowed) ? 'display:none;' : 'display:inline;';
            $cat_allowed = ($this->isCategoriesAllowed) ? '1' : '';
			$content = str_ireplace('{h:ddl_event_name}', $this->DrawEventsDDL('', 'onchange="javascript:phpCalendar.eventSelectedDDL(2,'.$this->timeSlot.','.$cat_allowed.')"', $ddl_display), $content);
			$content = str_ireplace('{h:ddl_category_name}', $this->DrawCategoriesDDL($this->arrParameters['selected_category'], 'onchange="javascript:phpCalendar.eventSelectedDDL(1, '.$this->timeSlot.')"'), $content);
            $content = str_ireplace('{h:ddl_location_name}', $this->DrawLocationsDDL('', '', ''), $content);
		}
        if($draw) echo $content.$this->crLt;
        else return $content.$this->crLt;
	}

	/**
	 * Draw single event edding form
	 * @param $draw
	*/	
	private function DrawEditEventForm($draw = true)
	{
		$content = $this->FileGetContents('templates/edit_event_form.tpl');
		
        if($content !== false){
			$content = str_ireplace('{h:class_move}', ' cal_move', $content);
			$content = str_ireplace('{h:ddl_from}', $this->DrawDateTime('from_edit', $this->arrParameters['year'], $this->arrParameters['month'], $this->arrParameters['day'], $this->arrToday['hours'], false), $content);
			$content = str_ireplace('{h:ddl_to}', $this->DrawDateTime('to_edit', $this->arrParameters['year'], $this->arrParameters['month'], $this->arrParameters['day'], $this->arrToday['hours'], false), $content);
			$content = str_ireplace('{h:lan_event_name}', $this->Lang('event_name'), $content);
            $content = str_ireplace('{h:lan_event_url}', $this->Lang('event_url'), $content);
			$content = str_ireplace('{h:lan_event_description}', $this->Lang('event_description'), $content);
			$content = str_ireplace('{h:lan_from}', $this->Lang('from'), $content);
			$content = str_ireplace('{h:lan_to}', $this->Lang('to'), $content); 
			$content = str_ireplace('{h:lan_edit_event}', $this->Lang('edit_event'), $content);
			$content = str_ireplace('{h:lan_close}', $this->Lang('close'), $content);
            $content = str_ireplace('{h:lan_category_name}', (($this->isCategoriesAllowed) ? $this->Lang('category_name').':' : ''), $content);
            $content = str_ireplace('{h:ddl_category_name}', (($this->isCategoriesAllowed) ? $this->DrawCategoriesDDL($this->arrParameters['selected_category'], '', '', 'sel_category_name', false, 'sel_category_name_edit') : ''), $content);
            $content = str_ireplace('{h:lan_location_name}', (($this->isLocationsAllowed) ? $this->Lang('location_name').':' : ''), $content);
			
			$h_delete_button = '';
            $h_update_button = '';
            if($this->arrEventsOperations['delete']){
                $h_delete_button = '<span id="divEditEvent_Delete"><a href="javascript:void(\'delete\')" onclick="phpCalendar.deleteEvent({h:val_id})">'.$this->Lang('delete').'</a></span>';
            }
            if($this->arrEventsOperations['edit']){
                $h_update_button = '<input class="form_button" type="button" name="btnSubmit" value="'.$this->Lang('update_event').'" onclick="javascript:phpCalendar.updateEvent();"/>';
            }
			$content = str_ireplace('{h:delete_button}', $h_delete_button, $content);
            $content = str_ireplace('{h:update_button}', $h_update_button, $content);
		}
        if($draw) echo $content.$this->crLt;
        else return $content.$this->crLt;
	}
    
	/**
	 * Draw event tooltips array
	 * @param $draw
	*/	
	private function DrawEventTooltips($draw = true)
	{
        $output = '';
		if(count($this->arrEventTooltips) > 0){
			$output .= '<script type="text/javascript">'.$this->crLt;
			foreach($this->arrEventTooltips as $key => $val){               
                $output .= 'GL_event_tooltips['.$key.'] = "'.$this->PrepareFormatedTextTooltips($val).'";'.$this->crLt;
			}			
			$output .= '</script>'.$this->crLt;			
		}
        if($draw) echo $output;
        else return $output;
    }    

	/**
	 * Draw events additing form
	*/	
	private function DrawEventsAddForm()
	{
		// draw Add Event Form from template
		$content = $this->FileGetContents('templates/events_add_form.tpl');

		$legend = '<legend class="cal_legend">';
		if($this->arrEventsOperations['add']){ $legend .= '<span class="active"><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_add\')">'.$this->Lang('add_event').'</a></span>'; }
		if($this->arrEventsOperations['manage']){ $legend .= '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_management\')">'.$this->Lang('events_management').'</a></span>'; }
		if($this->arrEventsOperations['delete_by_range']){ $legend .= '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_by_range\')">'.$this->Lang('delete_by_range').'</a></span>'; }
		$legend .= '&nbsp;</legend>';
		
		$content = str_ireplace('{h:legend}', $legend, $content);
		$content = str_ireplace('{h:lan_event_name}', $this->Lang('event_name'), $content);
        $content = str_ireplace('{h:lan_event_url}', $this->Lang('event_url'), $content);
		$content = str_ireplace('{h:lan_event_description}', $this->Lang('event_description'), $content);
		$content = str_ireplace('{h:lan_or}', $this->Lang('or'), $content);
		$content = str_ireplace('{h:lan_add_event}', '+ '.$this->Lang('add_new_event'), $content);
		$content = str_ireplace('{h:lan_cancel}', $this->Lang('cancel'), $content);
		$content = str_ireplace('{h:lan_from}', $this->Lang('from'), $content);
		$content = str_ireplace('{h:lan_to}', $this->Lang('to'), $content);
		$content = str_ireplace('{h:lan_or}', $this->Lang('or'), $content);
		$content = str_ireplace('{h:lan_add_event_to_list}', $this->Lang('lbl_add_event_to_list'), $content);
		$content = str_ireplace('{h:lan_add_event_occurrences}', $this->Lang('lbl_add_event_occurrences'), $content);
		$content = str_ireplace('{h:ddl_from}', $this->DrawDateTime('from', $this->arrToday['year'], $this->ConvertToDecimal($this->arrToday['mon']), $this->ConvertToDecimal($this->arrToday['mday']), $this->arrToday['hours'], false, true), $content);
		$content = str_ireplace('{h:ddl_to}', $this->DrawDateTime('to', $this->arrToday['year'], $this->ConvertToDecimal($this->arrToday['mon']), $this->ConvertToDecimal($this->arrToday['mday']), $this->arrToday['hours']+1, false, true), $content);
		
		$content = str_ireplace('{h:lan_repeats}', $this->Lang('repeats'), $content);
		$content = str_ireplace('{h:ddl_repeat_type}', $this->DrawRepeatTimeDDL(), $content);
		
		$content = str_ireplace('{h:lan_repeat_every}', $this->Lang('repeat_every'), $content);
		$content = str_ireplace('{h:ddl_repeat_every}', $this->DrawRepeatEveryDDL(), $content);
		$content = str_ireplace('{h:lan_weeks}', $this->Lang('weeks_word'), $content);
		$content = str_ireplace('{h:lan_months}', $this->Lang('months_word'), $content);		
		

		$content = str_ireplace('{h:lan_repeat_on}', $this->Lang('repeat_on'), $content);
		$content = str_ireplace('{h:lan_hours}', $this->Lang('hours'), $content);
		$content = str_ireplace('{h:lan_repeatedly}', $this->Lang('repeatedly'), $content);
		$content = str_ireplace('{h:lan_one_time}', $this->Lang('one_time'), $content);
		
		$content = str_ireplace('{h:lan_sun}', $this->Lang('sun'), $content);
		$content = str_ireplace('{h:lan_mon}', $this->Lang('mon'), $content);		
		$content = str_ireplace('{h:lan_tue}', $this->Lang('tue'), $content);		
		$content = str_ireplace('{h:lan_wed}', $this->Lang('wed'), $content);		
		$content = str_ireplace('{h:lan_thu}', $this->Lang('thu'), $content);
		$content = str_ireplace('{h:lan_fri}', $this->Lang('fri'), $content);
		$content = str_ireplace('{h:lan_sat}', $this->Lang('sat'), $content);				

		$content = str_ireplace('{h:ddl_repeat_on_weekday_num}', $this->DrawRepeatOnWeekdayNumDDL(), $content);
		$content = str_ireplace('{h:ddl_repeat_on_weekday}', $this->DrawRepeatOnWeekdayDDL(), $content);
		
		$content = str_ireplace('{h:ddl_from_date}', $this->DrawDate('from_date', $this->arrToday['year'], $this->ConvertToDecimal($this->arrToday['mon']), $this->ConvertToDecimal($this->arrToday['mday']), false), $content);
		$content = str_ireplace('{h:ddl_to_date}', $this->DrawDate('to_date', $this->arrToday['year'], $this->ConvertToDecimal($this->arrToday['mon']), $this->ConvertToDecimal($this->arrToday['mday']), false), $content);
		$content = str_ireplace('{h:ddl_from_time}', $this->DrawTime('from_time', $this->arrToday['hours'], false), $content);
		$content = str_ireplace('{h:ddl_to_time}', $this->DrawTime('to_time', $this->arrToday['hours']+1, false), $content);
		
		// display categories if allowed
		if($this->arrCatOperations['manage']){
			$content = str_ireplace('{h:lan_category_name}', $this->Lang('category_name').':<br />', $content);
			$content = str_ireplace('{h:ddl_categories}', $this->DrawCategoriesDDL($this->arrParameters['selected_category'], 'onchange="javascript:phpCalendar.categoryOnChange(this.value,'.$this->timeSlot.')"', ''), $content);		
		}else{
			$content = str_ireplace('{h:lan_category_name}', '', $content);
			$content = str_ireplace('{h:ddl_categories}', '', $content);					
		}
        
		// display locations if allowed
		if($this->arrLocOperations['manage']){
			$content = str_ireplace('{h:lan_location_name}', $this->Lang('location_name').':<br />', $content);
			$content = str_ireplace('{h:ddl_locations}', $this->DrawLocationsDDL('', '', ''), $content);		
		}else{
			$content = str_ireplace('{h:lan_location_name}', '', $content);
			$content = str_ireplace('{h:ddl_locations}', '', $content);					
		}        
		
        if($content !== false){
			echo $content;
            $this->arrInitJsFunction[] = 'phpCalendar.setFocus("event_name");';
            $this->InitWYSIWYG();
		}
	}	

	/**
	 * Draw events editing form
	 * @param $event_id
	*/	
	private function DrawEventsEditForm($event_id)
	{
		// draw Edit Event Form from template
		$content = $this->FileGetContents('templates/events_edit_form.tpl');			   			   

		$content = str_ireplace('{h:lan_event_name}', $this->Lang('event_name'), $content);
        $content = str_ireplace('{h:lan_event_url}', $this->Lang('event_url'), $content);
		$content = str_ireplace('{h:lan_event_description}', $this->Lang('event_description'), $content);
		$content = str_ireplace('{h:lan_or}', $this->Lang('or'), $content);
		$content = str_ireplace('{h:lan_cancel}', $this->Lang('cancel'), $content);
		$content = str_ireplace('{h:lan_edit_event}', $this->Lang('edit_event'), $content);
		$content = str_ireplace('{h:lan_update_event}', $this->Lang('update'), $content);
        
        if($content !== false){
			if(!$this->allowEventsMultipleOccurrences){
				$sql  = 'SELECT
							'.EVENTS_TABLE.'.id,
							'.EVENTS_TABLE.'.name,
                            '.EVENTS_TABLE.'.url,
							'.EVENTS_TABLE.'.description,
							'.EVENTS_TABLE.'.category_id,
                            '.EVENTS_TABLE.'.location_id, 
                            '.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.name' : '""').' as category_name,
                            '.(($this->isLocationsAllowed) ? EVENTS_LOCATIONS_TABLE.'.name' : '""').' as location_name,
							'.CALENDAR_TABLE.'.event_date, 
							'.CALENDAR_TABLE.'.event_time 
						FROM '.EVENTS_TABLE.'
                            '.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
                            '.(($this->isLocationsAllowed) ? 'LEFT OUTER JOIN '.EVENTS_LOCATIONS_TABLE.' ON '.EVENTS_TABLE.'.location_id = '.EVENTS_LOCATIONS_TABLE.'.id ' : '').'
							LEFT OUTER JOIN '.CALENDAR_TABLE.' ON '.EVENTS_TABLE.'.id = '.CALENDAR_TABLE.'.event_id ';
			}else{
				$sql  = 'SELECT
							'.EVENTS_TABLE.'.id,
							'.EVENTS_TABLE.'.name,
                            '.EVENTS_TABLE.'.url,
							'.EVENTS_TABLE.'.description,
                            '.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.name' : '""').' as category_name,
                            '.(($this->isLocationsAllowed) ? EVENTS_LOCATIONS_TABLE.'.name' : '""').' as location_name,
							'.EVENTS_TABLE.'.category_id,
                            '.EVENTS_TABLE.'.location_id
						FROM '.EVENTS_TABLE.'
                            '.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
                            '.(($this->isLocationsAllowed) ? 'LEFT OUTER JOIN '.EVENTS_LOCATIONS_TABLE.' ON '.EVENTS_TABLE.'.location_id = '.EVENTS_LOCATIONS_TABLE.'.id ' : '');
			}			
			$sql .= 'WHERE '.EVENTS_TABLE.'.id = '.(int)$event_id;
					
			$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve data of specific event for editing');
			if(!empty($result)){
				$content = str_ireplace('{h:event_id}', $result['id'], $content);
				$content = str_ireplace('{h:event_name}', $this->PrepareFormatedText($result['name']), $content);
                $content = str_ireplace('{h:event_url}', $this->PrepareFormatedText($result['url']), $content);
				$content = str_ireplace('{h:event_description}', $result['description'], $content);

				// display event datetime info, if allowed
				if(!$this->allowEventsMultipleOccurrences){
					$content = str_ireplace('{h:lan_event_date}', $this->Lang('event_date').':', $content);
					$content = str_ireplace('{h:lan_start_time}', $this->Lang('start_time').':', $content);
					$content = str_ireplace('{h:event_date}', $result['event_date'], $content);
					$content = str_ireplace('{h:event_time}', $result['event_time'], $content);
				}else{
					$arr_clean = array('{h:lan_event_date}','{h:lan_start_time}','{h:event_date}','{h:event_time}');
					$content = str_replace($arr_clean, '', $content);
				}

                $this->ReplaceHolders($content, '{h:lan_category_name}', $this->Lang('category_name').':<br />', $this->arrCatOperations['manage'], '');
                $this->ReplaceHolders($content, '{h:ddl_categories}', $this->DrawCategoriesDDL($result['category_id']), $this->arrCatOperations['manage'], '');
                $this->ReplaceHolders($content, '{h:lan_location_name}', $this->Lang('location_name').':<br />', $this->arrLocOperations['manage'], '');
                $this->ReplaceHolders($content, '{h:ddl_locations}', $this->DrawLocationsDDL($result['location_id']), $this->arrLocOperations['manage'], '');

				echo $content;
			}
			
			$this->InitWYSIWYG();
		}
	}	

	/**
	 * Draw events details form
	 * @param $event_id
	*/	
	private function DrawEventsDetailsForm($event_id)
	{
		// draw Events Details Form from template
		$content = $this->FileGetContents('templates/events_details_form.tpl');

		$content = str_ireplace('{h:lan_event_name}', $this->Lang('event_name'), $content);
        $content = str_ireplace('{h:lan_event_url}', $this->Lang('event_url'), $content);
		$content = str_ireplace('{h:lan_event_description}', $this->Lang('event_description'), $content);
		$content = str_ireplace('{h:lan_event_details}', $this->Lang('event_details'), $content);
		$content = str_ireplace('{h:lan_back}', $this->Lang('back'), $content);

        if($content !== false){
			if(!$this->allowEventsMultipleOccurrences){
				$sql  = 'SELECT
							'.EVENTS_TABLE.'.id,
							'.EVENTS_TABLE.'.name,
                            '.EVENTS_TABLE.'.url,
							'.EVENTS_TABLE.'.description,
							'.EVENTS_TABLE.'.category_id,
                            '.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.name' : '""').' as category_name,
                            '.(($this->isLocationsAllowed) ? EVENTS_LOCATIONS_TABLE.'.name' : '""').' as location_name,
							'.CALENDAR_TABLE.'.event_date, 
							'.CALENDAR_TABLE.'.event_time 
						FROM '.EVENTS_TABLE.'
                            '.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
                            '.(($this->isLocationsAllowed) ? 'LEFT OUTER JOIN '.EVENTS_LOCATIONS_TABLE.' ON '.EVENTS_TABLE.'.location_id = '.EVENTS_LOCATIONS_TABLE.'.id ' : '').'
							LEFT OUTER JOIN '.CALENDAR_TABLE.' ON '.EVENTS_TABLE.'.id = '.CALENDAR_TABLE.'.event_id ';
			}else{ 
				$sql  = 'SELECT
							'.EVENTS_TABLE.'.id,
							'.EVENTS_TABLE.'.name,
                            '.EVENTS_TABLE.'.url,
							'.EVENTS_TABLE.'.description,
                            '.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.name' : '""').' as category_name,
                            '.(($this->isLocationsAllowed) ? EVENTS_LOCATIONS_TABLE.'.name' : '""').' as location_name,
							'.EVENTS_TABLE.'.category_id
						FROM '.EVENTS_TABLE.'
                            '.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
                            '.(($this->isLocationsAllowed) ? 'LEFT OUTER JOIN '.EVENTS_LOCATIONS_TABLE.' ON '.EVENTS_TABLE.'.location_id = '.EVENTS_LOCATIONS_TABLE.'.id ' : '');
			}			
			$sql .= 'WHERE '.EVENTS_TABLE.'.id = '.(int)$event_id;
					
			$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve data of specific event for viewing details');
			if(!empty($result)){
				$content = str_ireplace('{h:event_name}', $result['name'], $content);
                $content = str_ireplace('{h:event_url}', preg_replace('/(?<=^.{30}).{4,}(?=.{15}$)/', '...', $result['url']), $content);
				$content = str_ireplace('{h:event_description}', $result['description'], $content);
				$content = str_ireplace('{h:js_back_function}', 'phpCalendar.eventsBack(\'events_management\')', $content);
				
				// display event datetime info, if allowed
				if(!$this->allowEventsMultipleOccurrences){					
					$content = str_ireplace('{h:lan_event_date}', $this->Lang('event_date').':', $content);
					$content = str_ireplace('{h:lan_start_time}', $this->Lang('start_time').':', $content);
					$content = str_ireplace('{h:event_date}', $result['event_date'], $content);
					$content = str_ireplace('{h:event_time}', $result['event_time'], $content);
				}else{
					$arr_clean = array('{h:lan_event_date}','{h:lan_start_time}','{h:event_date}','{h:event_time}');
					$content = str_replace($arr_clean, '', $content);
				}
                $this->ReplaceHolders($content, '{h:lan_category_name}', $this->Lang('category_name').':', $this->arrCatOperations['manage'], '');
                $this->ReplaceHolders($content, '{h:category_name}', $result['category_name'], $this->arrCatOperations['manage'], $this->Lang('not_defined'));
                $this->ReplaceHolders($content, '{h:lan_location_name}', $this->Lang('location_name').':', $this->arrLocOperations['manage'], '');
                $this->ReplaceHolders($content, '{h:location_name}', $result['location_name'], $this->arrLocOperations['manage'], $this->Lang('not_defined'));
			}
		}
		
		if($this->isParticipantsAllowed){
			$sql = 'SELECT
						'.EVENTS_PARTICIPANTS_TABLE.'.id as euid,
						'.PARTICIPANTS_TABLE.'.id,
						'.PARTICIPANTS_TABLE.'.first_name,
						'.PARTICIPANTS_TABLE.'.last_name,
						'.PARTICIPANTS_TABLE.'.email
					FROM '.EVENTS_PARTICIPANTS_TABLE.'
						INNER JOIN '.PARTICIPANTS_TABLE.' ON '.EVENTS_PARTICIPANTS_TABLE.'.participant_id = '.PARTICIPANTS_TABLE.'.id
					WHERE
						'.EVENTS_PARTICIPANTS_TABLE.'.event_id = '.(int)$event_id.'
					ORDER BY '.PARTICIPANTS_TABLE.'.last_name ASC';
					
			$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve data of participants, assigned to specific event');
			$assigned_participants_list = '';
			$temp_content = '';
			if($result[1] <= 0){
				$assigned_participants_list .= '<table align="center" border="0">';
				$assigned_participants_list .= '<tr valign="middle"><td><span class="cal_msg_error">'.$this->Lang('msg_no_participant_assigned').'</span></td></tr>';
				$assigned_participants_list .= '<tr><th nowrap="nowrap" height="10px"></th></tr>';
			}else{
				$assigned_participants_list .= '<table class="fieldset_content" align="center" border="0">';
				$assigned_participants_list .= '<tr><th colspan="4"></th></tr>
					  <tr>
						<th width="25px"></th>
						<th align="left">'.$this->Lang('first_name').'</th>
						<th align="left">'.$this->Lang('last_name').'</th>
						<th align="left">'.$this->Lang('email').'</th>
					  </tr>
					  <tr><th colspan="4" nowrap="nowrap" height="2px"></th></tr>';
				$event_participants_count = 0;				
				foreach($result[0] as $key => $val){
					$temp_content .= '<tr>
						<td align="right">'.++$event_participants_count.'.</td>
						<td align="left">'.$val['first_name'].'</td>
						<td align="left">'.$val['last_name'].'</td>
						<td align="left">'.$val['email'].'</td>
					</tr>';
				}            
			}
			$assigned_participants_list .= $temp_content;
			$assigned_participants_list .= '</table>';
			$content = str_ireplace('{h:assigned_participants_list}', $assigned_participants_list, $content);
		}else{
			$content = str_ireplace('{h:assigned_participants_list}', '', $content);
		}		
		
		echo $content;
	}	

	/**
	 * Draw events by range
	*/	
	private function DrawDeleteEventsByRange()
	{
		$content = $this->FileGetContents('templates/events_delete_by_range.tpl');
		
		$legend = '<legend class="cal_legend">';
		if($this->arrEventsOperations['add']){ $legend .= '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_add\')">'.$this->Lang('add_event').'</a></span>'; }
		if($this->arrEventsOperations['manage']){ $legend .= '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_management\')">'.$this->Lang('events_management').'</a></span>'; }
		if($this->arrEventsOperations['delete_by_range']){ $legend .= '<span class="active"><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_by_range\')">'.$this->Lang('delete_by_range').'</a></span>'; }
		$legend .= '&nbsp;</legend>';

		$content = str_ireplace('{h:legend}', $legend, $content);
        $this->ReplaceHolders($content, '{h:lan_category_name}', $this->Lang('category_name').':<br />', $this->isCategoriesAllowed, '');
        $this->ReplaceHolders($content, '{h:ddl_categories}', $this->DrawCategoriesDDL().'<br />', $this->isCategoriesAllowed, '');
        $this->ReplaceHolders($content, '{h:lan_location_name}', (($this->isLocationsAllowed) ? $this->Lang('location_name').':<br />' : ''), $this->isCategoriesAllowed, '');
        $this->ReplaceHolders($content, '{h:ddl_locations}', $this->DrawLocationsDDL(), $this->isLocationsAllowed, '');

		$content = str_ireplace('{h:lan_from}', $this->Lang('from'), $content);
		$content = str_ireplace('{h:lan_to}', $this->Lang('to'), $content); 
		$content = str_ireplace('{h:ddl_from}', $this->DrawDate('from', $this->arrToday['year'], $this->ConvertToDecimal($this->arrToday['mon']), $this->ConvertToDecimal($this->arrToday['mday']), false), $content);
		$content = str_ireplace('{h:ddl_to}', $this->DrawDate('to', $this->arrToday['year'], $this->ConvertToDecimal($this->arrToday['mon']), $this->ConvertToDecimal($this->arrToday['mday']), false), $content);
		
		$content = str_ireplace('{h:lan_or}', $this->Lang('or'), $content);
		$content = str_ireplace('{h:lan_cancel}', $this->Lang('cancel'), $content);
		$content = str_ireplace('{h:lan_delete_events}', $this->Lang('delete_events'), $content);
		
		echo $content;
	}

	/**
	 * Draw events management
	*/	
	private function DrawEventsManagement()
	{	
		$hid_sort_by = ($this->GetParameter('hid_sort_by') != '') ? $this->GetParameter('hid_sort_by') : 'name';
		$hid_sort_direction = ($this->GetParameter('hid_sort_direction') != '') ? $this->GetParameter('hid_sort_direction') : 'ASC';
		if(strtolower($hid_sort_direction) == 'asc') $next_sort_direction = 'desc';
		else $next_sort_direction = 'asc';

		$sql = 'SELECT COUNT(*) as cnt FROM '.EVENTS_TABLE.' WHERE 1=1 '.$this->PrepareWhereClauseParticipant();
		$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve amount of existing events');
		$total_records = $result['cnt'];
		$page_size = 20;
		$total_pages = (int)$result['cnt']/$page_size;
		$total_pages_partualy = $result['cnt'] % $page_size;
		if($total_pages_partualy != 0) $total_pages +=1;
		
		$limit_start = ($this->arrParameters['page']-1) * $page_size;
		if($limit_start < 0) $limit_start = '0';
		$limit_end = $page_size;
		
		if(!$this->allowEventsMultipleOccurrences){
			$sql  = 'SELECT
						'.EVENTS_TABLE.'.id,
						'.EVENTS_TABLE.'.name,
						'.EVENTS_TABLE.'.description,
						'.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.color' : '""').' as color, 
                        '.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.name' : '""').' as category_name,
                        '.(($this->isLocationsAllowed) ? EVENTS_LOCATIONS_TABLE.'.name' : '""').' as location_name,
						MIN('.CALENDAR_TABLE.'.event_date) as event_date,
						(SELECT c1.event_time FROM '.CALENDAR_TABLE.' c1 WHERE c1.event_id = '.EVENTS_TABLE.'.id AND c1.event_date = MIN('.CALENDAR_TABLE.'.event_date) ORDER BY c1.event_time ASC LIMIT 0,1) as event_time,
						(SELECT COUNT(*) FROM '.EVENTS_PARTICIPANTS_TABLE.' ep WHERE ep.event_id = '.EVENTS_TABLE.'.id) as participants_count
					FROM '.EVENTS_TABLE.'
						'.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
                        '.(($this->isLocationsAllowed) ? 'LEFT OUTER JOIN '.EVENTS_LOCATIONS_TABLE.' ON '.EVENTS_TABLE.'.location_id = '.EVENTS_LOCATIONS_TABLE.'.id ' : '').'
						LEFT OUTER JOIN '.CALENDAR_TABLE.' ON '.EVENTS_TABLE.'.id = '.CALENDAR_TABLE.'.event_id
					GROUP BY '.EVENTS_TABLE.'.id ';
		}else{
			$sql  = 'SELECT
						'.EVENTS_TABLE.'.id,
						'.EVENTS_TABLE.'.name,
						'.EVENTS_TABLE.'.description,
						'.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.color' : '""').' as color, 
                        '.(($this->isCategoriesAllowed) ? EVENTS_CATEGORIES_TABLE.'.name' : '""').' as category_name,
                        '.(($this->isLocationsAllowed) ? EVENTS_LOCATIONS_TABLE.'.name' : '""').' as location_name,
						(SELECT COUNT(*) FROM '.EVENTS_PARTICIPANTS_TABLE.' ep WHERE ep.event_id = '.EVENTS_TABLE.'.id) as participants_count
					FROM '.EVENTS_TABLE.'
                        '.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
                        '.(($this->isLocationsAllowed) ? 'LEFT OUTER JOIN '.EVENTS_LOCATIONS_TABLE.' ON '.EVENTS_TABLE.'.location_id = '.EVENTS_LOCATIONS_TABLE.'.id ' : '');
					
		}
		$sql .= ' WHERE 1=1 '.$this->PrepareWhereClauseParticipant();
		$sql .= ' ORDER BY '.$hid_sort_by.' '.$hid_sort_direction.' ';
		$sql .= ' LIMIT '.$limit_start.', '.$limit_end;
				
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve data of existing events - Events Management');
		$content = $this->FileGetContents('templates/events_management_row.tpl');			   			   
		
		echo '<fieldset class="cal_fieldset">';
		$legend = '<legend class="cal_legend">';
		if($this->arrEventsOperations['add']){ $legend .= '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_add\')">'.$this->Lang('add_event').'</a></span>'; }
		if($this->arrEventsOperations['manage']){ $legend .= '<span class="active"><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_management\')">'.$this->Lang('events_management').'</a></span>'; }
		if($this->arrEventsOperations['delete_by_range']){ $legend .= '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_by_range\')">'.$this->Lang('delete_by_range').'</a></span>'; }
		$legend .= '&nbsp;</legend>';
		echo $legend;

		$sort_image = ($next_sort_direction == 'asc') ? '<img src="'.$this->calDir.'images/down.png" alt="down" border="0" />' : '<img src="'.$this->calDir.'images/up.png" alt="up" border="0" />';
		echo '<table class="tbl_management" align="center" border="0" cellspacing="0" cellpadding="2" width="100%">
			  <tr><td colspan="8" nowrap="nowrap" height="3px"></td></tr>
			  <tr>
				<th width="15px"></th>
				<th class="cal_left"><a href="javascript:phpCalendar.eventsSort(\'name\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('event_name').'</a>'.(($hid_sort_by == 'name') ? ' '.$sort_image : '').'</th>';
				///<th align='left'>'.$this->Lang('event_description').'</th>				
				// display categories if allowed
				if($this->arrCatOperations['manage']){
					echo '<th class="cal_left"><a href="javascript:phpCalendar.eventsSort(\'category_name\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('category_name').'</a>'.(($hid_sort_by == 'category_name') ? ' '.$sort_image : '').'</th>';
				}else{
					echo '<th></th>';
				}
				// display locations if allowed
				if($this->arrLocOperations['manage']){
					echo '<th class="cal_left"><a href="javascript:phpCalendar.eventsSort(\'location_name\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('location_name').'</a>'.(($hid_sort_by == 'location_name') ? ' '.$sort_image : '').'</th>';
				}else{
					echo '<th></th>';
				}
				// show event's datetime info, if allowed
				if(!$this->allowEventsMultipleOccurrences){
					echo '<th align="center" width="90px"><a href="javascript:phpCalendar.eventsSort(\'event_date\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('event_date').'</a>'.(($hid_sort_by == 'event_date') ? ' '.$sort_image : '').'</th>';
					echo '<th align="center" width="90px"><a href="javascript:phpCalendar.eventsSort(\'event_time\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('start_time').'</a>'.(($hid_sort_by == 'event_time') ? ' '.$sort_image : '').'</th>';
				}else{
					echo '<th></th>';
					echo '<th></th>';
				}
				echo '<th align="center" width="120px"></th>';
				echo '<th align="center" width="140px">'.((!$this->arrEventsOperations['edit'] && !$this->arrEventsOperations['details'] && !$this->arrEventsOperations['delete']) ? '' : $this->Lang('actions')).'</th>
			  </tr>
			  <tr><td colspan="8" nowrap="nowrap" height="2px"></td></tr>';
		$events_count = 0;
		foreach($result[0] as $key => $val){
			$temp_content = $content;
			$events_count++;
			
			$drawed_buttons = 0;
			$edit_button = '';
			$details_button = '';
			$delete_button = '';
			if($this->arrEventsOperations['details']){ $details_button = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:phpCalendar.eventsDetails({h:event_id});">'.$this->Lang('details').'</a>'; $drawed_buttons++; }
			if($this->arrEventsOperations['edit'])   { $edit_button    = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:phpCalendar.eventsEdit({h:event_id});">'.$this->Lang('edit').'</a>'; $drawed_buttons++; }			
			if($this->arrEventsOperations['delete']) { $delete_button  = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:void(0);" onclick="phpCalendar.eventsDelete({h:event_id});">'.$this->Lang('delete').'</a>'; $drawed_buttons++; }

			$temp_content = str_ireplace('{h:edit_button}', $edit_button, $temp_content); 
			$temp_content = str_ireplace('{h:details_button}', $details_button, $temp_content);
			$temp_content = str_ireplace('{h:delete_button}', $delete_button, $temp_content);
			
			$description  = $this->SubString($val['description'], 50);
			$temp_content = str_ireplace('{h:event_num}', $limit_start+$events_count.'. ', $temp_content);
			$temp_content = str_ireplace('{h:event_name}', '<span style="color:'.$val['color'].'">'.$val['name'].'</span>', $temp_content);
			///$temp_content = str_ireplace('{h:event_description}', $description, $temp_content);
            $this->ReplaceHolders($temp_content, '{h:event_category}', $val['category_name'], $this->arrCatOperations['manage'], '');
            $this->ReplaceHolders($temp_content, '{h:event_location}', $val['location_name'], $this->arrLocOperations['manage'], '');

			// show event's datetime info, if allowed
			if(!$this->allowEventsMultipleOccurrences){
				$temp_content = str_ireplace('{h:event_date}', $val['event_date'], $temp_content);
				$temp_content = str_ireplace('{h:event_time}', $val['event_time'], $temp_content);
			}else{
				$temp_content = str_ireplace(array('{h:event_date}','{h:event_time}'), '', $temp_content);
			}
			
            // occurrences management
            $occurrences_link = '<span class="cal_gray">[</span> <a href="javascript:void(0);" onclick="phpCalendar.showEventOccurrences({h:event_id});">'.$this->Lang('occurrences').'</a> <span class="cal_gray">]</span> ';
            $temp_content = str_ireplace('{h:occurrences_link}', $occurrences_link, $temp_content);
            
			if($this->isParticipantsAllowed){
                if($this->arrParticipantsOperations['assign_to_events']){
                    $temp_content = str_ireplace('{h:participants_link}', '<span class="cal_gray">[</span> <a href="javascript:void(0);" onclick="phpCalendar.eventsParticipantsManagement({h:event_id});">'.$this->Lang('participants').'</a> <span class="cal_gray">]</span> <span class="cal_gray">('.$val['participants_count'].')</span>', $temp_content);
                }else{
                    $temp_content = str_ireplace('{h:participants_link}', '', $temp_content);
                }
			}else{
				$temp_content = str_ireplace('{h:participants_link}', '', $temp_content);				
			}
			
			$temp_content = str_ireplace('{h:event_id}', $val['id'], $temp_content);
            echo $temp_content;
		}
		echo '<tr><td colspan="8" style="border-bottom:1px solid #eeeeee;">&nbsp;</td></tr>';		
		echo '<tr>';
		echo '<td class="cal_left" style="padding-left:10px" colspan="7">'.$this->Lang('pages').': ';
				for($i_pages = 1; $i_pages <= $total_pages; $i_pages++){
					if($i_pages > 1) echo ', ';
					$i_pages_a = ($i_pages == $this->arrParameters['page']) ? '<strong>'.$i_pages.'</strong>' : $i_pages;
					echo '<a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_management\',\'\',\''.$i_pages.'\');">'.$i_pages_a.'</a>';
				}
		echo '</td>';
		echo '<td class="cal_right" style="padding-right:10px">'.$this->Lang('total_events').': '.$total_records.'</td>';
		echo '</tr>';		
		echo '</table>';
		echo '</fieldset>';
	}	
	
	/**
	 * Draw events participants management
	 * @param $event_id
	*/	
	private function DrawEventsParticipantsManagement($event_id)
	{
        // check if assigning participants to events allowed
        if(!$this->arrParticipantsOperations['assign_to_events']) return '';
        
		$content = $this->FileGetContents('templates/events_participants_management.tpl');
		$colspan = '5';

        // get event name
        $sql  = 'SELECT name FROM '.EVENTS_TABLE.' WHERE id = '.(int)$event_id;
        $result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve name of name');
		$event_name = isset($result['name']) ? $result['name'] : $event_id;
		
        // select participants, that were not assigned to this event				
        $sql  = 'SELECT
                    '.PARTICIPANTS_TABLE.'.id,
                    '.PARTICIPANTS_TABLE.'.first_name,
                    '.PARTICIPANTS_TABLE.'.last_name,
                    '.PARTICIPANTS_TABLE.'.email
                FROM '.PARTICIPANTS_TABLE.'
                WHERE '.PARTICIPANTS_TABLE.'.id NOT IN (SELECT ep.participant_id FROM '.EVENTS_PARTICIPANTS_TABLE.' ep WHERE ep.event_id = '.(int)$event_id.') 
                ORDER BY '.PARTICIPANTS_TABLE.'.last_name ASC';
        $result_assigned = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve events that still not assigned to specific participant');

		echo '<fieldset class="cal_fieldset">
			  <legend class="cal_legend"><span class="single">'.$this->Lang('manage_participants_for_event').': '.$event_name.'</span></legend>';	

		$sql = 'SELECT
					'.EVENTS_PARTICIPANTS_TABLE.'.id as euid,
                    '.PARTICIPANTS_TABLE.'.id,
                    '.PARTICIPANTS_TABLE.'.first_name,
                    '.PARTICIPANTS_TABLE.'.last_name,
                    '.PARTICIPANTS_TABLE.'.email
                FROM '.EVENTS_PARTICIPANTS_TABLE.'
                    INNER JOIN '.PARTICIPANTS_TABLE.' ON '.EVENTS_PARTICIPANTS_TABLE.'.participant_id = '.PARTICIPANTS_TABLE.'.id
                WHERE
                    '.EVENTS_PARTICIPANTS_TABLE.'.event_id = '.(int)$event_id.'
                ORDER BY '.PARTICIPANTS_TABLE.'.last_name ASC';
				
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve data of participants, assigned to specific event');
	    
        if($result[1] <= 0){
            echo '<table align="center" width="100%" border="0">';
            echo '<tr valign="middle"><td><span class="cal_msg_error">'.$this->Lang('msg_no_participant_assigned').'</span></td></tr>';
			echo '<tr><th nowrap="nowrap" height="10px"></th></tr>';
        }else{
			if($result_assigned[1] <= 0){
				echo '<table align="center" width="100%" border="0">
					  <tr valign="top"><td><span class="cal_msg_success">'.$this->Lang('msg_all_participants_assigned').'</span></td></tr>
					  </table>';
			}
            echo '<table class="fieldset_content tbl_management" align="center" border="0" cellspacing="0" cellpadding="2">';
            echo '<tr><th colspan="'.$colspan.'"></th></tr>
                  <tr>
                    <th width="25px"></th>
                    <th class="cal_left">'.$this->Lang('first_name').'</th>
                    <th class="cal_left">'.$this->Lang('last_name').'</th>
                    <th class="cal_left">'.$this->Lang('email').'</th>
                    <th align="center">'.$this->Lang('actions').'</th>
                  </tr>
                  <tr><th colspan="'.$colspan.'" nowrap="nowrap" height="2px"></th></tr>';
            $event_participants_count = 0;
            foreach($result[0] as $key => $val){
                $temp_content = $content;
                $event_participants_count++;
                
                $temp_content = str_ireplace('{h:participant_num}', $event_participants_count.'. ', $temp_content);
                $temp_content = str_ireplace('{h:participant_first_name}', $val['first_name'], $temp_content);
                $temp_content = str_ireplace('{h:participant_last_name}', $val['last_name'], $temp_content);
                $temp_content = str_ireplace('{h:participant_email}', $val['email'], $temp_content);
				$temp_content = str_ireplace('{h:delete_button}', '<a href="javascript:void(0);" onclick="javascript:phpCalendar.eventsParticipantDelete('.$event_id.','.$val['euid'].');">'.$this->Lang('delete').'</a>', $temp_content);
                
                echo $temp_content;			
            }            
        }
		echo '</table>';
		
        if($result_assigned[1] > 0){
            echo '<table class="fieldset_content" align="center" border="0">
            <tr valign="middle">
                <td align="center">
                    '.$this->Lang('select_participant').':&nbsp; 
                    <select name="assigned_participant_id" id="assigned_participant_id">
                        <option value="">-- '.$this->Lang('select').' --</option>';	
                        foreach($result_assigned[0] as $key => $val){
                            echo '<option value="'.$val['id'].'">'.$val['first_name'].' '.$val['last_name'].'</option>';
                        }                        
                    echo '
                    </select>
                </td>
            </tr>
            <tr><td align="center" colspan="3" style="height:30px;padding:0px;"><div id="divEventParticipantAssign_msg"></div></td></tr>
            <tr>
                <td colspan="3" align="center">
                    <input class="form_button" type="button" name="btnSubmit" value="+ '.$this->Lang('add_participant').'" onclick="javascript:phpCalendar.eventsAssignParticipant(\''.$event_id.'\');"/>
                    &nbsp;- '.$this->Lang('or').' -&nbsp;
                    <a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:phpCalendar.eventsCancel();">'.$this->Lang('cancel').'</a>
                </td>
            </tr>
            </table>';            
        }else{
            echo '<table class="fieldset_content" align="center" border="0">
                  <tr><td align="center"><a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:phpCalendar.eventsCancel();">'.$this->Lang('back').'</a></td></tr>
                  </table>';            
        }
		echo '</fieldset>';		
	}	
	
	/**
	 * Draw events occurrences
	 * @param $event_id
	*/	
	private function DrawEventsOccurrencesManagement($event_id)
	{
		$content = $this->FileGetContents('templates/events_occurencies_management.tpl');

		$sql = 'SELECT					
            '.CALENDAR_TABLE.'.id,
            '.CALENDAR_TABLE.'.event_id,
            '.CALENDAR_TABLE.'.event_date,
            '.CALENDAR_TABLE.'.event_time,
            '.CALENDAR_TABLE.'.slot,
            DATE_FORMAT('.CALENDAR_TABLE.'.event_time, "%H:%i") as event_time_formatted,
            '.EVENTS_TABLE.'.category_id,
            '.EVENTS_TABLE.'.name,
            '.EVENTS_TABLE.'.description,
            (SELECT CONCAT(c.event_date, "##", c.event_time) FROM '.CALENDAR_TABLE.' c WHERE c.unique_key = '.CALENDAR_TABLE.'.unique_key ORDER BY c.id DESC LIMIT 0, 1) as last_slot
        FROM '.CALENDAR_TABLE.'
            INNER JOIN '.EVENTS_TABLE.' ON '.CALENDAR_TABLE.'.event_id = '.EVENTS_TABLE.'.id
        WHERE
            '.EVENTS_TABLE.'.id = '.(int)$event_id.' AND
            '.CALENDAR_TABLE.'.slot = 1
        ORDER BY '.CALENDAR_TABLE.'.event_date ASC, '.CALENDAR_TABLE.'.event_time ASC';
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve data of event occurrences');
                
        echo '<fieldset class="cal_fieldset">';
        if($result[1] <= 0){
            echo '<legend class="cal_legend"><span class="single">'.$this->Lang('event_occurrences').'</span></legend>';	
            echo '<table align="center" width="100%" border="0">';
            echo '<tr valign="middle"><td><span class="cal_msg_error">'.$this->Lang('msg_no_occurrences_found').'</span></td></tr>';
			echo '<tr><th nowrap="nowrap" height="10px"></th></tr>';
            echo '</table>';
        }else{
            $event_name = (isset($result[0][0]['name'])) ? $result[0][0]['name'] : '';
            
            echo '<legend class="cal_legend"><span class="single">'.$this->Lang('occurrences_of_event').': '.$event_name.'</span></legend>';	
            echo '<table class="fieldset_content tbl_management" align="center" border="0" cellspacing="0" cellpadding="2">';
            echo '<tr><th></th></tr>
                  <tr>
                    <th width="25px"></th>
                    <th class="cal_left" width="100px">'.$this->Lang('From').'</th>
                    <th class="cal_left" width="100px"></th>
                    <th class="cal_left" width="100px">'.$this->Lang('To').'</th>
                    <th class="cal_left"></th>
                    <th class="cal_right">'.(($this->arrEventsOperations['delete']) ? $this->Lang('actions') : '&nbsp;').'</th>
                  </tr>
                  <tr><th colspan="6" nowrap="nowrap" height="2px"></th></tr>';
            $event_occurrences_count = 0;
            foreach($result[0] as $key => $val){
                $temp_content = $content;
                $event_occurrences_count++;

                $last_slot_parts = explode('##', $val['last_slot']);
                $event_date_to = isset($last_slot_parts[0]) ? $last_slot_parts[0] : '';
                $event_time_to = isset($last_slot_parts[1]) ? $this->AddSlot($last_slot_parts[1]) : '';

                if($this->dateFormat == 'dd/mm/yyyy'){
                    $event_date_from = date('d M, Y', strtotime($val['event_date']));
                    $event_date_to = date('d M, Y', strtotime($event_date_to));
                }else if($this->dateFormat == 'mm/dd/yyyy'){                    
                    $event_date_from = date('M d, Y', strtotime($val['event_date']));
                    $event_date_to = date('M d, Y', strtotime($event_date_to));
                }else{
                    $event_date_from = date('Y M, d', strtotime($val['event_date']));
                    $event_date_to = date('Y M, d', strtotime($event_date_to));
                }
                $event_time_from = $this->ConvertToHour($this->ParseHour($val['event_time']), $this->ParseMinutes($val['event_time']), true);
                $event_time_to = $this->ConvertToHour($this->ParseHour($event_time_to), $this->ParseMinutes($event_time_to), true);
                
                $temp_content = str_ireplace('{h:occurrence_num}', $event_occurrences_count.'. ', $temp_content);
                $temp_content = str_ireplace('{h:occurrence_date_from}', $event_date_from, $temp_content);
                $temp_content = str_ireplace('{h:occurrence_time_from}', $event_time_from, $temp_content);
                $temp_content = str_ireplace('{h:occurrence_date_to}', $event_date_to, $temp_content);
                $temp_content = str_ireplace('{h:occurrence_time_to}', $event_time_to, $temp_content);
                $delete_button = ($this->arrEventsOperations['delete']) ? '<a href="javascript:void(0);" onclick="javascript:phpCalendar.deleteEventOccurences('.$val['id'].');">'.$this->Lang('delete').'</a>' : '';
                $temp_content = str_ireplace('{h:delete_button}', $delete_button, $temp_content);
                echo $temp_content;			
            }            
            echo '</table>';
        }
        echo '<table class="fieldset_content" align="center" border="0">';
        echo '<tr><td align="center"><a class="form_cancel_link" name="lnkCancel" href="javascript:void(0);" onclick="javascript:phpCalendar.occurrencesCancel();">'.$this->Lang('back').'</a></td></tr>';
        echo '</table>';
		echo '</fieldset>';		
	}	

	////////////////////////////////////////////////////////////////////////////
	// CATEGORIES

	/**
	 * Draw categories additing form
	*/	
	private function DrawCategoriesAddForm()
	{
		// draw categories add form from template
		$content = $this->FileGetContents('templates/categories_add_form.tpl');
		
		$legend  = '<legend class="cal_legend">';
		$legend .= '<span class="active"><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'categories_add\')">'.$this->Lang('add_category').'</a></span>';
		$legend .= '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'categories_management\')">'.$this->Lang('manage_categories').'</a></span>';
		$legend .= '&nbsp;</legend>';
	
		$content = str_ireplace('{h:legend}', $legend, $content);		
		$content = str_ireplace('{h:lan_cat_name}', $this->Lang('category_name'), $content);		
		$content = str_ireplace('{h:lan_cat_description}', $this->Lang('category_description'), $content);
		$content = str_ireplace('{h:lan_add_category}', '+ '.$this->Lang('add_new_category'), $content);
		$content = str_ireplace('{h:lan_cancel}', $this->Lang('cancel'), $content);
		$content = str_ireplace('{h:lan_or}', $this->Lang('or'), $content);
		$content = str_ireplace('{h:lan_duration}', $this->Lang('duration'), $content);
        $content = str_ireplace('{h:lan_show_in_filter}', $this->Lang('show_in_filter'), $content);

		$content = str_ireplace('{h:ddl_durations}', $this->DrawCategoryDurations('', true), $content);
		
		if($this->arrCatOperations['allow_colors']){
			$content = str_ireplace('{h:lan_cat_color}', $this->Lang('category_color').':', $content);
			$content = str_ireplace('{h:ddl_colors}', $this->DrawColors('', false, true), $content);
		}else{
			$content = str_ireplace('{h:lan_cat_color}', '', $content);
			$content = str_ireplace('{h:ddl_colors}', '', $content);
		}
        $content = str_ireplace('{h:chk_show_in_filter}', $this->DrawShowInFilter('1', false), $content);
		
        if($content !== false){
			echo $content;
            $this->arrInitJsFunction[] = 'phpCalendar.setFocus("category_name");';
		}        
	}	

	/**
	 * Draw categories edit form
	 * @param $category_id
	*/	
	private function DrawCategoriesEditForm($category_id)
	{
		// draw categories edit form from template
		$content = $this->FileGetContents('templates/categories_edit_form.tpl');

		$content = str_ireplace('{h:lan_edit_category}', $this->Lang('edit_category'), $content);
		$content = str_ireplace('{h:lan_category_name}', $this->Lang('category_name'), $content);		
		$content = str_ireplace('{h:lan_category_description}', $this->Lang('category_description'), $content);
		$content = str_ireplace('{h:lan_duration}', $this->Lang('duration'), $content);
        $content = str_ireplace('{h:lan_show_in_filter}', $this->Lang('show_in_filter'), $content);
		
		$content = str_ireplace('{h:lan_or}', $this->Lang('or'), $content);
		$content = str_ireplace('{h:lan_cancel}', $this->Lang('cancel'), $content);
		$content = str_ireplace('{h:lan_update_category}', $this->Lang('update'), $content);
		
        if($content !== false){
			$sql = 'SELECT id, name, description, color, duration, show_in_filter
					FROM '.EVENTS_CATEGORIES_TABLE.'
					WHERE id = '.(int)$category_id;
					
			$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve data of category for editing');
			if(!empty($result)){
				$content = str_ireplace('{h:cat_name}', $result['name'], $content);
				$content = str_ireplace('{h:cat_description}', $result['description'], $content);
				$content = str_ireplace('{h:category_id}', $result['id'], $content);
				$content = str_ireplace('{h:ddl_durations}', $this->DrawCategoryDurations($result['duration'], true), $content);
                $content = str_ireplace('{h:chk_show_in_filter}', $this->DrawShowInFilter($result['show_in_filter'], false), $content);

				if($this->arrCatOperations['allow_colors']){
					$content = str_ireplace('{h:lan_category_color}', $this->Lang('category_color').':', $content);
					$content = str_ireplace('{h:ddl_colors}', $this->DrawColors($result['color'], false, true), $content);
				}else{
					$content = str_ireplace('{h:lan_category_color}', '', $content);
					$content = str_ireplace('{h:ddl_colors}', '', $content);
				}
				echo $content;
                $this->arrInitJsFunction[] = 'phpCalendar.setFocus("category_name");';                
			}
		}
	}	

	/**
	 * Draw categories details form
	 * @param $category_id
	*/	
	private function DrawCategoriesDetailsForm($category_id)
	{
        $show_in_filter_array = array('0'=>'<span class=cal_no>'.$this->Lang('No').'</span>', '1'=>'<span class=cal_yes>'.$this->Lang('Yes').'</span>');
        
		// draw categories details form from template
		$content = $this->FileGetContents('templates/categories_details_form.tpl');

		$content = str_ireplace('{h:lan_category_name}', $this->Lang('category_name'), $content);
		$content = str_ireplace('{h:lan_category_description}', $this->Lang('category_description'), $content);
		$content = str_ireplace('{h:lan_category_details}', $this->Lang('category_details'), $content);
		$content = str_ireplace('{h:lan_back}', $this->Lang('back'), $content);
		$content = str_ireplace('{h:lan_duration}', $this->Lang('duration'), $content);
        $content = str_ireplace('{h:lan_show_in_filter}', $this->Lang('show_in_filter'), $content);
		
        if($content !== false){
			$sql = 'SELECT id, name, description, color, duration, show_in_filter
					FROM '.EVENTS_CATEGORIES_TABLE.'
					WHERE id = '.(int)$category_id;
					
			$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve data of category for viewing details');
			if(!empty($result)){
				$content = str_ireplace('{h:category_name}', $result['name'], $content);
				$content = str_ireplace('{h:category_description}', $result['description'], $content);
				$content = str_ireplace('{h:js_back_function}', 'phpCalendar.categoriesBack()', $content);
				$content = str_ireplace('{h:ddl_durations}', $this->DrawCategoryDurations($result['duration'], false), $content);
                $content = str_ireplace('{h:lbl_show_in_filter}', $show_in_filter_array[$result['show_in_filter']], $content);

				if($this->arrCatOperations['allow_colors']){
					$content = str_ireplace('{h:lan_cat_color}', $this->Lang('category_color').':', $content);					
					$content = str_ireplace('{h:ddl_colors}', $this->DrawColors($result['color'], false, false), $content);					
				}else{
					$content = str_ireplace('{h:lan_cat_color}', '', $content);
					$content = str_ireplace('{h:ddl_colors}', '', $content);
				}
				echo $content;
			}
		}
	}	

	/**
	 * Draw events categories management
	*/	
	private function DrawEventsCategoriesManagement()
	{
		$colspan = 5;
		
		$hid_sort_by = ($this->GetParameter('hid_sort_by') != '') ? $this->GetParameter('hid_sort_by') : 'name';
		$hid_sort_direction = ($this->GetParameter('hid_sort_direction') != '') ? $this->GetParameter('hid_sort_direction') : 'ASC';
		if(strtolower($hid_sort_direction) == 'asc') $next_sort_direction = 'desc';
		else $next_sort_direction = 'asc';
        $show_in_filter_array = array('0'=>'<span class=cal_no>'.$this->Lang('No').'</span>', '1'=>'<span class=cal_yes>'.$this->Lang('Yes').'</span>');

		$sql = 'SELECT COUNT(*) as cnt FROM '.EVENTS_CATEGORIES_TABLE;
		$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve amount of existing categories');
		$total_records = $result['cnt'];
		$page_size = 20;
		$total_pages = (int)$result['cnt']/$page_size;
		$total_pages_partualy = $result['cnt'] % $page_size;
		if($total_pages_partualy != 0) $total_pages +=1;
		
		$limit_start = ($this->arrParameters['page']-1) * $page_size;
		if($limit_start < 0) $limit_start = '0';
		$limit_end = $page_size;
		
		$sql  = 'SELECT id, name, description, color, duration, show_in_filter ';
		$sql .= 'FROM '.EVENTS_CATEGORIES_TABLE.' ';
		$sql .= 'ORDER BY '.$hid_sort_by.' '.$hid_sort_direction.' ';
		$sql .= 'LIMIT '.$limit_start.', '.$limit_end;
				
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve existing categories');
		$content = $this->FileGetContents('templates/categories_management_row.tpl');			   			   

		echo '<fieldset class="cal_fieldset">';
		if($this->arrCatOperations['add'] || $this->arrCatOperations['edit']) echo '<legend class="cal_legend">';
        if($this->arrCatOperations['add']) echo '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'categories_add\')">'.$this->Lang('add_category').'</a></span>';
		if($this->arrCatOperations['edit']) echo '<span class="active"><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'categories_management\')">'.$this->Lang('manage_categories').'</a></span>';
		if($this->arrCatOperations['add'] || $this->arrCatOperations['edit']) echo '&nbsp;</legend>';

		$sort_image = ($next_sort_direction == 'asc') ? '<img src="'.$this->calDir.'images/down.png" alt="down" border="0" />' : '<img src="'.$this->calDir.'images/up.png" alt="up" border="0" />';
		echo '<table class="tbl_management" align="center" border="0" cellspacing="0" cellpadding="2" width="100%">
			  <tr><th colspan="'.($colspan+1).'"></th></tr>
			  <tr>
				<th width="25px"></th>
				<th class="cal_left"><a href="javascript:phpCalendar.categoriesSort(\'name\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('category_name').'</a>'.(($hid_sort_by == 'name') ? ' '.$sort_image : '').'</th>
				<th class="cal_left"'.(($this->arrCatOperations['allow_colors']) ? ' width="130px"><a href="javascript:phpCalendar.categoriesSort(\'color\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('category_color') : ' width="1px">').'</a>'.(($hid_sort_by == 'color') ? ' '.$sort_image : '').'</th>
				<th class="cal_left" width="90px"><a href="javascript:phpCalendar.categoriesSort(\'duration\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('duration').'</a>'.(($hid_sort_by == 'duration') ? ' '.$sort_image : '').'</th>
                <th align="center" width="90px"><a href="javascript:phpCalendar.categoriesSort(\'show_in_filter\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('show_in_filter').'</a>'.(($hid_sort_by == 'show_in_filter') ? ' '.$sort_image : '').'</th>
				<th align="center"'.((!$this->arrCatOperations['edit'] && !$this->arrCatOperations['details'] && !$this->arrCatOperations['delete']) ? ' width="1px"' : ' width="140px">'.$this->Lang('actions')).'</th>
			  </tr>
			  <tr><th colspan="'.($colspan+1).'" nowrap="nowrap" height="2px"></th></tr>';
		///<th align='left'>".$this->Lang('category_description')."</th>

		$categories_count = 0;
		foreach($result[0] as $key => $val){
			$temp_content = $content;
			$categories_count++;
			
			$drawed_buttons = 0;
			$edit_button = '';
			$details_button = '';
			$delete_button = '';
			if($this->arrCatOperations['details']){ $details_button = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:phpCalendar.categoriesDetails({h:category_id});">'.$this->Lang('details').'</a>'; $drawed_buttons++; }
			if($this->arrCatOperations['edit'])   { $edit_button    = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:phpCalendar.categoriesEdit({h:category_id});">'.$this->Lang('edit').'</a>'; $drawed_buttons++; }			
			if($this->arrCatOperations['delete']) { $delete_button  = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:void(0);" onclick="javascript:phpCalendar.categoriesDelete({h:category_id},\''.($this->removeCategoryEvents ? '1' : '0').'\');">'.$this->Lang('delete').'</a>'; $drawed_buttons++; }

			$temp_content = str_ireplace('{h:edit_button}', $edit_button, $temp_content); 
			$temp_content = str_ireplace('{h:details_button}', $details_button, $temp_content);
			$temp_content = str_ireplace('{h:delete_button}', $delete_button, $temp_content);
			
			$temp_content = str_ireplace('{h:category_id}', $val['id'], $temp_content);
			$temp_content = str_ireplace('{h:category_num}', $limit_start+$categories_count.'. ', $temp_content);
			$temp_content = str_ireplace('{h:category_name}', $val['name'], $temp_content);
			///$description = (strlen($val['description']) > 50) ? substr($val['description'], 0, 50).'...' : $val['description'];
			///$temp_content = str_ireplace('{h:category_description}', $description, $temp_content);
			if($this->arrCatOperations['allow_colors']){
				$temp_content = str_ireplace('{h:category_color}', $this->DrawColors($val['color'], false, false), $temp_content);				
			}else{
				$temp_content = str_ireplace('{h:category_color}', '', $temp_content);
			}
			$temp_content = str_ireplace('{h:category_duration}', (($val['duration'] != '') ? $val['duration'].' '.$this->Lang('min').'.' : '<span class="cal_gray">- '.$this->Lang('not_defined').' -</span>'), $temp_content);
            $temp_content = str_ireplace('{h:category_show_in_filter}', $show_in_filter_array[$val['show_in_filter']], $temp_content);
            echo $temp_content;
		}
		echo '<tr><td colspan="'.($colspan+1).'" style="border-bottom:1px solid #eeeeee;">&nbsp;</td></tr>';		
		echo '<tr>';
		if(!$this->arrCatOperations['edit'] && !$this->arrCatOperations['details'] && !$this->arrCatOperations['delete']) $colspan = 3;
		echo '<td class="cal_left" style="padding-left:10px" colspan="'.$colspan.'">'.$this->Lang('pages').': ';
				for($i_pages = 1; $i_pages <= $total_pages; $i_pages++){
					if($i_pages > 1) echo ', ';
					$i_pages_a = ($i_pages == $this->arrParameters['page']) ? '<strong>'.$i_pages.'</strong>' : $i_pages;
					echo '<a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'categories_management\',\'\',\''.$i_pages.'\');">'.$i_pages_a.'</a>';
				}
		echo '</td>';
		echo '<td class="cal_right" style="padding-right:10px">'.$this->Lang('total_categories').': '.$total_records.'</td>';
		echo '</tr>';		
		echo '</table>';
		echo '</fieldset>';
	}	
	
	////////////////////////////////////////////////////////////////////////////
	// LOCATIONS

	/**
	 * Draw locations additing form
	*/	
	private function DrawLocationsAddForm()
	{
		// draw add location form from template
		$content = $this->FileGetContents('templates/locations_add_form.tpl');
		
		$legend  = '<legend class="cal_legend">';
		$legend .= '<span class="active"><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'locations_add\')">'.$this->Lang('add_location').'</a></span>';
		$legend .= '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'locations_management\')">'.$this->Lang('manage_locations').'</a></span>';
		$legend .= '&nbsp;</legend>';
	
		$content = str_ireplace('{h:legend}', $legend, $content);		
		$content = str_ireplace('{h:lan_loc_name}', $this->Lang('location_name'), $content);		
		$content = str_ireplace('{h:lan_loc_description}', $this->Lang('location_description'), $content);
		$content = str_ireplace('{h:lan_add_location}', '+ '.$this->Lang('add_new_location'), $content);
		$content = str_ireplace('{h:lan_cancel}', $this->Lang('cancel'), $content);
		$content = str_ireplace('{h:lan_or}', $this->Lang('or'), $content);
		$content = str_ireplace('{h:lan_duration}', $this->Lang('duration'), $content);
        $content = str_ireplace('{h:lan_show_in_filter}', $this->Lang('show_in_filter'), $content);

        $content = str_ireplace('{h:chk_show_in_filter}', $this->DrawShowInFilter('1', false), $content);

        if($content !== false){
			echo $content;
            $this->arrInitJsFunction[] = 'phpCalendar.setFocus("location_name");';
		}
	}  
	
	/**
	 * Draw locations edit form
	 * @param $location_id
	*/	
	private function DrawLocationsEditForm($location_id)
	{
		// draw locations edit form from template
		$content = $this->FileGetContents('templates/locations_edit_form.tpl');

		$content = str_ireplace('{h:lan_edit_location}', $this->Lang('edit_location'), $content);
		$content = str_ireplace('{h:lan_loc_name}', $this->Lang('location_name'), $content);		
		$content = str_ireplace('{h:lan_loc_description}', $this->Lang('location_description'), $content);
        $content = str_ireplace('{h:lan_show_in_filter}', $this->Lang('show_in_filter'), $content);

		$content = str_ireplace('{h:lan_or}', $this->Lang('or'), $content);
		$content = str_ireplace('{h:lan_cancel}', $this->Lang('cancel'), $content);
		$content = str_ireplace('{h:lan_update_location}', $this->Lang('update'), $content);
		
        if($content !== false){
			$sql = 'SELECT id, name, description, show_in_filter
					FROM '.EVENTS_LOCATIONS_TABLE.'
					WHERE id = '.(int)$location_id;
					
			$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve data of location for editing');
			if(!empty($result)){
				$content = str_ireplace('{h:loc_name}', $result['name'], $content);
				$content = str_ireplace('{h:loc_description}', $result['description'], $content);
				$content = str_ireplace('{h:location_id}', $result['id'], $content);
                $content = str_ireplace('{h:chk_show_in_filter}', $this->DrawShowInFilter($result['show_in_filter'], false), $content);

				echo $content;
                $this->arrInitJsFunction[] = 'phpCalendar.setFocus("location_name");';                
			}
		}
	}	

	/**
	 * Draw location details form
	 * @param $location_id
	*/	
	private function DrawLocationsDetailsForm($location_id)
	{
        $show_in_filter_array = array('0'=>'<span class=cal_no>'.$this->Lang('No').'</span>', '1'=>'<span class=cal_yes>'.$this->Lang('Yes').'</span>');
        
		// draw Locations Details Form from template
		$content = $this->FileGetContents('templates/locations_details_form.tpl');

		$content = str_ireplace('{h:lan_location_details}', $this->Lang('location_details'), $content);        
		$content = str_ireplace('{h:lan_location_name}', $this->Lang('location_name'), $content);		
		$content = str_ireplace('{h:lan_location_description}', $this->Lang('location_description'), $content);
        $content = str_ireplace('{h:lan_show_in_filter}', $this->Lang('show_in_filter'), $content);
		$content = str_ireplace('{h:lan_back}', $this->Lang('back'), $content);
		
        if($content !== false){
			$sql = 'SELECT id, name, description, show_in_filter
					FROM '.EVENTS_LOCATIONS_TABLE.'
					WHERE id = '.(int)$location_id;
					
			$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve data of location for viewing details');
			if(!empty($result)){
				$content = str_ireplace('{h:location_name}', $result['name'], $content);
                $content = str_ireplace('{h:location_description}', $result['description'], $content);
                $content = str_ireplace('{h:lbl_show_in_filter}', $show_in_filter_array[$result['show_in_filter']], $content);
				$content = str_ireplace('{h:js_back_function}', 'phpCalendar.locationsBack()', $content);
			}
			echo $content;
		}
	}

	/**
	 * Draw events locations management
	*/	
	private function DrawEventsLocationsManagement()
	{
		$colspan = 4;
		
		$hid_sort_by = ($this->GetParameter('hid_sort_by') != '') ? $this->GetParameter('hid_sort_by') : 'name';
		$hid_sort_direction = ($this->GetParameter('hid_sort_direction') != '') ? $this->GetParameter('hid_sort_direction') : 'ASC';
		if(strtolower($hid_sort_direction) == 'asc') $next_sort_direction = 'desc';
		else $next_sort_direction = 'asc';
        $show_in_filter_array = array('0'=>'<span class=cal_no>'.$this->Lang('No').'</span>', '1'=>'<span class=cal_yes>'.$this->Lang('Yes').'</span>');

		$sql = 'SELECT COUNT(*) as cnt FROM '.EVENTS_LOCATIONS_TABLE;
		$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve amount of existing locations');
		$total_records = $result['cnt'];
		$page_size = 20;
		$total_pages = (int)$result['cnt']/$page_size;
		$total_pages_partualy = $result['cnt'] % $page_size;
		if($total_pages_partualy != 0) $total_pages +=1;
		
		$limit_start = ($this->arrParameters['page']-1) * $page_size;
		if($limit_start < 0) $limit_start = '0';
		$limit_end = $page_size;
		
		$sql  = 'SELECT id, name, description, show_in_filter ';
		$sql .= 'FROM '.EVENTS_LOCATIONS_TABLE.' ';
		$sql .= 'ORDER BY '.$hid_sort_by.' '.$hid_sort_direction.' ';
		$sql .= 'LIMIT '.$limit_start.', '.$limit_end;
				
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve existing locations');
		$content = $this->FileGetContents('templates/locations_management_row.tpl');			   			   

		echo '<fieldset class="cal_fieldset">';
		if($this->arrLocOperations['add'] || $this->arrLocOperations['edit']) echo '<legend class="cal_legend">';
        if($this->arrLocOperations['add']) echo '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'locations_add\')">'.$this->Lang('add_location').'</a></span>';
		if($this->arrLocOperations['edit']) echo '<span class="active"><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'locations_management\')">'.$this->Lang('manage_locations').'</a></span>';
		if($this->arrLocOperations['add'] || $this->arrLocOperations['edit']) echo '&nbsp;</legend>';

		$sort_image = ($next_sort_direction == 'asc') ? '<img src="'.$this->calDir.'images/down.png" alt="down" border="0" />' : '<img src="'.$this->calDir.'images/up.png" alt="up" border="0" />';
		echo '<table class="tbl_management" align="center" border="0" cellspacing="0" cellpadding="2" width="100%">
			  <tr><th colspan="'.($colspan+1).'"></th></tr>
			  <tr>
				<th width="25px"></th>
				<th class="cal_left"><a href="javascript:phpCalendar.locationsSort(\'name\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('location_name').'</a>'.(($hid_sort_by == 'name') ? ' '.$sort_image : '').'</th>
				<th class="cal_left"><a href="javascript:phpCalendar.locationsSort(\'description\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('location_description').'</a>'.(($hid_sort_by == 'description') ? ' '.$sort_image : '').'</th>
                <th align="center" width="90px"><a href="javascript:phpCalendar.locationsSort(\'show_in_filter\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('show_in_filter').'</a>'.(($hid_sort_by == 'show_in_filter') ? ' '.$sort_image : '').'</th>
                <th align="center"'.((!$this->arrLocOperations['edit'] && !$this->arrLocOperations['details'] && !$this->arrLocOperations['delete']) ? ' width="1px"' : ' width="140px">'.$this->Lang('actions')).'</th>
			  </tr>
			  <tr><th colspan="'.($colspan+1).'" nowrap="nowrap" height="2px"></th></tr>';

		$locations_count = 0;
		foreach($result[0] as $key => $val){
			$temp_content = $content;
			$locations_count++;
			
			$drawed_buttons = 0;
			$edit_button = '';
			$details_button = '';
			$delete_button = '';
			if($this->arrLocOperations['details']){ $details_button = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:phpCalendar.locationsDetails({h:location_id});">'.$this->Lang('details').'</a>'; $drawed_buttons++; }
			if($this->arrLocOperations['edit'])   { $edit_button    = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:phpCalendar.locationsEdit({h:location_id});">'.$this->Lang('edit').'</a>'; $drawed_buttons++; }			
			if($this->arrLocOperations['delete']) { $delete_button  = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:void(0);" onclick="javascript:phpCalendar.locationsDelete({h:location_id});">'.$this->Lang('delete').'</a>'; $drawed_buttons++; }

			$temp_content = str_ireplace('{h:edit_button}', $edit_button, $temp_content); 
			$temp_content = str_ireplace('{h:details_button}', $details_button, $temp_content);
			$temp_content = str_ireplace('{h:delete_button}', $delete_button, $temp_content);
			
			$temp_content = str_ireplace('{h:location_id}', $val['id'], $temp_content);
			$temp_content = str_ireplace('{h:location_num}', $limit_start+$locations_count.'. ', $temp_content);
			$temp_content = str_ireplace('{h:location_name}', $val['name'], $temp_content);
			
            $description = (strlen($val['description']) > 50) ? substr($val['description'], 0, 50).'...' : $val['description'];
			$temp_content = str_ireplace('{h:location_description}', $description, $temp_content);

            $temp_content = str_ireplace('{h:category_show_in_filter}', $show_in_filter_array[$val['show_in_filter']], $temp_content);			
            echo $temp_content;
		}
		echo '<tr><td colspan="'.($colspan+1).'" style="border-bottom:1px solid #eeeeee;">&nbsp;</td></tr>';		
		echo '<tr>';
		if(!$this->arrLocOperations['edit'] && !$this->arrLocOperations['details'] && !$this->arrLocOperations['delete']) $colspan = 3;
		echo '<td class="cal_left" style="padding-left:10px" colspan="'.$colspan.'">'.$this->Lang('pages').': ';
				for($i_pages = 1; $i_pages <= $total_pages; $i_pages++){
					if($i_pages > 1) echo ', ';
					$i_pages_a = ($i_pages == $this->arrParameters['page']) ? '<strong>'.$i_pages.'</strong>' : $i_pages;
					echo '<a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'locations_management\',\'\',\''.$i_pages.'\');">'.$i_pages_a.'</a>';
				}
		echo '</td>';
		echo '<td class="cal_right" style="padding-right:10px">'.$this->Lang('total_locations').': '.$total_records.'</td>';
		echo '</tr>';		
		echo '</table>';
		echo '</fieldset>';
	}	


	/**
	 * Draw participants management
	*/	
	private function DrawParticipantsManagement()
	{
		$colspan = 5;
		
		$hid_sort_by = ($this->GetParameter('hid_sort_by') != '') ? $this->GetParameter('hid_sort_by') : 'last_name';
		$hid_sort_direction = ($this->GetParameter('hid_sort_direction') != '') ? $this->GetParameter('hid_sort_direction') : 'ASC';
		if(strtolower($hid_sort_direction) == 'asc') $next_sort_direction = 'desc';
		else $next_sort_direction = 'asc';

		$sql = 'SELECT COUNT(*) as cnt FROM '.PARTICIPANTS_TABLE;
		$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Check how many participants exist');
		$total_records = $result['cnt'];
		$page_size = 20;
		$total_pages = (int)$result['cnt']/$page_size;
		$total_pages_partualy = $result['cnt'] % $page_size;
		if($total_pages_partualy != 0) $total_pages +=1;
		
		$limit_start = ($this->arrParameters['page']-1) * $page_size;
		if($limit_start < 0) $limit_start = '0';
		$limit_end = $page_size;
		
		$sql  = 'SELECT id, first_name, last_name, email ';
		$sql .= 'FROM '.PARTICIPANTS_TABLE.' ';
		$sql .= 'ORDER BY '.$hid_sort_by.' '.$hid_sort_direction.' ';
		$sql .= 'LIMIT '.$limit_start.', '.$limit_end;
				
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve data of existing participants');
		$content = $this->FileGetContents('templates/participants_management_row.tpl');			   			   
		
		echo '<fieldset class="cal_fieldset">';
		if($this->arrParticipantsOperations['add'] || $this->arrParticipantsOperations['edit']) echo '<legend class="cal_legend">';
        if($this->arrParticipantsOperations['add']) echo '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'participants_add\')">'.$this->Lang('add_participant').'</a></span>';
		if($this->arrParticipantsOperations['edit']) echo '<span class="active"><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'participants_management\')">'.$this->Lang('manage_participants').'</a></span>';
		if($this->arrParticipantsOperations['add'] || $this->arrParticipantsOperations['edit']) echo '&nbsp;</legend>';
		
		$sort_image = ($next_sort_direction == 'asc') ? '<img src="'.$this->calDir.'images/down.png" alt="down" border="0" />' : '<img src="'.$this->calDir.'images/up.png" alt="up" border="0" />';
		echo '<table class="tbl_management" align="center" border="0" cellspacing="0" cellpadding="2" width="100%">
			  <tr><th colspan="'.$colspan.'"></th></tr>
			  <tr>
				<th width="25px"></th>
				<th class="cal_left"><a href="javascript:phpCalendar.participantsSort(\'first_name\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('first_name').'</a>'.(($hid_sort_by == 'first_name') ? ' '.$sort_image : '').'</th>
				<th class="cal_left"><a href="javascript:phpCalendar.participantsSort(\'last_name\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('last_name').'</a>'.(($hid_sort_by == 'last_name') ? ' '.$sort_image : '').'</th>
				<th class="cal_left"><a href="javascript:phpCalendar.participantsSort(\'email\',\''.$next_sort_direction.'\',\''.$this->arrParameters['page'].'\')">'.$this->Lang('email').'</a>'.(($hid_sort_by == 'email') ? ' '.$sort_image : '').'</th>
				<th align="center"'.((!$this->arrParticipantsOperations['edit'] && !$this->arrParticipantsOperations['details'] && !$this->arrParticipantsOperations['delete']) ? ' width="1px"' : ' width="140px">'.$this->Lang('actions')).'</th>
			  </tr>
			  <tr><th colspan="'.$colspan.'" nowrap="nowrap" height="2px"></th></tr>';

		$participants_count = 0;
		foreach($result[0] as $key => $val){
			$temp_content = $content;
			$participants_count++;
			
			$drawed_buttons = 0;
			$edit_button = '';
			$details_button = '';
			$delete_button = '';
			if($this->arrParticipantsOperations['details']){ $details_button = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:phpCalendar.participantsDetails({h:participant_id});">'.$this->Lang('details').'</a>'; $drawed_buttons++; }
			if($this->arrParticipantsOperations['edit'])   { $edit_button    = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:phpCalendar.participantsEdit({h:participant_id});">'.$this->Lang('edit').'</a>'; $drawed_buttons++; }			
			if($this->arrParticipantsOperations['delete']) { $delete_button  = (($drawed_buttons > 0) ? ' | ' : '').'<a href="javascript:void(0);" onclick="javascript:phpCalendar.participantsDelete({h:participant_id});">'.$this->Lang('delete').'</a>'; $drawed_buttons++; }

			$temp_content = str_ireplace('{h:edit_button}', $edit_button, $temp_content); 
			$temp_content = str_ireplace('{h:details_button}', $details_button, $temp_content);
			$temp_content = str_ireplace('{h:delete_button}', $delete_button, $temp_content);
			
			$temp_content = str_ireplace('{h:participant_id}', $val['id'], $temp_content);
			$temp_content = str_ireplace('{h:participant_num}', $limit_start+$participants_count.'. ', $temp_content);
			$temp_content = str_ireplace('{h:participant_first_name}', $val['first_name'], $temp_content);
			$temp_content = str_ireplace('{h:participant_last_name}', $val['last_name'], $temp_content);
			$temp_content = str_ireplace('{h:participant_email}', $val['email'], $temp_content);
			
			echo $temp_content;
		}

		echo '<tr><td colspan="'.$colspan.'" style="border-bottom:1px solid #eeeeee;">&nbsp;</td></tr>';		
		echo '<tr>';
		if(!$this->arrParticipantsOperations['edit'] && !$this->arrParticipantsOperations['details'] && !$this->arrParticipantsOperations['delete']) $colspan = 3;
		echo '<td class="cal_left" style="padding-left:10px" colspan="'.($colspan-1).'">'.$this->Lang('pages').': ';
				for($i_pages = 1; $i_pages <= $total_pages; $i_pages++){
					if($i_pages > 1) echo ', ';
					$i_pages_a = ($i_pages == $this->arrParameters['page']) ? '<strong>'.$i_pages.'</strong>' : $i_pages;
					echo '<a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'participants_management\',\'\',\''.$i_pages.'\');">'.$i_pages_a.'</a>';
				}
		echo '</td>';
		echo '<td class="cal_right" style="padding-right:10px">'.$this->Lang('total').': '.$total_records.'</td>';
		echo '</tr>';		
		echo '</table>';
		echo '</fieldset>';
	}	

	/**
	 * Draw participants details form
	 * @param $participant_id
	*/	
	private function DrawParticipantsDetailsForm($participant_id)
	{
		// draw Participant Details Form from template
		$content = $this->FileGetContents('templates/participants_details_form.tpl');

		$content = str_ireplace('{h:lan_participant_details}', $this->Lang('participant_details'), $content);
		$content = str_ireplace('{h:lan_participant_first_name}', $this->Lang('first_name'), $content);
		$content = str_ireplace('{h:lan_participant_last_name}', $this->Lang('last_name'), $content);
		$content = str_ireplace('{h:lan_participant_email}', $this->Lang('email'), $content);
		$content = str_ireplace('{h:lan_back}', $this->Lang('back'), $content);
		
        if($content !== false){
			$sql = 'SELECT id, first_name, last_name, email
					FROM '.PARTICIPANTS_TABLE.'
					WHERE id = '.(int)$participant_id;
					
			$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve data of specific participant for viewing details');
			if(!empty($result)){
				$content = str_ireplace('{h:first_name}', $result['first_name'], $content);
				$content = str_ireplace('{h:last_name}', $result['last_name'], $content);
				$content = str_ireplace('{h:email}', $result['email'], $content);
				$content = str_ireplace('{h:js_back_function}', 'phpCalendar.participantsBack()', $content);
			}
			echo $content;
		}
	}
	
	/**
	 * Draw participants edit form
	 * @param $participant_id
	*/	
	private function DrawParticipantsEditForm($participant_id)
	{
		// draw Participants Edit Form from template
		$content = $this->FileGetContents('templates/participants_edit_form.tpl');

		$content = str_ireplace('{h:lan_edit_participant}', $this->Lang('edit_participant'), $content);
		$content = str_ireplace('{h:lan_participant_first_name}', $this->Lang('first_name'), $content);
		$content = str_ireplace('{h:lan_participant_last_name}', $this->Lang('last_name'), $content);
		$content = str_ireplace('{h:lan_participant_email}', $this->Lang('email'), $content);
		$content = str_ireplace('{h:lan_or}', $this->Lang('or'), $content);
		$content = str_ireplace('{h:lan_cancel}', $this->Lang('cancel'), $content);
		$content = str_ireplace('{h:lan_update_participant}', $this->Lang('update'), $content);

		$content = str_ireplace('{h:participant_id}', $participant_id, $content);			
		
        if($content !== false){
			$sql = 'SELECT id, first_name, last_name, email
					FROM '.PARTICIPANTS_TABLE.'
					WHERE id = '.(int)$participant_id;

			$result = $this->DatabaseQuery($sql, CAL_DATA_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Retrieve data of specific participant for editing');
			if(!empty($result)){
				$content = str_ireplace('{h:first_name}', $result['first_name'], $content);
				$content = str_ireplace('{h:last_name}', $result['last_name'], $content);
				$content = str_ireplace('{h:email}', $result['email'], $content);
			}
			echo $content;
            $this->arrInitJsFunction[] = 'phpCalendar.setFocus("first_name");';
		}
	}
	
	/**
	 * Draw participants add form
	*/	
	private function DrawParticipantsAddForm(){
		// draw Add Event Form from template
		$content = $this->FileGetContents('templates/participants_add_form.tpl');

		echo '<fieldset class="cal_fieldset">';
		if($this->arrParticipantsOperations['add'] || $this->arrParticipantsOperations['edit']) echo '<legend class="cal_legend">';
        if($this->arrParticipantsOperations['add']) echo '<span class="active"><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'participants_add\')">'.$this->Lang('add_participant').'</a></span>';
		if($this->arrParticipantsOperations['edit']) echo '<span><a href="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'participants_management\')">'.$this->Lang('manage_participants').'</a></span>';
		if($this->arrParticipantsOperations['add'] || $this->arrParticipantsOperations['edit']) echo '&nbsp;</legend>';
		
		$content = str_ireplace('{h:lan_participant_first_name}', $this->Lang('first_name'), $content);
		$content = str_ireplace('{h:lan_participant_last_name}', $this->Lang('last_name'), $content);
		$content = str_ireplace('{h:lan_participant_email}', $this->Lang('email'), $content);
		$content = str_ireplace('{h:lan_or}', $this->Lang('or'), $content);
		$content = str_ireplace('{h:lan_cancel}', $this->Lang('cancel'), $content);
		$content = str_ireplace('{h:lan_add_participant}', '+ '.$this->Lang('add_new_participant'), $content);
		
		echo $content;
		echo '</fieldset>';        
        $this->arrInitJsFunction[] = 'phpCalendar.setFocus("first_name");';
	}

	/**
	 * Draw events statistics
	*/	
	private function DrawEventsStatistics()
	{
		echo '<fieldset class="cal_fieldset" style="margin-top:20px; padding-bottom:7px;">
			  <legend class="cal_legend cal_bold"><span class="single">'.$this->Lang('events_statistics').'</span></legend>';
    	echo '<div style="float:right;padding:7px;">		        
				<select id="selChartType" onchange="javascript:phpCalendar.doPostBack(\'view\',\''.$this->arrParameters['view_type'].'\',\''.$this->arrParameters['year'].'\',\''.$this->arrParameters['month'].'\',\''.$this->arrParameters['day'].'\',\'events_statistics\',\'\',\'\', this.value)">		
					<option value="columnchart" '.(($this->arrParameters['chart_type'] == 'columnchart') ? 'selected="selected"' : '').'>'.$this->Lang('chart_column').'</option>
					<option value="barchart" '.(($this->arrParameters['chart_type'] == 'barchart') ? 'selected="selected"' : '').'>'.$this->Lang('chart_bar').'</option>
					<option value="piechart" '.(($this->arrParameters['chart_type'] == 'piechart') ? 'selected="selected"' : '').'>'.$this->Lang('chart_pie').'</option>
					<option value="areachart" '.(($this->arrParameters['chart_type'] == 'areachart') ? 'selected="selected"' : '').'>'.$this->Lang('chart_area').'</option>
		        </select> ';				
		echo '</div>';
		echo '<div id="div_visualization" style="float:right;width:'.(intval($this->calWidth)*0.82).'px;height:310px;"><img src="'.$this->calDir.'style/'.$this->cssStyle.'/images/loading.gif" style="margin:100px auto;" alt="loading..."></div>';
		echo '</fieldset>';
	}
	
	/**
	 * Draw events exporting
	*/	
	private function DrawEventsExporting()
	{
		$event_from_day 	= ($this->GetParameter('event_from_day') != '') ? $this->GetParameter('event_from_day') : $this->ConvertToDecimal($this->arrToday['mday']);
		$event_from_month 	= ($this->GetParameter('event_from_month') != '') ? $this->GetParameter('event_from_month') : $this->ConvertToDecimal($this->arrToday['mon']);
		$event_from_year 	= ($this->GetParameter('event_from_year') != '') ? $this->GetParameter('event_from_year') : $this->arrToday['year'];
		$event_to_day 		= ($this->GetParameter('event_to_day') != '') ? $this->GetParameter('event_to_day') : $this->ConvertToDecimal($this->arrToday['mday']);
		$event_to_month 	= ($this->GetParameter('event_to_month') != '') ? $this->GetParameter('event_to_month') : $this->ConvertToDecimal($this->arrToday['mon']);
		$event_to_year 		= ($this->GetParameter('event_to_year') != '') ? $this->GetParameter('event_to_year') : $this->arrToday['year'];
        $export_format 		= ($this->GetParameter('sel_export_format') != '') ? $this->GetParameter('sel_export_format') : 'csv';
        $category_name_change = ($this->GetParameter('sel_category_name_change') != '') ? $this->GetParameter('sel_category_name_change') : '';
        $location_name_change = ($this->GetParameter('sel_location_name_change') != '') ? $this->GetParameter('sel_location_name_change') : '';
		
		$content = $this->FileGetContents('templates/events_exporting.tpl');
		
		$legend = '<legend class="cal_legend cal_bold"><span class="single">'.$this->Lang('export').'</span></legend>';
		
		$content = str_ireplace('{h:legend}', $legend, $content);
		$content = str_ireplace('{h:lan_category_name}', (($this->isCategoriesAllowed) ? $this->Lang('category_name').':' : ''), $content);
		$content = str_ireplace('{h:ddl_categories}', $this->DrawCategoriesDDL($category_name_change, '', '', 'sel_category_name_change'), $content);		
		$content = str_ireplace('{h:lan_location_name}', (($this->isLocationsAllowed) ? $this->Lang('location_name').':' : ''), $content);
		$content = str_ireplace('{h:ddl_locations}', $this->DrawLocationsDDL($location_name_change, '', '', 'sel_location_name_change'), $content);		

		$content = str_ireplace('{h:lan_from}', $this->Lang('from'), $content);
		$content = str_ireplace('{h:lan_to}', $this->Lang('to'), $content); 
		$content = str_ireplace('{h:ddl_from}', $this->DrawDate('from', $event_from_year, $event_from_month, $event_from_day, false), $content);
		$content = str_ireplace('{h:ddl_to}', $this->DrawDate('to', $event_to_year, $event_to_month, $event_to_day, false), $content);

		$content = str_ireplace('{h:lan_export_format}', $this->Lang('export_format'), $content);
        $content = str_ireplace('{h:ddl_export_formats}', $this->DrawExportFormatsDDL($export_format), $content);

		$content = str_ireplace('{h:lan_or}', $this->Lang('or'), $content);
		$content = str_ireplace('{h:lan_cancel}', $this->Lang('cancel'), $content);
		$content = str_ireplace('{h:lan_export_events}', $this->Lang('export_events'), $content);
		
		echo $content;		
	}	

	/**
	 * Draw calendar types changer
	 * @param $draw - draw or return
	*/	
	private function DrawTypesChanger($draw = true)
	{
		$output = '';
		$options = '';
        $options_count = 0;
        
		foreach($this->arrViewTypes as $key => $val){
			if($val['enabled'] == true){
                $options_count++;
                if($this->viewChangerType == 'tabs'){
                    $class = ($this->arrParameters['view_type'] == $key) ? 'active_tab' : 'tab';
                    $options .= '<input class="'.$class.'" type="button" value="'.$val['name'].'" onclick="javascript:phpCalendar.doPostBack(\'view\',\''.$key.'\')" />';
                }else{
                    $options .= '<option value="'.$key.'"'.(($this->arrParameters['view_type'] == $key) ? ' selected="selected"' : '').'>'.$val['name'].'</option>';
                }
			}			
		}
    	if($options != '' && $options_count > 1){
            if($this->viewChangerType == 'tabs'){
                $output  = '<div class="types_chager">';
                $output .= '<input type="hidden" name="view_type" id="view_type" value="'.$this->arrParameters['view_type'].'">'; 
            }else{
                $output = '<select class="form_select" name="view_type" id="view_type" onchange="javascript:phpCalendar.doPostBack(\'view\',this.value)">';
            }
            $output .= $options;
            if($this->viewChangerType == 'tabs') $output .= '</div>'; 
            else $output .= '</select>';
		}else{
            $output = '<input type="hidden" name="hid_view_type" value="'.$this->defaultView.'">';
        }
	
		if($draw) echo $output;
		else return $output;
	}

	/**
	 * Draw today jumper
	 * @param $draw - draw or return
	*/	
	private function DrawTodayJumper($draw = true)
	{
		$output = '<input class="form_button" type="button" value="'.$this->Lang('today').'" onclick="javascript:phpCalendar.jumpTodayDate()" />';
		if($draw) echo $output;
		else return $output;
	}
	
	/**
	 * Draw date jumper
	 * @param $draw - draw or return
	 * @param $draw_day
	 * @param $draw_month
	 * @param $draw_year
	*/	
	private function DrawDateJumper($draw = true, $draw_day = true, $draw_month = true, $draw_year = true)
	{
		$result = '';
        $day_ddl = '';
        $month_ddl = '';
        $year_ddl = '';
		
		// draw days ddl
		if($draw_day){
			$day_ddl .= '<select class="form_select" name="jump_day" id="jump_day">';
			for($i=1; $i <= 31; $i++){
				$i_converted = $this->ConvertToDecimal($i);
				$day_ddl .= '<option value="'.$i_converted.'"'.(($this->arrParameters['day'] == $i_converted) ? ' selected="selected"' : '').'>'.$i_converted.'</option>';
			}
			$day_ddl .= '</select> ';			
		}else{
			$day_ddl .= '<input type="hidden" name="jump_day" id="jump_day" value="'.$this->arrToday['mday'].'" />';			
		}

		// draw months ddl
		if($draw_month){			
			$month_ddl .= '<select class="form_select" name="jump_month" id="jump_month" onchange="javascript:phpCalendar.refillDaysInMonth(\'jump_\')">';
			for($i=1; $i <= 12; $i++){
				$i_converted = $this->ConvertToDecimal($i);
				$month_ddl .= '<option value="'.$i_converted.'"'.(($this->arrParameters['month'] == $i_converted) ? ' selected="selected"' : '').'>'.$this->arrMonths[$i].'</option>';
			}
			$month_ddl .= '</select> ';			
		}else{
			$month_ddl .= '<input type="hidden" name="jump_month" id="jump_month" value="'.$this->ConvertToDecimal($this->arrToday['mon']).'" />';			
		}

		// draw years ddl
		if($draw_year){			
			$year_ddl .= '<select class="form_select" name="jump_year" id="jump_year" '.(($draw_month) ? 'onchange="javascript:phpCalendar.refillDaysInMonth(\'jump_\')"' : '').'>';
			for($i=$this->arrParameters['year']-10; $i <= $this->arrParameters['year']+10; $i++){
				$year_ddl .= '<option value="'.$i.'"'.(($this->arrParameters['year'] == $i) ? ' selected="selected"' : '').'>'.$i.'</option>';
			}
			$year_ddl .= '</select> ';
		}else{
			$year_ddl .= '<input type="hidden" name="jump_year" id="jump_year" value="'.$this->arrToday['year'].'" />';			
		}
		
        if($this->dateFormat == 'dd/mm/yyyy'){
            $result .= $day_ddl.$month_ddl.$year_ddl;
        }else if($this->dateFormat == 'mm/dd/yyyy'){
            $result .= $month_ddl.$day_ddl.$year_ddl;
        }else if($this->dateFormat == 'yyyy/mm/dd'){
            $result .= $year_ddl.$month_ddl.$day_ddl;
        }else{
            $result .= $day_ddl.$month_ddl.$year_ddl;
        }
        
		$result .= '<input class="form_button" type="button" value="'.$this->Lang('go').'" onclick="javascript:phpCalendar.jumpToDate()" />';
		
		if($draw_month){
			$this->arrInitJsFunction[] = 'phpCalendar.refillDaysInMonth("jump_");';	
		}		

		if($draw) echo $result;
		else return $result;
	}

	/**
	 * Draw export formats dropdown list
	 * @param $selected_item - selected option
	 * @param $name
	*/	
	private function DrawExportFormatsDDL($selected_item = '', $name = 'sel_export_format')
	{
		$output  = '<select class="form_select" id="'.$name.'" name="'.$name.'">';
        foreach($this->exportTypes as $key => $val){
            if($val['enabled']) $output .= '<option '.(($selected_item == $key) ? ' selected="selected"' : '').'value="'.$key.'">'.$val['name'].'</option>';
        }
        $output .= '</select>';
        return $output;
    }

	/**
	 * Draw categories dropdown list
	 * @param $selected_item - selected option
	 * @param $on_js_event
	 * @param $style
	 * @param $name
	 * @param $use_filter
	*/	
	private function DrawCategoriesDDL($selected_item = '', $on_js_event = '', $style = '', $name = 'sel_category_name', $use_filter = false, $id = '')
	{
		if(!$this->isCategoriesAllowed) return '';
		
		// select categories
		$sql = 'SELECT id, name, description, color, duration
				FROM '.EVENTS_CATEGORIES_TABLE.'
				WHERE '.(($use_filter) ? ' show_in_filter = 1 AND ' : '').'
                      (duration = \'\' OR duration >= '.$this->timeSlot.')
				ORDER BY name ASC                
				LIMIT 0, 1000';
				
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve data of existing categories');

		$output = '<select class="form_select" id="'.(empty($id) ? $name : $id).'" name="'.$name.'" style="'.$style.'" '.$on_js_event.'>';
		$select_category_lang = ($name == 'sel_category_name_change') ? $this->Lang('show_all') : $this->Lang('select_category');
		$output .= '<option value="">-- '.$select_category_lang.' --</option>';
		foreach($result[0] as $key => $val){
			$selected = ($selected_item == $val['id']) ? ' selected="selected"' : '';
			$val_duration = ($name == 'sel_category_name' && $val['duration'] != '') ? '#'.$val['duration'] : '';
			$output .= '<option value="'.$val['id'].$val_duration.'"'.$selected.'>'.$val['name'].(($val['duration'] != '') ? ' ('.$val['duration'].' '.$this->Lang('min').')' : '').'</option>';
		}
		$output .= ($name == 'sel_category_name_change') ? '<option value="0" '.(($selected_item === '0') ? ' selected="selected"' : '').'>[ '.$this->Lang('not_defined').' ]</option>' : '';
		$output .= '</select>';            
		
		return $output;
	}
    
	/**
	 * Draw locations dropdown list
	 * @param $selected_item - selected option
	 * @param $on_js_event
	 * @param $style
	 * @param $name
	 * @param $use_filter
	*/	
	private function DrawLocationsDDL($selected_item = '', $on_js_event = '', $style = '', $name = 'sel_location_name', $use_filter = false)
	{
		if(!$this->isLocationsAllowed) return '';
    
		// select locations
		$sql = 'SELECT id, name, description
				FROM '.EVENTS_LOCATIONS_TABLE.'
				'.(($use_filter) ? 'WHERE show_in_filter = 1' : '').'                      
				ORDER BY name ASC                
				LIMIT 0, 1000';
				
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve data of existing locations');

		$output = '<select class="form_select" id="'.$name.'" name="'.$name.'" style="'.$style.'" '.$on_js_event.'>';
		$select_location_lang = ($name == 'sel_location_name_change') ? $this->Lang('show_all') : $this->Lang('select_location');
		$output .= '<option value="">-- '.$select_location_lang.' --</option>';
		foreach($result[0] as $key => $val){
			$selected = ($selected_item == $val['id']) ? ' selected="selected"' : '';			
			$output .= '<option value="'.$val['id'].'"'.$selected.'>'.$val['name'].'</option>';
		}
		$output .= ($name == 'sel_location_name_change') ? '<option value="0" '.(($selected_item === '0') ? ' selected="selected"' : '').'>[ '.$this->Lang('not_defined').' ]</option>' : '';
		$output .= '</select>';            
		
		return $output;
	}

	/**
	 * Draw events dropdown list
	 * @param $selected_item - selected option
	 * @param $on_js_event
	 * @param $style
	*/	
	private function DrawEventsDDL($selected_item = '', $on_js_event = '', $style = '')
	{		
		// draw Add Event Form from template
		$sql = 'SELECT
					'.EVENTS_TABLE.'.id,
					'.EVENTS_TABLE.'.category_id,
					'.EVENTS_TABLE.'.name,
					'.EVENTS_TABLE.'.description,
					ec.name as category_name,
					ec.duration
				FROM '.EVENTS_TABLE.'
					LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ec ON '.EVENTS_TABLE.'.category_id = ec.id
				WHERE (ec.id IS NULL OR (ec.duration = \'\' OR ec.duration >= '.$this->timeSlot.'))
				      '.$this->PrepareWhereClauseParticipant().'
				ORDER BY '.EVENTS_TABLE.'.category_id ASC
				LIMIT 0, 1000';
		
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve data of existing events - DDL');

		$output = '<select class="form_select" id="sel_event_name" name="sel_event_name" style="'.$style.'" '.$on_js_event.'>';
        $output .= '<option value="">-- '.$this->Lang('select_event').' --</option>';
		$cur_category = '';
		foreach($result[0] as $key => $val){
			if($this->arrCatOperations['manage'] && ($cur_category != $val['category_id'])){
				if($cur_category != '') $output .= '</optgroup>';
				$output .= '<optgroup label="'.(($val['category_name']) ? $val['category_name'] : $this->Lang('undefined').' *').(($val['duration'] != '') ? ' ('.$val['duration'].' '.$this->Lang('min').')' : '').'">';
				$cur_category = $val['category_id'];
			}			
			$selected = ($selected_item == $val['id']) ? ' selected="selected"' : '';
			$output .= '<option value="'.$val['id'].(($val['duration'] != '') ? '#'.$val['duration'] : '').'"'.$selected.'>'.$val['name'].'</option>';
		}
		$output .= '</select>';            
		
		return $output;
	}

	/**
	 * Draw repeat time DDL
	 * @param $draw - draw or return
	*/	
	private function DrawRepeatTimeDDL($draw = false)
	{		
		$output = '<select name="event_repeat_type" id="event_repeat_type" onchange="javascript:phpCalendar.repeatTypeOnChange(this.value)">
					<option value="weekly">'.$this->Lang('weekly').'</option>
					<option value="monthly">'.$this->Lang('monthly').'</option>
				</select>';
		
		if($draw) echo $output;
		else return $output;
	}

	/**
	 * Draw repeat every DDL
	 * @param $draw - draw or return
	*/	
	private function DrawRepeatEveryDDL($draw = false)
	{		
		$output = '<select name="event_repeat_every" id="event_repeat_every">';
		for($i=1; $i<=10; $i++){
			$output .= '<option value="'.$i.'">'.$i.'</option>';
		}
		$output .= '</select>';
		
		if($draw) echo $output;
		else return $output;
	}
	
	/**
	 * Draw repeat on weekday DDL
	 * @param $draw - draw or return
	*/	
	private function DrawRepeatOnWeekdayDDL($draw = false)
	{		
		$output = '<select name="repeat_on_weekday" id="repeat_on_weekday">
			<option value="0">'.$this->Lang('sun').'</option>
			<option value="1">'.$this->Lang('mon').'</option>
			<option value="2">'.$this->Lang('tue').'</option>
			<option value="3">'.$this->Lang('wed').'</option>
			<option value="4">'.$this->Lang('thu').'</option>
			<option value="5">'.$this->Lang('fri').'</option>
			<option value="6">'.$this->Lang('sat').'</option>
		</select>';
		
		if($draw) echo $output;
		else return $output;
	}

	/**
	 * Draw repeat on weekday num DDL
	 * @param $draw - draw or return
	*/	
	private function DrawRepeatOnWeekdayNumDDL($draw = false)
	{		
		$output = '<select name="repeat_on_weekday_num" id="repeat_on_weekday_num">
			<option value="1">1'.$this->Lang('suffix', 1).'</option>
			<option value="2">2'.$this->Lang('suffix', 2).'</option>
			<option value="3">3'.$this->Lang('suffix', 3).'</option>
			<option value="4">4'.$this->Lang('suffix', 4).'</option>
			<option value="5">5'.$this->Lang('suffix', 5).'</option>
		</select>';
		
		if($draw) echo $output;
		else return $output;
	}
	
	/**
	 * Draw datetime calendar
	 * @param $draw - draw or return
	 * @param $type - from|to
	*/	
	private function DrawDateTime($type = 'from', $year = '', $month = '', $day = '', $hour = '', $draw = true, $disabled = false)
	{		
		$output  = $this->DrawDate($type, $year, $month, $day, false, $disabled);
		$output .= $this->DrawTime($type, $hour, false, $disabled);
		
		if($draw) echo $output;
		else return $output;
	}
	
	/**
	 * Draw date calendar
	 * @param $type - from|to
	 * @param $year
	 * @param $month
	 * @param $day
	 * @param $draw
	 * @param $disabled
	*/	
	private function DrawDate($type = 'from', $year = '', $month = '', $day = '', $draw = true, $disabled = false)
	{		
		$result = '';
		$disabled = ($disabled) ? ' disabled="disabled"' : '';
        $day_ddl = '';
        $month_ddl = '';
        $year_ddl = '';
		
		// draw days ddl
		$day_ddl = '<select class="form_select" name="event_'.$type.'_day" id="event_'.$type.'_day"'.$disabled.'>';
		for($i=1; $i <= 31; $i++){
			$i_converted = $this->ConvertToDecimal($i);
			$day_ddl .= '<option value="'.$i_converted.'"'.(($day == $i_converted) ? ' selected="selected"' : '').'>'.$i_converted.'</option>';
		}
		$day_ddl .= '</select> ';			

		// draw months ddl
		$month_ddl .= '<select class="form_select" onchange="javascript:phpCalendar.refillDaysInMonth(\'event_'.$type.'_\')" name="event_'.$type.'_month" id="event_'.$type.'_month"'.$disabled.'>';
		for($i=1; $i <= 12; $i++){
			$i_converted = $this->ConvertToDecimal($i);
			$month_ddl .= '<option value="'.$i_converted.'"'.(($month == $i_converted) ? ' selected="selected"' : '').'>'.$this->arrMonths[$i].'</option>';
		}
		$month_ddl .= '</select> ';			

		// draw years ddl
		$year_ddl .= '<select class="form_select" onchange="javascript:phpCalendar.refillDaysInMonth(\'event_'.$type.'_\')" name="event_'.$type.'_year" id="event_'.$type.'_year"'.$disabled.'>';
		for($i=$year-10; $i <= $year+10; $i++){
			$year_ddl .= '<option value="'.$i.'"'.(($year == $i) ? ' selected="selected"' : '').'>'.$i.'</option>';
		}
		$year_ddl .= '</select> ';		
		
        if($this->dateFormat == 'dd/mm/yyyy'){
            $result .= $day_ddl.$month_ddl.$year_ddl;
        }else if($this->dateFormat == 'mm/dd/yyyy'){
            $result .= $month_ddl.$day_ddl.$year_ddl;
        }else if($this->dateFormat == 'yyyy/mm/dd'){
            $result .= $year_ddl.$month_ddl.$day_ddl;
        }else{
            $result .= $day_ddl.$month_ddl.$year_ddl;
        }

		$this->arrInitJsFunction[] = 'phpCalendar.refillDaysInMonth("event_'.$type.'_");';

		if($draw) echo $result;
		else return $result;
	}
	
	/**
	 * Delete all cache files
	*/	
	public function DeleteCache()
	{
		if ($hdl = opendir($this->cacheDir)){
			while(false !== ($obj = @readdir($hdl))){
				if($obj == '.' || $obj == '..' || $obj == '.htaccess'){ 
					continue; 
				}
				@unlink($this->cacheDir.$obj);
			}
		}
	}
	
	/**
	 * Save http request vars
	 * @param $http_request_vars
	*/	
	public function SaveHttpRequestVars($http_request_vars = array())
	{
		$this->httpRequestVars = $http_request_vars;
	}

	/**
	 * Draw ime calendar
	 * @param $type - from|to
	 * @param $hour
	 * @param $draw - draw or return
	 * @param $disabled
	*/	
	private function DrawTime($type = 'from', $hour = '', $draw = true, $disabled = false)
	{		
		$disabled = ($disabled) ? ' disabled="disabled"' : '';

		// draw hours ddl
		$result = '<select class="form_select" name="event_'.$type.'_hour" id="event_'.$type.'_hour"'.$disabled.'>';
		if($this->timeSlot == '10' || $this->timeSlot == '15' || $this->timeSlot == '30' || $this->timeSlot == '45'){
            $blocks_count = 0;
            $max_blocks = ($this->timeSlot == '45') ? 4 : (int)(60 / $this->timeSlot);
			for($i_hour=$this->fromHour; $i_hour < $this->toHour; $i_hour++){				
                for($i_block=0; $i_block < $max_blocks; $i_block++, $blocks_count++){
					$result .= $this->PrepareOption($hour, $i_hour, $i_block, ($this->timeSlot == '45' ? $blocks_count : 0));
				}
			}
			if($type == 'to' || $type == 'to_edit' || $type == 'to_time') $result .= $this->PrepareOption($hour, $i_hour, $i_block);
		}else{
            $increment = ($this->timeSlot == '120') ? 2 : 1;
			for($i_hour=$this->fromHour; $i_hour < $this->toHour; $i_hour += $increment){
                $result .= $this->PrepareOption($hour, $i_hour);
			}
            if($type == 'to' || $type == 'to_edit' || $type == 'to_time') $result .= $this->PrepareOption($hour, $i_hour);
		}
		$result .= '</select> ';			

		if($draw){
			echo $result;
		}else{
			return $result;
		}
	}

	/**
	 * Draw category durations dropdown box
	 * @param $duration - selected
	 * @param $draw_ddl - draw ddl or return selected value
	*/	
	private function DrawCategoryDurations($duration = '', $draw_ddl = true)
	{
		if(!$draw_ddl){
			return $duration.' '.(($duration != '') ? $this->Lang('min').'.' : '<span class="cal_gray">- '.$this->Lang('not_defined').' -</span>');
		}

		$output = '';
		$output .= '<select class="form_select" name="category_duration" id="category_duration">';
		$output .= '<option '.(($duration == '') ? 'selected="selected"' : '').' value="">-- '.$this->Lang('default').' --</option>';
        if($this->timeSlot <= 10) $output .= '<option '.(($duration == 10) ? 'selected="selected"' : '').' value="10">10 '.$this->Lang('min').'.</option>';			
        if($this->timeSlot <= 15) $output .= '<option '.(($duration == 15) ? 'selected="selected"' : '').' value="15">15 '.$this->Lang('min').'.</option>';			
        if($this->timeSlot <= 30) $output .= '<option '.(($duration == 30) ? 'selected="selected"' : '').' value="30">30 '.$this->Lang('min').'.</option>';			
        if($this->timeSlot <= 45) $output .= '<option '.(($duration == 45) ? 'selected="selected"' : '').' value="45">45 '.$this->Lang('min').'.</option>';			
        if($this->timeSlot <= 60) $output .= '<option '.(($duration == 60) ? 'selected="selected"' : '').' value="60">60 '.$this->Lang('min').'.</option>';			
        if($this->timeSlot <= 120) $output .= '<option '.(($duration == 120) ? 'selected="selected"' : '').' value="120">120 '.$this->Lang('min').'.</option>';			
		$output .= '</select> ';

		return $output;		
	}

	/**
	 vDraw colors dropdown box
	 * @param $color - selected
	 * @param $draw
	 * @param $draw_ddl - draw ddl or return selected value
	*/	
	private function DrawColors($color = '', $draw = true, $draw_ddl = true)
	{		
		$output = '';
	
		// draw colors ddl
		$arr_colors = self::GetColorsByName();
		$output = '<table class="cal_left" border="0" cellpadding="0" cellspacing="0"><tr>';
		if($draw_ddl){
			$output .= '<td class="cal_left" valign="middle">';
			$output .= '<select class="form_select" name="category_color" id="category_color" onChange="javascript:phpCalendar.changeColor(\'colorBox\', this.value);">';
			$output .= '<option value="">-- '.$this->Lang('default').' --</option>';
			foreach($arr_colors as $key => $val){
				$output .= '<optgroup label="'.$key.'">';
				foreach($val as $v_key => $v_val){
					$selected = '';
					if($color == $v_key){
						$selected = ' selected="selected"';
					}
					$output .= '<option value="'.$v_key.'"'.$selected.'>'.$v_val.'</option>';				
				}
				$output .= '</optgroup>';
			}			
			$output .= '</select>&nbsp;';
			$output .= '</td>';
		}
		$output .= '<td class="cal_left" valign="top">';
		$output .= '<div id="colorBox" class="'.((!$draw_ddl) ? 'color_box' : 'color_box_dll').'" style="background-color:'.$color.';layer-background-color:'.$color.';"></div>';
		$output .= '</td>';
		
		if(!$draw_ddl){
			$output .= '<td class="cal_left" valign="middle" style="padding:0 5px">';
			$color_name = self::GetColorNameByValue($color);
			$output .= ($color_name != '') ? $color_name : '<span class="cal_gray">- '.$this->Lang('default').' -</span>';
			$output .= '</td>';
		}

		$output .= '</tr></table>';
		
		if($draw) echo $output;
		else return $output;
	}
    
	/**
	 * Draw show in filter checkbox
	 * @param $selected_value
	 * @param $draw
	*/	
	private function DrawShowInFilter($selected_value = '', $draw = true)
	{
		$output = '<input '.(($selected_value == 1) ? 'checked="checked"' : '').' name="show_in_filter" id="show_in_filter" type="checkbox" value="1" />';        
        
		if($draw) echo $output;
		else return $output;
    }
    
	
	/**
	 * Get colors by name
	*/
	static private function GetColorsByName()
    {
		$colors = array(
			'Reds' => array(
				'#CD5C5C' => 'Indian Red',
				'#F08080' => 'Light Coral',
				'#FA8072' => 'Salmon',
				'#E9967A' => 'Dark Salmon',
				'#FFA07A' => 'Light Salmon',
				'#DC143C' => 'Crimson',
				'#FF0000' => 'Red',
				'#B22222' => 'Fire Brick',
				'#8B0000' => 'Dark Red'		
			),
			
			'Pinks' => array(
				'#FFC0CB' => 'Pink',
				'#FFB6C1' => 'Light Pink',
				'#FF69B4' => 'Hot Pink',
				'#FF1493' => 'Deep Pink',
				'#C71585' => 'Medium Violet Red',
				'#DB7093' => 'Pale Violet Red'
			),

			'Oranges' => array(
				'#FFA07A' => 'Light Salmon',
				'#FF7F50' => 'Coral',
				'#FF6347' => 'Tomato',
				'#FF4500' => 'Orange Red',
				'#FF8C00' => 'Dark Orange',
				'#FFA500' => 'Orange'			
			),
			
			'Yellows' => array(
				'#FFD700' => 'Gold',
				'#FFFF00' => 'Yellow',
				'#FFFFE0' => 'Light Yellow',
				'#FFFACD' => 'Lemon Chiffon',
				'#FAFAD2' => 'Light Goldenrod Yellow',
				'#FFEFD5' => 'Papaya Whip',
				'#FFE4B5' => 'Moccasin',
				'#FFDAB9' => 'Peach Puff',
				'#EEE8AA' => 'Pale Goldenrod',
				'#F0E68C' => 'Khaki',
				'#BDB76B' => 'Dark Khaki'			
			),
			
			'Purples' => array(
				'#E6E6FA' => 'Lavender',
				'#D8BFD8' => 'Thistle',
				'#DDA0DD' => 'Plum',
				'#EE82EE' => 'Violet',
				'#DA70D6' => 'Orchid',
				'#FF00FF' => 'Fuchsia',
				'#FF00FF' => 'Magenta',
				'#BA55D3' => 'Medium Orchid',
				'#9370DB' => 'Medium Purple',
				'#8A2BE2' => 'Blue Violet',
				'#9400D3' => 'Dark Violet',
				'#9932CC' => 'Dark Orchid',
				'#8B008B' => 'Dark Magenta',
				'#800080' => 'Purple',
				'#4B0082' => 'Indigo',
				'#6A5ACD' => 'Slate Blue',
				'#483D8B' => 'Dark Slate Blue'			
			),
			
			'Greens' => array(			
				'#ADFF2F' => 'Green Yellow',
				'#7FFF00' => 'Chartreuse',
				'#7CFC00' => 'Lawn Green',
				'#00FF00' => 'Lime',
				'#32CD32' => 'Lime Green',
				'#98FB98' => 'Pale Green',
				'#90EE90' => 'Light	Green',
				'#00FA9A' => 'Medium Spring Green',
				'#00FF7F' => 'Spring Green',
				'#3CB371' => 'Medium Sea Green',
				'#2E8B57' => 'Sea Green',
				'#228B22' => 'Forest Green',
				'#008000' => 'Green',
				'#006400' => 'Dark Green',
				'#9ACD32' => 'Yellow Green',
				'#6B8E23' => 'Olive Drab',
				'#808000' => 'Olive',
				'#556B2F' => 'Dark Olive Green',
				'#66CDAA' => 'Medium Aquamarine',
				'#8FBC8F' => 'Dark Sea Green',
				'#20B2AA' => 'Light Sea Green',
				'#008B8B' => 'Dark Cyan',
				'#008080' => 'Teal'
			),
			
			'Blues' => array(
				'#00FFFF' => 'Aqua',
				'#00FFFF' => 'Cyan',
				'#E0FFFF' => 'Light Cyan',
				'#AFEEEE' => 'Pale Turquoise',
				'#7FFFD4' => 'Aquamarine',
				'#40E0D0' => 'Turquoise',
				'#48D1CC' => 'Medium Turquoise',
				'#00CED1' => 'Dark Turquoise',
				'#5F9EA0' => 'Cadet Blue',
				'#4682B4' => 'Steel Blue',
				'#B0C4DE' => 'Light Steel Blue',
				'#B0E0E6' => 'Powder Blue',
				'#ADD8E6' => 'Light Blue',
				'#87CEEB' => 'Sky Blue',
				'#87CEFA' => 'Light Sky Blue',
				'#00BFFF' => 'Deep Sky Blue',
				'#1E90FF' => 'Dodger Blue',
				'#6495ED' => 'Cornflower Blue',
				'#7B68EE' => 'Medium Slate Blue',
				'#4169E1' => 'Royal Blue',
				'#0000FF' => 'Blue',
				'#0000CD' => 'Medium Blue',
				'#00008B' => 'Dark Blue',
				'#000080' => 'Navy',
				'#191970' => 'Midnight Blue'			
			),			
			
			'Browns' => array(
				'#FFF8DC' => 'Cornsilk',
				'#FFEBCD' => 'Blanched Almond',
				'#FFE4C4' => 'Bisque',
				'#FFDEAD' => 'Navajo White',
				'#F5DEB3' => 'Wheat',
				'#DEB887' => 'Burly Wood',
				'#D2B48C' => 'Tan',
				'#BC8F8F' => 'Rosy Brown',
				'#F4A460' => 'Sandy Brown',
				'#DAA520' => 'Goldenrod',
				'#B8860B' => 'Dark Goldenrod',
				'#CD853F' => 'Peru',
				'#D2691E' => 'Chocolate',
				'#8B4513' => 'Saddle Brown',
				'#A0522D' => 'Sienna',
				'#A52A2A' => 'Brown',
				'#800000' => 'Maroon'			
			),

			'Whites' => array(
				'#FFFFFF' => 'White',
				'#FFFAFA' => 'Snow',
				'#F0FFF0' => 'Honeydew',
				'#F5FFFA' => 'Mint Cream',
				'#F0FFFF' => 'Azure',
				'#F0F8FF' => 'Alice Blue',
				'#F8F8FF' => 'Ghost White',
				'#F5F5F5' => 'White Smoke',
				'#FFF5EE' => 'Seashell',
				'#F5F5DC' => 'Beige',
				'#FDF5E6' => 'Old Lace',
				'#FFFAF0' => 'Floral White',
				'#FFFFF0' => 'Ivory',
				'#FAEBD7' => 'Antique White',
				'#FAF0E6' => 'Linen',
				'#FFF0F5' => 'Lavender Blush',
				'#FFE4E1' => 'Misty Rose'			
			),
			
			'Greys' => array(
				'#DCDCDC' => 'Gainsboro',
				'#D3D3D3' => 'Light Grey',
				'#C0C0C0' => 'Silver',
				'#A9A9A9' => 'Dark Gray',
				'#808080' => 'Gray',
				'#696969' => 'Dim Gray',
				'#778899' => 'Light Slate Gray',
				'#708090' => 'Slate Gray',
				'#2F4F4F' => 'Dark Slate Gray',
				'#000000' => 'Black'			
			)
		);
		return $colors;
	}	

	/**
	 * Get collor name by value
	 * @param $color_value
	*/	
    private static function GetColorNameByValue($color_value)
    {
		$arr_colors = self::GetColorsByName();
        foreach($arr_colors as $key => $val){
			if(is_array($val)){
				foreach($val as $v_key => $v_val){
					if($v_key == $color_value) return $v_val;
				}
			}
		}		
		return '';
	}

	////////////////////////////////////////////////////////////////////////////
	// Auxilary
	////////////////////////////////////////////////////////////////////////////
	/**
	 * Reset calendar categories operations
	*/	
	private function ResetCategoriesOperations()
	{
		if($this->isCategoriesAllowed == false) $this->SetCategoriesOperations(array());
	}

	/**
	 * Reset calendar locations operations
	*/	
	private function ResetLocationsOperations()
	{
		if($this->isLocationsAllowed == false) $this->SetLocationsOperations(array());
	}

	/**
	 * Set language
	*/	
	private function SetLanguage()
	{
        if(@file_exists($this->calDir.'langs/'.$this->langName.'.php')){
            include_once($this->calDir.'langs/'.$this->langName.'.php');
            if(@function_exists('setLanguage')){
                if(!defined('_CAL_'.$this->uPrefix.'_LANG_INCLUDED')){
                    $this->lang = setLanguage();
                    define('_CAL_'.$this->uPrefix.'_LANG_INCLUDED', true);    
                }
            }else{
				if($this->isDebug) $this->arrErrors['lang_inc_error'] = 'Your language interface option is turned on, but the system has failed to open correctly stream: <b>\'langs/'.$this->langName.'.php\'</b>. <br />The structure of the file is corrupted or invalid. Please check it or return the language option to default value: <b>\'en\'</b>!';					
			}
    	}else{
			if($this->isDebug) $this->arrErrors['lang_inc_error'] = 'Your language interface option is turned on, but the system has failed to open stream: <b>\'langs/'.$this->langName.'.php\'</b>. <br />No such file or directory. Please check it or return the language option to default value: <b>\'en\'</b>!';
    	}
        
		$this->arrWeekDays[0] = array('short'=>$this->Lang('sun'), 'long'=>$this->Lang('sunday'));
		$this->arrWeekDays[1] = array('short'=>$this->Lang('mon'), 'long'=>$this->Lang('monday'));
		$this->arrWeekDays[2] = array('short'=>$this->Lang('tue'), 'long'=>$this->Lang('tuesday'));
		$this->arrWeekDays[3] = array('short'=>$this->Lang('wed'), 'long'=>$this->Lang('wednesday'));
		$this->arrWeekDays[4] = array('short'=>$this->Lang('thu'), 'long'=>$this->Lang('thursday'));
		$this->arrWeekDays[5] = array('short'=>$this->Lang('fri'), 'long'=>$this->Lang('friday'));
		$this->arrWeekDays[6] = array('short'=>$this->Lang('sat'), 'long'=>$this->Lang('saturday'));
		
		$this->arrMonths['1'] = $this->Lang('months', 1);
		$this->arrMonths['2'] = $this->Lang('months', 2);
		$this->arrMonths['3'] = $this->Lang('months', 3);
		$this->arrMonths['4'] = $this->Lang('months', 4);
		$this->arrMonths['5'] = $this->Lang('months', 5);
		$this->arrMonths['6'] = $this->Lang('months', 6);
		$this->arrMonths['7'] = $this->Lang('months', 7);
		$this->arrMonths['8'] = $this->Lang('months', 8);
		$this->arrMonths['9'] = $this->Lang('months', 9);
		$this->arrMonths['10'] = $this->Lang('months', 10);
		$this->arrMonths['11'] = $this->Lang('months', 11);
		$this->arrMonths['12'] = $this->Lang('months', 12);

		$this->arrViewTypes['daily']['name']     = $this->Lang('daily');
		$this->arrViewTypes['weekly']['name']    = $this->Lang('weekly');
		$this->arrViewTypes['monthly']['name'] 	 = $this->Lang('monthly');
		$this->arrViewTypes['monthly_double']['name'] = $this->Lang('monthly_double');
		$this->arrViewTypes['yearly']['name'] 	 = $this->Lang('yearly');
		$this->arrViewTypes['list_view']['name'] = $this->Lang('list_view');
	}

	/**
	 * Check chache files
	*/	
	private function CheckCacheFiles()
	{		
		$files_count = 0;
		$oldest_file_name = '';
		$oldest_file_time = date('Y-m-d H:i:s');
		if($hdl = opendir($this->cacheDir)){
			while (false !== ($obj = @readdir($hdl))){
				if($obj == '.' || $obj == '..' || $obj == '.htaccess'){ 
					continue; 
				}
				$file_time = date('Y-m-d H:i:s', filectime($this->cacheDir.$obj));
				if($file_time < $oldest_file_time){
					$oldest_file_time = $file_time;
					$oldest_file_name = $this->cacheDir.$obj;
				}				
				$files_count++;
			}
		}		
		if($files_count > $this->maxCacheFiles){
			@unlink($oldest_file_name);		
		}
	}

	/**
	 * Start Caching of page
	 * @param $cachefile - name of file to be cached
	*/	
	private function StartCaching($cachefile)
	{
		if(!$this->isCachingAllowed) return false;
        if($cachefile != '' && file_exists($cachefile)) {        
            // minutes - how many time save the cache
            $cachetime = (int)$this->cacheLifetime * 60;     
            // Serve from the cache if it is younger than $cachetime
            if(file_exists($cachefile) && (filesize($cachefile) > 0) && ((time() - $cachetime) < filemtime($cachefile))){
                // the page has been cached from an earlier request
                // output the contents of the cache file
                include_once($cachefile); 
                echo '<!-- Generated from cache at '.date('H:i', filemtime($cachefile)).' -->'.$this->crLt;
                return true;
			}        
        }
        // start the output buffer
        ob_start();
	}
	
	/**
	 * Finish Caching of page
	 * @param $cachefile - name of file to be cached
	*/	
	private function FinishCaching($cachefile)
	{
		if(!$this->isCachingAllowed) return false;
		if($cachefile != ''){
			// open the cache file for writing
			$fp = @fopen($cachefile, 'w'); 
			// save the contents of output buffer to the file
			@fwrite($fp, ob_get_contents());
			// close the file
			@fclose($fp); 
			// Send the output to the browser
			ob_end_flush();
			// check if we exeeded max number of cache files
			$this->CheckCacheFiles();
		}
	}

	/**
	 * Returns list of events for week day for certain hour
	 * @param $events - array of events
	 * @param $hour
	*/	
	private function GetEventsListForWeekDay($events = array(), $hour = '')
	{
		$output = '';
		$events_count = count($events);
		$events_count_text = ($events_count > 0) ? ' ('.(($events_count > 1) ? $events_count.' '.$this->Lang('events_lc') : $events_count.' '.$this->Lang('event_lc')).')' : '';
		
		if($this->eventsDisplayType['weekly'] == 'tooltip'){
			if($events_count > 0){
				$output .= '<div class="events_list_tooltip" style="width:'.$this->weekColWidth.';" onmouseover="return overlib(\'';	
				foreach($events as $key => $cal){
					$output .= '&#8226; <label '.(($this->arrCatOperations['allow_colors']) ? 'style=color:'.$cal['color'] : '').'>'.$this->PrepareFormatedTextOverlib($cal['name'],'w').'</label><br>';
				}
				$output .= '\',CAPTION,\''.$hour.' '.$events_count_text.'\');" onmouseout="return nd();">'.$this->Lang('view').' ('.$events_count.') &raquo;</div>';
			}
		}else{
			foreach($events as $key => $cal){
                $this->arrEventTooltips[$cal['event_id']] = $this->PrepareFormatedTextOverlib($cal['description'],'w');
				$cal_name = $this->SubString($cal['name'], 6);
				$output .= '<span class="event_wrapper" onmouseover="return overlib(GL_event_tooltips['.$cal['event_id'].']'.((strlen($cal['description']) > 50) ? ', WIDTH, 210' : '').')" onmouseout="return nd();" style="'.(($this->arrCatOperations['allow_colors']) ? 'color:'.$cal['color'].';' : '').'width:'.$this->weekColWidth.';">';
				
                $output .= '&#8226;';
				if((!$this->arrEventsOperations['edit'] && !$this->arrEventsOperations['details']) || !$this->arrEventsOperations['delete']){
					$output .= '<img src="'.$this->calDir.'images/spacer.gif" width="12px" height="12px" alt="icon" />';
				}                
				if($this->arrEventsOperations['edit'] || $this->arrEventsOperations['details']){
                    $disable_all = (($this->arrEventsOperations['edit']) ? 'false': 'true');
                    $icon_edit_tooltip = (($this->arrEventsOperations['edit']) ? $this->Lang('click_to_edit') : $this->Lang('click_to_view'));
					$output .= '<img onclick="javascript:phpCalendar.callEditEventForm(\'divEditEvent\',\''.$cal['id'].'\',\''.$this->isCategoriesAllowed.'\',\''.$this->isLocationsAllowed.'\',\''.$disable_all.'\',\''.$this->token.'\',\''.$this->timeSlot.'\')" src="'.$this->calDir.'style/'.$this->cssStyle.'/images/'.(($this->arrEventsOperations['edit']) ? 'edit.gif' : 'details.gif').'" title="'.$icon_edit_tooltip.'" style="border:0px;" alt="icon" />';
				}
				if($this->arrEventsOperations['delete']){
					$output .= '<img onclick="phpCalendar.deleteEvent(\''.$cal['id'].'\');" title="'.$this->Lang('click_to_delete').'" src="'.$this->calDir.'style/'.$this->cssStyle.'/images/delete.gif" style="border:0px;" alt="delete" />';
				}
				$output .= '<label>'.$cal_name.'</label>';
				$output .= '</span>';
			}
		}
		return $output;
	}

	/**
	 * Prepare list of events for certain day
	 * @param $events
	*/	
	private function DrawEventsListForDayPrepare($events = array())
	{
		// prepare array of unique events (group events for blocks)
		$arr_help = array();
		$left = '0';
		$empty_slots = 0;
		$arr_slots = array();
        $max_key = '';
		foreach($events as $key => $val){
            //in check [#005 05.04.2014]
            if($max_key >= $key) continue;
			if(count($val) > 0){
				foreach($val as $k => $v){
                    $unique_name = $v['category_id'].'-'.$v['name'];
					if(!isset($arr_slots[$unique_name])) $arr_slots[$unique_name] = 0;
					if($v['slot'] == '1') $arr_slots[$unique_name]++;					
					if(!isset($arr_help[$unique_name.'-'.$arr_slots[$unique_name]])){
						$arr_help[$unique_name.'-'.$arr_slots[$unique_name]] = array('id'=>$v['id'], 'name'=>$v['name'], 'start'=>$key, 'slots'=>'1', 'color'=>$v['color'], 'left'=>$left, 'from'=>$key, 'to'=>'');
					}else{
                        $arr_help[$unique_name.'-'.$arr_slots[$unique_name]]['to'] = $key;
						$arr_help[$unique_name.'-'.$arr_slots[$unique_name]]['slots']++;
					}
					$left = '1';				
					$empty_slots = 0;
				}				
			}else{
				$empty_slots++;
				if($empty_slots > 0) $left = '0';				
			}
            $max_key = $key;
		}
        return $arr_help;
    }

	/**
	 * Draws list of events for certain day
	 * @param $arr_help
	 * @param $key_hour
	*/	
	private function DrawEventsListForDay($arr_help = array(), $key_hour = '')
	{
        $output = '';
		$count = 0;
        $disable_all = (($this->arrEventsOperations['edit']) ? 'false': 'true');
        $icon_edit_tooltip = (($this->arrEventsOperations['edit']) ? $this->Lang('click_to_edit') : $this->Lang('click_to_view'));
		foreach($arr_help as $key => $val){            
	        // calculate left offset, depending on number of slots		
			$hour = $this->ParseHour($val['start']);
			$min  = $this->ParseMinutes($val['start']);
			if($val['left'] == '0') $count = 0;  
			$left = (50 * $count++) + 12;
            $right = 0;
            if($this->direction == 'rtl'){
                $right = $left;
                $left = 0;
            }
            $slots = $val['slots'];
            
            if($val['from'] == $key_hour){

                // prepare times by format
                if($this->timeFormat == 'AM/PM'){
                    $i_from = $this->ConvertToHour($this->ParseHour($val['from']), $this->ParseMinutes($val['from']), true);
                    $i_to_add_slot = $this->AddSlot($val['to']);
                    $i_to = $this->ConvertToHour($this->ParseHour($i_to_add_slot), $this->ParseMinutes($i_to_add_slot), true);
                }else{
                    $i_from = $val['from'];
                    $i_to = $this->AddSlot($val['to']);
                }

                // prevent event overflow
                $i_fromHour = (int)$i_from;
                $i_toHour = ((int)$i_to != 0 || $i_to == '') ? (int)$i_to : 24;
                if($i_toHour > $this->toHour){
                    $slots = ($this->toHour - (int)$i_from);
                    if($slots == 1) $val['to'] = '';
                // prevent event underflow
                }else if($i_fromHour <= $this->fromHour){
                    // add code here
                    //echo '=='.$this->fromHour.' '.$i_from.'<>'.$i_to;
                }
                
               
                $height = (18 * (int)$slots) - 1;
                $text_color = ($val['color'] != '') ? 'color:'.$val['color'].';' : '';
                $cursor = '';    
                
                $output .= '<div title="'.$this->PrepareFormatedText($val['name']).'" class="event_day_block" style="margin-right:'.$right.'px;margin-left:'.$left.'px;height:'.$height.'px;'.$text_color.'">';
                $output .= '<span class="edb_title" '.(($this->arrEventsOperations['edit'] || $this->arrEventsOperations['details']) ? 'onclick="javascript:phpCalendar.callEditEventForm(\'divEditEvent\',\''.$val['id'].'\',\''.$this->isCategoriesAllowed.'\',\''.$this->isLocationsAllowed.'\', \''.$disable_all.'\',\''.$this->token.'\',\''.$this->timeSlot.'\')"' : '').'>'.$i_from.(($val['to'] != '') ? ' - '.$i_to : '');
                if($val['to'] == '') $output .= ' '.$this->SubString($val['name'], 5);
                $output .= '<div style="float:right;">';
                if($this->arrEventsOperations['edit'] || $this->arrEventsOperations['details']){
                    $output .= ' <img onclick="javascript:phpCalendar.callEditEventForm(\'divEditEvent\',\''.$val['id'].'\',\''.$this->isCategoriesAllowed.'\',\''.$this->isLocationsAllowed.'\', \''.$disable_all.'\',\''.$this->token.'\',\''.$this->timeSlot.'\')" src="'.$this->calDir.'style/'.$this->cssStyle.'/images/'.(($this->arrEventsOperations['edit']) ? 'edit.gif' : 'details.gif').'" title="'.$icon_edit_tooltip.'" style="cursor:pointer;border:0px;margin-top:2px;" alt="icon" />';
                    $cursor = 'cursor:pointer;';
                }
                if($this->arrEventsOperations['delete']){
                    $output .= ' <img onclick="javascript:phpCalendar.stopPropagation(event);phpCalendar.deleteEvent(\''.$val['id'].'\');" title="'.$this->Lang('click_to_delete').'" src="'.$this->calDir.'style/'.$this->cssStyle.'/images/delete.gif" style="cursor:pointer;border:0px;margin-top:2px;" alt="icon" />';
                }
                $output .= '</div></span>';
                if($val['to'] > $val['from']){
                    $output .= '<div class="edb_content" '.(($this->arrEventsOperations['edit'] || $this->arrEventsOperations['details']) ? 'onclick="javascript:phpCalendar.callEditEventForm(\'divEditEvent\',\''.$val['id'].'\',\''.$this->isCategoriesAllowed.'\',\''.$this->isLocationsAllowed.'\', \''.$disable_all.'\',\''.$this->token.'\',\''.$this->timeSlot.'\')"' : '').' style="'.$cursor.'height:'.($height-18).'px;">';
                    $output .= $this->SubString($val['name'], 15);
                    $output .= '</div>';
                }                
                $output .= '</div>';
            }
		}		
		return $output;		
	}
    
    /**
     * Fills array with time slots
     * @param $slot
    */
    private function FillTimeSlots($slot = '60')
    {
        $arrEvents = array();
        if($slot == '10') $max_blocks = 6;
        else if($slot == '15') $max_blocks = 4;
        else if($slot == '30') $max_blocks = 2;
        else if($slot == '45') $max_blocks = 4;
        else $max_blocks = 1;
        $blocks_count = 0;
        $multiplier = ($slot == '45') ? 15 : $slot;
        for($i_hour=$this->fromHour; $i_hour < $this->toHour; $i_hour++){
            for($i_block=0; $i_block < $max_blocks; $i_block++, $blocks_count++){
                if($slot == '45' && $blocks_count % 3 != 0) continue;
                $hours_part = $i_hour;
                $minutes_part = ($i_block)*$multiplier;					
                $this->PrepareMinutesHoures($minutes_part, $hours_part);
                $ind = $this->ConvertToDecimal($hours_part).':'.$minutes_part;
                $arrEvents[$ind] = array();
            }
        }
        
        return $arrEvents;
    }

	/**
	 * Returns list of events for certain hour
	 * @param $events - array of events
	 * @param $max_length
	*/	
	private function GetEventsListForHour($events = array(), $max_length = '')
	{
		#echo '<pre>';
		#print_r($events);
		#echo '</pre>';
		
		$output = '';
		foreach($events as $key => $cal){
			if($output != '') $output .= ', ';
            $this->arrEventTooltips[$cal['event_id']] = $this->PrepareFormatedTextOverlib($cal['description'],'d');
			$output .= '<span onmouseover="return overlib(GL_event_tooltips['.$cal['event_id'].']'.((strlen($cal['description']) > 50) ? ', WIDTH, 210' : '').')" onmouseout="return nd();" '.(($this->arrCatOperations['allow_colors']) ? 'style="color:'.$cal['color'].'"' : '').'>'.$cal['name'].'</span>';
			if((!$this->isEventPartsAllowed && $cal['slot'] == '1')){
				if($this->arrEventsOperations['edit'] || $this->arrEventsOperations['details']){
                    $disable_all = (($this->arrEventsOperations['edit']) ? 'false': 'true');
                    $icon_edit_tooltip = (($this->arrEventsOperations['edit']) ? $this->Lang('click_to_edit') : $this->Lang('click_to_view'));
					$output .= ' <a href="javascript:void(0);" onclick="javascript:phpCalendar.callEditEventForm(\'divEditEvent\',\''.$cal['id'].'\',\''.$this->isCategoriesAllowed.'\',\''.$this->isLocationsAllowed.'\',\''.$disable_all.'\',\''.$this->token.'\',\''.$this->timeSlot.'\')"><img src="'.$this->calDir.'style/'.$this->cssStyle.'/images/'.(($this->arrEventsOperations['edit'] ? 'edit.gif' : 'details.gif')).'" title="'.$icon_edit_tooltip.'" style="border:0px;vertical-align:middle;" alt="icon" /></a>';
				}
			}
			if($this->isEventPartsAllowed || (!$this->isEventPartsAllowed && $cal['slot'] == '1')){				
				if($max_length == '' && $this->arrEventsOperations['delete']){
					$output .= ' <a href="javascript:void(0);" onclick="javascript:phpCalendar.deleteEvent(\''.$cal['id'].'\');" title="'.$this->Lang('click_to_delete').'"><img src="'.$this->calDir.'style/'.$this->cssStyle.'/images/delete.gif" title="'.$this->Lang('click_to_delete').'" style="border:0px;vertical-align:middle;" alt="icon" /></a>';
				}
			}
		}
		if($max_length != '' && strlen($output) > $max_length){
			$output = '<label title="'.$output.'" style="cursor:help">'.$this->SubString($output, $max_length).'</label>';
		}
		return $output;
	}	
				
	/**
	 * Returns count of events for the certain day
	 * @param $event_date - day of events
	*/	
	private function GetEventCountForDay($event_date = '')
	{
		// prepare events for this day of week
		$sql = 'SELECT
					'.CALENDAR_TABLE.'.event_date,
					'.CALENDAR_TABLE.'.event_time,
					DATE_FORMAT('.CALENDAR_TABLE.'.event_time, "%H") as event_time_formatted,
					'.EVENTS_TABLE.'.name,
					'.EVENTS_TABLE.'.description
				FROM '.CALENDAR_TABLE.'
					INNER JOIN '.EVENTS_TABLE.' ON '.CALENDAR_TABLE.'.event_id = '.EVENTS_TABLE.'.id
				WHERE
					'.CALENDAR_TABLE.'.event_date = '.$event_date.'
					'.$this->PrepareWhereClauseParticipant().'
					'.$this->PrepareWhereClauseEventTime().'
				GROUP BY '.EVENTS_TABLE.'.id';
		$result = $this->DatabaseQuery($sql, CAL_ROWS_ONLY, 'Retrieve data of events for specific date');
		return $result;
	}

	/**
	 * Returns count of events for the certain month
	 * @param $event_year - $year of events
	 * @param $event_month - $month of events
	*/	
	private function GetEventsCountForMonth($event_year = '', $event_month = '')
	{
		// prepare events for this day of week
		$sql = 'SELECT
					GROUP_CONCAT(DISTINCT '.EVENTS_TABLE.'.id ORDER BY '.EVENTS_TABLE.'.id ASC SEPARATOR "'.$this->separator.'") as cnt,
					'.CALENDAR_TABLE.'.event_date,
					SUBSTRING('.CALENDAR_TABLE.'.event_date, 9, 2) as day
				FROM '.CALENDAR_TABLE.'
					INNER JOIN '.EVENTS_TABLE.' ON '.CALENDAR_TABLE.'.event_id = '.EVENTS_TABLE.'.id
                    '.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
				WHERE
					SUBSTRING('.CALENDAR_TABLE.'.event_date, 1, 4) =  \''.$event_year.'\' AND 
					SUBSTRING('.CALENDAR_TABLE.'.event_date, 6, 2) =  \''.$event_month.'\'
					'.$this->PrepareWhereClauseParticipant().'
					'.$this->PrepareWhereClauseCategory().'
                    '.$this->PrepareWhereClauseLocation().'
					'.$this->PrepareWhereClauseEventTime().'					
				GROUP BY
					SUBSTRING('.CALENDAR_TABLE.'.event_date, 9, 2)';
		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve data of events to prepare array for specific day');

		$arrEventsCount = array(
			'01'=>0, '02'=>0, '03'=>0, '04'=>0, '05'=>0, '06'=>0, '07'=>0, '08'=>0, '09'=>0, '10'=>0, '11'=>0, '12'=>0, '13'=>0, '14'=>0, '15'=>0,
			'16'=>0, '17'=>0, '18'=>0, '19'=>0, '20'=>0, '21'=>0, '22'=>0, '23'=>0, '24'=>0, '25'=>0, '26'=>0, '27'=>0, '28'=>0, '29'=>0, '30'=>0, '31'=>0);
			 
		foreach($result[0] as $key => $val){
			$cnt_array = explode($this->separator, $val['cnt']);
			$arrEventsCount[$val['day']] = count($cnt_array);
		}		
		return $arrEventsCount;
	}

	/**
	 * Returns list of events for the certain month
	 * @param $event_year - $year of events
	 * @param $event_month - $month of events
	 * @param $show_id
	*/	
	private function GetEventsListForMonth($event_year = '', $event_month = '', $show_id = false)
	{
        $tooltip_width = 140;
        
		// prepare events for this day of week
		$sql = 'SELECT ';
		if($this->arrParameters['view_type'] == 'monthly' && $this->eventsDisplayType['monthly'] == 'tooltip'){
            if($this->arrCatOperations['allow_colors']) $span_color = '<span style=color:", IF('.EVENTS_CATEGORIES_TABLE.'.color IS NOT NULL, '.EVENTS_CATEGORIES_TABLE.'.color, "#000000"), ";>';
			else $span_color = '<span>';
			$sql .= 'GROUP_CONCAT(CONCAT("'.$span_color.'", '.EVENTS_TABLE.'.name, "</span>") ORDER BY '.CALENDAR_TABLE.'.event_time ASC SEPARATOR "'.$this->separator.'") as cnt, ';
		}else{
            $tooltip_width = ($this->arrParameters['view_type'] == 'list_view') ? 170 : 150;
			if($this->arrCatOperations['allow_colors']) $span_color = ' style=\"color:", IF('.EVENTS_CATEGORIES_TABLE.'.color IS NOT NULL, '.EVENTS_CATEGORIES_TABLE.'.color, "#000000"), ";\"';
			else $span_color = '';
            $target = ($this->eventsLinkTarget != '') ? ' target=\"'.$this->eventsLinkTarget.'\"' : '';
			if($show_id){
				$time_format_str = ($this->isShowTime) ? 'TIME_FORMAT('.CALENDAR_TABLE.'.event_time, "'.$this->timeFormatSQL.'"), " - "' : '""';
                $sql .= 'GROUP_CONCAT(CONCAT("<a href=\"", IF('.EVENTS_TABLE.'.url != "", '.EVENTS_TABLE.'.url, CONCAT("javascript:phpCalendar.eventsDetails(", '.EVENTS_TABLE.'.id, ")")),';
                if($this->useStoredProcedures) $sql .= '"\" onmouseover=\"return overlib(\'", apphp_text_encode_overlib('.EVENTS_TABLE.'.description), "\',WIDTH,140)\" onmouseout=\"return nd();\">",';
                else $sql .= '"\" onmouseover=\"return overlib(GL_event_tooltips[", '.EVENTS_TABLE.'.id, "],WIDTH,'.$tooltip_width.')\" onmouseout=\"return nd();\"'.$span_color.'>",'; 
                $sql .= $time_format_str.', '.EVENTS_TABLE.'.name, "</a>") ORDER BY '.CALENDAR_TABLE.'.event_time ASC SEPARATOR "'.$this->separator.'") as cnt, ';
			}else{
				if($this->arrParameters['view_type'] == 'monthly_double'){ /* show only info without description */
                    if($this->useStoredProcedures) $sql .= 'GROUP_CONCAT(CONCAT("<span '.$span_color.'>", apphp_text_encode('.EVENTS_TABLE.'.name), "</span>") ORDER BY '.CALENDAR_TABLE.'.event_time ASC SEPARATOR "'.$this->separator.'") as cnt, ';
                    else $sql .= 'GROUP_CONCAT(CONCAT("<span '.$span_color.'>", REPLACE(REPLACE(REPLACE('.EVENTS_TABLE.'.name, "\\\\", "&#92;"), "\'", "&#39;"), "\"", "&#34;"), "</span>") ORDER BY '.CALENDAR_TABLE.'.event_time ASC SEPARATOR "'.$this->separator.'") as cnt, ';				
				}else{
                    $sql .= 'GROUP_CONCAT(CONCAT(IF('.EVENTS_TABLE.'.url != "", CONCAT("<a'.$target.' href=\"", '.EVENTS_TABLE.'.url, "\">"), ""),';
                    if($this->useStoredProcedures) $sql .= '"<span onmouseover=\"return overlib(\'", apphp_text_encode_overlib('.EVENTS_TABLE.'.description), "\',WIDTH,150)\" onmouseout=\"return nd();\" '.$span_color.'>",';
                    else $sql .= '"<span onmouseover=\"return overlib(GL_event_tooltips[", '.EVENTS_TABLE.'.id, "],WIDTH,'.$tooltip_width.')\" onmouseout=\"return nd();\" '.$span_color.'>",';                        
                    $sql .= EVENTS_TABLE.'.name, "</span>", IF('.EVENTS_TABLE.'.url != "", "</a>", "")) ORDER BY '.CALENDAR_TABLE.'.event_time ASC SEPARATOR "'.$this->separator.'") as cnt, ';
				}
			}
		}
		$sql .= CALENDAR_TABLE.'.event_date,
                    GROUP_CONCAT(CONCAT('.EVENTS_TABLE.'.id, "===", '.EVENTS_TABLE.'.description) ORDER BY '.CALENDAR_TABLE.'.event_time ASC SEPARATOR "'.$this->separator.'") as id_description,
                    SUBSTRING('.CALENDAR_TABLE.'.event_date, 9, 2) as day
				FROM '.CALENDAR_TABLE.'
					INNER JOIN '.EVENTS_TABLE.' ON '.CALENDAR_TABLE.'.event_id = '.EVENTS_TABLE.'.id
					'.(($this->isCategoriesAllowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ON '.EVENTS_TABLE.'.category_id = '.EVENTS_CATEGORIES_TABLE.'.id ' : '').'
				WHERE
					SUBSTRING('.CALENDAR_TABLE.'.event_date, 1, 4) = \''.$event_year.'\' AND 
					SUBSTRING('.CALENDAR_TABLE.'.event_date, 6, 2) = \''.$event_month.'\'
					'.$this->PrepareWhereClauseParticipant().'
					'.$this->PrepareWhereClauseCategory().'
                    '.$this->PrepareWhereClauseLocation().'
					'.$this->PrepareWhereClauseEventTime().'
					'.$this->PrepareWhereClauseFromToTime().'					
				GROUP BY
					SUBSTRING('.CALENDAR_TABLE.'.event_date, 9, 2),
					'.CALENDAR_TABLE.'.event_date';
					
				// Old code - fixed for sql_mode=only_full_group_by 
				//GROUP BY
				//	SUBSTRING('.CALENDAR_TABLE.'.event_date, 9, 2) ';
					

		$result = $this->DatabaseQuery($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC, 'Retrieve data of events to prepare array for specific month');

		$arrEventsList = array(
			'01'=>0, '02'=>0, '03'=>0, '04'=>0, '05'=>0, '06'=>0, '07'=>0, '08'=>0, '09'=>0, '10'=>0, '11'=>0, '12'=>0, '13'=>0, '14'=>0, '15'=>0,
			'16'=>0, '17'=>0, '18'=>0, '19'=>0, '20'=>0, '21'=>0, '22'=>0, '23'=>0, '24'=>0, '25'=>0, '26'=>0, '27'=>0, '28'=>0, '29'=>0, '30'=>0, '31'=>0);

		foreach($result[0] as $key => $val){
			$cnt_array = explode($this->separator, $val['cnt']);
			$arrEventsList[$val['day']] = $cnt_array;
            // prepare array of event id/description
            $id_array = explode($this->separator, $val['id_description']);
            foreach($id_array as $id_key){
                $id_key_array = explode('===', $id_key);
                $id_key = isset($id_key_array[0]) ? $id_key_array[0] : '';
                $id_description = isset($id_key_array[1]) ? $id_key_array[1] : '';
                if(!isset($this->arrEventTooltips[$id_key])) $this->arrEventTooltips[$id_key] = $this->PrepareFormatedTextOverlib($id_description,'m');
            }
		}
		
		#echo '<pre>';
		#print_r($arrEventsList);
		#echo '</pre>';

		return $arrEventsList;
	}
	
	/**
	 * Returns list of events for the certain month's day
	 * @param $actday - day of month
	 * @param $month
	 * @param $year
	*/	
	private function GetMonthlyDayEvents($actday, $month = '', $year = '')
	{
		$total = '';
		$events_list = '';
		$events_count = 0;
		$events = $this->arrTemp;
        
        if(isset($events[$this->ConvertToDecimal($actday)])){
			$arrEvents = $this->RemoveDuplications($events[$this->ConvertToDecimal($actday)]);			
		}else{
			$arrEvents = array();
		}
        
		if(isset($events[$this->ConvertToDecimal($actday)]) && is_array($events[$this->ConvertToDecimal($actday)])){			
			if(($this->arrParameters['view_type'] == 'monthly' || $this->arrParameters['view_type'] == 'monthly_double') && $this->eventsDisplayType['monthly'] == 'tooltip'){
				//in check [#001 17.03.2010] $arrEvents = explode($this->separator, $events[$this->ConvertToDecimal($actday)][0]);
				$events_list .= '<div class="events_list_tooltip" onmouseover="return overlib(\'';	
				foreach($arrEvents as $event){					
					$events_list .= '&#8226; '.$this->PrepareFormatedTextOverlib($event,'m').'<br />';
					if($total != '' && ++$events_count >= $total) break;
				}
				$events_list .= '\',CAPTION,\''.(($year.$month != '') ? $this->Lang('months', (int)$month).' '.$actday.', '.$year : $actday).'\');" onmouseout="return nd();">'.$this->Lang('view_events').' &raquo;</div>';
			}else{
                // list
				$events_list .= '<div class="events_list_inline">';
				foreach($arrEvents as $event){
					$events_list .= '&#8226; '.$event.'<br />';
					if($total != '' && ++$events_count >= $total) break;
				}
				$events_list .= '</div>';				
			}
			
		}		
		return $events_list;		
	}
	
	/**
	 * Draws monthly day cell with events and scrolling
	 * @param $events_count - count of events
	 * @param $actday - day of month
	 * @param $month
	 * @param $year
	 * @param $draw
	*/	
	private function DrawMonthlyDayCell($events_count, $actday, $month = '', $year = '', $draw = true)
	{
        $events_list = '';
		if($events_count > 5){
			$cel_height = number_format(((int)$this->calHeight)/6 * 4/5, '0').'px';
			$cel_id = $this->arrParameters['month'].$this->ConvertToDecimal($actday);
			if($this->eventsDisplayType['monthly'] == 'inline'){
				$events_list .= '<div style="height:'.$cel_height.';overflow-y:hidden;overflow-x:hidden;" id="divDayEventContainer'.$cel_id.'">';
			}
			$events_list .= $this->GetMonthlyDayEvents($actday, $month, $year);
			if($this->eventsDisplayType['monthly'] == 'inline'){
				$events_list .= '</div>';
				$events_list .= '<a id="dayEventLinkShow'.$cel_id.'" style="display:" href="javascript:phpCalendar.toggleCellScroll(\''.$cel_id.'\');">'.$this->Lang('show_all').' &raquo;</a>';
				$events_list .= '<a id="dayEventLinkCollapse'.$cel_id.'" style="display:none;" href="javascript:phpCalendar.toggleCellScroll(\''.$cel_id.'\');">&laquo; '.$this->Lang('close_lc').'</a>';
			}
		}else{
			$events_list .= $this->GetMonthlyDayEvents($actday, $month, $year);
		}
        if($draw) echo $events_list;
		else return $events_list;
	}
	
	/**
	 * Draws monthly day cell with events and scrolling
	 * @param $events_count - count of events
	 * @param $actday - day of month
	 * @param $month
	 * @param $year
	 * @param $draw
	*/	
	private function DrawMonthlyDoubledDayCell($events_count, $actday, $month = '', $year = '', $draw = true)
	{
		$events = $this->arrTemp;		
        if(isset($events[$this->ConvertToDecimal($actday)])){
			$arrEvents = $this->RemoveDuplications($events[$this->ConvertToDecimal($actday)]);			
		}else{
			$arrEvents = array();
		}
		$events_list = '';
		
		if($events_count > 0){
			$events_count_text = ($events_count > 0) ? ' ('.(($events_count > 1) ? $events_count.' '.$this->Lang('events_lc') : $events_count.' '.$this->Lang('event_lc')).')' : '';
			$caption_text = (($year.$month != '') ? $this->Lang('months', (int)$month).' '.$actday.', '.$year : $actday);
			$events_list .= '<div style="display:block;background-color:#a66500;width:15px;height:6px;" onmouseover="return overlib(\'';	

			$events_list_overlib = '';
			foreach($arrEvents as $event){
				$events_list_overlib .= '&#8226; '.$this->PrepareFormatedTextOverlib($event,'md').'<br />';
			}
			$events_list .= $events_list_overlib;
			$events_list .= '\',CAPTION,\''.$caption_text.$events_count_text.'\');" onmouseout="return nd();"></div>';
		}
        if($draw) echo $events_list;
		else return $events_list;
	}	

	/**
	 * Check if parameters is 4-digit year
	 * @param $year - string to be checked if it's 4-digit year
	*/	
	private function IsYear($year = '')
	{
		if(strlen($year) != 4 || !ctype_digit($year)) return false;
		else return true;
	}

	/**
	 * Check if parameters is month
	 * @param $month - string to be checked if it's 2-digit month
	*/	
	private function IsMonth($month = '')
	{
		if((strlen($month) != 2) || !ctype_digit($month) || ($month > 12)) return false;
		else return true;
	}

	/**
	 * Check if parameters is day
	 * @param $day - string to be checked if it's 2-digit day
	*/	
	private function IsDay($day = '')
	{
		if((strlen($day) != 2) || !ctype_digit($day) || ($day > 31)) return false;
		else return true;
	}

	/**
	 * Remove duplications from array
	 * @param $arr_input
	*/	
	private function RemoveDuplications($arr_input = array())
	{
		$arr_output = array();
		if(is_array($arr_input)){
			foreach($arr_input as $key => $val){
				$arr_output[$val] = $val;
			}			
		}
		return $arr_output;
	}

	/**
	 * Prepare minutes and houres
	 * @param &$minutes_part
	 * @param &$hours_part
	*/	
	private function PrepareMinutesHoures(&$minutes_part, &$hours_part)
	{
		if($minutes_part == '0' || $minutes_part == '60'){
			$minutes_part = '00';
			if($minutes_part == '60') $hours_part += 1;						
		}
	}

	/**
	 * Convert to decimal number with leading zero
	 * @param $number
	*/	
	private function ConvertToDecimal($number)
	{
		return (($number < 0) ? '-' : '').((abs($number) < 10) ? '0' : '').abs($number);
	}

	/**
	 * Convert to hour formar with leading zero
	 * @param $number
	 * @param $minutes
	 * @param $use_format
	*/	
	private function ConvertToHour($number, $minutes = '00', $use_format = false)
	{
		$number = (($number < 10 && strlen($number) == 1) ? '0'.$number : $number);
		if($use_format){
			if($this->timeFormat == '24'){
				return $number.':'.$minutes;	
			}else{
				return (int)(($number <= 12) ? $number : ($number - 12)).':'.$minutes.' '.(($number < 12) ? 'AM' : 'PM');	
			}
		}else{
			return $number.':'.$minutes;	
		}		
	}
	
	/**
	 * Parse hour from hour format string
	 * @param $hour
	*/	
	private function ParseHour($hour)
	{
		$hour_array = explode(':', $hour);
		return (isset($hour_array[0]) ? $hour_array[0] : '0');
	}	

	/**
	 * Parse minutes from hour format string
	 * @param $hour
	*/	
	private function ParseMinutes($hour)
	{
		$hour_array = explode(':', $hour);
		return (isset($hour_array[1]) ? $hour_array[1] : '0');
	}	

	/**
	 * Get formatted microtime
	*/	
    private function GetFormattedMicrotime()
	{
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }
	
	/**
	 * Prepare option for hours dropdown box
	 * @param $hour
	 * @param $i_hour
	 * @param $i_block
	 * @param $blocks_count
	*/
	private function PrepareOption($hour, $i_hour, $i_block = '', $blocks_count = 0)
	{
        if($this->timeSlot == '10' || $this->timeSlot == '15' || $this->timeSlot == '30' || $this->timeSlot == '45'){
            $multiplier = ($this->timeSlot == '45') ? 15 : $this->timeSlot;
			$hours_part = $i_hour;
			$minutes_part = ($i_block)*$multiplier;					
			$this->PrepareMinutesHoures($minutes_part, $hours_part);
			$i_converted = $this->ConvertToDecimal($hours_part);
			$i_converted_hour = $this->ConvertToHour($hours_part, $minutes_part);
			$i_converted_hour_formated = $this->ConvertToHour($hours_part, $minutes_part, true);
            if($this->timeSlot == '45' && $blocks_count % 3 != 0) return '';
			return '<option value="'.$i_converted_hour.'"'.(($hour == $i_converted) ? ' selected="selected"' : '').'>'.(($this->timeFormat == '24') ? $i_converted_hour : $i_converted_hour_formated).'</option>';
		}else{
            // 60 or 120
			$i_converted = $this->ConvertToDecimal($i_hour);
			$i_converted_hour = $this->ConvertToHour($i_hour);
			$i_converted_hour_formated = $this->ConvertToHour($i_hour, '00', true);
			return '<option value="'.$i_converted_hour.'"'.(($hour == $i_converted) ? ' selected="selected"' : '').'>'.(($this->timeFormat == '24') ? $i_converted_hour : $i_converted_hour_formated).'</option>';
		}	
	}

	/**
	 * Prepare text for SQL
	 * @param $string
	*/
	private function PrepareText($string)
	{
		$search	= array("\\","\0","\n","\r","\x1a","'",'"');
		$replace = array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
		return str_replace($search, $replace, $string);
	}

	/**
	 * Prepare text for HTML
	 * @param $string
	*/
	private function PrepareFormatedText($string)
	{
		$search	= array('"', "'");
		$replace = array("&#34;", "&#39;");
		return str_replace($search, $replace, $string);
	}
    
	/**
	 * Prepare text for HTML
	 * @param $string
	*/
	private function PrepareFormatedTextTooltips($string)
	{
		$search	= array('&#92;&#39;', '&#92;&#34;', '&#92;&#92;', "\r\n");
		$replace = array('\"', "\'", "\\\\", '');
		return str_replace($search, $replace, $string);
	}  
	
	/**
	 * Prepare text for overlib tooltips
	 * @param $string
	 * @param $type
	*/
	private function PrepareFormatedTextOverlib($string, $type = '')
	{
		$string = str_replace(array("\\", '&#92;'), '&#92;&#92;', $string);
		$string = str_replace(array('"', '&#34;'), '&#92;&#34;', $string);
		$string = str_replace(array("'", '&#39;'), '&#92;&#39;', $string);
		if($type == 'd'){
			$string = str_replace(array('&#92;&#92;&#39;'), '&#39;', $string);
			$string = str_replace(array('&#92;&#92;&#34;'), '&#34;', $string);			
		}else if($type == 'w' || $type == 'm'){
			$string = str_replace(array('&#92;&#39;', '&#92;&#92;&#39;'), '&#39;', $string);
			$string = str_replace(array('&#92;&#34;', '&#92;&#92;&#34;'), '&#34;', $string);
		}
		
		return $string;
	}
		
	/**
	 * Prepare selected category where clause for SELECT SQL
	 * @param $selected_category
	*/
	private function PrepareWhereClauseCategory($selected_category = '')
	{
        if(!$this->isCategoriesAllowed) return '';
        if($this->categoryID != ''){
            $sel_category = $this->categoryID;
        }else if($selected_category != ''){
            $sel_category = $selected_category;
        }else{
            $sel_category = $this->arrParameters['selected_category'];
        }
		return ($sel_category !== '') ? ' AND '.EVENTS_TABLE.'.category_id = '.(int)$sel_category : '';
	}

	/**
	 * Prepare selected location where clause for SELECT SQL
	 * @param $selected_location
	*/
	private function PrepareWhereClauseLocation($selected_location = '')
	{
        if(!$this->isLocationsAllowed) return '';
		$sel_location = ($selected_location != '') ? $selected_location : $this->arrParameters['selected_location'];
		return ($sel_location !== '') ? ' AND '.EVENTS_TABLE.'.location_id = '.(int)$sel_location : '';
	}
	
	/**
	 * Prepare Event Type where clause for SELECT SQL 
	*/
	private function PrepareWhereClauseEventTime()
	{
		$output = '';
		if($this->timeSlot == '60'){
			$output = ' AND SUBSTRING('.CALENDAR_TABLE.'.event_time, 4, 2) = \'00\' ';
		}else if($this->timeSlot == '30'){
			$output = ' AND (SUBSTRING('.CALENDAR_TABLE.'.event_time, 4, 2) = \'00\' OR SUBSTRING('.CALENDAR_TABLE.'.event_time, 4, 2) = \'30\') ';
        }else if($this->timeSlot == '10'){
			$output = ' AND (SUBSTRING('.CALENDAR_TABLE.'.event_time, 5, 1) = \'0\') ';            
		}else{
			// 45 min. - all variations
			// 15 min. - all variations
		}
		return $output;
	}
	
	/**
	 * Prepare From-To where clause for SELECT SQL 
	*/
	private function PrepareWhereClauseFromToTime()
	{
		$output = '';
		if($this->fromHour != '0') $output .= ' AND SUBSTRING('.CALENDAR_TABLE.'.event_time, 1, 2) >= \''.$this->ConvertToDecimal($this->fromHour).'\' ';
		if($this->toHour != '0') $output .= ' AND SUBSTRING('.CALENDAR_TABLE.'.event_time, 1, 2) < \''.$this->ConvertToDecimal($this->toHour).'\' ';	
		return $output;
	}

	/**
	 * Prepare selected participant where clause for SELECT SQL 
	*/
	private function PrepareWhereClauseParticipant()
	{
		return (($this->participantID) ? ' AND '.EVENTS_TABLE.'.participant_id = '.(int)$this->participantID : '');
	}

	/**
	 * Insert Events Occurrences
	 * @param $insert_id
	 * @param $start_date
	 * @param $finish_date
	 * @param $from_time
	 * @param $to_time
	 * @param $week_days
	 * @param $event_repeat_type
	 * @param $event_repeat_every
	*/
	private function InsertEventsOccurrencesRepeatedly($insert_id, $start_date, $finish_date, $from_time, $to_time, $week_days = array(), $event_repeat_type, $event_repeat_every)
	{		
		$current_date  = $start_date.' '.$from_time;
		$finish_date   = $finish_date.' '.$to_time;
		$start_hour    = $this->ParseHour($from_time);
		$start_minutes = $this->ParseMinutes($from_time);
		$event_from_year  = substr($start_date, 0, 4);
		$event_from_month = substr($start_date, 5, 2);
		$event_from_day   = substr($start_date, 8, 2);
		
		$repeat_on_weekday_num = $this->GetParameter('repeat_on_weekday_num');
		$repeat_on_weekday = $this->GetParameter('repeat_on_weekday');
		//$repeat_on_weekday_num_count = 0;

		$sql = 'INSERT INTO '.CALENDAR_TABLE.' (id, event_id, event_date, event_time, slot, unique_key) VALUES ';				
		$offset = 0;
		$can_add = true;
		
		$sql_parts = 0;
		$prev_date = '';
		$unique_key = '';
		$weeks_count = 0;
		$month_count = 0;
		$month_curr = -1;
		while($current_date < $finish_date){
			$current = getdate(mktime($start_hour,$start_minutes+$offset,0,$event_from_month,$event_from_day,$event_from_year));
			$curr_date = $current['year'].'-'.$this->ConvertToDecimal($current['mon']).'-'.$this->ConvertToDecimal($current['mday']);
			$curr_time = $this->ConvertToDecimal($current['hours']).':'.$this->ConvertToDecimal($current['minutes']);
			$current_date = $curr_date.' '.$curr_time;
			
			// check if can insert in this week day
			if(
				($event_repeat_type == 'weekly' && $week_days[$current['wday']] == 'on') || 
				($event_repeat_type == 'monthly' && $repeat_on_weekday == $current['wday'])
			){
				if(!$this->allowEditingEventsInPast && ($current_date < date('Y-m-d H:i:s'))){						
					$can_add = false;
				}
				if($current_date < $finish_date && ($curr_time >= $from_time) && ($curr_time < $to_time)){					
					if($curr_date != $prev_date){
						$unique_key = $this->GetRandomString(10);
						$prev_date = $curr_date;						
						$slot = '1';
					}else{
						$slot = '0';
					}
					if($event_repeat_type == 'weekly'){
						if($weeks_count % $event_repeat_every == 0){
							if($sql_parts++ > 0) $sql .= ', ';
							$sql .= '(NULL, '.(int)$insert_id.', \''.$curr_date.'\', \''.$curr_time.'\', '.$slot.', \''.$unique_key.'\')';
						}
					}else if($event_repeat_type == 'monthly'){						
						if($month_count % $event_repeat_every == 0){
							if($this->CheckRepeatOnWeekDayInMonth($curr_date, $repeat_on_weekday, $repeat_on_weekday_num)){
								if($sql_parts++ > 0) $sql .= ', ';
								$sql .= '(NULL, '.(int)$insert_id.', \''.$curr_date.'\', \''.$curr_time.'\', '.$slot.', \''.$unique_key.'\')';
							}							
						}
					}
				}
			}			
			$offset += $this->timeSlot;
			
			// calculate days difference
			$days_diff = round((strtotime($current_date) - strtotime($start_date.' '.$from_time))/60/60/24);
			$weeks_count = (int)($days_diff/7);
			$month_count = (int)($days_diff/30);
		}
		if(!$sql_parts) $can_add = false;
		if(!$can_add){
			if(!$sql_parts) $this->arrMessages[] = $this->Message('error', $this->Lang('error_no_dates_found'));
			else $this->arrMessages[] = $this->Message('error', $this->Lang('msg_editing_event_in_past'));		
		}else{
			// add event occurrences to calendar
			if(!$this->DatabaseVoidQuery($sql, 'Insert event occurrences to calendar - repeatedly')){
				$this->arrMessages[] = $this->Message('error', $this->Lang('error_inserting_new_events'));
			}else{
				return true;
				$this->DeleteCache();
			}
		}
        return false;
	}

	/**
	 * Insert Events Occurrences
	 * @param $insert_id
	 * @param $start_date
	 * @param $finish_date
	 * @param $event_from_hour
	 * @param $event_to_hour
	 * @param $event_from_day
	 * @param $event_from_month
	 * @param $event_from_year
	 * @param $check_if_exists
	 * @param $delete_events
	 * @param $event_unique_key
	*/
	private function InsertEventsOccurrences($insert_id, $start_date, $finish_date, $event_from_hour, $event_to_hour, $event_from_day, $event_from_month, $event_from_year, $check_if_exists = true, $delete_events = false, $event_unique_key = '')
	{
		$current_date = $start_date;
		$start_hour = $this->ParseHour($event_from_hour);
		$start_minutes = $this->ParseMinutes($event_from_hour);
		// check for double occurrences of events
		if(!$this->allowEventsMultipleOccurrences){
			$sql_check = 'SELECT id FROM '.CALENDAR_TABLE.' WHERE (';
			$check_if_exists = true;
		}else{
			$sql_check = 'SELECT id FROM '.CALENDAR_TABLE.' WHERE event_id = '.(int)$insert_id.' AND '.(($delete_events) ? 'unique_key != \''.$event_unique_key.'\' AND' : '').' (';
		}
		$sql = 'INSERT INTO '.CALENDAR_TABLE.' (id, event_id, event_date, event_time, slot, unique_key) VALUES ';				
		$offset = 0;
		$value_parts = 0;
		$can_add = true;

		$convToHour = $this->ConvertToHour($this->toHour);
		$convFromHour = $this->ConvertToHour($this->fromHour);

		$sql_check_inner = '';
		$unique_key = $this->GetRandomString(10);
		while($current_date < $finish_date){
			$current = getdate(mktime($start_hour,$start_minutes+$offset,0,$event_from_month,$event_from_day,$event_from_year));
			$curr_date = $current['year'].'-'.$this->ConvertToDecimal($current['mon']).'-'.$this->ConvertToDecimal($current['mday']);
			$curr_time = $this->ConvertToDecimal($current['hours']).':'.$this->ConvertToDecimal($current['minutes']);
			$current_date = $curr_date.' '.$curr_time;					
			if(!$this->allowEditingEventsInPast && ($current_date < date('Y-m-d H:i:s'))){						
				$can_add = false;
			}
			if($current_date < $finish_date && ($curr_time < $convToHour) && ($curr_time >= $convFromHour)){
				if($value_parts > 0){					
					$sql .= ', ';
					$slot = '0';
				}else{
					$slot = '1';
				}
				$sql .= '(NULL, '.(int)$insert_id.', \''.$curr_date.'\', \''.$curr_time.'\', '.$slot.', \''.$unique_key.'\')';
				if($offset > 0) $sql_check_inner .= ' OR ';
				$sql_check_inner .= '(event_date = \''.$curr_date.'\' AND event_time = \''.$curr_time.'\')';
				$value_parts++;
			}											
			$offset += $this->timeSlot;
		}
		$sql_check .= (($sql_check_inner != '') ? $sql_check_inner : '1=1').')';
		if(!$can_add){
			$this->arrMessages[] = $this->Message('error', $this->Lang('msg_editing_event_in_past'));		
		}else{
			// check if such event occurrences already exist
			if($check_if_exists && $this->DatabaseQuery($sql_check, CAL_ROWS_ONLY, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC, 'Check if occurrences of specific event already exist') > 0){
				if(!$this->allowEventsMultipleOccurrences){
					$this->arrMessages[] = $this->Message('error', $this->Lang('error_duplicate_events_inserting'));					
				}else{
					$this->arrMessages[] = $this->Message('error', $this->Lang('error_duplicate_event_inserting'));
				}
			}else{
				// delete occurrences of event (for daily event editing)
				if($delete_events){
					$sql_delete = 'DELETE FROM '.CALENDAR_TABLE.' WHERE unique_key = \''.$event_unique_key.'\'';
					$this->DatabaseVoidQuery($sql_delete, 'Delete event occurrences from calendar');
				}
				// add event occurrences to calendar
				if(!$this->DatabaseVoidQuery($sql, 'Insert event occurrences to calendar - once')){
					$this->arrMessages[] = $this->Message('error', $this->Lang('error_inserting_new_events'));		
				}else{
					return true;
					$this->DeleteCache();					
				}
			}				
		}
		return false;
	}

	/**
	 * Check action type
	 * @param $event_action - event action
	*/
	private function IsActionType($event_action)
	{
		$type = '';
		switch($event_action)
		{
			// Categories actions
			case 'categories_add':
			case 'categories_edit':
			case 'categories_details':
			case 'categories_management':
			case 'categories_delete':
			case 'categories_update':
			case 'categories_insert':
				$type = 'category';
				break;

			// Locations actions
			case 'locations_add':
			case 'locations_edit':
			case 'locations_details':
			case 'locations_management':
			case 'locations_delete':
			case 'locations_update':
			case 'locations_insert':
				$type = 'location';
				break;
			
			// Statistics actions
			case 'events_statistics':
				$type = 'statistics';
				break;			

			// Exporting actions
			case 'events_exporting':
			case 'events_exporting_execute':
				$type = 'exporting';
				break;
			
			// Participants actions
			case 'participants_add':
			case 'participants_edit':
			case 'participants_details':
			case 'participants_management':
			case 'participants_delete':
			case 'participants_update':
			case 'participants_insert':
				$type = 'participant';
				break;

			// Events actions
			case 'events_add':
			case 'events_edit':
			case 'events_details':
			case 'events_management':
			case 'events_delete':
			case 'events_update':
			case 'events_insert':
			case 'events_delete_by_range':
			case 'events_participants_management':
            case 'events_participants_assign':
			case 'events_participants_delete':
				$type = 'event';
				break;
			default:
				break;
		}
		return $type;
	}

	/**
	 * Get query string parameter value
	 * @param $var - parameter
	 * @param $default_value
	 * @param $method - method
	*/
    private function GetParameter($var = '', $default_value = '', $method = 'request')
	{
		$output = '';
        switch($method){
            case 'get':
                $output = isset($_GET[$var]) ? $_GET[$var] : $default_value;                                
                break;
            case 'post':
                $output = isset($_POST[$var]) ? $_POST[$var] : $default_value;                                
                break;
            case 'session':
                $output = isset($_SESSION[$var]) ? $_SESSION[$var] : $default_value;                                
                break;
            default:
                $output = isset($_REQUEST[$var]) ? $_REQUEST[$var] : $default_value;                                
                break;
        }
		if($output == '' && $default_value != '') $output = $default_value;
		if(!$this->CheckInput($output)) $output = '';
		return $output;
    }

	/**
	 * Check input for bad characters
	 * @param $input
	*/
	private function CheckInput($input)
	{		
		if($input == '') return true;
		
		$error = 0;
		// removed - because WYSIWYG makes problems in FF
		// '<img', 
		$bad_string = array('%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '%3Cscrip', 'javascript:', '<script', 'script>', 'expression(', '<frame', '<iframe', '<applet', '<meta', '<style', '<form', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'ftp://', 'smb://');
		foreach($bad_string as $string_value){
			if(strstr($input, $string_value)) $error = 1;
		}
			
		// removed - because WYSIWYG makes problems in FF
		// (preg_match('/<[^>]*style*\"?[^>]*>/i', $input)) ||
		// (preg_match('/<[^>]*img*\"?[^>]*>/i', $input)) ||
		// (preg_match('/http:\/\//i', $input)) )
		// (preg_match('/https:\/\//i', $input))        
		if(preg_match('/<[^>]*script*\"?[^>]*>/i', $input) ||
		    preg_match('/<[^>]*object*\"?[^>]*>/i', $input) ||
			preg_match('/<[^>]*iframe*\"?[^>]*>/i', $input) ||
			preg_match('/<[^>]*applet*\"?[^>]*>/i', $input) ||
			preg_match('/<[^>]*meta*\"?[^>]*>/i', $input) ||			
			preg_match('/<[^>]*form*\"?[^>]*>/i', $input) ||			
			preg_match('/<[^>]*onmouseover*\"?[^>]*>/i', $input) ||
			preg_match('/<[^>]*body*\"?[^>]*>/i', $input) ||
			preg_match('/\([^>]*\"?[^)]*\)/i', $input) ||
			preg_match('/ftp:\/\//i', $input))
        {
			$error = 1;
		}
		
		$ss = $_SERVER['HTTP_USER_AGENT'];
		
		if((preg_match('/libwww/i',$ss)) ||
			(preg_match('/^lwp/i',$ss))  ||
			(preg_match('/^Jigsaw/i',$ss)) ||
			(preg_match('/^Wget/i',$ss)) ||
			(preg_match('/^Indy\ Library/i',$ss)) )
		{ 
			$error = 1;
		}
		
		if($error){
			return false;
		}
		return true;
	}

	/**
	 * Remove bad chars from input
	 * @param $str_words - input
	*/
    private function RemoveBadChars($str_words)
	{
        $found = false;
		// removed - because WYSIWYG makes problems in FF
		// '<img', 		
        $bad_string = array('select', 'drop', '--', 'insert', 'delete', 'xp_', '%20union%20', '/*', '*/union/*', '+union+', 'load_file', 'outfile', 'document.cookie', 'onmouse', '<script', 'script>', '<iframe', '<applet', '<meta', '<style', '<form', '<body', '<link', '_GLOBALS', '_REQUEST', '_GET', '_POST', 'include_path', 'prefix', 'https://', 'ftp://', 'smb://', 'onmouseover=', 'onmouseout=');
        for($i = 0; $i < count($bad_string); $i++){
            $str_words = str_ireplace($bad_string[$i], '', $str_words);
        }
        return $str_words;            
    }

	/**
	 * Get max day for month
	 * @param $year
	 * @param $month
	 * @param $day
	*/
	private function GetDayForMonth($year = '', $month = '', $day = '')
	{
		if($day < 29){
			return $day;
		}else if($day == 29){
    		if((int)$month == 2 && !checkdate(2, 29, (int)$year)){
				return 28;
			}else{
				return 29;
			}			
		}else if($day == 30){
			if((int)$month != 2){
				return 30;
			}else{
				return 28;
			}
		}else if($day == 31){
			if((int)$month == 2){
				return 28;
			}else if((int)$month == 4 || (int)$month == 6 || (int)$month == 9 || (int)$month == 11){
				return 30;
			}else{
				return 31;
			}			
		}else{
			return 30;	
		}		
	}

	/**
	 * Draws messages
	 * @param $type - message type
	 * @param $message - message text
	*/
	private function Message($type, $message)
	{
		if(!empty($message)) $message = '<div class="message_box_'.$type.'"><div class="message_content">'.$message.'</div></div>';
		return $message;	
	}

	/**
	 * Execute SQL query 
	 * @param $sql
	 * @param $return_type
	 * @param $first_row_only
	 * @param $fetch_func
	 * @param $description
	*/
	private function DatabaseQuery($sql, $return_type = CAL_DATA_ONLY, $first_row_only = CAL_ALL_ROWS, $fetch_func = CAL_FETCH_ASSOC, $description = '')
	{
		$sql_debug_text = '';
        $result = apcal_database_query($sql, $return_type, $first_row_only, $fetch_func);
        if($this->isDebug && $sql != ''){
            $num_rows = isset($result[1]) ? ' ('.$result[1].') ' : '';
			if($description != '') $sql_debug_text .= '- <b><i>'.$description.$num_rows.':</i></b><br />';
			$this->arrSQLs[] = $sql_debug_text.htmlentities($sql).'<br />'.apcal_database_error();
		}
        return $result;
    }
    
	/**
	 * Execute SQL void query 
	 * @param $sql
	 * @param $description
	*/
	private function DatabaseVoidQuery($sql, $description = '')
	{
		$sql_debug_text = '';
        $affected_rows = 0;
        $result = apcal_database_void_query($sql, $affected_rows);
        if($this->isDebug && $sql != ''){
			if($description != '') $sql_debug_text .= '- <b><i>'.$description.' ('.$affected_rows.'):</i></b><br />';
			$this->arrSQLs[] = $sql_debug_text.htmlentities($sql).'<br />'.apcal_database_error();
		}
        return $result;
    }    
	
	/**
	 * 	Creates a random string with characters 1-10 and a-z
	 * 		@param length - the length of the random string 	
	*/
	function GetRandomString($length = 10)
	{
		$rand_string = '';
		for ($i = 0; $i < $length; $i++) {
			$x = mt_rand(0, 35);
			if ($x > 9) $rand_string .= chr($x + 87);
			else $rand_string .= $x;
		}
		return $rand_string;
	}
	
	/**
	 * Check if a day is a disabled day
	 * 	 	@param $day
	*/
	private function IsDisabledDay($day)
	{
		return (isset($this->weekDisabledDays[$day]) && $this->weekDisabledDays[$day]) ? true : false;	
	}
	
    /**
     * Returns Operating System name
    */
	private function GetOSName()
	{
		// some possible outputs
		// Linux: Linux localhost 2.4.21-0.13mdk #1 Fri Mar 14 15:08:06 EST 2003 i686		
		// FreeBSD: FreeBSD localhost 3.2-RELEASE #15: Mon Dec 17 08:46:02 GMT 2001		
		// WINNT: Windows NT XN1 5.1 build 2600		
		// MAC: Darwin Ron-Cyriers-MacBook-Pro.local 10.6.0 Darwin Kernel Version 10.6.0: Wed Nov 10 18:13:17 PST 2010; root:xnu-1504.9.26~3/RELEASE_I386 i386
		$os_name = strtoupper(substr(PHP_OS, 0, 3));
		switch($os_name){
			case 'WIN':
				return 'windows'; break;
			case 'LIN':
				return 'linux'; break;
			case 'FRE':
				return 'freebsd'; break;
			case 'DAR':
				return 'mac'; break;
			default:
				return 'windows'; break;
		}
	}

	/**
	 * Return a week number in month
	 * 		@param $date
	 * 		@param $repeat_on_weekday
	 * 		@param $repeat_on_weekday_num
	*/
	function CheckRepeatOnWeekDayInMonth($date = '', $repeat_on_weekday = '', $repeat_on_weekday_num = '')
	{
		if($date == '') return false;

        $m_start = date('N',strtotime(date('n/1/Y',strtotime($date))));
        $date_start = (date('j',strtotime($date))+$m_start) / 7;
        $wnim = floor($date_start);
        if($wnim != $date_start) $wnim++;
        if($m_start > $repeat_on_weekday) $wnim--;

        return ($wnim == $repeat_on_weekday_num) ? true : false;
	}
	
	/**
	 * Initialize WYSIWYG
	*/
	private function InitWYSIWYG()
	{
		if(!$this->isWYSIWYG) return false;
        $width = ($this->GetParameter('hid_event_action') == 'events_edit') ? '397' : '315';
        echo '<link rel="stylesheet" href="'.$this->calDir.'modules/tinyeditor/css/tinyeditor.css">';
        echo '<script src="'.$this->calDir.'modules/tinyeditor/tiny.editor.packed.js"></script>';
        echo '<script type="text/javascript">
            var editor = new TINY.editor.edit("editor", {
                id: "event_description",
                width: '.$width.',
                height: 100,
                cssclass: "tinyeditor",
                controlclass: "tinyeditor-control",
                rowclass: "tinyeditor-header",
                dividerclass: "tinyeditor-divider",
                controls: ["bold", "italic", "underline", "strikethrough", "|", "undo", "redo",'.($width == '397' ? ' "unformat",' : '').' "|", "image", "link", "unlink"],
                footer: true,
                fonts: ["Verdana","Arial","Georgia","Trebuchet MS"],
                xhtml: true,
                cssfile: "custom.css",
                bodyid: "editor",
                footerclass: "tinyeditor-footer",
                toggle: {text: "source", activetext: "wysiwyg", cssclass: "toggle"},
                resize: {cssclass: "resize"},
                direction: "'.$this->direction.'"
            });            
        </script>';
	}
	
	/**
	 * Returns text for language constant
	 * 		@param $key
	 * 		@param $sub_key
	*/
	private function Lang($key = '', $sub_key = '')
	{
		if($sub_key != ''){
			return isset($this->lang[$key][$sub_key]) ? $this->lang[$key][$sub_key] : $key;
		}else{
			return isset($this->lang[$key]) ? $this->lang[$key] : $key;
		}
	}
	
	/**
	 * Adds time slot to current time
	 * @param $slot_time
	 * @param $curr_time
	*/
	private function AddSlot($slot_time, $curr_time = '')
	{
		return ($slot_time != '') ? date('H:i', strtotime($slot_time)+($this->timeSlot*60)) : $slot_time; 
	}

	/**
	 * Returns content of file
	 * 	    @param $file
	*/	
	private function FileGetContents($file)
	{
		$content = file_get_contents($this->calDir.$file);
        return $content;
	}

	/**
	 *  Cut string by last letter
	 * @param $text
	 * @param $length
	 * @param $three_dots
	 */
	private function SubString($text, $length = '0', $three_dots = true)
	{
		$output = $text;
		if(strlen($text) > $length){
			///if($this->langName == 'en') $output = substr($output, 0, (int)$length);
			$output = mb_substr($text, 0, (int)$length, 'UTF-8');
			if($three_dots) $output .= '...';
		}
		return $output;
	}
	
    /**
     * Check for F5 refresh case
     * @param $operation_randomize_code
     */
    private function CheckF5CaseValidation($operation_randomize_code = '')
	{
		if($operation_randomize_code == '') return true;
        if($this->dataSaveType == 'cookie' && isset($_COOKIE[$this->uPrefix.'operation_randomize_code']) && ($_COOKIE[$this->uPrefix.'operation_randomize_code'] == $operation_randomize_code)){
            return false;
        }else if($this->dataSaveType == 'session' && isset($_SESSION[$this->uPrefix.'operation_randomize_code']) && ($_SESSION[$this->uPrefix.'operation_randomize_code'] == $operation_randomize_code)){
            return false;
        }
		return true;
    }
	
	/**
	 * Display debug info
	*/
	private function DisplayDebugInfo()
	{
		echo '<div class="debug_info" style="width:'.$this->calWidth.';">';
		echo '<b>'.$this->Lang('debug_info').'</b>: ('.$this->Lang('total_running_time').': '.round((float)$this->endTime - (float)$this->startTime, 6).' sec.) <br />=========<br /><br />';
		if(count($this->arrErrors) > 0){
            echo '<b>ERRORS: ('.count($this->arrErrors).')</b> <a id="lnk_debug_error" href="javascript:void(0);" onclick="javascript:phpCalendar.toggleLinks(\'lnk_debug_error\',\'[-]\',\'[+]\',\'debug_error\');">[-]</a> <br />--------<br />';
            echo '<p id="debug_error" class="debug_error">'.$this->DrawErrors(false).'</p><br />';
        }else{
            echo '<b>ERRORS: ('.count($this->arrErrors).')</b> <br />--------<br /><br />';            
        }
        if(count($this->arrWarnings) > 0){
            echo '<b>WARNINGS: ('.count($this->arrWarnings).')</b> <a id="lnk_debug_warning" href="javascript:void(0);" onclick="javascript:phpCalendar.toggleLinks(\'lnk_debug_warning\',\'[-]\',\'[+]\',\'debug_warning\');">[-]</a> <br />--------<br />';
            echo '<p id="debug_warning" class="debug_warning">'.$this->DrawWarnings(false).'</p><br />';
        }else{
            echo '<b>WARNINGS: (0)</b> <br />--------<br /><br />';
        }
		echo '<b>SQL:</b> <a id="lnk_debug_sql" href="javascript:void(0);" onclick="javascript:phpCalendar.toggleLinks(\'lnk_debug_sql\',\'[-]\',\'[+]\',\'debug_sql\');">[-]</a> <br />--------<br />';
		echo '<p id="debug_sql" class="debug_sql">'.$this->DrawSQLs(false).'</p><br />';
		echo '<b>SESSION:</b> <a id="lnk_debug_session" href="javascript:void(0);" onclick="javascript:phpCalendar.toggleLinks(\'lnk_debug_session\',\'[-]\',\'[+]\',\'debug_session\');">[-]</a> <br />--------<br />';
		echo '<pre id="debug_session" class="debug_session">';
		if(isset($_SESSION)){
            foreach($_SESSION as $key => $val){
                echo htmlentities($key).' => ';
                echo (!is_array($val)) ? htmlentities($val) : $val;
                echo '<br>';
            }
        }
		echo '</pre><br />';
		echo '<b>COOKIE:</b> <a id="lnk_debug_cookie" href="javascript:void(0);" onclick="javascript:phpCalendar.toggleLinks(\'lnk_debug_cookie\',\'[-]\',\'[+]\',\'debug_cookie\');">[-]</a> <br />--------<br />';
		echo '<pre id="debug_cookie" class="debug_cookie">';
        foreach($_COOKIE as $key => $val) echo htmlentities($key).' => '.htmlentities($val).'<br>';
		echo '</pre><br />';
		echo '<b>GET:</b> <a id="lnk_debug_get" href="javascript:void(0);" onclick="javascript:phpCalendar.toggleLinks(\'lnk_debug_get\',\'[-]\',\'[+]\',\'debug_get\');">[-]</a> <br />--------<br />';
		echo '<pre id="debug_get" class="debug_get">';
        foreach($_GET as $key => $val) echo htmlentities($key).' => '.htmlentities($val).'<br>';
		echo '</pre><br />';
		echo '<b>POST:</b> <a id="lnk_debug_post" href="javascript:void(0);" onclick="javascript:phpCalendar.toggleLinks(\'lnk_debug_post\',\'[-]\',\'[+]\',\'debug_post\');">[-]</a> <br />--------<br />';
		echo '<pre id="debug_post" class="debug_post">';
        foreach($_POST as $key => $val) echo htmlentities($key).' => '.htmlentities($val).'<br>';
		echo '</pre><br />';
		echo '</div>';	
	}

}
