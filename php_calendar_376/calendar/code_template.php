<?php
    // wee need to start session before we start html output 
    // session_start();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>ApPHP Calendar :: Code Example</title>
</head>
<body>
This is a code template (it's commented out now).<br />
To run it you have to uncomment appropriate lines of code and define some important settings.<br /><br />
<a href="../docs/GettingStarted.html">Lean here</a> how to do it.
<?php
    ## +-----------------------------------------------------------------------+
    ## | 1. Creating & Calling:                                                
    ## +-----------------------------------------------------------------------+
    ## *** define a relative (virtual) path to calendar.class.php file  
    ## *** and other files (relatively to the current file)
    ## *** RELATIVE PATH ONLY *** Ex.: '', 'calendar/' or '../calendar/'
    // define ('CALENDAR_DIR', '');                     
    // require_once(CALENDAR_DIR.'inc/connection.inc.php');
    // require_once(CALENDAR_DIR.'calendar.class.php');
    
    ## *** create calendar object 
    ## *** 2 optional parameters: $calendar_type ('small' or 'normal') and $prefix
    ## *** Ex.: Calendar('normal', 'abc_');
    // $objCalendar = new Calendar();
    
    ## +-----------------------------------------------------------------------+
    ## | 2. General Settings:                                                 
    ## +-----------------------------------------------------------------------+
    ## +-- Submission Settings & Debug Mode -----------------------------------
    ## *** set PostBack method: 'get' or 'post'
    // $objCalendar->SetPostBackMethod('post');
    ## *** show debug info - false|true
    // $objCalendar->Debug(false);

    ## +-- Passing Parameters -------------------------------------------------
    ## *** save http request variables between  calendar's sessions
    /// $http_request_vars = array('param1', 'param2');
    /// $objCalendar->SaveHttpRequestVars($http_request_vars);

    ## +-- Cache Settings -----------------------------------------------------
    ## *** define caching parameters: 1st - allow caching or not, 2nd - caching lifetime in minutes
    ## *** default cache folder: tmp/cache/
    // $objCalendar->SetCachingParameters(false, 15);
    ## *** delete all pages in cache
    /// $objCalendar->DeleteCache();
    
    ## +-- Languages ----------------------------------------------------------
    ## *** set interface language (default - English)
    ## *** (en) - English  (es) - Spanish     (de) - German  (fr) - French  
    ## *** (it) - Italian  (pt) - Portuguese  (nl) - Netherlands
    // $objCalendar->SetInterfaceLang('en');    

    ## +-- Week Settings ------------------------------------------------------
    ## *** set week day name length - 'short' or 'long'
    // $objCalendar->SetWeekDayNameLength('long');
    ## *** set start day of the week: from 1 (Sunday) to 7 (Saturday)
    // $objCalendar->SetWeekStartedDay('1');
    ## *** disable certain days of the week: from 1 (Sunday) to 7 (Saturday). Ex.: 1,2 or 7
    /// $objCalendar->SetDisabledDays(6,7);
    ## *** define showing a week number of the year
    // $objCalendar->ShowWeekNumberOfYear(true);


    ## +-----------------------------------------------------------------------+
    ## | 3. Events & Categories Settings:                                     
    ## +-----------------------------------------------------------------------+
    ## +-- Events Actions & Operations ----------------------------------------
    ## *** allow multiple occurrences for events in the same time slot: false|true - default
    // $objCalendar->SetEventsMultipleOccurrences(true);
    ## *** allow editing events in the past
    /// $objCalendar->EditingEventsInPast(false);
    ## *** allow deleting events in the past
    /// $objCalendar->DeletingEventsInPast(false);	
    ## *** block deleting events before certain period of time (in hours)
    // $objCalendar->BlockEventsDeletingBefore(24);
    ## *** set (allow) calendar events operations
    // $objCalendar->SetEventsOperations(array(
    //    'add'=>true,
    //    'edit'=>true,
    //    'details'=>true,
    //    'delete'=>true,
    //    'delete_by_range'=>true,
    //    'manage'=>true
    // ));

    ## +-- Categories Actions & Operations ------------------------------------
    ## *** set (allow) using categories
    // $objCalendar->AllowCategories(true);
    ## *** set only allowed category ID (parameter must be a numeric value)
    /// $objCalendar->SetCategoryID(0);
    ## *** set calendar categories operations
    // $objCalendar->SetCategoriesOperations(array(
    //    'add'=>true, 
    //    'edit'=>true,
    //    'details'=>true,
    //    'delete'=>true,
    //    'manage'=>true,
    //    'allow_colors'=>true,
    //    'show_filter'=>true
    // ));

    ## +-- Locations Actions & Operations ------------------------------------
    ## *** set (allow) using locations
    // $objCalendar->AllowLocations(true);
    ## *** set calendar locations operations
    // $objCalendar->SetLocationsOperations(array(
    //    'add'=>true, 
    //    'edit'=>true,
    //    'details'=>true,
    //    'delete'=>true,
    //    'manage'=>true,
    //    'allow_colors'=>true,
    //    'show_filter'=>true
    // ));

    ## +-----------------------------------------------------------------------+
    ## | 4. Participants Settings:                                                   
    ## +-----------------------------------------------------------------------+
    ## +-- Participants Settings -----------------------------------------------------
    ## *** set participant ID (parameter must be a numeric value) who can access the events
    /// $participant_id = 0;
    /// $objCalendar->SetParticipantID($participant_id);    
    ## *** set (allow) calendar participants operations
    // $objCalendar->AllowParticipants(true);
    ## *** set participants settings
    // $objCalendar->SetParticipantsOperations(array(
    //    'add'=>true,
    //    'edit'=>true,
    //    'details'=>true,
    //    'delete'=>true,
    //    'manage'=>true,
    //    'assign_to_events'=>true
    // ));


    ## +-----------------------------------------------------------------------+
    ## | 5. Time Settings and Formatting:                                      
    ## +-----------------------------------------------------------------------+
    ## +-- TimeZone Settings --------------------------------------------------
    ## *** set timezone
    ## *** (list of supported Timezones - http://us3.php.net/manual/en/timezones.php)
    // $objCalendar->SetTimeZone('America/Los_Angeles');    
    ## *** get current timezone
    /// echo $objCalendar->GetCurrentTimeZone();

    ## +-- Time Format & Settings ----------------------------------------------
    ## *** define time format - 24|AM/PM
    // $objCalendar->SetTimeFormat('24');
    ## *** define allowed hours frame (from, to). Possible values: 0...24
    // $objCalendar->SetAllowedHours(0, 22);
    ## *** define time slot - 10|15|30|45|60|120 minutes
    // $objCalendar->SetTimeSlot('60');
    ## *** set showing times in Daily, Weekly and List Views
    // $objCalendar->ShowTime('true');
    

    ## +-----------------------------------------------------------------------+
    ## | 6. Visual Settings:                                                   
    ## +-----------------------------------------------------------------------+
    ## +-- Calendar Views -----------------------------------------------------
    ## *** set (allow) calendar Views
    // $objCalendar->SetCalendarViews(array(
    //    'daily'=>true, 
    //    'weekly'=>true,
    //    'monthly'=>true,
    //    'monthly_double'=>true,
    //    'yearly'=>true,
    //    'list_view'=>true
    // ));                        
    ## *** set default calendar view - 'daily'|'weekly'|'monthly'|'yearly'|'list_view'|'monthly_small'|'monthly_double'
    // $objCalendar->SetDefaultView('monthly');    
    ## *** Set action link for monthly small view - file2.php or ../file3.php etc.
    /// $objCalendar->SetMonthlySmallLinks('');    

    ## +-- Calendar Actions -----------------------------------------------------
    ## *** set (allow) calendar actions
    ## *** default exporting folder: tmp/export/
    // $objCalendar->SetCalendarActions(array('statistics'=>true, 'exporting'=>true, 'printing'=>true));
    // ## *** set (allow) calendar export types
    // $objCalendar->SetExportTypes(array('csv'=>true, 'xml'=>true, 'ics'=>true));
    
    ## *** set CSS style: 'green'|'brown'|'blue' - default
    // $objCalendar->SetCssStyle('blue');
    ## *** specify using of WYSIWYG editor
    // $objCalendar->AllowWYSIWYG(true);
    ## *** set calendar width and height
    // $objCalendar->SetCalendarDimensions('800px', '500px');
    ## *** set type of displaying for events
    ## *** possible values for daily   - 'inline'|'block'
    ## *** possible values for weekly  - 'inline'|'tooltip'
    ## *** possible values for monthly - 'inline'|'list'|'tooltip'
    // $objCalendar->SetEventsDisplayType(array('daily'=>'block', 'weekly'=>'tooltip', 'monthly'=>'tooltip'));
    ## *** set Sunday color - true|false
    // $objCalendar->SetSundayColor(true);    
    ## *** set calendar caption
    // $objCalendar->SetCaption('ApPHP Calendar v'.Calendar::Version());


    ## +-----------------------------------------------------------------------+
    ## | 7. Draw Calendar:                                                     
    ## +-----------------------------------------------------------------------+
    ## *** drawing calendar
    // $objCalendar->Show();
    
?>
</body>
</html>