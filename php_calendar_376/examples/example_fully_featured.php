<?php
    // wee need to start session before we start html output 
    session_start();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="author" content="ApPHP Company">
    <meta name="generator" content="ApPHP Calendar">
    <title>ApPHP Calendar :: Standard Calendar (Fully Featured)</title>
</head>
<body>
<a href="index.php" style="position:absolute;text-decoration:none;color:#225588;">[ Index ]</a>    
<center>
<?php

    ## +-----------------------------------------------------------------------+
    ## | 1. Creating & Calling:                                                | 
    ## +-----------------------------------------------------------------------+
    ## *** define a relative (virtual) path to calendar.class.php file  
    ## *** and other files (relatively to the current file)
    ## *** RELATIVE PATH ONLY *** Ex.: '', 'calendar/' or '../calendar/'
    define ('CALENDAR_DIR', '../calendar/');                     
    require_once(CALENDAR_DIR.'inc/connection.inc.php');
    require_once(CALENDAR_DIR.'calendar.class.php');
    
    ## *** create calendar object
    $objCalendar = new Calendar();
    
    ## +-----------------------------------------------------------------------+
    ## | 2. General Settings:                                                  |
    ## +-----------------------------------------------------------------------+
    ## +-- Submission Settings & Debug Mode -----------------------------------
    ## *** set PostBack method: 'get' or 'post'
    $objCalendar->SetPostBackMethod('post');
    ## *** show debug info - false|true
    $objCalendar->Debug(false);

    ## +-- Participants Settings -----------------------------------------------------
    ## *** set participant ID (must be numeric value)
    /// $participant_id = 0;
    /// $objCalendar->SetParticipantID($participant_id);    

    ## +-- Passing Parameters -------------------------------------------------
    ## *** save http request variables between  calendar's sessions
    /// $http_request_vars = array('param1', 'param2');
    /// $objCalendar->SaveHttpRequestVars($http_request_vars);

    ## +-- Cache Settings -----------------------------------------------------
    ## *** define caching parameters:
    ## *** 1st - allow caching or not, 2nd - caching lifetime in minutes
    $objCalendar->SetCachingParameters(false, 15);
    ## *** define all caching pages
    /// $objCalendar->DeleteCache();
    
    ## +-- Languages ----------------------------------------------------------
    ## *** set interface language (default - English)
    ## *** (en) - English  (es) - Spanish     (de) - German  (fr) - French  
    ## *** (it) - Italian  (pt) - Portuguese
    $objCalendar->SetInterfaceLang('en');    

    ## +-- Week Settings ------------------------------------------------------
    ## *** set week day name length - 'short' or 'long'
    $objCalendar->SetWeekDayNameLength('long');
    ## *** set start day of the week: from 1 (Sunday) to 7 (Saturday)
    $objCalendar->SetWeekStartedDay('1');
    ## *** disable certain days of the week: from 1 (Sunday) to 7 (Saturday). Ex.: 1,2 or 7
    /// $objCalendar->SetDisabledDays(6,7);
    ## *** define showing a week number of the year
    $objCalendar->ShowWeekNumberOfYear(true);


    ## +-----------------------------------------------------------------------+
    ## | 3. Events & Categories Settings:                                      |
    ## +-----------------------------------------------------------------------+
    ## +-- Events Actions & Operations ----------------------------------------
    ## *** allow multiple occurrences for events in the same time slot: false|true - default
     $objCalendar->SetEventsMultipleOccurrences(true);
    ## *** allow editing events in past
    /// $objCalendar->EditingEventsInPast(false);
    ## *** allow deleting events in past
    /// $objCalendar->DeletingEventsInPast(false);	
    ## *** block deleting events before certain period of time (in hours)
    // $objCalendar->BlockEventsDeletingBefore(24);
    ## *** set (allow) calendar events operations
    $objCalendar->SetEventsOperations(array(
        'add'=>true,
        'edit'=>true,
        'details'=>true,
        'delete'=>true,
        'delete_by_range'=>true,
        'manage'=>true,
        'show_filter'=>true
    ));

    ## +-- Categories Actions & Operations ------------------------------------
    ## *** set (allow) using categories
    $objCalendar->AllowCategories(true);
    ## *** set calendar categories operations
    $objCalendar->SetCategoriesOperations(array(
        'add'=>true, 
        'edit'=>true,
        'details'=>true,
        'delete'=>true,
        'manage'=>true,
        'allow_colors'=>true,
        'show_filter'=>true
    ));

    ## +-- Locations Actions & Operations ------------------------------------
    ## *** set (allow) using locations
    $objCalendar->AllowLocations(true);
    ## *** set calendar locations operations
    $objCalendar->SetLocationsOperations(array(
       'add'=>true, 
       'edit'=>true,
       'details'=>true,
       'delete'=>true,
       'manage'=>true,
       'allow_colors'=>true,
       'show_filter'=>true
    ));


    ## +-----------------------------------------------------------------------+
    ## | 4. Participants Settings:                                                    | 
    ## +-----------------------------------------------------------------------+
    ## +-- Participants Settings -----------------------------------------------------
    ## *** set (allow) calendar participants operations
    $objCalendar->AllowParticipants(true);
    ## *** set participants settings
    $objCalendar->SetParticipantsOperations(array(
        'add'=>true,
        'edit'=>true,
        'details'=>true,
        'delete'=>true,
        'manage'=>true,
        'assign_to_events'=>true
    ));


    ## +-----------------------------------------------------------------------+
    ## | 5. Time Settings and Formatting:                                      | 
    ## +-----------------------------------------------------------------------+
    ## +-- TimeZone Settings --------------------------------------------------
    ## *** set timezone
    ## *** (list of supported Timezones - http://us3.php.net/manual/en/timezones.php)
    $objCalendar->SetTimeZone('America/Los_Angeles');    
    ## *** get current timezone
    /// echo $objCalendar->GetCurrentTimeZone();

    ## +-- Time Format & Settings ----------------------------------------------
    ## *** define time format - 24|AM/PM
    $objCalendar->SetTimeFormat('24');
    ## *** define allowed hours frame (from, to). Possible values: 0...24
    $objCalendar->SetAllowedHours(0, 22);
    ## *** define time slot - 15|30|45|60|120 minutes
    $objCalendar->SetTimeSlot('60');
    ## *** set showing times in Daily, Weekly and List views
    $objCalendar->ShowTime('true');
    

    ## +-----------------------------------------------------------------------+
    ## | 6. Visual Settings:                                                   | 
    ## +-----------------------------------------------------------------------+
    ## +-- Calendar Views -----------------------------------------------------
    ## *** set (allow) calendar Views
    $views = array('daily'=>true, 
                   'weekly'=>true,
                   'monthly'=>true,
                   'monthly_double'=>true,
                   'yearly'=>true,
                   'list_view'=>true);                        
    $objCalendar->SetCalendarViews($views);
    ## *** set default calendar view - 'daily'|'weekly'|'monthly'|'yearly'|'list_view'|'monthly_small'|'monthly_double'
    $objCalendar->SetDefaultView('monthly');    
    ## *** Set action link for monthly small view - file2.php or ../file3.php etc.
    /// $objCalendar->SetMonthlySmallLinks('');    

    ## +-- Calendar Actions -----------------------------------------------------
    ## *** set (allow) calendar actions
    $calendar_actions = array(
        'statistics'=>true,
        'exporting'=>true,
        'printing'=>true
    );
    $objCalendar->SetCalendarActions($calendar_actions);
    
    ## *** set CSS style: 'green'|'brown'|'blue' - default
    $objCalendar->SetCssStyle('blue');
    ## *** specify using of WYSIWYG editor
    $objCalendar->AllowWYSIWYG(true);
    ## *** set calendar width and height
    $objCalendar->SetCalendarDimensions('800px', '500px');
    ## *** set type of displaying for events
    ## *** possible values for daily   - 'inline'|'block'
    ## *** possible values for weekly  - 'inline'|'tooltip'
    ## *** possible values for monthly - 'inline'|'list'|'tooltip'
    $events_display_type = array('daily'=>'block', 'weekly'=>'tooltip', 'monthly'=>'tooltip');
    $objCalendar->SetEventsDisplayType($events_display_type);
    ## *** set Sunday color - true|false
    $objCalendar->SetSundayColor(true);    
    ## *** set calendar caption
    $objCalendar->SetCaption('ApPHP Calendar v'.Calendar::Version());


    ## +-----------------------------------------------------------------------+
    ## | 7. Draw Calendar:                                                     | 
    ## +-----------------------------------------------------------------------+
    ## *** drawing calendar
    $objCalendar->Show();

?>
</center>
</body>
</html>
