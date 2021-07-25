<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP Calendar Pro                                                 		   #
##  Developed by:  ApPhp <info@apphp.com>                                      #
##  License:       GNU LGPL v.3                                                #
##  Site:          https://www.apphp.com/php-calendar/                         #
##  Copyright:     ApPHP Calendar (c) 2009-2012. All rights reserved.          #
##                                                                             #
################################################################################

    require_once('install/settings.inc.php');    

    if(file_exists('../calendar/inc/'.EI_CONFIG_FILE_NAME)){
		echo '<!doctype html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
            <meta name="author" content="ApPHP Company">
            <meta name="generator" content="ApPHP Calendar">
            <title>Index | ApPHP Calendar Examples</title>
            <style>span { color:#555; }</style>
        </head>
        <body>       
        <p style="padding:10px;">		
			Click below to view ApPHP Calendar examples:<br /><br />
			1. Example #1 - <a href="example_full_access.php">Standard Calendar</a> <span>(full access - commonly used for Back-End)</span><br />
			2. Example #2 - <a href="example_limited_access.php">Standard Calendar</a> <span>(limited access - commonly used for Front-End)</span><br />
            3. Example #3 - <a href="example_fully_featured.php">Fully Featured Calendar</a> <span>(full access with Categories and Locations)</span><br />
			4. Example #4 - <a href="example_small.php">Small & Standard Calendars</a> <span>(limited access)</span><br />
			5. Example #5 - <a href="cron.php">Run Cron Job file</a> <span>(in debug mode - for MySQL v5.0 or later)</span><br />
			6. Code Example - <a href="../calendar/code_template.php">Code Template</a><br />
		</p>
        </body>
        </html>';
	}else{
		header('location: install/index.php');		
	}
    exit;
