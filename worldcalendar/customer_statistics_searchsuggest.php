<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
header("Expires: Thu, 01 Jan 1970 00:00:01 GMT"); 
set_magic_quotes_runtime(0);

include("include/dbcommon.php");
include("include/customer_statistics_variables.php");

if(!@$_SESSION["UserID"])
{ 
	return;
}
if(!CheckSecurity(@$_SESSION["_".$strTableName."_OwnerID"],"Search"))
{
	return;
}

$conn=db_connect();	

$response = array();

if (isset($_GET['searchFor']) && postvalue('searchFor') != '') {

	$searchFor = postvalue('searchFor');
	$searchField = GoodFieldName( postvalue('searchField') );
	
	if ( $searchField == '' || $searchField=="TimeField")
	{
		$field="TimeField";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($whereCondition,SecuritySQL("Search"));
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	if ( $searchField == '' || $searchField=="Theme")
	{
		$field="Theme";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($whereCondition,SecuritySQL("Search"));
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	if ( $searchField == '' || $searchField=="Description")
	{
		$field="Description";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($whereCondition,SecuritySQL("Search"));
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	if ( $searchField == '' || $searchField=="Category")
	{
		$field="Category";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($whereCondition,SecuritySQL("Search"));
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	if ( $searchField == '' || $searchField=="EndTime")
	{
		$field="EndTime";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($whereCondition,SecuritySQL("Search"));
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	if ( $searchField == '' || $searchField=="DayEvent")
	{
		$field="DayEvent";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($whereCondition,SecuritySQL("Search"));
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	if ( $searchField == '' || $searchField=="Period")
	{
		$field="Period";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($whereCondition,SecuritySQL("Search"));
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	if ( $searchField == '' || $searchField=="Recurrence")
	{
		$field="Recurrence";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($whereCondition,SecuritySQL("Search"));
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	if ( $searchField == '' || $searchField=="details")
	{
		$field="details";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($whereCondition,SecuritySQL("Search"));
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	if ( $searchField == '' || $searchField=="income")
	{
		$field="income";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($whereCondition,SecuritySQL("Search"));
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	if ( $searchField == '' || $searchField=="outcome")
	{
		$field="outcome";
		if(CheckFieldPermissions($field))
		{
		$whereCondition = ($suggestAllContent) ? " like '%".str_replace("'","''",$searchFor)."%'" : " like '".str_replace("'","''",$searchFor)."%'";
		$whereCondition = " ".GetFullFieldName($field).$whereCondition;
		$whereCondition = whereAdd($whereCondition,SecuritySQL("Search"));
		$whereCondition = whereAdd($gsqlWhere,$whereCondition);
		$strSQL = "SELECT DISTINCT ".GetFullFieldName($field)." ".$gsqlFrom." WHERE ".$whereCondition.$gsqlTail." ORDER BY 1 LIMIT 10";
		$rs=db_query($strSQL,$conn);

			while ($row = db_fetch_numarray($rs)) {
				$pos = strpos($row[0],"\n");
				if ($pos!==FALSE) {
					$response[] = substr($row[0],0,$pos);
				} else {
					$response[] = $row[0];
				}
			}
		}
		}
	db_close($conn);
}

sort($response);

if ($output = array_chunk(array_unique($response),10)) {
	foreach( $output[0] as $value ) {
		echo ($suggestAllContent) ? str_replace($searchFor,"<b>".$searchFor."</b>",substr($value,0,50))."\n" : substr($value,0,50)."\n";
	}
}
?>