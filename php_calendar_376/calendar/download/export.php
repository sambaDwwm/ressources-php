<?php
/**
* @project ApPHP Calendar
* @copyright (c) 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

// wee need to start session here
session_start();

// security check #2 (check token for POST requests)
if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET'){
	$token_post = isset($_GET['cal_token']) ? $_GET['cal_token'] : 'token_post';
	$cal_format = isset($_GET['cal_format']) ? $_GET['cal_format'] : ''; 
	$token_session = isset($_SESSION['cal_token']) ? $_SESSION['cal_token'] : 'token_session';
    if((!empty($token_session) && !empty($token_post) && $token_session == $token_post) || (!in_array($cal_format, array('csv', 'xml', 'ics')))){        
        $file = 'export.'.$cal_format;
        $file_path = '../tmp/export/'.$file;
        
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Pragma: no-cache'); // HTTP/1.0
        header('Content-type: application/force-download'); 
        header('Content-Disposition: inline; filename="'.$file.'"'); 
        header('Content-Transfer-Encoding: binary'); 
        header('Content-length: '.filesize($file_path)); 
        header('Content-Type: application/octet-stream'); 
        header('Content-Disposition: attachment; filename="'.$file.'"'); 
        readfile($file_path);
        exit(0);		
    }else{
        echo 'Incorrect parameters have been passed.<br />';
        echo 'Please check you have session feature is turned on.';        
    }        
}

?>