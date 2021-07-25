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
<div class="top-navigation-wrapper">
    <div class="top-navigation wrapper container">
        <div class="top-navigation-<?php echo ((Application::Get('lang_dir') == 'rtl') ? 'right' : 'left'); ?>">
            <div class="menu-top-menu-container">
                <ul id="menu-top-menu" class="menu">
                    <li id="menu-item" class="menu-item"><a href="index.php"><?php echo _HOME;?></a></li>
                    <li id="menu-item" class="menu-item"><a href="<?php echo prepare_link('pages', 'system_page', 'about_us', 'index', 'about_us', '', '', true); ?>"><?php echo _ABOUT;?></a></li>
                    <li id="menu-item" class="menu-item"><a href="<?php echo prepare_link('pages', 'system_page', 'contact_us', 'index', 'contact_us', '', '', true); ?>"><?php echo _CONTACT;?></a></li>
                </ul>
            </div>
        </div>				
        <div class="top-navigation-<?php echo ((Application::Get('lang_dir') == 'rtl') ? 'left' : 'right'); ?>">
            <div id="languages-wrap" class="no_print">                
                <?php				
                    $objLang = new Languages();				
                    if($objLang->GetLanguagesCount('front-end') > 1){
                        echo '<div class="flags">';
                        $objLang->DrawLanguagesBar();
                        echo '</div>';
                        echo '<div class="lang_name">'._LANGUAGES.'</div>';			
                    }
                ?>
            </div>
        </div> 
        <div class="clear"></div>
    </div> 
</div> 

