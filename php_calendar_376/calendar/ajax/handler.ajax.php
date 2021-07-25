<?php
/**
* @project ApPHP Calendar
* @copyright (c) 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

require_once('../inc/connection.inc.php');

$cid = isset($_POST['cid']) ? (int)$_POST['cid'] : '';
$check_key = isset($_POST['check_key']) ? $_POST['check_key'] : '';
$categories_allowed = isset($_POST['categories_allowed']) ? $_POST['categories_allowed'] : false;
$locations_allowed = isset($_POST['locations_allowed']) ? $_POST['locations_allowed'] : false;
$post_token = isset($_POST['token']) ? $_POST['token'] : '';
$session_token = isset($_SESSION['cal_token']) ? $_SESSION['cal_token'] : '';
$slot_size = isset($_POST['slot_size']) ? $_POST['slot_size'] : '';
$arr = array();
if(!defined('INSTALLATION_KEY')) define('INSTALLATION_KEY', '');

if($check_key == INSTALLATION_KEY && $cid != '' && $post_token == $session_token){
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
	header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
	header('Pragma: no-cache'); // HTTP/1.0
	header('Content-Type: application/json');
	
	$arr[] = '"status": "1"';
	$sql = 'SELECT
                e.category_id,
				e.name,
				e.url,
				e.description,
				c.unique_key,
				'.(($categories_allowed) ? 'IF(ec.name != "", ec.name, "n/d") ' : '""').' as category_name,
                '.(($locations_allowed) ? 'IF(el.name != "", el.name, "n/d") ' : '""').' as location_name
			FROM '.CALENDAR_TABLE.' c
				INNER JOIN '.EVENTS_TABLE.' e ON c.event_id = e.id
				'.(($categories_allowed) ? 'LEFT OUTER JOIN '.EVENTS_CATEGORIES_TABLE.' ec ON e.category_id = ec.id' : '').'
				'.(($locations_allowed) ? 'LEFT OUTER JOIN '.EVENTS_LOCATIONS_TABLE.' el ON e.location_id = el.id' : '').'
			WHERE c.id = '.$cid.'
			LIMIT 0, 1';
	$result = apcal_database_query($sql, CAL_DATA_AND_ROWS, CAL_FIRST_ROW_ONLY, CAL_FETCH_ASSOC);
	if($result[1] > 0){
		$arr[] = '"name": "'.encode_text($result[0]['name']).'"';
		$arr[] = '"url": "'.encode_text($result[0]['url']).'"';
        $arr[] = '"category_id": "'.encode_text($result[0]['category_id']).'"';
		$arr[] = '"category_name": "'.encode_text($result[0]['category_name']).'"';
		$arr[] = '"location_name": "'.encode_text($result[0]['location_name']).'"';
		$arr[] = '"description": "'.str_replace(array("\r\n", "\n", "\r"), '', encode_text($result[0]['description'])).'"';
		$arr[] = '"unique_key": "'.$result[0]['unique_key'].'"';
	}

	$sql = 'SELECT event_date, event_time FROM '.CALENDAR_TABLE.' WHERE unique_key = \''.$result[0]['unique_key'].'\' ORDER BY event_date ASC, event_time ASC';
	$result = apcal_database_query($sql, CAL_DATA_AND_ROWS, CAL_ALL_ROWS, CAL_FETCH_ASSOC);
	if($result[1] > 0){
		$arr[] = '"from_date": "'.$result[0][0]['event_date'].'"';
		$arr[] = '"from_time": "'.$result[0][0]['event_time'].'"';
		
		// Add one time slot to return the final date time
		$event_to_date = $result[0][$result[1]-1]['event_date'];
		$event_to_time = $result[0][$result[1]-1]['event_time'];
		$event_to_datetime = $event_to_date.' '.$event_to_time;
		if($slot_size != ''){
			$event_to_date = date('Y-m-d', strtotime($event_to_datetime)+($slot_size*60)); 	
			$event_to_time = date('H:i:s', strtotime($event_to_datetime)+($slot_size*60)); 	
		}
		
		$arr[] = '"to_date": "'.$event_to_date.'"';
		$arr[] = '"to_time": "'.$event_to_time.'"';    
	}
	
	echo '{';
	echo implode(', ', $arr);
	echo '}';
}else{
	// wrong parameters passed!
	$arr[] = '"status": "0"';
	echo '{';
	echo implode(', ', $arr);
	echo '}';
}

/**
 *	Prepare text for HTML
*/
function encode_text($string)
{
	$search  = array("\'",'\"','"',"'","\\"); //
	$replace = array('&#92;&#39;','&#92;&#34;','&#34;','&#39;','&#92;'); 
	return str_replace($search, $replace, $string);
}	

