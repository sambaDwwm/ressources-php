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

if(Modules::IsModuleInstalled('appointments') && ModulesSettings::Get('appointments', 'is_active') == 'yes'){
    if(!$objLogin->IsLoggedInAs('owner','mainadmin')){	
        redirect_to('index.php?page=find_doctors');
    }
}

?>