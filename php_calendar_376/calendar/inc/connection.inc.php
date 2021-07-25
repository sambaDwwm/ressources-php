<?php
/**
* @project ApPHP Calendar
* @copyright (c) 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

@session_start();

//------------------------------------------------------------------------------
require_once('config.inc.php');
require_once('shared.inc.php');
require_once('settings.inc.php');
require_once('database.'.(DB_TYPE == 'PDO' ? 'pdo.' : '').'inc.php');
