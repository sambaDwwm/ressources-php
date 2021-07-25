<?php
/**
* @project ApPHP Medical Appointment
* @copyright (c) 2012 - 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

$host = isset($_GET['host']) ? urldecode(base64_decode($_GET['host'])) : '';
$key = isset($_GET['key']) ? base64_decode($_GET['key']) : '';

$basedir = '../../';

require_once($basedir.'include/base.inc.php');
if($key != INSTALLATION_KEY) exit(0);

require_once($basedir.'include/shared.inc.php');
require_once($basedir.'include/settings.inc.php');
require_once($basedir.'include/functions.database.'.(DB_TYPE == 'PDO' ? 'pdo.' : '').'inc.php');
require_once($basedir.'include/functions.common.inc.php');
require_once($basedir.'include/functions.html.inc.php');

// Set autoload register method
// -------------------------------------
spl_autoload_register('my_autoloader');

function my_autoloader($class_name){
    global $basedir;

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

    if(in_array($class_name, $core_classes)){
        require_once($basedir.'include/classes/core/'.$class_name.'.class.php');
	}else{
        require_once($basedir.'include/classes/'.$class_name.'.class.php');
	}
}

define('APPHP_BASE', get_base_url());
@date_default_timezone_set(TIME_ZONE);

$objSession 		= new Session();
$objLogin 			= new Login();
$objSettings 		= new Settings();
$objSiteDescription = new SiteDescription();
Modules::Init();
ModulesSettings::Init();
Application::Init();
Languages::Init();

require_once($basedir.'include/messages.'.Application::Get('lang').'.inc.php');

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8">
    <title>Appointments Form</title>
    <script type="text/javascript" src="<?php echo $host; ?>js/main.js"></script>
    <script type="text/javascript" src="<?php echo $host; ?>js/jquery-1.6.3.min.js"></script>
    <link href="<?php echo $host; ?>templates/default/css/style.css" type="text/css" rel="stylesheet" />    
</head>
<body>
    <?php
        echo '<h2>'._APPOINTMENTS.'</h2>';
        echo Doctors::DrawAppointmentsBlock(array(
            'action_url'=>$host,
            'target'=>'_parent',
            'draw'=>true
        ));
    ?>
</body>
</html>