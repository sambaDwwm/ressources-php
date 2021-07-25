<?php
/**
* @project ApPHP Medical Appointment
* @copyright (c) 2012 - 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

@session_start();	

//------------------------------------------------------------------------------
require_once('shared.inc.php');
require_once('settings.inc.php');
require_once('functions.common.inc.php');
require_once('functions.html.inc.php');
require_once('functions.validation.inc.php');
require_once('functions.database.'.(DB_TYPE == 'PDO' ? 'pdo.' : 'mysqli.').'inc.php');
define('APPHP_BASE', get_base_url());

// Set autoload register method
// -------------------------------------
spl_autoload_register('my_autoloader');

function my_autoloader($class_name){
    $core_classes = array(
        /* core classes ALL - no differences */
        'Backup',
        'BanList',
        'Banners',
        'Email',
        'GalleryAlbums',
        'GalleryAlbumItems',
        'MicroGrid',
        'Modules',
        'ModulesSettings',
        'Roles',
        'RolePrivileges',
        'Session',
        'Settings',
        'SocialNetworks',
        'States',
        /* core classes ALL - have differences */
        'Cron',
        /* core classes excepting MicroBlog - no differences */
        'Accounts',
        'Admins',
        'ContactUs',
        'FaqCategories',
        'FaqCategoryItems',
        'News',
        'NewsSubscribed',
        'PagesGrid',
        'RSSFeed',
        'SiteDescription',
        'Vocabulary',
        /* core classes excepting MicroBlog - have differences */
        'AdminsAccounts',
        'Application',
        'Comments',
        'EmailTemplates',
        'Languages',
        'Pages',
        /* core classes excepting MicroBlog, MicroCMS - have differences */
        'Currencies',
    );
	
    if($class_name == 'PHPMailer'){
		require_once('modules/phpmailer/class.phpmailer.php');
    }else if(in_array($class_name, $core_classes)){
        require_once('classes/core/'.$class_name.'.class.php');	
	}else{
		require_once('classes/'.$class_name.'.class.php');	
	}	
}

if(defined('APPHP_CONNECT') && APPHP_CONNECT == 'direct'){	
	// Set time zone
	//------------------------------------------------------------------------------
	@date_default_timezone_set(TIME_ZONE);
	
	Modules::Init();
	ModulesSettings::Init();

	$objSession  = new Session();
	$objLogin    = new Login();
	$objSettings = new Settings();

    // use messages file according to preferences
    $lang_file = $objLogin->GetPreferredLang();
    include_once('messages'.($lang_file != '' ? '.'.$lang_file : '').'.inc.php');
    
}else{	
	// set timezone
	//------------------------------------------------------------------------------
	Settings::SetTimeZone();
	
	Modules::Init();
	ModulesSettings::Init();

	// create main objects
	//------------------------------------------------------------------------------
	$objSession 		= new Session();
	$objLogin 			= new Login();
	$objSettings 		= new Settings();
	$objSiteDescription = new SiteDescription();
	Application::Init();
	Languages::Init();
	
	// force SSL mode if defined
	//------------------------------------------------------------------------------
	$ssl_mode = $objSettings->GetParameter('ssl_mode');
	$ssl_enabled = false; 
	if($ssl_mode == '1'){
		$ssl_enabled = true; 
	}else if($ssl_mode == '2' && $objLogin->IsLoggedInAsAdmin()){
		$ssl_enabled = true; 
	}else if($ssl_mode == '3' && ($objLogin->IsLoggedInAsPatient() || $objLogin->IsLoggedInAsDoctor())){
		$ssl_enabled = true; 
	}
	if($ssl_enabled && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') && isset($_SERVER['HTTP_HOST'])){ 
		redirect_to('https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); 
	}
	
	// include files for administrator use only
	//------------------------------------------------------------------------------
	if($objLogin->IsLoggedInAsAdmin()){
		include_once('functions.admin.inc.php');
	}
	
	// include language file
	//------------------------------------------------------------------------------
	if(!defined('APPHP_LANG_INCLUDED')){
		if(get_os_name() == 'windows'){
			$lang_file_path = str_replace('index.php', '', $_SERVER['SCRIPT_FILENAME']).'include/messages.'.Application::Get('lang').'.inc.php';
		}else{
			$lang_file_path = 'include/messages.'.Application::Get('lang').'.inc.php';
		}
		if(file_exists($lang_file_path)){
			include_once($lang_file_path);
		}else if(file_exists('include/messages.inc.php')){
			include_once('include/messages.inc.php');
		}		
	}
}
