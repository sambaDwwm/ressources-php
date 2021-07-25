<?php

$strTableName="editcalendar";
$_SESSION["OwnerID"] = $_SESSION["_".$strTableName."_OwnerID"];

$strOriginalTableName="calendar";

$gPageSize=20;
$ColumnsCount		= 1;

$gstrOrderBy="";
if(strlen($gstrOrderBy) && strcasecmp(substr($gstrOrderBy,0,8),"order by"))
	$gstrOrderBy="order by ".$gstrOrderBy;
	
$gsqlHead="select   `calendar`.`id`,   `idusercal`,  `DateField`,   `DateField` as `DateField2`,   `TimeField`,   `Theme`,   `calendar`.`Description`,   `calendar`.`Category`,   `EndTime`,   `DayEvent`,   `EndDate`,   `Period`,   `Recurrence`,  `details`,  `income`,  `outcome`,  `category`.`Color` AS `Color`   ";
$gsqlFrom="from `calendar` left join `category`  on (`calendar`.`Category`=`category`.`id`)";
$gsqlWhere="";
$gsqlTail="";
// $gstrSQL = "select   `calendar`.`id`,   `idusercal`,  `DateField`,   `DateField` as `DateField2`,   `TimeField`,   `Theme`,   `calendar`.`Description`,   `calendar`.`Category`,   `EndTime`,   `DayEvent`,   `EndDate`,   `Period`,   `Recurrence`,  `details`,  `income`,  `outcome`,  `category`.`Color` AS `Color`   from `calendar` left join `category`  on (`calendar`.`Category`=`category`.`id`)";
$gstrSQL = gSQLWhere("");

include("include/Copy_of_calendar_settings.php");
include("include/Copy_of_calendar_events.php");
?>