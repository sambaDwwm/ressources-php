<?php
/**
* @project ApPHP Medical Appointment
* @copyright (c) 2012 - 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

define('PROJECT_NAME', 'MedicalAppointment'); /* don't change it! */

define('IMAGE_DIRECTORY', 'images/');
define('CACHE_DIRECTORY', 'tmp/cache/');     

define('SITE_MODE', 'production');     // demo|development|production
define('DEFAULT_TEMPLATE', 'default'); // default
define('DEFAULT_DIRECTION', 'ltr');    // ltr|rtl

define('PATIENTS_ENCRYPTION', false);   // true|false - defines whether encrypt patients info or not 

// (list of supported Timezones - http://us3.php.net/manual/en/timezones.php)    
define('TIME_ZONE', 'America/Los_Angeles'); 

define('CURRENT_VERSION', '3.1.x');

define('DB_TYPE', 'MySQLi'); /* possible values: PDO, MySQLi */

// return types for database_query function
// --------------------------------------------------------------
define('ALL_ROWS', 0);
define('FIRST_ROW_ONLY', 1);
define('DATA_ONLY', 0);
define('ROWS_ONLY', 1);
define('DATA_AND_ROWS', 2);
define('FIELDS_ONLY', 3);
define('FETCH_ASSOC', 'mysqli_fetch_assoc');
define('FETCH_ARRAY', 'mysqli_fetch_array');

// definition of tables constants
// --------------------------------------------------------------
define('TABLE_ACCOUNTS', DB_PREFIX.'accounts');
define('TABLE_APPOINTMENTS', DB_PREFIX.'appointments');      
define('TABLE_BANLIST', DB_PREFIX.'banlist');      
define('TABLE_BANNERS', DB_PREFIX.'banners');      
define('TABLE_BANNERS_DESCRIPTION', DB_PREFIX.'banners_description');      
define('TABLE_CLINIC', DB_PREFIX.'clinic');      
define('TABLE_CLINIC_DESCRIPTION', DB_PREFIX.'clinic_description');
define('TABLE_COMMENTS', DB_PREFIX.'comments');      
define('TABLE_COUNTRIES', DB_PREFIX.'countries');
define('TABLE_CURRENCIES', DB_PREFIX.'currencies');
define('TABLE_DOCTORS', DB_PREFIX.'doctors');
define('TABLE_DOCTOR_ADDRESSES', DB_PREFIX.'doctor_addresses');
define('TABLE_DOCTOR_IMAGES', DB_PREFIX.'doctor_images');
define('TABLE_DOCTOR_MEMBERSHIP_PLANS', DB_PREFIX.'doctor_membership_plans');
define('TABLE_DOCTOR_SPECIALITIES', DB_PREFIX.'doctor_specialities');
define('TABLE_EMAIL_TEMPLATES', DB_PREFIX.'email_templates');      
define('TABLE_EVENTS_REGISTERED', DB_PREFIX.'events_registered');
define('TABLE_FAQ_CATEGORIES', DB_PREFIX.'faq_categories');
define('TABLE_FAQ_CATEGORY_ITEMS', DB_PREFIX.'faq_category_items');      
define('TABLE_GALLERY_ALBUMS', DB_PREFIX.'gallery_albums');      
define('TABLE_GALLERY_ALBUMS_DESCRIPTION', DB_PREFIX.'gallery_albums_description');      
define('TABLE_GALLERY_ALBUM_ITEMS', DB_PREFIX.'gallery_album_items');      
define('TABLE_GALLERY_ALBUM_ITEMS_DESCRIPTION', DB_PREFIX.'gallery_album_items_description');      
define('TABLE_INSURANCES', DB_PREFIX.'insurances');
define('TABLE_INSURANCES_DESCRIPTION', DB_PREFIX.'insurances_description');      
define('TABLE_LANGUAGES', DB_PREFIX.'languages');
define('TABLE_MEMBERSHIP_PLANS', DB_PREFIX.'membership_plans');
define('TABLE_MEMBERSHIP_PLANS_DESCRIPTION', DB_PREFIX.'membership_plans_description');
define('TABLE_MENUS', DB_PREFIX.'menus');      
define('TABLE_MODULES', DB_PREFIX.'modules');      
define('TABLE_MODULES_SETTINGS', DB_PREFIX.'modules_settings');      
define('TABLE_NEWS', DB_PREFIX.'news');
define('TABLE_NEWS_SUBSCRIBED', DB_PREFIX.'news_subscribed');
define('TABLE_ORDERS', DB_PREFIX.'orders');
define('TABLE_PAGES', DB_PREFIX.'pages');
define('TABLE_PATIENTS', DB_PREFIX.'patients');      
define('TABLE_PATIENT_GROUPS', DB_PREFIX.'patient_groups');      
define('TABLE_PRIVILEGES', DB_PREFIX.'privileges');
define('TABLE_ROLES', DB_PREFIX.'roles');
define('TABLE_ROLE_PRIVILEGES', DB_PREFIX.'role_privileges');
define('TABLE_SCHEDULES', DB_PREFIX.'schedules');
define('TABLE_SCHEDULE_TIMEBLOCKS', DB_PREFIX.'schedule_timeblocks');      
define('TABLE_SEARCH_WORDLIST', DB_PREFIX.'search_wordlist');      
define('TABLE_SETTINGS', DB_PREFIX.'settings');      
define('TABLE_SITE_DESCRIPTION', DB_PREFIX.'site_description');
define('TABLE_SOCIAL_NETWORKS', DB_PREFIX.'social_networks');
define('TABLE_SPECIALITIES', DB_PREFIX.'specialities');
define('TABLE_SPECIALITIES_DESCRIPTION', DB_PREFIX.'specialities_description');
define('TABLE_STATES', DB_PREFIX.'states');
define('TABLE_TIMEOFFS', DB_PREFIX.'timeoffs');
define('TABLE_VISIT_REASONS', DB_PREFIX.'visit_reasons');
define('TABLE_VISIT_REASONS_DESCRIPTION', DB_PREFIX.'visit_reasons_description');
define('TABLE_VOCABULARY', DB_PREFIX.'vocabulary');      

// set errors handling
// --------------------------------------------------------------
if(SITE_MODE == 'development'){
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');    
}else{
	error_reporting(E_ALL);
	ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
}

