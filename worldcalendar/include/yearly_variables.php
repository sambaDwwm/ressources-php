<?php

$strTableName="yearly";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="calendar";

$gPageSize=20;
$ColumnsCount		= 1;

$gstrOrderBy="ORDER BY DateField ASC, TimeField ASC";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="select `id`,   `calendar`.`idusercal`,  `DateField`,   `Description`,   `Theme`,   `TimeField`,   `EndTime`,   `DayEvent`,   `EndDate`,   `Period`,   `Recurrence`,   `Category`,  `details`,  `income`,  `outcome`  ";
$gsqlFrom="From `calendar`";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "select `id`,   `calendar`.`idusercal`,  `DateField`,   `Description`,   `Theme`,   `TimeField`,   `EndTime`,   `DayEvent`,   `EndDate`,   `Period`,   `Recurrence`,   `Category`,  `details`,  `income`,  `outcome`  From `calendar`";
$gstrSQL = gSQLWhere("");

include("include/yearly_settings.php");
include("include/yearly_events.php");
?>