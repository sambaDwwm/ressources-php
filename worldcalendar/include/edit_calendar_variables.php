<?php

$strTableName="edit calendar";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="calendar";

$gPageSize=20;
$ColumnsCount		= 1;

$gstrOrderBy="ORDER BY DateField ASC, TimeField ASC";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="select `id`,   `idusercal`,  `DateField`,  `TimeField`,   `Theme`,   `Description`,   `Category`,   `EndTime`,   `DayEvent`,   `EndDate`,   `Period`,   `Recurrence`,  `details`,  `income`,  `outcome`  ";
$gsqlFrom="from `calendar`";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "select `id`,   `idusercal`,  `DateField`,  `TimeField`,   `Theme`,   `Description`,   `Category`,   `EndTime`,   `DayEvent`,   `EndDate`,   `Period`,   `Recurrence`,  `details`,  `income`,  `outcome`  from `calendar`";
$gstrSQL = gSQLWhere("");

include("include/edit_calendar_settings.php");
include("include/edit_calendar_events.php");
?>