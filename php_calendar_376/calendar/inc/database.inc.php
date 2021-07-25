<?php
/**
* @project ApPHP Calendar
* @copyright (c) 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

// Setup connection
$database_connection = @mysqli_connect(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
if(!$database_connection){
	$msg = 'DB connection error!';	
	if(DB_CONNECTION_MODE == 'debug'){
		$msg .= ' Please configure your settings in inc/config.inc.php file.';
		$msg .= '<br>Error description: '.mysqli_connect_error();
	}else{
		$msg .= ' Please configure your settings.';
		$msg .= '<br>To get more info about this problem, switch DB_CONNECTION_MODE to \'debug\' in config.inc.php';		
	}
	echo $msg;
	die();
}

// set collation
apcal_set_collation();
// set group_concat max length
apcal_set_group_concat_max_length();
// Disable ONLY_FULL_GROUP_BY
//apcal_set_only_full_group_by();

/**
 * Database query
 * @param $sql
 * @param $return_type
 * @param $first_row_only
 * @param $fetch_func
 */
function apcal_database_query($sql, $return_type = CAL_DATA_ONLY, $first_row_only = CAL_ALL_ROWS, $fetch_func = CAL_FETCH_ASSOC)
{	
    global $database_connection;
    
    $data_array = array();
	$num_rows = 0;
	$fields_len = 0;
	
	$result = mysqli_query($database_connection, $sql) or die($sql . '|' . mysqli_error($database_connection));
	if ($return_type == 0 || $return_type == 2) {
		while ($row_array = $fetch_func($result)) {
			if (!$first_row_only) {
				array_push($data_array, $row_array);
			} else {
				$data_array = $row_array;
				break;
			}
		}
	}
	$num_rows = mysqli_num_rows($result);
	$fields_len = mysqli_num_fields($result);
	mysqli_free_result($result);
		
	switch ($return_type) {
		case CAL_DATA_ONLY:
			return $data_array;
		case CAL_ROWS_ONLY:
			return $num_rows;
		case CAL_DATA_AND_ROWS:
			return array($data_array, $num_rows);
		case CAL_FIELDS_ONLY:
			return $fields_len;
	}	
}

/**
 * Database void query
 * @param $sql
 * @param &$affected_rows
 */
function apcal_database_void_query($sql, &$affected_rows = null)
{
    global $database_connection;
    
	if($database_connection){
		$result = mysqli_query($database_connection, $sql);	
		$affected_rows = mysqli_affected_rows($database_connection);
		if(preg_match('/\bupdate\b/i', $sql)){
			if($affected_rows >= 0) return true;
		}else if(preg_match('/\binsert\b/i', $sql)){
			if($affected_rows >= 0) return mysqli_insert_id($database_connection);
		}else if($affected_rows > 0){ 
			return true;
		}
	}
	
	return false;
}

/**
 * Set collation
 **/
function apcal_set_collation()
{
	$encoding = 'utf8';
	$collation = 'utf8_unicode_ci';
	
	$sql_variables = array(
		'character_set_client'  =>$encoding,
		'character_set_server'  =>$encoding,
		'character_set_results' =>$encoding,
		'character_set_database'=>$encoding,
		'character_set_connection'=>$encoding,
		'collation_server'      =>$collation,
		'collation_database'    =>$collation,
		'collation_connection'  =>$collation
	);

	foreach($sql_variables as $var => $value){
		$sql = 'SET '.$var.'='.$value.';';
		apcal_database_void_query($sql);
	}        
}

/**
 * Set group_concat maximal length
 **/
function apcal_set_group_concat_max_length()
{
	apcal_database_void_query('SET SESSION group_concat_max_len = 6036');	
}

/**
 * Return database error
 */
function apcal_database_error()
{
	return mysqli_error($database_connection);
}

/**
 * Disable ONLY_FULL_GROUP_BY
 */
function apcal_set_only_full_group_by()
{
	apcal_database_void_query('SET sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY", ""));');
}
