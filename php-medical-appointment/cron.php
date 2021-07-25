<?php 
/**
* @project ApPHP Medical Appointment
* @copyright (c) 2012 - 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

require_once('include/base.inc.php');
require_once('include/connection.php');

if(!$objLogin->IsLoggedIn()){

    ////////////////////////////////////////////////////////////////////////////
    // 1. Cron - check if there is some work for cron
    ////////////////////////////////////////////////////////////////////////////
    Cron::Run();
    
}    
    
