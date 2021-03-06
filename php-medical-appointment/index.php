<?php
################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 #
## --------------------------------------------------------------------------- #
##  ApPHP Medical Appointment version 3.0.1                                    #
##  Developed by:  ApPHP <info@apphp.com>                                      #
##  License:       GNU LGPL v.3                                                #
##  Site:          https://www.apphp.com/php-medical-appointment/              #
##  Copyright:     ApPHP Medical Appointment (c) 2012 - 2014                   #
##                 All rights reserved.                                        #
##                                                                             #
##  Additional modules (embedded):                                             #
##  -- ApPHP EasyInstaller v2.0.5 (installation module)      https://apphp.com #
##  -- ApPHP Tabs v2.0.3 (tabs menu control)        		 https://apphp.com #
##  -- TinyMCE (WYSIWYG editor)                   http://tinymce.moxiecode.com #
##  -- Crystal Project Icons (icons set)               http://www.everaldo.com #
##  -- Free Icons (icons set)                        http://www.gettyicons.com #
##  -- Securimage v2.0 BETA (captcha script)         http://www.phpcaptcha.org #
##  -- jQuery 1.4.2 (New Wave Javascript)             		 http://jquery.com #
##  -- Google AJAX Libraries API                  http://code.google.com/apis/ #
##  -- Lytebox v3.22                                       http://lytebox.com/ #
##  -- JsCalendar v1.0 (DHTML/JavaScript Calendar)      http://www.dynarch.com #
##  -- RokBox System 			  				   http://www.rockettheme.com/ #
##  -- VideoBox	  						   http://videobox-lb.sourceforge.net/ #
##  -- CrossSlide jQuery plugin v0.6.2 	                     by Tobia Conforto #
##  -- PHPMailer v5.2 https://code.google.com/a/apache-extras.org/p/phpmailer/ #
##                                                                             #
################################################################################

// *** check if database connection parameters file exists
if(!file_exists('include/base.inc.php')) {
	header('location: install.php');
	exit;
}

## uncomment, if your want to prevent "Web Page exired" message when use $submission_method = "post";
// session_cache_limiter('private, must-revalidate');    

// *** Set flag that this is a parent file
define('APPHP_EXEC', 'access allowed');

require_once('include/base.inc.php');
require_once('include/connection.php');

// *** call handler if exists
// -----------------------------------------------------------------------------
if((Application::Get('page') != '') && file_exists('page/handlers/handler_'.Application::Get('page').'.php')){
	include_once('page/handlers/handler_'.Application::Get('page').'.php');
}else if((Application::Get('patient') != '') && file_exists('patient/handlers/handler_'.Application::Get('patient').'.php')){
	if(Modules::IsModuleInstalled('patients')){	
		include_once('patient/handlers/handler_'.Application::Get('patient').'.php');
	}
}else if((Application::Get('doctor') != '') && file_exists('doctor/handlers/handler_'.Application::Get('doctor').'.php')){
	include_once('doctor/handlers/handler_'.Application::Get('doctor').'.php');	
}else if((Application::Get('admin') != '') && file_exists('admin/handlers/handler_'.Application::Get('admin').'.php')){
	include_once('admin/handlers/handler_'.Application::Get('admin').'.php');
}else if((Application::Get('admin') == 'export') && file_exists('admin/downloads/export.php')){
	include_once('admin/downloads/export.php');
}

// *** get site content
// -----------------------------------------------------------------------------
$cachefile = '';
if($objSettings->GetParameter('caching_allowed') && !$objLogin->IsLoggedIn()){
	$c_page        = Application::Get('page');
	$c_page_id     = Application::Get('page_id');
	$c_system_page = Application::Get('system_page');
	$c_album_code  = Application::Get('album_code');
	$c_news_id     = Application::Get('news_id');
	$c_patient     = Application::Get('patient');
	$c_admin       = Application::Get('admin');
	
	if(($c_page == '' && $c_patient == '' && $c_admin == '') || 
	   ($c_page == 'pages' && $c_page_id != '') || 
	   ($c_page == 'news' && $c_news_id != '') || 
	   ($c_page == 'gallery' && $c_album_code != '')
	   )
	{		
		$cachefile = md5(
			$c_page.'-'.
			$c_page_id.'-'.
			$c_system_page.'-'.
			$c_album_code.'-'.
			$c_news_id.'-'.
			Application::Get('lang')).'.cch';	
		if($c_page == 'news' && $c_news_id != ''){
			if(!News::CacheAllowed($c_news_id)) $cachefile = '';
		}else{
			$objTempPage = new Pages((($c_system_page != '') ? $c_system_page : $c_page_id));
			if(!$objTempPage->CacheAllowed()) $cachefile = '';			
		}
		if(start_caching($cachefile)) exit;
	}
}
require_once('templates/'.Application::Get('template').'/default.php');
if($objSettings->GetParameter('caching_allowed') && !$objLogin->IsLoggedIn()) finish_caching($cachefile);

Application::DrawPreview();

echo "\n".'<!-- This page was generated by ApPHP Medical Appointment v'.CURRENT_VERSION.' (https://www.apphp.com/php-medical-appointment/) -->';
