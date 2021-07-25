<a href="index.php" style="position:absolute;text-decoration:none;color:#225588;">[ Index ]</a>    
<br />
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
    ## *** set javascript functions
    $objCalendar->SetJsFunctions();
    ## *** show debug info - false|true
    $objCalendar->Debug(true);

    ## +-----------------------------------------------------------------------+
    ## | 2. Send Notification:                                                 | 
    ## +-----------------------------------------------------------------------+
    ## *** set admin email, based on the site domain name
    $objCalendar->SetAdminEmail('info@yourdomain.com');    
    ## *** set notification pending time (in hours)
    $objCalendar->SetNotificationTime(24);    
    ## *** send emails
    $objCalendar->SendNotifications();    
   
?>