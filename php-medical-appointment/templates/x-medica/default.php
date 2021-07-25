<?php
/**
* @project ApPHP Medical Appointment
* @copyright (c) 2012 - 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license https://www.apphp.com/php-medical-appointment/index.php?page=template_license
*/

// *** Make sure the file isn't accessed directly
defined('APPHP_EXEC') or die('Restricted Access');
//--------------------------------------------------------------------------

header('content-type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html dir="<?php echo ((Application::Get('lang_dir') == 'rtl') ? 'rtl' : 'ltr'); ?>" lang="<?php echo Application::Get('lc_time_name'); ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">

	<meta name="keywords" content="<?php echo Application::Get('tag_keywords'); ?>" />
	<meta name="description" content="<?php echo Application::Get('tag_description'); ?>" />
    <meta name="author" content="ApPHP Company - Advanced Power of PHP">
    <meta name="generator" content="ApPHP Medical Appointment v<?php echo CURRENT_VERSION; ?>">

    <title><?php echo Application::Get('tag_title'); ?></title>

    <base href="<?php echo APPHP_BASE; ?>" /> 
	<link rel="SHORTCUT ICON" href="<?php echo APPHP_BASE; ?>images/icons/apphp.ico" />
    
    <!--[if lt IE 9]><script src="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/js/html5.js"></script><![endif]-->
	<link rel="stylesheet" href="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/css/style.css" type="text/css">
	<link rel="stylesheet" href="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/css/responsive.css">
    <!--[if IE]><link rel="stylesheet" href="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/css/style-ie.css" type="text/css"><![endif]-->
	<!--[if IE 7]><link rel="stylesheet" href="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/css/ie7-style.css" /><![endif]-->
    <link rel="stylesheet" id="superfish-css" href="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/css/superfish.css" type="text/css" media="all">
	<link href="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/css/print.css" type="text/css" rel="stylesheet" media="print" />
	<?php if(Application::Get('lang_dir') == 'rtl'){ ?>
        <link rel="stylesheet" href="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/css/style-rtl.css" type="text/css">
	<?php } ?>

	<!-- Opacity Module -->
	<link href="<?php echo APPHP_BASE; ?>modules/opacity/opacity.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo APPHP_BASE; ?>modules/opacity/opacity.js"></script>

	<script type="text/javascript" src="<?php echo APPHP_BASE; ?>js/main.js"></script>
    <?php echo Application::SetLibraries(); ?>    	

    <script type="text/javascript" src="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/js/superfish.js"></script>
    <script type="text/javascript" src="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/js/supersub.js"></script>
    <script type="text/javascript" src="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/js/scripts.js"></script>
    
    <?php
	    $banner_image = '';
		Banners::DrawBannersTop($banner_image, false);
    ?>
</head>
<body class="home page page-id-9 page-template-default">
<div class="body-wrapper">
    
    <?php include('topmenu.php'); ?>
    <?php include('header.php'); ?>
    
	<div class="content-wrapper container wrapper main">
        
		<!-- Navigation -->
        <?php include('navigation.php'); ?>
		
		<div class="page-container container">	
        <div class="page-wrapper">
		<div class="row">
            <div class="twelve columns mb0">        
                <div class="fullwidth">
                    <!-- header-box begin -->
                    <?php
                        if(!$objLogin->IsLoggedIn()){
                            if($banner_image != '') echo '<div id="header-wrap">'.$banner_image.'</div>';
                        }else{
                            echo '<div id="header-wrap-logged"></div>';
                        }		
                    ?>
                    <!-- header-box end -->	
                </div>
                <div class="clear"></div>
            </div>
            
            <div class="page-container container">					
            <div class="page-wrapper left-sidebar">
        	<div class="row">
                <div class="page-left mb0 twelve columns">
                <div class="row">
                    <div class="page-item mb20 nine columns">
                        <div class="row">                        
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
                            <div class="clear"></div>
                        </div>
                    </div>
                    
                    <div class="three columns left-sidebar">
                        <div class="custom-sidebar">
                            <h3 class="side_box_heading"><?php echo _APPOINTMENTS; ?></h3>
                            <?php
                                //draw_block_top(_APPOINTMENTS);
                                Doctors::DrawAppointmentsBlock();
                            ?>
                        </div>
                        <?php
                            // Draw menu tree
                            Menu::DrawMenu('left'); 
                        ?>                            
                    </div>
                    
                    <div class="clear"></div>
                </div>
                </div>
                    
                <div class="clear"></div>
            </div>
            </div> <!-- page wrapper -->			
            </div>
            
            <div class="clear"></div>
        </div>
        </div> <!-- page wrapper -->
    
        <div class="footer-top-shadow"></div>
		</div> <!-- container -->
        
        <?php include('footer.php'); ?>

	</div> <!-- content wrapper -->
</div> <!-- body wrapper -->
</body>
</html>