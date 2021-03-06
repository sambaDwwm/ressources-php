<?php
/**
* @project ApPHP Calendar
* @copyright (c) 2014 ApPHP
* @author ApPHP <info@apphp.com>
* @license http://www.gnu.org/licenses/
*/

// PDO DATABASE FUNCTIONS 09.02.2020

// setup connection
//------------------------------------------------------------------------------
try{
	$dbh = new PDO('mysqli:host='.DATABASE_HOST.';dbname='.DATABASE_NAME,
		DATABASE_USERNAME,
		DATABASE_PASSWORD,
		array(PDO::MYSQLI_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\'')
	);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT); 				

}catch(Exception $e){    
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	header('Status: 503 Service Temporarily Unavailable');
	$output = apcal_fatal_error_page_content();
	if(DB_CONNECTION_MODE == 'debug'){
		$output = str_ireplace('{DESCRIPTION}', '<p>This application is currently experiencing some database difficulties</p>', $output);
		$output = str_ireplace(
			'{CODE}',
			'<b>Description:</b> '.$e->getMessage().'<br>
			<b>File:</b> '.$e->getFile().'<br>
			<b>Line:</b> '.$e->getLine(),
			$output
		);
	}else{
		$output = str_ireplace('{DESCRIPTION}', '<p>This application is currently experiencing some database difficulties. Please check back again later</p>', $output);
		$output = str_ireplace('{CODE}', 'For more information turn on debug mode in your application', $output);
	}
	echo $output;
	exit(1);
}

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
function apcal_database_query($sql, $return_type = CAL_DATA_ONLY, $first_row_only = CAL_ALL_ROWS, $fetch_func = PDO::FETCH_ASSOC)
{
	global $dbh;
    
	$data_array = array();
	$num_rows = 0;
	$fields_len = 0;
	if($fetch_func == 'mysqli_fetch_assoc') $fetch_func = PDO::FETCH_ASSOC;
	else if($fetch_func == 'mysqli_fetch_array') $fetch_func = PDO::FETCH_BOTH;

	$sth = $dbh->query($sql);
	//if($debug == true){
	//	$err = $dbh->errorInfo();
	//	echo $sql.'-'.(isset($err[2]) ? $err[2] : '');
	//}
	if($sth){
		if($return_type == 0 || $return_type == 2){
			while($row_array = $sth->fetch($fetch_func)){
				if(!$first_row_only){
					array_push($data_array, $row_array);
				}else{
					$data_array = $row_array;
					break;
				}
			}
		}		
		
		$num_rows = $sth->rowCount(); 
		$fields_len = $sth->columnCount(); 
	}

	$sth = null;

	switch($return_type){
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
	global $dbh;
		
    $zero_affected = true;    
	$result = $dbh->exec($sql);
	//if($debug == true){
	//	$err = $dbh->errorInfo();
	//	echo $sql.'-'.(isset($err[2]) ? $err[2] : '');
	//}
	
	$affected_rows = $result;
	if(preg_match('/update /i', $sql)){
		if($zero_affected && $affected_rows >= 0) return true;
		if(!$zero_affected && $affected_rows > 0) return true;
	}else if(preg_match('/drop t/i', $sql)){
		if($affected_rows >= 0) return true;
	}else if(preg_match('/create t/i', $sql)){
		if($affected_rows >= 0) return true;
	}else if($affected_rows > 0){ 
		return true;
	}
	return false;
}

/**
 * Set group_concat maximal length
 */
function apcal_set_group_concat_max_length()
{
	apcal_database_void_query('SET SESSION group_concat_max_len = 1024');	
}

/**
 * Disable ONLY_FULL_GROUP_BY
 */
function apcal_set_only_full_group_by()
{
	apcal_database_void_query('SET sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY", ""));');
}


/**
 * Set sql_mode
 */
function apcal_set_sql_mode()
{
	apcal_database_void_query('SET sql_mode = ""');
}

/**
 * Returns fata error page content
 * @return html code
 */    
function apcal_fatal_error_page_content()
{
	return '<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Database Fatal Error</title>
	<style type="text/css">
		html{background:#f9f9f9}
		body{background:#fff; color:#333; font-family:sans-serif; margin:2em auto; padding:1em 2em 2em; -webkit-border-radius:3px; border-radius:3px; border:1px solid #dfdfdf; max-width:750px; text-align:left;}
		#error-page{margin-top:50px}
		#error-page h2{border-bottom:1px dotted #ccc;}
		#error-page p{font-size:16px; line-height:1.5; margin:2px 0 15px}
		#error-page .code-wrapper{color:#400; background-color:#f1f2f3; padding:5px; border:1px dashed #ddd}
		#error-page code{font-size:15px; font-family:Consolas,Monaco,monospace;}
		a{color:#21759B; text-decoration:none}
		a:hover{color:#D54E21}
		#footer{font-size:14px; margin-top:50px; color:#555;}
	</style>
	</head>
	<body id="error-page">
		<h2>Database connection error!</h2>
		{DESCRIPTION}
		<div class="code-wrapper">
		<code>{CODE}</code>
		</div>
		<div id="footer">
			If you\'re unsure what this error means you should probably contact your host.
			If you still need a help, you can alway visit <a href="http://apphp.net/forum" target="_blank" rel="noopener noreferrer">ApPHP Support Forums</a>.
		</div>
	</body>
	</html>';        
}

/**
 * Return database error
 */
function apcal_database_error()
{
	global $dbh;
		
	$err = $dbh->errorInfo();
	return (isset($err[2]) ? $err[2] : '');
}

/**
 * Return database last inset ID
 */
function apcal_database_insert_id()
{	
	global $dbh;
	
	return $dbh->lastInsertId();
}

/**
 * Return affected rows
 */
function apcal_affected_rows()
{
	return mysqli_affected_rows();
}

