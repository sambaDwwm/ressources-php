<?php

// *** Make sure the file isn't accessed directly
defined('APPHP_EXEC') or die('Restricted Access');
//--------------------------------------------------------------------------

?>
<div id="footer" class="no_print">
	<div id="footer_wrapper">
        <div class="fleft">
            <?php 
                // Draw footer menu
                Menu::DrawFooterMenu();	
            ?>		  		
        </div>		
        <div class="fright">
			<?php echo $footer_text = $objSiteDescription->DrawFooter(false); ?>
			<?php if(!empty($footer_text)) echo "&nbsp;".draw_divider(false)."&nbsp;"; ?>
            <?php
                if($objSettings->GetParameter('rss_feed')){
                    echo '<a href="feeds/rss.xml" title="RSS Feed"><img src="images/rss.png" alt="RSS Feed" border="0" /></a>&nbsp;';
                }
            ?>
           
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
        <br />
        <div class="social-icon-wrapper">
        <?php
            SocialNetworks::DrawSocialIcons();
        ?>
        </div>                                 
		<br /><br /><br />
	</div>
	
</div>			
