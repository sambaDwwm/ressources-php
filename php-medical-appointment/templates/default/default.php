<?php
/**
* @project ApPHP Medical Appointment
* @copyright (c) 2012 - 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

// *** Make sure the file isn't accessed directly
defined('APPHP_EXEC') or die('Restricted Access');
//--------------------------------------------------------------------------

header('content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>	
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
	<meta name="keywords" content="<?php echo Application::Get('tag_keywords'); ?>" />
	<meta name="description" content="<?php echo Application::Get('tag_description'); ?>" />
    <meta name="author" content="ApPHP Company - Advanced Power of PHP">
    <meta name="generator" content="ApPHP Medical Appointment v<?php echo CURRENT_VERSION; ?>">

    <title><?php echo Application::Get('tag_title'); ?></title>

    <base href="<?php echo APPHP_BASE; ?>" /> 
	<link rel="SHORTCUT ICON" href="<?php echo APPHP_BASE; ?>images/icons/apphp.ico" />
  
	<link href="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/css/style.css" type="text/css" rel="stylesheet" />
	<?php if(Application::Get('lang_dir') == 'rtl'){ ?>
		<link href="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/css/style-rtl.css" type="text/css" rel="stylesheet" />
	<?php } ?>
	<!--[if IE]>
	<link href="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/css/style-ie.css" type="text/css" rel="stylesheet" />
	<![endif]-->
	<link href="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/css/print.css" type="text/css" rel="stylesheet" media="print" />

	<!-- Opacity Module -->
	<link href="<?php echo APPHP_BASE; ?>modules/opacity/opacity.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo APPHP_BASE; ?>modules/opacity/opacity.js"></script>

	<script type="text/javascript" src="<?php echo APPHP_BASE; ?>js/main.js"></script>

    <?php echo Application::SetLibraries(); ?>    	
    <?php
	    $banner_image = '';
		Banners::DrawBannersTop($banner_image, false);
    ?>
</head>
<body dir="<?php echo Application::Get('lang_dir');?>">
<a name="top"></a>
<div id="wrap">
	
	<!-- HEADER -->
	<?php include_once 'templates/'.Application::Get('template').'/header.php'; ?>
	
		<!-- header-box begin -->
		<?php
			if(!$objLogin->IsLoggedIn()){
				if($banner_image != '') echo '<div id="header-wrap">'.$banner_image.'</div>';
			}else{
				echo '<div id="header-wrap-logged"></div>';
			}		
		?>
		<!-- header-box end -->	
	
	<div id="languages-wrap" class="no_print">
		<!-- languages -->
		<?php				
			$objLang = new Languages();				
			if($objLang->GetLanguagesCount('front-end') > 1){
				echo '<div class="lang_name">'._LANGUAGES.'</div>';			
				echo '<div class="flags">';
				$objLang->DrawLanguagesBar();
				echo '</div>';
			}
		?>
	</div>

	<?php
        // Draw header menu
        Menu::DrawHeaderMenu(array(
            'menu_class'=>'nav',
            'submenu_class' => 'down_inner'
        ));
	?>		  

	<div id="content-wrap">
		<div id="left-column<?php echo '-'.Application::Get('defined_left'); ?>" class="no_print">
			<!-- LEFT COLUMN -->
			<?php
				draw_block_top(_APPOINTMENTS);
				Doctors::DrawAppointmentsBlock();
				draw_block_bottom();						
			
				// Draw menu tree
				Menu::DrawMenu('left'); 
			?>                            
			<!-- END OF LEFT COLUMN -->				
		</div>

		<div id="content<?php echo '-'.Application::Get('defined_right'); ?>">
			<!-- MAIN CONTENT -->
			<?php
                if(Application::Get('page') != '' && Application::Get('page') != 'home'){
                    if(file_exists('page/'.Application::Get('page').'.php')){	 
                        include_once('page/'.Application::Get('page').'.php');
                    }else{
                        include_once('page/404.php');
                    }
				}else if(Application::Get('patient') != ''){
                    if(Modules::IsModuleInstalled('patients') && file_exists('patient/'.Application::Get('patient').'.php')){	
						include_once('patient/'.Application::Get('patient').'.php');
					}else{
						include_once('patient/404.php');
					}
				}else if(Application::Get('doctor') != ''){
                    if(file_exists('doctor/'.Application::Get('doctor').'.php')){	
						include_once('doctor/'.Application::Get('doctor').'.php');
					}else{
						include_once('doctor/404.php');
					}					
				}else if((Application::Get('admin') != '') && file_exists('admin/'.Application::Get('admin').'.php')){
					include_once('admin/'.Application::Get('admin').'.php');
				}else{
					if(Application::Get('template') == 'admin'){
						include_once('admin/home.php');
					}else{
						include_once('page/pages.php');										
					}
				}
			?>
			<!-- END OF MAIN CONTENT -->			
		</div>
		
		<!-- FOOTER -->
		<?php include_once 'templates/'.Application::Get('template').'/footer.php'; ?>
	</div>
</div>

</body>
</html>