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

<div class="navigation-gimmick"></div>
<div class="main-navigation-wrapper">
    <div class="responsive-menu-wrapper">
        <?php
            // Draw header menu (responsive)
            Menu::DrawHeaderMenu(array(
                'menu_id'=>'menu-main',
                'menu_class'=>'menu dropdown-menu',
                'view_type'=>'dropdownlist'
            ));
        ?>		  
    </div>

    <div class="navigation-wrapper">                
        <div id="main-superfish-wrapper" class="menu-wrapper">
            <?php
                // Draw header menu
                Menu::DrawHeaderMenu(array(
                    'menu_class'=>'sf-menu sf-js-enabled sf-shadow',
                    'submenu_class' => 'sub-menu'
                ));
            ?>		  
        </div>                
        <div class="clear"></div>                
        <div class="navigation-bottom-shadow"></div>            
    </div>
    
    <!-- HERE -->
    
    <!-- search form -->
    <div class="top-search-form">
        <?php
            Search::DrawQuickSearch();
        ?>
        
    </div>		
    <div class="clear"></div>
</div>	
