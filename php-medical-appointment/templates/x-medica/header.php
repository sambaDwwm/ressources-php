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

$clinic_info = Clinic::GetClinicInfo();

?>

<div class="header-wrapper">
    <div class="header-container container">			
        <div class="logo-wrapper">
            <img src="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/images/logo.png" alt="logo">    
            <a href="<?php echo APPHP_BASE; ?>index.php"><?php echo ($objLogin->IsLoggedInAsAdmin()) ? _ADMIN_PANEL : $objSiteDescription->DrawHeader('header_text'); ?></a>
            <br>
            <span class="slogan">
            <?php
                if($objLogin->IsLoggedInAsAdmin() && Application::Get('preview') == 'yes'){
                    echo prepare_permanent_link('index.php?preview=no', _BACK_TO_ADMIN_PANEL, '', 'header');
                }else{
                    echo $objSiteDescription->GetParameter('slogan_text');				
                }
            ?>
            </span>
        </div>
        <div class="logo-right-text">
            <img src="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/images/phone.png" alt="icon">
            <span><?php echo (isset($clinic_info['phone']) ? $clinic_info['phone'] : ''); ?></span>
            <div class="clear" style="height:3px;"></div>
            <img src="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/images/fax.png" alt="icon">
            <span><?php echo (isset($clinic_info['fax']) ? $clinic_info['fax'] : ''); ?></span>
            <div class="clear" style="height:3px;"></div>            
            <img src="<?php echo APPHP_BASE; ?>templates/<?php echo Application::Get('template');?>/images/email.png" alt="icon">
            <span><?php echo $objSettings->GetParameter('admin_email'); ?></span>
        </div>
        <div class="clear"></div>
    </div>
</div>

