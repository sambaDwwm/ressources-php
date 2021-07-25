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

?>

<div class="footer-container container">
	<div class="footer-wrapper">        
        <div class="row">
            <div class="six columns mb0">
                <div class="footer-menu-wrapper">
                <?php 
                    // Draw footer menu
                    Menu::DrawFooterMenu();	
                ?>
                </div>
            </div>
            <div class="six columns mb0">
                <div class="social-icon-wrapper">
                <?php
                    SocialNetworks::DrawSocialIcons(array('wrapper'=>'div', 'wrapper_class'=>'social-icon'));
                ?>
                </div>                
            </div>                
        </div>
        <div class="clear"></div>
    </div>

		
    <div class="copyright-wrapper">
        <div class="copyright-left">
			<?php
                $footer_text = $objSiteDescription->DrawFooter(false);
                echo '<span>'.$footer_text.'</span>';
            ?>
			<?php if(!empty($footer_text)) echo "&nbsp;".draw_divider(false)."&nbsp;"; ?>
            <?php
                if($objSettings->GetParameter('rss_feed')){
                    echo '<a href="feeds/rss.xml" title="RSS Feed"><img src="images/rss.png" alt="RSS Feed" border="0" /></a>&nbsp;';
                }
            ?>        
        </div> 
        <div class="copyright-right">
			<form name="frmLogout" id="frmLogout" action="index.php" method="post">
            <?php if($objLogin->IsLoggedIn()){ ?>
				<?php draw_hidden_field('submit_logout', 'logout'); ?>	
                <a class="main_link" href="javascript:appFormSubmit('frmLogout');"><?php echo _BUTTON_LOGOUT; ?></a>
            <?php }else{ ?>
				<?php
					if(Modules::IsModuleInstalled('doctors')){			
						if(ModulesSettings::Get('doctors', 'allow_login') == 'yes'){
							echo '<a class="main_link" href="index.php?doctor=login">'._DOCTOR_LOGIN.'</a>';
							echo ' &nbsp;'.draw_divider(false).'&nbsp; ';
						}
					}
				?>                
                <a class="main_link" href="index.php?admin=login"><?php echo _ADMIN_LOGIN; ?></a>
            <?php } ?>
            </form>
        </div> 
        <div class="clear"></div>
    </div>				
</div>
