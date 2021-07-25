<?php

$strTableName="globals";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="globals";

$gPageSize=20;
$ColumnsCount		= 1;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="select `id`,   `TimePeriod`  ";
$gsqlFrom="From `globals`";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "select `id`,   `TimePeriod`  From `globals`";
$gstrSQL = gSQLWhere("");

include("include/globals_settings.php");
include("include/globals_events.php");
?>