<?php

$strTableName="category";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="category";

$gPageSize=20;
$ColumnsCount		= 1;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="select `id`,   `idusercat`,   `Category`,   `price`,   `taskdetails`,   `picture`,   `Color`  ";
$gsqlFrom="From `category`";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "select `id`,   `idusercat`,   `Category`,   `price`,   `taskdetails`,   `picture`,   `Color`  From `category`";
$gstrSQL = gSQLWhere("");

include("include/category_settings.php");
include("include/category_events.php");
?>